<?php

namespace App\Http\Controllers\Admin;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class NutritionAIController extends Controller
{
    public function form()
    {
        return view('product-index');
    }

    public function nutritionCalculation(Request $request)
    {
        // Validate input
        $request->validate([
            'title'           => 'required|string',
            'qty'             => 'required|numeric|min:1',
            'measurement'     => 'required|string',
            'carbs'           => 'nullable|numeric|min:0',
            'protein'         => 'nullable|numeric|min:0',
            'fat'             => 'nullable|numeric|min:0'
        ]);

        // Extract inputs
        $title = $request->input('title');
        $qty = $request->input('qty');
        $measurement = strtolower($request->input('measurement'));  // Convert measurement to lowercase
        $carbs = $request->input('carbs');
        $protein = $request->input('protein');
        $fat = $request->input('fat');

        // Convert measurement to grams/ml
        $converted_qty = $this->convertToGrams($title, $qty, $measurement);

        // Fetch missing values from OpenAI if any value is null or 0.00
        if ($carbs === null || $carbs == 0.00 || $protein === null || $protein == 0.00 || $fat === null || $fat == 0.00) {
            $openAIResponse = $this->fetchFromOpenAI($title, $converted_qty);

            $carbs = ($carbs === null || $carbs == 0.00) ? ($openAIResponse['carbs'] ?? 0.00) : $carbs;
            $protein = ($protein === null || $protein == 0.00) ? ($openAIResponse['protein'] ?? 0.00) : $protein;
            $fat = ($fat === null || $fat == 0.00) ? ($openAIResponse['fat'] ?? 0.00) : $fat;
        }

        // Ensure numeric values with two decimal places
        return response()->json([
            'title' => $title,
            'protein' => round($protein, 2),
            'carbs' => round($carbs, 2),
            'fat' => round($fat, 2),
            'converted_qty' => round($converted_qty, 2) . " g/ml",
            'measurement' => $measurement
        ]);
    }

