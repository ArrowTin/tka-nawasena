@extends('layouts.app')

@section('wrapper')
    <main class="rbt-main-wrapper">
        @yield('content')

        <div class="rbt-newsletter-area newsletter-style-2 bg-color-primary rbt-section-gap">
            <div class="container">
                <div class="row row--15 align-items-center">
                    <div class="col-lg-12">
                        <div class="inner text-center">
                            <div class="section-title text-center">
                                <span class="subtitle bg-white-opacity">Get Latest Histudy Update</span>
                                <h2 class="title color-white"><strong>Subscribe</strong> Our Newsletter</h2>
                                <p class="description color-white mt--20">Lorem ipsum, dolor sit amet consectetur adipisicing
                                    elit. Ipsam
                                    explicabo sit est eos earum reprehenderit inventore nam autem corrupti rerum!</p>
                            </div>
                            <form action="#" class="newsletter-form-1 mt--40">
                                <input type="email" placeholder="Enter Your E-Email">
                                <button type="submit" class="rbt-btn btn-gradient hover-icon-reverse btn-md">
                                    <span class="icon-reverse-wrapper">
                                        <span class="btn-text">Subscribe</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </span>
                                </button>
                            </form>
                            <span class="note-text color-white mt--20">No ads, No trails, No commitments</span>

                            <div class="row row--15 mt--50">
                                <!-- Start Single Counter -->
                                <div class="col-lg-3 offset-lg-3 col-md-6 col-sm-6 single-counter">
                                    <div class="rbt-counterup rbt-hover-03 style-2 text-color-white">
                                        <div class="inner">
                                            <div class="content">
                                                <h3 class="counter color-white"><span class="odometer"
                                                        data-count="500">00</span>
                                                </h3>
                                                <h5 class="title color-white">Successfully Trained</h5>
                                                <span class="subtitle color-white">Learners &amp; counting</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Counter -->

                                <!-- Start Single Counter -->
                                <div class="col-lg-3 col-md-6 col-sm-6 single-counter mt_mobile--30">
                                    <div class="rbt-counterup rbt-hover-03 style-2 text-color-white">
                                        <div class="inner">
                                            <div class="content">
                                                <h3 class="counter color-white"><span class="odometer"
                                                        data-count="100">00</span>
                                                </h3>
                                                <h5 class="title color-white">Certification Students</h5>
                                                <span class="subtitle color-white">Online Course</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Counter -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start Footer aera -->
        <footer class="rbt-footer footer-style-1">
            <div class="footer-top">
                <div class="container">
                    <div class="row row--15 mt_dec--30">
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12 mt--30">
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

                        <div class="offset-lg-1 col-lg-2 col-md-6 col-sm-6 col-12 mt--30">
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
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 col-sm-6 col-12 mt--30">
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
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mt--30">
                            <div class="footer-widget">
                                <h5 class="ft-title">Get Contact</h5>
                                <ul class="ft-link">
                                    <li><span>Phone:</span> <a href="#">(406) 555-0120</a></li>
                                    <li><span>E-mail:</span> <a href="mailto:hr@example.com">rainbow@example.com</a></li>
                                    <li><span>Location:</span> North America, USA</li>
                                </ul>
                                <ul class="social-icon social-default icon-naked justify-content-start mt--20">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer aera -->
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

    </main>
@endsection
