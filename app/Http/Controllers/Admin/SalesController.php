<?php

namespace App\Http\Controllers\Admin;

use DB;
use stdClass;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Account;
use App\Models\Barcode;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Shipment;
use Stripe\Issuing\Card;
use App\Models\Warehouse;
use App\Models\ProductItem;
use App\Models\SaleInvoice;
use Illuminate\Support\Str;
use App\Jobs\SendInvoiceJob;
use App\Models\SalesPayment;
use App\Models\ShopifyStore;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Services\FedExService;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\SendInvoiceByEmail;
use App\Models\ProductWarehouse;
use App\Services\ShopifyService;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\JsonResponse;
use App\Models\SaleShippingAddress;
use App\Models\SalesInvoicePayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController;
use App\Models\CreditActivity;
use Illuminate\Support\Facades\Validator;

class SalesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $fedExService;
    function __construct(FedExService $fedExService)
    {
        $this->fedExService = $fedExService;
        $this->middleware('permission:sale-list|sale-create|sale-edit|sale-delete|sale-show
          ', ['only' => ['index', 'show']]);
        $this->middleware('permission:sale-list', ['only' => ['index']]);
        $this->middleware('permission:sale-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:sale-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:sale-delete', ['only' => ['destroy']]);
        $this->middleware('permission:sale-show', ['only' => ['show']]);
    }

    public function index(Request $req)
    {
        if ($req->filled('search')) {

            $searchTerm = $req->search;

            // Search products by name
            $sales = Sale::search($searchTerm)->get();
            $users = User::search($searchTerm)->get();
            $users->load('customer.sales', 'warehouse.sales');
            // return $users->customer->sales;
            $users = $users ? $users : collect();

            $salesByCustomer = collect();
            $salesByWarehouse = collect();
            foreach ($users as $user) {
                if ($user->customer) {
                    foreach ($user->customer->sales as $sale) {
                        $salesByCustomer->push($sale);
                    }
                }
                if ($user->warehouse) {
                    foreach ($user->warehouse->sales as $sale) {
                        $salesByWarehouse->push($sale);
                    }
                }
            }

            // return $salesByCustomer;
            // return $salesByWarehouse;

            $shipment = Shipment::search($searchTerm)->get();
            $shipment->load('sales');
            $shipment = $shipment ? $shipment : collect();
            $salesByShipping = [];
            foreach ($shipment as $ship) {
                $salesByShipping[] = $ship->sales;
            }

            $mergedSales = $sales->merge($salesByCustomer)->merge($salesByWarehouse)->merge($salesByShipping)->unique('id');
            $sales = $mergedSales->load('customer', 'warehouse');
            // dd($sales);
            $customers = Customer::all();
            $warehouses = Warehouse::all();
            return view('back.sales.index', compact('sales', 'customers', 'warehouses'));
        }
        if (auth()->user()->hasRole('Cashier') || auth()->user()->hasRole('Manager')) {
            $warehouseId = auth()->user()->warehouse_id;
            $sales = Sale::where('warehouse_id', $warehouseId)->latest()->get();
            $sales->load('customer');
            $sales->load('warehouse');
            $customers = Customer::all();
            $warehouses = Warehouse::all();
            return view('back.sales.index', compact('sales', 'customers', 'warehouses'));
        } else {
            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $sales = Sale::where('warehouse_id', $warehouseId)->latest()->get();
            } else {
                $sales = Sale::latest()->get();
            }

            // Eager load related customer and warehouse data
            $sales->load(['customer', 'warehouse']);

            // Fetch customers and warehouses
            $customers = Customer::all();
            $warehouses = Warehouse::all();

            // Return the view with the compacted data
            return view('back.sales.index', compact('sales', 'customers', 'warehouses'));
        }
    }

    public function filterSales(Request $req)
    {
        $query = Sale::with('customer', 'warehouse');

        $filters = $req->all();

        if (isset($filters['date'])) {
            $query->where('date', $filters['date']);
        }

        if (isset($filters['reference'])) {
            $query->where('reference', $filters['reference']);
        }

        if (isset($filters['customer_id']) && $filters['customer_id'] > 0) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (isset($filters['warehouse_id'])  && $filters['warehouse_id'] > 0) {
            $query->where('warehouse_id', $filters['warehouse_id']);
        }

        if (isset($filters['status'])  && $filters['status'] > 0) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['payment_status'])  && $filters['payment_status'] > 0) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (isset($filters['shipping_status'])  && $filters['shipping_status'] > 0) {
            $query->where('shipping_status', $filters['shipping_status']);
        }

        $sales = $query->get();
        $customers = Customer::all();
        $warehouses = Warehouse::all();

        return view('back.sales.index', compact('sales', 'customers', 'warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::where('blacklist', '!=', 1)->get();

        $products = Product::all();
        $units = Unit::all();
        $warehouses = Warehouse::all();
        $bank_accounts = Account::all();
        $payments = PaymentMethod::all();
        // return $units;
        return view('back.sales.create', compact('customers', 'products', 'units', 'warehouses', 'bank_accounts', 'payments'));
    }

    public function pos()
    {
        // $products = Product::with('images')->get();
        $firstWarehouseId = Warehouse::first()->id;

        $columns = [
            'products.id',
            'products.name',
            'products.sku',
            'products.barcode',
            'products.purchase_price',
            'products.sell_price',
            'products.brand_id',
            'products.category_id',
            'products.sub_category_id',
            'products.stock_alert',
            'products.product_type',
            'products.tax_type',
            'products.order_tax',
            'products.status',
            'products.imei_no',
            'products.product_unit',
            'products.sale_unit',
            'products.purchase_unit',
            'products.warehouse_id',
            'products.customer_id',
            'products.quantity',
            'products.shopify_id',
            'products.created_by',
            'products.updated_by',
            'products.deleted_by',
            'products.deleted_at',
            'products.created_at',
            'products.updated_at',
            'products.product_live',
            'products.new_product',
            'products.featured_product',
            'products.best_seller',
            'products.recommended',
        ];

        $products = Product::select($columns)->with('images', 'sale_units', 'product_warehouses')->whereHas('product_warehouses', function ($query) use ($firstWarehouseId) {
            $query->where('warehouse_id', $firstWarehouseId);
            $query->where('quantity', '>', 0);
        })->get();

        $productByBrand = Product::select($columns)->with('images', 'sale_units', 'product_warehouses')->whereHas('product_warehouses', function ($query) use ($firstWarehouseId) {
            $query->where('warehouse_id', $firstWarehouseId);
            $query->where('quantity', '>', 0);
        })->get()->groupBy('brand_id');
        // dd($productByBrand);

        $warehouses = Warehouse::all();

        $customers = Customer::where('blacklist', '!=', 1)->get();
        $accounts = Account::all();
        $units = Unit::all();
        // dd($products);
        return view('back.sales.pos', compact('products', 'warehouses', 'customers', 'accounts', 'units', 'productByBrand'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'customer_id' => 'required',
            'order_items' => 'required',
        ], [
            'customer_id.required' => 'Please select a customer',
            'order_items.required' => 'Please select a product',
        ]);

        try {
            DB::beginTransaction();

            // $reference = 'Order#' . substr(Str::uuid(), 0, 6);
            $sale = new Sale();
            $sale->reference = $request->invoice_id;
            $sale->date = $request->date ?? date('Y-m-d');
            $sale->customer_id = $request->customer_id;
            $sale->ntn = $request->ntn_no ?? null;
            $sale->order_tax = $request->order_tax;
            $sale->discount = $request->discount;
            $sale->shipping = $request->shipping;
            $sale->status = $request->status ?? 'Completed';
            $sale->payment_status = $request->payment_status;
            $sale->payment_method = $request->payment_method;
            $sale->amount_recieved = $request->amount_recieved;
            $sale->change_return = $request->change_return;
            $sale->amount_due = $request->amount_due;
            $sale->amount_pay = $request->amount_pay;
            $sale->notes = $request->note;
            $sale->grand_total = $request->grand_total;
            $sale->warehouse_id = $request->warehouse_id;
            $sale->bank_account = $request->bank_account;
            $sale->shipping_method = $request->shipping_method;
            $sale->created_by = auth()->id();
            $sale->save();

            $line_items = [];
            foreach ($request->order_items as $itemData) {
                $productItem = new ProductItem();
                $productItem->sale_id = $sale->id;
                $productItem->product_id = $itemData['id'];
                $productItem->quantity = $itemData['quantity'];
                $productItem->discount = $itemData['discount'] ?? null;
                $productItem->order_tax = $itemData['order_tax'];
                $productItem->tax_type = $itemData['tax_type'];
                $productItem->discount_type = $itemData['discount_type'] ?? null;
                $productItem->price = $itemData['price'];
                $productItem->sub_total = $itemData['subtotal'];
                $productItem->stock = $itemData['stock'];
                $productItem->sale_unit = $itemData['sale_unit'] ?? null;
                $productItem->save();

                // Shopify product integration
                $shopify_product = Product::find($itemData['id']);
                $line_items[] = [
                    'variant_id' => $shopify_product->variants->first()->id ?? $shopify_product->shopify_id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'name' => $shopify_product->name,
                    'title' => $shopify_product->sku,
                ];

                $product = Product::with('unit', 'sale_units')->find($itemData['id']);
                $warehouse_product = ProductWarehouse::where('product_id', $itemData['id'])->where('warehouse_id', $request->warehouse_id)->first();
                $productQuantity = $warehouse_product->quantity;
                $finalStock = $this->calculateStock($product, $itemData, $productQuantity);
                $warehouse_product->update(['quantity' => $finalStock]);
            }

            // Shopify Integration (if enabled)
            $shopify_enable = Setting::first();
            if ($shopify_enable->shopify_enable == 1) {
                $this->createShopifyOrder($line_items, $sale, $request);
            }

            $invoice = new SaleInvoice();
            $invoice->invoice_id = $sale->reference;
            $invoice->sale_id = $sale->id;
            $invoice->user_id = auth()->user()->id;
            $invoice->save();

            if ($sale->payment_status != 'pending') {
                foreach ($request->payment_methods as $method) {
                    if ($method['method'] == 'Cash') {
                        $salePayment = SalesPayment::create([
                            'customer_id' => $sale->customer_id,
                            'account_id' => $method['bank_account'] ?? null,
                            'payment_date' => $sale->date,
                            'status' => 1,
                            'total_pay' => $method['amount_received'],
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                            'payment_method' => $method['method'],
                        ]);
                        SalesInvoicePayment::create([
                            'sale_invoice_id' => $invoice->id,
                            'sales_payment_id' => $salePayment->id,
                            'paid_amount' => $method['amount_received'],
                        ]);
                    }
                    if ($method['method'] == 'Card') {
                        $salePayment = SalesPayment::create([
                            'customer_id' => $sale->customer_id,
                            'account_id' => null,
                            'payment_date' => $sale->date,
                            'status' => 1,
                            'total_pay' => $method['amount_received'],
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                            'payment_method' => $method['method'],
                            'card_id' => $method['customer_card'],
                        ]);
                        SalesInvoicePayment::create([
                            'sale_invoice_id' => $invoice->id,
                            'sales_payment_id' => $salePayment->id,
                            'paid_amount' => $method['amount_received'],
                        ]);
                    }
                    if ($method['method'] == 'Credit Store') {
                        $salePayment = SalesPayment::create([
                            'customer_id' => $sale->customer_id,
                            'account_id' => null,
                            'payment_date' => $sale->date,
                            'status' => 1,
                            'total_pay' => $method['paying_amount_fee'],
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                            'payment_method' => $method['method'],
                            'card_id' => null,
                        ]);
                        SalesInvoicePayment::create([
                            'sale_invoice_id' => $invoice->id,
                            'sales_payment_id' => $salePayment->id,
                            'paid_amount' => $method['paying_amount_fee'],
                        ]);

                        $sale->customer->balance -= $method['paying_amount_fee'];
                        $sale->customer->save();
                        $sale->save();
                    }
                }
            }

            $customer = Customer::find($request->customer_id)->load('user');
            $warehouse = Warehouse::find($request->warehouse_id)->load('users');
            SaleShippingAddress::create([
                'sale_id' => $sale->id,
                'name' => $customer->user->name,
                'email' => $customer->user->email,
                'address' => $customer->user->address ?? 'address',
                'city' => $customer->city ?? 'city',
                'country' => $customer->country ?? 'country',
                'state' => $customer->state ?? 'state',
                'zip_code' => $customer->user->zip_code ?? 123456,
                'contact_no' => $customer->user->contact_no ?? 123456789,
            ]);

            // // add credit store if amount received is greater than grand total
            // if($sale->amount_recieved > $sale->grand_total){
            //     $additional_balance = $sale->amount_recieved - $sale->grand_total;
            //     CreditActivity::create([
            //         'customer_id' => $sale->customer_id,
            //         'action' => 'Added',
            //         'credit_balance' => $customer->balance + $additional_balance,
            //         'added_deducted' => $additional_balance,
            //         'comment' => 'Added balance for Order',
            //     ]);
            //     $customer->balance += $additional_balance;

            //     $customer->save();
            // }





            // if ($request->shipping_method != 'Store Pickup') {
            //     $createShippmentResponse =  $this->createShipment($request, $customer, $warehouse);
            //     // return $createShippment;
            //     if ($createShippmentResponse['status'] === 'success') {

            //         $sale->shipping_method = $request->shipping_method;
            //         $sale->tracking_number = $createShippmentResponse['data']['output']['transactionShipments'][0]['masterTrackingNumber'];
            //         $sale->save();
            //         DB::commit();
            //     } else {
            //         // Return errors
            //         return response()->json([
            //             'message' => $createShippmentResponse['message'],
            //             'errors' => $createShippmentResponse['errors'],
            //         ], 422);
            //     }
            // } else {
            //     DB::commit();
            // }

            DB::commit();

            try {
                $sale->load('productItems', 'customer', 'warehouse', 'invoice');
                $totalDue = $sale->customer->sales->sum('amount_due');
                $job = new SendInvoiceJob($sale, $customer->user->email, getLogo(), $totalDue);
                dispatch($job);
                Log::info('Email sent to: ' . $customer->user->email);
            } catch (\Exception $e) {
                Log::error('Error sending email: ' . $e->getMessage());
            }

            // DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    protected function createShopifyOrder($line_items, $sale, $request)
    {
        // $shopify_store = ShopifyStore::where('user_id', auth()->user()->id)->first();
        $shopify_store = ShopifyStore::first();
        if (!$shopify_store) {
            throw new \Exception('Shop not found.');
        }

        $shopDomain = $shopify_store->shop_domain;
        $accessToken = $shopify_store->access_token;
        $customer = Customer::find($request->customer_id);
        $client = new Client();

        $orderData = [
            'email' => $customer->user->email,
            'financial_status' => 'paid',
            'fulfillment_status' => 'fulfilled',
            'line_items' => $line_items,
            'transactions' => [
                [
                    'kind' => 'sale',
                    'status' => 'success',
                    'amount' => $sale->grand_total,
                ]
            ]
        ];

        try {
            $response = $client->request('POST', "https://{$shopDomain}/admin/api/2023-04/orders.json", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token' => $accessToken,
                ],
                'body' => json_encode(['order' => $orderData]),
            ]);

            $shopifyOrder = json_decode($response->getBody()->getContents(), true);
            if (isset($shopifyOrder['order']['id'])) {
                $sale->shopify_order_id = $shopifyOrder['order']['id'];
                $sale->save();
            }
        } catch (\Exception $e) {
            throw new \Exception('Failed to create order in Shopify: ' . $e->getMessage());
        }
    }

    protected function calculateStock($product, $itemData, $productQuantity)
    {
        $finalStock = 0;
        if ($product->product_type != 'service') {
            $convertedUnit = Unit::find($itemData['sale_unit']);
            if ($product->product_unit != $itemData['sale_unit']) {
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

        return $finalStock;
    }

    protected function createShipment($request, $customer, $warehouse)
    {
        $shipmentDetails = $this->prepareShipmentDetails($request, $customer, $warehouse);

        $shipmentResponse = $this->fedExService->createShipment($shipmentDetails);

        return $shipmentResponse;
    }

    private function prepareShipmentDetails($request, $customer, $warehouse)
    {
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
                            "personName" => $customer->user->name,
                            "phoneNumber" => $customer->user->contact_no ??  "1234567890",
                        ],
                        "address" => [
                            "streetLines" => $r_streetLines, //[$customer->user->address],
                            "city" => $customer->city ??  "Collierville",
                            "stateOrProvinceCode" => $customer->user->state_code ?? "FL",
                            "postalCode" => $customer->user->postal_code ?? "38017",
                            "countryCode" => $customer->user->country_code ?? "Us",
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



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::find($id);
        $sale->load('productItems', 'customer', 'warehouse', 'invoice');
        $totalDue = $sale->customer->sales->sum('amount_due');
        $logo = getLogo();
        // dd($logo);
        return view('back.sales.sale-detail', compact('sale', 'logo', 'totalDue'));
    }

    public function downloadInvoice($id)
    {
        $sale = Sale::find($id);
        $sale->load('productItems', 'customer', 'warehouse', 'invoice');

        $totalDue = $sale->customer->sales->sum('amount_due');
        $logo = getLogo();
        $pdf = Pdf::loadView('back.sales.invoice', ['sale' => $sale, 'logo' => $logo, 'totalDue' => $totalDue]);

        return $pdf->download($sale->invoice->invoice_id . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $sale = Sale::find($id);
        $sale->load('productItems.product.unit', 'productItems.product.sale_units', 'customer');
        // dd($sale);
        $customers = Customer::where('blacklist', '!=', 1)->get();
        $products = Product::all();
        $units = Unit::all();
        $warehouses = Warehouse::all();
        $bank_accounts = Account::all();
        $payments = PaymentMethod::all();

        return view('back.sales.edit', compact('sale', 'customers', 'units', 'warehouses', 'products', 'bank_accounts', 'payments'));
    }


    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            // Find Sale for update
            $sale = Sale::findOrFail($id);
            $reference = $sale->reference;
            $sale->update([
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'ntn' => $request->ntn_no ?? null,
                'order_tax' => $request->order_tax,
                'discount' => $request->discount,
                'shipping' => $request->shipping,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'amount_recieved' => $request->amount_recieved,
                'change_return' => $request->change_return,
                'amount_due' => $request->amount_due,
                'amount_pay' => $request->amount_pay,
                'grand_total' => $request->grand_total,
                'warehouse_id' => $request->warehouse_id,
                'bank_account' => $request->account_id,
                'updated_by' => auth()->id(),
            ]);


            $productItem = ProductItem::where('sale_id', $id)->get();
            $productItem->load('sale_units', 'product.unit');
            // return $productItem;
            foreach ($productItem as $product) {

                $warehouse_product = ProductWarehouse::where('product_id', $product->product->id)->where('warehouse_id', $request->warehouse_id)->first();
                $finalStock = 0;
                if ($product->product->product_type != 'service') {
                    if ($product->product->product_unit != $product->sale_units->id) {
                        $expression = $warehouse_product->quantity . $product->product->unit->operator . $product->sale_units->operator_value;
                        $convertedStock = eval("return $expression;");
                        $stock = $convertedStock + $product->quantity;
                        $secondExp = $stock . $product->sale_units->operator . $product->sale_units->operator_value;
                        $finalStock = eval("return $secondExp;");
                    } else {
                        $finalStock = $warehouse_product->quantity + $product->quantity;
                    }
                } else {
                    $finalStock = $warehouse_product->quantity + $product->quantity;
                }
                $warehouse_product->update(['quantity' => $finalStock]);
                $product->delete();
            }

            // Prepare line items for Shopify order update
            $line_items = [];
            foreach ($request->order_items as $itemData) {

                $productItem = ProductItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $itemData['id'],
                    'quantity' => $itemData['quantity'],
                    'discount' => $itemData['discount'],
                    'order_tax' => $itemData['order_tax'],
                    'tax_type' => $itemData['tax_type'],
                    'discount_type' => $itemData['discount_type'] ?? null,
                    'price' => $itemData['price'],
                    'sub_total' => $itemData['subtotal'],
                    'stock' => $itemData['stock'],
                    'sale_unit' => $itemData['sale_unit'],
                ]);
                $shopify_product = Product::find($itemData['id']);

                $line_items[] = [

                    'variant_id' => $shopify_product->variants->first()->id ?? $productItem->product->shopify_id, // Use variant_id here
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price']

                ];

                $product = Product::with('unit', 'sale_units')->find($itemData['id']);
                $warehouse_product = ProductWarehouse::where('product_id', $itemData['id'])->where('warehouse_id', $request->warehouse_id)->first();

                $finalStock = 0;
                if ($product->product_type != 'service') {
                    $convertedUnit = Unit::find($itemData['sale_unit']);
                    if ($product->product_unit != $itemData['sale_unit']) {
                        if ($product->unit->parent_id == 0) {
                            $expression = $warehouse_product->quantity . $product->unit->operator . $convertedUnit->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock - $itemData['quantity'];
                            $secondExp = $stock . $convertedUnit->operator . $convertedUnit->operator_value;
                            $finalStock = eval("return $secondExp;");
                        }
                    } else {
                        $finalStock = $warehouse_product->quantity - $itemData['quantity'];
                    }
                } else {
                    $finalStock = $warehouse_product->quantity - $itemData['quantity'];
                }

                // $product->update(['quantity' => "$finalStock"]);
                $warehouse_product->update(['quantity' => $finalStock]);
            }

            // delete sales invoice payment
            $salesInvoicePayment = SalesInvoicePayment::where('sale_invoice_id', $sale->invoice->id)->get();

            foreach ($salesInvoicePayment as $payment) {
                if ($payment->salesPayment->payment_method == 'Credit Store') {
                    $sale->customer->balance += $payment->paid_amount;
                    $sale->customer->save();
                    $sale->save();
                }
                $payment->salesPayment->delete();
                $payment->delete();
            }
            // create sales payment
            if ($sale->payment_status != 'pending') {

                foreach ($request->payment_methods as $method) {
                    if ($method['method'] == 'Cash') {
                        $salePayment = SalesPayment::create([
                            'customer_id' => $sale->customer_id,
                            // 'account_id' => $method['bank_account'] ?? null,
                            'payment_date' => $sale->date,
                            'status' => 1,
                            'total_pay' => $method['amount_received'],
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                            'payment_method' => $method['method'],
                        ]);
                        SalesInvoicePayment::create([
                            'sale_invoice_id' => $sale->invoice->id,
                            'sales_payment_id' => $salePayment->id,
                            'paid_amount' => $method['amount_received'],
                        ]);
                    }
                    if ($method['method'] == 'Card') {
                        $salePayment = SalesPayment::create([
                            'customer_id' => $sale->customer_id,
                            'account_id' => null,
                            'payment_date' => $sale->date,
                            'status' => 1,
                            'total_pay' => $method['amount_received'],
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                            'payment_method' => $method['method'],
                            'card_id' => $method['customer_card'],
                        ]);
                        SalesInvoicePayment::create([
                            'sale_invoice_id' => $sale->invoice->id,
                            'sales_payment_id' => $salePayment->id,
                            'paid_amount' => $method['amount_received'],
                        ]);
                    }
                    if ($method['method'] == 'Credit Store') {
                        $salePayment = SalesPayment::create([
                            'customer_id' => $sale->customer_id,
                            'account_id' => null,
                            'payment_date' => $sale->date,
                            'status' => 1,
                            'total_pay' => $method['paying_amount_fee'],
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                            'payment_method' => $method['method'],
                            'card_id' => null,
                        ]);
                        SalesInvoicePayment::create([
                            'sale_invoice_id' => $sale->invoice->id,
                            'sales_payment_id' => $salePayment->id,
                            'paid_amount' => $method['paying_amount_fee'],
                        ]);

                        $sale->customer->balance -= $method['paying_amount_fee'];
                        $sale->customer->save();
                        $sale->save();
                    }
                }
            }


            // Check if Shopify integration is enabled and update order on Shopify
            if ($sale->shopify_order_id) { // Check if the sale is linked to Shopify
                $shopify_store = ShopifyStore::firstOrFail();
                $shopify_enable = Setting::first();
                if ($shopify_enable->shopify_enable == 1) { // Check if Shopify integration is enabled
                    $client = new Client();
                    $response = $client->request('PUT', "https://{$shopify_store->shop_domain}/admin/api/2023-04/orders/{$sale->shopify_order_id}.json", [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'X-Shopify-Access-Token' => $shopify_store->access_token,
                        ],
                        'body' => json_encode(['order' => ['line_items' => $line_items]])
                    ]);
                }
            }



            // dd($response->getBody()->getContents());

            DB::commit();
            return response()->json(['message' => 'Sale successfully updated', 'reference' => $reference], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sales.index')->with(['error' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */




    public function destroy($id)
    {
        try {
            // Find the sale in your local database
            $sale = Sale::findOrFail($id);
            $sale->load('invoice');

            // Check if Shopify integration is enabled
            $shopify_enable = Setting::first()->shopify_enable;

            // If Shopify is enabled and the sale has a Shopify order ID, proceed with deleting from Shopify
            if ($shopify_enable && $sale->shopify_order_id) {
                // Get the Shopify store details
                $shopify_store = ShopifyStore::where('user_id', auth()->user()->id)->first();

                if (!$shopify_store) {
                    return response()->json(['message' => 'Shop not found'], 404);
                }

                // Prepare the Shopify API request
                $shopDomain = $shopify_store->shop_domain;
                $accessToken = $shopify_store->access_token;
                $client = new Client();

                // Send the DELETE request to the Shopify API
                $response = $client->request('DELETE', "https://{$shopDomain}/admin/api/2023-04/orders/{$sale->shopify_order_id}.json", [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-Shopify-Access-Token' => $accessToken,
                    ],
                ]);

                // Check the response status
                if ($response->getStatusCode() != 200) {
                    // If the deletion was not successful, return an error message
                    return redirect()->back()->with(['error' => 'Failed to delete Shopify order'], 500);
                }
            }


            $productItems = ProductItem::where('sale_id', $id)->get();
            $productItems->load('sale_units', 'product.unit');

            foreach ($productItems as $product) {
                $warehouse_product = ProductWarehouse::where('product_id', $product->product->id)
                    ->where('warehouse_id', $sale->warehouse_id)
                    ->first();
                $finalStock = 0;

                if ($product->product->product_type != 'service') {
                    if ($product->product->product_unit != $product->sale_units->id) {
                        $expression = $warehouse_product->quantity . $product->product->unit->operator . $product->sale_units->operator_value;
                        $convertedStock = eval("return $expression;");
                        $stock = $convertedStock + $product->quantity;
                        $secondExp = $stock . $product->sale_units->operator . $product->sale_units->operator_value;
                        $finalStock = eval("return $secondExp;");
                    } else {
                        $finalStock = $warehouse_product->quantity + $product->quantity;
                    }
                } else {
                    $finalStock = $warehouse_product->quantity + $product->quantity;
                }

                $warehouse_product->update(['quantity' => "$finalStock"]);
                $product->delete();
            }

            // delete sales invoice payment
            $salesInvoicePayment = SalesInvoicePayment::where('sale_invoice_id', $sale->invoice->id)->get();

            foreach ($salesInvoicePayment as $payment) {
                if ($payment->salesPayment->payment_method == 'Credit Store') {
                    $sale->customer->balance += $payment->paid_amount;
                    $sale->customer->save();
                    $sale->save();
                }
                $payment->salesPayment->delete();
                $payment->delete();
            }
            // Delete the sale from your local database
            SaleInvoice::where('sale_id', $id)->first()->delete();

            $sale->delete();

            return redirect()->back()->with('success', 'Sale deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('sales.index')->with(['error' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }


    public function salesDelete(Request $req)
    {
        if (!empty($req->ids) && is_array($req->ids)) {
            foreach ($req->ids as $key => $id) {
                $sale = Sale::find($id);
                if ($sale) {


                    $productItem = ProductItem::where('sale_id', $id)->get();
                    $productItem->load('sale_units', 'product.unit');
                    foreach ($productItem as $product) {
                        $warehouse_product = ProductWarehouse::where('product_id', $product->product->id)->where('warehouse_id', $sale->warehouse_id)->first();
                        $finalStock = 0;
                        if ($product->product->product_type != 'service') {
                            if ($product->product->product_unit != $product->sale_units->id) {
                                $expression = $warehouse_product->quantity . $product->product->unit->operator . $product->sale_units->operator_value;
                                $convertedStock = eval("return $expression;");
                                $stock = $convertedStock + $product->quantity;
                                $secondExp = $stock . $product->sale_units->operator . $product->sale_units->operator_value;
                                $finalStock = eval("return $secondExp;");
                            } else {
                                $finalStock = $warehouse_product->quantity + $product->quantity;
                            }
                        } else {
                            $finalStock = $warehouse_product->quantity + $product->quantity;
                        }
                        // $productForUpdate = Product::find($product->product->id);
                        $warehouse_product->update(['quantity' => "$finalStock"]);
                        $product->delete();
                    }
                    // delete sales invoice payment
                    $salesInvoicePayment = SalesInvoicePayment::where('sale_invoice_id', $sale->invoice->id)->get();

                    foreach ($salesInvoicePayment as $payment) {
                        if ($payment->salesPayment->payment_method == 'Credit Store') {
                            $sale->customer->balance += $payment->paid_amount;
                            $sale->customer->save();
                            $sale->save();
                        }
                        $payment->salesPayment->delete();
                        $payment->delete();
                    }
                    // Delete the sale from your local database
                    SaleInvoice::where('sale_id', $id)->first()->delete();

                    $sale->delete();
                }
            }
            return response()->json(['status' => 200, 'message' => 'Sale Delete Successfully!']);
        }
    }

    public function getProductDetails(Request $request)
    {
        if (!$request->query) {
            return response()->json(['success' => false]);
        }
        $code = $request->input('query');

        $product = Product::where(function ($query) use ($code) {
            $query->where('sku', 'like', '%' . $code . '%')
                ->orWhere('name', 'like', '%' . $code . '%')
                ->orWhereHas('barcodes', function ($barcodeQuery) use ($code) {
                    $barcodeQuery->where('code', 'like', '%' . $code . '%');
                    $barcodeQuery->orWhere('name', 'like', '%' . $code . '%');
                });
        })->with('unit', 'sale_units', 'purchase_unit')->get();

        // $product = Product::with('unit')->get();

        if ($product) {
            return response()->json(['success' => true, 'product' => $product]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getProductDetailsByWarehouse(Request $request)
    {

        if (!$request->query) {
            return response()->json(['success' => false]);
        }
        $code = $request->input('query');

        $columns = [
            'products.id',
            'products.name',
            'products.sku',
            'products.barcode',
            'products.purchase_price',
            'products.sell_price',
            'products.brand_id',
            'products.category_id',
            'products.sub_category_id',
            'products.stock_alert',
            'products.product_type',
            'products.tax_type',
            'products.order_tax',
            'products.status',
            'products.imei_no',
            'products.product_unit',
            'products.sale_unit',
            'products.purchase_unit',
            'products.warehouse_id',
            'products.customer_id',
            'products.quantity',
            'products.shopify_id',
            'products.created_by',
            'products.updated_by',
            'products.deleted_by',
            'products.deleted_at',
            'products.created_at',
            'products.updated_at',
            'products.product_live',
            'products.new_product',
            'products.featured_product',
            'products.best_seller',
            'products.recommended',
            'products.product_weight_unit',
            'products.product_weight',
            'products.product_height',
            'products.product_width',
            'products.product_length',
            'products.product_dimension_unit',
        ];


        $product = Product::where(function ($query) use ($code) {
            $query->where('sku', 'like', '%' . $code . '%')
                ->orWhere('name', 'like', '%' . $code . '%')
                ->orWhereHas('barcodes', function ($barcodeQuery) use ($code) {
                    $barcodeQuery->where('code', 'like', '%' . $code . '%')
                        ->orWhere('name', 'like', '%' . $code . '%');
                });
        })
            ->with(['unit', 'sale_units', 'purchase_unit'])
            ->leftJoin('product_warehouses', function ($join) use ($request) {
                $join->on('products.id', '=', 'product_warehouses.product_id')
                    ->where('product_warehouses.warehouse_id', '=', $request->warehouse_id);
            })
            ->select(array_merge(
                $columns, // Manually specified columns from 'products'
                ['product_warehouses.quantity as warehouse_quantity']
            ))
            ->whereNotNull('product_warehouses.product_id') // Only get products with entries in product_warehouses
            ->get();

        // $product = Product::with('unit')->get();

        if ($product) {
            return response()->json(['success' => true, 'product' => $product]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    // public function getProductDetailsByWarehouse(Request $request)
    // {

    //     if (!$request->query) {
    //         return response()->json(['success' => false]);
    //     }
    //     $code = $request->input('query');


    //     $product = Product::where(function ($query) use ($code) {
    //         $query->where('sku', 'like', '%' . $code . '%')
    //             ->orWhere('name', 'like', '%' . $code . '%')
    //             ->orWhereHas('barcodes', function ($barcodeQuery) use ($code) {
    //                 $barcodeQuery->where('code', 'like', '%' . $code . '%')
    //                     ->orWhere('name', 'like', '%' . $code . '%');
    //             });
    //     })
    //         ->with(['unit', 'sale_units', 'purchase_unit'])
    //         ->leftJoin('product_warehouses', function ($join) use ($request) {
    //             $join->on('products.id', '=', 'product_warehouses.product_id')
    //                 ->where('product_warehouses.warehouse_id', '=', $request->warehouse_id);
    //         })
    //         ->select('products.*', 'product_warehouses.quantity as warehouse_quantity')
    //         ->whereNotNull('product_warehouses.product_id') // Only get products with entries in product_warehouses
    //         ->get();

    //     // $product = Product::with('unit')->get();

    //     if ($product) {
    //         return response()->json(['success' => true, 'product' => $product]);
    //     } else {
    //         return response()->json(['success' => false]);
    //     }
    // }

    public function getAllProductDetailsByWarehouse(Request $request)
    {


        $columns = [
            'products.id',
            'products.name',
            'products.sku',
            'products.barcode',
            'products.purchase_price',
            'products.sell_price',
            'products.brand_id',
            'products.category_id',
            'products.sub_category_id',
            'products.stock_alert',
            'products.product_type',
            'products.tax_type',
            'products.order_tax',
            'products.status',
            'products.imei_no',
            'products.product_unit',
            'products.sale_unit',
            'products.purchase_unit',
            'products.warehouse_id',
            'products.customer_id',
            'products.quantity',
            'products.shopify_id',
            'products.created_by',
            'products.updated_by',
            'products.deleted_by',
            'products.deleted_at',
            'products.created_at',
            'products.updated_at',
            'products.product_live',
            'products.new_product',
            'products.featured_product',
            'products.best_seller',
            'products.recommended',
        ];
        $products = Product::select($columns)
            ->with('category', 'unit', 'sale_units', 'images', 'product_warehouses')->whereHas('product_warehouses', function ($query) use ($request) {
            $query->where('warehouse_id', $request->warehouse_id);
            $query->where('quantity', '>', 0);
        })->get();

        if ($products) {
            return response()->json(['success' => true, 'products' => $products]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function saleCreate(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'customer_id' => 'required',
            'product_items' => 'required',

        ], [
            'customer_id.required' => 'Please select customer',
            'order_items.required' => 'Please select product',
        ]);


        // // Check stock levels before beginning the transaction
        $product_item = json_decode($request->product_items);
        foreach ($product_item as $itemData) {
            $product = Product::with('unit', 'sale_units', 'product_warehouses')->find($itemData->id);
            $warehouse_product = ProductWarehouse::where('product_id', $itemData->id)->where('warehouse_id', $request->warehouse_id)->first();

            $finalStock = 0;
            if ($product->product_type != 'service') {
                $convertedUnit = Unit::find($itemData->sale_unit);
                if ($product->product_unit != $itemData->sale_unit) {
                    if ($product->unit->parent_id == 0) {
                        $expression = $warehouse_product->quantity . $product->unit->operator . $convertedUnit->operator_value;
                        $convertedStock = eval("return $expression;");
                        $stock = $convertedStock - $itemData->quantity;
                        $secondExp = $stock . $convertedUnit->operator . $convertedUnit->operator_value;
                        $finalStock = eval("return $secondExp;");
                    }
                } else {
                    $finalStock = $warehouse_product->quantity - $itemData->quantity;
                }
            } else {
                $finalStock = $warehouse_product->quantity - $itemData->quantity;
            }

            if ($finalStock < 0) {
                // return response()->json(['error' => "Insufficient stock for product: {$product->name} only $warehouse_product->quantity left"], 400);
                return response()->json(['error' => "product: {$product->name} has only $warehouse_product->quantity stock left"], 400);
            }
        }

        try {
            DB::beginTransaction();


            // Generate a unique reference for the sale
            // $reference = substr(uniqid(), 0, 5);
            // append 'SAL-' to the reference
            $reference = mt_rand(10000000, 99999999);
            // Create new Sale entry
            $sale = new Sale();
            $sale->reference = $reference;
            $sale->date = now();
            $sale->customer_id = $request->customer_id;
            // use customer's tax no in ntn_no
            $customer = Customer::find($request->customer_id);
            $sale->ntn = $customer->tax_number ?? null;
            $sale->order_tax = $request->order_tax;
            $sale->discount = $request->discount;
            $sale->shipping = $request->shipping;
            $sale->status = "Completed";
            $sale->payment_method = $request->payment_method;
            $sale->payment_status = $request->payment_status;
            $sale->amount_recieved = $request->amount_recieved;
            $sale->amount_due = $request->amount_due;
            $sale->amount_pay = $request->amount_pay;
            $sale->notes = $request->notes;
            $sale->grand_total = $request->grand_total;
            $sale->warehouse_id = $request->warehouse_id;
            $sale->bank_account = $request->account_id;
            $sale->created_by = auth()->id();
            $sale->shipping_method = 'Store Pickup';
            $sale->save();

            $line_items = [];
            // Iterate over each product item
            $product_item = json_decode($request->product_items);
            foreach ($product_item as $itemData) {

                $productItem = new ProductItem();
                $productItem->sale_id = $sale->id;
                $productItem->product_id = $itemData->id;
                $productItem->quantity = $itemData->quantity;
                $productItem->discount = $itemData->discount ?? null;
                $productItem->order_tax = $itemData->order_tax;
                $productItem->tax_type = $itemData->tax_type;
                $productItem->discount_type = $itemData->discount_type ?? null;
                $productItem->price = $itemData->price;
                $productItem->sub_total = $itemData->subtotal;
                // $productItem->stock = $itemData->stock;
                $productItem->sale_unit = $itemData->sale_unit ?? null;
                $productItem->save();


                $shopify_product = Product::find($itemData->id);
                // if (!$shopify_product || !$shopify_product->variant_id) {  // Assuming variant_id is correctly stored in your Product model
                //     throw new \Exception('Variant not found or variant ID missing for product: ' . $itemData['id']);
                // }
                if (!$itemData->price) {
                    throw new \Exception('Price not provided for product: ' . $itemData->id);
                }
                $line_items[] = [
                    'variant_id' => $shopify_product->variants->first()->id ?? $shopify_product->shopify_id, // Use variant_id here
                    'quantity' => $itemData->quantity,
                    'price' => $itemData->price,
                    'name' => $shopify_product->name,
                    'title' => $shopify_product->sku
                ];

                $product = Product::with('unit', 'sale_units')->find($itemData->id);
                $warehouse_product = ProductWarehouse::where('product_id', $itemData->id)->where('warehouse_id', $request->warehouse_id)->first();

                $finalStock = 0;
                if ($product->product_type != 'service') {
                    $convertedUnit = Unit::find($itemData->sale_unit);
                    if ($product->product_unit != $itemData->sale_unit) {
                        if ($product->unit->parent_id == 0) {
                            $expression = $warehouse_product->quantity . $product->unit->operator . $convertedUnit->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock - $itemData->quantity;
                            $secondExp = $stock . $convertedUnit->operator . $convertedUnit->operator_value;
                            $finalStock = eval("return $secondExp;");
                        }
                    } else {
                        $finalStock = $warehouse_product->quantity - $itemData->quantity;
                    }
                } else {
                    $finalStock = $warehouse_product->quantity - $itemData->quantity;
                }

                $warehouse_product->update(['quantity' => "$finalStock"]);
            }




            // Check if Shopify integration is enabled
            $shopify_enable = Setting::first()->shopify_enable;
            if ($shopify_enable == 1) {
                $shopify_store = ShopifyStore::first();
                if (!$shopify_store) {
                    return redirect()->route('sales.index')->with('error', 'Shop not found.');
                }

                $shopDomain = $shopify_store->shop_domain;
                $accessToken = $shopify_store->access_token;
                $customer = Customer::find($request->customer_id);
                $client = new Client();

                $orderData = [
                    'email' => $customer->user->email,
                    'financial_status' => 'paid',
                    'fulfillment_status' => 'fulfilled',
                    'line_items' => $line_items,
                    'transactions' => [
                        [
                            'kind' => 'sale',
                            'status' => 'success',
                            'amount' => $sale->grand_total
                        ]
                    ]
                ];

                try {
                    $response = $client->request('POST', "https://{$shopDomain}/admin/api/2023-04/orders.json", [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'X-Shopify-Access-Token' => $accessToken,
                        ],
                        'body' => json_encode(['order' => $orderData]),
                    ]);

                    $shopifyOrder = json_decode($response->getBody()->getContents(), true);
                    if (isset($shopifyOrder['order']['line_items'])) {
                        foreach ($shopifyOrder['order']['line_items'] as $shopifyItem) {
                            $localItem = ProductItem::where('sale_id', $sale->id)
                                ->where('shopify_line_item_id', $shopifyItem['variant_id'])
                                ->first();

                            if ($localItem) {
                                $localItem->update(['shopify_line_item_id' => $shopifyItem['id']]);
                            }
                        }
                    }
                    if ($shopifyOrder['order']['id']) {
                        $sale->shopify_order_id = $shopifyOrder['order']['id'];
                        $sale->save();
                    }
                } catch (\Exception $e) {
                    return redirect()->route('sales.index')->with('error', 'Failed to create order in Shopify: ' . $e->getMessage());
                }
            }


            $invoice =  new SaleInvoice();
            $invoice->invoice_id = $reference;
            $invoice->sale_id = $sale->id;
            $invoice->user_id = auth()->user()->id;
            // $invoice->go_green = $request->go_green_input;
            $invoice->save();

            if ($request->payment_method == 'Cash') {
                $salePayment = SalesPayment::create([
                    'customer_id' => $sale->customer_id,
                    'account_id' => $sale->bank_account ?? null,
                    'payment_date' => $sale->date,
                    'status' => 1,
                    'total_pay' => $sale->amount_recieved,
                    'note' => $request->payment_note,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'payment_method' => $request->payment_method,
                    'card_id' => null
                ]);

                SalesInvoicePayment::create([
                    'sale_invoice_id' => $invoice->id,
                    'sales_payment_id' => $salePayment->id,
                    'paid_amount' => $sale->amount_recieved,
                ]);
            } elseif ($request->payment_method == 'Card') {
                $salePayment = SalesPayment::create([
                    'customer_id' => $sale->customer_id,
                    'account_id' => null,
                    'payment_date' => $sale->date,
                    'status' => 1,
                    'total_pay' => $sale->amount_recieved,
                    'note' => $request->payment_note,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'payment_method' => $request->payment_method,
                    'card_id' => $request->card_id ?? null
                ]);

                SalesInvoicePayment::create([
                    'sale_invoice_id' => $invoice->id,
                    'sales_payment_id' => $salePayment->id,
                    'paid_amount' => $sale->amount_recieved,
                ]);
            }

            $customer = Customer::find($sale->customer_id);
            SaleShippingAddress::create([
                'sale_id' => $sale->id,
                'name' => $customer->user->name,
                'email' => $customer->user->email,
                'address' => $customer->user->address ?? 'address',
                'city' => $customer->city ?? 'city',
                'country' => $customer->country ?? 'country',
                'state' => $customer->state ?? 'state',
                'zip_code' => $customer->user->zip_code ?? 123456,
                'contact_no' => $customer->user->contact_no ?? 123456789,
            ]);

            // // add credit store if amount received is greater than grand total
            // if($sale->amount_recieved > $sale->grand_total){
            //     $additional_balance = $sale->amount_recieved - $sale->grand_total;
            //     CreditActivity::create([
            //         'customer_id' => $sale->customer_id,
            //         'action' => 'Added',
            //         'credit_balance' => $customer->balance + $additional_balance,
            //         'added_deducted' => $additional_balance,
            //         'comment' => 'Added balance for Order',
            //     ]);
            //     $customer->balance += $additional_balance;

            //     $customer->save();
            // }


            DB::commit();
            return response()->json(['message' => 'Sale successfully created', 'reference' => $reference], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->route('sales.index')->with(['error' => 'Error occurred: ' . $e->getMessage()], 500);
            return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }


    public function saleSearch(Request $req)
    {
        if (!$req->query) {
            return response()->json(['success' => false]);
        }

        $searchTerm = $req->input('query');

        // Search sales by name
        $sales = Sale::search($searchTerm)->get();
        $users = User::search($searchTerm)->get();
        $users->load('customer.sales', 'warehouse.sales');
        // return $users->customer->sales;
        $users = $users ? $users : collect();

        $salesByCustomer = collect();
        $salesByWarehouse = collect();
        foreach ($users as $user) {
            if ($user->customer) {
                foreach ($user->customer->sales as $sale) {
                    $salesByCustomer->push($sale);
                }
            }
            if ($user->warehouse) {
                foreach ($user->warehouse->sales as $sale) {
                    $salesByWarehouse->push($sale);
                }
            }
        }

        $shipment = Shipment::search($searchTerm)->get();
        $shipment->load('sales');
        $shipment = $shipment ? $shipment : collect();
        $salesByShipping = [];
        foreach ($shipment as $ship) {
            $salesByShipping[] = $ship->sales;
        }



        $mergedSales = $sales->merge($salesByCustomer)->merge($salesByWarehouse)->merge($salesByShipping)->unique('id');


        if ($mergedSales) {
            return response()->json(['success' => true, 'sales' => $mergedSales]);
        } else {
            return response()->json(['success' => false]);
        }
    }



    public function sendInvoiceToCustomer($email, $id)
    {

        try {
            $sale = Sale::find($id);
            $sale->load('productItems', 'customer', 'warehouse', 'invoice');
            $totalDue = $sale->customer->sales->sum('amount_due');
            $job = new SendInvoiceJob($sale, $email, getLogo(), $totalDue);
            dispatch($job);
            Log::info('Email sent to: ' . $email);
            return redirect()->back()->with('success', 'Email sent successfully');
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
