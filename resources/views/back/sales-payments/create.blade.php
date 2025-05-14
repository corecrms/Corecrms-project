@extends('back.layout.app')
@section('title', 'Add Sales Template')
@section('content')
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            @include('back.layout.errors')
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 25%">Create invoice payment</h3>
            </div>


            <form class="container-fluid" action="{{ route('sales-payments.store') }}" method="POST">
                @csrf
                <div class="card card-shadow rounded-3 border-0 mt-5">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-12  col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="SalesCustomerId">Customer</label>
                                    <select class="form-control form-select" name="customer_id" id="SalesCustomerId"
                                        aria-label="Default select example" required>
                                        <option value="" disabled selected>Select Customer</option>
                                        @if ($customers->count() > 0)
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    data-customer="{{ json_encode($customer) }}"
                                                    data-sale="{{ json_encode($customer->sales) }}"
                                                    data-saved-cards="{{ json_encode($customer->user->savedCards) }}">
                                                    {{ $customer->user->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No Customer Found</option>
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="form-group col-lg-4 col-md-4">
                                <label for="totalAmount">Invoice Total</label>
                                <input type="text" class="form-control" id="totalAmount" readonly>
                            </div>
                            <div class="form-group col-lg-4 col-md-4">
                                <label for="totalPaid">Total Paid</label>
                                <input type="text" class="form-control" id="totalPaid" readonly>
                            </div>
                            <div class="form-group col-lg-4 col-md-4">
                                <label for="totalDue">Total Due</label>
                                <input type="text" class="form-control" id="totalDue" readonly>
                            </div>
                            {{-- select invoice --}}
                            <div class="form-group col-lg-12 col-md-12" id="invoiceDiv">
                                <label for="invoice_id">Invoice</label>
                                <select class="form-control form-select" id="invoice_id" required>
                                    <option value="" disabled selected>Select Invoice</option>

                                </select>
                            </div>
                            <div id="sortable-table" class="mt-5"></div>

                            <div class="form-group col-md-4 mb-2">
                                <label for="total_pay" class="form-label">Total Payment</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-money-bill-alt"></i></span>
                                    <input class="form-control" readonly name="total_pay" type="number" id="total_pay"
                                        required>
                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    {{-- <option value="" disabled selected>Select Method</option> --}}
                                    <option value="Cash" selected>Cash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Card">Credit Card</option>
                                </select>
                            </div>
                            {{-- <div class="form-group col-md-4 mb-2" id="account_div">
                                <label for="account_id" class="form-label">Account</label>
                                <select class="form-select" id="account_id" name="account_id" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group col-md-4 mb-2" id="card_div" style="display: none">
                                <label for="account_id" class="form-label">Saved Cards</label>
                                <select class="form-select" id="card_no" name="card_id" required>
                                    <option value="" disabled selected>Select Account</option>
                                    {{-- @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach --}}
                                </select>
                            </div>

                            <div class="form-group col-md-3 mb-2" id="cheque_div" style="display: none">
                                <label for="cheque" class="form-label">Cheque No</label>
                                <div class="input-group">
                                    {{-- <span class="input-group-text"><i class="far fa-sticky-note"></i></span> --}}
                                    <input class="form-control" name="cheque_no" type="text" value=""
                                        id="cheque" required>
                                </div>
                            </div>

                            <div class="form-group col-md-3 mb-2" id="receipt_div" style="display: none">
                                <label for="receipt" class="form-label">Receipt No</label>
                                <div class="input-group">
                                    {{-- <span class="input-group-text"><i class="far fa-sticky-note"></i></span> --}}
                                    <input class="form-control" name="receipt_no" type="text" value=""
                                        id="receipt" required>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label for="pay_date" class="mb-1 fw-bold">Payment Date </label>
                                    <input class="form-control subheading" name="payment_date" id="pay_date"
                                        type="date" value="<?php echo date('Y-m-d'); ?>" />
                                </div>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 mb-2">
                                <label for="description" class="form-label">Note</label>
                                <textarea class="form-control" rows="3" name="note" id="description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="">
                    <button type="submit" class="btn save-btn text-white mt-3">Submit</button>
                    {{-- <a href="{{ route('sales-payments.index') }}" class="btn cancel-btn">Back</a> --}}
                </div>
            </form>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('change', '#SalesCustomerId', function() {

            var customer = $(this).find(':selected').data('customer');
            var sales = $(this).find(':selected').data('sale');
            var savedCards = $(this).find(':selected').data('saved-cards');
            // console.log(savedCards);
            $('#card_no').empty();
            $('#card_no').append('<option value="" disabled selected>Select Card</option>');
            if (savedCards.length > 0) {
                savedCards.forEach(function(card) {
                    $('#card_no').append('<option value="' + card.id + '">' + '************' + card
                        .card_last_four + '</option>');
                });
            }

            $('#totalDue').val(customer.total_due_invoice.toFixed(2));
            $('#totalPaid').val(customer.total_paid_invoice.toFixed(2));
            $('#totalAmount').val(customer.total_amount);
            var invoiceSelect = $('#invoice_id');

            // Clear the invoice select field
            invoiceSelect.empty();
            invoiceSelect.append('<option value="" disabled selected>Select Invoice</option>');
            console.log(sales);
            // Populate the invoice select field with the customer's sales invoices
            sales.forEach(function(sale) {
                if (sale.invoice !== null) {
                    if (sale.amount_due <= 0) {
                        return;
                    }
                    console.log(sale.invoice);
                    invoiceSelect.append('<option value="' + sale.id + '" data-invoice=\'' + JSON.stringify(sale.invoice) + '\' data-customer=\'' + JSON.stringify(customer) + '\' data-sale=\'' + JSON.stringify(sale) + '\'>' + sale.invoice.invoice_id + '</option>');

                }
            });

        });
        let itemsSet = new Set();
        $(document).on('change', '#invoice_id', function() {
            var invoice = $(this).find(':selected').data('invoice');
            let customer = $(this).find(':selected').data('customer');
            let sale = $(this).find(':selected').data('sale');
            if (sale.amount_due <= 0) {
                toastr.success('This invoice has been paid');
                this.value = '';
                return;
            }
            console.log(invoice);

            let invoice_no = invoice.invoice_id;
            let invoice_id = invoice.id;
            let invoice_total = sale.grand_total;
            let invoice_due = sale.amount_due;

            if (itemsSet.has(invoice_id)) {
                toastr.error('This invoice has already been added');
                this.value = '';
                return;
            }

            console.log(invoice_no, invoice_total, invoice_due);
            appendItem(invoice_id, invoice_no, invoice_total, invoice_due);
        });
        let items = [];
        let itemCount = 1;

        function appendItem(invoice_id, invoice_no, invoice_total, invoice_due) {
            let newRow =
                `
                    <div class="col-md-11 m-auto">
                        <div class="card bg-light border-light mb-3">
                            <div class="card-header d-flex">
                                <span class="card-title w-100">${invoice_no} Invoice Details</span>
                                <input type="hidden"  value="${invoice_id}" name="items[${itemCount}][invoice_id]">
                                <button type="button" class="btn btn-danger float-right remove-item"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="invoiceNumber">Invoice No</label>
                                        <input type="text" id="invoiceNumber-${itemCount}" value="${invoice_no}" readonly="readonly" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="invoiceTotal">Invoice Total</label>
                                        <input type="text" id="invoiceTotal-${itemCount}" value="${invoice_total}" readonly="readonly" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="invoiceDue">Invoice Due</label>
                                        <input type="text" id="invoiceDue-${itemCount}" value="${invoice_due}" readonly="readonly" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="paidAmount">Paid Amount</label>
                                        <input type="number" step="any" id="paidAmount-${itemCount}" name="items[${itemCount}][pay_amount]" placeholder="Enter an amount" required="required" min="0" max="${invoice_due}" value="1" class="form-control paidAmount">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

            $('#sortable-table').append(newRow);
            // Add the invoice_id to the Set to track it as added
            itemsSet.add(invoice_id);
            itemCount++;
        }

        $(document).on('change', '.paidAmount', function() {
            var totalPay = 0;

            $('.paidAmount').each(function() {
                totalPay += Number($(this).val());
            });

            $('#total_pay').val(totalPay.toFixed(2));
        });


        $(document).on('click', '.remove-item', function(e) {
            e.preventDefault();
            $(this).closest('.card').remove();
            calculateTotal();
        });


        $(document).on('change', '#payment_method', function(e) {
            e.preventDefault();
            let method = $(this).val();

            // Hide all sections and remove required attribute
            $('#cheque_div, #receipt_div, #card_div').css('display', 'none');
            $('#cheque, #receipt, #card_no').prop('required', false);

            if (method === 'Cheque') {
                $('#cheque_div').css('display', 'block');
                $('#receipt_div').css('display', 'block');
                $('#cheque').prop('required', true);
                $('#receipt').prop('required', true);
            } 
            else if (method === 'Card') {
                $('#card_div').css('display', 'block');
                $('#card_no').prop('required', true);
            }
        });
        $('#payment_method').trigger('change');

    </script>
@endsection
