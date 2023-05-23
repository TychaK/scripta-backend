<?php

namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait ArticlesTrait
{
    public function fetch($url)
    {

        $client = new Client();

        try {

            $response = $client->request('GET', $url, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $responseData = json_decode(($response->getBody()->getContents()));

            Log::info("Received response from api call  ... " . json_encode($responseData));

            return $responseData;

        } catch (\GuzzleHttp\Exception\RequestException $exception) {

            Log::info("Encountered exception trying to get data ...");

            Log::info($exception->getResponse()->getBody());

            return [];
        }

    }
}
