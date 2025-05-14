@extends('user.layout.app')
@section('content')
    <section class="mt-4">
        <div class="container-fluid">
            {{-- Bannaer Section  --}}
            <div class="row">
                <div class="col-lg-2 col-md-4"></div>
                <div class="col-lg-10 col-md-8">
                    <div class="card border-0 card-shadow p-2">
                        <div class="card-body"
                            style="background: url({{ asset('front/assets/img/hero.jpeg') }}); height: 86vh">
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
            </div>
        </div>
    </section>

    <!-- SECTION -->
    <section class="mt-5">
        <div class="container-fluid">
            <div class="framebox">
                <div class="owl-carousel">
                    @foreach ($new_products as $product)
                        <a href="{{ route('user.product.index', ['code' => $product->category->code, 'sku' => $product->sku]) }}"
                            class="item rounded-3">
                            <div class="card item1 border-0 text-center">
                                <div class="card-body pt-0">
                                    {{-- <img src="{{ asset('storage' . $product->images[0]['img_path']) }}" alt="Not Found"
                                        class="img-fluid" /> --}}
                                        @if (count($product->images) > 0)
                                            <img src="{{ asset('/storage' . $product->images[0]['img_path']) }}"
                                                alt="No" class="img-fluid">
                                        @else
                                            <img src="{{ asset('back/assets/image/no-image.png') }}" alt=""
                                                class="" />
                                        @endif
                                    <h3 class="title fs-6">{{ $product->name ?? '' }}</h3>
                                    <p class="m-0">${{ $product->sell_price ?? '0.00' }}</p>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-4 pe-0">
                    <div class="p-5 ps-4"
                        style="
                            background: url({{ asset('front/assets/img/home-1.jpeg') }});
                            height: 65vh;
                            width: 100%;
                        ">
                        <h3 class="title fs-3 mt-4">
                            Saving 15% <br class="d-none d-md-block" />
                            on <span class="text-warning">Orange</span>
                            <br class="d-none d-md-block" />
                            Juice
                        </h3>
                        <p>100% Naturals</p>
                    </div>
                    <div class="card border-2 card-border mt-4 rounded-4">
                        <div class="card-header border-0 bg-transparent p-0 rounded-top-5">
                            <img src="{{ asset('front/assets/img/home-2.jpg') }}" alt=""
                                class="img-fluid w-100 rounded-top-5" />
                        </div>
                        <div class="card-body text-center">
                            <p class="product-category">Category</p>
                            <h3 class="product-name fw-bold m-0">
                                <a href="#" class="fw-bold">Product name goes here</a>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8 d-none d-md-block">
                    <div class="text-center">
                        <p class="badge light-warningbg">WE LOVE THEM</p>
                    </div>
                    <h2 class="hr-text text-center mt-2">
                        <span class="bg-white px-2">Top Selling Products</span>
                    </h2>
                    <div>
                        <ul class="nav nav-pills mb-3 justify-content-center gap-3 mt-5" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active tab-btn rounded-5 text-dark" id="pills-showall-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-showall" type="button" role="tab"
                                    aria-controls="pills-showall" aria-selected="true">
                                    Show all
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-5 text-dark" id="pills-newproducts-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-newproducts" type="button" role="tab"
                                    aria-controls="pills-newproducts" aria-selected="false">
                                    New Products
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-5 text-dark" id="pills-trending-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-trending" type="button" role="tab"
                                    aria-controls="pills-trending" aria-selected="false">
                                    Trending
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-showall" role="tabpanel"
                                aria-labelledby="pills-showall-tab" tabindex="0">
                                <div class="d-flex flex-wrap">

                                    @foreach ($top_selling_products as $product)
                                        <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                            <a href="{{route('user.product.index',['code' => $product->product->category->code, 'sku' => $product->product->sku])}}">
                                                <div class="card tab-card border-0 rounded-0">
                                                    <div class="card-header p-0 border-0 bg-transparent">

                                                            @if (count($product->product->images) > 0)
                                                                <img src="{{ asset('/storage' . $product->product->images[0]['img_path']) }}"
                                                                    alt="No" class="img-fluid">
                                                            @else
                                                                <img src="{{ asset('back/assets/image/no-image.png') }}" alt=""
                                                                    class=""  />
                                                            @endif
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <p class="product-category">
                                                            {{ $product->product->category->name ?? '' }}</p>
                                                        <h3 class="product-name">
                                                            <a href="{{route('user.product.index',['code' => $product->product->category->code, 'sku' => $product->product->sku])}}">{{ $product->product->name }}</a>
                                                        </h3>
                                                        <div class="text-warning">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                        <h4 class="product-price product-old-price">
                                                            {{ $product->product->sell_price ?? '0.00' }}
                                                        </h4>
                                                    </div>
                                                    {{-- <form action="" method="get">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="card-footer border-0 bg-transparent d-flex gap-2 pt-0">
                                                            <div class="quantity d-flex">
                                                                <button class="btn bag-btn text-white minusBtn rounded-0"
                                                                    type="button">
                                                                    -
                                                                </button>
                                                                <input type="number"
                                                                    class="form-control quantityInput rounded-end-0 rounded-start-0"
                                                                    value="1" name="quantity" />
                                                                <button class="btn bag-btn text-white plusBtn rounded-0"
                                                                    type="button">
                                                                    +
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $product->product->id }}">
                                                            <input type="hidden" name="price"
                                                                value="{{ $product->product->sell_price }}">
                                                            <button class="btn bag-btn text-white" type="submit">
                                                                <i class="bi bi-bag"></i>
                                                            </button>
                                                        </div>
                                                    </form> --}}
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
                                        <a href="{{route('user.product.index',['code' => $product->category->code, 'sku' => $product->sku])}}">
                                            <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                                <div class="card tab-card border-0 rounded-0">
                                                    <div class="card-header p-0 border-0 bg-transparent">
                                                        @if (count($product->images) > 0)
                                                            <img src="{{ asset('/storage' . $product->images[0]['img_path']) }}"
                                                                alt="No" class="img-fluid">
                                                        @else
                                                            <img src="{{ asset('back/assets/image/no-image.png') }}" alt=""
                                                                class="" />
                                                        @endif
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <p class="product-category">{{ $product->category->name }}</p>
                                                        <h3 class="product-name">
                                                            <a href="{{route('user.product.index',['code' => $product->category->code, 'sku' => $product->sku])}}">{{ $product->name }}</a>
                                                        </h3>
                                                        <div class="text-warning">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                        <h4 class="product-price product-old-price">
                                                            {{ $product->sell_price ?? '' }}
                                                        </h4>
                                                    </div>
                                                    <form action="" method="get">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="card-footer border-0 bg-transparent d-flex gap-2 pt-0">
                                                            <div class="quantity d-flex">
                                                                <button class="btn bag-btn text-white minusBtn rounded-0"
                                                                    type="button">
                                                                    -
                                                                </button>
                                                                <input type="number"
                                                                    class="form-control quantityInput rounded-end-0 rounded-start-0"
                                                                    value="1" name="quantity" />
                                                                <button class="btn bag-btn text-white plusBtn rounded-0"
                                                                    type="button">
                                                                    +
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $product->id }}">
                                                            <input type="hidden" name="price"
                                                                value="{{ $product->sell_price }}">
                                                            <button class="btn bag-btn text-white" type="submit">
                                                                <i class="bi bi-bag"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach


                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-trending" role="tabpanel"
                                aria-labelledby="pills-trending-tab" tabindex="0">
                                <div class="d-flex flex-wrap">
                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                        <div class="card tab-card border-0 rounded-0">
                                            <div class="card-header p-0 border-0 bg-transparent">
                                                <img src="assets/img/product01.png" class="img-fluid" alt="" />
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="product-category">Category</p>
                                                <h3 class="product-name">
                                                    <a href="#">Product name goes here</a>
                                                </h3>
                                                <div class="text-warning">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <h4 class="product-price product-old-price">
                                                    $980.00
                                                </h4>
                                            </div>
                                            <div class="card-footer border-0 bg-transparent d-flex gap-2 pt-0">
                                                <div class="quantity d-flex">
                                                    <button class="btn bag-btn text-white minusBtn rounded-0">
                                                        -
                                                    </button>
                                                    <input type="number"
                                                        class="form-control quantityInput rounded-end-0 rounded-start-0"
                                                        value="1" />
                                                    <button class="btn bag-btn text-white plusBtn rounded-0">
                                                        +
                                                    </button>
                                                </div>
                                                <button class="btn bag-btn text-white">
                                                    <i class="bi bi-bag"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                        <div class="card tab-card border-0 rounded-0">
                                            <div class="card-header p-0 border-0 bg-transparent">
                                                <img src="assets/img/product01.png" class="img-fluid" alt="" />
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="product-category">Category</p>
                                                <h3 class="product-name">
                                                    <a href="#">Product name goes here</a>
                                                </h3>
                                                <div class="text-warning">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <h4 class="product-price product-old-price">
                                                    $980.00
                                                </h4>
                                            </div>
                                            <div class="card-footer border-0 bg-transparent d-flex gap-2 pt-0">
                                                <div class="quantity d-flex">
                                                    <button class="btn bag-btn text-white minusBtn rounded-0">
                                                        -
                                                    </button>
                                                    <input type="number"
                                                        class="form-control quantityInput rounded-end-0 rounded-start-0"
                                                        value="1" />
                                                    <button class="btn bag-btn text-white plusBtn rounded-0">
                                                        +
                                                    </button>
                                                </div>
                                                <button class="btn bag-btn text-white">
                                                    <i class="bi bi-bag"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                        <div class="card tab-card border-0 rounded-0">
                                            <div class="card-header p-0 border-0 bg-transparent">
                                                <img src="assets/img/product01.png" class="img-fluid" alt="" />
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="product-category">Category</p>
                                                <h3 class="product-name">
                                                    <a href="#">Product name goes here</a>
                                                </h3>
                                                <div class="text-warning">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <h4 class="product-price product-old-price">
                                                    $980.00
                                                </h4>
                                            </div>
                                            <div class="card-footer border-0 bg-transparent d-flex gap-2 pt-0">
                                                <div class="quantity d-flex">
                                                    <button class="btn bag-btn text-white minusBtn rounded-0">
                                                        -
                                                    </button>
                                                    <input type="number"
                                                        class="form-control quantityInput rounded-end-0 rounded-start-0"
                                                        value="1" />
                                                    <button class="btn bag-btn text-white plusBtn rounded-0">
                                                        +
                                                    </button>
                                                </div>
                                                <button class="btn bag-btn text-white">
                                                    <i class="bi bi-bag"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                        <div class="card tab-card border-0 rounded-0">
                                            <div class="card-header p-0 border-0 bg-transparent">
                                                <img src="assets/img/product01.png" class="img-fluid" alt="" />
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="product-category">Category</p>
                                                <h3 class="product-name">
                                                    <a href="#">Product name goes here</a>
                                                </h3>
                                                <div class="text-warning">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <h4 class="product-price product-old-price">
                                                    $980.00
                                                </h4>
                                            </div>
                                            <div class="card-footer border-0 bg-transparent d-flex gap-2 pt-0">
                                                <div class="quantity d-flex">
                                                    <button class="btn bag-btn text-white minusBtn rounded-0">
                                                        -
                                                    </button>
                                                    <input type="number"
                                                        class="form-control quantityInput rounded-end-0 rounded-start-0"
                                                        value="1" />
                                                    <button class="btn bag-btn text-white plusBtn rounded-0">
                                                        +
                                                    </button>
                                                </div>
                                                <button class="btn bag-btn text-white">
                                                    <i class="bi bi-bag"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                        <div class="card tab-card border-0 rounded-0">
                                            <div class="card-header p-0 border-0 bg-transparent">
                                                <img src="assets/img/product01.png" class="img-fluid" alt="" />
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="product-category">Category</p>
                                                <h3 class="product-name">
                                                    <a href="#">Product name goes here</a>
                                                </h3>
                                                <div class="text-warning">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <h4 class="product-price product-old-price">
                                                    $980.00
                                                </h4>
                                            </div>
                                            <div class="card-footer border-0 bg-transparent d-flex gap-2 pt-0">
                                                <div class="quantity d-flex">
                                                    <button class="btn bag-btn text-white minusBtn rounded-0">
                                                        -
                                                    </button>
                                                    <input type="number"
                                                        class="form-control quantityInput rounded-end-0 rounded-start-0"
                                                        value="1" />
                                                    <button class="btn bag-btn text-white plusBtn rounded-0">
                                                        +
                                                    </button>
                                                </div>
                                                <button class="btn bag-btn text-white">
                                                    <i class="bi bi-bag"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                        <div class="card tab-card border-0 rounded-0">
                                            <div class="card-header p-0 border-0 bg-transparent">
                                                <img src="assets/img/product01.png" class="img-fluid" alt="" />
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="product-category">Category</p>
                                                <h3 class="product-name">
                                                    <a href="#">Product name goes here</a>
                                                </h3>
                                                <div class="text-warning">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <h4 class="product-price product-old-price">
                                                    $980.00
                                                </h4>
                                            </div>
                                            <div class="card-footer border-0 bg-transparent d-flex gap-2 pt-0">
                                                <div class="quantity d-flex">
                                                    <button class="btn bag-btn text-white minusBtn rounded-0">
                                                        -
                                                    </button>
                                                    <input type="number"
                                                        class="form-control quantityInput rounded-end-0 rounded-start-0"
                                                        value="1" />
                                                    <button class="btn bag-btn text-white plusBtn rounded-0">
                                                        +
                                                    </button>
                                                </div>
                                                <button class="btn bag-btn text-white">
                                                    <i class="bi bi-bag"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                        <div class="card tab-card border-0 rounded-0">
                                            <div class="card-header p-0 border-0 bg-transparent">
                                                <img src="assets/img/product01.png" class="img-fluid" alt="" />
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="product-category">Category</p>
                                                <h3 class="product-name">
                                                    <a href="#">Product name goes here</a>
                                                </h3>
                                                <div class="text-warning">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <h4 class="product-price product-old-price">
                                                    $980.00
                                                </h4>
                                            </div>
                                            <div class="card-footer border-0 bg-transparent d-flex gap-2 pt-0">
                                                <div class="quantity d-flex">
                                                    <button class="btn bag-btn text-white minusBtn rounded-0">
                                                        -
                                                    </button>
                                                    <input type="number"
                                                        class="form-control quantityInput rounded-end-0 rounded-start-0"
                                                        value="1" />
                                                    <button class="btn bag-btn text-white plusBtn rounded-0">
                                                        +
                                                    </button>
                                                </div>
                                                <button class="btn bag-btn text-white">
                                                    <i class="bi bi-bag"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                        <div class="card tab-card border-0 rounded-0">
                                            <div class="card-header p-0 border-0 bg-transparent">
                                                <img src="assets/img/product01.png" class="img-fluid" alt="" />
                                            </div>
                                            <div class="card-body text-center">
                                                <p class="product-category">Category</p>
                                                <h3 class="product-name">
                                                    <a href="#">Product name goes here</a>
                                                </h3>
                                                <div class="text-warning">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <h4 class="product-price product-old-price">
                                                    $980.00
                                                </h4>
                                            </div>
                                            <div class="card-footer border-0 bg-transparent d-flex gap-2 pt-0">
                                                <div class="quantity d-flex">
                                                    <button class="btn bag-btn text-white minusBtn rounded-0">
                                                        -
                                                    </button>
                                                    <input type="number"
                                                        class="form-control quantityInput rounded-end-0 rounded-start-0"
                                                        value="1" />
                                                    <button class="btn bag-btn text-white plusBtn rounded-0">
                                                        +
                                                    </button>
                                                </div>
                                                <button class="btn bag-btn text-white">
                                                    <i class="bi bi-bag"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-4 pe-3 pe-md-0 mt-3">
                    <div class="p-5 ps-4"
                        style="
                            background: url({{ asset('front/assets/img/home-3.jpg') }});
                            height: 65vh;
                            width: 102%;
                            ">
                        <h3 class="title fs-3 mt-4">
                            COCONUT <br class="d-none d-md-block" />
                            ON <span class="text-primary">SHIPPING</span>
                            <br class="d-none d-md-block" />
                            FREE
                        </h3>
                        <a class="btn bag-btn text-white rounded-5 align-items-center" href="#"><span>Explore
                                Now</span> <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8 mt-3 d-none d-md-block">
                    <div class="p-5 ps-4"
                        style="
              background: url({{ asset('front/assets/img/home-4.jpeg') }});
              background-repeat: no-repeat;
              height: 47vh;
            ">
                        <h3 class="title fs-3 mt-4 p-5 py-3 text-white">
                            Start your day with tasty <br class="d-none d-md-block" />
                            organic veggies
                        </h3>
                        <a class="btn bag-btn text-white rounded-5 m-5 my-3 p-2 px-3" href="#"><span>Start Shopping
                                Now!</span>
                            <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Delivery Section -->
    <section class="mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 pe-lg-0 pe-3">
                    <div class="p-5 ps-4 d-none d-md-block"
                        style="
              background: url({{ asset('front/assets/img/home-1.jpeg') }});
              height: 65vh;
              width: 100%;
            ">
                        <h3 class="title fs-3 mt-4">
                            Saving 15% <br class="d-none d-md-block" />
                            on <span class="text-warning">Orange</span>
                            <br class="d-none d-md-block" />
                            Juice
                        </h3>
                        <p>100% Naturals</p>
                    </div>
                    <div class="services mt-3">
                        <div class="d-flex gap-3 align-items-center border-bottom py-4 bg-light px-3">
                            <div>
                                <i class="fa-solid fa-truck-fast fs-1 purple-txtcolor"></i>
                            </div>
                            <div>
                                <h2 class="heading fs-5 mb-1">FREE SHIPPING</h2>
                                <p class="m-0 text-secondary fs-6">Free Shipping On Us</p>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-center border-bottom py-4 bg-light px-3">
                            <div>
                                <i class="bi bi-box2 fs-1 purple-txtcolor"></i>
                            </div>
                            <div>
                                <h2 class="heading fs-5 mb-1">MONEY RETURNS</h2>
                                <p class="m-0 text-secondary fs-6">Returns within 30 days</p>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-center border-bottom py-4 bg-light px-3">
                            <div>
                                <i class="fa-solid fa-wallet fs-1 purple-txtcolor"></i>
                            </div>
                            <div>
                                <h2 class="heading fs-5 mb-1">SECURE PAYMENT</h2>
                                <p class="m-0 text-secondary fs-6">Safe and secure payment</p>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-center py-4 bg-light px-3">
                            <div>
                                <i class="bi bi-shield-check fs-1 purple-txtcolor"></i>
                            </div>
                            <div>
                                <h2 class="heading fs-5 mb-1">SUPPORT 24/7</h2>
                                <p class="m-0 text-secondary fs-6">Contact 24 Hours Day</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div>
                        <div class="text-center">
                            <p class="badge light-warningbg">WE LOVE THEM</p>
                        </div>
                        <h2 class="hr-text text-center mt-2">
                            <span class="bg-white px-2">SALE PURCHASE GROCERY MARKET DEMO</span>
                        </h2>
                    </div>
                    <div>
                        <ul class="nav nav-pills mb-3 justify-content-center gap-3 mt-5" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active tab-btn rounded-5 text-dark" id="pills-show-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-show" type="button" role="tab"
                                    aria-controls="pills-show" aria-selected="true">
                                    Show all
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-5 text-dark" id="pills-recommendation-product-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-recommendation-product" type="button"
                                    role="tab" aria-controls="pills-recommendation-product" aria-selected="false">
                                    Recommendation
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-5 text-dark" id="pills-giftpacking-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-giftpacking" type="button"
                                    role="tab" aria-controls="pills-giftpacking" aria-selected="false">
                                    Gift Packing
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-show" role="tabpanel"
                                aria-labelledby="pills-show-tab" tabindex="0">
                                <div class="card rounded-4">
                                    <div class="card-body">
                                        <div class="free-delivery-bg text-center p-5 rounded-4">
                                            <h4>ON TIME FREE DELIVERY</h4>
                                            <div class="free-usercode bg-white p-3 py-2 btn">
                                                <p class="m-0">Use Code:</p>
                                                <p class="fw-bold code-txtcolor m-0">Sale&Purchase</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap mt-3">
                                            <div class="selling-product col-lg-3 col-md-4 p-2">
                                                <div class="card tab-card border-0 rounded-0">
                                                    <div class="card-header p-0 border-0 bg-transparent">
                                                        <img src="{{ asset('front/assets/img/product01.png') }}"
                                                            class="img-fluid" alt="" />
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <p class="product-category">Category</p>
                                                        <h3 class="product-name">
                                                            <a href="#">Product name goes here</a>
                                                        </h3>
                                                        <div class="text-warning">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                        <h4 class="product-price product-old-price">
                                                            $980.00
                                                        </h4>
                                                    </div>
                                                    <div
                                                        class="card-footer border-0 bg-transparent d-flex justify-content-center pt-0">
                                                        <div class="quantity d-flex justify-content-center">
                                                            <button class="btn bag-btn text-white minusBtn rounded-0 px-1">
                                                                -
                                                            </button>
                                                            <input type="number"
                                                                class="form-control quantityInput rounded-end-0 rounded-start-0 w-50"
                                                                value="1" />
                                                            <button class="btn bag-btn text-white plusBtn rounded-0 px-1">
                                                                +
                                                            </button>
                                                        </div>
                                                        <button class="btn bag-btn text-white px-2">
                                                            <i class="bi bi-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-recommendation-product" role="tabpanel"
                                aria-labelledby="pills-recommendation-product-tab" tabindex="0">
                                <div class="card rounded-4">
                                    <div class="card-body">
                                        <div class="recommendation-delivery-bg text-center p-5 rounded-4">
                                            <h4>ON TIME FREE DELIVERY</h4>
                                            <div class="free-usercode bg-white p-3 py-2 btn">
                                                <p class="m-0">Use Code:</p>
                                                <p class="fw-bold code-txtcolor m-0">Sale&Purchase</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap mt-3">
                                            <div class="selling-product col-lg-3 col-md-4 p-2">
                                                <div class="card tab-card border-0 rounded-0">
                                                    <div class="card-header p-0 border-0 bg-transparent">
                                                        <img src="assets/img/product01.png" class="img-fluid"
                                                            alt="" />
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <p class="product-category">Category</p>
                                                        <h3 class="product-name">
                                                            <a href="#">Product name goes here</a>
                                                        </h3>
                                                        <div class="text-warning">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                        <h4 class="product-price product-old-price">
                                                            $980.00
                                                        </h4>
                                                    </div>
                                                    <div
                                                        class="card-footer border-0 bg-transparent d-flex justify-content-center pt-0">
                                                        <div class="quantity d-flex justify-content-center">
                                                            <button class="btn bag-btn text-white minusBtn rounded-0 px-1">
                                                                -
                                                            </button>
                                                            <input type="number"
                                                                class="form-control quantityInput rounded-end-0 rounded-start-0 w-50"
                                                                value="1" />
                                                            <button class="btn bag-btn text-white plusBtn rounded-0 px-1">
                                                                +
                                                            </button>
                                                        </div>
                                                        <button class="btn bag-btn text-white px-2">
                                                            <i class="bi bi-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="selling-product col-lg-3 col-md-4 p-2">
                                                <div class="card tab-card border-0 rounded-0">
                                                    <div class="card-header p-0 border-0 bg-transparent">
                                                        <img src="assets/img/product01.png" class="img-fluid"
                                                            alt="" />
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <p class="product-category">Category</p>
                                                        <h3 class="product-name">
                                                            <a href="#">Product name goes here</a>
                                                        </h3>
                                                        <div class="text-warning">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                        <h4 class="product-price product-old-price">
                                                            $980.00
                                                        </h4>
                                                    </div>
                                                    <div
                                                        class="card-footer border-0 bg-transparent d-flex justify-content-center pt-0">
                                                        <div class="quantity d-flex justify-content-center">
                                                            <button class="btn bag-btn text-white minusBtn rounded-0 px-1">
                                                                -
                                                            </button>
                                                            <input type="number"
                                                                class="form-control quantityInput rounded-end-0 rounded-start-0 w-50"
                                                                value="1" />
                                                            <button class="btn bag-btn text-white plusBtn rounded-0 px-1">
                                                                +
                                                            </button>
                                                        </div>
                                                        <button class="btn bag-btn text-white px-2">
                                                            <i class="bi bi-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="selling-product col-lg-3 col-md-4 p-2">
                                                <div class="card tab-card border-0 rounded-0">
                                                    <div class="card-header p-0 border-0 bg-transparent">
                                                        <img src="assets/img/product01.png" class="img-fluid"
                                                            alt="" />
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <p class="product-category">Category</p>
                                                        <h3 class="product-name">
                                                            <a href="#">Product name goes here</a>
                                                        </h3>
                                                        <div class="text-warning">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                        <h4 class="product-price product-old-price">
                                                            $980.00
                                                        </h4>
                                                    </div>
                                                    <div
                                                        class="card-footer border-0 bg-transparent d-flex justify-content-center pt-0">
                                                        <div class="quantity d-flex justify-content-center">
                                                            <button class="btn bag-btn text-white minusBtn rounded-0 px-1">
                                                                -
                                                            </button>
                                                            <input type="number"
                                                                class="form-control quantityInput rounded-end-0 rounded-start-0 w-50"
                                                                value="1" />
                                                            <button class="btn bag-btn text-white plusBtn rounded-0 px-1">
                                                                +
                                                            </button>
                                                        </div>
                                                        <button class="btn bag-btn text-white px-2">
                                                            <i class="bi bi-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="selling-product col-lg-3 col-md-4 p-2">
                                                <div class="card tab-card border-0 rounded-0">
                                                    <div class="card-header p-0 border-0 bg-transparent">
                                                        <img src="assets/img/product01.png" class="img-fluid"
                                                            alt="" />
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <p class="product-category">Category</p>
                                                        <h3 class="product-name">
                                                            <a href="#">Product name goes here</a>
                                                        </h3>
                                                        <div class="text-warning">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                        <h4 class="product-price product-old-price">
                                                            $980.00
                                                        </h4>
                                                    </div>
                                                    <div
                                                        class="card-footer border-0 bg-transparent d-flex justify-content-center pt-0">
                                                        <div class="quantity d-flex justify-content-center">
                                                            <button class="btn bag-btn text-white minusBtn rounded-0 px-1">
                                                                -
                                                            </button>
                                                            <input type="number"
                                                                class="form-control quantityInput rounded-end-0 rounded-start-0 w-50"
                                                                value="1" />
                                                            <button class="btn bag-btn text-white plusBtn rounded-0 px-1">
                                                                +
                                                            </button>
                                                        </div>
                                                        <button class="btn bag-btn text-white px-2">
                                                            <i class="bi bi-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-giftpacking" role="tabpanel"
                                aria-labelledby="pills-giftpacking-tab" tabindex="0">
                                <div class="card rounded-4">
                                    <div class="card-body">
                                        <div class="gift-delivery-bg text-center p-5 rounded-4">
                                            <h4 class="text-white">ON TIME FREE DELIVERY</h4>
                                            <div class="free-usercode bg-white p-3 py-2 btn">
                                                <p class="m-0">Use Code:</p>
                                                <p class="fw-bold code-txtcolor m-0">Sale&Purchase</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap mt-3">
                                            <div class="selling-product col-lg-3 col-md-4 p-2">
                                                <div class="card tab-card border-0 rounded-0">
                                                    <div class="card-header p-0 border-0 bg-transparent">
                                                        <img src="assets/img/product01.png" class="img-fluid"
                                                            alt="" />
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <p class="product-category">Category</p>
                                                        <h3 class="product-name">
                                                            <a href="#">Product name goes here</a>
                                                        </h3>
                                                        <div class="text-warning">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                        <h4 class="product-price product-old-price">
                                                            $980.00
                                                        </h4>
                                                    </div>
                                                    <div
                                                        class="card-footer border-0 bg-transparent d-flex justify-content-center pt-0">
                                                        <div class="quantity d-flex justify-content-center">
                                                            <button class="btn bag-btn text-white minusBtn rounded-0 px-1">
                                                                -
                                                            </button>
                                                            <input type="number"
                                                                class="form-control quantityInput rounded-end-0 rounded-start-0 w-50"
                                                                value="1" />
                                                            <button class="btn bag-btn text-white plusBtn rounded-0 px-1">
                                                                +
                                                            </button>
                                                        </div>
                                                        <button class="btn bag-btn text-white px-2">
                                                            <i class="bi bi-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="selling-product col-lg-3 col-md-4 p-2">
                                                <div class="card tab-card border-0 rounded-0">
                                                    <div class="card-header p-0 border-0 bg-transparent">
                                                        <img src="assets/img/product01.png" class="img-fluid"
                                                            alt="" />
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <p class="product-category">Category</p>
                                                        <h3 class="product-name">
                                                            <a href="#">Product name goes here</a>
                                                        </h3>
                                                        <div class="text-warning">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                        <h4 class="product-price product-old-price">
                                                            $980.00
                                                        </h4>
                                                    </div>
                                                    <div
                                                        class="card-footer border-0 bg-transparent d-flex justify-content-center pt-0">
                                                        <div class="quantity d-flex justify-content-center">
                                                            <button class="btn bag-btn text-white minusBtn rounded-0 px-1">
                                                                -
                                                            </button>
                                                            <input type="number"
                                                                class="form-control quantityInput rounded-end-0 rounded-start-0 w-50"
                                                                value="1" />
                                                            <button class="btn bag-btn text-white plusBtn rounded-0 px-1">
                                                                +
                                                            </button>
                                                        </div>
                                                        <button class="btn bag-btn text-white px-2">
                                                            <i class="bi bi-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="selling-product col-lg-3 col-md-4 p-2">
                                                <div class="card tab-card border-0 rounded-0">
                                                    <div class="card-header p-0 border-0 bg-transparent">
                                                        <img src="assets/img/product01.png" class="img-fluid"
                                                            alt="" />
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <p class="product-category">Category</p>
                                                        <h3 class="product-name">
                                                            <a href="#">Product name goes here</a>
                                                        </h3>
                                                        <div class="text-warning">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                        <h4 class="product-price product-old-price">
                                                            $980.00
                                                        </h4>
                                                    </div>
                                                    <div
                                                        class="card-footer border-0 bg-transparent d-flex justify-content-center pt-0">
                                                        <div class="quantity d-flex justify-content-center">
                                                            <button class="btn bag-btn text-white minusBtn rounded-0 px-1">
                                                                -
                                                            </button>
                                                            <input type="number"
                                                                class="form-control quantityInput rounded-end-0 rounded-start-0 w-50"
                                                                value="1" />
                                                            <button class="btn bag-btn text-white plusBtn rounded-0 px-1">
                                                                +
                                                            </button>
                                                        </div>
                                                        <button class="btn bag-btn text-white px-2">
                                                            <i class="bi bi-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="selling-product col-lg-3 col-md-4 p-2">
                                                <div class="card tab-card border-0 rounded-0">
                                                    <div class="card-header p-0 border-0 bg-transparent">
                                                        <img src="assets/img/product01.png" class="img-fluid"
                                                            alt="" />
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <p class="product-category">Category</p>
                                                        <h3 class="product-name">
                                                            <a href="#">Product name goes here</a>
                                                        </h3>
                                                        <div class="text-warning">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                        <h4 class="product-price product-old-price">
                                                            $980.00
                                                        </h4>
                                                    </div>
                                                    <div
                                                        class="card-footer border-0 bg-transparent d-flex justify-content-center pt-0">
                                                        <div class="quantity d-flex justify-content-center">
                                                            <button class="btn bag-btn text-white minusBtn rounded-0 px-1">
                                                                -
                                                            </button>
                                                            <input type="number"
                                                                class="form-control quantityInput rounded-end-0 rounded-start-0 w-50"
                                                                value="1" />
                                                            <button class="btn bag-btn text-white plusBtn rounded-0 px-1">
                                                                +
                                                            </button>
                                                        </div>
                                                        <button class="btn bag-btn text-white px-2">
                                                            <i class="bi bi-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="text-center">
                            <p class="badge light-warningbg">WE LOVE THEM</p>
                        </div>
                        <h2 class="hr-text text-center mt-2">
                            <span class="bg-white px-2">Featured Category</span>
                        </h2>
                    </div>
                    <div class="d-flex justify-content-between gap-2 mt-5 featured-category align-items-center">
                        @foreach ($categories as $category)
                            <div class="card rounded-4 h-100 text-center w-100">
                                <div class="card-body text-center h-100">
                                    <img src="assets/img/icon-1.jpeg" alt="" class="img-fluid" />
                                    <p class="fw-bold mt-3 mb-0">{{ $category->name }}</p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    {{-- <div class="latest-news mt-4">
                        <div class="text-center">
                            <p class="badge light-warningbg">WE LOVE THEM</p>
                        </div>
                        <h2 class="hr-text text-center mt-2">
                            <span class="bg-white px-2">Latest News</span>
                        </h2>
                        <div class="owl-slider mt-5">
                            <div id="carousel" class="owl-carousel">
                                <div class="item">
                                    <div class="card border-0">
                                        <div class="card-header border-0 bg-transparent">
                                            <img src="{{asset('front/assets/img/carousel-1.jpg')}}" alt=""
                                                class="img-fluid w-100" />
                                        </div>
                                        <div class="card-body">
                                            <h2 class="heading">
                                                Reverse heart disease without the Body of Parasites
                                            </h2>
                                            <p class="ellipses-txt">
                                                Fusce ac pharetra urna. Duis non lacus sit amet lacus
                                                interdum facilisis sed non est. Ut mi metus, semper
                                            </p>
                                            <a href="#"
                                                class="purple-txtcolor d-flex align-items-center gap-2"><span>Continue
                                                    Reading</span>
                                                <i class="fa-solid fa-arrow-right"></i></a>
                                        </div>
                                        <div class="card-footer bg-transparent mx-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-1 align-items-center">
                                                    <img src="assets/img/user.png" alt=""
                                                        class="img-fluid rounded-circle" />
                                                    <p class="m-0">by Rose Tyler</p>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <i class="bi bi-calendar3"></i>
                                                        <p class="m-0">Feb 22, 2024</p>
                                                    </div>
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <i class="bi bi-chat-left"></i>
                                                        <p class="m-0">2</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="card border-0">
                                        <div class="card-header border-0 bg-transparent">
                                            <img src="assets/img/carousel-1.jpg" alt=""
                                                class="img-fluid w-100" />
                                        </div>
                                        <div class="card-body">
                                            <h2 class="heading">
                                                Reverse heart disease without the Body of Parasites
                                            </h2>
                                            <p class="ellipses-txt">
                                                Fusce ac pharetra urna. Duis non lacus sit amet lacus
                                                interdum facilisis sed non est. Ut mi metus, semper
                                            </p>
                                            <a href="#"
                                                class="purple-txtcolor d-flex align-items-center gap-2"><span>Continue
                                                    Reading</span>
                                                <i class="fa-solid fa-arrow-right"></i></a>
                                        </div>
                                        <div class="card-footer bg-transparent mx-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-1 align-items-center">
                                                    <img src="assets/img/user.png" alt=""
                                                        class="img-fluid rounded-circle" />
                                                    <p class="m-0">by Rose Tyler</p>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <i class="bi bi-calendar3"></i>
                                                        <p class="m-0">Feb 22, 2024</p>
                                                    </div>
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <i class="bi bi-chat-left"></i>
                                                        <p class="m-0">2</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="card border-0">
                                        <div class="card-header border-0 bg-transparent">
                                            <img src="assets/img/carousel-1.jpg" alt=""
                                                class="img-fluid w-100" />
                                        </div>
                                        <div class="card-body">
                                            <h2 class="heading">
                                                Reverse heart disease without the Body of Parasites
                                            </h2>
                                            <p class="ellipses-txt">
                                                Fusce ac pharetra urna. Duis non lacus sit amet lacus
                                                interdum facilisis sed non est. Ut mi metus, semper
                                            </p>
                                            <a href="#"
                                                class="purple-txtcolor d-flex align-items-center gap-2"><span>Continue
                                                    Reading</span>
                                                <i class="fa-solid fa-arrow-right"></i></a>
                                        </div>
                                        <div class="card-footer bg-transparent mx-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-1 align-items-center">
                                                    <img src="assets/img/user.png" alt=""
                                                        class="img-fluid rounded-circle" />
                                                    <p class="m-0">by Rose Tyler</p>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <i class="bi bi-calendar3"></i>
                                                        <p class="m-0">Feb 22, 2024</p>
                                                    </div>
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <i class="bi bi-chat-left"></i>
                                                        <p class="m-0">2</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="card border-0">
                                        <div class="card-header border-0 bg-transparent">
                                            <img src="assets/img/carousel-1.jpg" alt=""
                                                class="img-fluid w-100" />
                                        </div>
                                        <div class="card-body">
                                            <h2 class="heading">
                                                Reverse heart disease without the Body of Parasites
                                            </h2>
                                            <p class="ellipses-txt">
                                                Fusce ac pharetra urna. Duis non lacus sit amet lacus
                                                interdum facilisis sed non est. Ut mi metus, semper
                                            </p>
                                            <a href="#"
                                                class="purple-txtcolor d-flex align-items-center gap-2"><span>Continue
                                                    Reading</span>
                                                <i class="fa-solid fa-arrow-right"></i></a>
                                        </div>
                                        <div class="card-footer bg-transparent mx-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-1 align-items-center">
                                                    <img src="assets/img/user.png" alt=""
                                                        class="img-fluid rounded-circle" />
                                                    <p class="m-0">by Rose Tyler</p>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <i class="bi bi-calendar3"></i>
                                                        <p class="m-0">Feb 22, 2024</p>
                                                    </div>
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <i class="bi bi-chat-left"></i>
                                                        <p class="m-0">2</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="card border-0">
                                        <div class="card-header border-0 bg-transparent">
                                            <img src="assets/img/carousel-1.jpg" alt=""
                                                class="img-fluid w-100" />
                                        </div>
                                        <div class="card-body">
                                            <h2 class="heading">
                                                Reverse heart disease without the Body of Parasites
                                            </h2>
                                            <p class="ellipses-txt">
                                                Fusce ac pharetra urna. Duis non lacus sit amet lacus
                                                interdum facilisis sed non est. Ut mi metus, semper
                                            </p>
                                            <a href="#"
                                                class="purple-txtcolor d-flex align-items-center gap-2"><span>Continue
                                                    Reading</span>
                                                <i class="fa-solid fa-arrow-right"></i></a>
                                        </div>
                                        <div class="card-footer bg-transparent mx-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-1 align-items-center">
                                                    <img src="assets/img/user.png" alt=""
                                                        class="img-fluid rounded-circle" />
                                                    <p class="m-0">by Rose Tyler</p>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <i class="bi bi-calendar3"></i>
                                                        <p class="m-0">Feb 22, 2024</p>
                                                    </div>
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <i class="bi bi-chat-left"></i>
                                                        <p class="m-0">2</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
    <!-- End Delivery Section -->

    <section class="mt-5 bg-light py-5">
        <div class="container-fluid">
            <div>
                <div class="text-center">
                    <p class="badge light-warningbg">WE LOVE THEM</p>
                </div>
                <h2 class="hr-text text-center mt-2 heading fs-2">
                    <span class="bg-light px-2">SHOP BY BRANDS</span>
                </h2>
            </div>
            <div class="framebox mt-5">
                <div class="owl-carousel px-2">
                    @foreach ($brands as $brand)
                        <a href="#" class="item rounded-3">
                            <img src="{{ asset('storage/brand/' . $brand->brand_img) }}" alt=""
                                class="img-fluid" />
                        </a>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
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
