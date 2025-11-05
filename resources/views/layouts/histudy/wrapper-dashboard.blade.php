@extends('layouts.v2.app')

@section('wrapper')
    <div class="rbt-page-banner-wrapper">
        <!-- Start Banner BG Image  -->
        <div class="rbt-banner-image"></div>
        <!-- End Banner BG Image  -->
    </div>
    <!-- Start Card Style -->
    <div class="rbt-dashboard-area rbt-section-overlayping-top rbt-section-gapBottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Start Dashboard Top  -->
                    <div class="rbt-dashboard-content-wrapper">
                        <div class="tutor-bg-photo bg_image bg_image--23 height-350"></div>
                        <!-- Start Tutor Information  -->
                        <div class="rbt-tutor-information">
                            <div class="rbt-tutor-information-left">
                                <div class="thumbnail rbt-avatars size-lg">
                                    <img src="{{ asset('assets/images/team/avatar.jpg') }}" alt="Instructor">
                                </div>
                                <div class="tutor-content">
                                    <h5 class="title text-capitalize">{{ Auth::user()->name }}</h5>
                                    <ul class="rbt-meta rbt-meta-white mt--5">
                                        <li><i class="feather-book"></i>
                                            {{ \App\Models\MyLearning::whereNotNull('active')->where('student_id', \App\Models\Student::where('user_id', Auth::id())->value('id'))->get()->filter(function ($paket) {
                                                    return $paket->is_active; // Hanya ambil paket yang aktif
                                                })->count() }}
                                            Paket Terdaftar
                                        </li>
                                        <li><i class="feather-award"></i>4 Sertifikat</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="rbt-tutor-information-right">
                                {{-- <div class="tutor-btn">
                  <a class="rbt-btn hover-icon-reverse btn-md" href="#">
                    <span class="icon-reverse-wrapper">
                      <span class="btn-text">Become an Instructor</span>
                      <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                      <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                    </span>
                  </a>
                </div> --}}
                            </div>
                        </div>
                        <!-- End Tutor Information  -->
                    </div>
                    <!-- End Dashboard Top  -->

                    <div class="row g-5">
                        <div class="col-lg-3 d-none d-lg-block">
                            <!-- Start Dashboard Sidebar  -->
                            <div class="rbt-default-sidebar sticky-top rbt-shadow-box rbt-gradient-border">
                                <div class="inner">
                                    <div class="content-item-content">

                                        <div class="rbt-default-sidebar-wrapper">
                                            <div class="section-title mb--20">
                                                <h6 class="rbt-title-style-2">Welcome, {{ Auth::user()->name }}</h6>
                                            </div>
                                            <nav class="mainmenu-nav">
                                                <ul class="dashboard-mainmenu rbt-default-sidebar-list">
                                                    <li><a href="{{ route('v3.dashboard') }}"
                                                            class="{{ request()->is('v3/dashboard') ? 'active' : '' }}"><i
                                                                class="feather-home"></i><span>Dashboard</span></a>
                                                    </li>
                                                    <li><a href="{{ route('v3.dashboard.paketku') }}"
                                                            class="{{ request()->is('v3/dashboard/paketku*') ? 'active' : '' }}"><i
                                                                class="feather-user"></i><span>Paket Ku</span></a>
                                                    </li>
                                                    <li><a href="{{ route('v3.dashboard.raporku') }}"
                                                            class="{{ request()->is('v3/dashboard/rapor*') ? 'active' : '' }}"><i
                                                                class="feather-book-open"></i><span>Rapor
                                                                Ku</span></a></li>
                                                </ul>
                                            </nav>

                                            <div class="section-title mb--20 mt--40">
                                                <h6 class="rbt-title-style-2">User</h6>
                                            </div>

                                            <nav class="mainmenu-nav">
                                                <ul class="dashboard-mainmenu rbt-default-sidebar-list">
                                                    <li><a href="#"><i
                                                                class="feather-settings"></i><span>Profil</span></a>
                                                    <li>
                                                        <a href="{{ route('v3.dashboard.order.history') }}">
                                                            <i class="feather-clock"></i>
                                                            <span>Order History</span>
                                                        </a>
                                                    </li>
                                                    </li>
                                                    <li>
                                                        <form id="logout-form-sidebar" action="{{ route('logout') }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                        </form>
                                                        <a href="#"
                                                            onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                                                            <i class="feather-log-out"></i><span>Logout</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- End Dashboard Sidebar  -->
                        </div>

                        <div class="col-lg-9">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Card Style -->
    <div class="rbt-separator-mid">
        <div class="container">
            <hr class="rbt-separator m-0">
        </div>
    </div>
    <!-- Start Footer aera -->
    <footer class="rbt-footer footer-style-1 bg-color-white overflow-hidden">
        <div class="footer-top">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget">
                            <div class="logo logo-dark">
                                <a href="index.html">
                                    <img src="{{ asset('assets/images/logo/logo.png" alt="Edu-cause">
                                </a>
                            </div>
                            <div class="logo d-none logo-light">
                                <a href="index.html">
                                    <img src="{{ asset('assets/images/dark/logo/logo-light.png" alt="Edu-cause">
                                </a>
                            </div>

                            <p class="description mt--20">We’re always in search for talented
                                and motivated people. Don’t be shy introduce yourself!
                            </p>

                            <ul class="social-icon social-default justify-content-start">
                                <li><a href="https://www.facebook.com/">
                                        <i class="feather-facebook"></i>
                                    </a>
                                </li>
                                <li><a href="https://www.twitter.com">
                                        <i class="feather-twitter"></i>
                                    </a>
                                </li>
                                <li><a href="https://www.instagram.com/">
                                        <i class="feather-instagram"></i>
                                    </a>
                                </li>
                                <li><a href="https://www.linkdin.com/">
                                        <i class="feather-linkedin"></i>
                                    </a>
                                </li>
                            </ul>

                            <div class="contact-btn mt--30">
                                <a class="rbt-btn hover-icon-reverse btn-border-gradient radius-round" href="#">
                                    <div class="icon-reverse-wrapper">
                                        <span class="btn-text">Contact With Us</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget">
                            <h5 class="ft-title">Useful Links</h5>
                            <ul class="ft-link">
                                <li>
                                    <a href="12-marketplace.html">Marketplace</a>
                                </li>
                                <li>
                                    <a href="04-kindergarten.html">kindergarten</a>
                                </li>
                                <li>
                                    <a href="13-university-classic.html">University</a>
                                </li>
                                <li>
                                    <a href="09-gym-coaching.html">GYM Coaching</a>
                                </li>
                                <li>
                                    <a href="faqs.html">FAQ</a>
                                </li>
                                <li>
                                    <a href="about-us-01.html">About Us</a>
                                </li>
                                <li>
                                    <a href="privacy-policy.html">Privacy policy</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget">
                            <h5 class="ft-title">Our Company</h5>
                            <ul class="ft-link">
                                <li>
                                    <a href="contact.html">Contact Us</a>
                                </li>
                                <li>
                                    <a href="become-a-teacher.html">Become Teacher</a>
                                </li>
                                <li>
                                    <a href="blog.html">Blog</a>
                                </li>
                                <li>
                                    <a href="instructor.html">Instructor</a>
                                </li>
                                <li>
                                    <a href="event-list.html">Events</a>
                                </li>
                                <li>
                                    <a href="course-filter-one-toggle.html">Course</a>
                                </li>
                                <li>
                                    <a href="contact.html">Contact</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget">
                            <h5 class="ft-title">Get Contact</h5>
                            <ul class="ft-link">
                                <li><span>Phone:</span> <a href="#">(406) 555-0120</a></li>
                                <li><span>E-mail:</span> <a href="mailto:hr@example.com">admin@example.com</a></li>
                            </ul>

                            <form class="newsletter-form mt--20" action="#">
                                <h6 class="w-600">Newsletter</h6>
                                <p class="description">2000+ Our students are subscribe Around the World.<br> Don’t be shy
                                    introduce yourself!</p>

                                <div class="form-group right-icon icon-email mb--20">
                                    <label for="email">Enter Your Email Here</label>
                                    <input id="email" type="email">
                                </div>

                                <div class="form-group mb--0">
                                    <button class="rbt-btn rbt-switch-btn btn-gradient radius-round btn-sm"
                                        type="submit">
                                        <span data-text="Submit Now">Submit Now</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="rbt-separator-mid">
            <div class="container">
                <hr class="rbt-separator m-0">
            </div>
        </div>
        <!-- Start Copyright Area  -->
        <div class="copyright-area copyright-style-1 ptb--20">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-12">
                        <p class="rbt-link-hover text-lg-start text-center">Copyright © 2024 <a
                                href="https://rainbowthemes.net">Rainbow-Themes.</a> All Rights Reserved</p>
                    </div>
                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-12">
                        <ul
                            class="copyright-link rbt-link-hover justify-content-center justify-content-lg-end mt_sm--10 mt_md--10">
                            <li><a href="#">Terms of service</a></li>
                            <li><a href="privacy-policy.html">Privacy policy</a></li>
                            <li><a href="subscription.html">Subscription</a></li>
                            <li><a href="login.html">Login & Register</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Copyright Area  -->
    </footer>
    <!-- End Footer aera -->
@endsection
