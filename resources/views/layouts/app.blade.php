<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>HRMIS | HRMIS - Human Resources Management Information System</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/logo/gmda-logo.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.transitions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/meanmenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/educate-custon-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/morrisjs/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scrollbar/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/metisMenu/metisMenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/metisMenu/metisMenu-vertical.css') }}">
    <link rel="stylesheet" href="{{ asset('css/calendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/calendar/fullcalendar.print.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('style-2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css" />

    @yield('css')
    <style>

    </style>
</head>

<body>
    <div class="left-sidebar-pro" style="display: none">
        @include('layouts.sidebar')
    </div>
    <div class="all-content-wrapper">
        {{-- <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="logo-pro">
                        <a href="#"><img class="main-logo" src="img/logo/logo.png" alt="" /></a>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="header-advance-area" style="margin-top: 60px;">
            <div class="header-top-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="header-top-wraper">
                                <div class="row">
                                    <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12" style="display: none">
                                        <div class="menu-switcher-pro">
                                            <button type="button" id="sidebarCollapse"
                                                class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
                                                <i class="educate-icon educate-nav"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        {{-- <h4 class="title-hrmis">HRMIS</h4> --}}
                                        <div class="logo-div">
                                            <a href="{{ env('APP_URL') }}/GMDA/gmda-leave/public/"><img
                                                    class="main-logo" src="{{ asset('img/logo/gmda-logo.png') }}"
                                                    alt="" style="max-width: 75px;" /></a>
                                            <div>
                                                <p class="title-hrmis">Human</p>
                                                <p class="title-hrmis">Resource </p>
                                                <p class="title-hrmis">Management</p>
                                                <p class="title-hrmis">Information System</p>
                                            </div>
                                        </div>

                                    </div>

                                    @include('layouts.topbar')

                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="header-right-info">
                                            <ul class="nav navbar-nav mai-top-nav header-right-menu">
                                                <li class="nav-item">
                                                    <a href="#" data-toggle="dropdown" role="button"
                                                        aria-expanded="false" class="nav-link dropdown-toggle">
                                                        <img src="img/product/pro4.jpg" alt="" />
                                                        <span class="admin-name">{{ Auth::user()->name }}
                                                            (Finance)</span>
                                                        <i class="fa fa-angle-down edu-icon edu-down-arrow"></i>
                                                    </a>
                                                    <ul role="menu"
                                                        class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                                        <li><a href="#"><span
                                                                    class="edu-icon edu-user-rounded author-log-ic"></span>My
                                                                Profile</a>
                                                        </li>
                                                        @php
                                                            $modules = \App\Models\moduleUrl::where(
                                                                'status',
                                                                'Active',
                                                            )->get();
                                                            $logout_url = \App\Models\moduleUrl::where(
                                                                'status',
                                                                'Active',
                                                            )
                                                                ->where('id', 1)
                                                                ->first();
                                                        @endphp
                                                        @foreach ($modules as $mod)
                                                            @if (\App\Helpers\commonHelper::isPermissionExist($mod->permission_name))
                                                                @if ($mod->id == 1)
                                                                    <li style="display: flex"><a
                                                                            href="{{ $mod->url }}{{ $mod->project_name }}/login?is_auth=yes{{ $mod->is_jwt_req == 1 ? '&token=' . session('jwt_token') : '' }}"><span
                                                                                class="edu-icon edu-user-rounded author-log-ic"></span>{{ $mod->name }}</a>
                                                                        @if ($mod->id == auth()->user()->landing_module)
                                                                            <a href="javascript:void(0)"><span
                                                                                    class="badge badge-success"
                                                                                    style="font-size: 9px">
                                                                                    <i
                                                                                        class="fa-solid fa-arrow-right"></i>
                                                                                </span></a>
                                                                        @else
                                                                            <a href="javascript:void(0)"
                                                                                class="def"
                                                                                data-id="{{ $mod->id }}"><span
                                                                                    class="badge badge-secondary"
                                                                                    style="font-size: 8px">Set
                                                                                    default</span></a>
                                                                        @endif
                                                                    </li>
                                                                @else
                                                                    <li style="display: flex"><a
                                                                            href="{{ $mod->url }}{{ $mod->project_name }}{{ $mod->is_jwt_req == 1 ? '?token=' . session('jwt_token') : '' }}"><span
                                                                                class="edu-icon edu-user-rounded author-log-ic"></span>{{ $mod->name }}
                                                                        </a>
                                                                        @if ($mod->id == auth()->user()->landing_module)
                                                                            <a href="javascript:void(0)"> <span
                                                                                    class="badge badge-success"
                                                                                    style="font-size: 9px"><i
                                                                                        class="fa-solid fa-check"></i></span></a>
                                                                        @else
                                                                            <a href="javascript:void(0)"
                                                                                class="def"
                                                                                data-id="{{ $mod->id }}"><span
                                                                                    class="badge badge-secondary"
                                                                                    style="font-size: 8px">Set
                                                                                    default</span></a>
                                                                        @endif

                                                                    </li>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        <li>
                                                            <a
                                                                href="{{ $logout_url->url }}{{ $logout_url->project_name }}/logout-ano">
                                                                <span
                                                                    class="edu-icon edu-locked author-log-ic"></span>Log
                                                                Out
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu start -->
            @include('layouts.mobile-menu')
            <!-- Mobile Menu end -->
        </div>

        @yield('content')

        <div class="footer-copyright-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer-copy-right">
                            <p>Copyright © 2024. All rights reserved. Developed by <a
                                    href="https://www.webcomindia.biz/">Web .com (India)</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{--
    <script src="js/vendor/jquery-1.12.4.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <script src="{{ asset('js/jquery-price-slider.js') }}"></script>
    <script src="{{ asset('js/jquery.meanmenu.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('js/counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('js/counterup/waypoints.min.js') }}"></script>
    <script src="{{ asset('js/counterup/counterup-active.js') }}"></script>
    <script src="{{ asset('js/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('js/scrollbar/mCustomScrollbar-active.js') }}"></script>
    <script src="{{ asset('js/metisMenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('js/metisMenu/metisMenu-active.js') }}"></script>
    <script src="{{ asset('js/morrisjs/raphael-min.js') }}"></script>
    <script src="{{ asset('js/morrisjs/morris.js') }}"></script>
    <script src="{{ asset('js/morrisjs/morris-active.js') }}"></script>
    <script src="{{ asset('js/sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('js/sparkline/jquery.charts-sparkline.js') }}"></script>
    <script src="{{ asset('js/sparkline/sparkline-active.js') }}"></script>
    <script src="{{ asset('js/calendar/moment.min.js') }}"></script>
    <script src="{{ asset('js/calendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/calendar/fullcalendar-active.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    {{--
    <script src="{{ asset('js/tawk-chat.js')}}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>

    @yield('js')
    <script>
        $(document).ready(function() {
            $('#sidebarCollapse').trigger('click');
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#date').inputmask('dd/mm/YYYY');
        });

        @if (Session::has('success'))
            // toastr.options =
            // {
            // "closeButton" : true,
            // "progressBar" : true
            // }
            toastr.success("{{ session('success') }}");
        @endif

        @if (Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.error("{{ session('error') }}");
        @endif

        @if (Session::has('info'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.info("{{ session('info') }}");
        @endif

        @if (Session::has('warning'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.warning("{{ session('warning') }}");
        @endif

        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif

        $(document).ready(function() {
            $(".def").on("click", function(e) {
                e.preventDefault();
                // Collect form data
                let formData = {
                    id: $(this).attr('data-id'),
                    _token: $('meta[name="csrf-token"]').attr("content") // CSRF token
                };
                $.blockUI({
                    message: '<h1>Just a moment...</h1>'
                });
                // AJAX POST request
                $.ajax({
                    url: "{{ route('set-default') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        $.unblockUI();
                        toastr.success(response.message);
                        console.log(response);
                        if (response.success) {
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        // Handle error
                        //alert("An error occurred. Please try again.");
                        $.unblockUI();
                        toastr.error(response.message);
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>
