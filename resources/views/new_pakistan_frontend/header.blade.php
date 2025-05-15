<style>
    .activetab {
        border-bottom: 2px solid #c80919 !important;
    }
</style>
<script>
    $(document).ready(function() {

      $(".search-btn-mob").on("click", function() {
          $(".header-search-container").css("display", "block");
      });

      $(document).on("click", function(event) {
          if (!$(event.target).closest(".header-search-container") && !$(event.target).closest(".search-btn-mob")) {
              $(".header-search-container").css("display", "none");
          }
      });

      $('#new-search2').on('input', function () {
      const searchTerm = $(this).val().trim().toLowerCase();

        if (searchTerm.length === 0) {
            $('.header-search-result').empty().hide();
            return;
        }

        $.ajax({
            url: `/search_items/${searchTerm}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const { products, test_codes } = response;

                $('.header-search-result').empty();

                if (products.length > 0 || test_codes.length > 0) {
                    products.forEach(product => {
                        $('.header-search-result').append(`
                            <li>
                                <a href="/medicines/${product.slug}" class="d-flex flex-column justify-content-between align-items-start w-100">
                                    <span class="product-name">${product.name}</span>
                                    <span class="category-name">Pharmacy</span>
                                </a>
                            </li>
                        `);
                    });

                    test_codes.forEach(test => {
                        $('.header-search-result').append(`
                            <li>
                                <a href="/labtest/${test.SLUG}" class="d-flex flex-column justify-content-between align-items-start w-100">
                                    <span class="product-name">${test.TEST_NAME}</span>
                                      <span class="category-name">Lab Test</span>
                                </a>
                            </li>
                        `);
                    });

                    $('.header-search-result').show();
                } else {
                    $('.header-search-result').hide();
                }
            },
            error: function (error) {
                console.error('Error fetching search results:', error);
            }
        });
      });
      $('#new-search').on('input', function () {
      const searchTerm = $(this).val().trim().toLowerCase();

        if (searchTerm.length === 0) {
            $('.header-search-result').empty().hide();
            return;
        }

        $.ajax({
            url: `/search_items/${searchTerm}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const { products, test_codes } = response;

                $('.header-search-result').empty();

                if (products.length > 0 || test_codes.length > 0) {
                    products.forEach(product => {
                        $('.header-search-result').append(`
                            <li>
                                <a href="/medicines/${product.slug}" class="d-flex flex-column justify-content-between align-items-start w-100">
                                    <span class="product-name">${product.name}</span>
                                    <span class="category-name">Pharmacy</span>
                                </a>
                            </li>
                        `);
                    });

                    test_codes.forEach(test => {
                        $('.header-search-result').append(`
                            <li>
                                <a href="/labtest/${test.SLUG}" class="d-flex flex-column justify-content-between align-items-start w-100">
                                    <span class="product-name">${test.TEST_NAME}</span>
                                      <span class="category-name">Lab Test</span>
                                </a>
                            </li>
                        `);
                    });

                    $('.header-search-result').show();
                } else {
                    $('.header-search-result').hide();
                }
            },
            error: function (error) {
                console.error('Error fetching search results:', error);
            }
        });
      });



  $(document).on('click', function(event) {
      if (!$(event.target).closest('.header-search-container')) {
          $('.header-search-result').hide();
        }
      });

      $('#new-search').on('focus', function() {
          if ($('.header-search-result').children().length > 0) {
              $('.header-search-result').show();
          }
      });

      $('#new-search').on('blur', function() {
          if ($(this).val() === "") {
              $('.header-search-result').hide();
          }
      });

      $('#new-search2').on('focus', function() {
          if ($('.header-search-result').children().length > 0) {
              $('.header-search-result').show();
          }
      });

      $('#new-search2').on('blur', function() {
          if ($(this).val() === "") {
              $('.header-search-result').hide();
          }
      });
  });

</script>


