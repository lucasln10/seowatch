<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class PageSpeedService
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('PAGESPEED_API_KEY');
    }

    public function runAnalysis($url, $strategy = 'desktop')
    {
        try {
            $response = $this->client->get('https://www.googleapis.com/pagespeedonline/v5/runPagespeed', [
                'query' => [
                    'url' => $url,
                    'key' => $this->apiKey,
                    'strategy' => $strategy ,
                ],
                'verify' => false,
            ]);

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            return 'Erro na requisição da API.' . $e->getMessage();
        }
    }

}