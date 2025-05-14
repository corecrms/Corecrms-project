{{-- @extends('back.auth.layout.app')

@section('content')
<section id="signup" class="signup">
    <div class="container">
        <div class="signup-form">
            <div class="text-center">
                <img src="dasheets/img/car-key.png" class="img-fluid" alt="car-key">
                <h2 class="text-center mt-2">Forget Password</h2>
                <p>Submit your email address and we'll send you a link to reset your password.</p>
            </div>
            <form>
                <div>
                    <input type="email" class="form-control" id="email" placeholder="example@mail.com" required />
                </div>

                <div class="d-flex justify-content-center">
                    <a href="login.html" class="btn create-btn mt-3 me-2">Back</a>
                    <button class="btn save-btn text-white mt-3">Continue</button>
                </div>
            </form>
        </div>
    </div>
</section>
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
            <form class="signup-input">
              <div class="password-container">
                <input
                  type="password"
                  id="password"
                  class="password-input form-control subheading"
                  placeholder="Password"
                />
                <img
                  src="dasheets/img/lock.svg"
                  class="password-toggle pe-2"
                  onclick="togglePasswordVisibility('password')"
                  alt=""
                />
              </div>
              <div class="password-container">
                <input
                  type="password"
                  id="password"
                  class="password-input form-control subheading"
                  placeholder="Retype Password"
                />
                <img
                  src="dasheets/img/lock.svg"
                  class="password-toggle pe-2"
                  onclick="togglePasswordVisibility('password')"
                  alt=""
                />
              </div>
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
