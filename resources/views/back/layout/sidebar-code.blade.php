<div class="sidebar customer-sidebar pb-3 bg-white">
    <nav class="navbar navbar-light">
        <a href="{{ route('dashboard') }}" class="navbar-brand ms-3 d-flex justify-content-center w-100">
            {{-- <h3 class="text-primary text-center">LOGO</h3> --}}
            <img style="width: 65px"
                src="{{ isset(auth()->user()->image) || auth()->user()->image != null ? asset('/storage/' . auth()->user()->image) : asset('back/assets/dasheets/img/profile-img.png') }}"
                alt="">
        </a>

        <div class="navbar-nav w-100 border-top pt-3">
            <a href="{{ route('dashboard') }}"
                class="nav-item nav-link  @if (request()->routeIs('dashboard')) active @endif">
                <i class="bi bi-grid me-2"></i><span>Dashboard</span>
            </a>

            <hr class="mx-3" />
            @role(['Admin', 'Manager'])
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark mt-3">
                    Product
                </p>
                <a href="{{ route('products.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('products.index')) active @endif">
                    <i class="fa-solid fa-list-check mt-1 me-2"></i><span>All Product</span>
                </a>
                {{-- edit product  --}}
                <a href="{{ route('products.create') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('products.create')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Create Product</span>
                </a>
                <a href="{{ route('categories.index') }}"
                    class="nav-item nav-link d-flex  @if (request()->routeIs('categories.*')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Category</span>
                </a>
                <a href="{{ route('sub-categories.index') }}"
                    class="nav-item nav-link d-flex  @if (request()->routeIs('sub-categories.*')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Sub Category</span>
                </a>
                <a href="{{ route('brands.index') }}"
                    class="nav-item nav-link d-flex  @if (request()->routeIs('brands.*')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Brands</span>
                </a>
                <a href="{{ route('units.index') }}"
                    class="nav-item nav-link d-flex  @if (request()->routeIs('units.*')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Units</span>
                </a>
            @endrole
            <hr class="mx-3" />
            <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                Sales
            </p>
            <a href="{{ route('sales.index') }}"
                class="nav-item nav-link d-flex  @if (request()->routeIs('sales.index')) active @endif">
                <i class="bi bi-cart mt-1 me-2"></i><span>All Sales</span>
            </a>
            <a href="{{ route('sales.create') }}"
                class="nav-item nav-link d-flex   @if (request()->routeIs('sales.create')) active @endif">
                <i class="bi bi-grid me-2"></i><span>Create Sale</span>
            </a>
            <a href="{{ route('sales.pos') }}"
                class="nav-item nav-link d-flex   @if (request()->routeIs('sales.pos')) active @endif">
                <i class="bi bi-grid me-2"></i><span>POS</span>
            </a>
            <a href="{{ route('manual-sale-return.create') }}"
                class="nav-item nav-link d-flex   @if (request()->routeIs('manual-sale-return.create')) active @endif">
                <i class="bi bi-grid me-2"></i><span>Create Sale Return</span>
            </a>
            <a href="{{ route('sale_return.index') }}"
                class="nav-item nav-link d-flex   @if (request()->routeIs('sale_return.*')) active @endif">
                <i class="bi bi-grid me-2"></i><span>Sales Return</span>
            </a>
            <a href="{{ route('device-return.index') }}"
                class="nav-item nav-link d-flex   @if (request()->routeIs('device-return.index')) active @endif">
                <i class="bi bi-grid me-2"></i><span>Device Return</span>
            </a>
            <a href="{{ route('reserved-order.index') }}"
                class="nav-item nav-link d-flex   @if (request()->routeIs('reserved-order.index')) active @endif">
                <i class="bi bi-grid me-2"></i><span>Reserve Orders</span>
            </a>
            <hr class="mx-3" />
            @role(['Admin', 'Manager'])
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Purchases
                </p>
                <a href="{{ route('purchases.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('purchases.index')) active @endif">
                    <i class="fa-solid fa-list-check mt-1 me-2"></i>All Purchases</span>
                </a>
                <a href="{{ route('purchases.create') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('purchases.create')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Create Purchase</span>
                </a>
                <a href="{{ route('purchase_return.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('purchase_return.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Purchases Return</span>
                </a>
            @endrole
            <hr class="mx-3" />
            <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                Payments
            </p>
            <a href="{{ route('sales-payments.index') }}"
                class="nav-item nav-link d-flex   @if (request()->routeIs('sales-payments.index')) active @endif">
                <i class="bi bi-grid me-2"></i><span>Invoice Payments</span>
            </a>
            <a href="{{ route('non-sales-payments.index') }}"
                class="nav-item nav-link d-flex   @if (request()->routeIs('non-sales-payments.index')) active @endif">
                <i class="bi bi-grid me-2"></i><span>Non-Invoice Payments</span>
            </a>
            <a href="{{ route('purchase-payments.index') }}"
                class="nav-item nav-link d-flex   @if (request()->routeIs('purchase-payments.index')) active @endif">
                <i class="bi bi-grid me-2"></i><span>Purchase Payment</span>
            </a>
            <a href="{{ route('non-purchase-payments.index') }}"
                class="nav-item nav-link d-flex   @if (request()->routeIs('non-purchase-payments.index')) active @endif">
                <i class="bi bi-grid me-2"></i><span>Non-Purchase Payments</span>
            </a>
            <hr class="mx-3" />
            @role(['Admin', 'Manager'])
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Inventory
                </p>
                <a href="{{ route('inventories.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('inventories.index')) active @endif">
                    <i class="fa-solid fa-list-check mt-1 me-2"></i><span>All Inventory</span>
                </a>
                <a href="{{ route('inventories.create') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('inventories.create')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Receive Inventory</span>
                </a>
                <a href="{{ route('bills.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('bills.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Bills</span>
                </a>
                <a href="{{ route('inventory-stock-count') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('inventory-stock-count')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Stock Count</span>
                </a>

                <hr class="mx-3" />
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Customers
                </p>
                <a href="{{ route('customers.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('customers.index')) active @endif">
                    <i class="fa-solid fa-list-check mt-1 me-2"></i><span>All Customers</span>
                </a>
                <a href="{{ route('customers.create') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('customers.create')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Create Customer</span>
                </a>
                <a href="{{ route('customers.blacklist') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('customers.blacklist')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Blacklist Customer</span>
                </a>
                <a href="{{ route('tiers.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('tiers.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Tiers</span>
                </a>
                <hr class="mx-3" />
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Transfers
                </p>
                <a href="{{ route('transfers.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('transfers.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>All Transfers</span>
                </a>
                <a href="{{ route('transfers.create') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('transfers.create')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Create Transfers</span>
                </a>

                <hr class="mx-3" />
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Vendors
                </p>
                <a href="{{ route('vendors.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('vendors.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>All Vendors</span>
                </a>
                <a href="{{ route('vendors.create') }}"
                    class="nav-item nav-link d-flex  @if (request()->routeIs('vendors.create')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Create Vendors</span>
                </a>
                <a href="{{ route('vendors.blacklist') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('vendors.blacklist')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Blacklist Vendors</span>
                </a>
                <hr class="mx-3" />
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Shipment
                </p>
                <a href="{{ route('shipment.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('shipment.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>All Shipments</span>
                </a>
                <hr class="mx-3" />
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Accounting
                </p>
                <a href="{{ route('accounts.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('accounts.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>List Accounts</span>
                </a>
                <a href="{{ route('credit-cards.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('credit-cards.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>All Cards</span>
                </a>
                <a href="{{ route('transfer-money.create') }}"
                    class="nav-item nav-link d-flex  @if (request()->routeIs('transfer-money.create')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Transfers Money</span>
                </a>
                <a href="{{ route('expenses.create') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('expenses.create')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Create Expense</span>
                </a>
                <a href="{{ route('expenses.index') }}"
                    class="nav-item nav-link d-flex  @if (request()->routeIs('expenses.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>All Expenses</span>
                </a>
                <a href="{{ route('deposits.create') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('deposits.create')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Create Deposit</span>
                </a>
                <a href="{{ route('deposits.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('deposits.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>All Deposits</span>
                </a>
                <a href="{{ route('expense-categories.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('expense-categories.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Expense Category</span>
                </a>
                <a href="{{ route('deposit-categories.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('deposit-categories.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Deposit Category</span>
                </a>
                <hr class="mx-3">
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Setting
                </p>
                <a href="{{ route('setting.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('setting.*')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>System Setting</span>
                </a>
                @role('Admin')
                    <a href="{{ route('users.index') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('users.*')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Users</span>
                    </a>
                    <a href="{{ route('roles.index') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('roles.*')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>User Role</span>
                    </a>
                    <a href="{{ route('permissions.index') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('permissions.*')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>User Permission</span>
                    </a>
                @endrole
                <a href="{{ route('sms.setting') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('sms.setting')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>SMS Setting</span>
                </a>
                <a href="{{ route('email.setting') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('email.setting')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Email Setting</span>
                </a>
                <a href="{{ route('pos.setting') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('pos.setting')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>POS Setting</span>
                </a>
                <a href="{{ route('payment.setting') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('payment.setting')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Payment Setting</span>
                </a>
                <a href="{{ route('warehouses.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('warehouses.*')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Warehouse</span>
                </a>
                <a href="{{ route('taxes.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('taxes.*')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Taxes</span>
                </a>

                <hr class="mx-3">
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    Reports
                </p>
                @can('today-report')
                    <a href="{{ route('today.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('today.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Today Report</span>
                    </a>
                @endcan
                @can('purchase-payment-report')
                    <a href="{{ route('purchase.payment.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('purchase.payment.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Purchase Payment</span>
                    </a>
                @endcan
                @can('sale-payment-report')
                    <a href="{{ route('sale.payment.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('sale.payment.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Sales Payment</span>
                    </a>
                @endcan
                @can('inventory-valuation-report')
                    <a href="{{ route('inventory.valuation.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('inventory.valuation.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Inventory Valuation</span>
                    </a>
                @endcan
                @can('expense-report')
                    <a href="{{ route('expense.report') }}"
                        class="nav-item nav-link d-flex  @if (request()->routeIs('expense.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Expense Report</span>
                    </a>
                @endcan
                @can('deposit-report')
                    <a href="{{ route('deposit.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('deposit.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Deposits Report</span>
                    </a>
                @endcan
                @can('warehouse-report')
                    <a href="{{ route('warehouse.report') }}"
                        class="nav-item nav-link d-flex  @if (request()->routeIs('warehouse.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Warehouse Report</span>
                    </a>
                @endcan
                @can('stock-report')
                    <a href="{{ route('stock.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('stock.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Stock Report</span>
                    </a>
                @endcan
                @can('product-report')
                    <a href="{{ route('product.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('product.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Product Report</span>
                    </a>
                @endcan
                @can('sale-report')
                    <a href="{{ route('sale.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('sale.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Sale Report</span>
                    </a>
                @endcan
                @can('product-sale-report')
                    <a href="{{ route('sale.product.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('sale.product.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Product Sales Report</span>
                    </a>
                @endcan
                @can('purchase-report')
                    <a href="{{ route('purchase.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('purchase.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Purchase Report</span>
                    </a>
                @endcan
                @can('product-purchase-report')
                    <a href="{{ route('purchase.product.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('purchase.product.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Product Purchases Report</span>
                    </a>
                @endcan
                @can('customer-report')
                    <a href="{{ route('customer.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('customer.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Customer Report</span>
                    </a>
                @endcan
                @can('supplier-report')
                    <a href="{{ route('vendor.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('vendor.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Supplier Report</span>
                    </a>
                @endcan
                @can('top-selling-product-report')
                    <a href="{{ route('top.product.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('top.product.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Top Selling Products</span>
                    </a>
                @endcan
                @can('best-customer-report')
                    <a href="{{ route('best.customer.report') }}"
                        class="nav-item nav-link d-flex  @if (request()->routeIs('best.customer.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>Best Customers</span>
                    </a>
                @endcan
                @can('user-report')
                    <a href="{{ route('user.report') }}"
                        class="nav-item nav-link d-flex   @if (request()->routeIs('user.report')) active @endif">
                        <i class="bi bi-grid me-2"></i><span>User Report</span>
                    </a>
                @endcan


            @endrole
            @role('Admin')
                <hr class="mx-3">
                <p class="submenu nav-item nav-link py-0 mb-0 bg-transparent fw-bold text-dark">
                    CMS
                </p>
                <a href="{{ route('cms.landing-page.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('cms.landing-page.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Landing Page</span>
                </a>
                <a href="{{ route('about-us.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('about-us.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>About Us</span>
                </a>
                <a href="{{ route('approval-requests.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('approval-requests.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Approval Requests</span>
                </a>
                <a href="{{ route('admin.contact-us') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('admin.contact-us')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Contact Us</span>
                </a>
                <a href="{{ route('admin.support-ticket') }}"
                    class="nav-item nav-link d-flex  @if (request()->routeIs('admin.support-ticket')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Support Tickets</span>
                </a>
                <a href="{{ route('admin.cms.product-review') }}"
                    class="nav-item nav-link d-flex  @if (request()->routeIs('admin.cms.product-review')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Product Reviews</span>
                </a>
                <a href="{{ route('notification.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('notification.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Notifications</span>
                </a>
                <a href="{{ route('admin.term-and-condition.index') }}"
                    class="nav-item nav-link d-flex  @if (request()->routeIs('admin.term-and-condition.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Terms And Conditions</span>
                </a>
                <a href="{{ route('admin.privacy-policy.index') }}"
                    class="nav-item nav-link d-flex   @if (request()->routeIs('admin.privacy-policy.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Privacy Policy</span>
                </a>
                <a href="{{ route('admin.exchange-policy.index') }}"
                    class="nav-item nav-link d-flex  @if (request()->routeIs('admin.exchange-policy.index')) active @endif">
                    <i class="bi bi-grid me-2"></i><span>Exchange Policy</span>
                </a>
            @endrole






        </div>
    </nav>
</div>