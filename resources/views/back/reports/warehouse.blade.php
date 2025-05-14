@extends('back.layout.app')
@section('title', 'Warehouses List')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@endsection

@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Warehouse Report</h3>
            </div>

            @include('back.layout.errors')

            <div class="row mt-3 justify-content-center">
                @if (auth()->user()->hasRole(['Admin']) && session('selected_warehouse_id') == '')
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="warehouses" class="mb-1 fw-bold">Warehouse</label>
                            <select class="form-control form-select subheading" aria-label="Default select example"
                                id="warehouses">
                                <option value="" selected>Select Warehouse</option>
                                @foreach ($all_warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse?->users?->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-6 col-xl-3 mt-4">
                    <div class="card-shadow border rounded d-flex align-items-center p-3">
                        <img src="{{ asset('back/assets/dasheets/img/content-sale.svg') }}" class="img-fluid text-center"
                            alt="">
                        <div class="ms-3">
                            <p class="mb-1 fs-6 text-muted subheading">Sale</p>
                            <h6 class="mb-0 sales-amount fs-6 text-dark ">{{ $total_sales }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mt-4">
                    <div class="card-shadow border rounded d-flex align-items-center p-3">
                        <img src="{{ asset('back/assets/dasheets/img/content-bag.svg') }}" class="img-fluid text-center"
                            alt="">
                        <div class="ms-3">
                            <p class="mb-1 text-muted subheading">Purchase</p>
                            <h6 class="mb-0 purchase-amount">{{ $total_purchase }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mt-4">
                    <div class="card-shadow border rounded d-flex align-items-center p-3">
                        <img src="{{ asset('back/assets/dasheets/img/content-right-arrow.svg') }}"
                            class="img-fluid text-center" alt="">
                        <div class="ms-3">
                            <p class="mb-1 fs-6 text-muted subheading">Sales Return</p>
                            <h6 class="mb-0 purchase-return-amount">{{ $total_purchase_return }}</h6>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-xl-3 mt-4">
                    <div class="card-shadow border rounded d-flex align-items-center p-3">
                        <img src="{{ asset('back/assets/dasheets/img/content-left-arrow.svg') }}"
                            class="img-fluid text-center" alt="">
                        <div class="ms-3">
                            <p class="mb-1 fs-6 text-muted subheading">
                                Purchase Return
                            </p>
                            <h6 class="mb-0 sale-return-amount">{{ $total_sale_return }}</h6>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card border-0 card-shadow rounded-3 p-2 mt-5">
                <div class="card-body">
                    {{-- generate nav tab content for sales and purchases --}}
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales"
                                type="button" role="tab" aria-controls="sales" aria-selected="true">Sales</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases"
                                type="button" role="tab" aria-controls="purchases"
                                aria-selected="false">Purchases</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sale-return-tab" data-bs-toggle="tab" data-bs-target="#sale-return"
                                type="button" role="tab" aria-controls="sale-return" aria-selected="false">Sale
                                Return</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="purchase-return-tab" data-bs-toggle="tab"
                                data-bs-target="#purchase-return" type="button" role="tab"
                                aria-controls="purchase-return" aria-selected="false">Purchase Return</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="expensetab" data-bs-toggle="tab" data-bs-target="#expense"
                                type="button" role="tab" aria-controls="expense"
                                aria-selected="false">Expenses</button>
                        </li>

                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="sales" role="tabpanel"
                            aria-labelledby="sales-tab">
                            <div class="row my-3">
                                <div class="col-md-3 col-12 mt-2">
                                    <div class="input-search position-relative">
                                        <input type="text" placeholder="Search Sale"
                                            class="form-control rounded-3 subheading" id="custom-filter1" />
                                        <span class="fa fa-search search-icon text-secondary"></span>
                                    </div>
                                </div>

                                <div class="col-md-9 col-12 text-end">
                                    <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i class="bi bi-file-earmark"
                                            id="download-pdf1"></i></a>
                                </div>
                            </div>
                            <table id="example1" class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Customer</th>
                                        <th>Warehouse</th>
                                        <th>Grand Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                        <th>Shipping Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouse_sales as $sale)
                                        <tr>
                                            <td class="text-primary align-middle"><a class="text-decoration-none"
                                                    href="{{ route('sales.show', $sale['id']) }}">{{ $sale['reference'] }}</a>
                                            </td>
                                            {{-- <td>{{ $sale['reference'] }}</td> --}}
                                            <td>{{ $sale['customer'] }}</td>
                                            <td>{{ $sale['warehouse'] }}</td>
                                            <td>${{ $sale['grand_total'] }}</td>
                                            <td>${{ $sale['paid'] }}</td>
                                            <td>${{ $sale['due'] }}</td>
                                            <td>{{ $sale['status'] }}</td>
                                            <td>{{ $sale['payment_status'] }}</td>
                                            <td>{{ $sale['shipping_status'] }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="card-footer bg-white border-0 rounded-3">
                                <div class="d-flex justify-content-between p-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label for="rowsPerPage1" class="col-form-label">Rows per page:</label>
                                        </div>
                                        <div class="col-auto">
                                            <select id="rowsPerPage1" class="form-select border-0">
                                                <option value="3" selected>3</option>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center text-end">
                                        <div class="col-auto">
                                            <p class="subheading col-form-label " id="dataTableInfo1">

                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <div class="new-pagination">
                                                <a class="rounded-start paginate_button" style="cursor: pointer"> ❮ </a>
                                                <a class="rounded-end paginate_button page-item next"
                                                    style="cursor: pointer"> ❯ </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
                            <div class="row my-3">
                                <div class="col-md-3 col-12 mt-2">
                                    <div class="input-search position-relative">
                                        <input type="text" placeholder="Search Sale"
                                            class="form-control rounded-3 subheading" id="custom-filter2" />
                                        <span class="fa fa-search search-icon text-secondary"></span>
                                    </div>
                                </div>

                                <div class="col-md-9 col-12 text-end">
                                    <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i class="bi bi-file-earmark"
                                            id="download-pdf2"></i></a>
                                </div>
                            </div>
                            <table id="example2" class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Supplier</th>
                                        <th>Warehouse</th>
                                        <th>Grand Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                        <th>Shipping Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouse_purchases as $purchase)
                                        <tr>
                                            {{-- <td>{{ $purchase['reference'] }}</td> --}}
                                            <td class="text-primary align-middle"><a class="text-decoration-none"
                                                    href="{{ route('purchases.show', $purchase['id']) }}">{{ $purchase['reference'] }}</a>
                                            </td>
                                            <td>{{ $purchase['vendor'] }}</td>
                                            <td>{{ $purchase['warehouse'] }}</td>
                                            <td>${{ $purchase['grand_total'] }}</td>
                                            <td>${{ $purchase['paid'] }}</td>
                                            <td>${{ $purchase['due'] }}</td>
                                            <td>{{ $purchase['status'] }}</td>
                                            <td>{{ $purchase['payment_status'] }}</td>
                                            <td>{{ $purchase['shipping_status'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="card-footer bg-white border-0 rounded-3">
                                <div class="d-flex justify-content-between p-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label for="rowsPerPage2" class="col-form-label">Rows per page:</label>
                                        </div>
                                        <div class="col-auto">
                                            <select id="rowsPerPage2" class="form-select border-0">
                                                <option value="3" selected>3</option>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center text-end">
                                        <div class="col-auto">
                                            <p class="subheading col-form-label " id="dataTableInfo2">

                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <div class="new-pagination">
                                                <a class="rounded-start paginate_button" style="cursor: pointer"> ❮ </a>
                                                <a class="rounded-end paginate_button page-item next"
                                                    style="cursor: pointer"> ❯ </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="sale-return" role="tabpanel" aria-labelledby="sale-return-tab">
                            <div class="row my-3">
                                <div class="col-md-3 col-12 mt-2">
                                    <div class="input-search position-relative">
                                        <input type="text" placeholder="Search Sale"
                                            class="form-control rounded-3 subheading" id="custom-filter3" />
                                        <span class="fa fa-search search-icon text-secondary"></span>
                                    </div>
                                </div>

                                <div class="col-md-9 col-12 text-end">
                                    <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i class="bi bi-file-earmark"
                                            id="download-pdf3"></i></a>
                                </div>
                            </div>
                            <table id="example3" class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        {{-- //here are params Reference	Customer	Sale RefSort table by Sale Ref in descending order	WarehouseSort table by Warehouse in descending order	Grand Total	Paid	Due	Status	Payment Status --}}
                                        <th>Customer</th>
                                        <th>Warehouse</th>
                                        <th>Grand Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouse_sale_return as $sale_return)
                                        <tr>
                                            <td>{{ $sale_return['date'] }}</td>
                                            <td class="text-primary align-middle"><a class="text-decoration-none"
                                                    href="{{ route('sale-return.detail', $sale_return['id']) }}">{{ $sale_return['reference'] }}</a>
                                            </td>
                                            {{-- <td>{{ $sale_return['reference'] }}</td> --}}
                                            <td>{{ $sale_return['customer'] }}</td>
                                            <td>{{ $sale_return['warehouse'] }}</td>
                                            <td>${{ $sale_return['grand_total'] }}</td>
                                            <td>${{ $sale_return['paid'] }}</td>
                                            <td>${{ $sale_return['due'] }}</td>
                                            <td>{{ $sale_return['status'] }}</td>
                                            <td>{{ $sale_return['payment_status'] }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="card-footer bg-white border-0 rounded-3">
                                <div class="d-flex justify-content-between p-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label for="rowsPerPage3" class="col-form-label">Rows per page:</label>
                                        </div>
                                        <div class="col-auto">
                                            <select id="rowsPerPage3" class="form-select border-0">
                                                <option value="3" selected>3</option>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center text-end">
                                        <div class="col-auto">
                                            <p class="subheading col-form-label " id="dataTableInfo3">

                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <div class="new-pagination">
                                                <a class="rounded-start paginate_button" style="cursor: pointer"> ❮ </a>
                                                <a class="rounded-end paginate_button page-item next"
                                                    style="cursor: pointer"> ❯ </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="purchase-return" role="tabpanel"
                            aria-labelledby="purchase-return-tab">
                            <div class="row my-3">
                                <div class="col-md-3 col-12 mt-2">
                                    <div class="input-search position-relative">
                                        <input type="text" placeholder="Search Sale"
                                            class="form-control rounded-3 subheading" id="custom-filter4" />
                                        <span class="fa fa-search search-icon text-secondary"></span>
                                    </div>
                                </div>

                                <div class="col-md-9 col-12 text-end">
                                    <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i class="bi bi-file-earmark"
                                            id="download-pdf4"></i></a>
                                </div>
                            </div>
                            <table id="example4" class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Vendor</th>
                                        <th>Warehouse</th>
                                        <th>Grand Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouse_purchase_return as $purchase_return)
                                        <tr>
                                            <td>{{ $purchase_return['date'] }}</td>
                                            <td class="text-primary align-middle"><a class="text-decoration-none"
                                                    href="{{ route('purchase-return.detail', $purchase_return['id']) }}">{{ $purchase_return['reference'] }}</a>
                                            </td>
                                            {{-- <td>{{ $purchase_return['reference'] }}</td> --}}
                                            <td>{{ $purchase_return['vendor'] }}</td>
                                            <td>{{ $purchase_return['warehouse'] }}</td>
                                            <td>${{ $purchase_return['grand_total'] }}</td>
                                            <td>${{ $purchase_return['paid'] }}</td>
                                            <td>${{ $purchase_return['due'] }}</td>
                                            <td>{{ $purchase_return['status'] }}</td>
                                            <td>{{ $purchase_return['payment_status'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            <div class="card-footer bg-white border-0 rounded-3">
                                <div class="d-flex justify-content-between p-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label for="rowsPerPage4" class="col-form-label">Rows per page:</label>
                                        </div>
                                        <div class="col-auto">
                                            <select id="rowsPerPage4" class="form-select border-0">
                                                <option value="3" selected>3</option>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center text-end">
                                        <div class="col-auto">
                                            <p class="subheading col-form-label " id="dataTableInfo4">

                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <div class="new-pagination">
                                                <a class="rounded-start paginate_button" style="cursor: pointer"> ❮ </a>
                                                <a class="rounded-end paginate_button page-item next"
                                                    style="cursor: pointer"> ❯ </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="expense" role="tabpanel" aria-labelledby="expense-tab">
                            <div class="row my-3">
                                <div class="col-md-3 col-12 mt-2">
                                    <div class="input-search position-relative">
                                        <input type="text" placeholder="Search Sale"
                                            class="form-control rounded-3 subheading" id="custom-filter5" />
                                        <span class="fa fa-search search-icon text-secondary"></span>
                                    </div>
                                </div>

                                <div class="col-md-9 col-12 text-end">
                                    <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i class="bi bi-file-earmark"
                                            id="download-pdf5"></i></a>
                                </div>
                            </div>
                            <table id="example5" class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Category</th>
                                        <th>Warehouse</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouse_expense as $expense)
                                        <tr>
                                            <td>{{ $expense['date'] }}</td>
                                            <td>{{ $expense['reference'] }}</td>
                                            <td>{{ $expense['category'] }}</td>
                                            <td>{{ $expense['warehouse'] }}</td>
                                            <td>${{ $expense['amount'] }}</td>
                                            <td>{{ $expense['description'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            <div class="card-footer bg-white border-0 rounded-3">
                                <div class="d-flex justify-content-between p-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label for="rowsPerPage5" class="col-form-label">Rows per page:</label>
                                        </div>
                                        <div class="col-auto">
                                            <select id="rowsPerPage5" class="form-select border-0">
                                                <option value="3" selected>3</option>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center text-end">
                                        <div class="col-auto">
                                            <p class="subheading col-form-label " id="dataTableInfo5">

                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <div class="new-pagination">
                                                <a class="rounded-start paginate_button" style="cursor: pointer"> ❮ </a>
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

            <div class="row mt-2">
                <div class="col-md-6 col-12 mt-4">
                    <div class="card-shadow text-center rounded p-4 card border-0 h-100">
                        <h4 class="mb-0 mt-2 heading text-start card-title">
                            Total Quantity
                        </h4>
                        <div class="card-body h-100">
                            <canvas id="Chart1" style="width: 100%; max-width: 600px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 mt-4">
                    <div class="card-shadow text-center rounded p-4 card border-0 h-100">
                        <h4 class="mb-0 mt-2 heading text-start card-title">
                            Sales Grand Total
                        </h4>
                        <div class="card-body h-100">
                            <canvas id="Chart2" style="width: 100%; max-width: 600px"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endsection

    @section('scripts')
        <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            $(document).ready(function() {
                $('input[name="dates"]').daterangepicker();

                //on click .applyBtn ajax call for filter data of warehouses between date range
                $('#warehouses').on('change', function() {
                    console.log('change');
                    let warehouse_id = $(this).val();
                    console.log(warehouse_id);
                    let baseUrl = "{{ route('warehouse.filter') }}";

                    let fullUrl = `${baseUrl}?id=${warehouse_id}`;
                    // using ajax to get data for the selected warehouse
                    $.ajax({
                        url: fullUrl,
                        type: 'GET',
                        success: function(data) {
                            console.log(data);
                            $('.sales-amount').text(data.data.total_sales);
                            $('.purchase-amount').text(data.data.total_purchase);
                            $('.sale-return-amount').text(data.data.total_sale_return);
                            $('.purchase-return-amount').text(data.data.total_purchase_return);

                            //update sales table
                            let table1 = $('#example1').DataTable();
                            table1.clear().draw();
                            data.data.warehouse_sales.forEach(sale => {
                                table1.row.add([
                                    sale.reference,
                                    sale.customer,
                                    sale.warehouse,
                                    sale.grand_total,
                                    sale.paid,
                                    sale.due,
                                    sale.status,
                                    sale.payment_status,
                                    sale.shipping_status
                                ]);
                            });
                            table1.draw();

                            //update purchases table
                            let table2 = $('#example2').DataTable();
                            table2.clear().draw();
                            data.data.warehouse_purchases.forEach(purchase => {
                                table2.row.add([
                                    purchase.reference,
                                    purchase.vendor,
                                    purchase.warehouse,
                                    purchase.grand_total,
                                    purchase.paid,
                                    purchase.due,
                                    purchase.status,
                                    purchase.payment_status,
                                    purchase.shipping_status
                                ]);
                            });
                            table2.draw();

                            //update sale return table
                            let table3 = $('#example3').DataTable();
                            table3.clear().draw();
                            data.data.warehouse_sale_return.forEach(sale_return => {
                                table3.row.add([
                                    sale_return.date,
                                    sale_return.reference,
                                    sale_return.customer,
                                    sale_return.warehouse,
                                    sale_return.grand_total,
                                    sale_return.paid,
                                    sale_return.due,
                                    sale_return.status,
                                    sale_return.payment_status
                                ]);
                            });
                            table3.draw();

                            //update purchase return table
                            let table4 = $('#example4').DataTable();
                            table4.clear().draw();
                            data.data.warehouse_purchase_return.forEach(purchase_return => {
                                table4.row.add([
                                    purchase_return.date,
                                    purchase_return.reference,
                                    purchase_return.vendor,
                                    purchase_return.warehouse,
                                    purchase_return.grand_total,
                                    purchase_return.paid,
                                    purchase_return.due,
                                    purchase_return.status,
                                    purchase_return.payment_status
                                ]);
                            });
                            table4.draw();

                            //update expense table
                            let table5 = $('#example5').DataTable();
                            table5.clear().draw();
                            data.data.warehouse_expense.forEach(expense => {
                                table5.row.add([
                                    expense.date,
                                    expense.reference,
                                    expense.category,
                                    expense.warehouse,
                                    expense.amount,
                                    expense.description
                                ]);
                            });
                            table5.draw();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                });

                for (let i = 1; i <= 5; i++) {
                    console.log(`#custom-filter${i}`)
                    let table = $(`#example${i}`).DataTable({
                        dom: 'Bfrtip',
                        buttons: [{
                            extend: 'pdf',
                            footer: true,
                            exportOptions: {
                                columns: ':visible' // Include all visible columns
                            }
                        }, ]
                    });

                    $(`#custom-filter${i}`).keyup(function() {
                        table.search(this.value).draw();
                    });

                    $(`#download-pdf${i}`).on('click', function() {
                        table.button('.buttons-pdf').trigger();
                    });

                    // // Custom pagination events
                    $('.new-pagination .paginate_button').on('click', function() {
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
                        $(`#dataTableInfo${i}`).text('Showing ' + startRecord + '-' + endRecord + ' of ' +
                            totalRecords + ' entries');
                    });

                    table.draw();

                }

            });
        </script>


        <!-- Template Javascript -->
        <script src="dasheets/js/main.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

        {{-- <script>
        var xValues = [];
        var yValues = [];
        var barColors = [];

        @foreach ($warehouseData as $data)
            xValues.push("{{ $data['label'] }}");
            yValues.push("{{ $data['data'] }}");
            // yValues.push({{ json_encode($data['data']) }});
            // barColors.push("rgba({{ rand(0, 255) }}, {{ rand(0, 255) }}, {{ rand(0, 255) }}, 1)");
        @endforeach

        new Chart("Chart1", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [
                    {
                        backgroundColor: barColors,
                        data: yValues,
                    },
                ],
            },
            // options: {
            //     title: {
            //         display: true,
            //         text: "World Wide Wine Production 2018"
            //     }
            // }
        });
    </script> --}}
        {{--
    <script>
        var warehouseData = @json($warehouseData);
        // var ctx = document.getElementById('warehouseChart').getContext('2d');

        var chart = new Chart("Chart1", {
            type: 'pie',
            data: {
                labels: warehouseData.map(data => data.label),
                datasets: [{
                    backgroundColor: 'rgba(98, 95, 237, 1)',
                    data: warehouseData.map(data => data.data[0]) // Total quantity data
                }]
            }
        });
    </script> --}}
        <script>
            var warehouseData = @json($warehouseData);
            var labels = warehouseData.map(data => data.label);
            var totalQuantities = warehouseData.map(data => data.data[0]); // Total quantity data
            // console.log(totalQuantities)
            var totalItems = warehouseData.map(data => data.data[1]); // Total items data
            // var quantity =
            // var ctx = document.getElementById('warehouseChart').getContext('2d');

            var chart = new Chart("Chart1", {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Quantity',
                        backgroundColor: '#FE9F43',
                        data: totalQuantities
                    }, ]
                },
                options: {
                    responsive: true,
                    // Additional options for customization (optional)
                    title: {
                        display: true,
                        text: 'Warehouse Total Quantity'
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            });
        </script>

        <script>
            var warehouseData = @json($warehouseData);

            var chart = new Chart("Chart2", {
                type: 'pie',
                data: {
                    labels: warehouseData.map(data => data.label),
                    datasets: [{
                        backgroundColor: '#FE9F43',
                        data: warehouseData.map(data => data.data[2].toFixed(1)) // Total quantity data
                    }]
                },
                options: {
                    responsive: true,
                    // Additional options for customization (optional)
                    title: {
                        display: true,
                        text: 'Warehouse Sales Distribution'
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            });
        </script>


        {{-- <script>
      var xValues = ["Warehourse 1", "Warehourse 2"];
      var yValues = [50, 50];
      var barColors = ["rgba(98, 95, 237, 1)", "rgba(155, 153, 243, 1)"];

      new Chart("Chart1", {
        type: "pie",
        data: {
          labels: xValues,
          datasets: [
            {
              backgroundColor: barColors,
              data: yValues,
            },
          ],
        },
        //   options: {
        //     title: {
        //       display: true,
        //       text: "World Wide Wine Production 2018"
        //     }
        //   }
      });
    </script> --}}

        {{-- <script>
      var xValues = ["Warehourse 1", "Warehourse 2"];
      var yValues = [30, 50];
      var barColors = ["rgba(98, 95, 237, 1)", "rgba(155, 153, 243, 1)"];

      new Chart("Chart2", {
        type: "pie",
        data: {
          labels: xValues,
          datasets: [
            {
              backgroundColor: barColors,
              data: yValues,
            },
          ],
        },
      });
    </script> --}}

    @endsection
