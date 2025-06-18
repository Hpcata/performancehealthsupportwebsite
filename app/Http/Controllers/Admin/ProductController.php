<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Storage;
use App\Models\Item;
use Illuminate\Support\Str;  // Make sure to import the Str class

class ProductController extends Controller
{

    public function search(Request $request)
    {
        $results = [];
        $query = $request->input('query');
        $page = $request->input('page', 1);
        $perPage = 20;
        $pagination = [];

        if ($query) {
            $client = new Client();
            try {
                $response = $client->request('GET', 'https://www.woolworths.com.au/apis/ui/Search/products/', [
                    'query' => ['searchTerm' => $query],
                    'headers' => ['Accept' => 'application/json'],
                ]);

                $responseBody = json_decode($response->getBody(), true);
                $products = $responseBody['Products'] ?? [];

                foreach ($products as $productGroup) {
                    $groupProducts = $productGroup['Products'] ?? [];
                    foreach ($groupProducts as $product) {
                        $additionalAttributes = $product['AdditionalAttributes'] ?? [];
                        $nutrition = [];
                        // dd($product);
                        if (isset($additionalAttributes['nutritionalinformation'])) {
                            $nutritionInfo = json_decode($additionalAttributes['nutritionalinformation'], true);
                            $attributes = $nutritionInfo['Attributes'] ?? [];

                            foreach ($attributes as $attribute) {
                                if ($attribute['Name'] === 'Carbohydrate Quantity Per Serve - Total - NIP') {
                                    $nutrition['carbohydrate'] = $attribute['Value'] ?? '';
                                } elseif ($attribute['Name'] === 'Protein Quantity Per Serve - Total - NIP') {
                                    $nutrition['protein'] = $attribute['Value'] ?? '';
                                } elseif ($attribute['Name'] === 'Fat Total Quantity Per Serve - Total - NIP') {
                                    $nutrition['fat'] = $attribute['Value'] ?? '';
                                }
                                elseif ($attribute['Name'] === 'Servings Per Pack - Total - NIP') {
                                    $nutrition['serving_per_pack'] = $attribute['Value'] ?? '';
                                }
                                elseif ($attribute['Name'] === 'Serving Size - Total - NIP') {
                                    $nutrition['serving_size'] = $attribute['Value'] ?? '';
                                }
                            }
                        }

                        $category = '';
                        if (!empty($additionalAttributes['piesdepartmentnamesjson'])) {
                            $decodedPiesDept = json_decode($additionalAttributes['piesdepartmentnamesjson'], true);
                
                            if (is_array($decodedPiesDept) && count($decodedPiesDept) >= 2) {
                                $category = $decodedPiesDept[1]; // Set second index as category
                            }else {
                                $category = $decodedPiesDept;
                            }
                        }

                        $results[] = [
                            'name' => $product['Name'] ?? '',
                            'barcode' => $product['Barcode'] ?? '',
                            'size' => $product['PackageSize'] ?? '',
                            'price' => $product['Price'] ?? '',
                            'image' => $product['SmallImageFile'] ?? '',
                            'category' => !empty($additionalAttributes) ? $additionalAttributes['sapdepartmentname'] : '',
                            'nutrition' => $nutrition,
                        ];
                    }
                }

                $total = count($products);
                $pagination = [
                    'current_page' => $page,
                    'total_pages' => ceil($total / $perPage),
                    'total' => $total,
                    'per_page' => $perPage,
                ];
            } catch (\Exception $e) {
                Log::error('Error fetching products: ' . $e->getMessage());
            }
        }

        if ($request->ajax()) {
            return response()->json(['results' => $results, 'pagination' => $pagination]);
        }

        return view('product-with-image', compact('results', 'query', 'pagination'));
    }


    // public function addFood(Request $request) 
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255|unique:items,title',
    //         'image' => 'required|url', // Ensure it's a valid URL
    //         'protein' => 'nullable',
    //         'carbs' => 'nullable',
    //         'fat' => 'nullable',
    //         'category' => 'nullable',
    //         'serving_per_pack' => 'nullable',
    //         'serving_size' => 'nullable',
    //     ]);
    //     try {
    //         // Step 1: Download the image from the URL
    //         $imageContent = file_get_contents($validated['image']);
    //         if ($imageContent === false) {
    //             return response()->json(['success' => false, 'message' => 'Unable to download the image.']);
    //         }

