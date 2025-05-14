@extends('back.layout.app')
@section('title', 'Create Purchase Return')
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
                <h3 class="all-adjustment text-center pb-2 mb-0">Create Purchase Return</h3>
            </div>
            <form class="container-fluid" action="{{ route('sale_return.store') }}" method="POST" id="createSaleForm">
                @csrf
                <div class="card card-shadow rounded-3 border-0 mt-5">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Date <span
                                            class="text-danger">*</span></label>

                                    <input class="form-control subheading" type="date" value="{{ $purchase->date }}" />

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Purchase <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control subheading" type="text" id="sale_ref"
                                        value="{{ $purchase->reference }}" readonly />

                                </div>
                            </div>
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
                    </div>
                </div>

                <div class="card card-shadow rounded-3 border-0 mt-4 p-3">
                    <div class="table-responsive">
                        <h3 class="all-adjustment text-center pb-1 mb-3">List Product Return <span
                                class="text-danger">*</span></h3>
                        <span class="alert alert-danger p-1 m-1 rounded mb-2">Any products with a quantity set to 0 won't be
                            refunded</span>
                        <table class="table text-center mt-2" id="mainTable">

                            <thead class="fw-bold ">
                                <tr>
                                    <th class="align-middle">#</th>
                                    <th class="align-middle">Product</th>
                                    <th class="align-middle">Net Unit Price</th>
                                    <th class="align-middle">Qty purchased</th>
                                    <th class="align-middle">Qty return</th>
                                    <th class="align-middle">Discount</th>
                                    <th class="align-middle">Tax</th>
                                    <th class="align-middle">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase->purchaseItems as $product)
                                    <tr>
                                        <td class="align-middle" data-product="{{ $product->product }}">
                                            {{ $loop->iteration }}</td>
                                        <td class="align-middle"> {{ $product->product->sku ?? '' }} ({{ $product->product->name ?? '' }})</td>
                                        <td class="product_sell_price align-middle">
                                            {{ $product->product->sell_price ?? '' }} </td>
                                        <td class="align-middle"> <span class="badges bg-darkwarning p-1">{{ $product->quantity ?? '' }} {{ $product->purchase_units?->short_name ? $product->purchase_units->short_name : ''}}</span></td>
                                        <td class="align-middle">
                                            <div class="quantity d-flex justify-content-center align-items-center">
                                                <button type="button" class="btn qty-minus-btn" id="minusBtn">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                                <input type="number" id="quantityInput"
                                                    class="product_qty border-0 qty-input " value="0" min="0" />
                                                <button type="button" class=" btn qty-plus-btn" id="plusBtn">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="align-middle">{{ $product->discount ?? '' }} </td>
                                        <td class="align-middle">{{ $product->order_tax ?? '' }} </td>
                                        <td class="product_price align-middle" id="subtotal">$ 0.00 </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-2 px-3">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 border rounded-2">
                            <div class="row border-bottom subheading">
                                <div class="col-md-6 col-6">Order Tax</div>
                                <div class="col-md-6 col-6" id="order_tax_display">$0.00</div><span> (0.00%)</span>
                                {{-- <div class="col-md-6 col-6" id="order_tax_display"></div><span> (0.00%)</span> --}}
                            </div>

                            <div class="row border-bottom">
                                <div class="col-md-6 col-6">Discount</div>
                                <div class="col-md-6 col-6" id="discount_display">0.00</div>
                            </div>

                            <div class="row border-bottom">
                                <div class="col-md-6 col-6">Shipping</div>
                                <div class="col-md-6 col-6" id="shipping_display">0.00</div>
                            </div>

                            <div class="row disabled-bg">
                                <div class="col-md-6 col-6">Grand Total</div>
                                <div class="col-md-6 col-6" id="grand_total">0.00</div>
                            </div>
                        </div>
                    </div>
                </div>

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
        {{-- <div class="modal fade" id="exampleModalToggle" tabindex="-1" aria-labelledby="exampleModalToggleLabel"
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
                                            @foreach ($units as $unit)
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
        </div> --}}
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const productCodeInput = document.getElementById("product_code");

            // Remove existing handlers to prevent duplicate bindings
            $(document).off("click", ".qty-minus-btn").on("click", ".qty-minus-btn", function() {
                var input = $(this).siblings(".qty-input");
                var currentValue = parseInt(input.val());
                if (currentValue > 0) {
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
                const stock = $(this).closest('tr').find('td:nth-child(4)').text();
                if(quantity > parseFloat(stock)){
                    toastr.success('Quantity exceeded');
                    $(this).val(parseFloat(stock));
                }
                const price = $(this).closest('tr').find('td:nth-child(3)').text();
                const subtotal = parseInt(quantity) * parseFloat(price);
                $(this).closest('tr').find('td:nth-child(8)').text(subtotal.toFixed(2));
                calculateTotal();
            });

            // Event listeners for discount, shipping, and order tax inputs
            $('#discount, #shipping, #order_tax').on('input', calculateTotal);


        });


        function calculateTotal() {
            let subtotal = 0;
            $('.table tbody tr').each(function() {
                const quantity = parseInt($(this).find('td:nth-child(5) input').val()) ||
                0; // Parse quantity as integer
                const price = parseFloat($(this).find('td:nth-child(3)').text()) || 0; // Parse price as float
                const itemSubtotal = quantity * price;
                subtotal += itemSubtotal;
                $(this).find('td:nth-child(8)').text(itemSubtotal.toFixed(2)); // Update subtotal for each row
            });

            // Calculate total tax, discount, shipping, and grand total
            const orderTax = parseFloat($('#order_tax').val()) || 0;
            const discountValue = parseFloat($('#discount').val()) || 0;
            const shipping = parseFloat($('#shipping').val()) || 0;

            const taxAmount = subtotal * (orderTax / 100);
            const grandTotal = subtotal + taxAmount - discountValue + shipping;

            // Update UI with calculated values
            $('#order_tax_display').text(`$${taxAmount.toFixed(2)} (${orderTax}%)`);
            $('#discount_display').text(`$${discountValue.toFixed(2)}`);
            $('#shipping_display').text(`$${shipping.toFixed(2)}`);
            $('#grand_total').text(`$${grandTotal.toFixed(2)}`);
        }



        $(document).ready(function() {
            $('#createSaleForm').on('submit', function(e) {
                e.preventDefault();

                // Collect form data
                let formData = {
                    date: $(this).find('[type=date]').val(),
                    // sale_ref: $(this).find('#sale_ref').val(),
                    purchase_id: {{$purchase->id}},
                    order_tax: $('#order_tax').val(),
                    discount: $('#discount').val(),
                    shipping: $('#shipping').val(),
                    status: $('#status').val(),
                    details: $('#details').val(),
                    grand_total: parseFloat(document.getElementById('grand_total').textContent.replace(
                        '$', '')),

                    return_items: [],
                };

                // Collect order items
                $('.table tbody tr').each(function() {
                    let item = {
                        id: $(this).find('td:first-child').data('product').id,
                        quantityReturn: $(this).find('td:nth-child(5) input').val(),
                        price: $(this).find('td:nth-child(3)').text(),
                        subtotal: $(this).find('td:nth-child(8)').text(),

                    };
                    if (item.quantityReturn != 0) {
                        formData.return_items.push(item);
                    }

                });

                if (formData.return_items.length === 0) {
                    toastr.error('Please add at least one item to the return list');
                    return;
                }

                // AJAX request to server
                $.ajax({
                    url: '{{ route('purchase_return.store') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        toastr.success(response.message);
                        window.location.href = "{{ route('purchase_return.index') }}";
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
    </script>

@endsection
