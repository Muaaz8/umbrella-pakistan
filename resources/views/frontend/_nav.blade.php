<div id="noteGet"></div>
<header id="header" class="header">


    <!-- MOBILE HEADER -->
    <div class="wsmobileheader clearfix">
        <a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>
        <span class="smllogo"><img src="images/logo.png" width="180" height="40" alt="mobile-logo"></span>
        <a href="tel:+1(407)693-8484" class="callusbtn"><i class="fas fa-phone"></i></a>
    </div>


    <!-- HEADER STRIP -->
    <div class="headtoppart bg-blue clearfix">
        <div class="headerwp clearfix">

            <!-- Address -->
            <div class="headertopleft">
                <div class="address clearfix"><span><i class="fas fa-map-marker-alt"></i>Progressive Center, 4th Floor Suite#410, Main Shahrah Faisal, Karachi </span><a href="tel:+1(407)693-8484" class="callusbtn">Call now <i
                            class="fas fa-phone"></i> +1 (407) 693-8484</a>
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


    <!-- NAVIGATION MENU -->
    <div class="wsmainfull menu clearfix original" style="visibility: visible;">
        <div class="wsmainwp clearfix">

            <!-- LOGO IMAGE -->
            <!-- For Retina Ready displays take a image with double the amount of pixels that your image will be displayed (e.g 360 x 80 pixels) -->
            <div class="desktoplogo"><a href="/"><img src="{{ asset('asset_frontend/images/logo.png') }}"
                        width="100" height="110" alt="header-logo" class="mt-2"></a></div>

            <!-- MAIN MENU -->
            <nav class="wsmenu clearfix">
                <div class="overlapblackbg"></div>
                <ul class="wsmenu-list">


                    <li class="nl-simple" aria-haspopup="true"><a href="/">Home</a></li>
                    <li class="nl-simple" aria-haspopup="true"><a href="{{route('about_us')}}">About</a></li>
                    <!-- DROPDOWN MENU -->
                    <li aria-haspopup="true"><span class="wsmenu-click"><i class="wsmenu-arrow"></i></span><a
                            href="#">Services <span class="wsarrow"></span></a>
                        <ul class="sub-menu">
                            <li class="nl-simple" aria-haspopup="true"><a href="/pharmacy">Pharmacy</a></li>
                            <li class="nl-simple" aria-haspopup="true"><a href="/labtests">Labtests</a></li>
                            <li class="nl-simple" aria-haspopup="true"><a href="{{ route('imaging') }}">Medical Imaging</a></li>
                            <li class="nl-simple" aria-haspopup="true"><a href="/coming_soon">Pain Management</a></li>
                            <li class="nl-simple" aria-haspopup="true"><a href="/substance-abuse/adolescent">Substance Abuse</a></li>
                            <!-- <li class="nl-simple" aria-haspopup="true"><a href="/coming_soon">Cardiology</a></li>
                            <li class="nl-simple" aria-haspopup="true"><a href="/coming_soon">Dermatology</a></li> -->
                        </ul>
                    </li> <!-- END DROPDOWN MENU -->
                    <li class="nl-simple" aria-haspopup="true"><a href="{{ url('/e-visit') }}">E-Visit</a></li>
                    <li class="nl-simple" aria-haspopup="true"><a href="{{ url('/our_doctors') }}">Doctors</a>
                    </li>
                    <li class="nl-simple" aria-haspopup="true"><a href="{{ route('contact_us') }}">Contact</a></li>
                    @if (!Auth::check())
                        <li aria-haspopup="true"><span class="wsmenu-click"><i class="wsmenu-arrow"></i></span><a
                                href="#">Join Us <span class="wsarrow"></span></a>
                            <ul class="sub-menu">
                                <li class="nl-simple" aria-haspopup="true"><a
                                        href="{{ route('login') }}">Login</a></li>
                                <li class="nl-simple" aria-haspopup="true"><a
                                        href="{{ url('doctor_register') }}">Register as Doctor</a></li>
                                <li class="nl-simple" aria-haspopup="true"><a
                                        href="{{ url('patient_register') }}">Register as Patient</a></li>
                            </ul>
                        </li>
                    @else
                        <li aria-haspopup="true"><span class="wsmenu-click"><i class="wsmenu-arrow"></i></span><a
                                href="#">Hi {{ Auth::user()->name }} <span class="wsarrow"></span></a>
                            <ul class="sub-menu">
                                <li class="nl-simple header-btn" aria-haspopup="true"><a
                                        href="{{ route('home') }}">Dashboard</a></li>
                                <li class="nl-simple" aria-haspopup="true">
                                    <a data-placement="bottom" title="Logout" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif

                    <li class="nl-simple cart-li" aria-haspopup="true">
                        <a href="/cart" class="menu-last-li"><i class="shopping cart icon ml-3"></i>
                            <span class="cartCounterShow">0</span> items</a>
                    </li>
                    {{--  <li class="nl-simple" aria-haspopup="true"><a href="{{ url('/pricing') }}"><button class="btn" style="background-color: #e4e404; color: black;">Become a Member</button></a></li>  --}}
                </ul>
            </nav> <!-- END MAIN MENU -->

        </div>
    </div>


</header>
