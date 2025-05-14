@extends('layouts.dashboard')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="container-fluid px-4">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('app.setting.update') }}" method="post" autocomplete="off">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-xl-12 col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="text-black main-title">App Settings</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group mb-3 pb-3">
                                                        <label class="font-w600">App Name: <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control " name="APP_NAME"
                                                            value="{{ $settings->APP_NAME }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-header">
                                            <h2 class="text-black main-title">SMTP Settings</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">MAIL HOST <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="MAIL_HOST" name="MAIL_HOST"
                                                            placeholder="" value="{{ $settings->MAIL_HOST }}"
                                                            type="text">
                                                    </div>
                                                    <div class="invalid-feedback">
                                                        Please enter a MAIL_HOST.
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">MAIL PORT <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="MAIL_PORT" name="MAIL_PORT"
                                                            placeholder="" value="{{ $settings->MAIL_PORT }}"
                                                            type="text">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">MAIL USERNAME <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="MAIL_USERNAME" name="MAIL_USERNAME"
                                                            placeholder="" value="{{ $settings->MAIL_USERNAME }}"
                                                            type="text">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">MAIL PASSWORD <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="MAIL_PASSWORD" name="MAIL_PASSWORD"
                                                            placeholder="" value="{{ $settings->MAIL_PASSWORD }}"
                                                            type="password">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">MAIL_ENCRYPTION <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="MAIL_ENCRYPTION"
                                                            name="MAIL_ENCRYPTION" placeholder=""
                                                            value="{{ $settings->MAIL_ENCRYPTION }}" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">MAIL_FROM_NAME <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="MAIL_FROM_ADDRESS"
                                                            name="MAIL_FROM_ADDRESS" placeholder=""
                                                            value="{{ $settings->MAIL_FROM_ADDRESS }}" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">MAIL_FROM_NAME <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="MAIL_FROM_NAME"
                                                            name="MAIL_FROM_NAME" placeholder=""
                                                            value="{{ $settings->MAIL_FROM_NAME }}" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="text-black main-title">GOOGLE SETTINGS</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="font-w600">GOOGLE_CLIENT_ID <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="GOOGLE_CLIENT_ID"
                                                            name="GOOGLE_CLIENT_ID" placeholder=""
                                                            value="{{ $settings->GOOGLE_CLIENT_ID }}" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="font-w600">GOOGLE_CLIENT_SECRET <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="GOOGLE_CLIENT_SECRET"
                                                            name="GOOGLE_CLIENT_SECRET" placeholder=""
                                                            value="{{ $settings->GOOGLE_CLIENT_SECRET }}" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="font-w600">GOOGLE_REDIRECT_URI <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="GOOGLE_REDIRECT"
                                                            name="GOOGLE_REDIRECT" placeholder=""
                                                            value="{{ $settings->GOOGLE_REDIRECT }}" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="text-black main-title">FACEBOOK SETTINGS</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="font-w600">FACEBOOK_CLIENT_ID <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="FACEBOOK_CLIENT_ID"
                                                            name="FACEBOOK_CLIENT_ID" placeholder=""
                                                            value="{{ $settings->FACEBOOK_CLIENT_ID }}" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="font-w600">FACEBOOK_CLIENT_SECRET <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="FACEBOOK_CLIENT_SECRET"
                                                            name="FACEBOOK_CLIENT_SECRET" placeholder=""
                                                            value="{{ $settings->FACEBOOK_CLIENT_SECRET }}"
                                                            type="text">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="font-w600">FACEBOOK_REDIRECT_URI <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="FACEBOOK_REDIRECT"
                                                            name="FACEBOOK_REDIRECT" placeholder=""
                                                            value="{{ $settings->FACEBOOK_REDIRECT }}" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="text-black main-title">STRIPE SETTINGS</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="font-w600">STRIPE_KEY <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="STRIPE_KEY" name="STRIPE_KEY"
                                                            placeholder="" value="{{ $settings->STRIPE_KEY }}"
                                                            type="text">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="font-w600">STRIPE_SECRET <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="STRIPE_SECRET"
                                                            name="STRIPE_SECRET" placeholder=""
                                                            value="{{ $settings->STRIPE_SECRET }}" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Save
                                                Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
