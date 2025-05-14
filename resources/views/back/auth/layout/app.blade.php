<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
       <!-- Icon Font Stylesheet -->
       <link
       href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
       rel="stylesheet"
     />
     <link
       rel="stylesheet"
       href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
     />

     <!-- Customized Bootstrap Stylesheet -->
     <link
       href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
       rel="stylesheet"
     />

     <!-- Template Stylesheet -->
     <link href="{{asset('back/assets/dasheets/css/style.css')}}" rel="stylesheet" />
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('style')

    <title>{{ env('APP_NAME') }} | @yield('title')</title>
</head>

<body class="signup-body">
    {{-- <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                @yield('content')
            </main>
        </div>

    </div> --}}
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('back/assets/js/scripts.js') }}"></script>
    <script>
        document.getElementById('inputRememberPassword').addEventListener('focus', function() {
            this.nextElementSibling.classList.add('focus');
        });

        document.getElementById('inputRememberPassword').addEventListener('blur', function() {
            this.nextElementSibling.classList.remove('focus');
        });
    </script>
    @yield('script')
    @if (session()->has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
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
</body>

</html>
