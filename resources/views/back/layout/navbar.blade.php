<div class="container-fluid position-relative bg-white d-flex p-0">
    <!-- Sidebar End -->

    <div class="sidebar pb-3">
        <nav class="navbar navbar-light">
            <a href="{{ route('dashboard') }}" class="navbar-brand ms-3">
                {{-- <h3 class="text-primary">LOGO</h3> --}}
                @php
                    $setting = \App\Models\Setting::first();
                @endphp
                {{-- <img src="{{ asset('/storaewrwege') . (isset($setting->logo) ? $setting->logo : '') }}" alt="Logo" style="width: 80px"> --}}
                <img style="width: 80px"
                    src="{{ isset(auth()->user()->image) || auth()->user()->image != null ? asset('/storage/' . auth()->user()->image) : asset('back/assets/dasheets/img/profile-img.png') }}"
                    alt="">

                <div class="navbar-nav">
                    <a href="{{ route('dashboard') }}"
                        class="nav-item nav-link  @if (request()->routeIs('dashboard')) active @endif text-center border-top">
                        {{-- <i class="bi bi-grid"></i> --}}
                        <img src="{{asset('back/assets/image/Icons/Dashboard.png')}}" width="50" alt="" class="img-fluid">
                        <p class="pt-1 mb-0">Dashboard</p>
                    </a>

                    @role(['Admin', 'Manager'])
                        <div id="navbar-toggler1"
                            class="nav-item  nav-link text-center  @if (request()->routeIs('products.*') ||
                                    request()->routeIs('print-lables.*') ||
                                    request()->routeIs('categories.*') ||
                                    request()->routeIs('brands.*') ||
                                    request()->routeIs('units.*') ||
                                    request()->routeIs('warranties.*')) active @endif"">
                            {{-- <i class="bi bi-box-seam"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/Products.png')}}" width="50" alt="" class="img-fluid">

                            <p class="pt-1 mb-0">Products</p>
                        </div>
                    @endrole

                    <div id="navbar-toggler2"
                        class="nav-item nav-link text-center @if (request()->routeIs('sales.*') ||
                                request()->routeIs('sales.pos') ||
                                request()->routeIs('sale_return.*') ||
                                request()->routeIs('manual-sale-return.*') ||
                                request()->routeIs('device-return.*') ||
                                request()->routeIs('reserved-order.*')) active @endif">
                        {{-- <i class="bi bi-cart"></i> --}}
                        <img src="{{asset('back/assets/image/Icons/Sales.png')}}" width="50" alt="" class="img-fluid">

                        <p class="pt-1 mb-0">Sales </p>
                    </div>

                    @role(['Admin', 'Manager'])
                        <div id="navbar-toggler3"
                            class="nav-item nav-link text-center @if (request()->routeIs('purchases.*') || request()->routeIs('purchase_return.*')) active @endif ">
                            {{-- <i class="bi bi-file-earmark-text"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/Purchase.png')}}" width="50" alt="" class="img-fluid">

                            <p class="pt-1 mb-0">Purchase</p>
                        </div>
                    @endrole

                    <div id="navbar-toggler4"
                        class="nav-item nav-link text-center @if (request()->routeIs('sales-payments.*') ||
                                request()->routeIs('non-sales-payments.*') ||
                                request()->routeIs('purchase-payments.*') ||
                                request()->routeIs('non-purchase-payments.*')) active @endif">
                        {{-- <i class="bi bi-clipboard-data"></i> --}}
                        <img src="{{asset('back/assets/image/Icons/Payments.png')}}" width="50" alt="" class="img-fluid">
                        <p class="pt-1 mb-0">Payments</p>
                    </div>

                    @role(['Admin', 'Manager'])
                        <div id="navbar-toggler5"
                            class="nav-item nav-link text-center @if (request()->routeIs('inventories.*') || request()->routeIs('inventory-stock-count') || request()->routeIs('inventory-stock-count-of-all-products') || request()->routeIs('bills.*')) active @endif ">
                            {{-- <i class="bi bi-bag"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/inventory.png')}}" width="50" alt="" class="img-fluid">
                            <p class="pt-1 mb-0">Inventory</p>
                        </div>
                        <div id="navbar-toggler6"
                            class="nav-item nav-link text-center @if (request()->routeIs('customers.*') || request()->routeIs('tiers.*')) active @endif">
                            {{-- <i class="bi bi-person"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/Customer.png')}}" width="50" alt="" class="img-fluid">
                            <p class="pt-1 mb-0">Customer</p>
                        </div>
                        <div id="navbar-toggler7"
                            class="nav-item nav-link text-center @if (request()->routeIs('transfers.*')) active @endif  ">
                            {{-- <i class="bi bi-arrow-90deg-left"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/Transfers.png')}}" width="50" alt="" class="img-fluid">
                            <p class="pt-1 mb-0">Transfer</p>
                        </div>
                        <div id="navbar-toggler8"
                            class="nav-item nav-link text-center @if (request()->routeIs('vendors.*')) active @endif">
                            {{-- <i class="bi bi-house"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/Vendors.png')}}" width="50" alt="" class="img-fluid">
                            <p class="pt-1 mb-0">Vendors</p>
                        </div>

                        <a href="{{ route('shipment.index') }}"
                            class="nav-item nav-link text-center @if (request()->routeIs('shipment.*')) active @endif">
                            {{-- <i class="bi bi-clipboard-data"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/Shipments.png')}}" width="50" alt="" class="img-fluid">
                            <p class="pt-1 mb-0">Shipment</p>
                        </a>

                        <div id="navbar-toggler9"
                            class="nav-item nav-link text-center @if (request()->routeIs('accounts.*') ||
                                    request()->routeIs('transfer-money.*') ||
                                    request()->routeIs('expenses.*') ||
                                    request()->routeIs('deposits.*') ||
                                    request()->routeIs('expense-categories.*') ||
                                    request()->routeIs('deposits-categories.*')) active @endif">
                            {{-- <i class="fa-solid fa-wallet fs-2"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/Accounting.png')}}" width="50" alt="" class="img-fluid">
                            <p class="pt-1 mb-0">Accounting</p>
                        </div>

                        <div id="navbar-toggler10"
                            class="nav-item nav-link text-center @if (request()->routeIs('setting.*') ||
                                    request()->routeIs('users.*') ||
                                    request()->routeIs('roles.*') ||
                                    request()->routeIs('permissions.*') ||
                                    request()->routeIs('warehouses.*') ||
                                    request()->routeIs('taxes.*')) active @endif">
                            {{-- <i class="bi bi-gear"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/Settings.png')}}" width="50" alt="" class="img-fluid">
                            <p class="pt-1 mb-0">Setting</p>
                        </div>
                        <div id="navbar-toggler11"
                            class="nav-item nav-link text-center @if (request()->routeIs('sales.report') ||
                                    request()->routeIs('sale.product.report') ||
                                    request()->routeIs('purchase.report') ||
                                    request()->routeIs('purchase.product.report') ||
                                    request()->routeIs('purchase.payment.report') ||
                                    request()->routeIs('sale.payment.report') ||
                                    request()->routeIs('today.report') ||
                                    request()->routeIs('inventory.valuation.report') ||
                                    request()->routeIs('warehouse.report') ||
                                    request()->routeIs('customer.detail') ||
                                    request()->routeIs('stock.show') ||
                                    request()->routeIs('product_item.show') ||
                                    request()->routeIs('vendor.report') ||
                                    request()->routeIs('customer.report') ||
                                    request()->routeIs('expense.report') ||
                                    request()->routeIs('deposit.report') ||
                                    request()->routeIs('stock.report') ||
                                    request()->routeIs('product.report') ||
                                    request()->routeIs('sale.report') ||
                                    request()->routeIs('user.report') ||
                                    request()->routeIs('top.product.report') ||
                                    request()->routeIs('best.customer.report')) active @endif">
                            {{-- <i class="bi bi-clipboard-data"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/Reports.png')}}" width="50" alt="" class="img-fluid">
                            <p class="pt-1 mb-0">Reports</p>
                        </div>
                    @endrole

                    @role(['Cashier'])
                        <a id="" href="{{ route('today.report') }}"
                            class="nav-item nav-link text-center @if (request()->routeIs('today.report')) active @endif">
                            {{-- <i class="bi bi-clipboard-data"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/Reports.png')}}" width="50" alt="" class="img-fluid">
                            <p class="pt-1 mb-0">Reports</p>
                        </a>
                    @endrole
                    @role(['Admin'])
                        <div id="navbar-toggler12"
                            class="nav-item nav-link text-center @if (request()->routeIs('cms.landing-page.*') ||
                                    request()->routeIs('ads.*') ||
                                    request()->routeIs('about-us.*') ||
                                    request()->routeIs('approval-requests.*') ||
                                    request()->routeIs('admin.contact-us') ||
                                    request()->routeIs('admin.support-ticket')) active @endif">
                            {{-- <i class="bi bi-clipboard-data"></i> --}}
                            <img src="{{asset('back/assets/image/Icons/CMS.png')}}" width="50" alt="" class="img-fluid">
                            <p class="pt-1 mb-0">CMS</p>
                        </div>
                        {{-- <div id="navbar-toggler13"
                            class="nav-item nav-link text-center @if (
                            request()->routeIs('companies.*') ||
                            request()->routeIs('departments.*') ||
                            request()->routeIs('designation.*') ||
                            request()->routeIs('office-shift.*') ||
                            request()->routeIs('employees.*') ||
                            request()->routeIs('attendance.*') ||
                            request()->routeIs('leave-request.*') ||
                            request()->routeIs('leave-type.*') ||
                            request()->routeIs('holidays.*') ||
                            request()->routeIs('payrolls.*') ) active @endif  ">
                            <i class="bi bi-house-gear-fill"></i>
                            <p class="pt-1 mb-0">HRM</p>
                        </div> --}}
                    @endrole
                    {{-- <div id="navbar-toggler11" class="nav-item nav-link text-center @if (request()->routeIs('sales-payments.*') || request()->routeIs('non-sales-payments.*') || request()->routeIs('purchase-payments.*') || request()->routeIs('non-purchase-payments.*')) active @endif">
                    <i class="bi bi-clipboard-data"></i>
                    <p class="pt-1 mb-0">Payments</p> --}}
                </div>

        </nav>
    </div>
</div>
