  <!-- FOOTER-1
      ============================================= -->
  <footer id="footer-1" class=" wide-40 footer division pt-4" style="background-color:white;border:1px #eee solid">
      <div class="container">


          <!-- FOOTER CONTENT -->
          <div class="row">


              <!-- FOOTER INFO -->
              <div class="col-md-12 col-lg-3">
                  <div class="footer-info mb-40">

                      <!-- Footer Logo -->
                      <!-- For Retina Ready displays take a image with double the amount of pixels that your image will be displayed (e.g 360 x 80  pixels) -->
                      <div class="footer-socials-links   text-center" id="footerLogoDiv">
                          <img src="{{ asset('asset_frontend/images/logo.png') }}" id="footerLogo" style="width:50%"
                              alt="footer-logo">
                      </div>
                      <!-- Text -->
                      <p class=" mt-20 align-item-center text-center" style="font-weight:500;"><strong>Umbrella Health
                              Care Systems</strong>
                      </p>

                      <!-- Social Icons -->
                      <div class="footer-socials-links mt-20  text-center">
                          <ul class="foo-socials text-center align-item-center  clearfix">

                              <li><a href="#" class="ico-facebook"><i class="fab fa-facebook-f"></i></a></li>
                              <li><a href="#" class="ico-twitter"><i class="fab fa-twitter"></i></a></li>
                              <li><a href="#" class="ico-google-plus"><i class="fab fa-google-plus-g"></i></a></li>
                              <li><a href="#" class="ico-tumblr"><i class="fab fa-tumblr"></i></a></li>

                          </ul>
                      </div>

                  </div>
              </div>


              <!-- FOOTER CONTACTS -->
              <div class="col-sm-4 col-md-4 col-lg-3 col-12 locationDiv footer-div mt-5">
                  <div class="footer-box mb-40">

                      <!-- Title -->
                      <h5 class="h5-xs">Our Location</h5>

                      <!-- Address -->
                      <p style="font-weight:500; ">6800 Olive Blvd </p>
                      <p style="font-weight:500; ">Suite B,</p>
                      <p style="font-weight:500; ">Saint Louis, MO, 63130</p>

                      <!-- Email -->
                      <p class="foo-email mt-20" style="font-weight:500; ">Email: <a
                              href="mailto:contact@umbrellamd.com" class="footer-link">contact@umbrellamd.com</a></p>
                      <p class="foo-email " style="font-weight:500; ">Or <a href="mailto:contact@umbrellamd.com"
                              class="footer-link">support@umbrellamd.com</a></p>

                      {{-- <!-- Phone -->contact --}}
                      <p style="font-weight:500; ">Phone: +1 (407) 693-8484</p>

                  </div>
              </div>


              <!-- FOOTER WORKING HOURS -->
              <div class=" col-md-4 col-sm-4  col-lg-3 col-12 col-sm-5 footer-div1 mt-5">
                  <div class="footer-box mb-40">

                      <!-- Title -->
                      <h5 class="h5-xs">Working Hours</h5>

                      <!-- Working Hours -->
                      <p class="p-sm" style="font-weight:500; ">Available <span>07:00 AM to 08:00 PM </span>
                      </p>
                      <h5 class="h5-xs mt-2">Umbrella Health Care Systems</h5>
                      <ul class="clearfix footer-ul">
                          <li><a class="font-weight-bold footer-link" href="{{ route('about_us') }}">About Us</a></li>
                          <li><a class="font-weight-bold footer-link" href="{{ route('contact_us') }}">Contact Us</a>
                          </li>
                          <li><a class="font-weight-bold footer-link" href="{{ route('careers') }}">Careers</a></li>
                          <li><a class="font-weight-bold footer-link" style="padding-left:1px"
                                  href="{{ route('faq') }}">FAQs</a></li>
                          <li><a class="font-weight-bold footer-link" style="padding-left:1px"
                                  href="{{ route('privacy_policy') }}">Privacy Policy</a></li>
                      </ul>
                  </div>
              </div>


              <!-- FOOTER PHONE NUMBER -->

              <div class="col-md-4 col-sm-4 col-lg-3 col-12 mt-5 emergency-div" id="emergencyDiv">
                  <div class="footer-box mb-40 ">

                      <!-- Title -->
                      <h5 class="h5-xs">Emergency Contact</h5>

                      <!-- Footer List -->
                      <h5 class="theme-blue m-0">
                          <form action="tel:14076938484"><button class="callbtn">Call +1 (407) 693-8484</button>
                          </form>
                      </h5>

                      <!-- Text -->
                      <p class="p-sm mt-3" style="font-weight:500; ">In emergency, Please call 911
                      </p>

                  </div>
              </div>


          </div> <!-- END FOOTER CONTENT -->
          <div class="bottom-footer p-4 mt-0">
              <div class="row">
                  <div class="col-md-12">
                      <h3 style="text-align:center">Disclaimer</h3>
                      <p>The information on this site is not intended or implied to be a substitute for professional
                          medical advice, diagnosis or treatment. All content, including text, graphics, images and
                          information, contained on or available through this web site is for general information
                          purposes only. Umbrellamd.com makes no representation and assumes no responsibility for the
                          accuracy of information contained on or available through this web site, and such information
                          is subject to change without notice. You are encouraged to confirm any information obtained
                          from or through this web site with other sources, and review all information regarding any
                          medical condition or treatment with your physician.</p>

                      <p>NEVER DISREGARD PROFESSIONAL MEDICAL ADVICE OR DELAY SEEKING MEDICAL TREATMENT BECAUSE OF
                          SOMETHING YOU HAVE READ ON OR ACCESSED THROUGH THIS WEB SITE. UMBRELLA HEALTH CARE SYSTEMS NOT
                          RESPONSIBLE
                          NOR LIABLE FOR ANY ADVICE, COURSE OF TREATMENT, DIAGNOSIS OR ANY OTHER INFORMATION, SERVICES
                          OR PRODUCTS THAT YOU OBTAIN THROUGH THIS WEB SITE.</p>
                  </div>
              </div>
          </div>
          <!-- FOOTER COPYRIGHT -->
          <div class="bottom-footer p-4 mt-0">
              <div class="row">
                  <div class="col-md-12">
                      <p class="footer-copyright text-center">&copy; <?= date('Y') ?> <span>Umbrella Health Care
                              Systems</span>. All Rights Reserved</p>
                  </div>
              </div>
          </div>


      </div> <!-- End container -->
  </footer> <!-- END FOOTER-1 -->
