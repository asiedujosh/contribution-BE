<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait SmsTrait
{
    public function sendSms($phoneNumber, $message)
    {
        try {
            // Retrieve the API key from the environment
            $apiKey = env('ARKESEL_API_KEY');

            // Initialize Guzzle client
            $client = new Client();

            // Make a GET request to the Arkesel SMS API
            $response = $client->request('GET', 'https://sms.arkesel.com/sms/api', [
                'query' => [
                    'action' => 'send-sms',
                    'api_key' => $apiKey,
                    'to' => $phoneNumber,
                    'from' => 'AFUN ROYAL FAMILY',
                    'sms' => $message,
                ],
                'verify' => false, // Disable SSL verification
            ]);

            // Get the response body
            $responseData = $response->getBody()->getContents();

            return response()->json(['success' => true, 'data' => $responseData]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
