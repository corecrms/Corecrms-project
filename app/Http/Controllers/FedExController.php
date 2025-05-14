<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\Customer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Services\FedExService;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class FedExController extends Controller
{


    // public function authorizeFedEx()
    // {
    //     $client = new Client();
    //     $response = $client->request('POST', config('fedex.api_url') . '/oauth/token', [
    //         'headers' => [
    //             'Content-Type' => 'application/x-www-form-urlencoded',
    //         ],
    //         'form_params' => [
    //             'grant_type' => 'client_credentials',
    //             'client_id' => config('fedex.client_key'),
    //             'client_secret' => config('fedex.client_secret'),
    //         ],
    //     ]);

    //     $data = json_decode($response->getBody(), true);

    //     if (isset($data['access_token'])) {
    //         return response()->json(['access_token' => $data['access_token']]);
    //     } else {
    //         return response()->json(['error' => 'Authorization failed'], 401);
    //     }
    // }

    protected $fedExService;

    public function __construct(FedExService $fedExService)
    {
        $this->fedExService = $fedExService;
    }

    public function createShipment(Request $request)
    {
        $shipmentDetails = $this->prepareShipmentDetails($request);

        $response = $this->fedExService->createShipment($shipmentDetails);

        return response()->json($response);
    }

    private function prepareShipmentDetails($request)
    {
        // return $shipmentDetails = [
        //     "accountNumber" => [
        //         "value" => config('fedex.account_number')
        //     ],
        //     "requestedShipment" => [
        //         "shipper" => [
        //             "address" => [
        //                 "streetLines" => ["Street 1", "Street 2"],
        //                 "city" => "City",
        //                 "stateOrProvinceCode" => "ST",
        //                 "postalCode" => "12345",
        //                 "countryCode" => "US"
        //             ]
        //         ],
        //         "recipient" => [
        //             "address" => [
        //                 "streetLines" => ["Street 1", "Street 2"],
        //                 "city" => "City",
        //                 "stateOrProvinceCode" => "ST",
        //                 "postalCode" => "12345",
        //                 "countryCode" => "US"
        //             ]
        //         ],
        //         "shippingChargesPayment" => [
        //             "paymentType" => "SENDER",
        //             "payor" => [
        //                 "responsibleParty" => [
        //                     "accountNumber" => [
        //                         "value" => config('fedex.account_number')
        //                     ]
        //                 ]
        //             ]
        //         ],
        //         "labelSpecification" => [
        //             "imageType" => "PDF",
        //             "labelStockType" => "PAPER_7X4.75"
        //         ],
        //         "rateRequestTypes" => ["ACCOUNT"],
        //         "packageCount" => 1,
        //         "requestedPackageLineItems" => [
        //             [
        //                 "weight" => [
        //                     "units" => "LB",
        //                     "value" => 50.0
        //                 ],
        //                 "dimensions" => [
        //                     "length" => 108,
        //                     "width" => 5,
        //                     "height" => 5,
        //                     "units" => "IN"
        //                 ]
        //             ]
        //         ]
        //     ]
        // ];


        return $shipmentData = [
            "labelResponseOptions" => "URL_ONLY",
            "requestedShipment" => [
                "shipper" => [
                    "contact" => [
                        "personName" => "Warehouse",
                        "phoneNumber" => "1234567890",
                        "companyName" => "Sale&Purchase",
                    ],
                    "address" => [
                        "streetLines" => ["SHIPPER STREET LINE 1"],
                        "city" => "HARRISON",
                        "stateOrProvinceCode" => "AR",
                        "postalCode" => "72601",
                        "countryCode" => "US",
                    ],
                ],
                "recipients" => [
                    [
                        "contact" => [
                            "personName" => "Muneer",
                            "phoneNumber" => "1234567890",
                            "companyName" => "Home",
                        ],
                        "address" => [
                            "streetLines" => ["RECIPIENT STREET LINE 1"],
                            "city" => "Collierville",
                            "stateOrProvinceCode" => "TN",
                            "postalCode" => "38017",
                            "countryCode" => "US",
                        ],
                    ],
                ],
                "shipDatestamp" => "2024-08-23",  // Use current or future date
                "serviceType" => "STANDARD_OVERNIGHT",
                "packagingType" => "FEDEX_SMALL_BOX",
                "pickupType" => "USE_SCHEDULED_PICKUP",
                "blockInsightVisibility" => false,
                "shippingChargesPayment" => [
                    "paymentType" => "SENDER",
                ],
                "shipmentSpecialServices" => [
                    "specialServiceTypes" => ["FEDEX_ONE_RATE"],
                ],
                "labelSpecification" => [
                    "imageType" => "PDF",
                    "labelStockType" => "PAPER_85X11_TOP_HALF_LABEL",
                ],
                "requestedPackageLineItems" => [
                    [
                        "weight" => [
                            "units" => "LB",
                            "value" => 10.0,  // Example weight
                        ],
                        "dimensions" => [
                            "length" => 12,
                            "width" => 12,
                            "height" => 12,
                            "units" => "IN",
                        ],
                    ],
                ],
            ],
            "accountNumber" => [
                "value" => config('fedex.account_number'),
            ],
        ];
    }


    public function calculateRates(Request $request)
    {

        $customerId = $request->input('customer_id');
        $warehouseId = $request->input('warehouse_id');
        $package_weight_unit = $request->input('package_weight_unit');
        $package_weight = $request->input('package_weight');
        $package_dimension_unit = $request->input('package_dimension_unit');
        $package_length = $request->input('package_length');
        $package_width = $request->input('package_width');
        $package_height = $request->input('package_height');
        $shipping_method = $request->input('shipping_method');


        $customer = Customer::findOrFail($customerId);
        $warehouse = Warehouse::findOrFail($warehouseId);
        $data = $request->all();
        $data['s_postalCode'] = $customer->user->postal_code ?? null;
        $data['s_countryCode'] = $customer->user->country_code ?? null;
        $data['r_postalCode'] = $warehouse->zip_code ?? null;
        $data['r_countryCode'] = $warehouse->users->country_code ?? null;

        $shipmentDetails = [
            "accountNumber" => [
                "value" => config('fedex.account_number')
            ],
            "requestedShipment" => [
                "shipper" => [
                    "address" => [
                        "postalCode" => $data['s_postalCode'],
                        "countryCode" => $data['s_countryCode']
                    ]
                ],
                "recipient" => [
                    "address" => [
                        "postalCode" => $data['r_postalCode'],
                        "countryCode" => $data['r_countryCode']
                    ]
                ],
                'serviceType' =>  $shipping_method ?? 'FEDEX_GROUND',
                "pickupType" => "DROPOFF_AT_FEDEX_LOCATION",
                "rateRequestType" => [
                    "ACCOUNT",
                    // "LIST"
                ],
                "requestedPackageLineItems" => [
                    [
                        "weight" => [
                            "units" => $package_weight_unit ?? 'LB',
                            "value" => $package_weight ?? 10,
                        ],
                        "dimensions" => [
                            "length" => $package_length ?? 12,
                            "width" => $package_width ?? 12,
                            "height" => $package_height ?? 12,
                            "units" => $package_dimension_unit ?? 'IN',
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->fedExService->calculateRatesQuotes($shipmentDetails);

        return $response;
    }


    public function calculateRatesOnClientSide(Request $request)
    {


        // dd($request->all());
        $customerPostalCode = $request->input('customer_postal_code');
        $customerCountryCode = $request->input('customer_country_code');
        $warehouseId = $request->input('warehouse_id');

        $warehouse = Warehouse::findOrFail($warehouseId);
        $data = $request->all();

        $data['s_postalCode'] = $warehouse->zip_code ?? null;
        $data['s_countryCode'] = $warehouse->users->country_code ?? null;

        $data['r_postalCode'] = $customerPostalCode ?? null;
        $data['r_countryCode'] = $customerCountryCode ?? null;



        $shipmentDetails = [
            "accountNumber" => [
                "value" => config('fedex.account_number')
            ],
            "requestedShipment" => [
                "shipper" => [
                    "address" => [
                        "postalCode" => $data['s_postalCode'],
                        "countryCode" => $data['s_countryCode']
                    ]
                ],
                "recipient" => [
                    "address" => [
                        "postalCode" => $data['r_postalCode'],
                        "countryCode" => $data['r_countryCode']
                    ]
                ],
                "pickupType" => "DROPOFF_AT_FEDEX_LOCATION",
                "rateRequestType" => [
                    "ACCOUNT",
                    // "LIST"
                ],
                "requestedPackageLineItems" => [
                    [
                        "weight" => [
                            "units" => "LB",
                            "value" => 10
                        ]
                    ]
                ]
            ]
        ];

        // return $shipmentDetails;


        $response = $this->fedExService->calculateRates($shipmentDetails);

        return $response;
    }



    // public function trackShipment(Request $request)
    // {
    //     $trackingNumber = $request->input('tracking_number');

    //     $response = $this->fedExService->trackShipment($trackingNumber);

    //     return response()->json($response);
    // }


    public function addressValidation(Request $request)
    {
        $address = $request['address'] ?? '';
        $maxLength = 35; // Maximum allowed length for each street line

        // Split the address into multiple lines if it exceeds the maximum length
        $r_streetLines = strlen($address) > $maxLength ? str_split($address, $maxLength) : [$address];

        $payload = [
            "addressesToValidate" => [
                [
                    "address" => [
                        "streetLines" => $r_streetLines,
                        "city" => $request['city'],
                        "stateOrProvinceCode" => $request['state_code'],
                        "postalCode" => $request['postal_code'],
                        "countryCode" => $request['country_code'],
                    ]
                ]
            ]
        ];


        // dd($payload);


        $response = $this->fedExService->addressValidation($payload);

        // return response()->json($response);
        return $response;
        // return $this->handleAddressValidationResponse($response, $payload);
    }


    public function postalCodeValidation(Request $request)
    {
        $postalCode = $request['postal_code'] ?? '';
        $countryCode = $request['country_code'] ?? '';
        $state_code = $request['state_code'] ?? '';

        $payload = [
            "carrierCode" => "FDXG",
            "countryCode" => $countryCode,
            "stateOrProvinceCode" => $state_code,
            "postalCode" => $postalCode,
            "shipDate" => date('Y-m-d'),
        ];

        $response = $this->fedExService->postalCodeValidation($payload);

        return $response;

    }

    protected function handleAddressValidationResponse($apiResponse, $originalPayload)
    {
        $statusCode = $apiResponse['status'] ?? 500;
        $responseBody = $apiResponse['data']['output'] ?? [];

        if ($statusCode === 200 && isset($responseBody['resolvedAddresses'])) {
            $resolvedAddresses = $responseBody['resolvedAddresses'];
            $originalAddress = $originalPayload['addressesToValidate'][0]['address'];

            foreach ($resolvedAddresses as $resolvedAddress) {
                if (
                    $resolvedAddress['stateOrProvinceCode'] !== $originalAddress['stateOrProvinceCode'] ||
                    $resolvedAddress['postalCode'] !== $originalAddress['postalCode']
                ) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Address validation failed due to mismatch in state or postal code.',
                        'resolvedAddress' => $resolvedAddress,
                        'alerts' => $responseBody['alerts'] ?? [],
                    ], 422);
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Address validation successful.',
                'data' => $apiResponse['data'],
            ]);
        } else {
            return response()->json([
                'status' => $statusCode,
                'message' => 'Error validating address.',
                'details' => $responseBody,
            ], $statusCode);
        }
    }
}
