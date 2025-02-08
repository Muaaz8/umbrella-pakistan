@extends('layouts.new_web_layout')

@section('meta_tags')
    <meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
    <meta charset="utf-8" />
    <meta name="p:domain_verify" content="d2ee0f53ab0b3c0147e662b58a2d26c3" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="language" content="en-us">
    <meta name="robots" content="index,follow" />
    <meta name="copyright" content="© {{ date('Y') }} All Rights Reserved. Powered By Community Healthcare Clinics">
    <meta name="url" content="https://www.communityhealthcareclinics.com">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.umbrellamd.com" />
    <meta property="og:site_name" content="Umbrellamd.com" />
    <meta name="twitter:site" content="@umbrellamd	">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="author" content="Umbrellamd">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    @foreach ($tags as $tag)
        <meta name="{{ $tag->name }}" content="{{ $tag->content }}">
    @endforeach
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    @if ($title != null)
        <title>{{ $title->content }} | Umbrellamd.com</title>
    @else
        <title>Umbrellamd.com</title>
    @endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script type="text/javascript">
        <?php
        header('Access-Control-Allow-Origin: *');
        ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            var array = $('#array').val();
            $('#banner1').html('');
            $('#banner2').html('');
            $('#banner3').html('');
            $.each(JSON.parse(array), function(key, arr) {
                if (arr.sequence == '1') {
                    if (arr.img == '' || arr.img == null) {
                        $('#banner1').html(arr.html);
                    } else {
                        $('#banner1').html('<img class="adds_img" ' +
                            'src="' + arr.img +
                            '" width="100%" height="100%" alt="Adds" loading="lazy">');
                    }
                } else if (arr.sequence == '2') {
                    if (arr.img == '' || arr.img == null) {
                        $('#banner2').html(arr.html);
                    } else {
                        $('#banner2').html('<img class="adds_img" ' +
                            'src="' + arr.img +
                            '" width="100%" height="100%" alt="Adds" loading="lazy">');
                    }
                } else if (arr.sequence == '3') {
                    if (arr.img == '' || arr.img == null) {
                        $('#banner3').html(arr.html);
                    } else {
                        $('#banner3').html('<img class="adds_img" ' +
                            'src="' + arr.img +
                            '" width="100%" height="100%" alt="Adds" loading="lazy">');
                    }
                }
            });
        });

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            var text = $(this).attr('href').split('?');
            var mode = text[1].split('=');
            var page = mode[1];
            mode = mode[0];
            if (mode == 'events') {
                $.ajax({
                    url: url,
                    success: function(data) {
                        $('#events').html(data);
                    }
                });
            } else if (mode == 'search') {
                var zip = $('#ev_search').val();
                if (zip != '') {
                    $.ajax({
                        url: url,
                        data: {
                            zip: zip,
                        },
                        success: function(data) {
                            $('#events').html(data);
                            $('#ev_search_btn').html('<i class="fa-solid fa-magnifying-glass"></i>');
                        }
                    });
                } else {
                    $.ajax({
                        url: "/?events=1",
                        success: function(data) {
                            $('#events').html(data);
                            $('#ev_search_btn').html('<i class="fa-solid fa-magnifying-glass"></i>');
                        }
                    });
                }
            }
        });

        $('#ev_search_btn').click(function() {
            $('#ev_search_btn').html('<i class="fa fa-spinner fa-spin"></i>');
            var zip = $('#ev_search').val();
            if (zip != '') {
                $.ajax({
                    url: "/therapy/events/search",
                    data: {
                        zip: zip,
                    },
                    success: function(data) {
                        $('#events').html(data);
                        $('#ev_search_btn').html('<i class="fa-solid fa-magnifying-glass"></i>');
                    }
                });
            } else {
                $.ajax({
                    url: "/?events=1",
                    success: function(data) {
                        $('#events').html(data);
                        $('#ev_search_btn').html('<i class="fa-solid fa-magnifying-glass"></i>');
                    }
                });
            }
        });
    </script>
    <script src="{{ asset('assets/js/home.js?n=1') }}"></script>
