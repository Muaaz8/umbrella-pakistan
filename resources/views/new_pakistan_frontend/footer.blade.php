<footer>
    <section id="footer-section">
        <div id="footer-1" class="footer">
            <div class="logo" id="footer-logo">
                <img src="{{ asset('assets/new_frontend/logo.png') }}" alt="umbrella-logo" />
            </div>
            <div class="flex gap-15" id="social-icons">
                <div><img src="{{ asset('assets/new_frontend/facebook-icon.svg') }}" alt="" /></div>
                <div><img src="{{ asset('assets/new_frontend/twitter-icon.svg') }}" alt="" /></div>
                <div><img src="{{ asset('assets/new_frontend/linkedin-icon.svg') }}" alt="" /></div>
                <div><img src="{{ asset('assets/new_frontend/instagram-icon.svg') }}" alt="" /></div>
                <div><img src="{{ asset('assets/new_frontend/pinterest-icon.svg') }}" alt="" /></div>
            </div>
        </div>
        <div id="footer-2" class="footer">
            <div class="footer-heading">
                <h3>Contact Us</h3>
                <div class="underline"></div>
            </div>
            <div class="footer-content">
                <div class="footer-highlight">
                    <img src="{{ asset('assets/new_frontend/location-icon.svg') }}" alt="" />
                    <a href="#" data-bs-toggle="modal" data-bs-target="#locationModal">Find Location</a>
                </div>
                <p>contact@umbrellaamd.com</p>
                <p>support@umbrellaamd.com</p>
                <p>+1 (407) 693-8484</p>
            </div>
        </div>
        <div id="footer-3" class="footer">
            <div class="footer-heading">
                <h3>Working Hours</h3>
                <div class="underline"></div>
            </div>
            <div class="footer-content">
                <p>07:00 am - 08:00 pm</p>
                <p>Umbrella Health Care Systems</p>
                <div class="footer-info">
                    <img src="{{ asset('assets/new_frontend/right-arrow-icon.svg') }}" alt="" />
                    <a href="{{ route('about_us') }}">About Us</a>
                </div>
                <div class="footer-info">
                    <img src="{{ asset('assets/new_frontend/right-arrow-icon.svg') }}" alt="" />
                    <a href="{{ route('contact_us') }}">Contact Us</a>
                </div>
                <div class="footer-info">
                    <img src="{{ asset('assets/new_frontend/right-arrow-icon.svg') }}" alt="" />
                    <a href="{{ route('faq') }}">FAQs</a>
                </div>
                <div class="footer-info">
                    <img src="{{ asset('assets/new_frontend/right-arrow-icon.svg') }}" alt="" />
                    <a href="{{ route('privacy_policy') }}">Privacy Policy</a>
                </div>
            </div>
        </div>
        <div id="footer-4" class="footer">
            <div class="footer-heading">
                <h3>Emergency Contact</h3>
                <div class="underline"></div>
            </div>
            <div class="footer-content">
                <div class="footer-highlight">
                    <img src="{{ asset('assets/new_frontend/phone-icon.svg') }}" alt="" />
                    <a href="">+1 (407) 693-8484</a>
                </div>
                <p>In Emergency, Please call 911</p>
            </div>
        </div>
    </section>
    <div class="seperation"></div>
    <section id="copyright">
        <p>
            Copyright &copy; {{ date('Y') }}.
            <span>Umbrella Health Care Systems. All Rights Reserved</span>
        </p>
    </section>
</footer>
<div class="modal fade" id="callingNewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <input type="hidden" id="session_user_id" />
        <div class="modal-content calling-modal">
            <div class="">
                <div id="phone">
                    <div class="main">
                        <div class="header-background"></div>
                        <div class="avatar-wrapper">
                            <img id="img" src="" alt="user-image" />
                        </div>
                        <div class="snippet" data-title=".dot-pulse">
                            <div class="stage">
                                <div class="dot-pulse"></div>
                            </div>
                        </div>
                        <h2 class="incoming">Join Session</h2>
                        <h6 class="with--">With Doctor</h6>
                        <h1 class="name">Anas Murtaza</h1>
                    </div>
                    <div class="footer d-flex justify-content-evenly flex-row-reverse">
                        <div class="decline">
                            <span id="callCounter" class="fs-5"></span>
                        </div>
                        <a href="{{ url('/pat/video/page/0') }}" id="patientVideoCallingAcceptBtn">
                            <div class="accept">
                                <span id="callCounter"></span><i class="fas fa-phone"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
