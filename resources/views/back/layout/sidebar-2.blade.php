<div class="sidebar customer-sidebar pb-3 bg-white">
    <nav class="navbar navbar-light">
        <a href="{{ route('dashboard') }}" class="navbar-brand ms-3 d-flex justify-content-center w-100">
            <img style="width: 65px"
                src="{{ isset(auth()->user()->image) || auth()->user()->image != null ? asset('/storage/' . auth()->user()->image) : asset('back/assets/dasheets/img/profile-img.png') }}"
                alt="">
        </a>
        <div class="navbar-nav w-100 border-top pt-3">
            <a href="{{ route('dashboard') }}" class="nav-item nav-link @if (request()->routeIs('dashboard')) active @endif">
                <i class="bi bi-grid me-2"></i><span>Dashboard</span>
            </a>
            <hr class="mx-3" />

            @role(['Admin', 'Manager'])
            <div class="ps-4">
                <h6 class="ps-2 fw-bold">Inventory</h6>
            </div>
            <a href="{{ route('products.index') }}" class="nav-item nav-link @if (request()->routeIs('products.index')) active @endif">
                <i class="bi bi-box-seam me-2"></i><span>Products</span>
            </a>
            <a href="{{ route('products.create') }}" class="nav-item nav-link @if (request()->routeIs('products.create')) active @endif">
                <i class="bi bi-bag-plus me-2"></i><span>Create Product</span>
            </a>
            <a href="{{ route('categories.index') }}" class="nav-item nav-link @if (request()->routeIs('categories.*')) active @endif">
                <i class="fa-solid fa-list me-2"></i><span>Category</span>
            </a>
            <a href="{{ route('sub-categories.index') }}" class="nav-item nav-link @if (request()->routeIs('sub-categories.*')) active @endif">
                <i class="bi bi-subtract me-2"></i><span>Sub Category</span>
            </a>
            <a href="{{ route('brands.index') }}" class="nav-item nav-link @if (request()->routeIs('brands.*')) active @endif">
                <i class="fa-solid fa-shapes me-2"></i><span>Brands</span>
            </a>
            <a href="{{ route('units.index') }}" class="nav-item nav-link @if (request()->routeIs('units.*')) active @endif">
                <i class="bi bi-unity me-2"></i><span>Unit</span>
            </a>
            <hr class="mx-3" />
            @endrole

            <div class="">
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Sales
                </p>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-graph-up-arrow me-2"></i> Sales
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('sales.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('sales.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>All Sales</span>
                        </a>
                        <a href="{{ route('sales.create') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('sales.create')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Create Sales</span>
                        </a>
                        <a href="{{ route('sales.pos') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('sales.pos')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>POS</span>
                        </a>
                        <a href="{{ route('manual-sale-return.create') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('manual-sale-return.create')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Create Sale Return</span>
                        </a>
                    </div>
                </div>
                <a href="{{ route('sale_return.index') }}" class="nav-item nav-link @if (request()->routeIs('sale_return.*')) active @endif">
                    <i class="bi bi-arrow-90deg-left me-2"></i><span>Sales Return</span>
                </a>
                <a href="{{ route('device-return.index') }}" class="nav-item nav-link @if (request()->routeIs('device-return.index')) active @endif">
                    <i class="bi bi-box-arrow-left me-2"></i><span>Device Return</span>
                </a>
                <a href="{{ route('reserved-order.index') }}" class="nav-item nav-link @if (request()->routeIs('reserved-order.index')) active @endif">
                    <i class="bi bi-arrow-repeat me-2"></i><span>Reverse Order</span>
                </a>
                <hr class="mx-3" />
            </div>

            @role(['Admin', 'Manager'])
                <div class="">
                    <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                        Purchase
                    </p>
                    <a href="{{ route('purchases.index') }}" class="nav-item nav-link @if (request()->routeIs('purchases.index')) active @endif">
                        <i class="bi bi-bag me-2"></i><span>All Purchase</span>
                    </a>
                    <a href="{{ route('purchases.create') }}" class="nav-item nav-link @if (request()->routeIs('purchases.create')) active @endif">
                        <i class="bi bi-bag-plus me-2"></i><span>Create Purchase</span>
                    </a>
                    <a href="{{ route('purchase_return.index') }}" class="nav-item nav-link @if (request()->routeIs('purchase_return.index')) active @endif">
                        <i class="bi bi-arrow-90deg-left me-2"></i><span>Purchase Return</span>
                    </a>
                    <hr class="mx-3" />
                </div>
            @endrole

            <div class="">
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Payment
                </p>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-credit-card-2-front me-2"></i> Payment
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('sales-payments.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('sales-payments.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Invoice Payments</span>
                        </a>
                        <a href="{{ route('non-sales-payments.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('non-sales-payments.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Non-Invoice Payments</span>
                        </a>
                        <a href="{{ route('purchase-payments.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('purchase-payments.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Purchase Payments</span>
                        </a>
                        <a href="{{ route('non-purchase-payments.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('non-purchase-payments.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Non-Purchase Payments</span>
                        </a>
                    </div>
                </div>

                @role(['Admin', 'Manager'])
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-bank me-2"></i> Transfer
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('transfers.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('transfers.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>All Transfer</span>
                        </a>
                        <a href="{{ route('transfers.create') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('transfers.create')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Create Transfer</span>
                        </a>
                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-calculator me-2"></i> Accounting
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('accounts.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('accounts.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>List Accounts</span>
                        </a>
                        <a href="{{ route('credit-cards.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('credit-cards.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>All Cards</span>
                        </a>
                        <a href="/transfer-money" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('transfer-money')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Transfer Money</span>
                        </a>
                        <a href="{{ route('expenses.create') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('expenses.create')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Create Expense</span>
                        </a>
                        <a href="{{ route('expenses.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('expenses.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>All Expense</span>
                        </a>
                        <a href="{{ route('deposits.create') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('deposits.create')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Create Deposit</span>
                        </a>
                        <a href="{{ route('deposits.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('deposits.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>All Deposit</span>
                        </a>
                        <a href="{{ route('expense-categories.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('expense-categories.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Expense Category</span>
                        </a>
                        <a href="{{ route('deposit-categories.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('deposit-categories.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Deposit Category</span>
                        </a>
                    </div>
                </div>
                @endrole
                <hr class="mx-3" />
            </div>

            @role(['Admin', 'Manager'])
            <div class="">
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Report
                </p>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-file-earmark-easel me-2"></i> Products
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        @can('product-report')
                        <a href="{{ route('product.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('product.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Product Report</span>
                        </a>
                        @endcan
                        @can('product-sale-report')
                        <a href="{{ route('sale.product.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('sale.product.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Product Sales Report</span>
                        </a>
                        @endcan
                        @can('product-purchase-report')
                        <a href="{{ route('purchase.product.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('purchase.product.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Product Purchases Report</span>
                        </a>
                        @endcan
                        @can('top-selling-product-report')
                        <a href="{{ route('top.product.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('top.product.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Top Selling Products</span>
                        </a>
                        @endcan
                        @can('expense-report')
                        <a href="{{ route('expense.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('expense.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Expenses Report</span>
                        </a>
                        @endcan
                        @can('deposit-report')
                        <a href="{{ route('deposit.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('deposit.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Deposit Report</span>
                        </a>
                        @endcan
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-boxes me-2"></i>Stock
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        @can('inventory-valuation-report')
                        <a href="{{ route('inventory.valuation.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('inventory.valuation.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Inventory Valuation</span>
                        </a>
                        @endcan
                        @can('stock-report')
                        <a href="{{ route('stock.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('stock.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Stock Report</span>
                        </a>
                        @endcan
                        @can('supplier-report')
                        <a href="{{ route('vendor.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('vendor.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Supplier Report</span>
                        </a>
                        @endcan
                        @can('warehouse-report')
                        <a href="{{ route('warehouse.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('warehouse.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Warehouse Report</span>
                        </a>
                        @endcan
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-people me-2"></i>Users
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        @can('customer-report')
                        <a href="{{ route('customer.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('customer.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Customer Report</span>
                        </a>
                        @endcan
                        @can('best-customer-report')
                        <a href="{{ route('best.customer.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('best.customer.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Best Customers Report</span>
                        </a>
                        @endcan
                        @can('user-report')
                        <a href="{{ route('user.report') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('user.report')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>User Report</span>
                        </a>
                        @endcan
                    </div>
                </div>
                @can('today-report')
                <a href="{{ route('today.report') }}" class="nav-item nav-link @if (request()->routeIs('today.report')) active @endif">
                    <i class="bi bi-calendar-event me-2"></i>
                    <span>Today Report</span>
                </a>
                @endcan
                @can('purchase-payment-report')
                <a href="{{ route('purchase.payment.report') }}" class="nav-item nav-link @if (request()->routeIs('purchase.payment.report')) active @endif">
                    <i class="bi bi-credit-card-2-back me-2"></i><span>Payment Report</span>
                </a>
                @endcan
                <hr class="mx-3" />
            </div>
            @endrole

            @role(['Admin', 'Manager'])
            <div class="">
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Inventory
                </p>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-houses me-2"></i> Inventory
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('inventories.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('inventories.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>All Inventory</span>
                        </a>
                        <a href="{{ route('inventories.create') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('inventories.create')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Receive Inventory</span>
                        </a>
                        <a href="{{ route('bills.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('bills.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Bills</span>
                        </a>
                        <a href="{{ route('inventory-stock-count') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('inventory-stock-count')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Stock Counts</span>
                        </a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-house-gear me-2"></i>Vendors
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('vendors.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('vendors.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>All Vendors</span>
                        </a>
                        <a href="{{ route('vendors.create') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('vendors.create')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Create Vendors</span>
                        </a>
                        <a href="{{ route('vendors.blacklist') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('vendors.blacklist')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Blacklist Vendors</span>
                        </a>
                    </div>
                </div>
                <a href="{{ route('shipment.index') }}" class="nav-item nav-link @if (request()->routeIs('shipment.index')) active @endif">
                    <i class="bi bi-truck me-2"></i><span>Shipment</span>
                </a>
                <hr class="mx-3" />
            </div>
            @endrole

            <div class="">
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Customers
                </p>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-2"></i> Customers
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('customers.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('customers.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>All Customers</span>
                        </a>
                        <a href="{{ route('customers.create') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('customers.create')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Create Customers</span>
                        </a>
                        <a href="{{ route('customers.blacklist') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('customers.blacklist')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Blacklist Customers</span>
                        </a>
                        <a href="{{ route('tiers.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('tiers.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Discount Tiers</span>
                        </a>
                    </div>
                </div>
                <hr class="mx-3" />
            </div>

            @role(['Admin', 'Manager'])
            <div class="">
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Setting
                </p>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-gear-wide-connected me-2"></i> Setting
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('setting.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('setting.*')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>System Setting</span>
                        </a>
                        @role('Admin')
                        <a href="{{ route('users.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('users.*')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Users</span>
                        </a>
                        <a href="{{ route('roles.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('roles.*')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>User Role</span>
                        </a>
                        <a href="{{ route('permissions.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('permissions.*')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>User Permission</span>
                        </a>
                        @endrole
                        <a href="{{ route('sms.setting') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('sms.setting')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>SMS Setting</span>
                        </a>
                        <a href="{{ route('email.setting') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('email.setting')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Email Setting</span>
                        </a>
                        <a href="{{ route('pos.setting') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('pos.setting')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>POS Setting</span>
                        </a>
                        <a href="{{ route('payment.setting') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('payment.setting')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Payment Setting</span>
                        </a>
                        <a href="{{ route('warehouses.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('warehouses.*')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Warehouse</span>
                        </a>
                        <a href="{{ route('taxes.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('taxes.*')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Taxes</span>
                        </a>
                    </div>
                </div>

                @role('Admin')
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-window-dock me-2"></i> CMS
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('cms.landing-page.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('cms.landing-page.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Landing Page</span>
                        </a>
                        <a href="{{ route('about-us.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('about-us.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>About Us</span>
                        </a>
                        <a href="{{ route('approval-requests.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('approval-requests.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Approval Request</span>
                        </a>
                        <a href="{{ route('admin.contact-us') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('admin.contact-us')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Contact Us</span>
                        </a>
                        <a href="{{ route('admin.support-ticket') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('admin.support-ticket')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Support Tickets</span>
                        </a>
                        <a href="{{ route('admin.cms.product-review') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('admin.cms.product-review')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Product Reviews</span>
                        </a>
                        <a href="{{ route('notification.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('notification.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Notifications</span>
                        </a>
                        <a href="{{ route('admin.term-and-condition.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('admin.term-and-condition.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Terms & Conditions</span>
                        </a>
                        <a href="{{ route('admin.privacy-policy.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('admin.privacy-policy.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Privacy Policy</span>
                        </a>
                        <a href="{{ route('admin.exchange-policy.index') }}" class="dropdown-item d-flex align-items-center gap-1 @if (request()->routeIs('admin.exchange-policy.index')) active @endif">
                            <i class="fa-solid fa-circle me-2"></i>
                            <span>Exchange Policy</span>
                        </a>
                    </div>
                </div>
                @endrole
                <hr class="mx-3" />
            </div>
            @endrole
        </div>
    </nav>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Loop through all dropdowns
    document.querySelectorAll(".nav-item.dropdown").forEach(function (dropdown) {
        // Check if any child link inside this dropdown is active
        if (dropdown.querySelector(".dropdown-item.active")) {
            // Add Bootstrap's `show` class to keep it open
            dropdown.classList.add("show");
            dropdown.querySelector(".dropdown-menu").classList.add("show");
        }
    });
});
</script>