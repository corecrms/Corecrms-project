@extends('user.dashboard-layout.app')


@section('content')
    <div class=" container-fluid pt-4 px-4 mb-5">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 30%">
                Edit Account Information
            </h3>
        </div>

        <form action="{{ route('user.account.info.update') }}" method="POST">
            @csrf
            @method('PUT')
            <!-- Input Fields -->
            <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
                <div class="card-header p-2 border-0">
                    <h4 class="mb-0 mt-2 heading text-start card-title">
                        Account Information
                    </h4>
                    @php
                        function splitName($fullName)
                        {
                            $nameParts = explode(' ', $fullName);
                            $firstName = array_shift($nameParts);
                            $secondName = implode(' ', $nameParts);
                            return [
                                'first_name' => $firstName,
                                'last_name' => $secondName,
                            ];
                        }
                        $names = splitName(auth()->user()->name);
                    @endphp
                </div>
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">First Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="first_name" required value="{{ $names['first_name'] }}" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Last Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="last_name" value="{{ $names['last_name'] }}" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="password" class="mb-1"> Password
                                    <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control subheading"
                                    id="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                                    title="Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character." />
                                <span class="text-danger">
                                    @error('password')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control subheading" id="exampleFormControlInput1" required
                                    value="{{ auth()->user()->email ?? '' }}" name="email" />
                                <span class="text-danger">
                                    @error('email')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        {{-- <div class="col-md-6 mt-2">
                        <div class="form-group d-flex align-items-center gap-2">
                            <input type="checkbox" class="subheading" id="changepassword" required />
                            <label for="changepassword" class="">Change Password</label>
                        </div>
                    </div> --}}
                    </div>
                </div>
            </div>

            <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0 mb-3">
                <div class="card-header p-2 border-0">
                    <h4 class="mb-0 mt-2 heading text-start card-title">
                        Additional Information
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Mobile Number</label>
                                <input type="tel" class="form-control subheading" id="exampleFormControlInput1"
                                    value="{{ auth()->user()->contact_no ?? '' }}" name="contact_no" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Username for login</label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    value="{{ auth()->user()->username ?? '' }}" name="username" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="city" class="mb-1">City</label>
                                <input type="text" class="form-control subheading" id="city"
                                    value="{{ auth()->user()->customer->city ?? '' }}" name="city" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="country" class="mb-1">Country</label>
                                <input type="tel" class="form-control subheading" id="country"
                                    value="{{ auth()->user()->customer->country ?? '' }}" name="country" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="country_code" class="mb-1">Country Code</label>
                                <input type="text" class="form-control subheading" id="country_code"
                                    value="{{ auth()->user()->country_code ?? '' }}" name="country_code" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="state" class="mb-1">State</label>
                                <input type="tel" class="form-control subheading" id="state"
                                    value="{{ auth()->user()->state ?? '' }}" name="state" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">

                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="state_code" class="mb-1">State Code</label>
                                <input type="tel" class="form-control subheading" id="state_code"
                                    value="{{ auth()->user()->state_code ?? '' }}" name="state_code" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="country" class="mb-1">Zip/Postal Code </label>
                                <input type="tel" class="form-control subheading" id="country"
                                    value="{{ auth()->user()->postal_code ?? '' }}" name="postal_code" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="form-group">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"
                                    name="address">{{ auth()->user()->address ?? '' }}</textarea>
                                <label for="floatingTextarea2">Address</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="card card-shadow border-0 mt-4 rounded-3">
                <div class="card-header border-0 rounded-3 py-3">
                    <h2 class="heading m-0">Manage your notifications</h2>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive p-2">
                        <table class="table">
                            <thead class="fw-bold">
                                <tr>
                                    <th>Type</th>
                                    <th>Email</th>
                                    <th>Text Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="align-middle">Out of Stock Products</td>
                                    <td class="pt-3">
                                        <label class="switch mt-2">
                                            <input type="checkbox" />
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td class="pt-3">
                                        <label class="switch mt-2">
                                            <input type="checkbox" />
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Out of Stock Products</td>
                                    <td class="pt-3">
                                        <label class="switch mt-2">
                                            <input type="checkbox" checked />
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td class="pt-3">
                                        <label class="switch mt-2">
                                            <input type="checkbox" />
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Out of Stock Products</td>
                                    <td class="pt-3">
                                        <label class="switch mt-2">
                                            <input type="checkbox" checked />
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td class="pt-3">
                                        <label class="switch mt-2">
                                            <input type="checkbox" />
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
            <button type="submit" class="save-btn btn text-white mt-2">Submit</button>
        </form>

        <!-- Input Fields End -->
    </div>
@endsection
