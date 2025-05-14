@extends('user.dashboard-layout.app')

@section('content')
    <div class="container-xxl container-fluid pt-4 px-4 mb-5">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0 w-25">
                Device Return
            </h3>
        </div>

        <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">

            <div class="card-body pt-0">
                <form action="{{route('devices.store')}}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1"><span class="fw-bold">IMEI</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    placeholder="IMEI" required name="imei_number" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="fw-bold">Shipping Method</label>
                                <select class="form-control form-select subheading mt-1" aria-label="Default select example"
                                    id="exampleFormControlSelect1" name="shipping_method" required>
                                    <option>DHL</option>
                                    <option>FEDEX</option>
                                    <option>USPS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Tracking Number</label>
                                <input type="text" class="form-control subheading" id="tracking_number"
                                    placeholder="Tracking umnber" name="tracking_number"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Email</label>
                                <input type="email" class="form-control subheading" id="exampleFormControlInput1"
                                    placeholder="Email" required name="email" value="{{auth()->user()->email ?? ''}}"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1"><span class="fw-bold">Reference number
                                        (optional)</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1" placeholder="Reference Number" name="reference_number"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1"><span class="fw-bold">Address
                                        </span></label>
                                <input type="text" class="form-control subheading" placeholder="Address" id="exampleFormControlInput1" required value="{{auth()->user()->address ?? ''}}" name="address"/>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="comment" class="mb-1"><span class="fw-bold">Comments
                                        </span>
                                </label>
                                <textarea class="form-control subheading" id="comment" name="comment" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                    <button class="btn save-btn text-white mt-4">Submit</button>
                </form>
            </div>
        </div>
        <!-- Input Fields End -->
    </div>
@endsection
