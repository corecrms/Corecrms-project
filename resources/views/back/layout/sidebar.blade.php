
<div class="container-fluid position-relative bg-white d-flex p-0">

    <!-- Sidebar Start -->


    <div class="positon-relative sidebar-ul">
        @role(['Admin', 'Manager'])
            <div id="product" style="display: none">
                <ul class="list-unstyled m-0 px-4 rounded-5">
                    <li class="mb-2 @if (request()->routeIs('products.index')) sidebar-list @endif">
                        <a href="{{ route('products.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />All Products</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('products.create')) sidebar-list @endif">
                        <a href="{{ route('products.create') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Create Product</a>
                    </li>
                    {{-- <li class="mb-2 @if (request()->routeIs('print-lables.create')) sidebar-list @endif">
                        <a href="{{ route('print-lables.index') }}"
                            class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Print Label</a>
                    </li> --}}
                    <li class="mb-2 @if (request()->routeIs('categories.*')) sidebar-list @endif">
                        <a href="{{ route('categories.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Category</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('sub-categories.*')) sidebar-list @endif">
                        <a href="{{ route('sub-categories.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Sub Category</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('brands.*')) sidebar-list @endif">
                        <a href="{{ route('brands.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Brand</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('units.*')) sidebar-list @endif">
                        <a href="{{ route('units.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Unit
                        </a>
                    </li>
                    {{-- <li class="mb-2 @if (request()->routeIs('warranties.*')) sidebar-list @endif">
                        <a href="{{ route('warranties.index') }}"
                            class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Warranty
                        </a>
                    </li> --}}

                </ul>
            </div>
        @endrole
        <div id="sale" style="display: none">
            <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                <li class="mb-2 @if (request()->routeIs('sales.index')) sidebar-list @endif">
                    <a href="{{ route('sales.index') }}" class="text-decoration-none nav-item nav-link "><img
                            src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                            alt="" />All Sales</a>
                </li>
                <li class="mb-2 @if (request()->routeIs('sales.create')) sidebar-list @endif">
                    <a href="{{ route('sales.create') }}" class="text-decoration-none nav-item nav-link "><img
                            src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                            alt="" />Create Sale</a>
                </li>
                <li class="mb-2 @if (request()->routeIs('sales.pos')) sidebar-list @endif">
                    <a href="{{ route('sales.pos') }}" class="text-decoration-none nav-item nav-link "><img
                            src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                            alt="" />POS</a>
                </li>
                <li class="mb-2 @if (request()->routeIs('manual-sale-return.create')) sidebar-list @endif">
                    <a href="{{ route('manual-sale-return.create') }}"
                        class="text-decoration-none nav-item nav-link"><img
                            src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                            alt="" />Create Sale Return</a>
                </li>
                <li class="mb-2 @if (request()->routeIs('sale_return.*')) sidebar-list @endif">
                    <a href="{{ route('sale_return.index') }}" class="text-decoration-none nav-item nav-link "><img
                            src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                            alt="" />Sales Return</a>
                </li>
                <li class="mb-2 @if (request()->routeIs('device-return.index')) sidebar-list @endif">
                    <a href="{{ route('device-return.index') }}" class="text-decoration-none nav-item nav-link"><img
                            src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                            alt="" />Device Return</a>
                </li>
                <li class="mb-2 @if (request()->routeIs('reserved-order.index')) sidebar-list @endif">
                    <a href="{{ route('reserved-order.index') }}" class="text-decoration-none nav-item nav-link"><img
                            src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                            alt="" />Reserve Orders</a>
                </li>
            </ul>
        </div>
        @role(['Admin', 'Manager'])
            <div id="purchase" style="display: none">
                <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                    <li class="mb-2 @if (request()->routeIs('purchases.index')) sidebar-list @endif">
                        <a href="{{ route('purchases.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />All Purchases</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('purchases.create')) sidebar-list @endif">
                        <a href="{{ route('purchases.create') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Create Purchase</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('purchase_return.index')) sidebar-list @endif">
                        <a href="{{ route('purchase_return.index') }}"
                            class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Purchases Return</a>
                    </li>
                </ul>
            </div>
        @endrole
        <div id="payment" style="display: none">
            <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                <li class="mb-2 @if (request()->routeIs('sales-payments.index')) sidebar-list @endif">
                    <a href="{{ route('sales-payments.index') }}" class="text-decoration-none nav-item nav-link"><img
                            src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                            alt="" />Invoice Payments</a>
                </li>
                <li class="mb-2 @if (request()->routeIs('non-sales-payments.index')) sidebar-list @endif">
                    <a href="{{ route('non-sales-payments.index') }}"
                        class="text-decoration-none nav-item nav-link"><img
                            src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                            alt="" />Non-Invoice Payments</a>
                </li>
                <li class="mb-2 @if (request()->routeIs('purchase-payments.index')) sidebar-list @endif">
                    <a href="{{ route('purchase-payments.index') }}"
                        class="text-decoration-none nav-item nav-link"><img
                            src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                            alt="" />Purchase Payments</a>
                </li>
                <li class="mb-2 @if (request()->routeIs('non-purchase-payments.index')) sidebar-list @endif">
                    <a href="{{ route('non-purchase-payments.index') }}"
                        class="text-decoration-none nav-item nav-link"><img
                            src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                            alt="" />Non-Purchase Payments</a>
                </li>
            </ul>
        </div>
        @role(['Admin', 'Manager'])
            <div id="inventory" style="display: none">
                <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                    <li class="mb-2 @if (request()->routeIs('inventories.index')) sidebar-list @endif">
                        <a href="{{ route('inventories.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />All Inventory</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('inventories.create')) sidebar-list @endif">

                        <a href="{{ route('inventories.create') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Receive Inventory</a>

                    </li>
                    <li class="mb-2 @if (request()->routeIs('bills.index')) sidebar-list @endif">
                        <a href="{{ route('bills.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Bills</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('inventory-stock-count')) sidebar-list @endif">
                        <a href="{{ route('inventory-stock-count-of-all-products') }}"
                            class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Stock Count</a>
                    </li>
                </ul>
            </div>
            <div id="customer" style="display: none">
                <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                    <li class="mb-2 @if (request()->routeIs('customers.index')) sidebar-list @endif">
                        <a href="{{ route('customers.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />All Customers</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('customers.create')) sidebar-list @endif">
                        <a href="{{ route('customers.create') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Create Customer</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('customers.blacklist')) sidebar-list @endif">
                        <a href="{{ route('customers.blacklist') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Blacklist Customer</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('tiers.index')) sidebar-list @endif">
                        <a href="{{ route('tiers.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Tiers</a>
                    </li>
                </ul>
            </div>
            <div id="transfer" style="display: none">
                <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                    <li class="mb-2 @if (request()->routeIs('transfers.index')) sidebar-list @endif">
                        <a href="{{ route('transfers.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />All Transfers</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('transfers.create')) sidebar-list @endif">
                        <a href="{{ route('transfers.create') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Create Transfer</a>
                    </li>
                </ul>
            </div>


            <div id="vendor" style="display: none">
                <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                    <li class="mb-2 @if (request()->routeIs('vendors.index')) sidebar-list @endif">
                        <a href="{{ route('vendors.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />All Vendors</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('vendors.create')) sidebar-list @endif">
                        <a href="{{ route('vendors.create') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Create Vendors
                        </a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('vendors.blacklist')) sidebar-list @endif">
                        <a href="{{ route('vendors.blacklist') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Blacklist Vendors</a>
                    </li>
                </ul>
            </div>
            <div id="accounting" style="display: none">
                <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                    <li class="mb-2 @if (request()->routeIs('accounts.index')) sidebar-list @endif">
                        <a href="{{ route('accounts.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />List Accounts</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('credit-cards.index')) sidebar-list @endif">
                        <a href="{{ route('credit-cards.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />All Cards</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('transfer-money.create')) sidebar-list @endif">
                        <a href="{{ route('transfer-money.index') }}"
                            class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Transfers Money</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('expenses.create')) sidebar-list @endif">
                        <a href="{{ route('expenses.create') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Create Expense</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('expenses.index')) sidebar-list @endif">
                        <a href="{{ route('expenses.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />All Expenses</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('deposits.create')) sidebar-list @endif">
                        <a href="{{ route('deposits.create') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Create Deposit</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('deposits.index')) sidebar-list @endif">
                        <a href="{{ route('deposits.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />All Deposits</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('expense-categories.index')) sidebar-list @endif">
                        <a href="{{ route('expense-categories.index') }}"
                            class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Expense Category</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('deposit-categories.index')) sidebar-list @endif">
                        <a href="{{ route('deposit-categories.index') }}"
                            class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Deposit Category</a>
                    </li>

                </ul>
            </div>
            <div id="setting" style="display: none">
                <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                    <li class="mb-2 @if (request()->routeIs('setting.*')) sidebar-list @endif">
                        <a href="/setting" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />System Setting</a>
                    </li>
                    @role('Admin')
                        <li class="mb-2 @if (request()->routeIs('users.*')) sidebar-list @endif">
                            <a href="{{ route('users.index') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Users
                            </a>
                        </li>

                        <li class="mb-2 @if (request()->routeIs('roles.*')) sidebar-list @endif">
                            <a href="{{ route('roles.index') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />
                                User Role
                            </a>
                        </li>

                        <li class="mb-2 @if (request()->routeIs('permissions.*')) sidebar-list @endif">
                            <a href="{{ route('permissions.index') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />User Permission
                            </a>
                        </li>
                    @endrole
                    <li class="mb-2">
                        <a href="{{ route('sms.setting') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />SMS Setting</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('email.setting') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Email Setting</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('pos.setting') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />POS Setting</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('payment.setting') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Payment Setting</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('warehouses.*')) sidebar-list @endif">
                        <a href="{{ route('warehouses.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Warehouse</a>
                    </li>
                    {{-- <li class="mb-2">
                        <a href="{{ route('payment-methods.index') }}"
                            class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Payment Methods</a>
                    </li> --}}
                    <li class="mb-2 @if (request()->routeIs('taxes.*')) sidebar-list @endif">
                        <a href="{{ route('taxes.index') }}" class="text-decoration-none nav-item nav-link "><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Taxes
                        </a>
                    </li>
                </ul>
            </div>
        @endrole
        @role(['Admin', 'Manager'])
            <div id="report" style="display: none;">
                <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                    @can('today-report')
                        <li class="mb-2 @if (request()->routeIs('today.report')) sidebar-list @endif">
                            <a href="{{ route('today.report') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Today Report
                            </a>
                        </li>
                    @endcan


                    <li class="mb-2" id="warehouseReport">
                        <a href="#" class="text-decoration-none nav-item nav-link">
                            <div class="d-flex justify-content-between">

                                <div>
                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2" alt="" />
                                    Payments
                                </div>
                                <i class="fa-solid fa-chevron-down ms-3"></i>
                            </div>
                        </a>
                        <ul class="sub-dropdown list-unstyled" style="display: none">
                            @can('purchase-payment-report')
                                <li class="@if (request()->routeIs('purchase.payment.report')) sidebar-list @endif">
                                    <a href="{{ route('purchase.payment.report') }}" class="dropdown-item ">
                                        Purchases
                                    </a>
                                </li>
                            @endcan
                            @can('sale-payment-report')
                                <li class="@if (request()->routeIs('sale.payment.report')) sidebar-list @endif">
                                    <a href="{{ route('sale.payment.report') }}" class="dropdown-item ">
                                        Sales
                                    </a>
                                </li>
                            @endcan
                            <!-- Add more sub-dropdown items as needed -->
                        </ul>
                    </li>

                    @can('inventory-valuation-report')
                        <li class="mb-2 @if (request()->routeIs('inventory.valuation.report')) sidebar-list @endif">
                            <a href="{{ route('inventory.valuation.report') }}"
                                class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Inventory Valuation</a>
                        </li>
                    @endcan
                    {{--
                    @can('sales-payment-report')
                        <li class="mb-2 @if (request()->routeIs('sale.payment.report')) sidebar-list @endif">
                            <a href="{{ route('sale.payment.report') }}"
                                class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Sales Payment Report</a>
                        </li>
                    @endcan
                    @can('purchase-payment-report')
                        <li class="mb-2 @if (request()->routeIs('purchase.payment.report')) sidebar-list @endif">
                            <a href="{{ route('purchase.payment.report') }}"
                                class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Purchase Payment Report</a>
                        </li>
                    @endcan --}}

                    @can('expense-report')
                        <li class="mb-2 @if (request()->routeIs('expense.report')) sidebar-list @endif">
                            <a href="{{ route('expense.report') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Expense Report</a>
                        </li>
                    @endcan
                    @can('deposit-report')
                        <li class="mb-2 @if (request()->routeIs('deposit.report')) sidebar-list @endif">
                            <a href="{{ route('deposit.report') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Deposits Report</a>
                        </li>
                    @endcan
                    @can('warehouse-report')
                        <li class="mb-2 @if (request()->routeIs('warehouse.report')) sidebar-list @endif">
                            <a href="{{ route('warehouse.report') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Warehouse Report</a>
                        </li>
                    @endcan
                    @can('stock-report')
                        <li class="mb-2 @if (request()->routeIs('stock.report')) sidebar-list @endif">
                            <a href="{{ route('stock.report') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Stock Report</a>
                        </li>
                    @endcan
                    @can('product-report')
                        <li class="mb-2 @if (request()->routeIs('product.report')) sidebar-list @endif">
                            <a href="{{ route('product.report') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Product Report</a>
                        </li>
                    @endcan
                    @can('sale-report')
                        <li class="mb-2 @if (request()->routeIs('sale.report')) sidebar-list @endif">
                            <a href="{{ route('sale.report') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Sale Report</a>
                        </li>
                    @endcan
                    @can('product-sale-report')
                        <li class="mb-2 @if (request()->routeIs('sale.product.report')) sidebar-list @endif">
                            <a href="{{ route('sale.product.report') }}"
                                class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Product Sales Report</a>
                        </li>
                    @endcan

                    @can('purchase-report')
                        <li class="mb-2 @if (request()->routeIs('purchase.report')) sidebar-list @endif">
                            <a href="{{ route('purchase.report') }}" class="text-decoration-none nav-item nav-link"><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Purchase Report</a>
                        </li>
                    @endcan

                    @can('product-purchase-report')
                        <li class="mb-2 @if (request()->routeIs('purchase.product.report')) sidebar-list @endif">
                            <a href="{{ route('purchase.product.report') }}"
                                class="text-decoration-none nav-item nav-link"><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Product Purchases Report</a>
                        </li>
                    @endcan

                    @can('customer-report')
                        <li class="mb-2 @if (request()->routeIs('customer.report')) sidebar-list @endif">
                            <a href="{{ route('customer.report') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Customer Report</a>
                        </li>
                    @endcan

                    @can('supplier-report')
                        <li class="mb-2 @if (request()->routeIs('vendor.report')) sidebar-list @endif">
                            <a href="{{ route('vendor.report') }}" class="text-decoration-none nav-item nav-link "><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Supplier Report</a>
                        </li>
                    @endcan







                    @can('top-selling-product-report')
                        <li class="mb-2 @if (request()->routeIs('top.product.report')) sidebar-list @endif ">
                            <a href="{{ route('top.product.report') }}" class="text-decoration-none nav-item nav-link"><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Top Selling Products</a>
                        </li>
                    @endcan
                    @can('best-customer-report')
                        <li class="mb-2 @if (request()->routeIs('best.customer.report')) sidebar-list @endif">
                            <a href="{{ route('best.customer.report') }}"
                                class="text-decoration-none nav-item nav-link"><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />Best Customers</a>
                        </li>
                    @endcan
                    @can('user-report')
                        <li class="mb-2 @if (request()->routeIs('user.report')) sidebar-list @endif ">
                            <a href="{{ route('user.report') }}" class="text-decoration-none nav-item nav-link"><img
                                    src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                    alt="" />User Report</a>
                        </li>
                    @endcan
                </ul>
            </div>
        @endrole
        {{-- for CMS --}}
        @role('Admin')
            <div id="cms" style="display: none">
                <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                    <li class="mb-2 @if (request()->routeIs('cms.landing-page.index')) sidebar-list @endif">
                        <a href="{{ route('cms.landing-page.index') }}"
                            class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Landing Page</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('about-us.index')) sidebar-list @endif">
                        <a href="{{ route('about-us.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />About Us</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('approval-requests.index')) sidebar-list @endif">
                        <a href="{{ route('approval-requests.index') }}"
                            class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Approval Requests</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('admin.contact-us')) sidebar-list @endif">
                        <a href="{{ route('admin.contact-us') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Contact Us</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('admin.support-ticket')) sidebar-list @endif">
                        <a href="{{ route('admin.support-ticket') }}"
                            class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Support Tickets</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('admin.cms.product-review')) sidebar-list @endif">
                        <a href="{{ route('admin.cms.product-review') }}"
                            class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Product Reviews</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('notification.index')) sidebar-list @endif">
                        <a href="{{ route('notification.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Notifications</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('admin.term-and-condition.index')) sidebar-list @endif">
                        <a href="{{ route('admin.term-and-condition.index') }}"
                            class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Terms And Conditions</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('admin.privacy-policy.index')) sidebar-list @endif">
                        <a href="{{ route('admin.privacy-policy.index') }}"
                            class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Privacy Policy</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('admin.return-policy.index')) sidebar-list @endif">
                        <a href="{{ route('admin.return-policy.index') }}"
                            class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Return Policy</a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('admin.exchange-policy.index')) sidebar-list @endif">
                        <a href="{{ route('admin.exchange-policy.index') }}"
                            class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />Exchange Policy</a>
                    </li>

                </ul>
            </div>
            <div id="hrm" style="display: none">
                <ul class="list-unstyled m-0 py-3 px-3 rounded-5">
                    <li class="mb-2 @if (request()->routeIs('companies.*')) sidebar-list @endif">
                        <a href="{{ route('companies.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />
                            Company
                        </a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('departments.*')) sidebar-list @endif">
                        <a href="{{ route('departments.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />
                            Department
                        </a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('designations.*')) sidebar-list @endif">
                        <a href="{{ route('designations.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />
                            Designation
                        </a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('office-shift.*')) sidebar-list @endif">
                        <a href="{{ route('office-shift.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />
                            Office Shift
                        </a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('employees.*')) sidebar-list @endif">
                        <a href="{{ route('employees.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />
                            Employees
                        </a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('attendance.*')) sidebar-list @endif">
                        <a href="{{ route('attendance.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />
                            Attendence
                        </a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('leave-request.*')) sidebar-list @endif">
                        <a href="{{ route('leave-request.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />
                            Leave Request
                        </a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('holidays.*')) sidebar-list @endif">
                        <a href="{{ route('holidays.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />
                            Holidays
                        </a>
                    </li>
                    <li class="mb-2 @if (request()->routeIs('.*')) sidebar-list @endif">
                        <a href="{{ route('payrolls.index') }}" class="text-decoration-none nav-item nav-link"><img
                                src="{{ asset('back/assets/dasheets/img/menu.svg') }}" class="img-fluid me-2"
                                alt="" />
                            Payroll
                        </a>
                    </li>

                </ul>
            </div>
        @endrole
    </div>
    <!-- Sidebar End -->
    <script>
        // Toggle submenu visibility when clicking on the menu item
        $(document).ready(function() {
            $("#warehouseReport").click(function(event) {
                var submenu = $(this).find(".sub-dropdown");
                var chevron = $(this).find("i");
                submenu.slideToggle();
                chevron.toggleClass("fa-chevron-left fa-chevron-down");
                event.stopPropagation(); // Prevent the click event from bubbling up to the document
            });

            // Hide submenu when clicking outside of it
            $(document).click(function(event) {
                var submenu = $(".sub-dropdown");
                if (
                    !submenu.is(event.target) &&
                    submenu.has(event.target).length === 0
                ) {
                    submenu.hide();
                    $(".fa-chevron-down")
                        .removeClass("fa-chevron-down")
                        .addClass("fa-chevron-left");
                }
            });
        });
    </script>
