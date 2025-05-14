@extends('back.layout.app')
@section('title', 'Add Purchase Template')
@section('content')
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            @include('back.layout.errors')
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 25%">Create Purchase payment</h3>
            </div>


            <form class="container-fluid" action="{{ route('purchase-payments.store') }}" method="POST">
                @csrf
                <div class="card card-shadow rounded-3 border-0 mt-5">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-12  col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="PurchaseSupplierId">Supplier</label>
                                    <select class="form-control form-select" name="vendor_id" id="PurchaseSupplierId"
                                        aria-label="Default select example" required>
                                        <option value="" disabled selected>Select Supplier</option>
                                        @if ($vendors->count() > 0)
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->id }}" data-vendor="{{ json_encode($vendor) }}"
                                                    data-purchase="{{ json_encode($vendor->purchases) }}">
                                                    {{ $vendor->user->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No Supplier Found</option>
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="form-group col-lg-4 col-md-4">
                                <label for="totalAmount">Purchase Total</label>
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

                            <div class="form-group col-lg-12 col-md-12" id="invoiceDiv">
                                <label for="invoice_id">Purchase</label>
                                <select class="form-control form-select" name="invoice_id" id="invoice_id" required>
                                    <option value="" disabled selected>Select Purchase</option>

                                </select>
                            </div>
                            <div id="sortable-table" class="mt-5"></div>

                            <div class="form-group col-md-4 mb-2">
                                <label for="total_pay" class="form-label">Total Payment</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-money-bill-alt"></i></span>
                                    <input class="form-control" readonly name="total_pay" id="total_pay" type="number"
                                        required>
                                </div>
                            </div>
                            {{-- <div class="form-group col-md-6 mb-2">
                                <label for="account_id" class="form-label">Account</label>
                                <select class="form-select" id="account_id" name="account_id" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group col-md-4 mb-2">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="Bank" selected>Bank</option>
                                    <option value="Card">Credit Card</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 mb-2" id="account_div">
                                <label for="account_id" class="form-label">Account</label>
                                <select class="form-select" id="account_id" name="account_id" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4 mb-2" id="card_div" style="display: none">
                                <label for="account_id" class="form-label">Saved Cards</label>
                                <select class="form-select" id="card_no" name="card_id" required>
                                    <option value="" disabled selected>Select Card</option>
                                    @foreach ($cards as $card)
                                        <option value="{{ $card->id }}">***********{{ $card->card_last_four ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group col-md-3 mb-2" id="cheque_div" style="display: none">
                                <label for="cheque" class="form-label">Cheque No</label>
                                <div class="input-group">
                                    <input class="form-control" name="cheque_no" type="text" value=""
                                        id="cheque" required>
                                </div>
                            </div>

                            <div class="form-group col-md-3 mb-2" id="receipt_div" style="display: none">
                                <label for="receipt" class="form-label">Receipt No</label>
                                <div class="input-group">
                                    <input class="form-control" name="receipt_no" type="text" value=""
                                        id="receipt" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_date" class="mb-1 fw-bold">Payment Date </label>
                                    <input class="form-control subheading" name="payment_date" id="payment_date"
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
                {{-- <div class="row">

                </div> --}}
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
        $(document).on('change', '#PurchaseSupplierId', function() {

            var vendor = $(this).find(':selected').data('vendor');
            var purchase = $(this).find(':selected').data('purchase');

            $('#totalDue').val(vendor.total_due_invoice);
            $('#totalPaid').val(vendor.total_paid_invoice);
            $('#totalAmount').val(vendor.total_amount);
            var invoiceSelect = $('#invoice_id');

            // Clear the invoice select field
            invoiceSelect.empty();
            invoiceSelect.append('<option value="" disabled selected>Select Purchase</option>');
            console.log(purchase);
            // Populate the invoice select field with the vendor's purchase invoices
            purchase.forEach(function(purchase) {
                if (purchase.invoice !== null) {
                    // console.log(purchase.invoice);
                    if (purchase.amount_due <= 0) {
                        return;
                    }

                    invoiceSelect.append('<option value="' + purchase.id + '" data-invoice=\'' + JSON
                        .stringify(purchase.invoice) + '\' data-vendor=\'' + JSON.stringify(vendor) +
                        '\'data-purchase=\'' + JSON.stringify(purchase) + '\'>' + purchase.invoice
                        .invoice_number + '</option>');
                }
            });

        });
        // let itemsSet = new Set();
        // $(document).on('change', '#invoice_id', function() {
        //     var invoice = $(this).find(':selected').data('invoice');
        //     let vendor = $(this).find(':selected').data('vendor');
        //     let purchase = $(this).find(':selected').data('purchase');

        //     if(purchase.amount_due <= 0){
        //         toastr.success('This invoice has been paid');
        //         this.value = '';
        //         return;
        //     }
        //     console.log(invoice);

        //     let invoice_number = invoice.invoice_number;
        //     let invoice_id = invoice.id;
        //     let invoice_total = purchase.grand_total;
        //     let invoice_due =  purchase.amount_due;
        //     console.log(invoice_number, invoice_total, invoice_due);
        //     if (itemsSet.has(invoice_id)) {
        //         toastr.error('This invoice has already been added');
        //         this.value = '';
        //         return;
        //     }
        //     appendItem(invoice_id,invoice_number,invoice_total,invoice_due);
        // });
        // let items = [];
        // let itemCount = 1;

        // function appendItem(invoice_id,invoice_number, invoice_total, invoice_due) {
        //     itemsSet.add(invoice_id);
        //     let newRow =
        //         `
    //             <div class="col-md-11 m-auto">
    //                 <div class="card bg-light border-light mb-3">
    //                     <div class="card-header d-flex">
    //                         <span class="card-title w-100">${invoice_number} Purchase Details</span>
    //                         <input type="hidden"  value="${invoice_id}" name="items[${itemCount}][invoice_id]">
    //                         <button type="button" class="btn btn-danger float-right remove-item"><i class="fas fa-times"></i></button>
    //                     </div>
    //                     <div class="card-body">
    //                         <div class="row">
    //                             <div class="form-group col-md-3">
    //                                 <label for="invoiceNumber">Purchase No</label>
    //                                 <input type="text" id="invoiceNumber-${itemCount}" value="${invoice_number}" readonly="readonly" class="form-control">
    //                             </div>
    //                             <div class="form-group col-md-3">
    //                                 <label for="invoiceTotal">Purchase Total</label>
    //                                 <input type="text" id="invoiceTotal-${itemCount}" value="${invoice_total}" readonly="readonly" class="form-control">
    //                             </div>
    //                             <div class="form-group col-md-3">
    //                                 <label for="invoiceDue">Purchase Due</label>
    //                                 <input type="text" id="invoiceDue-${itemCount}" value="${invoice_due}" readonly="readonly" class="form-control">
    //                             </div>
    //                             <div class="form-group col-md-3">
    //                                 <label for="paidAmount">Paid Amount</label>
    //                                 <input type="number" step="any" id="paidAmount-${itemCount}" name="items[${itemCount}][pay_amount]" placeholder="Enter an amount" required="required" min="0" max="${invoice_due}" value="0" class="form-control paidAmount">
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>

    //             </div>`;

        //     $('#sortable-table').append(newRow);
        //     itemCount++;
        // }

        $(document).on('change', '.paidAmount', function() {
            var totalPay = 0;

            $('.paidAmount').each(function() {
                totalPay += Number($(this).val());
            });

            $('#total_pay').val(totalPay);
        });


        // $(document).on('click', '.remove-item', function(e) {
        //     e.preventDefault();
        //     let invoiceId = $(this).closest('.card').find('input[type="hidden"]').val();
        //     itemsSet.delete(invoiceId);
        //     $(this).closest('.card').remove();
        //     // calculateTotal();
        // });

        let itemsSet = new Set(); // To keep track of added invoice IDs
        let itemCount = 1;

        $(document).on('change', '#invoice_id', function() {
            var invoice = $(this).find(':selected').data('invoice');
            let vendor = $(this).find(':selected').data('vendor');
            let purchase = $(this).find(':selected').data('purchase');

            if (purchase.amount_due <= 0) {
                toastr.success('This invoice has been paid');
                this.value = '';
                return;
            }

            let invoice_id = invoice.id;
            let invoice_number = invoice.invoice_number;
            let invoice_total = purchase.grand_total;
            let invoice_due = purchase.amount_due;

            // Check if the invoice has already been added
            if (itemsSet.has(invoice_id)) {
                toastr.error('This invoice has already been added');
                this.value = '';
                return;
            }

            console.log(invoice_number, invoice_total, invoice_due);

            appendItem(invoice_id, invoice_number, invoice_total, invoice_due);
        });

        function appendItem(invoice_id, invoice_number, invoice_total, invoice_due) {
            // Add invoice ID to the set to track the added items
            itemsSet.add(invoice_id);

            let newRow = `
            <div class="col-md-11 m-auto invoice-row" data-invoice-id="${invoice_id}">
                <div class="card bg-light border-light mb-3">
                    <div class="card-header d-flex">
                        <span class="card-title w-100">${invoice_number} Purchase Details</span>
                        <input type="hidden" value="${invoice_id}" name="items[${itemCount}][invoice_id]">
                        <button type="button" class="btn btn-danger float-right remove-item"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="invoiceNumber">Purchase No</label>
                                <input type="text" id="invoiceNumber-${itemCount}" value="${invoice_number}" readonly="readonly" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="invoiceTotal">Purchase Total</label>
                                <input type="text" id="invoiceTotal-${itemCount}" value="${invoice_total}" readonly="readonly" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="invoiceDue">Purchase Due</label>
                                <input type="text" id="invoiceDue-${itemCount}" value="${invoice_due}" readonly="readonly" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="paidAmount">Paid Amount</label>
                                <input type="number" step="any" id="paidAmount-${itemCount}" name="items[${itemCount}][pay_amount]" placeholder="Enter an amount" required="required" min="0" max="${invoice_due}" value="0" class="form-control paidAmount">
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

            $('#sortable-table').append(newRow);
            itemCount++;
        }

        // Event handler for removing an item and removing it from the itemsSet
        $(document).on('click', '.remove-item', function() {
            let invoiceId = $(this).closest('.invoice-row').data('invoice-id'); // Get invoice ID from the row
            itemsSet.delete(invoiceId); // Remove the invoice ID from the set
            $(this).closest('.invoice-row').remove(); // Remove the UI element
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
        $('#payment_method').trigger('change');
    </script>
@endsection
