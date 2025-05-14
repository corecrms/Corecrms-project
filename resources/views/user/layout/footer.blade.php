    <!-- FOOTER -->
    <footer id="footer" class="py-2">
        @php
            $socials = App\Models\Setting::first();
        @endphp
        <div class="container-xxl container-fluid border-bottom border-dark pb-2">
            <div class="row justify-content-between">
                <div class="col-md-2 col-6 mt-1">
                    <h2 class="heading fs-4">ALL POLICY</h2>
                    <p class="m-0 fs-6">
                        <a href="{{route('user.return-policy')}}" class="text-dark">Return Policy</a>
                    </p>
                    <p class="m-0 fs-6">
                        <a href="{{route('user.exchange-policy')}}" class="text-dark">Exchange Policy</a>
                    </p>
                    <p class="m-0 fs-6">
                        <a href="{{ route('user.privacy-policy') }}" class="text-dark">Privacy Policy</a>
                    </p>
                </div>
                <div class="col-md-2 col-6 mt-1">
                    <h2 class="heading fs-4">NEED HELP</h2>
                    <p class="m-0 fs-6"><a href="#" class="text-dark">FAQs</a></p>
                    <p class="m-0 fs-6">
                        <a href="#" class="text-dark">Customer Care</a>
                    </p>
                    <p class="m-0 fs-6">
                        <a href="#" class="text-dark">Become A Seller</a>
                    </p>
                    <p class="m-0 fs-6">
                        <a href="#" class="text-dark">Support Center</a>
                    </p>
                </div>
                <div class="col-md-2 col-6 mt-1">
                    <h2 class="heading fs-4">COMPANY</h2>
                    <p class="m-0 fs-6"><a href="{{route('about-us')}}" class="text-dark">About Us</a></p>
                    <p class="m-0 fs-6">
                        <a href="#" class="text-dark">Delivery Information</a>
                    </p>
                    <p class="m-0 fs-6">
                        <a href="#" class="text-dark">Your Careers</a>
                    </p>
                    <p class="m-0 fs-6">
                        <a href="{{ route('user.term-and-condition') }}" class="text-dark">Terms and Conditions</a>
                    </p>
                </div>
                <div class="col-md-2 col-6 mt-1">
                    <h2 class="heading fs-4">CORPORATE</h2>
                    <p class="m-0 fs-6"><a href="#" class="text-dark">About Us</a></p>
                    <p class="m-0 fs-6">
                        <a href="#" class="text-dark">Delivery Information</a>
                    </p>
                    <p class="m-0 fs-6">
                        <a href="#" class="text-dark">Your Careers</a>
                    </p>
                </div>
                <div class="col-md-3 mt-1">
                    <h2 class="heading fs-5">STAY CONNECTED</h2>
                    <p class="m-0 fs-6">
                        <a href="tel:{{ $socials->company_phone ?? '+880 123 456 789' }}" class="text-dark"><i class="bi bi-telephone me-2"></i>
                            <span>
                                {{ $socials->company_phone ?? '+880 123 456 789' }}
                            </span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="container py-2">
            <div class="row  justify-content-between">
                <div class="col-md-4 text-center mt-2">
                    <h2 class="heading fs-4">PAYMENT OPTION</h2>
                    <img src="{{ asset('front/assets/img/credit-cards.png') }}" alt="" class="img fluid" />
                </div>


                <div class="col-md-4 text-center mt-2">
                    <h2 class="heading fs-4">SOCIAL MEDIA</h2>
                    <div class="d-flex align-items-center justify-content-center gap-3 mt-3">

                            <a href="{{ $socials->linkedin ?? 'www.linkedin.com' }}">
                                <img src="{{ asset('front/assets/img/footer-linkedin.svg') }}" alt=""
                                    class="img-fluid" />
                            </a>
                            <a href="{{ $socials->fb ?? 'www.facebook.com' }}">
                                <img src="{{ asset('front/assets/img/footer-facebook.svg') }}" alt=""
                                    class="img-fluid" />
                            </a>
                            <a href="{{ $socials->twitch ?? 'www.twitch.com' }}">
                                <img src="{{ asset('front/assets/img/footer-twitch.svg') }}" alt=""
                                    class="img-fluid" />
                            </a>
                            <a href="{{ $socials->twitter ?? 'www.twitter.com' }}">
                                <img src="{{ asset('front/assets/img/footer-twitter.svg') }}" alt=""
                                    class="img-fluid" />
                            </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- /FOOTER -->
