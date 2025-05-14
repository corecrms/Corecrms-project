<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FedExService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('fedex.api_url') ? 'https://apis-sandbox.fedex.com' : 'https://apis.fedex.com';
    }

    private function getAccessToken()
    {
        $response = $this->client->post($this->baseUrl . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => config('fedex.client_key'),
                'client_secret' => config('fedex.client_secret'),
            ],
        ]);

        $body = json_decode($response->getBody());

        return $body->access_token;
    }
    // private function getAccessToken()
    // {
    //     // Check if the token exists and hasn't expired
    //     if (Cache::has('fedex_access_token')) {
    //         return Cache::get('fedex_access_token');
    //     }

    //     // Request a new token if it doesn't exist or has expired
    //     $response = $this->client->post($this->baseUrl . '/oauth/token', [
    //         'form_params' => [
    //             'grant_type' => 'client_credentials',
    //             'client_id' => config('fedex.client_key'),
    //             'client_secret' => config('fedex.client_secret'),
    //         ],
    //     ]);

    //     $body = json_decode($response->getBody());

    //     // Store the token with an expiry time minus a buffer
    //     $expiresIn = $body->expires_in; // typically in seconds
    //     $expiresAt = now()->addSeconds($expiresIn - 60); // minus 60 seconds as a buffer

    //     Cache::put('fedex_access_token', $body->access_token, $expiresAt);

    //     return $body->access_token;
    // }


    public function createShipment($shipmentDetails)
    {
        $token = $this->getAccessToken();


        try {
            $response = $this->client->post($this->baseUrl . '/ship/v1/shipments', [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type'  => 'application/json',
                    'X-locale' => 'en_US',
                ],
                'json' => $shipmentDetails,
            ]);

            // return json_decode($response->getBody(), true);
            return [
                'status' => 'success',
                'data' => json_decode($response->getBody(), true),
            ];
        } catch (\Exception $e) {
            $responseBody = $e->getResponse()->getBody(true);
            $errorData = json_decode($responseBody, true);

            // return response()->json([
            //     'message' => 'Shipment creation failed.',
            //     'errors' => $errorData['errors'] ?? [],
            // ], 422);
            return [
                'status' => 'error',
                'message' => 'Shipment creation failed.',
                'errors' => $errorData['errors'] ?? [],
            ];
        }
    }


    // public function calculateRates($data)
    // {
    //     $token = $this->getAccessToken();

    //     $accessToken = $token ?? null;
    //     // return $accessToken;
    //     $accountNumber = config('fedex.account_number');
    //     // return $accountNumber;

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $accessToken,
    //         'Content-Type' => 'application/json',
    //     ])->post($this->baseUrl . '/rate/v1/rates/quotes', [
    //         "accountNumber" => [
    //             "value" => $accountNumber
    //         ],
    //         "requestedShipment" => [
    //             "shipper" => [
    //                 "address" => [
    //                     "postalCode" => $data['s_postalCode'],
    //                     "countryCode" => $data['s_countryCode']
    //                 ]
    //             ],
    //             "recipient" => [
    //                 "address" => [
    //                     "postalCode" => $data['r_postalCode'],
    //                     "countryCode" => $data['r_countryCode']
    //                 ]
    //             ],
    //             "pickupType" => "DROPOFF_AT_FEDEX_LOCATION",
    //             "rateRequestType" => [
    //                 "ACCOUNT",  // Rate based on your account
    //                 "LIST"      // Standard list rate
    //             ],
    //             "requestedPackageLineItems" => [
    //                 [
    //                     "weight" => [
    //                         "units" => "LB",
    //                         "value" => 3
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ]);

    //     if ($response->successful()) {
    //         $rateDetails = $response->json();
    //         // Extracting the charges and taxes from the response
    //         $totalCharges = $rateDetails['output']['transactionShipments'][0]['shipmentRating']['shipmentRateDetails'][0]['totalNetCharge'];
    //         $currency = $rateDetails['output']['transactionShipments'][0]['shipmentRating']['shipmentRateDetails'][0]['currency'];

    //         echo "Total Shipping Charges: $totalCharges $currency";
    //     } else {
    //         echo "Error: " . $response->body();
    //     }
    // }

    public function calculateRates($shipmentDetails)
    {
        $token = $this->getAccessToken();

        try {
            $response = $this->client->post($this->baseUrl . '/rate/v1/rates/quotes', [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type'  => 'application/json',
                    'X-locale' => 'en_US',
                ],
                'json' => $shipmentDetails,
            ]);

            if ($response->getStatusCode() === 200) {
                $rateDetails = json_decode($response->getBody(), true);

                // Check if 'rateReplyDetails' exists and is not empty
                if (isset($rateDetails['output']['rateReplyDetails']) && !empty($rateDetails['output']['rateReplyDetails'])) {
                    $rateReplyDetails = $rateDetails['output']['rateReplyDetails'];

                    // Loop through rateReplyDetails to extract totalNetCharge and currency
                    // dd($rateReplyDetails);
                    $charges = [];
                    foreach ($rateReplyDetails as $detail) {
                        $charges[] = [
                            'serviceType' => $detail['serviceType'] ?? 'Unknown Service',
                            'totalNetCharge' => $detail['ratedShipmentDetails'][0]['totalNetCharge'] ?? 'N/A',
                            'currency' => $detail['ratedShipmentDetails'][0]['currency'] ?? 'N/A',
                            'deliveryTimestamp' => $detail['ratedShipmentDetails'][0]['shipmentRateDetail']['deliveryTimestamp'] ?? 'N/A',
                        ];
                    }

                    return response()->json([

                        'status' => 200,
                        'message' => 'Rates retrieved successfully.',
                        'serviceType' => $rateReplyDetails[0]['serviceType'] ?? 'Unknown Service',
                        'charge' => $rateReplyDetails[0]['ratedShipmentDetails'][0]['totalNetCharge'] ?? 'N/A',
                        'charges' => $charges,
                    ]);
                } else {
                    return response()->json([
                        'message' => 'No rate information available in the response.',
                        'details' => $rateDetails,
                    ], 422);
                }
            } else {
                return response()->json([
                    'message' => 'Error fetching rates.',
                    'details' => json_decode($response->getBody(), true),
                ], $response->getStatusCode());
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle Guzzle-specific errors
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody(true) : null;
            $errorData = $responseBody ? json_decode($responseBody, true) : [];

            return response()->json([
                'message' => 'Rate calculation failed due to a request error.',
                'errors' => $errorData,
            ], 422);
        } catch (\Exception $e) {
            // Handle other types of errors
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function calculateRatesQuotes($shipmentDetails)
    {
        $token = $this->getAccessToken();

        try {
            $response = $this->client->post($this->baseUrl . '/rate/v1/rates/quotes', [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type'  => 'application/json',
                    'X-locale' => 'en_US',
                ],
                'json' => $shipmentDetails,
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);

                $charge =  $data['output']['rateReplyDetails'][0]['ratedShipmentDetails'][0]['totalNetCharge'];

                return response()->json([
                    'status' => 200,
                    'message' => 'Rates retrieved successfully.',
                    'charge' => $charge,
                    'data' => $data,
                ]);

            }
            else {
                return response()->json([
                    'message' => 'Error fetching rates.',
                    'details' => json_decode($response->getBody(), true),
                ], $response->getStatusCode());
            }

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle Guzzle-specific errors
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody(true) : null;
            $errorData = $responseBody ? json_decode($responseBody, true) : [];

            return response()->json([
                'message' => 'Rate calculation failed due to a request error.',
                'errors' => $errorData,
            ], 422);
        } catch (\Exception $e) {
            // Handle other types of errors
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }


    public function trackShipment($trackingNumber)
    {
        $token = $this->getAccessToken();

        try {
            $response = $this->client->get($this->baseUrl . '/track/v1/shipments/' . $trackingNumber, [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type'  => 'application/json',
                    'X-locale' => 'en_US',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $trackingDetails = json_decode($response->getBody(), true);

                return response()->json([
                    'status' => 200,
                    'message' => 'Shipment tracking details retrieved successfully.',
                    'data' => $trackingDetails,
                ]);
            } else {
                return response()->json([
                    'message' => 'Error fetching tracking details.',
                    'details' => json_decode($response->getBody(), true),
                ], $response->getStatusCode());
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle Guzzle-specific errors
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody(true) : null;
            $errorData = $responseBody ? json_decode($responseBody, true) : [];

            return response()->json([
                'message' => 'Shipment tracking failed due to a request error.',
                'errors' => $errorData,
            ], 422);
        } catch (\Exception $e) {
            // Handle other types of errors
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }


    public function cancelShipment($trackingNumber)
    {
        $token = $this->getAccessToken();

        try {
            $response = $this->client->delete($this->baseUrl . '/ship/v1/shipments/' . $trackingNumber, [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type'  => 'application/json',
                    'X-locale' => 'en_US',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Shipment cancelled successfully.',
                ]);
            } else {
                return response()->json([
                    'message' => 'Error cancelling shipment.',
                    'details' => json_decode($response->getBody(), true),
                ], $response->getStatusCode());
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle Guzzle-specific errors
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody(true) : null;
            $errorData = $responseBody ? json_decode($responseBody, true) : [];

            return response()->json([
                'message' => 'Shipment cancellation failed due to a request error.',
                'errors' => $errorData,
            ], 422);
        } catch (\Exception $e) {
            // Handle other types of errors
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }



    // public function addressValidation($request)
    // {
    //     $token = $this->getAccessToken();

    //     try {
    //         $response = $this->client->post($this->baseUrl . '/address/v1/addresses/resolve', [
    //             'headers' => [
    //                 'Authorization' => "Bearer {$token}",
    //                 'Content-Type'  => 'application/json',
    //                 'X-locale' => 'en_US',
    //             ],
    //             'json' => $request,
    //         ]);

    //         $statusCode = $response->getStatusCode();
    //         $responseBody = json_decode($response->getBody(), true);

    //         if ($statusCode === 200) {
    //             // Check if there's an alert and handle it
    //             if (isset($responseBody['alerts']) && !empty($responseBody['alerts'])) {
    //                 $alerts = $responseBody['alerts'];

    //                 // Handle different alert types or codes if needed
    //                 foreach ($alerts as $alert) {
    //                     if ($alert['alertType'] !== 'NOTE') {
    //                         return response()->json([
    //                             'status' => 'error',
    //                             'message' => 'Address validation failed.',
    //                             'alert' => $alert,
    //                         ], 422);
    //                     }
    //                 }
    //             }

    //             // Extract resolved address and other details
    //             $resolvedAddresses = $responseBody['resolvedAddresses'] ?? [];

    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Address validation successful.',
    //                 'data' => [
    //                     'resolvedAddresses' => $resolvedAddresses,
    //                     'alerts' => $alerts ?? [],
    //                 ],
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'message' => 'Error validating address.',
    //                 'details' => $responseBody,
    //             ], $statusCode);
    //         }
    //     } catch (\GuzzleHttp\Exception\RequestException $e) {
    //         // Handle Guzzle-specific errors
    //         $responseBody = $e->hasResponse() ? $e->getResponse()->getBody(true) : null;
    //         $errorData = $responseBody ? json_decode($responseBody, true) : [];

    //         return response()->json([
    //             'message' => 'Address validation failed due to a request error.',
    //             'errors' => $errorData,
    //         ], 422);
    //     } catch (\Exception $e) {
    //         // Handle other types of errors
    //         return response()->json([
    //             'message' => 'An unexpected error occurred.',
    //             'errors' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function addressValidation($request)
    {
        $token = $this->getAccessToken();

        try {
            $response = $this->client->post($this->baseUrl . '/address/v1/addresses/resolve', [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type'  => 'application/json',
                    'X-locale' => 'en_US',
                ],
                'json' => $request,
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody(), true);

            if ($statusCode === 200) {
                // Check for alerts or warnings in the response
                if (isset($responseBody['alerts']) && !empty($responseBody['alerts'])) {
                    $alerts = $responseBody['alerts'];

                    foreach ($alerts as $alert) {
                        // Check for any alert that is not a 'NOTE'
                        if ($alert['alertType'] !== 'NOTE') {
                            return response()->json([
                                'status' => 'error',
                                'message' => $alert['message'] ?? 'Address validation failed.',
                                'alert' => $alert,
                            ], 422);
                        }
                    }
                }

                // Extract resolved addresses and any other details
                $resolvedAddresses = $responseBody['resolvedAddresses'] ?? [];

                return response()->json([
                    'status' => 200,
                    'message' => 'Address validation successful.',
                    'data' => [
                        'resolvedAddresses' => $resolvedAddresses,
                        'alerts' => $alerts ?? [],
                    ],
                ]);
            } else {
                // Handle non-200 status codes
                return response()->json([
                    'message' => 'Error validating address.',
                    'details' => $responseBody,
                ], $statusCode);
            }



            // return [
            //     'status' => 200,
            //     'message' => 'Address validation successful.',
            //     'data' => json_decode($response->getBody(), true),
            // ];

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle Guzzle-specific errors
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody(true) : null;
            $errorData = $responseBody ? json_decode($responseBody, true) : [];

            return response()->json([
                'message' => 'Address validation failed due to a request error.',
                'errors' => $errorData,
            ], 422);
        } catch (\Exception $e) {
            // Handle other types of errors
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }


    // public function postalCodeValidation($request){
    //     $token = $this->getAccessToken();

    //     try {
    //         $response = $this->client->post($this->baseUrl . '/country/v1/postal/validate', [
    //             'headers' => [
    //                 'Authorization' => "Bearer {$token}",
    //                 'Content-Type'  => 'application/json',
    //                 'X-locale' => 'en_US',
    //             ],
    //             'json' => $request,
    //         ]);

    //         $statusCode = $response->getStatusCode();
    //         $responseBody = json_decode($response->getBody(), true);

    //         if ($statusCode === 200) {
    //             // Check if there's an alert and handle it
    //             if (isset($responseBody['alerts']) && !empty($responseBody['alerts'])) {
    //                 $alerts = $responseBody['alerts'];

    //                 // Handle different alert types or codes if needed
    //                 foreach ($alerts as $alert) {
    //                     if ($alert['alertType'] !== 'NOTE') {
    //                         return response()->json([
    //                             'status' => 'error',
    //                             'message' => 'Postal code validation failed.',
    //                             'alert' => $alert,
    //                         ], 422);
    //                     }
    //                 }
    //             }

    //             // Extract resolved address and other details
    //             $resolvedAddresses = $responseBody['resolvedAddresses'] ?? [];

    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Postal code validation successful.',
    //                 'data' => [
    //                     'resolvedAddresses' => $resolvedAddresses,
    //                     'alerts' => $alerts ?? [],
    //                 ],
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'message' => 'Error validating postal code.',
    //                 'details' => $responseBody,
    //             ], $statusCode);
    //         }
    //     } catch (\GuzzleHttp\Exception\RequestException $e) {
    //         // Handle Guzzle-specific errors
    //         $responseBody = $e->hasResponse() ? $e->getResponse()->getBody(true) : null;
    //         $errorData = $responseBody ? json_decode($responseBody, true) : [];

    //         return response()->json([
    //             'message' => 'Postal code validation failed due to a request error.',
    //             'errors' => $errorData,
    //         ], 422);
    //     } catch (\Exception $e) {
    //         // Handle other types of errors
    //         return response()->json([
    //             'message' => 'An unexpected error occurred.',
    //             'errors' => $e->getMessage(),
    //         ], 500);
    //     }

    // }

    public function postalCodeValidation($request)
    {
        $token = $this->getAccessToken();

        try {
            $response = $this->client->post($this->baseUrl . '/country/v1/postal/validate', [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type'  => 'application/json',
                    'X-locale' => 'en_US',
                ],
                'json' => $request,
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody(), true);

            if ($statusCode === 200) {
                // Handle alerts if they exist in the response
                $alerts = $responseBody['output']['alerts'] ?? [];

                foreach ($alerts as $alert) {
                    if ($alert['alertType'] !== 'NOTE') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Postal code validation failed.',
                            'alert' => $alert,
                        ], 422);
                    }
                }

                // Extract relevant information
                $resolvedAddresses = $responseBody['output']['locationDescriptions'] ?? [];
                $cleanedPostalCode = $responseBody['output']['cleanedPostalCode'] ?? null;

                return response()->json([
                    'status' => 200,
                    'message' => 'Postal code validation successful.',
                    'data' => [
                        'resolvedAddresses' => $resolvedAddresses,
                        'cleanedPostalCode' => $cleanedPostalCode,
                        'alerts' => $alerts,
                    ],
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error validating postal code.',
                    'details' => $responseBody,
                ], $statusCode);
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle Guzzle-specific errors
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody(true) : null;
            $errorData = $responseBody ? json_decode($responseBody, true) : [];

            return response()->json([
                'status' => 'error',
                'message' => 'Postal code validation failed due to a request error.',
                'errors' => $errorData,
            ], 422);
        } catch (\Exception $e) {
            // Handle other types of errors
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
