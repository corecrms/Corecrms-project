@extends('back.layout.app')
@section('title', 'Products')
@section('style')

    </style>
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        .ui-autocomplete {
            padding: 0 !important;
        }

        .ui-menu .ui-menu-item-wrapper {
            text-align: left;
        }

        .ui-menu {
            width: 221px !important;
            max-height: 320px !important;
            overflow-y: scroll !important;
            overflow-x: hidden !important;
        }

        /* Customize the scrollbar */
        .ui-menu::-webkit-scrollbar {
            width: 10px;
            /* Width of the scrollbar */
        }

        /* Track */
        .ui-menu::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Color of the track */
        }

        /* Handle */
        .ui-menu::-webkit-scrollbar-thumb {
            background: #888;
            /* Color of the scrollbar handle */
        }

        /* Handle on hover */
        .ui-menu::-webkit-scrollbar-thumb:hover {
            background: #555;
            /* Color of the scrollbar handle on hover */
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
    <div class="content ">




        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Stock Count</h3>
            </div>
            @if (session('success'))
                <div class="mt-2 alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                @if (is_array(session('error')))
                    {{-- If the error is an array, loop through it --}}
                    @foreach (session('error') as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ $error }}.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endforeach
                @else
                    {{-- If the error is a string, display it normally --}}
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 card-shadow rounded-3 p-2 mt-4">
                <div class="card-header border-0 bg-white">

                    <div class="row my-3">
                        <div class="col-md-3 col-12 mt-2">
                            <input type="text" placeholder="Search Product"
                            class="form-control rounded-3 subheading" id="custom-filter" name="search" />

                        </div>

                        <div class="col-md-9 col-12 text-end">

                            <a href="{{ route('inventory-stock-count') }}" class="btn create-btn rounded-3 mt-2">Count By Warehouse </a>

                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <div class="alert alert-danger p-2 text-end" id="deletedAlert" style="display: none">
                        <div style="display: flex;justify-content:space-between">
                            <span><span id="deleteRowCount">0</span> rows selected</span>
                            <button class="btn btn-sm btn-danger" id="deleteRowTrigger">Delete</button>
                        </div>
                    </div>
                    <table class="table " id="example">
                        <thead class="fw-bold">
                            <tr>
                                <th>
                                    #
                                </th>
                                <th class="text-secondary">Image</th>
                                <th class="text-secondary">Name</th>
                                <th class="text-secondary">SKU</th>
                                <th class="text-secondary">Product Type</th>
                                <th class="text-secondary">Category</th>
                                <th class="text-secondary">Brand</th>
                                <th class="text-secondary">Bin Location</th>
                                <th class="text-secondary">Cost</th>
                                <th class="text-secondary">Price</th>
                                <th class="text-secondary">Unit</th>
                                <th class="text-secondary">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td class="align-middle">
                                        {{$loop->iteration ?? ''}}
                                    </td>

                                    <td class="align-middle">
                                        @if (count($product->images) > 0)
                                            <img src="{{ asset('/storage' . $product->images[0]['img_path']) }}"
                                                alt="No" style="width: 70px">
                                            <span
                                                class="badge bg-primary rounded-pill">+{{ count($product->images) - 1 }}</span>
                                        @else
                                            <img src="{{ asset('back/assets/image/no-image.png') }}" alt=""
                                                class="" style=" width: 70px" />
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {{ $product->name }}
                                    </td>
                                    <td class="align-middle">{{ $product->sku ?? '' }}</td>
                                    <td class="align-middle">{{ $product->product_type ?? '....' }}</td>
                                    <td class="align-middle">{{ $product->category->name }}</td>
                                    <td class="align-middle">{{ $product->brand->name ?? '' }}</td>
                                    <td class="align-middle">
                                        {{ $product->ailse ?? '' }}-{{ $product->rack ?? '' }}-{{ $product->shelf ?? '' }}-{{ $product->bin ?? '' }}
                                    </td>
                                    <td class="align-middle">${{ $product->purchase_price ?? '....'}}</td>
                                    <td class="align-middle">${{ $product->sell_price }}</td>
                                    <td class="align-middle">{{ $product->unit->short_name ?? '....' }}</td>
                                    {{-- <td class="align-middle">{{ $product->product_warehouses->sum('quantity') ?? '0' }}</td> --}}

                                    <td class="align-middle">
                                        @if (auth()->user()->hasRole(['Cashier', 'Manager']))
                                            {{ $product->product_warehouses->where('warehouse_id', auth()->user()->warehouse_id)->first()->quantity ?? '0' }}
                                        @elseif (session('selected_warehouse_id'))
                                            {{ $product->product_warehouses->where('warehouse_id', session('selected_warehouse_id'))->first()->quantity ?? '0' }}
                                        @else
                                            {{ $product->product_warehouses->sum('quantity') ?? '0' }}
                                        @endif
                                    </td>

                                </tr>
                            @empty
                            @endforelse

                        </tbody>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>



        $(document).ready(function() {

            // var table = $('#example').DataTable({
            //     dom: 'Bfrtip',
            //     select: true,
            //     select: {
            //         style: 'multi'
            //     },
            //     buttons: [{
            //             extend: 'pdf',
            //             footer: true,
            //             exportOptions: {
            //                 columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
            //             }
            //         },
            //         {
            //             extend: 'csv',
            //             footer: false,
            //             exportOptions: {
            //                 columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
            //             }

            //         },
            //         {
            //             extend: 'excel',
            //             footer: false,
            //             exportOptions: {
            //                 columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
            //             }
            //         }
            //     ]
            // });

            // // Select all checkbox click handler
            // $('#myCheckbox09').on('click', function() {
            //     var isSelected = $(this).is(':checked'); // Check if checkbox is checked

            //     // Select/deselect all checkboxes with class 'select-checkbox'
            //     $('.select-checkbox').prop('checked', isSelected);

            //     // Optional: Update DataTables selection based on checkbox state
            //     if (isSelected) {
            //         table.rows().select(); // Select all rows in DataTables (adjust if needed)
            //         // confirm('Are you sure you want to delete all record?');
            //         $('#deletedAlert').css('display', 'block');
            //         $('#deleteRowCount').text($('.deleteRow:checked').length);


            //     } else {
            //         table.rows().deselect(); // Deselect all rows in DataTables (adjust if needed)
            //         $('#deletedAlert').css('display', 'none');
            //     }
            // });

            // table.on('select.dt', function(e, dt, type, indexes) {
            //     // console.log("slected")
            //     var row = table.row(indexes[0]); // Get the selected row

            //     // Find checkbox within the selected row
            //     var checkbox = row.node().querySelector('.select-checkbox');

            //     if (checkbox) { // Check if checkbox exists
            //         // console.log("slected")
            //         checkbox.checked = true; // Check the checkbox
            //         $('#deletedAlert').css('display', 'block');
            //         $('#deleteRowCount').text($('.deleteRow:checked').length);

            //     }
            // });

            // table.on('deselect.dt', function(e, dt, type, indexes) {
            //     var selectedRows = table.rows('.selected').count();
            //     var row = table.row(indexes[0]); // Get the selected/deselected row
            //     var checkbox = row.node().querySelector('.select-checkbox');

            //     if (checkbox) {
            //         // Update checkbox state based on event type
            //         checkbox.checked = type === 'select';
            //     }
            //     $ // Show/hide delete alert based on selection count
            //     if (selectedRows === 0) {
            //         $('#deletedAlert').css('display', 'none');
            //     } else {
            //         $('#deletedAlert').css('display', 'block');
            //         $('#deleteRowCount').text($('.deleteRow:checked').length);
            //     }
            // });


            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                select: {
                    style: 'multi',
                    selector: 'td:first-child .select-checkbox' // Add this line to restrict row selection to the first cell
                },
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'csv',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }

                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    }
                ]
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
            $(".delete-product-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this Product?");
                if (decision) {
                    return true;
                }
                return false;
            });
        });
    </script>
@endsection
