@extends('back.layout.app')
@section('title', 'Add Sales Template')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Add Deposit </h1>
                    <p>Add a Deposit
                    </p>
                </div>
            </div>
            <div class="create-product-form rounded" style="padding: 20px 20px 80px 20px">

                <form action="{{ route('sales-payments.store') }}" method="POST">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row mb-3">

                                <div class="col-lg-3  col-md-6">
                                    <div class="form-group">
                                        <label for="vendorId">Select Invoice</label>
                                        <select class="form-control form-select" name="sales_invoice_id" id="SalesInvoiceId"
                                            aria-label="Default select example" required>
                                            <option value="" disabled selected>Select Invoice</option>
                                            @if ($salesInvoices->count() > 0)
                                                @foreach ($salesInvoices as $invoice)
                                                    <option value="{{ $invoice->id }}"
                                                        data-invoice="{{ json_encode($invoice) }}">
                                                        {{ $invoice->invoice_number }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No Sales Invoice Found</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="issueDate">Issue Date</label>
                                        <input type="date" class="form-control" id="issueDate" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="dueDate">Due Date</label>
                                        <input type="date" class="form-control" id="dueDate" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="referenceNumber">Reference Number</label>
                                        <input type="text" class="form-control" value="" id="referenceNumber"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 mb-2">
                            <label for="date" class="form-label">Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input class="form-control" name="payment_date" type="date" id="date" required
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-money-bill-alt"></i></span>
                                <input class="form-control" name="amount" id="payableAmount" type="text" id="amount"
                                    required>
                            </div>
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="account_id" class="form-label">Account</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="1">Cash</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6 mb-2">
                            <label for="reference" class="form-label">Reference</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-sticky-note"></i></span>
                                <input class="form-control" name="reference_number" type="text" value=""
                                    id="reference" required>
                            </div>
                        </div>
                        <div class="form-group col-md-12 mb-2">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" rows="3" name="description" id="description"></textarea>
                        </div>
                    </div>
                    <div class=" float-end">
                        <button type="submit" class="btn confirm-btn me-2">Submit</button>
                        <a href="{{ route('sales-payments.index') }}" class="btn cancel-btn">Back</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('change', '#SalesInvoiceId', function() {
            var id = $(this).val();
            let salesInvoice = $(this).find(':selected').data('invoice');
            console.log(salesInvoice);

            // #payableAmount
            $('#payableAmount').val((salesInvoice.net_amount - salesInvoice.paid_amount) <= 0 ? 0 : (salesInvoice
                .net_amount - salesInvoice.paid_amount));
            $('#referenceNumber').val(salesInvoice.reference_number);
            $('#issueDate').val(salesInvoice.issue_date);
            $('#dueDate').val(salesInvoice.due_date);

        });
    </script>
@endsection
