    <!-- NAVIGATION -->
    <nav id="navigation" class="d-none d-lg-block">

        <div class="container-xxl container-fluid border-bottom py-2">
            <div class="row align-items-center">
                <div class="col-md-2 ">
                    {{-- <div class="dropdown">
                        <button class="btn border-0 {{ request()->routeIs('/') ? 'show' : '' }}" type="button"
                            data-bs-toggle="dropdown" aria-expanded="true">
                            Categories <i class="bi bi-chevron-down ms-2"></i>
                        </button>
                        <ul class="dropdown-menu mt-2 border-top-0 rounded-0 pb-0 {{ request()->routeIs('/') ? 'show' : '' }}"
                            style="
                                position: absolute;
                                inset: 0px auto auto 0px;
                                margin: 0px;
                                transform: translate(0px, 40px);
                                width: 228px;
                            "
                            data-popper-placement="bottom-start">
                            @php
                                $categories = App\Models\Category::where('status', 1)->get();
                            @endphp
                            @foreach ($categories as $category)
                                <li class="border-bottom">
                                    <div class="dropdown hover-dropdown">
                                        <button class="btn d-flex justify-content-between w-100" type="button">
                                            <span>
                                                <img src="{{ asset('storage/category/' . $category->icon) }}"
                                                    alt="" style="width: 20px;" class="rounded-cicle">
                                                {{ $category->name }}</span>
                                            @if ($category->subcategories->count() > 0)
                                                <i class="bi bi-chevron-right ms-2"></i>
                                            @endif
                                        </button>

                                        @if ($category->subcategories->count() > 0)
                                            <ul class="dropdown-menu hover-dropdown-menu"
                                                data-popper-placement="bottom-start" style="width:1070px; ">
                                                <div class="row">

                                                    @foreach ($category->subcategories as $subcategory)
                                                        <div class="col-3 col-md-3">
                                                            <div class="">
                                                                <a href="{{ route('category.products', ['code' => $subcategory->code]) }}"
                                                                    class="text-decoration-none text-dark p-2 w-100"
                                                                    style="transition: font-weight 0.3s; font-weight: normal;"
                                                                    onmouseover="this.style.fontWeight='bold';"
                                                                    onmouseout="this.style.fontWeight='normal';">
                                                                     {{ $subcategory->name }}
                                                                 </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </ul>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div> --}}
                    {{-- <div class="dropdown">
                        <button class="btn border-0 {{ request()->routeIs('/') || request()->routeIs('category.products') ||  request()->routeIs('shop') ? 'show' : '' }}" type="button"
                            data-bs-toggle="dropdown" aria-expanded="true">
                            Categories <i class="bi bi-chevron-down ms-2"></i>
                        </button>
                        <ul class="dropdown-menu mt-2 border-top-0 rounded-0 pb-0 {{ request()->routeIs('/') || request()->routeIs('category.products') ||  request()->routeIs('shop') ? 'show' : '' }}"
                            style="
                                position: absolute;
                                inset: 0px auto auto 0px;
                                margin: 0px;
                                transform: translate(0px, 40px);
                                width: 228px;
                            "
                            data-popper-placement="bottom-start">
                            @php
                                $brands = App\Models\Brand::with('category')->get();
                            @endphp
                            @foreach ($brands as $brand)
                                <li class="border-bottom">
                                    <div class="dropdown hover-dropdown">
                                        <button class="btn d-flex justify-content-between w-100" type="button">
                                            <span>
                                                <img src="{{ asset('storage/brand/' . $brand->brand_img) }}"
                                                    alt="" style="width: 20px;" class="rounded-cicle">
                                                {{ $brand->name }}</span>
                                            @if ($brand->category->count() > 0)
                                                <i class="bi bi-chevron-right ms-2"></i>
                                            @endif
                                        </button>

                                        @if ($brand->category->count() > 0)
                                            <ul class="dropdown-menu hover-dropdown-menu"
                                                data-popper-placement="bottom-start" style="width:1070px; ">
                                                <div class="row ms-0">

                                                    @foreach ($brand->category as $category)
                                                        <div class="col-3 col-md-3">
                                                            <span class="fw-bold fs-6 ps-2 text-decoration-underline mb-5">
                                                                {{ $category->name ?? '' }}
                                                            </span>
                                                            @foreach ($category->subcategories as $subcategory)
                                                                <div class="{{$loop->iteration == 1 ? 'mt-2': ''}}">
                                                                    <a href="{{ route('category.products', ['code' => $subcategory->code]) }}"
                                                                        class="text-decoration-none text-dark p-2 w-100"
                                                                        style="transition: font-weight 0.3s; font-weight: normal;"
                                                                        onmouseover="this.style.fontWeight='bold';"
                                                                        onmouseout="this.style.fontWeight='normal';">
                                                                        {{ $subcategory->name }}
                                                                    </a>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    @endforeach
                                                </div>
                                            </ul>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div> --}}

                    <div class="dropdown ">
                        <button class="btn border-0 {{ request()->routeIs('/') || request()->routeIs('category.products') ||  request()->routeIs('shop') ? 'show' : '' }}" type="button"
                            data-bs-toggle="dropdown" aria-expanded="true">
                            Categories <i class="bi bi-chevron-down ms-2"></i>
                        </button>
                        <ul class="dropdown-menu mt-2 shadow border-top-0 pb-0 {{ request()->routeIs('/') || request()->routeIs('category.products') ||  request()->routeIs('shop') ? 'show' : '' }}"
                            style="
                                position: absolute;
                                inset: 0px auto auto 0px;
                                margin: 0px;
                                transform: translate(0px, 40px);
                                width: 228px;
                                border-radius: 0 0 7px 7px;                              
                            "
                            data-popper-placement="bottom-start">
                            @php
                                $brands = App\Models\Brand::with('category')->get();
                                $visibleBrands = request()->routeIs('shop') || request()->routeIs('category.products') ? 2:5;  // Number of brands to show initially
                            @endphp

                            @foreach ($brands as $index => $brand)
                                <li class="border-bottom brand-item {{ $index >= $visibleBrands ? 'd-none' : '' }}">
                                    <div class="dropdown hover-dropdown manu-hover">
                                        <button class="btn d-flex justify-content-between w-100" type="button">
                                            <span>
                                                {{-- <img src="{{ asset('storage/brand/' . $brand->brand_img) }}" alt="" style="width: 20px;" class="rounded-circle"> --}}
                                                {{ $brand->name }}
                                            </span>
                                            @if ($brand->category->count() > 0)
                                                <i class="bi bi-chevron-right ms-2"></i>
                                            @endif
                                        </button>

                                        @if ($brand->category->count() > 0)
                                            <ul class="dropdown-menu hover-dropdown-menu" data-popper-placement="bottom-start" style="width:1070px;">
                                                <div class="row ms-0">
                                                    @foreach ($brand->category as $category)
                                                        <div class="col-3 col-md-3">
                                                            <span class="fw-bold fs-6 ps-2 text-decoration-underline mb-5">
                                                                {{ $category->name ?? '' }}
                                                            </span>
                                                            @foreach ($category->subcategories as $subcategory)
                                                                <div class="{{ $loop->iteration == 1 ? 'mt-2' : '' }}">
                                                                    <a href="{{ route('category.products', ['code' => $subcategory->code]) }}"
                                                                        class="text-decoration-none text-dark p-2 w-100"
                                                                        style="transition: font-weight 0.3s; font-weight: normal;"
                                                                        onmouseover="this.style.fontWeight='bold';"
                                                                        onmouseout="this.style.fontWeight='normal';">
                                                                        {{ $subcategory->name }}
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </ul>
                                        @endif
                                    </div>
                                </li>
                            @endforeach

                            <!-- See All Brands Button -->
                            <li class="border-bottom text-center">
                                <button id="see-all-brands" class="btn btn-link">See All Categories</button>
                            </li>
                        </ul>
                    </div>





                </div>
                <div class="col-md-6">
                    <ul class="d-flex gap-4 mb-2 mb-lg-0">
                        <li>
                            <a class="text-dark @if (request()->routeIs('/')) landing-active @endif"
                                href="/">HOME</a>
                        </li>
                        <li>
                            <a class="text-dark @if (request()->routeIs('shop')) landing-active @endif"
                                href="{{ route('shop') }}">SHOP</a>
                        </li>
                        <li>
                            <a class="text-dark @if (request()->routeIs('about-us')) landing-active @endif"
                                href="{{ route('about-us') }}">ABOUT US</a>
                        </li>
                        <li>
                            <a class="text-dark @if (request()->routeIs('contact-us.index')) landing-active @endif"
                                href="{{ route('contact-us.index') }}">CONTACT</a>
                        </li>
                    </ul>
                </div>
                @php
                    $socials = App\Models\Setting::first();
                @endphp
                <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                    <p class="m-0 fs-6 border-end pe-2 me-2">
                        <a href="tel:{{ $socials->company_phone ?? '+880 123 456 789' }}" class="text-dark"><i
                                class="bi bi-telephone me-2"></i><span>{{ $socials->company_phone ?? '+254 1888 344 809' }}</span></a>
                    </p>
                    <div class="d-flex align-items-center justify-content-center gap-3 align-items-center">
                        <a href="{{ $socials->linkedin ?? 'www.linkedin.com' }}">
                            <img src="{{ asset('front/assets/img/footer-linkedin.svg') }}" alt=""
                                class="img-fluid" />
                        </a>
                        <a href="{{ $socials->fb ?? 'www.facebook.com' }}">
                            <img src="{{ asset('front/assets/img/footer-facebook.svg') }}" alt=""
                                class="img-fluid" />
                        </a>
                        <a href="{{ $socials->twitch ?? 'www.twitch.com' }}">
                            <img src="{{ asset('front/assets/img/footer-twitch.svg') }}" alt=""
                                class="img-fluid" />
                        </a>
                        <a href="{{ $socials->twitter ?? 'www.twitter.com' }}">
                            <img src="{{ asset('front/assets/img/footer-twitter.svg') }}" alt=""
                                class="img-fluid" />
                        </a>
                    </div>
                </div>
            </div>
            <!-- <div id="responsive-nav">
            <ul class="main-nav d-lg-flex d-block p-3 px-lg-0 gap-3 py-3">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#">Hot Deals</a></li>
              <li><a href="#">Categories</a></li>
              <li><a href="#">Laptops</a></li>
              <li><a href="#">Smartphones</a></li>
              <li><a href="#">Cameras</a></li>
              <li><a href="#">Accessories</a></li>
            </ul>
          </div> -->
        </div>
    </nav>
    <!-- /NAVIGATION -->
