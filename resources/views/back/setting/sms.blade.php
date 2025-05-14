@extends('back.layout.app')

@section('style')
    <style>
        /* Style the tab */
        /* Style the buttons inside the tab */
        .tab button {
            /* display: block; */
            background-color: inherit;
            padding: 16px;
            width: 100%;
            border: none;
            /* outline: none; */
            text-align: left;
            cursor: pointer;
            /* transition: 0.3s; */
        }

        .tab button:hover {
            background: rgba(76, 73, 227, 0.1);
            border-left: 4px solid rgba(76, 73, 227, 1);
        }

        .tab button.active {
            background-color: rgba(76, 73, 227, 0.1);
            border-left: 4px solid rgba(76, 73, 227, 1);
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
              <h3 class="all-adjustment text-center pb-2 mb-0">Sms Setting</h3>
            </div>
            @include('back.layout.errors')
            <form action="{{ route('setting.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                    <!-- Input Fields -->
                    <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
                        <div class="card-header p-2 border-0">
                            <h4 class="mb-0 mt-2 heading text-start card-title">
                                Default sms gateway
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">Default SMS Gateway</label>
                                        <select class="form-control form-select subheading"
                                            aria-label="Default select example" id="exampleFormControlSelect1">
                                            <option>Default SMS Gateway</option>
                                            <option>Default SMS Gateway 1</option>
                                            <option>Default SMS Gateway 2</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <button class="btn save-btn text-white mt-3">Submit</button>
                        </div>
                    </div>

                    <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
                        <div class="card-header p-2 border-0">
                            <h4 class="mb-0 mt-2 heading text-start card-title">
                                TWILIO_SMS
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row mt-2">
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">TWILIO SID <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control subheading"
                                            id="exampleFormControlInput1" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">TWILIO TOKEN <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            placeholder="Please! leave this field if you haven't changed it"
                                            class="form-control subheading" id="exampleFormControlInput1" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">TWILIO FROM <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control subheading"
                                            id="exampleFormControlInput1" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 PT-0">
                            <button class="btn save-btn text-white mt-3">Submit</button>
                        </div>
                    </div>

                    {{-- <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
                        <div class="card-header p-2 border-0">
                            <h4 class="mb-0 mt-2 heading text-start card-title">
                                Nexmo (now Vonage)
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row mt-2">
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">NEXMO KEY <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control subheading"
                                            id="exampleFormControlInput1" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">NEXMO SECRET <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            placeholder="Please! leave this field if you haven't changed it"
                                            class="form-control subheading" id="exampleFormControlInput1" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">SMS FROM <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control subheading"
                                            id="exampleFormControlInput1" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 PT-0">
                            <button class="btn save-btn text-white mt-3">Submit</button>
                        </div>
                    </div>

                    <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
                        <div class="card-header p-2 border-0">
                            <h4 class="mb-0 mt-2 heading text-start card-title">InfoBip</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mt-2">
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">BASE URL</label>
                                        <input type="text" class="form-control subheading"
                                            id="exampleFormControlInput1" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">API KEY</label>
                                        <input type="text"
                                            placeholder="Please! leave this field if you haven't changed it"
                                            class="form-control subheading" id="exampleFormControlInput1" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">SMS sender number Or
                                            Name</label>
                                        <input type="text" class="form-control subheading"
                                            id="exampleFormControlInput1" required />
                                    </div>
                                </div>
                            </div>
                            <button class="btn save-btn text-white mt-3">Submit</button>
                        </div>
                        <div class="card-footer bg-transparent border-0 PT-0">
                            <div class="d-flex align-items-center">
                                <h4 class="heading fw-bold mt-2 fs-6">BASE_URL:</h4>
                                <p class="m-0">The Infobip data center used for API traffic</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <h4 class="heading fw-bold mt-2 fs-6">API_KEY:</h4>
                                <p class="m-0">Authentication method. See API documentation</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <h4 class="heading fw-bold mt-2 fs-6">
                                    SMS sender number or name:
                                </h4>
                                <p class="m-0">
                                    displayed on recipient's device as message sender.
                                </p>
                            </div>
                            <div class="d-flex align-items-center">
                                <h4 class="heading fw-bold mt-2 fs-6">
                                    Watsapp sender number:
                                </h4>
                                <p class="m-0">
                                    Registered WhatsApp sender number. Must be in international
                                    format.
                                </p>
                            </div>
                            <h4 class="heading fw-bold mt-1 fs-6">## Links</h4>
                            <h4 class="heading fw-bold mt-2 fs-6">
                                [API Reference](<a href="https://www.infobip.com/docs/api">https://www.infobip.com/docs/api<a>)
                            </h4>
                            <h4 class="heading fw-bold mt-2 fs-6">
                                [PHP Client for Infobip
                                API](<a href="https://github.com/infobip/infobip-api-php-client">https://github.com/infobip/infobip-api-php-client<a>)
                            </h4>
                        </div>
                    </div> --}}
                    <!-- Input Fields End -->

            </form>


        </div>
    </div>
@endsection
