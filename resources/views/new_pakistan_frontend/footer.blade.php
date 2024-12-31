@php
$page = DB::table('pages')->where('url', '/')->first();
@endphp
<section id="disclaimer">
    <div class="disclaimer-box"></div>
    <div id="disclaimer-content">
        <div>
            <h2>DISCLAIMER</h2>
            <div class="underline"></div>
        </div>
        <div>
            @php
            $section = DB::table('section')
            ->where('page_id', $page->id)
            ->where('section_name', 'disclaimer')
            ->where('sequence_no', '1')
            ->first();
            $top_content = DB::table('content')
            ->where('section_id', $section->id)
            ->first();
            $image_content = DB::table('images_content')
            ->where('section_id', $section->id)
            ->first();
            @endphp
            @if ($top_content)
            {!! $top_content->content !!}
            @else
            <p>
                The information on this site is not intended or implied to be a
                substitute for professional medical advice, diagnosis or
                treatment. All content, including text, graphics, images and
                Information, contained on or available through this web site is
                for general information purposes only. Umbrellamd.com makes no
                representation and assumes no responsibility for the accuracy of
                information contained on or available through this web site, and
                such information is subject to change without notice. You are
                encouraged to confirm any information obtained from or through
                this web site with other sources, and review all information
                regarding any medical condition or treatment with your
                physician.
            </p>
            <p>
                Never disregard professional medical advice or delay seeking
                medical treatment because of something you have read on or
                accessed through this web site. Community Health Care Clinics not
                responsible nor liable for any advice, course of treatment,
                diagnosis or any other information, services or products that
                you obtain through this website.
            </p>
            @endif
        </div>
    </div>
    <div class="custom-shape-divider-bottom-1731257443">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
            preserveAspectRatio="none">
            <path
                d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"
                class="shape-fill"></path>
        </svg>
    </div>
    <div class="disclaimer-blob">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="gray"
                d="M46,-39.1C56.3,-35.6,59.3,-17.8,54.9,-4.4C50.5,9.1,38.9,18.1,28.5,30.5C18.1,42.9,9.1,58.5,-2.6,61.1C-14.2,63.7,-28.4,53.2,-43.7,40.8C-59.1,28.4,-75.5,14.2,-75.6,-0.1C-75.6,-14.3,-59.3,-28.6,-44,-32.2C-28.6,-35.7,-14.3,-28.4,1.7,-30.2C17.8,-31.9,35.6,-42.7,46,-39.1Z"
                transform="translate(100 100)" />
        </svg>
    </div>
</section>
<footer>
    <section id="footer-section">
        <div id="footer-1" class="footer">
            <div class="logo" id="footer-logo">
                <img src="{{ asset('assets/new_frontend/logo.png') }}" alt="umbrella-logo" />
            </div>
            <div class="flex gap-15" id="social-icons">
                <a href="https://www.facebook.com/share/15m4ofYggZ/" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.linkedin.com/company/community-health-care-clinics/" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
                <a href="https://www.instagram.com/community_healthcare_clinics?igsh=MXh6aHRzM2NrNThlMw==" target="_blank"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div id="footer-2" class="footer">
            <div class="footer-heading">
                <h3>Contact Us</h3>
                <div class="underline"></div>
            </div>
            <div class="footer-content">
                <p class="d-flex align-items-center"><i class="fa-solid mx-2 fa-envelope"></i> <span>contact@communityhealthcareclinics.com</span></p>
                <p class="d-flex align-items-center"><i class="fa-solid mx-2 fa-envelope"></i> <span>support@communityhealthcareclinics.com</span></p>
                <p class="d-flex align-items-center"><i class="fa-solid mx-2 fa-location-dot"></i>  <span>Progressive Center, 4th Floor Suite#410, Main Shahrah Faisal, Karachi</span></p>

                <div class="footer-highlight">
                    <i class="fa-solid fa-phone"></i>
                    <a href="tel:+14076938484">+1 (407) 693-8484</a>
            </div>
            <div class="footer-highlight">
                <i class="fa-brands fa-whatsapp"></i>
                <a href="https://wa.me/923372350684" target="_blank">0337-2350684</a>
            </div>
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
                {{-- <div class="footer-info">
                    <i class="fa-solid fa-chevron-right"></i>
                    <a href="{{ route('privacy_policy') }}">Privacy Policy</a>
                </div> --}}
            </div>
        </div>
        <div id="footer-4" class="footer">
            <div class="footer-heading">
                <h3>Find Us</h3>
                <div class="underline"></div>
            </div>

            <div class="footer-content">

                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3620.017148282561!2d67.0743981!3d24.8632639!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33f3f9ba7181d%3A0x99571ff4d3fb7e52!2sCommunity%20Health%20Care%20Clinics!5e0!3m2!1sen!2s!4v1734451314564!5m2!1sen!2s" width="300" height="150" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

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
