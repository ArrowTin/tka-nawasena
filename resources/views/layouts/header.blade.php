<div class="rbt-sticky-placeholder"></div>

<!-- Start Header Top  -->
<div
    class="rbt-header-top rbt-header-top-1 header-space-betwween bg-not-transparent bg-color-darker top-expended-activation">
    <div class="container-fluid"></div>
</div>
<!-- End Header Top  -->
<div class="rbt-header-wrapper header-space-betwween header-sticky">
    <div class="container-fluid">
        <div class="mainbar-row rbt-navigation-center align-items-center">
            <div class="header-left rbt-header-content">
                <div class="header-info">
                    <div class="logo logo-dark">
                        <a href="#">
                            <img src="{{ asset('assets/images/logo-nawapp-academy.png') }}" alt="Nawasena Academy Logo">
                        </a>
                    </div>

                    <div class="logo d-none logo-light">
                        <a href="#">
                            <img src="{{ asset('assets/images/logo-nawapp-academy.png') }}" alt="Nawasena Academy Logo">
                        </a>
                    </div>
                </div>
                <div class="header-info">
                    <div class="rbt-category-menu-wrapper rbt-category-update">


                    </div>
                </div>
            </div>

            {{-- <div class="rbt-main-navigation d-none d-xl-block">
                <nav class="mainmenu-nav">
                    <ul class="mainmenu">
                        <li class="position-static">
                            <a href="#rbt-categories-area">Pelayanan Kami</a>
                        </li>

                        <li class="position-static">
                            <a href="#rbt-course-area">Paket Kami</a>
                        </li>

                        <li class="position-static">
                            <a href="#rbt-about-area">Tentang Kami</a>
                        </li>

                        <li class="position-static">
                            <a href="#rbt-testimonial-area">Testimoni</a>
                        </li>

                        <li class="position-static">
                            <a href="#rbt-event-area">Event</a>
                        </li>

                        <li class="position-static">
                            <a href="#rbt-article-area">Artikel</a>
                        </li>

                    </ul>
                </nav>
            </div> --}}

            <div class="header-right">

                <!-- Navbar Icons -->
                <ul class="quick-access">
                    <li class="access-icon">
                        <div class="rbt-btn-wrapper d-none d-xl-block">
                            <a class="rbt-btn hover-icon-reverse btn-border-gradient radius-round btn-sm"
                                href="#">
                                <div class="icon-reverse-wrapper">
                                    <span class="btn-text">Dashboard</span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                </div>
                            </a>
                        </div>
                    </li>

                    @if (Auth::check())
                        <li class="account-access rbt-user-wrapper d-none d-xl-block">
                            <a href="#"><i class="feather-user"></i>{{ Auth::user()->name }}</a>
                            <div class="rbt-user-menu-list-wrapper">
                                <div class="inner">
                                    <div class="rbt-admin-profile">
                                        <div class="admin-thumbnail">
                                            <img src="{{ asset('assets/images/team/avatar.jpg') }}" alt="User Images">
                                        </div>
                                        <div class="admin-info">
                                            <span class="name">{{ Auth::user()->name }}</span>
                                            <a class="rbt-btn-link color-primary" href="#">View
                                                Profile</a>
                                        </div>
                                    </div>
                                    <hr class="mb--10 mt--10">
                                    <ul class="user-list-wrapper">
                                        <li>
                                            <a class="rbt-btn-link color-primary" href="#">Order
                                                History</a>
                                        </li>
                                        <li>
                                            <form id="logout-form" action="#" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                            <a href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="feather-log-out"></i>
                                                <span>Logout</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>

                <!-- Start Mobile-Menu-Bar -->
                <div class="mobile-menu-bar d-block d-xl-none">
                    <div class="hamberger">
                        <button class="hamberger-button rbt-round-btn">
                            <i class="feather-menu"></i>
                        </button>
                    </div>
                </div>
                <!-- Start Mobile-Menu-Bar -->

            </div>
        </div>
    </div>
</div>
<a class="rbt-close_side_menu" href="javascript:void(0);"></a>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scroll behavior to all anchor links in navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                // Only handle hash links, not just "#"
                if (href !== '#' && href.length > 1) {
                    e.preventDefault();

                    const targetId = href.substring(1);
                    const targetElement = document.getElementById(targetId);

                    if (targetElement) {
                        // Calculate offset for sticky header (adjust this value as needed)
                        const headerOffset = 100;
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset -
                            headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    });
</script>

<style>
    /* Add smooth scroll behavior for browsers that support it */
    html {
        scroll-behavior: smooth;
    }
</style>
