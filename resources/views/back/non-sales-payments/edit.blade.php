@extends('back.layout.app')
@section('title', 'Edit Non Sale Invoice Payment')
@section('content')
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            @include('back.layout.errors')
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 25%">Edit Non invoice payment</h3>
            </div>


            <form class="container-fluid" action="{{ route('non-sales-payments.update', $nonSalesInvoicePayment->id) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="card card-shadow rounded-3 border-0 mt-5">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-6  col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="SalesCustomerId">Customer</label>
                                    <select class="form-control form-select" name="customer_id" id="SalesCustomerId"
                                        aria-label="Default select example" required disabled>
                                        <option value="" disabled selected>Select Customer</option>
                                        @if ($customers->count() > 0)
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    data-customer="{{ json_encode($customer) }}"
                                                    data-sale="{{ json_encode($customer->sales) }}"
                                                    {{ $nonSalesInvoicePayment->customer_id ?? '' == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->user->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No Customer Found</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{-- type select option --}}
                            <div class="form-group col-md-6">
                                <label for="payment_type" class="form-label">Type</label>
                                <select class="form-select" id="payment_type" name="payment_type" required>
                                    <option value="due"
                                        {{ $nonSalesInvoicePayment->payment_type ?? '' == 'due' ? 'selected' : '' }}>Due
                                    </option>
                                    <option value="pay"
                                        {{ $nonSalesInvoicePayment->payment_type ?? '' == 'pay' ? 'selected' : '' }}>Payment
                                    </option>
                                </select>
                            </div>
                            <div class="row" id="pay_type" style="display: none">
                                <div class="form-group col-md-4 mb-2">
                                    <label for="account_id" class="form-label">Account</label>
                                    <select class="form-select" id="account_id" name="account_id">
                                        <option value="" disabled selected>Select Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ $nonSalesInvoicePayment->account_id ?? '' == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4 mb-2">
                                    <label for="cheque" class="form-label">Cheque No</label>
                                    <div class="input-group">
                                        <input class="form-control" name="cheque_no" type="text"
                                            value="{{ $nonSalesInvoicePayment->cheque_no ?? '' }}" id="cheque">
                                    </div>
                                </div>

                                <div class="form-group col-md-4 mb-2">
                                    <label for="receipt" class="form-label">Receipt No</label>
                                    <div class="input-group">
                                        {{-- <span class="input-group-text"><i class="far fa-sticky-note"></i></span> --}}
                                        <input class="form-control" name="receipt_no" type="text"
                                            value="{{ $nonSalesInvoicePayment->reciept_no ?? '' }}" id="receipt">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label for="amount_pay" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-money-bill-alt"></i></span>
                                    <input class="form-control" name="amount_pay" id="amount_pay" type="number" required
                                        value="{{ $nonSalesInvoicePayment->amount_pay ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_date" class="mb-1 fw-bold">Payment Date </label>
                                    <input class="form-control subheading" id="payment_date" type="date"
                                        name="payment_date" value="{{ $nonSalesInvoicePayment->payment_date ?? '' }}" />

                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1" {{ $nonSalesInvoicePayment->status == 1 ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ $nonSalesInvoicePayment->status == 0 ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 mb-2">
                                <label for="description" class="form-label">Note</label>
                                <textarea class="form-control" rows="3" name="note" id="description">{{ $nonSalesInvoicePayment->note ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" float-start">
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

            $('#totalDue').val(customer.total_due_invoice);
            $('#totalPaid').val(customer.total_paid_invoice);
            $('#totalAmount').val(customer.total_amount);
            var invoiceSelect = $('#invoice_id');

            // Clear the invoice select field
            invoiceSelect.empty();
            invoiceSelect.append('<option value="" disabled selected>Select Invoice</option>');
            console.log(sales);
            // Populate the invoice select field with the customer's sales invoices
            sales.forEach(function(sale) {
                if (sale.invoice !== null) {
                    console.log(sale.invoice);
                    invoiceSelect.append('<option value="' + sale.id + '" data-invoice=\'' + JSON.stringify(
                            sale.invoice) + '\' data-customer=\'' + JSON.stringify(customer) + '\'>' +
                        sale.invoice.invoice_id + '</option>');
                }
            });

        });



        $(document).on('change', '#payment_type', function() {
            var type = $(this).val();
            if (type == 'pay') {
                $('#pay_type').show();
            } else {
                $('#pay_type').hide();
            }

        });

        $(window).on('load', function() {
            $('#payment_type').trigger('change');
        });
    </script>
@endsection
