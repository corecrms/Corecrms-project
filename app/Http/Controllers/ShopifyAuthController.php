<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ShopifyStore;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ShopifyAuthController extends Controller
{
    public function redirectToShopify()
    {
        // dd(request('shop'));
        $shopUrl = request('shop'); // Make sure 'shop' parameter is passed in the query string

        $apiKey = env('SHOPIFY_API_KEY');
        // dd($apiKey);
        if (!$apiKey) {
            return "API key is missing."; // Or handle this scenario appropriately
        }
        $scopes = 'write_products,read_products,write_orders,read_orders';
        $redirectUri = route('shopify.callback');

        $authUrl = "https://lara-app-1234.myshopify.com/admin/oauth/authorize?client_id={$apiKey}&scope={$scopes}&redirect_uri={$redirectUri}&response_type=code";
        // dd($authUrl);
        return redirect($authUrl);
    }

    public function handleCallback(Request $request)
    {
        $shopUrl = $request->input('shop');
        $code = $request->input('code');
        $apiKey = env('SHOPIFY_API_KEY');
        $apiSecret = env('SHOPIFY_API_SECRET');
        $user_id = Auth::id();
        // dd($user_id);
        $response = Http::post("https://{$shopUrl}/admin/oauth/access_token", [
            'client_id' => $apiKey,
            'client_secret' => $apiSecret,
            'code' => $code,
        ]);
        // dd($response);
        $accessToken = $response->json()['access_token'];
        // dd($accessToken);
        // Here you should save the access token to your database associated with the shop URL

        // Store or update the token in the database
        ShopifyStore::updateOrCreate(
            ['shop_domain' => $shopUrl],
            ['access_token' => $accessToken, 'user_id' => $user_id]
        );

        return redirect('/')->with('success', 'Shopify store connected successfully.');
    }

}
