<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <!-- jQuery UI JS -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" />

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />

    <!-- Dynamically set favicon using the site logo -->
    <link rel="icon" type="image/png" href="{{ Storage::url(getLogo()) }}" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css"  /> --}}

    <!-- Template Stylesheet -->
    {{-- <link href="{{ asset('back/assets/dasheets/css/style.css') }}" rel="stylesheet" /> --}}
    <link href="{{ asset('back/assets/dasheets/css/crm.css') }}" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    {{-- <link href="{{ asset('back/assets/css/styles.css') }}" rel="stylesheet" /> --}}
    {{-- <link href="{{ asset('back/assets/js/simplebar/css/simplebar.css') }}" rel="stylesheet" /> --}}


    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('style')

    {{-- <title>{{ env('APP_NAME') }} | @yield('title')</title> --}}
    <title>{{ getSetting()->company_name ?? '' }} | @yield('title')</title>
    <style>
        .page-link {
            color: black;
        }

        .dt-buttons {
            display: none;
        }

        .cursor-p {
            cursor: pointer;
        }

        .dataTables_filter {
            display: none;
        }
    </style>
    <style>
        .orange-bg {
            background: #FE9F44;
        }

        .orange-bg-light {
            background: #FFC6A4;
        }

        .btn-orange-bg {
            background: #FE9F44;
            color: white;
        }

        .btn-orange-bg-light {
            background: #FFC6A4;

        }

        .btn-orange-bg:hover {
            background: #FFC6A4;
            color: white;
        }

        .btn-orange-bg-light:hover {
            background: #FE9F44;
        }

        .darkblue-bg {
            background: #092C4C;
        }

        .darkgreen-bg {
            background: #0E9384;
        }

        .normalblue-bg {
            background: #155EEF;
        }
    </style>
</head>

