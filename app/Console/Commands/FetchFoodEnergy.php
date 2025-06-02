<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FetchFoodEnergy extends Command
{
    protected $signature = 'nutrition:fetch-energy';
    protected $description = 'Fetch and update energy info (kJ) for foods using OpenAI based on serving size';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $foods = Item::whereNull('energy')
            ->whereNotNull('serving_size')
            ->whereNotNull('serving_size_unit')
            // ->where('id', 126)
            ->get();
        // dd($foods->count());
        if ($foods->isEmpty()) {
            $this->info('No foods need updating.');
            return;
        }

        foreach ($foods as $food) {
            $this->info("Fetching energy for: {$food->id} {$food->title}");

            $energy = $this->fetchEnergyFromOpenAI($food->title, $food->serving_size, $food->serving_size_unit);
           
            if ($energy !== null) {
                $food->update(['energy' => round($energy, 2).'kJ']);
                $this->info("Updated: {$food->id} - {$food->title} (Energy: {$energy} kJ)");
            } else {
                $this->error("Failed to update energy for: {$food->title}");
            }
        }
    }

    private function fetchEnergyFromOpenAI($title, $servingSize, $unit)
    {
        try {
            $client = new \GuzzleHttp\Client();

            // $prompt = "
            //     Using the Australian the afcd food standards website or similar credible information, calculate the energy for the food stated and according to its serving size

            //     Food name: {$title}  
            //     Serving size: {$servingSize} {$unit}

            //     Please respond with valid JSON only, in this format:
            //     {
            //     \"energy\": energy_in_kJ
            //     }
            //     ";

            $prompt = "Using the Australian https://afcd.foodstandards.gov.au/ website or similar credible information, calculate the energy for the food stated and according to its serving size

                Food name: {$title}  
                Serving size: {$servingSize} {$unit}

                Please respond with valid JSON only, in this format:
                {
                \"energy\": energy_in_kJ
                }
                ";

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
                    'max_tokens' => 150,
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            if (!isset($result['choices'][0]['message']['content'])) {
                throw new \Exception('Invalid OpenAI response format.');
            }

            $responseText = trim($result['choices'][0]['message']['content']);

            // Log full response for debugging (optional)
            Log::info("OpenAI response for '{$title}': " . $responseText);

            // Extract JSON block from the response using regex
            preg_match('/\{\s*"energy"\s*:\s*[\d.]+\s*\}/', $responseText, $matches);

            if (!isset($matches[0])) {
                throw new \Exception('Could not extract valid JSON from OpenAI response.');
            }

            $parsed = json_decode($matches[0], true);

            if (!isset($parsed['energy'])) {
                throw new \Exception('Energy value not found in JSON.');
            }

            return (float) $parsed['energy'];

        } catch (\Exception $e) {
            Log::error("OpenAI API error for '{$title}': " . $e->getMessage());
            return null;
        }
    }

}
