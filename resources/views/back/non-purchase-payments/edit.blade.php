@extends('back.layout.app')
@section('title', 'Edit Non Purchase Payment')
@section('content')
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            @include('back.layout.errors')
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 25%">Edit Non Purchase Payment</h3>
            </div>


            <form class="container-fluid" action="{{ route('non-purchase-payments.update', $nonPurchaseInvoicePayment->id) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="card card-shadow rounded-3 border-0 mt-5">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-6  col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="SalesCustomerId">Vendor</label>
                                    <select class="form-control form-select" name="vendor_id" id="SalesCustomerId"
                                        aria-label="Default select example" required disabled>
                                        <option value="" disabled selected>Select Vendor</option>
                                        @if ($vendors->count() > 0)
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->id }}"  {{ $nonPurchaseInvoicePayment->vendor_id ?? '' == $vendor->id ? 'selected' : '' }}>
                                                    {{ $vendor->user->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No Vendor Found</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{-- type select option --}}
                            <div class="form-group col-md-6">
                                <label for="payment_type" class="form-label">Type</label>
                                <select class="form-select" id="payment_type" name="payment_type" required>
                                    <option value="pay"
                                        {{ $nonPurchaseInvoicePayment->payment_type ?? '' == 'pay' ? 'selected' : '' }}>Payment
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
                                                {{ $nonPurchaseInvoicePayment->account_id ?? '' == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4 mb-2">
                                    <label for="cheque" class="form-label">Cheque No</label>
                                    <div class="input-group">
                                        <input class="form-control" name="cheque_no" type="text"
                                            value="{{ $nonPurchaseInvoicePayment->cheque_no ?? '' }}" id="cheque">
                                    </div>
                                </div>

                                <div class="form-group col-md-4 mb-2">
                                    <label for="receipt" class="form-label">Receipt No</label>
                                    <div class="input-group">
                                        {{-- <span class="input-group-text"><i class="far fa-sticky-note"></i></span> --}}
                                        <input class="form-control" name="receipt_no" type="text"
                                            value="{{ $nonPurchaseInvoicePayment->reciept_no ?? '' }}" id="receipt">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label for="amount_pay" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-money-bill-alt"></i></span>
                                    <input class="form-control" name="amount_pay" id="amount_pay" type="number" required
                                        value="{{ $nonPurchaseInvoicePayment->amount_pay ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_date" class="mb-1 fw-bold">Payment Date </label>
                                    <input class="form-control subheading" id="payment_date" type="date"
                                        name="payment_date" value="{{ $nonPurchaseInvoicePayment->payment_date ?? '' }}" />

                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1" {{ $nonPurchaseInvoicePayment->status == 1 ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ $nonPurchaseInvoicePayment->status == 0 ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 mb-2">
                                <label for="description" class="form-label">Note</label>
                                <textarea class="form-control" rows="3" name="note" id="description">{{ $nonPurchaseInvoicePayment->note ?? '' }}</textarea>
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
