<!-- Sidebar Start -->
<div class="customer-sidebar pe-4 pb-3 border-0">
    <nav class="navbar navbar-light">
        <a href="{{ route('user.dashboard') }}" class="navbar-brand mx-4 mb-3 text-center">
            <h4 class="text-center">{{ auth()->user()->name ?? 'Name' }}</h4>
        </a>
        <div class="navbar-nav w-100">
            <div class="nav-item dropdown">
                <a href="#"
                    class="nav-link dropdown-toggle show d-flex align-items-center {{ request()->routeIs('user.dashboard') || request()->routeIs('user.account.info') || request()->routeIs('user.addressbook.index') ? 'active' : '' }}"
                    data-bs-toggle="dropdown">
                    <i class="bi bi-person-gear fs-4"></i> My Account</a>
                <div class="dropdown-menu bg-transparent show border-0">
                    <a href="{{ route('user.dashboard') }}"
                        class="dropdown-item d-flex align-items-center {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"><i
                            class="bi bi-grid me-2 fs-5"></i> Account Dashboard</a>
                    <a href="{{ route('user.account.info') }}"
                        class="dropdown-item d-flex align-items-center gap-2 {{ request()->routeIs('user.account.info') ? 'active' : '' }}"><i
                            class="bi bi-info-circle fs-5"></i> Account Information</a>
                    <a href="{{ route('user.addressbook.index') }}"
                        class="dropdown-item d-flex align-items-center gap-2 {{ request()->routeIs('user.addressbook.index') ? 'active' : '' }}"><i
                            class="fa-regular fa-address-book fs-5"></i>Address
                        Book</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center {{ request()->routeIs('user.notification') ? 'active' : '' }}"
                    data-bs-toggle="dropdown"><i class="bi bi-bell fs-4 me-2"></i>Notification</a>
                {{-- <div class="dropdown-menu bg-transparent border-0">
                    <a href="customertaxforms.html" class="dropdown-item d-flex align-items-center gap-2"><i
                            class="fa-brands fa-wpforms fs-5"></i>Tax Forms</a>
                </div> --}}
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{route('user.notification')}}" class="dropdown-item d-flex align-items-center gap-2 {{ request()->routeIs('user.notification') ? 'active' : '' }}"><i
                            class="fa-brands fa-wpforms fs-5 "></i>All Notification</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#"
                    class="nav-link dropdown-toggle d-flex align-items-center {{ request()->routeIs('user.wishlist.index') ? 'active' : '' }}"
                    data-bs-toggle="dropdown"><i class="bi bi-bag-check me-2 fs-4"></i>Checkout</a>
                <div class="dropdown-menu bg-transparent border-0">
                    {{-- <a href="customersavedcart.html" class="dropdown-item d-flex align-items-center gap-2"><i
                            class="bi bi-cart fs-5"></i>Saved Shopping Cart</a> --}}
                    <a href="{{ route('user.wishlist.index') }}"
                        class="dropdown-item d-flex align-items-center gap-2 {{ request()->routeIs('user.wishlist.index') ? 'active' : '' }}"><i
                            class="bi bi-cart fs-5 "></i>Wishlist</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center"
                    data-bs-toggle="dropdown"><i class="fa-brands fa-first-order fs-4 me-2"></i>Order Info</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{ route('user.orders.index') }}" class="dropdown-item d-flex align-items-center gap-2"><i
                            class="fa-brands fa-first-order-alt fs-5"></i>My Orders</a>
                    <a href="{{ route('reserve-order.index') }}"
                        class="dropdown-item d-flex align-items-center gap-2"><i
                            class="bi bi-life-preserver fs-5"></i>Reserve Orders</a>
                    <a href="{{ route('user.quick-order') }}" class="dropdown-item d-flex align-items-center gap-2"><i
                            class="fa-solid fa-truck-fast fs-5"></i>Quick Order</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center"
                    data-bs-toggle="dropdown"><i class="bi bi-device-hdd fs-4 me-2"></i>Devices</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{ route('devices.index') }}" class="dropdown-item d-flex align-items-center gap-2"><i
                            class="fa-brands fa-first-order-alt fs-5"></i>My Orders</a>
                    <a href="{{ route('devices.create') }}" class="dropdown-item d-flex align-items-center gap-2"><i
                            class="fa-solid fa-right-left fs-5"></i>Device Returns /
                        RMA</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center"
                    data-bs-toggle="dropdown"><i class="fa-solid fa-wallet fs-4 me-2"></i>Wallet</a>
                <div class="dropdown-menu bg-transparent border-0">

                    <a href="{{route('user.credit-activity')}}" class="dropdown-item d-flex align-items-center justify-content-between">
                        <div class="icon-text-group">
                            <i class="fa-solid fa-credit-card fs-5"></i>
                            <span>Store Credit</span>
                        </div>
                        <div>${{ number_format(auth()->user()->customer->balance ?? 0, 2 ) }}</div>
                    </a>
                    <a href="{{ route('saved-card-info') }}" class="dropdown-item d-flex align-items-center gap-2"><i
                            class="fa-solid fa-money-bill-wave fs-5"></i>Saved Payment
                        Information</a>
                    <a href="{{ route('user.balance-sheet') }}"
                        class="dropdown-item d-flex align-items-center gap-2"><i
                            class="fa-solid fa-scale-balanced fs-5"></i>Balance
                        Sheet</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center"
                    data-bs-toggle="dropdown"><i class="fa-solid fa-phone fs-4 me-2"></i>Contact Us</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{ route('support-ticket.index') }}"
                        class="dropdown-item d-flex align-items-center gap-2"><i
                            class="fa-solid fa-ticket fs-5"></i>Support Ticket</a>
                </div>
            </div>
        </div>
    </nav>
</div>
<!-- Sidebar End -->
