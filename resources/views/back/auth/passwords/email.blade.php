@extends('back.auth.layout.app')

{{-- @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
@section('title', 'Login')
@section('content')
    <section class="signup position-relative">
        <div class="right-down-arrow">
            <img src="{{ asset('back/assets/dasheets/img/Ellipse.png') }}" class="img-fluid" alt="" />
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-12 position-relative signup-img">
                    <img src="{{ asset('back/assets/dasheets/img/login.svg') }}"
                        class="img-fluid text-center align-items-center p-4 py-5" alt="" />
                </div>
                <div class="col-md-6 col-12 px-4 py-5">
                    <div class="signup-form text-white my-5">
                        <div class="mb-5">
                            <h2>Reset Password</h2>
                            <p>Enter your email for reset password</p>
                        </div>
                        <form class="signup-input" method="POST" action="{{ route('password.email') }}">
                            @csrf
                            @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                            <div class="password-container">
                                <input type="email" id="email" class="password-input form-control subheading"
                                    placeholder="Email Address" name="email" />
                                <img src="dasheets/img/lock.svg" class="password-toggle pe-2"
                                    onclick="togglePasswordVisibility('password')" alt="" />
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <button class="btn save-btn text-white p-3 w-100 mt-4" type="submit">
                                Send Password Reset Link
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