    //         // Step 2: Generate a unique filename and save to storage
    //         $imageName = Str::random(32) . '.jpg';  // Generate a random 32-character string and append '.jpg'
    //         // Full path to store the image in public storage
    //         $imagePath = 'items/' . $imageName; // Define the folder and filename
        
    //         // Save the image to storage
    //         Storage::disk('public')->put($imagePath, $imageContent);
            
    //         $protein = $validated['protein'] ? rtrim($validated['protein'], 'g') : 0;
    //         $carbs = $validated['carbs'] ? rtrim($validated['carbs'], 'g') : 0;
    //         $fat = $validated['fat'] ? rtrim($validated['fat'], 'g') : 0;
    //         $serving_size = $validated['serving_size'] ? rtrim($validated['serving_size'], 'G') : 0;
    //         // dd($protein, $carbs, $fat);

    //         if(isDecimal($protein)){
    //             $protein = floatval($protein);
    //         }else {
    //             $protein = formatDecimal($protein);
    //         }

    //         if(isDecimal($carbs)){
    //             $carbs = floatval($carbs);
    //         }else {
    //             $carbs = formatDecimal($carbs);
    //         }

    //         if(isDecimal($fat)){
    //             $fat = floatval($fat);
    //         }else {
    //             $fat = formatDecimal($fat);
    //         }

    //         if(isDecimal($serving_size)){
    //             $serving_size = floatval($serving_size);
    //         }else {
    //             $serving_size = formatDecimal($serving_size);
    //         }

    //         $keywords = explode(" ", strtolower($validated['category']));

    //         // Search for any matching keyword in the database
    //         $foodCategory = \App\Models\FoodCategory::where(function ($query) use ($keywords) {
    //             foreach ($keywords as $keyword) {
    //                 $query->orWhereRaw("LOWER(name) LIKE ?", ["%$keyword%"]);
    //             }
    //         })->first();

    //         if(!$foodCategory){

    //             $foodCategory = new \App\Models\FoodCategory();
    //             $foodCategory->name = ucwords($validated['category']);
    //             $foodCategory->save();
    //         }

    //         // Step 3: Save food details in the database
    //         $food = Item::where('title', $validated['name'])->first();

    //         if($food){
    //             return response()->json(['success' => false, 'message' => 'Food already exists.']);
    //         }

    //         $food = new Item(); // Assuming you have a Food model
    //         $food->title = $validated['name'];
    //         $food->protein = $protein;
    //         $food->carbs = $carbs;
    //         $food->fat = $fat;
    //         $food->serving_per_pack = $validated['serving_per_pack'];
    //         $food->serving_size = $serving_size;
    //         $food->image = 'items/' . $imageName; // Path to the stored image
    //         $food->is_swiped = 0;
    //         $food->category_id = isset($foodCategory) ? $foodCategory->id : null;
    //         $food->save();

    //         // Step 4: Redirect with success message
    //         return response()->json(['success' => true, 'message' => 'Food added successfully.']);
    //     } catch (\Exception $e) {
    //         dd($e->getMessage());
    //         Log::error('Error adding food: ' . $e->getMessage());
    //         return response()->json(['error' => false, 'message' => 'Failed to add food ']);
    //     }
    // }

    // public function addFood(Request $request) 
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255|unique:items,title',
    //         'image' => 'required|url', // Ensure it's a valid URL
    //         'protein' => 'nullable',
    //         'carbs' => 'nullable',
    //         'fat' => 'nullable',
    //         'category' => 'nullable',
    //         'serving_per_pack' => 'nullable',
    //         'serving_size' => 'nullable',
    //     ]);

