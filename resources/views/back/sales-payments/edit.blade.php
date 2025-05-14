@extends('back.layout.app')
@section('title', 'Edit Sales Template')
@section('content')
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            @include('back.layout.errors')
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 25%">Edit invoice payment</h3>
            </div>


            <form class="container-fluid" action="{{ route('sales-payments.update', $salesInvoicePayment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card card-shadow rounded-3 border-0 mt-5">
                    <div class="card-body">
                        <div class="row mb-3">

                            <div class="form-group col-lg-4 col-md-4">
                                <label for="totalAmount">Invoice No.</label>
                                <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                    value="{{ $salesInvoicePayment->saleInvoice->invoice_id }}" readonly>
                            </div>
                            <div class="form-group col-lg-4 col-md-4">
                                <label for="totalPaid">Total Paid</label>
                                <input type="text" class="form-control" id="totalPaid"
                                    value="{{ $salesInvoicePayment->saleInvoice->sale->grand_total }}" readonly>
                            </div>
                            <div class="form-group col-lg-4 col-md-4">
                                <label for="totalDue">Total Due</label>
                                <input type="text" class="form-control" id="totalDue"
                                    value="{{ $salesInvoicePayment->saleInvoice->sale->amount_due }}" readonly>
                            </div>

                            {{-- <div class="form-group col-md-6 mb-2 mt-3">
                                <label for="account_id" class="form-label">Account</label>
                                <select class="form-select" id="account_id" name="account_id" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{$salesInvoicePayment->salesPayment->account_id == $account->id ? 'selected': ''}}>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group col-md-4 mb-2">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="Cash" {{$salesInvoicePayment->salesPayment->payment_method == "Cash" ? 'selected':''}}>Cash</option>
                                    <option value="Cheque" {{$salesInvoicePayment->salesPayment->payment_method == "Cheque" ? 'selected':''}}>Cheque</option>
                                    <option value="Card" {{$salesInvoicePayment->salesPayment->payment_method == "Card" ? 'selected':''}}>Credit Card</option>
                                </select>
                            </div>
                            {{-- <div class="form-group col-md-4 mb-2" id="account_div">
                                <label for="account_id" class="form-label">Account</label>
                                <select class="form-select" id="account_id" name="account_id" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{$salesInvoicePayment->salesPayment->account_id == $account->id ? 'selected': ''}}>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group col-md-4 mb-2" id="card_div" style="display: none">
                                <label for="account_id" class="form-label">Saved Cards</label>
                                <select class="form-select" id="card_no" name="card_id" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach ($salesInvoicePayment->salesPayment->customer->user->savedCards as $card)
                                        <option value="{{$card->id ?? ''}}" {{$card->id == $salesInvoicePayment->salesPayment->card_id ? 'selected':''}}>************{{$card->card_last_four ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3 " id="cheque_div" style="display: none">
                                <label for="cheque" class="form-label" >Cheque No</label>
                                <div class="input-group">
                                    {{-- <span class="input-group-text"><i class="far fa-sticky-note"></i></span> --}}
                                    <input class="form-control" name="cheque_no" type="text" value="{{$salesInvoicePayment->salesPayment->cheque_no ?? ''}}"
                                        id="cheque" required>
                                </div>
                            </div>

                            <div class="form-group col-md-3" id="receipt_div" style="display: none">
                                <label for="receipt" class="form-label">Receipt No</label>
                                <div class="input-group">
                                    {{-- <span class="input-group-text"><i class="far fa-sticky-note"></i></span> --}}
                                    <input class="form-control" name="receipt_no" type="text" value="{{$salesInvoicePayment->salesPayment->reciept_no ?? ''}}"
                                        id="receipt" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label for="total_pay" class="form-label">Paid Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-money-bill-alt"></i></span>
                                    <input class="form-control" name="paid_amount" id="paid_amount"
                                        value="{{ $salesInvoicePayment->paid_amount }}" type="number" required step="any">
                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="pay_date" class="form-label">Payment Date </label>
                                    <input class="form-control subheading" id="pay_date" type="date"
                                        value="<?php echo date('Y-m-d'); ?>" name="payment_date"/>

                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 mb-2">
                                <label for="description" class="form-label">Note</label>
                                <textarea class="form-control" rows="3" name="description" id="description"></textarea>
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

        //     let totalDue = {{ $salesInvoicePayment->saleInvoice->sale->amount_due }};
        //     let paidAmount = {{ $salesInvoicePayment->paid_amount }};

        //     let paidAmountInput = $(this).val();

        //     if(paidAmountInput < paidAmount){
        //        let  totalDueAmount = parsFloat(totalDue) + paidAmountInput;
        //         // totalDue = totalDue - paidAmountInput;
        //         $('#totalDue').val(totalDueAmount);
        //     }

        //     // if (paidAmount <= totalDue) {
        //     //     totalDueInput = $('#totalDue').val();

        //     //     totalPay = totalDueInput - paidAmount;
        //     //     // alert(totalPay);
        //     //     // $(this).val(paidAmount);
        //     //     $('#totalDue').val(totalPay);

        //     // } else {
        //     //     $(this).val({{ $salesInvoicePayment->saleInvoice->sale->amount_due }});
        //     // }

        // });


        $(document).on('input', '#paid_amount', function() {
            let totalDue = parseFloat('{{ $salesInvoicePayment->saleInvoice->sale->amount_due }}') + parseFloat('{{ $salesInvoicePayment->paid_amount }}');
            let paidAmountInput = parseFloat($(this).val());

            // If the input paid amount is less than or equal to the total due, update the total due
            if (paidAmountInput <= totalDue) {
                let newTotalDue = totalDue - paidAmountInput;
                $('#totalDue').val(newTotalDue.toFixed(2));
            }
            else if (paidAmountInput <= 0) {
            // If the paid amount is less than or equal to 0, set it to 0
                $(this).val(0);
            }
            else {
                // If the input paid amount is greater than the total due, set it to the total due
                $(this).val(totalDue.toFixed(2));
                $('#totalDue').val('0.00');
            }
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
            }else if (method === 'Card') {
                $('#card_div').css('display', 'block');
                $('#card_no').prop('required', true);
            }
        });
        $(document).ready(function () {
            $('#payment_method').trigger('change');
        });
    </script>
@endsection
