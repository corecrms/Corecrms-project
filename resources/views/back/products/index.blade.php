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
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">All Products</h3>
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
                            <form action="{{ route('products.index') }}" method="GET" class="d-flex ">
                                <div class="input-search position-relative">
                                    <input type="text" placeholder="Search Product"
                                        class="form-control rounded-3 subheading" id="searchInput" name="search"
                                        value="{{ request()->get('search') ?? '' }}" />
                                    <span class="fa fa-search search-icon text-secondary"></span>
                                </div>
                                <div id="suggestionsContainer"></div>
                                <button class="btn save-btn btn-sm text-white  ms-2 " type="submit">Search</button>
                            </form>

                        </div>

                        <div class="col-md-9 col-12 text-end">
                            <a href="#" class="btn create-btn rounded-3 mt-2" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
                                aria-controls="staticBackdrop">Filter <i class="bi bi-funnel"></i></a>
                            <a href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">Excel <i
                                    class="bi bi-file-earmark-text"></i></a>
                            <a href="{{ route('export-products') }}" class="btn import-customer-btn rounded-3 mt-2">
                                Export File
                            </a>
                            <a href="{{ route('import-products') }}" class="btn import-customer-btn rounded-3 mt-2"
                                data-bs-toggle="modal" data-bs-target="#importfilemodal">
                                Import File
                            </a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf">Pdf <i
                                    class="bi bi-file-earmark"></i></a>
                            @can('products-create')
                                <a href="{{ route('products.create') }}" class="btn create-btn rounded-3 mt-2">Create <i
                                        class="bi bi-plus-lg"></i></a>
                            @endcan

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
                                {{-- <th>
                                    <label for="myCheckbox09" class="checkbox">
                                        <input class="checkbox__input" type="checkbox" id="myCheckbox09" />
                                        <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22">
                                            <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                stroke="rgba(76, 73, 227, 1)" rx="3" />
                                            <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                        </svg>
                                    </label>
                                </th> --}}
                                <th class="text-secondary">Image</th>
                                <th class="text-secondary">Name</th>
                                <th class="text-secondary">SKU</th>
                                <th class="text-secondary">Product Type</th>
                                <th class="text-secondary">Category</th>
                                <th class="text-secondary">Brand</th>
                                <th class="text-secondary">Cost</th>
                                <th class="text-secondary">Price</th>
                                <th class="text-secondary">Unit</th>
                                <th class="text-secondary">Qty</th>
                                {{-- <th class="text-secondary">Status</th> --}}

                                <th class="text-secondary">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @forelse ($products as $product)
                                <tr>
                                    <td class="align-middle">
                                        <label for="select-checkbox" class="checkbox">
                                            <input class="checkbox__input select-checkbox deleteRow" type="checkbox"
                                                id="select-checkbox" data-id="{{ $product->id }}" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
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
                                    <td class="align-middle">${{ $product->purchase_price ?? '....' }}</td>
                                    <td class="align-middle">${{ $product->sell_price }}</td>
                                    <td class="align-middle">{{ $product->unit->short_name ?? '....' }}</td>
                                    <td class="align-middle">
                                        @if (auth()->user()->hasRole(['Cashier', 'Manager']))
                                            {{ $product->product_warehouses->where('warehouse_id', auth()->user()->warehouse_id)->first()->quantity ?? '0' }}
                                        @elseif (session('selected_warehouse_id'))
                                            {{ $product->product_warehouses->where('warehouse_id', session('selected_warehouse_id'))->first()->quantity ?? '0' }}
                                        @else
                                            {{ $product->product_warehouses->sum('quantity') ?? '0' }}
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="">
                                            <a class="btn btn-secondary bg-transparent border-0 text-dark" role="button"
                                                id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                @can('products-show')
                                                    <a class="dropdown-item"
                                                        href="{{ route('get-one-product-details', $product->id) }}">
                                                        <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                            class="img-fluid me-1" alt="" />
                                                        Product Detail
                                                    </a>
                                                @endcan
                                                @can('products-edit')
                                                    <a class="dropdown-item"
                                                        href="{{ route('products.edit', $product->id) }}">
                                                        <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                            class="img-fluid me-1" alt="" />
                                                        Edit Product
                                                    </a>
                                                @endcan
                                                @can('products-delete')
                                                    <form class=""
                                                        action="{{ route('products.destroy', $product->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item confirm-text">
                                                            <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                                class="img-fluid me-1" alt="">
                                                            Delete Product
                                                        </button>
                                                    </form>
                                                @endcan
                                                <a class="dropdown-item"
                                                    href="{{ route('products.duplicate', $product->id) }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                        class="img-fluid me-1" alt="" />
                                                    Duplicate Product
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse --}}

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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel"
        style="width: 20rem">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="staticBackdropLabel">Filter</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('products.filter') }}" method="POST">
                @csrf
                @method('POST')
                <div>
                    <div class="form-group">
                        <label for="product_code">Product Code</label>
                        <input type="text" class="form-control mt-2" name="code" id="product_code"
                            placeholder="Code">
                    </div>
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control mt-2" name="name" id="product_name"
                            placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control form-select subheading mt-1" aria-label="Default select example"
                            name="category_id" id="category_id">
                            <option value="0">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="brand_id">Brand</label>
                        <select class="form-control form-select subheading mt-1" aria-label="Default select example"
                            name="brand_id" id="brand_id">
                            <option value="0">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn save-btn text-white mt-3" type="submit">Filter <i
                            class="bi bi-funnel"></i></button>

                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="importfilemodal" tabindex="-1" aria-labelledby="importfilemodalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('import-products') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title modal_header_import d-flex align-items-center" id="importfilemodalLabel">
                            <span class="me-1">
                                <i class="far fa-file-alt"></i>
                            </span>
                            Import a file
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="file_upload_modal">

                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupFile01">

                                    <div class="icons">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="click">
                                        <span class="button">Click to upload</span>
                                        <span>or drag and drop</span>
                                    </div>
                                    <span class="max_span ">.xlsx(max 100Mb)</span>

                                </label>
                                <input type="file" name="file" class="form-control" id="inputGroupFile01">
                            </div>

                            <div class="upload_field d-none">
                                <div><i class="fas fa-file-download"></i></div>
                                <div>
                                    <p>Upload failed, please try again</p>
                                    <span>Vendor_List.xlsx</span>
                                    <p>Try again</p>
                                </div>

                            </div>
                            <ul class="">
                                <li class="error">
                                    <span><i class="fas fa-info-circle"></i></span>
                                    <span> File format should be like sample file. <br> * fields are mandatory
                                        <a href="{{ asset('sample-files/products.xlsx') }}" class="text-primary"
                                            download>Download sample file</a>
                                    </span>
                                </li>
                            </ul>
                            <ul class="d-none">
                                <li class="error">
                                    <span><i class="fas fa-times"></i></span>
                                    <span>File Must be less then 100 MB </span>
                                </li>
                                <li class="">
                                    <span><i class="fas fa-check"></i></span>
                                    <span> File format should be: xlsx</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-lg-between ">
                        <button type="button" class="btn  " data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary theme_btn_blue">
                            <i class="fas fa-paper-plane me-1"></i>Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

