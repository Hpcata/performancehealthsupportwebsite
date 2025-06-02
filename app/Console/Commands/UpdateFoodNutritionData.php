<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Item;
use Log;

class UpdateFoodNutritionData extends Command
{
    protected $signature = 'update:food-nutrition-data';
    protected $description = 'Fetch food details from Woolworths API and update serving sizes, total quantity, and servings per qty';

    public function handle()
    {
        $foods = Item::whereNull('energy')->get(); // You can filter this if needed

        foreach ($foods as $food) {
            try {
                $product = json_decode($food->woolworth_json, true);

                if (!is_array($product)) {
                    $this->warn("⚠️ Invalid JSON for item ID {$food->id}");
                    continue;
                }

                $additionalAttributes = $product['AdditionalAttributes'] ?? [];
                $nutrition = [];

                if (isset($additionalAttributes['nutritionalinformation'])) {
                    $nutritionInfo = json_decode($additionalAttributes['nutritionalinformation'], true);
                    $attributes = $nutritionInfo['Attributes'] ?? [];

                    foreach ($attributes as $attribute) {
                        $name = $attribute['Name'] ?? '';
                        $value = $attribute['Value'] ?? null;

                        $parsed = $this->parseValueWithUnit($value);

                        switch ($name) {
                            case 'Energy kJ Quantity Per Serve - Total - NIP':
                                $nutrition['energy'] = $parsed;
                                break;
                            case 'Fat Saturated Quantity Per Serve - Total - NIP':
                                $nutrition['saturated'] = $parsed;
                                break;
                            case 'Sugars Quantity Per Serve - Total - NIP':
                                $nutrition['sugars'] = $parsed;
                                break;
                            case 'Dietary Fibre Quantity Per Serve - Total - NIP':
                                $nutrition['dietary_fibre'] = $parsed;
                                break;
                            case 'Sodium Quantity Per Serve - Total - NIP':
                                $nutrition['sodium'] = $parsed;
                                break;
                        }
                    }
                }

                // Set the final values (value + unit as string)
                $food->energy = $nutrition['energy'] ?? null;
                $food->saturated = $nutrition['saturated'] ?? null;
                $food->sugars = $nutrition['sugars'] ?? null;
                $food->dietary_fibre = $nutrition['dietary_fibre'] ?? null;
                $food->sodium = $nutrition['sodium'] ?? null;

                $food->save();

                $this->info("✅ Updated item ID {$food->id}: {$food->title}");

            } catch (\Exception $e) {
                $this->error("❌ Error for ID {$food->id} - {$food->title}: " . $e->getMessage());
                Log::error("Error for ID {$food->id} - {$food->title}: " . $e->getMessage());
            }
        }

        $this->info('✅ Food nutrition details updated successfully.');
    }

    private function parseValueWithUnit($value)
    {
        if (!$value || !is_string($value)) {
            return null;
        }

        $value = str_ireplace('Approx.', '', $value); // remove 'Approx.'
        $value = trim($value);

        // Keep only "number + unit"
        preg_match('/([0-9]+(?:\.[0-9]+)?)\s*([a-zA-Zμ]+)/', $value, $matches);

        if (isset($matches[1]) && isset($matches[2])) {
            return $matches[1] . $matches[2]; // like "786.5kJ"
        }

        return null; // fallback if parsing fails
    }

}
