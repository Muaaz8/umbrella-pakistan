<div id="noteGet"></div>
<header id="header" class="header">


    <!-- HEADER STRIP -->
    <div class="headtoppart bg-blue clearfix" style="background-color:#08295a !important">
        <div class="headerwp clearfix">

            <!-- Address -->
            <div class="headertopleft">
                <div class="address clearfix"><span><i class="fas fa-map-marker-alt"></i>625 School House Road #2, Lakeland, FL 33813</span> <a href="tel:123456789" class="callusbtn"><i
                            class="fas fa-phone"></i>+1 (407) 693-8484</a>
                </div>
            </div>

            <!-- Social Links -->
            <div class="headertopright">
                <a class="googleicon" title="Google" href="#"><i class="fab fa-google"></i> <span
                        class="mobiletext02">Google</span></a>
                <a class="linkedinicon" title="Linkedin" href="#"><i class="fab fa-linkedin-in"></i> <span
                        class="mobiletext02">Linkedin</span></a>
                <a class="twittericon" title="Twitter" href="#"><i class="fab fa-twitter"></i> <span
                        class="mobiletext02">Twitter</span></a>
                <a class="facebookicon" title="Facebook" href="#"><i class="fab fa-facebook-f"></i> <span
                        class="mobiletext02">Facebook</span></a>
            </div>

        </div>
    </div> <!-- END HEADER STRIP -->

</header>

