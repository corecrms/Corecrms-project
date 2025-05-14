@extends('user.layout.app')

@section('style')
    <style>
        .star-label {
            font-size: 52px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>

    <link href="{{ asset('front/exzoom/jquery.exzoom.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous">
    </script>
@endsection

@section('content')
    <!-- Bredcrumbs -->
    <div class="container-xxl container-fluid">
        <div class="d-flex gap-2 mt-3">
            <p class="m-0">
                <a href="/" class="text-decoration-none text-secondary">Home</a><i class="bi bi-chevron-right"></i>
            </p>
            <p class="m-0">{{ $product->name ?? '' }}</p>
        </div>
    </div>
    <!-- End Bredcrumbs -->

    @auth
        <section class="mt-3">
            <div class="container-xxl container-fluid">
                <div class="row">
                    <div class="col-md-9 border-end mt-3">
                        <h3>{{ $product->name ?? 'N/A' }}</h3>

                        <div class="d-flex gap-2 flex-wrap">
                            <div class="border-end d-flex gap-2">
                                <div class="text-warning">
                                    @for ($i = 0; $i < $product->averageRating(); $i++)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                </div>
                                <p class="m-0 me-2">({{ $product->reviews->count() }} customer review)</p>
                            </div>
                            <div class="border-end">
                                <p class="m-0 me-2" id="wishlist" style="cursor: pointer;"
                                    data-product-id="{{ $product->id }}"
                                    data-user-id="{{ auth()->check() ? auth()->user()->id : '' }}">
                                    <i class=" {{ $wishlist ? 'fa' : 'fa-regular' }} fa-heart me-2 text-danger"></i>Add to
                                    Wishlist
                                </p>
                            </div>
                        </div>
                        <p class="mt-2">SKU: <span>{{ $product->sku ?? 'N/A' }}</span></p>
                        <div class="row p-3">
                            <div class="col-md-6 border rounded-4">

                                <div class="exzoom" id="exzoom">
                                    <!-- Images -->
                                    <div class="exzoom_img_box">
                                        <ul class='exzoom_img_ul'>
                                            @if (count($product->images) > 0)
                                                @foreach ($product->images as $item)
                                                    <li>
                                                        <img src="{{ asset('/storage' . $item['img_path']) }}" alt="No"
                                                            class="img-fluid w-100">
                                                    </li>
                                                @endforeach
                                            @else
                                                <li> <img src="{{ asset('back/assets/image/no-image.png') }}" alt=""
                                                        class="img-fluid w-100" />
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                    <div class="exzoom_nav"></div>
                                    <!-- Nav Buttons -->
                                    <p class="exzoom_btn">
                                        <a href="javascript:void(0);" class="exzoom_prev_btn">
                                            < </a>
                                                <a href="javascript:void(0);" class="exzoom_next_btn"> > </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="purple-txtcolor fs-4">${{ $product->sell_price ?? '0.00' }}</p>
                              
                                <p class="m-0 mt-2 fw-bold fs-6">GET IT TODAY</p>
                                <p class="m-0 mt-2 fs-6">
                                    <i class="fa-solid fa-house-flag me-1 purple-txtcolor"></i><span
                                        class="fw-bold me-1">Pickup:</span>
                                    <span id="warehouse_address">Unavailable</span>
                                </p>

                                {{-- <p class="m-0 mt-2 fs-6">
                                   
                                    <i class="fa-solid fa-check me-1"></i><span
                                        id="stock">{{ $product->product_warehouses->first()->quantity ?? 'Out of Stock' }}</span>
                                    in stock
                                </p> --}}
                                <p class="m-0 mt-2 fs-6">
                                    <i class="fa-solid fa-check me-1"></i>
                                    <span id="stock">
                                        {{ ($product->product_warehouses->first()->quantity ?? 0) > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </p>
                                
                                
                                <form action="{{ route('add-to-cart.index') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="form-group">
                                        <input type="hidden" name="warehouse_stock" id="warehouse_stock" value="0">
                                        <label for="warehouse_id" class="mb-1">Select Warehouse</label>
                                        <select class="form-select" id="warehouse_id" name="warehouse_id">
                                            @php
                                                $warehouses = $product->product_warehouses->filter(function (
                                                    $warehouse,
                                                ) {
                                                    return $warehouse->quantity > 0;
                                                });
                                            @endphp

                                            @if ($warehouses->isNotEmpty())
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->warehouse->id }}"
                                                        data-quantity="{{ $warehouse->quantity }}"
                                                        data-address="{{ $warehouse->warehouse->users->address }}"
                                                        >
                                                        {{ $warehouse->warehouse->users->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="" selected>No Warehouse Available</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="d-flex gap-3 align-items-center mt-3">
                                        <div class="quantity d-flex justify-content-center w-25">
                                            <button class="btn bag-btn text-white minusBtn rounded-0 px-1" type="button">
                                                -
                                            </button>
                                            <input type="number"
                                                class="form-control quantityInput rounded-end-0 rounded-start-0 text-center"
                                                value="1" name="quantity" />
                                            <button class="btn bag-btn text-white plusBtn rounded-0 px-1" type="button">
                                                +
                                            </button>
                                        </div>
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="price" value="{{ $product->sell_price }}">
                                        <button class="btn bag-btn text-white rounded-5 w-100 py-2" type="submit">
                                            <i class="bi bi-bag me-1"></i>Add to cart
                                        </button>

                                    </div>
                                </form>
                                <p class="or-text text-center mt-4">
                                    <span class="bg-white px-2">OR</span>
                                </p>
                                {{-- <button class="btn bag-btn text-white rounded-5 w-100 py-2">
                                    <i class="bi bi-bag me-2"></i>Buy Now
                                </button> --}}
                                <div class="mt-2 p-3"
                                    style="
                                    background: url({{ asset('front/assets/img/product-preview.jpeg') }});
                                    background-repeat: no-repeat;
                                    ">
                                    <h2 class="heading mb-1">Have Question?</h2>
                                    <p>Our expert are ready to help.</p>
                                    <h2 class="heading mt-4 purple-txtcolor">Call: {{ $setting->company_phone ?? '+12345679' }}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <!-- tabs -->
                        <div class="mt-3">
                            <ul class="nav nav-tabs border-bottom pb-3" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active border-0 purple-txtcolor heading fs-5" id="home-tab"
                                        data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab"
                                        aria-controls="home-tab-pane" aria-selected="true">
                                        Description
                                    </button>
                                </li>
                                {{-- <li class="nav-item" role="presentation">
                                    <button class="nav-link border-0 purple-txtcolor heading fs-5" id="profile-tab"
                                        data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button"
                                        role="tab" aria-controls="profile-tab-pane" aria-selected="false">
                                        Additional Information
                                    </button>
                                </li> --}}
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link border-0 purple-txtcolor heading fs-5" id="contact-tab"
                                        data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button"
                                        role="tab" aria-controls="contact-tab-pane" aria-selected="false">
                                        Reviews ({{ $product->reviews->count() }})
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
                                    aria-labelledby="home-tab" tabindex="0">
                                    <p class="mt-4">
                                        {!! $product->description ?? 'N/A' !!}
                                    </p>

                                    {{-- <div class="p-5 ps-4 mt-4"
                                        style="
                                            background: url({{ asset('front/assets/img/home-4.jpeg') }});
                                            background-repeat: no-repeat;
                                            height: 47vh;
                                        ">
                                        <h3 class="title fs-3 mt-4 p-5 py-3 text-white">
                                            Start your day with tasty <br class="d-none d-md-block" />
                                            organic veggies
                                        </h3>
                                        <a class="btn bag-btn text-white rounded-5 m-5 my-3 p-2 px-3"
                                            href="#"><span>Start Shopping Now!</span>
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div> --}}
                                </div>
                                {{-- <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel"
                                    aria-labelledby="profile-tab" tabindex="0">
                                    <div class="row border-bottom py-3 mt-4">
                                        <div class="col-md-4 col-5 border-end border-dark">
                                            <p class="m-0">Type</p>
                                        </div>
                                        <div class="col-md-8 col-7">
                                            <p class="m-0">Organic, Vegetarian</p>
                                        </div>
                                    </div>
                                    <div class="row border-bottom py-3">
                                        <div class="col-md-4 col-5 border-end border-dark">
                                            <p class="m-0">Color</p>
                                        </div>
                                        <div class="col-md-8 col-7">
                                            <p class="m-0">White, Brown</p>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel"
                                    aria-labelledby="contact-tab" tabindex="0">
                                    <div class="row mt-4">
                                        <div class="col-md-6 mt-3">
                                            <h2 class="heading border-bottom py-3">
                                                {{ $product->reviews->count() }} REVIEW FOR {{ $product->name ?? '' }}
                                            </h2>

                                            <div class="" style="overflow-y: auto;height: 447px;">
                                                @forelse ($product->reviews as $review)
                                                    <div class="border rounded-3 p-3 mt-2">
                                                        <div class="d-flex gap-2">
                                                            <div>
                                                                <h2 class="heading fs-6 m-0">
                                                                    {{ $review->name ?? '' }}</h2>
                                                            </div>
                                                            <div>
                                                                <p class="m-0 text-secondary fs-6">
                                                                    {{ $review->created_at->diffForHumans() }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            <div>
                                                                <p class="m-0 text-secondary fs-6">{{ $review->review ?? '' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p>There are no reviews yet.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <h2 class="heading border-bottom py-3">
                                                {{ $product->reviews->count() }} REVIEW FOR {{ $product->name ?? '' }}
                                            </h2>

                                            <div>
                                                <h2 class="fw-bold heading fs-5">
                                                    Your Rating <span class="text-danger">*</span>
                                                </h2>

                                                <article>
                                                    <form class="star">
                                                        <label class="star-label" style="cursor: pointer;font-size: 28px;">
                                                            ★
                                                            <input type="radio" name="note" value="1">
                                                        </label>
                                                        <label class="star-label" style="cursor: pointer;font-size: 28px;">
                                                            ★
                                                            <input type="radio" name="note" value="2">
                                                        </label>
                                                        <label class="star-label" style="cursor: pointer;font-size: 28px;">
                                                            ★
                                                            <input type="radio" name="note" value="3">
                                                        </label>
                                                        <label class="star-label" style="cursor: pointer;font-size: 28px;">
                                                            ★
                                                            <input type="radio" name="note" value="4">
                                                        </label>
                                                        <label class="star-label" style="cursor: pointer;font-size: 28px;">
                                                            ★
                                                            <input type="radio" name="note" value="5">
                                                        </label>
                                                    </form>
                                                </article>

                                            </div>

                                            <form action="{{ route('product-reviews.store') }}" method="POST">
                                                @csrf
                                                {{-- Agent id --}}
                                                <div class="mb-3">
                                                    <input type="hidden" name="product_id" id="product_id"
                                                        value="{{ $product->id ?? '' }}" class="form-control" />
                                                </div>
                                                {{-- Rating number --}}
                                                <div class="mb-3">
                                                    <input type="hidden" name="rating" id="rating"
                                                        class="form-control" />
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="review" class="mb-1">Your Review</label>
                                                    <textarea class="form-control" id="review" name="review" placeholder="Add Note" rows="5">{{ old('review') }}</textarea>
                                                    @error('review')
                                                        <span class="text-danger">{{ $message ?? '' }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label for="name" class="mb-1">Name</label>
                                                    <input type="text" placeholder="Name" class="form-control"
                                                        name="name" value="{{ auth()->user()->name }}" id="name"
                                                        required />
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label for="email" class="mb-1">Email</label>
                                                    <input type="email" placeholder="example@mail.com" class="form-control"
                                                        id="email" required name="email"
                                                        value="{{ auth()->user()->email }}" />
                                                </div>
                                                <button type="submit" class="btn btn-primary">Submit</button>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Tabs -->
                    </div>
                    <div class="col-md-3 mt-3">
                        <h2 class="heading border-bottom py-3">People also bought</h2>
                        <div>
                            @forelse ($top_selling_products as $product)
                                {{-- {{dd($product)}} --}}
                                <a
                                    href="{{ route('user.product.index', ['code' => $product->product->category->code, 'sku' => $product->product->sku]) }}">
                                    <div class="d-flex gap-3 align-items-center mt-3">
                                        <div>
                                            @if (count($product->product->images) > 0)
                                                <img src="{{ asset('/storage' . $product->product->images[0]['img_path']) }}"
                                                    alt="No" class="img-fluid" width="70">
                                            @else
                                                <img src="{{ asset('back/assets/image/no-image.png') }}" alt=""
                                                    class="" class="img-fluid" width="70" />
                                            @endif
                                        </div>
                                        <div>
                                            <h2 class="heading fs-6 mb-1">
                                                {{ $product->product->name ?? '' }}
                                            </h2>
                                            <p class="m-0 text-secondary fs-6">${{ $product->product->sell_price ?? '0.00' }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p class="mt-3 mb-2 text-center">Products Not Found! </p>
                            @endforelse

                        </div>
                        <div class="services mt-3">
                            <h2 class="heading border-bottom pb-3 mt-4">Our Services</h2>
                            <div class="d-flex gap-3 align-items-center border-bottom py-4 bg-light px-3">
                                <div>
                                    <i class="fa-solid fa-truck-fast fs-1 purple-txtcolor"></i>
                                </div>
                                <div>
                                    <h2 class="heading fs-5 mb-1">
                                        {{ $heading->free_shipping_heading ?? 'FREE SHIPPING' }}
                                    </h2>
                                    <p class="m-0 text-secondary fs-6">
                                        {{ $heading->free_shipping_desc ?? 'Free Shipping On Us' }}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex gap-3 align-items-center border-bottom py-4 bg-light px-3">
                                <div>
                                    <i class="bi bi-box2 fs-1 purple-txtcolor"></i>
                                </div>
                                <div>
                                    <h2 class="heading fs-5 mb-1">
                                        {{ $heading->money_returns_heading ?? 'MONEY RETURNS' }}
                                    </h2>
                                    <p class="m-0 text-secondary fs-6">
                                        {{ $heading->money_returns_desc ?? 'Returns within 30 days' }}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex gap-3 align-items-center border-bottom py-4 bg-light px-3">
                                <div>
                                    <i class="fa-solid fa-wallet fs-1 purple-txtcolor"></i>
                                </div>
                                <div>
                                    <h2 class="heading fs-5 mb-1">
                                        {{ $heading->secure_payment_heading ?? 'SECURE PAYMENT' }}
                                    </h2>
                                    <p class="m-0 text-secondary fs-6">
                                        {{ $heading->secure_payment_desc ?? 'Safe and secure payment' }}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex gap-3 align-items-center py-4 bg-light px-3">
                                <div>
                                    <i class="bi bi-shield-check fs-1 purple-txtcolor"></i>
                                </div>
                                <div>
                                    <h2 class="heading fs-5 mb-1">
                                        {{ $heading->support_heading ?? 'SUPPORT 24/7' }}
                                    </h2>
                                    <p class="m-0 text-secondary fs-6">
                                        {{ $heading->support_desc ?? 'Contact 24 Hours Day' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="special-product">
                            <h2 class="heading border-bottom pb-3 mt-4">Our Services</h2>
                            <div class="card border-2 card-border">
                                <div class="card-header bg-transparent border-0">
                                    <img src="assets/img/product06.png" alt="" class="img-fluid w-100" />
                                </div>
                                <div class="card-body text-center">
                                    <p class="text-secondary m-0 fs-6">Meat & Seafood</p>
                                    <h2 class="heading fs-6 fw-bold">
                                        Angie’s Boomchickapop Sweet & Salty Lupin Beans In Water
                                    </h2>
                                    <div class="text-warning">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <p class="text-secondary m-0 fs-6">$4 - $15</p>
                                    </div>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <div class="rounded-circle p-2 px-3 days-bg text-white">
                                            <p class="fs-6 m-0">565</p>
                                            <p class="fs-6 m-0">Days</p>
                                        </div>
                                        <div class="rounded-circle border p-2 px-3 days-bg text-white">
                                            <p class="fs-6 m-0">565</p>
                                            <p class="fs-6 m-0">Days</p>
                                        </div>
                                        <div class="rounded-circle border p-2 px-3 days-bg text-white">
                                            <p class="fs-6 m-0">565</p>
                                            <p class="fs-6 m-0">Days</p>
                                        </div>
                                        <div class="rounded-circle border p-2 px-3 days-bg text-white">
                                            <p class="fs-6 m-0">565</p>
                                            <p class="fs-6 m-0">Days</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        @if ($ads)
                            
                            <a href="{{$ads->url ?? ''}}"  target="_blank">
                                <div class="p-2 ps-2 mt-4 border-0  w-100 text-center">
                                    <img class="img-fluid rounded-3" src="{{ asset('/storage/' . $ads->image) }}" />
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @else
        <div class="mt-3 d-flex justify-content-center align-items-center" style="height: 75vh">
            <h4>Please <a href="{{ route('login') }}">login</a> to see product detail...</h4>
        </div>
    @endauth

    @include('user.components.brands')
@endsection


@section('scripts')
    <script src="{{ asset('front/exzoom/jquery.exzoom.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on("click", ".plusBtn", function() {
                var $quantityInput = $(this).siblings(".quantityInput");
                $quantityInput.val(parseInt($quantityInput.val()) + 1);
            });

            $(document).on("click", ".minusBtn", function() {
                var $quantityInput = $(this).siblings(".quantityInput");
                var currentValue = parseInt($quantityInput.val());
                if (currentValue > 1) {
                    $quantityInput.val(currentValue - 1);
                }
            });

            // $(document).on("click", "#wishlist", function() {
            //     // $(this).find('i').toggleClass('fa-regular').toggleClass('fa');
            //     $(this).find('i').addClass('fa').removeClass('fa-regular');

            //     var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            //     var user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};

            //     var prod_id = {{ $product->id ? $product->id : 'null' }};

            //     console.log("product")

            //     // alert(prod_id);
            //     if (isAuthenticated) {
            //         // var product_id = {{ $product->id }};
            //         var product_id = prod_id;

            //         var data = {
            //             product_id: product_id,
            //             user_id: user_id
            //         }
            //         $.ajax({
            //             type: "POST",
            //             url: "{{ route('wishlist.store') }}",
            //             headers: {
            //                 'X-CSRF-TOKEN': "{{ csrf_token() }}"
            //             },
            //             data: data,
            //             success: function(response) {
            //                 console.log(response);
            //                 if (response.status == 200) {
            //                     Swal.fire({
            //                         icon: 'success',
            //                         title: 'Success',
            //                         text: response.message,
            //                         showConfirmButton: false,
            //                         timer: 1000
            //                     });
            //                 } else {
            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Oops...',
            //                         text: response.message,
            //                     });
            //                 }
            //             }
            //         });
            //     } else {
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Oops...',
            //             text: 'You need to login first!',
            //         });
            //         window.location.href = "{{ route('login') }}";
            //     }

            // });

            $(document).on("click", "#wishlist", function() {
                var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
                var user_id = $(this).data('user-id');
                var prod_id = $(this).data('product-id');

                console.log("Product ID:", prod_id);

                $(this).find('i').addClass('fa').removeClass('fa-regular');

                if (isAuthenticated) {
                    var data = {
                        product_id: prod_id,
                        user_id: user_id
                    };

                    $.ajax({
                        type: "POST",
                        url: "{{ route('wishlist.store') }}",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: data,
                        success: function(response) {
                            console.log(response);
                            if (response.status == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1000
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
                                });
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'You need to login first!',
                    });
                    window.location.href = "{{ route('login') }}";
                }
            });

            $(document).on("change", "#warehouse_id", function() {
                var quantity = $(this).find('option:selected').data('quantity');
                var address = $(this).find('option:selected').data('address');
                // $('#stock').text(quantity);
                $('#warehouse_stock').val(quantity);
                $('#warehouse_address').text(address != '' ? address : 'No address found')

            });
            $('#warehouse_id').trigger('change');
        });

        $(function() {

            $("#exzoom").exzoom({

                // thumbnail nav options
                "navWidth": 60,
                "navHeight": 60,
                "navItemNum": 5,
                "navItemMargin": 7,
                "navBorder": 1,

                // autoplay
                "autoPlay": true,

                // autoplay interval in milliseconds
                "autoPlayTimeout": 2000

            });

        });
    </script>

    {{-- Star Rating --}}
    <script>
        const LABELCOLORINACTIV = "#dccfcf";
        const LABELCOLORACTIV = "#4dc717";

        const RATINGSLABELS = document.querySelectorAll("form.star label");
        const RATINGSINPUTS = document.querySelectorAll("form.star input");

        // make inputs disappear
        RATINGSINPUTS.forEach(function(anInput) {
            anInput.style.display = "none";
        });

        // manage label click & hover display
        function notationLabels(e) {
            let currentLabelRed = e.target;
            let currentLabelBlack = e.target;

            // console.log(e.target.localName);

            if (e.type == "mouseenter" || !e.target.control.checked) {
                // coloring red from the clicked/hovered label included, going backward till the node start - if we are hovering or the star isn't already checked.
                while (currentLabelRed != null) {
                    currentLabelRed.style.color = LABELCOLORACTIV;
                    currentLabelRed = currentLabelRed.previousElementSibling;
                }

                // coloring black from the clicked/hovered label excluded, going forward till the node end
                while ((currentLabelBlack = currentLabelBlack.nextElementSibling) != null) {
                    currentLabelBlack.style.color = LABELCOLORINACTIV;
                }
            } else {
                // if the clicked label was already checked we uncheck it and prevent the click event from doing its job - defacto enabling zero star rating
                e.target.control.checked = false;
                e.preventDefault();
            }
            if (e.type == "click") {
                // Assuming the rating value is stored in the value attribute of the input
                const ratingValue = e.target.control.value;
                const ratingInput = document.getElementById('rating');
                ratingInput.value = ratingValue;
            }
        }

        function notationLabelsOut(e) {
            let notesNode = e.target.parentNode.querySelectorAll("label");
            let currentLabel = notesNode[notesNode.length - 1];

            // console.log("out : " + e.target.localName);
            // console.log("out checked: " + e.target.control.checked);

            notesNode.forEach(function redrum(starLabel) {
                starLabel.style.color = LABELCOLORACTIV;
            });

            while (currentLabel != null && !currentLabel.control.checked) {
                currentLabel.style.color = LABELCOLORINACTIV;
                currentLabel = currentLabel.previousElementSibling;

                //console.log("currentLabel null?: " + currentLabel);
                // previousElementSibling become the input ...
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            RATINGSLABELS.forEach(function(aStar) {
                aStar.style.color = "#eee";
                aStar.addEventListener("click", notationLabels);
                aStar.addEventListener("mouseenter", notationLabels);
                aStar.addEventListener("mouseout", notationLabelsOut);
            });

            // stop a callback to the label click event function notationLabels passed on the input element associated ... why ... that's behond me
            // alternatively we could check for e.target.localName in the notationLabels function
            RATINGSINPUTS.forEach(function(aStarInput) {
                aStarInput.addEventListener("click", function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
@endsection
