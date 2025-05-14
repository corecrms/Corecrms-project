<?php

namespace App\Http\Controllers\Admin;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;

class EasyshipApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('back.shipment.easyship.index');

        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://api.easyship.com/2023-01/shipments",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS =>"{\n    \"origin_address\": \"HK\",\n    \"destination_country_alpha2\": \"US\",\n    \"destination_postal_code\": \"10001\",\n    \"taxes_duties_paid_by\": \"Sender\",\n    \"is_insured\": false,\n    \"items\": [\n        {\n            \"actual_weight\": 0.5,\n            \"height\": 5,\n            \"width\": 5,\n            \"length\": 5,\n            \"category\": \"mobiles\",\n            \"declared_currency\": \"USD\",\n            \"declared_customs_value\": 200\n        }\n    ]\n}",
        //     CURLOPT_HTTPHEADER => array(
        //         "Content-Type: application/json",
        //         "Authorization: Bearer prod_7BdvYJbNSKV5R7Bo8jtkQ8M10ldY7lObKnw5nVSutqo="
        //     ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        // return $response;

        // $client = new Client();
        // $access_token = "prod_7BdvYJbNSKV5R7Bo8jtkQ8M10ldY7lObKnw5nVSutqo=";
        // try {
        //     $response = $client->request('POST', 'https://api.easyship.com/2023-01/shipments', [
        //         'headers' => [
        //             'Content-Type' => 'application/json',
        //             'Authorization' => 'Bearer ' . $access_token
        //         ],
        //         // 'body' => json_encode(['product' => $productData]),
        //         'body' => '{"origin_address":{"line_1":"Canada Town","line_2":"Block 3","state":"Yuen Long","city":"Hong Kong","postal_code":"0000","country_alpha2":"HK","contact_name":"Foo Bar","company_name":"Test Plc.","contact_phone":"+852-3008-5678","contact_email":"asd@asd.com"},"destination_address":{"line_1":"Kennedy Town","line_2":"Block 3","state":"Yuen Long","city":"Hong Kong","postal_code":"0000","country_alpha2":"HK","contact_name":"Foo Bar","company_name":"Test Plc.","contact_phone":"+852-3008-5678","contact_email":"asd@asd.com"},"regulatory_identifiers":{"eori":"DE 123456789 12345","ioss":"IM1234567890","vat_number":"EU1234567890"},"buyer_regulatory_identifiers":{"ein":"12-3456789","vat_number":"EU1234567890"},"incoterms":"DDU","insurance":{"is_insured":false},"order_data":{"buyer_selected_courier_name":"test_courier","platform_name":"test plat_form","order_created_at":"2024-01-31T18:00:00Z"},"courier_selection":{"allow_courier_fallback":false,"apply_shipping_rules":true},"shipping_settings":{"additional_services":{"qr_code":"none"},"units":{"weight":"kg","dimensions":"cm"},"buy_label":false,"buy_label_synchronous":false,"printing_options":{"format":"png","label":"4x6","commercial_invoice":"A4","packing_slip":"4x6"}},"parcels":[{"total_actual_weight":1,"box":null,"items":[{"description":"item","category":null,"hs_code":"123456","contains_battery_pi966":true,"contains_battery_pi967":true,"contains_liquids":true,"sku":"sku","origin_country_alpha2":"HK","quantity":2,"dimensions":{"length":1,"width":2,"height":3},"actual_weight":10,"declared_currency":"USD","declared_customs_value":20}]}]}',

        //         // 'body' => {"incoterms":"DDU","insurance":{"is_insured":false},"courier_selection":{"allow_courier_fallback":false,"apply_shipping_rules":true},"shipping_settings":{"additional_services":{"qr_code":"none"},"units":{"weight":"kg","dimensions":"cm"},"buy_label":false,"buy_label_synchronous":false,"printing_options":{"format":"png","label":"4x6","commercial_invoice":"A4","packing_slip":"4x6"}}}',
        //     ]);
        //     // $response = $client->request('POST', 'https://api.easyship.com/2023-01/shipments', [
        //     //     'headers' => [
        //     //         'Content-Type' => 'application/json',
        //     //         'Authorization' => 'Bearer ' . $access_token,
        //     //     ],
        //     //     'json' => [
        //     //         "incoterms" => "DDU",
        //     //         "insurance" => [
        //     //             "is_insured" => false,
        //     //         ],
        //     //         "courier_selection" => [
        //     //             "allow_courier_fallback" => false,
        //     //             "apply_shipping_rules" => true,
        //     //         ],
        //     //         "shipping_settings" => [
        //     //             "additional_services" => [
        //     //                 "qr_code" => "none",
        //     //             ],
        //     //             "units" => [
        //     //                 "weight" => "kg",
        //     //                 "dimensions" => "cm",
        //     //             ],
        //     //             "buy_label" => false,
        //     //             "buy_label_synchronous" => false,
        //     //             "printing_options" => [
        //     //                 "format" => "png",
        //     //                 "label" => "4x6",
        //     //                 "commercial_invoice" => "A4",
        //     //                 "packing_slip" => "4x6",
        //     //             ],
        //     //         ],
        //     //     ],
        //     // ]);

        //     $res = json_decode($response->getBody()->getContents(), true);
        //     // dd($res);
        //     return $res;
        // } catch (\Exception $e) {
        //     // return  $e->getMessage();
        //     return $e;
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.shipment.easyship.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $client = new Client();
        $access_token = "prod_7BdvYJbNSKV5R7Bo8jtkQ8M10ldY7lObKnw5nVSutqo=";
        try {
            // $response = $client->request('POST','https://api.easyship.com/2023-01/shipments',[
            //     'headers' => [
            //         'Content-Type' => 'application/json',
            //         'Authorization' => 'Bearer '.$access_token
            //     ],
            //     'body' => json_encode(['product' => $productData]),
            //     // 'body' => {"incoterms":"DDU","insurance":{"is_insured":false},"courier_selection":{"allow_courier_fallback":false,"apply_shipping_rules":true},"shipping_settings":{"additional_services":{"qr_code":"none"},"units":{"weight":"kg","dimensions":"cm"},"buy_label":false,"buy_label_synchronous":false,"printing_options":{"format":"png","label":"4x6","commercial_invoice":"A4","packing_slip":"4x6"}}}',
            // ]);
            $response = $client->request('POST', 'https://api.easyship.com/2023-01/shipments', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token,
                ],
                'json' => $data,
            ]);

            $res = json_decode($response->getBody()->getContents(), true);
            // dd($res);
            return response()->json($res);
        }  catch (ClientException $e) {
            // Output full error response
            $responseBody = $e->getResponse()->getBody()->getContents();
            return response()->json(['error' => $responseBody], 422);
        } catch (\Exception $e) {
            // General exception handling
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

            $client = new Client();
            $access_token = "prod_7BdvYJbNSKV5R7Bo8jtkQ8M10ldY7lObKnw5nVSutqo=";
            try {
                $response = $client->request('GET', 'https://api.easyship.com/2023-01/shipments/' . $id, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $access_token,
                    ],
                ]);
                $shipment = json_decode($response->getBody()->getContents(), true);
                // dd($shipment['shipment']);
                return view('back.shipment.easyship.edit', compact('shipment'));
            } catch (ClientException $e) {
                // Output full error response
                $responseBody = $e->getResponse()->getBody()->getContents();
                return response()->json(['error' => $responseBody], 422);
            } catch (\Exception $e) {
                // General exception handling
                return response()->json(['error' => $e->getMessage()], 500);
            }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
