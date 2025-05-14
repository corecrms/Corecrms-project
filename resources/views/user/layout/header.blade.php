<style>
    @media (max-width: 990px) {
        #top-right-btn {
            margin-top: 15px !important
        }
    }
</style>
<!-- HEADER -->
<header>
    <!-- MAIN HEADER -->
    <div id="header" class="py-2">
        <div class="container-xxl container-fluid">
            <!-- <div class="row">
          <div class="col-md-2">
            <div class="d-flex gap-3"></div>
          </div>
        </div> -->
            <div class="row align-items-center">
                <div class="col-lg-2 text-center text-md-start">
                    <div class="d-flex justify-content-between gap-3">
                        <a href="{{ route('/') }}" class="logo text-white">
                            @if (getLogo())
                                <img src="{{ asset('storage' . getLogo()) }}" alt="logo" width="120"
                                style="width: 90px; object-fit: contain;"/>
                            @else
                                <h1 class="text-white">Logo</h1>
                            @endif
                        </a>

                        <i class="bi bi-list btn fs-3 text-white" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasLeft" aria-controls="offcanvasLeft"></i>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="header-search">
                        @php
                            $categories = App\Models\Category::where('status', 1)->get();
                        @endphp
                        <form class="d-flex justify-content-start align-items-center w-100">
                            <select class="form-control form-select rounded-end-0 w-25 rounded-start-5 p-2 w-100"
                                aria-label="Default select example" id="searchCategoryId">
                                <option value="" selected>All</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Products"
                                    class="form-control rounded-start-0 rounded-end-5 pe-5 p-2 header-input searchInput" id="searchInput" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </form>
                        <div id="suggestionsContainerLoading" style="display: none;"></div>
                    </div>
                </div>

                <div class="col-lg-3  d-flex justify-content-between gap-2" id="top-right-btn">
                    <div class="d-flex gap-2">
                        @role(['Admin', 'Manager', 'Cashier'])
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                        @endrole
                        @role('Client')
                            <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Dashboard</a>
                        @endrole
                        @auth
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button class="btn btn-danger">Logout</button>
                            </form>
                        @else
                            <div class="d-flex gap-1 align-items-center text-white">
                                <i class="bi bi-person fs-3 fw-bold"></i>
                                <a href="{{ route('login') }}">
                                    <h5 class="title m-0 fw-bold text-white">Sign In</h5>
                                </a>
                            </div>
                            <div class="d-flex gap-1 align-items-center text-white">
                                <i class="fa-regular fa-heart fs-4"></i>
                                <a href="{{ route('register') }}">
                                    <h5 class="title m-0 fw-bold text-white">Sign Up</h5>
                                </a>
                            </div>
                        @endauth
                    </div>
                    @if (auth()->user())
                        @php
                            // $cartPrice =  App\Models\AddToCart::where('customer_id',auth()->id())->sum('price');
                            $carts = App\Models\AddToCart::where('customer_id', auth()->id())->get();
                            $cartPrice = 0;
                            foreach ($carts as $cart) {
                                $cartPrice += $cart->price * $cart->quantity;
                            }
                        @endphp
                    @endif
                    <a href="{{ route('add-to-cart.index') }}" class="btn rounded-5 bg-white">
                        <i class="bi bi-cart3"></i>
                        <span>${{ number_format($cartPrice ?? '0.00',2) }}</span>
                    </a>

                </div>
            </div>
        </div>
    </div>
</header>
<!-- /HEADER -->
