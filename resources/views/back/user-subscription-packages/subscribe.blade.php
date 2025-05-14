@extends('back.layout.app')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-12">
                        <div class="COnftainer_boxes">
                            <h1>Payment Gateway</h1>
                        </div>
                    </div>

                    <div class="col-lg-10 mx-auto">
                        <ul class="nav nav-pills mb-3 tabs_payment_btns" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-paywithcards-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-paywithcards" type="button" role="tab"
                                    aria-controls="pills-paywithcards" aria-selected="true">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadiocardspayments"
                                            id="paycheck" checked />
                                        <label class="form-check-label" for="paycheck">
                                            <span> Pay with card</span>
                                            <img src="dashassets/images/cards.png" alt="" />
                                        </label>
                                    </div>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-paypall-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-paypall" type="button" role="tab"
                                    aria-controls="pills-paypall" aria-selected="false">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadiocardspayments"
                                            id="  Paypallcheck" />
                                        <label class="form-check-label" for="  Paypallcheck">
                                            <span>Paypall</span>
                                            <img src="dashassets/images/paypall.png" alt="" />
                                        </label>
                                    </div>
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-paywithcards" role="tabpanel"
                                aria-labelledby="pills-paywithcards-tab">
                                <div class="payment_gateways_tabs">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h4>Enter your payment detail</h4>

                                            <form id="payment-form"
                                                action="{{ route('subscription.packages.subscribe', $subscriptionPackage->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="subscriptionPackage" id="plan"
                                                    value="{{ $subscriptionPackage->id }}">

                                                <div class="row">
                                                    <div class="col-xl-4 col-lg-4">
                                                        <div class="form-group">
                                                            <label for="">Name</label>
                                                            <input type="text" name="name" id="card-holder-name"
                                                                class="form-control" value=""
                                                                placeholder="Name on the card">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xl-4 col-lg-4">
                                                        <div class="form-group">
                                                            <label for="">Card details</label>
                                                            <div id="card-element"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-12 col-lg-12">
                                                        <hr>

                                                        <button type="submit" class="btn btn-primary" id="card-button"
                                                            data-secret="{{ $intent->client_secret }}">Purchase</button>

                                                    </div>
                                                </div>

                                            </form>

                                            <div class="row d-none">
                                                <div class="col-md-12 mt-3">
                                                    <label for="input1" class="form-label">Enter your card number: *
                                                        <span>*</span></label>
                                                    <input type="text" class="form-control" id="input1"
                                                        placeholder=" " />
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label for="input1" class="form-label">MM/YY:
                                                        <span>*</span></label>
                                                    <input type="text" class="form-control" id="input1"
                                                        placeholder=" " />
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label for="input1" class="form-label">CVC:
                                                        <span>*</span></label>
                                                    <input type="text" class="form-control" id="input1"
                                                        placeholder=" " />
                                                </div>

                                                <div class="col-md-12 mt-3">
                                                    <button class="btn green_theme" data-bs-toggle="modal"
                                                        data-bs-target="#modalsubmittsuccessfull" type="button">
                                                        <i class="fas fa-check me-1"></i>Submit Payment
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-md-0 mt-3">
                                            <h4>{{ $subscriptionPackage->name }} Plan</h4>

                                            <div class="border-bottom py-3">
                                                <h5>Plan Duration</h5>
                                                {{-- duration month --}}
                                                {{ $subscriptionPackage->duration }} months

                                            </div>
                                            <div class="border-bottom py-3">
                                                <ul class="total_list">
                                                    <li class="d-none">
                                                        <span>Save</span>
                                                        <span>$290</span>
                                                    </li>
                                                    <li>
                                                        <span>Sub Total</span>
                                                        <span>${{ $subscriptionPackage->price }}</span>
                                                    </li>
                                                    <li>
                                                        <span>Total:</span>
                                                        <span>{{ $subscriptionPackage->price }}$</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-paypall" role="tabpanel"
                                aria-labelledby="pills-paypall-tab">
                                <div class="payment_gateways_tabs">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h4>Enter your payment detail</h4>

                                            <div class="row">
                                                <div class="col-md-12 mt-3">
                                                    <label for="input1" class="form-label">Email Id
                                                        <span>*</span></label>
                                                    <input type="email" class="form-control" id="input1"
                                                        placeholder=" " />
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label for="input1" class="form-label">First Name
                                                        <span>*</span></label>
                                                    <input type="text" class="form-control" id="input1"
                                                        placeholder=" " />
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label for="input1" class="form-label">Last Name
                                                        <span>*</span></label>
                                                    <input type="text" class="form-control" id="input1"
                                                        placeholder=" " />
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label for="input1" class="form-label">
                                                        Username <span>*</span></label>
                                                    <input type="text" class="form-control" id="input1"
                                                        placeholder=" " />
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label for="input1" class="form-label">
                                                        Password <span>*</span></label>
                                                    <input type="password" class="form-control" id="input1"
                                                        placeholder=" " />
                                                </div>

                                                <div class="col-md-12 mt-3">
                                                    <button class="btn green_theme" data-bs-toggle="modal"
                                                        data-bs-target="#modalsubmittsuccessfull" type="button">
                                                        <i class="fas fa-check me-1"></i>Submit Payment
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-md-0 mt-3">
                                            <h4>Premium Plan</h4>

                                            <div class="border-bottom py-3">
                                                <h5>Billing Cycle</h5>
                                                <div class="billing_cycle">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="billingwithpaypall" id="billingwithpaypall1" />
                                                        <label class="form-check-label" for="billingwithpaypall1">
                                                            Annual <span>(save 25%)</span>
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="billingwithpaypall" id="billingwithpaypall2" checked />
                                                        <label class="form-check-label" for="billingwithpaypall2">
                                                            Monthly
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-bottom py-3">
                                                <ul class="total_list">
                                                    <li>
                                                        <span>Save</span>
                                                        <span>$290</span>
                                                    </li>
                                                    <li>
                                                        <span>Total:</span>
                                                        <span>380$</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stripe = Stripe('{{ env('STRIPE_KEY') }}')

            const elements = stripe.elements()
            const cardElement = elements.create('card')

            cardElement.mount('#card-element')

            const form = document.getElementById('payment-form')
            const cardBtn = document.getElementById('card-button')
            const cardHolderName = document.getElementById('card-holder-name')

            form.addEventListener('submit', async (e) => {
                e.preventDefault()

                cardBtn.disabled = true
                const {
                    setupIntent,
                    error
                } = await stripe.confirmCardSetup(
                    cardBtn.dataset.secret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: cardHolderName.value
                            }
                        }
                    }
                )

                if (error) {
                    cardBtn.disable = false
                } else {
                    let token = document.createElement('input')
                    token.setAttribute('type', 'hidden')
                    token.setAttribute('name', 'token')
                    token.setAttribute('value', setupIntent.payment_method)
                    form.appendChild(token)
                    form.submit();
                }
            });
        });
    </script>
@endsection
