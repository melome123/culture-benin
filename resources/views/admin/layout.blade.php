<!DOCTYPE html>
<html lang="zxx">
    <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Links Of CSS File -->
		<link rel="stylesheet" href="{{URL::asset("assets/css/sidebar-menu.css")}}">
		<link rel="stylesheet" href="{{URL::asset("assets/css/simplebar.css")}}">
		<link rel="stylesheet" href="{{URL::asset("assets/css/apexcharts.css")}}">
		<link rel="stylesheet" href="{{URL::asset("assets/css/prism.css")}}">
		<link rel="stylesheet" href="{{URL::asset("assets/css/rangeslider.css")}}">
		<link rel="stylesheet" href="{{URL::asset("assets/css/sweetalert.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("assets/css/quill.snow.css")}}">
        <link rel="stylesheet" href="{{URL::asset("assets/css/google-icon.css")}}">
        <link rel="stylesheet" href="{{URL::asset("assets/css/remixicon.css")}}">
        <link rel="stylesheet" href="{{URL::asset("assets/css/swiper-bundle.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("assets/css/fullcalendar.main.css")}}">
        <link rel="stylesheet" href="{{URL::asset("assets/css/jsvectormap.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("assets/css/lightpick.css")}}">
		<link rel="stylesheet" href="{{URL::asset("assets/css/style.css")}}">
		
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="assets/images/favicon.png">
		<!-- Title -->
		<title>Trezo - Bootstrap 5 Admin Dashboard Template</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="boxed-size">
        <!-- Start Preloader Area -->
        <div class="preloader" id="preloader">
            <div class="preloader">
                <div class="waviy position-relative">
                    <span class="d-inline-block">B</span>
                    <span class="d-inline-block">E</span>
                    <span class="d-inline-block">N</span>
                    <span class="d-inline-block">I</span>
                    <span class="d-inline-block">N</span>
                </div>
            </div>
        </div>
        <!-- End Preloader Area -->

        <!-- Start Sidebar Area -->
        <div class="sidebar-area" id="sidebar-area">
            <div class="logo position-relative">
                <a href="index.html" class="d-block text-decoration-none position-relative">
                    <img src="{{URL::asset("assets/images/logo.png" )}}" alt="logo-icon">
                </a>
                <button class="sidebar-burger-menu bg-transparent p-0 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y" id="sidebar-burger-menu">
                    <i data-feather="x"></i>
                </button>
            </div>

            <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar>
                <ul class="menu-inner">
                     @auth
                            @if(auth()->user()->role_id === 1)
                                <li class="menu-item">
                        <a href="{{URL('/admin/dashboard')}}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">home</span>
                            <span class="title">Home</span>
                        </a>
                    </li>
                     <li class="menu-title small text-uppercase">
                        <span class="menu-title-text">APPS</span>
                    </li>

                    <li class="menu-item">
                        <a href="{{URL('/admin/users/list')}}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">account_box</span>
                            <span class="title">Users</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{URL('/admin/langues/list')}}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">dictionary</span>
                            <span class="title">Langues</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{URL('/admin/regions/list')}}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">location_on</span>
                            <span class="title">Regions</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{URL('/admin/roles/list')}}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">dictionary</span>
                            <span class="title">Roles</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{URL('/admin/contenus/list')}}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">dictionary</span>
                            <span class="title">Contenus</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{URL('/admin/medias/list')}}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">dictionary</span>
                            <span class="title">Medias</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{URL('/admin/typecontenus/list')}}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">dictionary</span>
                            <span class="title">Typecontenus</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{URL('/admin/typemedias/list')}}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">dictionary</span>
                            <span class="title">Typemedia</span>
                        </a>
                    </li>
                            @endif
                            @if(auth()->user()->role_id === 2|| auth()->user()->role_id === 1)
                                 <li class="menu-item">
                        <a href="{{URL('/moderation/comments')}}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">dictionary</span>
                            <span class="title">Commentaire</span>
                        </a>
                    </li>
                     <li class="menu-item">
                        <a href="{{URL('/moderation/contenus')}}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">dictionary</span>
                            <span class="title">Contenus</span>
                        </a>
                    </li>
                            @endif
                        @endauth
                </ul>
            </aside>
        </div>
        <!-- End Sidebar Area -->

        <!-- Start Main Content Area -->
        <div class="container-fluid">
            <div class="main-content d-flex flex-column">
                <!-- Start Header Area -->
                <header class="header-area bg-white mb-4 rounded-bottom-15" id="header-area">
                    <div class="row align-items-center">
                        <div class="col-lg-4 col-sm-6">
                            <div class="left-header-content">
                                <ul class="d-flex align-items-center ps-0 mb-0 list-unstyled justify-content-center justify-content-sm-start">
                                    <li>
                                        <button class="header-burger-menu bg-transparent p-0 border-0" id="header-burger-menu">
                                            <span class="material-symbols-outlined">menu</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-8 col-sm-6">
                            <div class="right-header-content mt-2 mt-sm-0">
                                <ul class="d-flex align-items-center justify-content-center justify-content-sm-end ps-0 mb-0 list-unstyled">
                                    <li class="header-right-item">
                                        <div class="light-dark">
                                            <button class="switch-toggle settings-btn dark-btn p-0 bg-transparent" id="switch-toggle">
                                                <span class="dark"><i class="material-symbols-outlined">light_mode</i></span> 
                                                <span class="light"><i class="material-symbols-outlined">dark_mode</i></span>
                                            </button>
                                        </div>
                                    </li>
                                    <li class="header-right-item">
                                        <button class="fullscreen-btn bg-transparent p-0 border-0" id="fullscreen-button">
                                            <i class="material-symbols-outlined text-body">fullscreen</i>
                                        </button>
                                    </li>
                                    
                                    <li class="header-right-item">
                                        <div class="dropdown admin-profile">
                                            <div class="d-xxl-flex align-items-center bg-transparent border-0 text-start p-0 cursor dropdown-toggle" data-bs-toggle="dropdown">
                                                <div class="flex-shrink-0">
                                                    <i class="material-symbols-outlined">account_circle</i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-none d-xxl-block">
                                                            <div class="d-flex align-content-center">
                                                                @yield('me')
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="dropdown-menu border-0 bg-white dropdown-menu-end">
                                                <div class="d-flex align-items-center info">
                                                    <div class="flex-shrink-0">
                                                        <img class="rounded-circle wh-30 administrator" src="assets/images/administrator.jpg" alt="admin">
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <h3 class="fw-medium">Olivia John</h3>
                                                        <span class="fs-12">Marketing Manager</span>
                                                    </div>
                                                </div>
                                                <ul class="admin-link ps-0 mb-0 list-unstyled">
                                                    <li>
                                                        <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="my-profile.html">
                                                            <i class="material-symbols-outlined">account_circle</i>
                                                            <span class="ms-2">My Profile</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="chat.html">
                                                            <i class="material-symbols-outlined">chat</i>
                                                            <span class="ms-2">Messages</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="to-do-list.html">
                                                            <i class="material-symbols-outlined">format_list_bulleted </i>
                                                            <span class="ms-2">My Task</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="checkout.html">
                                                            <i class="material-symbols-outlined">credit_card </i>
                                                            <span class="ms-2">Billing</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <ul class="admin-link ps-0 mb-0 list-unstyled">
                                                    <li>
                                                        <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="settings.html">
                                                            <i class="material-symbols-outlined">settings </i>
                                                            <span class="ms-2">Settings</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="tickets.html">
                                                            <i class="material-symbols-outlined">support</i>
                                                            <span class="ms-2">Support</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="lock-screen.html">
                                                            <i class="material-symbols-outlined">lock</i>
                                                            <span class="ms-2">Lock Screen</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="login.html">
                                                            <i class="material-symbols-outlined">logout</i>
                                                            <span class="ms-2">Logout</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="header-right-item">
                                        <button class="theme-settings-btn p-0 border-0 bg-transparent" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
                                            <i class="material-symbols-outlined" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Click On Theme Settings">settings</i>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- End Header Area -->
                @yield('content')
                <div class="flex-grow-1"></div>

                <!-- Start Footer Area -->
                <footer class="footer-area bg-white text-center rounded-top-7">
                    <p class="fs-14">© Culture Bénin</p>
                </footer>
                <!-- End Footer Area -->
            </div>
        </div>
        <!-- Start Main Content Area -->

        <!-- Start Theme Setting Area -->
        <div class="offcanvas offcanvas-end bg-white" data-bs-scroll="true" data-bs-backdrop="true" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel" style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">
            <div class="offcanvas-header bg-body-bg py-3 px-4">
                <h5 class="offcanvas-title fs-18" id="offcanvasScrollingLabel">Theme Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-4">
                <div class="mb-4 pb-2">
                    <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">RTL / LTR</h4>
                    <div class="settings-btn rtl-btn">
                        <label id="switch" class="switch">
                            <input type="checkbox" onchange="toggleTheme()" id="slider">
                            <span class="sliders round"></span>
                        </label>
                    </div>
                </div>
                <div class="mb-4 pb-2">
                    <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Container Style Fluid / Boxed</h4>
                    <button class="boxed-style settings-btn fluid-boxed-btn" id="boxed-style">
                        Click To <span class="fluid">Fluid</span> <span class="boxed">Boxed</span>
                    </button>
                </div>
                <div class="mb-4 pb-2">
                    <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Only Sidebar Light / Dark</h4>
                    <button class="sidebar-light-dark settings-btn sidebar-dark-btn" id="sidebar-light-dark">
                        Click To <span class="dark1">Dark</span> <span class="light1">Light</span>
                    </button>
                </div>
                <div class="mb-4 pb-2">
                    <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Only Header Light / Dark</h4>
                    <button class="header-light-dark settings-btn header-dark-btn" id="header-light-dark">
                        Click To <span class="dark2">Dark</span> <span class="light2">Light</span>
                    </button>
                </div>
                <div class="mb-4 pb-2">
                    <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Only Footer Light / Dark</h4>
                    <button class="footer-light-dark settings-btn footer-dark-btn" id="footer-light-dark">
                        Click To <span class="dark3">Dark</span> <span class="light3">Light</span>
                    </button>
                </div>
                <div class="mb-4 pb-2">
                    <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Card Style Radius / Square</h4>
                    <button class="card-radius-square settings-btn card-style-btn" id="card-radius-square">
                        Click To <span class="square">Square</span> <span class="radius">Radius</span>
                    </button>
                </div>
                <div class="mb-4 pb-2">
                    <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Card Style BG White / Gray</h4>
                    <button class="card-bg settings-btn card-bg-style-btn" id="card-bg">
                        Click To <span class="white">White</span> <span class="gray">Gray</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- End Theme Setting Area -->
          @stack('scripts')
        <!-- Link Of JS File -->
        <script src="{{URL::asset("assets/js/bootstrap.bundle.min.js")}}"></script>
        <script src="{{URL::asset("assets/js/sidebar-menu.js")}}"></script>
        <script src="{{URL::asset("assets/js/dragdrop.js")}}"></script>
        <script src="{{URL::asset("assets/js/rangeslider.min.js")}}"></script>
        <script src="{{URL::asset("assets/js/sweetalert.js")}}"></script>
        <script src="{{URL::asset("assets/js/quill.min.js")}}"></script>
        <script src="{{URL::asset("assets/js/data-table.js")}}"></script>
        <script src="{{URL::asset("assets/js/prism.js")}}"></script>
        <script src="{{URL::asset("assets/js/clipboard.min.js")}}"></script>
        <script src="{{URL::asset("assets/js/feather.min.js")}}"></script>
        <script src="{{URL::asset("assets/js/simplebar.min.js")}}"></script>
        <script src="{{URL::asset("assets/js/apexcharts.min.js")}}"></script>
        <script src="{{URL::asset("assets/js/echarts.js")}}"></script>
        <script src="{{URL::asset("assets/js/swiper-bundle.min.js")}}"></script>
        <script src="{{URL::asset("assets/js/fullcalendar.main.js")}}"></script>
        <script src="{{URL::asset("assets/js/jsvectormap.min.js")}}"></script>
        <script src="{{URL::asset("assets/js/world-merc.js")}}"></script>
        <script src="{{URL::asset("assets/js/moment.min.js")}}"></script>
        <script src="{{URL::asset("assets/js/lightpick.js")}}"></script>
        <script src="{{URL::asset("assets/js/custom/apexcharts.js")}}"></script>
        <script src="{{URL::asset("assets/js/custom/echarts.js")}}"></script>
        <script src="{{URL::asset("assets/js/custom/custom.js")}}"></script>
    </body>
</html>