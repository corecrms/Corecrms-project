@extends('user.layout.app')

@section('style')
    <style>
        .list-group-item {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <!-- Order Status -->
    <section class="order-status bg-light py-5">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
                <div>
                    <h2 class="heading fs-4 m-0">
                        <i class="bi bi-1-circle-fill"></i> SHOPPING CART
                    </h2>
                </div>
                <div>
                    <h2 class="heading fs-4 mx-2 m-0 checkout-border">
                        <i class="bi bi-2-circle-fill mb-3"></i> CHECKOUT
                    </h2>
                </div>
                <div>
                    <h2 class="heading fs-4 m-0 text-secondary">
                        <i class="bi bi-3-circle-fill"></i> ORDER STATUS
                    </h2>
                </div>
            </div>
            <p class="m-0 mt-4 text-center">
            </p>
        </div>
    </section>
    <!-- End Order Status -->

    <!-- Checkout -->
    <section class="mt-5">
        @php
            $user = \App\Models\User::find(auth()->id())->load('customer');
        @endphp
        <div class="container-xxl container-fluid">
            <div class="row mb-5">


                <div class="col-md-7 border border-2 mt-3 p-3" id="shipping_section">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h2 class="heading border-bottom py-3">Billing Detail</h2>
                    <div class="mt-3 mb-3">
                        <label for="shipping_address" class="form-label">Choose Shipping Address</label>
                        <select name="shipping_address" id="shipping_address" class="form-control" style="cursor: pointer">
                            <option value="" data-address="{{ $user }}">Default Address </option>
                            @foreach (auth()->user()->shippingAddresses as $address)
                                <option value="{{ $address->id }}" data-address="{{ $address }}">
                                    {{ $address->address }}</option>
                            @endforeach
                        </select>
                    </div>
                    @php
                        function splitName($fullName)
                        {
                            $nameParts = explode(' ', $fullName);
                            $firstName = array_shift($nameParts);
                            $secondName = implode(' ', $nameParts);
                            return [
                                'first_name' => $firstName,
                                'last_name' => $secondName,
                            ];
                        }
                        $names = splitName(auth()->user()->name);
                    @endphp
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="first_name" class="mb-1">First Name *</label>
                                <input type="text" placeholder="First Name" class="form-control" name="first_name"
                                    id="first_name" required value="{{ $names['first_name'] ?? 'Name' }}" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="last_name" class="mb-1">Last Name *</label>
                                <input type="text" placeholder="Last Name" class="form-control" name="last_name"
                                    id="last_name" value="{{ $names['last_name'] }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="email" class="mb-1">Email address *</label>
                                <input type="email" placeholder="Email Address" class="form-control" id="email"
                                    required value="{{ auth()->user()->email ?? '' }}" name="email" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="contact_no" class="mb-1">Phone *</label>
                                <input type="text" placeholder="Phone" class="form-control" id="contact_no" required
                                    value="{{ auth()->user()->contact_no ?? '' }}" name="contact_no" />
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="company_name" class="mb-1">Company Name (Optional)</label>
                            <input type="text" placeholder="Company Name" class="form-control" name="company_name"
                                id="company_name" value="{{ auth()->user()->customer->business_name ?? null }}" />
                        </div>
                        <div class="form-group mt-3">
                            <label for="address" class="mb-1">Address Line 1</label>
                            <input type="text" placeholder="Address Line 1" class="form-control"
                                id="address" required value="{{ auth()->user()->address ?? null }}" name="address" />

                            <label for="address" class=" mt-1">Address Line 2</label>
                            <input type="text" placeholder="Address Line 2 (optional)"
                                class="form-control mt-1" id="appartment" name="appartment"
                                value="{{ auth()->user()->address_line_2 ?? null }}" />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="city" class="mb-1">Town / City *</label>
                                <input type="text" class="form-control" id="city" required
                                    value="{{ auth()->user()->customer->city ?? null }}" placeholder="City"
                                    name="city" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="state" class="mb-1">State *</label>
                                <input type="text" class="form-control" id="state" required
                                    placeholder="State" name="state"
                                    value="{{ auth()->user()->state ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="state_code" class="mb-1">State Code *</label>
                                <input type="text" class="form-control" id="state_code" required
                                    placeholder="State" name="state_code"
                                    value="{{ auth()->user()->state_code ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="country" class="mb-1">Country / Region *</label>
                                {{-- <select class="form-control form-select" aria-label="Default select example" id="country" required
                                    name="country">
                                    <option value="" disabled>Select a country...</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country }}">{{ $country }}</option>
                                    @endforeach
                                </select> --}}
                                <input type="text" id="country" required name="country" class="form-control"
                                    placeholder="Country Name" value="{{ auth()->user()->customer->country ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="country_code" class="mb-1">Country Code</label>

                                <input type="text" id="country_code" required name="country" class="form-control"
                                    placeholder="Country Code" value="{{ auth()->user()->country_code ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="zip_code" class="mb-1">Zip Code *</label>
                                <input type="text" class="form-control" id="zip_code" required name="zip_code"
                                    value="{{ auth()->user()->postal_code }}" />
                            </div>
                        </div>

                    </div>


                    <h2 class="heading border-bottom py-2 mt-2">
                        Additional Information
                    </h2>
                    <div class="form-group mt-3">
                        <label for="notes" class="mb-1">Order Notes (Optional)</label>
                        <textarea class="form-control" id="notes" placeholder="Notes about your order, e.g. special notes for delivery"
                            rows="5" name="notes"></textarea>
                    </div>
                </div>
                {{-- <div class="col-md-7" id="fedex_api_section" style="display: none">
                    <h2 class="heading border-bottom py-3">Shipping Method</h2>
                    <div class="list-group">
                        <label class="list-group-item d-flex align-items-center">
                            <input class="form-check-input me-2" type="radio" name="shippingOption"
                                value="fedex_priority">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <img src="https://static.cellairisrepairparts.com/skin/frontend/msentrix2022/default/images/new-checkout/fedex.svg"
                                        alt="FedEx" class="me-2" style="width: 40px;">
                                    <strong>Priority Overnight</strong>
                                    <br><small class="text-muted">Est. delivery Fri Aug 30, 2024 10:30AM</small>
                                </div>
                                <span class="ms-auto">$25.00</span>
                            </div>
                        </label>

                        <div class="py-2 px-3"><strong>2 - Day shipping - Fastest Estimated Arrival Time: Mon Sep 02, 2024
                                05:00PM</strong></div>

                        <label class="list-group-item d-flex align-items-center">
                            <input class="form-check-input me-2" type="radio" name="shippingOption"
                                value="ups_2nd_day_air">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <img src="path_to_ups_logo" alt="UPS" class="me-2" style="width: 30px;">
                                    <strong>2nd Day Air</strong>
                                    <br><small class="text-muted">Est. delivery Mon Sep 02, 2024 11:00PM</small>
                                </div>
                                <span class="ms-auto">$9.50</span>
                            </div>
                        </label>

                        <label class="list-group-item d-flex align-items-center">
                            <input class="form-check-input me-2" type="radio" name="shippingOption"
                                value="fedex_2day">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <img src="path_to_fedex_logo" alt="FedEx" class="me-2" style="width: 30px;">
                                    <strong>2Day</strong>
                                    <br><small class="text-muted">Est. delivery Mon Sep 02, 2024 05:00PM</small>
                                </div>
                                <span class="ms-auto">$9.50</span>
                            </div>
                        </label>

                        <div class="py-2 px-3"><strong>Ground shipping - Fastest Estimated Arrival Time: Mon Sep 02, 2024
                                11:00PM</strong></div>

                        <label class="list-group-item d-flex align-items-center">
                            <input class="form-check-input me-2" type="radio" name="shippingOption"
                                value="ups_ground">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <img src="path_to_ups_logo" alt="UPS" class="me-2" style="width: 30px;">
                                    <strong>Ground</strong>
                                    <br><small class="text-muted">Est. delivery Mon Sep 02, 2024 11:00PM</small>
                                </div>
                                <span class="ms-auto">$8.50</span>
                            </div>
                        </label>

                        <label class="list-group-item d-flex align-items-center">
                            <input class="form-check-input me-2" type="radio" name="shippingOption"
                                value="fedex_ground">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <img src="path_to_fedex_logo" alt="FedEx" class="me-2" style="width: 30px;">
                                    <strong>Ground</strong>
                                    <br><small class="text-muted">Est. delivery Mon Sep 02, 2024 11:59PM</small>
                                </div>
                                <span class="ms-auto">$8.50</span>
                            </div>
                        </label>

                        <label class="list-group-item d-flex align-items-center">
                            <input class="form-check-input me-2" type="radio" name="shippingOption"
                                value="usps_first_class">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <img src="path_to_usps_logo" alt="USPS" class="me-2" style="width: 30px;">
                                    <strong>First Class</strong>
                                    <br><small class="text-muted">Est. delivery 2-5 business days</small>
                                </div>
                                <span class="ms-auto">$4.50</span>
                            </div>
                        </label>
                    </div>
                </div> --}}
                <div class="col-md-7 border border-2 mt-3 p-3" id="fedex_api_section" style="display: none">
                    <h2 class="heading border-bottom py-3">Shipping Method</h2>
                    <div class="list-group">
                        <div class="w-100 d-flex justify-content-center mt-5">
                            <div class="spinner-border text-secondary" role="status" id="fedex-spinner">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 mt-2 p-3">
                    <div class="border border-2 p-3">
                        <h2 class="heading border-bottom py-3">Your Order</h2>
                        @php
                            $grand_total = 0;
                            $subtotal = 0;
                        @endphp
                        @forelse (auth()->user()->carts as $cart)
                            @php
                                $subtotal = $cart->product->sell_price * $cart->quantity;
                                $grand_total += $subtotal;
                                $quantity = $cart->product->product_warehouses
                                    ->filter(function ($warehouse) use ($cart) {
                                        return $warehouse->warehouse_id == $cart->warehouse_id;
                                    })
                                    ->first()->quantity;
                            @endphp
                            <div
                                class="d-flex gap-5 align-items-center mt-3 border-bottom pb-3 cartItems position-relative">
                                <div>
                                    @if (count($cart->product->images) > 0)
                                        <img src="{{ asset('/storage' . $cart->product->images[0]['img_path']) }}"
                                            alt="No" class="img-fluid" width="70">
                                    @else
                                        <img src="{{ asset('back/assets/image/no-image.png') }}" alt=""
                                            class="img-fluid" width="70" />
                                    @endif
                                </div>
                                <div>
                                    <h2 class="heading fs-6 mb-1">
                                        {{ $cart->product->name ?? 'N/A' }}
                                        {{-- Lorem ipsum dolor sit amet consectetur adipisicing lorem12 --}}
                                    </h2>
                                    <div class="d-flex gap-2 align-items-center mt-2">

                                        <div class="quantity d-flex justify-content-center w-25">
                                            <button class="btn bag-btn text-white minusBtn rounded-0 px-1">
                                                -
                                            </button>
                                            <input type="text"
                                                class="form-control quantityInput rounded-end-0 rounded-start-0 w-75"
                                                value="{{ $cart->quantity }}" data-product_id="{{ $cart->product_id }}"
                                                data-warehouse_id="{{ $cart->warehouse_id }}"
                                                data-product_price="{{ $cart->price }}" />
                                            <button class="btn bag-btn text-white plusBtn rounded-0 px-1">
                                                +
                                            </button>
                                        </div>
                                        <span>x</span>
                                        <span class="product-price">${{ $cart->product->sell_price ?? '0.00' }}</span>

                                    </div>
                                    <p class="m-0 mt-2 fs-6">subtotal: <span
                                            class="product-subtotal">${{ number_format($subtotal, 2) }}</span></p>

                                </div>
                                <div class="fw-bold badge rounded-pill text-bg-warning position-absolute top-0 end-0"><span
                                        class="stock">{{ $quantity ?? 0 }}</span> in Stock</div>
                            </div>
                        @empty
                            <div class="text-center">
                                No Product Found
                            </div>
                        @endforelse
                        <div class="border-bottom pb-3">
                            <div class="d-flex justify-content-between">
                                <p class="m-0 mt-2 heading">Subtotal</p>
                                <p class="m-0 mt-2 heading" id="subtotal">
                                    ${{ number_format($grand_total ?? '0.00', 2) }}
                                </p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="m-0 mt-2 heading">Discount</p>
                                <p class="m-0 mt-2 heading" id="discount">
                                    {{-- %{{ number_format(auth()->user()->customer->tier->discount ?? 0.0, 2) }} --}}
                                </p>
                            </div>
                            <input type="hidden" id="discount_amount" name="discount_amount">

                            <div class="d-flex justify-content-between">
                                <p class="m-0 mt-2 heading">Shipping Fee</p>
                                <p class="m-0 mt-2 heading" id="shipping_fee">$0.00</p>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <p class="m-0 mt-3 heading fw-bold">Total</p>
                                <p class="m-0 mt-3 heading fw-bold" id="grand_total">
                                    ${{ number_format($grand_total ?? '0.00', 2) }}</p>
                            </div>
                        </div>
                        <div class="payment-method mt-3" id="payment-method" style="display: none">
                            <div class="input-radio">
                                <input type="radio" name="payment" id="payment-1" value="Credit Balance" />
                                <label for="payment-1">
                                    <span></span>
                                    Use Credit Balance
                                </label>
                                <div class="caption">
                                    <p>
                                        Use your credit balance to pay for this order.
                                    </p>
                                </div>
                            </div>
                            <div class="input-radio">
                                <input type="radio" name="payment" id="payment-2" value="Stripe Payment" />
                                <label for="payment-2">
                                    <span></span>
                                    Pay with Stripe
                                </label>
                                <div class="caption">
                                    <p>
                                        Please send a check to Store Name, Store Street, Store
                                        Town, Store State / County, Store Postcode.
                                    </p>
                                </div>
                            </div>
                            <div class="input-radio mb-2">
                                <input type="radio" name="payment" id="payment-3" value="Paypal Payment" />
                                <label for="payment-3">
                                    <span></span>
                                    Pay with Paypal
                                </label>

                            </div>
                            {{-- <div class="input-radio">
                                <input type="radio" name="payment" id="payment-4" checked value="Cash on delivery" />
                                <label for="payment-4">
                                    <span></span>
                                    Cash on delivery
                                </label>
                                <div class="caption">
                                    <p>Pay with cash upon delivery.</p>
                                </div>
                            </div> --}}
                        </div>
                        <button class="btn bag-btn text-white rounded-5 w-100 py-2" id="place_order"
                            style="display: none">
                            Place Order
                        </button>
                        <button class="btn bag-btn text-white rounded-5 w-100 py-2" id="next_btn">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Checkout -->
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $('.plusBtn').click(function() {
                var input = $(this).parent().find('.quantityInput');
                var value = parseInt(input.val());
                // if (value < 100) {
                value = value + 1;
                // }
                input.val(value).trigger('change');

            });

            $('.minusBtn').click(function() {
                var input = $(this).parent().find('.quantityInput');
                var value = parseInt(input.val());
                if (value > 1) {
                    value = value - 1;
                }
                input.val(value).trigger('change');
            });

            $('.quantityInput').change(function() {
                var value = $(this).val();
                if (value < 1) {
                    value = 1;
                }
                // if (value > 100) {
                //     value = 100;
                // }
                $(this).val(value);
                let quantity = $(this).val();
                let stock = parseFloat($(this).closest('.cartItems').find('.stock').text());
                if (quantity > stock) {
                    toastr.error('Quantity exceeded');
                    $(this).val(stock);
                }
                let price = $(this).closest('.cartItems').find('.product-price').text().replace('$', '');
                let subtotal = price * $(this).val();
                $(this).closest('.cartItems').find('.product-subtotal').text('$' + subtotal);
                calculate();

            });

            $('.quantityInput').trigger('change');

            // function calculate() {
            //     let total = 0;
            //     $('.product-subtotal').each(function() {
            //         total += parseFloat($(this).text().replace('$', ''));
            //     });

            //     $('#subtotal').text('$' + total.toFixed(2));

            //     // alert($('#shipping_fee').text().replace('$', ''));
            //     // add discount percentage

            //     total = total + parseFloat($('#shipping_fee').text().replace('$', ''));

            //     let discount = parseFloat($('#discount').text().replace('%', ''));
            //     total = total - (total * (discount / 100));

            //     // $('#grand_total').text('$' + (total + parseFloat($('#shipping_fee').text().replace('$', ''))).toFixed(2));

            //     $('#grand_total').text('$' + total.toFixed(2));
            // }
            // calculate();

            // function calculate() {
            //     let total = 0;

            //     // Calculate the subtotal
            //     $('.product-subtotal').each(function() {
            //         total += parseFloat($(this).text().replace('$', ''));
            //     });

            //     // Display the subtotal
            //     $('#subtotal').text('$' + total.toFixed(2));

            //     // Get the shipping fee and add to the total
            //     let shippingFee = parseFloat($('#shipping_fee').text().replace('$', ''));
            //     total += shippingFee;

            //     // Get the discount percentage
            //     let discountPercentage = parseFloat($('#discount').text().replace('%', ''));

            //     // Calculate the discount amount
            //     let discountAmount = total * (discountPercentage / 100);

            //     // Subtract the discount from the total
            //     total = total - discountAmount;

            //     // Display the discount in the format -$200 (7%)
            //     $('#discount').text('$' + discountAmount.toFixed(2) + ' (' + discountPercentage + '%)');

            //     // Display the grand total
            //     $('#grand_total').text('$' + total.toFixed(2));
            // }

            // // Call the calculate function to update everything
            // calculate();

            function calculate() {
                let total = 0;

                // Calculate the subtotal
                $('.product-subtotal').each(function() {
                    total += parseFloat($(this).text().replace('$', ''));
                });

                // Display the subtotal
                $('#subtotal').text('$' + total.toFixed(2));

                // Get the shipping fee and add to the total
                let shippingFee = parseFloat($('#shipping_fee').text().replace('$', '')) || 0;
                total += shippingFee;

                // Set the discount percentage (store this value in the script, no need to show it)
                let discountPercentage = {{auth()->user()->customer->tier->discount ?? 0}} ; // You can change this value as needed

                // Calculate the discount amount
                let discountAmount = total * (discountPercentage / 100);

                $('#discount_amount').val(discountAmount);
                // Subtract the discount from the total
                total = total - discountAmount;

                // Display the discount in the format -$200 (7%)
                $('#discount').text('-$' + discountAmount.toFixed(2) + ' (' + discountPercentage + '%)');

                // Display the grand total
                $('#grand_total').text('$' + total.toFixed(2));
            }

            // Call the calculate function to update everything
            calculate();



            $(document).on('change', '.shipping-option', function() {
                let shippingFee = $(this).data('cost');
                // alert(shippingFee);
                console.log("Selected Shipping Fee: $" + shippingFee);
                $('#shipping_fee').text('$' + shippingFee);
                calculate();
                $('#place_order').show();
                $('#payment-method').show();
            });
        });


        // $(document).ready(function() {
        //     $(document).on('click', '#place_order', function() {
        //         let first_name = $('#first_name').val();
        //         let last_name = $('#last_name').val();
        //         let company_name = $('#company_name').val();
        //         let country = $('#country').val();
        //         let address = $('#address').val();
        //         let appartment = $('#appartment').val();
        //         let city = $('#city').val();
        //         let state = $('#state').val();
        //         let contact_no = $('#contact_no').val();
        //         let zip_code = $('#zip_code').val();
        //         let email = $('#email').val();
        //         let notes = $('#notes').val();
        //         let payment = $('input[name="payment"]:checked').val();
        //         let grand_total = $('#grand_total').text().replace('$', '');
        //         let products = [];
        //         $('.cartItems').each(function() {
        //             let product_id = $(this).find('.quantityInput').data('product_id');
        //             let product_price = $(this).find('.quantityInput').data('product_price');
        //             let quantity = $(this).find('.quantityInput').val();
        //             let warehouse_id = $(this).find('.quantityInput').data('warehouse_id');
        //             products.push({
        //                 product_id: product_id,
        //                 price: product_price,
        //                 quantity: quantity,
        //                 warehouse_id: warehouse_id
        //             });
        //         });

        //         let data = {
        //             name: first_name + ' ' + last_name,
        //             company_name: company_name,
        //             country: country,
        //             address: address,
        //             appartment: appartment,
        //             city: city,
        //             state: state,
        //             contact_no: contact_no,
        //             zip_code: zip_code,
        //             email: email,
        //             notes: notes,
        //             payment_method: payment,
        //             grand_total: grand_total,
        //             products: products,
        //             customer_id: "{{ auth()->user()->customer->id }}",
        //             _token: "{{ csrf_token() }}"
        //         };
        //         // console.log(data)
        //         // return;
        //         $.ajax({
        //             url: "{{ route('user.checkout.store') }}",
        //             type: "POST",
        //             data: data,
        //             success: function(response) {
        //                 if (response.status == 200) {
        //                     toastr.success('Order placed successfully');
        //                     window.location.href = "{{ route('user.orders.index') }}";
        //                 }
        //             },
        //             error: function(xhr) {
        //                 if (xhr.status === 422) { // If validation fails
        //                     var errors = xhr.responseJSON.errors;
        //                     $.each(errors, function(key, value) {
        //                         // if(key == "state"){
        //                         //     $('#state').addClass('is-invalid');
        //                         // }
        //                         toastr.error(value[0]); // Display first error message
        //                     });
        //                 } else {
        //                     toastr.error('An error occurred while processing your request.');
        //                 }
        //             },
        //         });
        //     });
        // });

        $(document).ready(function() {
            $(document).on('click', '#stripe_pay_btn', function() {
                let first_name = $('#first_name').val();
                let last_name = $('#last_name').val();
                let company_name = $('#company_name').val();
                let country = $('#country').val();
                let country_code = $('#country').val();
                let state_code = $('#state_code').val();
                let address = $('#address').val();
                let appartment = $('#appartment').val();
                let city = $('#city').val();
                let state = $('#state').val();
                let contact_no = $('#contact_no').val();
                let zip_code = $('#zip_code').val();
                let email = $('#email').val();
                let notes = $('#notes').val();
                let payment = $('input[name="payment"]:checked').val();
                let grand_total = $('#grand_total').text().replace('$', '');
                let shipping_method = $('input[name="shippingOption"]:checked').val();
                let shipping_fee = $('#shipping_fee').text().replace('$', '');
                let products = [];
                $('.cartItems').each(function() {
                    let product_id = $(this).find('.quantityInput').data('product_id');
                    let product_price = $(this).find('.quantityInput').data('product_price');
                    let quantity = $(this).find('.quantityInput').val();
                    let warehouse_id = $(this).find('.quantityInput').data('warehouse_id');
                    products.push({
                        product_id: product_id,
                        price: product_price,
                        quantity: quantity,
                        warehouse_id: warehouse_id
                    });
                });

                let data = {
                    name: first_name + ' ' + last_name,
                    company_name: company_name,
                    country: country,
                    country_code: country_code,
                    address: address,
                    appartment: appartment,
                    city: city,
                    state: state,
                    state_code: state_code,
                    contact_no: contact_no,
                    zip_code: zip_code,
                    email: email,
                    notes: notes,
                    payment_method: payment,
                    grand_total: grand_total,
                    products: products,
                    shipping_method: shipping_method,
                    shipping_fee: shipping_fee,
                    customer_id: "{{ auth()->user()->customer->id }}",
                    _token: "{{ csrf_token() }}",
                    // add discount amount
                    // discount:
                    discount: $('#discount_amount').val(),
                };

                $('#stripe_pay_btn').text('Processing...');


                $.ajax({
                    url: "{{ route('stripe') }}",
                    type: "POST",
                    data: data,
                    success: function(response) {
                        // if (response.status == 200) {
                        //     toastr.success('Order placed successfully');
                        //     window.location.href = "{{ route('user.orders.index') }}";
                        // }
                        // if(response.status == 200){
                        //     var stripe = Stripe(response.stripe_key);
                        //     stripe.redirectToCheckout({
                        //         sessionId: response.session_id
                        //     }).then(function (result) {
                        //         console.log(result.error.message);
                        //     });
                        // }

                        if (response.status == 200) {
                            window.location.href = response.url;
                        }
                    },
                    complete: function() {
                        $('#stripe_pay_btn').text('Place Order');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // If validation fails
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                // if(key == "state"){
                                //     $('#state').addClass('is-invalid');
                                // }
                                toastr.error(value[0]); // Display first error message
                            });
                        } else {
                            toastr.error('An error occurred while processing your request.');
                        }
                    },
                });
            });

            $(document).on('click', '#paypal_pay_btn', function() {
                let first_name = $('#first_name').val();
                let last_name = $('#last_name').val();
                let company_name = $('#company_name').val();
                let country = $('#country').val();
                let country_code = $('#country').val();
                let state_code = $('#state_code').val();
                let address = $('#address').val();
                let appartment = $('#appartment').val();
                let city = $('#city').val();
                let state = $('#state').val();
                let contact_no = $('#contact_no').val();
                let zip_code = $('#zip_code').val();
                let email = $('#email').val();
                let notes = $('#notes').val();
                let payment = $('input[name="payment"]:checked').val();
                let grand_total = $('#grand_total').text().replace('$', '');
                let shipping_method = $('input[name="shippingOption"]:checked').val();
                let shipping_fee = $('#shipping_fee').text().replace('$', '');
                let products = [];
                $('.cartItems').each(function() {
                    let product_id = $(this).find('.quantityInput').data('product_id');
                    let product_price = $(this).find('.quantityInput').data('product_price');
                    let quantity = $(this).find('.quantityInput').val();
                    let warehouse_id = $(this).find('.quantityInput').data('warehouse_id');
                    products.push({
                        product_id: product_id,
                        price: product_price,
                        quantity: quantity,
                        warehouse_id: warehouse_id
                    });
                });

                let data = {
                    name: first_name + ' ' + last_name,
                    company_name: company_name,
                    country: country,
                    country_code: country_code,
                    address: address,
                    appartment: appartment,
                    city: city,
                    state: state,
                    state_code: state_code,
                    contact_no: contact_no,
                    zip_code: zip_code,
                    email: email,
                    notes: notes,
                    payment_method: payment,
                    grand_total: grand_total,
                    products: products,
                    shipping_method: shipping_method,
                    shipping_fee: shipping_fee,
                    customer_id: "{{ auth()->user()->customer->id }}",
                    _token: "{{ csrf_token() }}",
                    discount: $('#discount_amount').val(),
                };

                $('#paypal_pay_btn').text('Processing...');


                $.ajax({
                    url: "{{ route('paypal') }}",
                    type: "POST",
                    data: data,
                    success: function(response) {

                        if (response.status == 200) {
                            window.location.href = response.url;
                        }

                    },
                    complete: function() {
                        $('#paypal_pay_btn').text('Place Order');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // If validation fails
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                // if(key == "state"){
                                //     $('#state').addClass('is-invalid');
                                // }
                                toastr.error(value[0]); // Display first error message
                            });
                        } else {
                            toastr.error('An error occurred while processing your request.');
                        }
                    },

                });
            });
            $(document).on('click', '#credit_balance_pay_btn', function() {
                let first_name = $('#first_name').val();
                let last_name = $('#last_name').val();
                let company_name = $('#company_name').val();
                let country = $('#country').val();
                let country_code = $('#country').val();
                let state_code = $('#state_code').val();
                let address = $('#address').val();
                let appartment = $('#appartment').val();
                let city = $('#city').val();
                let state = $('#state').val();
                let contact_no = $('#contact_no').val();
                let zip_code = $('#zip_code').val();
                let email = $('#email').val();
                let notes = $('#notes').val();
                let payment = $('input[name="payment"]:checked').val();
                let grand_total = $('#grand_total').text().replace('$', '');
                let shipping_method = $('input[name="shippingOption"]:checked').val();
                let shipping_fee = $('#shipping_fee').text().replace('$', '');
                let products = [];
                $('.cartItems').each(function() {
                    let product_id = $(this).find('.quantityInput').data('product_id');
                    let product_price = $(this).find('.quantityInput').data('product_price');
                    let quantity = $(this).find('.quantityInput').val();
                    let warehouse_id = $(this).find('.quantityInput').data('warehouse_id');
                    products.push({
                        product_id: product_id,
                        price: product_price,
                        quantity: quantity,
                        warehouse_id: warehouse_id
                    });
                });

                let data = {
                    name: first_name + ' ' + last_name,
                    company_name: company_name,
                    country: country,
                    country_code: country_code,
                    address: address,
                    appartment: appartment,
                    city: city,
                    state: state,
                    state_code: state_code,
                    contact_no: contact_no,
                    zip_code: zip_code,
                    email: email,
                    notes: notes,
                    payment_method: payment,
                    grand_total: grand_total,
                    products: products,
                    shipping_method: shipping_method,
                    shipping_fee: shipping_fee,
                    customer_id: "{{ auth()->user()->customer->id }}",
                    _token: "{{ csrf_token() }}",
                    discount: $('#discount_amount').val(),
                };

                $('#credit_balance_pay_btn').text('Processing...');


                $.ajax({
                    url: "{{ route('usecreditbalance') }}",
                    type: "POST",
                    data: data,
                    success: function(response) {

                        console.log(response);
                        if (response.status === 200) {
                            window.location.href = response.url;
                        } else if (response.status === 400) {
                            toastr.error(response.message);
                        } else {
                            toastr.error(response.error || 'An error occurred.');
                        }
                    },
                    complete: function() {
                        $('#credit_balance_pay_btn').text('Place Order');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // If validation fails
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                // if(key == "state"){
                                //     $('#state').addClass('is-invalid');
                                // }
                                toastr.error(value[0]); // Display first error message
                            });
                        } else {
                            toastr.error('An error occurred while processing your request.');
                        }
                    },

                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const paymentRadios = document.querySelectorAll('input[name="payment"]');
            const placeOrderButton = document.getElementById('place_order');

            paymentRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // console.log("chang")
                    if (this.value === 'Stripe Payment') {
                        placeOrderButton.id = 'stripe_pay_btn';
                        console.log("change into stripe")
                    } else if (this.value === 'Paypal Payment') {
                        placeOrderButton.id = 'paypal_pay_btn';
                        console.log("change into paypal")
                    } else if (this.value === 'Cash on delivery') {
                        placeOrderButton.id = 'place_order';
                        console.log("change into place")
                    } else if (this.value === 'Credit Balance') {
                        placeOrderButton.id = 'credit_balance_pay_btn';
                        console.log("change into credit balance")
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#shipping_address').change(function() {
                let address_id = $(this).val();
                if (address_id) {
                    let address = $(this).find(':selected').data('address');

                    let first_name = address.name.split(' ')[0];
                    let last_name = address.name.split(' ')[1];


                    $('#first_name').val(first_name);
                    $('#last_name').val(last_name);
                    $('#contact_no').val(address.phone_no);
                    $('#email').val(address.email);
                    $('#address').val(address.address);
                    $('#appartment').val(address.address_line_1);
                    $('#city').val(address.city);
                    $('#state').val(address.state);
                    $('#state_code').val(address.state_code);
                    $('#country').val(address.country);
                    $('#country_code').val(address.country_code);
                    $('#zip_code').val(address.postal_code);

                } else {
                    let address = $(this).find(':selected').data('address');

                    console.log(address)
                    let first_name = address.name.split(' ')[0];
                    let last_name = address.name.split(' ')[1];

                    $('#first_name').val(first_name);
                    $('#last_name').val(last_name);
                    $('#contact_no').val(address.contact_no);
                    $('#email').val(address.email);
                    $('#address').val(address.address);
                    $('#appartment').val(address.address_line_1);
                    $('#city').val(address.customer.city);
                    $('#state').val(address.state);
                    $('#state_code').val(address.state_code);
                    $('#country').val(address.customer.country);
                    $('#country_code').val(address.country_code);
                    $('#zip_code').val(address.postal_code);
                }

            });
        });

        $(document).on('click', '#next_btn', function() {
            if ($('#first_name').val() == '' || $('#last_name').val() == '' || $('#contact_no').val() == '' || $(
                    '#email').val() == '' ||
                $('#address').val() == '' || $('#city').val() == '' || $('#state').val() == '' || $('#state_code')
                .val() == '' ||
                $('#country').val() == '' || $('#country_code').val() == '' || $('#zip_code').val() == '') {
                toastr.error('All fields are required');
                return;
            }

            // fedex api address validation
            $('#fedex-spinner').show();
            // $.ajax({
            //     url: '{{ route('address-validation') }}',
            //     type: 'POST',
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     data: {
            //         address: $('#address').val(),
            //         city: $('#city').val(),
            //         state_code: $('#state_code').val(),
            //         postal_code: $('#zip_code').val(),
            //         country_code: $('#country_code').val(),
            //     },
            //     success: function(response) {
            //         console.log(response);
            //         console.log("Response Status:", response.status);

            //         if (response.status === 200) {
            //             // alert('Address is valid');
            //             // toastr.success('Address is valid');
            //             $('#next_btn').hide();
            //             $('#fedex_api_section').show();
            //             getFedexMethods();
            //         } else {
            //             toastr.error('Invalid address. Please enter a valid address.');
            //             $('#fedex-spinner').hide();
            //             window.location.reload();
            //         }
            //     },
            //     error: function(xhr) {
            //         toastr.error('Invalid address. Please enter a valid address.');
            //         $('#fedex-spinner').hide();
            //         window.location.reload();
            //     }
            // });





            // function fedexPostalCodeValidation(){
            //     // fedex postal code validation
            //     let postal_code = $('#zip_code').val();
            //     let country_code = $('#country_code').val();
            //     let state_code = $('#state_code').val();

            //     $.ajax({
            //         url: '{{ route('postal-code-validation') }}',
            //         type: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             postal_code: postal_code,
            //             country_code: country_code,
            //         },
            //         success: function(response) {
            //             console.log(response);
            //             console.log("Response Status:", response.status);

            //             if (response.status === 200) {
            //                 toastr.success('Address is valid');
            //                 $('#next_btn').hide();
            //                 $('#fedex_api_section').show();
            //                 getFedexMethods();
            //             } else {
            //                 toastr.error('Invalid address. Please enter a valid address.');
            //                 $('#fedex-spinner').hide();
            //                 // window.location.reload();
            //             }
            //         },
            //         error: function(xhr) {
            //             toastr.error('Invalid address. Please enter a valid address.');
            //             $('#fedex-spinner').hide();
            //             // window.location.reload();
            //         }
            //     });

            // }


            let customer_id = {{ auth()->user()->customer->id }};
            let carts = @json(auth()->user()->carts);

            // Check if there is at least one cart item
            let warehouse_id = carts.length > 0 ? carts[0].warehouse_id : null;
            // alert(warehouse_id);


            let customer_postal_code = $('#zip_code').val();
            let customer_country_code = $('#country_code').val();



            // function getFedexMethods() {
            //     $.ajax({
            //         url: '{{ route('calculate-rates-client-side') }}',
            //         type: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             customer_postal_code: customer_postal_code,
            //             customer_country_code: customer_country_code,
            //             warehouse_id: warehouse_id,
            //         },
            //         success: function(response) {
            //             console.log(response);
            //             console.log("Response Status:", response.status);

            //             if (response.status === 200) {

            //                 const serviceTypeNames = {
            //                     'FIRST_OVERNIGHT': 'First Overnight',
            //                     'PRIORITY_OVERNIGHT': 'Priority Overnight',
            //                     'STANDARD_OVERNIGHT': 'Standard Overnight',
            //                     'FEDEX_2_DAY': 'FedEx 2 Day',
            //                     'FEDEX_EXPRESS_SAVER': 'FedEx Express Saver',
            //                     'FEDEX_GROUND': 'FedEx Ground',
            //                 };

            //                 let fedexApiSection = $('#fedex_api_section');
            //                 fedexApiSection.show(); // Show the section if it was hidden

            //                 let shippingOptionsHtml = '';

            //                 response.charges.forEach(function(method, index) {
            //                     let displayName = serviceTypeNames[method.serviceType] || method
            //                         .serviceType;
            //                     let estimatedDelivery = calculateEstimatedDeliveryTime(method
            //                         .serviceType);
            //                     let cost = `$${method.totalNetCharge}`;

            //                     shippingOptionsHtml += `
        //                         <label class="list-group-item d-flex align-items-center mt-4">
        //                             <input class="form-check-input me-2 shipping-option" id="shippingOption_${index}" type="radio" name="shippingOption" value="${method.serviceType}" data-cost="${method.totalNetCharge}">
        //                             <div class="d-flex justify-content-between w-100">
        //                                 <div>
        //                                     <img src="https://static.cellairisrepairparts.com/skin/frontend/msentrix2022/default/images/new-checkout/fedex.svg" alt="FedEx" class="me-2" style="width: 40px;">
        //                                     <strong>${displayName}</strong>
        //                                     <br><small class="text-muted">Estimate Delivery Time ${estimatedDelivery}</small>
        //                                 </div>
        //                                 <span class="ms-auto">${cost}</span>
        //                             </div>
        //                         </label>
        //                     `;
            //                 });

            //                 fedexApiSection.find('.list-group').html(shippingOptionsHtml);


            //             } else {
            //                 toastr.error('Failed to retrieve shipping options.');
            //             }
            //         },
            //         error: function(xhr) {
            //             toastr.error('Shipping fee not added, possibly due to an invalid address.');
            //             // window.location.reload();
            //         },
            //         complete: function() {

            //             $('#fedex-spinner').hide();
            //         }
            //     });
            // }

            function getFedexMethods() {

                $.ajax({
                    url: '{{ route('calculate-rates-client-side') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        customer_postal_code: customer_postal_code,
                        customer_country_code: customer_country_code,
                        warehouse_id: warehouse_id,
                    },
                    success: function(response) {
                        console.log(response);
                        console.log("Response Status:", response.status);

                        if (response.status === 200) {

                            const serviceTypeNames = {
                                'FIRST_OVERNIGHT': 'First Overnight',
                                'PRIORITY_OVERNIGHT': 'Priority Overnight',
                                'STANDARD_OVERNIGHT': 'Standard Overnight',
                                'FEDEX_2_DAY': 'FedEx 2 Day',
                                'FEDEX_EXPRESS_SAVER': 'FedEx Express Saver',
                                'FEDEX_GROUND': 'FedEx Ground',
                            };

                            let fedexApiSection = $('#fedex_api_section');
                            fedexApiSection.show(); // Show the section if it was hidden

                            let shippingOptionsHtml = '';

                            // Loop through the FedEx rates and add them to the options
                            response.charges.forEach(function(method, index) {
                                let displayName = serviceTypeNames[method.serviceType] || method
                                    .serviceType;
                                let estimatedDelivery = calculateEstimatedDeliveryTime(method
                                    .serviceType);
                                let cost = `$${method.totalNetCharge}`;

                                shippingOptionsHtml += `
                                    <label class="list-group-item d-flex align-items-center mt-4">
                                        <input class="form-check-input me-2 shipping-option" id="shippingOption_${index}" type="radio" name="shippingOption" value="${method.serviceType}" data-cost="${method.totalNetCharge}">
                                        <div class="d-flex justify-content-between w-100">
                                            <div>
                                                <img src="https://static.cellairisrepairparts.com/skin/frontend/msentrix2022/default/images/new-checkout/fedex.svg" alt="FedEx" class="me-2" style="width: 40px;">
                                                <strong>${displayName}</strong>
                                                <br><small class="text-muted">Estimate Delivery Time ${estimatedDelivery}</small>
                                            </div>
                                            <span class="ms-auto">${cost}</span>
                                        </div>
                                    </label>
                                `;
                            });

                            fedexApiSection.find('.list-group').html(shippingOptionsHtml);

                        } else {
                            toastr.error('Failed to retrieve shipping options.');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Shipping fee not added, possibly due to an invalid address.');
                    },
                    complete: function() {
                        $('#fedex-spinner').hide();

                        // Add the "Store Pickup" option on complete
                        let storePickupOption = `
                            <label class="list-group-item d-flex align-items-center mt-4">
                                <input class="form-check-input me-2 shipping-option" id="shippingOption_store_pickup" type="radio" name="shippingOption" value="STORE_PICKUP" data-cost="0">
                                <div class="d-flex justify-content-between w-100">
                                    <div>
                                        <img src="{{ asset('front/assets/img/store-pickup.png') }}" alt="Store Pickup" class="me-2" style="width: 40px;">
                                        <strong>Store Pickup</strong>
                                        <br><small class="text-muted">Pick up your order at the store.</small>
                                    </div>
                                    <span class="ms-auto">$0.00</span>
                                </div>
                            </label>
                        `;

                        // Add the "Free Shipping" option on complete
                        let freeShippingOption = `
                            <label class="list-group-item d-flex align-items-center mt-4">
                                <input class="form-check-input me-2 shipping-option" id="shippingOption_free_shipping" type="radio" name="shippingOption" value="FREE_SHIPPING" data-cost="0">
                                <div class="d-flex justify-content-between w-100">
                                    <div>
                                        <img src="{{ asset('front/assets/img/free-shipping.jpg') }}" alt="Store Pickup" class="me-2" style="width: 40px;">
                                        <strong>Free Shipping</strong>
                                        <br><small class="text-muted">Shipping in 0 fee</small>
                                    </div>
                                    <span class="ms-auto">$0.00</span>
                                </div>
                            </label>
                        `;

                        $('#fedex_api_section .list-group').append(freeShippingOption);
                        $('#fedex_api_section .list-group').append(storePickupOption);
                    }

                });
            }


            getFedexMethods();


            // Function to calculate the estimated delivery time dynamically
            function calculateEstimatedDeliveryTime(serviceType) {
                const now = new Date();
                const dayOfWeek = now.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
                let estimatedTime;

                switch (serviceType) {
                    case 'FIRST_OVERNIGHT':
                        estimatedTime = new Date(now);
                        if (dayOfWeek === 5 || dayOfWeek === 6) {
                            estimatedTime.setDate(now.getDate() + (dayOfWeek === 5 ? 3 : 2));
                        } else {
                            estimatedTime.setDate(now.getDate() + 1);
                        }
                        estimatedTime.setHours(8, 0, 0, 0);
                        break;
                    case 'PRIORITY_OVERNIGHT':
                        estimatedTime = new Date(now);
                        if (dayOfWeek === 5 || dayOfWeek === 6) {
                            estimatedTime.setDate(now.getDate() + (dayOfWeek === 5 ? 3 : 2));
                        } else {
                            estimatedTime.setDate(now.getDate() + 1);
                        }
                        estimatedTime.setHours(10, 30, 0, 0);
                        break;
                    case 'STANDARD_OVERNIGHT':
                        estimatedTime = new Date(now);
                        if (dayOfWeek === 5 || dayOfWeek === 6) {
                            estimatedTime.setDate(now.getDate() + (dayOfWeek === 5 ? 3 : 2));
                        } else {
                            estimatedTime.setDate(now.getDate() + 1);
                        }
                        estimatedTime.setHours(12, 0, 0, 0);
                        break;
                    case 'FEDEX_2_DAY':
                        estimatedTime = new Date(now);
                        if (dayOfWeek === 5) {
                            estimatedTime.setDate(now.getDate() + 4);
                        } else if (dayOfWeek === 6) {
                            estimatedTime.setDate(now.getDate() + 4);
                        } else {
                            estimatedTime.setDate(now.getDate() + 2);
                        }
                        estimatedTime.setHours(16, 30, 0, 0);
                        break;
                    case 'FEDEX_EXPRESS_SAVER':
                        estimatedTime = new Date(now);
                        if (dayOfWeek === 5) {
                            estimatedTime.setDate(now.getDate() + 5);
                        } else if (dayOfWeek === 6) {
                            estimatedTime.setDate(now.getDate() + 5);
                        } else {
                            estimatedTime.setDate(now.getDate() + 3);
                        }
                        estimatedTime.setHours(16, 30, 0, 0);
                        break;
                    case 'FEDEX_GROUND':
                        estimatedTime = new Date(now);
                        estimatedTime.setDate(now.getDate() + 5);
                        estimatedTime.setHours(17, 0, 0, 0);
                        break;
                    default:
                        estimatedTime = new Date(now);
                        estimatedTime.setDate(now.getDate() + 5);
                        estimatedTime.setHours(17, 0, 0, 0);
                        break;
                }

                // Format the estimated time as a string
                return estimatedTime.toLocaleString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                });
            }



            $('#shipping_section').hide();
            $('#next_btn').hide();
            $('#fedex_api_section').show();
            // $('#place_order').show();
        });
    </script>
@endsection
