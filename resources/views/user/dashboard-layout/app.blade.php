<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>
        {{ getSetting()->company_name ?? '' }} | @yield('title')
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" rel="stylesheet"
        type="text/css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

     <!-- Dynamically set favicon using the site logo -->
     <link rel="icon" type="image/png" href="{{Storage::url(getLogo())}}" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('front/assets/css/style.css') }}" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Template Stylesheet -->
    <link href="{{asset('front/dasheets/css/style.css')}}" rel="stylesheet" />
    <link href="{{asset('front/assets/css/style.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" />
    @yield('style')
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
</head>

<body>

    @include('user.layout.header')
    @include('user.layout.navigation')

    <div class="container-xxl container-fluid position-relative bg-white d-flex mt-5">
        <!-- Sidebar Start -->
        @include('user.dashboard-layout.sidebar')
        <!-- Sidebar End -->

        <div class="content border-0">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand navbar-light px-4 py-0">
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
            </nav>
            <!-- Navbar End -->
            <!-- Content Start -->
            @yield('content')
            <!-- Content End -->
        </div>

    </div>


    @include('user.layout.footer')

    @include('user.layout.offcanvas')


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"
        integrity="sha512-DUC8yqWf7ez3JD1jszxCWSVB0DMP78eOyBpMa5aJki1bIRARykviOuImIczkxlj1KhVSyS16w2FSQetkD4UU2w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- <script src="assets/js/slick.min.js"></script> -->
    <script src="{{ asset('front/assets/js/main.js') }}"></script>
    <script>
      $(".sidebar-toggler").click(function () {
        $(".customer-sidebar, .content").toggleClass("open");
        return false;
      });
    </script>
    @if (session()->has('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1000,
            })
        </script>
    @endif
    @if (session()->has('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
            })
        </script>
    @endif
    @yield('scripts')
</body>

</html>