<!-- header  -->
<div id="offerBanner" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        @php
            $top_banners = DB::table('services')->where('name','ticker')->get();
        @endphp
        @foreach ($top_banners as $key => $banner)
            <div class="carousel-item {{$key==0?'active':''}}">
                <div class="offer-banner primary-bg text-white text-center py-2">
                    <span>{{ $banner->status }}</span>
                </div>
            </div>
        @endforeach
      {{-- <div class="carousel-item active">
        <div class="offer-banner primary-bg text-white text-center py-2">
          <span>Get 20% off on your first purchase! Use code: FIRST20</span>
        </div>
      </div>
      <div class="carousel-item">
        <div class="offer-banner primary-bg text-white text-center py-2">
          <span>Free delivery on orders above $50!</span>
        </div>
      </div>
      <div class="carousel-item">
        <div class="offer-banner primary-bg text-white text-center py-2">
          <span>Limited Time Offer: Buy 1 Get 1 Free!</span>
        </div>
      </div> --}}
    </div>
  </div>

<header class="py-2">
    <nav>
      <section id="navbar">
        <div class="wrap flex gap-15 between position-relative">
          <div id="nav-logo" class="logo" onclick="window.location.href='{{ url('/') }}'" style="cursor: pointer;">
            <img src="{{ asset('assets/new_frontend/logo.png') }}" alt="umbrella-logo" />
          </div>
          <div class="flex gap-2" id="nav-right-side">
            <div class="header-search-container w-100 w-lg-50 form-control px-2 py-2 position-relative">
                <div class="d-flex align-items-center justify-content-between">
                    <input type="search" name="header-search" placeholder="Search" class="header-search-field w-100" id="new-search">
                    <ul class="header-search-result categories-list rounded-3"></ul>
                    <button type="button" class="header-search-btn px-2"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>
            @if (Auth::check())
            <div class="dropdown" >
                <button class="dropdown-toggle w-100" type="button" id="joinDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-user-group"></i> Hi {{ Auth::user()->name}}
                </button>
                <ul class="dropdown-menu" aria-labelledby="joinDropdown">
                    <li><a class="dropdown-item" href="{{ route('home') }}">Go to Dashboard</a></li>
                    <li><a class="dropdown-item"  href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>
              </div>
              @else
                <div class="dropdown" >
                    <button class="dropdown-toggle w-100" type="button" id="joinDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user-group"></i> Join Us
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="joinDropdown">
                        <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                        <li><a class="dropdown-item" href="{{ route('doc_register') }}" >Register as Doctor</a></li>
                        <li><a class="dropdown-item" href="{{ route('pat_register') }}" >Register as Patient</a></li>
                    </ul>
                </div>
              @endif
              <button class="nav_btns">
                <i class="fa-brands fa-whatsapp"></i>
                <a href="https://wa.me/923372350684" target="_blank">0337-2350684</a>
              </button>
            <div class="position-relative" onclick="window.location.href='{{ url('/my/cart') }}'">
              <img src="{{ asset('assets/new_frontend/purchase-icon.svg') }}" alt="shop-icon" />
              @if (Auth::check())
                <div class="cart-count">
                    <span>{{ app('item_count_cart_responsive') }}</span>
                </div>
              @endif
            </div>
          </div>
          <div class="d-flex gap-3 align-items-center cart-search-container">
            <div class="position-relative cart-mob" onclick="window.location.href='{{ url('/my/cart') }}'">
                <img src="{{ asset('assets/new_frontend/purchase-icon.svg') }}" alt="shop-icon" />
                @if (Auth::check())
                  <div class="cart-count">
                      <span>{{ app('item_count_cart_responsive') }}</span>
                  </div>
                @endif
            </div>
            <i class="fa-solid fa-magnifying-glass search-btn-mob text-danger"></i>
            <div class="hamburger_container" onclick="toggleDrawer()">
              <div class="hamburger">
                <div></div>
                <div></div>
                <div></div>
              </div>
            </div>
          </div>
          <div class="drawer" id="drawer">
            <span class="close-btn" onclick="toggleDrawer()">×</span>
            <img width="220px" src="{{ asset('assets/new_frontend/logo.png') }}" alt="" />
            <hr />
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ route('vendor', ['shop_type' => 'pharmacy']) }}">Pharmacy</a>
            <a href="{{ route('vendor', ['shop_type' => 'labs']) }}">Lab Tests</a>
            <a href="{{ route('e-visit') }}">E-Visit</a>
            <a href="{{ route('doc_profile_page_list') }}">Our Doctors</a>
            <a href="{{ route('about_us') }}">About Us</a>
            <a href="{{ route('contact_us') }}">Contact Us</a>
            <hr />
            @if (Auth::check())
              <a href="{{ route('home') }}">Go to Dashboard</a></li>
              <a href="{{ route('logout') }}"
              onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            @else
            <a class="drawer-item" href="{{ route('login') }}">Login</a>
            <a class="drawer-item" href="{{ route('doc_register') }}" >Register as Doctor</a>
            <a class="drawer-item" href="{{ route('pat_register') }}" >Register as Patient</a>
            @endif
            <a href="https://wa.me/923372350684" target="_blank">0337-2350684</a>
          </div>
          <div
            class="blur-overlay"
            id="blurOverlay"
            onclick="toggleDrawer()"
          ></div>
        </div>
        <div class="flex gap-15" id="nav-left-side">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ route('vendor', ['shop_type' => 'pharmacy']) }}">Pharmacy</a>
            <a href="{{ route('vendor', ['shop_type' => 'labs']) }}">Lab Tests / Imaging</a>
            <a href="{{ route('e-visit') }}">E-Visit</a>
            <a href="{{ route('doc_profile_page_list') }}">Our Doctors</a>
            <a href="{{ route('about_us') }}">About Us</a>
            <a href="{{ route('contact_us') }}">Contact Us</a>
            <a href="{{ route('doc_profile_page_list', ['online' => true]) }}" class="text-success online_docs d-flex gap-1 align-items-center"><span class="blink-dot"></span><span style="font-weight: 800;">Online Doctors</span></a>



            {{--            <div class="dropdown">
                <a
                  class="dropdown-toggle"
                  href="#"
                  role="button"
                  id="servicesDropdown"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  Services
                </a>
                <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                  <li><a class="dropdown-item" href="{{ route('pharmacy') }}">Pharmacy</a></li>
                  <li><a class="dropdown-item" href="{{ route('labs') }}">Lab Tests</a></li>
                  <li><a class="dropdown-item" href="{{ route('imaging') }}">Imaging</a></li>
                  <li><a class="dropdown-item" href="{{ route('psychiatry',['slug'=>'anxiety']) }}">Psychiatry</a></li>
                  <li>
                    <a class="dropdown-item" href="{{ route('pain.management') }}">Pain Management</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('substance',['slug'=>'first-visit']) }}">Substance Abuse</a>
                  </li>
                </ul>
              </div> --}}
          </div>
      </section>
    </nav>
    <div class="header-search-container w-100 w-lg-50 form-control px-2 py-2 position-relative">
      <div class="d-flex align-items-center justify-content-between">
          <input type="search" name="header-search" placeholder="Search" class="header-search-field w-100" id="new-search2">
          <ul class="header-search-result categories-list rounded-3"></ul>
          <button type="button" class="header-search-btn px-2"><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
    </div>
  </header>

