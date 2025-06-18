<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Item;
use Log;

class UpdateFoodServingSize extends Command
{
    protected $signature = 'update:food-serving-sizes';
    protected $description = 'Fetch food details from Woolworths API and update serving sizes, total quantity, and servings per qty';

    public function handle()
    {
        $client = new Client();
        $foods = Item::whereNull('woolworth_json')->get(); // Fetch only foods missing serving size
    
        foreach ($foods as $food) {
            try {
                // 🔹 Search Woolworths API using the food title
                $response = $client->request('GET', 'https://www.woolworths.com.au/apis/ui/Search/products/', [
                    'query' => ['searchTerm' => $food->title],
                    'headers' => ['Accept' => 'application/json'],
                ]);
    
                $responseBody = json_decode($response->getBody(), true);
                $products = $responseBody['Products'] ?? [];
    
                if (!empty($products)) {
                    $bestMatchProduct = null;
                    $highestMatchScore = 0;
    
                    foreach ($products as $productGroup) {
                        $groupProducts = $productGroup['Products'] ?? [];
    
                        foreach ($groupProducts as $product) {
                            $productTitle = trim(strtolower($product['Name'] ?? ''));
                            $foodTitle = trim(strtolower($food->title));
    
                            // 🔹 Calculate similarity between food title & product title
                            similar_text($foodTitle, $productTitle, $matchPercentage);
    
                            // 🔹 Select product with the highest title match
                            if ($matchPercentage > $highestMatchScore) {
                                $highestMatchScore = $matchPercentage;
                                $bestMatchProduct = $product;
                            }
                        }
                    }
    
                    // 🔹 Ensure best match is above 80% similarity
                    if ($highestMatchScore >= 80 && $bestMatchProduct) {
                        $additionalAttributes = $bestMatchProduct['AdditionalAttributes'] ?? [];
                        $nutrition = [];
                        
                        $food->update([
                            'woolworth_json' => $bestMatchProduct // 🔹 Save entire product JSON
                        ]);

                        $this->info("✅ Updated {$food->id} - {$food->title}");
                        // if (isset($additionalAttributes['nutritionalinformation'])) {
                        //     $nutritionInfo = json_decode($additionalAttributes['nutritionalinformation'], true);
                        //     $attributes = $nutritionInfo['Attributes'] ?? [];
    
                        //     foreach ($attributes as $attribute) {
                        //         if ($attribute['Name'] === 'Carbohydrate Quantity Per Serve - Total - NIP') {
                        //             $nutrition['carbohydrate'] = (float) $attribute['Value'] ?? null;
                        //         }
                        //         if ($attribute['Name'] === 'Protein Quantity Per Serve - Total - NIP') {
                        //             $nutrition['protein'] = (float) $attribute['Value'] ?? null;
                        //         }
                        //         if ($attribute['Name'] === 'Fat Total Quantity Per Serve - Total - NIP') {
                        //             $nutrition['fat'] = (float) $attribute['Value'] ?? null;
                        //         }
                        //         if ($attribute['Name'] === 'Servings Per Pack - Total - NIP') {
                        //             $nutrition['serving_per_pack'] = (float) $attribute['Value'] ?? null;
                        //         }
                        //         if ($attribute['Name'] === 'Serving Size - Total - NIP') {
                        //             $nutrition['serving_size'] = $attribute['Value'] ?? null;
                        //         }
                        //     }
                        // }
    
                        // 🔹 Ensure Carbs, Protein, and Fat match the existing food record
                        // if (
                        //     isset($food->carbs, $food->protein, $food->fat) &&
                        //     isset($nutrition['carbohydrate'], $nutrition['protein'], $nutrition['fat']) &&
                        //     abs($food->carbs - $nutrition['carbohydrate']) <= 0.1 &&
                        //     abs($food->protein - $nutrition['protein']) <= 0.1 &&
                        //     abs($food->fat - $nutrition['fat']) <= 0.1
                        // ) {
                        //     // 🔹 Validate and Extract Serving Size
                        //     $quantity = null;
                        //     $unit = null;
                        //     if (isset($nutrition['serving_size']) && !empty($nutrition['serving_size'])) {
                        //         $serving_size = trim($nutrition['serving_size']);
    
                        //         // Extract quantity and unit (e.g., "250 ml", "155.00 g", "300ML")
                        //         if (preg_match('/^([\d.]+)\s*([a-zA-Z]+)$/', $serving_size, $matches)) {
                        //             $quantity = (float) $matches[1]; // Convert to float
                        //             $unit = strtolower(trim($matches[2])); // Convert unit to lowercase
    
                        //             // Normalize unit (convert ml, ML, mL to 'ml', g, G to 'g', etc.)
                        //             $unit = str_replace(['ML', 'mL'], 'ml', $unit);
                        //             $unit = str_replace(['G', 'g'], 'g', $unit);
                        //         }
                        //     }
    
                        //     // 🔹 Update food record in database
                        //     $food->update([
                        //         'serving_size' => $quantity ?? null,
                        //         'serving_size_unit' => $unit ?? null,
                        //         'serving_per_pack' => $nutrition['serving_per_pack'] ?? null
                        //     ]);
    
                        //     $this->info("✅ Updated {$food->title} → Serving Size: {$quantity} {$unit}");
                        // } else {
                        //     $this->warn("⚠ Nutrition values do not match for: {$food->title}");
                        // }
                    } else {
                        $this->warn("⚠ No good match found for: {$food->id} - {$food->title}");
                    }
                } else {
                    $this->warn("⚠ No products found for: {$food->id} - {$food->title}");
                }
            } catch (\Exception $e) {
                $this->error("❌ Error fetching data for {$food->id} - {$food->title}: " . $e->getMessage());
                Log::error("Error fetching data for {$food->id} - {$food->title}: " . $e->getMessage());
            }
            // dd('11');
        }
    
        $this->info('✅ Food nutrition details updated successfully.');
       
    }
    // public function handle()
    // {
    //     $client = new Client();
    //     $foods = Item::whereNull('serving_size')->get(); // Fetch only foods missing serving size
    
