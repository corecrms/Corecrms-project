@extends('back.layout.app')
@section('title', 'Edit Invoice')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Edit Sales Invoice </h1>
                </div>
            </div>
            <div class="create-product-form rounded" style="padding: 20px 20px 80px 20px">

                <form action="{{ route('sales-invoices.update', $salesInvoice) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row mb-3">

                                <div class="col-lg-3  col-md-6">
                                    <div class="form-group">
                                        <label for="vendorId">Vendor</label>
                                        <select class="form-control form-select" name="vendor_id" id="vendorId"
                                            aria-label="Default select example">
                                            @if ($vendors->count() > 0)
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}"
                                                        {{ $vendor->id == $salesInvoice->vendor_id ? 'selected' : '' }}>
                                                        {{ $vendor->user->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No Vendor Found</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3  col-md-6">
                                    <div class="form-group">
                                        <label for="customerId">Customer</label>
                                        <select class="form-control form-select" name="customer_id" id="customerId"
                                            aria-label="Default select example">
                                            @if ($vendors->count() > 0)
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ $customer->id == $salesInvoice->customer_id ? 'selected' : '' }}>
                                                        {{ $customer->user->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No Customer Found</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="issueDate">Issue Date</label>
                                        <input type="date" name="issue_date" class="form-control"
                                            value="{{ $salesInvoice->issue_date }}" id="issueDate">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="dueDate">Due Date</label>
                                        <input type="date" name="due_date" class="form-control"
                                            value="{{ $salesInvoice->due_date }}" id="dueDate">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="invoiceNumber">Invoice Number</label>
                                        <input type="text" name="invoice_number" class="form-control"
                                            value="{{ $salesInvoice->invoice_number }}" readonly id="invoiceNumber">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="referenceNumber">Reference Number</label>
                                        <input type="text" name="reference_number" class="form-control"
                                            value="{{ $salesInvoice->reference_number }}" id="referenceNumber">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="card repeater" data-select2-id="31">
                            <div class="item-section py-4">
                                <div class="row justify-content-between align-items-center">
                                    <div
                                        class="col-md-12 d-flex  px-4 align-items-center justify-content-between justify-content-md-end">
                                        <div class="all-button-box">
                                            <a href="javascript:void()" data-repeater-create=""
                                                class="btn btn-xs add-item btn-white btn-icon-only width-auto"
                                                data-toggle="modal" data-target="#add-bank">
                                                <i class="fas fa-plus"></i> Add item
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-0" data-select2-id="30">
                                <div class="table-responsive" data-select2-id="29">
                                    <table class="table table-striped mb-0" data-repeater-list="items" id="sortable-table"
                                        data-select2-id="sortable-table">
                                        <thead>
                                            <tr>
                                                <th>Items</th>
                                                <th>Quantity</th>
                                                <th>Price </th>
                                                <th>Discount</th>
                                                <th class="text-right">Amount <br><small
                                                        class="text-danger font-weight-bold">before tax &amp;
                                                        discount</small>
                                                </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        @foreach ($salesInvoice->salesInvoiceDetails as $key => $item)
                                            <tbody class="ui-sortable" data-repeater-item="" data-select2-id="28">
                                                <tr data-select2-id="27">
                                                    <td width="25%">
                                                        <select class="form-control form-select item"
                                                            name="items[{{ $key }}][item]" required>
                                                            @if ($products->count() > 0)
                                                                <option value="" disabled selected>Select Item
                                                                </option>
                                                                @foreach ($products as $product)
                                                                    <option value="{{ $product->id }}"
                                                                        {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                                                        {{ $product->name }}
                                                                    </option>
                                                                @endforeach
                                                            @else
                                                                <option value="" disabled selected>No item found
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="form-group input-group">
                                                            <input class="form-control quantity" required="required"
                                                                min="1" placeholder="Qty"
                                                                name="items[{{ $key }}][qty]" type="number"
                                                                value="{{ $item->qty }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group input-group price-input">
                                                            <input class="form-control price" required="required"
                                                                placeholder="Price"
                                                                name="items[{{ $key }}][price]" type="number"
                                                                value="{{ $item->price }}">
                                                            <span class="input-group-text">USD</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group input-group price-input">
                                                            <input class="form-control discount" placeholder="Discount"
                                                                name="items[{{ $key }}][discount]"
                                                                type="number" value="{{ $item->discount }}">
                                                            <span class="input-group-text">USD</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-right amount">{{ $item->qty * $item->price }}</td>
                                                    <td>
                                                        <a href="javascript:void(0)"
                                                            class="fas fa-trash text-danger remove-item"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="form-group">
                                                            <textarea class="form-control" rows="2" placeholder="Description"
                                                                name="items[{{ $key }}][description]" cols="50">{{ $item->description }}</textarea>
                                                        </div>
                                                    </td>
                                                    <td colspan="5"></td>
                                                </tr>
                                            </tbody>
                                        @endforeach
                                        <tfoot>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td><strong>Sub Total (USD)</strong></td>
                                                <td class="text-right subTotal">0.00</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td><strong>Discount (USD)</strong></td>
                                                <td class="text-right totalDiscount">0.00</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td class="blue-text"><strong>Total Amount (USD)</strong></td>
                                                <td class="text-right totalAmount blue-text">0.00</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card mb-3">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-3 mb-3">
                                <div class="form-group">
                                    <label for="product_type"> Type </label>
                                    <select class="form-control form-select" aria-label="Default select example"
                                        id="product_type" name="product_type">
                                        <option>General </option>
                                        <option>Combo</option>


                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="product_weight">Weight</label>
                                    <input type="number" class="form-control" id="product_weight" name="product_weight"
                                        placeholder="Weight">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="custom_field_1">Custom Field1</label>
                                    <input type="text" class="form-control" id="custom_field_1" name="custom_field_1"
                                        placeholder="Custom Field 1">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="custom_field_2">Custom Field2</label>
                                    <input type="text" class="form-control" id="custom_field_2" name="custom_field_2"
                                        placeholder="Custom Field 2">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="custom_field_3">Custom Field2</label>
                                    <input type="text" class="form-control" id="custom_field_3" name="custom_field_3"
                                        placeholder="Custom Field 3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div> --}}
                    <div class=" float-end">
                        <button type="submit" class="btn confirm-btn me-2">Submit</button>
                        <a href="{{ route('products.index') }}" class="btn cancel-btn">Back</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            calculateTotal();
        });

        let items = [];
        let totalAmount = 0;

        $(document).on('change', '.quantity, .price, .discount', function() {
            let quantity = $(this).closest('tr').find('.quantity').val();
            let price = $(this).closest('tr').find('.price').val();
            let discount = $(this).closest('tr').find('.discount').val();
            let amount = (quantity * price);
            $(this).closest('tr').find('.amount').text(amount.toFixed(2));
            calculateTotal();
        });

        function calculateTotal() {
            console.log
            let subTotal = 0;
            let totalDiscount = 0;
            let totalAmount = 0;

            $('.amount').each(function() {
                subTotal += parseFloat($(this).text());
            });

            $('.discount').each(function() {
                let discount = parseFloat($(this).val()) || 0;
                totalDiscount += discount;
                console.log(totalDiscount);
            });

            totalAmount = subTotal - totalDiscount;
            console.log(totalDiscount)
            console.log(subTotal)
            console.log(totalAmount)

            $('.subTotal').text(subTotal.toFixed(2));
            $('.totalDiscount').text(totalDiscount.toFixed(2));
            $('.totalAmount').text(totalAmount.toFixed(2));
        }

        let itemCount = 1;

        function appendItem() {
            let newRow =
                `<tbody class="ui-sortable" data-repeater-item="" style=""
                    data-select2-id="230">
                    <tr data-select2-id="229">
                        <td width="25%">
                            <select class="form-control form-select item"
                                name="items[${itemCount}][item]" required>
                                @if ($products->count() > 0)
                                    <option value="" disabled selected>Select Item</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled selected>No item found</option>
                                @endif
                            </select>
                        </td>
                        <td>
                            <div class="form-group price-input">
                                <input class="form-control quantity" required="required" min="1"
                                    placeholder="Qty" name="items[${itemCount}][qty]" type="number"
                                    value="">
                            </div>
                        </td>
                        <td>
                            <div class="form-group input-group price-input">
                                <input class="form-control price" required="required"
                                    placeholder="Price" name="items[${itemCount}][price]" type="number"
                                    value="">
                                <span class="input-group-text">USD</span>
                            </div>
                        </td>
                        iv>
                        </td>
                        <td>
                            <div class="form-group input-group price-input">
                                <input class="form-control discount"
                                    placeholder="Discount" name="items[${itemCount}][discount]" type="number"
                                    value="">
                                <span class="input-group-text">USD</span>
                            </div>
                        </td>
                        <td class="text-right amount">0.00</td>
                        <td>
                            <a href="javascript:void(0)" class="fas fa-trash text-danger remove-item"></a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="form-group">
                                <textarea class="form-control" rows="2" placeholder="Description" name="items[${itemCount}][description]"
                                    cols="50"></textarea>
                            </div>
                        </td>
                        <td colspan="5"></td>
                    </tr>
                </tbody>`;

            $('#sortable-table').append(newRow);
            itemCount++;
        }

        $(document).on('click', '.add-item', function(e) {
            e.preventDefault();
            appendItem();
        });

        $(document).on('click', '.remove-item', function(e) {
            e.preventDefault();
            $(this).closest('tbody').remove();
            calculateTotal();
        });

        //  $(document).on('change', '.item', function() {
        //     let id = $(this).val();
        //     let row = $(this).closest('tr');
        //     $.ajax({
        //         url: `/products/${id}`,
        //         method: 'GET',
        //         success: function(response) {
        //             console.log(response);
        //             row.find('.price').val(response.unit_price_for_sale);
        //             row.find('.quantity').val(1);
        //             row.find('.discount').val(0);
        //             row.find('.amount').text(response.unit_price_for_sale);
        //             calculateTotal();
        //         }
        //     });
        // });
    </script>
@endsection
