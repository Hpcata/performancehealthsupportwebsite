<?php

namespace App\Services;

class JsonService
{
    // send JSON request
    public function sendRequest($status, $data, $code)
    {
        $response = [
            'status' => $status,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }
}
