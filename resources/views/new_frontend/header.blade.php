<!-- ******* HEADER START ******** -->
<?php
    $states = DB::table('physical_locations')->join('states','states.id','physical_locations.state_id')->select('states.*','state_id')->groupBy('state_id')->get();
    $cities = DB::table('physical_locations')->join('cities','cities.id','physical_locations.city_id')->select('cities.*','city_id')->groupBy('city_id')->get();
    $data = DB::table('physical_locations')->get();
?>
<style>
  .dropdown-select {
    display: none;
  }

  .address_phone_card {
    text-align: left;
    font-size: 13px;
    box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
    /* padding: 8px; */
    border-radius: 10px;
    transition: all 300ms linear;
    cursor: pointer;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .address_phone_card:hover {
    transform: scale(1.07);

  }

  .find_location_btn {
    cursor: pointer;
    color:#fff;
  }

  .find_location_btn:hover {
    color: rgb(255, 53, 53);
  }

  .main_cards_scroll {
    overflow-y: auto;
    max-height: 366px;
  }

  .buttons_Main_div {
    display: flex;
    gap: 0px;
  }

  .buttons_Main_div button {
    background-color: #08295a;
    width: 100%;
    border: 1px solid #fff;
    color: #fff;
    padding: 7px;
    font-size: 15px;
    border-radius: 0px 0px 0px 10px;

  }

  .buttons_Main_div .second_btn {
    border-radius: 0px 0px 10px 0px;

  }

  .services_ul li {
    list-style: disc;
    margin-left: 30px;
  }

  .heading_underL {
    color: #08295a;
    text-decoration: 2px underline #08295a;
  }

  .left_servi_main {
    box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
    padding: 15px 8px;
    border-radius: 5px;
  }

  /* -----symptoms-Checker-Css-- */
  #heading {
    text-transform: uppercase;
    color: #673AB7;
    font-weight: normal
  }

  #msform {
    text-align: center;
    position: relative;
    margin-top: 20px
  }

  #msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
  }

  .form-card {
    text-align: left
  }

  #msform fieldset:not(:first-of-type) {
    display: none
  }

  #msform .custom_input {
    padding: 8px 15px 8px 15px;
    border: 1px solid #ccc;
    border-radius: 0px;
    margin-bottom: 14px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    background-color: #ECEFF1;
    font-size: 16px;
    letter-spacing: 1px
  }

  #msform .custom_input:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #673AB7;
    outline-width: 0
  }

  #msform .action-button {
    width: 100px;
    background: #673AB7;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 0px 10px 5px;
    float: right
  }

  #msform .action-button:hover,
  #msform .action-button:focus {
    background-color: #311B92
  }

  #msform .action-button-previous {
    width: 100px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px 10px 0px;
    float: right
  }

  #msform .action-button-previous:hover,
  #msform .action-button-previous:focus {
    background-color: #000000
  }

  .card {
    z-index: 0;
    border: none;
    position: relative
  }

  .fs-title {
    font-size: 25px;
    color: #673AB7;
    margin-bottom: 15px;
    font-weight: normal;
    text-align: left
  }

  .purple-text {
    color: #673AB7;
    font-weight: normal
  }

  .steps {
    font-size: 25px;
    color: gray;
    margin-bottom: 10px;
    font-weight: normal;
    text-align: right
  }

  .fieldlabels {
    color: gray;
    text-align: left
  }

  #progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey
  }

  #progressbar .active {
    color: #673AB7
  }

  #progressbar li {
    list-style-type: none;
    font-size: 15px;
    width: 25%;
    float: left;
    position: relative;
    font-weight: 400
  }

  #progressbar #account:before {
    font-family: FontAwesome;
    content: "\f13e"
  }

  #progressbar #personal:before {
    font-family: FontAwesome;
    content: "\f007"
  }

  #progressbar #payment:before {
    font-family: FontAwesome;
    content: "\f030"
  }

  #progressbar #confirm:before {
    font-family: FontAwesome;
    content: "\f00c"
  }

  #progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 20px;
    color: #ffffff;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px
  }

  #progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
  }

  #progressbar li.active:before,
  #progressbar li.active:after {
    background: #673AB7
  }

  .progress {
    height: 20px
  }

  .progress-bar {
    background-color: #673AB7
  }

  .fit-image {
    width: 100%;
    object-fit: cover
  }

  .right__user {
    display: flex;
    justify-content: end;
    gap: 14px;
    align-items: center;
  }

  .right__user_img {
    border-radius: 15px;
    width: 30px;
    height: 30px;
  }

  .chat__main__ {
    max-height: 180px;
    overflow-y: auto;
  }

  .message__div {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 10px;
  }

  .send_icon:hover {
    transform: scale(1.3);
    transition: 150ms ease-in;
    color: #08295a;
    font-weight: 600;
    cursor: pointer;
  }

  .left_p {
    background-color: #cecece;
    padding: 10px 19px;
    border-radius: 10px 10px 10px 0px;
    color: #000;
    text-align: left;
    max-width: 300px;
    margin-top: 10px;
    margin-bottom: 10px;
  }

  .right_p {
    background-color: #08295a;
    padding: 10px 19px;
    border-radius: 10px 10px 0px 10px;
    color: #fff;
    max-width: 300px;
  }
  .btn_finish{
    float: right;
    margin-top: 10px;
    padding: 10px;
    background: #08295a;
    border: 0px;
    border-radius: 5px;
    color: #ffff;
    width: 100px;
  }
  #send_button{
    border: none;
    padding: 6px 8px;
    outline: none;
    background: #08295a;
    color: #fff;
    border-radius: 5px;
}
.mr3{
  margin-right: 10px;
}
</style>
<p class="notification"></p>
<header>
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a class="nav-link resp-cart-icon" href="{{ url('/my/cart') }}"><i class="fa-solid fa-cart-shopping"></i>
        @if (Auth::check())
        <sup id="cart_counter">
          {{ app('item_count_cart_responsive') }}
        </sup>
        @else
        <sup id="cart_counter">0</sup>
        @endif
      </a>
      {{-- <a class="nav-link resp-cart-icon" href="{{ url('/') }}"><i
          class="fa-solid fa-cart-shopping"></i><sup>0</sup> </a> --}}
      <a class="navbar-brand navbar-logo" href="{{ url('/') }}">
        <img src="{{ asset('assets/images/dashboards_logo.png?n=2')}}" alt="Community Health Care Clinics"
          title="Community Health Care Clinics" class="" width="120" height="70" />
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav nav-list mx-auto">
          <li class="nav-item mx-1">
            <a class="nav-link " aria-current="page" href="{{ url('/') }}">Home</a>
          </li>
          <li class="nav-item mx-1">
            <a class="nav-link" href="{{ route('about_us') }}">About</a>
          </li>
          <li class="nav-item mx-1 dropdown">
            <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Services
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('pharmacy') }}">Pharmacy</a></li>
              <li><a class="dropdown-item" href="{{ route('labs') }}">LabTests</a></li>
              <li><a class="dropdown-item" href="{{ route('imaging') }}">Medical Imaging</a></li>
              <li><a class="dropdown-item" href="{{ route('primary') }}">Primary Care</a></li>
              <li><a class="dropdown-item" href="{{ route('psychiatry',['slug'=>'anxiety']) }}">Psychiatry</a></li>
              <li><a class="dropdown-item" href="{{ route('pain.management') }}">Pain Management</a></li>
              <li><a class="dropdown-item" href="{{ route('substance',['slug'=>'first-visit']) }}">Substance Abuse</a>
              </li>
            </ul>
          </li>

          <li class="nav-item mx-1">
            <a class="nav-link" href="{{ route('e-visit') }}">E-Visit</a>
          </li>
          {{-- <li class="nav-item mx-1">
            <a class="nav-link" href="{{ route('our_doctors') }}">Doctors</a>
          </li> --}}
          <li class="nav-item mx-1">
            <a class="nav-link" href="{{ route('contact_us') }}">Contact</a>
          </li>
          <!-- @if (!Auth::check())
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Join Us</a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                  <li><a class="dropdown-item" href="{{ route('doc_register') }}">Register as Doctor</a></li>
                  <li><a class="dropdown-item" href="{{ route('pat_register') }}">Register as Patient</a></li>
                </ul>
              </li>
            @else
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Hi {{ Auth::user()->name }} </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                     <li><a class="dropdown-item" href="{{ route('home') }}">Go To Dashboard</a></li>
                  <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Sign Out</a></li>
                </ul>
              </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @endif -->

        </ul>
        <ul class="navbar-nav nav-right-items align-items-center after-login-dash-res">

          @if (!Auth::check())
          <li class="nav-item"><a class="nav-link go-dash-btn me-2" data-bs-toggle="modal"
              data-bs-target="#symptomsOpen">Symptoms Checker</a></li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle join-us-drop go-dash-btn" href="#" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">Join Us</a>
            <ul class="dropdown-menu" style="font-size: 14px;">

              <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
              <li><a class="dropdown-item" href="{{ route('doc_register') }}">Register as Doctor</a></li>
              <li><a class="dropdown-item" href="{{ route('pat_register') }}">Register as Patient</a></li>
              <!-- <li><a class="dropdown-item" href="{{ route('assistant_doctor_register') }}">Register as Assistant Doctor</a></li> -->
            </ul>
          </li>

          @else


          <!-- /////////////////////////// -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle join-us-drop go-dash-btn d-flex align-items-center " href="#" role="button"
              data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-user fs-3"></i>

              <h6 class="me-3"> Hi {{ Auth::user()->name }} </h6></a>
            <ul class="dropdown-menu" style="font-size: 14px;">
              <li><a class="dropdown-item" data-bs-toggle="modal"
              data-bs-target="#symptomsOpen">Symptoms Checker</a></li>
              <li><a class="dropdown-item" href="{{ route('home') }}">Go To Dashboard</a></li>
              <li><a class="dropdown-item"  href="{{ route('logout') }}"
              onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></li>
              <!-- <li><a class="dropdown-item" href="{{ route('assistant_doctor_register') }}">Register as Assistant Doctor</a></li> -->
            </ul>
          </li>
          <!-- /////////////////////////// -->


          <!-- <li class="nav-item d-flex align-items-center ">
            <i class="fa-regular fa-circle-user fs-3"></i>
            <h6 class="me-3"> Hi {{ Auth::user()->name }} </h6>
          </li>
          <li class="nav-item"><a class="nav-link go-dash-btn me-2" data-bs-toggle="modal"
              data-bs-target="#symptomsOpen">Symptoms Checker</a></li>

          <li class="nav-item"><a class="nav-link go-dash-btn" href="{{ route('home') }}">Go To Dashboard</a></li>
          <li class="nav-item"><a class="nav-link dash-logout-btn" href="{{ route('logout') }}"
              onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                class="fa-solid fa-arrow-right-from-bracket"></i> Logout </a></li> -->

          <!-- <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Hi {{ Auth::user()->name }} </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                     <li><a class="dropdown-item" href="{{ route('home') }}">Go To Dashboard</a></li>
                  <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Sign Out</a></li>
                </ul>
              </li> -->

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
          @endif


          <!-- <li class="nav-item">
              <h6 class="me-3">Hi, Anas</h6>
                </li>
            <li><a class="dropdown-item go-dash-btn" href="{{ route('home') }}">Go To Dashboard</a></li>
            <li><a class="dropdown-item dash-logout-btn" href="{{ route('home') }}"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout </a></li>
           -->


          <!-- <li class="nav-item m-auto">
                <div class="navbar-social-icon d-flex">
                  <a href="https://www.facebook.com/umbrellamd1" target="_blank"><i class="fab fa-facebook-f facebook-bg"></i></a>
                  <a href="https://www.linkedin.com/company/umbrella-health-care-systems" target="_blank"><i class="fab fa-linkedin facebook-bg"></i></a>
                  <a href="#"><i class="fab fa-twitter twitter-bg" target="_blank"></i></a>
                  <a href="#"><i class="fab fa-instagram insta-bg" ></i></a>
                </div>
              </li>
              <li class="nav-item">
                <div class="navbar-icon">
                  <ul class="navbar-location">
                  <li class="ab-spacing">
                      <i class="fa-solid fa-location-dot"></i><span> 6800 Olive Blvd
                      Suite B, Saint Louis, MO, 63130 </span>
                    </li>
                    <li><i class="fa-solid fa-phone"></i> +1 (407) 693-8484</li>
                  </ul>
                </div>
              </li> -->

              <li class="nav-item navbar-add-cart mx-2">
            <a class="nav-link" href="{{ url('/my/cart') }}"><i class="fa-solid fa-cart-shopping"></i>
              @if (Auth::check())
              <sup id="cart_counter_res">
                {{ app('item_count_cart') }}
              </sup>
              @else
              <sup id="cart_counter_res">0</sup>
              @endif
            </a>
          </li>

        </ul>
      </div>
    </div>
  </nav>

