<?php

namespace App\Services;

use GuzzleHttp\Client;

class ShopifyService
{
    protected $client;
    protected $shopUrl;
    protected $accessToken;

    public function __construct($shopUrl, $accessToken)
    {
        // dd($shopUrl, $accessToken);
        $this->shopUrl = $shopUrl;
        $this->accessToken = $accessToken;
        $this->client = new Client([
            'base_uri' => "https://{$this->shopUrl}/",
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $this->accessToken,
            ],
        ]);
    }

    public function createProduct($productData)
    {
        try {
            $response = $this->client->request('POST', 'admin/api/2022-07/products.json', [
                'json' => ['product' => $productData]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            // Log error or handle exception
            return null;
        }
    }
}