    public function calculateNutrition(Request $request)
    {
        $request->validate([
            'title'       => 'required|string',
            'qty'         => 'required',
            'measurement' => 'required|string',
            'carbs'       => 'nullable|numeric|min:0',
            'protein'     => 'nullable|numeric|min:0',
            'fat'         => 'nullable|numeric|min:0',
            'energy'      => 'nullable',
            'saturated'   => 'nullable',
            'sugars'      => 'nullable',
            'dietary_fibre' => 'nullable',
            'sodium'      => 'nullable'
        ]);

        $title = $request->input('title');
        $qtyInput = $request->input('qty');
        $qty = $this->parseFraction($qtyInput);
        $measurement = strtolower($request->input('measurement'));

        $item = \App\Models\Item::find($request->id);
        $serving_size = $request->input('serving_size');
        $serving_size_unit = $request->input('serving_size_unit');
        $servings_per_pack = $request->input('servings_per_pack');

        // Fallback: Use qty and measurement if serving size or unit is missing
        $serving_size = (!empty($serving_size) && floatval($serving_size) > 0) ? $serving_size : $qty;
        $serving_size_unit = !empty($serving_size_unit) ? $serving_size_unit : $measurement;

        // Base macros
        $baseCarbs = $item->carbs ?? null;
        $baseProtein = $item->protein ?? null;
        $baseFat = $item->fat ?? null;
        $baseEnergy = $item->energy ?? null;
        $baseSaturated = $item->saturated ?? null;
        $baseSugars = $item->sugars ?? null;
        $baseDietaryFibre = $item->dietary_fibre ?? null;
        $baseSodium = $item->sodium ?? null;

        // Fetch from AI if needed
        if (is_null($baseCarbs) || is_null($baseProtein) || is_null($baseFat) ||
            $baseCarbs == 0.00 || $baseProtein == 0.00 || $baseFat == 0.00) {

            $aiNutrition = $this->fetchFromOpenAI($title, $serving_size, $serving_size_unit);

            $baseCarbs = $baseCarbs > 0 ? $baseCarbs : $aiNutrition['carbs'];
            $baseProtein = $baseProtein > 0 ? $baseProtein : $aiNutrition['protein'];
            $baseFat = $baseFat > 0 ? $baseFat : $aiNutrition['fat'];
            $baseEnergy = $baseEnergy > 0 ? $baseEnergy : $aiNutrition['energy'];
            $baseSaturated = $baseSaturated > 0 ? $baseSaturated : $aiNutrition['saturated'];
            $baseSugars = $baseSugars > 0 ? $baseSugars : $aiNutrition['sugars'];
            $baseDietaryFibre = $baseDietaryFibre > 0 ? $baseDietaryFibre : $aiNutrition['dietary_fibre'];
            $baseSodium = $baseSodium > 0 ? $baseSodium : $aiNutrition['sodium'];

            $serving_size = $serving_size ?: $aiNutrition['serving_size'];
            $serving_size_unit = $serving_size_unit ?: $aiNutrition['serving_size_unit'];
            $servings_per_pack = $servings_per_pack ?: $aiNutrition['servings_per_pack'];
        }

        // Unit conversion (if needed)
        if (in_array($measurement, ["piece", "tablespoon", "teaspoon", "cup", "handful", "dessert spoon", "pouch", "tub", "slice"])) {
            $convertedToGrams = $this->convertToGrams($title, $qty, $measurement);
            $qty = $convertedToGrams;
        }

        // Final safety net for serving size
        if (empty($serving_size) || floatval($serving_size) == 0) {
            $serving_size = $qty;
        }

        $num_servings = $qty / $serving_size;

        // Nutrition calculation
        $scaledCarbs = scaleNutritionValue($baseCarbs, $num_servings);
        $scaledProtein = scaleNutritionValue($baseProtein, $num_servings);
        $scaledFat = scaleNutritionValue($baseFat, $num_servings);
        $scaledEnergy = scaleNutritionValue($baseEnergy, $num_servings);
        $scaledSaturated = scaleNutritionValue($baseSaturated, $num_servings);
        $scaledSugars = scaleNutritionValue($baseSugars, $num_servings);
        $scaledDietaryFibre = scaleNutritionValue($baseDietaryFibre, $num_servings);
        $scaledSodium = scaleNutritionValue($baseSodium, $num_servings);

        return response()->json([
            'title' => $title,
            'protein' => round($scaledProtein, 2),
            'carbs' => round($scaledCarbs, 2),
            'fat' => round($scaledFat, 2),
            'energy' => $scaledEnergy,
            'saturated' => $scaledSaturated,
            'sugars' => $scaledSugars,
            'dietary_fibre' => $scaledDietaryFibre,
            'sodium' => $scaledSodium,
            'converted_qty' => round($num_servings, 2) . " g",
            'measurement' => $measurement,
            'serving_size' => round($serving_size, 2),
            'serving_size_unit' => $serving_size_unit,
            'servings_per_pack' => round($servings_per_pack ?? 0, 2),
            'alternate_serving_sizes' => $this->getAlternateServingSizes($title, $qty, $measurement)
        ]);
    }

    private function parseFraction($value)
    {
        if (strpos($value, '/') !== false) {
            [$numerator, $denominator] = explode('/', $value);
            if (is_numeric($numerator) && is_numeric($denominator) && $denominator != 0) {
                return floatval($numerator) / floatval($denominator);
            }
        }
        return is_numeric($value) ? floatval($value) : 0;
    }

