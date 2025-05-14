@extends('back.layout.app')
@section('title', 'Stocks List')
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
                <h3 class="all-adjustment text-center pb-2 mb-0">Stock Report</h3>
            </div>

            @include('back.layout.errors')
            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="mb-4 col-lg-12">
                        <h3 class="text-center">{{ $stock_show->product->name }}</h3>
                    </div>
                    <div class="col-md-5">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Warehouse</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stock_warehouse as $warehouse)
                                    {{-- {{dd($warehouse)}} --}}
                                    <tr>
                                        <td>{{ $warehouse['warehouse'] }}</td>
                                        <td>{{ $warehouse['quantity'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                            <button class="nav-link" id="transfer-return-tab" data-bs-toggle="tab"
                                data-bs-target="#transfer-return" type="button" role="tab"
                                aria-controls="transfer-return" aria-selected="false">Transfer</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="inventory-return-tab" data-bs-toggle="tab"
                                data-bs-target="#inventory-return" type="button" role="tab"
                                aria-controls="inventory-return" aria-selected="false">Inventory</button>
                        </li>
                    </ul>

                    {{-- and add table in their content --}}
                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade show active" id="sales" role="tabpanel" aria-labelledby="sales-tab">
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
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stock_sale as $sale)
                                        {{-- {{dd($sale)}} --}}
                                        <tr>
                                            {{-- <td>{{ $sale['reference'] }}</td> --}}
                                            <td class="text-primary align-middle"><a class="text-decoration-none" href="{{route('sales.show', $sale['id'])}}">{{ $sale['reference'] }}</a></td>
                                            <td>{{ $sale['customer'] }}</td>
                                            <td>{{ $sale['warehouse'] }}</td>
                                            <td>{{ $sale['quantity'] }}</td>
                                            <td>${{ $sale['price'] }}</td>
                                            <td>${{ $sale['sub_total'] }}</td>
                                            <td>{{ $sale['date'] }}</td>
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
                                        <input type="text" placeholder="Search Purchase"
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
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stock_purchase as $purchase)
                                        <tr>
                                            {{-- <td>{{ $purchase['reference'] }}</td> --}}
                                            <td class="text-primary align-middle"><a class="text-decoration-none" href="{{route('purchases.show', $purchase['id'])}}">{{ $purchase['reference'] }}</a></td>
                                            <td>{{ $purchase['vendor'] }}</td>
                                            <td>{{ $purchase['warehouse'] }}</td>
                                            <td>{{ $purchase['quantity'] }}</td>
                                            <td>${{ $purchase['price'] }}</td>
                                            <td>${{ $purchase['sub_total'] }}</td>
                                            <td>{{ $purchase['date'] }}</td>
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
                                        <input type="text" placeholder="Search Sale Return"
                                            class="form-control rounded-3 subheading" id="custom-filter3" />
                                        <span class="fa fa-search search-icon text-secondary"></span>
                                    </div>
                                </div>

                                <div class="col-md-9 col-12 text-end">
                                    <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i
                                            class="bi bi-file-earmark" id="download-pdf3"></i></a>
                                </div>
                            </div>
                            <table id="example3" class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Supplier</th>
                                        <th>Warehouse</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stock_sale_return as $sale)
                                        <tr>
                                            {{-- <td>{{ $sale['reference'] }}</td> --}}
                                            <td class="text-primary align-middle"><a class="text-decoration-none" href="{{route('sale-return.detail', $sale['id'])}}">{{ $sale['reference'] }}</a></td>
                                            <td>{{ $sale['customer'] }}</td>
                                            <td>{{ $sale['warehouse'] }}</td>
                                            <td>{{ $sale['quantity'] }}</td>
                                            <td>${{ $sale['price'] }}</td>
                                            <td>${{ $sale['sub_total'] }}</td>
                                            <td>{{ $sale['date'] }}</td>
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
                        <div class="tab-pane fade" id="purchase-return" role="tabpanel" aria-labelledby="purchase-return-tab">
                            <div class="row my-3">
                                <div class="col-md-3 col-12 mt-2">
                                    <div class="input-search position-relative">
                                        <input type="text" placeholder="Search Purchase Return"
                                            class="form-control rounded-3 subheading" id="custom-filter4" />
                                        <span class="fa fa-search search-icon text-secondary"></span>
                                    </div>
                                </div>
                                <div class="col-md-9 col-12 text-end">
                                    <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i class="bi bi-file-earmark"
                                            id="download-pdf4"></i></a>
                                </div>
                            </div>
                            <table id="example" class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Supplier</th>
                                        <th>Warehouse</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($stock_purchase_return as $purchase)
                                        <tr>
                                            <td class="text-primary align-middle"><a class="text-decoration-none" href="{{route('purchase-return.detail', $purchase['id'])}}">{{ $purchase['reference'] }}</a></td>
                                            <td>{{ $purchase['vendor'] }}</td>
                                            <td>{{ $purchase['warehouse'] }}</td>
                                            <td>{{ $purchase['quantity'] }}</td>
                                            <td>${{ $purchase['price'] }}</td>
                                            <td>${{ $purchase['sub_total'] }}</td>
                                            <td>{{ $purchase['date'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No data available</td>
                                        </tr>
                                    @endforelse
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
                        <div class="tab-pane fade" id="transfer-return" role="tabpanel"
                            aria-labelledby="transfer-return-tab">
                            <div class="row my-3">
                                <div class="col-md-3 col-12 mt-2">
                                    <div class="input-search position-relative">
                                        <input type="text" placeholder="Search Transfer"
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
                                        <th>From Warehouse</th>
                                        <th>To Warehouse</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($stock_transfer as $transfer)
                                        <tr>
                                            <td>{{ $transfer['date'] }}</td>
                                            <td>{{ $transfer['reference'] }}</td>
                                            <td>{{ $transfer['from_warehouse'] }}</td>
                                            <td>{{ $transfer['to_warehouse'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="inventory-return" role="tabpanel"
                            aria-labelledby="inventory-return-tab">
                            <div class="row my-3">
                                <div class="col-md-3 col-12 mt-2">
                                    <div class="input-search position-relative">
                                        <input type="text" placeholder="Search Purchase Return"
                                            class="form-control rounded-3 subheading" id="custom-filter6" />
                                        <span class="fa fa-search search-icon text-secondary"></span>
                                    </div>
                                </div>
                                <div class="col-md-9 col-12 text-end">
                                    <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i class="bi bi-file-earmark"
                                            id="download-pdf6"></i></a>
                                </div>
                            </div>
                            <table id="example6" class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Product</th>
                                        <th>Warehouse</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($stock_inventory as $inventory)
                                        <tr>
                                            <td>{{ $inventory['date'] }}</td>
                                            <td>{{ $inventory['reference'] }}</td>
                                            <td>{{ $inventory['product'] }}</td>
                                            <td>{{ $inventory['warehouse'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                            <div class="card-footer bg-white border-0 rounded-3">
                                <div class="d-flex justify-content-between p-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label for="rowsPerPage6" class="col-form-label">Rows per page:</label>
                                        </div>
                                        <div class="col-auto">
                                            <select id="rowsPerPage6" class="form-select border-0">
                                                <option value="3" selected>3</option>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center text-end">
                                        <div class="col-auto">
                                            <p class="subheading col-form-label " id="dataTableInfo6">

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

                    {{-- <div class="card-footer bg-white border-0 rounded-3">
                    <div class="d-flex justify-content-between p-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <label for="rowsPerPage" class="col-form-label">Rows per page:</label>
                            </div>
                            <div class="col-auto">
                                <select id="rowsPerPage" class="form-select border-0">
                                    <option value="3" selected>3</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                </select>
                            </div>
                        </div>
                        <div class="row align-items-center text-end">
                            <div class="col-auto">
                                <p class="subheading col-form-label " id="dataTableInfo">

                                </p>
                            </div>
                            <div class="col-auto">
                                <div class="new-pagination">
                                    <a class="rounded-start paginate_button" style="cursor: pointer"> ❮ </a>
                                    <a class="rounded-end paginate_button page-item next" style="cursor: pointer"> ❯ </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
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

                for (let i = 1; i <= 6; i++) {
                    // console.log(`#custom-filter${i}`)
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

    @endsection
