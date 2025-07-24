@php
$page = DB::table('pages')->where('url', '/')->first();
@endphp
<footer class="new-footer py-4 pt-sm-5 pb-sm-4">
  <section class="container-fluid px-0">
    <div
      class="row gx-0 gx-sm-3 gx-md-0 gy-2 gy-sm-2 gy-md-3 gx-lg-4 w-85 mx-auto justify-content-between justify-content-sm-start justify-content-md-between">
      <div class="footer-logo-container col-sm-6 col-md-4 col-lg-4 d-flex flex-column gap-4 gap-md-0 justify-content-sm-between mt-0 ps-sm-0 py-2 py-sm-auto">
        <div class="new-footer-logo">
          <img src="{{ asset('assets/new_frontend/logo-new.png') }}" alt="logo" />
        </div>
        <div
          class="social-links-container d-flex align-items-center justify-content-between justify-content-md-start gap-3 gap-sm-2 gap-md-4 flex-shrink-0 mt-2 mt-sm-0">
          <a target="_blank" href="https://www.facebook.com/share/15m4ofYggZ/"><i
              class="fa-brands fa-facebook-f"></i></a>
          <a target="_blank" href="https://www.linkedin.com/company/community-health-care-clinics/"><i
              class="fa-brands fa-linkedin-in"></i></a>
          <a target="_blank" href="https://www.instagram.com/community_healthcare_clinics?igsh=MXh6aHRzM2NrNThlMw=="><i
              class="fa-brands fa-instagram"></i></a>
          <a target="_blank" href="https://www.youtube.com/@CommunityHealthcareClinics"><i
              class="fa-brands fa-youtube"></i></a>
          <a target="_blank" href="https://www.tiktok.com/@community.healthc"><i class="fa-brands fa-tiktok"></i></a>
        </div>
      </div>
      <div class="col-6 col-sm-6 col-md-4 col-lg-1 social-links-section mt-2 mt-sm-0 px-md-0">
        <h5 class="new-footer-heading fw-semibold mt-2">LINKS</h5>
        <ul class="list-unstyled mt-3 links d-flex flex-column gap-2">
          <li><a class="fs-14 hover-anim" href="{{ url('/') }}">Home</a></li>
          <li><a class="fs-14 hover-anim" href="{{ route('about_us') }}">About Us</a></li>
          <li><a class="fs-14 hover-anim" href="{{ route('contact_us') }}">Contact Us</a></li>
          <li><a class="fs-14 hover-anim" href="{{ route('faq') }}">FAQs</a></li>
        </ul>
      </div>
      <div class="col-6 col-sm-6 col-md-4 col-lg-2 mt-2 mt-sm-4 mt-md-0 working-hours-section px-md-0">
        <h5 class="new-footer-heading fw-semibold mt-2">WORKING HOURS</h5>
        <ul class="list-unstyled mt-3 links d-flex flex-column gap-2">
          <li class="fs-14"><span class="fw-semibold">In-clinic:</span> 9am - 9pm</li>
          <li class="fs-14"><span class="fw-semibold">Online:</span> 24/7</li>
          <li class="fs-14">Community Healthcare Clinics</li>
        </ul>
      </div>
      <div class="col-sm-6 col-md-12 col-lg-4 mt-2 mt-sm-4 mt-lg-0 px-md-0">
        <h5 class="new-footer-heading fw-semibold mt-2">CONTACT US</h5>
        <ul class="list-unstyled mt-3 links d-flex flex-column gap-2">
          <li class="fs-14 d-flex align-items-center gap-2"><a href="https://wa.me/923372350684"><span class="fw-semibold">Phone: </span> 0337-2350684</a><a href="tel:+14076938484"><span class="fw-semibold"> | </span> +1 (407) 693-8484</a>
          </li>
          <li class="fs-14">
            <a target="_blank"
              href="https://maps.google.com/maps?ll=24.863264,67.074398&z=15&t=m&hl=en&gl=US&mapclient=embed&cid=11049335347165757010">
              <span class="fw-semibold">Location:</span> Progressive Center,
              4th Floor Suite#410, Main Shahrah Faisal, Karachi
            </a>
          </li>
          <li class="fs-14">
            <a href="mailto:contact@communityhealthcareclinics.com">
              <span class="fw-semibold">Email:</span>
              contact@communityhealthcareclinics.com
            </a>
          </li>
        </ul>
      </div>
      <hr class="col-12 border-blue-3 text-blue mt-2 mt-lg-5 mb-0" />
      <div class="col-sm-12 px-0">
        <div class="w-100 d-flex flex-column-reverse flex-md-row gap-2 gap-lg-0 flex-wrap align-items-center justify-content-between">
          <div class="d-flex align-items-center justify-content-between gap-4 fs-14">
            <a class="hover-anim" href="{{ route('terms_of_use') }}">Terms of Use</a>
            <a class="hover-anim" href="{{ route('privacy_policy') }}">Privacy Policy</a>
          </div>
          <p class="fs-14 text-center text-sm-start">
            Copyright © 2025 Community Healthcare Clinics. All Rights
            Reserved.
          </p>
        </div>
      </div>
    </div>
  </section>
</footer>