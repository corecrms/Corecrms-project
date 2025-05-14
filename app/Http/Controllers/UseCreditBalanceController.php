<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\Product;
use App\Models\AddToCart;
use App\Models\Warehouse;
use App\Models\ProductItem;
use App\Models\SaleInvoice;
use Illuminate\Support\Str;
use App\Models\SalesPayment;
use Illuminate\Http\Request;
use App\Models\CreditActivity;
use App\Services\FedExService;
use App\Models\SavedCreditCard;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\SaleShippingAddress;
use App\Models\SalesInvoicePayment;


class UseCreditBalanceController extends Controller
{


    protected $fedExService;

    public function __construct(FedExService $fedExService)
    {
        $this->fedExService = $fedExService;
    }

    public function userCredit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact_no' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'state' => 'required',
            'products' => 'required',
            'grand_total' => 'required|numeric'
        ]);

        $balance = auth()->user()->customer->balance;

        if ($balance <= 0) {
            return response()->json(['message' => 'Insufficient balance', 'status' => 400]);
        } else {
            if ($request->grand_total <= $balance) {
                auth()->user()->customer->update(['balance' => $balance - $request->grand_total]);
                $this->createSale($request->all());

                CreditActivity::create([
                    'customer_id' => auth()->user()->customer->id,
                    'action' => 'Used',
                    'credit_balance' => $balance,
                    'added_deducted' => $request->grand_total,
                    'comment' => 'Used balance for Order',
                ]);
                return response()->json(['success' => 'Order has been placed successfully', 'status' => 200, 'url' => route('user.orders.index')],);
            } else {
                $remaining_amount = $request->grand_total - $balance;

                return $this->stripe($request, $remaining_amount);
            }
        }
    }

    public function stripe($request, $remaining_amount)
    {
        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));

        try {
            $jsonEncode = json_encode($request->all());
            session()->put('requestedData', $jsonEncode);

            $response = $stripe->checkout->sessions->create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => "Products",
                            ],
                            'unit_amount' => $remaining_amount * 100,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'allow_promotion_codes' => true,
                'metadata' => [
                    // 'request' => json_encode($request->all()),
                    'remaining_amount' => $remaining_amount,
                ],
                'success_url' => route('usebalance.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('usebalance.stripe.cancel'),
            ]);

            if (isset($response->id) && $response->id != '') {
                return response()->json(['url' => $response->url, 'status' => 200]);
            } else {
                return response()->json(['status' => 400, 'error' => 'Payment creation failed.']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }
    }



    // public function success(Request $request)
    // {
    //     // dd($request->all());
    //     $stripe =  new \Stripe\StripeClient(config('stripe.stripe_sk'));
    //     if (isset($request->session_id)) {

    //         $response = $stripe->checkout->sessions->retrieve($request->session_id);

    //         // $req = json_decode($response->metadata->request, true);
    //         $req = json_decode(session()->get('requestedData'), true);

    //         $remaining_amount = $response->metadata->remaining_amount;
    //         $paymentIntent = $response->payment_intent;
    //         $paymentIntentResponse = $stripe->paymentIntents->retrieve($paymentIntent, ['expand' => ['payment_method']]);

    //         $paymentMethod = $paymentIntentResponse->payment_method;
    //         $cardDetails = $paymentMethod->card;

    //         $customerCard =  SavedCreditCard::UpdateOrCreate(
    //             [
    //                 'user_id' => auth()->id(),
    //                 'card_brand' => $cardDetails->brand,
    //                 'card_last_four' => $cardDetails->last4,
    //                 'card_exp_month' => $cardDetails->exp_month,
    //             ],
    //             [
    //                 'user_id' => auth()->id(),
    //                 'card_id' => $cardDetails->fingerprint,
    //                 'card_brand' => $cardDetails->brand,
    //                 'card_last_four' => $cardDetails->last4,
    //                 'card_exp_month' => $cardDetails->exp_month,
    //                 'card_exp_year' => $cardDetails->exp_year,
    //                 'card_fingerprint' => $cardDetails->fingerprint,
    //             ]
    //         );

    //         // create sale here
    //         $this->createSale($req, $remaining_amount,$customerCard);

    //         // modified balance
    //         auth()->user()->customer->update(['balance' => 0]);
    //          CreditActivity::create([
    //                 'customer_id' => auth()->user()->customer->id,
    //                 'action' => 'Used',
    //                 'credit_balance' => 0,
    //                 'added_deducted' => $req['grand_total'] - $remaining_amount,
    //                 'comment' => 'Used balance for Order',
    //         ]);



    //         return redirect()->route('user.orders.index')->with('success', 'Order placed successfully!');
    //     } else {
    //         return redirect()->route('cancel');
    //     }
    // }

    // public function success(Request $request)
    // {
    //     $stripe =  new \Stripe\StripeClient(config('stripe.stripe_sk'));
    //     if (isset($request->session_id)) {
    //         $response = $stripe->checkout->sessions->retrieve($request->session_id);
    //         $req = json_decode(session()->get('requestedData'), true);

    //         $remaining_amount = $response->metadata->remaining_amount;
    //         $paymentIntent = $response->payment_intent;
    //         $paymentIntentResponse = $stripe->paymentIntents->retrieve($paymentIntent, ['expand' => ['payment_method']]);
    //         $paymentMethod = $paymentIntentResponse->payment_method;
    //         $cardDetails = $paymentMethod->card;

    //         // Begin database transaction
    //         DB::beginTransaction();

    //         try {

    //             $customerCard =  SavedCreditCard::UpdateOrCreate(
    //                 [
    //                     'user_id' => auth()->id(),
    //                     'card_brand' => $cardDetails->brand,
    //                     'card_last_four' => $cardDetails->last4,
    //                     'card_exp_month' => $cardDetails->exp_month,
    //                 ],
    //                 [
    //                     'user_id' => auth()->id(),
    //                     'card_id' => $cardDetails->fingerprint,
    //                     'card_brand' => $cardDetails->brand,
    //                     'card_last_four' => $cardDetails->last4,
    //                     'card_exp_month' => $cardDetails->exp_month,
    //                     'card_exp_year' => $cardDetails->exp_year,
    //                     'card_fingerprint' => $cardDetails->fingerprint,
    //                 ]
    //             );
    //             // Create sale
    //             $salesPaymentId = $this->createSale($req, $remaining_amount, $customerCard);

    //             // dd($salesPaymentId);
    //             if ($salesPaymentId['status'] === 'error') {
    //                 // Rollback the transaction
    //                 DB::rollBack();

    //                 // // Optionally, cancel or refund the Stripe payment
    //                 if ($paymentIntentResponse->status == 'requires_capture') {
    //                     // Cancel the payment if it hasn't been captured yet
    //                     $stripe->paymentIntents->cancel($paymentIntent);
    //                 } else {
    //                     // Issue a refund if the payment was already captured
    //                     $stripe->refunds->create(['payment_intent' => $paymentIntent]);
    //                 }

    //                 return redirect()->route('user.checkout')
    //                     ->withErrors($salesPaymentId['errors'])
    //                     ->with('error', $salesPaymentId['message']);
    //             }

    //             // modified balance
    //             auth()->user()->customer->update(['balance' => 0]);
    //             CreditActivity::create([
    //                 'customer_id' => auth()->user()->customer->id,
    //                 'action' => 'Used',
    //                 'credit_balance' => 0,
    //                 'added_deducted' => $req['grand_total'] - $remaining_amount,
    //                 'comment' => 'Used balance for Order',
    //             ]);


    //             // Commit the transaction
    //             DB::commit();

    //             session()->forget('requestedData');
    //             return redirect()->route('user.orders.index')->with('success', 'Order placed successfully!');
    //         } catch (\Exception $e) {
    //             // Rollback the transaction
    //             DB::rollBack();

    //             // // Optionally, cancel or refund the Stripe payment
    //             if ($paymentIntentResponse->status == 'requires_capture') {
    //                 $stripe->paymentIntents->cancel($paymentIntent);
    //             } else {
    //                 $stripe->refunds->create(['payment_intent' => $paymentIntent]);
    //             }

    //             // Return with error
    //             return redirect()->route('user.checkout')
    //                 ->with('error', 'An error occurred while processing your order. Please try again. ' . $e->getMessage());
    //         }
    //     } else {
    //         return redirect()->route('cancel');
    //     }
    // }

    public function success(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
        if (isset($request->session_id)) {
            $response = $stripe->checkout->sessions->retrieve($request->session_id);
            $req = json_decode(session()->get('requestedData'), true);

            $remaining_amount = $response->metadata->remaining_amount;
            $paymentIntent = $response->payment_intent;
            $paymentIntentResponse = $stripe->paymentIntents->retrieve($paymentIntent, ['expand' => ['payment_method']]);
            $paymentMethod = $paymentIntentResponse->payment_method;
            $cardDetails = $paymentMethod->card;

            DB::beginTransaction();

            try {
                $customerCard = SavedCreditCard::UpdateOrCreate(
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

                // Create sale
                $salesPaymentId = $this->createSale($req, $remaining_amount, $customerCard);

                // Check if 'status' key exists
                if (isset($salesPaymentId['status']) && $salesPaymentId['status'] === 'error') {
                    DB::rollBack();

                    if ($paymentIntentResponse->status == 'requires_capture') {
                        $stripe->paymentIntents->cancel($paymentIntent);
                    } else {
                        $stripe->refunds->create(['payment_intent' => $paymentIntent]);
                    }

                    return redirect()->route('user.checkout')
                        ->withErrors($salesPaymentId['errors'] ?? [])
                        ->with('error', $salesPaymentId['message'] ?? 'An unknown error occurred.');
                }

                auth()->user()->customer->update(['balance' => 0]);
                CreditActivity::create([
                    'customer_id' => auth()->user()->customer->id,
                    'action' => 'Used',
                    'credit_balance' => 0,
                    'added_deducted' => $req['grand_total'] - $remaining_amount,
                    'comment' => 'Used balance for Order',
                ]);

                DB::commit();

                session()->forget('requestedData');
                return redirect()->route('user.orders.index')->with('success', 'Order placed successfully!');
            } catch (\Exception $e) {
                DB::rollBack();

                if ($paymentIntentResponse->status == 'requires_capture') {
                    $stripe->paymentIntents->cancel($paymentIntent);
                } else {
                    $stripe->refunds->create(['payment_intent' => $paymentIntent]);
                }

                return redirect()->route('user.checkout')
                    ->with('error', 'An error occurred while processing your order. Please try again. ' . $e->getMessage());
            }
        } else {
            return redirect()->route('cancel');
        }
    }



    public function cancel()
    {
        return redirect()->route('user.checkout')->with('error', 'Payment has been cancelled.');
    }


    public function createSale($request, $stripe_amount = null, $customerCard = null)
    {

        // dd($request);

        $data = collect($request)->toArray();
        $data['status'] = 'completed';
        $data['payment_status'] = "paid";
        $data['payment_method'] = "Card";

        // Convert the array to a collection
        $productsCollection = collect($data['products']);

        // Group products by warehouse_id
        $groupedProducts = $productsCollection->groupBy('warehouse_id');

        // Convert the collection back to an array
        $groupedProductsArray = $groupedProducts->toArray();

        $salesPaymentId = [];
        $salesId = [];
        foreach ($groupedProductsArray as $warehouse_id => $products) {

            try {
                DB::beginTransaction();

                $reference = mt_rand(10000000, 99999999);

                $sale = new Sale();
                $sale->reference = $reference;
                $sale->date = date('Y-m-d');
                $sale->customer_id = $data['customer_id'];
                $sale->ntn = $data['ntn_no'] ?? null;
                $sale->amount_recieved = $data['grand_total'] ?? 0;
                $sale->amount_due = 0.00;
                $sale->amount_pay = $data['grand_total'];
                $sale->grand_total = $data['grand_total'];
                $sale->notes = $data['notes'] ?? null;
                $sale->status = $data['status'];
                $sale->payment_status = $data['payment_status'];
                $sale->payment_method = $data['payment_method'];
                $sale->shipping = $data['shipping_fee'] ?? 0;
                $sale->shipping_method = $data['shipping_method'] == 'STORE_PICKUP' ? 'Store Pickup ' : $data['shipping_method'];
                $sale->warehouse_id = $warehouse_id;
                $sale->discount = $data['discount'] ?? 0;
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

                    // decrement quantity...
                    $product = Product::with('unit', 'sale_units')->find($itemData['product_id']);
                    $warehouse_product = ProductWarehouse::where('product_id', $itemData['product_id'])->where('warehouse_id', $warehouse_id)->first();
                    $productQuantity = $warehouse_product->quantity;
                    $finalStock = 0;
                    if ($product->product_type != 'service') {
                        $convertedUnit = Unit::find($product->sale_unit);
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

                    if ($stripe_amount == null) {
                        $salePayment = SalesPayment::create([
                            'customer_id' => $sale->customer_id,
                            'payment_method' => "Credit Store",
                            'payment_date' => $sale->date,
                            'status' => 1,
                            'total_pay' => $sale->grand_total,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                        ]);

                        SalesInvoicePayment::create([
                            'sale_invoice_id' => $invoice->id,
                            'sales_payment_id' => $salePayment->id,
                            'paid_amount' => $sale->grand_total,
                        ]);
                    } else {
                        $salePayment1 = SalesPayment::create([
                            'customer_id' => $sale->customer_id,
                            'payment_method' => "Credit Store",
                            'payment_date' => $sale->date,
                            'status' => 1,
                            'total_pay' => $sale->grand_total - $stripe_amount,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                        ]);

                        SalesInvoicePayment::create([
                            'sale_invoice_id' => $invoice->id,
                            'sales_payment_id' => $salePayment1->id,
                            'paid_amount' => $sale->grand_total - $stripe_amount,
                        ]);

                        $salePayment2 = SalesPayment::create([
                            'customer_id' => $sale->customer_id,
                            'account_id' => $sale->bank_account ?? null,
                            'payment_method' => 'Card',
                            'card_id' => $customerCard->id,
                            'payment_date' => $sale->date,
                            'status' => 1,
                            'total_pay' => $stripe_amount,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                        ]);

                        SalesInvoicePayment::create([
                            'sale_invoice_id' => $invoice->id,
                            'sales_payment_id' => $salePayment2->id,
                            'paid_amount' => $stripe_amount,
                        ]);
                        $salesPaymentId[] = $salePayment2->id;
                    }
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



                // if($request['shipping_method'] != 'STORE_PICKUP'){
                //     try {
                //         $createShippmentResponse = $this->createShipment($request, $warehouse);

                //         if ($createShippmentResponse['status'] === 'success') {
                //             $sale->shipping_method = $request['shipping_method'];
                //             $sale->tracking_number = $createShippmentResponse['data']['output']['transactionShipments'][0]['masterTrackingNumber'];
                //             $sale->save();
                //             DB::commit();

                //             // return ['status' => 'success'];
                //         } else {
                //             DB::rollBack();
                //             return [
                //                 'status' => 'error',
                //                 'message' => $createShippmentResponse['message'] ?? 'Shipment creation failed.',
                //                 'errors' => $createShippmentResponse['errors'] ?? ['error' => 'Shipment creation failed.']
                //             ];
                //         }
                //     } catch (\Exception $e) {
                //         DB::rollBack();
                //         return [
                //             'status' => 'error',
                //             'message' => 'Error occurred: ' . $e->getMessage()
                //         ];
                //     }
                // }
                // else
                // {
                //     $sale->shipping_method = $request['shipping_method'];
                //     $sale->save();
                //     DB::commit();
                // }

                $sale->shipping_method = $request['shipping_method'];
                $sale->save();
                DB::commit();

                sendInvoiceToCustomerViaEmailAndSms($sale->customer->user->email, $sale->id);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return [
                    'status' => 'error',
                    'message' => 'Error occurred: ' . $e->getMessage()
                ];
            }
        }

        AddToCart::where('customer_id', auth()->id())->delete();
        return $salesPaymentId ?? [];
    }


    protected function createShipment($request, $warehouse)
    {
        $shipmentDetails = $this->prepareShipmentDetails($request, $warehouse);
        // dd($shipmentDetails);

        $shipmentResponse = $this->fedExService->createShipment($shipmentDetails);

        return $shipmentResponse;
    }

    private function prepareShipmentDetails($request, $warehouse)
    {
        $r_address = $request['address'] ?? '';
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
                            "personName" => $request['name'],
                            "phoneNumber" => $request['contact_no'] ??  "1234567890",
                        ],
                        "address" => [
                            "streetLines" => $r_streetLines, //[$customer->user->address],
                            "city" => $request['city'] ??  "Collierville",
                            "stateOrProvinceCode" => $request['state_code'] ?? "FL",
                            "postalCode" => $request['zip_code'] ?? "38017",
                            "countryCode" => $request['country_code'] ?? "Us",
                        ],
                    ],
                ],
                "shipDatestamp" => Carbon::parse(now())->addDays(2)->format('Y-m-d'),  // Use current or future date
                "serviceType" => $request['shipping_method'] ?? 'STANDARD_OVERNIGHT', //PRIORITY_OVERNIGHT  //STANDARD_OVERNIGHT
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
                    ],
                ],
            ],
            "accountNumber" => [
                "value" => config('fedex.account_number'),
            ],
        ];
    }
}
