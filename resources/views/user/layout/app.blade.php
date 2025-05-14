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

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Dynamically set favicon using the site logo -->
    <link rel="icon" type="image/png" href="{{ Storage::url(getLogo()) }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('front/assets/css/style.css') }}" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
    @yield('style')
    <style>
        .nav-tabs .nav-link.active {
            border-bottom: 2px solid rgba(76, 73, 227, 1) !important;
        }
    </style>

    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        .ui-autocomplete {
            padding: 0 !important;
        }

        .ui-menu .ui-menu-item-wrapper {
            text-align: left;
        }

        .ui-menu {
            /* width: 500px !important; */
            max-height: 320px !important;
            overflow-y: scroll !important;
            overflow-x: hidden !important;
            border-radius: 7px !important;
        }

        /* Customize the scrollbar */
        .ui-menu::-webkit-scrollbar {
            width: 10px;
            /* Width of the scrollbar */
        }

        /* Track */
        .ui-menu::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Color of the track */
        }

        /* Handle */
        .ui-menu::-webkit-scrollbar-thumb {
            background: #888;
            /* Color of the scrollbar handle */
        }

        /* Handle on hover */
        .ui-menu::-webkit-scrollbar-thumb:hover {
            background: #555;
            /* Color of the scrollbar handle on hover */
        }

        .whatsapp-float {
            position: fixed;
            width: 65px;
            height: 65px;
            bottom: 25px;
            right: 40px;
            background-color: #25d366;
            color: #fff;
            border-radius: 50px;
            /* text-align: center; */
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 1000;
        }

        .whatsapp-icon {
            /* margin-top: 16px; */
        }

        .hidden-content {
            display: none;
        }

        /* .extra-categories {
            display: none;
        }

        .extra-categories.show {
            display: block;
        } */
    </style>
    <style>
        @media(max-width: 576px) {

            .selling-product {
                width: 100% !important;
            }

        }
    </style>
    <style>
        .search-icon-spinner{
            position: absolute;
            top: 30%;
            left: 10px;
            transform: translateY(-50%);
            font-size: 18px;
        }
    </style>
</head>

