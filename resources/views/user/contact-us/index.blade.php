@extends('user.layout.app')

@section('title', 'About Us')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" rel="stylesheet"
        type="text/css" />
@endsection

@section('content')
    <!-- Order Status -->
    <section class="contact-bg py-5" style="background: url({{asset('front/assets/img/contact-us.jpeg')}});">
        <div class="container">
            <h1 class="text-center text-white py-5">Contact Us</h1>
        </div>
    </section>
    <!-- End Order Status -->

    <!-- Contact us -->
    <section class="mt-5">
        <div class="container-xxl container-fluid">
            <div class="row mb-4">
                <div class="col-md-8 mt-3">
                    <div class="text-center">
                        <p class="badge light-warningbg">GET IN TOUCH</p>
                    </div>
                    <h2 class="hr-text text-center mt-2 heading fs-2 border-start-0">
                        <span class="bg-white px-2">LEAVE US A MESSAGE</span>
                    </h2>
                    <form action="{{route('contact-us.store')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">First Name</label>
                                    <input type="text" placeholder="First Name" class="form-control"
                                        id="exampleFormControlInput1" required name="first_name"/>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Last Name</label>
                                    <input type="text" placeholder="Last Name" class="form-control"
                                        id="exampleFormControlInput1" required name="last_name"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Subject</label>
                            <input type="text" placeholder="Subject" class="form-control" id="exampleFormControlInput1" name="subject" />
                        </div>
                        <div class="form-group mt-3">
                            <label for="exampleFormControlTextarea1" class="mb-1 fw-bold">Your Message</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Your Message" rows="5" name="message"></textarea>
                        </div>
                        <button class="btn bag-btn text-white rounded-5 mt-4" type="submit">
                            Send Message
                        </button>
                    </form>
                </div>
                <div class="col-md-4 mt-3">
                    <div class="card rounded-4 p-3">
                        <div class="card-body">
                            <div class="d-flex gap-3 align-items-center">
                                <div>
                                    <i class="fa-regular fa-envelope icon purple-txtcolor fs-1"></i>
                                </div>
                                <div>
                                    <p class="heading mb-2">
                                        Contact us through this email
                                    </p>
                                    <a href="#"
                                        class="purple-txtcolor fs-5 text-decoration-underline">{{$setting->email ?? 'example@gmail.com'}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card rounded-4 p-3 mt-4">
                        <div class="card-body">
                            <div class="d-flex gap-3 align-items-center">
                                <div>
                                    <i class="bi bi-geo-alt icon purple-txtcolor fs-1"></i>
                                </div>
                                <div>
                                    <p class="heading mb-2">
                                        {{$admin->address ?? 'Location'}}
                                    </p>
                                    <a href="#"
                                        class="purple-txtcolor fs-5 text-decoration-underline">{{$setting->company_phone ?? '+123456789'}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card rounded-4 p-3 mt-4">
                        <div class="card-body">
                            <div class="d-flex gap-3 align-items-center">
                                <div>
                                    <i class="bi bi-bag-dash icon purple-txtcolor"></i>
                                </div>
                                <div>
                                    <p class="fs-5 mb-2">
                                        Slowly she drifted to the southeast, rising higher &
                                        higher as the flame
                                    </p>
                                    <a href="#"
                                        class="purple-txtcolor fs-5 text-decoration-underline">example@mail.com</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
    <!-- End Checkout -->
    @include('user.components.brands')
@endsection
