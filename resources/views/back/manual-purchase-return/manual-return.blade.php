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
                <h3 class="all-adjustment text-center pb-2 mb-0">Manual Purchase Return</h3>
            </div>
            <form class="container-fluid" action="{{ route('manual-sale-return.store') }}" method="POST" id="createSaleForm">
                @csrf
                <div class="card card-shadow rounded-3 border-0 mt-5">
                    <div class="card-body">


                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Date <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control subheading" type="date" value="<?php echo date('Y-m-d'); ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Vendors <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" name="vendor_id" id="vendor_id"
                                        @if(auth()->user()->hasRole(['Cashier','Manager']))
                                            disabled
                                        @endif>
                                        <option value="">Select Vendors</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}"
                                            @if(auth()->user()->hasRole(['Manager']) && auth()->user()->warehouse_id == $warehouse->id)
                                                selected
                                            @endif>{{ $vendor->user->name }}</option>
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
                                        @foreach ($warehouse as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->users->name ?? ''}}</option>
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
                                    <th class="align-middle">Net Unit Price</th>
                                    <th class="align-middle">Stock</th>
                                    <th class="align-middle">Qty</th>
                                    <th class="align-middle">Discount</th>
                                    <th class="align-middle">Tax</th>
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
                            <div class="row border-bottom">
                                <div class="col-md-6 col-6">Order Tax</div>
                                <div class="col-md-6 col-6" id="order_tax_display">$0.00</div><span> (0.00%)</span>
                            </div>

                            <div class="row border-bottom">
                                <div class="col-md-6 col-6">Discount</div>
                                <div class="col-md-6 col-6" id="discount_display">$0.00</div>
                            </div>

                            <div class="row border-bottom">
                                <div class="col-md-6 col-6">Shipping</div>
                                <div class="col-md-6 col-6" id="shipping_display">$0.00</div>
                            </div>

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
                                    <label for="order_tax" class="mb-1 fw-bold">Order Tax</label>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="shipping" class="mb-1 fw-bold">Shipping </label>
                                    <input type="number" placeholder="$0.00" class="form-control subheading"
                                        id="shipping" value="0" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
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
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label for="details" class="mb-1 fw-bold">Please provide any details</label>
                            <textarea class="form-control subheading" id="details" placeholder="A few words" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Input Fields End -->
                <button class="btn save-btn text-white mt-3" type="submit">Submit</button>

            </form>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalToggle" tabindex="-1" aria-labelledby="exampleModalToggleLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title product-title" id="exampleModalToggleLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-title">
                            <h4 class="item-head"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_price" class="mb-1 fw-bold">Product Price</label>
                                        <input type="text" class="form-control subheading" id="product_price"
                                            value="Product Price *" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tax_type" class="mb-1 fw-bold">Tax Type</label>
                                        <select class="form-control form-select subheading"
                                            aria-label="Default select example" id="tax_type">
                                            <option value="" disabled selected>Select Tax Type</option>
                                            <option value="1">Inclusive</option>
                                            <option value="2">Exclusive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="order_tax_item" class="mb-1 fw-bold">Order Tax</label>
                                        <input type="number" class="form-control subheading" id="order_tax_item"
                                            value="0" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount_type_item" class="mb-1 fw-bold">Discount Type</label>
                                        <select name="discount_type" id="discount_type"
                                            class="form-control form-select subheading"
                                            aria-label="Default select example">
                                            <option value="" disabled selected> Select Discount Type</option>
                                            <option value="fixed">Fixed</option>
                                            <option value="percentage">Percentage</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount_item" class="mb-1 fw-bold">Discount</label>
                                        <input type="text" class="form-control subheading" id="discount_item"
                                            value="" placeholder="discount here" />
                                    </div>
                                    <input type="hidden" id="hidden_id">
                                </div>

                                <div class="col-md-6" id="unit_section">
                                    <div class="form-group">
                                        <label for="sale_unit_item" class="mb-1 fw-bold">Sale Unit</label>
                                        <select name="sale_unit_item" id="sale_unit_item"
                                            class="form-control form-select subheading"
                                            aria-label="Default select example">
                                            <option value="" disabled selected>Select Sale Unit</option>
                                            @foreach($units as $unit)
                                                <option value="{{$unit->id ?? ''}}" data-sale-unit-item="{{$unit}}"> {{$unit->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-product-item="" id="saveChangesButton">Save
                            changes</button>
                    </div>
                </div>
            </div>
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
                    $('#warehouse_id').prop('disabled', hasRow);

                    if (!isDuplicate) {
                        // Append row code here
                        // Assuming data contains product name, code, etc.
                        let quantity = ui.item.product.warehouse_quantity;
                        if(ui.item.product.product_type != 'service'){
                            if(ui.item.product.product_unit != ui.item.product.sale_unit){
                                if(ui.item.product.unit.parent_id == 0){
                                    quantity = eval(`${ui.item.product.warehouse_quantity}${ui.item.product.unit.operator}${ui.item.product.sale_units.operator_value} `);
                                }
                            }
                        }

                        row.innerHTML = `
                            <td class="align-middle">${prodCount}</td>
                            <td class="product_name align-middle ">${ui.item.product.name}</td>
                            <td class=" align-middle ">${ui.item.product.sku}</td>
                            <td class="product_sell_price align-middle ">${ui.item.product.sell_price}</td>
                            <td class="align-middle">
                            <span class="badges bg-darkwarning p-1" product_stock data-converted-unit="${ui.item.product.sale_units?.id ? ui.item.product.sale_units.id : ''}">${quantity}${ui.item.product.sale_units?.short_name ? ui.item.product.sale_units.short_name : '' }</span>
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
                            <td class="align-middle">0.00</td>
                            <td class="align-middle">0.00</td>
                            <td class="product_price align-middle">0.00</td>
                            <td class="align-middle">
                            <div class="d-flex justify-content-center">
                                <a href="#" class="btn item-edit" data-product='${JSON.stringify(ui.item.product)}' data-bs-target="#exampleModalToggle" data-bs-toggle="modal">
                                    <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}" alt="" />
                                </a>
                                <a href="#" class="btn btn-plus item-delete">
                                    <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}" alt="" />
                                </a>
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
                    url: '/get-product-detail-by-warehouse', // Replace with your search route
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
                        // If there's exactly one result, add it to the table automatically
                        if (suggestions.length === 1) {
                            $("#product_code").autocomplete("option", "select").call(null, null, {
                                item: suggestions[0]
                            });
                        } else {
                            // If there are multiple suggestions, show them in the dropdown
                            response(suggestions);
                        }
                    }
                });

            }


            function calculateChangeReturn() {
                // Parse the input values to floats
                let amountRecieved = parseFloat(amountRecievedInput.value);
                let amountPay = parseFloat(amountPayInput.value);
                // Get the payment status
                let paymentStatus = document.getElementById('payment_status').value;

                // If the payment status is 'partial' and amountPay is greater than amountRecieved, show an error and return
                if (paymentStatus === 'partial' && amountPay > amountRecieved) {
                    alert('Paying amount cannot be greater than received amount for partial payments');
                    amountPayInput.value = amountRecieved;
                    return;
                }
                // Calculate the change return
                let changeReturn = amountRecieved - amountPay;

                // Check if the change return is a valid number (it will be NaN if either input field is empty or non-numeric)
                if (!isNaN(changeReturn)) {
                    // Update the change_return field
                    document.getElementById('change_return').textContent = changeReturn.toFixed(2);
                }
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
                const stock = $(this).closest('tr').find('td:nth-child(5)').text();
                if(quantity > parseFloat(stock)){
                    toastr.error('Quantity exceeded');
                    $(this).val(parseFloat(stock));
                }
                const price = $(this).closest('tr').find('td:nth-child(4)').text();
                const subtotal = parseInt(quantity) * parseFloat(price);
                $(this).closest('tr').find('td:nth-child(9)').text(subtotal.toFixed(2));
                calculateTotal();
            });

            // Event listeners for discount, shipping, and order tax inputs
            $('#discount, #shipping, #order_tax').on('input', calculateTotal);




        });

        function calculateTotal() {
            let subtotal = 0;
            $('.table tbody tr').each(function() {
                subtotal += parseFloat($(this).find('td:nth-child(9)').text() || 0);
            });

            // Assume `orderTax` is a percentage value from an input field

            const orderTax = parseFloat($('#order_tax').val() == '' ? 0 : $('#order_tax').val()) / 100;
            const taxAmount = subtotal * orderTax;

            const discountValue = parseFloat($('#discount').val() == '' ? 0 : $('#discount').val());

            const shipping = parseFloat($('#shipping').val() == '' ? 0 : $('#shipping').val());

            const grandTotal = subtotal + taxAmount - discountValue + shipping;

            // Update the UI
            $('#order_tax_display').text(`$${taxAmount.toFixed(2)} (${orderTax * 100}%)`);
            $('#discount_display').text(`$${discountValue.toFixed(2)}`);
            $('#shipping_display').text(`$${shipping.toFixed(2)}`);
            $('#grand_total').text(`$${grandTotal.toFixed(2)}`);
            if ($('#payment_status').val() == 'paid') {
                $('#amount_pay').val(grandTotal);
                $('#amount_recieved').val(grandTotal);
            }

        }

        // When an item-edit button is clicked
        $(document).on('click', '.item-edit', function() {
            $('#exampleModalToggle').show();
            // Parse the product data from the button's data-product attribute
            const product = JSON.parse($(this).attr('data-product'));
            console.log(product);
            // Populate the modal fields with the product data
            $('#exampleModalToggle .product-title').text(product.name);
            $('#exampleModalToggle #product_price').val(product.sell_price || product.price);
            $('#exampleModalToggle #tax_type').val(product.tax_type);
            $('#exampleModalToggle #order_tax_item').val(product.order_tax);
            $('#exampleModalToggle #discount_type').val(product.discount_type ?? "fixed");
            $('#exampleModalToggle #discount_item').val(product.discount ?? '0.00');
            $('#exampleModalToggle #hidden_id').val(product.id);
            if(product.product_type == 'service' ){
                $('#exampleModalToggle #unit_section').css('display','none');
            }
            else
            {
                $('#exampleModalToggle #unit_section').css('display','block');
                $('#exampleModalToggle #sale_unit_item').val(product.sale_units?.id);
            }
            product.discount = 0;
            product.discount_type = "fixed";
            // add product into the data-product-item attribute of #saveChangesButton
            $('#saveChangesButton').attr('data-product-item', JSON.stringify(product));
        });

        $('#saveChangesButton').click(function() {
            // Retrieve and parse updated product data from modal
            const product_details = JSON.parse($('#saveChangesButton').attr('data-product-item'));
            let sale_units;
            if(product_details.product_type != 'service'){
                const selectedOption = $('#exampleModalToggle #sale_unit_item option:selected');
                sale_units = selectedOption.data('sale-unit-item');
            }

            const updatedProduct = {
                // existing code to retrieve data...
                tax_type: parseFloat($('#exampleModalToggle #tax_type').val()),
                order_tax: parseFloat($('#exampleModalToggle #order_tax_item').val()) ? parseFloat($('#exampleModalToggle #order_tax_item').val()) : 0,
                discount_type: $('#exampleModalToggle #discount_type').val(),
                discount: parseFloat($('#exampleModalToggle #discount_item').val()) ? parseFloat($('#exampleModalToggle #discount_item').val()) : 0,
                id: parseFloat($('#exampleModalToggle #hidden_id').val()),
                price: parseFloat($('#exampleModalToggle #product_price').val()) ? parseFloat($('#exampleModalToggle #product_price').val()) : 0,
                sale_units: sale_units ?? '',
            };

            // console.log(product_details)
            // console.log(updatedProduct)
            var updatedStock = product_details.warehouse_quantity;
            if (product_details.product_type != 'service') {

                if(updatedProduct.sale_units.parent_id != 0){
                    // big to small unit
                    updatedStock = eval(`${product_details.warehouse_quantity}${product_details.unit.operator}${updatedProduct.sale_units.operator_value}`);

                }
                else
                {
                    // small to large unit conversion
                    updatedStock = product_details.warehouse_quantity;
                }
            }
            updatedProduct.quantity = updatedStock;



            let price = parseFloat(updatedProduct.price);
            if (updatedProduct.tax_type == 2) {
                price += updatedProduct.order_tax;
            } else if (updatedProduct.tax_type == 1) {
                price -= price * (updatedProduct.order_tax / (100 + updatedProduct.order_tax));
            }else{
                price = price;
            }

            if (updatedProduct.discount_type == 'fixed') {
                price -= updatedProduct.discount ? updatedProduct.discount : 0;
            } else if (updatedProduct.discount_type == 'percentage') {
                price -= price * (updatedProduct.discount / 100);
            }else{
                price = price;
            }

            updatedProduct.price = price.toFixed(2);

            // const rowProduct = JSON.parse($(this).attr('data-product-item'));
            console.log(updatedProduct)
            $('#mainTable tbody tr').each(function() {

                const rowProductId = parseInt($(this).find('td:nth-child(10)').find('a').data('product').id);
                if (rowProductId === updatedProduct.id) {
                    // console.log(updatedProduct);
                    // Update the product details in the table
                    $(this).find('td:nth-child(4)').text(updatedProduct.price);
                    $(this).find('td:nth-child(5)').html(`<span class="badges bg-darkwarning p-1" data-converted-unit="${updatedProduct.sale_units?.id ? updatedProduct.sale_units.id : ''}">${updatedStock ?? product_details.warehouse_quantity}${updatedProduct.sale_units?.short_name ? updatedProduct.sale_units.short_name : ''}</span>` );
                    $(this).find('td:nth-child(7)').text(updatedProduct.discount ? updatedProduct.discount : 0);
                    $(this).find('td:nth-child(8)').text(updatedProduct.order_tax);
                    // for sub total
                    const quantity = $(this).find('td:nth-child(6)').find('input').val();
                    const subtotal = parseInt(quantity) * parseFloat(updatedProduct.price);
                    $(this).find('td:nth-child(9)').text(subtotal.toFixed(2));

                    var mergedArray = {};

                    // Merge the arrays manually
                    for (var key in product_details) {
                        // Check if the key is "quantity"
                        if (key === 'quantity') {
                            // If it is, retain the value from the first array
                            mergedArray[key] = product_details[key];
                        } else {
                            // If not, check if the key exists in the second array
                            // If it does, use the value from the second array; otherwise, use the value from the first array
                            mergedArray[key] = updatedProduct.hasOwnProperty(key) ? updatedProduct[key] : product_details[key];
                        }
                    }

                    console.log(mergedArray);

                    $(this).find('td:nth-child(10)').find('a').attr('data-product', JSON.stringify(mergedArray));


                    $('#exampleModalToggle .btn-close').trigger('click');

                    // $('#exampleModalToggle').hide();
                    // return false;
                }
            });

        });

        $(document).ready(function() {
            $('#createSaleForm').on('submit', function(e) {
                e.preventDefault();

                // Collect form data
                let formData = {
                    date: $(this).find('[type=date]').val(),
                    vendor_id: $('#vendor_id').val(),
                    order_tax: $('#order_tax').val(),
                    discount: $('#discount').val(),
                    shipping: $('#shipping').val(),
                    status: $('#status').val(),
                    amount_pay: $('#amount_pay').val(),
                    note: $('#notes').val(),
                    warehouse_id: $('#warehouse_id').val(),
                    grand_total: parseFloat(document.getElementById('grand_total').textContent.replace(
                        '$', '')),

                    return_items: []
                };

                // Collect order items
                $('#mainTable tbody tr').each(function() {
                    let item = {
                        id: $(this).find('td:nth-child(10) .item-edit').data('product').id,
                        quantityReturn: $(this).find('td:nth-child(6) input').val(),
                        discount: $(this).find('td:nth-child(7)').text(),
                        discount_type: $(this).find('td:nth-child(10) .item-edit').data('product').discount_type,
                        tax_type: $(this).find('td:nth-child(10) .item-edit').data('product').tax_type,
                        order_tax: $(this).find('td:nth-child(8)').text(),
                        return_unit: $(this).find('td:nth-child(5) span').data('converted-unit'),
                        price: $(this).find('td:nth-child(4)').text(),
                        subtotal: $(this).find('td:nth-child(9)').text(),
                    };
                    formData.return_items.push(item);

                });
                // console.log(formData);

                // AJAX request to server
                $.ajax({
                    url: '{{ route("manual-purchase-return.store") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        toastr.success('Manually Purchase Return created successfully!');
                        window.location.href = "{{ route('manual-purchase-return.index') }}";
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
