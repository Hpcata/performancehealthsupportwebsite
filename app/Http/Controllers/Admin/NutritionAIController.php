<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

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
        // Validate input
        $request->validate([
            'title'           => 'required|string',
            'qty'             => 'required',
            'measurement'     => 'required|string',
            'carbs'           => 'nullable|numeric|min:0',
            'protein'         => 'nullable|numeric|min:0',
            'fat'             => 'nullable|numeric|min:0',
            'energy'          => 'nullable'
        ]);
        // dd($request->input('qty'));
        // Extract inputs
        $title = $request->input('title');
        // $qty = $request->input('qty');
        // $qty = $this->convertFractionToDecimal($qty);
        $qtyInput = $request->input('qty');
        $qty = $this->parseFraction($qtyInput);

        $measurement = strtolower($request->input('measurement'));
    
        $item = \App\Models\Item::find($request->id);
        $serving_size = $request->input('serving_size');
        $servings_per_pack = $request->input('servings_per_pack');
        $serving_size_unit = $request->input('serving_size_unit');
    
        // Use original base macros (from DB or AI), not passed-in scaled values
        $baseCarbs = $item->carbs ?? null;
        $baseProtein = $item->protein ?? null;
        $baseFat = $item->fat ?? null;
        $baseEnergy = floatval($item->energy ?? null) ?? null;
        
        // If missing, fetch from AI
        if ($baseCarbs === null || $baseProtein === null || $baseFat === null || $baseCarbs == 0.00 || $baseProtein == 0.00 || $baseFat == 0.00) {
            $aiNutrition = $this->fetchFromOpenAI($title, $serving_size, $serving_size_unit); // 100g base
    
            $baseCarbs = $baseCarbs > 0 ? $baseCarbs : $aiNutrition['carbs'];
            $baseProtein = $baseProtein > 0 ? $baseProtein : $aiNutrition['protein'];
            $baseFat = $baseFat > 0 ? $baseFat : $aiNutrition['fat'];
            $baseEnergy = $baseEnergy > 0 ? $baseEnergy : $aiNutrition['energy'];
            $serving_size = $serving_size ?: $aiNutrition['serving_size'];
            $serving_size_unit = $serving_size_unit ?: $aiNutrition['serving_size_unit'];
            $servings_per_pack = $servings_per_pack ?: $aiNutrition['servings_per_pack'];
        }
    
        // Calculate serving size or servings per pack if one is missing
        $serving_size = $serving_size ?: null;
        $servings_per_pack = $servings_per_pack ?: null;
    
        if (!$serving_size && $qty && $servings_per_pack) {
            $serving_size = $qty / $servings_per_pack;
        } elseif (!$servings_per_pack && $qty && $serving_size) {
            $servings_per_pack = $qty / $serving_size;
        }
    
        // Convert to grams if unit is non-standard
        $num_servings = $qty / $serving_size;

        // if($measurement == 'g') {
        //     $num_servings = $qty / $serving_size;
        // } else {
        //     $convertedToGrams = $this->getGramsFromTitle($title, $qty, $measurement);
        //     dd($convertedToGrams);
        //     $num_servings = $convertedToGrams / $serving_size;
        // }
        
        if (in_array($measurement, ["piece", "tablespoon", "teaspoon", "cup", "handful", "dessert spoon", "pouch", "tub", "slice"])) {
            $convertedToGrams = $this->convertToGrams($title, $qty, $measurement);
            $num_servings = $convertedToGrams / $serving_size;
        }
    
        // Final macronutrient calculation (based on original per-serving/base values)
        $scaledCarbs = $baseCarbs * $num_servings;
        $scaledProtein = $baseProtein * $num_servings;
        $scaledFat = $baseFat * $num_servings;
        $scaledEnergy = $baseEnergy * $num_servings;
        // dd($scaledCarbs, $scaledProtein, $scaledFat );
        // Return nutrition data
        return response()->json([
            'title' => $title,
            'protein' => round($scaledProtein, 2),
            'carbs' => round($scaledCarbs, 2),
            'fat' => round($scaledFat, 2),
            'energy' => round($scaledEnergy, 2),
            'converted_qty' => round($num_servings, 2) . " g",
            'measurement' => $measurement,
            'serving_size' => round($serving_size, 2),
            'serving_size_unit' => $serving_size_unit ?? 'g',
            'servings_per_pack' => round($servings_per_pack, 2),
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
     * Helper function to convert fractional quantities like 1/2, 1/4 into decimal (float).
     */
    private function convertFractionToDecimal($fraction)
    {
        if (strpos($fraction, '/') !== false) {
            // If the qty is a fraction (e.g., 1/2, 3/4, 2/3)
            list($numerator, $denominator) = explode('/', $fraction);
            return (float)$numerator / (float)$denominator;
        }
        return (float)$fraction; // if it's already a decimal or integer value
    }

    // public function getGramsFromTitle($title, $qty, $measurement) {
    //     // Set your OpenAI API key here
    //     $apiKey = config('services.openai.key');
    
    //     // Define the prompt with placeholders for title, quantity, and measurement
    //     $prompt = <<<EOT
    //     You are an Australian-accredited dietitian.
    
    //     For the food and quantity supplied, calculate and return the equivalent weight in grams (g).
    
    //     ────────────────────────
    //     🇦🇺 Australian Household Benchmarks
    //     ────────────────────────
    //     • WEIGHT ↔ VOLUME  
    //     – 1 cup = 250 mL           – 1 Tbsp = 20 mL  
    //     – 1 tsp  = 5 mL
        
    //     • STANDARD PIECES  
    //     – Fruit (apple, banana, orange, etc.) = 150 g  
    //     – Bread roll = 70 g  
    //     – Bread slice = 35 g  
    //     – Small yoghurt tub = 170 g ± 20 g  
    //     – Cheese slice = 25 g  
    //     – Egg (whole) = 55 g  
    //     – Handful nuts/seeds = 30 g  
    //     – Weet‑Bix / breakfast biscuit = 16 g
    
    //     • Specific Ingredients Conversions  
    //     – Milk (1 cup) = 250 g  
    //     – Flour (1 cup) = 125 g  
    //     – Sugar (1 cup) = 220 g  
    //     – Rice (1 cup) = 200 g  
    //     – Butter (1 cup) = 250 g  
    //     – Beans (Black beans, etc.) (1 cup) = 200 g  
    //     – Oil (1 cup) = 230 g  
    //     – Honey (1 cup) = 340 g
    //     -Cous Cous (1 cup) = 200 g
    
    //     ────────────────────────
    //     ✅ Instructions
    //     ────────────────────────
    //     1. Always output a number representing grams — no text, no JSON, no explanation.
    //     2. If the food title suggests a specific ingredient (e.g., flour, rice, milk), apply the specific conversion for that ingredient.
    //     3. If the food item is not found in the specific ingredient list, use the default conversion (for example, 1 cup → 250 g).
    //     4. If the food is in pieces, estimate using 150 g per piece.
    //     5. If the quantity is in tablespoons, teaspoons, or dessert spoons, use standard conversions:  
    //         • Tablespoon → 20 g
    //         • Teaspoon → 5 g
    //         • Dessert Spoon → 10 g 
    //     6. Round to the nearest whole number.
    //     7. Never return `null`. Always estimate.
    
    //     ────────────────────────
    //     📝 Response Format
    //     ────────────────────────
    //     Return only the number.
    
    //     Example outputs:  
    //     `250`
    //     `80` 
    //     `150`
    
    //     Food: {$title}  
    //     Quantity: {$qty} {$measurement}
    // EOT;
    
    
    //     // Prepare data for the API request
    //     $data = [
    //         'model' => 'gpt-4',  // Specify the GPT model you're using
    //         'messages' => [
    //             ['role' => 'system', 'content' => 'You are an Australian-accredited dietitian.'],
    //             ['role' => 'user', 'content' => str_replace(['{$title}', '{$qty}', '{$measurement}'], [$title, $qty, $measurement], $prompt)],
    //         ],
    //         'temperature' => 0.5,  // Use a moderate level of creativity (adjust as needed)
    //         'max_tokens' => 100,   // Limit the response to prevent over-fetching
    //     ];
    
    //     // Set the OpenAI API URL
    //     $url = 'https://api.openai.com/v1/chat/completions';
    
    //     // Initialize cURL session
    //     $ch = curl_init();
    
    //     // Set the cURL options
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         'Content-Type: application/json',
    //         'Authorization: Bearer ' . $apiKey
    //     ]);
    
    //     // Execute the cURL request
    //     $response = curl_exec($ch);
    
    //     // Check for errors in the cURL request
    //     if ($response === false) {
    //         $error = curl_error($ch);
    //         curl_close($ch);
    //         return "Error: $error";
    //     }
    
    //     // Close cURL session
    //     curl_close($ch);
    
    //     // Decode the response from the API
    //     $responseData = json_decode($response, true);
    
    //     // Check if the response contains valid data
    //     if (isset($responseData['choices'][0]['message']['content'])) {
    //         $grams = trim($responseData['choices'][0]['message']['content']);
    //         return (int)$grams;  // Return the grams value as an integer
    //     } else {
    //         return 'Error: Invalid response from API';
    //     }
    // }
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
            'pouch' => null,
            // Weight conversions
            // 'oz' => 28.35, 
            // 'lb' => 453.59
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
    private function fetchFromOpenAI($title, $qty, $unit)
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
            // dd($responseText);
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
            \Log::error("OpenAI API Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Find food swaps dynamically using AI or database
     */
    private function getFoodSwaps($food, $carbs, $protein, $fat)
    {
        try {
            $client = new Client();
            $prompt = "
            Suggest three alternative foods that have similar macronutrients to the given food.
            The alternatives should have similar carbs, protein, and fat values.
            
            **Original Food**: $food  
            **Carbs**: $carbs g  
            **Protein**: $protein g  
            **Fat**: $fat g  

            Provide a JSON response in the following format:
            {
                \"swaps\": [
                    {\"food\": \"Alternative 1\", \"carbs\": X, \"protein\": Y, \"fat\": Z},
                    {\"food\": \"Alternative 2\", \"carbs\": X, \"protein\": Y, \"fat\": Z},
                    {\"food\": \"Alternative 3\", \"carbs\": X, \"protein\": Y, \"fat\": Z}
                ]
            }
            ";

            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer '. config('services.openai.key'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'    => 'gpt-4-0613',
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'max_tokens' => 200,
                    'temperature' => 0.7,
                ],
            ]);

            // Decode API response
            $result = json_decode($response->getBody(), true);

            // 🔍 Debugging: Log the full response from OpenAI
            \Log::info('OpenAI Response: ', $result);

            // ✅ Fix: Ensure "choices" exists before accessing it
            if (empty($result['choices']) || !isset($result['choices'][0]['message']['content'])) {
                throw new \Exception("Invalid API response: Missing content field.");
            }

            // Extract the response text
            $responseText = trim($result['choices'][0]['message']['content']);

            // ✅ Remove triple quotes (""") if present
            $responseText = preg_replace('/^"""\s*|\s*"""$/', '', $responseText);

            // ✅ Remove Markdown-style formatting (```json ... ```)
            $responseText = preg_replace('/^```json|```$/', '', $responseText);

            // ✅ Remove extra whitespace
            $responseText = trim($responseText);
            $responseText = preg_replace('/(\d+(\.\d+)?)\s*g/', '$1', $responseText);


            // dd($responseText);
            // ✅ Fix: Ensure JSON is properly decoded
            $parsedResponse = json_decode($responseText, true);

            // 🔍 Debugging: Log parsed response
            \Log::debug($parsedResponse);

            // ✅ Ensure "swaps" key exists in the parsed response
            if (!isset($parsedResponse['swaps']) || !is_array($parsedResponse['swaps'])) {
                throw new \Exception("Invalid JSON format: Missing 'swaps' key.");
            }

            return $parsedResponse['swaps'];
        } catch (\Exception $e) {
            dd($e->getMessage());
            return [];
        }
        
    }

    // private function getAlternateServingSizes($title, $qty, $measurement)
    // {
    //     try {
    //         $client = new Client();

    //         $food = \App\Models\Item::with('category')->where('title',$title)->first();
    //         $foodCategory = $food->category->name ?? 'Groceries';

    //         $measurementRules = [
    //             "Poultry, Meat & Seafood" => ["grams", "pieces"],
    //             "Deli & Chilled Meals" => ["grams", "pieces"],
    //             "Dairy, Eggs & Fridge" => ["grams", "cups", "tbsp"],
    //             "Bakery" => ["pieces", "grams"],
    //             "Freezer" => ["grams", "pieces"],
    //             "Snacks & Confectionery" => ["grams", "pieces"],
    //             "Pantry" => ["grams", "cups", "tbsp"],
    //             "International Foods" => ["grams", "pieces"], // Adjust per food type
    //             "Drinks" => ["ml", "cups"],
    //             "Fruit & Vegetables" => ["grams", "pieces", "cups"],
    //             "Takeaway Foods" => ["pieces", "grams"],
    //             "Supplements" => ["grams", "pieces"],
    //             "Groceries" => ["grams"]
    //         ];
    
    //         $allowedMeasurements = $measurementRules[$foodCategory] ?? ["grams"];
    
    //         // ✅ Step 2: Build AI Prompt Dynamically
    //         $prompt = "
    //         You are a nutrition expert in Australia. Given a food item, determine the **three most common measurement units** used in Australia for this food and provide alternative serving sizes accordingly.
    
    //         **Food Name**: $title  
    //         **Quantity**: $qty $measurement  
    //         **Food Category**: $foodCategory  
    //         **Allowed Measurements**: " . implode(", ", $allowedMeasurements) . "
    
    //         Return response in strict JSON format with alternate serving sizes.
    //         ";

    //         $response = $client->post('https://api.openai.com/v1/chat/completions', [
    //             'headers' => [
    //                 'Authorization' => 'Bearer '. config('services.openai.key'), 
    //                 'Content-Type'  => 'application/json',
    //             ],
    //             'json' => [
    //                 'model'    => 'gpt-4-0613',
    //                 'messages' => [['role' => 'user', 'content' => $prompt]],
    //                 'max_tokens' => 200,
    //                 'temperature' => 0.1,
    //             ],
    //         ]);

    //         $result = json_decode($response->getBody(), true);
    //         $responseText = trim($result['choices'][0]['message']['content']);
    //         dd($responseText);
    //         $responseText = preg_replace('/^```json|```$/', '', $responseText);
    //         $parsedResponse = json_decode(trim($responseText), true);

    //         if (!isset($parsedResponse['serving_sizes']) || !is_array($parsedResponse['serving_sizes'])) {
    //             throw new \Exception("Invalid JSON format: Missing 'serving_sizes' key.");
    //         }

    //         return $parsedResponse['serving_sizes'];
    //     } catch (\Exception $e) {
    //         \Log::error("OpenAI API Error: " . $e->getMessage());
    //         return [
    //             "cups" => "Standard serving size unavailable",
    //             "grams_milliliters" => "Standard serving size unavailable",
    //             "pieces_or_tbsp" => "Standard serving size unavailable"
    //         ];
    //     }
    // }

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
            dd($e->getMessage());
            \Log::error("OpenAI API Error: " . $e->getMessage());
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

    public function calculateNutritions(Request $request)
    {
        $request->validate([
            'title'       => 'required|string',
            'qty'         => 'required|numeric|min:0.01',
            'measurement' => 'required|string',
            'carbs'       => 'nullable|numeric',
            'protein'     => 'nullable|numeric',
            'fat'         => 'nullable|numeric',
            // 'per_100g'    => 'required|boolean' // Indicates if provided values are per 100g or per serving
        ]);

        $title = trim(strtolower($request->input('title')));
        $qty = $request->input('qty');
        $measurement = strtolower($request->input('measurement'));
        $carbs = $request->input('carbs');
        $protein = $request->input('protein');
        $fat = $request->input('fat');
        $isPer100g = $request->input('per_100g'); // True if values are per 100g, false if per serving

        // Convert values based on quantity
        if ($isPer100g) {
            $factor = $qty / 100; // Scaling factor if per 100g
        } else {
            $factor = 1; // No scaling needed if already per serving
        }

        // Adjust values
        $carbs = !is_null($carbs) ? round($carbs * $factor, 2) : null;
        $protein = !is_null($protein) ? round($protein * $factor, 2) : null;
        $fat = !is_null($fat) ? round($fat * $factor, 2) : null;

        // Identify missing values
        $missingValues = [];
        if (is_null($protein)) $missingValues[] = 'Protein';
        if (is_null($carbs)) $missingValues[] = 'Carbohydrates';
        if (is_null($fat)) $missingValues[] = 'Fat';

        // Fetch missing values from OpenAI
        if (!empty($missingValues)) {
            try {
                $client = new Client();
                $openaiApiKey = config('services.openai.key');

                $prompt = "
                You are an expert nutritionist. Provide approximate values per 100g for the missing nutrients of the following food:
                
                - Food: $title
                - Required data: " . implode(', ', $missingValues) . "
                - Output format:
                " . implode(": [value]g\n", $missingValues) . ": [value]g
                ";

                $response = $client->post('https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer '. config('services.openai.key'),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'model' => 'gpt-4',
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a nutritionist assistant.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'max_tokens' => 50
                    ]
                ]);

                $apiResponse = json_decode($response->getBody(), true);
                $nutritionText = $apiResponse['choices'][0]['message']['content'] ?? '';

                // Extract and scale missing values
                if (is_null($protein)) {
                    preg_match('/Protein:\s*([\d\.]+)g/', $nutritionText, $match);
                    $protein = isset($match[1]) ? round($match[1] * $factor, 2) : 0;
                }
                if (is_null($carbs)) {
                    preg_match('/Carbohydrates:\s*([\d\.]+)g/', $nutritionText, $match);
                    $carbs = isset($match[1]) ? round($match[1] * $factor, 2) : 0;
                }
                if (is_null($fat)) {
                    preg_match('/Fat:\s*([\d\.]+)g/', $nutritionText, $match);
                    $fat = isset($match[1]) ? round($match[1] * $factor, 2) : 0;
                }

            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to fetch data from OpenAI.',
                    'details' => $e->getMessage()
                ], 500);
            }
        }

        // Response
        return response()->json([
            'success' => true,
            'result' => [
                'title' => ucfirst($title),
                'protein' => $protein,
                'carbs' => $carbs,
                'fat' => $fat,
                'serving_size' => "$qty $measurement",
                'per_100g' => $isPer100g
            ]
        ]);
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
            $aiNutrition = $this->fetchFromOpenAI($title, 100); // Fetch for 100g
            $carbs = ($carbs > 0) ? $carbs : $aiNutrition['carbs'];
            $protein = ($protein > 0) ? $protein : $aiNutrition['protein'];
            $fat = ($fat > 0) ? $fat : $aiNutrition['fat'];
            $serving_size = $serving_size ?: $aiNutrition['serving_size'];
            $serving_size_unit = $serving_size_unit ?: $aiNutrition['serving_size_unit'];
            $servings_per_pack = $servings_per_pack ?: $aiNutrition['servings_per_pack'];
        }

        $num_servings = $qty / $serving_size; // Example: 500ml / 250ml = 2 servings
        // dd($num_servings);
        // dd($measurement);
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
    // public function calculateNutrition(Request $request)
    // {
    //     $request->validate([
    //         'title'      => 'required|string',
    //         'carbs'      => 'required|numeric',
    //         'protein'    => 'required|numeric',
    //         'fat'        => 'required|numeric',
    //         'qty'        => 'required|numeric',
    //         'measurement'  => 'required|string',
    //     ]);

    //     $title = $request->input('title');
    //     $carbs = $request->input('carbs');
    //     $protein = $request->input('protein');
    //     $fat = $request->input('fat');
    //     $qty = $request->input('qty');
    //     $measurement = $request->input('measurement');

    //     // Create dynamic prompt with exact quantity/measurement logic
    //     $prompt = "
    //     You are a precise nutrition assistant.
        
    //     The given food item has nutrition values per serving. Adjust the nutrition values based on the requested quantity and measurement type.
        
    //     ### Given Food Item:
    //     - Title: $title
    //     - Serving Size: $serving_size $measurement
    //     - Servings Per Pack: $serving_per_pack
    //     - Measurement Type: $measurement (solid, liquid, or powdered)
        
    //     ### Nutrition Info (Per $serving_size $measurement):
    //     - Carbohydrates: {$carbs}g
    //     - Protein: {$protein}g
    //     - Fat: {$fat}g
        
    //     ### User Request:
    //     - Requested Quantity: $qty $measurement
        
    //     ### Task:
    //     1. Identify if the unit is **solid (g, kg, oz)**, **liquid (ml, cup, L, tablespoon)**, or **powdered (scoop, packet, bar)**.
    //     2. Convert the requested quantity into servings.
    //     3. Scale the **carbohydrates, protein, and fat** values based on the new quantity.
    //     4. Apply the correct conversion factors for **cup, tablespoon, ml, g, etc.**
    //     5. Convert and return the final values for **protein, carbs, and fat** in this JSON format:
        
    //     {
    //         \"title\": \"$title\",
    //         \"protein\": [value],
    //         \"carbs\": [value],
    //         \"fat\": [value],
    //         \"serving_size\": \"$serving_size $measurement\",
    //         \"serving_per_pack\": $serving_per_pack,
    //         \"total_servings_in_qty\": [calculated_servings]
    //     }
    //     ";

    //     try {
    //         $client = new Client();

    //         $response = $client->post('https://api.openai.com/v1/chat/completions', [
    //             'headers' => [
    //                  'Authorization' => 'Bearer '. config('services.openai.key'),
    //                 'Content-Type' => 'application/json',
    //             ],
    //             'json' => [
    //                 'model' => 'gpt-4-0613',
    //                 'messages' => [
    //                     ['role' => 'system', 'content' => 'You are a nutrition calculation assistant.'],
    //                     ['role' => 'user', 'content' => $prompt],
    //                 ],
    //                 'max_tokens' => 400
    //             ]
    //         ]);

    //         $result = json_decode($response->getBody(), true);

    //         $nutritionData = $result['choices'][0]['message']['content'] ?? 'Nutritional information unavailable.';

    //         return response()->json([
    //             'result' => $nutritionData
    //         ]);

    //     } catch (\GuzzleHttp\Exception\RequestException $e) {
    //         return response()->json([
    //             'error' => 'Failed to fetch data from OpenAI. Please try again.',
    //             'details' => $e->getMessage()
    //         ], 500);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Error: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    // public function calculateNutrition(Request $request)
    // {
    //     $request->validate([
    //         'query' => 'required|string',
    //     ]);

    //     $client = new client();

    //     // Extracting the user's full query
    //     $userQuery = $request->query;

    //     // Prompt to OpenAI for parsing + calculation
    //     $prompt = "
    //     Extract the nutritional details from the provided text and calculate the total Protein, Carbohydrates, and Fat for the specified quantity.

    //     Text:
    //     $userQuery

    //     Format the result like this:
    //     - Protein: [value]g
    //     - Carbohydrates: [value]g
    //     - Fat: [value]g
    //     ";

    //     $response = $client->chat()->create([
    //         'model' => 'gpt-4',
    //         'messages' => [
    //             ['role' => 'system', 'content' => 'You are a nutrition calculation assistant.'],
    //             ['role' => 'user', 'content' => $prompt]
    //         ],
    //     ]);

    //     $result = $response['choices'][0]['message']['content'];

    //     return response()->json(['result' => $result]);
    // }

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
