@extends('user.layout.app')

@section('title', 'About Us')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" rel="stylesheet"
        type="text/css" />
@endsection

@section('content')
    <!-- Order Status -->
    <section class="">
        <div class="container">
            <h1 class="text-center py-5">About Us</h1>
        </div>
    </section>
    <!-- End Order Status -->

    <!-- About US -->
    <section class="mt-5">
        <div class="container-xxl container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6 mt-3">
                    <div>
                        <p class="badge light-warningbg">The Best Services</p>
                    </div>
                    <h1>{{ $section->section_1_title ?? 'WELCOME TO OUR STORE' }}</h1>
                    <p class="fs-5">
                        {{ $section->section_1_description ??
                            'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below
                                                                        for those interested.
                                                                        Sections 1.10.32 and 1.10.33 from “de
                                                                        Finibus Bonorum et Malorum” by Cicero from the 1914.' }}
                    </p>
                    <div>
                        <h5>SPEED OF SERVICE</h5>
                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75"
                            aria-valuemin="0" aria-valuemax="100" style="height: 20px">
                            <div class="progress-bar days-bg" style="width: 75%">
                                <h5 class="text-white m-0">98%</h5>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h5>PRODUCT QUALITY</h5>
                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75"
                            aria-valuemin="0" aria-valuemax="100" style="height: 20px">
                            <div class="progress-bar days-bg" style="width: 100%">
                                <h5 class="text-white m-0">100%</h5>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h5>HAPPY CLIENTS</h5>
                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75"
                            aria-valuemin="0" aria-valuemax="100" style="height: 20px">
                            <div class="progress-bar days-bg" style="width: 90%">
                                <h5 class="text-white m-0">90%</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    @if (isset($section->section_1_image) && $section->section_1_image)
                        <img src="{{ asset('storage/' . $section->section_1_image) }}" alt=""
                            class="img-fluid w-100" />
                    @else
                        <img src="assets/img/about-us.jpeg" alt="" class="img-fluid w-100" />
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- End About US -->

    <!-- Contact us -->
    <section class="mt-5">
        <div class="container-xxl container-fluid">
            <div class="row">
                @php
                    $extra_content = !is_null($section) ? json_decode($section->our_services) : [];
                @endphp
                @if (isset($extra_content) && count($extra_content) > 0)
                    @foreach ($extra_content as $item)
                        <div class="col-md-4 mt-3">
                            <div class="card rounded-4 p-3">
                                <div class="card-body text-center">
                                    <img src="{{ asset('/storage/' . $item->icon) }}" alt="not found" style="width: 100px">
                                    <div>
                                        <p class="fs-5 mb-2">
                                            {{ $item->description ?? '' }}
                                        </p>
                                        <a href="{{ $item->link ?? '#' }}" class="btn btn-primary btn-sm">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-4 mt-3">
                        <div class="card rounded-4 p-3">
                            <div class="card-body text-center">
                                <i class="fa-regular fa-envelope icon purple-txtcolor"></i>
                                <div>
                                    <p class="fs-5 mb-2">
                                        Slowly she drifted to the southeast, rising higher & higher
                                        as the flame
                                    </p>
                                    <a href="#"
                                        class="purple-txtcolor fs-5 text-decoration-underline">example@mail.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="card rounded-4 p-3">
                            <div class="card-body text-center">
                                <i class="fa-regular fa-envelope icon purple-txtcolor"></i>
                                <div>
                                    <p class="fs-5 mb-2">
                                        Slowly she drifted to the southeast, rising higher & higher
                                        as the flame
                                    </p>
                                    <a href="#"
                                        class="purple-txtcolor fs-5 text-decoration-underline">example@mail.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="card rounded-4 p-3">
                            <div class="card-body text-center">
                                <i class="fa-regular fa-envelope icon purple-txtcolor"></i>
                                <div>
                                    <p class="fs-5 mb-2">
                                        Slowly she drifted to the southeast, rising higher & higher
                                        as the flame
                                    </p>
                                    <a href="#"
                                        class="purple-txtcolor fs-5 text-decoration-underline">example@mail.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
    <!-- End Checkout -->

    <section class="mt-5">
        <div class="container-xxl container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6 mt-3">
                    @if (isset($section->section_2_image) && $section->section_2_image)
                        <img src="{{ asset('storage/' . $section->section_2_image) }}" alt=""
                            class="img-fluid w-100" />
                    @else
                        <img src="assets/img/about-us.jpeg" alt="" class="img-fluid w-100" />
                    @endif
                </div>
                <div class="col-md-6 mt-3">
                    <div>
                        {{-- <p class="badge light-warningbg">HEALTHY LIFE</p> --}}
                    </div>
                    <h2>{{ $section->section_2_title ?? 'SPECIAL OFFERS FOR YOU' }}</h2>
                    @if (isset($section->section_2_desc) && $section->section_2_desc)
                        <p class="fs-5">
                            {{ $section->section_2_desc ?? '' }}
                        </p>
                    @else
                        <p class="fs-5">
                            It is a long established fact that a reader will be distrac
                            readable content of a page when looking at it layout ofusing Lorem
                            Ipsum is that it has a more.
                        </p>
                        <div class="d-flex gap-3 align-items-center">
                            <div>
                                <i class="fa-regular fa-envelope icon purple-txtcolor"></i>
                            </div>
                            <div>
                                <h5>NATURAL PRODUCTS</h5>
                                <p class="fs-6 mb-2">
                                    Slowly she drifted to the southeast, rising higher & higher as
                                    the flame
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-center mt-3">
                            <div>
                                <i class="fa-regular fa-envelope icon purple-txtcolor"></i>
                            </div>
                            <div>
                                <h5>NATURAL PRODUCTS</h5>
                                <p class="fs-6 mb-2">
                                    Slowly she drifted to the southeast, rising higher & higher as
                                    the flame
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-center mt-3">
                            <div>
                                <i class="fa-regular fa-envelope icon purple-txtcolor"></i>
                            </div>
                            <div>
                                <h5>NATURAL PRODUCTS</h5>
                                <p class="fs-6 mb-2">
                                    Slowly she drifted to the southeast, rising higher & higher as
                                    the flame
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- <section class="count-bg py-5 mt-5">
        <div class="container">
            <div class="row text-center counters py-5">
                <div class="col-lg-3 col-6 text-center">
                    <div class="d-flex justify-content-center">
                        <h1 data-purecounter-start="0" data-purecounter-end="90" data-purecounter-duration="1"
                            class="purecounter text-white"></h1>
                        <h1 class="positive text-white">+</h1>
                    </div>
                    <h3 class="text-white">Years</h3>
                </div>
                <div class="col-lg-3 col-6 text-center">
                    <div class="d-flex justify-content-center">
                        <h1 data-purecounter-start="0" data-purecounter-end="90" data-purecounter-duration="1"
                            class="purecounter text-white"></h1>
                        <h1 class="positive text-white">+</h1>
                    </div>
                    <h3 class="text-white">Happy Clients</h3>
                </div>
                <div class="col-lg-3 col-6 text-center">
                    <div class="d-flex justify-content-center">
                        <h1 data-purecounter-start="0" data-purecounter-end="90" data-purecounter-duration="1"
                            class="purecounter text-white"></h1>
                        <h1 class="positive text-white">+</h1>
                    </div>
                    <h3 class="text-white">Complete Project</h3>
                </div>
                <div class="col-lg-3 col-6 text-center">
                    <div class="d-flex justify-content-center">
                        <h1 data-purecounter-start="0" data-purecounter-end="90" data-purecounter-duration="1"
                            class="purecounter text-white"></h1>
                        <h1 class="positive text-white">+</h1>
                    </div>
                    <h3 class="text-white">Product Sale</h3>
                </div>
            </div>
        </div>
    </section> --}}
    @include('user.components.brands')
@endsection


{{-- @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="{{asset('front/assets/js/count.js')}}"></script>
@endsection --}}
