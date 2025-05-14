@extends('back.auth.layout.app')
@section('title', 'Login')
@section('content')

    {{-- <section id="signup" class="signup">
        <div class="container">
            <div class="signup-form">
                <div>
                    <h2 class="text-center">Sign In</h2>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <label for="inputEmail">Email</label>
                        <input class="form-control  @error('email') is-invalid @enderror" name="email" id="inputEmail"
                            type="email" placeholder="name@example.com" />
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label for="inputPassword">Password</label>
                        <input class="form-control  @error('password') is-invalid @enderror" name="password"
                            id="inputPassword" type="password" placeholder="Password" />
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row keep-me-logged">
                        <div class="col-md-7 col-6 mt-3">
                            <input class="form-check-input" id="inputRememberPassword" type="checkbox"
                                        value="" />
                                    <label class="form-check-label" for="inputRememberPassword">Remember
                                        Password</label>
                        </div>

                        <div class="col-md-5 col-6 mt-3 exist">
                            <a class="small text-decoration-none forget-btn" href="#">Forgot
                                Password?</a>
                        </div>
                    </div>

                    <button class="btn save-btn text-white w-100 mt-3 rounded-5">Sign In</button>

                    <div class="exist mt-3">
                        <p class="exist">Not registered yet? <a href="{{route('register')}}" class="text-decoration-none">Create
                                Account</a></p>
                    </div>

                    <div class="or">
                        <p class="text-center">or</p>
                    </div>

                    <div>
                        <button class="btn create-btn w-100 rounded-5">
                            <img src="{{ asset('back/assets/dasheets/img/google.png') }}" class="pe-2"
                                alt="" />Sign
                            in with Google
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section> --}}

    <div >
        <section class="signup position-relative">
          <div class="right-down-arrow">
            <img src="{{asset('back/assets/dasheets/img/Ellipse.png')}}" class="img-fluid" alt="" />
          </div>
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-md-6 col-12 position-relative signup-img">
                <img
                  src="{{asset('back/assets/dasheets/img/login.svg')}}"
                  class="img-fluid text-center align-items-center py-5"
                  alt=""
                />
              </div>
              <div class="col-md-6 col-12 py-3 px-4">
                <div class="signup-form text-white my-5">
                  <div class="mb-5">
                    <h2>Sign in</h2>
                    <p>Please enter your credentials</p>
                  </div>
                  <form class="signup-input" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="password-container">
                      <input
                        type="email"
                        class="form-control @error('email') border border-danger @enderror"
                        placeholder="Email"
                        name="email"
                        required
                      />

                      <img
                        src="{{asset('back/assets/dasheets/img/mail.svg')}}"
                        class="password-toggle pe-2"
                        alt=""
                      />

                    </div>
                    @error('email')
                          <span class="text-danger" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                    @enderror
                    <div class="password-container">
                      <input
                        type="password"
                        id="password"
                        class="password-input form-control subheading @error('password') border border-danger @enderror"
                        placeholder="Password"
                        name="password"
                      />

                      <img
                        src="{{asset('back/assets/dasheets/img/lock.svg')}}"
                        class="password-toggle pe-2"
                        onclick="togglePasswordVisibility()"
                        alt=""
                      />
                    </div>
                    @error('password')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror

                    <div class="row text-white keep-me-logged">
                      <div class="col-md-6 d-flex mt-3">
                        <input type="checkbox" class="checkboxing" name="" id="" />
                        <span>Keep me signed in</span>
                      </div>

                      <div class="col-md-6 mt-3 forget-password text-end">
                        <a href="{{route('password.request')}}" class="text-decoration-none text-white"> Forgot Password? </a>
                      </div>
                    </div>
                    <button class="btn save-btn text-white p-3 w-100 mt-4">
                      Sign In
                    </button>
                  </form>
                    <div class="exist mt-2">
                        <p class="exist">Not registered yet? <a href="{{route('register')}}" class="text-decoration-none">Create
                                Account</a></p>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Template Javascript -->
        <script src="dasheets/js/main.js"></script>
        <script>
            function togglePasswordVisibility(input){
                let passwordInput = document.getElementById('password');
                if(passwordInput.type == 'password')
                    passwordInput.type = 'text'
                else
                    passwordInput.type = 'password'
            }
        </script>
    </div>


@endsection


