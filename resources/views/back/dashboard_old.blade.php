@extends('back.layout.app')
@section('title', 'Dashboard')
@section('style')
    <style>
        .ui-datepicker .ui-datepicker-header {
            background: white;
            border: none;
        }
    </style>
    <style>
        .dataTables_paginate {
            display: none;
        }

        .dataTables_length {
            display: none;
        }

        .dataTables_info {
            display: none;
        }
    </style>
@endsection
@section('content')
    {{-- <div class="container-fluid position-relative bg-white d-flex p-0"> --}}
        {{-- <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End --> --}}

        <!-- Content Start -->
        <div class="content">

            <!-- Sale & Revenue Start -->
            <div class="container-fluid px-4">
                @include('back.layout.errors')

                <div class="row">
                    <div class="col-md-6 mt-4">
                        <div class="card border-0 h-100 card-shadow p-3">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <h4 class="mb-0 mt-2 heading text-start card-title">
                                        Good Morning, {{ auth()->user()->name }}!
                                    </h4>
                                    <p>Here’s what happening with your store today!</p>
                                    <div class="mt-4">
                                        <h6 class="mb-0 sales-amount text-dark">${{ $todayPurchaseAmount ?? 0 }}</h6>
                                        <p>Today's Total Purchases</p>
                                    </div>
                                    <div class="mt-4">
                                        <h6 class="mb-0 sales-amount text-dark">${{ $todaySalesAmount ?? 0 }}</h6>
                                        <p>Today's Total Sales</p>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <img src="{{ asset('back/assets/dasheets/img/overview.png') }}" class="img-fluid"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-4">
                        <div class="row">
                            <div class="col-md-6 mt-4">
                                <a href="{{ route('sales.index') }}" class="text-decoration-none">
                                    <div class="shadow border rounded d-flex align-items-center p-3">
                                        <img src="{{ asset('back/assets/dasheets/img/content-sale.svg') }}"
                                            class="img-fluid text-center" alt="" />
                                        <div class="ms-3">
                                            <p class="mb-1 fs-6 text-muted subheading">Sale</p>
                                            <h6 class="mb-0 sales-amount">${{ $SalesAmount ?? 0 }}</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 mt-4">
                                <a href="{{ route('purchases.index') }}" class="text-decoration-none">
                                    <div class="shadow border rounded d-flex align-items-center p-3">
                                        <img src="{{ asset('back/assets/dasheets/img/content-bag.svg') }}"
                                            class="img-fluid text-center" alt="" />
                                        <div class="ms-3">
                                            <p class="mb-1 text-muted subheading">Purchase</p>
                                            <h6 class="mb-0 sales-amount">${{ $PurchaseAmount ?? 0 }}</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-6 mt-4">
                                <a href="{{ route('sale_return.index') }}" class="text-decoration-none">
                                    <div class="shadow border rounded d-flex align-items-center p-3">
                                        <img src="{{ asset('back/assets/dasheets/img/content-right-arrow.svg') }}"
                                            class="img-fluid text-center" alt="" />
                                        <div class="ms-3">
                                            <p class="mb-1 fs-6 text-muted subheading">Sales Return</p>
                                            <h6 class="mb-0 sales-amount">${{ $saleReturn ?? 0 }}</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 mt-4">
                                <a href="{{ route('purchase_return.index') }}" class="text-decoration-none">
                                    <div class="shadow border rounded d-flex align-items-center p-3">
                                        <img src="{{ asset('back/assets/dasheets/img/content-left-arrow.svg') }}"
                                            class="img-fluid text-center" alt="" />
                                        <div class="ms-3">
                                            <p class="mb-1 fs-6 text-muted subheading">
                                                Purchase Return
                                            </p>
                                            <h6 class="mb-0 sales-amount">${{ $purchaseReturn ?? 0 }}</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Sale & Revenue End -->

            <!-- Sales Chart Start -->
            <div class="container-fluid px-4">
                <div class="row mt-2">
                    {{-- <div class="col-md-7 col-12 mt-4">
                        <div class="shadow text-center rounded p-4 card border-0 h-100">
                            <div class="row card-header border-0 bg-white">
                                <div class="col-md-6 col-12">
                                    <h4 class="mb-0 mt-2 heading text-start card-title">
                                        Sale and Purchase
                                    </h4>
                                </div>
                                <div class="col-md-6 col-12 d-flex flex-wrap text-center">
                                    <div class="calendar-icon p-2 fs-6 me-2 rounded-3">
                                        <span class="me-2 bg-primary text-white badge">1W</span>
                                        <span class="me-2">1M</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="height: 320px;">
                                <canvas id="barChart" class="dashboard-chart"></canvas>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-7 col-12 mt-4">
                        <div class="shadow text-center rounded p-4 card border-0 h-100">
                            <div class="row card-header border-0 bg-white">
                                <div class="col-md-6 col-12">
                                    <h4 class="mb-0 mt-2 heading text-start card-title">
                                        Sale and Purchase
                                    </h4>
                                </div>
                                <div class="col-md-6 col-12 d-flex flex-wrap justify-content-end">
                                    <div class="calendar-icon p-2 fs-6 me-2 rounded-3">
                                        <span class="me-2 btn btn-primary text-white btn-sm italic" id="week-btn"
                                            style="padding: 1px;">This Week</span>
                                        <span class="me-2 btn btn-secondary btn-sm " id="month-btn"
                                            style="padding: 1px;">This Month</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="height: 320px;">
                                <canvas id="barChart" class="dashboard-chart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 mt-4">
                        <div class="shadow rounded p-4 px-3 w-100 card border-0 h-100">
                            <div class="card-header bg-white p-0 m-0 d-flex justify-content-between">
                                <h5 class="text-start mt-2 pb-2 heading ps-3">
                                    This Month Top Selling Products
                                </h5>

                            </div>
                            <div class="card-body h-100 px-0">
                                <div class="table-responsive text-center">
                                    <table class="table" id="example1">
                                        <thead>
                                            <tr>
                                                <th class="text-secondary">Code</th>
                                                <th class="text-secondary">Product</th>
                                                <th class="text-secondary">Warehouse</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topSellingProducts as $product)
                                                <tr>
                                                    <td class="align-middle text-start" style="width:100px;">
                                                        {{ $product->product->sku ?? '' }}
                                                    </td>
                                                    <td class="align-middle text-start">{{ $product->product->name ?? '' }}
                                                    </td>
                                                    <td class="align-middle text-start">
                                                        {{ $product->product->product_warehouses[0]?->warehouse->users->name ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 text-end">


                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <label for="rowsPerPage1" class="col-form-label">Rows per page:</label>
                                            </div>
                                            <div class="col-auto">
                                                <select id="rowsPerPage1" class="form-select border-0">
                                                    <option value="3" selected="">3</option>
                                                    <option value="5">5</option>
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <div class="row align-items-center text-end justify-content-end">
                                            <div class="col-auto text-end">
                                                <p class="subheading col-form-label" id="dataTableInfo1">1-4 of 4 entries
                                                </p>
                                            </div>
                                            <div class="col-auto">
                                                <div class="new-pagination " id="example1_paginate">
                                                    <a class="rounded-start paginate_button" style="cursor: pointer"> ❮
                                                    </a>
                                                    <a class="rounded-end paginate_button page-item next"
                                                        style="cursor: pointer"> ❯ </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sales Chart End -->

            <!-- Recent Sales Start -->
            <div class="container-fluid px-4">
                <div class="row mt-2">
                    <div class="col-md-7 text-center mt-4">
                        <div class="shadow card p-3 rounded-3 border-0 h-100">
                            <div class="card-header bg-white p-0 m-0 mb-2">
                                <h5 class="text-start ps-3 mt-2 pb-2 heading">Stock Alert</h5>
                            </div>
                            <div class="card-body p-0 m-0">
                                <div class="table-responsive">
                                    <table class="table" id="example2">
                                        <thead>
                                            <tr>
                                                <th class="text-secondary">Code</th>
                                                <th class="text-secondary">Product</th>
                                                <th class="text-secondary">Warehouse</th>
                                                <th class="text-secondary">Quantity</th>
                                                <th class="text-secondary">Alert Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stockAlert as $stock)
                                                <tr>
                                                    <td class="align-middle text-start">{{ $stock->product->sku ?? '' }}
                                                    </td>
                                                    <td class="align-middle text-start">{{ $stock->product->name ?? '' }}
                                                    </td>
                                                    <td class="align-middle text-start">
                                                        {{ $stock->warehouse->users->name ?? '' }}</td>
                                                    <td class="align-middle text-start">
                                                        {{ number_format($stock->quantity) ?? 0 }}</td>
                                                    <td class="align-middle text-start"><span
                                                            class="badges bg-lightred text-center">{{ $stock->product->stock_alert ?? 0 }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 text-end">



                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <label for="rowsPerPage2" class="col-form-label">Rows per page:</label>
                                            </div>
                                            <div class="col-auto">
                                                <select id="rowsPerPage2" class="form-select border-0">
                                                    <option value="3" selected="">3</option>
                                                    <option value="5">5</option>
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <div class="row align-items-center text-end justify-content-end">
                                            <div class="col-auto text-end">
                                                <p class="subheading col-form-label" id="dataTableInfo2">1-4 of 4 entries
                                                </p>
                                            </div>
                                            <div class="col-auto">
                                                <div class="new-pagination " id="example2_paginate">
                                                    <a class="rounded-start paginate_button" style="cursor: pointer"> ❮
                                                    </a>
                                                    <a class="rounded-end paginate_button page-item next"
                                                        style="cursor: pointer"> ❯ </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 text-center mt-4">
                        <div class="shadow p-3 rounded-3 card border-0 h-100">
                            <div class="card-header bg-white p-0 m-0">
                                <h5 class="text-start ps-3 mt-2 pb-2 heading">
                                    Top Customer For The Month
                                </h5>
                            </div>
                            <div class="card-body p-0 m-0">
                                <div class="table-responsive text-center">
                                    <table class="table" id="example3">
                                        <thead>
                                            <tr>
                                                <th class="text-secondary">Customer</th>
                                                <th class="text-secondary">Total Sale</th>
                                                <th class="text-secondary">Grand Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topCustomerForMonth as $customer)
                                                <tr>
                                                    <td class="align-middle text-start">
                                                        {{ $customer->customer->user->name ?? '' }}</td>
                                                    <td class="align-middle text-start">{{ $customer->total_sales ?? 0 }}
                                                    </td>
                                                    <td class="align-middle text-start">{{ $customer->total_amount ?? 0 }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 text-end">


                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <label for="rowsPerPage3" class="col-form-label">Rows per page:</label>
                                            </div>
                                            <div class="col-auto">
                                                <select id="rowsPerPage3" class="form-select border-0">
                                                    <option value="3" selected="">3</option>
                                                    <option value="5">5</option>
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <div class="row align-items-center text-end justify-content-end">
                                            <div class="col-auto text-end">
                                                <p class="subheading col-form-label" id="dataTableInfo3">1-4 of 4 entries
                                                </p>
                                            </div>
                                            <div class="col-auto">
                                                <div class="new-pagination " id="example3_paginate">
                                                    <a class="rounded-start paginate_button" style="cursor: pointer"> ❮
                                                    </a>
                                                    <a class="rounded-end paginate_button page-item next"
                                                        style="cursor: pointer"> ❯ </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid pt-4 px-4 mt-3 mb-5">
                <div class="card border-0 card-shadow p-3 rounded-3">
                    <div class="card-header bg-white p-0 m-0">
                        <h5 class="text-start ps-3 mt-2 pb-1 heading">
                            Recent Transactions
                        </h5>
                    </div>
                    <div class="card-body p-0 m-0 mt-2">
                        <div class="table-responsive text-center">
                            <table class="table text-start" id="example4">
                                <thead>
                                    <tr>
                                        <th class="text-secondary">Reference</th>
                                        <th class="text-secondary">Customer</th>
                                        <th class="text-secondary">Warehouse</th>
                                        <th class="text-secondary">Status</th>
                                        <th class="text-secondary">Grand Total</th>
                                        <th class="text-secondary">Paid</th>
                                        <th class="text-secondary">Due</th>
                                        <th class="text-secondary">Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentTransactions as $sale)
                                        <tr>
                                            <td>{{ $sale->reference ?? 'N/A' }}</td>
                                            <td>{{ $sale->customer->user->name ?? 'N/A' }}</td>
                                            <td>{{ $sale->warehouse->users->name ?? 'N/A' }}</td>
                                            <td class="align-middle">
                                                @if ($sale->status == 'completed' || $sale->status == 'Completed')
                                                    <span class="badges bg-lightgreen text-center">{{ ucwords($sale->status ?? '') }}</span>
                                                @elseif ($sale->status == 'pending' || $sale->status == 'Pending')
                                                    <span class="badges bg-lightred text-center">{{ ucwords($sale->status ?? '') }}</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($sale->grand_total ?? '0.00',2) }}</td>
                                            <td>${{ number_format($sale->amount_recieved ?? '0.00',2) }}</td>
                                            <td>${{ number_format($sale->amount_due ?? '0.00',2) }}</td>
                                            <td class="align-middle">
                                                @if ($sale->payment_status == 'paid')
                                                    <span class="badges bg-lightgreen text-center">{{ ucwords($sale->payment_status ?? '') }}</span>
                                                @elseif ($sale->payment_status == 'partial')
                                                    <span class="badges bg-lightyellow text-center">{{ ucwords($sale->payment_status ?? '') }}</span>
                                                @else
                                                    <span class="badges bg-lightred text-center">{{ ucwords($sale->payment_status ?? '') }}</span>
                                                @endif
                                            </td>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 h-100 text-end">


                        <div class="row">
                            <div class="col-6">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <label for="rowsPerPage4" class="col-form-label">Rows per page:</label>
                                    </div>
                                    <div class="col-auto">
                                        <select id="rowsPerPage4" class="form-select border-0">
                                            <option value="3" selected="">3</option>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <div class="row align-items-center text-end justify-content-end">
                                    <div class="col-auto text-end">
                                        <p class="subheading col-form-label" id="dataTableInfo4">1-4 of 4 entries</p>
                                    </div>
                                    <div class="col-auto">
                                        <div class="new-pagination " id="example4_paginate">
                                            <a class="rounded-start paginate_button" style="cursor: pointer"> ❮ </a>
                                            <a class="rounded-end paginate_button page-item next" style="cursor: pointer">
                                                ❯ </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Recent Sales End -->



        <!-- Calendar Modal -->
        <div class="modal fade" id="myModal" aria-labelledby="exampleModalToggleLabel" tabindex="-1"
            style="display: none" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content calendar-modal">
                    <div class="modal-header border-0 text-white">
                        <button type="button" class="btn-close text-white calendar-close-btn" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="datepicker" class="hasDatepicker"></div>
                    </div>
                </div>
            </div>
        </div>

    {{-- </div> --}}