@endsection
@section('content')
    <!-- ******* SLIDER STARTS ******** -->
    <input id="array" type="hidden" value="{{ $banners }}">
    <div id="carouselExampleFade" class="carousel slide carousel-fade home-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators m-0">
            <div class="container">
                <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
        </div>
        <div class="carousel-inner">
            @php
                $page = DB::table('pages')->where('url', '/')->first();
                $section = DB::table('section')
                    ->where('page_id', $page->id)
                    ->where('section_name', 'slider1')
                    ->where('sequence_no', '1')
                    ->first();
                $top_content = DB::table('content')
                    ->where('section_id', $section->id)
                    ->get();
                $image_content = DB::table('images_content')
                    ->where('section_id', $section->id)
                    ->first();
            @endphp
            <div class="carousel-item active">
                <img src="{{ asset('assets/images/umbrellla_header1.jpg') }}" class="d-block w-100 carousel-desktop-img"
                    alt='{{ $image_content? $image_content->alt :'UMBRELLA HEALTH CARE SYSTEMS' }}' width="1350" height="555" />
                 <!-- <img
                                    src="{{ asset('assets/images/umbrellla_header1_1.jpg ') }}"
                                    class="d-block w-100 carousel-resp-img"
                                    alt="UMBRELLA HEALTH CARE SYSTEMS"
                                    width="auto"
                                    height="auto"
                                  /> -->
                <div class="container">
                    <div class="slider-content">
                        <!-- <img src="{{ asset('assets/images/umbrella_white.png ') }}" alt="UMBRELLA HEALTH CARE SYSTEMS" width="86"
                                    height="86" /> -->
                        @if ($top_content[0])
                            {!! $top_content[0]->content !!}
                        @else
                            <h1>UMBRELLA HEALTH CARE SYSTEMS</h1>
                            <p>
                                Umbrella Health Care Systems Is An Online Service To Assist You
                                With All Your Medical Needs.
                            </p>
                        @endif
                        <button onclick="window.location.href='{{ route('about_us') }}'"
                            title="To see all of our services">Read
                            More</button>
                    </div>
                </div>
                <div class="slider-box">
                    <div class="container">
                        <p>
                            @if ($top_content[1])
                                {!! $top_content[1]->content !!}
                            @else
                                <p>Umbrella Healthcare Systems offers easy access to high-quality primary care that
                                    isfocused on you, your needs, and your health. Choosing a primary care doctor and
                                    establishes a long-term connection with a committed Team of physicians who gets to know
                                    you as a person. Members have access to quality medical care and individualised support
                                    for managing chronic diseases and more complex challenges.</p>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @php
                $page = DB::table('pages')->where('url', '/')->first();
                $section = DB::table('section')
                    ->where('page_id', $page->id)
                    ->where('section_name', 'slider2')
                    ->first();
                $top_content = DB::table('content')
                    ->where('section_id', $section->id)
                    ->get();
                $image_content = DB::table('images_content')
                ->where('section_id', $section->id)
                ->first();
            @endphp
            <div class="carousel-item">
                <img src="{{ asset('assets/images/umbrella_header_pharmacy.jpg') }}"
                    class="d-block w-100 carousel-desktop-img" width="1350" height="555" alt='{{ $image_content?$image_content->alt:'UMBRELLA E-PHARMACY' }}' />
                <!-- <img src="{{ asset('assets/images/umbrella_header_pharmacy_1.jpg') }}" class="d-block w-100 carousel-resp-img"  width="auto" height="auto" alt="UMBRELLA E-PHARMACY"  /> -->
                <div class="container">
                    <div class="slider-content">
                        <img src="{{ asset('assets/images/med_capsul.png ') }}" alt="UMBRELLA E-PHARMACY" width="86"
                            height="86" />
                        @if ($top_content[0])
                            {!! $top_content[0]->content !!}
                        @else
                            <h2><strong>UMBRELLA E-PHARMACY</strong></h2>
                            <p>Patients Can Order From A Wide Range Of Products Including Prescription Drugs, Diagnostic
                                Equipment, And Over-The-Counter Medicines With Ease And Convenience From The Comfort Of
                                Their Homes</p>
                        @endif
                        <a href="{{ route('pharmacy') }}" title="Visit Store"> <button>Visit Store</button></a>
                    </div>
                </div>


                <div class="slider-box">
                    <div class="container">
                        @if ($top_content[1])
                            {!! $top_content[1]->content !!}
                        @else
                            <p>Umbrellamd is global premier digital healthcare platform that aims to revolutionize the
                                healthcare system. It connects patients with the right doctors. Patients can use Umbrellamd
                                (web or mobile app) for the online doctors appointment, e-consultation and online lab tests.
                                We provide medical services for people without insurance and high deductible at very
                                affordable price. Doctors are available from 07:00 am to 08:00 pm to assist you with all
                                your needs.</p>
                        @endif
                    </div>
                </div>
            </div>
            @php
                $page = DB::table('pages')->where('url', '/')->first();
                $section = DB::table('section')
                    ->where('page_id', $page->id)
                    ->where('section_name', 'slider3')
                    ->first();
                $top_content = DB::table('content')
                    ->where('section_id', $section->id)
                    ->get();
                $image_content = DB::table('images_content')
                    ->where('section_id', $section->id)
                    ->first();
            @endphp
            <div class="carousel-item">
                <img src="{{ asset('assets/images/umbrella_header_lab.jpg') }}"
                    class="d-block w-100 carousel-desktop-img" alt='{{ $image_content?$image_content->alt:'UMBRELLA HEALTH CARE SYSTEMS' }}' width="1350"
                    height="555" />
                <div class="container">
                    <div class="slider-content">
                        <img src="{{ asset('assets/images/lab_test_icon.png ') }}" width="86" height="86"
                            alt="UMBRELLA E-LABS" />
                        @if ($top_content[0])
                            {!! $top_content[0]->content !!}
                        @else
                            <h2>UMBRELLA E-LABS</h2>
                            <p>Umbrella Health Care Systems Medical Labs Are State Of The Art Lab Services , We Use Several
                                Reference Labs To Bring You Best Prices And Precision Lab Work, You Can Feel Free To Order
                                Certain Labtest Without Any Physician’s Referral, All Results Are Highly Confidential.</p>
                        @endif
                        <a href="{{ route('labs') }}" title="Visit Store"> <button>Visit Store</button></a>

                    </div>
                </div>

                <div class="slider-box">
                    <div class="container">
                        @if ($top_content[1])
                            {!! $top_content[1]->content !!}
                        @else
                            <p>Umbrellamd is global premier digital healthcare platform that aims to revolutionize the
                                healthcare system. It connects patients with the right doctors. Patients can use Umbrellamd
                                (web or mobile app) for the online doctors appointment, e-consultation and online lab tests.
                                We provide medical services for people without insurance and high deductible at very
                                affordable price. Doctors are available from 07:00 am to 08:00 pm to assist you with all
                                your needs.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ******* SLIDER ENDS ******** -->

    <!-- ******* STEPS STARTS ******** -->
    <section class="steps-wrapper">
        @if (Auth()->user())
        @else
            <div class="container">
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="text-center mb-4 heading-bg">
                                <h1>Steps For New User</h1>
                            </div>
                            <div class="col-lg-3 mb-5 col-sm-6">
                                <!-- <a data-bs-toggle="modal" data-bs-target="#loginModal" title="LOGIN | REGISTER"> -->
                                <div class="steps-inner-div" data-bs-toggle="modal" data-bs-target="#loginModal"
                                    title="LOGIN | REGISTER">
                                    <div class="">
                                        <img src="{{ asset('assets/images/step1.png ') }}" width="60" height="60"
                                            alt="LOGIN | REGISTER" />
                                        <h1>LOGIN | REGISTER</h1>
                                    </div>
                                    <div class="for-steps">
                                        <p>STEP 1</p>
                                    </div>
                                </div>
                                <!-- </a> -->
                            </div>

                            <div class="col-lg-3 mb-5 col-sm-6">
                                <!-- <a data-bs-toggle="modal" data-bs-target="#e-visitModal" title="E-VISIT | PHARMACY | LAB TEST"> -->
                                <div class="steps-inner-div" data-bs-toggle="modal" data-bs-target="#e-visitModal"
                                    title="E-VISIT | PHARMACY | LAB TEST">
                                    <div class="">
                                        <img src="{{ asset('assets/images/step2.png ') }}" width="60" height="60"
                                            alt="E-VISIT | PHARMACY" />
                                        <h1>E-VISIT | PHARMACY <br />LAB TEST</h1>
                                    </div>
                                    <div class="for-steps">
                                        <p>STEP 2</p>
                                    </div>
                                </div>
                                <!-- </a> -->
                            </div>

                            <div class="col-lg-3 mb-5 col-sm-6">
                                <a href="{{ route('login') }}" title="CONFIRM ORDER">
                                    <div class="steps-inner-div">
                                        <div class="">
                                            <img src="{{ asset('assets/images/step3.png ') }}" width="60"
                                                height="60" alt=">CONFIRM ORDER" />
                                            <h1>CONFIRM ORDER</h1>
                                        </div>
                                        <div class="for-steps">
                                            <p>STEP 3</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-3 mb-5 col-sm-6">
                                <a href="{{ route('login') }}" title="CHECKOUT">
                                    <div class="steps-inner-div">
                                        <div class="">
                                            <img src="{{ asset('assets/images/step4.png ') }}" width="60"
                                                height="60" alt="CHECKOUT" />
                                            <h1>CHECKOUT</h1>
                                        </div>
                                        <div class="for-steps">
                                            <p>STEP 4</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
    <!-- ******* STEPS ENDS ******** -->
    @if (session()->get('error_message'))
        <div id="errorDiv1" class="alert alert-danger col-12 col-md-6 offset-md-3 mt-2 text-center">
            @php
                $es = session()->get('error_message');
            @endphp
            <span role="alert"> <strong>{{ $es }}</strong></span>

        </div>
    @endif
    <section class="mt-3">
        <div class="container" style="cursor:pointer;" id="banner1"
            onclick="window.location.href='/add_discount_in_cart/cbc---complete-blood-count-h-h-rbc-indices-wbc-plt'">
            <!-- <img class="adds_img" src="{{ asset('assets/images/ad_banner.jpg') }}" width="100%" height="100%" alt="Adds"
                              loading="lazy"> -->
        </div>
    </section>

    <!-- ******* TABS STARTS ******** -->
    <section class="tabs-wrapper">
        <div class="container pt-3">
            <ul class="nav nav-pills mb-3 col-12" id="pills-tab" role="tablist">
                <li class="nav-item col-6 col-sm-4 col-md-3   p-1" role="presentation">
                    <button class="nav-link active" id="pills-evisit-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-evisit" type="button" role="tab" aria-controls="pills-evisit"
                        aria-selected="true">
                        <a href="#pills-evisit-tab">
                            E-VISIT
                        </a>
                    </button>
                </li>
                <li class="nav-item col-6 col-sm-4 col-md-3   p-1" role="presentation">
                    <button class="nav-link pharmacy-penal" id="pills-pharmacy-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-pharmacy" type="button" role="tab" aria-controls="pills-pharmacy"
                        aria-selected="false">
                        <a href="#pills-pharmacy-tab">
                            PHARMACY
                        </a>
                    </button>
                </li>
                <li class="nav-item col-6 col-sm-4 col-md-3   p-1" role="presentation">
                    <button class="nav-link labtest-penal" id="pills-labtest-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-labtest" type="button" role="tab" aria-controls="pills-labtest"
                        aria-selected="false">
                        <a href="#pills-labtest-tab">
                            LABTESTS
                        </a>
                    </button>
                </li>
                <li class="nav-item col-6 col-sm-4 col-md-3   p-1" role="presentation">
                    <button class="nav-link imaging-penal" id="pills-imaging-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-imaging" type="button" role="tab" aria-controls="pills-imaging"
                        aria-selected="false">
                        <a href="#pills-labtest-tab">
                            IMAGING
                        </a>
                    </button>
                </li>
                <li class="nav-item col-6 col-sm-4 col-md-3   p-1" role="presentation">
                    <button class="nav-link" id="pills-primaryCare-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-primaryCare" type="button" role="tab"
                        aria-controls="pills-primaryCare" aria-selected="false">
                        <a href="#pills-primaryCare-tab">
                            PRIMARY CARE
                        </a>
                    </button>
                </li>
                <li class="nav-item col-6 col-sm-4 col-md-3   p-1" role="presentation">
                    <button class="nav-link" id="pills-psychaitrist-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-psychaitrist" type="button" role="tab"
                        aria-controls="pills-psychaitrist" aria-selected="false">
                        <a href="#pills-psychaitrist-tab">
                            PSYCHIATRY
                        </a>
                    </button>
                </li>
                <li class="nav-item col-6 col-sm-4 col-md-3   p-1" role="presentation">
                    <button class="nav-link" id="pills-painManagement-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-painManagement" type="button" role="tab"
                        aria-controls="pills-painManagement" aria-selected="false">
                        <a href="#pills-painManagement-tab">
                            PAIN MANAGEMENT
                        </a>
                    </button>
                </li>
                <li class="nav-item col-6 col-sm-4 col-md-3   p-1" role="presentation">
                    <button class="nav-link" id="pills-substance-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-substance" type="button" role="tab" aria-controls="pills-substance"
                        aria-selected="false">
                        <a href="#pills-painManagement-tab">
                            SUBSTANCE ABUSE
                        </a>
                    </button>
                </li>
                <li class="nav-item col-6 col-sm-4 col-md-6 m-auto   p-1" role="presentation">
                    <!-- <button class="nav-link" id="pills-ther-sess-tab" data-bs-toggle="pill" data-bs-target="#pills-ther-sess"
                                  type="button" role="tab" aria-controls="pills-ther-sess" aria-selected="false"
                                  style="background-color:#ff2a2a; color:white">
                                  <a style="color:#fff !important" href="#pills-painManagement-tab">
                                    Join Group Therapy Session
                                  </a>
                                </button> -->
                </li>
            </ul>
        </div>
        <div class="container my-3">
            <div class="row">
                <div class="tab-content" id="pills-tabContent">

                    <div class="tab-pane fade show active" id="pills-evisit" role="tabpanel"
                        aria-labelledby="pills-evisit-tab">
                        <div class="col-md-12">
                            <div class="row justify-content-between">
                                <div class="col-md-5 tab-col">
                                    <div class="tabs-content">
                                        @php
                                            $section = DB::table('section')
                                                ->where('page_id', $page->id)
                                                ->where('section_name', 'e-visit')
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
                                            <h1>E-VISIT</h1>
                                            <p>Umbrella Health Care Systems provide you with facility to visit doctors,
                                                therapist, or medical
                                                expert online. Find best Doctors to get instant medical advice for your
                                                health problems.
                                                Ask the doctors online and consult them on face-to-face video chat and get
                                                solution to your medical
                                                problems from home.</p>
                                        @endif
                                        @if (Auth::check())
                                            @if (Auth::User()->user_type == 'patient')
                                                <button
                                                    onclick="location.href='{{ route('patient_evisit_specialization') }}'">TALK
                                                    TO
                                                    DOCTORS</button>
                                            @elseif(Auth::User()->user_type == 'doctor')
                                                <button onclick="location.href='{{ route('doctor_queue') }}'">GO TO
                                                    WAITING ROOM</button>
                                            @else
                                                <button>OUR DOCTORS</button>
                                            @endif
                                        @else
                                            <span data-bs-toggle="modal" data-bs-target="#loginModal">
                                                <button>TALK TO DOCTORS</button>
                                            </span>
                                        @endif
                                        <!-- <a href="{{ route('pat_register') }}"><button>TALK TO DOCTORS</button></a> -->
                                    </div>
                                </div>
                                <div class="col-md-6 tabs-img video-div">
                                    <button type="button" class="btn btn-primary video-btn" data-bs-toggle="modal"
                                        href="#video-modal" role="button" title="UMBRELLA-HEALTH-CARE-SYSTEMS-VIDEO">
                                        <a class="play-btn" href="#"
                                            title="UMBRELLA-HEALTH-CARE-SYSTEMS-VIDEO"></a>
                                    </button>
                                    <img src="{{ asset('assets/images/image-1.jpg') }}" width="100%" height="100%"
                                        alt='{{ $image_content?$image_content->alt:'umbrella-E-Visit' }}' loading="lazy">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-pharmacy" role="tabpanel" aria-labelledby="pills-pharmacy-tab">
                        <div class="col-md-12">
                            <div class="tabs-content">
                                <div class="row">
                                    <div class="pb-3">
                                        @php
                                            $section = DB::table('section')
                                                ->where('page_id', $page->id)
                                                ->where('section_name', 'pharmacy')
                                                ->first();
                                            $top_content = DB::table('content')
                                                ->where('section_id', $section->id)
                                                ->first();
                                        @endphp
                                        @if ($top_content)
                                            {!! $top_content->content !!}
                                        @else
                                            <h2><strong>Umbrella Health Care Systems - Pharmacy</strong></h2>
                                            <p>Our Pharmacy Offers prescription drugs at discounted prices</p>
                                        @endif
                                    </div>
                                    @foreach ($data['prescribed_medicines_category'] as $key => $item)
                                        <div class="col-lg-2 col-sm-4 col-6 mb-2 labsbutton">
                                            <button class="grow_ellipse"
                                                onclick="getPharmacyProductByCategory({{ $item->id }},8)"><i
                                                    class="fa-solid fa-capsules"></i>{{ $item->title }}</button>
                                        </div>
                                    @endforeach
                                    <div class="col-lg-2 col-sm-4 col-6 mb-2 view-more-btn">
                                        <button class="grow_ellipse"
                                            onclick="location.href='{{ route('pharmacy') }}'">View More</button>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="input-group pharmacy-search">
                                            <input type="text" id="pharmacySearchText" class="form-control"
                                                placeholder="Search what you are looking for"
                                                aria-label="Recipient's username">
                                            <span class="input-group-text searchPharmacyProduct"><i
                                                    class="fa-solid fa-magnifying-glass"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-11 m-auto">
                                        <div class="col-md-12 col-sm-12 col-11 m-auto">
                                            <div class="row" id="load_pharmacy_item_by_category">

                                            </div>
                                        </div>
                                        <div class="text-center mt-5 prescription-req-view-btn"
                                            id="load_pharmacy_more_btn">
                                            <a href="{{ route('pharmacy') }}" title="View More"> <button>View More
                                                </button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-labtest" role="tabpanel" aria-labelledby="pills-labtest-tab">
                        <div class="row mb-3 align-items-baseline b-bottom">
                            <div class="tabs-content pb-3">
                                @php
                                    $section = DB::table('section')
                                        ->where('page_id', $page->id)
                                        ->where('section_name', 'lab-test')
                                        ->first();
                                    $top_content = DB::table('content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                @if ($top_content)
                                    {!! $top_content->content !!}
                                @else
                                    <h2><strong>Umbrella Health Care Systems - Online Labtests</strong></h2>
                                    <p>Umbrella Health Care Systems medical labs are state of the art lab services ,
                                        we several reference labs to bring you best price and precise lab work.</p>
                                @endif
                            </div>
                            @foreach ($data['labtest_category'] as $key => $item)
                                <div class="col-lg-2 col-sm-4 col-6 mb-2 labsbutton">
                                    <button class="grow_ellipse"
                                        onclick="getLabtestProductByCategory({{ $item->id }},8)">
                                        <img src="{{ asset('assets/images/' . $item->thumbnail) }}" alt="Labtests"
                                            loading="lazy" />
                                        {{ $item->product_parent_category }}
                                    </button>
                                </div>
                            @endforeach

                            <div class="col-lg-2 col-sm-4 col-6 mb-2 view-more-btn">
                                <button class="grow_ellipse elip_pad"
                                    onclick="window.location.href='{{ route('labs') }}'" title="View More">View
                                    More</button>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="input-group pharmacy-search">
                                    <input id="labSearchText" type="text" class="form-control"
                                        placeholder="Search what you are looking for" aria-label="Recipient's username" />
                                    <span class="input-group-text labSearchBtn"><i
                                            class="fa-solid fa-magnifying-glass"></i></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-8">
                                <div class="text-hover mb-5 hover-text-new hover-text-new-home">
                                    {{--All lab tests include $6 Physician's fee.
                                    <i class="fa-solid fa-circle-info"></i>--}}
                                    <div class="tooltip">$6 fee is collected on behalf of affiliated physicians oversight
                                        for lab testing,
                                        lab results may require physicians follow-up services, UmbrellaMD will collect this
                                        fee for each order
                                        and it&apos;s non-refundable</div>
                                </div>
                            </div>
                        </div>
                        <h4 class="text-center pt-3 pb-5">Most Popular Labtests</h4>

                        <div class="row" id="load_labtest_item_by_category">
                            <h1>No Product Found</h1>
                        </div>

                        <div class="text-center mt-5 prescription-req-view-btn">
                            <a href="{{ route('labs') }}" title="View More"> <button>View More </button></a>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-imaging" role="tabpanel" aria-labelledby="pills-imaging-tab">
                        <div class="row">
                            <div class="tabs-content mb-3">
                                @php
                                    $section = DB::table('section')
                                        ->where('page_id', $page->id)
                                        ->where('section_name', 'imaging')
                                        ->first();
                                    $top_content = DB::table('content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                @if ($top_content)
                                    {!! $top_content->content !!}
                                @else
                                    <h2><strong>Umbrella Health Care Systems - Medical Imaging Services</strong></h2>
                                    <p>Umbrella Health Care Systems provides imaging services as well, you can find
                                        different MRI,
                                        CT scan, Ultrasound, and X-Ray services here.</p>
                                @endif
                            </div>
                            @foreach ($data['imaging_category'] as $key => $item)
                                <div class="col-lg-2 col-sm-4 col-6 mb-2 labsbutton">
                                    <button class="grow_ellipse"
                                        onclick="getImagingProductByCategory({{ $item->id }},6)">
                                        <img src="{{ asset('assets/images/' . $item->thumbnail) }}"
                                            alt="Medical Imaging Services" loading="lazy" />
                                        {{ $item->product_parent_category }}
                                    </button>
                                </div>
                            @endforeach
                            <div class="col-lg-2 col-sm-4 col-6 mb-2 view-more-btn">
                                <button class="grow_ellipse" onclick="window.location.href='{{ route('imaging') }}'"
                                    title="View More">View More</button>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="input-group pharmacy-search">
                                    <input id="imagingSearchText" type="text" class="form-control"
                                        placeholder="Search what you are looking for"
                                        aria-label="Recipient&apos;s username" />
                                    <span class="input-group-text imagingSearchBtn"><i
                                            class="fa-solid fa-magnifying-glass"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4" id="load_imaging_item_by_category">
                        </div>
                        <div class="text-center mt-5 prescription-req-view-btn" id="load_more_btn_imaging">
                            <a href="{{ route('imaging') }}" title="View More"> <button>View More </button></a>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-painManagement" role="tabpanel"
                        aria-labelledby="pills-painManagement-tab">
                        <div class="row">
                            <div class="tabs-content mb-3">
                                @php
                                    $section = DB::table('section')
                                        ->where('page_id', $page->id)
                                        ->where('section_name', 'pain-management')
                                        ->first();
                                    $top_content = DB::table('content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                @if ($top_content)
                                    {!! $top_content->content !!}
                                @else
                                    <h2><strong>Umbrella Health Care Systems - Pain Management</strong></h2>
                                    <p>If pain becomes chronic, it can disrupt your life and affect the people you love and
                                        care about.
                                        Our doctors at Umbrella Health Care Systems offers online pain management,
                                        nonnarcotic treatments
                                        that minimize the effects of pain on your life. We have physicians who specialize in
                                        managing and treating chronic pain.</p>
                                @endif
                            </div>

                        </div>
                        <div class="row">
                            @foreach ($data['pain_categories'] as $key => $item)
                                <div class="col-lg-3 col-sm-4 col-6 mb-2 substanceAbuse-btn-home">
                                    <button class="grow_ellipse"
                                        onclick="window.location.href='{{ route('pain.management') }}'">
                                        <img src="{{ asset('assets/images/' . $item->thumbnail) }}" alt="Pain Management"
                                            loading="lazy" />{{ $item->title }}
                                    </button>
                                </div>
                            @endforeach
                            <div class="text-center mt-5 substance-read-btn">
                                <a href="{{ route('pain.management') }}" title="Learn More Services"><button>Learn More
                                        Services</button></a>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-substance" role="tabpanel"
                        aria-labelledby="pills-substance-tab">
                        <div class="row">
                            <div class="tabs-content mb-3">
                                @php
                                    $section = DB::table('section')
                                        ->where('page_id', $page->id)
                                        ->where('section_name', 'substance-abuse')
                                        ->first();
                                    $top_content = DB::table('content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                @if ($top_content)
                                    {!! $top_content->content !!}
                                @else
                                    <h2><strong>Umbrella Health Care Systems - Substance Abuse</strong></h2>
                                    <p>Umbrella Health Care Systems provide best quality psychiatric services and
                                        consultations to all age groups.We are a staff of professionals committed to
                                        helping patients through all stages of their lives. We see children, adolescent,
                                        general adults, and older adults. Explore our site to learn about our services,
                                        useful resources on various health topics, our contact information, and how to
                                        prepare
                                        for your first visit.</p>
                                @endif
                            </div>
                            @foreach ($data['substance_categories'] as $key => $item)
                                <div class="col-lg-2 col-sm-4 col-6 mb-2 substanceAbuse-btn-home">
                                    <button class="grow_ellipse"
                                        onclick="location.href='{{ route('substance', ['slug' => $item->slug]) }}'">
                                        <img src="{{ asset('assets/images/' . $item->thumbnail) }}" alt="Substance Abuse"
                                            loading="lazy" /><span>{{ $item->title }}</span>
                                    </button>
                                </div>
                            @endforeach
                            <div class="text-center mt-5 substance-read-btn">
                                <a href="{{ route('substance', ['slug' => 'first-visit']) }}"><button>Read About Health
                                        Issue</button></a>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-primaryCare" role="tabpanel"
                        aria-labelledby="pills-primaryCare-tab">
                        <div class="row">
                            <div class="tabs-content mb-3">
                                @php
                                    $section = DB::table('section')
                                        ->where('page_id', $page->id)
                                        ->where('section_name', 'primary-care')
                                        ->first();
                                    $top_content = DB::table('content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                @if ($top_content)
                                    {!! $top_content->content !!}
                                @else
                                    <h2><strong>Umbrella Health Care Systems - Primary Care</strong></h2>
                                    <p>Umbrella Healthcare Systems offers easy access to high-quality primary care
                                        that is focused on you, your needs, and your health. Choosing a primary care
                                        doctor and establishing a long-term connection with a committed Care Team who
                                        gets to know you as a person. Members have access to quality medical care and
                                        individualised support for managing chronic diseases and more complex challenges.
                                    </p>
                                @endif
                                <div class="text-center mt-5 substance-read-btn">
                                    <a href="{{ route('primary') }}"><button>Read More About Primary Care</button></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-psychaitrist" role="tabpanel"
                        aria-labelledby="pills-psychaitrist-tab">
                        <div class="row">
                            <div class="tabs-content mb-3">
                                @php
                                    $section = DB::table('section')
                                        ->where('page_id', $page->id)
                                        ->where('section_name', 'psychiatry')
                                        ->first();
                                    $top_content = DB::table('content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                @if ($top_content)
                                    {!! $top_content->content !!}
                                @else
                                    <h2><strong>Umbrella Health Care Systems - PSYCHIATRY</strong></h2>
                                    <p>Getting the support you need has never been simpler thanks to Umbrella Health Care
                                        System’s
                                        skilled team of psychiatrists, who are known for offering their patients
                                        compassionate,
                                        holistic care for a wide range of psychiatric challenges at convenience in the palm
                                        of your hand.</p>
                                @endif
                            </div>
                            @foreach ($data['psychiatrist'] as $key => $item)
                                {{-- <div class="col-lg-2 col-sm-4 col-6 mb-2 substanceAbuse-btn-home">
              <button class="grow_ellipse" onclick="location.href='{{ route('substance',['slug'=>$item->slug]) }}'">
                <img src="{{ asset('assets/images/'.$item->thumbnail)}}" alt="PSYCHIATRY" loading="lazy" /><span>{{
                  $item->title }}</span>
              </button>
            </div> --}}
                                <div class="col-lg-3 col-sm-6 mb-2 labsbutton">
                                    <button class="grow_ellipse"
                                        onclick="location.href='{{ route('psychiatry', ['slug' => $item->slug]) }}'">
                                        <img src="{{ asset('assets/images/' . $item->thumbnail) }}" alt="PSYCHIATRY"
                                            loading="lazy" />
                                        <span class="m-auto">{{ $item->title }}</span>
                                    </button>
                                </div>
                            @endforeach
                            @if (isset($data['psychiatrist']))
                                <div class="text-center mt-5 substance-read-btn">
                                    <a href="{{ route('psychiatry', ['slug' => 'anxiety']) }}"><button>Read More About
                                            Psychiatry </button></a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-ther-sess" role="tabpanel"
                        aria-labelledby="pills-ther-sess-tab">
                        <div class="col-md-12">
                            <div class="tabs-content">
                                <div class="row">
                                    <div class="pb-3">
                                        <h1>Umbrella Health Care Systems - Therapy Session</h1>
                                        <p>Group therapy can help people improve their mental health. It involves at least
                                            one mental health
                                            professional and two or more people in therapy. Many use it to address a
                                            specific mental health
                                            concern. The group dynamic often helps people feel supported as they move
                                            forward. Whether your goal
                                            is growth, improving social skills, or something else, group therapy could help
                                            you achieve it.</p>
                                    </div>
                                    @foreach ($data['therapy'] as $key => $item)
                                        <div class="col-lg-2 col-sm-4 col-6 labsbutton">
                                            <button class="grow_ellipse"
                                                onclick="window.location.href='/therapy-session/{{ $item->slug }}'">
                                                {{ Str::limit($item->title, 15) }}</button>
                                        </div>
                                    @endforeach
                                    <div class="mt-2 enr__regs_">
                                        <button onclick="window.location.href='/therapy-session/view'"
                                            class="btn process-pay">View More</button>
                                    </div>

                                </div>
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="input-group pharmacy-search">
                                        <input type="text" id="ev_search" class="form-control"
                                            placeholder="Enter Zip Code" aria-label="Recipient's username">
                                        <span id="ev_search_btn" class="input-group-text"><i
                                                class="fa-solid fa-magnifying-glass"></i></span>
                                    </div>
                                </div>
                                <!-- -----start-new-card---- -->
                                <section class="mt-3" id="events">
                                    <div class="row">
                                        @if (count($therapy_events) != 0)
                                            <h4 class="py-2 text-danger">Upcoming Events</h4>
                                        @endif
                                        @forelse($therapy_events as $te)
                                            <div class="col-lg-4 col-md-6 mb-4">
                                                <div class="card__Mains position-relative">
                                                    <div class="location_tag">
                                                        <!-- <img src="{{ asset('assets/images/Group_19.png') }}" alt=""> -->
                                                        <span class="gr_ther_ss">Group Therapy</span>
                                                    </div>
                                                    <div class="row align-items-center">
                                                        <div class="col-lg-5">
                                                            <div class="doc_intro__ ">
                                                                <div class="card_imgs">
                                                                    <img src="{{ $te->doc_img }}" alt="">
                                                                </div>
                                                                <p class="p1">Dr.{{ $te->doc_name }}</p>
                                                                {{-- <pclass="p2">Ph.D.LCSW</p> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-7">
                                                            <div class="doc_Main_m px-2">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="day_color"><span
                                                                            class="w_cap">{{ substr($te->day, 0, 1) }}</span><span
                                                                            class="day_color_nes">{{ substr($te->day, 1, strlen($te->day)) }}</span>
                                                                    </span>
                                                                    <div class="main_day">
                                                                        <span
                                                                            class="date__s">{{ explode('-', $te->date)[1] }}</span>
                                                                        <p class="month__year_">
                                                                            {{ explode('-', $te->date)[0] . ' ' . explode('-', $te->date)[2] }}
                                                                        </p>
                                                                    </div>

                                                                </div>
                                                                <div class="text-center">
                                                                    <!-- <p class="time__cst">{{ $te->start_time }}</p> -->
                                                                    <div style="height:50px">

                                                                        @if (isset($te->short_des->help))
                                                                            <p class="docc__bio">{!! $te->short_des->help !!}
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="mt-2 enr__regs_">
                                                                        @if (!Auth()->user())
                                                                            <button data-bs-toggle="modal"
                                                                                data-bs-target="#therapy_login"
                                                                                class="btn doc__btns">Login/Register</button>
                                                                        @elseif(Auth()->user()->user_type == 'patient')
                                                                            <button
                                                                                onclick="window.location.href='/therapy/event/payment/{{ $te->session_id }}'"
                                                                                class="btn doc__btns">Get Enrolled</button>
                                                                        @endif
                                                                        <button
                                                                            onclick="window.location.href='/view/psychiatrist/{{ $te->event_id }}'"
                                                                            class="btn doc__btns">View Details</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <h4 class="py-2 text-danger">No Upcoming Events</h4>
                                        @endforelse
                                        {{ $therapy_events->links('pagination::bootstrap-4') }}
                                    </div>
                                </section>
                                <!-- -----start-new-card---- -->
                            </div>
                        </div>
                    </div>

                </div>
    </section>
    <!-- ******* TABS ENDS ******** -->

    <section>
        <div class="container my-3">
            <section>
                <div class="container my-4" id="banner2">
                    <!-- <img class="adds_img" src="{{ asset('assets/images/ad_banner.jpg') }}" width="100%" height="100%" alt="Adds"
                                  loading="lazy"> -->
                </div>
                <h1>Solutions to Complex Medical Problems</h1>
                Talk to a doctor, therapist, or medical expert anywhere you are
                common medical issues, as well as telebehavioral health services
                for emotional and mental health concerns. We leverage the latest
                technology to simplify and personalize both the organization&apos;s
                and the member&apos;s experience. Our dedication to clinical
                excellence ensures that you have a safe and secure consultation
        </div>
        </div>
    </section>

    <section>
        <div class=" my-4">
            <section>
                <div class="container my-4" id="banner3">
                    <!-- <img class="adds_img" src="{{ asset('assets/images/ad_banner.jpg') }}" width="100%" height="100%" alt="Adds"
                                  loading="lazy"> -->
                </div>
            </section>

            <section class="problem-wrapper care-sec ">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="text-center my-3 home-headings heading-bg">
                            @php
                                $section = DB::table('section')
                                    ->where('page_id', $page->id)
                                    ->where('section_name', 'last-section-header')
                                    ->first();
                                $top_content = DB::table('content')
                                    ->where('section_id', $section->id)
                                    ->first();
                                @endphp
                            @if ($top_content)
                                {!! $top_content->content !!}
                            @else
                                <h2><strong>Umbrella Health Care Systems is the Best Health Care Website</strong></h2>
                            @endif
                        </div>
                        @php
                            $section = DB::table('section')
                                ->where('page_id', $page->id)
                                ->where('section_name', 'last-section-description')
                                ->first();
                            $top_content = DB::table('content')
                                ->where('section_id', $section->id)
                                ->first();
                            $image_content = DB::table('images_content')
                                ->where('section_id', $section->id)
                                ->first();
                        @endphp
                        <div class="col-md-6">
                            <div class="">
                                <img src="{{ asset('assets/images/happy-doc.png ') }}" width="100%" height="100%"
                                    alt='{{ $image_content?$image_content->alt:'Happy Client' }}' loading="lazy" />
                            </div>
                        </div>

                        <div class="col-md-6 prblem-wrapper-inner-div mt-3">
                            <div>
                                @if ($top_content)
                                    {!! $top_content->content !!}
                                @else
                                    <p>Get started now! Doctors are ready to help you get the care you need,
                                        anywhere and anytime in the United States. Access to doctors, psychiatrists,
                                        psychologists, therapists and other medical experts, care is available from 07:00 AM
                                        to 08:00 PM. Select and see your favorite providers again and again, right from your
                                        smartphone,
                                        tablet or computer.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ******* MEDICAL-SPECIALIST STARTS ******** -->
            {{-- <section class="medical-specialist-sec animatable fadeInUp ">
      <div class="container">
        <div class="row pb-5">
          <div class="text-center mb-5 mt-2 heading-bg">
            <h1>Our Medical Specialists</h1>
          </div>
          @foreach ($doctors as $doctor)

          <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card">
              <div class="face front-face">
                <img src="{{$doctor->user_image}}" alt="Our Medical Specialists" class="profile" loading="lazy" />

                <div class="pt-3 text-uppercase name">Dr. {{ $doctor->name . ' ' . $doctor->last_name }}</div>
                <div class="designation">General</div>
              </div>
              <div class="face back-face">
                <span class="fas fa-quote-left"></span>
                <div class="testimonial text-break">
                  {{ $doctor->bio ?? ''}}
                </div>
                <span class="fas fa-quote-right"></span>
              </div>
            </div>
          </div>

          @endforeach

        </div>
        <div class="text-center">
          <a href="{{ route('our_doctors') }}"><button>Meet All Doctors</button></a>
        </div>
      </div>
    </section> --}}
            <!-- ******* MEDICAL-SPECIALIST ENDS ******** -->

            <!-- *******PARTNERS SECTION STARTS ******** -->
            <!-- <section class="partner-sec  animatable fadeInDown">
                              <div class="container">
                                <div class="row partners-wrapper justify-content-between">
                                  <div class="col-md-3 col-sm-4 ">
                                    <div class="partners-div">
                                      <img src="{{ asset('assets/images/partner (1).svg') }}" alt="">
                                    </div>
                                  </div>
                                  <div class="col-md-3 col-sm-4 ">
                                    <div class="partners-div">
                                      <img src="{{ asset('assets/images/partner(2).png ') }}" alt="" style="width: 55%;">
                                    </div>
                                  </div>
                                  <div class="col-md-3 col-sm-4 ">
                                    <div class="partners-div">
                                      <img src="{{ asset('assets/images/partner(3).png ') }}" alt="">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </section> -->
            <!-- *******PARTNERS SECTION ENDS ******** -->



            {{-- <div class="container-fluid">
      <div class="row">

        <div class="col-md-2">
          <div class="text-center">
            <a class="btn btn-info notification">Info</a>
          </div>
        </div>
      </div>
    </div> --}}



            <!-- =============== Calling Modal Start ==========================  -->
            <!-- Button trigger modal -->




            <!-- =============== Calling Modal Ends ==========================  -->






            <!-- ******* LOGIN-REGISTER-MODAL STARTS ******** -->
            <!-- Modal -->
            <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Registration Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-login-reg-btn my-3">
                                <a href="{{ route('pat_register') }}"> REGISTER AS A PATIENT</a>
                                <a href="{{ route('doc_register') }}">REGISTER AS A DOCTOR </a>
                            </div>
                            <div class="login-or-sec">
                                <hr />
                                OR
                                <hr />
                            </div>
                            <div>
                                <p>Already have account?</p>
                                <a href="{{ route('login') }}">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ******* LOGIN-REGISTER-MODAL ENDS ******** -->

            <!-- ******* E-VISIT-MODAL STARTS ******** -->
            <div class="modal fade" id="e-visitModal" tabindex="-1" aria-labelledby="e-visitModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-e-visit-btn my-3">
                                <div>
                                    <a href="{{ route('e-visit') }}"><button>E-VISIT</button></a>
                                </div>
                                <div>
                                    <a href="{{ route('pharmacy') }}"> <button>PHARMACY </button></a>
                                </div>
                                <div>
                                    <a href="{{ route('labs') }}"> <button>LAB TESTS </button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ******* E-VISIT-MODAL ENDS ******** -->

            <div class="container">
                <!-- Button trigger modal -->

                <!-- Modal -->
                <div class="modal fade" id="video-modal" aria-hidden="true" data-bs-backdrop="static"
                    aria-labelledby="video-modalLabel" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">How E-Visit Works.</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0">
                                <!-- 16:9 aspect ratio -->
                                <div class="embed-responsive embed-responsive-16by9" id="yt-player">
                                    <iframe title="UHCS-Video"
                                        srcdoc="
                  <style>
                      body, .full {
                          width: 100%;
                          height: 100%;
                          margin: 0;
                          position: absolute;
                          display: flex;
                          justify-content: center;
                          object-fit: cover;
                      }
                  </style>
                  <a href='https://www.youtube.com/embed/Sh85ZmXNIXM?autoplay=1' class='full'>
                      <img src='https://vumbnail.com/Sh85ZmXNIXM.jpg' class='full'/>
                      <svg
                          version='1.1'
                          viewBox='0 0 68 48'
                          width='68px'
                          style='position: relative;'>
                          <path d='M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z' fill='#f00'></path>
                          <path d='M 45,24 27,14 27,34' fill='#fff'></path>
                      </svg>
                  </a>"
                                        style="max-width: 640px; width: 100%; aspect-ratio: 16/9;" frameborder="0">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal -->
            <div class="modal fade cart-modal" id="afterLogin" tabindex="-1" aria-labelledby="afterLoginLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="afterLoginLabel">Item Added</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="custom-modal">
                                <div class="succes succes-animation icon-top"><i class="fa fa-check"></i></div>
                                <div class="content">
                                    <p class="type">Item Added</p>
                                    <div class="modal-login-reg-btn"><button data-bs-dismiss="modal" aria-label="Close">
                                            Continue Shopping
                                        </button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade cart-modal" id="alreadyadded" tabindex="-1" aria-labelledby="alreadyaddedLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="alreadyaddedLabel">Item Not Added</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="custom-modal">
                                <div class="succes succes-animation icon-top"><i class="fa fa-times"></i></div>
                                <div class="content">
                                    <p class="type">Item Is Already in Cart</p>
                                    <div class="modal-login-reg-btn"><button data-bs-dismiss="modal" aria-label="Close">
                                            Continue Shopping
                                        </button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Modal -->
            <div class="modal fade cart-modal" id="beforeLogin" tabindex="-1" aria-labelledby="beforeLoginLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="beforeLoginLabel">Not Logged In</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="custom-modal">
                                <div class="icon-top"><i class="fa fa-times"></i></div>
                                <div class="content">
                                    <p class="type">Please login to add into cart</p>
                                    <div class="modal-login-reg-btn">
                                        <a href="{{ route('login') }}"> Login </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ******* LABTEST ENDS ******** -->

            <!-- Modal -->
            <div class="modal fade" id="therapy_login" tabindex="-1" aria-labelledby="therapy_loginModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Register To Get Enrolled</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-login-reg-btn my-3">
                                <a href="{{ route('pat_register') }}"> REGISTER AS A PATIENT</a>
                            </div>
                            <div class="login-or-sec">
                                <hr />
                                OR
                                <hr />
                            </div>
                            <div>
                                <p>Already have account?</p>
                                <a href="{{ route('login') }}">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endsection
