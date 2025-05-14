@extends('back.layout.app')
@section('title', 'Categories')
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
@endsection

@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Create Purchase</h3>
            </div>
            <form class="container-fluid" action="{{ route('purchases.store') }}" method="POST" id="createPurchaseForm">
                @csrf
                <div class="card card-shadow rounded-3 border-0 mt-5">
                    <div class="card-body">


                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Date <span
                                            class="text-danger">*</span></label>

                                    <input class="form-control subheading" type="date" value="<?php echo date('Y-m-d'); ?>" />

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Supplier <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-select subheading mt-1" required
                                        aria-label="Default select example" name="vendor_id" id="vendors">
                                        <option>Select Supplier</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}"
                                                data-phone="{{ $vendor->user->contact_no }}"
                                                data-email="{{ $vendor->user->email }}"
                                                data-name="{{ $vendor->user->name }}"
                                                data-address="{{ $vendor->user->address }}"
                                                >{{ $vendor->user->name }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{ session('selected_warehouse_id') }}"> --}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Warehouse<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" name="warehouse_id"id="warehouse_id"
                                        @if(auth()->user()->hasRole(['Cashier', 'Manager'])) disabled @endif>
                                        <option value="">Select Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}"
                                            @if(auth()->user()->hasRole(['Cashier', 'Manager'])) selected @endif>{{ $warehouse->users->name ?? ''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Product</label>
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
                            <div class="row border-bottom">
                                <div class="col-md-6 col-6">Order Tax</div>
                                <div class="col-md-6 col-6" id="order_tax_display">$0.00</div><span> (0.00%)</span>
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
                                    <input type="number" placeholder="e.g: 349887645" class="form-control subheading"
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="shipping" class="mb-1 fw-bold">Shipping </label>
                                    <input type="number" placeholder="$0.00" class="form-control subheading" id="shipping"
                                        value="0" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status" class="mb-1 fw-bold">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-select subheading" aria-label="Default select example"
                                        id="status">
                                        <option value="completed">Completed</option>
                                        <option value="pending">Pending</option>
                                        <option value="ordered">Ordered</option>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_status" class="mb-1 fw-bold">Payment Status</label>
                                    <select class="form-control form-select subheading" aria-label="Default select example"
                                        id="payment_status">
                                        <option value="paid">Paid</option>
                                        <option value="partial">Partial</option>
                                        <option value="pending" selected>Pending</option>
                                    </select>
                                </div>
                            </div> --}}
                            <input type="hidden" value="pending" id="payment_status">
                        </div>

                        <div class="row mt-2" id="purchase-calc" style="display:none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_method" class="mb-1 fw-bold">Payment Method</label>
                                    <select class="form-control form-select subheading" aria-label="Default select example"
                                        id="payment_method">
                                        <option disabled selected>Select Payment Method</option>
                                        @foreach ($payments as $payment)
                                            <option value="{{$payment->name}}">{{$payment->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bank_account" class="mb-1 fw-bold">Bank Account</label>
                                    <select class="form-control form-select subheading" aria-label="Default select example"
                                        id="bank_account" >
                                    @foreach ($bank_accounts as $bank_account)
                                        <option value="{{ $bank_account->id }}">{{ $bank_account->name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount_received" class="mb-1 fw-bold">Cash Received</label>
                                    <input type="text" placeholder="$0.00" class="form-control subheading"
                                        id="amount_received" />
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
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label for="notes" class="mb-1 fw-bold">Note</label>
                            <textarea class="form-control subheading" id="notes" placeholder="Add Note" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Input Fields End -->
                <button class="btn save-btn text-white mt-3" type="button" data-bs-target="#purchaseInvoiceModal"
                data-bs-toggle="modal" >Submit</button>

                {{-- Purchase Invoice Model --}}
                <div class="modal fade" id="purchaseInvoiceModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
                    tabindex="-1">
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
                                            <h3 class="all-adjustment text-center pb-1 text-white purchase-generated"
                                                style="width: 60%">
                                                Purchase Generated
                                            </h3>
                                            {{-- <p class="text-white mt-5">
                                                Purchase Generated against the user below for amount of $300,
                                                You can print a paper bill or
                                                <span class="go-green">GO GREEN.</span>
                                            </p>
                                            <p class="text-secondary">
                                                (Going green will send bill by SMS and Email)
                                            </p> --}}

                                            <div class="form-group text-white print-overlay">
                                                <label for="nameInput" class="mb-1 fw-bold">Vendor Name</label>
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


                                            <div class="border-bottom pb-5">
                                                <button class="btn print-btn text-white mt-3 px-3" type="button" id="print-btn">
                                                    Print
                                                </button>
                                                <input type="hidden" name="go_green" id="go_green_input" value="0">

                                            </div>

                                            <button class="btn newpurchase-btn newsale-btn text-white mt-3 px-3">
                                                <div class="spinner-border text-light spinner-border-sm ms-2 me-2" role="status"
                                                    id="btn-spinner" style="display: none">
                                                </div>
                                                <span id="btn-text">Finish Purchase</span>
                                            </button>
                                        </div>

                                        <div class="col-md-7" id="invoice_print">
                                            <section class="invoice text-center justify-content-center" id="invoice">
                                                @php
                                                    $setting = \App\Models\Setting::first();
                                                @endphp
                                                <div class="container">
                                                    <div class="card border-0 rounded-3 card-shadow my-5">
                                                        <div class="card-header bg-white border-0 p-4">
                                                            <div class="row">
                                                                <div class="col-md-6 align-items-center align-middle">
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="dasheets/img/itsol.png" class="img-fluid"
                                                                            alt="" />
                                                                        <div>
                                                                            <h4 class="all-adjustment border-0 w-100 fw-bold">
                                                                                {{ $setting->company_name ?? 'Company Name' }}
                                                                            </h4>
                                                                            <p>Company tag's line here</p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6 text-end">
                                                                    <p class="my-1">
                                                                        {{ $setting->company_phone ?? '+123456789' }}</p>
                                                                    <p class="my-1">
                                                                        {{ $setting->email ?? 'company@gmail.com' }}</p>
                                                                    <p class="m-0">
                                                                        <span class="fw-bold">TAX ID</span>
                                                                        <span id="txt_modal">123-654-789</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-4">
                                                            <div class="mt-5">
                                                                <div class="row text-start">
                                                                    <div class="col-md-2 col-3">
                                                                        <p class="m-0">Invoice:</p>
                                                                    </div>
                                                                    <div class="col-md-6 col-6">
                                                                        <p class="fw-bold m-0" id="invoice_id"></p>
                                                                        <input type="hidden" name="invoice_input_id" id="invoice_input_id">
                                                                    </div>
                                                                </div>

                                                                <div class="row text-start mt-2">
                                                                    <div class="col-md-2 col-3">
                                                                        <p class="m-0">Client:</p>
                                                                    </div>
                                                                    <div class="col-md-6 col-6">
                                                                        <p class="fw-bold m-0" id="vendorName">Vendor Name
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <div class="row text-start mt-2">
                                                                    <div class="col-md-2 col-3"></div>
                                                                    <div class="col-md-6 col-6">
                                                                        <p class="m-0" id="vendorPhone">+1 234 675 8976</p>
                                                                    </div>
                                                                </div>

                                                                <div class="row text-start mt-2">
                                                                    <div class="col-md-2 col-3"></div>
                                                                    <div class="col-md-6 col-6">
                                                                        <p class="m-0" id="vendorEmail">Vendor Email</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row text-start mt-2">
                                                                    <div class="col-md-2 col-3"></div>
                                                                    <div class="col-md-6 col-6">
                                                                        <p class="m-0" id="vendorAddress">Vendor Email</p>
                                                                    </div>
                                                                </div>

                                                                {{-- <div class="row text-start mt-2">
                                                                    <div class="col-md-2 col-3"></div>
                                                                    <div class="col-md-6 col-6">
                                                                        <p class="m-0">
                                                                            <span class="fw-bold">Tax ID</span>
                                                                            <span id="txt_modal">123-654-789</span>
                                                                        </p>
                                                                    </div>
                                                                </div> --}}

                                                                <div class="row text-start mt-2">
                                                                    <div class="col-md-2 col-3">Date:</div>
                                                                    <div class="col-md-6 col-7">
                                                                        <p class="m-0">{{ date('Y-m-d') }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="table-responsive mt-3 specific-border pb-3">
                                                                <table class="table" id="modalTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="fw-bold">#</th>
                                                                            <th class="fw-bold">Product Name</th>
                                                                            <th class="fw-bold">Net Unit Price</th>
                                                                            <th class="fw-bold">Qty</th>
                                                                            <th class="fw-bold">Price</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        <tr>
                                                                            <td class="pt-3">6</td>
                                                                            <td class="pt-3">Pineapple</td>
                                                                            <td class="pt-3">$3.00</td>
                                                                            <td class="pt-3">1 pc</td>
                                                                            <td class="pt-3">$0.00</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div class="row mt-3 pb-5 specific-border text-start">
                                                                <div class="col-md-6">
                                                                    <p>Payment:</p>
                                                                    <p class="partially-paid mt-5">Pending</p>
                                                                </div>
                                                                <div class="col-md-6">

                                                                    <div class="row border-bottom p-1">
                                                                        <div class="col-md-6 col-6">Tax</div>
                                                                        <div class="col-md-6 col-6 fw-bold text-secondary"
                                                                            id="order_tax_modal">
                                                                            $0.00 (0.00%)
                                                                        </div>
                                                                    </div>

                                                                    <div class="row border-bottom p-1">
                                                                        <div class="col-md-6 col-6">Discount</div>
                                                                        <div class="col-md-6 col-6 fw-bold text-secondary"
                                                                            id="discount_modal">
                                                                            $0.00 (0.00%)
                                                                        </div>
                                                                    </div>

                                                                    <div class="row border-bottom p-1">
                                                                        <div class="col-md-6 col-6">Shipping</div>
                                                                        <div class="col-md-6 col-6 fw-bold text-secondary"
                                                                            id="shipping_modal">
                                                                            $0.00
                                                                        </div>
                                                                    </div>
                                                                    <div class="row fw-bold p-1 specific-border">
                                                                        <div class="col-md-6 col-6">Grand Total</div>
                                                                        <div class="col-md-6 col-6 fw-bold"
                                                                            id="grand_total_modal">
                                                                            $0.00
                                                                        </div>
                                                                    </div>

                                                                    <div class="row border-bottom mt-4 p-1">
                                                                        <div class="col-md-6 col-6">
                                                                            Amount Received
                                                                        </div>
                                                                        <div class="col-md-6 col-6 fw-bold text-secondary"
                                                                            id="amount_received_modal">
                                                                            $0.00
                                                                        </div>
                                                                    </div>
                                                                    {{-- <div class="row border-bottom p-1">
                                                                        <div class="col-md-6 col-6">
                                                                            Amount Returned
                                                                        </div>
                                                                        <div class="col-md-6 col-6 fw-bold text-secondary"
                                                                            id="change_return_modal">
                                                                            $0.00
                                                                        </div>
                                                                    </div> --}}
                                                                    <div class="row fw-bold p-1">
                                                                        <div class="col-md-6 col-6">
                                                                            Amount Pending
                                                                        </div>
                                                                        <div class="col-md-6 col-6 fw-bold"
                                                                            id="pending_amount_modal">
                                                                            $0.00
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <p class="my-5 text-start">
                                                                Signature: ______________________
                                                            </p>
                                                        </div>
                                                        <div class="card-footer invoice-footer border-0 m-0 p-4">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center align-middle p-3">
                                                                <p class="fw-bold m-0">Thank You!</p>
                                                                <p class="subheading m-0">www.website.com</p>
                                                            </div>
                                                        </div>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tax_type" class="mb-1 fw-bold">Tax Type</label>
                                        <select class="form-control form-select subheading"
                                            aria-label="Default select example" id="tax_type">
                                            <option value="" disabled selected>Select Tax Type</option>
                                            <option value="1">Inclusive</option>
                                            <option value="2">Exclusive</option>
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
                                        <label for="sale_unit_item" class="mb-1 fw-bold">Purchase Unit</label>
                                        <select name="sale_unit_item" id="sale_unit_item"
                                            class="form-control form-select subheading"
                                            aria-label="Default select example">
                                            <option value="" disabled selected>Select Purchase Unit</option>
                                            @foreach($units as $unit)
                                                <option value="{{$unit->id ?? ''}}" data-sale-unit-item="{{$unit}}"> {{$unit->name}} </option>
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
                        if (row.querySelector('td:nth-child(3)').textContent === ui.item.product.sku) {
                            // Select the input element
                            let qtyInput = $(row).find('td:nth-child(6) .qty-input');

                            // Get the current value of the input
                            let currentValue = parseInt(qtyInput.val());

                            // Increment the value and set it to the input
                            qtyInput.val(currentValue + 1).change();

                            isDuplicate = true;
                        }
                    });
                    $('#warehouse_id').prop('disabled', hasRow);
                    if (!isDuplicate) {
                        let quantity = ui.item.product.warehouse_quantity;
                        if(ui.item.product.product_type != 'service'){
                            if(ui.item.product.product_unit != ui.item.product.purchase_unit){
                                if(ui.item.product.unit.parent_id == 0){
                                    quantity = eval(`${ui.item.product.warehouse_quantity}${ui.item.product.unit.operator}${ui.item.product.purchase_unit.operator_value} `);
                                }
                            }
                        }
                        // Append row code here
                        // Assuming data contains product name, code, etc.
                        row.innerHTML = `
                            <td class="align-middle">${prodCount}</td>
                            <td class="product_name align-middle ">${ui.item.product.name}</td>
                            <td class=" align-middle ">${ui.item.product.sku}</td>
                            <td class="product_sell_price align-middle ">${ui.item.product.sell_price}</td>
                            <td class="align-middle">
                                <span class="badges bg-darkwarning p-1" product_stock data-converted-unit="${ui.item.product.purchase_unit?.id ? ui.item.product.purchase_unit.id : ''}">${quantity}${ui.item.product.purchase_unit?.short_name ? ui.item.product.purchase_unit.short_name : '' }</span>
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
                if(!$('#warehouse_id').val()){
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
                    }
                });

            }



            let amountReceivedInput = document.getElementById('amount_received');
            let amountPayInput = document.getElementById('amount_pay');

            // Add event listeners
            amountReceivedInput.addEventListener('input', calculateChangeReturn);
            amountPayInput.addEventListener('input', calculateChangeReturn);

            // function calculateChangeReturn() {
            //     // Parse the input values to floats
            //     let amountReceived = parseFloat(amountReceivedInput.value);
            //     let amountPay = parseFloat(amountPayInput.value);
            //     // Get the payment status
            //     let paymentStatus = document.getElementById('payment_status').value;

            //     // If the payment status is 'partial' and amountPay is greater than amountReceived, show an error and return
            //     if (paymentStatus === 'partial' && amountPay > amountReceived) {
            //         alert('Paying amount cannot be greater than received amount for partial payments');
            //         amountPayInput.value = amountReceived;
            //         return;
            //     }
            //     // Calculate the change return
            //     let changeReturn = amountReceived - amountPay;

            //     // Check if the change return is a valid number (it will be NaN if either input field is empty or non-numeric)
            //     if (!isNaN(changeReturn)) {
            //         // Update the change_return field
            //         document.getElementById('change_return').textContent = changeReturn.toFixed(2);
            //     }
            // }
            function calculateChangeReturn() {
                // Parse the input values to floats
                let amountRecieved = parseFloat(amountReceivedInput.value);
                let amountPay = parseFloat(amountPayInput.value);
                // Get the payment status
                let paymentStatus = document.getElementById('payment_status').value;

                if (paymentStatus === 'partial' && amountPay < amountRecieved) {
                    alert('Received amount cannot be greater than paying amount for partial payments');
                    amountReceivedInput.value = 0;
                    document.getElementById('change_return').textContent = amountPay.toFixed(2);

                    return;
                }

                // Calculate the change return
                // let changeReturn = amountRecieved - amountPay;
                let changeReturn = amountPay - amountRecieved ;

                // Check if the change return is a valid number (it will be NaN if either input field is empty or non-numeric)
                if (!isNaN(changeReturn)) {
                    // Update the change_return field
                    document.getElementById('change_return').textContent = changeReturn.toFixed(2);
                }
            }

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

                const price = $(this).closest('tr').find('td:nth-child(4)').text();
                const subtotal = parseInt(quantity) * parseFloat(price);
                $(this).closest('tr').find('td:nth-child(8)').text(subtotal.toFixed(2));
                calculateTotal();
            });

            // Event listeners for discount, shipping, and order tax inputs
            $('#discount, #shipping, #order_tax').on('input', calculateTotal);


            // Get the select element
            const vendorsSelect = document.getElementById('vendors');

            // Add event listener for change event
            vendorsSelect.addEventListener('change', function() {
                // Get the selected option
                const selectedOption = this.options[this.selectedIndex];

                // Get data attributes from the selected option
                const name = selectedOption.dataset.name;
                const phone = selectedOption.dataset.phone;
                const email = selectedOption.dataset.email;
                const address = selectedOption.dataset.address;

                // Update the modal with vendor details
                document.getElementById('nameInput').value = name;
                document.getElementById('phoneInput').value = phone;
                document.getElementById('emailInput').value = email;
                document.getElementById('vendorName').innerHTML = name;
                document.getElementById('vendorPhone').innerHTML = phone;
                document.getElementById('vendorEmail').innerHTML = email;
                document.getElementById('vendorAddress').innerHTML = address ?? null;
                // document.getElementById('purchasesTaxIdInput').value = purchasesTaxId;
            });

        });

        function calculateTotal() {
            let subtotal = 0;
            $('.table tbody tr').each(function() {
                subtotal += parseFloat($(this).find('td:nth-child(8)').text() || 0);
            });

            // Assume `orderTax` is a percentage value from an input field

            const orderTax = parseFloat($('#order_tax').val() == '' ? 0 : $('#order_tax').val()) / 100;
            const taxAmount = subtotal * orderTax;

            const discountValue = parseFloat($('#discount').val() == '' ? 0 : $('#discount').val());

            const shipping = parseFloat($('#shipping').val() == '' ? 0 : $('#shipping').val());

            const grandTotal = subtotal + taxAmount - discountValue + shipping;
            const discountPercentage = (discountValue / subtotal) * 100;

            // Update the UI
            let orderTextPercentage = orderTax * 100;
            $('#order_tax_display').text(`$${taxAmount.toFixed(2)} (${orderTextPercentage.toFixed()}%)`);
            // $('#discount_display').text(`$${discountValue.toFixed(2)}`);
            $('#discount_display').text(`$${discountValue.toFixed(2)} (${discountPercentage.toFixed(2)}%)`);

            $('#shipping_display').text(`$${shipping.toFixed(2)}`);
            $('#grand_total').text(`$${grandTotal.toFixed(2)}`);
            if ($('#payment_status').val() == 'paid') {
                $('#amount_pay').val(grandTotal);
                $('#amount_received').val(grandTotal);
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
            $('#exampleModalToggle #discount_type').val(product.discount_type ?? "fixed");
            $('#exampleModalToggle #discount_item').val(product.discount ?? '0.00');
            $('#exampleModalToggle #hidden_id').val(product.id);
            if(product.product_type == 'service' ){
                $('#exampleModalToggle #unit_section').css('display','none');
            }
            else
            {
                $('#exampleModalToggle #unit_section').css('display','block');
                $('#exampleModalToggle #sale_unit_item').val(product.purchase_unit?.id);
            }

            product.discount = 0;
            product.discount_type = "fixed";
            // add product into the data-product-item attribute of #saveChangesButton
            $('#saveChangesButton').attr('data-product-item', JSON.stringify(product));
        });

        $('#saveChangesButton').click(function() {
            // Retrieve and parse updated product data from modal
            // const product_details = JSON.parse($('#saveChangesButton').attr('data-product-item'));
            const product_details = JSON.parse($(this).attr('data-product-item'));

            let purchase_unit;
            if(product_details.product_type != 'service'){
                const selectedOption = $('#exampleModalToggle #sale_unit_item option:selected');
                purchase_unit = selectedOption.data('sale-unit-item');
            }

            const updatedProduct = {
                // existing code to retrieve data...
                discount_type: $('#exampleModalToggle #discount_type').val(),
                discount: parseFloat($('#exampleModalToggle #discount_item').val()) ? parseFloat($('#exampleModalToggle #discount_item').val()) : 0,
                id: parseFloat($('#exampleModalToggle #hidden_id').val()),
                price: parseFloat($('#exampleModalToggle #product_price').val()) ? parseFloat($('#exampleModalToggle #product_price').val()) : 0,
                purchase_unit: purchase_unit ?? '',
            };
            console.log('product_details')
            console.log(product_details)

            var updatedStock = product_details.warehouse_quantity;
            if (product_details.product_type != 'service') {

                if(updatedProduct.purchase_unit.parent_id != 0){
                    // big to small unit
                    updatedStock = eval(`${product_details.warehouse_quantity}${product_details.unit.operator}${updatedProduct.purchase_unit.operator_value}`);
                }
                else
                {
                    // small to large unit conversion
                    updatedStock = product_details.warehouse_quantity;
                }
            }
            updatedProduct.quantity = updatedStock;

            let price = parseFloat(updatedProduct.price);

            if (updatedProduct.discount_type == 'fixed') {
                price -= updatedProduct.discount ? updatedProduct.discount : 0;
            } else if (updatedProduct.discount_type == 'percentage') {
                price -= price * (updatedProduct.discount / 100);
            }else{
                price = price;
            }

            updatedProduct.price = price.toFixed(2);

            $('#mainTable tbody tr').each(function() {

                const rowProductId = parseInt($(this).find('td:nth-child(9)').find('a').data('product').id);
                if (rowProductId === updatedProduct.id) {
                    // console.log(updatedProduct);
                    // Update the product details in the table
                    $(this).find('td:nth-child(4)').text(updatedProduct.price);
                    $(this).find('td:nth-child(5)').html(`<span class="badges bg-darkwarning p-1" data-converted-unit="${updatedProduct.purchase_unit?.id ? updatedProduct.purchase_unit.id : ''}">${updatedStock ?? product_details.quantity}${updatedProduct.purchase_unit?.short_name ? updatedProduct.purchase_unit.short_name : ''}</span>` );
                    $(this).find('td:nth-child(7)').text(updatedProduct.discount ? updatedProduct.discount : 0);
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
                            mergedArray[key] = updatedProduct.hasOwnProperty(key) ? updatedProduct[key] : product_details[key];
                        }
                    }

                    console.log(mergedArray);

                    $(this).find('td:nth-child(9)').find('a').attr('data-product', JSON.stringify(mergedArray));


                    $('#exampleModalToggle .btn-close').trigger('click');

                    // $('#exampleModalToggle').hide();
                    // return false;
                    }
                });

        });

        $(document).ready(function() {
            $('#createPurchaseForm').on('submit', function(e) {
                e.preventDefault();
                let amountReceived = 0;
                if($('#payment_status').val() == 'partial' || $('#payment_status').val() == 'paid'){
                    amountReceived = parseFloat($('#amount_received').val());
                }

                // Collect form data
                let formData = {
                    date: $(this).find('[type=date]').val(),
                    vendor_id: $('#vendors').val(),
                    ntn_no: $('#ntn_no').val(),
                    order_tax: $('#order_tax').val(),
                    discount: $('#discount').val(),
                    shipping: $('#shipping').val(),
                    status: $('#status').val(),
                    payment_status: $('#payment_status').val(),
                    payment_method: $('#payment_method').val(),
                    bank_account: $('#bank_account').val(),
                    amount_received: amountReceived,
                    change_return: 0,
                    amount_pay: $('#amount_pay').val(),
                    notes: $('#notes').val(),
                    invoice_id: $('#invoice_input_id').val(),
                    warehouse_id: $('#warehouse_id').val(),
                    grand_total: parseFloat(document.getElementById('grand_total').textContent.replace(
                        '$', '')),
                    // amount_due: parseFloat(document.getElementById('grand_total').textContent.replace(
                    //     '$', '')) - parseFloat($('#amount_pay').val()) || 0,
                    amount_due: parseFloat(document.getElementById('grand_total').textContent.replace('$', ''))  - amountReceived,

                    order_items: []
                };

                // Collect order items
                $('#mainTable tbody tr').each(function() {
                    let item = {
                        id: $(this).find('td:nth-child(9) .item-edit').data('product').id,
                        quantity: $(this).find('td:nth-child(6) input').val(),
                        discount: $(this).find('td:nth-child(7)').text(),
                        discount_type: $(this).find('td:nth-child(9) .item-edit').data('product').discount_type,
                        tax_type: 1,
                        order_tax: 0,
                        purchase_unit: $(this).find('td:nth-child(5) span').data('converted-unit'),
                        price: $(this).find('td:nth-child(4)').text(),
                        subtotal: $(this).find('td:nth-child(8)').text(),
                        stock: $(this).find('td:nth-child(5)').text(),
                    };
                    formData.order_items.push(item);
                });

                $('#btn-text').hide();
                $('#btn-spinner').show();

                // AJAX request to server
                $.ajax({
                    url: '{{ route("purchases.store") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        // Handle success (maybe redirect or show a success message)
                        window.location.href = "{{ route('purchases.index') }}";


                        console.log('Success:', response);
                    },
                    error: function(error) {
                        // Handle error
                        console.log('Error:', error);
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


                    const newRow = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${productName}</td>
                            <td>${netUnitPrice}</td>
                            <td>${qty}</td>
                            <td>${price}</td>
                        </tr>
                    `;

                    // Append the new row
                    modalTableBody.innerHTML += newRow;
                });
            }

            function generateInvoiceId() {
                const timestamp = Date.now();
                const randomNum = Math.floor(Math.random() * 9000) + 1000;
                // Concatenate timestamp and random number to create the unique ID
                const invoiceId = `INV-${timestamp}-${randomNum}`;
                return invoiceId;
            }


            function AddDetailsInModal() {
                var getOrderTax = document.getElementById('order_tax_display').innerHTML;
                var discount = document.getElementById('discount_display').innerHTML;
                var shipping = document.getElementById('shipping_display').innerHTML;
                var total = document.getElementById('grand_total').innerHTML;

                var am_pay = document.getElementById('amount_pay').innerHTML;
                var am_received = document.getElementById('amount_received').value;
                var return_pay = document.getElementById('change_return').innerHTML;


                document.getElementById('order_tax_modal').innerHTML = getOrderTax;
                document.getElementById('discount_modal').innerHTML = discount;
                document.getElementById('shipping_modal').innerHTML = shipping;
                document.getElementById('grand_total_modal').innerHTML = total;

                // document.getElementById('amount_pay_modal').innerHTML = am_pay;
                // var payment_status = document.getElementById('payment_status').value;
                // if (payment_status == 'partial' || payment_status == 'pending') {
                //     document.getElementById('pending_amount_modal').innerHTML = total;
                // }
                var payment_status = document.getElementById('payment_status').value;
                if (payment_status == 'partial' || payment_status == 'pending') {
                    // document.getElementById('pending_amount_modal').innerHTML = total;
                    // document.getElementById('pending_amount_modal').innerHTML = `$${$('#grand_total').text().replace('$', '') - $('#amount_received').val()}`;
                    document.getElementById('pending_amount_modal').innerHTML = `$${($('#grand_total').text().replace('$', '') - $('#amount_received').val()).toFixed(2)}`;
                }
                else
                {
                    document.getElementById('pending_amount_modal').innerHTML = 0.00;
                }
                // document.getElementById('payment_status').innerHTML = am_received;
                document.getElementById('amount_received_modal').innerHTML = am_received;
                // document.getElementById('change_return_modal').innerHTML = return_pay;
                const invoice_id = generateInvoiceId();
                document.getElementById('invoice_id').innerHTML = invoice_id;
                document.getElementById('invoice_input_id').value = invoice_id;
                let ntn = document.getElementById('ntn_no').value ;
                document.getElementById('txt_modal').innerHTML = ntn;
            }

            // Call the function to populate modal table when the modal is shown
            document.getElementById('purchaseInvoiceModal').addEventListener('shown.bs.modal', function() {
                populateModalTable();
                AddDetailsInModal();
            });


        });


    </script>

<script>
    // Function to print the specific section
    function printSpecificSection() {
        // Get the content to print
        const contentToPrint = document.getElementById('invoice_print').innerHTML;

        // Open a new window and write the content to it
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print Invoice</title>');
        // printWindow.document.write('link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" >');
        // printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">');
        printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/css/bootstrap.min.css') }}">');
        printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/css/Printstyle.css') }}">');
        printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/dasheets/css/style.css') }}">');
        printWindow.document.write('</head><body>');
        printWindow.document.write(contentToPrint);
        printWindow.document.write('</body></html>');
        // Print the content in the new window
        printWindow.print();
    }

    // Attach a click event listener to the print button
    document.getElementById('print-btn').addEventListener('click', printSpecificSection);
</script>
@endsection
