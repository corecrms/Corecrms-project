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
        <div class="container-fluid py-5 px-4">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Email Setting</h3>
            </div>
            @include('back.layout.errors')
            <form action="{{ route('setting.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="card rounded-3 border-0 card-shadow mt-5 p-0">
                    <div class="card-header p-3 border-0">
                        <p class="m-0">SMTP Settings</p>
                    </div>
                    <div class="card-body p-3">
                        <div class="row fw-bold">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_host">Host <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control subheading mt-1" id="smtp_host" name="smtp_host" value="{{$setting->smtp_host ?? ''}}" required/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_port">Port <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control subheading mt-1" id="smtp_port" name="smtp_port" value="{{$setting->smtp_port ?? ''}}" required/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_username">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control subheading mt-1" id="smtp_username" name="smtp_username" value="{{$setting->smtp_username ?? ''}}" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row fw-bold mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_password">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control subheading mt-1" id="smtp_password" name="smtp_password" value="{{$setting->password ?? ''}}" required/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_encryption">Encryption <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control subheading mt-1" placeholder="SSL" id="smtp_encryption" name="smtp_encryption" value="{{$setting->smtp_encryption ?? ''}}" required/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_address">From Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control subheading mt-1"  id="smtp_address" name="smtp_address" value="{{$setting->smtp_address ?? ''}}" required />
                                </div>
                            </div>
                        </div>
                        <div class="row fw-bold mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_from_name">From Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control subheading mt-1" id="smtp_from_name" name="smtp_from_name" value="{{$setting->smtp_from_name ?? ''}}" required/>
                                </div>
                            </div>
                        </div>
                        <button class="btn save-btn text-white mt-3" type="submit">Submit</button>
                    </div>
                </div>


            </form>


        </div>
    </div>
@endsection