<div id="myHeader" style="background-color:#fff !important; ">

    <div class="header_wrapper container-fluid">

        {{-- Top Header --}}
        <div class="header-top-bar">

            {{-- Logo --}}
            <div class="myLogo">
                <a class="site-logo" href="/"><img src="{{ asset('asset_frontend/images/logo.png') }}"
                        alt='Logo' /></a>
            </div>



            {{-- Search Bar --}}
            <div class="custom-search-bar">
                <form class="live-search-form" action>
                    <div class="ui action input search-label">
                        <input type="text" placeholder="What are you Looking for..." id="live-search-input">
                        <!-- <div class="ui active inline loader" style=" position: absolute; left: 92%; top: 15%; "></div> -->
                        <button type="button" class="ui icon button theme-color">
                            <i class="search icon white-color"></i>
                            </a>
                    </div>

                    <div class="search-flydown">
                        <div class="search-flydown--results visible">
                            <div class="search-flydown--product-items" style="max-height: 385px; overflow-y: scroll;">
                            </div>

                            <div style="display:none;" class="search-flydown--footer">
                                <a class="search-flydown--continue" href="">
                                    View all results
                                </a>
                            </div>
                        </div>
                    </div>
                    <div id="customNav">
                        <div class="navigation_wrapper">
                            <nav class="wsmenu clearfix row">

                                <ul class="wsmenu-list" style="padding:0px">
                                    <li class="nl-simple" aria-haspopup="true"><a
                                            href="{{ url('/e-visit') }}">E-Visit</a></li>
                                    <!-- DROPDOWN MENU -->
                                    <!-- <li class="nl-simple" aria-haspopup="true"><a href="#">Doctors</a></li> -->
                                    <li aria-haspopup="true"><a href="{{ url('/our_doctors') }}">Doctors</a></li>
                                    <li aria-haspopup="true"><span class="wsmenu-click"><i
                                                class="wsmenu-arrow"></i></span><a href="/pharmacy">Pharmacy </a>
                                        {{-- <ul class="sub-menu">
                                            <li aria-haspopup="true"><a href="/pharmacy">Pain & Fever</a></li>
                                            <li aria-haspopup="true"><a href="/pharmacy">Allergy & Asthama</a></li>
                                            <li aria-haspopup="true"><a href="/pharmacy">Digestive Health</a></li>
                                            <li aria-haspopup="true"><a href="/pharmacy">Cough, Cold & Flu</a></li>
                                            <li aria-haspopup="true"><a href="/pharmacy">Sleeping & Snoring</a></li>
                                            <li aria-haspopup="true"><a href="/pharmacy">Braces & Support</a></li>
                                            <li aria-haspopup="true"><a href="/pharmacy">View All</a></li>
                                        </ul> --}}
                                    </li> <!-- END DROPDOWN MENU -->

                                    <!-- SIMPLE NAVIGATION LINK -->
                                    <li class="nl-simple" aria-haspopup="true"><a href="/labs">Lab Tests</a></li>
                                    <li class="nl-simple" aria-haspopup="true"><a href="/imaging">Imaging</a></li>
                                    <li class="nl-simple" aria-haspopup="true"><a
                                            href="/coming_soon">Cardiology</a>
                                        {{-- {{ url('/substance_abuse/self-pay-fees') }} --}}
                                    </li>
                                    <li class="nl-simple" aria-haspopup="true"><a
                                            href="/coming_soon">Dermatology</a>
                                    </li>
                                    <!-- <li aria-haspopup="true"><span class="wsmenu-click"><i class="wsmenu-arrow"></i></span><a href="#">Physical Therapy <span class="wsarrow"></span></a>
                            <ul class="sub-menu">
                               <li aria-haspopup="true"><a href="/pharmacy">Pediatric Physical Therapy</a></li>
                               <li aria-haspopup="true"><a href="/pharmacy">Geriatric Physical Therapy</a></li>
                               <li aria-haspopup="true"><a href="/pharmacy">Orthopedic Physical Therapy</a></li>
                               <li aria-haspopup="true"><a href="/pharmacy">Vestibular Rehabilitation</a></li>
                               <li aria-haspopup="true"><a href="/pharmacy">Neurological Physical Therapy</a></li>
                               <li aria-haspopup="true"><a href="/pharmacy">View All</a></li>
                            </ul>
                       </li>	 -->

                                    <!-- DROPDOWN MENU -->
                                    <!-- <li aria-haspopup="true"><span class="wsmenu-click"><i class="wsmenu-arrow"></i></span><a
                                href="#">Home Health Care <span class="wsarrow"></span></a>
                            <ul class="sub-menu">
                                <li aria-haspopup="true"><a href="/pharmacy">Services #1</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Services #2</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Services #3</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Services #4</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Services #5</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Services #6</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">View All</a></li>
                            </ul>
                        </li> -->
                                    <!-- END DROPDOWN MENU -->

                                    <!-- DROPDOWN MENU
                        <li aria-haspopup="true"><span class="wsmenu-click"><i class="wsmenu-arrow"></i></span><a
                                href="#">Health & Living <span class="wsarrow"></span></a>
                            <ul class="sub-menu">
                                <li aria-haspopup="true"><a href="/pharmacy"> Diabetes Management</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">First Aid</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Vitamins</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Weighing Scales</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Warmers</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Skin, Nail & Hair Care</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">View All</a></li>
                            </ul>
                        </li> END DROPDOWN MENU
                         DROPDOWN MENU
                        <li aria-haspopup="true"><span class="wsmenu-click">
                            <i class="wsmenu-arrow"></i></span>
                            <a href="#">Personal Care <span class="wsarrow"></span></a>
                            <ul class="sub-menu">
                                <li aria-haspopup="true"><a href="/pharmacy">Cosmetics</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Hair Care</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Skin Care</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Vitamins</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Hygene Products</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">Womens/Mens Care</a></li>
                                <li aria-haspopup="true"><a href="/pharmacy">View All</a></li>
                            </ul>
                        </li> END DROPDOWN MENU -->




                                    <!-- NAVIGATION MENU BUTTON -->
                                    {{-- <li class="nl-simple header-btn" aria-haspopup="true"><a href="appointment.html">Make an Appointment</a></li> --}}


                                </ul>

                                <ul class="wsmenu-list ">

                                    @if (Route::has('login'))
                                        @auth
                                            @if (!isset($med_prof))
                                                <li class="site-header-account-link"><a href="{{ url('/home') }}"><i
                                                            class="fa fa-user"></i>
                                                        @if (Auth::user()->user_type == 'doctor')
                                                            Dr. {{ Auth::user()->name }}
                                                        @else
                                                            {{ Auth::user()->name }}
                                                        @endif
                                                        <span class="wsarrow ml-2"></span>
                                                    </a>
                                            @endif
                                            <ul class="sub-menu">
                                                <li aria-haspopup="true">
                                                    <a data-placement="bottom" title="Logout" style="font-size:16px"
                                                        href="{{ route('logout') }}"
                                                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                        <i style="font-size:16px" class="fas fa-sign-out-alt"></i>Logout
                                                    </a>
                                                </li>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display: none;">@csrf
                                                </form>
                                                {{-- <li class="nl-simple add_to_cart_icon" aria-haspopup="true">
                                    <a href="/cart"><span><img src="{{ asset('asset_frontend/images/4.gif') }}"
                            style="height:40px;margin-bottom:5px"/></span></a> --}}


                                            </ul>
                                            </li>
                                        @else
                                            <li class="nl-simple login" aria-haspopup="true" style="float:right;">
                                                <a href="{{ route('login') }}">
                                                    Login
                                                </a>
                                                <!-- <a href="{{ url('register?type=doctor') }}"> Register as Doctor </a>/ -->
                                            </li>
                                            @if (Route::has('register'))
                                                <li aria-haspopup="true" style="float:right;"><span
                                                        class="wsmenu-click"><i class="wsmenu-arrow"></i></span><a
                                                        href="#">Register <span class="wsarrow"></span></a>
                                                    <ul class="sub-menu subMenuRegister">
                                                        <li aria-haspopup="true"><a
                                                                href="{{ url('doctor_register') }}">Register as
                                                                Doctor</a></li>
                                                        <li aria-haspopup="true"><a
                                                                href="{{ url('patient_register') }}">Register as
                                                                Patient</a></li>
                                                    </ul>
                                                </li>
                                            @endif
                                        @endauth

                                        </li>
                                    @endif
                                </ul>

                            </nav>



                            <nav class="navbar  navbar-light d-block d-sm-block d-lg-none d-md-block">

                                <div class="collapse bg-light navbar-collapse">
                                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                                        <li class="nav-item active"><a class="nav-link" href="#">Home <span
                                                    class="sr-only">(current)</span></a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ url('/e-visit') }}">E-Visit</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ url('register?type=doctor') }}">Doctors</a></li>
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                                role="button" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">Pharmacy </a>
                                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="/pharmacy">Pain & Fever</a>
                                                <a class="dropdown-item" href="/pharmacy">Allergy & Asthama</a>
                                                <a class="dropdown-item" href="/pharmacy">Digestive Health</a>
                                                <a class="dropdown-item" href="/pharmacy">Cough, Cold & Flu</a>
                                                <a class="dropdown-item" href="/pharmacy">Sleeping & Snoring</a>
                                                <a class="dropdown-item" href="/pharmacy">Braces & Support</a>
                                                <a class="dropdown-item" href="/pharmacy">View All</a>
                                            </div>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="/labs">Lab
                                                Tests</a></li>
                                        <li class="nav-item"><a class="nav-link" href="/imaging">Imaging</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ url('/substance_abuse/self-pay-fees') }}">Substance
                                                Abuse</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="/coming_soon">Dermatology</a>
                                        </li>


                                        @if (Route::has('login'))
                                            @auth
                                                @if (!isset($med_prof))
                                                    <li class="nav-item"><a class="nav-link"
                                                            href="{{ url('/home') }}">{{ Auth::user()->name }}</a>
                                                    </li>
                                                    {{-- <li class="">
                                                        <a class="px-0" data-placement="bottom" title="Logout" href="{{ route('logout') }}"
                                                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                            <i style="font-size:20px" class="zmdi zmdi-sign-in icon"></i>
                                                            Logout
                                                        </a>
                                                    </li> --}}

                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                        style="display: none;">@csrf
                                                    </form>
                                                @endif
                                            @else
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ route('login') }}">Login</a></li>
                                                @if (Route::has('register'))
                                                    <li class="nav-item dropdown">
                                                        <a class="nav-link dropdown-toggle nav-link" href="#"
                                                            id="navbarDropdown" role="button" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">Register</a>
                                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                            <a class="dropdown-item"
                                                                href="{{ url('register?type=doctor') }}">Register as
                                                                Doctor</a>
                                                            <a class="dropdown-item"
                                                                href="{{ url('register?type=patient') }}">Register as
                                                                Patient</a>
                                                        </div>
                                                    </li>
                                                @endif
                                            @endauth
                                            </li>
                                        @endif
                                    </ul>

                                </div>


                            </nav>
                        </div>
                    </div>

                </form>

            </div>

            {{-- Cart Icon --}}

            <div class="top-cart">

                <a href="/cart"><i class="shopping cart icon ml-3"
                        style="color:#ecdf41;font-size:30px;width: 2em; padding: 0 15px 0 0; margin:0px;"></i>
                    <p class="font-weight-bold ml-4"
                        style="font-size:12px;  margin-left: 0.5rem!important;text-align: center;font-size: 12px;">
                        <span class="cart-counter-badge cartCounterShow">0</span> Items
                    </p>

                </a>
            </div>
            <button class="navbar-toggler text-white d-block d-sm-block d-lg-none d-md-block"
                style="background-color: #08295a;padding: 11px 15px 11px 15px;" type="button" data-toggle="collapse"
                data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false"
                aria-label="Toggle navigation" id="mobileMenuBtn">
                <span class="fa fa-bars"></span>
            </button>

        </div>
        <nav class="navbar  navbar-light d-block d-sm-block d-lg-none d-md-block">
            <div class="collapse bg-light navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active"><a class="nav-link" href="#">Home <span
                                class="sr-only">(current)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/e-visit') }}">E-Visit</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="{{ url('register?type=doctor') }}">Doctors</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pharmacy </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/pharmacy">Pain & Fever</a>
                            <a class="dropdown-item" href="/pharmacy">Allergy & Asthama</a>
                            <a class="dropdown-item" href="/pharmacy">Digestive Health</a>
                            <a class="dropdown-item" href="/pharmacy">Cough, Cold & Flu</a>
                            <a class="dropdown-item" href="/pharmacy">Sleeping & Snoring</a>
                            <a class="dropdown-item" href="/pharmacy">Braces & Support</a>
                            <a class="dropdown-item" href="/pharmacy">View All</a>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/labs">Lab Tests</a></li>
                    <li class="nav-item"><a class="nav-link" href="/imaging">Imaging</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Cardiology</a></li>
                    <li class="nav-item"><a class="nav-link" href="/coming_soon">Dermatology</a></li>


                    @if (Route::has('login'))
                        @auth
                            @if (!isset($med_prof))
                                <li class="nav-item"><a class="nav-link"
                                        href="{{ url('/home') }}">{{ Auth::user()->name }}</a></li>
                            @endif
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle nav-link" href="#" id="navbarDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Register</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ url('register?type=doctor') }}">Register as
                                            Doctor</a>
                                        <a class="dropdown-item" href="{{ url('register?type=patient') }}">Register as
                                            Patient</a>
                                    </div>
                                </li>
                            @endif
                        @endauth
                        </li>
                    @endif
                </ul>

            </div>
        </nav>


        <style>
            .wsmenu>.wsmenu-list>li>ul.sub-menu {
                top: 35px !important;
            }

            .top-cart i,
            .top-cart p {
                padding-top: 8px !important;
                padding-left: 8px !important;
            }

            @media only screen and (min-device-width:768px) and (max-device-width:1024px) and (orientation:portrait) {
                .myLogo .site-logo img {
                    width: 90px !important;
                    height: 90px !important;
                }

            }

            @media only screen and (device-width : 375px) and (device-height : 812px) and (-webkit-device-pixel-ratio : 3) {

                .top-cart i,
                .top-cart p {

                    margin-left: 10px !important;
                }
            }

            @media only screen and (max-width:767px) {
                .myLogo .site-logo img {
                    width: 70px !important;
                    height: 70px !important;
                }

                .top-cart i,
                .top-cart p {
                    padding-top: 8px !important;
                    padding-left: 8px !important;
                    margin-left: 10px !important;
                }
            }

        </style>

    </div>



</div>