<body>
    {{-- @include('back.layout.navbar')

    @include('back.layout.sidebar-2')
    <!-- Content Start -->
    <div class="content">
      <!-- Navbar Start -->
      <nav class="navbar navbar-expand border-bottom bg-white navbar-light sticky-top px-4 py-0"
          style="height: 80px">
          <a href="{{route('dashboard')}}" class="navbar-brand d-flex d-lg-none me-4">
              <h2 class="text-primary mb-0"></h2>

          </a>
          <a href="#"
              class="sidebar-toggler text-decoration-none flex-shrink-0 align-items-center d-inline-flex">
              <i class="fa fa-bars"></i>
          </a>
          @php
              $role = auth()->user()->roles->first()->name ?? '';
              if ($role == 'Admin' || $role == 'Manager') {
                  $warehouses = \App\Models\Warehouse::all();
              }else{
                  $warehouses = \App\Models\Warehouse::where('user_id', auth()->user()->id)->get();
              }
              // $warehouses = \App\Models\Warehouse::all();
          @endphp

            @if (auth()->user()->hasRole('Admin'))
                <form class="ms-4" action="{{ route('switch-warehouse') }}" method="POST">
                    @csrf
                    <select class="form-select form-control rounded-5" name="warehouse_id" id="selected_warehouse_id" onchange="this.form.submit()">
                        <option value="">Select warehouse</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $warehouse->id == session('selected_warehouse_id') ? 'selected' : '' }}>{{ $warehouse->users->name ?? '' }}</option>
                        @endforeach
                    </select>
                </form>
            @endif


          <div class="navbar-nav align-items-center ms-auto">
            <div class="nav-item">
                <a href="{{route('sales.pos')}}" class="nav-link">
                    <button class="border-theme btn bg-theme rounded-5">POS</button>
                </a>

            </div>
              <div class="nav-item dropdown">
                  <a href="#" class="nav-link" data-bs-toggle="dropdown">
                      <i class="fa fa-bell align-items-center d-inline-flex"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">

                        @php
                            $notifications = \App\Models\UserNotification::all();
                        @endphp
                      @forelse ( $notifications as $notification)
                          <a href="#" class="dropdown-item">
                              <h6 class="fw-normal mb-0">{{ $notification->subject }}</h6>
                              <small>{{ $notification->created_at->diffForHumans() }}</small>
                          </a>
                        @empty
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">No notification</h6>
                            </a>
                      @endforelse

                      <a href="{{route('notification.index')}}" class="dropdown-item text-center">See all notifications</a>
                  </div>
              </div>
              <div class="nav-item dropdown">
                  <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                      <img class="rounded-circle me-lg-2" src="{{(isset(auth()->user()->image) || auth()->user()->image !=null)  ? asset('/storage'.auth()->user()->image) : asset('back/assets/dasheets/img/profile-img.png') }}" alt=""
                          style="width: 40px; height: 40px" />
                  </a>
                  <div class="dropdown-menu dropdown-menu-end bg-light  rounded-0 rounded-bottom m-0">
                      <a href="{{route('profile.index')}}" class="dropdown-item">My Profile</a>
                      @role(['Admin', 'Manager'])
                        <a href="{{route('setting.index')}}" class="dropdown-item">Settings</a>
                      @endrole

                          <li><a class="dropdown-item" href="{{ route('logout') }}"
                              onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">Logout</a>
                      </li>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                      </form>
                  </div>
              </div>
          </div>
      </nav>
    </div> --}}

    {{-- <div class="container-fluid position-relative bg-white d-flex p-0">

        <!-- Sidebar Start -->
        @include('back.layout.sidebar-2')

        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            @include('back.layout.navbar-2')
            <!-- Navbar End -->

            @yield('content')
        </div>
        <!-- Recent Sales End -->

    </div> --}}
    <!-- Content End -->

    <div class="container-fluid position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->

        <!-- Spinner End -->

        <!-- Sidebar Start -->
        @include('back.layout.sidebar-2')

        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="contents">
            <!-- Navbar Start -->
            @include('back.layout.navbar-2')
            <!-- Navbar End -->

            @yield('content')

            @php
                $links = \App\Models\Setting::first() ?? [];
            @endphp

            <div class="container-fluid px-4 mb-5 main-footer">
                <div class="bg-footer p-3 bg-white">
                    <div class="row align-items-center">
                        <div class="col-md-6 align-items-center align-middle">
                            <p class="fw-bold m-0">
                                {{$links->footer ?? 'ITSOL - Inventory and Stock Management'}}
                            </p>
                        </div>
                        <div class="col-md-6 text-end d-flex gap-3 align-items-center justify-content-end">
                            <a href="{{ $links->linkedin ?? '' }}" target="_blank" class="text-decoration-none">
                                <!-- <img
                                    src="dasheets/img/footer-linkedin.svg"
                                    class="img-fluid me-2"
                                    alt="linkedin"
                                /> -->
                                <i class="fa-brands fa-linkedin-in darkorange-txt fs-3"></i>
                            </a>
                            <a href="{{ $links->fb ?? '' }}" target="_blank" class="text-decoration-none">
                                <i class="fa-brands fa-facebook-f fs-4 darkorange-txt"></i>
                            </a>
                            <a href="{{ $links->twitch ?? '' }}" target="_blank" class="text-decoration-none">
                                <!-- <img
                          src="dasheets/img/footer-twitch.svg"
                          class="img-fluid me-2"
                          alt="Twitch"
                      /> -->
                                <i class="fa-brands fa-twitch fs-4 darkorange-txt"></i>
                            </a>
                            <a href="{{ $links->twitter ?? '' }}" target="_blank" class="text-decoration-none">
                                <!-- <img
                          src="dasheets/img/footer-twitter.svg"
                          class="img-fluid me-2"
                          alt="Twitter"
                      /> -->
                                <i class="fa-brands fa-twitter fs-4 darkorange-txt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            {{-- @include('back.layout.footer') --}}

        </div>
        <!-- Recent Sales End -->

        <!-- Calendar Modal -->
        <div class="modal fade" id="myModal" aria-labelledby="exampleModalToggleLabel" tabindex="-1"
            style="display: none" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content calendar-modal">
                    <div class="modal-header border-0 text-white">
                        <button type="button" class="btn-close text-white calendar-close-btn" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="datepicker"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @yield('modal')

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"> --}}
    </script>
    <script src="{{ asset('back/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/scripts.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatables-simple-demo.js') }}"></script>
    <script src="{{ asset('back/assets/js/simplebar/js/simplebar.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Template Javascript -->
    <script src="{{ asset('back/assets/dasheets/js/main.js') }}"></script>
    <script src="{{ asset('back/assets/dasheets/js/chart.js') }}"></script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script> --}}
    @yield('scripts')




</body>

</html>
