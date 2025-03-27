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
                        //dd($product);
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


    public function addFood(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|url', // Ensure it's a valid URL
            'protein' => 'nullable',
            'carbs' => 'nullable',
            'fat' => 'nullable',
            'category' => 'nullable',
            'serving_per_pack' => 'nullable',
            'serving_size' => 'nullable',
        ]);
        try {
            // Step 1: Download the image from the URL
            $imageContent = file_get_contents($validated['image']);
            if ($imageContent === false) {
                return response()->json(['success' => false, 'message' => 'Unable to download the image.']);
            }

            // Step 2: Generate a unique filename and save to storage
            $imageName = Str::random(32) . '.jpg';  // Generate a random 32-character string and append '.jpg'
            // Full path to store the image in public storage
            $imagePath = 'items/' . $imageName; // Define the folder and filename
        
            // Save the image to storage
            Storage::disk('public')->put($imagePath, $imageContent);
            
            $protein = $validated['protein'] ? rtrim($validated['protein'], 'g') : 0;
            $carbs = $validated['carbs'] ? rtrim($validated['carbs'], 'g') : 0;
            $fat = $validated['fat'] ? rtrim($validated['fat'], 'g') : 0;
            $serving_size = $validated['serving_size'] ? rtrim($validated['serving_size'], 'G') : 0;
            // dd($protein, $carbs, $fat);

            if(isDecimal($protein)){
                $protein = floatval($protein);
            }else {
                $protein = formatDecimal($protein);
            }

            if(isDecimal($carbs)){
                $carbs = floatval($carbs);
            }else {
                $carbs = formatDecimal($carbs);
            }

            if(isDecimal($fat)){
                $fat = floatval($fat);
            }else {
                $fat = formatDecimal($fat);
            }

            if(isDecimal($serving_size)){
                $serving_size = floatval($serving_size);
            }else {
                $serving_size = formatDecimal($serving_size);
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

            // Step 3: Save food details in the database
            $food = new Item(); // Assuming you have a Food model
            $food->title = $validated['name'];
            $food->protein = $protein;
            $food->carbs = $carbs;
            $food->fat = $fat;
            $food->serving_per_pack = $validated['serving_per_pack'];
            $food->serving_size = $serving_size;
            $food->image = 'items/' . $imageName; // Path to the stored image
            $food->is_swiped = 0;
            $food->category_id = isset($foodCategory) ? $foodCategory->id : null;
            $food->save();

            // Step 4: Redirect with success message
            return response()->json(['success' => true, 'message' => 'Food added successfully.']);
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Error adding food: ' . $e->getMessage());
            return response()->json(['error' => false, 'message' => 'Failed to add food ']);
        }
    }
}
