@extends('back.layout.app')
@section('title', 'Pos')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <style>
        /* For Webkit browsers (Chrome, Safari) */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f2f2f2;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #bbbbbb;
            border-radius: 5px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #4c49e3;
        }

        /* Corner */
        ::-webkit-scrollbar-corner {
            background: #f2f2f2;
        }
    </style>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-md-6 mt-2">
                    <div class="card rounded-3 border-0 card-shadow h-100 p-3">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Customer <span
                                        class="text-danger">*</span></label>
                                <select class="form-control form-select subheading mt-1" aria-label="Default select example"
                                    name="customer_id" id="customers">
                                    <option value="">Select customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" data-phone="{{ $customer->user->contact_no }}"
                                            data-email="{{ $customer->user->email }}"
                                            data-name="{{ $customer->user->name }}"
                                            data-discount="{{ $customer->tier->discount ?? 0 }}"
                                            data-saved-cards="{{ json_encode($customer->user->savedCards) }}"
                                            data-balance="{{ $customer->balance ?? 0 }}"
                                            data-due="{{ $customer->total_due_invoice ?? 0 }}"
                                            data-accounts="{{ $customer->accounts ?? '' }}">
                                            {{ $customer->user->name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="warehouse_id" id="warehouse_id"
                                    value="{{ session('selected_warehouse_id') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="warehouses" class="mb-1 fw-bold">Warehouse</label>
                                <select class="form-control form-select subheading mt-1" required
                                    aria-label="Default select example" name="warehouse_id" id="warehouses"
                                    @if (auth()->user()->hasRole(['Cashier', 'Manager'])) disabled @endif>
                                    <option value="">Select Warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}"
                                            @if (auth()->user()->hasRole(['Cashier', 'Manager']) && auth()->user()->warehouse_id == $warehouse->id) selected
                                            @elseif($loop->iteration == 1)
                                                selected @endif>
                                            {{ $warehouse->users->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <audio src="{{ asset('back/assets/audio/mixkit-cool-interface-click-tone-2568.wav') }}"
                                id="click-audio" preload="auto" style="display:none;"></audio>

                            <div class="table-responsive mt-4 border-top pt-4" style="max-height: 200px;">
                                <table class="table text-center">
                                    <thead class="fw-bold">
                                        <tr>
                                            <th class="align-middle">Product</th>
                                            <th class="align-middle">Price</th>
                                            {{-- <th class="align-middle">Stock</th> --}}
                                            <th class="align-middle">Qty</th>
                                            <th class="align-middle">Subtotal</th>
                                            <th class="align-middle">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center save-btn total-pay text-white p-2 mt-5">
                                <p class="m-0">Total Payable: <span id="grand_total">$0</span></p>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="form-group fw-bold">
                                        <label for="order_tax">Tax</label>
                                        <div class="input-group mt-2">
                                            <input type="number" placeholder="0%" class="form-control subheading"value="0"
                                                id="order_tax" name="order_tax" />
                                            <span class="input-group-text subheading" id="basic-addon2"><i
                                                    class="bi bi-percent"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group fw-bold">
                                        <label for="discount">Discount (%)</label>
                                        <div class="input-group mt-2">
                                            <input type="number" placeholder="e.g 10%"
                                                class="form-control subheading"value="0" id="discount" name="discount" />
                                            <span class="input-group-text subheading" id="basic-addon2"><i
                                                    class="bi bi-percent"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group fw-bold">
                                        <label for="shipping">Shipping</label>
                                        <div class="input-group mt-2">
                                            <input type="number" placeholder="$0.00" class="form-control subheading"
                                                id="shipping" value="0" name="shipping" />
                                            <span class="input-group-text subheading" id="basic-addon2"><i
                                                    class="bi bi-currency-dollar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn delete-btn text-white mt-5 me-2 delete-btn">
                                <i class="bi bi-power me-2"></i>Reset
                            </button>
                            <button type="button" class="btn print-btn text-white mt-5 px-3" id="payNowModalBtn">
                                <i class="fa-solid fa-cart-shopping me-2"></i>Pay Now
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                    <div class="card border-0 rounded-3 card-shadow h-100">
                        <div class="card-body product-scroll">
                            <ul class="nav nav-pills mb-3 row" id="pills-tab" role="tablist">
                                <div class="col-md-12 mt-1">
                                    <div class="position-relative" id="searchContainer">
                                        <div class="input-search position-relative">
                                            <input type="text" class="form-control subheading"
                                                placeholder="Product Code / Name" id="product_code"
                                                name="product_code" />
                                            <div id="suggestionsContainer"></div>
                                            <span class="fa fa-search search-icon text-secondary"></span>
                                        </div>

                                    </div>
                                </div>
                                <li class="nav-item col-md-6 mt-3" role="presentation">
                                    <button class="shopping-items nav-link active me-2 w-100 border-theme"
                                        id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                                        type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                                        <i class="fa-solid fa-layer-group"></i>
                                        List of Category
                                    </button>
                                </li>
                                <li class="nav-item col-md-6 mt-3" role="presentation">
                                    <button class="nav-link shopping-items w-100 border-theme" id="pills-profile-tab"
                                        data-bs-toggle="pill" data-bs-target="#pills-profile" type="button"
                                        role="tab" aria-controls="pills-profile" aria-selected="false">
                                        <i class="fa-solid fa-building-columns"></i>
                                        Brands
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                    aria-labelledby="pills-home-tab" tabindex="0">

                                    <div class="row" id="mySpinner" style="display: none">
                                        <div class="d-flex justify-content-center align-items-center"
                                            style="height: 350px;">
                                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"
                                                role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="product-listing">
                                        @forelse ($products as $product)
                                            <div class="col-md-3 col-6 mt-2 product-item"
                                                data-product="{{ $product }}">
                                                <div class="card">
                                                    <button class="btn text-decoration-none">
                                                        @if (isset($product->images[0]['img_path']))
                                                            <img src="{{ asset('/storage' . $product->images[0]['img_path']) }}"
                                                                alt="No" class="w-100" style=" max-height: 130px">
                                                        @else
                                                            <img src="{{ asset('back/assets/image/no-image.png') }}"
                                                                alt="" class="w-100"
                                                                style=" max-height: 130px" />
                                                        @endif
                                                        <h4 class="text-center pt-2 heading text-dark">
                                                            {{ $product->name ?? '' }}
                                                        </h4>
                                                        <div class="d-flex justify-content-between">
                                                            <p class="text-dark">${{ $product->sell_price ?? '' }}</p>
                                                            <a href="#" class="text-dark"><i
                                                                    class="fa-solid fa-cart-shopping"></i></a>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                        @endforelse

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                    aria-labelledby="pills-profile-tab" tabindex="0">
                                    <div class="row" id="product-listing">
                                        @forelse ($productByBrand as $brand)
                                            @forelse ($brand as $product)
                                                <div class="col-md-3 col-6 mt-2 product-item"
                                                    data-product="{{ $product }}">
                                                    <div class="card">
                                                        <button class="btn text-decoration-none">
                                                            @if (isset($product->images[0]['img_path']))
                                                                <img src="{{ asset('/storage' . $product->images[0]['img_path']) }}"
                                                                    alt="No" class="w-100"
                                                                    style=" max-height: 130px">
                                                            @else
                                                                <img src="{{ asset('back/assets/image/no-image.png') }}"
                                                                    alt="" class="w-100"
                                                                    style=" max-height: 130px" />
                                                            @endif
                                                            <h4 class="text-center pt-2 heading text-dark">
                                                                {{ $product->name ?? '' }}
                                                            </h4>
                                                            <div class="d-flex justify-content-between">
                                                                <p class="text-dark">${{ $product->sell_price ?? '' }}</p>
                                                                <a href="#" class="text-dark"><i
                                                                        class="fa-solid fa-cart-shopping"></i></a>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                            @empty
                                            @endforelse
                                        @empty

                                        @endforelse

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pay Now Modal -->

            <!-- Bootstrap Modal -->
            <div class="modal fade" id="payNowModal" tabindex="-1" aria-labelledby="payNowModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="payNowModalLabel">Create Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h3 class=" text-center my-4" id="customerName"></h3>
                            <form class="form">
                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-12"><span>
                                                    <fieldset class="form-group" id="__BVID__221">
                                                        <legend tabindex="-1"
                                                            class="bv-no-focus-ring col-form-label pt-0"
                                                            id="__BVID__221__BV_label_">Received Amount *
                                                        </legend>
                                                        <div><input type="text" placeholder="Received Amount"
                                                                class="form-control" label="Received_Amount"
                                                                aria-describedby="Received_Amount-feedback"
                                                                id="recieve_amount" name="recieve_amount">
                                                        </div>
                                                    </fieldset>
                                                </span></div>
                                            <div class="col-sm-12 col-md-12 col-lg-12"><span>
                                                    <fieldset class="form-group" id="__BVID__224">
                                                        <legend tabindex="-1"
                                                            class="bv-no-focus-ring col-form-label pt-0"
                                                            id="__BVID__224__BV_label_">Paying Amount *
                                                        </legend>
                                                        <div><input type="text" placeholder="Paying Amount"
                                                                class="form-control" label="Paying_Amount"
                                                                aria-describedby="Paying_Amount-feedback" id="pay_amount"
                                                                name="pay_amount" disabled>
                                                        </div>
                                                    </fieldset>
                                                </span></div>
                                            <div class="col-sm-12 col-md-12 col-lg-12"><label>Due Amount
                                                    :</label>
                                                <p class="change_amount">0.00</p>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-12"><label>Return Amount
                                                    :</label>
                                                <p class="return_amount">0.00</p>
                                            </div>
                                        </div> --}}

                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-12">
                                                <span>
                                                    <fieldset class="form-group" id="__BVID__221">
                                                        <legend tabindex="-1"
                                                            class="bv-no-focus-ring col-form-label pt-0"
                                                            id="__BVID__221__BV_label_">
                                                            Received Amount *
                                                        </legend>
                                                        <div>
                                                            <input type="text" placeholder="Received Amount"
                                                                class="form-control" label="Received_Amount"
                                                                aria-describedby="Received_Amount-feedback"
                                                                id="recieve_amount" name="recieve_amount">
                                                        </div>
                                                    </fieldset>
                                                </span>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-12">
                                                <span>
                                                    <fieldset class="form-group" id="__BVID__224">
                                                        <legend tabindex="-1"
                                                            class="bv-no-focus-ring col-form-label pt-0"
                                                            id="__BVID__224__BV_label_">
                                                            Paying Amount *
                                                        </legend>
                                                        <div>
                                                            <input type="text" placeholder="Paying Amount"
                                                                class="form-control" label="Paying_Amount"
                                                                aria-describedby="Paying_Amount-feedback" id="pay_amount"
                                                                name="pay_amount" readonly>
                                                        </div>
                                                    </fieldset>
                                                </span>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-12">
                                                <label>Due Amount:</label>
                                                <p class="change_amount" id="change_amount">0.00</p>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-12">
                                                <label>Return Amount:</label>
                                                <p class="return_amount">0.00</p>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="col-md-6">
                                        <div class="card"><!----><!---->
                                            <div class="card-body"><!----><!---->
                                                <div class="list-group">
                                                    <div
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Total Products
                                                        <span class="badge bg-primary badge-pill">1</span>
                                                    </div>
                                                    <div
                                                        class="list-group-item  d-flex justify-content-between align-items-center">
                                                        Order Tax
                                                        <span class="font-weight-bold" id="order-tax-pay">$ 0.00
                                                            (0%)</span>
                                                    </div>
                                                    <div
                                                        class="list-group-item d-flex  justify-content-between align-items-center">
                                                        Discount
                                                        <span class="font-weight-bold" id="discount-pay">$ 0.00</span>
                                                    </div>
                                                    <div
                                                        class="list-group-item d-flex  justify-content-between align-items-center">
                                                        Shipping
                                                        <span class="font-weight-bold" id="shipping-pay">$ 0.00</span>
                                                    </div>
                                                    <div
                                                        class="list-group-item d-flex  justify-content-between align-items-center">
                                                        Total Payable
                                                        <span class="font-weight-bold" id="total-pay">$ 0.00</span>
                                                    </div>
                                                </div>
                                            </div><!----><!---->
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="paymentChoice">Payment Choice *</label>
                                            <select class="form-control" id="paymentChoice" name="payment_method">
                                                <option value="" selected>Select Payment Method</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Card">Card</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="accountDiv" style="display: none">
                                        <!-- Account -->
                                        <div class="form-group">
                                            <label for="accountChoice">Account</label>
                                            <select class="form-control" id="accountChoice" name="account_id">
                                                <option value="">Choose Account</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6" style="display: none" id="cardDiv">
                                        <div class="form-group">
                                            <label for="card_id">Choose Customer Card</label>
                                            <select class="form-control" id="card_id" name="card_id">
                                                <option value="">Choose Card</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Payment Notes -->
                                        <div class="form-group">
                                            <label for="paymentNotes">Payment Notes</label>
                                            <textarea class="form-control" id="paymentNotes" name="payment_notes" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Sale Notes -->
                                        <div class="form-group">
                                            <label for="saleNotes">Sale Notes</label>
                                            <textarea class="form-control" id="saleNotes" name="sale_notes" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>


                                <!-- Submit Button -->
                                <button type="submit" id="posFormBtn" class="btn btn-primary save-btn mt-4">
                                    <div class="spinner-border text-light spinner-border-sm ms-2 me-2" role="status"
                                        id="btn-spinner" style="display: none">
                                    </div>
                                    <span id="btn-text">Submit</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Bootstrap Modal -->
            <!-- Modal -->
            <div class="modal fade" id="exampleModalToggle" tabindex="-1" aria-labelledby="exampleModalToggleLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title product-title" id="exampleModalToggleLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
                                            <label for="order_tax_item" class="mb-1 fw-bold">Order Tax</label>
                                            <input type="number" class="form-control subheading" id="order_tax_item"
                                                value="0" />
                                        </div>
                                    </div>
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
                                                value="0" placeholder="discount here" />
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
                        <div class="modal-footer justify-content-start">
                            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                            <button type="button" class="btn btn-primary save-btn" data-product-item=""
                                id="saveChangesButton">Save
                                changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    {{-- <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js"></script> --}}

    {{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}
    <!-- Template Javascript -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> --}}



    <!-- Load jQuery first -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <!-- Load jQuery plugins or other scripts that depend on jQuery -->
    <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- Load Bootstrap after jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function playAudio() {
                // var audio = new Audio('{{ asset('back/assets/audio/') }}');
                var audio = document.getElementById('click-audio');
                if (audio) {
                    audio.play();
                }
            }

            document.querySelectorAll('.product-item').forEach(function(product) {
                product.addEventListener('click', playAudio);
            });

            $('.form').on('submit', function(e) {
                e.preventDefault();

                if ($('#paymentChoice').val() == '') {
                    toastr.error('Please select the payment method');
                    return;
                }

                // Get product items
                const product_items = [];
                $('.table tbody tr').each(function() {
                    let product_item = $(this).find('.item-edit').attr('data-product');
                    product_item = JSON.parse(product_item);
                    console.log(product_item);
                    // console.log(product_item.discount);

                    const product = {
                        id: $(this).find('.product_id').val(),
                        sku: $(this).find('.product_sku').text(),
                        quantity: $(this).find('.qty-input').val(),
                        price: $(this).find('.product_sell_price').text(),
                        subtotal: $(this).find('.product_price').text(),
                        tax_type: product_item.tax_type,
                        order_tax: 0,
                        discount_type: product_item.discount_type,
                        discount: product_item.discount,
                        sale_unit: product_item?.sale_units?.id ?? '',
                        // sale_unit: product_item?.sale_unit ?? '',
                    };
                    product_items.push(product);

                });

                // get values for grand_total, shipping, order_tax, discount
                const grandTotal = $('#grand_total').text().replace('$', '');
                const shipping = $('#shipping').val();
                const orderTax = $('#order_tax').val();
                const discount = $('#discount').val();

                // Get form data
                let formData = $(this).serializeArray();
                formData.push({
                    name: 'amount_recieved',
                    value: parseFloat($('#recieve_amount').val()) > parseFloat($('#grand_total').text().replace('$', '')) ?
                        $('#grand_total').text().replace('$', '') :
                        $('#recieve_amount').val()
                });
                // formData.push({
                //     name: 'amount_recieved',
                //     value: parseFloat($('#recieve_amount').val()) ?? 0
                // });
                formData.push({
                    name: 'amount_pay',
                    value: $('#grand_total').text().replace('$', '')
                });
                formData.push({
                    name: 'change_return',
                    value: 0
                });
                formData.push({
                    name: 'note',
                    value: $('#sale_notes').val()
                });
                formData.push({
                    name: 'amount_due',
                    value: $('#change_amount').text()
                });
                // formData.push({
                //     name: 'amount_due',
                //     value: parseFloat(document.getElementById('grand_total').textContent.replace(
                //         '$', '')) - parseFloat(document.querySelector('#recieve_amount').value)
                // });
                let payment_status = "pending";
                let receive_amount = parseFloat($('#recieve_amount').val());
                if (receive_amount < grandTotal && receive_amount > 0) {
                    payment_status = "partial";
                } else if (receive_amount >= grandTotal) {
                    payment_status = "paid";
                }
                formData.push({
                    name: 'payment_status',
                    value: payment_status
                });
                console.log(formData);
                // return
                formData.push({
                    name: 'product_items',
                    value: JSON.stringify(product_items)
                });
                formData.push({
                    name: 'grand_total',
                    value: grandTotal
                });
                formData.push({
                    name: 'shipping',
                    value: shipping
                });
                formData.push({
                    name: 'order_tax',
                    value: orderTax
                });
                formData.push({
                    name: 'discount',
                    value: discount
                });
                //also push customer_id and warehouse_id
                formData.push({
                    name: 'customer_id',
                    value: $('#customers').val()
                });
                formData.push({
                    name: 'warehouse_id',
                    value: $('#warehouses').val()
                });

                $('#btn-text').hide();
                $('#btn-spinner').show();

                $.ajax({
                    url: '{{ route('sales.pos.create') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    success: function(response) {
                        // handle success
                        console.log(response);
                        window.location.href = "{{ route('sales.index') }}";
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status === 404) {
                            // Redirect to a custom 404 page
                            window.location.href = "{{ route('errors.404') }}";
                        } else if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                            toastr.error(jqXHR.responseJSON.error);
                        } else {
                            toastr.error('An error occurred while creating the sale. Please try again.');
                        }
                    },
                    complete: function() {
                        $('#btn-text').show();
                        $('#btn-spinner').hide();
                    }
                });
            });

            const productCodeInput = document.getElementById("product_code");
            let prodCount = 1;

            function createRowHTML(product) {
                // console.log(product);
                return `
                <td class=" align-middle "><span class="product_sku">${product.sku}</span><div> <span class="badge bg-success">${product.name}</span>
                    <a href="#" class="btn item-edit p-0" data-product='${JSON.stringify(product)}' data-bs-target="#exampleModalToggle" data-bs-toggle="modal"><img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}" alt="" width="12" height="12" /></a>
                    </div>
                </td>
                <td class="product_sell_price align-middle ">${product.sell_price}</td>
                <td class="align-middle">
                <div class="quantity d-flex justify-content-center align-items-center">
                    <button type="button" class="btn qty-minus-btn" id="minusBtn">
                    <i class="fa-solid fa-minus"></i>
                    </button>
                    <input type="number" id="quantityInput" class="product_qty border-0 qty-input " value="1" min="1" />
                    <button type="button" class=" btn qty-plus-btn" id="plusBtn">
                    <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                </td>
                <td class="product_price align-middle">${product.sell_price}</td>
                <td class="align-middle">
                <div class="d-flex justify-content-center">
                    <a href="#" class="btn btn-plus item-delete">
                        <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}" alt="" />
                    </a>
                </div>
                </td>
                <input type="hidden" class="product_id" name="product_id" value="${product.id}" />
            `;
            }

            $(document).on('click', '.item-edit', function() {
                $('#exampleModalToggle').show();
                // Parse the product data from the button's data-product attribute
                const product = JSON.parse($(this).attr('data-product'));
                // Populate the modal fields with the product data
                $('#exampleModalToggle .product-title').text(product.name);
                $('#exampleModalToggle #product_price').val(product.sell_price || product.price);
                $('#exampleModalToggle #tax_type').val(product.tax_type);
                // $('#exampleModalToggle #order_tax_item').val(product.order_tax);
                $('#exampleModalToggle #discount_type').val(product.discount_type ?? "fixed");
                $('#exampleModalToggle #discount_item').val(product.discount ?? '0.00');
                $('#exampleModalToggle #hidden_id').val(product.id);
                if (product.product_type == 'service') {
                    $('#exampleModalToggle #unit_section').css('display', 'none');
                } else {
                    $('#exampleModalToggle #unit_section').css('display', 'block');
                    $('#exampleModalToggle #sale_unit_item').val(product.sale_units?.id);
                    console.log($('#exampleModalToggle #sale_unit_item').val(product.sale_units?.id));
                }
                product.discount = 0;
                product.discount_type = "fixed";
                // add product into the data-product-item attribute of #saveChangesButton
                $('#saveChangesButton').attr('data-product-item', JSON.stringify(product));
            });

            $('#saveChangesButton').click(function() {
                // Retrieve and parse updated product data from modal
                const product_details = JSON.parse($('#saveChangesButton').attr('data-product-item'));
                let sale_unit;
                if (product_details.product_type != 'service') {
                    const selectedOption = $('#exampleModalToggle #sale_unit_item option:selected');
                    sale_unit = selectedOption.data('sale-unit-item');
                }

                const updatedProduct = {
                    // existing code to retrieve data...
                    tax_type: parseFloat($('#exampleModalToggle #tax_type').val()),
                    order_tax: parseFloat($('#exampleModalToggle #order_tax_item').val()) ? parseFloat(
                        $('#exampleModalToggle #order_tax_item').val()) : 0,
                    discount_type: $('#exampleModalToggle #discount_type').val(),
                    discount: parseFloat($('#exampleModalToggle #discount_item').val()) ? parseFloat($(
                        '#exampleModalToggle #discount_item').val()) : 0,
                    id: parseFloat($('#exampleModalToggle #hidden_id').val()),
                    price: parseFloat($('#exampleModalToggle #product_price').val()) ? parseFloat($(
                        '#exampleModalToggle #product_price').val()) : 0,
                    sale_units: sale_unit ?? '',
                };

                // var updatedStock = 0;
                // if (product_details.product_type != 'service') {

                //     if(updatedProduct.sale_unit.parent_id != 0){
                //         // big to small unit
                //         updatedStock = eval(`${product_details.quantity}${product_details.unit.operator}${updatedProduct.sale_unit.operator_value}`);

                //     }else
                //     {
                //         // small to large unit conversion
                //         updatedStock = product_details.quantity;
                //     }
                //     updatedProduct.quantity = updatedStock;
                // }



                let price = parseFloat(updatedProduct.price);
                if (updatedProduct.tax_type == 2) {
                    price += updatedProduct.order_tax;
                } else if (updatedProduct.tax_type == 1) {
                    price -= price * (updatedProduct.order_tax / (100 + updatedProduct.order_tax));
                } else {
                    price = price;
                }

                if (updatedProduct.discount_type == 'fixed') {
                    price -= updatedProduct.discount ? updatedProduct.discount : 0;
                } else if (updatedProduct.discount_type == 'percentage') {
                    price -= price * (updatedProduct.discount / 100);
                } else {
                    price = price;
                }

                updatedProduct.price = price.toFixed(2);

                // const rowProduct = JSON.parse($(this).attr('data-product-item'));
                // console.log(updatedProduct)
                $('.table tbody tr').each(function() {

                    const rowProductId = parseInt($(this).find('.product_id').val());
                    if (rowProductId === updatedProduct.id) {
                        // console.log('heelo');
                        // Update the product details in the table
                        $(this).find('td:nth-child(2)').text(updatedProduct.price);
                        // for sub total
                        const quantity = $(this).find('td:nth-child(3)').find('input').val();
                        const subtotal = parseInt(quantity) * parseFloat(updatedProduct.price);
                        $(this).find('td:nth-child(4)').text(subtotal.toFixed(2));

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
                                mergedArray[key] = updatedProduct.hasOwnProperty(key) ?
                                    updatedProduct[key] : product_details[key];
                            }
                        }
                        console.log('mergedArray');
                        console.log(mergedArray);

                        $(this).find('td:nth-child(1)').find('a').attr('data-product', JSON
                            .stringify(mergedArray));

                        $('#exampleModalToggle .btn-close').trigger('click');
                    }
                });

            });

            function updateGrandTotal() {
                let grandTotal = 0;
                document.querySelectorAll('.table tbody tr').forEach(row => {
                    let price = parseFloat(row.querySelector('.product_sell_price').textContent);
                    let quantity = parseInt(row.querySelector('.qty-input').value);
                    grandTotal += price * quantity;
                });
                document.getElementById('grand_total').textContent = grandTotal.toFixed(2);
            }

            var suggestionsContainer = $("#suggestionsContainer");
            $("#product_code").autocomplete({
                source: function(request, response) {
                    var searchTerm = request.term;
                    performAddressSearch(searchTerm, response);
                },
                minLength: 2,
                select: function(event, ui) {
                    const tableBody = document.querySelector(".table tbody");
                    const row = document.createElement("tr");
                    let isDuplicateCode = false;
                    $('#product_code').val('');
                    document.querySelectorAll('.table tbody tr').forEach(row => {
                        if (row.querySelector('.product_sku').textContent == ui.item.product
                            .sku) {
                            // console.log(row.querySelector('.product_sku').textContent);
                            // console.log(ui.item.product.sku);/
                            // console.log("pr"+ui.item.product)
                            // Select the input element
                            // let qtyInput = $(row).find('td:nth-child(6) .qty-input');
                            let qtyInput = parseInt(row.querySelector('.qty-input').value);

                            console.log(qtyInput);
                            // Get the current value of the input
                            let currentValue = parseInt(qtyInput);

                            // Increment the value and set it to the input
                            row.querySelector('.qty-input').value = currentValue + 1;

                            isDuplicateCode = true;
                        }
                    });

                    if (!isDuplicateCode) {
                        // Append row code here
                        // Assuming data contains product name, code, etc.
                        row.innerHTML = createRowHTML(ui.item.product);
                        tableBody.appendChild(row);
                        prodCount++;
                    }
                    updateGrandTotal();

                },
                appendTo: "#suggestionsContainer"
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>").append("<div>" + item.label + "</div>").appendTo(ul);
            };

            function performAddressSearch(searchTerm, response) {
                let warehouse = $('#warehouses').val();
                if (!warehouse) {
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

            // function calculateTotal() {
            //     let subtotal = 0;
            //     $('.table tbody tr').each(function() {
            //         subtotal += parseFloat($(this).find('td:nth-child(4)').text() || 0);
            //     });
            //     // Assume `orderTax` is a percentage value from an input field

            //     const orderTax = parseFloat($('#order_tax').val() == '' ? 0 : $('#order_tax').val()) / 100;
            //     const taxAmount = subtotal * orderTax;

            //     const discountValue = parseFloat($('#discount').val() == '' ? 0 : $('#discount').val());

            //     const shipping = parseFloat($('#shipping').val() == '' ? 0 : $('#shipping').val());
            //     // console.log(shipping, subtotal, taxAmount, discountValue);
            //     const grandTotal = subtotal + taxAmount - discountValue + shipping;

            //     $('#grand_total').text(`$${grandTotal.toFixed(2)}`);
            // }

            function calculateTotal() {
                let subtotal = 0;

                // Calculate the subtotal
                $('.table tbody tr').each(function() {
                    subtotal += parseFloat($(this).find('td:nth-child(4)').text()) || 0;
                });

                // Get the tax percentage and calculate the tax amount
                const orderTax = parseFloat($('#order_tax').val()) || 0;
                const taxAmount = subtotal * (orderTax / 100);

                // Get the discount percentage and calculate the discount amount
                const discountPercentage = parseFloat($('#discount').val()) || 0;
                const discountAmount = subtotal * (discountPercentage / 100);

                // Get the shipping cost
                const shipping = parseFloat($('#shipping').val()) || 0;

                // Calculate the grand total
                const grandTotal = subtotal + taxAmount - discountAmount + shipping;

                // Display the grand total
                $('#grand_total').text(`$${grandTotal.toFixed(2)}`);
            }


            function productItemListing() {
                const productItems = document.querySelectorAll('.product-item');
                productItems.forEach(item => {
                    item.addEventListener('click', function() {
                        const product = JSON.parse(this.dataset.product);
                        console.log(product);

                        const tableBody = document.querySelector(".table tbody");
                        const row = document.createElement("tr");
                        let isDuplicate = false;

                        document.querySelectorAll('.table tbody tr').forEach(row => {
                            if (row.querySelector('.product_sku').textContent == product
                                .sku) {
                                const quantityInput = $(row).find('.qty-input');
                                quantityInput.val(parseInt(quantityInput.val()) + 1)
                                    .change();

                                isDuplicate = true;
                            }
                        });

                        if (!isDuplicate) {
                            // Append row code here
                            // Assuming data contains product name, code, etc.
                            let quantity = product.quantity;

                            row.innerHTML = createRowHTML(product);

                            tableBody.appendChild(row);

                        }
                        // updateGrandTotal();
                        calculateTotal();
                    });
                });
            }

            productItemListing();

            $(document).on('click', '.item-delete', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                calculateTotal();
            });

            // document.getElementById('payNowModalBtn').addEventListener('click', function() {
            //     const productRows = document.querySelectorAll('.table tbody tr');
            //     const totalProducts = productRows.length;
            //     const receivedAmountInput = document.getElementById('recieve_amount');
            //     const payingAmountInput = document.getElementById('pay_amount');
            //     const changeAmount = document.querySelector('.change_amount');
            //     const totalProductsBadge = document.querySelector('.list-group .badge');
            //     const customerName = document.getElementById('customerName');
            //     //check if customer is selected
            //     const customerSelect = document.getElementById('customers');
            //     if (customerSelect.value === '') {
            //         toastr.error('Please select a customer.');
            //         return;
            //     }
            //     if (totalProducts === 0) {
            //         toastr.error('Please add a product to the list.');
            //         return;
            //     }

            //     let subTotal = 0;
            //     productRows.forEach(row => {
            //         let price = parseFloat(row.querySelector('.product_sell_price').textContent);
            //         let quantity = parseInt(row.querySelector('.qty-input').value);
            //         subTotal += price * quantity;
            //     });

            //     customerName.textContent = customerSelect.options[customerSelect.selectedIndex].dataset
            //         .name;
            //     receivedAmountInput.value = $('#grand_total').text().replace('$', '');
            //     payingAmountInput.value = $('#grand_total').text().replace('$', '');
            //     changeAmount.textContent = '0.00';
            //     totalProductsBadge.textContent = totalProducts;
            //     const orderTax = parseFloat($('#order_tax').val() == '' ? 0 : $('#order_tax').val()) / 100;
            //     const taxAmount = subTotal * orderTax;

            //     const orderTaxPercentage = (orderTax * 100).toFixed();
            //     $('#order-tax-pay').text(`$${taxAmount.toFixed(2)} (${orderTaxPercentage}%)`);

            //     $('#discount-pay').text(`$${$('#discount').val()}`);
            //     $('#shipping-pay').text(`$${$('#shipping').val()}`);
            //     $('#total-pay').text(`${$('#grand_total').text()}`);

            //     $('#payNowModal').modal('show');
            // });

            document.getElementById('payNowModalBtn').addEventListener('click', function() {
                const productRows = document.querySelectorAll('.table tbody tr');
                const totalProducts = productRows.length;
                const receivedAmountInput = document.getElementById('recieve_amount');
                const payingAmountInput = document.getElementById('pay_amount');
                const changeAmount = document.querySelector('.change_amount');
                const totalProductsBadge = document.querySelector('.list-group .badge');
                const customerName = document.getElementById('customerName');

                // Check if customer is selected
                const customerSelect = document.getElementById('customers');
                if (customerSelect.value === '') {
                    toastr.error('Please select a customer.');
                    return;
                }

                if (totalProducts === 0) {
                    toastr.error('Please add a product to the list.');
                    return;
                }

                let subTotal = 0;
                productRows.forEach(row => {
                    let price = parseFloat(row.querySelector('.product_sell_price').textContent);
                    let quantity = parseInt(row.querySelector('.qty-input').value);
                    subTotal += price * quantity;
                });

                customerName.textContent = customerSelect.options[customerSelect.selectedIndex].dataset
                    .name;
                receivedAmountInput.value = $('#grand_total').text().replace('$', '');
                payingAmountInput.value = $('#grand_total').text().replace('$', '');
                changeAmount.textContent = '0.00';
                totalProductsBadge.textContent = totalProducts;

                // Calculate and display order tax
                const orderTax = parseFloat($('#order_tax').val()) || 0;
                const taxAmount = subTotal * (orderTax / 100);
                const orderTaxPercentage = (orderTax).toFixed();
                $('#order-tax-pay').text(`$${taxAmount.toFixed(2)} (${orderTaxPercentage}%)`);

                // Calculate and display discount as a percentage
                const discountPercentage = parseFloat($('#discount').val()) || 0;
                const discountAmount = subTotal * (discountPercentage / 100);
                $('#discount-pay').text(`-$${discountAmount.toFixed(2)} (${discountPercentage}%)`);

                // Display shipping cost
                $('#shipping-pay').text(`$${$('#shipping').val()}`);

                // Display grand total
                $('#total-pay').text(`${$('#grand_total').text()}`);

                // Show the modal
                $('#payNowModal').modal('show');
            });


            let amountRecievedInput = document.getElementById('recieve_amount');
            let amountPayInput = document.getElementById('pay_amount');

            // Add event listeners
            amountRecievedInput.addEventListener('input', calculateChangeReturn);
            amountPayInput.addEventListener('input', calculateChangeReturn);

            // function calculateChangeReturn() {
            //     // Parse the input values to floats
            //     let amountRecieved = parseFloat(amountRecievedInput.value);
            //     let amountPay = parseFloat(amountPayInput.value);
            //     let grandTotal =  parseFloat($('#grand_total').text().replace('$', ''));

            //     if (amountPay > amountRecieved) {
            //         toastr.error('Paying amount cannot be greater than received amount');
            //         amountPayInput.value = amountRecieved;
            //         return;
            //     }
            //     if (amountPay > grandTotal) {
            //         toastr.error('Paying amount cannot be greater than grand total');
            //         amountPayInput.value = grandTotal;
            //         return;
            //     }
            //     // Calculate the change return
            //     let changeReturn = amountRecieved - amountPay;
            //     console.log(changeReturn);
            //     if (!isNaN(changeReturn)) {
            //         // Update the change_return field
            //         document.querySelector('.change_amount').textContent = changeReturn.toFixed(2);
            //     }
            // }

            // function calculateChangeReturn() {
            //     // Parse the input values to floats
            //     let amountRecieved = parseFloat(amountRecievedInput.value);

            //     let amountPay = parseFloat(amountPayInput.value);

            //     if (amountPay < amountRecieved) {
            //         alert('Received amount cannot be greater than paying amount for partial payments');
            //         amountRecievedInput.value = amountPay;
            //         document.getElementById('change_return').textContent = amountPay.toFixed(2);
            //         return;
            //     }
            //     // Calculate the change return
            //     // let changeReturn = amountRecieved - amountPay;
            //     let changeReturn = amountPay - amountRecieved;

            //     // Check if the change return is a valid number (it will be NaN if either input field is empty or non-numeric)
            //     if (!isNaN(changeReturn)) {
            //         // Update the change_return field
            //         document.querySelector('.change_amount').textContent = changeReturn.toFixed(2);
            //     }
            // }

            function calculateChangeReturn() {
                // Parse the input values to floats
                let amountRecieved = parseFloat(amountRecievedInput.value);
                let amountPay = parseFloat(amountPayInput.value);

                // Ensure both values are valid numbers
                if (isNaN(amountRecieved) || isNaN(amountPay)) {
                    return; // Exit the function if inputs are not valid
                }

                // If received amount is greater than paying amount, calculate return amount
                if (amountRecieved > amountPay) {
                    let returnAmount = amountRecieved - amountPay;
                    document.querySelector('.return_amount').textContent = returnAmount.toFixed(2);
                    document.querySelector('.change_amount').textContent = '0.00'; // No due amount
                } else {
                    // If received amount is less than or equal to paying amount, calculate due amount
                    let dueAmount = amountPay - amountRecieved;
                    document.querySelector('.change_amount').textContent = dueAmount.toFixed(2);
                    document.querySelector('.return_amount').textContent = '0.00'; // No return amount
                }
            }


            $('.delete-btn').click(function() {
                $('table tbody').empty();
                // Reset tax, shipping, discount, and total payable
                $('#order_tax, #discount, #shipping').val(0);
                $('#grand_total').text('$0');
                $('#customers').prop('selectedIndex', 0);
                $('#warehouses').prop('selectedIndex', 0);
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
                const price = $(this).closest('tr').find('td:nth-child(2)').text();
                const subtotal = parseInt(quantity) * parseFloat(price);
                $(this).closest('tr').find('td:nth-child(4)').text(subtotal.toFixed(2));
                calculateTotal();
            });

            $('#discount, #shipping, #order_tax').on('input', calculateTotal);

            $(document).on('change', '#warehouses', function() {
                let warehouse_id = $(this).val();
                $('#product-listing').hide();
                $('#mySpinner').show();
                $.ajax({
                    url: '/get-all-product-by-warehouse',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        warehouse_id: warehouse_id
                    },
                    success: function(response) {
                        // handle success
                        console.log(response);
                        // Empty the product-listing div
                        $('#product-listing').show();
                        $('#product-listing').empty();
                        // Iterate over the products in the response and generate HTML for each product
                        response.products.forEach(function(product) {
                            var productHTML = `
                            <div class="col-md-3 col-6 mt-2 product-item" >
                                <div class="card">
                                    <button class="btn text-decoration-none">
                                        ${product.images[0] ? `<img src="{{ Storage::url('${product.images[0].img_path}') }}" alt="No" class="w-100" style="max-height: 130px">` : `<img src="{{ asset('back/assets/image/no-image.png') }}" alt="" class="w-100" style="max-height: 130px" />`}
                                        <h4 class="text-center pt-2 heading text-dark">${product.name}</h4>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-dark">$${product.sell_price}</p>
                                            <a href="#" class="text-dark"><i class="fa-solid fa-cart-shopping"></i></a>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        `;
                            var $productItem = $(productHTML);
                            $productItem.attr('data-product', JSON.stringify(product));
                            $('#product-listing').append($productItem);
                        });
                        productItemListing();
                    },
                    complete: function() {
                        $('#mySpinner').hide();
                        $('#product-listing').show();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // handle error
                        console.error(textStatus, errorThrown);
                    }
                });
            });



            // Function to check table status and toggle the warehouse selector
            function toggleWarehouseSelector() {
                var tableBody = $('.table tbody');
                var isNotEmpty = tableBody.find('tr').length !== 0;
                $('#warehouses').prop('disabled', isNotEmpty);
            }
            toggleWarehouseSelector();

            // // // Listen for changes to the table and update the warehouse selector accordingly
            // $('.table tbody').on('DOMSubtreeModified', function() {
            //     toggleWarehouseSelector();
            // });


            // Target the table's <tbody> element
            const tableBody = document.querySelector('.table tbody');

            // Configuration for the observer
            const config = {
                childList: true,
                subtree: true
            }; // Observe child node changes

            // Callback function when mutations are observed
            const callback = function(mutationsList, observer) {
                for (const mutation of mutationsList) {
                    if (mutation.type === 'childList') {
                        // Call the function to toggle the warehouse selector
                        toggleWarehouseSelector();
                    }
                }
            };

            // Create an observer instance linked to the callback function
            const observer = new MutationObserver(callback);

            // Start observing the target node with the configured parameters
            observer.observe(tableBody, config);



            $(document).on('change', '#paymentChoice', function() {

                if (this.value == 'Card') {
                    $('#cardDiv').show();
                    // $('#accountDiv').hide();
                    let savedCards = $('#customers').find(':selected').data('saved-cards');
                    let customer_id = $('#customers').val();
                    $('#card_id').empty();
                    $('#card_id').append('<option value="" disabled selected>Select Card</option>');
                    if (savedCards.length > 0) {
                        savedCards.forEach(function(card) {
                            $('#card_id').append('<option value="' + card.id + '">' +
                                '************' + card.card_last_four + '</option>');
                        });
                    }
                } else if (this.value == 'Cash') {
                    // $('#accountDiv').show();
                    $('#cardDiv').hide();
                    let accounts = $('#customers').find(':selected').data('accounts');

                    $('#accountChoice').empty();
                    $('#accountChoice').append(
                        '<option value="" disabled selected>Select Account</option>');
                    if (accounts.length > 0) {
                        accounts.forEach(function(account) {
                            $('#accountChoice').append('<option value="' + account.id + '">' +
                                account.name + '</option>');
                        });
                    }
                } else {
                    $('#cardDiv').hide();
                    // $('#accountDiv').hide();
                }

            });


            $('#customers').change(function() {
                var discount = $('option:selected', this).data('discount');
                $('#discount').val(discount);
                $('#discount').trigger('input');
                calculateTotal()
            });


        });
    </script>

@endsection
