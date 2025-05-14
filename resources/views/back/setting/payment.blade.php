@extends('back.layout.app')

@section('style')
    <style>
        /* The switch - the box around the slider */
        .switch {
            font-size: 17px;
            position: relative;
            display: inline-block;
            width: 3.5em;
            height: 2em;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: #d1d5db;
            border-radius: 50px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 25px;
            width: 53px;
        }

        .slider:before {
            position: absolute;
            content: "";
            display: flex;
            align-items: center;
            justify-content: center;
            height: 1.5em;
            width: 1.5em;
            inset: 0;
            background-color: white;
            border-radius: 50px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .switch input:checked+.slider {
            background: rgba(76, 73, 227, 1);
        }

        .switch input:focus+.slider {
            box-shadow: 0 0 1px rgba(76, 73, 227, 1);
        }

        .switch input:checked+.slider:before {
            transform: translateX(1.6em);
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">
                    Payment Gateway
                </h3>
            </div>
            @include('back.layout.errors')
            <form id="submit-payment-settings"  action="javascript:void(0);" method="post"
                enctype="multipart/form-data">
                @csrf

                <!-- Input Fields -->
                <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
                    <div class="card-header p-2 border-0">
                        <h4 class="mb-0 mt-2 heading text-start card-title">
                            Payment Gateway
                        </h4>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold my-3">Stripe Configuration</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">STRIPE_KEY <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Please! leave this field if you haven't changed it"
                                        name="stripe_key" value="{{$setting->stripe_key ?? ''}}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">STRIPE_SECRET <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Please! leave this field if you haven't changed it"
                                        name="stripe_secret" value="{{$setting->stripe_secret ?? ''}}" />
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row mt-3">
                            <div class="col-md-4 col-auto mt-2">
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2" for="delete_stripe_key">
                                        <input type="checkbox" name="delete_stripe_key" id="delete_stripe_key"
                                            {{ $setting->delete_stripe_key ?? '' == 1 ? 'checked' : '' }} />
                                        <span class="slider"></span>
                                    </label>
                                    <p class="m-0">Delete Stripe API keys </p>
                                </div>
                            </div>
                        </div> --}}

                        <h6 class="fw-bold mt-4 mb-2">PayPal Configuration</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">PAYPAL_CLIENT_ID</label>
                                    <input type="password" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Please! leave this field if you haven't changed it"
                                        name="paypal_client_id" value="{{$setting->paypal_client_id ?? ''}}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">PAYPAL_CLIENT_SECRET </label>
                                    <input type="password" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Please! leave this field if you haven't changed it"
                                        name="paypal_client_secret" value="{{$setting->paypal_client_secret ?? ''}}" />
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">PAYPAL_APP_ID </label>
                                    <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Please! leave this field if you haven't changed it"
                                        name="paypal_app_id" value="{{$setting->paypal_app_id ?? ''}}" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <button class="btn save-btn text-white mt-3">Submit</button>
                    </div>
                </div>
                <!-- Input Fields End -->

            </form>

        </div>
    </div>
@endsection

@section('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = $(this).serialize(); // Serialize the form data
            var delete_stripe_key = $('#delete_stripe_key').prop('checked') ? 1 : 0;
            formData += `&delete_stripe_key=${delete_stripe_key}`;
            $.ajax({
                url: "{{ route('setting.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    // Handle the successful response here
                    console.log(response);
                    location.reload();
                    // Optionally, you can redirect the user to another page
                    // window.location.href = "/success";
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

@endsection
