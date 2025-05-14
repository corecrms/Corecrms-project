<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasLeft" aria-labelledby="offcanvasLeftLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="text-center">
            <a href="{{ route('/') }}" class="logo">
                @if (getLogo())
                    <img src="{{ asset('storage' . getLogo()) }}" alt="logo"  style="width: 120px; object-fit: contain;"/>
                @else
                    <h1 class="">Logo</h1>
                @endif
            </a>
        </div>
        {{-- <div class="input-search position-relative mt-3">
            <input type="text" placeholder="Search Table" class="form-control rounded-5 ps-5 p-2" />
            <span class="fa fa-search search-icon text-secondary"></span>
        </div> --}}
        <div class="input-search position-relative mt-3">
            <input type="text" placeholder="Search Products"
                class="form-control rounded-5 ps-5 p-2" id="searchInput2" />
            <span class="fa fa-search search-icon text-secondary"></span>

        </div>
        <div id="suggestionsContainer2"></div>
        <div class="mt-3">
            <ul class="nav nav-tabs border-bottom" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active border-0 purple-txtcolor heading fs-5" id="home-tab"
                        data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab"
                        aria-controls="home-tab-pane" aria-selected="true">
                        Menu
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link border-0 purple-txtcolor heading fs-5" id="profile-tab" data-bs-toggle="tab"
                        data-bs-target="#profile-tab-pane" type="button" role="tab"
                        aria-controls="profile-tab-pane" aria-selected="false">
                        Categories
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">
                    <ul class="list-unstyled mt-2">

                        <li class="mt-4">
                            <a class="text-dark" href="/">HOME</a>
                        </li>
                        <li class="mt-2">
                            <a class="text-dark" href="{{ route('shop') }}">SHOP</a>
                        </li>
                        <li class="mt-2">
                            <a class="text-dark" href="{{ route('about-us') }}">ABOUT US</a>
                        </li class="mt-2">
                        <li class="mt-2">
                            <a class="text-dark" href="{{ route('contact-us.index') }}">CONTACT</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                    tabindex="0">
                    @php
                        $categories = App\Models\Category::where('status', 1)->get();
                    @endphp
                    <ul class="list-unstyled mt-2">
                        @foreach ($categories as $category )

                            @foreach ($category->subcategories as $subcategory )
                                <li class="mt-2 border-bottom p-2 mt-2">
                                    <a href="{{ route('category.products', ['code' => $subcategory->code]) }}" class="text-decoration-none text-dark">
                                        {{ $subcategory->name ?? 'N/A' }}
                                    </a>
                                </li>
                            @endforeach

                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
