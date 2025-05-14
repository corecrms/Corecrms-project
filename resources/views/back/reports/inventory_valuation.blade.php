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
                <h3 class="all-adjustment text-center pb-2 mb-0">Inventory Valuation</h3>
            </div>

            @include('back.layout.errors')

            <div class="card border-0 card-shadow rounded-3 p-2 mt-5">
                <div class="card-header border-0 bg-white">
                    <div class="row my-3">
                        <div class="col-md-3 col-12 mt-2">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search" class="form-control rounded-3 subheading"
                                    id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-9 col-12 text-end">
                            {{-- <a href="#" class="btn create-btn rounded-3 mt-2">Filter <i class="bi bi-funnel"></i></a> --}}
                            <a href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">
                                Excel <i class="bi bi-file-earmark-text"></i></a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf">Pdf <i
                                    class="bi bi-file-earmark"></i></a>
                            {{-- <a href="{{ route('stocks.create') }}" class="btn create-btn rounded-3 mt-2">Create <i
                                    class="bi bi-plus-lg"></i></a> --}}
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="example">
                        <thead>
                            <tr>
                                <th class="text-secondary">Item Name</th>
                                <th class="text-secondary">Sku</th>
                                <th class="text-secondary">Stock On Hand</th>
                                <th class="text-secondary">Asset Value</th>
                            </tr>
                        </thead>
                    
                        <tbody>
                            @foreach ($inventories as $inventory)
                                <tr>
                                    <td class="align-middle">{{ $inventory['name'] }}</td>
                                    <td class="align-middle">{{ $inventory['sku'] }}</td>
                                    <td class="align-middle total_stock">{{ $inventory['total_stock'] }}</td>
                                    <td class="align-middle asset_value">${{ $inventory['total_price'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <!-- Add a footer section to display the totals -->
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">Total:</th>
                                <th id="total_stock_sum"></th>
                                <th id="total_price_sum"></th>
                            </tr>
                        </tfoot>
                    </table>
                    
                
                </div>

                <div class="card-footer bg-white border-0 rounded-3">
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

            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                paging: true,
                buttons: [
                    'pdf', 'excel',
                ],
            });

            $('#custom-filter').keyup(function() {
                table.search(this.value).draw();
            });

            $('#download-pdf').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });
            $('#download-excel').on('click', function() {
                table.button('.buttons-excel').trigger();
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
            $('#rowsPerPage').on('change', function() {
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

                $('#rowsPerPage').val(table.page.len());
                $('#dataTableInfo').text('Showing ' + startRecord + '-' + endRecord + ' of ' +
                    totalRecords + ' entries');
            });

            table.draw();

        });
        $(document).ready(function() {
            // Function to calculate the sum of specific column values
            function calculateColumnSum(selector) {
                let sum = 0;
                $(selector).each(function() {
                    sum += parseFloat($(this).text()) || 0;
                });
                return sum;
            }

            // Calculate totals when the page is loaded
            function updateTableFooter() {
                // Calculate the sum of the total stock column
                let totalStockSum = calculateColumnSum('.total_stock');

                // Calculate the sum of the asset value column (remove $ sign before summing)
                let totalPriceSum = 0;
                $('.asset_value').each(function() {
                    let price = parseFloat($(this).text().replace('$', '')) || 0;
                    totalPriceSum += price;
                });

                // Update the footer cells with calculated sums
                $('#total_stock_sum').text(totalStockSum);
                $('#total_price_sum').text('$' + totalPriceSum.toFixed(2));
            }

            // Update the footer totals initially and whenever the table data changes
            updateTableFooter();

            // If using pagination or filtering with DataTables, update the totals on redraw
            $('#example').on('draw.dt', function() {
                updateTableFooter();
            });
        });
    </script>

@endsection
