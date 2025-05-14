<div class="col-md-2">
    <div class="dropdown">
        <button class="btn border-0 {{ request()->routeIs('/') ? 'show' : '' }}" type="button" data-bs-toggle="dropdown"
            aria-expanded="true">
            Categories <i class="bi bi-chevron-down ms-2"></i>
        </button>
        <ul class="dropdown-menu mt-2 border-top-0 rounded-0 pb-0 {{ request()->routeIs('/') ? 'show' : '' }}"
            style="
                position: absolute;
                inset: 0px auto auto 0px;
                margin: 0px;
                transform: translate(0px, 40px);
                width: 228px;
            "  data-popper-placement="bottom-start">
            @php
                $categories = App\Models\Category::where('status', 1)->get();
            @endphp
            @foreach ($categories as $category)
                <li class="border-bottom">
                    <div class="dropdown hover-dropdown">
                        <button class="btn d-flex justify-content-between w-100" type="button">
                            <span>
                                {{-- <i class="fa-solid fa-bowl-food"></i> --}}
                                <img src="{{ asset('storage/category/' . $category->icon) }}" alt=""
                                    style="width: 20px;" class="rounded-circle">
                                {{ $category->name }}
                            </span>
                            @if ($category->subcategories->count() > 0)
                                <i class="bi bi-chevron-right ms-2"></i>
                            @endif
                        </button>

                        @if ($category->subcategories->count() > 0)
                            <ul class="dropdown-menu hover-dropdown-menu" data-popper-placement="bottom-start" >
                                <div class="row">
                                    @foreach ($category->subcategories as $subcategory)
                                        <div class="col-md-4">
                                            <div class="dropdown hover-dropdown2">
                                                <a href="{{ route('category.products', ['code' => $subcategory->code]) }}"
                                                    class="btn d-flex justify-content-between w-100">
                                                    {{ $subcategory->name }}
                                                    @if ($subcategory->products->count() > 0)
                                                        <i class="bi bi-chevron-right"></i>
                                                    @endif
                                                </a>

                                                {{-- @if ($subcategory->products->count() > 0)
                                                            <ul class="dropdown-menu hover-dropdown-menu2 d-none"
                                                                data-popper-placement="bottom-start">
                                                                @foreach ($subcategory->products as $product)
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('user.product.index', ['code' => $subcategory->category->code, 'sku' => $product->sku]) }}">{{ $product->name }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif --}}
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
    </div>


</div>