@endsection

@section('scripts')
    <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const searchInput = document.getElementById("searchInput");

            var suggestionsContainer = $("#suggestionsContainer");
            $("#searchInput").autocomplete({
                source: function(request, response) {
                    var searchTerm = request.term;
                    performAddressSearch(searchTerm, response);
                },
                minLength: 1,
                select: function(event, ui) {
                    console.log(ui.item);
                },
                appendTo: "#suggestionsContainer"
            }).autocomplete("instance")._renderItem = function(ul, item) {
                // return $("<li>").append("<div> <a href='/product/details'> " + item.label + "</div>").appendTo(ul);
                return $("<li>").append(
                    `<div> <a href='/product/detail/${item.id}' class="nav-link"> ${item.label} </a> </div>`
                ).appendTo(ul);
            };

            function performAddressSearch(searchTerm, response) {

                $.ajax({
                    url: '/search-products', // Replace with your search route
                    dataType: "json",
                    data: {
                        query: searchTerm,
                    },
                    success: function(data) {
                        console.log(data);
                        var suggestions = [];
                        for (var i = 0; i < data.product.length; i++) {
                            suggestions.push({
                                value: data.product[i].name,
                                label: data.product[i].name,
                                id: data.product[i].id,
                                product: data.product[i]
                            });
                        }
                        response(suggestions);
                    }
                });

            }

        });


        $(document).ready(function() {



            var table = $('#example').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    // url: '{{ route('products.index') }}', // Update with your endpoint
                    url: window.location.href,
                    type: 'GET',
                    data: function(d) {
                        d.search = $('#search-input').val(); // Pass any additional parameters if needed
                    }
                },
                columns: [
                    // {
                    //     data: 'checkbox',
                    //     orderable: false,
                    //     searchable: false
                    // }, // Checkbox column
                    {
                        data: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'sku'
                    },
                    {
                        data: 'product_type'
                    },
                    {
                        data: 'category'
                    },
                    {
                        data: 'brand'
                    },
                    {
                        data: 'purchase_price'
                    },
                    {
                        data: 'sell_price'
                    },
                    {
                        data: 'unit'
                    },
                    {
                        data: 'quantity'
                    },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: 'Bfrtip',
                select: {
                    style: 'multi',
                    selector: 'td:first-child .select-checkbox'
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


            // Select all checkbox click handler
            $('#myCheckbox09').on('click', function() {
                var isSelected = $(this).is(':checked'); // Check if checkbox is checked

                // Select/deselect all checkboxes with class 'select-checkbox'
                $('.select-checkbox').prop('checked', isSelected);

                // Optional: Update DataTables selection based on checkbox state
                if (isSelected) {
                    table.rows().select(); // Select all rows in DataTables (adjust if needed)
                    $('#deletedAlert').css('display', 'block');
                    $('#deleteRowCount').text($('.deleteRow:checked').length);
                } else {
                    table.rows().deselect(); // Deselect all rows in DataTables (adjust if needed)
                    $('#deletedAlert').css('display', 'none');
                }
            });

            // Handle click on checkbox to toggle row selection
            $('#example tbody').on('click', 'input[type="checkbox"]', function(e) {
                var $row = $(this).closest('tr');

                // Check the checkbox state and toggle row selection accordingly
                if (this.checked) {
                    table.row($row).select();
                    // $('#myCheckbox09').prop('checked', true);
                } else {
                    table.row($row).deselect();
                    // if ($('.deleteRow:checked').length === 0)
                    //     $('#myCheckbox09').prop('checked', false);

                }

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            // Handle click on table cells with .select-checkbox
            $('#example tbody').on('click', 'td.select-checkbox', function(e) {
                $(this).parent().find('input[type="checkbox"]').trigger('click');
            });

            // Update the count and alert display whenever the selection changes
            table.on('select.dt deselect.dt', function() {
                var selectedRows = table.rows('.selected').count();
                if (selectedRows === 0) {
                    $('#deletedAlert').css('display', 'none');
                } else {
                    $('#deletedAlert').css('display', 'block');
                    $('#deleteRowCount').text(selectedRows);
                }
            });


            $('#deleteRowTrigger').on("click", function(event) { // triggering delete one by one
                if (confirm("Are you sure you won't be able to revert this!")) {
                    if ($('.deleteRow:checked').length > 0) { // at-least one checkbox checked
                        var ids = [];
                        $('.deleteRow').each(function() {
                            if ($(this).is(':checked')) {
                                let id = $(this).data('id');
                                ids.push(id);
                            }
                        });
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: "POST",
                            url: "{{ route('products.delete') }}",
                            data: {
                                ids
                            },
                            success: function(result) {
                                if (result.status === 200) {
                                    toastr.success(result.message)
                                    location.reload();
                                }
                            },
                            async: false
                        });
                    }
                }
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
