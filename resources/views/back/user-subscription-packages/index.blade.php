@extends('back.layout.app')

@section('content')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="content">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-12">
                        <div class="COnftainer_boxes">
                            <h1>Subscription Plans</h1>
                            <p>
                                Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                                Eveniet placeat quidem tempore repellendus qui voluptatibus id
                                in officiis molestias! Odio consequuntur voluptatem libero
                                neque ipsam! Voluptatem odit illo obcaecati nihil.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mt-3">

                    @foreach ($subscriptionPackages as $package)
                        <div class="col-lg-4 col-md-6 col-12 mt-3">
                            <div class="subscrib_plane subscrib_plane_basic">
                                <div class="row">
                                    <div class="col-8 mx-auto">
                                        <div class="headers">
                                            <div class="package">
                                                <span class="icons"><img src="{{ asset('dashassets/images/basic.svg') }}"
                                                        alt="" /></span>
                                                <h2>{{ $package->name }}</h2>
                                            </div>
                                            <div class="price">
                                                <h2>${{ $package->price }}</h2>
                                                {{-- duration --}}
                                                <span>/{{ $package->duration }} months</span>
                                            </div>
                                            {{-- <div class="off"><span>Annually save: 25% </span></div>
                                        <div class="afteroff"><span>$220</span></div> --}}
                                        </div>
                                    </div>
                                </div>

                                <ul class="subscription_desc">
                                    @foreach ($subscriptionServices as $service)
                                        @php
                                            $isServiceExist = $package->subscriptionServices->contains($service->id);
                                        @endphp
                                        <li class="{{ $isServiceExist ? 'tic' : 'error' }}">
                                            <span class="icons"><img
                                                    src="{{ $isServiceExist ? asset('dashassets/images/tick.svg') : asset('dashassets/images/cancel.svg') }}"
                                                    alt="" /></span>
                                            <span>Lorem Ipsum is simply dummy text</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="text-center mt-3">
                                    @if ($package->id == $user->subscription_package_id)
                                        <button class="btn">Current</button>
                                    @endif

                                    {{-- <button class="btn">
                                    Upgrade <i class="fas fa-chevron-right me-1"></i>
                                </button> --}}


                                    <a href="{{ route('subscription.packages.show', $package->id) }}"
                                        class="btn theme_btn_blue">
                                        Upgrade <i class="fas fa-chevron-right me-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
@endsection
