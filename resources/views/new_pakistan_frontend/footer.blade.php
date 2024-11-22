<footer>
    <section id="footer-section">
        <div id="footer-1" class="footer">
            <div class="logo" id="footer-logo">
                <img src="{{ asset('assets/new_frontend/logo.png') }}" alt="umbrella-logo" />
            </div>
            <div class="flex gap-15" id="social-icons">
                <div><i class="fa-brands fa-facebook"></i></div>
                <div><i class="fa-brands fa-linkedin"></i></div>
                <div><i class="fa-brands fa-instagram"></i></div>
                <div><i class="fa-brands fa-pinterest"></i></div>
            </div>
        </div>
        <div id="footer-2" class="footer">
            <div class="footer-heading">
                <h3>Contact Us</h3>
                <div class="underline"></div>
            </div>
            <div class="footer-content">
                <div class="footer-highlight">
                    <i class="fa-solid fa-location-dot"></i>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#locationModal">Find Location</a>
                </div>
                <p>contact@communityhealthcareclinics.com</p>
                <p>support@communityhealthcareclinics.com</p>
                <p>0337-2350684</p>
            </div>
        </div>
        <div id="footer-3" class="footer">
            <div class="footer-heading">
                <h3>Working Hours</h3>
                <div class="underline"></div>
            </div>
            <div class="footer-content">
                <p>07:00 am - 08:00 pm</p>
                <p>Community Health Care Clinics</p>
                <div class="footer-info">
                    <i class="fa-solid fa-chevron-right"></i>
                    <a href="{{ route('about_us') }}">About Us</a>
                </div>
                <div class="footer-info">
                    <i class="fa-solid fa-chevron-right"></i>
                    <a href="{{ route('contact_us') }}">Contact Us</a>
                </div>
                <div class="footer-info">
                    <i class="fa-solid fa-chevron-right"></i>
                    <a href="{{ route('faq') }}">FAQs</a>
                </div>
                <div class="footer-info">
                    <i class="fa-solid fa-chevron-right"></i>
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
                    <i class="fa-brands fa-whatsapp"></i>
                    <a href="">0337-2350684</a>
                </div>
                <p>In Emergency, Please call 911</p>
            </div>
        </div>
    </section>
    <div class="seperation"></div>
    <section id="copyright">
        <p>
            Copyright &copy; {{ date('Y') }}.
            <span>Community Health Care Clinics. All Rights Reserved</span>
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
