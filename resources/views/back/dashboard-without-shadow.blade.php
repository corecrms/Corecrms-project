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

        .badges {
            width: fit-content
        }
    </style>

@endsection
@section('content')


    <!-- Content Start -->
    <div class="content">

        <!-- Sale & Revenue Start -->
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="row">
                <div class="col-lg-3 col-md-6 mt-4">
                    <a href="{{ route('sales.index') }}" class="text-decoration-none">
                        <div class="  border rounded orange-bg d-flex align-items-center p-3 rounded-4">
                            <img src="{{ asset('back/assets/dasheets/img/content-sale.svg') }}"
                                class="img-fluid text-center bg-white p-2 rounded-4" alt="" />
                            <div class="ms-3">
                                <p class="mb-1 fs-6 text-white subheading">Sale</p>
                                <h6 class="mb-0 text-white sales-amount">${{ $SalesAmount ?? 0 }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mt-4">
                    <a href="{{ route('purchases.index') }}" class="text-decoration-none">
                        <div class="  border darkblue-bg rounded d-flex align-items-center p-3 rounded-4">
                            <img src="{{ asset('back/assets/dasheets/img/content-bag.svg') }}"
                                class="img-fluid text-center bg-white p-2 rounded-4" alt="" />
                            <div class="ms-3">
                                <p class="mb-1 text-white subheading">Purchase</p>
                                <h6 class="mb-0 sales-amount text-white">${{ $PurchaseAmount ?? 0 }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mt-4">
                    <a href="{{ route('sale_return.index') }}" class="text-decoration-none">
                        <div class="  border rounded d-flex align-items-center p-3 darkgreen-bg rounded-4">
                            <img src="{{ asset('back/assets/dasheets/img/content-right-arrow.svg') }}"
                                class="img-fluid text-center bg-white p-2 rounded-4" alt="" />
                            <div class="ms-3">
                                <p class="mb-1 fs-6 text-white subheading">
                                    Sales Return
                                </p>
                                <h6 class="mb-0 sales-amount text-white">${{ $saleReturn ?? 0 }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mt-4">
                    <a href="{{ route('purchase_return.index') }}" class="text-decoration-none">
                        <div class="  border rounded d-flex align-items-center p-3 normalblue-bg rounded-4">
                            <img src="{{ asset('back/assets/dasheets/img/content-left-arrow.svg') }}"
                                class="img-fluid text-center bg-white p-2 rounded-4" alt="" />
                            <div class="ms-3">
                                <p class="mb-1 fs-6 text-white subheading">
                                    Purchase Return
                                </p>
                                <h6 class="mb-0 sales-amount text-white">${{ $purchaseReturn ?? 0 }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>


        <div class="container-fluid px-4">

            <div class="row">
                <!-- Profit -->
                <div class="col-xl-3 col-sm-6 col-12 mt-3">
                    <div class="card revenue-widget flex-fill rounded-3" style="border: 1px solid #e6eaed">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                <div>
                                    <h4 class="mb-1">${{ $totalProfit ?? '' }}</h4>
                                    <p class="m-0">Profit</p>
                                </div>
                                <span class="revenue-icon bg-cyan-transparent text-cyan">
                                    <i class="fa-solid fa-layer-group fs-16"></i>
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0">
                                    <span
                                        class="fs-13 fw-bold {{ $statistics['profitChange'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $statistics['profitChange'] }}%</span> vs
                                    Last Month
                                </p>
                                <a href="" class="text-decoration-underline fs-13 fw-medium text-dark">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Profit -->

                <!-- Invoice -->
                <div class="col-xl-3 col-sm-6 col-12 mt-3">
                    <div class="card revenue-widget flex-fill rounded-3" style="border: 1px solid #e6eaed">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                <div>
                                    <h4 class="mb-1">
                                        ${{ number_format($totalInvoiceDue, 2) }}
                                    </h4>
                                    <p class="m-0">Invoice Due</p>
                                </div>
                                <span class="revenue-icon bg-teal-transparent text-teal">
                                    <i class="fa-solid fa-chart-pie"></i>
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0">
                                    <span
                                        class="fs-13 fw-bold {{ $statistics['invoiceDueChange'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $statistics['invoiceDueChange'] }}%</span> vs
                                    Last Month

                                </p>
                                <a href="{{ route('sales-payments.index') }}"
                                    class="text-decoration-underline fs-13 fw-medium text-dark">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Invoice -->

                <!-- Expenses -->
                <div class="col-xl-3 col-sm-6 col-12 mt-3">
                    <div class="card revenue-widget flex-fill rounded-3" style="border: 1px solid #e6eaed">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                <div>
                                    <h4 class="mb-1">${{ number_format($totalExpenses, 2) }}</h4>
                                    <p class="m-0">Total Expenses</p>
                                </div>
                                <span class="revenue-icon bg-orange-transparent text-orange">
                                    <i class="fa-solid fa-life-ring"></i>
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0">
                                    <span
                                        class="fs-13 fw-bold {{ $statistics['expensesChange'] >= 0 ? 'text-success' : 'text-danger' }}">{{ $statistics['expensesChange'] }}%</span>
                                    vs
                                    Last Month
                                </p>
                                <a href="{{ route('expenses.index') }}"
                                    class="text-decoration-underline fs-13 fw-medium text-dark">View
                                    All</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Expenses -->

                <!-- Returns -->
                <div class="col-xl-3 col-sm-6 col-12 mt-3">
                    <div class="card revenue-widget flex-fill rounded-3" style="border: 1px solid #e6eaed">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                <div>
                                    <h4 class="mb-1">$78,200</h4>
                                    <p class="m-0">Total Payment Returns</p>
                                </div>
                                <span class="revenue-icon bg-indigo-transparent text-indigo">
                                    <i class="fa-solid fa-hashtag"></i>
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0">
                                    <span class="fs-13 fw-bold text-danger">-20%</span> vs
                                    Last Month
                                </p>
                                <a href="" class="text-decoration-underline fs-13 fw-medium text-dark">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Returns -->
            </div>
        </div>


        <!-- Sale & Revenue End -->

        <!-- Sales Chart Start -->
        <div class="container-fluid px-4">
            <div class="row mt-2">

                <div class="col-md-7 col-12 mt-4">
                    <div class="shadow text-center rounded p-4 card border-0 h-100">
                        <div class="row card-header border-0 bg-white">
                            <div class="col-md-6 col-12">
                                <h4 class="mb-0 mt-2 heading text-start card-title">
                                    <span>
                                        <i class="bi bi-cart orange-txt lightorange-bg p-2 rounded-3"></i> </span>
                                    Sale and Purchase
                                </h4>
                            </div>
                            <div class="col-md-6 col-12 d-flex flex-wrap justify-content-end">
                                <div class="calendar-icon p-2 fs-6 me-2 rounded-3">
                                    <span class="me-2 btn btn-orange-bg text-white btn-sm italic" id="week-btn"
                                        style="padding: 1px;">This Week</span>
                                    <span class="me-2 btn btn-orange-bg-light btn-sm text-white" id="month-btn"
                                        style="padding: 1px;">This
                                        Month</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="height: 320px;">
                            <canvas id="barChart" class="dashboard-chart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- overall information  --}}
                <div class="col-md-5 mt-4">
                    <div class="  rounded p-4 px-3 pb-0 pt-3 w-100 card border-0 h-100">
                        <div class="card-header bg-white p-0 m-0">
                            <h5 class="text-start mt-2 pb-2 heading ps-3">
                                <span>
                                    <i class="bi bi-info-circle orange-txt lightorange-bg p-2 rounded-3"></i>
                                </span>
                                Overall Information
                            </h5>
                            @php
                                $totalSupplier = \App\Models\Vendor::count();
                                $totalCustomer = \App\Models\Customer::count();
                                $totalProduct = \App\Models\Sale::count();
                            @endphp
                        </div>
                        <div class="card-body p-0 pb-3">
                            <div class="row">
                                <div class="col-md-4 mt-3">
                                    <div class="card h-100" style="background: #f9fafb; border: 1px solid #e6eaed">
                                        <div class="card-body text-center">
                                            <i class="bi bi-person-check text-primary fs-3"></i>
                                            <p class="text-muted fs-6 mb-1">Suppliers</p>
                                            <h6 class="fw-bold m-0">
                                                {{ $totalSupplier ?? 0 }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="card h-100" style="background: #f9fafb; border: 1px solid #e6eaed">
                                        <div class="card-body text-center">
                                            <i class="bi bi-people fs-3 darkorange-txt"></i>
                                            <p class="text-muted fs-6 mb-1">Customers</p>
                                            <h6 class="fw-bold m-0">
                                                {{ $totalCustomer ?? 0 }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="card h-100" style="background: #f9fafb; border: 1px solid #e6eaed">
                                        <div class="card-body text-center">
                                            <i class="bi bi-cart fs-3 darkgreen-txt"></i>
                                            <p class="text-muted fs-6 mb-1">Orders</p>
                                            <h6 class="fw-bold m-0">
                                                {{ $totalProduct ?? 0 }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent p-0 pt-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <h5>Customers Overview</h5>
                                <div class="dropdown dropdown-wraper">
                                    {{-- <a href="javascript:void(0);" class="dropdown-toggle btn btn-sm border btn-white"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-calendar-week me-2"></i>Today
                                    </a> --}}
                                    <ul class="dropdown-menu p-3">
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item">Today</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item">Weekly</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item">Monthly</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-sm-5">
                                    <div id="customer-chart"></div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="row gx-0">
                                        <div class="col-sm-6">
                                            <div class="text-center border-end">
                                                <h2 class="mb-1" id="cSaleAmount">0.00</h2>
                                                <p class="darkorange-txt m-0">Sales</p>
                                                <span
                                                    class="badge badge-success badge darkgreen-bg d-inline-flex align-items-center"><i
                                                        class="ti ti-arrow-up-left me-1"></i>
                                                    <span id="cSalePercentage"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-center">
                                                <h2 class="mb-1" id="cReturnAmount">0.00</h2>
                                                <p class="text-teal darkgreen-txt m-0">Return</p>
                                                <span
                                                    class="badge badge-success badge darkgreen-bg d-inline-flex align-items-center"><i
                                                        class="ti ti-arrow-up-left me-1"></i>
                                                    <span id="cReturnPercentage"></span>
                                                </span>
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


        <!-- Recent Sales Start -->
        <div class="container-fluid px-4">
            <div class="row mt-2">

                <div class="col-12 col-md-4 mt-4">
                    <div class="  card p-3 rounded-3 border-0 h-100">
                        <div class="card-body h-100 px-0 pt-2 ">
                            <table class="table" id="example1">
                                <thead class="">
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="text-start pb-2 heading">
                                                        <span>
                                                            <i
                                                                class="bi bi-info-circle orange-txt lightorange-bg p-2 rounded-3"></i>
                                                        </span>
                                                        {{-- This Month  --}}
                                                        Top Selling Products
                                                    </h5>
                                                </div>
                                                <div class="text-start pb-2 heading">
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);"
                                                            class="dropdown-toggle border btn btn-sm btn-white d-flex align-items-center"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-calendar-week me-2"></i> <span
                                                                id="selectedFilter-1">All</span>
                                                        </a>
                                                        <ul class="dropdown-menu p-3">
                                                            <li><a href="javascript:void(0);"
                                                                    class="dropdown-item filter-option-1"
                                                                    data-filter="all">All</a></li>
                                                            <li><a href="javascript:void(0);"
                                                                    class="dropdown-item filter-option-1"
                                                                    data-filter="today">Today</a></li>
                                                            <li><a href="javascript:void(0);"
                                                                    class="dropdown-item filter-option-1"
                                                                    data-filter="weekly">Weekly</a></li>
                                                            <li><a href="javascript:void(0);"
                                                                    class="dropdown-item filter-option-1"
                                                                    data-filter="monthly">Monthly</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topSellingProducts as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-2 align-items-center  py-2">
                                                    <div>
                                                        <img src="{{ isset($product->product->images[0]) ? asset('/storage' . $product->product->images[0]->img_path) : 'https://placehold.co/600x400' }}"
                                                            width="50" alt="" class="img-fluid rounded-3" />
                                                    </div>
                                                    <div>
                                                        <h6 class="heading fw-bold" style="font-size: 14px">
                                                            {{-- {{ $product->product->name ?? '' }} --}}
                                                            {{ strlen($product?->product?->name) > 20 ? substr($product?->product?->name, 0, 20) . '...' : $product?->product?->name }}

                                                        </h6>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <p class="m-0 text-muted">
                                                                    {{-- {{ $product->product->product_warehouses[0]?->warehouse->users->name ?? 'N/A' }} --}}
                                                                    {{-- Atlanta --}}
                                                                    {{ optional($product->product->product_warehouses[0] ?? null)?->warehouse?->users?->name ?? 'N/A' }}

                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

                {{-- stock alert  --}}
                <div class="col-12 col-md-4 mt-4">
                    <div class="  card p-3 rounded-3 border-0 h-100">
                        <div class="card-body h-100 px-0 pt-2 ">
                            <table class="table" id="example2">
                                <thead class="">
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="text-start pb-2 heading">
                                                        <span>
                                                            <i
                                                                class="bi bi-box orange-txt lightorange-bg p-2 rounded-3"></i>
                                                        </span>
                                                        Stock Alert
                                                    </h5>
                                                </div>
                                                <div>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockAlert as $stock)
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-2 align-items-center py-2 justify-content-between">
                                                    <div class="d-flex gap-2 align-items-center gap-2">
                                                        <div>
                                                            <img src="{{ isset($stock->product->images[0]) ? asset('/storage' . $stock->product->images[0]->img_path) : 'https://placehold.co/600x400' }}"
                                                                width="50" alt=""
                                                                class="img-fluid rounded-3" />
                                                        </div>
                                                        <div>
                                                            <h6 class="heading fw-bold" style="font-size: 14px">
                                                                {{-- {{ $stock->product->name ?? '' }} --}}
                                                                {{-- truncate the long product name and add ...  --}}
                                                                {{ strlen($stock?->product?->name) > 20 ? substr($stock?->product?->name, 0, 20) . '...' : $stock?->product?->name }}
                                                            </h6>
                                                            <p class="m-0 text-muted">ID: #{{ $stock->product->sku ?? 0 }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="text-muted mb-1">Instock</p>
                                                        <p class="m-0 orange-txt">
                                                            {{ number_format($stock->quantity) ?? 0 }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                {{-- recent sales  --}}
                <div class="col-12 col-md-4 mt-4">
                    <div class="  card p-3 rounded-3 border-0 h-100">
                        <div class="card-body h-100 px-0 pt-2 ">
                            <table class="table" id="example5">
                                <thead class="">
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="text-start pb-2 heading">
                                                        <span>
                                                            <i
                                                                class="bi bi-box orange-txt lightorange-bg p-2 rounded-3"></i>
                                                        </span>
                                                        Recent Sales
                                                    </h5>
                                                </div>
                                                <div class="text-start pb-2 heading">
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);"
                                                            class="dropdown-toggle border btn btn-sm btn-white d-flex align-items-center"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-calendar-week me-2"></i> <span
                                                                id="selectedFilter-5">All</span>
                                                        </a>
                                                        <ul class="dropdown-menu p-3">
                                                            <li><a href="javascript:void(0);"
                                                                    class="dropdown-item filter-option-5"
                                                                    data-filter="all">All</a></li>
                                                            <li><a href="javascript:void(0);"
                                                                    class="dropdown-item filter-option-5"
                                                                    data-filter="today">Today</a></li>
                                                            <li><a href="javascript:void(0);"
                                                                    class="dropdown-item filter-option-5"
                                                                    data-filter="weekly">Weekly</a></li>
                                                            <li><a href="javascript:void(0);"
                                                                    class="dropdown-item filter-option-5"
                                                                    data-filter="monthly">Monthly</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentSaleProducts as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-2 align-items-center py-2 justify-content-between">
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <div>
                                                            <img src="{{ isset($product->product->images[0]) ? asset('/storage' . $product->product->images[0]->img_path) : 'https://placehold.co/600x400' }}"
                                                                width="50" alt=""
                                                                class="img-fluid rounded-3" />
                                                        </div>
                                                        <div>
                                                            <h6 class="heading fw-bold" style="font-size: 14px">
                                                                {{ strlen($product?->product?->name) > 20 ? substr($product?->product?->name, 0, 20) . '...' : $product?->product?->name }}
                                                            </h6>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <p class="m-0 text-muted">
                                                                        {{ $product?->sub_total ?? '0' }}
                                                                    </p>
                                                                </div>
                                                                <div>
                                                                    <p class="mb-1">
                                                                        <i class="bi bi-circle-fill orange-txt"
                                                                            style="font-size: 6px"></i>
                                                                    </p>
                                                                </div>
                                                                <p class="m-0">{{ $product?->quantity }} Qty</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        {{-- <p class="mb-1 text-muted">Today</p>
                                                        <span class="badges bg-purple text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                                                            <span class="mb-1">
                                                                <i class="bi bi-circle-fill text-white" style="font-size: 6px"></i>
                                                            </span>
                                                            <span>Processing</span>
                                                        </span> --}}
                                                        <p class="m-0 text-muted">
                                                            {{ $product->created_at->format('d/m/y') }}</p>
                                                        {{-- <span class="badges bg-purple text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                                                            <span class="mb-1">
                                                                <i class="bi bi-circle-fill text-white" style="font-size: 6px"></i>
                                                            </span>
                                                            <span>Processing</span>
                                                        </span>  --}}
                                                        @if ($product->sale->status == 'completed')
                                                            <span
                                                                class="badges bg-success text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                                                                <span class="mb-1">
                                                                    <i class="bi bi-circle-fill text-white"
                                                                        style="font-size: 6px"></i>
                                                                </span>
                                                                <span>Completed</span>
                                                            </span>
                                                        @else
                                                            <span
                                                                class="badges bg-warning text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                                                                <span class="mb-1">
                                                                    <i class="bi bi-circle-fill text-white"
                                                                        style="font-size: 6px"></i>
                                                                </span>
                                                                <span>Pending</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                                            <div class="new-pagination " id="example5_paginate">
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

        <div class="container-fluid px-4 mt-3">
            <div class="row">

                <div class="col-md-6 mt-4">
                    <div class="  card p-3 rounded-3 border-0 h-100">
                        <div class="card-header bg-white p-0 m-0 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-start mt-2 pb-2 heading">
                                        <span>
                                            <i
                                                class="bi bi-exclamation-triangle orange-txt lightorange-bg p-2 rounded-3"></i>
                                        </span>
                                        Sale Statistics
                                    </h5>
                                </div>
                                <div>
                                    <a href="#" class="text-dark">View all</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 m-0">
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <div class="border p-2 rounded-3">
                                    <h5 class="heading darkgreen-txt m-0">
                                        <span>$12,189</span>
                                        <span
                                            class="badges p-1 ms-3 px-2 text-center darkgreen-bg fw-normal align-items-center"
                                            style="font-size: 11px !important"><i
                                                class="ti ti-arrow-up-left me-1"></i>25%</span>
                                    </h5>
                                    <p class="m-0">Revenue</p>
                                </div>
                                <div class="border p-2 rounded-3">
                                    <h5 class="heading darkorange-txt m-0">
                                        <span>$12,189</span>
                                        <span class="badges p-1 ms-3 px-2 text-center bg-red fw-normal align-items-center"
                                            style="font-size: 11px !important"><i
                                                class="ti ti-arrow-up-left me-1"></i>25%</span>
                                    </h5>
                                    <p class="m-0">Revenue</p>
                                </div>
                            </div>
                            <div id="sales-statistics"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-4">
                    <div class="  card p-3 rounded-3 border-0 h-100">
                        <div class="card-header bg-white p-0 m-0 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-start mt-2 pb-2 heading">
                                        <span>
                                            <i class="bi bi-flag orange-txt lightorange-bg p-2 rounded-3"></i>
                                        </span>
                                        Recent Transaction
                                    </h5>
                                </div>
                                <div class="text-start pb-2 heading">
                                    <div class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle border btn btn-sm btn-white d-flex align-items-center"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-calendar-week me-2"></i> <span id="selectedFilter-4">All</span>
                                        </a>
                                        <ul class="dropdown-menu p-3">
                                            <li><a href="javascript:void(0);" class="dropdown-item filter-option-4" data-filter="all">All</a></li>
                                            <li><a href="javascript:void(0);" class="dropdown-item filter-option-4" data-filter="today">Today</a></li>
                                            <li><a href="javascript:void(0);" class="dropdown-item filter-option-4" data-filter="weekly">Weekly</a></li>
                                            <li><a href="javascript:void(0);" class="dropdown-item filter-option-4" data-filter="monthly">Monthly</a></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 m-0">
                            <div class="table-responsive text-center">
                                <table class="table text-start" id="example4">
                                    <thead>
                                        <tr>
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
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($recentTransactions as $sale)
                                            <tr>
                                                <td class="text-muted align-middle">#{{ $sale->reference ?? 'N/A' }}</td>
                                                <td class="align-middle">
                                                    <div class="d-flex gap-2 align-items-center ">
                                                        {{-- <div>
                                                            <img src="{{ asset('images/customer-img.png') }}"
                                                                width="40" alt=""
                                                                class="img-fluid rounded-3" />
                                                        </div> --}}
                                                        <div>
                                                            <h6 class="heading fw-bold" style="font-size: 14px">
                                                                {{ $sale->customer->user->name ?? 'N/A' }}
                                                            </h6>
                                                            {{-- <p class="m-0">
                                                                <i class="bi bi-geo-alt-fill"></i>
                                                                {{ $sale?->customer?->country ?? '' }}
                                                            </p> --}}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle">{{ $sale->warehouse->users->name ?? 'N/A' }}</td>
                                                <td class="align-middle">
                                                    @if ($sale->status == 'completed' || $sale->status == 'Completed')
                                                        {{-- <span class="badges bg-lightgreen text-center">{{ ucwords($sale->status ?? '') }}</span> --}}
                                                        <span
                                                            class="badges bg-green text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                                                            <span class="mb-1">
                                                                <i class="bi bi-circle-fill text-white"
                                                                    style="font-size: 6px"></i></span>
                                                            <span> Completed </span>
                                                        </span>
                                                    @elseif ($sale->status == 'pending' || $sale->status == 'Pending')
                                                        {{-- <span class="badges bg-lightred text-center">{{ ucwords($sale->status ?? '') }}</span> --}}
                                                        <span
                                                            class="badges bg-red text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                                                            <span class="mb-1">
                                                                <i class="bi bi-circle-fill text-white"
                                                                    style="font-size: 6px"></i></span>
                                                            <span> Pending </span>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    ${{ number_format($sale->grand_total ?? '0.00', 2) }}</td>
                                                <td class="align-middle">
                                                    ${{ number_format($sale->amount_recieved ?? '0.00', 2) }}</td>
                                                <td class="align-middle">
                                                    ${{ number_format($sale->amount_due ?? '0.00', 2) }}</td>
                                                <td class="align-middle">
                                                    @if ($sale->payment_status == 'paid')
                                                        {{-- <span class="badges bg-lightgreen text-center"></span> --}}
                                                        <span
                                                            class="badges bg-green text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                                                            <span class="mb-1">
                                                                <i class="bi bi-circle-fill text-white"
                                                                    style="font-size: 6px"></i></span>
                                                            <span> {{ ucwords($sale->payment_status ?? '') }} </span>
                                                        </span>
                                                    @elseif ($sale->payment_status == 'partial')
                                                        {{-- <span class="badges bg-lightyellow text-center"></span> --}}
                                                        <span
                                                            class="badges bg-yellow text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                                                            <span class="mb-1">
                                                                <i class="bi bi-circle-fill text-white"
                                                                    style="font-size: 6px"></i></span>
                                                            <span> {{ ucwords($sale->payment_status ?? '') }} </span>
                                                        </span>
                                                    @else
                                                        {{-- <span class="badges bg-lightred text-center">{{ ucwords($sale->payment_status ?? '') }}</span> --}}
                                                        <span
                                                            class="badges bg-red text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                                                            <span class="mb-1">
                                                                <i class="bi bi-circle-fill text-white"
                                                                    style="font-size: 6px"></i></span>
                                                            <span> {{ ucwords($sale->payment_status ?? '') }} </span>
                                                        </span>
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
                                            <p class="subheading col-form-label" id="dataTableInfo4">1-4 of 4 entries
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <div class="new-pagination " id="example4_paginate">
                                                <a class="rounded-start paginate_button" style="cursor: pointer"> ❮
                                                </a>
                                                <a class="rounded-end paginate_button page-item next"
                                                    style="cursor: pointer">
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
        </div>
        <div class="container-fluid px-4 mt-3">
            <div class="row">
                {{-- top customer  --}}
                <div class="col-12 col-md-6 mt-4">
                    <div class="  card p-3 rounded-3 border-0 h-100">
                        <div class="card-body h-100 px-0 pt-2 ">
                            <table class="table" id="example3">
                                <thead class="">
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="text-start pb-2 heading">
                                                        <span>
                                                            <i
                                                                class="bi bi-people orange-txt lightorange-bg p-2 rounded-3"></i>
                                                        </span>
                                                        Top Customer For The Month
                                                    </h5>
                                                </div>
                                                <div>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topCustomerForMonth as $customer)
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-2 align-items-center py-2 justify-content-between">
                                                    <div class="d-flex gap-2 align-items-center gap-2">
                                                        <div>
                                                            <img src="{{ asset('images/customer-img.png') }}"
                                                                width="50" alt=""
                                                                class="img-fluid rounded-3" />
                                                        </div>
                                                        <div>
                                                            <h6 class="heading fw-bold" style="font-size: 14px">
                                                                {{ $customer?->customer?->user?->name ?? '' }}
                                                            </h6>
                                                            <p class="m-0 text-muted">
                                                                {{-- location icon --}}
                                                                <i class="bi bi-geo-alt-fill"></i>
                                                                {{ $customer?->customer?->country ?? '' }} .
                                                                {{ $customer->total_sales ?? 0 }} Orders
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="text-muted mb-1">Grand Total</p>
                                                        <p class="m-0 orange-txt">${{ $customer?->total_amount ?? 0 }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

                {{-- Top category  --}}
                <div class="col-md-6 mt-4">
                    <div class="  card p-3 rounded-3 border-0 h-100">
                        <div class="card-header bg-white p-0 m-0 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-start mt-2 pb-2 heading">
                                        <span>
                                            <i class="bi bi-people orange-txt lightorange-bg p-2 rounded-3"></i>
                                        </span>
                                        Top Category
                                    </h5>
                                </div>
                                <div>
                                    <a href="{{ route('categories.index') }}" class="text-dark">View all</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 m-0">
                            <canvas id="top-category"></canvas>
                            <!-- <div id="sales-statistics"></div> -->
                        </div>
                        {{-- total number of categories --}}
                        {{-- <div class="card-footer mt-3 bg-white border-0 text-end">
                            Total number of category: {{ $totalCategories }}
                        </div> --}}
                        <h6 class="mb-2 fw-bold">Category Statistics</h6>
                        <div class="border rounded-2">
                            <div class="d-flex align-items-center justify-content-between border-bottom p-2">
                                <p class="d-inline-flex align-items-center mb-0">
                                    <i class="bi bi-square-fill me-1 darkblue-txt" style="font-size: 7px !important"></i>
                                    Total Number Of Categories
                                </p>
                                <h6 class="fw-bold m-0">{{ $totalCategories ?? '' }}</h6>
                            </div>
                            <div class="d-flex align-items-center justify-content-between p-2">
                                <p class="d-inline-flex align-items-center mb-0">
                                    <i class="bi bi-square-fill me-1 orange-txt" style="font-size: 7px !important"></i>
                                    Total Number Of Products
                                </p>
                                <h6 class="fw-bold m-0">
                                    @php
                                        $productCount = \App\Models\Product::count();
                                        echo $productCount;
                                    @endphp
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Order statistics -->
        <div class="container-fluid px-4 mt-4">
            <div class="card border-0   p-3 rounded-3">
                <div class="card-header bg-white p-0 m-0 mb-2">
                    <h5 class="text-start mt-2 pb-2 heading">
                        <span>
                            <i class="bi bi-box-seam orange-txt lightorange-bg p-2 rounded-3"></i>
                        </span>
                        Order Statistics
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div id="chart"></div>
                </div>
            </div>
        </div>
        <!-- End order statistics -->
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
                        backgroundColor: "#FE9F44",
                        borderWidth: 1,
                    },
                    {
                        label: "Purchase",
                        data: [], // Initialize empty data
                        backgroundColor: "#FFC6A4",
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
            $(this).removeClass('btn-orange-bg-light').addClass('btn-orange-bg text-white');
            $('#month-btn').removeClass('btn-orange-bg').addClass('btn-orange-bg-light');
            fetchData('week');
        });

        $('#month-btn').click(function() {
            $(this).removeClass('btn-orange-bg-light').addClass('btn-orange-bg text-white');
            $('#week-btn').removeClass('btn-orange-bg').addClass('btn-orange-bg-light');
            fetchData('month');
        });

        // Initial fetch for week data
        $(document).ready(function() {
            fetchData('week');
        });
    </script>

    <script>
        $(document).ready(function() {
            for (let i = 1; i <= 5; i++) {
                let table = $(`#example${i}`).DataTable({
                    dom: 'Bfrtip',
                    order: [],
                    pageLength: 5,
                    ordering: false,
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

    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        function generateData() {
            let series = [];
            let hours = Array.from({
                length: 10
            }, (_, i) => `${i}:00`);
            let days = ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"];

            hours.forEach((hour) => {
                let dataPoints = [];
                days.forEach((day) => {
                    dataPoints.push({
                        x: day, // Days on X-axis
                        y: Math.floor(Math.random() * 100), // Random value
                    });
                });
                series.push({
                    name: hour, // Time on Y-axis
                    data: dataPoints,
                });
            });

            return series;
        }

        var options = {
            series: generateData(),
            chart: {
                height: 400,
                type: "heatmap",
            },
            dataLabels: {
                enabled: false,
            },
            colors: ["#FE9F44"],
            xaxis: {
                categories: ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"],
                // title: { text: 'Days of the Week' },
            },
            yaxis: {
                categories: Array.from({
                    length: 24
                }, (_, i) => `${i}:00`),
                // title: { text: 'Time (24-hour format)' },
            },
        };

        var chart = new ApexCharts(document.querySelector("#chartO"), options);
        chart.render();
    </script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('/sales-data')
                .then(response => response.json())
                .then(data => {
                    var options = {
                        series: data.heatmapData,
                        chart: {
                            height: 400,
                            type: "heatmap",
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        colors: ["#FE9F44"],
                        xaxis: {
                            categories: ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"], // Days of the week
                        },
                        yaxis: {
                            categories: Array.from({ length: 24 }, (_, i) => `${i}:00`), // Hours of the day
                        },
                    };

                    var chart = new ApexCharts(document.querySelector("#chartO"), options);
                    chart.render();
                })
                .catch(error => console.error("Error fetching sales data:", error));
        });
    </script> --}}


    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch('/sales-data') // Call Laravel API
            .then(response => response.json())
            .then(data => {
                var options = {
                    series: data.heatmapData,
                    chart: {
                        height: 400,
                        type: "heatmap",
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    colors: ["#FE9F44"],
                    xaxis: {
                        categories: ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"], // Days of the week
                    },
                    yaxis: {
                        categories: Array.from({ length: 24 }, (_, i) => `${i}:00`), // Hours of the day
                    },
                };

                var chart = new ApexCharts(document.querySelector("#chartO"), options);
                chart.render();
            })
            .catch(error => console.error("Error fetching sales data:", error));
    });
</script> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Fetch data from Laravel endpoint
        async function fetchWeeklySalesData() {
            try {
                const response = await fetch('/weekly-sales');
                return await response.json();
            } catch (error) {
                console.error('Error fetching sales data:', error);
                return [];
            }
        }

        // Initialize chart with real data
        async function initializeChart() {
            const series = await fetchWeeklySalesData();

            var options = {
                series: series,
                chart: {
                    height: 400,
                    type: "heatmap",
                },
                dataLabels: {
                    enabled: false,
                },
                colors: ["#FE9F44"],
                xaxis: {
                    categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                },
                yaxis: {
                    categories: Array.from({
                        length: 24
                    }, (_, i) => `${i.toString().padStart(2, '0')}:00`),
                },
                tooltip: {
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        // const day = w.globals.categoryLabels[dataPointIndex];
                        const day = w.globals.categoryLabels[dataPointIndex];
                        const hour = w.config.series[seriesIndex].name;
                        const sales = series[seriesIndex][dataPointIndex];
                        return `
                        <div class="p-2">
                            <div><strong>${hour}</strong></div>
                            <div>Total Sales: $${sales.toFixed(2)}</div>
                        </div>
                    `;
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chartO"), options);
            chart.render();
        }

        // Initialize the chart when the page loads
        document.addEventListener('DOMContentLoaded', initializeChart);
    </script> --}}


    {{-- <script>
        if ($("#chart").length > 0) {
          var options = {
            chart: {
              type: "heatmap",
              height: 370,
            },
            plotOptions: {
              heatmap: {
                radius: 4,
                enableShades: false,
                colorScale: {
                  ranges: [
                    {
                      from: 0,
                      to: 99,
                      color: "#FFE3CB",
                    },
                    {
                      from: 100,
                      to: 200,
                      color: "#FE9F43",
                    },
                  ],
                },
              },
            },
            legend: {
              show: false,
            },
            dataLabels: {
              enabled: false,
            },
            grid: {
              padding: {
                top: -20,
                bottom: 0,
                left: 0,
                right: 0, // Minimize padding around the heatmap
              },
            },
            yaxis: {
              labels: {
                offsetX: -15, // Adjust horizontal alignment
              },
            },
            series: [
              {
                name: "2 Am",
                data: [
                  {
                    x: "Mon",
                    y: 100,
                  },
                  {
                    x: "Tue",
                    y: 100,
                  },
                  {
                    x: "Wed",
                    y: 100,
                  },
                  {
                    x: "Thu",
                    y: 32,
                  },
                  {
                    x: "Fri",
                    y: 32,
                  },
                  {
                    x: "Sat",
                    y: 32,
                  },
                  {
                    x: "Sun",
                    y: 32,
                  },
                ],
              },
              {
                name: "4 Am",
                data: [
                  {
                    x: "Mon",
                    y: 100,
                    color: "#ff5722",
                  },
                  {
                    x: "Tue",
                    y: 100,
                  },
                  {
                    x: "Wed",
                    y: 100,
                  },
                  {
                    x: "Thu",
                    y: 120,
                  },
                  {
                    x: "Fri",
                    y: 32,
                  },
                  {
                    x: "Sat",
                    y: 50,
                  },
                  {
                    x: "Sun",
                    y: 40,
                  },
                ],
              },
              {
                name: "6 Am",
                data: [
                  {
                    x: "Mon",
                    y: 22,
                  },
                  {
                    x: "Tue",
                    y: 29,
                  },
                  {
                    x: "Wed",
                    y: 13,
                  },
                  {
                    x: "Thu",
                    y: 32,
                  },
                  {
                    x: "Fri",
                    y: 32,
                  },
                  {
                    x: "Sat",
                    y: 32,
                  },
                  {
                    x: "Sun",
                    y: 32,
                  },
                ],
              },
              {
                name: "8 Am",
                data: [
                  {
                    x: "Mon",
                    y: 0,
                  },
                  {
                    x: "Tue",
                    y: 29,
                  },
                  {
                    x: "Wed",
                    y: 13,
                  },
                  {
                    x: "Thu",
                    y: 32,
                  },
                  {
                    x: "Fri",
                    y: 30,
                  },
                  {
                    x: "Sat",
                    y: 100,
                  },
                  {
                    x: "Sun",
                    y: 100,
                  },
                ],
              },
              {
                name: "10 Am",
                data: [
                  {
                    x: "Mon",
                    y: 200,
                  },
                  {
                    x: "Tue",
                    y: 200,
                  },
                  {
                    x: "Wed",
                    y: 200,
                  },
                  {
                    x: "Thu",
                    y: 32,
                  },
                  {
                    x: "Fri",
                    y: 0,
                  },
                  {
                    x: "Sat",
                    y: 0,
                  },
                  {
                    x: "Sun",
                    y: 32,
                  },
                ],
              },
              {
                name: "12 Am",
                data: [
                  {
                    x: "Mon",
                    y: 0,
                  },
                  {
                    x: "Tue",
                    y: 0,
                  },
                  {
                    x: "Wed",
                    y: 75,
                  },
                  {
                    x: "Thu",
                    y: 0,
                  },
                  {
                    x: "Fri",
                    y: 0,
                  },
                  {
                    x: "Sat",
                    y: 0,
                  },
                  {
                    x: "Sun",
                    y: 0,
                  },
                ],
              },
              {
                name: "14 Pm",
                data: [
                  {
                    x: "Mon",
                    y: 0,
                  },
                  {
                    x: "Tue",
                    y: 20,
                  },
                  {
                    x: "Wed",
                    y: 13,
                  },
                  {
                    x: "Thu",
                    y: 32,
                  },
                  {
                    x: "Fri",
                    y: 0,
                  },
                  {
                    x: "Sat",
                    y: 0,
                  },
                  {
                    x: "Sun",
                    y: 32,
                  },
                ],
              },
              {
                name: "16 Pm",
                data: [
                  {
                    x: "Mon",
                    y: 13,
                  },
                  {
                    x: "Tue",
                    y: 20,
                  },
                  {
                    x: "Wed",
                    y: 13,
                  },
                  {
                    x: "Thu",
                    y: 32,
                  },
                  {
                    x: "Fri",
                    y: 200,
                  },
                  {
                    x: "Sat",
                    y: 13,
                  },
                  {
                    x: "Sun",
                    y: 32,
                  },
                ],
              },

              {
                name: "18 Am",
                data: [
                  {
                    x: "Mon",
                    y: 0,
                  },
                  {
                    x: "Tue",
                    y: 20,
                  },
                  {
                    x: "Wed",
                    y: 13,
                  },
                  {
                    x: "Thu",
                    y: 32,
                  },
                  {
                    x: "Fri",
                    y: 0,
                  },
                  {
                    x: "Sat",
                    y: 200,
                  },
                  {
                    x: "Sun",
                    y: 200,
                  },
                ],
              },
            ],
          };
          var chart = new ApexCharts(document.querySelector("#chart"), options);
          chart.render();
        }
    </script> --}}



    <script>
        if ($("#chart").length > 0) {
            // Fetch data from Laravel endpoint
            async function fetchWeeklySalesData() {
                try {
                    const response = await fetch('/weekly-sales-heatmap');
                    return await response.json();
                } catch (error) {
                    console.error('Error fetching sales data:', error);
                    return [];
                }
            }

            // Initialize chart with real data
            async function initializeChart() {
                const series = await fetchWeeklySalesData();

                var options = {
                    chart: {
                        type: "heatmap",
                        height: 370,
                    },
                    plotOptions: {
                        heatmap: {
                            radius: 4,
                            enableShades: false,
                            colorScale: {
                                ranges: [{
                                        from: 0,
                                        to: 99,
                                        color: "#FFE3CB",
                                    },
                                    {
                                        from: 100,
                                        to: 200,
                                        color: "#FE9F43",
                                    },
                                    {
                                        from: 201,
                                        to: 300,
                                        color: "#FF5722",
                                    }
                                ],
                            },
                        },
                    },
                    legend: {
                        show: false,
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    grid: {
                        padding: {
                            top: -20,
                            bottom: 0,
                            left: 0,
                            right: 0,
                        },
                    },
                    yaxis: {
                        labels: {
                            offsetX: -15,
                        },
                    },
                    series: series,
                    tooltip: {
                        custom: function({
                            series,
                            seriesIndex,
                            dataPointIndex,
                            w
                        }) {
                            const day = w.globals.categoryLabels[dataPointIndex];
                            const timeSlot = w.config.series[seriesIndex].name;
                            const sales = series[seriesIndex][dataPointIndex];

                            return `
                                <div class="p-2">
                                    <div><strong>Time: ${timeSlot}</strong></div>
                                    <div>Total Sales: $${sales.toFixed(2)}</div>
                                </div>
                            `;
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            }

            // Initialize the chart when the page loads
            document.addEventListener('DOMContentLoaded', initializeChart);
        }
    </script>


    {{-- filters  --}}
    <script>
        $(document).ready(function() {
            $(".filter-option-1").click(function() {
                var filter = $(this).data("filter");
                $("#selectedFilter-1").text($(this).text()); // Change dropdown text
                let table = $('#example1').DataTable();

                $.ajax({
                    url: "{{ route('topSellingProducts.filter') }}",
                    method: "GET",
                    data: {
                        filter: filter
                    },
                    success: function(response) {
                        // $("#example1 tbody").html(response);
                        let formatedData = response.data.map(item => [
                            `<div class="d-flex gap-2 align-items-center py-2">
                                <div>
                                    ${item.image}
                                </div>
                                <div>
                                    <h6 class="heading fw-bold" style="font-size: 14px">
                                        ${item.product_name}
                                    </h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            <p class="m-0 text-muted">
                                                ${item.wherehouse}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>`,
                        ]);
                        table.clear().rows.add(formatedData).draw();

                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });

        // recent sales
        $(document).on("click", ".filter-option-5", function () {
            let filter = $(this).data("filter");
            $("#selectedFilter-5").text($(this).text()); // Update dropdown label
            let table = $('#example5').DataTable();

            $.ajax({
                url: "{{ route('recentSales.filter') }}",
                type: "GET",
                data: { filter: filter },
                success: function (response) {
                    // $("#example5 tbody").html(response.html); // Update the table dynamically
                    // table.clear().draw(); // Clear current DataTable content
                    // table.rows.add($(response.html)).draw();

                    let formattedData = response.data.map(item => [

                        `<div class="d-flex gap-2 align-items-center py-2 justify-content-between">
                <div class="d-flex gap-2 align-items-center">
                    <div>
                        ${item.image}
                    </div>
                    <div>
                        <h6 class="heading fw-bold" style="font-size: 14px">
                            ${item.product_name}
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <div>
                                <p class="m-0 text-muted">
                                    <i class="bi bi-circle-fill darkblue-txt"
                                        style="font-size: 6px"></i>
                                    ${item.sub_total}
                                </p>
                            </div>
                            <div>
                                <p class="mb-1">
                                    <i class="bi bi-circle-fill orange-txt"
                                        style="font-size: 6px"></i>
                                </p>
                            </div>
                            <p class="m-0"> ${item.quantity}  Qty</p>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <p class="m-0 text-muted">
                    ${item.created_at}</p>
                        ${item.status}
                </div>
            </div>`,
                    ]);

                    table.clear().rows.add(formattedData).draw(); // Use the formatted array
                },
            });
        });

        $(document).on("click", ".filter-option-4", function () {
            let filter = $(this).data("filter");
            $("#selectedFilter-4").text($(this).text()); // Update dropdown label
            let table = $("#example4").DataTable();
            $.ajax({
                url: "{{ route('recentTransactions.filter') }}",
                type: "GET",
                data: { filter: filter },
                success: function (response) {
                    // $("#example4 tbody").html(response.html); // Update the table body dynamically
                    // table.clear().rows.add(response.data).draw();
                    let formattedData = response.data.map(item => [
                        item.reference,
                        item.customer_name,
                        item.warehouse_name,
                        item.status,
                        item.grand_total,
                        item.amount_received,
                        item.amount_due,
                        item.payment_status
                    ]);

                    table.clear().rows.add(formattedData).draw(); // Use the formatted array
                },
            });
        });



    </script>



@endsection
