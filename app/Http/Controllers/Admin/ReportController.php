<?php

namespace App\Http\Controllers\Admin;

use Log;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Warehouse;
use App\Models\SaleReturn;
use App\Models\ProductItem;
use App\Models\ManualReturn;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\DepositCategory;
use App\Models\ExpenseCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProductInventory;

use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\SalesInvoicePayment;
use App\Http\Controllers\BaseController;

// use Illuminate\Support\Facades\Log;

class ReportController extends BaseController
{
    //generate method for saleReport

    public function todayReport()
    {


        if (auth()->user()->hasRole(['Manager', 'Cashier'])) {
            $todayPurchase = Purchase::whereDate('created_at', today())->where('warehouse_id', auth()->user()->warehouse_id)->sum('grand_total');
            $todaySale = Sale::whereDate('created_at', today())->where('warehouse_id', auth()->user()->warehouse_id)->sum('grand_total');
            $todayExpense = Expense::whereDate('created_at', today())->where('warehouse_id', auth()->user()->warehouse_id)->sum('amount');

            $todayPurchaseReturn = PurchaseReturn::whereDate('created_at', today())
                ->whereHas('purchase', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })->sum('amount_due');

            $todaySaleReturn = SaleReturn::whereDate('created_at', today())
                ->whereHas('sales', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })->sum('amount_due');

            $todayManualSaleReturn = ManualReturn::whereDate('created_at', today())->where('warehouse_id', auth()->user()->warehouse_id)->sum('amount_due');
            $todaySaleReturn += $todayManualSaleReturn;
            $todaySaleDiscount = Sale::whereDate('created_at', today())->where('warehouse_id', auth()->user()->warehouse_id)->sum('discount');
            $todayPurchaseDiscount = Purchase::whereDate('created_at', today())->where('warehouse_id', auth()->user()->warehouse_id)->sum('discount');
            $todayLoanInterest = Sale::whereDate('created_at', today())->where('warehouse_id', auth()->user()->warehouse_id)->sum('amount_due');


