<?php

namespace App\Http\Controllers\Admin;

use Log;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Services\FedExService;
use App\Http\Controllers\Controller;
use App\Jobs\SendTrackingNoJob;
// use Illuminate\Support\Facades\Log;

class ManualShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $fedExService;
    public function __construct(FedExService $fedExService)
    {
        $this->fedExService = $fedExService;
    }

    public function index()
    {
        // Check if the user has the 'Cashier' or 'Manager' role
        if (auth()->user()->hasRole('Cashier') || auth()->user()->hasRole('Manager')) {
            $warehouseId = auth()->user()->warehouse_id;
            $sales = Sale::where('warehouse_id', $warehouseId)
                ->latest()->get();
        } else {

            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $sales = Sale::where('warehouse_id', $warehouseId)
                    ->where('shipping_method', '!=', 'Store Pickup')
                    ->latest()->get();
            } else {

                $sales = Sale::where('shipping_method', '!=', 'Store Pickup')
                    ->latest()->get();
            }
        }

        // Eager load related customer and warehouse data
        $sales->load(['customer', 'warehouse']);


        // Return the view with the compacted data
        return view('back.shipment.fedex.index', compact('sales'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sale = Sale::where('tracking_number', null)->get();
        $sale->load('customer');
        $sale->load('warehouse');

        return view('back.shipment.fedex.create', compact('sale'));
    }



    public function createShipment($id)
    {
        $sale = Sale::where('tracking_number', null)->where('id', $id)->first();
        $sale->load('customer');
        $sale->load('warehouse');

        return view('back.shipment.fedex.create', compact('sale'));
    }

    public function storeShipment(Request $request)
    {
        // dd($request->all());

        $sale = Sale::with('customer', 'warehouse')->find($request->sale_id);

        $sale->request = $request->all();


        try {
            $createShippmentResponse =  $this->createFedexShipment($sale, $sale->customer, $sale->warehouse);
            // dd($createShippmentResponse);

            if ($createShippmentResponse['status'] === 'success') {
                $newSale = Sale::find($sale->id);

                $newSale->shipping_method = $request->service_type;
                $newSale->tracking_number = $createShippmentResponse['data']['output']['transactionShipments'][0]['masterTrackingNumber'];

                // Extract and store shipping label URL
                $shippingLabelUrl = $createShippmentResponse['data']['output']['transactionShipments'][0]['pieceResponses'][0]['packageDocuments'][0]['url'];
                $newSale->shipping_label = $shippingLabelUrl;

                // Extract and store shipping fee (totalNetCharge)
                $shippingFee = $createShippmentResponse['data']['output']['transactionShipments'][0]['completedShipmentDetail']['shipmentRating']['shipmentRateDetails'][0]['totalNetCharge'];
                // $newSale->shipping_fee = $shippingFee;
                $newSale->save();

                try {
                    $job = new SendTrackingNoJob($sale->customer->user->email, $sale->customer->user->name, $sale->reference, $newSale->tracking_number);
                    dispatch($job);
                    Log::info('Email sent to: ' . $sale->customer->user->email);
                } catch (\Exception $e) {
                    Log::error('Error sending email: ' . $e->getMessage());
                }

                return redirect()->route('shipment.index')->with('success', 'Shipment created successfully.');
            } else {

                // Log the specific error returned by the FedEx API for debugging purposes
                Log::error('FedEx API Error: ', $createShippmentResponse['errors'] ?? []);

                // Retrieve the error code and message from the FedEx API response
                $errorDetails = $createShippmentResponse['errors'][0] ?? [];
                $errorCode = $errorDetails['code'] ?? 'Unknown Error Code';
                $errorMessage = $errorDetails['message'] ?? 'Something went wrong, please try again.';

                // Format the error message
                $formattedErrorMessage = "Error Code: $errorCode<br>Message: $errorMessage";

                // Redirect back with the formatted error message
                return redirect()->back()->with('error', $formattedErrorMessage);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }


    protected function createFedexShipment($request, $customer, $warehouse)
    {
        $shipmentDetails = $this->prepareShipmentDetails($request, $customer, $warehouse);
        // dd($shipmentDetails);
        $shipmentResponse = $this->fedExService->createShipment($shipmentDetails);

        return $shipmentResponse;
    }

    private function prepareShipmentDetails($request, $customer, $warehouse)
    {
        // dd($request['shipping_method']);
        $r_address = $customer->user->address ?? '';
        $maxLength = 35; // Maximum allowed length for each street line

        // Split the address into multiple lines if it exceeds the maximum length
        $r_streetLines = strlen($r_address) > $maxLength ? str_split($r_address, $maxLength) : [$r_address];

        $s_address = $warehouse->users->address ?? '';
        $maxLength = 35; // Maximum allowed length for each street line

        // Split the address into multiple lines if it exceeds the maximum length
        $s_streetLines = strlen($s_address) > $maxLength ? str_split($s_address, $maxLength) : [$s_address];
        return $shipmentData = [
            "labelResponseOptions" => "URL_ONLY",
            "requestedShipment" => [
                "shipper" => [
                    "contact" => [
                        "personName" => $warehouse->users->name ?? "Warehouse",
                        "phoneNumber" => $warehouse->users->contact_no ?? '123456789',
                        "companyName" => getSetting()->comapny_name ?? 'Company Name',
                    ],
                    "address" => [
                        "streetLines" => $s_streetLines,
                        "city" => $warehouse->city,
                        "stateOrProvinceCode" => $warehouse->users->state_code ?? "AZ",
                        "postalCode" => $warehouse->zip_code ?? "72601",
                        "countryCode" => $warehouse->users->country_code ?? "Us",
                    ],
                ],
                "recipients" => [
                    [
                        "contact" => [
                            "personName" => $customer->user->name,
                            "phoneNumber" => $customer->user->contact_no ??  "1234567890",
                        ],
                        "address" => [
                            "streetLines" => $r_streetLines,
                            "city" => $customer->city ??  "Collierville",
                            "stateOrProvinceCode" => $customer->user->state_code ?? "FL",
                            "postalCode" => $customer->user->postal_code ?? "38017",
                            "countryCode" => $customer->user->country_code ?? "Us",
                        ],
                    ],
                ],
                "shipDatestamp" => Carbon::parse(now())->addDays(2)->format('Y-m-d'),  // Use current or future date
                "serviceType" => $request->request['service_type'] ?? 'STANDARD_OVERNIGHT', //PRIORITY_OVERNIGHT  //STANDARD_OVERNIGHT
                "packagingType" => "YOUR_PACKAGING",
                "pickupType" => "USE_SCHEDULED_PICKUP",
                "blockInsightVisibility" => false,
                "shippingChargesPayment" => [
                    "paymentType" => "SENDER",
                ],
                // "shipmentSpecialServices" => [
                //     "specialServiceTypes" => ["THIRD_PARTY_CONSIGNEE"],
                // ],
                "labelSpecification" => [
                    "imageType" => "PDF",
                    "labelStockType" => "PAPER_85X11_TOP_HALF_LABEL",
                ],
                "requestedPackageLineItems" => [
                    [
                        "weight" => [
                            "units" => $request->request['package_weight_unit'] ?? "LB",
                            "value" => $request->request['package_weight'],  // Example weight
                        ],
                        "dimensions" => [
                            "length" => $request->request['package_length'] ?? 12,
                            "width" => $request->request['package_width'] ?? 12,
                            "height" => $request->request['package_height'] ?? 12,
                            "units" => $request->request['dimension_unit'] ?? "IN",
                        ],
                    ],
                ],
            ],
            "accountNumber" => [
                "value" => config('fedex.account_number'),
            ],
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