{{-- after registration and login modal --}}
<!-- ----------symptoms Checker Modal------- -->


<!-- Modal -->
<div class="modal fade" id="symptomsOpen" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Automated Symptoms Checker</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>
          <div class="">
            <div class="row justify-content-center p-0 m-0">
              <div class=" text-center p-0">
                <div class="card px-0 ">
                  <form id="msform">
                    <fieldset>
                      <div class="form-card">
                        <div class="row">
                          <div class="col-7">
                            <h2 class="fs-title">Patient Information:</h2>
                          </div>
                          <div class="col-5">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                            <label class="fieldlabels">Name: *</label>
                            <input class="custom_input symptom_checker_name name" type="text" name="name" placeholder="Name" value="{{ (Auth::check())? auth()->user()->name : '' }}" required/>
                            <small class="text-danger symptom_checker_name_error invalid-feedback "></small>
                          </div>
                          <div class="col-md-6">
                            <label class="fieldlabels">Email: *</label>
                            <input class="custom_input symptom_checker_email email" type="email" name="email" placeholder="Email" value="{{ (Auth::check())? auth()->user()->email : '' }}" required/>
                            <small class="text-danger symptom_checker_email_error invalid-feedback"></small>
                          </div>
                          <div class="col-md-6">
                            @php
                                if(Auth::check()){
                                    $dob = auth()->user()->date_of_birth;
                                    if ($dob) {
                                        $formattedDob = Carbon\Carbon::parse($dob)->format('Y-m-d');
                                        $age = Carbon\Carbon::parse($formattedDob)->age;
                                    } else {
                                        // Handle the case where the date of birth is not set
                                        $age = '';
                                    }
                                }
                            @endphp
                            <label class="fieldlabels">Age: *</label>
                            @if (Auth::check())
                                <input class="custom_input symptom_checker_age age" type="age" name="text" value="{{ isset($age)? $age : '' }}" placeholder="19" required/>
                            @else
                            <input class="custom_input symptom_checker_age age" type="age" name="text" placeholder="19" required/>
                            @endif
                            <small class="text-danger symptom_checker_age_error invalid-feedback"></small>
                          </div>
                          <div class="col-md-6">
                              <label class="fieldlabels">Gender: *</label>
                              <select name="gender" class="custom_input symptom_checker_gender gender">
                                <option selected disabled> Select Gender </option>
                               @if (Auth::check())
                                    <option value="male" {{ (auth()->user()->gender == 'male')? 'selected': '' }}> Male </option>
                                    <option value="female" {{ (auth()->user()->gender == 'female')? 'selected': '' }}> Female </option>
                                    <option value="other" {{ (auth()->user()->gender == 'other')? 'selected': '' }}> Other </option>
                               @else
                                    <option value="male" > Male </option>
                                    <option value="female" > Female </option>
                                    <option value="other"> Other </option>
                               @endif
                              </select>
                              <small class="text-danger symptom_checker_gender_error invalid-feedback"></small>
                        </div>
                        </div>

                      </div> <input type="button" name="next" class="next action-button" value="Next" />
                    </fieldset>
                    <fieldset>
                      <div class="form-card">
                        <div class="row">
                          <div class="col-7">
                            <h2 class="fs-title">Disclaimer</h2>
                          </div>
                          <div class="col-5">
                          </div>
                        </div>

                        <div class="accordion-body border rounded-2">
                            <p style="text-align: justify;">
                                Kindly be aware that this tool is not designed to offer medical advice.
                            </p><br>
                            <p style="text-align: justify;">
                                Our tool is not a substitute for professional medical advice, diagnosis, or treatment. It is crucial to thoroughly review the label of any over-the-counter (OTC) medications you may be considering. The label provides information about active ingredients and includes critical details such as potential drug interactions and side effects. Always consult with your physician or a qualified healthcare provider for any questions regarding a medical condition. Never disregard professional medical advice or delay seeking it due to information found on our website. If you suspect a medical emergency, please contact your doctor or call 911 without delay. Community Healthcare Clinics does not endorse or recommend specific products or services. Any reliance on information provided by Community Healthcare Clinics is solely at your discretion and risk.
                            </p>
                        </div>
                        <input type="checkbox" id="agree" class="agreeCheckbox" required>
                        <label for="agree"> By checking this box, It is considered you have read and agreed to the disclaimer.</label>
                        <small class="text-danger symptom_checker_check_error"></small>
                        </div> <input type="button" name="next" class="next action-button" value="Next" />
                    </fieldset>
                    <fieldset>
                      <div>
                        <div class="chat__main__">
                          <div class="text-start right__user">
                            <p class="right_p">Hello, How may i help you today??</p>
                            <img class="right__user_img" height="30" width="30" src="../../assets/images/doc__.jpg"
                              alt="">
                          </div>
                        </div>
                        <div>
                          <i class="loader fa fa-spinner fa-spin d-none" style="font-size:45px;"></i>
                          <div class="message__div">
                            <input type="text" class="form-control chat_answer" placeholder="Type symptoms...." name="answer">
                            <div>
                                <button type="submit" class="send_button" id="send_button"><i class="fa-regular fa-paper-plane me-0 send_icon send_button"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <button type="button" name="next" class="next action-button chat_next_button skip d-none" > Skip </button>
                      <input type="button" name="next" class="next action-button chat_next_button d-none" value="Next" />
                    </fieldset>
                    <fieldset>
                      <div>
                        <div class="text-start conclusions">
                            <i class="conclusion_loader fa fa-spinner fa-spin d-none d-flex justify-content-center" style="font-size:45px;"></i>
                            <h3 class="CEva_heading">Clinical Evaluation</h3>
                            <p class="CEva" style="text-align: justify;"></p>
                            <h3 class="HRep_heading">Hypothesis Report</h3>
                            <p class="HRep" style="text-align: justify;"></p>
                            <h3 class="INote_heading">Intake Notes</h3>
                            <p class="INote" style="text-align: justify;"></p>
                            <h3 class="RAT_heading">Referrals And Tests</h3>
                            <p class="RAT" style="text-align: justify;"></p>
                        </div>

                      </div>
                      <input type="button" name="next" class="next action-button" value="Submit" /> <input type="button"
                        name="previous" class="previous action-button-previous" value="Previous" />
                    </fieldset>
                    <fieldset>
                      <div class="form-card">
                        <div class="row">
                          <div class="col-7">
                            <h2 class="fs-title">Finish:</h2>
                          </div>
                          <div class="col-5">
                            <h2 class="steps">Step 4 - 4</h2>
                          </div>
                        </div> <br><br>
                        <h2 class="purple-text text-center"><strong>SUCCESS !</strong></h2> <br>
                        <div class="row justify-content-center">
                          <div class="col-3"> <img src="https://i.imgur.com/GwStPmg.png" class="fit-image"> </div>
                        </div> <br><br>
                        <div class="row justify-content-center">
                          <div class="col-7 text-center">
                            {{-- <h5 class="purple-text text-center">You Have Successfully Signed Up</h5> --}}
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="registar_open" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Automated Symptoms Checker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="">
                        <div class="row justify-content-center p-0 m-0">
                            <div class=" text-center p-0">
                                <div class="card px-0 ">
                                    <div class="model_body">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#registar_open').modal('hide');
        $.ajax({
            type: "get",
            url: "/check_cookie",
            success: function(response) {
                if (response == 'UnAuth') {
                    $('#registar_open').modal('hide');
                } else if (response != 0) {
                    console.log(response);
                    html = '<div class="text-start conclusions">' +
                        '<i class="conclusion_loader fa fa-spinner fa-spin d-none d-flex justify-content-center" style="font-size:45px;"></i>' +
                        '<h3 class="CEva_heading">Clinical Evaluation</h3>' +
                        '<p class="CEva" style="text-align: justify;">' + response
                        .clinical_evaluation + '</p>' +
                        '<h3 class="HRep_heading">Hypothesis Report</h3>' +
                        '<p class="HRep" style="text-align: justify;">' + response
                        .hypothesis_report + '</p>' +
                        '<h3 class="INote_heading">Intake Notes</h3>' +
                        '<p class="INote" style="text-align: justify;">' + response.intake_notes +
                        '</p>' +
                        '<h3 class="RAT_heading">Referrals And Tests</h3>' +
                        '<p class="RAT" style="text-align: justify;">' + response
                        .referrals_and_tests + '</p>' +
                        '</div>' +
                        '<input type="button" name="next" class="next action-button btn_finish" value="Submit" />';
                    $('.model_body').html(html);
                    $('#registar_open').modal('show');
                } else if (response == 0) {
                    $('#registar_open').modal('hide');
                }
            }
        });
        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;
        var current = 1;
        var steps = $("fieldset").length;
        var msg;
        var questions = 0;
        var session_id = '';
        var flag = false;

        setProgressBar(current);

        $(".next").click(async function() {
            if (current == 1) {
                var name = $('.symptom_checker_name').val();
                var syemail = $('.symptom_checker_email').val();
                var age = $('.symptom_checker_age').val();
                var gender = $('.symptom_checker_gender').val();
                $.ajax({
                    type: "POST",
                    url: "/symptom_checker_cookie_store",
                    async: false,
                    data: {
                        email: syemail,
                        name: name,
                        age: age,
                        gender: gender,
                    },
                    success: function(response) {
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                var element = $('.' + key);
                                element.addClass('is-invalid');
                                element.closest('.col-md-6').find(
                                    '.invalid-feedback').text(value);
                            });
                        } else {
                            flag = true;
                        }
                    }
                });
            } else if (current == 2) {
                if ($('.agreeCheckbox').is(':checked')) {
                    $('.symptom_checker_check_error').text('');
                    flag = true;
                } else {
                    flag = false;
                    $('.symptom_checker_check_error').text('Please read and check to proceed.');
                }
            } else if (current == 3) {
                flag = true;
                $.ajax({
                    type: "POST",
                    url: "/chat_done",
                    data: {
                        session_id: session_id,
                    },
                    beforeSend: function() {
                        $(".CEva_heading").addClass('d-none');
                        $(".HRep_heading").addClass('d-none');
                        $(".INote_heading").addClass('d-none');
                        $(".RAT_heading").addClass('d-none');
                        $(".conclusion_loader").removeClass('d-none');
                    },
                    success: function(response) {
                        if (response.auth == 0) {
                            var fullUrl = window.location.href;
                            html = ' <div class="modal-login-reg-btn my-3">' +
                                '<a href="' + fullUrl +
                                'patient_register"> REGISTER AS A PATIENT</a>' +
                                '<a href="' + fullUrl +
                                'doctor_register">REGISTER AS A DOCTOR </a>' +
                                '</div>' +
                                '<div class="login-or-sec">' +
                                '<hr>' +
                                'OR' +
                                '<hr>' +
                                '</div>' +
                                '<div style="text-align: center;">' +
                                '<p>Already have account?</p>' +
                                '<a href="' + fullUrl + 'login">Login</a>' +
                                '</div>';
                            $('.conclusions').html(html);
                            $('.next').addClass('d-none');
                            $('.previous').addClass('d-none');
                        } else {
                            $(".conclusion_loader").addClass('d-none');
                            $(".CEva_heading").removeClass('d-none');
                            $(".HRep_heading").removeClass('d-none');
                            $(".INote_heading").removeClass('d-none');
                            $(".RAT_heading").removeClass('d-none');
                            $(".CEva").html(response.response_api.clinical_evaluation);
                            $(".HRep").html(response.response_api.hypothesis_report);
                            $(".INote").html(response.response_api.intake_notes);
                            $(".RAT").html(response.response_api.referrals_and_tests);
                            $('.previous').addClass('d-none');
                        }

                    },
                });
            }
            if (flag) {
                current_fs = $(this).parent();
                next_fs = $(this).parent().next();
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                next_fs.show();
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        opacity = 1 - now;
                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 500
                });
                setProgressBar(++current);
                flag = false;
            }
        });

        $(".previous").click(function() {

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();

            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 500
            });
            setProgressBar(--current);
        });
        $("#send_button").click(function(e) {
            e.preventDefault();
            var answer = $('.chat_answer').val();
            var userImage =
                '{{ auth()->check() ? \App\Helper::check_bucket_files_url(auth()->user()->user_image) : '../../assets/images/no_image.png' }}';
            $('.chat__main__').append('<div class="text-end justify-content-lg-start right__user">' +
                '<img class="right__user_img" height="30" width="30" src="' + userImage +
                '" alt="">' +
                '<p class="left_p">' + answer + '</p></div>');
            $('.chat_answer').val('');
            if (questions == 0) {
                if (answer.includes('yes')) {
                    $('.chat__main__').append('<div class="text-start right__user">' +
                        '<p class="right_p">Sorry! This is an Emergency Situation. Please Visit Nearby Doctor immediately.</p>' +
                        '<img class="right__user_img" height="30" width="30" src="../../assets/images/doc__.jpg" alt=""></div>'
                    );
                    answer = $('.chat_answer').val('');
                    questions = 8;
                    session_id = response.session_id;
                    $('.chat__main__').animate({
                        scrollTop: $('.chat__main__')[0].scrollHeight
                    }, 'slow');
                    $('.chat_answer').val('');
                } else {
                    $('.chat__main__').append('<div class="text-start right__user">' +
                        '<p class="right_p">Did your tooth knocked out of mouth?</p>' +
                        '<img class="right__user_img" height="30" width="30" src="../../assets/images/doc__.jpg" alt=""></div>'
                    );
                    answer = $('.chat_answer').val('');
                    questions = 1;
                    session_id = response.session_id;
                    $('.chat__main__').animate({
                        scrollTop: $('.chat__main__')[0].scrollHeight
                    }, 'slow');
                    $('.chat_answer').val('');
                }
            } else if (questions == 1) {
                if (answer.includes('yes')) {
                    $('.chat__main__').append('<div class="text-start right__user">' +
                        '<p class="right_p">Sorry! This is an Emergency Situation. Please Visit Nearby Doctor immediately.</p>' +
                        '<img class="right__user_img" height="30" width="30" src="../../assets/images/doc__.jpg" alt=""></div>'
                    );
                    answer = $('.chat_answer').val('');
                    questions = 8;
                    session_id = response.session_id;
                    $('.chat__main__').animate({
                        scrollTop: $('.chat__main__')[0].scrollHeight
                    }, 'slow');
                    $('.chat_answer').val('');
                } else {
                    $('.chat__main__').append('<div class="text-start right__user">' +
                        '<p class="right_p">Ok, How may i help you?</p>' +
                        '<img class="right__user_img" height="30" width="30" src="../../assets/images/doc__.jpg" alt=""></div>'
                    );
                    answer = $('.chat_answer').val('');
                    questions = 2;
                    session_id = response.session_id;
                    $('.chat__main__').animate({
                        scrollTop: $('.chat__main__')[0].scrollHeight
                    }, 'slow');
                    $('.chat_answer').val('');
                }
            } else if (questions < 8) {
                $.ajax({
                    type: "POST",
                    url: "/symptom_chat",
                    data: {
                        message: answer,
                        session_id: session_id,
                    },
                    beforeSend: function() {
                        $(".loader").removeClass('d-none');
                        // $('#acceptIcon_'+order_id).html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {
                        $(".loader").addClass('d-none');
                        $('.chat__main__').append('<div class="text-start right__user">' +
                            '<p class="right_p">' + response.response + '</p>' +
                            '<img class="right__user_img" height="30" width="30" src="../../assets/images/doc__.jpg" alt=""></div>'
                        );
                        answer = $('.chat_answer').val('');
                        questions++;
                        session_id = response.session_id;
                        $('.chat__main__').animate({
                            scrollTop: $('.chat__main__')[0].scrollHeight
                        }, 'slow');
                        $('.chat_answer').val('');
                    },
                    error: function(response) {}
                });
            }
            if (questions >= 3) {
                $('.skip').removeClass('d-none');
            }
            if (questions >= 8) {
                $(".message__div").html('');
                $(".message__div").html(
                    'Your Questions Limit has Completed!! Please click Next to view Conclusion.');
                $('.chat_next_button').removeClass('d-none');
            }
        })

        function setProgressBar(curStep) {
            var percent = parseFloat(100 / steps) * curStep;
            percent = percent.toFixed();
            $(".progress-bar")
                .css("width", percent + "%")
        }

        $(".submit").click(function() {
            return false;
        })
    });
    $(document).ready(function() {
        $(document).on('click', '.btn_finish', function(e) {
            e.preventDefault();
            $.ajax({
                type: "get",
                url: "/forget_cookie",
                success: function(response) {
                    if (response == 1) {
                        $('#registar_open').modal('hide');
                    } else {
                        alert('X');
                    }
                }
            });
        });
        $(document).on('click', '.btn-close', function(e) {
            e.preventDefault();
            $.ajax({
                type: "get",
                url: "/forget_cookie",
                success: function(response) {
                    if (response == 1) {
                        $('#registar_open').modal('hide');
                    } else {
                        alert('X');
                    }
                }
            });
        });
    });

    $(document).ready(function() {
        var url = window.location.href;
        var activePage = url;
        $('#nav-left-side a').each(function() {
            var linkPage = this.href;
            if (activePage == linkPage) {
                $(this).closest("a").addClass("activetab");
            }
        });
    });
</script>