    /**
     * Convert measurement units to grams/ml dynamically
     */
    private function convertToGrams($title, $qty, $measurement)
    {
        $conversionTable = [
            'g' => 1,
            'ml' => 1,
            'mL' => 1,

            // Australian Standard Cup Conversions
            'cup' => [
                'flour' => 125,  // 1 cup = 125g flour in Australia
                'sugar' => 220,  // 1 cup = 220g sugar in Australia
                'milk' => 250,   // 1 cup = 250ml milk (same as grams for liquid)
                'butter' => 250, // 1 cup = 250g butter (AU standard)
                'rice' => 200,   // 1 cup = 200g rice
                'oil' => 230,    // 1 cup = 230g oil
                'black beans' => 200, // 1 cup = 200g for black beans
                'default' => 250 // General assumption for 1 cup
            ],

            // Australian-based Spoon Conversions
            'teaspoon' => 5,  // 1 tsp = 5g (same as international standard)
            'tablespoon' => 20, // 1 tbsp = 20g (AU tablespoon is larger than US/UK 15g)
            'dessert spoon' => 10, // 1 dessert spoon = 10g

            // Other unit conversions
            'handful' => 30, // Approximate conversion for a handful
            'piece' => 150, // Default weight per piece (can vary)
            'pouch' => 250, // Approximate conversion for a pouch
            'tub' => 500, // Approximate conversion for a tub
            'slice' => 80,
            'loaf' => null,
            'muffin'=> null,
        ];

        if ($measurement == 'cup') {
            foreach ($conversionTable['cup'] as $key => $gramsPerCup) {
                if (stripos($title, $key) !== false) {
                    return $qty * $gramsPerCup;
                }
            }
            return $qty * $conversionTable['cup']['default'];
        }

        return isset($conversionTable[$measurement]) ? $qty * $conversionTable[$measurement] : null;
    }