    //     try {
    //         // Step 1: Download and Save Image
    //         $imageContent = file_get_contents($validated['image']);
    //         if ($imageContent === false) {
    //             return response()->json(['success' => false, 'message' => 'Unable to download the image.']);
    //         }
    //         $imageName = Str::random(32) . '.jpg';  
    //         $imagePath = 'items/' . $imageName; 
    //         Storage::disk('public')->put($imagePath, $imageContent);
            
    //         // Step 2: Validate & Process Nutrition Values
    //         $protein = $validated['protein'] ? rtrim($validated['protein'], 'g') : 0;
    //         $carbs = $validated['carbs'] ? rtrim($validated['carbs'], 'g') : 0;
    //         $fat = $validated['fat'] ? rtrim($validated['fat'], 'g') : 0;
    //         $serving_size = $validated['serving_size'] ? rtrim($validated['serving_size'], 'G') : 0;
    //         $servings_per_pack = $validated['serving_per_pack'] ?? 0;

    //         if (isDecimal($protein)) { $protein = floatval($protein); } else { $protein = formatDecimal($protein); }
    //         if (isDecimal($carbs)) { $carbs = floatval($carbs); } else { $carbs = formatDecimal($carbs); }
    //         if (isDecimal($fat)) { $fat = floatval($fat); } else { $fat = formatDecimal($fat); }
    //         if (isDecimal($serving_size)) { $serving_size = floatval($serving_size); } else { $serving_size = formatDecimal($serving_size); }

    //         // Step 3: Fetch Nutrition Data from OpenAI if missing
    //         if ($protein == 0 || $carbs == 0 || $fat == 0 || $serving_size == 0 || $servings_per_pack == 0) {
    //             $nutritionData = $this->fetchFromOpenAI($validated['name']);
    //             if (!empty($nutritionData)) {
    //                 $protein = (float) str_replace('g', '', $nutritionData['protein']) ?? $protein;
    //                 $carbs = (float) str_replace('g', '', $nutritionData['carbs']) ?? $carbs;
    //                 $fat = (float) str_replace('g', '', $nutritionData['fat']) ?? $fat;
    //                 $serving_size = (float) str_replace('g', '', $nutritionData['serving_size']) ?? $serving_size;
    //                 $serving_size_unit = $nutritionData['serving_size_unit'];
    //                 $servings_per_pack = $nutritionData['servings_per_pack'] ?? $servings_per_pack;
    //             }
    //         }

    //         // Step 4: Find or Create Food Category
    //         $foodCategory = null;
    //         if (!empty($validated['category'])) {
    //             $keywords = explode(" ", strtolower($validated['category']));
    //             $foodCategory = \App\Models\FoodCategory::where(function ($query) use ($keywords) {
    //                 foreach ($keywords as $keyword) {
    //                     $query->orWhereRaw("LOWER(name) LIKE ?", ["%$keyword%"]);
    //                 }
    //             })->first();

    //             if (!$foodCategory) {
    //                 $foodCategory = new \App\Models\FoodCategory();
    //                 $foodCategory->name = ucwords($validated['category']);
    //                 $foodCategory->save();
    //             }
    //         }

    //         // Step 5: Check if Food Already Exists
    //         $food = Item::where('title', $validated['name'])->first();
    //         if ($food) {
    //             return response()->json(['success' => false, 'message' => 'Food already exists.']);
    //         }
            
    //         // Step 6: Save Food in Database
    //         $food = new Item();
    //         $food->title = $validated['name'];
    //         $food->protein = $protein;
    //         $food->carbs = $carbs;
    //         $food->fat = $fat;
    //         $food->serving_per_pack = $servings_per_pack;
    //         $food->serving_size = $serving_size;
    //         $food->serving_size_unit = $serving_size_unit;
    //         $food->image = 'items/' . $imageName; 
    //         $food->is_swiped = 0;
    //         $food->category_id = $foodCategory ? $foodCategory->id : null;
    //         $food->save();

    //         return response()->json(['success' => true, 'message' => 'Food added successfully.']);

    //     } catch (\Exception $e) {
    //         Log::error('Error adding food: ' . $e->getMessage());
    //         return response()->json(['error' => false, 'message' => 'Failed to add food']);
    //     }
    // }