@endsection
@section('scripts')
    <!-- Template Javascript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js">
    </script>
    {{-- <script>
        // Chart Data
        var ctx = document.getElementById("barChart").getContext("2d");
        var currentWeek = [];
        var currentDate = new Date();
        for (var i = 0; i < 7; i++) {
            var day = currentDate.getDate();
            var month = currentDate.toLocaleString('default', { month: 'short' });
            var label = day + ' ' + month;
            currentWeek.push(label);
            currentDate.setDate(currentDate.getDate() - 1);
        }

        var weakSales = @json($weekSales);
        var weekPurchase = @json($weekPurchase);
        console.log(weakSales)
        var myChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: currentWeek,
                datasets: [{
                        label: "Sales",
                        data: Object.values(weakSales),
                        backgroundColor: "rgba(98, 95, 237, 1)",
                        // borderColor: "rgba(255, 99, 132, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: "Purchase",
                        data: Object.values(weekPurchase),
                        backgroundColor: "rgba(155, 153, 243, 1)",
                        // borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
                responsive: true, // Make the chart responsive
                maintainAspectRatio: false, // Disable aspect ratio
            },
        });
        // Modal Calculator
        $(function() {
            $("#datepicker").datepicker({
                firstDay: 1,
            });
        });
    </script> --}}


    <script>
        // Initialize chart
        var ctx = document.getElementById("barChart").getContext("2d");
        var myChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: [], // Initialize empty labels
                datasets: [{
                        label: "Sales",
                        data: [], // Initialize empty data
                        backgroundColor: "rgba(98, 95, 237, 1)",
                        borderWidth: 1,
                    },
                    {
                        label: "Purchase",
                        data: [], // Initialize empty data
                        backgroundColor: "rgba(155, 153, 243, 1)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
                responsive: true,
                maintainAspectRatio: false,
            },
        });

        // Function to update chart data
        function updateChartData(data) {
            myChart.data.labels = Object.keys(data.weekSales);
            myChart.data.datasets[0].data = Object.values(data.weekSales);
            myChart.data.datasets[1].data = Object.values(data.weekPurchase);
            myChart.update();
        }

        // Fetch data for the given period (week or month)
        function fetchData(period) {
            $.ajax({
                url: '/fetch-sales-purchases',
                type: 'GET',
                data: {
                    period: period
                },
                success: function(response) {
                    updateChartData(response);
                },
                error: function() {
                    alert('Error fetching data');
                }
            });
        }

        // Event listeners for the buttons
        $('#week-btn').click(function() {
            $(this).removeClass('btn-secondary').addClass('btn-primary text-white');
            $('#month-btn').removeClass('btn-primary text-white').addClass('btn-secondary');
            fetchData('week');
        });

        $('#month-btn').click(function() {
            $(this).removeClass('btn-secondary').addClass('btn-primary text-white');
            $('#week-btn').removeClass('btn-primary text-white').addClass('btn-secondary');
            fetchData('month');
        });

        // Initial fetch for week data
        $(document).ready(function() {
            fetchData('week');
        });
    </script>

    <script>
        $(document).ready(function() {
            for (let i = 1; i <= 4; i++) {
                let table = $(`#example${i}`).DataTable({
                    dom: 'Bfrtip',
                    order: [],
                    pageLength: 5,
                    buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: ':visible' // Include all visible columns
                        }
                    }, ]
                });


                // Custom pagination events for each table
                $(`#example${i}_paginate .paginate_button`).on('click', function() {
                    if ($(this).hasClass('rounded-start')) {
                        table.page('previous').draw('page');
                    } else if ($(this).hasClass('rounded-end')) {
                        table.page('next').draw('page');
                    }
                });

                // Handle rows per page change
                $(`#rowsPerPage${i}`).on('change', function() {
                    var rowsPerPage = $(this).val();
                    table.page.len(rowsPerPage).draw();
                });

                // Update rows per page select on table draw
                table.on('draw', function() {

                    var pageInfo = table.page.info();
                    var currentPage = pageInfo.page + 1; // Adding 1 to match human-readable page numbering
                    var totalPages = pageInfo.pages;
                    var totalRecords = pageInfo.recordsTotal;

                    // Calculate start and end records for the current page
                    var startRecord = pageInfo.start + 1;
                    var endRecord = startRecord + pageInfo.length - 1;
                    if (endRecord > totalRecords) {
                        endRecord = totalRecords;
                    }

                    $(`#rowsPerPage${i}`).val(table.page.len());
                    $(`#dataTableInfo${i}`).text('' + startRecord + '-' + endRecord + ' of ' +
                        totalRecords + ' entries');
                });

                table.draw();

            }

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
@endsection
