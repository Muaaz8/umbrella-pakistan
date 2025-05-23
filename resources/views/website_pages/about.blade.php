@extends('layouts.new_web_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="language" content="en-us">
    <meta name="robots" content="index,follow" />
    <meta name="copyright" content="© 2022 All Rights Reserved. Powered By Community Healthcare Clinics">
    <meta name="url" content="https://www.communityhealthcareclinics.com">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.umbrellamd.com" />
    <meta property="og:site_name" content="Community Healthcare Clinics | communityhealthcareclinics.com" />
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
        <title>{{ $title->content }} | Umbrella Health Care Systems</title>
    @else
        <title>About Us | Umbrella Health Care Systems</title>
    @endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <section class="about-bg about-us-bg">
        <div class="container">
            <div class="row">
                <div class="back-arrow-about">
                    <!-- <i class="fa-solid fa-circle-arrow-left" onclick="history.back()"></i> -->
                    {{-- <i class="fa-solid fa-circle-arrow-left go-back" onclick="window.location=document.referrer;"></i> --}}
                    <h1>ABOUT US</h1>
                    <nav aria-label="breadcrumb">
                        <i class="fa-solid fa-arrow-left"></i>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">About Us</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    @php
        $page = DB::table('pages')->where('url', '/about-us')->first();
        $section = DB::table('section')
            ->where('page_id', $page->id)
            ->where('sequence_no', '1')
            ->first();
    @endphp
    <section class="about-sec-wrapper animatable fadeInDown">
        <div class="container">
            <div class="row my-5">
                <div class="col-md-12">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-6 about-inner-div">
                            <div class="about-img-wrap">
                                @php
                                    $image_content = DB::table('images_content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                <img src="{{ asset('assets/images/about-1.jpeg') }}" alt='{{ $image_content?$image_content->alt:'About us bottom' }}' />
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="about-content-wrap mt-3">
                                @php
                                    $top_content = DB::table('content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                @if ($top_content)
                                    {!! $top_content->content !!}
                                @else
                                    <p>WELCOME TO UMBRELLA HEALTH CARE SYSTEMS</p>
                                    <h3><strong>Telemedicine with latest technology</strong></h3>
                                    <p>Umbrella Health Care Systems is bringing hospital to your house. Physicians and
                                        patients can share information in real time from one computer screen to another.
                                        Using Umbrella Health Care Systems services, patients can see a doctor for diagnosis
                                        and treatment without having to actually go to clinic or hospital. Patients can
                                        consult a physician at the comfort of their home.</p>
                                    <p>Doctors are ready to help you get the care you need, anywhere in the United States.
                                        Access to doctors, psychiatrists, psychologists, therapists and other medical
                                        experts, care is available from 07:00 AM to 08:00 PM. Select and see your favorite
                                        providers again and again, right from your smartphone, tablet or computerasdasd.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-sec-wrapper-2 animatable fadeInUp">
        <div class="container">
            <div class="row my-5">
                <div class="col-md-12">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-5">
                            <div class="about-content-wrap-2 mb-3">
                                @php
                                    $section = DB::table('section')
                                    ->where('page_id', $page->id)
                                    ->where('sequence_no', '2')
                                    ->first();
                                    $bottom_content = DB::table('content')
                                    ->where('section_id', $section->id)
                                    ->first();
                                    @endphp
                                @if ($bottom_content)
                                    {!! $bottom_content->content !!}
                                @else
                                    <h6>HIGHEST QUALITY CARE</h6>
                                    <h3>Complete Medical Solutions in One Place</h3>
                                    <p>
                                    Umbrella Health Care Systems provide complete solution for
                                    health related problems.Umbrella E-Pharmacy contains variety
                                    of prescribed and over the counter medicines with efficient
                                    shipping services. Umbrella E-Lab Are State Of The Art Lab
                                    Services. Imaging, Substance Abuse and Dermatology services
                                    are also coming soon.
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 about-inner-div">
                            <div class="about-img-wrap-2">
                                @php
                                    $image_content = DB::table('images_content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                <img src="{{ asset('assets/images/about-2.jpeg') }}" alt='{{ $image_content?$image_content->alt:'About-us-top' }}' />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="counter-sec-wrapper animatable fadeInDown">
        <div class="container">

            <div class="row justify-content-around">

                <div class="four col-md-3">
                    <div class="counter-box">
                        <i class="fa-solid fa-heart-pulse"></i>
                        <span class="counter">2147</span>
                        <p>Happy Patients</p>
                    </div>
                </div>
                <div class="four col-md-3">
                    <div class="counter-box">
                        <i class="fa-solid fa-user-doctor"></i>
                        <span class="counter">3275</span>
                        <p>Qualified Doctors</p>
                    </div>
                </div>
                <div class="four col-md-3">
                    <div class="counter-box">
                        <i class="fa-solid fa-capsules"></i>
                        <span class="counter">289</span>
                        <p>Products In Pharmacy</p>
                    </div>
                </div>
                <!-- <div class="four col-md-3">
                  <div class="counter-box">
                      <i class="fa  fa-user"></i>
                      <span class="counter">1563</span>
                      <p>Saved Trees</p>
                  </div>
              </div> -->
            </div>
        </div>
    </section>
    <!-- ******* ABOUT ENDS ******** -->
@endsection