    //     foreach ($foods as $food) {
    //         try {
    //             // 🔹 Search Woolworths API using the food title
    //             $response = $client->request('GET', 'https://www.woolworths.com.au/apis/ui/Search/products/', [
    //                 'query' => ['searchTerm' => $food->title],
    //                 'headers' => ['Accept' => 'application/json'],
    //             ]);
    
    //             $responseBody = json_decode($response->getBody(), true);
    //             $products = $responseBody['Products'] ?? [];
    
    //             if (!empty($products)) {
    //                 $bestMatchProduct = null;
    //                 $highestMatchScore = 0;
    
    //                 foreach ($products as $productGroup) {
    //                     $groupProducts = $productGroup['Products'] ?? [];
    
    //                     foreach ($groupProducts as $product) {
    //                         $productTitle = trim(strtolower($product['Name'] ?? ''));
    //                         $foodTitle = trim(strtolower($food->title));
    
    //                         // 🔹 Calculate similarity between food title & product title
    //                         similar_text($foodTitle, $productTitle, $matchPercentage);
    
    //                         // 🔹 Select product with the highest title match
    //                         if ($matchPercentage > $highestMatchScore) {
    //                             $highestMatchScore = $matchPercentage;
    //                             $bestMatchProduct = $product;
    //                         }
    //                     }
    //                 }
    
    //                 // 🔹 Ensure best match is above 80% similarity
    //                 if ($highestMatchScore >= 80 && $bestMatchProduct) {
    //                     $additionalAttributes = $bestMatchProduct['AdditionalAttributes'] ?? [];
    //                     $nutrition = [];
    