            $todayCashPayment = Sale::whereDate('created_at', today())->where('payment_method', 'Cash')->where('warehouse_id', auth()->user()->warehouse_id)->sum('amount_pay');
            $todayCardPayment = Sale::whereDate('created_at', today())->where('payment_method', 'Card')->where('warehouse_id', auth()->user()->warehouse_id)->sum('amount_pay');
        } else {

            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');

                $todayPurchase = Purchase::whereDate('created_at', today())->where('warehouse_id', $warehouseId)->sum('grand_total');
                $todaySale = Sale::whereDate('created_at', today())->where('warehouse_id', $warehouseId)->sum('grand_total');
                $todayExpense = Expense::whereDate('created_at', today())->where('warehouse_id', $warehouseId)->sum('amount');
                $todayPurchaseReturn = PurchaseReturn::whereDate('created_at', today())
                    ->whereHas('purchase', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    })->sum('amount_due');
                $todaySaleReturn = SaleReturn::whereDate('created_at', today())
                    ->whereHas('sales', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    })->sum('amount_due');
                $todayManualSaleReturn = ManualReturn::whereDate('created_at', today())->where('warehouse_id', $warehouseId)->sum('amount_due');
                $todaySaleReturn += $todayManualSaleReturn;
                $todaySaleDiscount = Sale::whereDate('created_at', today())->where('warehouse_id', $warehouseId)->sum('discount');
                $todayPurchaseDiscount = Purchase::whereDate('created_at', today())->where('warehouse_id', $warehouseId)->sum('discount');
                $todayLoanInterest = Sale::whereDate('created_at', today())->where('warehouse_id', $warehouseId)->sum('amount_due');
                $todayCardPayment = Sale::whereDate('created_at', today())->where('payment_method', 'Card')->where('warehouse_id', $warehouseId)->sum('amount_pay');
                $todayCashPayment = Sale::whereDate('created_at', today())->where('payment_method', 'Cash')->where('warehouse_id', $warehouseId)->sum('amount_pay');
            } else {
                $todayPurchase = Purchase::whereDate('created_at', today())->sum('grand_total');
                $todaySale = Sale::whereDate('created_at', today())->sum('grand_total');
                $todayExpense = Expense::whereDate('created_at', today())->sum('amount');
                $todayPurchaseReturn = PurchaseReturn::whereDate('created_at', today())->sum('amount_due');
                $todaySaleReturn = SaleReturn::whereDate('created_at', today())->sum('amount_due');
                $todayManualSaleReturn = ManualReturn::whereDate('created_at', today())->sum('amount_due');
                $todaySaleReturn += $todayManualSaleReturn;
                $todaySaleDiscount = Sale::whereDate('created_at', today())->sum('discount');
                $todayPurchaseDiscount = Purchase::whereDate('created_at', today())->sum('discount');
                $todayLoanInterest = Sale::whereDate('created_at', today())->sum('amount_due');


                $todayCashPayment = Sale::whereDate('created_at', today())->where('payment_method', 'Cash')->sum('amount_pay');
                // dd($todayCashPayment);
                $todayCardPayment = Sale::whereDate('created_at', today())->where('payment_method', 'Card')->sum('amount_pay');
            }
        }

        // $todayPurchase = Purchase::whereDate('created_at', today())->sum('grand_total');
        // // dd($todayPurchase);
        // $todaySale = Sale::whereDate('created_at', today())->sum('grand_total');
        // $todayExpense = Expense::whereDate('created_at', today())->sum('amount');
        // $todayPurchaseReturn = PurchaseReturn::whereDate('created_at', today())->sum('amount_due');
        // $todaySaleReturn = SaleReturn::whereDate('created_at', today())->sum('amount_due');
        // $todayManualSaleReturn = ManualReturn::whereDate('created_at', today())->sum('amount_due');
        // $todaySaleReturn += $todayManualSaleReturn;
        // $todaySaleDiscount = Sale::whereDate('created_at', today())->sum('discount');
        // $todayPurchaseDiscount = Purchase::whereDate('created_at', today())->sum('discount');
        // $todayLoanInterest = Sale::whereDate('created_at', today())->sum('amount_due');

        $todaySales = $todaySale - $todaySaleReturn - $todayManualSaleReturn;
        $todayExpenses = $todayPurchase + $todayExpense + $todayPurchaseReturn + $todayLoanInterest;
        $todayDiscounts = $todaySaleDiscount + $todayPurchaseDiscount;
        $COGS = $todayPurchase - $todayPurchaseReturn - $todayDiscounts;
        $grossProfit = $todaySales - $COGS;
        $netLoss = $grossProfit - $todayExpenses;



        return view('back.reports.today-report', compact(
            'todayPurchase',
            'todaySale',
            'todayExpense',
            'todayPurchaseReturn',
            'todaySaleReturn',
            'todaySaleDiscount',
            'todayPurchaseDiscount',
            'todayLoanInterest',
            'grossProfit',
            'netLoss',
            'todayCashPayment',
            'todayCardPayment',
        ));
    }


    public function saleReport()
    {

        if (auth()->user()->hasRole('Manager')) {
            $sales = Sale::where('warehouse_id', auth()->user()->warehouse_id ?? null)->get();
        } else {
            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $sales = Sale::where('warehouse_id', $warehouseId)->get();
            } else {
                $sales = Sale::all();
            }
        }

        return view('back.reports.sale', compact('sales'));
    }

    // and purchase report
    public function purchaseReport()
    {

        if (auth()->user()->hasRole('Manager')) {
            $purchases = Purchase::where('warehouse_id', auth()->user()->warehouse_id ?? null)->get();
        } else {

            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $purchases = Purchase::where('warehouse_id', $warehouseId)->get();
            } else {
                $purchases = Purchase::all();
            }
        }

        return view('back.reports.purchase', compact('purchases'));
    }

    public function saleFilter(Request $request)
    {
        $fromDate = date('Y-m-d H:i:s', strtotime($request->start));
        $toDate = date('Y-m-d H:i:s', strtotime($request->end));
        // dd($fromDate, $toDate, $request->all());


        if (auth()->user()->hasRole('Manager')) {
            $sales = Sale::whereBetween('created_at', [$fromDate, $toDate])->get()->map(function ($sale) {

                if (auth()->user()->warehouse_id == $sale->warehouse_id) {
                    return [
                        'checkbox' => '', // Since it's not directly linked to data, we'll handle it in DataTables rendering
                        'date' => $sale->date,
                        'reference' => $sale->reference,
                        'customer' => optional($sale->customer)->user->name ?? '',
                        'warehouse' => optional($sale->warehouse)->users->name ?? '',
                        'status' => $sale->status ?? '',
                        'grand_total' => $sale->grand_total ?? '0.00',
                        'amount_recieved' => $sale->amount_recieved ?? '0.00',
                        'change_return' => $sale->change_return ?? '0.00',
                        'payment_status' => $sale->payment_status ?? '',
                    ];
                }
            })->filter();
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $sales = Sale::whereBetween('created_at', [$fromDate, $toDate])->get()->map(function ($sale) use ($warehouseId) {
                    if ($warehouseId == $sale->warehouse_id) {
                        return [
                            'checkbox' => '', // Since it's not directly linked to data, we'll handle it in DataTables rendering
                            'date' => $sale->date,
                            'reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? '',
                            'warehouse' => optional($sale->warehouse)->users->name ?? '',
                            'status' => $sale->status ?? '',
                            'grand_total' => $sale->grand_total ?? '0.00',
                            'amount_recieved' => $sale->amount_recieved ?? '0.00',
                            'change_return' => $sale->change_return ?? '0.00',
                            'payment_status' => $sale->payment_status ?? '',
                        ];
                    }
                })->filter();
            } else {
                $sales = Sale::whereBetween('created_at', [$fromDate, $toDate])->get()->map(function ($sale) {
                    return [
                        'checkbox' => '', // Since it's not directly linked to data, we'll handle it in DataTables rendering
                        'date' => $sale->date,
                        'reference' => $sale->reference,
                        'customer' => optional($sale->customer)->user->name ?? '',
                        'warehouse' => optional($sale->warehouse)->users->name ?? '',
                        'status' => $sale->status ?? '',
                        'grand_total' => $sale->grand_total ?? '0.00',
                        'amount_recieved' => $sale->amount_recieved ?? '0.00',
                        'change_return' => $sale->change_return ?? '0.00',
                        'payment_status' => $sale->payment_status ?? '',
                    ];
                });
            }

            // $sales = Sale::whereBetween('created_at', [$fromDate, $toDate])->get()->map(function ($sale) {
            //     return [
            //         'checkbox' => '', // Since it's not directly linked to data, we'll handle it in DataTables rendering
            //         'date' => $sale->date,
            //         'reference' => $sale->reference,
            //         'customer' => optional($sale->customer)->user->name ?? '',
            //         'warehouse' => optional($sale->warehouse)->users->name ?? '',
            //         'status' => $sale->status ?? '',
            //         'grand_total' => $sale->grand_total ?? '0.00',
            //         'amount_recieved' => $sale->amount_recieved ?? '0.00',
            //         'change_return' => $sale->change_return ?? '0.00',
            //         'payment_status' => $sale->payment_status ?? '',
            //     ];
            // });
        }


        return ['data' => $sales];
    }
    public function purchaseFilter(Request $request)
    {
        $fromDate = date('Y-m-d H:i:s', strtotime($request->start));
        $toDate = date('Y-m-d H:i:s', strtotime($request->end));

        if (auth()->user()->hasRole('Manager')) {
            $purchases = Purchase::whereBetween('created_at', [$fromDate, $toDate])->get()->map(function ($sale) {
                if (auth()->user()->warehouse_id == $sale->warehouse_id) {
                    return [
                        'checkbox' => '', // Since it's not directly linked to data, we'll handle it in DataTables rendering
                        'date' => $sale->date,
                        'reference' => $sale->reference,
                        'vendor' => optional($sale->vendor)->user->name ?? '',
                        'warehouse' => optional($sale->warehouse)->users->name ?? '',
                        'status' => $sale->status ?? '',
                        'grand_total' => $sale->grand_total ?? '0.00',
                        'amount_recieved' => $sale->amount_recieved ?? '0.00',
                        'change_return' => $sale->change_return ?? '0.00',
                        'payment_status' => $sale->payment_status ?? '',
                    ];
                }
            });
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $purchases = Purchase::whereBetween('created_at', [$fromDate, $toDate])->get()->map(function ($sale) use ($warehouseId) {
                    if ($sale->warehouse_id == $warehouseId) {
                        return [
                            'checkbox' => '', // Since it's not directly linked to data, we'll handle it in DataTables rendering
                            'date' => $sale->date,
                            'reference' => $sale->reference,
                            'vendor' => optional($sale->vendor)->user->name ?? '',
                            'warehouse' => optional($sale->warehouse)->users->name ?? '',
                            'status' => $sale->status ?? '',
                            'grand_total' => $sale->grand_total ?? '0.00',
                            'amount_recieved' => $sale->amount_recieved ?? '0.00',
                            'change_return' => $sale->change_return ?? '0.00',
                            'payment_status' => $sale->payment_status ?? '',
                        ];
                    }
                    return null;
                })->filter();
            } else {
                $purchases = Purchase::whereBetween('created_at', [$fromDate, $toDate])->get()->map(function ($sale) {
                    return [
                        'checkbox' => '', // Since it's not directly linked to data, we'll handle it in DataTables rendering
                        'date' => $sale->date,
                        'reference' => $sale->reference,
                        'vendor' => optional($sale->vendor)->user->name ?? '',
                        'warehouse' => optional($sale->warehouse)->users->name ?? '',
                        'status' => $sale->status ?? '',
                        'grand_total' => $sale->grand_total ?? '0.00',
                        'amount_recieved' => $sale->amount_recieved ?? '0.00',
                        'change_return' => $sale->change_return ?? '0.00',
                        'payment_status' => $sale->payment_status ?? '',
                    ];
                });
            }
        }

        return ['data' => $purchases];
    }

    public function productSaleReport()
    {
        if (auth()->user()->hasRole('Manager')) {
            $sales = Sale::with('productItems')->where('warehouse_id', auth()->user()->warehouse_id)->get();
        } else {
            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $sales = Sale::with('productItems')->where('warehouse_id', $warehouseId)->get();
            } else {
                $sales = Sale::with('productItems')->get();
            }
        }
        return view('back.reports.product_sale', compact('sales'));
    }
    public function productPurchaseReport()
    {

        if (auth()->user()->hasRole('Manager')) {
            $purchases = Purchase::with('purchaseItems')->where('warehouse_id', auth()->user()->warehouse_id)->get();
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $purchases = Purchase::with('purchaseItems')->where('warehouse_id', $warehouseId)->get();
            } else {
                $purchases = Purchase::with('purchaseItems')->get();
            }
        }

        return view('back.reports.product_purchase', compact('purchases'));
    }
    public function saleProductFilter(Request $request)
    {

        // Set the time for the start date to the start of the day (00:00:00)
        $fromDate = date('Y-m-d 00:00:00', strtotime($request->start));
        // Set the time for the end date to the end of the day (23:59:59)
        $toDate = date('Y-m-d 23:59:59', strtotime($request->end));



        if (auth()->user()->hasRole('Manager')) {
            $sales = Sale::with(['productItems', 'customer.user', 'warehouse.users'])
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->get()
                ->flatMap(function ($sale) {
                    return $sale->productItems->map(function ($item) use ($sale) {
                        return [
                            'checkbox' => '', // Handled in DataTables rendering
                            'date' => $sale->date,
                            'reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? 'N/A',
                            'warehouse' => optional($sale->warehouse)->users->name ?? 'N/A',
                            'bin_location' => $item->product->ailse ?? ''."-". $item->product->rack ?? ''."-".$item->product->shelf ?? ''."-".$item->product->bin ?? ''."-",
                            'product' => $item->product->name ?? 'N/A', // Ensure product is eager loaded or handled properly
                            'quantity' => $item->quantity,
                            'grand_total' => $sale->grand_total,
                        ];
                    });
                });
        } else {
            $sales = Sale::with(['productItems', 'customer.user', 'warehouse.users'])
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->get()
                ->flatMap(function ($sale) {
                    return $sale->productItems->map(function ($item) use ($sale) {
                        return [
                            'checkbox' => '', // Handled in DataTables rendering
                            'date' => $sale->date,
                            'reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? 'N/A',
                            'warehouse' => optional($sale->warehouse)->users->name ?? 'N/A',
                            'bin_location' => $item->product->ailse ?? ''."-". $item->product->rack ?? ''."-".$item->product->shelf ?? ''."-".$item->product->bin ?? ''."-",
                            'product' => $item->product->name ?? 'N/A', // Ensure product is eager loaded or handled properly
                            'quantity' => $item->quantity,
                            'grand_total' => $sale->grand_total,
                        ];
                    });
                });
        }


        return ['data' => $sales];
    }

    public function purchaseProductFilter(Request $request)
    {
        $fromDate = date('Y-m-d H:i:s', strtotime($request->start));
        $toDate = date('Y-m-d H:i:s', strtotime($request->end));

        if (auth()->user()->hasRole('Manager')) {

            $purchases = Purchase::with(['purchaseItems', 'vendor.user', 'warehouse.users'])
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->get()

                ->flatMap(function ($purchase) {
                    return $purchase->purchaseItems->map(function ($item) use ($purchase) {
                        return [
                            'checkbox' => '', // Handled in DataTables rendering
                            'date' => $purchase->date,
                            'reference' => $purchase->reference,
                            'vendor' => optional($purchase->vendor)->user->name ?? 'N/A',
                            'warehouse' => optional($purchase->warehouse)->users->name ?? 'N/A',
                            'product' => $item->product->name ?? 'N/A', // Ensure product is eager loaded or handled properly
                            'quantity' => $item->quantity,
                            'grand_total' => $purchase->grand_total,
                        ];
                    });
                });
        } else {
            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $purchases = Purchase::with(['purchaseItems', 'vendor.user', 'warehouse.users'])
                    ->where('warehouse_id', $warehouseId)
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->get()

                    ->flatMap(function ($purchase) {
                        return $purchase->purchaseItems->map(function ($item) use ($purchase) {
                            return [
                                'checkbox' => '', // Handled in DataTables rendering
                                'date' => $purchase->date,
                                'reference' => $purchase->reference,
                                'vendor' => optional($purchase->vendor)->user->name ?? 'N/A',
                                'warehouse' => optional($purchase->warehouse)->users->name ?? 'N/A',
                                'product' => $item->product->name ?? 'N/A', // Ensure product is eager loaded or handled properly
                                'quantity' => $item->quantity,
                                'grand_total' => $purchase->grand_total,
                            ];
                        });
                    });
            } else {
                $purchases = Purchase::with(['purchaseItems', 'vendor.user', 'warehouse.users'])
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->get()

                    ->flatMap(function ($purchase) {
                        return $purchase->purchaseItems->map(function ($item) use ($purchase) {
                            return [
                                'checkbox' => '', // Handled in DataTables rendering
                                'date' => $purchase->date,
                                'reference' => $purchase->reference,
                                'vendor' => optional($purchase->vendor)->user->name ?? 'N/A',
                                'warehouse' => optional($purchase->warehouse)->users->name ?? 'N/A',
                                'product' => $item->product->name ?? 'N/A', // Ensure product is eager loaded or handled properly
                                'quantity' => $item->quantity,
                                'grand_total' => $purchase->grand_total,
                            ];
                        });
                    });
            }
        }

        return ['data' => $purchases];
    }

    public function productReport()
    {

        if (auth()->user()->HasRole('Manager')) {

            $product_items = ProductItem::with('unit', 'unit.parentUnit')
                ->whereHas('sale', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })
                ->select(
                    'product_items.product_id',
                    'units.parent_id', // get parent_id from units table
                    DB::raw('SUM(CASE WHEN units.operator = "*" THEN product_items.quantity * units.operator_value WHEN units.operator = "/" THEN product_items.quantity / units.operator_value ELSE product_items.quantity END) as total_sales'),
                    DB::raw('SUM(sales.grand_total) as total_amount')
                )
                ->join('sales', 'product_items.sale_id', '=', 'sales.id')

                ->leftJoin('units', 'product_items.sale_unit', '=', 'units.id') // Changed to leftJoin
                ->groupBy('product_items.product_id', 'units.parent_id')
                ->get();

            $parent_units = Unit::whereIn('id', $product_items->pluck('parent_id')->filter())
                ->get()
                ->keyBy('id');

            // Fetch product data
            $products = Product::whereIn('id', $product_items->pluck('product_id'))
                ->get()
                ->keyBy('id');

            // Merge product and unit data into product items results
            $product_reports = $product_items->map(function ($item) use ($products, $parent_units) {
                $item->product = $products[$item->product_id]->name ?? '';
                $item->sku = $products[$item->product_id]->sku ?? '';
                $item->id = $products[$item->product_id]->id ?? '';
                $item->unit_name = isset($parent_units[$item->parent_id]) ? $parent_units[$item->parent_id]->short_name : null; // Check if parent unit exists
                return $item;
            });
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $product_items = ProductItem::with('unit', 'unit.parentUnit')
                    ->whereHas('sale', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    })
                    ->select(
                        'product_items.product_id',
                        'units.parent_id', // get parent_id from units table
                        DB::raw('SUM(CASE WHEN units.operator = "*" THEN product_items.quantity * units.operator_value WHEN units.operator = "/" THEN product_items.quantity / units.operator_value ELSE product_items.quantity END) as total_sales'),
                        DB::raw('SUM(sales.grand_total) as total_amount')
                    )
                    ->join('sales', 'product_items.sale_id', '=', 'sales.id')

                    ->leftJoin('units', 'product_items.sale_unit', '=', 'units.id') // Changed to leftJoin
                    ->groupBy('product_items.product_id', 'units.parent_id')
                    ->get();

                $parent_units = Unit::whereIn('id', $product_items->pluck('parent_id')->filter())
                    ->get()
                    ->keyBy('id');

                // Fetch product data
                $products = Product::whereIn('id', $product_items->pluck('product_id'))
                    ->get()
                    ->keyBy('id');

                // Merge product and unit data into product items results
                $product_reports = $product_items->map(function ($item) use ($products, $parent_units) {
                    $item->product = $products[$item->product_id]->name ?? '';
                    $item->sku = $products[$item->product_id]->sku ?? '';
                    $item->id = $products[$item->product_id]->id ?? '';
                    $item->unit_name = isset($parent_units[$item->parent_id]) ? $parent_units[$item->parent_id]->short_name : null; // Check if parent unit exists
                    return $item;
                });
            } else {

                $product_items = ProductItem::with('unit', 'unit.parentUnit')->select(
                    'product_items.product_id',
                    'units.parent_id', // get parent_id from units table
                    DB::raw('SUM(CASE WHEN units.operator = "*" THEN product_items.quantity * units.operator_value WHEN units.operator = "/" THEN product_items.quantity / units.operator_value ELSE product_items.quantity END) as total_sales'),
                    DB::raw('SUM(sales.grand_total) as total_amount')
                )
                    ->join('sales', 'product_items.sale_id', '=', 'sales.id')
                    ->leftJoin('units', 'product_items.sale_unit', '=', 'units.id') // Changed to leftJoin
                    ->groupBy('product_items.product_id', 'units.parent_id')
                    ->get();

                $parent_units = Unit::whereIn('id', $product_items->pluck('parent_id')->filter())
                    ->get()
                    ->keyBy('id');

                // Fetch product data
                $products = Product::whereIn('id', $product_items->pluck('product_id'))
                    ->get()
                    ->keyBy('id');

                // Merge product and unit data into product items results
                $product_reports = $product_items->map(function ($item) use ($products, $parent_units) {
                    $item->product = $products[$item->product_id]->name ?? '';
                    $item->sku = $products[$item->product_id]->sku ?? '';
                    $item->id = $products[$item->product_id]->id ?? '';
                    $item->unit_name = isset($parent_units[$item->parent_id]) ? $parent_units[$item->parent_id]->short_name : null; // Check if parent unit exists
                    return $item;
                });
            }
        }



        return view('back.reports.product', compact('product_reports'));
    }

    public function productFilter(Request $request)
    {
        $fromDate = date('Y-m-d H:i:s', strtotime($request->start));
        $toDate = date('Y-m-d H:i:s', strtotime($request->end));


        if (auth()->user()->hasRole('Manager')) {
            $product_items = ProductItem::with('unit', 'unit.parentUnit')
                ->whereHas('sale', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })
                ->select(
                    'product_items.product_id',
                    'units.parent_id', // get parent_id from units table
                    DB::raw('SUM(CASE WHEN units.operator = "*" THEN product_items.quantity * units.operator_value WHEN units.operator = "/" THEN product_items.quantity / units.operator_value ELSE product_items.quantity END) as total_sales'),
                    DB::raw('SUM(sales.grand_total) as total_amount')
                )
                ->join('sales', function ($join) use ($fromDate, $toDate) {
                    $join->on('product_items.sale_id', '=', 'sales.id')
                        ->whereBetween('sales.created_at', [$fromDate, $toDate]);
                })
                ->leftJoin('units', 'product_items.sale_unit', '=', 'units.id') // Changed to leftJoin
                ->groupBy('product_items.product_id', 'units.parent_id')
                ->get();

            $parent_units = Unit::whereIn('id', $product_items->pluck('parent_id')->filter())
                ->get()
                ->keyBy('id');

            // Fetch product data
            $products = Product::whereIn('id', $product_items->pluck('product_id'))
                ->get()
                ->keyBy('id');
            // Merge product and unit data into product items results
            $product_reports = $product_items->map(function ($item) use ($products, $parent_units) {
                $item->product = $products[$item->product_id]->name ?? '';
                $item->sku = $products[$item->product_id]->sku ?? '';
                $item->id = $products[$item->product_id]->id ?? '';
                $item->unit_name = isset($parent_units[$item->parent_id]) ? $parent_units[$item->parent_id]->short_name : null; // Check if parent unit exists
                return $item;
            });
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $product_items = ProductItem::with('unit', 'unit.parentUnit')
                    ->whereHas('sale', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    })
                    ->select(
                        'product_items.product_id',
                        'units.parent_id', // get parent_id from units table
                        DB::raw('SUM(CASE WHEN units.operator = "*" THEN product_items.quantity * units.operator_value WHEN units.operator = "/" THEN product_items.quantity / units.operator_value ELSE product_items.quantity END) as total_sales'),
                        DB::raw('SUM(sales.grand_total) as total_amount')
                    )
                    ->join('sales', function ($join) use ($fromDate, $toDate) {
                        $join->on('product_items.sale_id', '=', 'sales.id')
                            ->whereBetween('sales.created_at', [$fromDate, $toDate]);
                    })
                    ->leftJoin('units', 'product_items.sale_unit', '=', 'units.id') // Changed to leftJoin
                    ->groupBy('product_items.product_id', 'units.parent_id')
                    ->get();

                $parent_units = Unit::whereIn('id', $product_items->pluck('parent_id')->filter())
                    ->get()
                    ->keyBy('id');

                // Fetch product data
                $products = Product::whereIn('id', $product_items->pluck('product_id'))
                    ->get()
                    ->keyBy('id');
                // Merge product and unit data into product items results
                $product_reports = $product_items->map(function ($item) use ($products, $parent_units) {
                    $item->product = $products[$item->product_id]->name ?? '';
                    $item->sku = $products[$item->product_id]->sku ?? '';
                    $item->id = $products[$item->product_id]->id ?? '';
                    $item->unit_name = isset($parent_units[$item->parent_id]) ? $parent_units[$item->parent_id]->short_name : null; // Check if parent unit exists
                    return $item;
                });
            } else {


                $product_items = ProductItem::with('unit', 'unit.parentUnit')->select(
                    'product_items.product_id',
                    'units.parent_id', // get parent_id from units table
                    DB::raw('SUM(CASE WHEN units.operator = "*" THEN product_items.quantity * units.operator_value WHEN units.operator = "/" THEN product_items.quantity / units.operator_value ELSE product_items.quantity END) as total_sales'),
                    DB::raw('SUM(sales.grand_total) as total_amount')
                )
                    ->join('sales', function ($join) use ($fromDate, $toDate) {
                        $join->on('product_items.sale_id', '=', 'sales.id')
                            ->whereBetween('sales.created_at', [$fromDate, $toDate]);
                    })
                    ->leftJoin('units', 'product_items.sale_unit', '=', 'units.id') // Changed to leftJoin
                    ->groupBy('product_items.product_id', 'units.parent_id')
                    ->get();

                $parent_units = Unit::whereIn('id', $product_items->pluck('parent_id')->filter())
                    ->get()
                    ->keyBy('id');

                // Fetch product data
                $products = Product::whereIn('id', $product_items->pluck('product_id'))
                    ->get()
                    ->keyBy('id');
                // Merge product and unit data into product items results
                $product_reports = $product_items->map(function ($item) use ($products, $parent_units) {
                    $item->product = $products[$item->product_id]->name ?? '';
                    $item->sku = $products[$item->product_id]->sku ?? '';
                    $item->id = $products[$item->product_id]->id ?? '';
                    $item->unit_name = isset($parent_units[$item->parent_id]) ? $parent_units[$item->parent_id]->short_name : null; // Check if parent unit exists
                    return $item;
                });
            }
        }




        return ['data' => $product_reports];
    }

    public function productShow($id)
    {

        $product = Product::find($id);
        if (auth()->user()->hasRole('Manager')) {


            $product_items = ProductItem::where('product_id', $id)
                ->whereHas('sale', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })
                ->get();
            $product_items->load('sale', 'product');
            $product_sale = $product_items->map(function ($item) {
                return [
                    'id' => $item->sale->id, // Added for DataTables 'checkbox
                    'date' => $item->sale->date,
                    'reference' => $item->sale->reference,
                    'product_name' => $item->product->name,
                    'customer' => optional($item->sale->customer)->user->name ?? '',
                    'warehouse' => optional($item->sale->warehouse)->users->name ?? '',
                    'quantity' => $item->quantity,
                    'sub_total' => $item->sub_total,
                ];
            });
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');

                $product_items = ProductItem::where('product_id', $id)
                    ->whereHas('sale', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    })
                    ->get();
                $product_items->load('sale', 'product');
                $product_sale = $product_items->map(function ($item) {
                    return [
                        'id' => $item->sale->id, // Added for DataTables 'checkbox
                        'date' => $item->sale->date,
                        'reference' => $item->sale->reference,
                        'product_name' => $item->product->name,
                        'customer' => optional($item->sale->customer)->user->name ?? '',
                        'warehouse' => optional($item->sale->warehouse)->users->name ?? '',
                        'quantity' => $item->quantity,
                        'sub_total' => $item->sub_total,
                    ];
                });
            } else {
                $product_items = ProductItem::where('product_id', $id)->get();
                $product_items->load('sale', 'product');
                $product_sale = $product_items->map(function ($item) {
                    return [
                        'id' => $item->sale->id, // Added for DataTables 'checkbox
                        'date' => $item->sale->date,
                        'reference' => $item->sale->reference,
                        'product_name' => $item->product->name,
                        'customer' => optional($item->sale->customer)->user->name ?? '',
                        'warehouse' => optional($item->sale->warehouse)->users->name ?? '',
                        'quantity' => $item->quantity,
                        'sub_total' => $item->sub_total,
                    ];
                });
            }
        }



        return view('back.reports.product_show', compact('product', 'product_items', 'product_sale'));
    }

    public function productSaleFilter(Request $req)
    {
        // dd($req->all());
        $fromDate = date('Y-m-d H:i:s', strtotime($req->start));
        $toDate = date('Y-m-d H:i:s', strtotime($req->end));





        $product_items = ProductItem::whereBetween('created_at', [$fromDate, $toDate])->where('product_id', $req->product_id)->get();
        $product_items->load('sale', 'product');

        $product_sale = $product_items->map(function ($item) {
            return [
                'date' => $item->sale->date,
                'reference' => $item->sale->reference,
                'product_name' => $item->product->name,
                'customer' => optional($item->sale->customer)->user->name ?? '',
                'warehouse' => optional($item->sale->warehouse)->users->name ?? '',
                'quantity' => $item->quantity,
                'sub_total' => $item->sub_total,
            ];
        });

        return ['data' => $product_sale];
    }

    public function stockReport()
    {

        if (auth()->user()->hasRole('Manager')) {
            $stock_report = ProductWarehouse::with('product')
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->groupBy('product_id')
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->get()
                ->map(function ($stock_report) {
                    return [
                        'id' => $stock_report->product_id,
                        'sku' => $stock_report->product->sku ?? '', // Assuming there's a 'sku' column
                        'product' => $stock_report->product->name ?? '',
                        'category' => $stock_report->product->category->name ?? '',
                        'total_quantity' => $stock_report->total_quantity ?? '0',
                    ];
                });
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {

                $stock_report = ProductWarehouse::with('product')
                    ->where('warehouse_id', session()->get('selected_warehouse_id'))
                    ->groupBy('product_id')
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                    ->get()
                    ->map(function ($stock_report) {
                        return [
                            'id' => $stock_report->product_id,
                            'sku' => $stock_report->product->sku ?? '', // Assuming there's a 'sku' column
                            'product' => $stock_report->product->name ?? '',
                            'category' => $stock_report->product->category->name ?? '',
                            'total_quantity' => $stock_report->total_quantity ?? '0',
                        ];
                    });
            } else {

                $stock_report = ProductWarehouse::with('product')
                    ->groupBy('product_id')
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                    ->get()
                    ->map(function ($stock_report) {
                        return [
                            'id' => $stock_report->product_id,
                            'sku' => $stock_report->product->sku ?? '', // Assuming there's a 'sku' column
                            'product' => $stock_report->product->name ?? '',
                            'category' => $stock_report->product->category->name ?? '',
                            'total_quantity' => $stock_report->total_quantity ?? '0',
                        ];
                    });
            }
        }


        return view('back.reports.stock', compact('stock_report'));
    }

    public function stockFilter(Request $request)
    {
        $fromDate = date('Y-m-d H:i:s', strtotime($request->start));
        $toDate = date('Y-m-d H:i:s', strtotime($request->end));

        if (auth()->user()->hasRole('Manager')) {
            $products = ProductWarehouse::with('product')
                ->where('warehouse_id', auth()->user()->warehouse_id ?? null)
                ->groupBy('product_id')
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->product_id,
                        'sku' => $product->product->sku ?? '', // Assuming there's a 'sku' column
                        'product' => $product->product->name ?? '',
                        'category' => $product->product->category->name ?? '',
                        'total_quantity' => $product->total_quantity ?? '0',
                    ];
                });
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $products = ProductWarehouse::with('product')
                    ->where('warehouse_id', session()->get('selected_warehouse_id'))
                    ->groupBy('product_id')
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->get()
                    ->map(function ($product) {
                        return [
                            'id' => $product->product_id,
                            'sku' => $product->product->sku ?? '', // Assuming there's a 'sku' column
                            'product' => $product->product->name ?? '',
                            'category' => $product->product->category->name ?? '',
                            'total_quantity' => $product->total_quantity ?? '0',
                        ];
                    });
            } else {

                $products = ProductWarehouse::with('product')
                    ->groupBy('product_id')
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->get()
                    ->map(function ($product) {
                        return [
                            'id' => $product->product_id,
                            'sku' => $product->product->sku ?? '', // Assuming there's a 'sku' column
                            'product' => $product->product->name ?? '',
                            'category' => $product->product->category->name ?? '',
                            'total_quantity' => $product->total_quantity ?? '0',
                        ];
                    });
            }
        }


        return ['data' => $products];
    }
    public function stockShow($id)
    {

        if (auth()->user()->hasRole('Manager')) {

            $stock_show = ProductWarehouse::with('product', 'product.product_warehouses', 'product.product_warehouses.warehouse', 'product.product_items', 'product.product_items.sale', 'product.product_items.sale.sale_return')
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->where('product_id', $id)
                ->first();

            // Check if $stock_show is null
            if (!$stock_show) {
                return redirect()->back()->with('error', 'Product inventory not found.');
            }

            $stock_warehouse = $stock_show->product->product_warehouses->map(function ($warehouse) {
                // Ensure $warehouse is not null and the relationships exist
                if ($warehouse && $warehouse->warehouse && $warehouse->warehouse->users && $warehouse->warehouse_id == auth()->user()->warehouse_id) {
                    return [
                        'warehouse' => $warehouse->warehouse->users->name,
                        'quantity' => $warehouse->quantity,
                    ];
                }
                return null; // Return null if the condition doesn't meet
            })->filter(); // Filter out null values from the map result


            $stock_sale = $stock_show->product->product_items->map(function ($sale) {

                if ($sale->sale->warehouse_id == auth()->user()->warehouse_id) {
                    return [
                        'id' => $sale->sale->id,
                        'date' => $sale->sale->date,
                        'reference' => $sale->sale->reference,
                        'customer' => optional($sale->sale->customer)->user->name ?? '',
                        'warehouse' => optional($sale->sale->warehouse)->users->name ?? '',
                        'quantity' => $sale->quantity,
                        'unit' => $sale->sale_units->short_name ?? '',
                        'price' => $sale->price,
                        'sub_total' => $sale->sub_total,
                    ];
                }
                return null;
            })->filter();

            $stock_purchase = $stock_show->product->purchase_items->map(function ($purchase) {

                if ($purchase->purchase->warehouse_id == auth()->user()->warehouse_id) {
                    return [
                        'id' => $purchase->purchase->id, // Added for DataTables 'checkbox
                        'date' => $purchase->purchase->date,
                        'reference' => $purchase->purchase->reference,
                        'vendor' => optional($purchase->purchase->vendor)->user->name ?? '',
                        'warehouse' => optional($purchase->purchase->warehouse)->users->name ?? '',
                        'quantity' => $purchase->quantity,
                        'unit' => $purchase->purchase_units->short_name ?? '',
                        'price' => $purchase->price,
                        'sub_total' => $purchase->sub_total,
                    ];
                }

                return null;
            })->filter();

            $stock_sale_return = $stock_show->product->sale_return_items->map(function ($sale) {

                if ($sale->sale_return->sales->warehouse_id == auth()->user()->warehouse_id) {
                    return [
                        'id' => $sale->sale_return->id,
                        'date' => $sale->sale_return->date,
                        'reference' => $sale->sale_return->reference,
                        'customer' => optional($sale?->sale_return?->sales?->customer)?->user?->name ?? '',
                        'warehouse' => optional($sale?->sale_return?->sales?->warehouse)?->users?->name ?? '',
                        'quantity' => $sale->return_quantity,
                        'unit' => $sale->sale_units->short_name ?? '',
                        'price' => $sale->price,
                        'sub_total' => $sale->subtotal,

                    ];
                }
                return  null;
            })->filter();

            $stock_purchase_return = $stock_show->product->purchase_return_items->map(function ($purchase) {

                if ($purchase->purchase_return->purchase->warehouse_id == auth()->user()->warehouse_id) {
                    return [
                        'id' => $purchase->purchase_return->id, // Added for DataTables 'checkbox
                        'date' => $purchase->purchase_return->date,
                        'reference' => $purchase->purchase_return->reference,
                        'vendor' => optional($purchase->purchase_return->purchase->vendor)->user->name ?? '',
                        'warehouse' => optional($purchase->purchase_return->purchase->warehouse)->users->name ?? '',
                        'quantity' => $purchase->return_quantity,
                        'unit' => $purchase->purchase_units->short_name ?? '',
                        'price' => $purchase->price,
                        'sub_total' => $purchase->subtotal,
                    ];
                }
                return null;
            })->filter();
            $stock_transfer = $stock_show->product->transfer_items;
            $stock_transfer->load('transfer');
            $stock_transfer = $stock_transfer->map(function ($transfer) {
                $fromWarehouseId = $transfer->transfer->from_warehouse->id ?? null;
                $toWarehouseId = $transfer->transfer->to_warehouse->id ?? null;

                if ($fromWarehouseId == auth()->user()->warehouse_id || $toWarehouseId == auth()->user()->warehouse_id) {
                    return [
                        'id' => $transfer->transfer->id,
                        'date' => $transfer->transfer->date,
                        'reference' => $transfer->transfer->reference,
                        'from_warehouse' => optional($transfer->transfer->from_warehouse->users)->name ?? '',
                        'to_warehouse' => optional($transfer->transfer->to_warehouse->users)->name ?? '',
                    ];
                }
                return null;
            })->filter();


            $stock_inventory = $stock_show->product->product_inventory;
            $stock_inventory = $stock_inventory->map(function ($inventory) {

                if ($inventory->inventory->warehouse_id == auth()->user()->warehouse_id) {
                    return [
                        'date' => $inventory->inventory->date ?? '',
                        'reference' => $inventory->inventory->reference,
                        'product' => $inventory->products->name ?? '',
                        'warehouse' => optional($inventory->inventory->warehouse)->users->name ?? '',
                    ];
                }

                return null;
            })->filter();
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $stock_show = ProductWarehouse::with('product', 'product.product_warehouses', 'product.product_warehouses.warehouse', 'product.product_items', 'product.product_items.sale', 'product.product_items.sale.sale_return')
                    ->where('warehouse_id', $warehouseId)
                    ->where('product_id', $id)
                    ->first();

                // Check if $stock_show is null
                if (!$stock_show) {
                    return redirect()->back()->with('error', 'Product inventory not found.');
                }

                $stock_warehouse = $stock_show->product->product_warehouses->map(function ($warehouse) use ($warehouseId) {
                    // Ensure $warehouse is not null and the relationships exist
                    if ($warehouse && $warehouse->warehouse && $warehouse->warehouse->users && $warehouse->warehouse_id == $warehouseId) {
                        return [
                            'warehouse' => $warehouse->warehouse->users->name,
                            'quantity' => $warehouse->quantity,
                        ];
                    }
                    return null; // Return null if the condition doesn't meet
                })->filter(); // Filter out null values from the map result


                $stock_sale = $stock_show->product->product_items->map(function ($sale) use ($warehouseId) {

                    if ($sale->sale->warehouse_id == $warehouseId) {
                        return [
                            'id' => $sale->sale->id,
                            'date' => $sale->sale->date,
                            'reference' => $sale->sale->reference,
                            'customer' => optional($sale->sale->customer)->user->name ?? '',
                            'warehouse' => optional($sale->sale->warehouse)->users->name ?? '',
                            'quantity' => $sale->quantity,
                            'unit' => $sale->sale_units->short_name ?? '',
                            'price' => $sale->price,
                            'sub_total' => $sale->sub_total,
                        ];
                    }
                    return null;
                })->filter();

                $stock_purchase = $stock_show->product->purchase_items->map(function ($purchase) use ($warehouseId) {

                    if ($purchase->purchase->warehouse_id == $warehouseId) {
                        return [
                            'id' => $purchase->purchase->id, // Added for DataTables 'checkbox
                            'date' => $purchase->purchase->date,
                            'reference' => $purchase->purchase->reference,
                            'vendor' => optional($purchase->purchase->vendor)->user->name ?? '',
                            'warehouse' => optional($purchase->purchase->warehouse)->users->name ?? '',
                            'quantity' => $purchase->quantity,
                            'unit' => $purchase->purchase_units->short_name ?? '',
                            'price' => $purchase->price,
                            'sub_total' => $purchase->sub_total,
                        ];
                    }

                    return null;
                })->filter();

                $stock_sale_return = $stock_show->product->sale_return_items->map(function ($sale) use ($warehouseId) {

                    if ($sale->sale_return->sales->warehouse_id == $warehouseId) {
                        return [
                            'id' => $sale->sale_return->id,
                            'date' => $sale->sale_return->date,
                            'reference' => $sale->sale_return->reference,
                            'customer' => optional($sale?->sale_return?->sales?->customer)?->user?->name ?? '',
                            'warehouse' => optional($sale?->sale_return?->sales?->warehouse)?->users?->name ?? '',
                            'quantity' => $sale->return_quantity,
                            'unit' => $sale->sale_units->short_name ?? '',
                            'price' => $sale->price,
                            'sub_total' => $sale->subtotal,

                        ];
                    }
                    return  null;
                })->filter();

                $stock_purchase_return = $stock_show->product->purchase_return_items->map(function ($purchase) use ($warehouseId) {

                    if ($purchase->purchase_return->purchase->warehouse_id == $warehouseId) {
                        return [
                            'id' => $purchase->purchase_return->id, // Added for DataTables 'checkbox
                            'date' => $purchase->purchase_return->date,
                            'reference' => $purchase->purchase_return->reference,
                            'vendor' => optional($purchase->purchase_return->purchase->vendor)->user->name ?? '',
                            'warehouse' => optional($purchase->purchase_return->purchase->warehouse)->users->name ?? '',
                            'quantity' => $purchase->return_quantity,
                            'unit' => $purchase->purchase_units->short_name ?? '',
                            'price' => $purchase->price,
                            'sub_total' => $purchase->subtotal,
                        ];
                    }
                    return null;
                })->filter();
                $stock_transfer = $stock_show->product->transfer_items;
                $stock_transfer->load('transfer');
                $stock_transfer = $stock_transfer->map(function ($transfer) use ($warehouseId) {
                    $fromWarehouseId = $transfer->transfer->from_warehouse->id ?? null;
                    $toWarehouseId = $transfer->transfer->to_warehouse->id ?? null;

                    if ($fromWarehouseId == $warehouseId || $toWarehouseId == $warehouseId) {
                        return [
                            'id' => $transfer->transfer->id,
                            'date' => $transfer->transfer->date,
                            'reference' => $transfer->transfer->reference,
                            'from_warehouse' => optional($transfer->transfer->from_warehouse->users)->name ?? '',
                            'to_warehouse' => optional($transfer->transfer->to_warehouse->users)->name ?? '',
                        ];
                    }
                    return null;
                })->filter();


                $stock_inventory = $stock_show->product->product_inventory;
                $stock_inventory = $stock_inventory->map(function ($inventory) use ($warehouseId) {

                    if ($inventory->inventory->warehouse_id == $warehouseId) {
                        return [
                            'date' => $inventory->inventory->date ?? '',
                            'reference' => $inventory->inventory->reference,
                            'product' => $inventory->products->name ?? '',
                            'warehouse' => optional($inventory->inventory->warehouse)->users->name ?? '',
                        ];
                    }

                    return null;
                })->filter();
            } else {
                $stock_show = ProductWarehouse::with('product', 'product.product_warehouses', 'product.product_warehouses.warehouse', 'product.product_items', 'product.product_items.sale', 'product.product_items.sale.sale_return')
                    ->where('product_id', $id)
                    ->first();

                // Check if $stock_show is null
                if (!$stock_show) {
                    return redirect()->back()->with('error', 'Product inventory not found.');
                }

                $stock_warehouse = $stock_show->product->product_warehouses->map(function ($warehouse) {
                    return [
                        'warehouse' => $warehouse->warehouse->users->name,
                        'quantity' => $warehouse->quantity,
                    ];
                });
                $stock_sale = $stock_show->product->product_items->map(function ($sale) {
                    return [
                        'id' => $sale->sale->id,
                        'date' => $sale->sale->date,
                        'reference' => $sale->sale->reference,
                        'customer' => optional($sale->sale->customer)->user->name ?? '',
                        'warehouse' => optional($sale->sale->warehouse)->users->name ?? '',
                        'quantity' => $sale->quantity,
                        'unit' => $sale->sale_units->short_name ?? '',
                        'price' => $sale->price,
                        'sub_total' => $sale->sub_total,
                    ];
                });
                // dd($stock_sale);

                $stock_purchase = $stock_show->product->purchase_items->map(function ($purchase) {
                    return [
                        'id' => $purchase->purchase->id, // Added for DataTables 'checkbox
                        'date' => $purchase->purchase->date,
                        'reference' => $purchase->purchase->reference,
                        'vendor' => optional($purchase->purchase->vendor)->user->name ?? '',
                        'warehouse' => optional($purchase->purchase->warehouse)->users->name ?? '',
                        'quantity' => $purchase->quantity,
                        'unit' => $purchase->purchase_units->short_name ?? '',
                        'price' => $purchase->price,
                        'sub_total' => $purchase->sub_total,
                    ];
                });
                $stock_sale_return = $stock_show->product->sale_return_items->map(function ($sale) {
                    // dd($sale->sale_return->sales->warehouse);
                    return [
                        'id' => $sale->sale_return->id,
                        'date' => $sale->sale_return->date,
                        'reference' => $sale->sale_return->reference,
                        'customer' => optional($sale?->sale_return?->sales?->customer)?->user?->name ?? '',
                        'warehouse' => optional($sale?->sale_return?->sales?->warehouse)?->users?->name ?? '',
                        'quantity' => $sale->return_quantity,
                        'unit' => $sale->sale_units->short_name ?? '',
                        'price' => $sale->price,
                        'sub_total' => $sale->subtotal,
                    ];
                });

                $stock_purchase_return = $stock_show->product->purchase_return_items->map(function ($purchase) {
                    return [
                        'id' => $purchase->purchase_return->id, // Added for DataTables 'checkbox
                        'date' => $purchase->purchase_return->date,
                        'reference' => $purchase->purchase_return->reference,
                        'vendor' => optional($purchase->purchase_return->purchase->vendor)->user->name ?? '',
                        'warehouse' => optional($purchase->purchase_return->purchase->warehouse)->users->name ?? '',
                        'quantity' => $purchase->return_quantity,
                        'unit' => $purchase->purchase_units->short_name ?? '',
                        'price' => $purchase->price,
                        'sub_total' => $purchase->subtotal,
                    ];
                });
                $stock_transfer = $stock_show->product->transfer_items;
                $stock_transfer->load('transfer');
                $stock_transfer = $stock_transfer->map(function ($transfer) {
                    return [
                        'id' => $transfer->transfer->id,
                        'date' => $transfer->transfer->date,
                        'reference' => $transfer->transfer->reference,
                        'from_warehouse' => optional($transfer->transfer->from_warehouse)->users->name ?? '',
                        'to_warehouse' => optional($transfer->transfer->to_warehouse)->users->name ?? '',
                    ];
                });
                $stock_inventory = $stock_show->product->product_inventory;
                $stock_inventory = $stock_inventory->map(function ($inventory) {
                    return [
                        'date' => $inventory->inventory->date ?? '',
                        'reference' => $inventory->inventory->reference,
                        'product' => $inventory->products->name ?? '',
                        'warehouse' => optional($inventory->inventory->warehouse)->users->name ?? '',

                    ];
                });
            }
        }




        return view('back.reports.stock_show', compact('stock_warehouse', 'stock_show', 'stock_sale', 'stock_purchase', 'stock_sale_return', 'stock_purchase_return', 'stock_transfer', 'stock_inventory'));
    }
    // public function stockShow($id)
    // {
    //     // $product = Product::find($id);
    //     // $stock = ProductInventory::where('product_id', $id)->get();

    //     $stock_show = ProductInventory::with('products', 'products.product_warehouses', 'products.product_warehouses.warehouse', 'products.product_items', 'products.product_items.sale', 'products.product_items.sale.sale_return')
    //         ->where('product_id', $id)
    //         ->first();

    //      // Check if $stock_show is null
    //     if (!$stock_show) {
    //         return redirect()->back()->with('error', 'Product inventory not found.');
    //     }

    //     $stock_warehouse = $stock_show->products->product_warehouses->map(function ($warehouse) {
    //         return [
    //             'warehouse' => $warehouse->warehouse->users->name,
    //             'quantity' => $warehouse->quantity,
    //         ];
    //     });
    //     $stock_sale = $stock_show->products->product_items->map(function ($sale) {
    //         return [
    //             'id' => $sale->sale->id,
    //             'date' => $sale->sale->date,
    //             'reference' => $sale->sale->reference,
    //             'customer' => optional($sale->sale->customer)->user->name ?? '',
    //             'warehouse' => optional($sale->sale->warehouse)->users->name ?? '',
    //             'quantity' => $sale->quantity,
    //             'unit' => $sale->sale_units->short_name ?? '',
    //             'price' => $sale->price,
    //             'sub_total' => $sale->sub_total,
    //         ];
    //     });
    //     // dd($stock_sale);

    //     $stock_purchase = $stock_show->products->purchase_items->map(function ($purchase) {
    //         return [
    //             'id' => $purchase->purchase->id, // Added for DataTables 'checkbox
    //             'date' => $purchase->purchase->date,
    //             'reference' => $purchase->purchase->reference,
    //             'vendor' => optional($purchase->purchase->vendor)->user->name ?? '',
    //             'warehouse' => optional($purchase->purchase->warehouse)->users->name ?? '',
    //             'quantity' => $purchase->quantity,
    //             'unit' => $purchase->purchase_units->short_name ?? '',
    //             'price' => $purchase->price,
    //             'sub_total' => $purchase->sub_total,
    //         ];
    //     });
    //     $stock_sale_return = $stock_show->products->sale_return_items->map(function ($sale) {
    //         // dd($sale->sale_return->sales->warehouse);
    //         return [
    //             'id' => $sale->sale_return->id,
    //             'date' => $sale->sale_return->date,
    //             'reference' => $sale->sale_return->reference,
    //             'customer' => optional($sale->sale_return->sales->customer)->user->name ?? '',
    //             'warehouse' => optional($sale->sale_return->sales->warehouse)->users->name ?? '',
    //             'quantity' => $sale->return_quantity,
    //             'unit' => $sale->sale_units->short_name ?? '',
    //             'price' => $sale->price,
    //             'sub_total' => $sale->subtotal,
    //         ];
    //     });

    //     $stock_purchase_return = $stock_show->products->purchase_return_items->map(function ($purchase) {
    //         return [
    //             'id' => $purchase->purchase_return->id, // Added for DataTables 'checkbox
    //             'date' => $purchase->purchase_return->date,
    //             'reference' => $purchase->purchase_return->reference,
    //             'vendor' => optional($purchase->purchase_return->purchase->vendor)->user->name ?? '',
    //             'warehouse' => optional($purchase->purchase_return->purchase->warehouse)->users->name ?? '',
    //             'quantity' => $purchase->return_quantity,
    //             'unit' => $purchase->purchase_units->short_name ?? '',
    //             'price' => $purchase->price,
    //             'sub_total' => $purchase->subtotal,
    //         ];
    //     });
    //     $stock_transfer = $stock_show->products->transfer_items;
    //     $stock_transfer->load('transfer');
    //     $stock_transfer = $stock_transfer->map(function ($transfer) {
    //         return [
    //             'id' => $transfer->transfer->id,
    //             'date' => $transfer->transfer->date,
    //             'reference' => $transfer->transfer->reference,
    //             'from_warehouse' => optional($transfer->transfer->from_warehouse)->users->name ?? '',
    //             'to_warehouse' => optional($transfer->transfer->to_warehouse)->users->name ?? '',
    //         ];
    //     });
    //     $stock_inventory = $stock_show->products->product_inventory;
    //     $stock_inventory = $stock_inventory->map(function ($inventory) {
    //         return [
    //             'date' => $inventory->inventory->date ?? '',
    //             'reference' => $inventory->inventory->reference,
    //             'product' => $inventory->products->name ?? '',
    //             'warehouse' => optional($inventory->inventory->warehouse)->users->name ?? '',

    //         ];
    //     });

    //     return view('back.reports.stock_show', compact('stock_warehouse', 'stock_show', 'stock_sale', 'stock_purchase', 'stock_sale_return', 'stock_purchase_return', 'stock_transfer', 'stock_inventory'));
    // }

    public function expenseReport()
    {

        if (auth()->user()->hasRole('Manager')) {
            $expenses = ExpenseCategory::with('expenses')
                ->whereHas('expenses', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })

                ->get();
        } else {
            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $expenses = ExpenseCategory::with('expenses')
                    ->whereHas('expenses', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    })

                    ->get();
            } else {
                $expenses = ExpenseCategory::with('expenses')->get();
            }
        }

        // list expenses group by expense category with total amount


        return view('back.reports.expense', compact('expenses'));
    }
    public function expenseFilter(Request $request)
    {
        $startDate = date('Y-m-d H:i:s', strtotime($request->start));
        $endDate = date('Y-m-d H:i:s', strtotime($request->end));

        if (auth()->user()->hasRole('Manager')) {
            $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])
                ->with('expenseCategory')
                ->where('warehouse_id', auth()->user()->warehouse_id ?? '')
                ->get()
                ->groupBy('expense_category_id')
                ->map(function ($group) {
                    return [
                        'category' => optional($group->first()->expenseCategory)->name ?? '',
                        'total_amount' => $group->sum('amount'),
                    ];
                })
                ->values(); // Reset the keys
        } else {
            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])
                    ->with('expenseCategory')
                    ->where('warehouse_id', session()->get('selected_warehouse_id'))
                    ->get()
                    ->groupBy('expense_category_id')
                    ->map(function ($group) {
                        return [
                            'category' => optional($group->first()->expenseCategory)->name ?? '',
                            'total_amount' => $group->sum('amount'),
                        ];
                    })
                    ->values(); // Reset the keys
            } else {
                $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])
                    ->with('expenseCategory')
                    ->get()
                    ->groupBy('expense_category_id')
                    ->map(function ($group) {
                        return [
                            'category' => optional($group->first()->expenseCategory)->name ?? '',
                            'total_amount' => $group->sum('amount'),
                        ];
                    })
                    ->values(); // Reset the keys
            }
        }



        return ['data' => $expenses];
    }

    public function depositReport()
    {



        if (auth()->user()->hasRole('Manager')) {
            $deposits = DepositCategory::with('deposits')
                ->whereHas('deposits', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })
                ->get();
        } else {
            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $deposits = DepositCategory::with('deposits')
                    ->whereHas('deposits', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    })
                    ->get();
            } else {
                $deposits = DepositCategory::with('deposits')->get();
            }
        }


        return view('back.reports.deposit', compact('deposits'));
    }
    public function depositFilter(Request $request)
    {
        $startDate = date('Y-m-d H:i:s', strtotime($request->start));
        $endDate = date('Y-m-d H:i:s', strtotime($request->end));


        if (auth()->user()->hasRole('Manager')) {

            $deposits = Deposit::whereBetween('created_at', [$startDate, $endDate])
                ->with('depositCategory')
                ->where('warehouse_id', auth()->user()->warehouse_id ?? '')
                ->get()
                ->groupBy('deposit_category_id')
                ->map(function ($group) {
                    return [
                        'date' => $group->first()->created_at->format('d-m-y'),
                        'category' => optional($group->first()->depositCategory)->name ?? '',
                        'total_amount' => $group->sum('amount'),
                    ];
                })
                ->values(); // Reset the keys

        } else {
            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $deposits = Deposit::whereBetween('created_at', [$startDate, $endDate])
                    ->with('depositCategory')
                    ->where('warehouse_id', session()->get('selected_warehouse_id'))
                    ->get()
                    ->groupBy('deposit_category_id')
                    ->map(function ($group) {
                        return [
                            'date' => $group->first()->created_at->format('d-m-y'),
                            'category' => optional($group->first()->depositCategory)->name ?? '',
                            'total_amount' => $group->sum('amount'),
                        ];
                    })
                    ->values(); // Reset the keys
            } else {
                $deposits = Deposit::whereBetween('created_at', [$startDate, $endDate])
                    ->with('depositCategory')
                    ->get()
                    ->groupBy('deposit_category_id')
                    ->map(function ($group) {
                        return [
                            'date' => $group->first()->created_at->format('d-m-y'),
                            'category' => optional($group->first()->depositCategory)->name ?? '',
                            'total_amount' => $group->sum('amount'),
                        ];
                    })
                    ->values(); // Reset the keys
            }
        }



        // $deposits = Deposit::whereBetween('created_at', [$startDate, $endDate])
        //     ->with('depositCategory')
        //     ->get()
        //     ->groupBy('deposit_category_id')
        //     ->map(function ($group) {
        //         return [
        //             'date' => $group->first()->created_at->format('d-m-y'),
        //             'category' => optional($group->first()->depositCategory)->name ?? '',
        //             'total_amount' => $group->sum('amount'),
        //         ];
        //     })
        //     ->values(); // Reset the keys

        return ['data' => $deposits];
    }

    public function customerReport()
    {

        if (auth()->user()->hasRole('Manager')) {
            $customers = Sale::with('customer')
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->get()
                ->groupBy('customer_id')
                ->map(function ($group) {
                    // $sale_return_due = SaleReturn::where('sale_id',$group->first()->id)->sum('amount_due');
                    return
                        [
                            'customer' => optional($group->first()->customer)->user->name ?? '',
                            'id' => $group->first()->customer->id,
                            'total_sales' => $group->count(),
                            'total_amount' => number_format($group->sum('grand_total')),
                            'total_paid' => number_format($group->sum('amount_recieved')),
                            'total_due' => ($group->sum('amount_due') + ($group->first()->customer->outstanding_balance ?? 0)),
                            // 'debit_amount' => $group->first()->customer->account->init_balance ?? '0.00',
                            'credit_amount' => $group->first()->customer->balance ?? '0.00',
                        ];
                })
                ->values(); // Reset the keys
        } else {

            if(auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')){
                $warehouseId = session()->get('selected_warehouse_id');
                $customers = Sale::with('customer')
                ->where('warehouse_id', $warehouseId)
                ->get()
                ->groupBy('customer_id')
                ->map(function ($group) {
                    return
                        [
                            'customer' => optional($group->first()->customer)->user->name ?? '',
                            'id' => $group->first()->customer->id,
                            'total_sales' => $group->count(),
                            'total_amount' => number_format($group->sum('grand_total')),
                            'total_paid' => number_format($group->sum('amount_recieved')),
                            'total_due' => ($group->sum('amount_due') + ($group->first()->customer->outstanding_balance ?? 0)),
                            'credit_amount' => $group->first()->customer->balance ?? '0.00',
                        ];
                })
                ->values(); // Reset the keys
            }else{
                $customers = Sale::with('customer')
                ->get()
                ->groupBy('customer_id')
                ->map(function ($group) {
                    return
                        [
                            'customer' => optional($group->first()->customer)->user->name ?? '',
                            'id' => $group->first()->customer->id,
                            'total_sales' => $group->count(),
                            'total_amount' => number_format($group->sum('grand_total')),
                            'total_paid' => number_format($group->sum('amount_recieved')),
                            'total_due' => ($group->sum('amount_due') + ($group->first()->customer->outstanding_balance ?? 0)),
                            // 'debit_amount' => $group->first()->customer->account->init_balance ?? '0.00',
                            'credit_amount' => $group->first()->customer->balance ?? '0.00',
                        ];
                })
                ->values(); // Reset the keys
            }

        }


        return view('back.reports.customer', compact('customers'));
    }
    public function customerFilter(Request $request)
    {
        $startDate = date('Y-m-d H:i:s', strtotime($request->start));
        $endDate = date('Y-m-d H:i:s', strtotime($request->end));

        $customers = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->with('customer')
            ->get()
            ->groupBy('customer_id')
            ->map(function ($group) {
                return [
                    'customer' => optional($group->first()->customer)->user->name ?? '',
                    'total_sales' => $group->count(),
                    'total_amount' => $group->sum('grand_total'),
                    'total_paid' => $group->sum('amount_recieved'),
                    'total_due' => $group->sum('amount_due'),
                    'id' => $group->first()->customer->id,
                    // 'debit_amount' => $group->first()->customer->account->init_balance ?? '0.00',
                    'credit_amount' => $group->first()->customer->balance ?? '0.00',

                ];
            })
            ->values(); // Reset the keys

        // dd($customers);
        return ['data' => $customers];
    }

    public function customerdownloadPdf($id)
    {
        $customer = Customer::with('user')->find($id);
        $sale = Sale::with('customer', 'invoice')->where('customer_id', $id)->get();

        $pdf = Pdf::loadView('back.pdf.customer', ['customer' => $customer, 'sale' => $sale]);
        return $pdf->download('report-' . $customer->user->name . '.pdf');
    }

    public function customerDetail($id)
    {
        // return $id;
        // $customer = Customer::with('user')->find($id);
        $sale = Sale::with('customer', 'invoice', 'shipment')->where('customer_id', $id)->get();
        // $sale_return = Sale::with('sale_return','sale_return.return_items','customer')->where('customer_id',$id)->get();
        $sale_return = Sale::with('sale_return', 'customer')
            ->where('customer_id', $id)
            ->whereHas('sale_return')  // Ensure at least one SaleReturn exists
            ->get();
        // dd($sale_return);
        return view('back.reports.customer-details', compact('sale', 'sale_return'));
    }

    public function vendorReport()
    {
        // list vendor sales with total count of sales by vendor
        // total grand_total, total amount_recieved as paid and total amount_due as due
        if (auth()->user()->hasRole('Manager')) {
            $vendors = Purchase::with('vendor')
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->get()
                ->groupBy('vendor_id')
                ->map(function ($group) {
                    return [
                        'vendor' => optional($group->first()->vendor)->user->name ?? '',
                        'total_sales' => $group->count(),
                        'total_amount' => $group->sum('grand_total'),
                        'total_paid' => number_format($group->sum('amount_recieved')  + $group->first()?->vendor?->totalNonInvoicePaymentsPay?->sum('amount_pay')),
                        'total_due' =>  number_format($group->sum('amount_due')  + $group->first()?->vendor?->totalNonInvoicePaymentsDue?->sum('amount_pay')),
                    ];
                })
                ->values(); // Reset the keys
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $vendors = Purchase::with('vendor')
                    ->where('warehouse_id', $warehouseId)
                    ->get()
                    ->groupBy('vendor_id')
                    ->map(function ($group) {
                        return [
                            'vendor' => optional($group->first()->vendor)->user->name ?? '',
                            'total_sales' => $group->count(),
                            'total_amount' => $group->sum('grand_total'),
                            'total_paid' => number_format($group->sum('amount_recieved')  + $group->first()?->vendor?->totalNonInvoicePaymentsPay?->sum('amount_pay')),
                            'total_due' =>  number_format($group->sum('amount_due')  + $group->first()?->vendor?->totalNonInvoicePaymentsDue?->sum('amount_pay')),
                        ];
                    })
                    ->values(); // Reset the keys
            } else {



                $vendors = Purchase::with('vendor')
                    ->get()
                    ->groupBy('vendor_id')
                    ->map(function ($group) {
                        return [
                            'vendor' => optional($group->first()->vendor)->user->name ?? '',
                            'total_sales' => $group->count(),
                            'total_amount' => $group->sum('grand_total'),
                            'total_paid' => number_format($group->sum('amount_recieved')  + $group->first()?->vendor?->totalNonInvoicePaymentsPay?->sum('amount_pay')),
                            'total_due' =>  number_format($group->sum('amount_due')  + $group->first()?->vendor?->totalNonInvoicePaymentsDue?->sum('amount_pay')),
                        ];
                    })
                    ->values(); // Reset the keys

            }
        }


        return view('back.reports.vendor', compact('vendors'));
    }

    public function vendorFilter(Request $request)
    {
        $startDate = date('Y-m-d H:i:s', strtotime($request->start));
        $endDate = date('Y-m-d H:i:s', strtotime($request->end));

        if (auth()->user()->hasRole('Manager')) {
            $vendors = Purchase::whereBetween('created_at', [$startDate, $endDate])
                ->with('vendor')
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->get()
                ->groupBy('vendor_id')
                ->map(function ($group) {
                    return [
                        'vendor' => optional($group->first()->vendor)->user->name ?? '',
                        'total_sales' => $group->count(),
                        'total_amount' => $group->sum('grand_total'),
                        'total_paid' => $group->sum('amount_recieved'),
                        'total_due' => $group->sum('amount_due'),
                    ];
                })
                ->values(); // Reset the keys
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $vendors = Purchase::whereBetween('created_at', [$startDate, $endDate])
                    ->with('vendor')
                    ->where('warehouse_id', $warehouseId)
                    ->get()
                    ->groupBy('vendor_id')
                    ->map(function ($group) {
                        return [
                            'vendor' => optional($group->first()->vendor)->user->name ?? '',
                            'total_sales' => $group->count(),
                            'total_amount' => $group->sum('grand_total'),
                            'total_paid' => $group->sum('amount_recieved'),
                            'total_due' => $group->sum('amount_due'),
                        ];
                    })
                    ->values(); // Reset the keys
            } else {

                $vendors = Purchase::whereBetween('created_at', [$startDate, $endDate])
                    ->with('vendor')
                    ->get()
                    ->groupBy('vendor_id')
                    ->map(function ($group) {
                        return [
                            'vendor' => optional($group->first()->vendor)->user->name ?? '',
                            'total_sales' => $group->count(),
                            'total_amount' => $group->sum('grand_total'),
                            'total_paid' => $group->sum('amount_recieved'),
                            'total_due' => $group->sum('amount_due'),
                        ];
                    })
                    ->values(); // Reset the keys
            }
        }

        return ['data' => $vendors];
    }

    public function warehouseReport()
    {

        if (auth()->user()->hasRole('Manager')) {
            $all_warehouses = Warehouse::all();
            $warehouseId = auth()->user()->warehouse_id ?? null;
            $warehouses = Warehouse::with('purchases')
                ->where('id', $warehouseId)
                ->get()
                ->map(function ($warehouse) {
                    $purchaseReturns = $warehouse->purchases->flatMap(function ($purchase) {
                        return $purchase->purchase_return ? [$purchase->purchase_return] : [];
                    });
                    $saleReturns = $warehouse->sales->flatMap(function ($sale) {
                        return $sale->sale_return ? [$sale->sale_return] : [];
                    });

                    return [
                        'total_sales' => $warehouse->sales->count(),
                        'total_purchase' => $warehouse->purchases->count(),
                        'total_purchase_return' => $purchaseReturns->count(),
                        'total_sale_return' => $saleReturns->count(),
                    ];
                });

            $total_sales = $warehouses->sum('total_sales');
            $total_purchase = $warehouses->sum('total_purchase');
            $total_purchase_return = $warehouses->sum('total_purchase_return');
            $total_sale_return = $warehouses->sum('total_sale_return');

            $warehouse_sales = Warehouse::with('sales')
                ->where('id', $warehouseId)
                ->get()
                ->flatMap(function ($warehouse) {
                    return $warehouse->sales->map(function ($sale) {
                        return [
                            'id' => $sale->id,
                            'reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? '',
                            'warehouse' => optional($sale->warehouse)->users->name ?? '',
                            'grand_total' => $sale->grand_total,
                            'paid' => $sale->amount_recieved,
                            'due' => $sale->amount_due,
                            'status' => $sale->status,
                            'payment_status' => $sale->payment_status,
                            'shipping_status' => $sale->shipment->status ?? '',
                        ];
                    });
                });

            $warehouse_purchases = Warehouse::with('purchases')
                ->where('id', $warehouseId)
                ->get()
                ->flatMap(function ($warehouse) {
                    return $warehouse->purchases->map(function ($purchase) {
                        return [
                            'id' => $purchase->id,
                            'date' => $purchase->date,
                            'reference' => $purchase->reference,
                            'vendor' => optional($purchase->vendor)->user->name ?? '',
                            'warehouse' => optional($purchase->warehouse)->users->name ?? '',
                            'grand_total' => $purchase->grand_total,
                            'paid' => $purchase->amount_recieved,
                            'due' => $purchase->amount_due,
                            'status' => $purchase->status,
                            'payment_status' => $purchase->payment_status,
                            'shipping_status' => $purchase->shipment->status ?? '',
                        ];
                    });
                });

            $warehouse_expense = Warehouse::with('expenses')
                ->where('id', $warehouseId)
                ->get()
                ->flatMap(function ($warehouse) {
                    return $warehouse->expenses->map(function ($expense) {
                        return [
                            'id' => $expense->id,
                            'date' => $expense->date,
                            'reference' => $expense->reference,
                            'category' => optional($expense->expenseCategory)->name ?? '',
                            'warehouse' => optional($expense->warehouse)->users->name ?? '',
                            'amount' => $expense->amount,
                            'description' => $expense->description,
                        ];
                    });
                });

            $warehouse_sale_return = Warehouse::with('sales')
                ->where('id', $warehouseId)
                ->get()
                ->flatMap(function ($warehouse) {
                    return $warehouse->sales->flatMap(function ($sale) {
                        return $sale->sale_return ? [$sale->sale_return] : [];
                    });
                })
                ->map(function ($return) {
                    return [
                        'id' => $return->id,
                        'date' => $return->date,
                        'reference' => $return->reference,
                        'customer' => optional($return->sales->customer)->user->name ?? '',
                        'sale_reference' => $return->sales->reference,
                        'warehouse' => optional($return->sales->warehouse)->users->name ?? '',
                        'grand_total' => $return->sales->grand_total,
                        'paid' => $return->sales->amount_recieved,
                        'due' => $return->sales->amount_due,
                        'status' => $return->status,
                        'payment_status' => $return->payment_status,

                    ];
                });

            $warehouse_purchase_return = Warehouse::with('purchases')
                ->where('id', $warehouseId)
                ->get()
                ->flatMap(function ($warehouse) {
                    return $warehouse->purchases->flatMap(function ($purchase) {
                        return $purchase->purchase_return ? [$purchase->purchase_return] : [];
                    });
                })
                ->map(function ($return) {
                    return [
                        'id' => $return->id,
                        'date' => $return->date,
                        'reference' => $return->reference,
                        'vendor' => optional($return->purchase->vendor)->user->name ?? '',
                        'purchase_reference' => $return->purchase->reference,
                        'warehouse' => optional($return->purchase->warehouse)->users->name ?? '',
                        'grand_total' => $return->purchase->grand_total,
                        'paid' => $return->purchase->amount_recieved,
                        'due' => $return->purchase->amount_due,
                        'status' => $return->status,
                        'payment_status' => $return->payment_status,

                    ];
                });


            $warehouses = Warehouse::with(['products', 'productWarehouses', 'users', 'sales'])
                ->where('id', $warehouseId)
                ->get();

            $warehouseData = $warehouses->map(function ($warehouse) {
                $totalQuantity = $warehouse->productWarehouses->sum('quantity');
                $totalItems = $warehouse->products->count();
                $sales_grand_total = $warehouse->sales->sum('grand_total');
                $sales_paid = $warehouse->sales->sum('amount_recieved');
                return [
                    'label' => $warehouse?->users?->name,
                    'data' => [$totalQuantity, $totalItems,  $sales_grand_total, $sales_paid],
                ];
            });
        } else {

            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $all_warehouses = Warehouse::all();

                $warehouses = Warehouse::with('purchases')
                    ->where('id', $warehouseId)
                    ->get()
                    ->map(function ($warehouse) {
                        $purchaseReturns = $warehouse->purchases->flatMap(function ($purchase) {
                            return $purchase->purchase_return ? [$purchase->purchase_return] : [];
                        });
                        $saleReturns = $warehouse->sales->flatMap(function ($sale) {
                            return $sale->sale_return ? [$sale->sale_return] : [];
                        });

                        return [
                            'total_sales' => $warehouse->sales->count(),
                            'total_purchase' => $warehouse->purchases->count(),
                            'total_purchase_return' => $purchaseReturns->count(),
                            'total_sale_return' => $saleReturns->count(),
                        ];
                    });

                $total_sales = $warehouses->sum('total_sales');
                $total_purchase = $warehouses->sum('total_purchase');
                $total_purchase_return = $warehouses->sum('total_purchase_return');
                $total_sale_return = $warehouses->sum('total_sale_return');

                $warehouse_sales = Warehouse::with('sales')
                    ->where('id', $warehouseId)
                    ->get()
                    ->flatMap(function ($warehouse) {
                        return $warehouse->sales->map(function ($sale) {
                            return [
                                'id' => $sale->id,
                                'reference' => $sale->reference,
                                'customer' => optional($sale->customer)->user->name ?? '',
                                'warehouse' => optional($sale->warehouse)->users->name ?? '',
                                'grand_total' => $sale->grand_total,
                                'paid' => $sale->amount_recieved,
                                'due' => $sale->amount_due,
                                'status' => $sale->status,
                                'payment_status' => $sale->payment_status,
                                'shipping_status' => $sale->shipment->status ?? '',
                            ];
                        });
                    });

                $warehouse_purchases = Warehouse::with('purchases')
                    ->where('id', $warehouseId)
                    ->get()
                    ->flatMap(function ($warehouse) {
                        return $warehouse->purchases->map(function ($purchase) {
                            return [
                                'id' => $purchase->id,
                                'date' => $purchase->date,
                                'reference' => $purchase->reference,
                                'vendor' => optional($purchase->vendor)->user->name ?? '',
                                'warehouse' => optional($purchase->warehouse)->users->name ?? '',
                                'grand_total' => $purchase->grand_total,
                                'paid' => $purchase->amount_recieved,
                                'due' => $purchase->amount_due,
                                'status' => $purchase->status,
                                'payment_status' => $purchase->payment_status,
                                'shipping_status' => $purchase->shipment->status ?? '',
                            ];
                        });
                    });

                $warehouse_expense = Warehouse::with('expenses')
                    ->where('id', $warehouseId)
                    ->get()
                    ->flatMap(function ($warehouse) {
                        return $warehouse->expenses->map(function ($expense) {
                            return [
                                'id' => $expense->id,
                                'date' => $expense->date,
                                'reference' => $expense->reference,
                                'category' => optional($expense->expenseCategory)->name ?? '',
                                'warehouse' => optional($expense->warehouse)->users->name ?? '',
                                'amount' => $expense->amount,
                                'description' => $expense->description,
                            ];
                        });
                    });

                $warehouse_sale_return = Warehouse::with('sales')
                    ->where('id', $warehouseId)
                    ->get()
                    ->flatMap(function ($warehouse) {
                        return $warehouse->sales->flatMap(function ($sale) {
                            return $sale->sale_return ? [$sale->sale_return] : [];
                        });
                    })
                    ->map(function ($return) {
                        return [
                            'id' => $return->id,
                            'date' => $return->date,
                            'reference' => $return->reference,
                            'customer' => optional($return->sales->customer)->user->name ?? '',
                            'sale_reference' => $return->sales->reference,
                            'warehouse' => optional($return->sales->warehouse)->users->name ?? '',
                            'grand_total' => $return->sales->grand_total,
                            'paid' => $return->sales->amount_recieved,
                            'due' => $return->sales->amount_due,
                            'status' => $return->status,
                            'payment_status' => $return->payment_status,

                        ];
                    });

                $warehouse_purchase_return = Warehouse::with('purchases')
                    ->where('id', $warehouseId)
                    ->get()
                    ->flatMap(function ($warehouse) {
                        return $warehouse->purchases->flatMap(function ($purchase) {
                            return $purchase->purchase_return ? [$purchase->purchase_return] : [];
                        });
                    })
                    ->map(function ($return) {
                        return [
                            'id' => $return->id,
                            'date' => $return->date,
                            'reference' => $return->reference,
                            'vendor' => optional($return->purchase->vendor)->user->name ?? '',
                            'purchase_reference' => $return->purchase->reference,
                            'warehouse' => optional($return->purchase->warehouse)->users->name ?? '',
                            'grand_total' => $return->purchase->grand_total,
                            'paid' => $return->purchase->amount_recieved,
                            'due' => $return->purchase->amount_due,
                            'status' => $return->status,
                            'payment_status' => $return->payment_status,

                        ];
                    });


                $warehouses = Warehouse::with(['products', 'productWarehouses', 'users', 'sales'])
                    ->where('id', $warehouseId)
                    ->get();

                $warehouseData = $warehouses->map(function ($warehouse) {
                    $totalQuantity = $warehouse->productWarehouses->sum('quantity');
                    $totalItems = $warehouse->products->count();
                    $sales_grand_total = $warehouse->sales->sum('grand_total');
                    $sales_paid = $warehouse->sales->sum('amount_recieved');
                    return [
                        'label' => $warehouse?->users?->name,
                        'data' => [$totalQuantity, $totalItems,  $sales_grand_total, $sales_paid],
                    ];
                });
            } else {

                $all_warehouses = Warehouse::all();
                $warehouses = Warehouse::with('purchases')
                    ->get()
                    ->map(function ($warehouse) {
                        $purchaseReturns = $warehouse->purchases->flatMap(function ($purchase) {
                            return $purchase->purchase_return ? [$purchase->purchase_return] : [];
                        });
                        $saleReturns = $warehouse->sales->flatMap(function ($sale) {
                            return $sale->sale_return ? [$sale->sale_return] : [];
                        });

                        return [
                            'total_sales' => $warehouse->sales->count(),
                            'total_purchase' => $warehouse->purchases->count(),
                            'total_purchase_return' => $purchaseReturns->count(),
                            'total_sale_return' => $saleReturns->count(),
                        ];
                    });

                $total_sales = $warehouses->sum('total_sales');
                $total_purchase = $warehouses->sum('total_purchase');
                $total_purchase_return = $warehouses->sum('total_purchase_return');
                $total_sale_return = $warehouses->sum('total_sale_return');

                $warehouse_sales = Warehouse::with('sales')
                    ->get()
                    ->flatMap(function ($warehouse) {
                        return $warehouse->sales->map(function ($sale) {
                            return [
                                'id' => $sale->id,
                                'reference' => $sale->reference,
                                'customer' => optional($sale->customer)->user->name ?? '',
                                'warehouse' => optional($sale->warehouse)->users->name ?? '',
                                'grand_total' => $sale->grand_total,
                                'paid' => $sale->amount_recieved,
                                'due' => $sale->amount_due,
                                'status' => $sale->status,
                                'payment_status' => $sale->payment_status,
                                'shipping_status' => $sale->shipment->status ?? '',
                            ];
                        });
                    });

                $warehouse_purchases = Warehouse::with('purchases')
                    ->get()
                    ->flatMap(function ($warehouse) {
                        return $warehouse->purchases->map(function ($purchase) {
                            return [
                                'id' => $purchase->id,
                                'date' => $purchase->date,
                                'reference' => $purchase->reference,
                                'vendor' => optional($purchase->vendor)->user->name ?? '',
                                'warehouse' => optional($purchase->warehouse)->users->name ?? '',
                                'grand_total' => $purchase->grand_total,
                                'paid' => $purchase->amount_recieved,
                                'due' => $purchase->amount_due,
                                'status' => $purchase->status,
                                'payment_status' => $purchase->payment_status,
                                'shipping_status' => $purchase->shipment->status ?? '',
                            ];
                        });
                    });

                $warehouse_expense = Warehouse::with('expenses')
                    ->get()
                    ->flatMap(function ($warehouse) {
                        return $warehouse->expenses->map(function ($expense) {
                            return [
                                'id' => $expense->id,
                                'date' => $expense->date,
                                'reference' => $expense->reference,
                                'category' => optional($expense->expenseCategory)->name ?? '',
                                'warehouse' => optional($expense->warehouse)->users->name ?? '',
                                'amount' => $expense->amount,
                                'description' => $expense->description,
                            ];
                        });
                    });

                $warehouse_sale_return = Warehouse::with('sales')
                    ->get()
                    ->flatMap(function ($warehouse) {
                        return $warehouse->sales->flatMap(function ($sale) {
                            return $sale->sale_return ? [$sale->sale_return] : [];
                        });
                    })
                    ->map(function ($return) {
                        return [
                            'id' => $return->id,
                            'date' => $return->date,
                            'reference' => $return->reference,
                            'customer' => optional($return->sales->customer)->user->name ?? '',
                            'sale_reference' => $return->sales->reference,
                            'warehouse' => optional($return->sales->warehouse)->users->name ?? '',
                            'grand_total' => $return->sales->grand_total,
                            'paid' => $return->sales->amount_recieved,
                            'due' => $return->sales->amount_due,
                            'status' => $return->status,
                            'payment_status' => $return->payment_status,

                        ];
                    });

                $warehouse_purchase_return = Warehouse::with('purchases')
                    ->get()
                    ->flatMap(function ($warehouse) {
                        return $warehouse->purchases->flatMap(function ($purchase) {
                            return $purchase->purchase_return ? [$purchase->purchase_return] : [];
                        });
                    })
                    ->map(function ($return) {
                        return [
                            'id' => $return->id,
                            'date' => $return->date,
                            'reference' => $return->reference,
                            'vendor' => optional($return->purchase->vendor)->user->name ?? '',
                            'purchase_reference' => $return->purchase->reference,
                            'warehouse' => optional($return->purchase->warehouse)->users->name ?? '',
                            'grand_total' => $return->purchase->grand_total,
                            'paid' => $return->purchase->amount_recieved,
                            'due' => $return->purchase->amount_due,
                            'status' => $return->status,
                            'payment_status' => $return->payment_status,

                        ];
                    });

                // $warehouses = Warehouse::all();
                // $warehouses->load('users');
                // $warehouseData = [];
                // foreach ($warehouses as $warehouse) {
                //     $totalQuantity = $warehouse->productWarehouses()->sum('quantity');
                //     $totalItems = $warehouse->products()->count();

                //     $warehouseData[] = [
                //         'label' => $warehouse->users->name,
                //         'data' => [`$totalQuantity Items`, `$totalItems Quantity`],
                //     ];
                // }
                $warehouses = Warehouse::with(['products', 'productWarehouses', 'users', 'sales'])->get();
            }

            $warehouseData = $warehouses->map(function ($warehouse) {
                $totalQuantity = $warehouse->productWarehouses->sum('quantity');
                $totalItems = $warehouse->products->count();
                $sales_grand_total = $warehouse->sales->sum('grand_total');
                $sales_paid = $warehouse->sales->sum('amount_recieved');
                // $sales_grand_total = "Grand Total ". $sales_grand_total;
                return [
                    'label' => $warehouse?->users?->name,
                    // 'data' => [$totalQuantity." Quantity", $totalItems. "Items"],
                    // 'data' => [$totalQuantity, $totalItems, $sales_grand_total, "ToTal Paid " . $sales_paid],
                    'data' => [$totalQuantity, $totalItems,  $sales_grand_total, $sales_paid],
                ];
            });
        }

        return view('back.reports.warehouse', compact('all_warehouses', 'total_sales', 'total_purchase', 'total_purchase_return', 'total_sale_return', 'warehouse_sales', 'warehouse_purchases', 'warehouse_expense', 'warehouse_sale_return', 'warehouse_purchase_return', 'warehouseData'));
    }
    public function warehouseFilter(Request $request)
    {
        if ($request->id) {
            $warehouses = collect([Warehouse::findOrFail($request->id)]);
        } else {
            $warehouses = Warehouse::all();
        }

        $data = [
            'total_sales' => 0,
            'total_purchase' => 0,
            'total_purchase_return' => 0,
            'total_sale_return' => 0,
        ];

        $warehouse_sales = collect();
        $warehouse_purchases = collect();
        $warehouse_expense = collect();
        $warehouse_sale_return = collect();
        $warehouse_purchase_return = collect();

        foreach ($warehouses as $warehouse) {
            // Update counts
            $data['total_sales'] += $warehouse->sales->count();
            $data['total_purchase'] += $warehouse->purchases->count();
            $data['total_purchase_return'] += $warehouse->purchases->flatMap(function ($purchase) {
                return $purchase->purchase_return ? [$purchase->purchase_return] : [];
            })->count();
            $data['total_sale_return'] += $warehouse->sales->flatMap(function ($sale) {
                return $sale->sale_return ? [$sale->sale_return] : [];
            })->count();

            // Update collections
            $warehouse_sales = $warehouse_sales->concat($warehouse->sales);
            $warehouse_purchases = $warehouse_purchases->concat($warehouse->purchases);
            $warehouse_expense = $warehouse_expense->concat($warehouse->expenses);
            $warehouse_sale_return = $warehouse_sale_return->concat($warehouse->sales->flatMap(function ($sale) {
                return $sale->sale_return ? [$sale->sale_return] : [];
            }));
            $warehouse_purchase_return = $warehouse_purchase_return->concat($warehouse->purchases->flatMap(function ($purchase) {
                return $purchase->purchase_return ? [$purchase->purchase_return] : [];
            }));
        }

        // Map collections

        $warehouse_sales = $warehouse_sales->map(function ($sale) {
            return [
                'id' => $sale->id,
                'date' => $sale->date,
                'reference' => $sale->reference,
                'customer' => optional($sale->customer)->user->name ?? '',
                'warehouse' => optional($sale->warehouse)->users->name ?? '',
                'grand_total' => $sale->grand_total,
                'paid' => $sale->amount_recieved,
                'due' => $sale->amount_due,
                'status' => $sale->status,
                'payment_status' => $sale->payment_status,
                'shipping_status' => $sale->shipment->status ?? '',
            ];
        });

        $warehouse_purchases = $warehouse_purchases->map(function ($purchase) {
            return [
                'id' => $purchase->id,
                'date' => $purchase->date,
                'reference' => $purchase->reference,
                'vendor' => optional($purchase->vendor)->user->name ?? '',
                'warehouse' => optional($purchase->warehouse)->users->name ?? '',
                'grand_total' => $purchase->grand_total,
                'paid' => $purchase->amount_recieved,
                'due' => $purchase->amount_due,
                'status' => $purchase->status,
                'payment_status' => $purchase->payment_status,
                'shipping_status' => $purchase->shipment->status ?? '',
            ];
        });

        $warehouse_expense = $warehouse_expense->map(function ($expense) {
            return [
                'id' => $expense->id,
                'date' => $expense->date,
                'reference' => $expense->reference,
                'category' => optional($expense->expenseCategory)->name ?? '',
                'warehouse' => optional($expense->warehouse)->users->name ?? '',
                'amount' => $expense->amount,
                'description' => $expense->description,
            ];
        });

        $warehouse_sale_return = $warehouse_sale_return->map(function ($return) {
            return [
                'id' => $return->id,
                'date' => $return->date,
                'reference' => $return->reference,
                'customer' => optional($return->sales->customer)->user->name ?? '',
                'sale_reference' => $return->sales->reference,
                'warehouse' => optional($return->sales->warehouse)->users->name ?? '',
                'grand_total' => $return->sales->grand_total,
                'paid' => $return->sales->amount_recieved,
                'due' => $return->sales->amount_due,
                'status' => $return->status,
                'payment_status' => $return->payment_status,
            ];
        });

        $warehouse_purchase_return = $warehouse_purchase_return->map(function ($return) {
            return [
                'id' => $return->id,
                'date' => $return->date,
                'reference' => $return->reference,
                'vendor' => optional($return->purchase->vendor)->user->name ?? '',
                'purchase_reference' => $return->purchase->reference,
                'warehouse' => optional($return->purchase->warehouse)->users->name ?? '',
                'grand_total' => $return->purchase->grand_total,
                'paid' => $return->purchase->amount_recieved,
                'due' => $return->purchase->amount_due,
                'status' => $return->status,
                'payment_status' => $return->payment_status,

            ];
        });


        return [
            'data' => [
                'warehouse' => $warehouses,
                'total_sales' => $data['total_sales'],
                'total_purchase' => $data['total_purchase'],
                'total_purchase_return' => $data['total_purchase_return'],
                'total_sale_return' => $data['total_sale_return'],
                'warehouse_sales' => $warehouse_sales,
                'warehouse_purchases' => $warehouse_purchases,
                'warehouse_expense' => $warehouse_expense,
                'warehouse_sale_return' => $warehouse_sale_return,
                'warehouse_purchase_return' => $warehouse_purchase_return,
            ]
        ];
    }

    public function userReport()
    {

        if (auth()->user()->hasRole('Manager')) {
            $users = User::with('customer', 'vendor')
                ->whereHas('customer', function ($q) {
                    $q->whereHas('sales', function ($q) {
                        $q->where('warehouse_id', auth()->user()->warehouse_id ?? null);
                    });
                })
                ->orWhereHas('vendor.purchases', function ($query) {
                    $query->where('warehouse_id', auth()->user()->warehouse_id ?? null);
                })
                ->get()
                ->filter(function ($user) {
                    return $user->customer != null || $user->vendor != null;
                })
                ->map(function ($user) {
                    return [
                        'id' => (string) $user->id,
                        'name' => $user->name,
                        'role' => $user->getRoleNames()->first(),
                        'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
                        'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
                        'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                            return $purchase->purchaseItems->sum('quantity');
                        }) : 0,
                        'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
                            return $sale->sale_return ? $sale->sale_return->count() : 0;
                        }) : 0,
                        'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                            return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
                        }) : 0,
                        // 'total_purchase_return' => $user->vendor ? $user->vendor->purchaseReturns->count() : 0,
                    ];
                })->values();
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $users = User::with('customer', 'vendor')
                    ->whereHas('customer', function ($q) use ($warehouseId) {
                        $q->whereHas('sales', function ($q) use ($warehouseId) {
                            $q->where('warehouse_id', $warehouseId);
                        });
                    })
                    ->orWhereHas('vendor.purchases', function ($query) use ($warehouseId) {
                        $query->where('warehouse_id', $warehouseId);
                    })
                    ->get()
                    ->filter(function ($user) {
                        return $user->customer != null || $user->vendor != null;
                    })
                    ->map(function ($user) {
                        return [
                            'id' => (string) $user->id,
                            'name' => $user->name,
                            'role' => $user->getRoleNames()->first(),
                            'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
                            'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
                            'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchaseItems->sum('quantity');
                            }) : 0,
                            'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
                                return $sale->sale_return ? $sale->sale_return->count() : 0;
                            }) : 0,
                            'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
                            }) : 0,
                            // 'total_purchase_return' => $user->vendor ? $user->vendor->purchaseReturns->count() : 0,
                        ];
                    })->values();
            } else {

                $users = User::with('customer', 'vendor')
                    ->get()
                    ->filter(function ($user) {
                        return $user->customer != null || $user->vendor != null;
                    })
                    ->map(function ($user) {
                        return [
                            'id' => (string) $user->id,
                            'name' => $user->name,
                            'role' => $user->getRoleNames()->first(),
                            'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
                            'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
                            'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchaseItems->sum('quantity');
                            }) : 0,
                            'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
                                return $sale->sale_return ? $sale->sale_return->count() : 0;
                            }) : 0,
                            'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
                            }) : 0,
                            // 'total_purchase_return' => $user->vendor ? $user->vendor->purchaseReturns->count() : 0,
                        ];
                    })->values();
            }
        }

        return view('back.reports.user', compact('users'));
    }

    public function userFilter(Request $request)
    {
        $startDate = date('Y-m-d H:i:s', strtotime($request->start));
        $endDate = date('Y-m-d H:i:s', strtotime($request->end));

        if (auth()->user()->hasRole('Manager') && auth()->user()->warehouse_id) {
            $users = User::whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('customer', function ($q) {
                    $q->whereHas('sales', function ($q) {
                        $q->where('warehouse_id', auth()->user()->warehouse_id);
                    });
                })
                ->orWhereHas('vendor.purchases', function ($query) {
                    $query->where('warehouse_id', auth()->user()->warehouse_id);
                })
                ->with('customer', 'vendor')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
                        'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
                        'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                            return $purchase->purchaseItems->sum('quantity');
                        }) : 0,
                        'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
                            return $sale->sale_return ? $sale->sale_return->count() : 0;
                        }) : 0,
                        'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                            return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
                        }) : 0,
                    ];
                });
        } else {
            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $users = User::whereBetween('created_at', [$startDate, $endDate])
                    ->whereHas('customer', function ($q) use ($warehouseId) {
                        $q->whereHas('sales', function ($q) use ($warehouseId) {
                            $q->where('warehouse_id', $warehouseId);
                        });
                    })
                    ->orWhereHas('vendor.purchases', function ($query) use ($warehouseId) {
                        $query->where('warehouse_id', $warehouseId);
                    })
                    ->with('customer', 'vendor')
                    ->get()
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
                            'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
                            'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchaseItems->sum('quantity');
                            }) : 0,
                            'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
                                return $sale->sale_return ? $sale->sale_return->count() : 0;
                            }) : 0,
                            'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
                            }) : 0,
                        ];
                    });
            } else {

                $users = User::whereBetween('created_at', [$startDate, $endDate])->with('customer', 'vendor')->get()
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'role' => $user->getRoleNames()->first(),
                            'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
                            'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
                            'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchaseItems->sum('quantity');
                            }) : 0,
                            'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
                                return $sale->sale_return ? $sale->sale_return->count() : 0;
                            }) : 0,
                            'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
                            }) : 0,
                        ];
                    });
            }
        }

        return ['data' => $users];
    }

    // public function userFilter2(Request $request)
    // {
    //     // dd($request->all());
    //     $roleFilter = $request->input('filter');

    //     if (auth()->user()->hasRole('Manager')) {
    //         $users = User::with('customer', 'vendor')
    //             ->whereHas('customer', function ($q) {
    //                 $q->whereHas('sales', function ($q) {
    //                     $q->where('warehouse_id', auth()->user()->warehouse_id ?? null);
    //                 });
    //             })
    //             ->orWhereHas('vendor.purchases', function ($query) {
    //                 $query->where('warehouse_id', auth()->user()->warehouse_id ?? null);
    //             })
    //             ->when($roleFilter, function ($query, $roleFilter) {
    //                 // Ensure to filter by role if $roleFilter is not null
    //                 return $query->whereHas('role', function ($q) use ($roleFilter) {
    //                     $q->where('name', $roleFilter);
    //                 });
    //             })
    //             ->get()
    //             ->filter(function ($user) {
    //                 return $user->customer != null || $user->vendor != null;
    //             })
    //             ->map(function ($user) {
    //                 return [
    //                     'id' => (string) $user->id,
    //                     'name' => $user->name,
    //                     'role' => $user->getRoleNames()->first(),
    //                     'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
    //                     'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
    //                     'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
    //                         return $purchase->purchaseItems->sum('quantity');
    //                     }) : 0,
    //                     'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
    //                         return $sale->sale_return ? $sale->sale_return->count() : 0;
    //                     }) : 0,
    //                     'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
    //                         return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
    //                     }) : 0,
    //                 ];
    //             })->values();
    //     } else {

    //         if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
    //             $warehouseId = session()->get('selected_warehouse_id');
    //             $users = User::with('customer', 'vendor')
    //                 ->whereHas('customer', function ($q) use ($warehouseId) {
    //                     $q->whereHas('sales', function ($q) use ($warehouseId) {
    //                         $q->where('warehouse_id', $warehouseId);
    //                     });
    //                 })
    //                 ->whereHas('role', function ($q) use ($filter) {
    //                     $q->where('name', $filter);
    //                 })
    //                 ->orWhereHas('vendor.purchases', function ($query) use ($warehouseId) {
    //                     $query->where('warehouse_id', $warehouseId);
    //                 })
    //                 ->get()
    //                 ->filter(function ($user) {
    //                     return $user->customer != null || $user->vendor != null;
    //                 })
    //                 ->map(function ($user) {
    //                     return [
    //                         'id' => (string) $user->id,
    //                         'name' => $user->name,
    //                         'role' => $user->getRoleNames()->first(),
    //                         'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
    //                         'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
    //                         'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
    //                             return $purchase->purchaseItems->sum('quantity');
    //                         }) : 0,
    //                         'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
    //                             return $sale->sale_return ? $sale->sale_return->count() : 0;
    //                         }) : 0,
    //                         'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
    //                             return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
    //                         }) : 0,
    //                         // 'total_purchase_return' => $user->vendor ? $user->vendor->purchaseReturns->count() : 0,
    //                     ];
    //                 })->values();
    //         } else {

    //             $users = User::with('customer', 'vendor')
    //                 ->get()
    //                 ->filter(function ($user) {
    //                     return $user->customer != null || $user->vendor != null;
    //                 })
    //                 ->map(function ($user) {
    //                     return [
    //                         'id' => (string) $user->id,
    //                         'name' => $user->name,
    //                         'role' => $user->getRoleNames()->first(),
    //                         'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
    //                         'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
    //                         'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
    //                             return $purchase->purchaseItems->sum('quantity');
    //                         }) : 0,
    //                         'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
    //                             return $sale->sale_return ? $sale->sale_return->count() : 0;
    //                         }) : 0,
    //                         'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
    //                             return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
    //                         }) : 0,
    //                         // 'total_purchase_return' => $user->vendor ? $user->vendor->purchaseReturns->count() : 0,
    //                     ];
    //                 })->values();
    //         }
    //     }




    //     return view('back.reports.user', compact('users'));
    // }

    public function userFilter2(Request $request)
    {
        $roleName = $request->input('filter');

        if (auth()->user()->hasRole('Manager')) {
            $users = User::with('customer', 'vendor')
                ->whereHas('customer', function ($q) {
                    $q->whereHas('sales', function ($q) {
                        $q->where('warehouse_id', auth()->user()->warehouse_id ?? null);
                    });
                })
                ->orWhereHas('vendor.purchases', function ($query) {
                    $query->where('warehouse_id', auth()->user()->warehouse_id ?? null);
                })
                ->when($roleName, function ($query, $roleName) {
                    return $query->whereHas('roles', function ($q) use ($roleName) {
                        $q->where('name', $roleName);
                    });
                })
                ->get()
                ->filter(function ($user) {
                    return $user->customer != null || $user->vendor != null;
                })
                ->map(function ($user) {
                    return [
                        'id' => (string) $user->id,
                        'name' => $user->name,
                        'role' => $user->getRoleNames()->first(),
                        'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
                        'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
                        'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                            return $purchase->purchaseItems->sum('quantity');
                        }) : 0,
                        'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
                            return $sale->sale_return ? $sale->sale_return->count() : 0;
                        }) : 0,
                        'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                            return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
                        }) : 0,
                    ];
                })->values();
        } else {
            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $users = User::with('customer', 'vendor')
                    ->whereHas('customer', function ($q) use ($warehouseId) {
                        $q->whereHas('sales', function ($q) use ($warehouseId) {
                            $q->where('warehouse_id', $warehouseId);
                        });
                    })
                    ->orWhereHas('vendor.purchases', function ($query) use ($warehouseId) {
                        $query->where('warehouse_id', $warehouseId);
                    })
                    ->when($roleName, function ($query, $roleName) {
                        return $query->whereHas('roles', function ($q) use ($roleName) {
                            $q->where('name', $roleName);
                        });
                    })
                    ->get()
                    ->filter(function ($user) {
                        return $user->customer != null || $user->vendor != null;
                    })
                    ->map(function ($user) {
                        return [
                            'id' => (string) $user->id,
                            'name' => $user->name,
                            'role' => $user->getRoleNames()->first(),
                            'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
                            'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
                            'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchaseItems->sum('quantity');
                            }) : 0,
                            'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
                                return $sale->sale_return ? $sale->sale_return->count() : 0;
                            }) : 0,
                            'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
                            }) : 0,
                        ];
                    })->values();
            } else {
                $users = User::with('customer', 'vendor')
                    ->when($roleName, function ($query, $roleName) {
                        return $query->whereHas('roles', function ($q) use ($roleName) {
                            $q->where('name', $roleName);
                        });
                    })
                    ->get()
                    ->filter(function ($user) {
                        return $user->customer != null || $user->vendor != null;
                    })
                    ->map(function ($user) {
                        return [
                            'id' => (string) $user->id,
                            'name' => $user->name,
                            'role' => $user->getRoleNames()->first(),
                            'total_sales' => $user->customer ? $user->customer->sales->count() : 0,
                            'total_purchase' => $user->vendor ? $user->vendor->purchases->count() : 0,
                            'total_inventory' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchaseItems->sum('quantity');
                            }) : 0,
                            'total_sale_return' => $user->customer ? $user->customer->sales->sum(function ($sale) {
                                return $sale->sale_return ? $sale->sale_return->count() : 0;
                            }) : 0,
                            'total_purchase_return' => $user->vendor ? $user->vendor->purchases->sum(function ($purchase) {
                                return $purchase->purchase_return ? $purchase->purchase_return->count() : 0;
                            }) : 0,
                        ];
                    })->values();
            }
        }

        return view('back.reports.user', compact('users'));
    }




    public function userShow($id)
    {
        // dd('he');
        $user = User::find($id);
        $customer = Customer::where('user_id', $id)->first();

        if (auth()->user()->hasRole('Manager')) {


            if ($customer) {
                $customer_sales = Sale::where('customer_id', $customer->id)
                    ->where('warehouse_id', auth()->user()->warehouse_id)
                    ->get();
                $sales = Sale::where('customer_id', $customer->id)
                    ->where('warehouse_id', auth()->user()->warehouse_id)

                    ->get()
                    ->map(function ($sale) {
                        return [
                            'id' => $sale->id,
                            'date' => $sale->date,
                            'reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? '',
                            'warehouse' => optional($sale->warehouse)->users->name ?? '',
                            'quantity' => $sale->productItems->sum('quantity'),
                            'status' => $sale->status,
                            'grand_total' => $sale->grand_total,
                            'paid' => $sale->amount_recieved,
                            'due' => $sale->amount_due,
                            'payment_status' => $sale->payment_status,
                            'shipping_status' => $sale->shipment->status ?? '',
                        ];
                    });

                $sale_return = $customer_sales->flatMap(function ($sale) {
                    if ($sale->sale_return != null) {
                        $return = $sale->sale_return;
                        return [[
                            'id' => $return->id,
                            'reference' => $return->reference,
                            'customer' => optional($return->sales->customer)->user->name ?? '',
                            'sale_reference' => $return->sales->reference,
                            'warehouse' => optional($return->sales->warehouse)->users->name ?? '',
                            'grand_total' => $return->sales->grand_total,
                            'paid' => $return->sales->amount_recieved,
                            'due' => $return->sales->amount_due,
                            'status' => $return->status,
                            'payment_status' => $return->payment_status,
                        ]];
                    }
                });


                return view('back.reports.user_show', compact('user', 'customer', 'sales', 'sale_return'));
            } else {
                $vendor = Vendor::where('user_id', $id)->first();
                $vendor_purchases = Purchase::where('vendor_id', $vendor->id)
                    ->where('warehouse_id', auth()->user()->warehouse_id)
                    ->get();
                $purchases = Purchase::where('vendor_id', $vendor->id)
                    ->where('warehouse_id', auth()->user()->warehouse_id)

                    ->get()
                    ->map(function ($purchase) {
                        return [
                            'id' => $purchase->id,
                            'date' => $purchase->date,
                            'reference' => $purchase->reference,
                            'vendor' => optional($purchase->vendor)->user->name ?? '',
                            'warehouse' => optional($purchase->warehouse)->users->name ?? '',
                            'status' => $purchase->status,
                            'grand_total' => $purchase->grand_total,
                            'paid' => $purchase->amount_recieved,
                            'due' => $purchase->amount_due,
                            'payment_status' => $purchase->payment_status,
                            'shipping_status' => $sale->shipment->status ?? '',
                        ];
                    });
                $purchase_return = $vendor_purchases->flatMap(function ($purchase) {
                    if ($purchase->purchase_return != null) {
                        $return = $purchase->purchase_return;
                        return [[
                            'id' => $return->id,
                            'reference' => $return->reference,
                            'vendor' => optional($return->purchase->vendor)->user->name ?? '',
                            'purchase_reference' => $return->purchase->reference,
                            'warehouse' => optional($return->purchase->warehouse)->users->name ?? '',
                            'grand_total' => $return->purchase->grand_total,
                            'paid' => $return->purchase->amount_recieved,
                            'due' => $return->purchase->amount_due,
                            'status' => $return->status,
                            'payment_status' => $return->payment_status,
                        ]];
                    }
                });
                return view('back.reports.user_show', compact('user', 'vendor', 'purchases', 'purchase_return'));
            }
        }
        if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {

            $warehouseId = session()->get('selected_warehouse_id');
            if ($customer) {
                $customer_sales = Sale::where('customer_id', $customer->id)
                    ->where('warehouse_id', $warehouseId)
                    ->get();
                $sales = Sale::where('customer_id', $customer->id)
                    ->where('warehouse_id', $warehouseId)
                    ->get()
                    ->map(function ($sale) {
                        return [
                            'id' => $sale->id,
                            'date' => $sale->date,
                            'reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? '',
                            'warehouse' => optional($sale->warehouse)->users->name ?? '',
                            'quantity' => $sale->productItems->sum('quantity'),
                            'status' => $sale->status,
                            'grand_total' => $sale->grand_total,
                            'paid' => $sale->amount_recieved,
                            'due' => $sale->amount_due,
                            'payment_status' => $sale->payment_status,
                            'shipping_status' => $sale->shipment->status ?? '',
                        ];
                    });

                $sale_return = $customer_sales->flatMap(function ($sale) {
                    if ($sale->sale_return != null) {
                        $return = $sale->sale_return;
                        return [[
                            'id' => $return->id,
                            'reference' => $return->reference,
                            'customer' => optional($return->sales->customer)->user->name ?? '',
                            'sale_reference' => $return->sales->reference,
                            'warehouse' => optional($return->sales->warehouse)->users->name ?? '',
                            'grand_total' => $return->sales->grand_total,
                            'paid' => $return->sales->amount_recieved,
                            'due' => $return->sales->amount_due,
                            'status' => $return->status,
                            'payment_status' => $return->payment_status,
                        ]];
                    }
                });


                return view('back.reports.user_show', compact('user', 'customer', 'sales', 'sale_return'));
            } else {
                $vendor = Vendor::where('user_id', $id)->first();
                $vendor_purchases = Purchase::where('vendor_id', $vendor->id)
                    ->where('warehouse_id', $warehouseId)
                    ->get();
                $purchases = Purchase::where('vendor_id', $vendor->id)
                    ->where('warehouse_id', $warehouseId)
                    ->get()
                    ->map(function ($purchase) {
                        return [
                            'id' => $purchase->id,
                            'date' => $purchase->date,
                            'reference' => $purchase->reference,
                            'vendor' => optional($purchase->vendor)->user->name ?? '',
                            'warehouse' => optional($purchase->warehouse)->users->name ?? '',
                            'status' => $purchase->status,
                            'grand_total' => $purchase->grand_total,
                            'paid' => $purchase->amount_recieved,
                            'due' => $purchase->amount_due,
                            'payment_status' => $purchase->payment_status,
                            'shipping_status' => $sale->shipment->status ?? '',
                        ];
                    });
                $purchase_return = $vendor_purchases->flatMap(function ($purchase) {
                    if ($purchase->purchase_return != null) {
                        $return = $purchase->purchase_return;
                        return [[
                            'id' => $return->id,
                            'reference' => $return->reference,
                            'vendor' => optional($return->purchase->vendor)->user->name ?? '',
                            'purchase_reference' => $return->purchase->reference,
                            'warehouse' => optional($return->purchase->warehouse)->users->name ?? '',
                            'grand_total' => $return->purchase->grand_total,
                            'paid' => $return->purchase->amount_recieved,
                            'due' => $return->purchase->amount_due,
                            'status' => $return->status,
                            'payment_status' => $return->payment_status,
                        ]];
                    }
                });
                return view('back.reports.user_show', compact('user', 'vendor', 'purchases', 'purchase_return'));
            }
        } else {
            if ($customer) {
                $customer_sales = Sale::where('customer_id', $customer->id)
                    ->get();
                $sales = Sale::where('customer_id', $customer->id)
                    ->get()
                    ->map(function ($sale) {
                        return [
                            'id' => $sale->id,
                            'date' => $sale->date,
                            'reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? '',
                            'warehouse' => optional($sale->warehouse)->users->name ?? '',
                            'quantity' => $sale->productItems->sum('quantity'),
                            'status' => $sale->status,
                            'grand_total' => $sale->grand_total,
                            'paid' => $sale->amount_recieved,
                            'due' => $sale->amount_due,
                            'payment_status' => $sale->payment_status,
                            'shipping_status' => $sale->shipment->status ?? '',
                        ];
                    });

                $sale_return = $customer_sales->flatMap(function ($sale) {
                    if ($sale->sale_return != null) {
                        $return = $sale->sale_return;
                        return [[
                            'id' => $return->id,
                            'reference' => $return->reference,
                            'customer' => optional($return->sales->customer)->user->name ?? '',
                            'sale_reference' => $return->sales->reference,
                            'warehouse' => optional($return->sales->warehouse)->users->name ?? '',
                            'grand_total' => $return->sales->grand_total,
                            'paid' => $return->sales->amount_recieved,
                            'due' => $return->sales->amount_due,
                            'status' => $return->status,
                            'payment_status' => $return->payment_status,
                        ]];
                    }
                });


                return view('back.reports.user_show', compact('user', 'customer', 'sales', 'sale_return'));
            } else {
                $vendor = Vendor::where('user_id', $id)->first();
                $vendor_purchases = Purchase::where('vendor_id', $vendor->id)
                    ->get();
                $purchases = Purchase::where('vendor_id', $vendor->id)
                    ->get()
                    ->map(function ($purchase) {
                        return [
                            'id' => $purchase->id,
                            'date' => $purchase->date,
                            'reference' => $purchase->reference,
                            'vendor' => optional($purchase->vendor)->user->name ?? '',
                            'warehouse' => optional($purchase->warehouse)->users->name ?? '',
                            'status' => $purchase->status,
                            'grand_total' => $purchase->grand_total,
                            'paid' => $purchase->amount_recieved,
                            'due' => $purchase->amount_due,
                            'payment_status' => $purchase->payment_status,
                            'shipping_status' => $sale->shipment->status ?? '',
                        ];
                    });
                $purchase_return = $vendor_purchases->flatMap(function ($purchase) {
                    if ($purchase->purchase_return != null) {
                        $return = $purchase->purchase_return;
                        return [[
                            'id' => $return->id,
                            'reference' => $return->reference,
                            'vendor' => optional($return->purchase->vendor)->user->name ?? '',
                            'purchase_reference' => $return->purchase->reference,
                            'warehouse' => optional($return->purchase->warehouse)->users->name ?? '',
                            'grand_total' => $return->purchase->grand_total,
                            'paid' => $return->purchase->amount_recieved,
                            'due' => $return->purchase->amount_due,
                            'status' => $return->status,
                            'payment_status' => $return->payment_status,
                        ]];
                    }
                });
                return view('back.reports.user_show', compact('user', 'vendor', 'purchases', 'purchase_return'));
            }
        }
    }

    public function bestCustomer()
    {

        if (auth()->user()->hasRole('Manager')) {
            $customers = Sale::with('customer')
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->get()
                ->groupBy('customer_id')
                ->map(function ($group) {
                    return [
                        'customer' => optional($group->first()->customer)->user->name ?? '',
                        'phone' => optional($group->first()->customer)->user->contact_no ?? '',
                        'email' => optional($group->first()->customer)->user->email ?? '',
                        'total_sales' => $group->count(),
                        'total_amount' => $group->sum('grand_total'),
                    ];
                })
                ->values(); // Reset the keys
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouse_id = session()->get('selected_warehouse_id');
                $customers = Sale::with('customer')
                    ->where('warehouse_id', $warehouse_id)
                    ->get()
                    ->groupBy('customer_id')
                    ->map(function ($group) {
                        return [
                            'customer' => optional($group->first()->customer)->user->name ?? '',
                            'phone' => optional($group->first()->customer)->user->contact_no ?? '',
                            'email' => optional($group->first()->customer)->user->email ?? '',
                            'total_sales' => $group->count(),
                            'total_amount' => $group->sum('grand_total'),
                        ];
                    })
                    ->values(); // Reset the keys
            } else {
                $customers = Sale::with('customer')
                    ->get()
                    ->groupBy('customer_id')
                    ->map(function ($group) {
                        return [
                            'customer' => optional($group->first()->customer)->user->name ?? '',
                            'phone' => optional($group->first()->customer)->user->contact_no ?? '',
                            'email' => optional($group->first()->customer)->user->email ?? '',
                            'total_sales' => $group->count(),
                            'total_amount' => $group->sum('grand_total'),
                        ];
                    })
                    ->values(); // Reset the keys
            }
        }


        $best_customers = $customers->sortByDesc('total_sales')->sortByDesc('total_amount');
        return view('back.reports.best_customer', compact('best_customers'));
    }

    public function bestCustomerFilter(Request $request)
    {
        $startDate = date('Y-m-d H:i:s', strtotime($request->start));
        $endDate = date('Y-m-d H:i:s', strtotime($request->end));

        if (auth()->user()->hasRole('Manager')) {

            $customers = Sale::with('customer')
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $groupedCustomers = $customers->filter(function ($sale) {
                return !is_null($sale->customer_id); // Ensure customer_id is not null
            })->groupBy('customer_id')
                ->map(function ($group) {
                    return [
                        'customer' => optional($group->first()->customer)->user->name ?? 'Unknown Customer',
                        'phone' => optional($group->first()->customer)->user->contact_no ?? 'N/A',
                        'email' => optional($group->first()->customer)->user->email ?? 'N/A',
                        'total_sales' => $group->count(),
                        'total_amount' => $group->sum('grand_total'),
                    ];
                })
                ->values();
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouse_id = session()->get('selected_warehouse_id');
                $customers = Sale::with('customer')
                    ->where('warehouse_id', $warehouse_id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();

                $groupedCustomers = $customers->filter(function ($sale) {
                    return !is_null($sale->customer_id); // Ensure customer_id is not null
                })->groupBy('customer_id')
                    ->map(function ($group) {
                        return [
                            'customer' => optional($group->first()->customer)->user->name ?? 'Unknown Customer',
                            'phone' => optional($group->first()->customer)->user->contact_no ?? 'N/A',
                            'email' => optional($group->first()->customer)->user->email ?? 'N/A',
                            'total_sales' => $group->count(),
                            'total_amount' => $group->sum('grand_total'),
                        ];
                    })
                    ->values();
            } else {
                $customers = Sale::with('customer')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();

                $groupedCustomers = $customers->filter(function ($sale) {
                    return !is_null($sale->customer_id); // Ensure customer_id is not null
                })->groupBy('customer_id')
                    ->map(function ($group) {
                        return [
                            'customer' => optional($group->first()->customer)->user->name ?? 'Unknown Customer',
                            'phone' => optional($group->first()->customer)->user->contact_no ?? 'N/A',
                            'email' => optional($group->first()->customer)->user->email ?? 'N/A',
                            'total_sales' => $group->count(),
                            'total_amount' => $group->sum('grand_total'),
                        ];
                    })
                    ->values();
            }
        }

        return ['data' => $groupedCustomers];
    }

    public function topProductReport()
    {

        if (auth()->user()->hasRole('Manager')) {
            $product_items = ProductItem::with('product')
                ->whereHas('sale', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('COUNT(*) as total_sales'))
                ->groupBy('product_id')
                ->get()
                ->map(function ($product_item) {
                    return [
                        // 'id' => $product_item->product_id,
                        'code' => $product_item->product->sku,
                        'product' => $product_item->product->name,
                        'total_sales' => $product_item->total_sales,
                        'total_amount' => $product_item->total_quantity,
                    ];
                });
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouse_id = session()->get('selected_warehouse_id');
                $product_items = ProductItem::with('product')
                    ->whereHas('sale', function ($q) use ($warehouse_id) {
                        $q->where('warehouse_id', $warehouse_id);
                    })
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('COUNT(*) as total_sales'))
                    ->groupBy('product_id')
                    ->get()
                    ->map(function ($product_item) {
                        return [
                            // 'id' => $product_item->product_id,
                            'code' => $product_item->product->sku,
                            'product' => $product_item->product->name,
                            'total_sales' => $product_item->total_sales,
                            'total_amount' => $product_item->total_quantity,
                        ];
                    });
            } else {
                $product_items = ProductItem::with('product')
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('COUNT(*) as total_sales'))
                    ->groupBy('product_id')
                    ->get()
                    ->map(function ($product_item) {
                        return [
                            // 'id' => $product_item->product_id,
                            'code' => $product_item->product->sku,
                            'product' => $product_item->product->name,
                            'total_sales' => $product_item->total_sales,
                            'total_amount' => $product_item->total_quantity,
                        ];
                    });
            }
        }


        $top_products = $product_items->sortByDesc('total_quantity');
        return view('back.reports.top_product', compact('top_products'));
    }

    public function topProductFilter(Request $request)
    {
        $startDate = date('Y-m-d H:i:s', strtotime($request->start));
        $endDate = date('Y-m-d H:i:s', strtotime($request->end));

        if (auth()->user()->hasRole('Manager')) {
            $product_items = ProductItem::whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('sale', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('COUNT(*) as total_sales'))
                ->groupBy('product_id')
                ->get()
                ->map(function ($product_item) {
                    return [
                        'code' => $product_item->product->sku,
                        'product' => $product_item->product->name,
                        'total_sales' => $product_item->total_sales,
                        'total_amount' => $product_item->total_quantity,
                    ];
                });
        } else {

            if (auth()->user()->hasRole('Admin') && session()->has('selected_warehouse_id')) {
                $warehouse_id = session()->get('selected_warehouse_id');
                $product_items = ProductItem::whereBetween('created_at', [$startDate, $endDate])
                    ->whereHas('sale', function ($q) use ($warehouse_id) {
                        $q->where('warehouse_id', $warehouse_id);
                    })
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('COUNT(*) as total_sales'))
                    ->groupBy('product_id')
                    ->get()
                    ->map(function ($product_item) {
                        return [
                            'code' => $product_item->product->sku,
                            'product' => $product_item->product->name,
                            'total_sales' => $product_item->total_sales,
                            'total_amount' => $product_item->total_quantity,
                        ];
                    });
            } else {
                $product_items = ProductItem::whereBetween('created_at', [$startDate, $endDate])
                    ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('COUNT(*) as total_sales'))
                    ->groupBy('product_id')
                    ->get()
                    ->map(function ($product_item) {
                        return [
                            'code' => $product_item->product->sku,
                            'product' => $product_item->product->name,
                            'total_sales' => $product_item->total_sales,
                            'total_amount' => $product_item->total_quantity,
                        ];
                    });
            }
        }



        $top_products = $product_items->sortByDesc('total_quantity');
        return ['data' => $top_products];
    }

    public function salePaymentReport()
    {

        if (auth()->user()->hasRole(['Manager'])) {
            $sales = Sale::with('customer', 'invoice')
                ->whereIn('payment_status', ['paid', 'partial'])
                ->where('warehouse_id', auth()->user()->warehouse_id ?? null)
                ->get()
                ->map(function ($sale) {
                    return [
                        'date' => $sale->date,
                        'invoice_id' => $sale->invoice->invoice_id,
                        'sale_id' => $sale->id,
                        'sale_reference' => $sale->reference,
                        'customer' => optional($sale->customer)->user->name ?? '',
                        'paid_by' => $sale->payment_method,
                        'payment_status' => $sale->payment_status,
                        'account' => $sale->account->name ?? '',
                        'total_amount' => $sale->grand_total,
                    ];
                })
                ->values(); // Reset the keys
        } else {

            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouse_id = session()->get('selected_warehouse_id');
                $sales = Sale::with('customer', 'invoice')
                    ->whereIn('payment_status', ['paid', 'partial'])
                    ->where('warehouse_id', $warehouse_id)
                    ->get()
                    ->map(function ($sale) {
                        return [
                            'date' => $sale->date,
                            'invoice_id' => $sale->invoice->invoice_id,
                            'sale_id' => $sale->id,
                            'sale_reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? '',
                            'paid_by' => $sale->payment_method,
                            'payment_status' => $sale->payment_status,
                            'account' => $sale->account->name ?? '',
                            'total_amount' => $sale->grand_total,
                        ];
                    })
                    ->values(); // Reset the keys
            } else {

                $sales = Sale::with('customer', 'invoice')
                    ->whereIn('payment_status', ['paid', 'partial'])
                    ->get()
                    ->map(function ($sale) {
                        return [
                            'date' => $sale->date,
                            'invoice_id' => $sale->invoice->invoice_id,
                            'sale_id' => $sale->id,
                            'sale_reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? '',
                            'paid_by' => $sale->payment_method,
                            'payment_status' => $sale->payment_status,
                            'account' => $sale->account->name ?? '',
                            'total_amount' => $sale->grand_total,
                        ];
                    })
                    ->values(); // Reset the keys
            }
        }



        // $salesInvoicePayments = SalesInvoicePayment::with('salesPayment','saleInvoice')
        // ->get()
        // ->map(function($payment){
        //     // dd($payment);
        //     return [
        //         'date' => $payment->salesPayment->payment_date,
        //         'invoice_id' => $payment->saleInvoice->invoice_id,
        //         'sale_id' => $payment->saleInvoice->sale->id,
        //         'sale_reference' => $payment->saleInvoice->sale->reference,
        //         'customer' => optional($payment->salesPayment->customer)->user->name ?? '',
        //         // 'paid_by' => $payment->salesPayment->payment_method,
        //         'payment_status' => $payment->salesPayment->payment_status,
        //         'account' => $payment->salesPayment->account->name ?? '',
        //         'total_amount' => $payment->paid_amount,
        //     ];
        // });
        $customers = Customer::with('user')->get();
        // return view('back.reports.sale_payment', compact('salesInvoicePayments','customers'));
        return view('back.reports.sale_payment', compact('sales', 'customers'));
    }

    public function salePaymentFilter(Request $request)
    {
        $startDate = date('Y-m-d H:i:s', strtotime($request->start));
        $endDate = date('Y-m-d H:i:s', strtotime($request->end));

        if (auth()->user()->hasRole('Manager')) {
            $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
                ->with('customer', 'invoice')
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->get()
                ->whereIn('payment_status', ['paid', 'partial'])
                ->map(function ($sale) {
                    return [
                        'date' => $sale->date,
                        'invoice_id' => $sale->invoice->invoice_id,
                        'sale_reference' => $sale->reference,
                        'customer' => optional($sale->customer)->user->name ?? '',
                        'paid_by' => $sale->payment_method,
                        'payment_status' => $sale->payment_status,
                        'account' => $sale->account->name ?? '',
                        'total_amount' => $sale->grand_total,
                    ];
                })
                ->values(); // Reset the keys
        } else {

            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouse_id = session()->get('selected_warehouse_id');
                $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
                    ->with('customer', 'invoice')
                    ->where('warehouse_id', $warehouse_id)
                    ->get()
                    ->whereIn('payment_status', ['paid', 'partial'])
                    ->map(function ($sale) {
                        return [
                            'date' => $sale->date,
                            'invoice_id' => $sale->invoice->invoice_id,
                            'sale_reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? '',
                            'paid_by' => $sale->payment_method,
                            'payment_status' => $sale->payment_status,
                            'account' => $sale->account->name ?? '',
                            'total_amount' => $sale->grand_total,
                        ];
                    })
                    ->values(); // Reset the keys
            } else {
                $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
                    ->with('customer', 'invoice')
                    ->get()
                    ->whereIn('payment_status', ['paid', 'partial'])
                    ->map(function ($sale) {
                        return [
                            'date' => $sale->date,
                            'invoice_id' => $sale->invoice->invoice_id,
                            'sale_reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? '',
                            'paid_by' => $sale->payment_method,
                            'payment_status' => $sale->payment_status,
                            'account' => $sale->account->name ?? '',
                            'total_amount' => $sale->grand_total,
                        ];
                    })
                    ->values(); // Reset the keys
            }
        }

        return ['data' => $sales];
    }

    public function salePaymentFilter2(Request $request)
    {

        if (auth()->user()->hasRole('Manager')) {
            $sales = Sale::with('customer', 'invoice')->where('customer_id', $request->customer_id)
                ->whereIn('payment_status', ['paid', 'partial'])
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->get()
                ->map(function ($sale) {
                    // dd($sale->date);
                    return [
                        'date' => $sale->date,
                        'invoice_id' => $sale->invoice->invoice_id,
                        'sale_id' => $sale->id,
                        'sale_reference' => $sale->reference,
                        'customer' => optional($sale->customer)->user->name ?? '',
                        'paid_by' => $sale->payment_method,
                        'payment_status' => $sale->payment_status,
                        'account' => $sale->bank_account,
                        'total_amount' => $sale->grand_total,
                    ];
                })
                ->values(); // Reset the keys
        } else {

            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouse_id = session()->get('selected_warehouse_id');
                $sales = Sale::with('customer', 'invoice')->where('customer_id', $request->customer_id)
                    ->whereIn('payment_status', ['paid', 'partial'])
                    ->where('warehouse_id', $warehouse_id)
                    ->get()
                    ->map(function ($sale) {
                        // dd($sale->date);
                        return [
                            'date' => $sale->date,
                            'invoice_id' => $sale->invoice->invoice_id,
                            'sale_id' => $sale->id,
                            'sale_reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? '',
                            'paid_by' => $sale->payment_method,
                            'payment_status' => $sale->payment_status,
                            'account' => $sale->bank_account,
                            'total_amount' => $sale->grand_total,
                        ];
                    })
                    ->values(); // Reset the keys
            } else {
                $sales = Sale::with('customer', 'invoice')->where('customer_id', $request->customer_id)
                    ->whereIn('payment_status', ['paid', 'partial'])
                    ->get()
                    ->map(function ($sale) {
                        // dd($sale->date);
                        return [
                            'date' => $sale->date,
                            'invoice_id' => $sale->invoice->invoice_id,
                            'sale_id' => $sale->id,
                            'sale_reference' => $sale->reference,
                            'customer' => optional($sale->customer)->user->name ?? '',
                            'paid_by' => $sale->payment_method,
                            'payment_status' => $sale->payment_status,
                            'account' => $sale->bank_account,
                            'total_amount' => $sale->grand_total,
                        ];
                    })
                    ->values(); // Reset the keys
            }


            // $sales = Sale::with('customer', 'invoice')->where('customer_id', $request->customer_id)
            //     ->whereIn('payment_status', ['paid', 'partial'])
            //     ->get()
            //     ->map(function ($sale) {
            //         // dd($sale->date);
            //         return [
            //             'date' => $sale->date,
            //             'invoice_id' => $sale->invoice->invoice_id,
            //             'sale_id' => $sale->id,
            //             'sale_reference' => $sale->reference,
            //             'customer' => optional($sale->customer)->user->name ?? '',
            //             'paid_by' => $sale->payment_method,
            //             'payment_status' => $sale->payment_status,
            //             'account' => $sale->bank_account,
            //             'total_amount' => $sale->grand_total,
            //         ];
            //     })
            //     ->values(); // Reset the keys
        }

        $customers = Customer::with('user')->get();
        return view('back.reports.sale_payment', compact('sales', 'customers'));
    }

    public function purchasePaymentReport()
    {

        if (auth()->user()->hasRole('Manager')) {
            $purchases = Purchase::with('vendor', 'invoice')
                ->whereIn('payment_status', ['paid', 'partial'])
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->get()
                ->map(function ($purchase) {
                    // dd($purchase->date);
                    return [
                        'date' => $purchase->date,
                        'invoice_id' => $purchase->invoice->invoice_number,
                        'purchase_id' => $purchase->id,
                        'purchase_reference' => $purchase->reference,
                        'supplier' => optional($purchase->vendor)->user->name ?? '',
                        'paid_by' => $purchase->payment_method ?? 'Bank',
                        'payment_status' => $purchase->payment_status,
                        'account' => $purchase->account->name ?? '...',
                        'total_amount' => $purchase->grand_total,
                    ];
                })
                ->values(); // Reset the keys
        } else {
            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouse_id = session()->get('selected_warehouse_id');
                $purchases = Purchase::with('vendor', 'invoice')
                    ->whereIn('payment_status', ['paid', 'partial'])
                    ->where('warehouse_id', $warehouse_id)
                    ->get()
                    ->map(function ($purchase) {
                        // dd($purchase->date);
                        return [
                            'date' => $purchase->date,
                            'invoice_id' => $purchase->invoice->invoice_number,
                            'purchase_id' => $purchase->id,
                            'purchase_reference' => $purchase->reference,
                            'supplier' => optional($purchase->vendor)->user->name ?? '',
                            'paid_by' => $purchase->payment_method ?? 'Bank',
                            'payment_status' => $purchase->payment_status,
                            'account' => $purchase->account->name ?? '...',
                            'total_amount' => $purchase->grand_total,
                        ];
                    })
                    ->values(); // Reset the keys
            } else {
                $purchases = Purchase::with('vendor', 'invoice')
                    ->whereIn('payment_status', ['paid', 'partial'])
                    ->get()
                    ->map(function ($purchase) {
                        // dd($purchase->date);
                        return [
                            'date' => $purchase->date,
                            'invoice_id' => $purchase->invoice->invoice_number,
                            'purchase_id' => $purchase->id,
                            'purchase_reference' => $purchase->reference,
                            'supplier' => optional($purchase->vendor)->user->name ?? '',
                            'paid_by' => $purchase->payment_method ?? 'Bank',
                            'payment_status' => $purchase->payment_status,
                            'account' => $purchase->account->name ?? '...',
                            'total_amount' => $purchase->grand_total,
                        ];
                    })
                    ->values(); // Reset the keys
            }

            // $purchases = Purchase::with('vendor', 'invoice')
            //     ->whereIn('payment_status', ['paid', 'partial'])
            //     ->get()
            //     ->map(function ($purchase) {
            //         // dd($purchase->date);
            //         return [
            //             'date' => $purchase->date,
            //             'invoice_id' => $purchase->invoice->invoice_number,
            //             'purchase_id' => $purchase->id,
            //             'purchase_reference' => $purchase->reference,
            //             'supplier' => optional($purchase->vendor)->user->name ?? '',
            //             'paid_by' => $purchase->payment_method,
            //             'payment_status' => $purchase->payment_status,
            //             'account' => $purchase->bank_account,
            //             'total_amount' => $purchase->grand_total,
            //         ];
            //     })
            //     ->values(); // Reset the keys
        }

        $vendors = Vendor::with('user')->get();

        return view('back.reports.purchase_payment', compact('purchases', 'vendors'));
    }

    public function purchasePaymentFilter(Request $request)
    {
        $startDate = date('Y-m-d H:i:s', strtotime($request->start));
        $endDate = date('Y-m-d H:i:s', strtotime($request->end));


        if (auth()->user()->hasRole('Manager')) {

            $purchases = Purchase::whereBetween('created_at', [$startDate, $endDate])
                ->with('vendor', 'invoice')
                ->whereIn('payment_status', ['paid', 'partial'])
                ->where('warehouse_id', auth()->user()->warehouse_id)
                ->get()
                ->map(function ($purchase) {

                    return [
                        'date' => $purchase->date,
                        'invoice_id' => $purchase->invoice->invoice_number,
                        'purchase_id' => $purchase->id,
                        'purchase_reference' => $purchase->reference,
                        'supplier' => optional($purchase->vendor)->user->name ?? '',
                        'paid_by' => $purchase->payment_method ?? '...',
                        'payment_status' => $purchase->payment_status,
                        'account' => $purchase->account->name ?? '...',
                        'total_amount' => $purchase->grand_total,
                    ];
                })
                ->values(); // Reset the keys
        } else {

            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouse_id = session()->get('selected_warehouse_id');
                $purchases = Purchase::whereBetween('created_at', [$startDate, $endDate])
                    ->with('vendor', 'invoice')
                    ->whereIn('payment_status', ['paid', 'partial'])
                    ->where('warehouse_id', $warehouse_id)
                    ->get()
                    ->map(function ($purchase) {

                        return [
                            'date' => $purchase->date,
                            'invoice_id' => $purchase->invoice->invoice_number,
                            'purchase_id' => $purchase->id,
                            'purchase_reference' => $purchase->reference,
                            'supplier' => optional($purchase->vendor)->user->name ?? '',
                            'paid_by' => $purchase->payment_method ?? '...',
                            'payment_status' => $purchase->payment_status,
                            'account' => $purchase->account->name ?? '...',
                            'total_amount' => $purchase->grand_total,
                        ];
                    })
                    ->values(); // Reset the keys
            } else {
                $purchases = Purchase::whereBetween('created_at', [$startDate, $endDate])
                    ->with('vendor', 'invoice')
                    ->whereIn('payment_status', ['paid', 'partial'])
                    ->get()
                    ->map(function ($purchase) {

                        return [
                            'date' => $purchase->date,
                            'invoice_id' => $purchase->invoice->invoice_number,
                            'purchase_id' => $purchase->id,
                            'purchase_reference' => $purchase->reference,
                            'supplier' => optional($purchase->vendor)->user->name ?? '',
                            'paid_by' => $purchase->payment_method ?? '...',
                            'payment_status' => $purchase->payment_status,
                            'account' => $purchase->account->name ?? '...',
                            'total_amount' => $purchase->grand_total,
                        ];
                    })
                    ->values(); // Reset the keys
            }
        }



        return ['data' => $purchases];
    }
    // public function purchasePaymentFilterByVendor(Request $request)
    // {



    //     if (auth()->user()->hasRole('Manager')) {

    //         $purchases = Purchase::with('vendor', 'invoice')
    //             ->whereIn('payment_status', ['paid', 'partial'])
    //             ->where('warehouse_id', auth()->user()->warehouse_id)
    //             ->where('vendor_id', $request->vendor_id)
    //             ->get()
    //             ->map(function ($purchase) {

    //                 return [
    //                     'date' => $purchase->date,
    //                     'invoice_id' => $purchase->invoice->invoice_number,
    //                     'purchase_id' => $purchase->id,
    //                     'purchase_reference' => $purchase->reference,
    //                     'supplier' => optional($purchase->vendor)->user->name ?? '',
    //                     'paid_by' => $purchase->payment_method ?? '...',
    //                     'payment_status' => $purchase->payment_status,
    //                     'account' => $purchase->account->name ?? '...',
    //                     'total_amount' => $purchase->grand_total,
    //                 ];
    //             })
    //             ->values(); // Reset the keys
    //     } else {

    //         if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
    //             $warehouse_id = session()->get('selected_warehouse_id');
    //             $purchases = Purchase::with('vendor', 'invoice')
    //                 ->whereIn('payment_status', ['paid', 'partial'])
    //                 ->where('warehouse_id', $warehouse_id)
    //                 ->where('vendor_id', $request->vendor_id)
    //                 ->get()
    //                 ->map(function ($purchase) {

    //                     return [
    //                         'date' => $purchase->date,
    //                         'invoice_id' => $purchase->invoice->invoice_number,
    //                         'purchase_id' => $purchase->id,
    //                         'purchase_reference' => $purchase->reference,
    //                         'supplier' => optional($purchase->vendor)->user->name ?? '',
    //                         'paid_by' => $purchase->payment_method ?? '...',
    //                         'payment_status' => $purchase->payment_status,
    //                         'account' => $purchase->account->name ?? '...',
    //                         'total_amount' => $purchase->grand_total,
    //                     ];
    //                 })
    //                 ->values(); // Reset the keys
    //         } else {
    //             $purchases = Purchase::with('vendor', 'invoice')
    //                 ->whereIn('payment_status', ['paid', 'partial'])
    //                 ->where('vendor_id', $request->vendor_id)
    //                 ->get()
    //                 ->map(function ($purchase) {

    //                     return [
    //                         'date' => $purchase->date,
    //                         'invoice_id' => $purchase->invoice->invoice_number,
    //                         'purchase_id' => $purchase->id,
    //                         'purchase_reference' => $purchase->reference,
    //                         'supplier' => optional($purchase->vendor)->user->name ?? '',
    //                         'paid_by' => $purchase->payment_method ?? '...',
    //                         'payment_status' => $purchase->payment_status,
    //                         'account' => $purchase->account->name ?? '...',
    //                         'total_amount' => $purchase->grand_total,
    //                     ];
    //                 })
    //                 ->values(); // Reset the keys
    //         }
    //     }


    //     $vendors = Vendor::with('user')->get();

    //     return view('back.reports.purchase_payment', compact('purchases', 'vendors'));

    // }


    public function purchasePaymentFilterByVendor(Request $request)
    {
        if (auth()->user()->hasRole('Manager')) {
            $purchases = Purchase::with('vendor', 'invoice')
                ->whereIn('payment_status', ['paid', 'partial'])
                ->where('warehouse_id', auth()->user()->warehouse_id);

            // Check if a specific vendor is selected
            if ($request->vendor_id) {
                $purchases->where('vendor_id', $request->vendor_id);
            }

            $purchases = $purchases->get()->map(function ($purchase) {
                return [
                    'date' => $purchase->date,
                    'invoice_id' => $purchase->invoice->invoice_number,
                    'purchase_id' => $purchase->id,
                    'purchase_reference' => $purchase->reference,
                    'supplier' => optional($purchase->vendor)->user->name ?? '',
                    'paid_by' => $purchase->payment_method ?? '...',
                    'payment_status' => $purchase->payment_status,
                    'account' => $purchase->account->name ?? '...',
                    'total_amount' => $purchase->grand_total,
                ];
            })->values();
        } else {
            // If Admin
            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouse_id = session()->get('selected_warehouse_id');
                $purchases = Purchase::with('vendor', 'invoice')
                    ->whereIn('payment_status', ['paid', 'partial'])
                    ->where('warehouse_id', $warehouse_id);
            } else {
                $purchases = Purchase::with('vendor', 'invoice')
                    ->whereIn('payment_status', ['paid', 'partial']);
            }

            // Check if a specific vendor is selected
            if ($request->vendor_id) {
                $purchases->where('vendor_id', $request->vendor_id);
            }

            $purchases = $purchases->get()->map(function ($purchase) {
                return [
                    'date' => $purchase->date,
                    'invoice_id' => $purchase->invoice->invoice_number,
                    'purchase_id' => $purchase->id,
                    'purchase_reference' => $purchase->reference,
                    'supplier' => optional($purchase->vendor)->user->name ?? '',
                    'paid_by' => $purchase->payment_method ?? '...',
                    'payment_status' => $purchase->payment_status,
                    'account' => $purchase->account->name ?? '...',
                    'total_amount' => $purchase->grand_total,
                ];
            })->values();
        }

        // Fetch vendors for the dropdown
        $vendors = Vendor::with('user')->get();

        // Pass purchases and vendors to the view
        return view('back.reports.purchase_payment', compact('purchases', 'vendors'));
    }


    public function inventoryValuation()
    {

        if (auth()->user()->hasRole('Manager')) {
            $warehouse_id = session()->get('selected_warehouse_id');
            $inventories = Product::with('product_warehouses')
                ->whereHas('product_warehouses', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })

                ->get()
                ->map(function ($product) use ($warehouse_id) {
                    $total_stock = $product->product_warehouses
                        ->where('warehouse_id', $warehouse_id)
                        ->sum('quantity');
                    $price = $product->purchase_price ? $product->purchase_price : $product->sell_price;
                    return [
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'total_stock' => $total_stock,
                        'total_price' => ($total_stock * $price),
                    ];
                });
        } else {

            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {

                $warehouse_id = session()->get('selected_warehouse_id');
                $inventories = Product::with('product_warehouses')
                    ->whereHas('product_warehouses', function ($q) use ($warehouse_id) {
                        $q->where('warehouse_id', $warehouse_id);
                    })
                    ->get()
                    ->map(function ($product) use ($warehouse_id) {
                        $total_stock = $product->product_warehouses
                            ->where('warehouse_id', $warehouse_id)
                            ->sum('quantity');
                        // dd($total_stock);

                        $price = $product->purchase_price ? $product->purchase_price : $product->sell_price;
                        return [
                            'name' => $product->name,
                            'sku' => $product->sku,
                            'total_stock' => $total_stock,
                            'total_price' => ($total_stock * $price),
                        ];
                    });
            } else {
                $inventories = Product::with('product_warehouses')
                    ->get()
                    ->map(function ($product) {
                        $total_stock = $product->product_warehouses->sum('quantity');
                        $price = $product->purchase_price ? $product->purchase_price : $product->sell_price;
                        return [
                            'name' => $product->name,
                            'sku' => $product->sku,
                            'total_stock' => $total_stock,
                            'total_price' => ($total_stock * $price),
                        ];
                    });
            }

            return view('back.reports.inventory_valuation', compact('inventories'));
        }
    }
}
