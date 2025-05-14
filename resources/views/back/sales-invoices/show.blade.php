@extends('back.layout.app')
@section('title', 'Products')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="page-title">
                <div class="row justify-content-between align-items-center">
                    <div
                        class="col-xl-4 col-lg-4 col-md-4 d-flex align-items-center justify-content-between justify-content-md-start mb-3 mb-md-0">
                        <div class="d-inline-block">
                            <h5 class="h4 d-inline-block font-weight-400 mb-0 "> Invoice Detail
                            </h5>
                        </div>
                    </div>

                </div>
            </div>

            @if ($salesInvoice->status != 'Paid')
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="timeline timeline-one-side" data-timeline-content="axis"
                                    data-timeline-axis-style="dashed">
                                    <div class="timeline-block d-inline-flex">
                                        <div class="timeline-content">
                                            <div class="text-sm h6">Create Invoice</div>
                                            <small><i class="fas fa-clock me-1"></i> Created on
                                                {{ getDateFormat($salesInvoice->created_at) }}</small>
                                        </div>
                                    </div>
                                    <div class="timeline-content float-end">
                                        <a href="{{ route('sales-invoices.edit', $salesInvoice) }}"
                                            class="edit-icon btn btn-primary btn-sm mx-1" title="Edit"><i
                                                class="fas fa-pencil-alt me-1"></i> Edit</a>

                                        @if ($salesInvoice->status == 'Draft')
                                            <a href={{-- "{{ route('sales-invoices.sendInvoiceByEmail', $salesInvoice) }}" --}} class="edit-icon btn btn-success btn-sm mx-1"
                                                title="Send"><i class="far fa-paper-plane me-1"></i> Send</a>
                                        @endif
                                        <button class="edit-icon btn btn-secondary btn-sm mx-1" title="Add Receipt"
                                            data-bs-toggle="modal" data-bs-target="#receiptModal"><i
                                                class="far fa-file  me-1"></i> Add Receipt</button>
                                    </div>

                                    {{-- <div class="timeline-block">
                                <span class="timeline-step timeline-step-sm bg-primary border-primary text-white"><i
                                        class="fas fa-plus-circle" aria-hidden="true"></i></span>
                                <div class="timeline-content">
                                    <div class="text-sm h6">Create Invoice</div>
                                    <div class="Action">
                                        <a href="https://accounting.itsolz.tech/invoice/49/edit"
                                            class="edit-icon float-right" data-toggle="tooltip"
                                            data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                    </div>
                                    <small><i class="fas fa-clock mr-1"></i>Created on Nov 21, 2023</small>
                                </div>
                            </div>
                            <div class="timeline-block">
                                <span class="timeline-step timeline-step-sm bg-warning border-warning text-white"><i
                                        class="fas fa-envelope"></i></span>
                                <div class="timeline-content">
                                    <div class="text-sm h6 ">Send Invoice</div>

                                    <small><i class="fas fa-clock mr-1"></i>Sent on Nov 21, 2023</small>
                                </div>
                            </div>
                            <div class="timeline-block">
                                <span class="timeline-step timeline-step-sm bg-info border-info text-white"><i
                                        class="far fa-money-bill-alt"></i></span>
                                <div class="timeline-content">
                                    <div class="text-sm h6 ">Get Paid</div>
                                    <a href="#" data-url="https://accounting.itsolz.tech/invoice/49/payment"
                                        data-ajax-popup="true" data-title="Add Receipt" class="edit-icon float-right"
                                        data-toggle="tooltip" data-original-title="Add Receipt"><i
                                            class="far fa-file"></i></a>
                                    <small>Awaiting payment</small>
                                </div>
                            </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row justify-content-between align-items-center mb-3">
                <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                    {{-- <div class="all-button-box mx-2">
                    <a href="https://accounting.itsolz.tech/invoice/49/payment/reminder"
                        class="btn btn-xs btn-white btn-icon-only width-auto">Receipt Reminder</a>
                </div> --}}
                    @if (!($salesInvoice->status == 'Draft'))
                        <div class="all-button-box mx-2">
                            <a href={{-- "{{ route('order.sendInvoiceByEmail', $salesInvoice) }}" --}}
                                class="btn btn-xs btn-white btn-outline-danger btn-icon-only width-auto">Resend Invoice</a>
                        </div>
                    @endif
                    <div class="all-button-box">
                        <a href={{-- "{{ route('order.pdf', $salesInvoice) }}" target="_blank" --}}
                            class="btn btn-xs btn-white btn-outline-dark btn-icon-only width-auto">Download</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice">
                                <div class="invoice-print">
                                    <div class="row invoice-title mt-2">
                                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                            <h2>Invoice</h2>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                                            <h3 class="invoice-number">{{ $salesInvoice->invoice_number }}</h3>
                                        </div>
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 text-md-right">
                                            <strong>Shipped To :</strong><br><br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Status :</strong><br>
                                            <span class="badge  bg-info">{{ $salesInvoice->status }}</span>
                                        </div>
                                        <div class="col-md-4 text-md-center">
                                            <strong>Issue Date :</strong><br>
                                            {{-- {{ getDateFormat($salesInvoice->issue_date) }} --}}
                                        </div>
                                        <div class="col-md-4 text-md-end">
                                            <strong>Due Date :</strong><br>
                                            {{-- {{ getDateFormat($salesInvoice->due_date) }} --}}
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="fw-bold">Product Summary</div>
                                            <small>All items here cannot be deleted.</small>
                                            <div class="table-responsive mt-2">
                                                <!--  -->
                                                <table class="table mb-0 table-striped invoice-table">
                                                    <thead class="bg-active">
                                                        <tr class="tr">
                                                            <th data-width="40" class="text-dark">#</th>
                                                            <th class="text-dark">Product</th>
                                                            <th class="text-dark">Quantity</th>
                                                            <th class="text-dark">Rate</th>
                                                            <th class="text-dark">Discount</th>
                                                            <th class="text-right text-dark" width="12%">Price<br>
                                                                <small class="text-danger font-weight-bold">before tax
                                                                    &amp; discount</small>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salesInvoice->salesInvoiceDetails as $key => $item)
                                                            <tr>
                                                                <td>{{ ++$key }}</td>
                                                                <td>{{ $item->product->name }}</td>
                                                                <td class="qty text-start">{{ $item->qty }}</td>
                                                                <td class="price">USD{{ $item->price }}</td>
                                                                <td> USD<span class="discount">
                                                                        {{ $item->discount ? $item->discount : 0.0 }}
                                                                    </span>

                                                                </td>
                                                                <td>USD{{ number_format($item->price * $item->qty, 2) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td data-width="40"></td>
                                                            <td class="text-start" id=""><b>Total</b></td>
                                                            <td class="text-start" id="total_qty"><b></b></td>
                                                            <td class="text-start" id="total_price"><b></b></td>
                                                            <td class="text-start" id="total_discount"><b></b></td>
                                                            <td class="text-start"> </td>

                                                        </tr>
                                                        <tr class="d-none tr2">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right"><b>Sub Total</b></td>
                                                            <td class="text-right">RS47,600.00</td>
                                                        </tr>
                                                        <tr class="tr2">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="blue-text text-right"><b>Total</b></td>
                                                            <td class="blue-text text-right">USD
                                                                {{ $salesInvoice->net_amount }}
                                                            </td>
                                                        </tr>
                                                        <tr class="tr2">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right"><b>Paid</b></td>
                                                            <td class="text-right">
                                                                USD
                                                                {{ $salesInvoice->paid_amount ? $salesInvoice->paid_amount : '0.00' }}
                                                            </td>
                                                        </tr>
                                                        <tr class="tr2">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right"><b>Credit Note</b></td>
                                                            <td class="text-right">USD
                                                                {{ $salesInvoice->creditNotes ? $salesInvoice->salesInvoiceCreditNotes->sum('amount') : '0.00' }}
                                                            </td>
                                                        </tr>
                                                        <tr class="tr2">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right"><b>Due</b></td>
                                                            <td class="text-right">
                                                                USD
                                                                @if ($salesInvoice->net_amount - $salesInvoice->paid_amount > 0)
                                                                    {{ $salesInvoice->net_amount - $salesInvoice->paid_amount }}
                                                                @else
                                                                    0.00
                                                                @endif
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                                <!--  -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h5 class="h4 d-inline-block font-weight-400 mb-4">Receipt Summary</h5>
                    <div class="card">
                        <div class="card-body py-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th class="text-dark">Date</th>
                                            <th class="text-dark">Amount</th>
                                            <th class="text-dark">Payment Type</th>
                                            <th class="text-dark">Account</th>
                                            <th class="text-dark">Reference</th>
                                            <th class="text-dark">Description</th>
                                            <th class="text-dark">Action</th>
                                        </tr>

                                        @foreach ($salesInvoice->salesInvoicePayments as $payment)
                                            <tr>
                                                <td>{{ getDateFormat($payment->payment_date) }}</td>
                                                <td>USD{{ $payment->amount }}</td>
                                                <td>Manually</td>
                                                <td> {{ $payment->payment_method }}</td>
                                                <td>{{ $payment->reference_number }}</td>
                                                <td>{{ $payment->description ? $payment->description : '--' }}</td>
                                                <td>
                                                    <form method="POST" class="delete-payment-form"
                                                        action="{{ route('sales-invoices.payment.destroy', ['salesInvoice' => $salesInvoice, 'salesInvoicePayment' => $payment]) }}"
                                                        id="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-white btn-transparent btn-icon"
                                                            title="Delete"><i
                                                                class="fas fa-trash text-danger"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h5 class="h4 d-inline-block font-weight-400 mb-4">Credit Note Summary</h5>
                    <div class="card">
                        <div class="card-body py-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th class="text-dark">Date</th>
                                            <th class="text-dark">Amount</th>
                                            <th class="text-dark">Reference</th>
                                            <th class="text-dark">Description</th>
                                        </tr>
                                        @if ($salesInvoice->salesInvoiceCreditNotes)
                                            @foreach ($salesInvoice->salesInvoiceCreditNotes as $creditNote)
                                                <tr>
                                                    <td>{{ getDateFormat($creditNote->credit_date) }}</td>
                                                    <td>USD {{ $creditNote->amount }}</td>
                                                    <td>{{ $creditNote->reference_number }}</td>
                                                    <td>{{ $creditNote->description ? $creditNote->description : '--' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center">No Credit Note Found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">Add Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action= "{{ route('sales-invoices.payment.store', $salesInvoice) }}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6 mb-2">
                                <label for="date" class="form-label">Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <input class="form-control" name="payment_date" type="date" id="date"
                                        required value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-2">
                                <label for="amount" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-money-bill-alt"></i></span>
                                    <input class="form-control" name="amount" type="text"
                                        value="{{ $salesInvoice->net_amount - $salesInvoice->paid_amount }}"
                                        id="amount" required>
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
                            <div class="col-md-12">
                                <input type="submit" value="Add" class="btn btn-primary">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="fw-bolder">Are You Sure?</p>
                    <p>This action cannot be undone. Do you want to continue?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn theme_btn_blue" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha2/dist/js/bootstrap.min.js"></script> --}}

    <script>
        $(document).ready(function() {

            $(document).ready(function() {
                $(".delete-payment-form").submit(function() {
                    var decision = confirm(
                        "Are you sure, You want to Delete this Payment Receipt?");
                    if (decision) {
                        return true;
                    }
                    return false;
                });
            });

            renderTotals();
        });

        function renderTotals() {
            let totalQty = 0;
            let totalPrice = 0;
            let totalDiscount = 0;
            $('.qty').each(function() {
                totalQty += parseInt($(this).text());
            });
            $('#total_qty b').text(totalQty);

            $('.price').each(function() {
                let price = $(this).text().replace('USD', '') || 0;
                price = parseFloat(price).toFixed(2);
                totalPrice += parseFloat(price);
            });
            $('#total_price b').text("USD " + totalPrice.toFixed(2));

            $('.discount').each(function() {
                let discount = $(this).text() || 0;
                discount = parseFloat(discount).toFixed(2);
                totalDiscount = parseFloat(totalDiscount) + parseFloat(discount);
            });
            $('#total_discount b').text("USD " + totalDiscount.toFixed(2));
        }

        $(document).on('click', '#confirmDelete', function() {
            let url = $(this).data('url');
            $('#delete-form').submit();
        });
    </script>
@endsection
