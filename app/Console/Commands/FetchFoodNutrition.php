<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item; // Assuming your food table model is Food
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FetchFoodNutrition extends Command
{
    protected $signature = 'nutrition:fetch';
    protected $description = 'Fetch and update nutrition info for foods using OpenAI';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $foods = Item::where(function ($query) {
                    $query->where('protein', 0.00)
                        ->where('carbs', 0.00)
                        ->where('fat', 0.00);
                })
                ->get();

        if ($foods->isEmpty()) {
            $this->info('No foods need updating.');
            return;
        }

        // $client = new Client();

        foreach ($foods as $food) {
            $this->info("Fetching data for: {$food->title}");

            $nutritionData = $this->fetchFromOpenAI($food->title);

            if (!empty($nutritionData)) {
                $food->update([
                    'protein' => (float) str_replace('g', '', $nutritionData['protein']),
                    'carbs' => (float) str_replace('g', '', $nutritionData['carbs']),
                    'fat' => (float) str_replace('g', '', $nutritionData['fat']),
                    'serving_size' => (float) str_replace('g', '', $nutritionData['serving_size']),
                    'serving_size_unit' => $nutritionData['serving_size_unit'],
                    'servings_per_pack' => $nutritionData['servings_per_pack'],
                ]);

                $this->info("Updated : {$food->id} - {$food->title}");
            } else {
                $this->error("Failed to update: {$food->title}");
            }
        }
    }

    private function fetchFromOpenAI($title)
    {
        try {
            $client = new Client();
            $prompt = "
            You are a nutrition expert. Given only a food name, estimate its standard serving size and macronutrient breakdown.

            **Food Name**: $title  

            Provide a response in **valid JSON format**:
            {
                \"protein\": value_in_grams,
                \"carbs\": value_in_grams,
                \"fat\": value_in_grams,
                \"serving_size\": standard_serving_size_in_grams_or_milliliters,
                \"serving_size_unit\": \"g\" or \"ml\",
                \"servings_per_pack\": estimated_number_of_servings
            }
            ";

            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer '. env('OPENAI_API_KEY'),
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

            $result = json_decode($response->getBody(), true);

            if (!isset($result['choices'][0]['message']['content'])) {
                throw new \Exception('Invalid OpenAI response format.');
            }

            $responseText = trim($result['choices'][0]['message']['content']);
            $jsonStart = strpos($responseText, '{');
            $jsonEnd = strrpos($responseText, '}');

            if ($jsonStart === false || $jsonEnd === false) {
                throw new \Exception('Invalid JSON format received.');
            }

            $responseText = substr($responseText, $jsonStart, ($jsonEnd - $jsonStart + 1));
            $parsedResponse = json_decode($responseText, true);

            if (!isset($parsedResponse['protein'], $parsedResponse['carbs'], $parsedResponse['fat'])) {
                throw new \Exception('Missing macronutrient values in response.');
            }

            return $parsedResponse;

        } catch (\Exception $e) {
            Log::error("OpenAI API Error: " . $e->getMessage());
            return [];
        }
    }
}
