@extends('user.dashboard-layout.app')


@section('content')
    <div class="container-xxl container-fluid pt-4 px-4 mb-5">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0">Orders View</h3>
        </div>

        <div class="card card-shadow border-0 mt-4 rounded-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mt-2">
                        <h2 class="heading">
                            Order#
                            <span class="fw-bold">{{ $sale->invoice->invoice_id ?? '1' }}</span>
                        </h2>
                    </div>
                    <div class="col-md-4 mt-2">
                        <h2 class="heading">
                            Status: <span class="fw-bold">{{ ucwords($sale->status ?? '') }}</span>
                        </h2>
                    </div>
                    <div class="col-md-4 mt-1 text-end">
                        <a href="{{ route('sales.downloadInvoice', $sale->id) }}" class="btn pdf rounded-3 mt-2"
                            id="download-pdf">Download Invoice <i class="bi bi-file-earmark"></i>
                        </a>
                        <a href="{{ route('user.orders.index') }}" class="btn rounded-3 mt-2 btn-sm">
                            Back <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                    {{-- <div class="col-md-4 mt-2 text-end">
                        <button href="#" class="btn rounded-3 mt-2 excel-btn me-2">
                            Excel <i class="bi bi-file-earmark-text"></i>
                        </button>
                        <button class="btn pdf rounded-3 mt-2">
                            Pdf <i class="bi bi-file-earmark"></i>
                        </button>
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <p class="text-secondary m-0">Billing address</p>
                        <div class="card border-0 card-shadow disabled-bg mt-3">
                            <div class="card-body">
                                <div class="d-flex gap-2 align-items-center">
                                    <i class="fa-solid fa-house-chimney-window fs-4"></i>
                                    <p class="m-0">{{ $sale->customer->user->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 card-shadow disabled-bg mt-3">
                            <div class="card-body">
                                <div class="d-flex gap-2">
                                    <i class="fa-solid fa-location-dot fs-4"></i>
                                    <div>
                                        <p class="m-0 border-bottom border-dark pb-1">
                                            {{ $sale->customer->user->address ?? '' }}
                                        </p>
                                        <p class="m-0 mt-2">{{ $sale->customer->user->email ?? '' }}</p>
                                        <p class="m-0 mt-1">{{ $sale->customer->city ?? '' }}</p>
                                        <p class="m-0 mt-1">{{ $sale->customer->country }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 card-shadow disabled-bg mt-3">
                            <div class="card-body">
                                <div class="d-flex gap-2">
                                    <i class="fa-solid fa-phone fs-4"></i>
                                    <p class="m-0">{{ $sale->customer->user->contact_no }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <p class="text-secondary m-0">Shipping address</p>
                        <div class="card border-0 card-shadow disabled-bg mt-3">
                            <div class="card-body">
                                <div class="d-flex gap-2 align-items-center">
                                    <i class="fa-solid fa-house-chimney-window fs-4"></i>
                                    <p class="m-0"> {{ $sale->shipingAddress->name ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 card-shadow disabled-bg mt-3">
                            <div class="card-body">
                                <div class="d-flex gap-2">
                                    <i class="fa-solid fa-location-dot fs-4"></i>
                                    <div>
                                        <p class="m-0 border-bottom border-dark pb-1">
                                            {{ $sale->shipingAddress->address }}
                                        </p>
                                        <p class="m-0 mt-2">{{ $sale->shipingAddress->email ?? '' }}</p>
                                        <p class="m-0 mt-1">{{ $sale->shipingAddress->city ?? '' }}</p>
                                        <p class="m-0 mt-1">{{ $sale->shipingAddress->country }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 card-shadow disabled-bg mt-3">
                            <div class="card-body">
                                <div class="d-flex gap-2">
                                    <i class="fa-solid fa-phone fs-4"></i>
                                    <p class="m-0">
                                        {{ $sale->customer->user->contact_no }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <p class="text-secondary m-0"></p>
                        {{-- <div class="card border-0 card-shadow disabled-bg mt-3">
                            <div class="card-body">
                                <div class="d-flex gap-2 align-items-center">
                                    <i class="fa-solid fa-box fs-4"></i>
                                    <p class="m-0">Lorem ipsum dolor sit amet consectetur</p>
                                </div>
                            </div>
                        </div> --}}
                        <div>
                            <p class="text-secondary m-0 mt-3">Shipping Method</p>
                            <div class="card border-0 card-shadow disabled-bg mt-2">
                                <div class="card-body">
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="fa-solid fa-bus fs-4"></i>
                                        <p class="m-0">{{ $sale->shipping_method ?? '...' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="text-secondary m-0 mt-3">Tracking number</p>
                            <div class="card border-0 card-shadow disabled-bg mt-2">
                                <div class="card-body">
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="fa-solid fa-box fs-4"></i>
                                        <p class="m-0">{{ $sale->tracking_number ?? '...' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="text-secondary m-0 mt-3">Payment method</p>
                            <div class="card border-0 card-shadow disabled-bg mt-2">
                                <div class="card-body">
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="bi bi-credit-card-2-front-fill fs-4"></i>
                                        <p class="m-0">

                                            @if (count($sale->invoice->saleInvoicePayment) > 1)
                                                <span>Multiple</span>
                                            @else
                                                @foreach ($sale->invoice->saleInvoicePayment as $payment)
                                                    @if ($payment->salesPayment->card)
                                                        {{ $payment->salesPayment->card->card_brand ?? 'Visa' }}:
                                                        (Last 4)
                                                        :
                                                        {{ $payment->salesPayment->card->card_last_four ?? '' }}
                                                    @else
                                                        {{ $payment->salesPayment->payment_method ?? '' }}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2 class="heading mt-2">
                        Items <span class="fw-bold">Ordered</span>
                    </h2>
                    <div class="disabled-bg mt-2 p-2 px-5 rounded-3">
                        <h2 class="heading">Order Date</h2>
                        <h2 class="heading fw-bold m-0">{{ $sale->date ?? '' }}</h2>
                    </div>
                </div>

                <!-- accordion -->
                <div class="accordion mt-3" id="accordionExample">
                    <div class="accordion-item rounded-4 border-0">
                        <h2 class="accordion-header rounded-4 border">
                            <button class="accordion-button collapsed rounded-4 heading" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                                aria-controls="collapseThree">
                                <i class="bi bi-phone-flip me-2"></i> Parts
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse border-0"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body border-0">
                                @foreach ($sale->productItems as $item)
                                    <div class="row">
                                        <div class="col-md-6 mt-2">
                                            <div class="card disabled-bg border-0 rounded-4 h-100">
                                                <div class="card-body">
                                                    <p class="text-secondary">Product name</p>
                                                    <div class=" border-bottom border-dark pb-2">
                                                        {{ $item->product->name ?? '' }} ({{ $item->product->sku ?? '' }})
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <div class="card disabled-bg border-0 rounded-4 h-100">
                                                <div class="card-body">
                                                    <p class="text-secondary">Price</p>
                                                    <div class=" border-bottom border-dark pb-2">
                                                        ${{ $item->price ?? '' }}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <div class="card disabled-bg border-0 rounded-4 h-100">
                                                <div class="card-body">
                                                    <p class="text-secondary">Quantity</p>
                                                    <div class=" border-bottom border-dark pb-2">
                                                        {{ $item->quantity ?? '' }}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <div class="card disabled-bg border-0 rounded-4 h-100">
                                                <div class="card-body">
                                                    <p class="text-secondary">Subtotal</p>
                                                    <div class=" border-bottom border-dark pb-2">
                                                        ${{ $item->sub_total ?? '' }}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="row mt-3">
                                    <div class="col-md-10"></div>
                                    <div class="col-md-2 disabled-bg p-2 rounded-3">
                                        <h2 class="heading fw-bold m-0">Subtotal: ${{ $sale->grand_total ?? '0.00' }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End accordion -->
                <div class="row mt-3">
                    <div class="col-md-7"></div>
                    <div class="col-md-5 disabled-bg rounded-3 p-3">
                        <div class="row border-bottom border-dark">
                            <div class="col-6">
                                <h2 class="heading text-secondary mb-1">Shipping Fees</h2>
                                <h2 class="heading text-secondary mb-1">Discount</h2>
                                <h2 class="heading fw-bold">Total</h2>
                            </div>
                            <div class="col-6">
                                <h2 class="heading text-secondary mb-1">${{ $sale->shipping ?? '0.00' }}</h2>
                                <h2 class="heading text-secondary mb-1">${{ $sale->discount ?? '0.00' }}</h2>
                                <h2 class="heading fw-bold">${{ number_format($sale->grand_total, 2) ?? '' }}</h2>
                            </div>
                        </div>
                        {{-- <div class="row border-bottom border-dark py-1">
                            <div class="col-6">
                                <h2 class="heading text-secondary m-0">
                                    American Express 1184
                                </h2>
                            </div>
                            <div class="col-6">
                                <h2 class="heading text-secondary m-0">$0.00</h2>
                            </div>
                        </div> --}}
                        <div class="row py-1">
                            <div class="col-6">
                                <h2 class="heading fw-bold m-0">Balance</h2>
                            </div>
                            <div class="col-6">
                                <h2 class="heading fw-bold m-0">${{ number_format($sale->amount_due, 2) ?? '' }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
