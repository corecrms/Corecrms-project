@extends('user.layout.app')

@section('content')
    @auth
        <!-- Order Status -->
        <section class="order-status bg-light py-5">
            <div class=" container">
                <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
                    <div>
                        <h2 class="heading fs-4 m-0">
                            <i class="bi bi-1-circle-fill"></i> SHOPPING CART
                        </h2>
                    </div>
                    <div>
                        <h2 class="heading fs-4 mx-2 m-0 checkout-border text-secondary">
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
                    You are out of time! Checkout now to avoid losing your order!
                </p>
            </div>
        </section>
        <!-- End Order Status -->
        <!-- Checkout -->
        <section class="mt-5">
            <div class="container-xxl container-fluid">
                <div class="row">
                    <div class="col-md-7 mt-3">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-secondary">Product</th>
                                        <th class="text-secondary">Price</th>
                                        <th class="text-secondary">SKU</th>
                                        <th class="text-secondary">Quantity</th>
                                        <th class="text-secondary">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grand_total = 0;
                                    @endphp
                                    @forelse ($cartItems as $item)
                                        @php
                                            $subtotal = $item->price * $item->quantity;
                                            $grand_total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-3 align-items-center">
                                                    <div>
                                                        {{-- <img src="{{ asset('storage' . $item->product->images[0]['img_path']) }}" width="70" alt=""
                                                            class="img-fluid" /> --}}
                                                        @if (count($item->product->images) > 0)
                                                            <img src="{{ asset('/storage' . $item->product->images[0]['img_path']) }}"
                                                                alt="No" class="img-fluid" width="70">
                                                        @else
                                                            <img src="{{ asset('back/assets/image/no-image.png') }}"
                                                                alt="" class="img-fluid" width="70" />
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h2 class="heading fs-6 mb-1">
                                                            {{ $item->product->name ?? 'N/A' }}
                                                        </h2>
                                                        {{-- <a href="#" class="text-decoration-underline text-dark">Remove</a> --}}
                                                        <form action="{{ route('add-to-cart.destroy', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                                            <button type="submit"
                                                                class="btn btn-link text-decoration-underline text-dark">Remove</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle product-price">${{ number_format($item->price ?? '0.00',2) }}</td>
                                            <td class="align-middle">{{ $item->product->sku ?? '344343' }}</td>
                                            <td class="align-middle">
                                                <div class="quantity d-flex">
                                                    <button class="btn bag-btn text-white minusBtn rounded-0 px-1">
                                                        -
                                                    </button>
                                                    <input type="number"
                                                        class="form-control quantityInput rounded-end-0 rounded-start-0"
                                                        style="width: 50px" value="{{ $item->quantity ?? '344343' }}" />
                                                    <button class="btn bag-btn text-white plusBtn rounded-0 px-1">
                                                        +
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="align-middle product-subtotal">
                                                ${{ number_format($item->price * $item->quantity ?? '0.00',2) }}</td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No Product Found!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-5 mt-3 p-3">
                        <div class="border border-2 p-3">
                            <h2 class="heading border-bottom py-3">Cart Totals</h2>
                            <div class="border-bottom pb-3">
                                <div class="d-flex justify-content-between">
                                    <p class="m-0 mt-2 heading">Subtotal</p>
                                    <p class="m-0 mt-2 heading" id="subtotal">${{ number_format($grand_total ?? '0' ,2) }}</p>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <p class="m-0 mt-3 heading fw-bold">Total</p>
                                    <p class="m-0 mt-3 heading fw-bold" id="grand_total">${{ number_format($grand_total ?? '0' ,2) }}</p>
                                </div>
                            </div>
                            <a href="{{ route('user.checkout') }}" class="btn bag-btn text-white rounded-5 w-100 py-2 mt-3">
                                Proceed to checkout
                            </a>
                            <a href="{{ route('shop') }}" class="btn bag-btn text-white rounded-5 w-100 py-2 mt-3">
                                Continue shopping
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h2 class="heading text-center fw-bold my-3 fs-4">You May Be Interested In…</h2>
                    @foreach ($products as $product)
                        <div class="selling-product col-lg-2 col-md-3 p-2">
                            <a
                                href="{{ route('user.product.index', ['code' => $product->category->code, 'sku' => $product->sku]) }}">
                                <div class="card tab-card border-0 rounded-0">
                                    <div class="card-header p-0 border-0 bg-transparent">
                                        @if (count($product->images) > 0)
                                            <img src="{{ asset('/storage' . $product->images[0]['img_path']) }}" alt="No"
                                                class="img-fluid">
                                        @else
                                            <img src="{{ asset('back/assets/image/no-image.png') }}" alt=""
                                                class="" />
                                        @endif
                                    </div>
                                    <div class="card-body text-center">
                                        <p class="product-category">{{ $product->category->name ?? '' }}</p>
                                        <h3 class="product-name">
                                            <a
                                                href="{{ route('user.product.index', ['code' => $product->category->code, 'sku' => $product->sku]) }}">{{ $product->name ?? '' }}</a>
                                        </h3>
                                        <div class="text-warning">
                                            @for ($i = 0; $i < $product->averageRating(); $i++)
                                                <i class="fa-solid fa-star"></i>
                                            @endfor
                                        </div>
                                        @auth

                                            <h4 class="product-price product-old-price">
                                                ${{ $product->sell_price ?? '0.00' }}
                                            </h4>
                                        @endauth
                                        {{-- <h4 class="product-price product-old-price">${{ $product->sell_price ?? '0.00' }}</h4> --}}
                                    </div>

                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
        <!-- End Checkout -->
    @else
        <div class="mt-3 d-flex justify-content-center align-items-center" style="height: 50vh">
            <h4>Please <a href="{{ route('login') }}">login</a> to see cart items...</h4>
        </div>
    @endauth
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.plusBtn').click(function() {
                var input = $(this).parent().find('.quantityInput');
                var value = parseInt(input.val());
                if (value < 100) {
                    value = value + 1;
                }
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
                if (value > 100) {
                    value = 100;
                }
                $(this).val(value);
                let price = $(this).closest('tr').find('.product-price').text().replace('$', '');
                let quantity = $(this).val();
                let subtotal = price * quantity;
                $(this).closest('tr').find('.product-subtotal').text('$' + subtotal.toFixed(2));
                calculate();

            });

            function calculate() {
                let total = 0;
                $('.product-subtotal').each(function() {
                    total += parseFloat($(this).text().replace('$', ''));
                });
                $('#subtotal').text('$' + total.toFixed(2));
                $('#grand_total').text('$' + total.toFixed(2));
            }
        });
    </script>
@endsection
