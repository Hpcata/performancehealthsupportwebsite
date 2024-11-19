<?php

namespace App\Services;

use Log;
use Illuminate\Support\Facades\Http;

class StripeService
{
    protected $url, $headers;

    public function __construct()
    {
        $this->url = config('constant.STRIPE_URL');
        $this->headers = [
            'Authorization' => 'Bearer ' . config('constant.STRIPE_SECRET'),
        ];
    }

    public function createCheckOutSession($paymentData)
    {
        try {
            $body = [
                'line_items' => $paymentData,
                'mode' => 'payment',
                'success_url' => route('payment.success'),
            ];

            $response = Http::withHeaders($this->headers)->asForm()->post($this->url, $body);
            return $this->prepareResonse($response);
        } catch (Exception $e) {
            Log::error($e->gerMessage());
            return false;
        }
    }

    public function prepareResonse($response)
    {
        if($response->getStatusCode() == 200) {
            return [
                'status' => true,
                'data' => $response->json(),
            ];
        } else {
            return [
                'status' => false,
                'data' => $response->json(),
            ];
        }
    }
}
