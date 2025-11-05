<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Nawasena TKA - @yield('title')</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Dynamic Meta Tags -->
    @stack('meta')

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- CSS
 ============================================ -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/sal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/euclid-circulara.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/magnigy-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/plyr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/jodit.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
</head>

<body>
    <div id="my_switcher" class="my_switcher">
        <ul>
            <li>
                <a href="javascript: void(0);" data-theme="light" class="setColor light">
                    <img src="{{ asset('assets/images/logo-nawapp-academy.png') }}" alt="Sun images"><span
                        title="Light Mode">
                        Light</span>
                </a>
            </li>
            <li>
                <a href="javascript: void(0);" data-theme="dark" class="setColor dark">
                    <img src="{{ asset('assets/images/logo-nawapp-academy.png') }}" alt="Vector Images"><span
                        title="Dark Mode">
                        Dark</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Start Header Area -->
    <header class="rbt-header rbt-header-10">
        @include('layouts.v2.header')

    </header>

    <!-- Mobile Menu Section -->
    <div class="popup-mobile-menu">
        <div class="inner-wrapper">
            <div class="inner-top">
                <div class="content">
                    <div class="logo">
                        <div class="logo logo-dark">
                            <a href="index.html">
                                <img src="{{ asset('assets/images/logo-nawapp-academy.png') }}"
                                    alt="Education Logo Images">
                            </a>
                        </div>

                        <div class="logo d-none logo-light">
                            <a href="index.html">
                                <img src="{{ asset('assets/images/logo-nawapp-academy.png') }}"
                                    alt="Education Logo Images">
                            </a>
                        </div>
                    </div>
                    <div class="rbt-btn-close">
                        <button class="close-button rbt-round-btn"><i class="feather-x"></i></button>
                    </div>
                </div>
                @if (Auth::check())
                    <p class="description">Welcome, {{ Auth::user()->name }}</p>
                @endif
            </div>

            <nav class="mainmenu-nav">
                <ul class="mainmenu">
                    <li><a href="#" class="active"><i class="feather-home"></i><span>Dashboard</span></a>
                    </li>
                    <li><a href="#" class=""><i class="feather-user"></i><span>Paket Ku</span></a>
                    </li>
                    <li><a href="#" class=""><i class="feather-book-open"></i><span>Rapor Ku</span></a></li>
                </ul>
            </nav>

        </div>
    </div>
    <!-- End Side Vav -->
    <a class="close_side_menu" href="javascript:void(0);"></a>

    @yield('wrapper')

    <div class="rbt-progress-parent">
        <svg class="rbt-back-circle svg-inner" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <!-- JS
============================================ -->
    <!-- Modernizer JS -->
    <script src="{{ asset('assets/js/vendor/modernizr.min.js') }}"></script>
    <!-- jQuery JS -->
    <script src="{{ asset('assets/js/vendor/jquery.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
    <!-- sal.js -->
    <script src="{{ asset('assets/js/vendor/sal.js') }}"></script>
    <!-- Dark Mode Switcher -->
    <script src="{{ asset('assets/js/vendor/js.cookie.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery.style.switcher.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/swiper.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery-appear.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/odometer.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/backtotop.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/isotop.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/imageloaded.js') }}"></script>

    <script src="{{ asset('assets/js/vendor/wow.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/waypoint.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/easypie.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/text-type.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery-one-page-nav.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/magnify-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/paralax-scroll.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/paralax.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/countdown.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/plyr.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jodit.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/Sortable.min.js') }}"></script>



    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
