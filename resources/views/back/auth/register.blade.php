@extends('back.auth.layout.app')
@section('title', 'Register')
@section('content')

    {{-- <section id="signup" class="signup">
        <div class="container">
            <div class="signup-form">
                <div>
                    <h2 class="text-center">Create Account</h2>
                </div>
                <form class="row g-3" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div>
                        <label for="inputFirstName">Name</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="inputFirstName" name="name"
                            type="text" placeholder="Enter your name" required />
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label for="inputEmail">Email address</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="inputEmail" type="email"
                            name="email" placeholder="name@example.com" required />
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div>
                        <label for="inputPassword">Password</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="inputPassword"
                            type="password" name="password" placeholder="Create a password" required />
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label for="inputPasswordConfirm">Confirm Password</label>
                        <input class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="inputPasswordConfirm" type="password" placeholder="Confirm password"
                            name="password_confirmation" />
                        @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button class="btn save-btn text-white w-100 mt-3 rounded-5">Sign Up</button>

                    <div class="exist mt-3">
                        <p class="exist">Already have an account? <a href="{{route('login')}}" class="text-decoration-none">Sign
                                In</a></p>
                    </div>

                    <div class="or">
                        <p class="text-center">or</p>
                    </div>

                    <div>
                        <button class="btn create-btn w-100 rounded-5">
                            <img src="{{asset('back/assets/dasheets/img/google.png')}}" class="pe-2" alt="" />Sign
                            in with Google
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section> --}}

    <section class="signup position-relative">
        <div class="right-down-arrow">
            <img src="{{ asset('back/assets/dasheets/img/Ellipse.png') }}" class="img-fluid" alt="" />
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-12 position-relative signup-img">
                    <img src="{{ asset('back/assets/dasheets/img/login.svg') }}"
                        class="img-fluid text-center align-items-center py-5" alt="" />
                </div>
                <div class="col-md-6 col-12 px-4">
                    <div class="signup-form text-white">
                        <div class="mb-5">
                            <h2>Sign Up</h2>
                            <p>Create your account to get started</p>
                        </div>
                        <form class="signup-input mt-2" method="POST" action="{{ route('register') }}">
                            @csrf
                            <input type="text" class="form-control subheading" placeholder="First Name"
                                id="exampleFormControlInput1" name="first_name" />
                            @error('first_name')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="text" class="form-control subheading" placeholder="Last Name"
                                id="exampleFormControlInput1" name="last_name" />
                            @error('last_name')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="password-container">
                                <input type="email" class="form-control" placeholder="Email" required name="email" />
                                <img src="{{ asset('back/assets/dasheets/img/mail.svg') }}" class="password-toggle pe-2"
                                    alt="" />
                            </div>
                            @error('email')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="password-container">
                                <input type="password" id="password" class="password-input form-control subheading"
                                    placeholder="Password" name="password"
                                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                                    title="Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character."
                                    required />
                                <img src="{{ asset('back/assets/dasheets/img/lock.svg') }}" class="password-toggle pe-2"
                                    onclick="togglePasswordVisibility('password')" alt="" />
                                @error('password')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="password-container">
                                <input type="password" id="password_confirmation"
                                    class="password-input form-control subheading" placeholder="Retype Password"
                                    name="password_confirmation" />
                                <img src="{{ asset('back/assets/dasheets/img/lock.svg') }}" class="password-toggle pe-2"
                                    onclick="togglePasswordVisibility('password_confirmation')" alt="" />
                            </div>
                            @error('password_confirmation')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <button class="btn save-btn text-white p-3 w-100 mt-4">
                                Sign Up
                            </button>
                        </form>
                        <div class="exist mt-2">
                            <p class="exist">Already have an account? <a href="{{ route('login') }}"
                                    class="text-decoration-none">Sign In</a></p>
                        </div>
                    </div>
                </div>
            </div>
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function() {});

        function togglePasswordVisibility(input) {
            let passwordInput = document.getElementById(input);
            if (passwordInput.type == 'password')
                passwordInput.type = 'text'
            else
                passwordInput.type = 'password'
        }
    </script>
@endsection
