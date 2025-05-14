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
    {{-- <div class="content">
        <div class="container-fluid px-4">
            <div class="row mb-5">
                <div class="border-bottom my-4">
                    <h3 class="all-adjustment text-center pb-2 mb-0">Setting</h3>
                </div>

                @include('back.layout.errors')

                <div class="container-fluid mt-2">
                    <form action="{{ route('setting.store') }}" method="POST">
                        @csrf
                        <h6 class="text-secondary">Links</h6>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="fb">Facebook</label>
                                <input type="text" name="fb" class="form-control" id="fb"
                                    value="www.facebook.com/098765434567">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="linkedin">LinkedIn</label>
                                <input type="text" name="linkedin" class="form-control" id="linkedin"
                                    value="www.linkedin.com">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="twitch">Twitch</label>
                                <input type="text" name="twitch" class="form-control" id="twitch"
                                    value="www.twitch.com">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="twitter">Twitter</label>
                                <input type="text" name="twitter" class="form-control" id="twitter"
                                    value="www.twitter.com">
                            </div>
                        </div>
                        <button class="btn save-btn mt-2 text-white">Save</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div> --}}

    <div class="content">
        <div class="container-fluid py-5 px-4">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Setting</h3>
            </div>
            @include('back.layout.errors')
            <form action="{{ route('setting.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card rounded-3 border-0 card-shadow mt-3 p-0">
                    <div class="card-header p-3 border-0">
                        <p class="m-0">System Setting</p>
                    </div>
                    <div class="card-body p-3">
                        <div class="row fw-bold">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency">Default Currency</label>
                                    <select class="form-control form-select subheading mt-1" name="currency"
                                        aria-label="Default select example" id="currency">
                                        <option value="Us Dollars"
                                            {{ ($setting->currency ?? '') == 'Us Dollars' ? 'selected' : '' }}>Us Dollars
                                        </option>
                                        <option value="PKR" {{ ($setting->currency ?? '') == 'PKR' ? 'selected' : '' }}>PKR
                                        </option>
                                        <option value="IND" {{ ($setting->currency ?? '') == 'IND' ? 'selected' : '' }}>IND
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email </label>
                                    <input type="email" name="email" class="form-control subheading mt-1"
                                        placeholder="someone@mail.com" id="email" value="{{ $setting->email ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="logo">Change Logo</label>
                                    <input type="file" name="logo" class="form-control subheading mt-1"
                                        id="logo" />
                                </div>
                            </div>
                        </div>
                        <div class="row fw-bold mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="company_name">Company Name </label>
                                    <input type="text" class="form-control subheading mt-1" id="name"
                                        name="company_name" value="{{ $setting->company_name ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="shop_domain">Shop Name </label>
                                    <input type="text" class="form-control subheading mt-1" id="name"
                                        name="shop_domain" value="{{ $shopify_store->shop_domain ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="access_token">Shop Access Token </label>
                                    <input type="text" class="form-control subheading mt-1" id="name"
                                        name="access_token" value="{{ $shopify_store->access_token ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="company_phone">Company Phone </label>
                                    {{-- <input type="text" class="form-control subheading mt-1" id="company_phone"
                                        name="company_phone" value="{{ $setting->company_phone ?? '' }}"
                                        pattern="[0-9]{11}" /> --}}
                                    <input type="text" class="form-control subheading mt-1" id="company_phone"
                                        name="company_phone" value="{{ $setting->company_phone ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="developed_by">Developed by </label>
                                    <input type="text" class="form-control subheading mt-1" id="developed_by"
                                        name="developed_by" value="{{ $setting->developed_by ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4 col-auto mt-2 mt-lg-4">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <label class="switch mt-2">
                                            <input type="checkbox" name="shopify_enable" id="shopify_enable"
                                                {{ $setting->shopify_enable ?? '' == 1 ? 'checked' : '' }} />
                                            <span class="slider"></span>
                                        </label>
                                        <p class="m-0">Enable Shopify</p>
                                    </div>
                                    <div class="d-flex align-items-center"  title="Show products pricing without login">
                                        <label class="switch mt-2">
                                            <input type="checkbox" name="show_pricing" id="show_pricing"
                                                {{ $setting->show_pricing ?? '' == 1 ? 'checked' : '' }}  />
                                            <span class="slider"></span>
                                        </label>
                                        <p class="m-0">Enable Pricing</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row fw-bold mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="footer">Footer </label>
                                    <input type="text" class="form-control subheading mt-1" id="footer" name="footer"
                                        value="{{ $setting->footer ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="default_lang">Default Language
                                    </label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" id="default_lang" name="default_lang">
                                        <option value="English"
                                            {{ ($setting->default_lang ?? '') == 'English' ? 'selected' : '' }}>English
                                        </option>
                                        <option value="Urdu"
                                            {{ ($setting->default_lang ?? '') == 'Urdu' ? 'selected' : '' }}>Urdu</option>
                                        <option value="Hindi"
                                            {{ ($setting->default_lang ?? '') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                        <option value="French"
                                            {{ ($setting->default_lang ?? '') == 'French' ? 'selected' : '' }}>French
                                        </option>
                                        <option value="Arabic"
                                            {{ ($setting->default_lang ?? '') == 'Arabic' ? 'selected' : '' }}>Arabic
                                        </option>
                                        <option value="Turkish"
                                            {{ ($setting->default_lang ?? '') == 'Turkish' ? 'selected' : '' }}>Turkish
                                        </option>
                                        <option value="Chinese"
                                            {{ ($setting->default_lang ?? '') == 'Chinese' ? 'selected' : '' }}>Chinese
                                        </option>
                                        <option value="Thai"
                                            {{ ($setting->default_lang ?? '') == 'Thai' ? 'selected' : '' }}>Thai</option>
                                        <option value="German"
                                            {{ ($setting->default_lang ?? '') == 'German' ? 'selected' : '' }}>German
                                        </option>
                                        <option value="Spanish"
                                            {{ ($setting->default_lang ?? '') == 'Spanish' ? 'selected' : '' }}>Spanish
                                        </option>
                                        <option value="Italian"
                                            {{ ($setting->default_lang ?? '') == 'Italian' ? 'selected' : '' }}>Italian
                                        </option>
                                        <option value="Korean"
                                            {{ ($setting->default_lang ?? '') == 'Korean' ? 'selected' : '' }}>Korean
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="default_customer">Default Customer</label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" id="default_customer"
                                        name="default_customer">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id ?? '' }}"
                                                {{ ($setting->default_customer ?? '') == ($customer->id ?? '') ? 'selected' : '' }}>
                                                {{ $customer->user->name ?? '' }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row fw-bold mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="default_warehouse">Default Warehouse</label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" id="default_warehouse"
                                        name="default_warehouse">
                                        <option value="">Choose Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id ?? '' }}"
                                                {{ ($setting->default_warehouse ?? '') == ($warehouse->id ?? '') ? 'selected' : '' }}>
                                                {{ $warehouse->users->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sms_gateway">Default SMS Gateway</label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" id="sms_gateway" name="sms_gateway">
                                        <option value="SMS Gateway 1" {{($setting->sms_gateway ?? '') == 'SMS Gateway 1' ? 'selected':''}} >SMS Gateway 1</option>
                                        <option value="SMS Gateway 2" {{($setting->sms_gateway ?? '') == 'SMS Gateway 2' ? 'selected':''}}>SMS Gateway 2</option>
                                        <option value="SMS Gateway 3" {{($setting->sms_gateway ?? '') == 'SMS Gateway 3' ? 'selected':''}}>SMS Gateway 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="time_zong">Time Zone</label>
                                    <select class="form-control form-select subheading mt-1"
                                        aria-label="Default select example" id="time_zone" name="time_zone">
                                        <option value="-12:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-12:00' ? 'selected' : '' }} @endif>
                                            (GMT -12:00) Eniwetok, Kwajalein</option>
                                        <option value="-11:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-11:00' ? 'selected' : '' }} @endif>
                                            (GMT -11:00) Midway Island, Samoa</option>
                                        <option value="-10:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-10:00' ? 'selected' : '' }} @endif>
                                            (GMT -10:00) Hawaii</option>
                                        <option value="-09:50"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-09:50' ? 'selected' : '' }} @endif>
                                            (GMT -9:30) Taiohae</option>
                                        <option value="-09:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-09:00' ? 'selected' : '' }} @endif>
                                            (GMT -9:00) Alaska</option>
                                        <option value="-08:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-08:00' ? 'selected' : '' }} @endif>
                                            (GMT -8:00) Pacific Time (US & Canada)</option>
                                        <option value="-07:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-07:00' ? 'selected' : '' }} @endif>
                                            (GMT -7:00) Mountain Time (US & Canada)</option>
                                        <option value="-06:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-06:00' ? 'selected' : '' }} @endif>
                                            (GMT -6:00) Central Time (US & Canada), Mexico City</option>
                                        <option value="-05:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-05:00' ? 'selected' : '' }} @endif>
                                            (GMT -5:00) Eastern Time (US & Canada), Bogota, Lima</option>
                                        <option value="-04:50"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-04:50' ? 'selected' : '' }} @endif>
                                            (GMT -4:30) Caracas</option>
                                        <option value="-04:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-04:00' ? 'selected' : '' }} @endif>
                                            (GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                                        <option value="-03:50"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-03:50' ? 'selected' : '' }} @endif>
                                            (GMT -3:30) Newfoundland</option>
                                        <option value="-03:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-03:00' ? 'selected' : '' }} @endif>
                                            (GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                                        <option value="-02:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-02:00' ? 'selected' : '' }} @endif>
                                            (GMT -2:00) Mid-Atlantic</option>
                                        <option value="-01:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '-01:00' ? 'selected' : '' }} @endif>
                                            (GMT -1:00) Azores, Cape Verde Islands</option>
                                        <option value="+00:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+00:00' ? 'selected' : '' }} @endif>
                                            (GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                                        <option value="+01:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+01:00' ? 'selected' : '' }} @endif>
                                            (GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
                                        <option value="+02:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+02:00' ? 'selected' : '' }} @endif>
                                            (GMT +2:00) Kaliningrad, South Africa</option>
                                        <option value="+03:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+03:00' ? 'selected' : '' }} @endif>
                                            (GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                                        <option value="+03:50"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+03:50' ? 'selected' : '' }} @endif>
                                            (GMT +3:30) Tehran</option>
                                        <option value="+04:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+04:00' ? 'selected' : '' }} @endif>
                                            (GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                                        <option value="+04:50"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+04:50' ? 'selected' : '' }} @endif>
                                            (GMT +4:30) Kabul</option>
                                        <option value="+05:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+05:00' ? 'selected' : '' }} @endif>
                                            (GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                                        <option value="+05:50"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+05:50' ? 'selected' : '' }} @endif>
                                            (GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
                                        <option value="+05:75"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+05:75' ? 'selected' : '' }} @endif>
                                            (GMT +5:45) Kathmandu, Pokhara</option>
                                        <option value="+06:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+06:00' ? 'selected' : '' }} @endif>
                                            (GMT +6:00) Almaty, Dhaka, Colombo</option>
                                        <option value="+06:50"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+06:50' ? 'selected' : '' }} @endif>
                                            (GMT +6:30) Yangon, Mandalay</option>
                                        <option value="+07:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+07:00' ? 'selected' : '' }} @endif>
                                            (GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                                        <option value="+08:00"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+08:00' ? 'selected' : '' }} @endif>
                                            (GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                                        <option value="+08:75"
                                            @if (isset($setting->time_zone)) {{ $setting->time_zone == '+08:75' ? 'selected' : '' }} @endif>
                                            (GMT +8:45) Eucla</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group fw-bold mt-2">
                            <label for="address">Address </label>
                            <textarea class="form-control subheading mt-1" id="address" name="address" rows="3">{{ $setting->address ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card rounded-3 border-0 card-shadow mt-5 p-0">
                    <div class="card-header p-3 border-0">
                        <p class="m-0">SMTP Settings</p>
                    </div>
                    <div class="card-body p-3">
                        <div class="row fw-bold">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_host">Host </label>
                                    <input type="email" class="form-control subheading mt-1" id="smtp_host"
                                        name="smtp_host" value="{{ $setting->smtp_host ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_port">Port </label>
                                    <input type="number" class="form-control subheading mt-1" id="smtp_port"
                                        name="smtp_port" value="{{ $setting->smtp_port ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_username">Username </label>
                                    <input type="text" class="form-control subheading mt-1" id="smtp_username"
                                        name="smtp_username" value="{{ $setting->smtp_username ?? '' }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row fw-bold mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_password">Password </label>
                                    <input type="password" class="form-control subheading mt-1" id="smtp_password"
                                        name="smtp_password" value="{{ $setting->password ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_encryption">Encryption </label>
                                    <input type="text" class="form-control subheading mt-1" placeholder="SSL"
                                        id="smtp_encryption" name="smtp_encryption"
                                        value="{{ $setting->smtp_encryption ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_address">From Address </label>
                                    <input type="text" class="form-control subheading mt-1" id="smtp_address"
                                        name="smtp_address" value="{{ $setting->smtp_address ?? '' }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row fw-bold mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="smtp_from_name">From Name </label>
                                    <input type="text" class="form-control subheading mt-1" id="smtp_from_name"
                                        name="smtp_from_name" value="{{ $setting->smtp_from_name ?? '' }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card rounded-3 border-0 card-shadow mt-5 p-0">
                    <div class="card-header p-3 border-0">
                        <p class="m-0">FedEx Configuration</p>
                    </div>
                    <div class="card-body p-3">
                        <div class="row fw-bold">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fedex_api_key">Api Key </label>
                                    <input type="text" class="form-control subheading mt-1" id="fedex_api_key"
                                        name="fedex_api_key" value="{{ $setting->fedex_api_key ?? '' }}" placeholder="Api Key"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fedex_secret_key">Secret Key </label>
                                    <input type="text" class="form-control subheading mt-1" id="fedex_secret_key"
                                        name="fedex_secret_key" value="{{ $setting->fedex_secret_key ?? '' }}" placeholder="Secret Key"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fedex_account_number">Account Number </label>
                                    <input type="text" class="form-control subheading mt-1" id="fedex_account_number"
                                        name="fedex_account_number" value="{{ $setting->fedex_account_number ?? '' }}" placeholder="e.g 12345678"/>
                                </div>
                            </div>
                        </div>
                        <div class="row fw-bold mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fedex_meter_number">Meter Number (optional) </label>
                                    <input type="password" class="form-control subheading mt-1" id="fedex_meter_number"
                                        name="fedex_meter_number" value="{{ $setting->fedex_meter_number ?? '' }}" placeholder="e.g 12345678"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fedex_api_url">Api URL </label>
                                    <input type="text" class="form-control subheading mt-1" placeholder="e.g. https://apis-sandbox.fedex.com"
                                        id="fedex_api_url" name="fedex_api_url"
                                        value="{{ $setting->fedex_api_url ?? '' }}" />
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                {{-- {{auth()->user()->getRoleNames()}} --}}
                @role('Admin')
                    <div class="card rounded-3 border-0 card-shadow mt-5 p-0">
                        <div class="card-header p-3 border-0">
                            <p class="m-0">Footer Links</p>
                        </div>
                        <div class="card-body p-3">
                            <div class="row fw-bold">
                                <div class="form-group fw-bold mt-2 col-md-6">
                                    <label for="smtp_host">Facebook </label>
                                    <input type="text" name="fb" class="form-control subheading mt-1" id="fb"
                                        value="{{ $setting->fb ?? '' }}" pattern="https?://.*"
                                        title="Please enter a valid URL starting with http:// or https://">
                                </div>
                                <div class="form-group fw-bold mt-2 col-md-6">
                                    <label for="smtp_host">LinkedIn </label>
                                    <input type="text" name="linkedin" class="form-control subheading mt-1"
                                        id="linkedin" value="{{ $setting->linkedin ?? '' }}" pattern="https?://.*"
                                        title="Please enter a valid LinkedIn profile URL starting with http:// or https://">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group fw-bold mt-2 col-md-6">
                                    <label for="smtp_host">Twitch </label>
                                    <input type="text" name="twitch" class="form-control subheading mt-1" id="twitch"
                                        value="{{ $setting->twitch ?? '' }}" pattern="https?://.*"
                                        title="Please enter a valid URL starting with http:// or https://">
                                </div>
                                <div class="form-group fw-bold mt-2 col-md-6">
                                    <label for="smtp_host">Twitter </label>
                                    <input type="text" name="twitter" class="form-control subheading mt-1" id="twitter"
                                        value="{{ $setting->twitter ?? '' }}" pattern="https?://.*"
                                        title="Please enter a valid URL starting with http:// or https://">
                                </div>
                            </div>

                        </div>


                    </div>
                @endrole

                <button class="btn save-btn text-white mt-3" type="submit">Submit</button>
            </form>


        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#shopify_enable').change(function() {
                if ($(this).is(':checked')) {
                    $(this).val(1);
                } else {
                    $(this).val(0);
                }
                // ajax call to update its value
                $.ajax({
                    url: "{{ route('setting.shopify.enable') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        shopify_enable: $(this).val()
                    },
                    success: function(response) {
                        console.log(response);
                        // toastr.success('Shopify Service Enabled');
                        if ($('#shopify_enable').val() == 1) {
                            toastr.success('Shopify Service Enabled');
                        } else {
                            toastr.success('Shopify Service Disabled');
                        }
                    }
                });
            });
            $('#show_pricing').change(function() {
                if ($(this).is(':checked')) {
                    $(this).val(1);
                } else {
                    $(this).val(0);
                }
                // ajax call to update its value
                $.ajax({
                    url: "{{ route('setting.pricing.enable') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        show_pricing: $(this).val()
                    },
                    success: function(response) {
                        console.log(response);
                        // toastr.success('Shopify Service Enabled');
                        if ($('#show_pricing').val() == 1) {
                            toastr.success('Show Pricing Enabled');
                        } else {
                            toastr.success('Show Pricing Disabled');
                        }
                    }
                });
            });


        });
    </script>
@endsection
