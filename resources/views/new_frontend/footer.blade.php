<!-- ******* FOOTER START ******** -->
<style>
    .dropdown-select{
      display: none;
    }
    /* .address_phone_card{
      text-align: left;
      font-size: 13px;
      box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
      padding: 8px;
      border-radius: 10px;
      transition: all 300ms linear;
      cursor: pointer;
    } */
    .address_phone_card:hover{
      transform: scale(1.07);

    }
    .find_location_btn{
      cursor: pointer;
    }
    .find_location_btn:hover{
      color: rgb(255, 53, 53);
    }
    .main_cards_scroll{
      overflow-y: auto;
      max-height: 366px;
    }
  </style>

  <footer class="footer-section border-top">
      <div class="container">
          <div class="footer-content pt-4">
              <div class="row">
                  <div class="col-xl-3 col-lg-3 mb-3">
                      <div class="footer-widget">
                          <div class="footer-logo">
                              <a href="{{ url('/') }}"
                                  ><img
                                      src="{{ asset('assets/images/logo_footers.png') }}"
                                      class=""
                                      alt="logo"
                                      width="200"
                                      height="110"
                              /></a>
                          </div>
                          <div class="footer-social-icon">
                              <span>Follow us</span>
                              <a
                                  href="https://www.facebook.com/umbrellamd1"
                                  target="_blank"
                                  title="facebook"
                                  ><i class="fab fa-facebook-f facebook-bg"></i
                              ></a>
                              <a
                                  href="https://www.linkedin.com/company/umbrella-health-care-systems"
                                  target="_blank"
                                  title="linkedin"
                                  ><i class="fab fa-linkedin facebook-bg"></i
                              ></a>
                              <a href="https://twitter.com/umbrellahcs"
                                  target="_blank"
                                  title="twitter"
                                  ><i class="fab fa-twitter twitter-bg"></i
                              ></a>
                              <a href="https://www.instagram.com/umbrellahealthcaresystems/"
                                  target="_blank"
                                  title="instagram"
                                  ><i class="fab fa-instagram insta-bg"></i
                              ></a>
                              <a href="https://www.pinterest.com/UmbrellaHealthCareSystems/"
                                  target="_blank"
                                  title="pinterest"
                                  ><i class="fab fa-pinterest pinterest-bg"></i
                              ></a>
                          </div>
                      </div>
                  </div>
                  <div class="col-xl-3 col-lg-3 col-md-6 mb-30">
                      <div class="footer-widget">
                          <div class="footer-widget-heading">
                              <h1>Contact Us</h1>
                          </div>
                          <ul>
                              <li>Email : <a href="mailto:contact@umbrellamd.com">contact@umbrellamd.com</a></li>
                              <!-- <li>Or</li> -->
                              <li><b>Or</b> Email : <a href="mailto:support@umbrellamd.com">support@umbrellamd.com</a></li>
                              <li><strong>Phone</strong>: +1 (407) 693-8484</li>
                              <li><strong>Near By Locations</strong>:</li>
                              <li>Saint Lousi Missouri</li>
                              <li>Saint Lousi Missouri</li>
                          </ul>
                      </div>
                  </div>
                  <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                      <div class="footer-widget">
                          <div class="footer-widget-heading">
                              <h1>Working Hours</h1>
                          </div>
                          <ul>
                              <li>Available 07:00 AM to 08:00 PM</li>
                              <li>Umbrella Health Care Systems</li>
                              <li>
                                  <a href="{{ route('about_us') }}">About Us</a>
                              </li>
                              <li>
                                  <a href="{{ route('contact_us') }}"
                                      >Contact Us</a
                                  >
                              </li>
                              {{--
                              <li><a href="#">Careers</a></li>
                              --}}
                              <li><a href="{{ route('faq') }}">FAQs</a></li>
                              <li>
                                  <a href="{{ route('privacy_policy') }}"
                                      >Privacy Policy</a
                                  >
                              </li>
                          </ul>
                      </div>
                  </div>
                  <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                      <div class="footer-widget">
                          <div class="footer-widget-heading" id="contact">
                              <h1>Emergency Contact</h1>
                          </div>
                          <ul>
                              <li>
                                  <a href="tel:+14076938484" class="footer-call-btn text-light">
                                      <i class="fa-solid fa-phone"></i>+1 (407)
                                      693-8484
                                  </a>
                              </li>
                              <li  class="mt-2">In emergency, Please call 911</li>
                              <hr class="my-1">
                                <li><strong>Find Us</strong>:</li>
                              <li class="ab-spacing mt-2">
                          <p class="find_location_btn footer-call-btn mb-2" data-bs-toggle="modal" data-bs-target="#locationModal">
    
                            <i class="fa-solid fa-location-dot" style="color: rgb(255, 53, 53)"></i><span>More Location</span>
                          </p>
                          </li>
                          </ul>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="disclaimer-wrapper pt-3">
          <div class="container">
              <div class="row">
                  <div>
                      <h1 class="text-center">Disclaimer</h1>
                      <p>
                          The information on this site is not intended or implied
                          to be a substitute for professional medical advice,
                          diagnosis or treatment. All content, including text,
                          graphics, images and information, contained on or
                          available through this web site is for general
                          information purposes only. Umbrellamd.com makes no
                          representation and assumes no responsibility for the
                          accuracy of information contained on or available
                          through this web site, and such information is subject
                          to change without notice. You are encouraged to confirm
                          any information obtained from or through this web site
                          with other sources, and review all information regarding
                          any medical condition or treatment with your physician.
                      </p>
                      <p>
                          <em>
                              NEVER DISREGARD PROFESSIONAL MEDICAL ADVICE OR DELAY
                              SEEKING MEDICAL TREATMENT BECAUSE OF SOMETHING YOU
                              HAVE READ ON OR ACCESSED THROUGH THIS WEB SITE.
                              UMBRELLA HEALTH CARE SYSTEMS NOT RESPONSIBLE NOR
                              LIABLE FOR ANY ADVICE, COURSE OF TREATMENT,
                              DIAGNOSIS OR ANY OTHER INFORMATION, SERVICES OR
                              PRODUCTS THAT YOU OBTAIN THROUGH THIS WEB SITE.
                          </em>
                      </p>
                  </div>
              </div>
          </div>
      </div>
      <div class="copyright-area">
          <div class="container">
              <div class="row">
                  <div class="text-center text-lg-left">
                      <div class="copyright-text">
                          <p>
                              &copy; {{date('Y')}} <b>Umbrella Health Care Systems.</b> All
                              Rights Reserved
                          </p>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </footer>

  <!-- ******* FOOTER ENDS ******** -->
  @if(auth()->user()==null)
  <!-- Chatbot -->
  <div class="botIcon" id="fixed-chat">
      <i id="pop" class="position-absolute" style="color:red;"></i>
      <div class="botIconContainer">
          <div class="iconInner">
              <i class="fa fa-commenting" aria-hidden="true"></i>
          </div>
      </div>
      <div class="Layout Layout-open Layout-expand Layout-right">
          <div class="Messenger_messenger">
              <div class="Messenger_header">
              <span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/umbrella.png)"></figure></span>
                  <h4 class="Messenger_prompt">&nbsp;&nbsp;How can we help you?</h4>
                  <span class="chat_close_icon"
                      ><i class="fa fa-window-close" aria-hidden="true"></i
                  ></span>
              </div>
              <div class="Messenger_content">
                  <div class="Messages">
                      <div class="Messages_list"></div>
                  </div>
                  <form id="messenger">
                      <div class="Input Input-blank">
                          <input
                              name="msg"
                              class="Input_field"
                              placeholder="Send a message..."
                          />
                          <button
                              type="submit"
                              class="Input_button Input_button-send"
                          >
                              <div class="Icon">
                                  <svg
                                      viewBox="1496 193 57 54"
                                      version="1.1"
                                      xmlns="http://www.w3.org/2000/svg"
                                      xmlns:xlink="http://www.w3.org/1999/xlink"
                                  >
                                      <g
                                          id="Group-9-Copy-3"
                                          stroke="none"
                                          stroke-width="1"
                                          fill="none"
                                          fill-rule="evenodd"
                                          transform="translate(1523.000000, 220.000000) rotate(-270.000000) translate(-1523.000000, -220.000000) translate(1499.000000, 193.000000)"
                                      >
                                          <path
                                              d="M5.42994667,44.5306122 L16.5955554,44.5306122 L21.049938,20.423658 C21.6518463,17.1661523 26.3121212,17.1441362 26.9447801,20.3958097 L31.6405465,44.5306122 L42.5313185,44.5306122 L23.9806326,7.0871633 L5.42994667,44.5306122 Z M22.0420732,48.0757124 C21.779222,49.4982538 20.5386331,50.5306122 19.0920112,50.5306122 L1.59009899,50.5306122 C-1.20169244,50.5306122 -2.87079654,47.7697069 -1.64625638,45.2980459 L20.8461928,-0.101616237 C22.1967178,-2.8275701 25.7710778,-2.81438868 27.1150723,-0.101616237 L49.6075215,45.2980459 C5.08414042,47.7885641 49.1422456,50.5306122 46.3613062,50.5306122 L29.1679835,50.5306122 C27.7320366,50.5306122 26.4974445,49.5130766 26.2232033,48.1035608 L24.0760553,37.0678766 L22.0420732,48.0757124 Z"
                                              id="sendicon"
                                              fill="#96AAB4"
                                              fill-rule="nonzero"
                                          ></path>
                                      </g>
                                  </svg>
                              </div>
                          </button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
  <!-- Chatbot -->
  @endif
  <div
      class="modal fade"
      id="callingNewModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="staticBackdropLabel"
      aria-hidden="true"
  >
      <div class="modal-dialog modal-dialog-centered">
          <input type="hidden" id="session_user_id" />
          <div class="modal-content calling-modal">
              <div class="">
                  <div id="phone">
                      <div class="main">
                          <div class="header-background"></div>
                          <div class="avatar-wrapper">
                              <img
                                  id="img"
                                  src=""
                                  alt="user-image"
                              />
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
                      <div
                          class="footer d-flex justify-content-evenly flex-row-reverse"
                      >
                          <div class="decline">
                              <span id="callCounter" class="fs-5"></span>
                          </div>
                          <a href="{{url('/pat/video/page/0')}}" id="patientVideoCallingAcceptBtn">
                              <div class="accept">
                                  <span id="callCounter"></span
                                  ><i class="fas fa-phone"></i></div
                          ></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- ------------------Delete-Button-Modal-start------------------ -->

              <!-- Modal -->
              <div class="modal fade" id="ask_change_status" tabindex="-1" aria-labelledby="ask_change_statusLabel" aria-hidden="true">
                  <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                      <h5 class="modal-title" id="ask_change_statusLabel">Status Changed</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <div class="ask_change_status-modal-body text-dark p-5">
                          Because you were not active for last 15 minutes that's why we have changed your status to offline
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button>
                      </div>
                  </div>
                  </div>
              </div>


      <!-- ------------------Delete-Button-Modal-start------------------ -->

  <span id="top-scroll-button"></span>