    //                     if (isset($additionalAttributes['nutritionalinformation'])) {
    //                         $nutritionInfo = json_decode($additionalAttributes['nutritionalinformation'], true);
    //                         $attributes = $nutritionInfo['Attributes'] ?? [];
    
    //                         foreach ($attributes as $attribute) {
    //                             if ($attribute['Name'] === 'Carbohydrate Quantity Per Serve - Total - NIP') {
    //                                 $nutrition['carbohydrate'] = (float) $attribute['Value'] ?? null;
    //                             }
    //                             if ($attribute['Name'] === 'Protein Quantity Per Serve - Total - NIP') {
    //                                 $nutrition['protein'] = (float) $attribute['Value'] ?? null;
    //                             }
    //                             if ($attribute['Name'] === 'Fat Total Quantity Per Serve - Total - NIP') {
    //                                 $nutrition['fat'] = (float) $attribute['Value'] ?? null;
    //                             }
    //                             if ($attribute['Name'] === 'Servings Per Pack - Total - NIP') {
    //                                 $nutrition['serving_per_pack'] = (float) $attribute['Value'] ?? null;
    //                             }
    //                             if ($attribute['Name'] === 'Serving Size - Total - NIP') {
    //                                 $nutrition['serving_size'] = $attribute['Value'] ?? null;
    //                             }
    //                         }
    //                     }
    
    //                     // 🔹 Ensure Carbs, Protein, and Fat match the existing food record
    //                     if (
    //                         isset($food->carbs, $food->protein, $food->fat) &&
    //                         isset($nutrition['carbohydrate'], $nutrition['protein'], $nutrition['fat']) &&
    //                         abs($food->carbs - $nutrition['carbohydrate']) <= 0.1 &&
    //                         abs($food->protein - $nutrition['protein']) <= 0.1 &&
    //                         abs($food->fat - $nutrition['fat']) <= 0.1
    //                     ) {
    //                         // 🔹 Validate and Extract Serving Size
    //                         $quantity = null;
    //                         $unit = null;
    //                         if (isset($nutrition['serving_size']) && !empty($nutrition['serving_size'])) {
    //                             $serving_size = trim($nutrition['serving_size']);
    
    //                             // Extract quantity and unit (e.g., "250 ml", "155.00 g", "300ML")
    //                             if (preg_match('/^([\d.]+)\s*([a-zA-Z]+)$/', $serving_size, $matches)) {
    //                                 $quantity = (float) $matches[1]; // Convert to float
    //                                 $unit = strtolower(trim($matches[2])); // Convert unit to lowercase
    
    //                                 // Normalize unit (convert ml, ML, mL to 'ml', g, G to 'g', etc.)
    //                                 $unit = str_replace(['ML', 'mL'], 'ml', $unit);
    //                                 $unit = str_replace(['G', 'g'], 'g', $unit);
    //                             }
    //                         }
    
    //                         // 🔹 Update food record in database
    //                         $food->update([
    //                             'serving_size' => $quantity ?? null,
    //                             'serving_size_unit' => $unit ?? null,
    //                             'qty' => $quantity ?? null,
    //                             'unit' => $unit ?? null,
    //                             'serving_per_pack' => $nutrition['serving_per_pack'] ?? null
    //                         ]);
    
    //                         $this->info("✅ Updated {$food->title} → Serving Size: {$quantity} {$unit}");
    //                     } else {
    //                         $this->warn("⚠ Nutrition values do not match for: {$food->title}");
    //                     }
    //                 } else {
    //                     $this->warn("⚠ No good match found for: {$food->title}");
    //                 }
    //             } else {
    //                 $this->warn("⚠ No products found for: {$food->title}");
    //             }
    //         } catch (\Exception $e) {
    //             $this->error("❌ Error fetching data for {$food->title}: " . $e->getMessage());
    //             Log::error("Error fetching data for {$food->title}: " . $e->getMessage());
    //         }
    //     }
    
    //     $this->info('✅ Food nutrition details updated successfully.');
    // }
    
}
