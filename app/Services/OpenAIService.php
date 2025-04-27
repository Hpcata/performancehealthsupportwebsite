<?php

namespace App\Services;

use GuzzleHttp\Client;
use Log;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer '. config('services.openai.key'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function generateImage($prompt, $size = '1024x1024')
    {
        try {
            // Refine the prompt for better results
            $refinedPrompt = $this->refinePrompt($prompt);

            $response = $this->client->post('images/generations', [
                'json' => [
                    'prompt' => $refinedPrompt,
                    'n' => 1,
                    'size' => $size,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['data'][0]['url'] ?? null;
        } catch (\Exception $e) {
            Log::error('OpenAI Image Generation Error: ' . $e->getMessage());
            return null;
        }
    }

    private function refinePrompt($prompt)
    {
        return $prompt . ", photorealistic, high resolution, vibrant colors, artistic composition, highly detailed";
    }
}
