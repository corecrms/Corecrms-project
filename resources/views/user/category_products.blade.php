@extends('user.layout.app')

@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.4.0/nouislider.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
@endsection
@section('content')
    <section class="">
        <div class="container">
            <h1 class="text-center py-5 text-decoration-underline">{{ $category->name }}</h1>
        </div>
    </section>

    <!-- Delivery Section -->
    <section class="">
        <i class="bi bi-funnel days-bg w-25 text-center text-white fs-3 rounded-4 rounded-start-0 d-block d-lg-none"
            data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft2" aria-controls="offcanvasLeft2"
            style="cursor: pointer"></i>
        <div class="container-xxl container-fluid">
            <div class="row">
                <div class="col-lg-3 pe-lg-0 pe-3 d-none d-lg-block">
                    <form action="{{ route('category.products', ['code' => $category->code]) }}" method="GET">

                        <div class="border-bottom pb-3">
                            <h4 class="heading mt-3">Filter by Price</h4>
                            {{-- <div class="w-100">
                                <div class="d-flex justify-content-between mb-2">
                                    <span id="minPrice">Less Price</span>
                                    <span id="maxPrice">High Price</span>
                                </div>
                                <div id="priceRange"></div>
                            </div> --}}
                            <div class="">
                                <select name="priceRangSelect" class="form-control" id="priceRangSelect">
                                    <option disabled selected>Select Price</option>
                                    <option value="1-500">1-500</option>
                                    <option value="500-1000">500-1000</option>
                                    <option value="1000-1500">1000-1500</option>
                                    <option value="1500-2000">1500-2000</option>
                                </select>
                            </div>
                            <input type="hidden" id="minPriceInput" name="min_price" value="">
                            <input type="hidden" id="maxPriceInput" name="max_price" value="">
                        </div>

                        <button class="btn btn-primary mt-4" type="submit">Search</button>
                    </form>

                    @if ($ads)
                        {{-- <div class="p-5 ps-4 mt-4 border-0  w-100 text-center">
                            <img class="img-fluid rounded-3" src="{{ asset('/storage/' . $ads->image) }}" />
                        </div> --}}
                        <a href="{{ $ads->url ?? '' }}" target="_blank">
                            <div class="p-2 ps-2 mt-4 border-0  w-100 text-center">
                                <img class="img-fluid rounded-3" src="{{ asset('/storage/' . $ads->image) }}" />
                            </div>
                        </a>
                    @endif

                </div>
                <div class="col-lg-9">
                    <div class="card rounded-4 border-0">
                        <div class="card-body">
                            <div class="d-flex flex-wrap mt-3">
                                {{-- @forelse ($products as $product)
                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                        <a
                                            href="{{ route('user.product.index', ['code' => $product->category->code, 'sku' => $product->sku]) }}">
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

                                                    @auth
                                                        <div class="d-flex justify-content-between mt-3">
                                                            <h4 class="product-price product-old-price m-0 p-0">
                                                                ${{ $product->sell_price ?? '0.00' }}
                                                            </h4>

                                                            @if ($product->product_warehouses && $product->totalQuantity() > 0)
                                                                <div class="badge text-bg-success">
                                                                    In Stock
                                                                </div>
                                                            @else
                                                                <div class="badge text-bg-danger">
                                                                    Out of Stock
                                                                </div>
                                                            @endif

                                                        </div>

                                                        <form action="{{ route('add-to-cart.index') }}" method="POST">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="form-group mt-2">
                                                                <input type="hidden" name="warehouse_stock"
                                                                    id="warehouse_stock" value="">

                                                                <select class="form-select" id="warehouse_id"
                                                                    name="warehouse_id">
                                                                    @php
                                                                        $warehouses = $product->product_warehouses->filter(
                                                                            function ($warehouse) {
                                                                                return $warehouse->quantity > 0;
                                                                            },
                                                                        );
                                                                    @endphp


                                                                    @if ($warehouses->isNotEmpty())
                                                                        @foreach ($warehouses as $warehouse)
                                                                            <option value="{{ $warehouse->warehouse->id }}"
                                                                                data-quantity="{{ $warehouse->quantity }}"
                                                                                data-address="{{ $warehouse->warehouse->users->address }}">
                                                                                {{ $warehouse->warehouse->users->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @else
                                                                        <option value="" selected>No Warehouse Available
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                            </div>

                                                            <div class=" border-0 bg-transparent d-flex gap-2 pt-0 mt-3">
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
                                                                <button class="btn btn-primary text-white" type="submit"
                                                                    @if ($product->totalQuantity() == 0) disabled @endif>
                                                                    <i class="bi bi-bag"></i>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    @endauth
                                                </div>

                                            </div>
                                        </a>

                                    </div>
                                @empty
                                    <div class="col-lg-12 mt-3">
                                        <h3 class="text-center">No Products Found</h3>
                                    </div>
                                @endforelse --}}

                                @foreach ($products as $product)
                                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                                        <a
                                            href="{{ route('user.product.index', ['code' => $product->category->code, 'sku' => $product->sku]) }}">
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
                                                <div class="card-footer border-0 bg-transparent text-center">
                                                    <p class="product-category">{{ $product->category->name ?? '' }}</p>
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

                                                    @if ($setting->show_pricing == 1 && auth()->user() == null)
                                                        <h4 class="product-price product-old-price">
                                                            ${{ $product->sell_price ?? '0.00' }}
                                                        </h4>                                                     
                                                    @endif

                                                    @auth
                                                        <div class="d-flex justify-content-between mt-3">
                                                            <h4 class="product-price product-old-price m-0 p-0">
                                                                ${{ $product->sell_price ?? '0.00' }}
                                                            </h4>

                                                            @if ($product->product_warehouses && $product->totalQuantity() > 0)
                                                                <div class="badge text-bg-success">
                                                                    In Stock
                                                                </div>
                                                            @else
                                                                <div class="badge text-bg-danger">
                                                                    Out of Stock
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <form action="{{ route('add-to-cart.index') }}" method="POST">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="form-group mt-2">
                                                                <input type="hidden" name="warehouse_stock"
                                                                    id="warehouse_stock_{{ $product->id }}" value="0">
                                                                <select class="form-select"
                                                                    id="warehouse_id_{{ $product->id }}" name="warehouse_id">
                                                                    @php
                                                                        $warehouses = $product->product_warehouses->filter(
                                                                            function ($warehouse) {
                                                                                return $warehouse->quantity > 0;
                                                                            },
                                                                        );
                                                                    @endphp
                                                                    @if ($warehouses->isNotEmpty())
                                                                        @foreach ($warehouses as $warehouse)
                                                                            <option value="{{ $warehouse->warehouse->id }}"
                                                                                data-quantity="{{ $warehouse->quantity }}"
                                                                                data-address="{{ $warehouse->warehouse->users->address }}">
                                                                                {{ $warehouse->warehouse->users->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @else
                                                                        <option value="" selected>No Warehouse Available
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                            </div>

                                                            <div class="border-0 bg-transparent d-flex gap-2 pt-0 mt-3">
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
                                                                <button class="btn btn-primary text-white" type="submit"
                                                                    @if ($product->totalQuantity() == 0) disabled @endif>
                                                                    <i class="bi bi-bag"></i>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    @endauth
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
    </section>

    {{-- mobile view filter  --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasLeft2" aria-labelledby="offcanvasLeftLabel">
        <div class="offcanvas-header">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('category.products', ['code' => $category->code]) }}" method="GET">

                <div class="border-bottom pb-3">
                    <h4 class="heading mt-3">Filter by Price</h4>
                    {{-- <div class="w-100">
                        <div class="d-flex justify-content-between mb-2">
                            <span id="minPrice2">Less Price</span>
                            <span id="maxPrice2">High Price</span>
                        </div>
                        <div id="priceRange2"></div>
                    </div> --}}
                    <div class="">
                        <select name="priceRangSelect" class="form-control" id="priceRangSelect2">
                            <option disabled selected>Select Price</option>
                            <option value="1-500">1-500</option>
                            <option value="500-1000">500-1000</option>
                            <option value="1000-1500">1000-1500</option>
                            <option value="1500-2000">1500-2000</option>
                        </select>
                    </div>
                    <input type="hidden" id="minPriceInput2" name="min_price" value="">
                    <input type="hidden" id="maxPriceInput2" name="max_price" value="">
                </div>

                <button class="btn btn-primary mt-4" type="submit">Search</button>
            </form>

            @if ($ads)
                <a href="{{ $ads->url ?? '' }}" target="_blank">
                    <div class="p-2 ps-2 mt-4 border-0  w-100 text-center">
                        <img class="img-fluid rounded-3" src="{{ asset('/storage/' . $ads->image) }}" />
                    </div>
                </a>
            @endif

        </div>
    </div>

    @include('user.components.brands')
@endsection


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.js"></script>


    {{-- <script>
        $(document).ready(function() {
            var priceRange = document.getElementById("priceRange");
            let min_price = @json($min_price) ?? 0;
            let max_price = @json($max_price) ?? 0;

            console.log(max_price)
            noUiSlider.create(priceRange, {
                start: [min_price, max_price],
                connect: true,
                range: {
                    min: parseInt(min_price),
                    max: parseInt(max_price),
                },
                step: 10,
            });

            priceRange.noUiSlider.on("update", function(values, handle) {
                $("#minPrice").text(values[0]);
                $("#maxPrice").text(values[1]);
                // $("#minPriceInput").val(values[0]);
                // $("#maxPriceInput").val(values[1]);
                if (handle === 0) {
                    $("#minPriceInput").val(values[0]);
                } else {
                    $("#maxPriceInput").val(values[1]);
                }
            });

        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var priceRangeSelect = document.getElementById('priceRangSelect');
            var minPriceInput = document.getElementById('minPriceInput');
            var maxPriceInput = document.getElementById('maxPriceInput');

            priceRangeSelect.addEventListener('change', function() {
                var selectedValue = this.value;
                var priceRange = selectedValue.split('-');

                if (priceRange.length === 2) {
                    minPriceInput.value = priceRange[0];
                    maxPriceInput.value = priceRange[1];
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var priceRangeSelect = document.getElementById('priceRangSelect2');
            var minPriceInput = document.getElementById('minPriceInput2');
            var maxPriceInput = document.getElementById('maxPriceInput2');

            priceRangeSelect.addEventListener('change', function() {
                var selectedValue = this.value;
                var priceRange = selectedValue.split('-');

                if (priceRange.length === 2) {
                    minPriceInput.value = priceRange[0];
                    maxPriceInput.value = priceRange[1];
                }
            });
        });

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
        // $(document).on("change", "#warehouse_id", function() {
        //     var quantity = $(this).find('option:selected').data('quantity');
        //     // $('#stock').text(quantity);
        //     $('#warehouse_stock').val(quantity);
        //     alert(quantity);
        // });
        // $('#warehouse_id').trigger('change');

        $(document).ready(function() {
            $("select[id^='warehouse_id_']").each(function() {
                $(this).trigger('change');
            });
        });

        $(document).on("change", "select[id^='warehouse_id_']", function() {
            var productId = $(this).attr('id').split('_')[2];
            var quantity = $(this).find('option:selected').data('quantity');
            $('#warehouse_stock_' + productId).val(quantity);
        });
    </script>
@endsection
