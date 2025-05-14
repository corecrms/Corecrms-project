<div class="content">
    @php
        $links = \App\Models\Setting::first() ?? [];
    @endphp
    <div class="container-fluid pt-4 px-4 mb-3">
        <div class="bg-footer rounded-top-5 p-3">
            <p class="fw-bold ms-2">{{$links->footer ?? 'ITSOL - Inventory and Stock Management'}} </p>
            <div class="row">
                <div class="col-md-6 align-items-center align-middle">
                    <div class="d-flex align-items-center">

                        @if (getLogo() != null)
                        <img src="{{asset('storage'. getLogo())}}" alt="No Image" width="50" class="img-fluid">
                        @else
                            <img src="{{ asset('back/assets/dasheets/img/itsol.png') }}" class="img-fluid" alt="" />
                        @endif
                        <div class="ms-2">
                            <p class="m-0">© 2024 Developed by {{$links->developed_by ?? 'ITSOL'}}</p>
                            <p class="m-0">All right reserved - v2</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-end">

                    <a href="{{ $links->linkedin ?? '' }}" target="_blank" class="text-decoration-none">
                        <img src="{{ asset('back/assets/dasheets/img/footer-linkedin.svg') }}" class="img-fluid me-2"
                            alt="linkedin">
                    </a>

                    <a href="{{ $links->fb ?? '' }}" target="_blank" class="text-decoration-none">
                        <img src="{{ asset('back/assets/dasheets/img/footer-facebook.svg') }}" class="img-fluid me-2"
                            alt="facebook">
                    </a>
                    <a href="{{ $links->twitch ?? '' }}" target="_blank" class="text-decoration-none">
                        <img src="{{ asset('back/assets/dasheets/img/footer-twitch.svg') }}" class="img-fluid me-2"
                            alt="Twitch">
                    </a>
                    <a href="{{ $links->twitter ?? '' }}" target="_blank" class="text-decoration-none">
                        <img src="{{ asset('back/assets/dasheets/img/footer-twitter.svg') }}" class="img-fluid me-2"
                            alt="Twitter">
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
