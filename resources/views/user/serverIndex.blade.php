@extends('user.layout.app')
@section('style')
    <style>
        @media (max-width: 768px) {
            .banner-desc {
                max-width: 300px !important
            }
        }

        .hero-carousel-1 {

            height: 75vh;
            border-radius: 20px;
            background-repeat: no-repeat;
            background-position: center;
        }

        @media (max-width: 2100px) {
            .hero-carousel-1 {
                height: 47vh;
            }
        }

        @media (max-width: 1920px) {
            .hero-carousel-1 {
                height: 50vh;
            }
        }

        @media (max-width: 1830px) {
            .hero-carousel-1 {
                height: 57vh;
            }
        }

        @media (max-width: 1520px) {
            .hero-carousel-1 {
                height: 63vh;
            }
        }

        @media (max-width: 1440px) {
            .hero-carousel-1 {
                height: 75vh;
            }
        }

        @media (max-width: 576px) {
            .hero-carousel-1 {
                height: 33vh;
                background-repeat: round;
                background-position: center;
                background-size: cover;
            }
        }
    </style>
@endsection
@section('content')
    <section class="mt-4">

        <div class="container-xxl container-fluid">
            <div class="row">
                <div class="col-lg-2 col-md-4"></div>
                <div class="col-lg-10 col-md-12 ps-4 ">
                    <div id="carouselExample" data-bs-ride="carousel" data-bs-interval="9000" class="carousel slide ">
                        <div class="carousel-inner shadow">
                            @forelse($banner_sections as $index => $banner)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <div class="card border-0 card-shadow p-2">
                                        <div class="card-body hero-carousel-1 d-flex align-items-center"
                                            style="background: url({{ asset('storage/' . $banner->image) }});background-position: center;background-size: cover;">
                                            <div class="row">
                                                <div class="p-md-5 ps-2 pt-md-5 pt-0 mt-md-5 mt-0 ms-0 ms-md-3 ms-lg-5 ">
                                                    <div class=" ">
                                                        <h1 class="main-heading m-0">
                                                            {{-- {{ $banner->title ?? 'Make Breakfast' }} --}}
                                                        </h1>
                                                        <p class="mt-3 banner-desc d-none d-md-block" style="width:500px">
                                                            {{-- {{ $banner->description ?? 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptates.' }} --}}
                                                        </p>
                                                        <a href="{{ $banner->link ?? '' }}"
                                                            class="btn bag-btn text-white rounded-5">
                                                            Shop Now
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="carousel-item active">
                                    <div class="card border-0 card-shadow p-2">
                                        <div class="card-body hero-carousel-1"
                                            style="background: url({{ asset('front/assets/img/hero.jpeg') }});height: 86vh;">
                                            <div class="row">
                                                <div class="col-md-7 p-5 mt-4">
                                                    <h1 class="main-heading">
                                                        Make Breakfast <br class="d-none d-md-block" />
                                                        <span class="green-txtcolor">Healthy</span>
                                                    </h1>
                                                    <p class="mt-3">
                                                        Get ready for spring as soon as today with $0
                                                        <br class="d-none d-md-block" />
                                                        delivery fees on signup with us…
                                                    </p>
                                                    <button class="btn bag-btn text-white rounded-5">
                                                        Shop Now
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        @if ($banner_sections->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                                data-bs-slide="prev">
                                <span class="fa-solid fa-chevron-left fw-bold text-dark fs-1" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                                data-bs-slide="next">
                                <span class="fa-solid fa-chevron-right fw-bold text-dark fs-1" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>




    <!-- SECTION -->
    <section class="mt-5">
        <div class="container-xxl container-fluid">
            <div class="framebox">
                <div class="owl-carousel">
                    @foreach ($new_products as $product)
                        <a href="{{ route('user.product.index', ['code' => $product->category->code, 'sku' => $product->sku]) }}"
                            class="item ">
                            <div class="card item1 border-0 text-center rounded-4">
                                <div class="card-body">
                                    {{-- <img src="{{ asset('storage' . $product->images[0]['img_path']) }}" alt="Not Found"
                                        class="img-fluid" /> --}}
                                    @if (count($product->images) > 0)
                                        <img src="{{ asset('/storage' . $product->images[0]['img_path']) }}" alt="No"
                                            class="img-fluid"
                                            style="`width: 200px;height: 125px;object-fit: contain; /* Ensures the image covers the entire area */">
                                    @else
                                        <img src="{{ asset('back/assets/image/no-image.png') }}" alt=""
                                            class="" />
                                    @endif
                                    {{-- <h3 class="title fs-6">{{ $product->name ?? '' }} Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam sit magni eaque.</h3>
                                    <p class="m-0">${{ $product->sell_price ?? '0.00' }}</p> --}}
                                </div>
                                <div class="card-footer bg-transparent border-0">
                                    <h3 class="title fs-6">{{ $product->name ?? '' }}
                                    </h3>
                                    @if ($setting->show_pricing == 1)
                                        <p class="m-0">${{ $product->sell_price ?? '0.00' }}</p>
                                    @else
                                        @auth
                                            <p class="m-0">${{ $product->sell_price ?? '0.00' }}</p>
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- /SECTION -->

    <section class="mt-5">
        <div class="container-xxl container-fluid">
            <div class="row">
                {{-- adds column  --}}
                <div class="col-lg-3 col-md-4 pe-0 order-2 order-md-1">
                    {{-- section 1  --}}
                    <div>
                        @foreach ($ads as $ad)
                            @if ($ad)
                                <a href="{{ $ad->url ?? '' }}" target="_blank">
                                    <div class="p-3 ps-3 mt-4 border-0  w-100 text-center">
                                        <img class="img-fluid rounded-3" src="{{ asset('storage/' . $ad->image) }}" />
                                    </div>
                                </a>
                            @else
                                <div class="p-5 ps-4"
                                    style="background: url('default-image-url.jpg') no-repeat center center; background-size: cover; height: 65vh; width: 100%;">

                                </div>
                            @endif
                        @endforeach


                        <div class="services mt-3">
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
                    </div>
                </div>
                {{-- product sections  --}}
                <div class="col-lg-9 col-md-8 order-1 order-md-2">
                    {{-- section 1 --}}
                    <div class="mt-4 d-none d-md-block">
                        <div class="text-center">
                            <p class="badge light-warningbg">WE LOVE THEM</p>
                        </div>
                        <h2 class="hr-text text-center mt-2">
                            <span
                                class="bg-white px-2">{{ $headings->top_selling_product ?? 'Top 3 Selling Products' }}</span>
                        </h2>
                        <div>
                            <ul class="nav nav-pills mb-3 justify-content-center gap-3 mt-5" id="pills-tab"
                                role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active tab-btn rounded-5 text-dark" id="pills-showall-tab"
                                        data-bs-toggle="pill" data-bs-target="#pills-showall" type="button"
                                        role="tab" aria-controls="pills-showall" aria-selected="true">
                                        Show all
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-5 text-dark" id="pills-newproducts-tab"
                                        data-bs-toggle="pill" data-bs-target="#pills-newproducts" type="button"
                                        role="tab" aria-controls="pills-newproducts" aria-selected="false">
                                        New Products
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-5 text-dark" id="pills-trending-tab"
                                        data-bs-toggle="pill" data-bs-target="#pills-trending" type="button"
                                        role="tab" aria-controls="pills-trending" aria-selected="false">
                                        Trending
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content card-shadow" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-showall" role="tabpanel"
                                    aria-labelledby="pills-showall-tab" tabindex="0">
                                    <div class="d-flex flex-wrap">

                                        @foreach ($top_selling_products as $product)
                                            <div class="selling-product col-lg-4 col-md-4 p-2 border">
                                                <a
                                                    href="{{ route('user.product.index', ['code' => $product->product->category->code, 'sku' => $product->product->sku]) }}">
                                                    <div class="card tab-card border-0 rounded-0">
                                                        <div class="card-body p-0 text-center">

                                                            @if (count($product->product->images) > 0)
                                                                <img src="{{ asset('/storage' . $product->product->images[0]['img_path']) }}"
                                                                    alt="No" class="img-fluid">
                                                            @else
                                                                <img src="{{ asset('back/assets/image/no-image.png') }}"
                                                                    alt="" class="" />
                                                            @endif
                                                        </div>

                                                        <div class="card-footer border-0  bg-transparent text-center">
                                                            <p class="product-category">
                                                                {{ $product->product->category->name ?? '' }}</p>
                                                            <h3 class="product-name">
                                                                <a
                                                                    href="{{ route('user.product.index', ['code' => $product->product->category->code, 'sku' => $product->product->sku]) }}">{{ $product->product->name }}</a>
                                                            </h3>
                                                            <div class="text-warning">
                                                                @for ($i = 0; $i < $product->product->averageRating(); $i++)
                                                                    <i class="fa-solid fa-star"></i>
                                                                @endfor
                                                                @for ($i = 0; $i < 5 - $product->product->averageRating(); $i++)
                                                                    <i class="fa-regular fa-star"></i>
                                                                @endfor
                                                            </div>
                                                            @if ($setting->show_pricing == 1)
                                                                <h4 class="product-price product-old-price">
                                                                    ${{ $product->product->sell_price ?? '0.00' }}
                                                                </h4>
                                                            @else
                                                                @auth
                                                                    <h4 class="product-price product-old-price">
                                                                        ${{ $product->product->sell_price ?? '0.00' }}
                                                                    </h4>
                                                                @endauth
                                                            @endif

                                                        </div>
                                                    </div>
                                                </a>

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-newproducts" role="tabpanel"
                                    aria-labelledby="pills-newproducts-tab" tabindex="0">
                                    <div class="d-flex flex-wrap">
                                        @foreach ($new_products as $product)
                                            <a
                                                href="{{ route('user.product.index', ['code' => $product->category->code, 'sku' => $product->sku]) }}">
                                                <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                                    <div class="card tab-card border-0 rounded-0">
                                                        <div class="card-body p-0 text-center">
                                                            @if (count($product->images) > 0)
                                                                <img src="{{ asset('/storage' . $product->images[0]['img_path']) }}"
                                                                    alt="No" class="img-fluid">
                                                            @else
                                                                <img src="{{ asset('back/assets/image/no-image.png') }}"
                                                                    alt="" class="" />
                                                            @endif
                                                        </div>

                                                        <div class="card-footer border-0  bg-transparent text-center">
                                                            <p class="product-category">{{ $product->category->name }}</p>
                                                            <h3 class="product-name">
                                                                <a
                                                                    href="{{ route('user.product.index', ['code' => $product->category->code, 'sku' => $product->sku]) }}">{{ $product->name }}</a>
                                                            </h3>
                                                            <div class="text-warning">
                                                                @for ($i = 0; $i < $product->averageRating(); $i++)
                                                                    <i class="fa-solid fa-star"></i>
                                                                @endfor
                                                                @for ($i = 0; $i < 5 - $product->averageRating(); $i++)
                                                                    <i class="fa-regular fa-star"></i>
                                                                @endfor
                                                            </div>

                                                            @if ($setting->show_pricing == 1)
                                                                <h4 class="product-price product-old-price">
                                                                    ${{ $product->sell_price ?? '' }}
                                                                </h4>
                                                            @else
                                                                @auth
                                                                    <h4 class="product-price product-old-price">
                                                                        ${{ $product->sell_price ?? '' }}
                                                                    </h4>
                                                                @endauth
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach


                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-trending" role="tabpanel"
                                    aria-labelledby="pills-trending-tab" tabindex="0">
                                    <div class="d-flex flex-wrap">
                                        @foreach ($top_selling_products as $product)
                                            <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                                <a
                                                    href="{{ route('user.product.index', ['code' => $product->product->category->code, 'sku' => $product->product->sku]) }}">
                                                    <div class="card tab-card border-0 rounded-0">
                                                        <div class="card-body p-0 text-center">

                                                            @if (count($product->product->images) > 0)
                                                                <img src="{{ asset('/storage' . $product->product->images[0]['img_path']) }}"
                                                                    alt="No" class="img-fluid">
                                                            @else
                                                                <img src="{{ asset('back/assets/image/no-image.png') }}"
                                                                    alt="" class="" />
                                                            @endif
                                                        </div>
                                                        <div class="card-footer border-0  bg-transparent text-center">
                                                            <p class="product-category">
                                                                {{ $product->product->category->name ?? '' }}</p>
                                                            <h3 class="product-name">
                                                                <a
                                                                    href="{{ route('user.product.index', ['code' => $product->product->category->code, 'sku' => $product->product->sku]) }}">{{ $product->product->name }}</a>
                                                            </h3>
                                                            <div class="text-warning">
                                                                @for ($i = 0; $i < $product->product->averageRating(); $i++)
                                                                    <i class="fa-solid fa-star"></i>
                                                                @endfor
                                                                @for ($i = 0; $i < 5 - $product->product->averageRating(); $i++)
                                                                    <i class="fa-regular fa-star"></i>
                                                                @endfor
                                                            </div>

                                                            @if ($setting->show_pricing == 1)
                                                                <h4 class="product-price product-old-price">
                                                                    ${{ $product->product->sell_price ?? '0.00' }}
                                                                </h4>
                                                            @else
                                                                @auth
                                                                    <h4 class="product-price product-old-price">
                                                                        ${{ $product->product->sell_price ?? '0.00' }}
                                                                    </h4>
                                                                @endauth
                                                            @endif
                                                        </div>

                                                    </div>
                                                </a>

                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 pt-0 pt-md-5">
                        <div>
                            <div class="text-center">
                                <p class="badge light-warningbg">WE LOVE THEM</p>
                            </div>
                            <h2 class="hr-text text-center mt-2">
                                <span class="bg-white px-2">
                                    {{ $headings->our_recomandation ?? 'Our Recomandations' }}
                                </span>
                            </h2>
                        </div>
                        <div>
                            <ul class="nav nav-pills mb-3 justify-content-center gap-3 mt-5" id="pills-tab"
                                role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active tab-btn rounded-5 text-dark" id="pills-show-tab"
                                        data-bs-toggle="pill" data-bs-target="#pills-show" type="button" role="tab"
                                        aria-controls="pills-show" aria-selected="true">
                                        Show all
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-5 text-dark" id="pills-recommendation-product-tab"
                                        data-bs-toggle="pill" data-bs-target="#pills-recommendation-product"
                                        type="button" role="tab" aria-controls="pills-recommendation-product"
                                        aria-selected="false">
                                        Recommendation
                                    </button>
                                </li>

                            </ul>
                            <div class="tab-content card-shadow rounded-4" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-show" role="tabpanel"
                                    aria-labelledby="pills-show-tab" tabindex="0">
                                    <div class="card rounded-4">
                                        <div class="card-body">

                                            <div class="d-flex flex-wrap mt-3">
                                                @foreach ($products as $product)
                                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                                        <a
                                                            href="{{ route('user.product.index', ['code' => $product->category->code, 'sku' => $product->sku]) }}">
                                                            <div class="card tab-card border-0 rounded-0">
                                                                <div
                                                                    class="card-body p-0 border-0 bg-transparent text-center">

                                                                    @if (count($product->images) > 0)
                                                                        <img src="{{ asset('/storage' . $product->images[0]['img_path']) }}"
                                                                            alt="No" class="img-fluid"
                                                                            style="width: 200px;height: 160px;object-fit: contain;">
                                                                    @else
                                                                        <img src="{{ asset('back/assets/image/no-image.png') }}"
                                                                            alt="" class=""
                                                                            style="width: 200px;height: 160px;object-fit: contain;" />
                                                                    @endif
                                                                </div>

                                                                <div
                                                                    class="card-footer bg-transparent border-0 text-center">
                                                                    <p class="product-category">
                                                                        {{ $product->category->name ?? '' }}</p>
                                                                    <h3 class="product-name">
                                                                        <a
                                                                            href="{{ route('user.product.index', ['code' => $product->category->code, 'sku' => $product->sku]) }}">{{ $product->name }}</a>
                                                                    </h3>
                                                                    <div class="text-warning">
                                                                        @for ($i = 0; $i < $product->averageRating(); $i++)
                                                                            <i class="fa-solid fa-star"></i>
                                                                        @endfor
                                                                        @for ($i = 0; $i < 5 - $product->averageRating(); $i++)
                                                                            <i class="fa-regular fa-star"></i>
                                                                        @endfor
                                                                    </div>

                                                                    @if ($setting->show_pricing == 1)
                                                                        <h4 class="product-price product-old-price">
                                                                            ${{ $product->sell_price ?? '0.00' }}
                                                                        </h4>
                                                                    @else
                                                                        @auth
                                                                            <h4 class="product-price product-old-price">
                                                                                ${{ $product->sell_price ?? '0.00' }}
                                                                            </h4>
                                                                        @endauth
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        </a>

                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-recommendation-product" role="tabpanel"
                                    aria-labelledby="pills-recommendation-product-tab" tabindex="0">
                                    <div class="card rounded-4">
                                        <div class="card-body">

                                            <div class="d-flex flex-wrap mt-3">
                                                @foreach ($top_selling_products as $product)
                                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                                        <a
                                                            href="{{ route('user.product.index', ['code' => $product->product->category->code, 'sku' => $product->product->sku]) }}">
                                                            <div class="card tab-card border-0 rounded-0">
                                                                <div class="card-body p-0  text-center">

                                                                    @if (count($product->product->images) > 0)
                                                                        <img src="{{ asset('/storage' . $product->product->images[0]['img_path']) }}"
                                                                            alt="No" class="img-fluid">
                                                                    @else
                                                                        <img src="{{ asset('back/assets/image/no-image.png') }}"
                                                                            alt="" class="" />
                                                                    @endif
                                                                </div>

                                                                <div class="footer bg-transparent border-0 text-center">
                                                                    <p class="product-category">
                                                                        {{ $product->product->category->name ?? '' }}</p>
                                                                    <h3 class="product-name">
                                                                        <a
                                                                            href="{{ route('user.product.index', ['code' => $product->product->category->code, 'sku' => $product->product->sku]) }}">{{ $product->product->name }}</a>
                                                                    </h3>
                                                                    <div class="text-warning">
                                                                        @for ($i = 0; $i < $product->product->averageRating(); $i++)
                                                                            <i class="fa-solid fa-star"></i>
                                                                        @endfor
                                                                        @for ($i = 0; $i < 5 - $product->product->averageRating(); $i++)
                                                                            <i class="fa-regular fa-star"></i>
                                                                        @endfor
                                                                    </div>

                                                                    @if ($setting->show_pricing == 1)
                                                                        <h4 class="product-price product-old-price">
                                                                            ${{ $product->product->sell_price ?? '0.00' }}
                                                                        </h4>
                                                                    @else
                                                                        @auth
                                                                            <h4 class="product-price product-old-price">
                                                                                ${{ $product->product->sell_price ?? '0.00' }}
                                                                            </h4>
                                                                        @endauth
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        </a>

                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="mt-5 pt-0 pt-md-5">
                            <div class="text-center">
                                <p class="badge light-warningbg">WE LOVE THEM</p>
                            </div>
                            <h2 class="hr-text text-center mt-2">
                                <span class="bg-white px-2">
                                    {{ $headings->feature_category ?? 'Featured Categories' }}
                                </span>
                            </h2>
                        </div>
                        <div class="d-flex justify-content-between gap-2 mt-5 featured-category align-items-center">
                            @foreach ($categories as $category)
                                @if ($loop->iteration <= 6)
                                    <div class="card rounded-4 h-100 text-center w-100">
                                        <div class="card-body text-center h-100">
                                            <img src="{{ isset($category->icon) ? asset('storage/category/' . $category->icon) : 'https://placehold.co/600x400' }}" alt=""
                                                style="width: 60px;" class="rounded-cicle">
                                            <p class="fw-bold mt-3 mb-0">{{ $category->name }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    @include('user.components.brands')
@endsection

@section('scripts')
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
        });
    </script>
@endsection
