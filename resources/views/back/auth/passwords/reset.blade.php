{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
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

@extends('back.auth.layout.app')
@section('title', 'Login')
@section('content')
<section class="signup position-relative">
    <div class="right-down-arrow">
      <img src="{{asset('back/assets/dasheets/img/Ellipse.png')}}" class="img-fluid" alt="" />
    </div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-12 position-relative signup-img">
          <img
            src="{{asset('back/assets/dasheets/img/login.svg')}}"
            class="img-fluid text-center align-items-center p-4 py-5"
            alt=""
          />
        </div>
        <div class="col-md-6 col-12 px-4 py-5">
          <div class="signup-form text-white my-5">
            <div class="mb-5">
              <h2>Reset Password</h2>
              <p>Enter a secure password to protect your account</p>
            </div>
            <form class="signup-input" method="POST" action="{{ route('password.update') }}">
                @csrf
                @include('back.layout.errors')
                <input type="hidden" name="token" value="{{ $token }}">
              <div class="password-container">
                <input
                  type="email"
                  id="email"
                  class="password-input form-control subheading @error('email') is-invalid @enderror"
                  name="email"
                  placeholder="Email"
                  value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                  readonly
                />
                <img
                  src="dasheets/img/lock.svg"
                  class="password-toggle pe-2"
                  onclick="togglePasswordVisibility('password')"
                  alt=""
                />
              </div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              <div class="password-container">
                <input
                  type="password"
                  id="password"
                  class="password-input form-control subheading @error('password') is-invalid @enderror"
                  placeholder="Password"
                  name="password"
                />
                <img
                  src="dasheets/img/lock.svg"
                  class="password-toggle pe-2"
                  onclick="togglePasswordVisibility('password')"
                  alt=""
                />
              </div>
              @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              <div class="password-container">
                <input
                  type="password"
                  id="password_confirmation"
                  class="password-input form-control subheading"
                  placeholder="Retype Password"
                  name="password_confirmation"
                />
                <img
                  src="dasheets/img/lock.svg"
                  class="password-toggle pe-2"
                  onclick="togglePasswordVisibility('password')"
                  alt=""
                />
              </div>
              @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              <button class="btn save-btn text-white p-3 w-100 mt-4">
                Reset Password
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
