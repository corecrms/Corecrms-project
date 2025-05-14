<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\CarbonPeriod;
use App\Models\SaleReturn;
use App\Models\ProductItem;
use App\Models\ManualReturn;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\ProductWarehouse;
use App\Models\SalesInvoiceDetail;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expense;
use App\Models\PurchaseInvoiceDetail;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // $SalesAmount = SalesInvoiceDetail::sum('price');
        // $PurchaseAmount = PurchaseInvoiceDetail::sum('price');
        $totalCategories = Category::count();

        if (auth()->user()->hasRole('Cashier') || auth()->user()->hasRole('Manager')) {
            $warehouseId = auth()->user()->warehouse_id;
            if($request->start){
                $startDate = Carbon::parse($request->start);
                $endDate = Carbon::parse($request->end);
                $SalesAmount = Sale::where('warehouse_id', $warehouseId)->whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');
                $PurchaseAmount = Purchase::where('warehouse_id', $warehouseId)->whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');
                $manualSaleReturn = ManualReturn::where('warehouse_id', $warehouseId)->whereBetween('created_at', [$startDate, $endDate])->sum('amount_due');
                $saleReturn = SaleReturn::whereBetween('created_at', [$startDate, $endDate])->whereHas('sales', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                })->sum('amount_due');
                $saleReturn += $manualSaleReturn;
                $purchaseReturn = PurchaseReturn::whereBetween('created_at', [$startDate, $endDate])->whereHas('purchase', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                })->sum('amount_due');
            }
            else
            {
                $SalesAmount = Sale::where('warehouse_id', $warehouseId)->sum('grand_total');
                $PurchaseAmount = Purchase::where('warehouse_id', $warehouseId)->sum('grand_total');
                $manualSaleReturn = ManualReturn::where('warehouse_id', $warehouseId)->sum('amount_due');
                $saleReturn = SaleReturn::whereHas('sales', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                })->sum('amount_due');
                $saleReturn += $manualSaleReturn;
                $purchaseReturn = PurchaseReturn::whereHas('purchase', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                })->sum('amount_due');
            }
            $todaySalesAmount = Sale::where('warehouse_id', $warehouseId)->whereDate('created_at', Carbon::today())->sum('grand_total');
            $todayPurchaseAmount = Purchase::where('warehouse_id', $warehouseId)->whereDate('created_at', Carbon::today())->sum('grand_total');

            $topSellingProducts = ProductItem::select('product_id', DB::raw('sum(quantity) as total_quantity'))
                ->whereHas('sale', function ($query) use ($warehouseId) {
                    // $query->where('warehouse_id', $warehouseId)->whereMonth('created_at', Carbon::now()->month);
                    $query->where('warehouse_id', $warehouseId);
                })
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->get();
            $topSellingProducts->load('product.product_warehouses', 'product.images');

            $topCustomerForMonth = Sale::select('customer_id', DB::raw('count(*) as total_sales'),  DB::raw('sum(grand_total) as total_amount'))
                ->where('warehouse_id', $warehouseId)
                ->whereMonth('created_at', Carbon::now()->month)
                ->groupBy('customer_id')
                ->orderBy('total_sales', 'desc')
                ->get();
            $topCustomerForMonth->load('customer');
            $products = Product::with('product_warehouses')->get();
            $stockAlert = collect();
            foreach ($products as $product) {
                foreach ($product->product_warehouses as $product_warehouse) {
                    if ($product_warehouse->quantity < $product->stock_alert && $product_warehouse->warehouse_id == $warehouseId) {
                        $stockAlert->push($product_warehouse);
                    }
                }
            }
            $stockAlert->each(function ($item) {
                $item->load('product', 'product.images', 'warehouse');
            });
            $recentTransactions = Sale::with('customer', 'warehouse')
                // ->whereMonth('date', Carbon::now()->month)
                ->where('payment_status', 'paid')
                ->where('warehouse_id', $warehouseId)->orderBy('created_at', 'desc')->get();
            $endDate = Carbon::now()->startOfDay();
            $weekSales = [];
            $weakPurchase = collect();
            for ($i = 0; $i < 7; $i++) {
                $date = $endDate->copy()->subDays($i);
                $salesForDay = Sale::where('warehouse_id', $warehouseId)->whereDate('date', $date)
                    ->sum('grand_total');
                $purchaseForDay = Purchase::where('warehouse_id', $warehouseId)->whereDate('date', $date)
                    ->sum('grand_total');
                $weekSales[$date->toDateString()] = $salesForDay;
                $weekPurchase[$date->toDateString()] = $purchaseForDay;
            }
            $weekDays = collect($weekSales)->keys();
            $allWeekDays = collect();
            for ($i = 6; $i >= 0; $i--) {
                $allWeekDays->push(Carbon::now()->subDays($i)->toDateString());
            }
            $missingDays = $allWeekDays->diff($weekDays);
            foreach ($missingDays as $missingDay) {
                $weekSales[$missingDay] = 0;
            }
            $weekDays = collect($weekPurchase)->keys();
            $allWeekDaysPurchase = collect();
            for ($i = 6; $i >= 0; $i--) {
                $allWeekDaysPurchase->push(Carbon::now()->subDays($i)->toDateString());
            }
            $missingDaysPurchase = $allWeekDaysPurchase->diff($weekDays);
            foreach ($missingDaysPurchase as $missingDay) {
                $weekPurchase[$missingDay] = 0;
            }


            $totalExpenses = Expense::where('warehouse_id', $warehouseId)->sum('amount');
            $totalInvoiceDue = Sale::where('warehouse_id', $warehouseId)->sum('amount_due');
            $totalProfit = $SalesAmount - $totalExpenses;

            $statistics = $this->getStatistics();

            // recent sale product of spaecific warehouse
            $recentSaleProducts = ProductItem::whereHas('sale', function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })->latest()->take(5)->get();
            $recentSaleProducts->load('product.product_warehouses', 'product.images', 'sale');

            // total today orders
            $totalTodayOrdersCount = Sale::where('warehouse_id', $warehouseId)->whereDate('created_at', Carbon::today())->count();

            // first low stock product
            $firstLowStockProduct = ProductWarehouse::where('warehouse_id', $warehouseId)->where('quantity','<', 5)->first();


            return view('back.dashboard', compact(
                'SalesAmount',
                'PurchaseAmount',
                'todaySalesAmount',
                'todayPurchaseAmount',
                'saleReturn',
                'purchaseReturn',
                'topSellingProducts',
                'topCustomerForMonth',
                'stockAlert',
                'recentTransactions',
                'weekSales',
                'weekPurchase',
                'totalCategories',
                'totalExpenses',
                'totalInvoiceDue',
                'totalProfit',
                'statistics',
                'recentSaleProducts',
                'totalTodayOrdersCount',
                'firstLowStockProduct',
            ));
        }



        if (session()->has('selected_warehouse_id')) {
            $warehouseId = session('selected_warehouse_id');

            if($request->start){
                $startDate = Carbon::parse($request->start);
                $endDate = Carbon::parse($request->end);
                $SalesAmount = Sale::where('warehouse_id', $warehouseId)->whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');
                $PurchaseAmount = Purchase::where('warehouse_id', $warehouseId)->whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');
                $manualSaleReturn = ManualReturn::where('warehouse_id', $warehouseId)->whereBetween('created_at', [$startDate, $endDate])->sum('amount_due');
                $saleReturn = SaleReturn::whereBetween('created_at', [$startDate, $endDate])->whereHas('sales', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                })->sum('amount_due');
                $saleReturn += $manualSaleReturn;
                $purchaseReturn = PurchaseReturn::whereBetween('created_at', [$startDate, $endDate])->whereHas('purchase', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                })->sum('amount_due');
            }
            else
            {
                $SalesAmount = Sale::where('warehouse_id', $warehouseId)->sum('grand_total');
                $PurchaseAmount = Purchase::where('warehouse_id', $warehouseId)->sum('grand_total');
                $manualSaleReturn = ManualReturn::where('warehouse_id', $warehouseId)->sum('amount_due');
                $saleReturn = SaleReturn::whereHas('sales', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                })->sum('amount_due');
                $saleReturn += $manualSaleReturn;
                $purchaseReturn = PurchaseReturn::whereHas('purchase', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                })->sum('amount_due');
            }

            $todaySalesAmount = Sale::where('warehouse_id', $warehouseId)->whereDate('created_at', Carbon::today())->sum('grand_total');
            $todayPurchaseAmount = Purchase::where('warehouse_id', $warehouseId)->whereDate('created_at', Carbon::today())->sum('grand_total');
            // $saleReturn = SaleReturn::where('warehouse_id', $warehouseId)->sum('amount_due');

            // dd($saleReturn);
            $saleReturn += $manualSaleReturn;
            $topSellingProducts = ProductItem::select('product_id', DB::raw('sum(quantity) as total_quantity'))
                ->whereHas('sale', function ($query) use ($warehouseId) {
                    // $query->where('warehouse_id', $warehouseId)->whereMonth('created_at', Carbon::now()->month);
                    $query->where('warehouse_id', $warehouseId);
                })
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->get();
            $topSellingProducts->load('product.product_warehouses', 'product.images');
            // $topSellingProducts->load('product.images');
            $topCustomerForMonth = Sale::select('customer_id', DB::raw('count(*) as total_sales'),  DB::raw('sum(grand_total) as total_amount'))
                ->where('warehouse_id', $warehouseId)
                ->whereMonth('created_at', Carbon::now()->month)
                ->groupBy('customer_id')
                ->orderBy('total_sales', 'desc')
                ->get();
            $topCustomerForMonth->load('customer');
            $products = Product::with('product_warehouses')->get();
            $stockAlert = collect();
            foreach ($products as $product) {
                foreach ($product->product_warehouses as $product_warehouse) {
                    if ($product_warehouse->quantity < $product->stock_alert && $product_warehouse->warehouse_id == $warehouseId) {
                        $stockAlert->push($product_warehouse);
                    }
                }
            }
            $stockAlert->each(function ($item) {
                $item->load('product', 'product.images', 'warehouse');
            });
            $recentTransactions = Sale::with('customer', 'warehouse')
                // ->whereMonth('date', Carbon::now()->month)
                ->where('payment_status', 'paid')
                ->where('warehouse_id', $warehouseId)->orderBy('created_at', 'desc')->get();
            $endDate = Carbon::now()->startOfDay();
            $weekSales = [];
            $weakPurchase = collect();
            for ($i = 0; $i < 7; $i++) {
                $date = $endDate->copy()->subDays($i);
                $salesForDay = Sale::where('warehouse_id', $warehouseId)->whereDate('date', $date)
                    ->sum('grand_total');
                $purchaseForDay = Purchase::where('warehouse_id', $warehouseId)->whereDate('date', $date)
                    ->sum('grand_total');
                $weekSales[$date->toDateString()] = $salesForDay;
                $weekPurchase[$date->toDateString()] = $purchaseForDay;
            }
            $weekDays = collect($weekSales)->keys();
            $allWeekDays = collect();
            for ($i = 6; $i >= 0; $i--) {
                $allWeekDays->push(Carbon::now()->subDays($i)->toDateString());
            }
            $missingDays = $allWeekDays->diff($weekDays);
            foreach ($missingDays as $missingDay) {
                $weekSales[$missingDay] = 0;
            }
            $weekDays = collect($weekPurchase)->keys();
            $allWeekDaysPurchase = collect();
            for ($i = 6; $i >= 0; $i--) {
                $allWeekDaysPurchase->push(Carbon::now()->subDays($i)->toDateString());
            }
            $missingDaysPurchase = $allWeekDaysPurchase->diff($weekDays);
            foreach ($missingDaysPurchase as $missingDay) {
                $weekPurchase[$missingDay] = 0;
            }


            $totalExpenses = Expense::where('warehouse_id', $warehouseId)->sum('amount');
            $totalInvoiceDue = Sale::where('warehouse_id', $warehouseId)->sum('amount_due');
            $totalProfit = $SalesAmount - $totalExpenses;

            $statistics = $this->getStatistics();
            $recentSaleProducts = ProductItem::whereHas('sale', function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })->latest()->get();
            $recentSaleProducts->load('product.product_warehouses', 'product.images', 'sale');


            // total today orders
            $totalTodayOrdersCount = Sale::where('warehouse_id', $warehouseId)->whereDate('created_at', Carbon::today())->count();

            // first low stock product
            $firstLowStockProduct = ProductWarehouse::where('warehouse_id', $warehouseId)->where('quantity','<', 5)->first();

            return view('back.dashboard', compact(
                'SalesAmount',
                'PurchaseAmount',
                'todaySalesAmount',
                'todayPurchaseAmount',
                'saleReturn',
                'purchaseReturn',
                'topSellingProducts',
                'topCustomerForMonth',
                'stockAlert',
                'recentTransactions',
                'weekSales',
                'weekPurchase',
                'totalCategories',
                'totalExpenses',
                'totalInvoiceDue',
                'totalProfit',
                'statistics',
                'recentSaleProducts',
                'totalTodayOrdersCount',
                'firstLowStockProduct',
            ));
        }

        if($request->start){
            $startDate = Carbon::parse($request->start);
            $endDate = Carbon::parse($request->end);
            $SalesAmount = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');
            $PurchaseAmount = Purchase::whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');
            $manualSaleReturn = ManualReturn::whereBetween('created_at', [$startDate, $endDate])->sum('amount_due');
            $saleReturn = SaleReturn::whereBetween('created_at', [$startDate, $endDate])->sum('amount_due');
            $saleReturn += $manualSaleReturn;
            $purchaseReturn = PurchaseReturn::whereBetween('created_at', [$startDate, $endDate])->sum('amount_due');
        }
        else
        {
            $SalesAmount = Sale::sum('grand_total');
            $PurchaseAmount = Purchase::sum('grand_total');
            $manualSaleReturn = ManualReturn::sum('amount_due');
            $saleReturn = SaleReturn::sum('amount_due');
            $saleReturn += $manualSaleReturn;
            $purchaseReturn = PurchaseReturn::sum('amount_due');
        }

        $todaySalesAmount = Sale::whereDate('created_at', Carbon::today())->sum('grand_total');

        $todayPurchaseAmount = Purchase::whereDate('created_at', Carbon::today())->sum('grand_total');


        // top selling products of this month with product and warehouse
        $topSellingProducts = ProductItem::select('product_id', DB::raw('sum(quantity) as total_quantity'))
            // ->whereHas('sale', function ($query) {
            //     $query->whereMonth('created_at', Carbon::now()->month);
            // })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->get();
        $topSellingProducts->load('product.product_warehouses', 'product.images');
        // dd($topSellingProducts);

        // Top Customers
        $topCustomerForMonth = Sale::select('customer_id', DB::raw('count(*) as total_sales'),  DB::raw('sum(grand_total) as total_amount'))
            ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('customer_id')
            ->orderBy('total_sales', 'desc')
            ->get();
        $topCustomerForMonth->load('customer');
        // Stock Alert
        $products = Product::with('product_warehouses')->get();
        $stockAlert = collect();

        foreach ($products as $product) {
            foreach ($product->product_warehouses as $product_warehouse) {
                if ($product_warehouse->quantity < $product->stock_alert) {
                    $stockAlert->push($product_warehouse);
                }
            }
        }
        // Load the relationships on each item in the collection
        $stockAlert->each(function ($item) {
            $item->load('product', 'product.images', 'warehouse');
        });

        // Recent Transaction
        $recentTransactions = Sale::with('customer', 'warehouse')
            // ->whereMonth('date', Carbon::now()->month)
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')->get();

        // this week sale and purchase
        // $thisWeekSale = Sale::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('grand_total');
        // $thisWeekPurchase = Purchase::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('grand_total');
        // Get the start and end dates of the current week

        // // Get the start date (7 days ago) and end date (today)
        // $startDate = Carbon::now()->subDays(7)->startOfDay();
        // $endDate = Carbon::now()->endOfDay();

        // // // Get the sales data in descending order by creation date
        // $thisWeekSale = Sale::whereBetween('date', [$startDate, $endDate])
        //     ->orderBy('created_at', 'desc')
        //     ->get();
        // for($i = 0; $i < 7; $i++){
        //     if($thisWeekSale->where('date', Carbon::now()->subDays($i)->toDateString())->count() == 0){
        //         $thisWeekSale->push(new Sale([
        //             'date' => Carbon::now()->subDays($i)->toDateString(),
        //             'grand_total' => 0
        //         ]));
        //     }
        // }

        // week sale&purchase
        // Get today's date
        // $endDate = Carbon::now()->startOfDay();

        // // Initialize an array to store the sales data for each day
        // $weekSales = [];
        // $weakPurchase = collect();

        // // Iterate over the previous 7 days
        // for ($i = 0; $i < 7; $i++) {
        //     // Calculate the date by subtracting $i days from the end date
        //     $date = $endDate->copy()->subDays($i);

        //     // Query the sales data for the current day
        //     $salesForDay = Sale::whereDate('date', $date)
        //         ->sum('grand_total');
        //     $purchaseForDay = Purchase::whereDate('date', $date)
        //         ->sum('grand_total');

        //     // Assign the total sales for the day to the array
        //     $weekSales[$date->toDateString()] = $salesForDay;
        //     // Assign the total purchase for the day to the array
        //     $weekPurchase[$date->toDateString()] = $purchaseForDay;
        // }

        // // Fill in missing days with 0 sales
        // $weekDays = collect($weekSales)->keys();
        // $allWeekDays = collect();
        // for ($i = 6; $i >= 0; $i--) {
        //     $allWeekDays->push(Carbon::now()->subDays($i)->toDateString());
        // }
        // $missingDays = $allWeekDays->diff($weekDays);

        // foreach ($missingDays as $missingDay) {
        //     $weekSales[$missingDay] = 0;
        // }

        // // Fill in missing days with 0 purchase
        // $weekDays = collect($weekPurchase)->keys();
        // $allWeekDaysPurchase = collect();
        // for ($i = 6; $i >= 0; $i--) {
        //     $allWeekDaysPurchase->push(Carbon::now()->subDays($i)->toDateString());
        // }
        // $missingDaysPurchase = $allWeekDaysPurchase->diff($weekDays);

        // foreach ($missingDaysPurchase as $missingDay) {
        //     $weekPurchase[$missingDay] = 0;
        // }

        $totalExpenses = Expense::sum('amount');
        $totalInvoiceDue = Sale::sum('amount_due');
        $totalProfit = $SalesAmount - $totalExpenses;

        $statistics = $this->getStatistics();

        // $recentSaleProducts
        // $recentSaleProducts = ProductItem::select('product_id', DB::raw('sum(quantity) as total_quantity'))
        //     ->whereHas('sale', function ($query) {
        //         $query->whereMonth('created_at', Carbon::now()->month);
        //     })
        //     ->groupBy('product_id')
        //     ->orderBy('total_quantity', 'desc')
        //     ->limit(5)
        //     ->get();
        // $recentSaleProducts->load('product.product_warehouses', 'product.images');

        // recent sale product with total recent sales amount and total quantity with date
        // $recentSaleProducts = ProductItem::select('product_id', DB::raw('sum(quantity) as total_quantity'), DB::raw('sum(sub_total) as total_amount'))
        // ->whereHas('sale', function ($query) {
        //         $query->whereMonth('created_at', Carbon::now()->month);
        //     })
        //     ->groupBy('product_id')
        //     ->orderBy('total_quantity', 'desc')
        //     ->limit(5)
        //     ->get();
        // $recentSaleProducts->load('product.product_warehouses', 'product.images','sale');

        // recent sale product
        $recentSaleProducts = ProductItem::latest()->take(5)->get();
        $recentSaleProducts->load('product.product_warehouses', 'product.images', 'sale');

        // total today orders
        $totalTodayOrdersCount = Sale::whereDate('created_at', Carbon::today())->count();

        // first low stock product
        $firstLowStockProduct = ProductWarehouse::where('quantity','<', 5)->first();




        return view('back.dashboard', compact(
            'SalesAmount',
            'PurchaseAmount',
            'todaySalesAmount',
            'todayPurchaseAmount',
            'saleReturn',
            'purchaseReturn',
            'topSellingProducts',
            'topCustomerForMonth',
            'stockAlert',
            'recentTransactions',
            // 'weekSales',
            // 'weekPurchase',
            'totalCategories',
            'totalExpenses',
            'totalInvoiceDue',
            'totalProfit',
            'statistics',
            'recentSaleProducts',
            'totalTodayOrdersCount',
            'firstLowStockProduct',
        ));
    }

    // public function getSalesData2Chart()
    // {
    //     $startOfWeek = Carbon::now()->startOfWeek(); // Monday 00:00:00
    //     $endOfWeek = Carbon::now()->endOfWeek(); // Sunday 23:59:59

    //     // Fetch sales data grouped by day and hour
    //     $sales = Sale::whereBetween('created_at', [$startOfWeek, $endOfWeek])
    //         ->select(
    //             DB::raw('DAYOFWEEK(created_at) as day_number'),
    //             DB::raw('HOUR(created_at) as hour'),
    //             DB::raw('SUM(grand_total) as total_sales')
    //         )
    //         ->groupBy('day_number', 'hour')
    //         ->get();
    //     // dd($sales);

    //     // Map day numbers to names (Sunday=1, Monday=2, ..., Saturday=7)
    //     $daysMap = [1 => 'Sun', 2 => 'Mon', 3 => 'Tue', 4 => 'Wed', 5 => 'Thur', 6 => 'Fri', 7 => 'Sat'];
    //     $hours = range(0, 23); // 24-hour format

    //     // Initialize dataset
    //     $heatmapData = [];
    //     foreach ($hours as $hour) {
    //         $dataPoints = [];
    //         foreach ($daysMap as $dayNum => $day) {
    //             $salesForDayHour = $sales->firstWhere(fn($sale) => $sale->day_number == $dayNum && $sale->hour == $hour);
    //             $dataPoints[] = [
    //                 'x' => $day,
    //                 'y' => $salesForDayHour ? (int) $salesForDayHour->total_sales : 0, // Default 0 if no sales
    //             ];
    //         }
    //         $heatmapData[] = [
    //             'name' => "{$hour}:00",
    //             'data' => $dataPoints,
    //         ];
    //     }

    //     return response()->json([
    //         'heatmapData' => $heatmapData
    //     ]);
    // }

    public function getWeeklySalesData()
    {
        // Get start and end of current week
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday

        // Query sales data grouped by day and hour
        $salesData = Sale::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DAYNAME(created_at) as day,
                    HOUR(created_at) as hour,
                    SUM(grand_total) as total_sales')
            ->groupBy('day', 'hour')
            ->orderByRaw('DAYOFWEEK(created_at)')
            ->orderBy('hour')
            ->get();
        dd($salesData);

        // Format data for the chart
        $formattedData = [];
        $hours = range(0, 23); // All 24 hours

        foreach ($hours as $hour) {
            $hourData = [
                'name' => sprintf("%02d:00", $hour),
                'data' => []
            ];

            // Initialize with 0 for all days
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $day) {
                $hourData['data'][] = [
                    'x' => substr($day, 0, 3), // "Mon", "Tue", etc.
                    'y' => 0 // Default value
                ];
            }

            // Fill with actual data
            foreach ($salesData as $sale) {
                if ($sale->hour == $hour) {
                    $dayIndex = array_search($sale->day, $days);
                    if ($dayIndex !== false) {
                        $hourData['data'][$dayIndex]['y'] = (float)$sale->total_sales;
                    }
                }
            }

            $formattedData[] = $hourData;
        }

        return response()->json($formattedData);
    }
    public function getWeeklySalesHeatmapData()
    {
        // Get start and end of current week
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday

        // Query sales data grouped by day and time slot
        $salesData = Sale::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DAYNAME(created_at) as day,
                    HOUR(created_at) as hour,
                    SUM(grand_total) as total_sales')
            ->groupBy('day', 'hour')
            ->orderByRaw('DAYOFWEEK(created_at)')
            ->orderBy('hour')
            ->get();

        // Define time slots (2-hour intervals)
        $timeSlots = [
            ['name' => '2 AM', 'range' => [2, 4]],
            ['name' => '4 AM', 'range' => [4, 6]],
            ['name' => '6 AM', 'range' => [6, 8]],
            ['name' => '8 AM', 'range' => [8, 10]],
            ['name' => '10 AM', 'range' => [10, 12]],
            ['name' => '12 PM', 'range' => [12, 14]],
            ['name' => '2 PM', 'range' => [14, 16]],
            ['name' => '4 PM', 'range' => [16, 18]],
            ['name' => '6 PM', 'range' => [18, 20]],
            ['name' => '8 PM', 'range' => [20, 22]],
            ['name' => '10 PM', 'range' => [22, 24]],
            ['name' => '12 AM', 'range' => [0, 2]],
        ];

        // Format data for the chart
        $formattedData = [];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($timeSlots as $slot) {
            $slotData = [
                'name' => $slot['name'],
                'data' => []
            ];

            foreach ($daysOfWeek as $day) {
                // Filter sales for this day and time slot
                $sales = $salesData->filter(function ($item) use ($day, $slot) {
                    return $item->day === $day &&
                        $item->hour >= $slot['range'][0] &&
                        $item->hour < $slot['range'][1];
                });

                $total = $sales->sum('total_sales');

                $slotData['data'][] = [
                    'x' => substr($day, 0, 3), // "Mon", "Tue", etc.
                    'y' => (float)$total
                ];
            }

            $formattedData[] = $slotData;
        }

        return response()->json($formattedData);
    }

    // public function getTopCategories()
    // {
    //     $topCategories = ProductItem::select(
    //             'product.category',
    //             DB::raw('SUM(product_items.quantity) as total_quantity_sold'),
    //             DB::raw('COUNT(product_items.id) as total_orders')
    //         )
    //         ->join('product', 'product_items.product_id', '=', 'product.id')
    //         ->groupBy('product.category')
    //         ->orderByDesc('total_quantity_sold')
    //         ->limit(3) // Get top 3 categories
    //         ->get();

    //     return response()->json([
    //         'labels' => $topCategories->pluck('category'),
    //         'data' => $topCategories->pluck('total_quantity_sold'),
    //         'colors' => ['#092C4C', '#E04F16', '#FE9F43'] // Your existing colors
    //     ]);
    // }

    public function getTopCategories()
    {
        $topCategories = ProductItem::select(
            'categories.name as category_name',
            DB::raw('SUM(product_items.quantity) as total_quantity_sold'),
            DB::raw('COUNT(DISTINCT product_items.sale_id) as total_orders')
        )
            ->join('products', 'product_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->orderByDesc('total_quantity_sold')
            ->limit(3)
            ->get();

        return response()->json([
            'labels' => $topCategories->pluck('category_name'),
            'data' => $topCategories->pluck('total_quantity_sold'),
            'colors' => ['#092C4C', '#E04F16', '#FE9F43']
        ]);
    }

    public function getCustomerSalesVsReturns()
    {
        // Get total sales amount
        $totalSales = Sale::where('status', 'completed')->sum('grand_total');

        // Get total returns amount
        $totalReturns = SaleReturn::where('status', 'received')->sum('grand_total');

        // Calculate percentages
        $total = $totalSales + $totalReturns;

        if ($total > 0) {
            $salesPercentage = round(($totalSales / $total) * 100);
            $returnsPercentage = round(($totalReturns / $total) * 100);
        } else {
            $salesPercentage = 50;
            $returnsPercentage = 50;
        }

        return response()->json([
            'series' => [$salesPercentage, $returnsPercentage],
            'labels' => ['Sales', 'Return'],
            'totalSales' => $totalSales,
            'totalReturns' => $totalReturns
        ]);
    }

    // public function fetchSalesPurchases(Request $request)
    // {
    //     $period = $request->input('period');
    //     $endDate = Carbon::now()->startOfDay();

    //     if ($period === 'week') {
    //         $interval = 7;
    //     } elseif ($period === 'month') {
    //         $interval = 30;
    //     } else {
    //         return response()->json(['error' => 'Invalid period'], 400);
    //     }

    //     $sales = [];
    //     $purchases = [];

    //     if (auth()->user()->hasRole('Cashier') || auth()->user()->hasRole('Manager')) {
    //         $warehouseId = auth()->user()->warehouse_id;
    //         for ($i = 0; $i < $interval; $i++) {
    //             $date = $endDate->copy()->subDays($i);

    //             $sales[$date->toDateString()] = Sale::where('warehouse_id',session($warehouseId))->whereDate('date', $date)->sum('grand_total');
    //             $purchases[$date->toDateString()] = Purchase::whereDate('date', $date)->sum('grand_total');
    //         }
    //     }


    //     else if (session()->has('selected_warehouse_id'))
    //     {
    //         for ($i = 0; $i < $interval; $i++) {
    //             $date = $endDate->copy()->subDays($i);

    //             $sales[$date->toDateString()] = Sale::where('warehouse_id',session('selected_warehouse_id'))->whereDate('date', $date)->sum('grand_total');
    //             $purchases[$date->toDateString()] = Purchase::whereDate('date', $date)->sum('grand_total');
    //         }
    //     }
    //     else
    //     {
    //         for ($i = 0; $i < $interval; $i++) {
    //             $date = $endDate->copy()->subDays($i);

    //             $sales[$date->toDateString()] = Sale::whereDate('date', $date)->sum('grand_total');
    //             $purchases[$date->toDateString()] = Purchase::whereDate('date', $date)->sum('grand_total');
    //         }

    //     }


    //     $allDays = collect();
    //     for ($i = $interval - 1; $i >= 0; $i--) {
    //         $allDays->push(Carbon::now()->subDays($i)->toDateString());
    //     }

    //     $missingSalesDays = $allDays->diff(collect($sales)->keys());
    //     foreach ($missingSalesDays as $missingDay) {
    //         $sales[$missingDay] = 0;
    //     }

    //     $missingPurchaseDays = $allDays->diff(collect($purchases)->keys());
    //     foreach ($missingPurchaseDays as $missingDay) {
    //         $purchases[$missingDay] = 0;
    //     }

    //     ksort($sales);
    //     ksort($purchases);
    //     // dd($sales);

    //     return response()->json(['weekSales' => $sales, 'weekPurchase' => $purchases]);
    // }


    public function fetchSalesPurchases(Request $request)
    {
        $period = $request->input('period');
        $endDate = Carbon::now()->startOfDay();

        if ($period === 'week') {
            $interval = 7;
        } elseif ($period === 'month') {
            $interval = 30;
        } else {
            return response()->json(['error' => 'Invalid period'], 400);
        }

        $sales = [];
        $purchases = [];

        if (auth()->user()->hasRole('Cashier') || auth()->user()->hasRole('Manager')) {
            $warehouseId = auth()->user()->warehouse_id;
            for ($i = 0; $i < $interval; $i++) {
                $date = $endDate->copy()->subDays($i);

                // Format date as mm/dd/yy
                $formattedDate = $date->format('m/d/y');
                $sales[$formattedDate] = Sale::where('warehouse_id', session($warehouseId))
                    ->whereDate('date', $date)
                    ->sum('grand_total');
                $purchases[$formattedDate] = Purchase::whereDate('date', $date)
                    ->sum('grand_total');
            }
        } else if (session()->has('selected_warehouse_id')) {
            for ($i = 0; $i < $interval; $i++) {
                $date = $endDate->copy()->subDays($i);

                // Format date as mm/dd/yy
                $formattedDate = $date->format('m/d/y');
                $sales[$formattedDate] = Sale::where('warehouse_id', session('selected_warehouse_id'))
                    ->whereDate('date', $date)
                    ->sum('grand_total');
                $purchases[$formattedDate] = Purchase::whereDate('date', $date)
                    ->sum('grand_total');
            }
        } else {
            for ($i = 0; $i < $interval; $i++) {
                $date = $endDate->copy()->subDays($i);

                // Format date as mm/dd/yy
                $formattedDate = $date->format('m/d/y');
                $sales[$formattedDate] = Sale::whereDate('date', $date)
                    ->sum('grand_total');
                $purchases[$formattedDate] = Purchase::whereDate('date', $date)
                    ->sum('grand_total');
            }
        }

        $allDays = collect();
        for ($i = $interval - 1; $i >= 0; $i--) {
            $allDays->push(Carbon::now()->subDays($i)->format('m/d/y')); // Format date as mm/dd/yy
        }

        $missingSalesDays = $allDays->diff(collect($sales)->keys());
        foreach ($missingSalesDays as $missingDay) {
            $sales[$missingDay] = 0;
        }

        $missingPurchaseDays = $allDays->diff(collect($purchases)->keys());
        foreach ($missingPurchaseDays as $missingDay) {
            $purchases[$missingDay] = 0;
        }

        ksort($sales);
        ksort($purchases);

        return response()->json(['weekSales' => $sales, 'weekPurchase' => $purchases]);
    }



    public function getStatistics()
    {
        // Get current month and last month dates
        $startOfCurrentMonth = Carbon::now()->startOfMonth();
        $endOfCurrentMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // Fetch data for the current and last month
        $currentSales = Sale::whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->sum('grand_total');
        $lastSales = Sale::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('grand_total');

        $currentExpenses = Expense::whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->sum('amount');
        $lastExpenses = Expense::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('amount');

        $currentInvoiceDue = Sale::whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])->sum('amount_due');
        $lastInvoiceDue = Sale::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('amount_due');

        // Calculate profit
        $currentProfit = $currentSales - $currentExpenses;
        $lastProfit = $lastSales - $lastExpenses;

        // Function to calculate percentage change
        function percentageChange($current, $last)
        {
            if ($last == 0) return $current == 0 ? 0 : 100; // Avoid division by zero
            return round((($current - $last) / $last) * 100, 2);
        }

        $salesChange = percentageChange($currentSales, $lastSales);
        $expensesChange = percentageChange($currentExpenses, $lastExpenses);
        $invoiceDueChange = percentageChange($currentInvoiceDue, $lastInvoiceDue);
        $profitChange = percentageChange($currentProfit, $lastProfit);

        // Pass data to the view
        // return view('dashboard', compact(
        //     'currentSales',
        //     'salesChange',
        //     'currentExpenses',
        //     'expensesChange',
        //     'currentInvoiceDue',
        //     'invoiceDueChange',
        //     'currentProfit',
        //     'profitChange'
        // ));

        return [
            'currentSales' => $currentSales,
            'salesChange' => $salesChange,
            'currentExpenses' => $currentExpenses,
            'expensesChange' => $expensesChange,
            'currentInvoiceDue' => $currentInvoiceDue,
            'invoiceDueChange' => $invoiceDueChange,
            'currentProfit' => $currentProfit,
            'profitChange' => $profitChange
        ];
    }



    // filters
    public function filterTopSellingProducts(Request $request)
    {
        $filter = $request->filter;
        $query = ProductItem::select('product_id', DB::raw('sum(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc');

        if ($filter == 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter == 'weekly') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filter == 'monthly') {
            $query->whereMonth('created_at', Carbon::now()->month);
        }

        $topSellingProducts = $query->get();
        $topSellingProducts->load('product.product_warehouses', 'product.images');

        // return view('back.partials.filters.top_selling_products', compact('topSellingProducts'))->render();

        // Convert data to array format
        $data = $topSellingProducts->map(function ($product) {
            return [
                'image' => '<img src="' . (isset($product->product->images[0]) ? asset('/storage' . $product->product->images[0]->img_path) : 'https://placehold.co/600x400') . '" width="50" alt="" class="img-fluid rounded-3" />',
                'product_name' => (strlen($product->product->name ?? '') > 20 ? substr($product->product->name, 0, 20) . '...' : $product->product->name ?? ''),
                'wherehouse' => optional($product->product->product_warehouses[0] ?? null)?->warehouse?->users?->name ?? 'N/A' ,
            ];
        });
        return response()->json(['data' => $data]);
    }


    public function filterRecentSales(Request $request)
    {
        $query = ProductItem::latest();

        if ($request->filter == 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($request->filter == 'weekly') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($request->filter == 'monthly') {
            $query->whereMonth('created_at', Carbon::now()->month);
        }

        $recentSaleProducts = $query->get();
        $recentSaleProducts->load('product.product_warehouses', 'product.images', 'sale');

        // $html = view('back.partials.filters.recent_sales', compact('recentSaleProducts'))->render();

        // return response()->json(['html' => $html]);

        // Convert data to array format
        $data = $recentSaleProducts->map(function ($product) {
            return [
                'image' => '<img src="' . (isset($product->product->images[0]) ? asset('/storage' . $product->product->images[0]->img_path) : 'https://placehold.co/600x400') . '" width="50" alt="" class="img-fluid rounded-3" />',
                'product_name' => (strlen($product->product->name ?? '') > 20 ? substr($product->product->name, 0, 20) . '...' : $product->product->name ?? ''),
                'sub_total' => $product->sub_total ?? '0',
                'quantity' => $product->quantity ?? '0',
                'created_at' => $product->created_at->format('d/m/y'),
                'status' => ($product->sale->status == 'completed')
                    ? '<span class="badges bg-success text-center px-1 d-flex gap-1 align-items-center justify-content-center"><span class="mb-1"><i class="bi bi-circle-fill text-white" style="font-size: 6px"></i></span><span>Completed</span></span>'
                    : '<span class="badges bg-warning text-center px-1 d-flex gap-1 align-items-center justify-content-center"><span class="mb-1"><i class="bi bi-circle-fill text-white" style="font-size: 6px"></i></span><span>Pending</span></span>',
            ];
        });

        return response()->json(['data' => $data]);
    }


    public function filterRecentTransactions(Request $request)
    {
        $query = Sale::with('customer.user', 'warehouse.users')
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc');

        if ($request->filter == 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($request->filter == 'weekly') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($request->filter == 'monthly') {
            $query->whereMonth('created_at', Carbon::now()->month);
        }

        $recentTransactions = $query->get();

        // $html = view('back.partials.filters.recent_transactions', compact('recentTransactions'))->render();

        // return response()->json(['html' => $html]);

        $data = $recentTransactions->map(function ($sale) {
            return [
                'reference' => '#' . ($sale->reference ?? 'N/A'),
                'customer_name' => optional($sale->customer->user)->name ?? 'N/A',
                'warehouse_name' => optional($sale->warehouse->users)->name ?? 'N/A',
                'status' => $this->getStatusBadge($sale->status),
                'grand_total' => '$' . number_format($sale->grand_total ?? 0, 2),
                'amount_received' => '$' . number_format($sale->amount_recieved ?? 0, 2),
                'amount_due' => '$' . number_format($sale->amount_due ?? 0, 2),
                'payment_status' => $this->getPaymentStatusBadge($sale->payment_status),
            ];
        });

        return response()->json(['data' => $data]);
    }

    private function getStatusBadge($status)
    {
        if (strtolower($status) === 'completed') {
            return '<span class="badges bg-green text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                    <span class="mb-1"><i class="bi bi-circle-fill text-white" style="font-size: 6px"></i></span>
                    <span> Completed </span>
                </span>';
        } elseif (strtolower($status) === 'pending') {
            return '<span class="badges bg-red text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                    <span class="mb-1"><i class="bi bi-circle-fill text-white" style="font-size: 6px"></i></span>
                    <span> Pending </span>
                </span>';
        }

        return '<span class="badges bg-secondary text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                <span class="mb-1"><i class="bi bi-circle-fill text-white" style="font-size: 6px"></i></span>
                <span>' . ucfirst($status) . '</span>
            </span>';
    }

    /**
     * Generate payment status badge HTML
     */
    private function getPaymentStatusBadge($payment_status)
    {
        $statusClass = match (strtolower($payment_status)) {
            'paid' => 'bg-green',
            'partial' => 'bg-yellow',
            default => 'bg-red',
        };

        return '<span class="badges ' . $statusClass . ' text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                <span class="mb-1"><i class="bi bi-circle-fill text-white" style="font-size: 6px"></i></span>
                <span>' . ucwords($payment_status ?? 'N/A') . '</span>
            </span>';
    }
}
