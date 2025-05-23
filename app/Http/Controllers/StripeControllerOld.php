<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\Product;
use App\Models\AddToCart;
use App\Models\ProductItem;
use App\Models\SaleInvoice;
use Illuminate\Support\Str;
use App\Models\SalesPayment;
use Illuminate\Http\Request;
use App\Services\FedExService;
use App\Models\SavedCreditCard;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\SaleShippingAddress;
use App\Models\SalesInvoicePayment;
use App\Models\Warehouse;

class StripeController extends Controller
{

    private $stripe;
    protected $fedExService;

    public function __construct(FedExService $fedExService)
    {
        $this->stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
        $this->fedExService = $fedExService;
    }

    public function stripe(Request $request)
    {
        dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact_no' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'state' => 'required',
            'payment_method' => 'required',
            'products' => 'required',
        ]);
        $jsonEncode = json_encode($request->all());
        session()->put();

        $response = $this->stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => "Products",
                        ],
                        'unit_amount' => $request->grand_total * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'allow_promotion_codes' => true,
            'metadata' => [
                'request' => $jsonEncode,
            ],
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);
        //dd($response);
        if (isset($response->id) && $response->id != '') {
            // return redirect($response->url);
            return response()->json(['url' => $response->url, 'status' => 200]);
        } else {
            return redirect()->route('cancel');
        }
    }

    public function success(Request $request)
    {
        if (isset($request->session_id)) {

            $response = $this->stripe->checkout->sessions->retrieve($request->session_id);
            // dd($response);

            $req = json_decode($response->metadata->request, true);
            $paymentIntent = $response->payment_intent;
            $paymentIntentResponse = $this->stripe->paymentIntents->retrieve($paymentIntent, ['expand' => ['payment_method']]);

            $paymentMethod = $paymentIntentResponse->payment_method;
            $cardDetails = $paymentMethod->card;

            // create sale here
            $salesPaymentId = $this->createSale($req);
            // dd($createSale);

            SavedCreditCard::UpdateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'card_brand' => $cardDetails->brand,
                    'card_last_four' => $cardDetails->last4,
                    'card_exp_month' => $cardDetails->exp_month,
                ],
                [
                    'user_id' => auth()->id(),
                    'card_id' => $cardDetails->fingerprint,
                    'card_brand' => $cardDetails->brand,
                    'card_last_four' => $cardDetails->last4,
                    'card_exp_month' => $cardDetails->exp_month,
                    'card_exp_year' => $cardDetails->exp_year,
                    'card_fingerprint' => $cardDetails->fingerprint,
                ]
            );

            $card = SavedCreditCard::where('card_last_four', $cardDetails->last4)
                ->where('card_brand', $cardDetails->brand)
                ->where('card_exp_month', $cardDetails->exp_month)
                ->where('card_exp_year', $cardDetails->exp_year)
                ->first();

            SalesPayment::whereIn('id', $salesPaymentId)->update(['card_id' => $card->id]);





            return redirect()->route('user.orders.index')->with('success', 'Order placed successfully!');
        } else {
            return redirect()->route('cancel');
        }
    }

    public function cancel()
    {
        return redirect()->route('user.checkout')->with('error', 'Payment is cancelled.');
    }



    public function createSale($request)
    {

        // dd($request);

        $data = collect($request)->toArray();
        // dd($data['customer_id']);
        $data['status'] = 'completed';
        $data['payment_status'] = "paid";
        $data['payment_method'] = "Card";
        // dd($data);

        // Convert the array to a collection
        $productsCollection = collect($data['products']);

        // Group products by warehouse_id
        $groupedProducts = $productsCollection->groupBy('warehouse_id');

        // dd($groupedProducts);

        // Convert the collection back to an array if needed
        $groupedProductsArray = $groupedProducts->toArray();
        $salesPaymentId = [];
        $salesId = [];
        foreach ($groupedProductsArray as $warehouse_id => $products) {

            try {
                DB::beginTransaction();

                $uuid = Str::uuid();
                $reference = substr($uuid, 0, 6);
                $reference = 'Order#' . $reference;

                $sale = new Sale();
                $sale->reference = $reference;
                $sale->date = date('Y-m-d');
                $sale->customer_id = $data['customer_id'];
                $sale->ntn = $data['ntn_no'] ?? null;
                $sale->amount_recieved = $data['grand_total'] ?? 0;
                $sale->amount_due = 0;
                $sale->amount_pay = $data['grand_total'];
                $sale->grand_total = $data['grand_total'];
                $sale->notes = $data['notes'] ?? null;
                $sale->status = $data['status'];
                $sale->payment_status = $data['payment_status'];
                $sale->payment_method = $data['payment_method'];
                $sale->warehouse_id = $warehouse_id;
                $sale->save();


                foreach ($products as $itemData) {
                    $product = Product::find($itemData['product_id']);
                    $productItem = new ProductItem();
                    $productItem->sale_id = $sale->id;
                    $productItem->product_id = $itemData['product_id'];
                    $productItem->quantity = $itemData['quantity'];
                    $productItem->price = $itemData['price'];
                    $productItem->discount = 0;
                    $productItem->order_tax = 0;
                    $productItem->sub_total = $itemData['price'] * $itemData['quantity'];
                    $productItem->sale_unit = $product->sale_unit ?? null;
                    $productItem->save();

                    // Rest of your code...
                    $product = Product::with('unit', 'sale_units')->find($itemData['product_id']);
                    $warehouse_product = ProductWarehouse::where('product_id', $itemData['product_id'])->where('warehouse_id', $warehouse_id)->first();
                    $productQuantity = $warehouse_product->quantity;
                    $finalStock = 0;
                    if ($product->product_type != 'service') {
                        $convertedUnit = Unit::find($product->sale_unit);
                        // dd($convertedUnit);
                        if ($product->product_unit != $convertedUnit->id) {
                            if ($product->unit->parent_id == 0) {
                                $expression = $productQuantity . $product->unit->operator . $convertedUnit->operator_value;
                                $convertedStock = eval("return $expression;");
                                $stock = $convertedStock - $itemData['quantity'];
                                $secondExp = $stock . $convertedUnit->operator . $convertedUnit->operator_value;
                                $finalStock = eval("return $secondExp;");
                            }
                        } else {
                            $finalStock = $productQuantity - $itemData['quantity'];
                        }
                    } else {
                        $finalStock = $productQuantity - $itemData['quantity'];
                    }
                    $warehouse_product->update(['quantity' => $finalStock]);
                }


                $invoice =  new SaleInvoice();
                $invoice->invoice_id = $reference;
                $invoice->sale_id = $sale->id;
                $invoice->user_id = auth()->user()->id;
                $invoice->save();


                if ($data['payment_status'] != 'pending') {

                    $salePayment = SalesPayment::create([
                        'customer_id' => $sale->customer_id,
                        'payment_method' => 'Card',
                        'payment_date' => $sale->date,
                        'status' => 1,
                        'total_pay' => $sale->amount_recieved,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);

                    SalesInvoicePayment::create([
                        'sale_invoice_id' => $invoice->id,
                        'sales_payment_id' => $salePayment->id,
                        'paid_amount' => $sale->amount_recieved,
                    ]);
                    // Add the salesPayment->id to the array
                    $salesPaymentId[] = $salePayment->id;
                }

                $data['sale_id'] = $sale->id;
                $warehouse = Warehouse::find($sale->warehouse_id);
                SaleShippingAddress::create([
                    'sale_id' => $sale->id,
                    'customer_id' => $data['customer_id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'contact_no' => $data['contact_no'],
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'country' => $data['country'],
                    'zip_code' => $data['zip_code'],
                    'notes' => $data['notes'] ?? null,
                    'appartment' => $data['appartment'] ?? null,
                    'state' => $data['state'],
                    'company_name' => $data['company_name'] ?? '',
                ]);

                $salesId[] = $sale->id;
                // dd("yes");
                $createShippmentResponse =  $this->createShipment($request,$warehouse);
                if ($createShippmentResponse['status'] === 'success') {

                    $sale->shipping_method = $request['shipping_method'];
                    $sale->tracking_number = $createShippmentResponse['data']['output']['transactionShipments'][0]['masterTrackingNumber'];
                    $sale->save();
                    DB::commit();
                }

                sendInvoiceToCustomerViaEmailAndSms($sale->customer->user->email, $sale->id);


            } catch (\Exception $e) {
                DB::rollBack();
                // return redirect()->back()->with('error', 'Something went wrong, please try again.');
                return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
            }
        }

        AddToCart::where('customer_id', auth()->id())->delete();
        return $salesPaymentId ?? [];

        // return response()->json(['success' => 'Order placed successfully!', 'status' => 200]);
        // return redirect()->route('user.orders.index')->with('success', 'Order placed successfully!');
    }



    protected function createShipment($request, $warehouse)
    {
        $shipmentDetails = $this->prepareShipmentDetails($request, $warehouse);
        // dd($shipmentDetails);

        $shipmentResponse = $this->fedExService->createShipment($shipmentDetails);

        // return response()->json($response);
        return $shipmentResponse;
    }

    private function prepareShipmentDetails($request, $warehouse)
    {
        $r_address = $request->address ?? '';
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
                        "streetLines" => $s_streetLines,  //[$warehouse->users->address ?? 'Shipper street line 1' ]
                        "city" => $warehouse->city,
                        "stateOrProvinceCode" => $warehouse->users->state_code ?? "AZ",
                        "postalCode" => $warehouse->zip_code ?? "72601",
                        "countryCode" => $warehouse->users->country_code ?? "Us",
                    ],
                ],
                "recipients" => [
                    [
                        "contact" => [
                            "personName" => $request->name,
                            "phoneNumber" => $request->contact_no ??  "1234567890",
                        ],
                        "address" => [
                            "streetLines" => $r_streetLines, //[$customer->user->address],
                            "city" => $request->city ??  "Collierville",
                            "stateOrProvinceCode" => $request->state_code ?? "FL",
                            "postalCode" => $request->postal_code ?? "38017",
                            "countryCode" => $request->country_code ?? "Us",
                        ],
                    ],
                ],
                "shipDatestamp" => Carbon::parse(now())->addDays(2)->format('Y-m-d'),  // Use current or future date
                "serviceType" => $request->shipping_method ?? 'STANDARD_OVERNIGHT', //PRIORITY_OVERNIGHT  //STANDARD_OVERNIGHT
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
                            "units" => "LB",
                            "value" => 4.0,  // Example weight
                        ],
                        // "dimensions" => [
                        //     "length" => 12,
                        //     "width" => 12,
                        //     "height" => 12,
                        //     "units" => "IN",
                        // ],
                    ],
                ],
            ],
            "accountNumber" => [
                "value" => config('fedex.account_number'),
            ],
        ];
    }
}
