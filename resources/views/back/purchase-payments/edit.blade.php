@extends('back.layout.app')
@section('title', 'Edit Purchase Invoice')
@section('content')
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            @include('back.layout.errors')
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 25%">Edit Purchase Invoice Payment</h3>
            </div>


            <form class="container-fluid" action="{{ route('purchase-payments.update', $purchaseInvoicePayment->id) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="card card-shadow rounded-3 border-0 mt-5">
                    <div class="card-body">
                        <div class="row mb-3">

                            <div class="form-group col-lg-4 col-md-4">
                                <label for="totalAmount">Invoice No.</label>
                                <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                    value="{{ $purchaseInvoicePayment->purchaseInvoice->invoice_number ?? '' }}" readonly>
                            </div>
                            <div class="form-group col-lg-4 col-md-4">
                                <label for="totalPaid">Total Paid</label>
                                <input type="text" class="form-control" id="totalPaid"
                                    value="{{ $purchaseInvoicePayment->purchaseInvoice->purchase->grand_total }}" readonly>
                            </div>
                            <div class="form-group col-lg-4 col-md-4">
                                <label for="totalDue">Total Due</label>
                                <input type="text" class="form-control" id="totalDue"
                                    value="{{ $purchaseInvoicePayment->purchaseInvoice->purchase->amount_due }}" readonly>
                            </div>

                            {{-- <div class="form-group col-md-6 mb-2 mt-3">
                                <label for="account_id" class="form-label">Account</label>
                                <select class="form-select" id="account_id" name="account_id" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{$purchaseInvoicePayment->purchasePayment->account_id == $account->id ? 'selected': ''}}>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3 mb-2 mt-3">
                                <label for="cheque" class="form-label">Cheque No</label>
                                <div class="input-group">
                                    <input class="form-control" name="cheque_no" type="text" value="{{$purchaseInvoicePayment->purchasePayment->cheque_no ?? ''}}"
                                        id="cheque" >
                                </div>
                            </div>

                            <div class="form-group col-md-3 mb-2 mt-3">
                                <label for="receipt" class="form-label">Receipt No</label>
                                <div class="input-group">

                                    <input class="form-control" name="receipt_no" type="text" value="{{$purchaseInvoicePayment->purchasePayment->reciept_no ?? ''}}"
                                        id="receipt" >
                                </div>
                            </div> --}}

                            <div class="form-group col-md-4 mb-2">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="Bank"
                                        {{ $purchaseInvoicePayment->purchasePayment->payment_method == 'Bank' ? 'selected' : '' }}>
                                        Bank</option>
                                    <option value="Card"
                                        {{ $purchaseInvoicePayment->purchasePayment->payment_method == 'Card' ? 'selected' : '' }}>
                                        Credit Card</option>
                                    <option value="Cheque"
                                        {{ $purchaseInvoicePayment->purchasePayment->payment_method == 'Cheque' ? 'selected' : '' }}>
                                        Cheque</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 mb-2" id="account_div">
                                <label for="account_id" class="form-label">Account</label>
                                <select class="form-select" id="account_id" name="account_id" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            {{ $purchaseInvoicePayment->purchasePayment->account_id == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4 mb-2" id="card_div" style="display: none">
                                <label for="account_id" class="form-label">Credit Cards</label>
                                <select class="form-select" id="card_no" name="card_id" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach ($cards as $card)
                                        <option value="{{ $card->id ?? '' }}"
                                            {{ $card->id == $purchaseInvoicePayment->purchasePayment->card_id ? 'selected' : '' }}>
                                            ************{{ $card->card_last_four ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3 mb-2 mt-2" id="cheque_div" style="display: none">
                                <label for="cheque" class="form-label">Cheque No</label>
                                <div class="input-group">

                                    <input class="form-control" name="cheque_no" type="text"
                                        value="{{ $purchaseInvoicePayment->purchasePayment->cheque_no ?? '' }}"
                                        id="cheque" required>
                                </div>
                            </div>

                            <div class="form-group col-md-3 mb-2 mt-2" id="receipt_div" style="display: none">
                                <label for="receipt" class="form-label">Receipt No</label>
                                <div class="input-group">

                                    <input class="form-control" name="receipt_no" type="text"
                                        value="{{ $purchaseInvoicePayment->purchasePayment->reciept_no ?? '' }}"
                                        id="receipt" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label for="total_pay" class="form-label">Paid Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-money-bill-alt"></i></span>
                                    <input class="form-control" name="paid_amount" id="paid_amount"
                                        value="{{ $purchaseInvoicePayment->paid_amount }}" type="number" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="pay_date" class="form-label">Payment Date </label>
                                    <input class="form-control subheading" id="pay_date" type="date"
                                        value="{{ $purchaseInvoicePayment->purchasePayment->payment_date ?? '' }}"
                                        name="payment_date" />

                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1"
                                        {{ $purchaseInvoicePayment->purchasePayment->status == '1' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0"
                                        {{ $purchaseInvoicePayment->purchasePayment->status == '0' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 mb-2">
                                <label for="description" class="form-label">Note</label>
                                <textarea class="form-control" rows="3" name="description" id="description">{{ $purchaseInvoicePayment->purchasePayment->note ?? '' }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn save-btn text-white me-2">Submit</button>
                    </div>
                </div>

            </form>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // $(document).on('change', '#paid_amount', function() {
        //     let totalDue = parseFloat('{{ $purchaseInvoicePayment->purchaseInvoice->purchase->amount_due }}') + parseFloat('{{ $purchaseInvoicePayment->paid_amount }}');
        //     let paidAmountInput = parseFloat($(this).val());

        //     // If the input paid amount is less than or equal to the total due, update the total due
        //     if (paidAmountInput <= totalDue) {
        //         let newTotalDue = totalDue - paidAmountInput;
        //         $('#totalDue').val(newTotalDue.toFixed(2));
        //     }
        //     else if (paidAmountInput <= 0) {
        //     // If the paid amount is less than or equal to 0, set it to 0
        //         $(this).val(0);
        //     }
        //     else {
        //         // If the input paid amount is greater than the total due, set it to the total due
        //         $(this).val(totalDue.toFixed(2));
        //         $('#totalDue').val('0.00');
        //     }
        // });

        $(document).on('change', '#paid_amount', function() {
            let totalDue = parseFloat('{{ $purchaseInvoicePayment->purchaseInvoice->purchase->amount_due }}') +
                parseFloat('{{ $purchaseInvoicePayment->paid_amount }}');
            let paidAmountInput = parseFloat($(this).val());

            // If the input paid amount is less than or equal to the total due, update the total due
            if (paidAmountInput <= totalDue) {
                let newTotalDue = totalDue - paidAmountInput;
                $('#totalDue').val(newTotalDue.toFixed(2));
            } else if (paidAmountInput <= 0) {
                // If the paid amount is less than or equal to 0, set it to 0
                $(this).val(0);
            } else {
                // If the input paid amount is greater than the total due, set it to the total due
                $(this).val(totalDue.toFixed(2));
                $('#totalDue').val('0.00');
            }
        });

        $(document).on('change', '#payment_method', function(e) {
            e.preventDefault();
            let method = $(this).val();

            // Hide all sections and remove required attribute
            $('#cheque_div, #receipt_div, #account_div, #card_div').css('display', 'none');
            $('#cheque, #receipt, #account_id, #card_no').prop('required', false);

            if (method === 'Cheque') {
                $('#cheque_div').css('display', 'block');
                $('#receipt_div').css('display', 'block');
                $('#cheque').prop('required', true);
                $('#receipt').prop('required', true);
            } else if (method === 'Bank') {
                $('#account_div').css('display', 'block');
                $('#account_id').prop('required', true);
            } else if (method === 'Card') {
                $('#card_div').css('display', 'block');
                $('#card_no').prop('required', true);
            }
        });
        $(document).ready(function() {
            $('#payment_method').trigger('change');
        });
    </script>
@endsection
