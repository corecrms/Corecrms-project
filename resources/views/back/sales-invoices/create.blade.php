@extends('back.layout.app')
@section('title', 'Create Invoice')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Add Sales Invoice </h1>
                </div>
            </div>
            <div class="create-product-form rounded" style="padding: 20px 20px 80px 20px">

                <form action="{{ route('sales-invoices.store') }}" method="POST">
                    @csrf
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
                                                    <option value="{{ $vendor->id }}">{{ $vendor->user->name }}</option>
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
                                                    <option value="{{ $customer->id }}">{{ $customer->user->name }}</option>
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
                                            value="{{ date('Y-m-d') }}" id="issueDate">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="dueDate">Due Date</label>
                                        <input type="date" name="due_date" class="form-control"
                                            value="{{ date('Y-m-d') }}" id="dueDate">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="invoiceNumber">Invoice Number</label>
                                        <input type="text" name="invoice_number" class="form-control"
                                            value="{{ getInvoiceNo() }}" readonly id="invoiceNumber">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="referenceNumber">Reference Number</label>
                                        <input type="text" name="reference_number" class="form-control" value=""
                                            id="referenceNumber">
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
                                        <tbody class="ui-sortable" data-repeater-item="" data-select2-id="28">
                                            <tr data-select2-id="27">
                                                <td width="25%">
                                                    <select class="form-control form-select item" name="items[0][item]"
                                                        required>
                                                        @if ($products->count() > 0)
                                                            <option value="" selected>Select Item</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}">
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            <option value="" selected>No item found</option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="form-group input-group">
                                                        <input class="form-control quantity" required="required"
                                                            min="1" placeholder="Qty" name="items[0][qty]"
                                                            type="number" value="">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group input-group price-input">
                                                        <input class="form-control price" required="required"
                                                            placeholder="Price" name="items[0][price]" type="number"
                                                            value="">
                                                        <span class="input-group-text">USD</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group input-group price-input">
                                                        <input class="form-control discount" placeholder="Discount"
                                                            name="items[0][discount]" type="number" value="">
                                                        <span class="input-group-text">USD</span>
                                                    </div>
                                                </td>
                                                <td class="text-right amount">0.00</td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="form-group">
                                                        <textarea class="form-control" rows="2" placeholder="Description" name="items[0][description]"
                                                            cols="50"></textarea>
                                                    </div>
                                                </td>
                                                <td colspan="5"></td>
                                            </tr>
                                        </tbody>
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
            let subTotal = 0;
            let totalDiscount = 0;
            let totalTax = 0;
            let totalAmount = 0;

            $('.amount').each(function() {
                subTotal += parseFloat($(this).text());
            });

            $('.discount').each(function() {
                let discount = parseFloat($(this).val()) || 0;
                totalDiscount += discount;
                console.log(totalDiscount);
            });

            $('.tax').each(function() {
                totalTax += parseFloat($(this).val());
            });

            totalAmount = subTotal - totalDiscount + totalTax;

            $('.subTotal').text(subTotal.toFixed(2));
            $('.totalDiscount').text(totalDiscount.toFixed(2));
            $('.totalTax').text(totalTax.toFixed(2));
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
                                <input class="form-control quantity" required="required"
                                    placeholder="Qty" name="items[${itemCount}][qty]" type="number" min="1"
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

        // $(document).on('change', '.item', function() {
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