</header>
<!-- Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="locationModalLabel">Find Location</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>
          <div class="row">
            <div class="col-md-7">
              <div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="mb-3 text-start">
                      <label for="zipp" class="form-label">Zip</label>
                      <input type="text" class="form-control zip_code" id="zipp" placeholder="63101">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3 text-start">
                      <label for="exampleFormControlInput1" class="form-label">State</label>
                      <select class="form-select state" aria-label="Default select example"
                        onchange="fetchByState(event.target.value)">
                        <option value="" selected>Select State</option>
                        @foreach ($states as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3 text-start">
                      <label for="exampleFormControlInput1" class="form-label">City</label>
                      <select class="form-select city" aria-label="Default select example"
                        onchange="fetchByCity(event.target.value)">
                        <option value="" selected>Select City</option>
                        @foreach ($cities as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="main_cards_scroll px-3">
                <div class="row locations_data">
                  @forelse ($data as $item)
                  <div class="col-md-6 mb-2">
                    <div class="address_phone_card">
                      <div class="px-2 mb-2">
                        <p><i class="fa-solid fa-location-dot" style="color: rgb(255, 53, 53)"></i><span> {{ $item->name
                            }}</span></p>
                        <p><i class="fa-solid fa-phone" style="color: rgb(12, 180, 12)"></i> {{ $item->phone_number }}
                        </p>
                      </div>

                      <div class="buttons_Main_div">
                        <button class="" onclick="showDetails({{ $item->id }})">Details</button>
                        <button class="second_btn"
                          onclick="showMap({{ $item->latitude }},{{ $item->longitude }}, {{ $item->id }})">Map</button>
                      </div>
                    </div>
                  </div>
                  @empty
                  <div class="col-md-12 mb-2"> No Data</div>
                  @endforelse
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="text-start left_servi_main services d-none">
                <div class="d-flex mb-2">
                  <p><i class="fa-solid fa-location-dot" style="color: rgb(255, 53, 53)"></i></p>
                  <h5 class="heading">625 School House Road #2, Lakeland, FL 33813</h5>

                </div>
                <div>
                  <div class="row">
                    <div class="col-md-5">
                      <div>
                        <h4 class="heading_underL">Services:</h4>
                        <ul class="services_ul">
                          <li>Imaging</li>
                          <li>Lab Test</li>
                          <li>lorem</li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-md-7">
                      <h4 class="heading_underL">Working Hours:</h4>
                      <p class="working_hours"></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-none map">
                <iframe class="w-100"
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3523.8474140516387!2d-81.96890502615281!3d27.96795891395188!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88dd3be31c915551%3A0x37b2876ea15a043e!2s625%20School%20House%20Rd%20%232%2C%20Lakeland%2C%20FL%2033813%2C%20USA!5e0!3m2!1sen!2s!4v1695389922835!5m2!1sen!2s"
                  width="300" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>


            </div>

          </div>
        </div>
      </div>
    </div>
    <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
  </div>
</div>
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
                                Our tool is not a substitute for professional medical advice, diagnosis, or treatment. It is crucial to thoroughly review the label of any over-the-counter (OTC) medications you may be considering. The label provides information about active ingredients and includes critical details such as potential drug interactions and side effects. Always consult with your physician or a qualified healthcare provider for any questions regarding a medical condition. Never disregard professional medical advice or delay seeking it due to information found on our website. If you suspect a medical emergency, please contact your doctor or call 911 without delay. Community Health Care Clinics does not endorse or recommend specific products or services. Any reliance on information provided by Community Health Care Clinics is solely at your discretion and risk.
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

</div>
<!-- ******* HEADER ENDS ******** -->
{{-- after registration and login modal --}}
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
  $(".zip_code").keyup(function () {
    var zip = $(".zip_code").val();
    var length = $(".zip_code").val().length;

    if (length >= 5) {
      $.ajax({
        type: "POST",
        url: "/get_states_cities",
        data: {
          zip: zip,
        },
        success: function (data) {
          // console.log(data);
          // $('.services').addClass('d-none');
          if (data == "") {
            $('.zipcode_error').text('Please enter a valid zipcode');
            $('.zip_code').addClass('border-danger');
            return false;
          } else {
            $('.services').addClass('d-none');
            $('.map').addClass('d-none');
            $('.zipcode_error').text('');
            $('.zip_code').removeClass('border-danger');
            $(".state option:contains('" + data.state + "')").prop('selected', true);
            $(".city option:contains('" + data.city + "')").prop('selected', true);
            // $('.state').append('<option value="'+data.state_id+'" selected>'+data.state+'</option>');
            // $('.city').append('<option value="'+data.city_id+'" selected>'+data.city+'</option>');
          }
        },
      });
      $.ajax({
        type: "POST",
        url: "/get_physical_location",
        data: {
          zip: zip,
        },
        success: function (location) {
          if (location == "") {
            $(".locations_data").html('<div class="col-md-12 mb-2"> No Data</div>')
            return false;
          } else {
            $(".locations_data").html('');
            $(".services_ul").html('');
            location.forEach(element => {
              // showMap(element.latitude,element.longitude,element.id);
              $('.locations_data').append('<div class="col-md-6 mb-2">' +
                '<div class="address_phone_card">' +
                '<div class="px-2 mb-2"><p><i class="fa-solid fa-location-dot" style="color: rgb(255, 53, 53)"></i><span>' +
                element.name + '</span></p><p><i class="fa-solid fa-phone" style="color: rgb(12, 180, 12)"></i>' +
                element.phone_number + '</p></div><div class="buttons_Main_div"><button class=""  onclick="showDetails(' + element.id + ')">Details</button>' +
                '<button class="second_btn" onclick="showMap(' + element.latitude + ',' + element.longitude + ',' + element.id + ')">Map</button></div></div></div>');
            });
          }
        }
      });
    }
  });

  function fetchByState(value) {
    var state = value;
    $(".zip_code").val("");
    $(".city").val("");
    $.ajax({
      type: "POST",
      url: "/get_physical_location_by_state",
      data: {
        state: value,
      },
      success: function (location) {
        $(".map").addClass('d-none');
        $('.services').addClass('d-none');
        if (location['data'] == "") {
          $(".locations_data").html('<div class="col-md-12 mb-2"> No Data</div>')
          return false;
        } else {
          $(".heading").html(location['name']);
          $(".locations_data").html('');
          location['data'].forEach(element => {
            $('.services_ul').html("");
            //   showDetails(element.id);
            $('.locations_data').append('<div class="col-md-6 mb-2">' +
              '<div class="address_phone_card">' +
              '<div class="px-2 mb-2"><p><i class="fa-solid fa-location-dot" style="color: rgb(255, 53, 53)"></i><span>' +
              element.name + '</span></p><p><i class="fa-solid fa-phone" style="color: rgb(12, 180, 12)"></i>' +
              element.phone_number + '</p></div><div class="buttons_Main_div"><button class="" onclick="showDetails(' + element.id + ')">Details</button>' +
              '<button class="second_btn" onclick="showMap(' + element.latitude + ',' + element.longitude + ',' + element.id + ')">Map</button></div></div></div>');
          });
          $('.city').html('');
          $(".city").append('<option value="">Select City</option>');
          location['cities'].forEach(element => {
            $(".city").append('<option value="' + element.id + '">' + element.name + '</option>');
          });
        }
      }
    });
  }

  function fetchByCity(value) {
    var city = value;
    var state = $(".state").val();
    $(".zip_code").val("");
    $.ajax({
      type: "POST",
      url: "/get_physical_location_by_city",
      data: {
        city: value,
        state: state,
      },
      success: function (location) {
        $(".map").addClass('d-none');
        $('.services').addClass('d-none');
        if (location == "") {
          $(".locations_data").html('<div class="col-md-12 mb-2"> No Data</div>')
          return false;
        } else {
          $(".locations_data").html('');
          location.forEach(element => {
            $('.locations_data').append('<div class="col-md-6 mb-2">' +
              '<div class="address_phone_card">' +
              '<div class="px-2 mb-2"><p><i class="fa-solid fa-location-dot" style="color: rgb(255, 53, 53)"></i><span>' +
              element.name + '</span></p><p><i class="fa-solid fa-phone" style="color: rgb(12, 180, 12)"></i>' +
              element.phone_number + '</p></div><div class="buttons_Main_div"><button class="" onclick="showDetails(' + element.id + ')">Details</button>' +
              '<button class="second_btn" onclick="showMap(' + element.latitude + ',' + element.longitude + ',' + element.id + ')">Map</button></div></div></div>');
          });
        }
      }
    });
  }


  function showMap(latitude, longitude, id) {
    $('.map').removeClass('d-none');
    $('.services').removeClass('d-none');
    // showDetails(id);
    $('.services').addClass('d-none');
    if (latitude != null && longitude != null) {
      $('.map').html('<iframe class="w-100"' +
        'src="https://www.google.com/maps/?q=' + latitude + ',' + longitude + '&hl=es;z=14&amp;output=embed"' +
        'width="300" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>');
    } else {
      $('.map').html("<p>Sorry Map is not available for this location</p>")
    }

  }

  function showDetails(id) {
    $('.services').removeClass('d-none');
    $('.map').addClass('d-none');
    $('.heading').html("");
    $('.services_ul').html("");
    $.ajax({
      type: "POST",
      url: "/get_physical_location_by_id",
      data: {
        id: id,
      },
      success: function (location) {
        $('.heading').html(location.name)
        if (location.time_from != null && location.time_to != null) {
          // console.log(location.time_from );
          var timeFrom = formatTime(location.time_from);
          var timeTo = formatTime(location.time_to);
          $('.working_hours').html('');
          var html = 'Available ' + timeFrom + ' to ' + timeTo;
          $('.working_hours').html(html);
        } else {
          $('.working_hours').html('No working hours available');
        }
        if (location.services != null) {
          $.each(location.services, function (indexInArray, valueOfElement) {
            $('.services_ul').append('<li>' + valueOfElement + '</li>');
          });
        } else {
          $('.services_ul').html("");
          $('.services_ul').append('<li>Services not Added</li>');
        }
      }
    })
  }
  function formatTime(timeString) {
    if (typeof timeString === 'string' && /\d{2}:\d{2}/.test(timeString)) {
      var timeParts = timeString.split(":");
      var hours = parseInt(timeParts[0]);
      var minutes = timeParts[1];
      var period = hours >= 12 ? "PM" : "AM";
      hours = hours % 12 || 12;
      return hours + ":" + minutes + " " + period;
    } else {
      return "Invalid time format";
    }
  }
  // --symptoms-Checker-jQuery--
$(document).ready(function () {
    $('#registar_open').modal('hide');
    $.ajax({
        type: "get",
        url: "/check_cookie",
        success: function (response) {
            if(response == 'UnAuth'){
                $('#registar_open').modal('hide');
            } else if(response != 0){
            console.log(response);
                html= '<div class="text-start conclusions">'+
                        '<i class="conclusion_loader fa fa-spinner fa-spin d-none d-flex justify-content-center" style="font-size:45px;"></i>'+
                        '<h3 class="CEva_heading">Clinical Evaluation</h3>'+
                        '<p class="CEva" style="text-align: justify;">'+response.clinical_evaluation+'</p>'+
                        '<h3 class="HRep_heading">Hypothesis Report</h3>'+
                        '<p class="HRep" style="text-align: justify;">'+response.hypothesis_report+'</p>'+
                        '<h3 class="INote_heading">Intake Notes</h3>'+
                        '<p class="INote" style="text-align: justify;">'+response.intake_notes+'</p>'+
                        '<h3 class="RAT_heading">Referrals And Tests</h3>'+
                        '<p class="RAT" style="text-align: justify;">'+response.referrals_and_tests+'</p>'+
                    '</div>'+
                    '<input type="button" name="next" class="next action-button btn_finish" value="Submit" />';
                $('.model_body').html(html);
                $('#registar_open').modal('show');
            } else if(response == 0){
                $('#registar_open').modal('hide');
            }
        }
    });
    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;
    var current = 1;
    var steps = $("fieldset").length;
    var msg;
    var questions = 1;
    var session_id = '';
    var flag = false;

    setProgressBar(current);

    $(".next").click(async function () {
        if(current == 1){
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
                success: function (response) {
                    if(response.errors){
                        $.each(response.errors, function (key, value) {
                            var element = $('.' + key);
                                element.addClass('is-invalid');
                                element.closest('.col-md-6').find('.invalid-feedback').text(value);
                        });
                    } else{
                        flag = true;
                    }
                }
            });
        }else if(current == 2){
            if ($('.agreeCheckbox').is(':checked')) {
                $('.symptom_checker_check_error').text('');
                flag = true;
            } else {
                flag = false;
                $('.symptom_checker_check_error').text('Please read and check to proceed.');
            }
        }else if(current == 3){
            flag = true;
            $.ajax({
            type: "POST",
            url: "/chat_done",
            data: {
                session_id: session_id,
            },
            beforeSend: function(){
                $(".CEva_heading").addClass('d-none');
                $(".HRep_heading").addClass('d-none');
                $(".INote_heading").addClass('d-none');
                $(".RAT_heading").addClass('d-none');
                $(".conclusion_loader").removeClass('d-none');
            },
            success: function (response) {
                if(response.auth == 0){
                   var fullUrl = window.location.href;
                   html =' <div class="modal-login-reg-btn my-3">'+
                    '<a href="'+fullUrl+'patient_register"> REGISTER AS A PATIENT</a>'+
                    '<a href="'+fullUrl+'doctor_register">REGISTER AS A DOCTOR </a>'+
                    '</div>'+
                    '<div class="login-or-sec">'+
                      '<hr>'+
                        'OR'+
                        '<hr>'+
                    '</div>'+
                    '<div style="text-align: center;">'+
                        '<p>Already have account?</p>'+
                        '<a href="'+fullUrl+'login">Login</a>'+
                    '</div>';
                    $('.conclusions').html(html);
                    $('.next').addClass('d-none');
                    $('.previous').addClass('d-none');
                }else{
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
        if(flag){
            current_fs = $(this).parent();
            next_fs = $(this).parent().next();
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
            next_fs.show();
            current_fs.animate({ opacity: 0 }, {
                step: function (now) {
                    opacity = 1 - now;
                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    next_fs.css({ 'opacity': opacity });
                },
                duration: 500
            });
            setProgressBar(++current);
            flag = false;
        }
    });

    $(".previous").click(function () {

      current_fs = $(this).parent();
      previous_fs = $(this).parent().prev();

      //Remove class active
      $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

      //show the previous fieldset
      previous_fs.show();

      //hide the current fieldset with style
      current_fs.animate({ opacity: 0 }, {
        step: function (now) {
          // for making fielset appear animation
          opacity = 1 - now;

          current_fs.css({
            'display': 'none',
            'position': 'relative'
          });
          previous_fs.css({ 'opacity': opacity });
        },
        duration: 500
      });
      setProgressBar(--current);
    });
    $("#send_button").click(function(e){
        e.preventDefault();
        var answer = $('.chat_answer').val();
        var userImage = '{{ (auth()->check()) ? \App\Helper::check_bucket_files_url(auth()->user()->user_image) : "../../assets/images/no_image.png" }}';
        $('.chat__main__').append('<div class="text-end justify-content-lg-start right__user">'+
                    '<img class="right__user_img" height="30" width="30" src="' + userImage + '" alt="">'+
                    '<p class="left_p">'+answer+'</p></div>');
        $('.chat_answer').val('');
        $.ajax({
            type: "POST",
            url: "/symptom_chat",
            data: {
                message: answer,
                session_id: session_id,
            },
            beforeSend: function () {
                $(".loader").removeClass('d-none');
                // $('#acceptIcon_'+order_id).html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function (response) {
                $(".loader").addClass('d-none');
                $('.chat__main__').append('<div class="text-start right__user">'+
                    '<p class="right_p">'+response.response+'</p>'+
                    '<img class="right__user_img" height="30" width="30" src="../../assets/images/doc__.jpg" alt=""></div>');
                answer = $('.chat_answer').val('');
                questions++;
                session_id = response.session_id;
                $('.chat__main__').animate({ scrollTop: $('.chat__main__')[0].scrollHeight }, 'slow');
                $('.chat_answer').val('');
            },
            error: function (response){
            }
        });
        if(questions >= 3){
                $('.skip').removeClass('d-none');
            }
        if(questions >= 8){
            $(".message__div").html('');
            $(".message__div").html('Your Questions Limit has Completed!! Please click Next to view Conclusion.');
            $('.chat_next_button').removeClass('d-none');
        }
    })

    function setProgressBar(curStep) {
      var percent = parseFloat(100 / steps) * curStep;
      percent = percent.toFixed();
      $(".progress-bar")
        .css("width", percent + "%")
    }

    $(".submit").click(function () {
      return false;
    })
});
  $(document).ready(function () {
    $(document).on('click','.btn_finish', function(e){
        e.preventDefault();
        $.ajax({
            type: "get",
            url: "/forget_cookie",
            success: function (response) {
                if(response == 1){
                    $('#registar_open').modal('hide');
                } else{
                    alert('X');
                }
            }
        });
    });
    $(document).on('click','.btn-close', function(e){
        e.preventDefault();
        $.ajax({
            type: "get",
            url: "/forget_cookie",
            success: function (response) {
                if(response == 1){
                    $('#registar_open').modal('hide');
                } else{
                    alert('X');
                }
            }
        });
    });
  });
</script>
