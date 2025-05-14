@extends('user.layout.app')

@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.4.0/nouislider.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <style>
        /* For WebKit browsers (Chrome, Safari) */
        #categoriesList::-webkit-scrollbar {
            width: 12px;
            /* Width of the scrollbar */
        }

        #categoriesList::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Background of the scrollbar track */
        }

        #categoriesList::-webkit-scrollbar-thumb {
            background: rgba(76, 73, 227, 1);
            /* Color of the scrollbar thumb */
            border-radius: 6px;
            /* Rounded corners */
        }

        #categoriesList::-webkit-scrollbar-thumb:hover {
            background: #555;
            /* Darker color on hover */
        }

        /* For Firefox */
        #categoriesList {
            scrollbar-width: thin;
            /* Make scrollbar thinner */
            scrollbar-color: rgba(76, 73, 227, 1) #f1f1f1;
            /* Thumb color and track color */
        }


        .fade-in {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeIn 0.5s ease-in-out forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
@section('content')
    <section class="">
        <div class="container">
            <h1 class="text-center py-5">Store</h1>

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
                    <form action="{{ route('shop') }}" method="GET">

                        <div class="border-bottom pb-2">
                            <h4 class="heading">Filters by categories</h4>
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Find a Category" class="form-control rounded-4 ps-5 p-2"
                                    id="categorySearchInput" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                            <div id="categoriesList" class="p-2" style="height: 700px; overflow-y: scroll;">
                                @forelse ($categories as $category)
                                    @if ($category->products->count() > 0)
                                        <div class="d-flex justify-content-between gap-2 align-items-center mt-3 flex-wrap category-item"
                                            data-category-name="{{ strtolower($category->name) }}">
                                            <div class="d-flex gap-3 align-items-center">
                                                {{-- <div class="checkbox">
                                                        <input class="checkbox__input" type="checkbox"
                                                            id="category{{ $category->id }}" name="categories[]"
                                                            value="{{ $category->id }}" />
                                                        <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 22 22">
                                                            <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                                stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                            <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                                stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                                        </svg>
                                                    </div> --}}
                                                {{-- bootstrap checkbox  --}}

                                                <input class="form-check-input" type="checkbox"
                                                    id="category{{ $category->id }}" name="categories[]"
                                                    value="{{ $category->id }}" style="width: 1.375em;height:1.375em;" />
                                                <label for="category{{ $category->id }}" class="form-check-label" style="cursor: pointer;">{{ $category->name ?? '' }}</label>
                                            </div>
                                            <div class="">
                                                <p class="bg-light p-1 rounded-3 m-0 text-end">
                                                    {{ $category->products->count() }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <p>No Category Found</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="border-bottom pb-3">
                            <h4 class="heading mt-3">Filter by Price</h4>
                            {{-- <div class="w-100">
                                <div class="d-flex justify-content-between mb-2">
                                    <span id="minPrice">Less Price</span>
                                    <span id="maxPrice">High Price</span>
                                </div>
                                <div id="priceRange"></div>
                            </div> --}}
                            {{-- <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><img
                                        src="{{asset('dashasset/img/darhboard.svg')}}" alt="" class="me-2" />Shop</a>
                                <div class="dropdown-menu bg-transparent border-0 text-white mt-0 pt-0">
                                    <a href="#" class="dropdown-item">$1-$500</a>
                                    <a href="#" class="dropdown-item">$500-$1000</a>
                                    <a href="#" class="dropdown-item">$1000-$1500</a>
                                    <a href="#" class="dropdown-item">$1500-$2000</a>
                                    <a href="#" class="dropdown-item">$2000-$3000</a>
                                </div>
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

                        <div class="border-bottom pb-2">
                            <h4 class="heading mt-3">Brands</h4>
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Find a Brand" class="form-control rounded-4 ps-5 p-2"
                                    id="brandSearchInput" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                            <div class="mt-3" id="brandList">
                                @forelse ($brands as $brand)
                                    <div class="d-flex gap-3 align-items-center mt-1 brand-item"
                                        data-brand-name="{{ strtolower($brand->name) }}">
                                        <div class="checkbox">
                                            <input class="checkbox__input" type="checkbox" id="brand{{ $brand->id }}"
                                                name="brands[]" value="{{ $brand->id }}" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </div>
                                        <label for="brand{{ $brand->id }}">{{ $brand->name ?? '' }}</label>
                                    </div>
                                @empty
                                    <p>No Brands Found</p>
                                @endforelse
                            </div>
                        </div>
                        <button class="btn btn-primary mt-3" type="submit">Search</button>
                    </form>

                    @if ($ads)
                        {{-- <div class="p-5 ps-4 d-none d-md-block mt-3"
                            style="background: url({{ asset('/storage/' . $ads->image) }});height: 65vh;width: 100%;">
                            <h3 class="title fs-3 mt-4">
                                {{ $ads->title }}
                            </h3>
                            <p>{{ $ads->description }}</p>
                        </div> --}}
                        <a href="{{ $ads->url ?? '' }}" target="_blank">
                            <div class="p-5 ps-4 mt-4 border-0  w-100 text-center">
                                <img class="img-fluid rounded-3" src="{{ asset('/storage/' . $ads->image) }}" />
                            </div>
                        </a>
                    @endif

                </div>
                <div class="col-lg-9">
                    <div class="card rounded-4 border-0">
                        <div class="card-body">
                            <div class="d-flex flex-wrap mt-3" id="product-list" style="transition: all 0.8s;">
                                @forelse ($products as $product)
                                    <div class="selling-product col-sm-12 col-lg-3 col-md-4 p-2 border">
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
                                                    @if ($setting->show_pricing == 1)
                                                        <h4 class="product-price product-old-price">
                                                            ${{ $product->sell_price ?? '0.00' }}</h4>
                                                    @else
                                                        @auth
                                                            <h4 class="product-price product-old-price">
                                                                ${{ $product->sell_price ?? '0.00' }}</h4>
                                                        @endauth
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <div class="mt-3 w-100">
                                        <h4 class="text-center">No Product Found!</h4>
                                    </div>
                                @endforelse
                            </div>

                            <div class="text-center mt-3">
                                <button id="load-more" class="btn btn-primary">
                                    Load More
                                    <div id="loading-spinner"
                                        class=" me-3 spinner-border spinner-border-sm text-light d-none" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>

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
            <form action="{{ route('shop') }}" method="GET">

                <div class="border-bottom pb-2">
                    <h4 class="heading">Filters by categories</h4>
                    <div class="input-search position-relative">
                        <input type="text" placeholder="Find a Category" class="form-control rounded-4 ps-5 p-2"
                            id="categorySearchInput2" />
                        <span class="fa fa-search search-icon text-secondary"></span>
                    </div>
                    <div id="categoriesList">
                        @forelse ($categories as $category)
                            @if ($category->products->count() > 0)
                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 flex-wrap category-item2"
                                    data-category-name="{{ strtolower($category->name) }}">
                                    <div class="d-flex gap-3 align-items-center">
                                        <div class="checkbox">
                                            <input class="checkbox__input" type="checkbox"
                                                id="category-m-{{ $category->id }}" name="categories[]"
                                                value="{{ $category->id }}" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </div>
                                        <label for="category-m-{{ $category->id }}">{{ $category->name ?? '' }}</label>
                                    </div>
                                    <div class="">
                                        <p class="bg-light p-1 rounded-3 m-0 text-end">
                                            {{ $category->products->count() }}</p>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p>No Category Found</p>
                        @endforelse
                    </div>
                </div>

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

                <div class="border-bottom pb-2">
                    <h4 class="heading mt-3">Brands</h4>
                    <div class="input-search position-relative">
                        <input type="text" placeholder="Find a Brand" class="form-control rounded-4 ps-5 p-2"
                            id="brandSearchInput2" />
                        <span class="fa fa-search search-icon text-secondary"></span>
                    </div>
                    <div class="mt-3" id="brandList">
                        @forelse ($brands as $brand)
                            <div class="d-flex gap-3 align-items-center mt-1 brand-item"
                                data-brand-name="{{ strtolower($brand->name) }}">
                                <div class="checkbox">
                                    <input class="checkbox__input" type="checkbox" id="brand{{ $brand->id }}"
                                        name="brands[]" value="{{ $brand->id }}" />
                                    <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22">
                                        <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                            stroke="rgba(76, 73, 227, 1)" rx="3" />
                                        <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                            stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                    </svg>
                                </div>
                                <label for="brand{{ $brand->id }}">{{ $brand->name ?? '' }}</label>
                            </div>
                        @empty
                            <p>No Brands Found</p>
                        @endforelse
                    </div>
                </div>
                <button class="btn btn-primary mt-3" type="submit">Search</button>
            </form>

            @if ($ads)
                {{-- <div class="p-5 ps-4 d-none d-md-block mt-3"
                    style="background: url({{ asset('/storage/' . $ads->image) }});height: 65vh;width: 100%;">
                    <h3 class="title fs-3 mt-4">
                        {{ $ads->title }}
                    </h3>
                    <p>{{ $ads->description }}</p>
                </div> --}}
                <a href="{{ $ads->url ?? '' }}" target="_blank">
                    <div class="p-5 ps-4 mt-4 border-0  w-100 text-center">
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
    <script>
        $(document).ready(function() {

        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            var priceRange = document.getElementById("priceRange");

            noUiSlider.create(priceRange, {
                start: [100, 900],
                connect: true,
                range: {
                    min: 0,
                    max: 1000,
                },
                step: 10,
            });

            priceRange.noUiSlider.on("update", function(values, handle) {
                $("#minPrice").text(values[0]);
                $("#maxPrice").text(values[1]);
            });
        });
    </script> --}}

    {{-- <script>
        $(document).ready(function() {
            var priceRange = document.getElementById("priceRange");
            let min_price = @json($min_price) ?? 0;
            let max_price = @json($max_price) ?? 0;


            if (parseInt(min_price) == parseInt(max_price)) {

                // Adjust max_price if it is equal to min_price
                max_price += 10;
            }

            console.log(parseInt(min_price), parseInt(max_price))
            noUiSlider.create(priceRange, {
                start: [parseInt(min_price), parseInt(max_price)],
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
    {{-- <script>
        $(document).ready(function() {
            var priceRange = document.getElementById("priceRange2");
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
                $("#minPrice2").text(values[0]);
                $("#maxPrice2").text(values[1]);
                // $("#minPriceInput").val(values[0]);
                // $("#maxPriceInput").val(values[1]);
                if (handle === 0) {
                    $("#minPriceInput2").val(values[0]);
                } else {
                    $("#maxPriceInput2").val(values[1]);
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
    </script>

    <script>
        document.getElementById('categorySearchInput').addEventListener('input', function() {
            var searchQuery = this.value.toLowerCase();
            var categoryItems = document.querySelectorAll('.category-item');
            // console.log("Search Query:", searchQuery);
            categoryItems.forEach(function(item) {
                var categoryName = item.getAttribute('data-category-name');
                // console.log("Category Name:", categoryName);
                if (categoryName.includes(searchQuery)) {
                    // item.style.display = 'flex';
                    item.classList.remove("d-none");
                    item.classList.add("d-flex");
                    // console.log("Showing:", categoryName);
                } else {
                    // item.style.display = 'none';
                    item.classList.remove("d-flex");
                    item.classList.add("d-none");
                    // console.log("Hiding:", categoryName);
                }
            });
        });
        // document.getElementById('brandSearchInput').addEventListener('input', function() {
        //     var searchQuery = this.value.toLowerCase();
        //     console.log(searchQuery);
        //     var brandItems = document.querySelectorAll('.brand-item');
        //     // console.log("Search Query:", searchQuery);
        //     brandItems.forEach(function(item) {
        //         var brandName = item.getAttribute('data-brand-name');
        //         // console.log("Category Name:", brandName);
        //         if (brandName.includes(searchQuery)) {
        //             // item.style.display = 'flex';
        //             item.classList.remove("d-none");
        //             item.classList.add("d-flex");
        //             // console.log("Showing:", brandName);
        //         } else {
        //             // item.style.display = 'none';
        //             item.classList.remove("d-flex");
        //             item.classList.add("d-none");
        //             // console.log("Hiding:", brandName);
        //         }
        //     });
        // });
        document.getElementById('brandSearchInput').addEventListener('input', function() {
            var searchQuery = this.value.toLowerCase();
            var brandItems = document.querySelectorAll('.brand-item');

            brandItems.forEach(function(item) {
                var brandName = item.getAttribute('data-brand-name');

                if (brandName && brandName.toLowerCase().includes(
                        searchQuery)) { // Add null check and convert brandName to lowercase
                    item.classList.remove("d-none");
                    item.classList.add("d-flex");
                } else {
                    item.classList.remove("d-flex");
                    item.classList.add("d-none");
                }
            });
        });

        document.getElementById('categorySearchInput2').addEventListener('input', function() {
            var searchQuery = this.value.toLowerCase();
            var categoryItems = document.querySelectorAll('.category-item2');
            // console.log("Search Query:", searchQuery);
            categoryItems.forEach(function(item) {
                var categoryName = item.getAttribute('data-category-name');
                // console.log("Category Name:", categoryName);
                if (categoryName.includes(searchQuery)) {
                    // item.style.display = 'flex';
                    item.classList.remove("d-none");
                    item.classList.add("d-flex");
                    // console.log("Showing:", categoryName);
                } else {
                    // item.style.display = 'none';
                    item.classList.remove("d-flex");
                    item.classList.add("d-none");
                    // console.log("Hiding:", categoryName);
                }
            });
        });
        document.getElementById('brandSearchInput2').addEventListener('input', function() {
            var searchQuery = this.value.toLowerCase();
            var brandItems = document.querySelectorAll('.brand-item');
            // console.log("Search Query:", searchQuery);
            brandItems.forEach(function(item) {
                var brandName = item.getAttribute('data-brand-name');
                // console.log("Category Name:", brandName);
                if (brandName.includes(searchQuery)) {
                    // item.style.display = 'flex';
                    item.classList.remove("d-none");
                    item.classList.add("d-flex");
                    // console.log("Showing:", brandName);
                } else {
                    // item.style.display = 'none';
                    item.classList.remove("d-flex");
                    item.classList.add("d-none");
                    // console.log("Hiding:", brandName);
                }
            });
        });
    </script>

    {{-- <script>
        let page = 1;

        document.getElementById('load-more').addEventListener('click', function() {

            const loadMoreButton = document.getElementById('load-more');
            const loadingSpinner = document.getElementById('loading-spinner');


            loadingSpinner.classList.remove('d-none');
            loadMoreButton.disabled = true;
            page++;
            let fullUrl = window.location.href;
            fetch(`${fullUrl}?page=${page}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.data.length > 0) {
                        data.data.forEach(product => {
                            const productHtml = `
                    <div class="selling-product col-lg-3 col-md-4 p-2 border">
                        <a href="/category/${product.category.code}/product/${product.sku}">
                            <div class="card tab-card border-0 rounded-0">
                                <div class="card-body p-0 text-center">
                                    <img src="${product.images.length > 0 ? '/storage' + product.images[0].img_path : 'back/assets/image/no-image.png'}" alt="No" class="img-fluid">
                                </div>
                                <div class="card-footer border-0 bg-transparent text-center">
                                    <p class="product-category">${product.category.name}</p>
                                    <h3 class="product-name">
                                        <a href="/category/${product.category.code}/product/${product.sku}">${product.name}</a>
                                    </h3>
                                </div>
                            </div>
                        </a>
                    </div>`;
                            document.getElementById('product-list').insertAdjacentHTML('beforeend',
                                productHtml);
                        });
                    } else {
                        document.getElementById('load-more').style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching products:', error))
                .finally(() => {
                    loadingSpinner.classList.add('d-none');
                    loadMoreButton.disabled = false;
                });


        });
    </script> --}}

    <script>
        let page = 1;

        document.getElementById('load-more').addEventListener('click', function() {
            const loadMoreButton = document.getElementById('load-more');
            const loadingSpinner = document.getElementById('loading-spinner');

            loadingSpinner.classList.remove('d-none');
            loadMoreButton.disabled = true;
            page++;
            let fullUrl = window.location.href;

            fetch(`${fullUrl}?page=${page}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.data.length > 0) {
                        data.data.forEach(product => {
                            const productHtml = `
                                <div class="selling-product col-sm-12 col-lg-3 col-md-4 p-2 border fade-in">
                                    <a href="/category/${product.category.code}/product/${product.sku}">
                                        <div class="card tab-card border-0 rounded-0">
                                            <div class="card-body p-0 text-center">
                                                <img src="${product.images.length > 0 ? '/storage' + product.images[0].img_path : 'back/assets/image/no-image.png'}" alt="No" class="img-fluid">
                                            </div>
                                            <div class="card-footer border-0 bg-transparent text-center">
                                                <p class="product-category">${product.category.name}</p>
                                                <h3 class="product-name">
                                                    <a href="/category/${product.category.code}/product/${product.sku}">${product.name}</a>
                                                </h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>`;

                            // Append the product to the product list
                            const productList = document.getElementById('product-list');
                            productList.insertAdjacentHTML('beforeend', productHtml);

                            // Remove fade-in class after the animation completes
                            const newProduct = productList.lastElementChild;
                            setTimeout(() => {
                                newProduct.classList.remove('fade-in');
                            }, 500); // Matches the animation duration (0.5s)
                        });
                    } else {
                        document.getElementById('load-more').style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching products:', error))
                .finally(() => {
                    loadingSpinner.classList.add('d-none');
                    loadMoreButton.disabled = false;
                });
        });
    </script>
@endsection
