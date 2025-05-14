@extends('back.layout.app')
@section('title', 'Categories')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        .ui-autocomplete {
            padding: 0 !important;
        }

        .ui-menu .ui-menu-item-wrapper {
            text-align: left;
        }
    </style>
@endsection

@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Create Bill</h3>
            </div>
            <form class="container-fluid" action="{{ route('bills.store') }}" method="POST" id="createBillForm">
                @csrf
                <div class="card card-shadow rounded-3 border-0 mt-5">
                    <div class="card-body">


                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Bill Date <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control subheading" type="date" id="billDate" value="<?php echo date('Y-m-d'); ?>" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Vendor <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" name="customer_id" id="vendors">
                                        <option value="">Select Vendor</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->user->name }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{ session('selected_warehouse_id') }}"> --}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Warehouse <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" name="warehouse_id"id="warehouse_id">
                                        <option value="">Select Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->users->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Product</label>
                            <div class="input-group">
                                <input type="text" class="form-control subheading" placeholder="Product Code / Name"
                                    id="product_code" name="product_code" />
                                <div id="suggestionsContainer"></div>

                                <span class="input-group-text subheading" id="basic-addon2"><i
                                        class="bi bi-upc-scan"></i></span>
                                {{-- <div class="search-dropdown" id="searchDropdown" style=" display: none;"></div> --}}
                            </div>
                            <p class="subheading m-0 p-0">
                                Scan the barcode or enter symbology
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card card-shadow rounded-3 border-0 mt-4 p-3">
                    <div class="table-responsive">
                        <table class="table text-center" id="mainTable">
                            <h3 class="all-adjustment text-center pb-1">Order Items</h3>
                            <thead class="fw-bold">
                                <tr>
                                    <th class="align-middle">#</th>
                                    <th class="align-middle">Product Name</th>
                                    <th class="align-middle">Product Code</th>
                                    <th class="align-middle">Price</th>
                                    <th class="align-middle">Qty</th>
                                    <th class="align-middle">Subtotal</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-2 px-3">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 border rounded-2">
                            <div class="row border-bottom subheading">
                                <div class="col-md-6 col-6">Tax</div>
                                <div class="col-md-6 col-6" id="order_tax_display">$0.00</div><span> (0.00%)</span>
                            </div>

                            <div class="row border-bottom">
                                <div class="col-md-6 col-6">Discount</div>
                                <div class="col-md-6 col-6" id="discount_display">$0.00</div>
                            </div>

                            {{-- <div class="row border-bottom">
                                <div class="col-md-6 col-6">Shipping</div>
                                <div class="col-md-6 col-6" id="shipping_display">$0.00</div>
                            </div> --}}

                            <div class="row disabled-bg">
                                <div class="col-md-6 col-6">Grand Total</div>
                                <div class="col-md-6 col-6" id="grand_total">$0.00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Fields -->
                <!-- Input Fields -->
                <div class="card card-shadow rounded-3 border-0 mt-4 p-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Due Date <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control subheading" type="date" id="dueDate" value="<?php echo date('Y-m-d'); ?>" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order_tax" class="mb-1 fw-bold">Tax</label>
                                    <input type="number" placeholder="0%" class="form-control subheading"value="0"
                                        id="order_tax" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount" class="mb-1 fw-bold">Discount</label>
                                    <input type="number" placeholder="$0.00" class="form-control subheading"value="0"
                                        id="discount" />
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="shipping" class="mb-1 fw-bold">Shipping </label>
                                    <input type="number" placeholder="$0.00" class="form-control subheading"
                                        id="shipping" value="0" />
                                </div>
                            </div> --}}
                        </div>
                        <div class="row">
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" name="status" id="status">
                                        <option disabled>Select Status</option>
                                        <option value="received">Received</option>
                                        <option value="pending">Pending</option>

                                    </select>
                                </div>
                            </div> --}}
                        </div>

                        <div class="form-group mt-2">
                            <label for="details" class="mb-1 fw-bold">Please provide any details</label>
                            <textarea class="form-control subheading" id="notes" placeholder="A few words" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Input Fields End -->
                <button class="btn save-btn text-white mt-3" type="submit">Submit</button>

            </form>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const productCodeInput = document.getElementById("product_code");
            let prodCount = 1;

            var suggestionsContainer = $("#suggestionsContainer");
            $("#product_code").autocomplete({
                source: function(request, response) {
                    var searchTerm = request.term;
                    performAddressSearch(searchTerm, response);
                },
                minLength: 2,
                select: function(event, ui) {
                    console.log(ui.item);

                    const tableBody = document.querySelector(".table tbody");
                    const row = document.createElement("tr");
                    let isDuplicate = false;
                    let hasRow = true;
                    document.querySelectorAll('.table tbody tr').forEach(row => {
                        if (row.querySelector('td:nth-child(3)').textContent === ui.item.product.sku) {
                            // alert('Duplicate product cannot be added!');

                            // Select the input element
                            let qtyInput = $(row).find('td:nth-child(6) .qty-input');

                            // Get the current value of the input
                            let currentValue = parseInt(qtyInput.val());

                            // Increment the value and set it to the input
                            qtyInput.val(currentValue + 1).change();

                            isDuplicate = true;
                        }
                    });

                    if (!isDuplicate) {
                        // Append row code here
                        // Assuming data contains product name, code, etc.

                        row.innerHTML = `
                            <td class="align-middle">${prodCount}</td>
                            <td class="product_name align-middle ">${ui.item.product.name}</td>
                            <td class=" align-middle ">${ui.item.product.sku}</td>
                            <td class="product_sell_price align-middle ">
                                <input
                                type="number"
                                id="price"
                                class="border-0"
                                value="${ui.item.product.sell_price}"
                                style="width:100px;text-align: center;background: rgba(237, 242, 246, 1);height: 32px;"/>

                            </td>
                            <td class="align-middle">
                            <div
                                class="quantity d-flex justify-content-center align-items-center"
                            >
                                <button type="button" class="btn qty-minus-btn" id="minusBtn">
                                <i class="fa-solid fa-minus"></i>
                                </button>
                                <input
                                type="number"
                                id="quantityInput"
                                class="product_qty border-0 qty-input "
                                value="0" min="0"
                                />
                                <button type="button" class=" btn qty-plus-btn" id="plusBtn">
                                <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            </td>
                            <td class="product_price align-middle">0.00</td>
                            <td class="align-middle" data-product='${JSON.stringify(ui.item.product)}'>
                                <div class="d-flex justify-content-center removeRowBtn" style="cursor:pointer;" >
                                    <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}" alt="" />
                                </div>
                            </td>

                        `;

                        tableBody.appendChild(row);
                        prodCount++;
                    }
                },
                appendTo: "#suggestionsContainer"
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>").append("<div>" + item.label + "</div>").appendTo(ul);
            };

            function performAddressSearch(searchTerm, response) {
                let warehouse = $('#warehouse_id').val();

                $.ajax({
                    url: '/get-product-details', // Replace with your search route
                    dataType: "json",
                    data: {
                        query: searchTerm,
                        warehouse_id: warehouse,

                    },
                    success: function(data) {
                        console.log(data);
                        var suggestions = [];
                        for (var i = 0; i < data.product.length; i++) {
                            suggestions.push({
                                value: data.product[i].sku,
                                label: data.product[i].name,
                                id: data.product[i].id,
                                product: data.product[i]
                            });
                        }
                        response(suggestions);
                    }
                });

            }


            // Remove existing handlers to prevent duplicate bindings
            $(document).off("click", ".qty-minus-btn").on("click", ".qty-minus-btn", function() {
                var input = $(this).siblings(".qty-input");
                var currentValue = parseInt(input.val());
                if (currentValue > 1) {
                    input.val(currentValue - 1).change();
                }
            });

            $(document).off("click", ".qty-plus-btn").on("click", ".qty-plus-btn", function() {
                var input = $(this).siblings(".qty-input");
                var currentValue = parseInt(input.val());
                input.val(currentValue + 1).change();
            });

            // Handle changes in quantity
            $(document).on('change', '.qty-input', function() {
                const quantity = $(this).val();
                const price = $(this).closest('tr').find('td:nth-child(4) #price').val();
                const subtotal = parseInt(quantity) * parseFloat(price);
                $(this).closest('tr').find('td:nth-child(6)').text(subtotal.toFixed(2));
                calculateTotal();
            });

            $(document).on('change','#price',function(){
                const price = $(this).val();
                const quantity = $(this).closest('tr').find('td:nth-child(5) .qty-input').val();
                const subtotal = parseInt(quantity) * parseFloat(price);
                $(this).closest('tr').find('td:nth-child(6)').text(subtotal.toFixed(2));
                calculateTotal();
            });

            // Event listeners for discount, shipping, and order tax inputs
            $('#discount, #order_tax').on('input', calculateTotal);

            $(document).on('click', '.removeRowBtn', function() {
            let row = $(this).closest('tr');
            row.remove();
            calculateTotal();
        })




        });

        function calculateTotal() {
            let subtotal = 0;
            $('.table tbody tr').each(function() {
                subtotal += parseFloat($(this).find('td:nth-child(6)').text() || 0);
            });

            // Assume `orderTax` is a percentage value from an input field

            const orderTax = parseFloat($('#order_tax').val() == '' ? 0 : $('#order_tax').val()) / 100;
            const taxAmount = subtotal * orderTax;

            const discountValue = parseFloat($('#discount').val() == '' ? 0 : $('#discount').val());

            const grandTotal = subtotal + taxAmount - discountValue;

            // Update the UI
            $('#order_tax_display').text(`$${taxAmount.toFixed(2)} (${orderTax * 100}%)`);
            $('#discount_display').text(`$${discountValue.toFixed(2)}`);
            $('#grand_total').text(`$${grandTotal.toFixed(2)}`);
        }


        $(document).ready(function() {
            $('#createBillForm').on('submit', function(e) {
                e.preventDefault();
                // alert('yes');

                // Collect form data
                let formData = {
                    bill_date: $('#billDate').val(),
                    due_date: $('#dueDate').val(),
                    vendor_id: $('#vendors').val(),
                    tax: $('#order_tax').val(),
                    discount: $('#discount').val(),
                    details: $('#notes').val(),
                    warehouse_id: $('#warehouse_id').val(),
                    grand_total: parseFloat(document.getElementById('grand_total').textContent.replace(
                        '$', '')),

                    product_items: []
                };

                // Collect order items
                $('#mainTable tbody tr').each(function() {
                    let item = {
                        product_id: $(this).find('td:nth-child(7)').data('product').id,
                        quantity: $(this).find('td:nth-child(5) input').val(),
                        price: $(this).find('td:nth-child(4) #price').val(),
                        subtotal: $(this).find('td:nth-child(6)').text(),
                    };
                    formData.product_items.push(item);

                });

                // AJAX request to server
                $.ajax({
                    url: '{{ route("bills.store") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        toastr.success('Bill created successfully!');
                        window.location.href = "{{ route('bills.index') }}";
                        console.log('Success:', response);
                    },

                    error: function(xhr) {
                        if (xhr.status === 422) { // If validation fails
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]); // Display first error message
                            });
                        } else {
                            toastr.error('An error occurred while processing your request.');
                        }
                    }
                });
            });
        });


        $(document).on('click', '.item-delete', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            calculateTotal();

             // Check if the table is empty
             if ($('#mainTable tbody tr').length === 0) {
                $('#warehouse_id').prop('disabled', false);
            }
        });



    </script>

@endsection