<body>

    @include('user.layout.header')
    @include('user.layout.navigation')

    @yield('content')

    @include('user.layout.footer')

    @include('user.layout.offcanvas')

    <!-- WhatsApp floating button -->
    @php
        $socials = App\Models\Setting::first();
    @endphp
    <a href="https://wa.me/{{ $socials->company_phone ?? '+44118344809' }}"
        class="whatsapp-float d-flex justify-content-center align-items-center" target="_blank">
        <i class="bi bi-whatsapp whatsapp-icon"></i>
    </a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"
        integrity="sha512-DUC8yqWf7ez3JD1jszxCWSVB0DMP78eOyBpMa5aJki1bIRARykviOuImIczkxlj1KhVSyS16w2FSQetkD4UU2w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- <script src="assets/js/slick.min.js"></script> -->
    <script src="{{ asset('front/assets/js/main.js') }}"></script>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        document.getElementById('see-all-brands').addEventListener('click', function() {
            document.querySelectorAll('.brand-item.d-none').forEach(function(item) {
                item.classList.remove('d-none');
            });
            this.parentElement.classList.add('d-none'); // Hide the "See All Brands" button after clicking
        });
    </script>


    <script>
        // document.addEventListener("DOMContentLoaded", function() {

        //     const searchInput = document.getElementById("searchInput");

        //     var suggestionsContainer = $("#suggestionsContainer");
        //     $("#searchInput").autocomplete({
        //         source: function(request, response) {
        //             var searchTerm = request.term;
        //             performAddressSearch(searchTerm, response);
        //         },
        //         minLength: 1,
        //         select: function(event, ui) {
        //             console.log(ui.item);
        //         },
        //         appendTo: "#suggestionsContainer"
        //     }).autocomplete("instance")._renderItem = function(ul, item) {
        //         // return $("<li>").append("<div> <a href='/product/details'> " + item.label + "</div>").appendTo(ul);
        //         return $("<li>").append(
        //             `<div> <a href='/category/${item.product.category.code}/product/${item.product.sku}' class="nav-link"> ${item.label} </a> </div>`
        //         ).appendTo(ul);
        //     };

        //     function performAddressSearch(searchTerm, response) {

        //         let category = $('#searchCategoryId').val();
        //         let url = '/search-products';

        //         if (category != null && category != '') {
        //             url = '/search-products/' + category;
        //         }

        //         $.ajax({
        //             url: url,
        //             dataType: "json",
        //             data: {
        //                 query: searchTerm,
        //             },
        //             success: function(data) {
        //                 console.log(data);
        //                 var suggestions = [];
        //                 for (var i = 0; i < data.product.length; i++) {
        //                     suggestions.push({
        //                         value: data.product[i].name,
        //                         label: data.product[i].name,
        //                         id: data.product[i].id,
        //                         product: data.product[i]
        //                     });
        //                 }
        //                 response(suggestions);
        //             }
        //         });

        //     }

        // });

        // with loading and not found message
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchInput");
            var suggestionsContainer = $("#suggestionsContainer");

            $("#searchInput").autocomplete({
                source: function(request, response) {
                    var searchTerm = request.term;
                    $('.search-icon').removeClass('fa fa-search search-icon').addClass('fa fa-spinner fa-spin search-icon-spinner');

                    performAddressSearch(searchTerm, response);
                },
                minLength: 1,
                select: function(event, ui) {
                    console.log(ui.item);
                },
                appendTo: "#suggestionsContainer"
            })
            .autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>").append(
                    `<div><a class="${item.textColorClass}" href='/category/${item.product?.category?.code}/product/${item?.product?.sku}' class="nav-link"> ${item?.label} </a></div>`
                ).appendTo(ul);

            };

            function performAddressSearch(searchTerm, response) {
                let category = $('#searchCategoryId').val();
                let url = '/search-products';

                if (category != null && category != '') {
                    url = '/search-products/' + category;
                }

                $.ajax({
                    url: url,
                    dataType: "json",
                    data: {
                        query: searchTerm,
                    },
                    success: function(data) {
                        console.log(data);
                        var suggestions = [];

                        // Create suggestions from the returned data
                        if (data.product && data.product.length > 0) {
                            for (var i = 0; i < data.product.length; i++) {
                                suggestions.push({
                                    value: data.product[i].name,
                                    label: data.product[i].name,
                                    id: data.product[i].id,
                                    product: data.product[i],
                                    textColorClass: 'text-dark'
                                });
                            }
                        }
                        else
                        {
                            suggestions.push({
                                value: "Product not found",
                                label: "Product not found",
                                id: 0,
                                product: {},
                                textColorClass: 'text-danger'
                            });
                        }

                        response(suggestions);
                    },
                    error: function() {
                        // Handle error and show appropriate message
                        $('#suggestionsContainerLoading').html(
                            '<div class="error-message">An error occurred while searching. Please try again.</div>'
                            );
                    },
                    complete: function() {
                        // Hide the loading spinner
                        $('#suggestionsContainerLoading').hide();
                        $('.search-icon-spinner').removeClass('fa fa-spinner fa-spin search-icon-spinner').addClass('fa fa-search search-icon');
                    }
                });
            }
        });



        // search for mobile view
        // document.addEventListener("DOMContentLoaded", function() {

        //     const searchInput = document.getElementById("searchInput2");

        //     var suggestionsContainer = $("#suggestionsContainer2");
        //     $("#searchInput2").autocomplete({
        //         source: function(request, response) {
        //             var searchTerm = request.term;
        //             performAddressSearch(searchTerm, response);
        //         },
        //         minLength: 1,
        //         select: function(event, ui) {
        //             console.log(ui.item);
        //         },
        //         appendTo: "#suggestionsContainer2"
        //     }).autocomplete("instance")._renderItem = function(ul, item) {
        //         // return $("<li>").append("<div> <a href='/product/details'> " + item.label + "</div>").appendTo(ul);
        //         return $("<li>").append(
        //             `<div> <a href='/category/${item.product.category.code}/product/${item.product.sku}' class="nav-link"> ${item.label} </a> </div>`
        //         ).appendTo(ul);
        //     };

        //     function performAddressSearch(searchTerm, response) {

        //         let category = $('#searchCategoryId').val();
        //         let url = '/search-products';

        //         if (category != null && category != '') {
        //             url = '/search-products/' + category;
        //         }

        //         $.ajax({
        //             url: url,
        //             dataType: "json",
        //             data: {
        //                 query: searchTerm,
        //             },
        //             success: function(data) {
        //                 console.log(data);
        //                 var suggestions = [];
        //                 for (var i = 0; i < data.product.length; i++) {
        //                     suggestions.push({
        //                         value: data.product[i].name,
        //                         label: data.product[i].name,
        //                         id: data.product[i].id,
        //                         product: data.product[i]
        //                     });
        //                 }
        //                 response(suggestions);
        //             }
        //         });

        //     }

        // });

        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchInput2");
            var suggestionsContainer = $("#suggestionsContainer2");

            $("#searchInput2").autocomplete({
                source: function(request, response) {
                    var searchTerm = request.term;
                    $('.search-icon').removeClass('fa fa-search search-icon').addClass('fa fa-spinner fa-spin search-icon-spinner');

                    performAddressSearch(searchTerm, response);
                },
                minLength: 1,
                select: function(event, ui) {
                    console.log(ui.item);
                },
                appendTo: "#suggestionsContainer2"
            })
            .autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>").append(
                    `<div><a class="${item.textColorClass}" href='/category/${item.product?.category?.code}/product/${item?.product?.sku}' class="nav-link"> ${item?.label} </a></div>`
                ).appendTo(ul);

            };

            function performAddressSearch(searchTerm, response) {
                let category = $('#searchCategoryId').val();
                let url = '/search-products';

                if (category != null && category != '') {
                    url = '/search-products/' + category;
                }

                $.ajax({
                    url: url,
                    dataType: "json",
                    data: {
                        query: searchTerm,
                    },
                    success: function(data) {
                        console.log(data);
                        var suggestions = [];

                        // Create suggestions from the returned data
                        if (data.product && data.product.length > 0) {
                            for (var i = 0; i < data.product.length; i++) {
                                suggestions.push({
                                    value: data.product[i].name,
                                    label: data.product[i].name,
                                    id: data.product[i].id,
                                    product: data.product[i],
                                    textColorClass: 'text-dark'
                                });
                            }
                        }
                        else
                        {
                            suggestions.push({
                                value: "Product not found",
                                label: "Product not found",
                                id: 0,
                                product: {},
                                textColorClass: 'text-danger'
                            });
                        }

                        response(suggestions);
                    },
                    error: function() {
                        // Handle error and show appropriate message
                        $('#suggestionsContainerLoading').html(
                            '<div class="error-message">An error occurred while searching. Please try again.</div>'
                            );
                    },
                    complete: function() {
                        // Hide the loading spinner
                        $('#suggestionsContainerLoading').hide();
                        $('.search-icon-spinner').removeClass('fa fa-spinner fa-spin search-icon-spinner').addClass('fa fa-search search-icon');
                    }
                });
            }
        });
    </script>

</body>

</html>