    public function addFood(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:items,title',
            'image' => 'required|url',
            'protein' => 'nullable',
            'carbs' => 'nullable',
            'fat' => 'nullable',
            'category' => 'nullable',
            'serving_per_pack' => 'nullable',
            'serving_size' => 'nullable',
        ]);

        try {

            // dd($validated);
            // Download image
            $imageContent = file_get_contents($validated['image']);
            if ($imageContent === false) {
                return response()->json(['success' => false, 'message' => 'Unable to download the image.']);
            }

            // Store Image
            $imageName = Str::random(32) . '.jpg';
            $imagePath = 'items/' . $imageName;
            Storage::disk('public')->put($imagePath, $imageContent);

            // Extract & Clean Values
            $protein = isset($validated['protein']) ? rtrim($validated['protein'], 'g') : 0;
            $carbs = isset($validated['carbs']) ? rtrim($validated['carbs'], 'g') : 0;
            $fat = isset($validated['fat']) ? rtrim($validated['fat'], 'g') : 0;
            $serving_size_parse = isset($validated['serving_size']) ? $this->parseServingSize($validated['serving_size']) : 0;
            $serving_size = $serving_size_parse != 0 ? $serving_size_parse['serving_size'] : '0';
            $serving_size_unit = $serving_size_parse != 0 ? $serving_size_parse['serving_size_unit'] : 'g';

            // Convert values to float
            $protein = isDecimal($protein) ? floatval($protein) : formatDecimal($protein);
            $carbs = isDecimal($carbs) ? floatval($carbs) : formatDecimal($carbs);
            $fat = isDecimal($fat) ? floatval($fat) : formatDecimal($fat);
            $serving_size = isDecimal($serving_size) ? floatval($serving_size) : formatDecimal($serving_size);
            
            // **Step 1: Check if any value is missing & Fetch from OpenAI**
            $missingValues = [];

            if ($protein == 0 || $protein == null || $protein == 0.0) {
                $missingValues[] = "protein";
            }
            if ($carbs == 0 || $carbs == null || $carbs == 0.0) {
                $missingValues[] = "carbs";
            }
            if ($fat == 0 || $fat == null || $fat == 0.0) {
                $missingValues[] = "fat";
            }
            if ($serving_size == 0 || $serving_size == null || $serving_size == 0.0) {
                $missingValues[] = "serving_size";
            }
            // dd($missingValues);
            // Call OpenAI only if there are missing values
            if (!empty($missingValues)) {
                $openAiData = $this->fetchFromOpenAI($validated['name'], $missingValues);
                // dd($openAiData);
                // Replace only missing values
                if (isset($openAiData['protein']) && $protein == 0) {
                    $protein = $openAiData['protein'];
                }
                if (isset($openAiData['carbs']) && $carbs == 0) {
                    $carbs = $openAiData['carbs'];
                }
                if (isset($openAiData['fat']) && $fat == 0) {
                    $fat = $openAiData['fat'];
                }
                if (isset($openAiData['serving_size']) && $serving_size == 0) {
                    $serving_size = $openAiData['serving_size'];
                }
                if (isset($openAiData['serving_per_pack']) && $validated['serving_per_pack'] == null) {
                    $validated['serving_per_pack'] = $openAiData['serving_per_pack'];
                }

                if (isset($openAiData['serving_size_unit']) && $serving_size_unit == 'g') {
                    $serving_size_unit = $openAiData['serving_size_unit'];
                }
            }

            $keywords = explode(" ", strtolower($validated['category']));

            // Search for any matching keyword in the database
            $foodCategory = \App\Models\FoodCategory::where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhereRaw("LOWER(name) LIKE ?", ["%$keyword%"]);
                }
            })->first();

            if(!$foodCategory){

                $foodCategory = new \App\Models\FoodCategory();
                $foodCategory->name = ucwords($validated['category']);
                $foodCategory->save();
            }

            // **Step 2: Save to Database**
            $food = new Item();
            $food->title = $validated['name'];
            $food->protein = cleanDecimal($protein);
            $food->carbs = cleanDecimal($carbs);
            $food->fat = cleanDecimal($fat);
            $food->qty = cleanDecimal($serving_size);
            $food->unit = $serving_size_unit;
            $food->serving_per_pack = $validated['serving_per_pack'];
            $food->serving_size = $serving_size;
            $food->serving_size_unit = $serving_size_unit;
            $food->image = 'items/' . $imageName;
            $food->is_swiped = 0;
            $food->category_id = isset($foodCategory) ? $foodCategory->id : null;
            $food->save();

            return response()->json(['success' => true, 'food'=> $food , 'message' => 'Food added successfully.']);
        } catch (\Exception $e) {
            Log::error('Error adding food: ' . $e->getMessage());
            return response()->json(['error' => false, 'message' => 'Failed to add food ']);
        }
    }

    private function fetchFromOpenAI($title, $missingValues)
    {
        try {
            $client = new Client();
            $prompt = "You are a nutrition expert. Given only a food name, estimate the missing macronutrient values and serving size.";
    
            $prompt .= "\n**Food Name**: $title\n";
    
            // Request only missing values
            foreach ($missingValues as $value) {
                $prompt .= "**$value**: Estimate the value in grams.\n";
            }
    
            if (in_array('serving_size', $missingValues)) {
                $prompt .= "**serving_size**: Estimate the standard serving size.\n";
                $prompt .= "**serving_size_unit**: Provide either 'g' for solid foods or 'ml' for liquid foods.\n";
            }
    
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer '. config('services.openai.key'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4-0613',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a food nutrition assistant.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 200,
                ],
            ]);
    
            $result = json_decode($response->getBody(), true);
            
            if (!isset($result['choices'][0]['message']['content'])) {
                throw new \Exception('Invalid OpenAI response format.');
            }
    
            // Extract JSON from OpenAI response
            $responseText = trim($result['choices'][0]['message']['content']);

            $parsedResponse = $this->parseOpenAIResponse($responseText);
            // dd( $parsedResponse);
            return $parsedResponse;
    
        } catch (\Exception $e) {
            Log::error("OpenAI API Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Convert OpenAI's structured text response into an associative array
     */
    private function parseOpenAIResponse($text)
    {
        $data = [];
        $lines = explode("\n", $text);

        foreach ($lines as $line) {
            if (preg_match('/\*\*(.*?)\*\*: (.*)/', $line, $matches)) {
                $key = strtolower(str_replace(' ', '_', trim($matches[1]))); // Convert to lowercase with underscores
                $value = trim($matches[2]);

                if ($key === "serving_size_unit") {
                    // Extract only "g" or "ml" for serving_size_unit
                    if (preg_match('/\b(g|ml)\b/i', $value, $unitMatch)) {
                        $value = strtolower($unitMatch[1]);
                    } else {
                        $value = ""; // Default empty if not found
                    }
                } else {
                    // Remove unnecessary words and extract only numeric values
                    $value = preg_replace('/[^0-9.]/', '', $value);

                    // Convert to float if it's numeric
                    if (is_numeric($value)) {
                        $value = (float) $value;
                    }
                }

                $data[$key] = $value;
            }
        }

        return $data;
    }

    private function parseServingSize($input) {
        // Remove extra spaces and convert to lowercase
        $input = strtolower(trim($input));
    
        // Remove words like "approximate", "about", "around"
        $input = preg_replace('/\b(approximate|about|around)\b/', '', $input);
    
        // Extract numeric value (including decimals)
        preg_match('/\d+(\.\d+)?/', $input, $matches);
        $value = isset($matches[0]) ? floatval($matches[0]) : 0;
    
        // Determine the unit (g or ml), default to "g" if missing
        $unit = 'g'; // Default unit is grams
        if (strpos($input, 'ml') !== false) {
            $unit = 'ml';
        } elseif (strpos($input, 'g') !== false) {
            $unit = 'g';
        }
    
        return [
            'serving_size' => $value,
            'serving_size_unit' => $unit
        ];
    }
}