    /**
     * Fetch missing macronutrients dynamically using OpenAI API
     */
    private function fetchFromOpenAI($title, $qty = null, $unit)
    {
        try {
            $qty = $qty ?? 100;
            $unit = $unit ?? 'grams/milliliters';
            $client = new Client();
            $prompt = "
            You are a nutrition expert. Estimate the macronutrient breakdown for:

            **Food Name**: $title
            **Quantity**: $qty $unit

            Return a valid JSON response:
            {
                \"protein\": value_in_grams,
                \"carbs\": value_in_grams,
                \"fat\": value_in_grams,
                \"energy\": value_in_kj,
                \"serving_size\": value_in_grams,
                \"serving_size_unit\": \"g\" or \"ml\",
                \"servings_per_pack\": number_of_servings
            }
            ";

            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer '. config('services.openai.key'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'    => 'gpt-4-0613',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a food nutrition assistant.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 200,
                ],
            ]);

            // Decode OpenAI response
            $result = json_decode($response->getBody(), true);
            // Check if OpenAI returned a valid response
            if (!isset($result['choices'][0]['message']['content'])) {
                throw new \Exception('Invalid OpenAI response format.');
            }

            $responseText = trim($result['choices'][0]['message']['content']);
            // Ensure only JSON is extracted
            $jsonStart = strpos($responseText, '{');
            $jsonEnd = strrpos($responseText, '}');
            if ($jsonStart === false || $jsonEnd === false) {
                throw new \Exception('Invalid JSON format received.');
            }

            $responseText = substr($responseText, $jsonStart, ($jsonEnd - $jsonStart + 1));
            $parsedResponse = json_decode($responseText, true);
            // Ensure response has required fields
            if (!isset($parsedResponse['protein'], $parsedResponse['carbs'], $parsedResponse['fat'])) {
                throw new \Exception('Missing macronutrient values in response.');
            }

            return $parsedResponse;


        } catch (\Exception $e) {
            Log::error("OpenAI API Error: " . $e->getMessage());
            return [];
        }
    }

    private function getAlternateServingSizes($title, $qty, $measurement)
    {
        try {
            $client = new Client();

            $prompt = <<<EOT
            You are an Australian‑accredited dietitian.
            For the food and quantity supplied, provide up to 5 alternative household measurements commonly used in Australia, relevant to the food type.

            For example, if the food is sliced bread, do NOT include 'rolls' as one of the units — because a roll is not a sliced bread format. Prefer 'slices', 'grams', 'loaf', or 'pieces'. If there are not 5 reasonable options, return only those that are relevant.

            ────────────────────────
            🇦🇺  Australian Household Benchmarks
            ────────────────────────
            • WEIGHT ↔ VOLUME
            – 1 cup = 250 mL           – 1 Tbsp = 20 mL
            – 1 tsp  = 5 mL

            • STANDARD PIECES
            – Fruit (apple, banana, orange, etc.) = 150 g
            – Bread roll = 70 g
            – Bread slice = 35 g
            – Small yoghurt tub = 170 g ± 20 g
            – Cheese slice = 25 g
            – Egg (whole) = 55 g
            – Handful nuts/seeds = 30 g
            – Weet‑Bix / breakfast biscuit = 16 g

            • AVOID mL for dry ingredients (like rice, oats, flour, powders)

            ────────────────────────
            ✅  Acceptable Units by Food Group
            ────────────────────────
            Liquids → mL, cup, Tbsp, tsp
            Dry grains → g, cup, Tbsp, tsp
            Cooked grains → g, cup, bowl (330 mL)
            Fruits/veg whole → g, piece
            Fruits/veg chopped/leafy → g, cup, Tbsp
            Beans, nuts, seeds → g, cup, Tbsp, tsp, handful
            Dairy (yoghurt, cheese) → g, cup, Tbsp, tsp, slice, tub
            Meat/poultry/fish/egg → g, piece, fillet, slice
            Bread & bakery → g, slice, roll, loaf, muffin
            Powders/condiments → g, cup, Tbsp, tsp
            Breakfast cereal (Weet-Bix, etc.) → g, piece, biscuit, cup, Tbsp
            Crackers/bars → g, piece, bar  ❌ *Not Tbsp/tsp*

            ────────────────────────
            🔁 Conversion Rules
            ────────────────────────
            1. Do NOT repeat the original input unit.
            2. Use at most 1 decimal place unless accuracy demands more.
            3. Order by usefulness in an Aussie kitchen.
            4. Skip any unit that does not make sense (e.g. “cup” of whole banana).
            5. Sample logic:
            • 70 g dry rice → 0.33 cup, 3.5 Tbsp, 14 tsp, 0.21 bowl (cooked)
            • 80 g bread → 2 slice, 0.11 loaf

            ────────────────────────
            📝 Response Format
            ────────────────────────
            Return just a JSON array.
            Each item must contain `quantity` (number) and `unit` (string), ordered most useful → least.

            Example:
            [
            {"quantity":1,   "unit":"cup"},
            {"quantity":12.5,"unit":"tablespoon"},
            {"quantity":0.4, "unit":"bowl"}
            ]

            Food: {$title}
            Quantity: {$qty} {$measurement}
            EOT;

            // Call OpenAI
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer '. config('services.openai.key'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4-0613',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.0,
                    'max_tokens' => 300
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            $responseText = trim($result['choices'][0]['message']['content']);

            // Extract just the JSON array using regex
            if (preg_match('/\[(.*?)\]/s', $responseText, $matches)) {
                $jsonArrayString = '[' . $matches[1] . ']';

                // Decode the array
                $parsedArray = json_decode($jsonArrayString, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Invalid JSON array from GPT");
                }

                // Convert into expected format:
                $converted = [];
                foreach ($parsedArray as $item) {
                    if (!isset($item['quantity'], $item['unit'])) {
                        continue;
                    }
                    $unit = strtolower($item['unit']);
                    $quantity = rtrim(rtrim(number_format($item['quantity'], 2, '.', ''), '0'), '.'); // tidy decimals
                    $converted[$unit] = "{$quantity} {$unit}";
                }

                return $converted;
            } else {
                throw new \Exception("No valid JSON array found in response");
            }

        } catch (\Exception $e) {
            Log::error("OpenAI API Error: " . $e->getMessage());
            return [
                'alternate_serving_sizes' => [
                    "cup" => "N/A cup",
                    "teaspoon" => "N/A teaspoon",
                    "tablespoon" => "N/A tablespoon",
                    "slice" => "N/A slice",
                    "piece" => "N/A piece"
                ]
            ];
        }
    }

    public function mealFoodNutritionCalculation(Request $request)
    {
        // Validate input
        $request->validate([
            'title'           => 'required|string',
            'qty'             => 'required|numeric',
            'measurement'     => 'required|string',
            'carbs'           => 'nullable|numeric|min:0',
            'protein'         => 'nullable|numeric|min:0',
            'fat'             => 'nullable|numeric|min:0'
        ]);
        // Extract inputs
        $title = $request->input('title');
        $qty = $request->input('qty');
        $measurement = strtolower($request->input('measurement'));
        $item = \App\Models\Item::find($request->id);
        $carbs = $request->input('carbs') ?? ($item->carbs ?? null);
        $protein = $request->input('protein') ?? ($item->protein ?? null);
        $fat = $request->input('fat') ?? ($item->fat ?? null);
        $serving_size = $request->input('serving_size') ?? ($item->serving_size ?? null);
        $servings_per_pack = $request->input('servings_per_pack') ?? ($item->servings_per_pack ?? null);
        $serving_size_unit = $request->input('serving_size_unit') ?? ($item->serving_size_unit ?? null);
        // Validate serving size & servings per pack
        $serving_size = $serving_size ?: null;
        $servings_per_pack = $servings_per_pack ?: null;

        // Auto-calculate missing serving details
        if (!$serving_size && $qty && $servings_per_pack) {
            $serving_size = $qty / $servings_per_pack;
        } elseif (!$servings_per_pack && $qty && $serving_size) {
            $servings_per_pack = $qty / $serving_size;
        }

        // Fetch missing macronutrients from AI if any are missing
        if ($carbs === null || $carbs == 0.00 || $protein === null || $protein == 0.00 || $fat === null || $fat == 0.00) {
            $aiNutrition = $this->fetchFromOpenAI($title, null, 100); // Fetch for 100g
            $carbs = ($carbs > 0) ? $carbs : $aiNutrition['carbs'];
            $protein = ($protein > 0) ? $protein : $aiNutrition['protein'];
            $fat = ($fat > 0) ? $fat : $aiNutrition['fat'];
            $serving_size = $serving_size ?: $aiNutrition['serving_size'];
            $serving_size_unit = $serving_size_unit ?: $aiNutrition['serving_size_unit'];
            $servings_per_pack = $servings_per_pack ?: $aiNutrition['servings_per_pack'];
        }

        $num_servings = $qty / $serving_size; // Example: 500ml / 250ml = 2 servings
        if($measurement == "piece" || $measurement == "tbsp" || $measurement == "cup") {
            $num_servings = $this->convertToGrams($title, $qty, $measurement);
            $num_servings = $num_servings / $serving_size;
        }

        // Scale macronutrients based on servings
        $scaledCarbs = $carbs * $num_servings;
        $scaledProtein = $protein * $num_servings;
        $scaledFat = $fat * $num_servings;

        return response()->json([
            'title' => $title,
            'protein' => round($scaledProtein, 2),
            'carbs' => round($scaledCarbs, 2),
            'fat' => round($scaledFat, 2),
            'converted_qty' => round($num_servings, 2) . " g",
            'measurement' => $measurement,
            'serving_size' => round($serving_size, 2),
            'serving_size_unit' => $serving_size_unit ?? 'g',
            'servings_per_pack' => round($servings_per_pack, 2),

        ]);
    }

    public function generateDescription(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $title = $request->input('title');

        $prompt = <<<EOD
            In making healthier food choices, provide a concise educational description (max 20 words) for the food item "$title". Avoid myths, clinical language, and keep it science-backed and useful. Base tone on the Australian Institute of Sport website.
            EOD;

        $client = new Client();

        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.openai.key'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'    => 'gpt-4-0613',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a food nutrition assistant.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 200,
                    'temperature' => 0.7,
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            $description = $result['choices'][0]['message']['content'] ?? '';
            $description = trim($description, '"');

            return response()->json([
                'description' => trim($description),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate description.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
