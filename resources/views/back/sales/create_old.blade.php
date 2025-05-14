@extends('back.layout.app')
@section('title', 'Create Sale')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        .ui-autocomplete {
            padding: 0 !important;
        }

        .ui-menu .ui-menu-item-wrapper {
            text-align: left;
        }
    </style>
    <style>
        .container {
            /* width: 100%;
            margin: 0 auto; */
            padding: 4px;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100px;
        }

        .info {
            width: 100%;
            margin-bottom: 20px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 5px;
            vertical-align: top;
        }

        .info .left,
        .info .right {
            width: 50%;
        }

        .endright {
            text-align: right;
            width: 100%;
        }

        .details {
            width: 100%;
            margin-bottom: 20px;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details th,
        .details td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .details th {
            background-color: #f2f2f2;
        }

        .footer {
            font-size: 12px;
        }


        .border-one {
            border: 3px solid #000 !important;
        }

        .row-border {
            border-bottom: 3px solid #000;
        }

        .border-end {
            border-right: 3px solid #343a40;
        }

        .fw-bold {
            font-weight: bold;
        }

        select {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Create Sale</h3>
            </div>
            <form class="container-fluid" action="{{ route('sales.store') }}" method="POST" id="createSaleForm">
                @csrf
                <div class="card card-shadow rounded-3 border-0 mt-5">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Date <span
                                            class="text-danger">*</span></label>

                                    <input class="form-control subheading" type="date" id="date"
                                        value="<?php echo date('Y-m-d'); ?>" />

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customers" class="mb-1 fw-bold">Customer <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" name="customer_id" id="customers">
                                        <option value="">Select customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                data-phone="{{ $customer->user->contact_no }}"
                                                data-email="{{ $customer->user->email }}"
                                                data-name="{{ $customer->user->name }}"
                                                data-address="{{ $customer->user->address }}"
                                                data-tier="{{ $customer->tier_id }}"
                                                data-tax-id="{{ $customer->tax_number ?? '' }}"
                                                data-discount="{{ $customer->tier->discount ?? 0 }}"
                                                data-saved-cards="{{ json_encode($customer->user->savedCards) }}"
                                                data-balance="{{ $customer->balance ?? 0 }}"
                                                data-due="{{ $customer->total_due_invoice ?? 0 }}"
                                                data-accounts="{{ $customer->accounts ?? '' }}">
                                                {{ $customer->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Warehouse<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" name="warehouse_id"id="warehouse_id"
                                        @if (auth()->user()->hasRole(['Cashier', 'Manager'])) disabled @endif>
                                        <option value="">Select Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" data-name="{{ $warehouse->users->name }}"
                                                data-phone="{{ $warehouse->users->contact_no }}"
                                                data-email="{{ $warehouse->users->email }}"
                                                data-address="{{ $warehouse->users->address }}"
                                                @if (auth()->user()->hasRole(['Cashier', 'Manager']) && auth()->user()->warehouse_id == $warehouse->id) selected @endif>
                                                {{ $warehouse->users->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="form-group mt-2">
                            <label for="exampleFormControlSelect1" class="mb-1 fw-bold">
                                Product <span class="spinner-border text-black spinner-border-sm ms-2 me-2" role="status"
                                    id="get-shipping-spinner" style="display: none">
                                </span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control subheading" placeholder="Product Code / Name"
                                    id="product_code" name="product_code" />
                                <div id="suggestionsContainer"></div>

                                <span class="input-group-text subheading" id="basic-addon2"><i
                                        class="bi bi-upc-scan"></i></span>
                                {{-- <div class="search-dropdown" id="searchDropdown" style=" display: none;"></div> --}}
                            </div>
                            <p class="subheading m-0 p-0">
                                Scan the barcode or enter symbology
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card card-shadow rounded-3 border-0 mt-4 p-3">
                    <div class="table-responsive">
                        <table class="table text-center" id="mainTable">
                            <h3 class="all-adjustment text-center pb-1">Order Items</h3>
                            <thead class="fw-bold">
                                <tr>
                                    <th class="align-middle">#</th>
                                    <th class="align-middle">Product Name</th>
                                    <th class="align-middle">Product Code</th>
                                    <th class="align-middle">Net Unit Price</th>
                                    <th class="align-middle">Stock</th>
                                    <th class="align-middle">Qty</th>
                                    <th class="align-middle">Discount</th>
                                    {{-- <th class="align-middle">Tax</th> --}}
                                    <th class="align-middle">Subtotal</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-2 px-3">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 border rounded-2">
                            <div class="row border-bottom subheading">
                                <div class="col-md-6 col-6">Order Tax</div>
                                <div class="col-md-6 col-6" id="order_tax_display">$0.00</div>
                            </div>

                            <div class="row border-bottom">
                                <div class="col-md-6 col-6">Discount</div>
                                <div class="col-md-6 col-6" id="discount_display">$0.00</div>
                            </div>

                            <div class="row border-bottom">
                                <div class="col-md-6 col-6">Shipping</div>
                                <div class="col-md-6 col-6" id="shipping_display">$0.00</div>
                            </div>

                            <div class="row disabled-bg">
                                <div class="col-md-6 col-6">Grand Total</div>
                                <div class="col-md-6 col-6" id="grand_total">$0.00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Fields -->
                <div class="card card-shadow rounded-3 border-0 mt-4 p-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ntn_no" class="mb-1 fw-bold">Tax ID:</label>
                                    <input type="text" placeholder="e.g: 349887645" class="form-control subheading"
                                        id="ntn_no" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order_tax" class="mb-1 fw-bold">Order Tax</label>
                                    <input type="number" placeholder="0%" class="form-control subheading"value="0"
                                        id="order_tax" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount" class="mb-1 fw-bold">Discount</label>
                                    <input type="number" placeholder="$0.00" class="form-control subheading"value="0"
                                        id="discount" />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="shipping" class="mb-1 fw-bold">Shipping Fee</label>
                                    <input type="number" placeholder="$0.00" class="form-control subheading"
                                        id="shipping" value="0" step="0.01" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="shipping" class="mb-1 fw-bold">Shipping Method</label>
                                    <select name="shipping_method" id="shipping_method" class="form-control">
                                        <option value="">Select Shipping Method</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status" class="mb-1 fw-bold">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-select subheading"
                                        aria-label="Default select example" id="status">
                                        <option value="completed">Completed</option>
                                        <option value="pending">Pending</option>
                                        <option value="ordered">Ordered</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_status" class="mb-1 fw-bold">Payment Status</label>
                                    <select class="form-control form-select subheading"
                                        aria-label="Default select example" id="payment_status">
                                        <option value="paid">Paid</option>
                                        <option value="partial">Partial</option>
                                        <option value="pending" selected>Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2" id="sale-calc" style="display:none;">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_method" class="mb-1 fw-bold">Select Payment Method</label>
                                    <select class="form-control form-select subheading"
                                        aria-label="Default select example" id="payment_method">
                                        <option value="">Choose Method</option>
                                        <option value="Cash">Cash Payment</option>
                                        <option value="Credit Store">Credit Store</option>
                                        <option value="Card">Card Payment</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="paying_amount" class="mb-1 fw-bold">Paying Amount</label>
                                    <input type="text" class="form-control" id="paying_amount">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="due_amount" class="mb-1 fw-bold">Due Amount</label>
                                    <input type="text" class="form-control" id="due_amount">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="return_amount" class="mb-1 fw-bold">Return Amount</label>
                                    <input type="text" class="form-control" id="return_amount" readonly>
                                </div>
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="form-group" id="account_div">
                                    <label for="bank_account" class="mb-1 fw-bold">Bank Account</label>
                                    <select class="form-control form-select subheading"
                                        aria-label="Default select example" id="bank_account">
                                        <option disabled selected>Select Account</option>
                                        @foreach ($bank_accounts as $bank_account)
                                            <option value="{{ $bank_account->id }}">{{ $bank_account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="display: none" id="card_div">
                                    <label for="card_id" class="mb-1 fw-bold">Saved Cards</label>
                                    <select class="form-control form-select subheading"
                                        aria-label="Default select example" id="card_id" name="card_id">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount_recieved" class="mb-1 fw-bold">Cash Recieved</label>
                                    <input type="text" placeholder="$0.00" class="form-control subheading"
                                        id="amount_recieved" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount_pay" class="mb-1 fw-bold">Paying Amount</label>
                                    <input type="text" placeholder="$0.00" class="form-control subheading"
                                        id="amount_pay" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="change_return" class="mb-1 fw-bold">Due Amount</label>
                                    <p class="subheading" id="change_return">0.00</p>
                                </div>
                            </div> --}}
                        </div>
                        <div id="sortable-table" class="mt-4"></div>

                        <div class="form-group mt-2">
                            <label for="notes" class="mb-1 fw-bold">Note</label>
                            <textarea class="form-control subheading" id="notes" placeholder="Add Note" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Input Fields End -->
                <button class="btn save-btn text-white mt-3" type="button" data-bs-target="#saleInvoiceModal"
                    data-bs-toggle="modal">Submit
                </button>

                {{-- Sale Invoice Model --}}
                <div class="modal fade" id="saleInvoiceModal" aria-hidden="true"
                    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content" style="background: rgb(0 0 0 / 79%)">
                            <div class="modal-header border-0">
                                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-5 col-12">
                                            <h3 class="all-adjustment text-center pb-1 text-white sale-generated"
                                                style="width: 40%">
                                                Sale Generated
                                            </h3>
                                            {{-- <p class="text-white mt-5">
                                                Sale Generated against the user below for amount of $300,
                                                You can print a paper bill or
                                                <span class="go-green">GO GREEN.</span>
                                            </p>
                                            <p class="text-secondary">
                                                (Going green will send bill by SMS and Email)
                                            </p> --}}
                                            <div class="form-group text-white print-overlay">
                                                <label for="nameInput" class="mb-1 fw-bold">Customer Name</label>
                                                <input type="text" placeholder="Phone No"
                                                    class="form-control subheading text-white" style="background: none"
                                                    id="nameInput" />
                                            </div>
                                            <div class="form-group text-white print-overlay mt-2">
                                                <label for="phoneInput" class="mb-1 fw-bold">Phone No.</label>
                                                <input type="text" placeholder="Phone No"
                                                    class="form-control subheading text-white" style="background: none"
                                                    id="phoneInput" />
                                            </div>
                                            <div class="form-group text-white print-overlay mt-2">
                                                <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Email</label>
                                                <input type="email" placeholder="Phone No"
                                                    class="form-control subheading text-white" style="background: none"
                                                    id="emailInput" />
                                            </div>
                                            {{-- <div class="form-group text-white print-overlay mt-2">
                                                <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Sales Tax ID</label>
                                                <input type="text" placeholder="Phone No"
                                                    class="form-control subheading text-white" style="background: none"
                                                    id="exampleFormControlInput1" />
                                            </div> --}}

                                            <div class="border-bottom pb-5">
                                                <button class="btn print-btn text-white mt-3 px-3" type="button"
                                                    id="print-btn" onclick="printInvoice()">
                                                    Print
                                                </button>
                                                {{-- <a href="javascript:window.print()"
                                                    class="btn print-btn text-white mt-3 px-3" type="button"
                                                    id="print-btn">
                                                    Print
                                                </a> --}}
                                                <input type="hidden" name="go_green" id="go_green_input"
                                                    value="0">
                                                {{-- <button class="btn green-btn text-white mt-3 px-3" type="button" id="gogreen-btn">
                                                    Go Green
                                                </button> --}}
                                            </div>

                                            <button class="btn newsale-btn text-white mt-3 px-3" id="createSaleBtn">
                                                <div class="spinner-border text-light spinner-border-sm ms-2 me-2"
                                                    role="status" id="btn-spinner" style="display: none">
                                                </div>
                                                <span id="#btn-text">Create New sale</span>
                                            </button>
                                        </div>

                                        <div class="col-md-7" id="invoice_print">
                                            <section class="invoice" id="invoice">
                                                <div class="container bg-white border-one p-4">

                                                    <div class="d-flex justify-content-between">
                                                        <div class="">
                                                            @if (getLogo() != null)
                                                                <img src="{{ asset('storage' . getLogo()) }}"
                                                                    alt="Logo" width="120">
                                                            @else
                                                                <h1 class="">Logo</h1>
                                                            @endif
                                                        </div>
                                                        <div class="">
                                                            <h4 id="invoice_warehouse_name">
                                                                Warehouse Name
                                                            </h4>
                                                            <p><span id="invoice_warehouse_address">122 Street Lahore
                                                                    Badami Bagh Lahore</span><br>Phone:
                                                                <span id="invoice_warehouse_no">00434434343</span><br><span
                                                                    id="invoice_warehouse_email">warehouse@gmail.com</span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-around">
                                                        <div class="">
                                                            <strong>SHIP TO:</strong><br>
                                                            {{-- {{ $sale->shipingAddress->name ?? 'Name' }}<br> --}}
                                                            <span class="invoice_cust_name" id="">Customer
                                                                Name</span><br>
                                                            <span class="invoice_cust_address" id="">Lahore
                                                                Pakistan</span><br>
                                                            Phone:
                                                            <span class="invoice_cust_no" id="">454543</span><br>
                                                            Email:
                                                            <span class="invoice_cust_email"
                                                                id="">example@gmail.com</span>
                                                        </div>
                                                        <div>
                                                            <strong>SOLD TO:</strong><br>
                                                            <span class="invoice_cust_name" id="">Customer
                                                                Name</span><br>
                                                            <span class="invoice_cust_address" id="">Lahore
                                                                Pakistan</span><br>
                                                            Phone:
                                                            <span class="invoice_cust_no" id="">454543</span><br>
                                                            Email:
                                                            <span class="invoice_cust_email"
                                                                id="">example@gmail.com</span>
                                                        </div>
                                                    </div>

                                                    <div class="details mt-2">
                                                        <table>
                                                            <tr>
                                                                <th>Date:</th>
                                                                <th>Invoice#:</th>
                                                                <th>Rep Name:</th>
                                                                <th>Shipping Method:</th>
                                                            </tr>
                                                            <tr>
                                                                <td id="invoice_date">
                                                                    2021-01-01
                                                                </td>
                                                                <td id="invoice_number">
                                                                    0433444
                                                                </td>
                                                                <td class="">
                                                                    {{ auth()->user()->name ?? '' }}
                                                                </td>
                                                                <td id="invoice_shipping_method">
                                                                    Store Pickup
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <div class="details">
                                                        <table class="table" id="modalTable">
                                                            <thead>
                                                                <tr>
                                                                    <th>UPC</th>
                                                                    <th>Product Name</th>
                                                                    <th>Price</th>
                                                                    <th>Qty</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Add your product rows here -->
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="info">
                                                        <table>
                                                            <tr>
                                                                <td class="left">
                                                                    <p>
                                                                        <span class="fw-bold">Prev
                                                                            Balance:
                                                                        </span>$<span
                                                                            id="invoice_total_prev_balance"></span>
                                                                        <br>
                                                                    </p>
                                                                    <p>
                                                                        <span class="fw-bold">Total Due
                                                                            Balance:</span>$<span
                                                                            id="invoice_total_due"></span>
                                                                        <br>
                                                                    </p>
                                                                </td>

                                                                <td class="" style="width: 300px;text-align: end;">
                                                                    <p><span class="fw-bold">Todays Total Due:</span> <span
                                                                            style="text-decoration: underline;">$<span
                                                                                id="invoice_today_total_due">0.00</span>
                                                                        </span><br>
                                                                        <span class="fw-bold">Shipping:</span>
                                                                        <span style="text-decoration: underline;"><span
                                                                                id="invoice_shipping">0.00</span></span><br>

                                                                        <span class="fw-bold">Discounts:</span>
                                                                        <span style="text-decoration: underline;"><span
                                                                                id="invoice_discount">0.00</span></span><br><span
                                                                            class="fw-bold">Amount Paid:</span> <span
                                                                            style="text-decoration: underline;">$ <span
                                                                                id="invoice_amount_paid">0.00</span></span><br><span
                                                                            class="fw-bold">Payment Method: <span
                                                                                id="invoice_payment_method"></span>

                                                                        </span>
                                                                        <br>
                                                                    </p>
                                                                </td>

                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="footer">
                                                        <p>Powered By 77Clouds.com</p>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalToggle" tabindex="-1" aria-labelledby="exampleModalToggleLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title product-title" id="exampleModalToggleLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-title">
                            <h4 class="item-head"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_price" class="mb-1 fw-bold">Product Price</label>
                                        <input type="text" class="form-control subheading" id="product_price"
                                            value="Product Price *" />
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tax_type" class="mb-1 fw-bold">Tax Type</label>
                                        <select class="form-control form-select subheading"
                                            aria-label="Default select example" id="tax_type">
                                            <option value="" disabled selected>Select Tax Type</option>
                                            <option value="1">Inclusive</option>
                                            <option value="2">Exclusive</option>
                                        </select>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="order_tax_item" class="mb-1 fw-bold">Order Tax</label>
                                        <input type="number" class="form-control subheading" id="order_tax_item"
                                            value="0" />
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount_type_item" class="mb-1 fw-bold">Discount Type</label>
                                        <select name="discount_type" id="discount_type"
                                            class="form-control form-select subheading"
                                            aria-label="Default select example">
                                            <option value="" disabled selected> Select Discount Type</option>
                                            <option value="fixed">Fixed</option>
                                            <option value="percentage">Percentage</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount_item" class="mb-1 fw-bold">Discount</label>
                                        <input type="text" class="form-control subheading" id="discount_item"
                                            value="" placeholder="discount here" />
                                    </div>
                                    <input type="hidden" id="hidden_id">
                                </div>

                                <div class="col-md-6" id="unit_section">
                                    <div class="form-group">
                                        <label for="sale_unit_item" class="mb-1 fw-bold">Sale Unit</label>
                                        <select name="sale_unit_item" id="sale_unit_item"
                                            class="form-control form-select subheading"
                                            aria-label="Default select example">
                                            <option value="" disabled selected>Select Sale Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id ?? '' }}"
                                                    data-sale-unit-item="{{ $unit }}"> {{ $unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-product-item="" id="saveChangesButton">Save
                            changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            $('#customers').change(function() {
                var discount = $('option:selected', this).data('discount');
                $('#discount').val(discount);
                // $('#discount_display').text('$' + discount.toFixed(2));
                $('#discount_display').text('%' + discount.toFixed(2));
                // $('#discount').trigger('input');
                var tax_id = $('option:selected', this).data('tax-id');
                $('#ntn_no').val(tax_id);

                var savedCards = $(this).find(':selected').data('saved-cards');
                // console.log(savedCards);
                $('#card_id').empty();
                $('#card_id').append('<option value="" disabled selected>Select Card</option>');
                if (savedCards.length > 0) {
                    savedCards.forEach(function(card) {
                        $('#card_id').append('<option value="' + card.id + '">' + '************' +
                            card.card_last_four + '</option>');
                    });
                }
                $('#sortable-table').empty();
            });

            const productCodeInput = document.getElementById("product_code");
            let prodCount = 1;

            var suggestionsContainer = $("#suggestionsContainer");
            $("#product_code").autocomplete({
                source: function(request, response) {
                    var searchTerm = request.term;
                    performAddressSearch(searchTerm, response);
                },
                minLength: 2,
                select: function(event, ui) {
                    console.log(ui.item);

                    const tableBody = document.querySelector(".table tbody");
                    const row = document.createElement("tr");
                    let isDuplicate = false;
                    let hasRow = true;
                    document.querySelectorAll('.table tbody tr').forEach(row => {
                        if (row.querySelector('td:nth-child(3)').textContent === ui.item.product
                            .sku) {
                            // alert('Duplicate product cannot be added!');

                            // Select the input element
                            let qtyInput = $(row).find('td:nth-child(6) .qty-input');

                            // Get the current value of the input
                            let currentValue = parseInt(qtyInput.val());

                            // Increment the value and set it to the input
                            qtyInput.val(currentValue + 1).change();

                            isDuplicate = true;
                        }
                    });
                    // Enable or disable the select box based on whether any rows exist
                    $('#warehouse_id').prop('disabled', hasRow);
                    if (!isDuplicate) {
                        let quantity = ui.item.product.warehouse_quantity;
                        if (ui.item.product.product_type != 'service') {
                            if (ui.item.product.product_unit != ui.item.product.sale_unit) {
                                if (ui.item.product.unit.parent_id == 0) {
                                    quantity = eval(
                                        `${ui.item.product.warehouse_quantity}${ui.item.product.unit.operator}${ui.item.product.sale_units.operator_value} `
                                    );
                                }
                            }
                        }

                        row.innerHTML = `
                            <td class="align-middle">${prodCount}</td>
                            <td class="product_name align-middle ">${ui.item.product.name}</td>
                            <td class=" align-middle product_sku">${ui.item.product.sku}</td>
                            <td class="product_sell_price align-middle ">${ui.item.product.sell_price}</td>
                            <td class="align-middle">
                            <span class="badges bg-darkwarning p-1" product_stock data-converted-unit="${ui.item.product.sale_units?.id ? ui.item.product.sale_units.id : ''}">${quantity}${ui.item.product.sale_units?.short_name ? ui.item.product.sale_units.short_name : '' }</span>
                            </td>
                            <td class="align-middle">
                            <div
                                class="quantity d-flex justify-content-center align-items-center"
                            >
                                <button type="button" class="btn qty-minus-btn" id="minusBtn">
                                <i class="fa-solid fa-minus"></i>
                                </button>
                                <input
                                type="number"
                                id="quantityInput"
                                class="product_qty border-0 qty-input "
                                value="0" min="0"
                                />
                                <button type="button" class=" btn qty-plus-btn" id="plusBtn">
                                <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            </td>
                            <td class="align-middle">0.00</td>
                            <td class="product_price align-middle">0.00</td>
                            <td class="align-middle">
                            <div class="d-flex justify-content-center">
                                <a href="#" class="btn item-edit" data-product='${JSON.stringify(ui.item.product)}' data-bs-target="#exampleModalToggle" data-bs-toggle="modal">
                                    <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}" alt="" />
                                </a>
                                <a href="#" class="btn btn-plus item-delete">
                                    <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}" alt="" />
                                </a>
                            </div>
                            </td>
                        `;

                        tableBody.appendChild(row);
                        prodCount++;
                    }
                },
                appendTo: "#suggestionsContainer"
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>").append("<div>" + item.label + "</div>").appendTo(ul);
            };

            function performAddressSearch(searchTerm, response) {
                let warehouse = $('#warehouse_id').val();
                if (!$('#warehouse_id').val()) {
                    toastr.error("Please select warehouse!");
                }
                $.ajax({
                    url: '/get-product-detail-by-warehouse', // Replace with your search route
                    dataType: "json",
                    data: {
                        query: searchTerm,
                        warehouse_id: warehouse,
                    },
                    success: function(data) {
                        console.log(data);
                        var suggestions = [];
                        for (var i = 0; i < data.product.length; i++) {
                            suggestions.push({
                                value: data.product[i].sku,
                                label: data.product[i].name,
                                id: data.product[i].id,
                                product: data.product[i]
                            });
                        }


                        // If there's exactly one result, add it to the table automatically
                        if (suggestions.length === 1) {
                            $("#product_code").autocomplete("option", "select").call(null, null, {
                                item: suggestions[0]
                            });
                        } else {
                            // If there are multiple suggestions, show them in the dropdown
                            response(suggestions);
                        }
                            // console.log(suggestions)
                    }
                });
            }

            // let payment_status = document.getElementById('payment_status');
            // payment_status.addEventListener('change', function() {
            //     if (this.value == 'paid') {
            //         document.getElementById('sale-calc').style.display = 'flex';
            //         // grand total value will be set to the amount pay value with disable input and amount_recieved will be set to grand total value
            //         var grandTotal = parseFloat(document.getElementById('grand_total').textContent.replace(
            //             '$', ''));
            //         document.getElementById('amount_pay').value = grandTotal;
            //         document.getElementById('amount_pay').setAttribute('disabled', true);
            //         document.getElementById('amount_recieved').value = grandTotal;
            //         document.getElementById('amount_recieved').setAttribute('disabled', true);
            //         document.getElementById('change_return').textContent = 0.00;

            //     } else if (this.value == 'partial') {
            //         document.getElementById('sale-calc').style.display = 'flex';
            //         var grandTotal = parseFloat(document.getElementById('grand_total').textContent.replace(
            //             '$', ''));
            //         document.getElementById('amount_pay').value = grandTotal;
            //         document.getElementById('amount_pay').setAttribute('disabled', true);

            //         // document.getElementById('amount_pay').value = 0;
            //         // var amountPayInput = document.getElementById('amount_pay');
            //         if (document.getElementById('amount_recieved').disabled) {
            //             document.getElementById('amount_recieved').disabled = false;
            //         }
            //         document.getElementById('amount_recieved').value = 0;
            //         document.getElementById('change_return').textContent = grandTotal.toFixed(2);



            //     } else {
            //         document.getElementById('sale-calc').style.display = 'none';
            //         document.getElementById('amount_recieved').value = 0;
            //         var grandTotal = parseFloat(document.getElementById('grand_total').textContent.replace(
            //             '$', ''));
            //         document.getElementById('change_return').textContent = grandTotal.toFixed(2);

            //     }
            // });

            // let amountRecievedInput = document.getElementById('amount_recieved');
            // let amountPayInput = document.getElementById('amount_pay');

            // // Add event listeners
            // amountRecievedInput.addEventListener('input', calculateChangeReturn);
            // amountPayInput.addEventListener('input', calculateChangeReturn);

            // function calculateChangeReturn() {
            //     // Parse the input values to floats
            //     let amountRecieved = parseFloat(amountRecievedInput.value);
            //     let amountPay = parseFloat(amountPayInput.value);
            //     // Get the payment status
            //     let paymentStatus = document.getElementById('payment_status').value;

            //     // If the payment status is 'partial' and amountPay is greater than amountRecieved, show an error and return
            //     // if (paymentStatus === 'partial' && amountPay > amountRecieved) {
            //     //     alert('Paying amount cannot be greater than received amount for partial payments');
            //     //     amountPayInput.value = amountRecieved;
            //     //     return;
            //     // }
            //     if (paymentStatus === 'partial' && amountPay < amountRecieved) {
            //         alert('Received amount cannot be greater than paying amount for partial payments');
            //         amountRecievedInput.value = 0;
            //         document.getElementById('change_return').textContent = amountPay.toFixed(2);

            //         return;
            //     }

            //     // Calculate the change return
            //     // let changeReturn = amountRecieved - amountPay;
            //     let changeReturn = amountPay - amountRecieved;

            //     // Check if the change return is a valid number (it will be NaN if either input field is empty or non-numeric)
            //     if (!isNaN(changeReturn)) {
            //         // Update the change_return field
            //         document.getElementById('change_return').textContent = changeReturn.toFixed(2);
            //     }
            // }




            let payment_status = document.getElementById('payment_status');
            payment_status.addEventListener('change', function() {
                if (this.value == 'paid') {
                    document.getElementById('sale-calc').style.display = 'flex';
                    // grand total value will be set to the amount pay value with disable input and amount_recieved will be set to grand total value
                    var grandTotal = parseFloat(document.getElementById('grand_total').textContent.replace(
                        '$', ''));
                    document.getElementById('paying_amount').value = grandTotal;
                    document.getElementById('paying_amount').setAttribute('disabled', true);
                    document.getElementById('due_amount').value = grandTotal;
                    document.getElementById('due_amount').setAttribute('disabled', true);

                } else if (this.value == 'partial') {
                    document.getElementById('sale-calc').style.display = 'flex';
                    var grandTotal = parseFloat(document.getElementById('grand_total').textContent.replace(
                        '$', ''));
                    document.getElementById('paying_amount').value = grandTotal;
                    document.getElementById('paying_amount').setAttribute('disabled', true);
                    document.getElementById('due_amount').value = grandTotal;
                    document.getElementById('due_amount').setAttribute('disabled', true);

                } else {
                    document.getElementById('sale-calc').style.display = 'none';
                    document.getElementById('due_amount').value = grandTotal;
                    document.getElementById('due_amount').setAttribute('disabled', true);
                    $('#sortable-table').empty();
                }
            });



            $(document).on('change', '#payment_method', function() {
                if (!$('#customers').find(':selected').length) {
                    toastr.error('Select customer first');
                    return;
                }
                if ($('#paying_amount').val() == 0) {
                    toastr.error('Error');
                    return
                }

                let paymentMethod = this.value;
                // let accounts = @json($bank_accounts);
                let accounts = $('#customers').find(':selected').data('accounts');


                // Check if the payment method field already exists
                if ($('#sortable-table').find(`input[type="hidden"][value="${paymentMethod}"]`).length >
                    0) {
                    toastr.error('This payment method has already been added')
                    return; // Exit the function if the payment method already exists
                }

                // <div class="form-group col-md-3">
                //                         <label for="bankAccount">Bank Account</label>
                //                         <select class="form-control" name="bank_account">
                //                             <option value="" disabled selected>Select Account</option>
                //                             ${accounts.map(account => `<option value="${account.id}">${account.name}</option>`).join('')}
                //                         </select>
                //                     </div>


                if (paymentMethod === 'Cash') {



                    let newRow = `
                    <div class="col-md-10 m-auto">
                        <div class="card bg-light border-light mb-3">
                            <div class="card-header d-flex">
                                <span class="card-title w-100">Cash Payment</span>
                                <input type="hidden"  value="Cash" name="payment">
                                <button type="button" class="btn btn-danger float-right remove-item"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="paymentMethod">Payment Method</label>
                                        <input type="text" id="cash_payment" value="Cash Payment" readonly="readonly" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="paymentMethod">Amount Received</label>
                                        <input type="number" value="0" id="amount_received" name="amount_received" class="form-control amount-received" min="0"  step="0.01" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $('#sortable-table').append(newRow);

                } else if (paymentMethod === 'Card') {

                    var savedCards = $('#customers').find(':selected').data('saved-cards') ?? [];

                    if (savedCards.length == 0) {
                        toastr.error('selected customer has no cards');
                        return;
                    }

                    let newRow = `
                    <div class="col-md-10 m-auto">
                        <div class="card bg-light border-light mb-3">
                            <div class="card-header d-flex">
                                <span class="card-title w-100">Card Payment</span>
                                <input type="hidden" value="Card" name="payment">
                                <button type="button" class="btn btn-danger float-right remove-item"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="paymentMethod">Payment Method</label>
                                        <input type="text" id="card_payment" value="Card Payment" readonly="readonly" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="customerCards">Customer Cards</label>
                                        <select class="form-control" name="customer_card">
                                            <option value="" disable>Select Card</option>
                                            ${savedCards.map(card => `<option value="${card.id}">**********${card.card_last_four}</option>`).join('')}
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="paymentMethod">Amount Received</label>
                                        <input type="number" id="amount_received" value="0" name="amount_received" class="form-control amount-received" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $('#sortable-table').append(newRow);

                } else if (paymentMethod === 'Credit Store') {
                    var balance = $('#customers').find(':selected').data('balance') ?? 0;
                    if (balance == 0) {
                        balance = parseFloat(balance);
                        toastr.error('selected customer has no balance');
                        return;
                    }

                    let newRow = `
                    <div class="col-md-10 m-auto">
                        <div class="card bg-light border-light mb-3">
                            <div class="card-header d-flex">
                                <span class="card-title w-100">Credit Store Payment</span>
                                <input type="hidden" value="Credit Store" name="payment">
                                <button type="button" class="btn btn-danger float-right remove-item"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="paymentMethod">Payment Method</label>
                                        <input type="text" id="credit_store_payment" value="Credit Store Payment" readonly="readonly" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="storeBalance">Credit Store Balance</label>
                                        <input type="text" name="store_balance" value="${balance}" class="form-control" readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="payingAmount">Amount Received</label>
                                        <input type="number" value="0" name="paying_amount_fee" class="form-control amount-received" max="${balance}" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $('#sortable-table').append(newRow);
                }
            });



            $(document).on('input', '.amount-received', function() {
                let totalPayingAmount = parseFloat($('#paying_amount').val()) || 0;
                let totalReceivedAmount = 0;

                // Calculate the total amount received from all fields
                $('.amount-received').each(function() {
                    totalReceivedAmount += parseFloat($(this).val()) || 0;
                });

                // Check if the current value causes the total received to exceed the paying amount
                // if (totalReceivedAmount > totalPayingAmount) {
                //     toastr.error('Total amount received cannot be greater than paying amount');
                //     $(this).val(0); // Reset the current input to zero
                //     updateDueAmount(); // Update the due amount after resetting the input
                //     return;
                // }
                // Update the due amount if everything is correct
                updateDueAmount();
            });

            function updateDueAmount() {
                let totalPayingAmount = parseFloat($('#paying_amount').val()) || 0;
                let totalReceivedAmount = 0;

                // Sum all the amounts received
                $('.amount-received').each(function() {
                    totalReceivedAmount += parseFloat($(this).val()) || 0;
                });

                // Calculate and update the due amount
                let dueAmount = 0;
                if(totalReceivedAmount < totalPayingAmount){
                    dueAmount = totalPayingAmount - totalReceivedAmount;
                }
                $('#due_amount').val(dueAmount.toFixed(2));



                let returnAmount = 0;
                if (totalReceivedAmount > totalPayingAmount) {
                    returnAmount = totalReceivedAmount - totalPayingAmount;
                }

                $('#return_amount').val(returnAmount.toFixed(2));
            }


            $(document).on('click', '.remove-item', function() {
                $(this).closest('.col-md-10').remove();
                updateDueAmount();
            });



            // Remove existing handlers to prevent duplicate bindings
            $(document).off("click", ".qty-minus-btn").on("click", ".qty-minus-btn", function() {
                var input = $(this).siblings(".qty-input");
                var currentValue = parseInt(input.val());
                if (currentValue > 1) {
                    input.val(currentValue - 1).change();
                }
            });

            $(document).off("click", ".qty-plus-btn").on("click", ".qty-plus-btn", function() {
                var input = $(this).siblings(".qty-input");
                var currentValue = parseInt(input.val());
                input.val(currentValue + 1).change();
            });

            // Handle changes in quantity
            $(document).on('change', '.qty-input', function() {
                const quantity = $(this).val();
                const stock = $(this).closest('tr').find('td:nth-child(5)').text();
                if (quantity > parseFloat(stock)) {
                    toastr.error('Quantity exceeded');
                    $(this).val(parseFloat(stock));
                }
                const price = $(this).closest('tr').find('td:nth-child(4)').text();

                // const subtotal = parseInt(quantity) * parseFloat(price);
                const subtotal = (parseFloat(quantity) * parseFloat(price));
                $(this).closest('tr').find('td:nth-child(8)').text(subtotal.toFixed(2));
                calculateTotal();
            });

            // Event listeners for discount, shipping, and order tax inputs
            $('#discount, #shipping, #order_tax').on('input', calculateTotal);


            // Get the select element
            const customersSelect = document.getElementById('customers');

            // Add event listener for change event
            customersSelect.addEventListener('change', function() {
                // Get the selected option
                const selectedOption = this.options[this.selectedIndex];

                // Get data attributes from the selected option
                const name = selectedOption.dataset.name;
                const phone = selectedOption.dataset.phone;
                const email = selectedOption.dataset.email;
                const address = selectedOption.dataset.address;
                const balance = selectedOption.dataset.balance;
                const total_due = selectedOption.dataset.due;

                // Update the modal with customer details
                document.getElementById('nameInput').value = name;
                document.getElementById('phoneInput').value = phone;
                document.getElementById('emailInput').value = email;

                document.querySelectorAll('.invoice_cust_name').forEach(el => el.innerHTML = name);
                document.querySelectorAll('.invoice_cust_address').forEach(el => el.innerHTML = address);
                document.querySelectorAll('.invoice_cust_no').forEach(el => el.innerHTML = phone);
                document.querySelectorAll('.invoice_cust_email').forEach(el => el.innerHTML = email);

                document.getElementById('invoice_total_prev_balance').innerHTML = total_due;
                // document.getElementById('invoice_total_due').innerHTML = (total_due + $('#payment_status').val() == 'pending' ? parseFloat(document.getElementById('grand_total').textContent.replace('$', '')) : document.getElementById('due_amount').value ) ;
                // let todayDueAmount = ($('#payment_status').val() == 'pending' ?
                //         parseFloat(document.getElementById('grand_total').textContent.replace('$', '')) :
                //         parseFloat(document.getElementById('due_amount').value)
                //     )
                // document.getElementById('invoice_total_due').innerHTML = (parseFloat(todayDueAmount) + total_due);

                let todayDueAmount = ($('#payment_status').val() == 'pending' ?
                    parseFloat(document.getElementById('grand_total').textContent.replace('$', '')) :
                    parseFloat(document.getElementById('due_amount').value)
                );

                // Ensure total_due is parsed as a float before addition
                let totalDueFloat = parseFloat(total_due);

                document.getElementById('invoice_total_due').innerHTML = (todayDueAmount + totalDueFloat)
                    .toFixed(2);

                document.getElementById('invoice_date').innerHTML = document.getElementById('date').value ??
                    new Date().toISOString().slice(0, 10);
                // console.log("customer due" )
            });

            // Get the select element
            const warehouseSelect = document.getElementById('warehouse_id');

            // Add event listener for change event
            warehouseSelect.addEventListener('change', function() {
                // Get the selected option
                const selectedOption = this.options[this.selectedIndex];

                // Get data attributes from the selected option
                const name = selectedOption.dataset.name;
                const phone = selectedOption.dataset.phone;
                const email = selectedOption.dataset.email;
                const address = selectedOption.dataset.address;

                // Update the modal with customer details
                document.getElementById('invoice_warehouse_name').innerHTML = name;
                document.getElementById('invoice_warehouse_no').innerHTML = phone;
                document.getElementById('invoice_warehouse_email').innerHTML = email;
                document.getElementById('invoice_warehouse_address').innerHTML = email;

            });

        });

        // function calculateTotal() {
        //     let subtotal = 0;
        //     $('.table tbody tr').each(function() {
        //         subtotal += parseFloat($(this).find('td:nth-child(9)').text() || 0);
        //     });

        //     // Assume `orderTax` is a percentage value from an input field

        //     const orderTax = parseFloat($('#order_tax').val() == '' ? 0 : $('#order_tax').val()) / 100;
        //     const taxAmount = subtotal * orderTax;

        //     const discountValue = parseFloat($('#discount').val() == '' ? 0 : $('#discount').val());

        //     const shipping = parseFloat($('#shipping').val() == '' ? 0 : $('#shipping').val());

        //     const grandTotal = subtotal + taxAmount - discountValue + shipping;

        //     // Update the UI
        //     let orderTaxInPercentage = orderTax * 100;
        //     $('#order_tax_display').text(`$${taxAmount.toFixed(2)} (${orderTaxInPercentage.toFixed()}%)`);
        //     $('#discount_display').text(`$${discountValue.toFixed(2)}`);
        //     $('#shipping_display').text(`$${shipping.toFixed(2)}`);
        //     $('#grand_total').text(`$${grandTotal.toFixed(2)}`);
        //     if ($('#payment_status').val() == 'paid') {
        //         $('#amount_pay').val(grandTotal);
        //         $('#amount_recieved').val(grandTotal);
        //     }
        // }

        function calculateTotal() {
            let subtotal = 0;
            $('.table tbody tr').each(function() {
                subtotal += parseFloat($(this).find('td:nth-child(8)').text() || 0);
            });

            // Assume `orderTax` is a percentage value from an input field
            const orderTax = parseFloat($('#order_tax').val() == '' ? 0 : $('#order_tax').val()) / 100;
            const taxAmount = subtotal * orderTax;

            // Discount is now a percentage
            const discountPercentage = parseFloat($('#discount').val() == '' ? 0 : $('#discount').val()) / 100;
            const discountAmount = subtotal * discountPercentage;

            const shipping = parseFloat($('#shipping').val() == '' ? 0 : $('#shipping').val());

            const grandTotal = subtotal + taxAmount - discountAmount + shipping;

            // Update the UI
            let orderTaxInPercentage = orderTax * 100;
            $('#order_tax_display').text(`$${taxAmount.toFixed(2)} (${orderTaxInPercentage.toFixed()}%)`);
            $('#discount_display').text(`$${discountAmount.toFixed(2)} (${(discountPercentage * 100).toFixed(2)}%)`);
            $('#shipping_display').text(`$${shipping.toFixed(2)}`);
            $('#grand_total').text(`$${grandTotal.toFixed(2)}`);


            if ($('#payment_status').val() == 'paid' || $('#payment_status').val() == 'partial') {
                $('#paying_amount').val(grandTotal.toFixed(2));
                $('#due_amount').val(grandTotal.toFixed(2));

                $('.amount-received').trigger('input');

            }
        }


        // When an item-edit button is clicked
        $(document).on('click', '.item-edit', function() {
            $('#exampleModalToggle').show();
            // Parse the product data from the button's data-product attribute
            const product = JSON.parse($(this).attr('data-product'));
            console.log(product);
            // Populate the modal fields with the product data
            $('#exampleModalToggle .product-title').text(product.name);
            $('#exampleModalToggle #product_price').val(product.sell_price || product.price);
            // $('#exampleModalToggle #tax_type').val(product.tax_type);
            // $('#exampleModalToggle #order_tax_item').val(product.order_tax);
            $('#exampleModalToggle #discount_type').val(product.discount_type ?? "fixed");
            $('#exampleModalToggle #discount_item').val(product.discount ?? '0.00');
            $('#exampleModalToggle #hidden_id').val(product.id);
            if (product.product_type == 'service') {
                $('#exampleModalToggle #unit_section').css('display', 'none');
            } else {
                $('#exampleModalToggle #unit_section').css('display', 'block');
                $('#exampleModalToggle #sale_unit_item').val(product.sale_units?.id);
            }
            product.discount = 0;
            product.discount_type = "fixed";
            // add product into the data-product-item attribute of #saveChangesButton
            $('#saveChangesButton').attr('data-product-item', JSON.stringify(product));
        });

        $('#saveChangesButton').click(function() {
            // Retrieve and parse updated product data from modal
            const product_details = JSON.parse($('#saveChangesButton').attr('data-product-item'));
            let sale_units;
            if (product_details.product_type != 'service') {
                const selectedOption = $('#exampleModalToggle #sale_unit_item option:selected');
                sale_units = selectedOption.data('sale-unit-item');
            }

            const updatedProduct = {
                // existing code to retrieve data...
                // tax_type: parseFloat($('#exampleModalToggle #tax_type').val()),
                // order_tax: parseFloat($('#exampleModalToggle #order_tax_item').val()) ? parseFloat($(
                //     '#exampleModalToggle #order_tax_item').val()) : 0,
                discount_type: $('#exampleModalToggle #discount_type').val(),
                discount: parseFloat($('#exampleModalToggle #discount_item').val()) ? parseFloat($(
                    '#exampleModalToggle #discount_item').val()) : 0,
                id: parseFloat($('#exampleModalToggle #hidden_id').val()),
                price: parseFloat($('#exampleModalToggle #product_price').val()) ? parseFloat($(
                    '#exampleModalToggle #product_price').val()) : 0,
                sale_units: sale_units ?? '',
            };

            // console.log(product_details)
            // console.log(updatedProduct)
            var updatedStock = product_details.warehouse_quantity;

            if (product_details.product_type != 'service') {

                if (updatedProduct.sale_units.parent_id != 0) {
                    // big to small unit
                    updatedStock = eval(
                        `${product_details.warehouse_quantity}${product_details.unit.operator}${updatedProduct.sale_units.operator_value}`
                    );

                } else {
                    // small to large unit conversion
                    updatedStock = product_details.warehouse_quantity;
                }
            }
            updatedProduct.quantity = updatedStock;



            let price = parseFloat(updatedProduct.price);
            // if (updatedProduct.tax_type == 2) {
            //     price += updatedProduct.order_tax;
            // } else if (updatedProduct.tax_type == 1) {
            //     price -= price * (updatedProduct.order_tax / (100 + updatedProduct.order_tax));
            // } else {
            //     price = price;
            // }

            if (updatedProduct.discount_type == 'fixed') {
                price -= updatedProduct.discount ? updatedProduct.discount : 0;
            } else if (updatedProduct.discount_type == 'percentage') {
                price -= price * (updatedProduct.discount / 100);
            } else {
                price = price;
            }

            updatedProduct.price = price.toFixed(2);

            // const rowProduct = JSON.parse($(this).attr('data-product-item'));
            console.log(updatedProduct)
            $('#mainTable tbody tr').each(function() {

                const rowProductId = parseInt($(this).find('td:nth-child(9)').find('a').data('product').id);
                if (rowProductId === updatedProduct.id) {
                    // console.log(updatedProduct);
                    // Update the product details in the table
                    $(this).find('td:nth-child(4)').text(updatedProduct.price);
                    $(this).find('td:nth-child(5)').html(
                        `<span class="badges bg-darkwarning p-1" data-converted-unit="${updatedProduct.sale_units?.id ? updatedProduct.sale_units.id : ''}">${updatedStock ?? product_details.quantity}${updatedProduct.sale_units?.short_name ? updatedProduct.sale_units.short_name : ''}</span>`
                    );
                    $(this).find('td:nth-child(7)').text(updatedProduct.discount ? updatedProduct.discount :
                        0);
                    // $(this).find('td:nth-child(8)').text(updatedProduct.order_tax);
                    // for sub total
                    const quantity = $(this).find('td:nth-child(6)').find('input').val();
                    const subtotal = parseInt(quantity) * parseFloat(updatedProduct.price);
                    $(this).find('td:nth-child(8)').text(subtotal.toFixed(2));

                    var mergedArray = {};

                    // Merge the arrays manually
                    for (var key in product_details) {
                        // Check if the key is "quantity"
                        if (key === 'quantity') {
                            // If it is, retain the value from the first array
                            mergedArray[key] = product_details[key];
                        } else {
                            // If not, check if the key exists in the second array
                            // If it does, use the value from the second array; otherwise, use the value from the first array
                            mergedArray[key] = updatedProduct.hasOwnProperty(key) ? updatedProduct[key] :
                                product_details[key];
                        }
                    }

                    console.log(mergedArray);

                    $(this).find('td:nth-child(9)').find('a').attr('data-product', JSON.stringify(
                        mergedArray));


                    $('#exampleModalToggle .btn-close').trigger('click');

                    // $('#exampleModalToggle').hide();
                    // return false;
                }
            });
            calculateTotal();

        });

        $(document).ready(function() {
            $('#createSaleForm').on('submit', function(e) {
                e.preventDefault();
                let amountReceived = 0;
                if ($('#payment_status').val() == 'partial' || $('#payment_status').val() == 'paid') {
                    // amountReceived = parseFloat($('#amount_recieved').val());
                    $('.amount-received').each(function() {
                        amountReceived += parseFloat($(this).val()) || 0;
                    });
                } else {

                }
                let paymentMethods = [];

                // Collect payment methods
                $('#sortable-table .card').each(function() {
                    let method = {
                        method: $(this).find('input[type="hidden"][name="payment"]').val(),
                        // bank_account: $(this).find('select[name="bank_account"]').val() ?? null,
                        customer_card: $(this).find('select[name="customer_card"]').val() ??
                            null,
                        amount_received: parseFloat($(this).find(
                            'input[name="amount_received"]').val()) ?? 0,
                        store_balance: parseFloat($(this).find('input[name="store_balance"]')
                            .val()) ?? 0,
                        paying_amount_fee: parseFloat($(this).find(
                            'input[name="paying_amount_fee"]').val()) ?? 0,
                    };
                    paymentMethods.push(method);
                });

                // Collect form data
                let formData = {
                    date: $(this).find('[type=date]').val(),
                    customer_id: $('#customers').val(),
                    ntn_no: $('#ntn_no').val(),
                    order_tax: $('#order_tax').val(),
                    discount: $('#discount').val(),
                    shipping: $('#shipping').val(),
                    status: $('#status').val(),
                    payment_status: $('#payment_status').val(),
                    payment_method: $('#payment_method').val() ?? 'Cash',
                    card_id: $('#card_id').val() ?? null,
                    bank_account: $('#bank_account').val() ?? null,
                    // amount_recieved: amountReceived ,
                    amount_recieved: amountReceived > parseFloat(document.getElementById('grand_total').textContent.replace('$', '')) ? parseFloat(document.getElementById('grand_total').textContent.replace('$', '')) : amountReceived,
                    amount_pay: $('#paying_amount').val(),
                    change_return: 0,
                    note: $('#notes').val(),
                    invoice_id: $('#invoice_number').text(),
                    warehouse_id: $('#warehouse_id').val(),
                    grand_total: parseFloat(document.getElementById('grand_total').textContent.replace(
                        '$', '')),

                    //amount_due will be result of grand total - amount_recieved
                    // amount_due: parseFloat(document.getElementById('grand_total').textContent.replace(
                    //     '$', '')) - amountReceived,

                    amount_due: $('#payment_status').val() == 'pending' ? parseFloat(document
                            .getElementById('grand_total').textContent.replace('$', '')) : document
                        .getElementById('due_amount').value,

                    order_items: [],
                    payment_methods: paymentMethods,
                    shipping_method: $('#shipping_method').val() ?? null,
                };

                // Collect order items
                $('#mainTable tbody tr').each(function() {
                    let item = {
                        id: $(this).find('td:nth-child(9) .item-edit').data('product').id ?? null,
                        quantity: $(this).find('td:nth-child(6) input').val(),
                        discount: $(this).find('td:nth-child(7)').text(),
                        discount_type: $(this).find('td:nth-child(9) .item-edit').data(
                            'product').discount_type,
                        tax_type: $(this).find('td:nth-child(9) .item-edit').data('product')
                            .tax_type,
                        order_tax: 0,
                        sale_unit: $(this).find('td:nth-child(5) span').data('converted-unit'),
                        price: $(this).find('td:nth-child(4)').text(),
                        subtotal: $(this).find('td:nth-child(8)').text(),
                        stock: $(this).find('td:nth-child(5)').text(),
                    };
                    if (item.quantity == 0) {
                        toastr.error("Please increment quantity!");
                        die;
                    }
                    formData.order_items.push(item);

                });


                $('#btn-text').hide();
                $('#btn-spinner').show();

                // AJAX request to server
                $.ajax({
                    url: '{{ route('sales.store') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    success: function(response) {
                        console.log(response);

                        if (response.status == 422) {
                            toastr.error('error');
                        }

                        toastr.success('Sale created successfully!');
                        window.location.href = "{{ route('sales.index') }}";
                        console.log('Success:', response);
                    },

                    error: function(xhr) {
                        // console.log(xhr)

                        if (xhr.status === 422) { // If Laravel validation fails
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                if (Array.isArray(value)) {
                                    toastr.error(value[
                                        0
                                    ]); // Display first error message for each field
                                } else {
                                    toastr.error(
                                        value
                                    ); // Display the error message directly if not an array
                                }
                            });
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Handle FedEx shipment creation errors
                            var fedExErrors = xhr.responseJSON.errors;
                            $.each(fedExErrors, function(key, value) {
                                if (typeof value === 'object') {
                                    if (value.parameterList) {
                                        // Handle parameterList errors (like "street lines cannot be null")
                                        $.each(value.parameterList, function(index,
                                            param) {
                                            toastr.error(param.value);
                                        });
                                    } else if (value.message) {
                                        // Handle direct error messages (like "Shipper State or Province code is invalid")
                                        toastr.error(value.message);
                                    }
                                } else {
                                    toastr.error(value[
                                        0
                                    ]); // Display first Laravel validation error message
                                }
                            });
                        } else {
                            toastr.error('An error occurred while processing your request.');
                        }


                    },
                    complete: function() {
                        $('#btn-text').show();
                        $('#btn-spinner').hide();
                    }
                });
            });
        });


        $(document).on('click', '.item-delete', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            calculateTotal();

            // Check if the table is empty
            if ($('#mainTable tbody tr').length === 0) {
                $('#warehouse_id').prop('disabled', false);
            }
        });


        $(document).ready(function() {

            function populateModalTable() {
                // Get the main table rows
                const mainTableRows = document.querySelectorAll('#mainTable tbody tr');

                // Select the modal table body
                const modalTableBody = document.querySelector('#modalTable tbody');

                modalTableBody.innerHTML = '';

                mainTableRows.forEach((row, index) => {
                    // Get data from specific columns
                    const productName = row.querySelector('.product_name').textContent;
                    const netUnitPrice = row.querySelector('.product_sell_price').textContent;
                    const qty = row.querySelector('.product_qty').value;
                    const price = row.querySelector('.product_price').textContent;
                    const sku = row.querySelector('.product_sku').textContent;
                    // const tax = row.querySelector('.product_tax').textContent;

                    const newRow = `
                        <tr>
                            <td>${sku}</td>
                            <td>${productName}</td>
                            <td>$${netUnitPrice}</td>
                            <td>${qty}</td>
                            <td>$${price}</td>
                        </tr>
                    `;

                    // Append the new row
                    modalTableBody.innerHTML += newRow;
                });
            }

            // function generateInvoiceId() {
            //     const timestamp = Date.now();
            //     const randomNum = Math.floor(Math.random() * 3000) + 1000;
            //     // Concatenate timestamp and random number to create the unique ID
            //     const invoiceId = `${timestamp}${randomNum}`;
            //     return invoiceId;
            // }

            function generateInvoiceId() {
                // Generate a random 6-digit number
                const randomNum = Math.floor(10000000 + Math.random() *
                    90000000); // Generates a number between 100000 and 999999
                const orderId = randomNum;
                return orderId;
            }

            function AddDetailsInModal() {
                var getOrderTax = document.getElementById('order_tax_display').innerHTML;
                var discount = document.getElementById('discount_display').innerHTML;
                var shipping = document.getElementById('shipping_display').innerHTML;
                var total = document.getElementById('grand_total').innerHTML;

                // var am_pay = document.getElementById('amount_pay').innerHTML;
                // var am_recieved = document.getElementById('amount_recieved').value;
                // let am_due = document.getElementById('change_return').innerHTML;

                var am_pay = document.getElementById('paying_amount').value;
                // var am_recieved = document.getElementById('amount_recieved').value;
                let am_recieved = 0;
                $('.amount-received').each(function() {
                    am_recieved += parseFloat($(this).val()) || 0;
                });

                // let am_due = document.getElementById('due_amount').innerHTML;
                let am_due = document.getElementById('due_amount').value;


                document.getElementById('invoice_discount').innerHTML = discount ?? 0;
                document.getElementById('invoice_amount_paid').innerHTML = am_recieved ?? 0;
                // document.getElementById('invoice_today_total_due').innerHTML = (am_due ?? "0");
                document.getElementById('invoice_today_total_due').innerHTML = $('#payment_status').val() ==
                    'pending' ? parseFloat(document.getElementById('grand_total').textContent.replace('$', '')) :
                    document.getElementById('due_amount').value;

                document.getElementById('invoice_total_due').innerHTML = (parseFloat(document.getElementById(
                    'invoice_today_total_due').innerHTML) + parseFloat(
                    document.getElementById('invoice_total_prev_balance').textContent)).toFixed(2);


                document.getElementById('invoice_payment_method').innerHTML = $('#payment_method option:selected')
                    .text();


                const invoice_id = generateInvoiceId();
                document.getElementById('invoice_number').innerHTML = invoice_id;
                let shippingValue = parseFloat(document.getElementById('shipping').value) || 0;

                // Set the innerHTML of the target element
                document.getElementById('invoice_shipping').innerHTML = shippingValue.toFixed(2);
                document.getElementById('invoice_shipping_method').innerHTML = $('#shipping_method option:selected')
                    .text();

            }

            // Call the function to populate modal table when the modal is shown
            document.getElementById('saleInvoiceModal').addEventListener('shown.bs.modal', function() {
                populateModalTable();
                AddDetailsInModal();
            });

        });

        // $(document).on('change', '#payment_method', function() {
        //     let method = $(this).val();

        //     if (method == 'Cash') {
        //         $('#account_div').show().find('select').prop('required', true);
        //         $('#card_div').hide().find('select').prop('required', false);
        //     } else if (method == 'Card') {
        //         $('#account_div').hide().find('select').prop('required', false);
        //         $('#card_div').show().find('select').prop('required', true);
        //     }
        // });

        // $(document).on('click','#gogreen-btn',function(){
        //     $('#saleInvoiceModal').modal('show');
        //     let email = $('#customers option:selected').data('email');
        // })

        // $(document).on('click', '#print-btn', function() {
        //     var printContents = document.getElementById('invoice_print').innerHTML;
        //     var originalContents = document.body.innerHTML;
        //     document.body.innerHTML = printContents;
        //     window.print();
        //     document.body.innerHTML = originalContents;
        // });
    </script>

    <script>
        // Function to print the specific section
        // function printSpecificSection() {
        //     // Get the content to print
        //     const contentToPrint = document.getElementById('invoice_print').innerHTML;

        //     // Open a new window and write the content to it
        //     const printWindow = window.open('', '_blank');
        //     printWindow.document.write('<html><head><title>Print Invoice</title>');
        //     // printWindow.document.write('link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" >');
        //     // printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">');
        //     printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/css/bootstrap.min.css') }}">');
        //     printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/css/Printstyle.css') }}">');
        //     printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/dasheets/css/style.css') }}">');
        //     printWindow.document.write('</head><body>');
        //     printWindow.document.write(contentToPrint);
        //     printWindow.document.write('</body></html>');
        //     // Print the content in the new window
        //     printWindow.print();
        // }

        // // Attach a click event listener to the print button
        // document.getElementById('print-btn').addEventListener('click', printSpecificSection);


        function printInvoice() {
            var printContents = document.getElementById('invoice_print').innerHTML;
            var originalContents = document.body.innerHTML;

            var printWindow = window.open('', '', 'height=800,width=1200');
            printWindow.document.write('<html><head><title>Invoice</title>');

            // Link to external stylesheet
            printWindow.document.write(
                '<link rel="stylesheet" type="text/css" href="{{ asset('back/assets/css/invoiceStyle.css') }}">');
            printWindow.document.write(
                '<link rel="stylesheet" type="text/css" href="{{ asset('back/assets/css/bootstrap.min.css') }}">');

            printWindow.document.write('</head><body style="margin-top:50px">');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');

            printWindow.document.close();
            printWindow.document.focus();
            printWindow.print();

            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 900);

            document.body.innerHTML = originalContents;
        }


        $(document).ready(function() {
            $('#customers, #warehouse_id').change(function() {
                var customerId = $('#customers').val();
                var warehouseId = $('#warehouse_id').val();
                $('#shipping').val(0);
                $('#shipping').trigger('input');
                $('#shipping_method').empty();
                $('#shipping_method').append(`<option value=""> Select Shipping Method </option>`);
                $('#shipping_method').append(`<option value="Store Pickup" selected>Store Pickup</option>`);
                if (customerId && warehouseId) {
                    // Make AJAX request to calculate rates
                    $('#product_code').prop('readonly', true);
                    $('#get-shipping-spinner').show();
                    $.ajax({
                        url: '{{ route('calculate-rates') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            customer_id: customerId,
                            warehouse_id: warehouseId,
                        },
                        success: function(response) {
                            // Log the entire response to inspect its structure
                            console.log(response);
                            console.log("Response Status:", response.status);

                            if (response.status === 200) {
                                console.log("yes");
                                // $('#shipping').val(response.charge);
                                // $('#shipping').trigger('input');
                                toastr.success('Shipping fee added successfully');
                                let shipping_method = $('#shipping_method');
                                shipping_method.empty();
                                shipping_method.append(
                                    `<option value="" disabled>Select Shipping Method</option>`
                                );
                                shipping_method.append(
                                    `<option value="Store Pickup" selected>Store Pickup</option>`
                                );
                                const serviceTypeNames = {
                                    'FIRST_OVERNIGHT': 'First Overnight',
                                    'PRIORITY_OVERNIGHT': 'Priority Overnight',
                                    'STANDARD_OVERNIGHT': 'Standard Overnight',
                                    'FEDEX_2_DAY': 'FedEx 2 Day',
                                    'FEDEX_EXPRESS_SAVER': 'FedEx Express Saver',
                                    'FEDEX_GROUND': 'FedEx Ground',
                                };

                                response.charges.forEach(function(method, index) {
                                    let selected = index === 0 ? 'selected' : '';
                                    let displayName = serviceTypeNames[method
                                            .serviceType] || method
                                        .serviceType; // Use the mapping or fallback to serviceType

                                    shipping_method.append(
                                        `<option value="${method.serviceType}" data-charges="${method.totalNetCharge}" >${displayName}</option>`
                                    );
                                });

                            }
                        },
                        error: function(xhr) {

                            toastr.error(
                                'shipping fee not added may be due to invalid address.');

                        },
                        complete: function() {
                            $('#product_code').prop('readonly', false);
                            $('#get-shipping-spinner').hide();

                        }
                    });
                }
            });
        });

        $(document).on('change', '#shipping_method', function() {
            if ($(this).val() === 'Store Pickup') {
                $('#shipping').val(0);
                $('#shipping').trigger('input');
                return;
            }

            let charges = $(this).find(':selected').data('charges');
            $('#shipping').val(charges);
            $('#shipping').trigger('input');
        });
    </script>
@endsection
