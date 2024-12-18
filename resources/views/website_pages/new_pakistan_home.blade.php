@extends('layouts.new_pakistan_layout')

@section('meta_tags')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="copyright" content="© {{ date('Y') }} All Rights Reserved. Powered By UmbrellaMd">
@foreach ($tags as $tag)
<meta name="{{ $tag->name }}" content="{{ $tag->content }}">
@endforeach
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
<style>
    .custom-card-body h1,
    .custom-card-body h2,
    .custom-card-body h3,
    .custom-card-body h4,
    .custom-card-body h5 {
        font-size: 1rem;
        color: #333;
        margin: 0;
        font-weight: 700;
        margin-bottom: 0px;
        text-decoration: underline;
    }

    .custom-card-body p {
        font-size: 0.8rem;
        color: #666;
        margin: 0 0 5px 0;
    }

    .tabs-section-container>h1,
    .tabs-section-container>h2,
    .tabs-section-container>h3,
    .tabs-section-container>h4{
        text-align: left;
        font-size: 1.7rem;
        color: #082755;
        width: max-content;
    }

    .tabs-section-container>.e-visit-content h1,
    .tabs-section-container>.e-visit-content h2,
    .tabs-section-container>.e-visit-content h3,
    .tabs-section-container>.e-visit-content h4{
        text-align: left;
        font-size: 1.7rem;
        color: #082755;
    }
    #solution-para figure {
        display: flex;
    }
    #solution-para figure img{
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }
</style>
@endsection


@section('page_title')
@if ($title != null)
<title>{{ $title->content }}</title>
@else
<title>Community Health Care Clinics</title>
@endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
    <?php
        header('Access-Control-Allow-Origin: *');
        ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
</script>
@endsection
@section('content')
<main>

    <section class="px-2 main-hero-section">
        <div class="container-fluid parent">
            <div class="div2 order-1 order-md-1 order-lg-2">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach ($banners as $key => $banner)
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$key}}"
                            class="{{$key==0?'active':''}}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner rounded-4">
                        @foreach ($banners as $key => $banner)
                        <div class="carousel-item {{$key==0?'active':''}}">
                            <img src="{{ $banner->img }}" class="d-block w-100 carousel-img" alt="Slide {{$key}}" />
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="div1 order-2 order-md-2 order-lg-1">

                <div class="row g-2 h-98">

                    <div class="col-lg-12 col-md-6 col-12 custom-card animate__animated animate__fadeInLeft"
                        style="animation-delay: 0s;">
                        <div class="custom-card-body">
                            @if($groupedSections["box-1"]["contents"])
                                {!! $groupedSections["box-1"]["contents"][0]["content"] !!}
                            @else
                            <h5 class="custom-card-title">Doctor consultation</h5>
                            <p class="custom-card-text">
                                Short description of the first card.
                            </p>
                            @endif
                            <button class="btn btn-primary custom-btn2"
                                onclick="window.location.href='{{route('e-visit')}}'">E-visit</button>
                        </div>
                        <div class="custom-card-img">
                            <img width="70" height="70" src="{{ asset('assets/new_frontend/doc1.png') }}"
                                alt="Image 1" />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-6 col-12 custom-card animate__animated animate__fadeInLeft"
                        style="animation-delay: 0.2s;">
                        <div class="custom-card-body">
                            @if($groupedSections["box-2"]["contents"])
                                {!! $groupedSections["box-2"]["contents"][0]["content"] !!}
                            @else
                            <h5 class="custom-card-title">Pharmacy</h5>
                            <p class="custom-card-text">
                                Short description of the first card.
                            </p>
                            @endif
                            <button class="btn btn-success custom-btn2"
                                onclick="window.location.href='{{route('pharmacy')}}'">Visit Our Store</button>
                        </div>
                        <div class="custom-card-img">
                            <img src="{{ asset('assets/new_frontend/med1.png') }}" alt="Image 1" />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 custom-card animate__animated animate__fadeInLeft"
                        style="animation-delay: 0.5s;">
                        <div class="custom-card-body">
                            @if($groupedSections["box-3"]["contents"])
                                {!! $groupedSections["box-3"]["contents"][0]["content"] !!}
                            @else
                            <h5 class="custom-card-title">Online Lab Tests</h5>
                            <p class="custom-card-text">
                                Short description of the first card.
                            </p>
                            @endif
                            <button class="btn btn-danger custom-btn2"
                                onclick="window.location.href='{{route('labs')}}'">Online Tests</button>
                        </div>
                        <div class="custom-card-img">
                            <img width="70" height="70" src="{{ asset('assets/new_frontend/lab3.png') }}"
                                alt="Image 1" />
                        </div>
                    </div>

                </div>
            </div>

            <div class="div3 row order-3 py-3">
                <div class="w-50 fw-bold">
                    <h2>
                        Featured
                        <span class="red">Categories</span>
                    </h2>
                    <div class="underline w-25"></div>
                </div>
            </div>

        </div>

        <div class="row px-3">
            <div class="col-lg-3 col-md-6 col-12 card-secondary-div animate__animated animate__fadeInUp"
                style="animation-delay: 0s;">
                <div class="card-secondary d-flex flex-row align-items-center justify-content-between">
                    <div>
                        @if($groupedSections["box-4"]["contents"])
                            {!! $groupedSections["box-4"]["contents"][0]["content"] !!}
                        @else
                        <h5>Personal Care</h5>
                        <p>Lorem ipsum dolor sit amet.</p>
                        @endif
                        <button class="btn btn-success custom-btn2"
                            onclick="window.location.href='{{route('pharmacy.category',['slug'=>'personal-care'])}}'">View
                            More</button>
                        <div class="custom-card-img">
                            <img width="60" height="60" src="{{ asset('assets/new_frontend/bottom1.png') }}"
                                alt="Image 1" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 card-secondary-div animate__animated animate__fadeInUp"
                style="animation-delay: 0.2s;">
                <div class="card-secondary d-flex flex-row align-items-center justify-content-between">
                    <div>
                        @if($groupedSections["box-5"]["contents"])
                            {!! $groupedSections["box-5"]["contents"][0]["content"] !!}
                        @else
                        <h5>Mother & Baby Care</h5>
                        <p>Lorem ipsum dolor sit amet.</p>
                        @endif
                        <button class="btn btn-success custom-btn2"
                            onclick="window.location.href='{{route('pharmacy.category',['slug'=>'baby-mothercare'])}}'">View
                            More</button>
                        <div class="custom-card-img">
                            <img width="60" height="60" src="{{ asset('assets/new_frontend/bottom2.png') }}"
                                alt="Image 1" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 card-secondary-div animate__animated animate__fadeInUp"
                style="animation-delay: 0.4s;">
                <div class="card-secondary d-flex flex-row align-items-center justify-content-between">
                    <div>
                        @if($groupedSections["box-6"]["contents"])
                            {!! $groupedSections["box-6"]["contents"][0]["content"] !!}
                        @else
                        <h5>Dermatology</h5>
                        <p>Lorem ipsum dolor sit amet.</p>
                        @endif
                        <button class="btn btn-success custom-btn2"
                            onclick="window.location.href='{{route('pharmacy.category',['slug'=>'dermatology'])}}'">View
                            More</button>
                        <div class="custom-card-img">
                            <img width="60" height="60" src="{{ asset('assets/new_frontend/bottom3.png') }}"
                                alt="Image 1" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 card-secondary-div animate__animated animate__fadeInUp"
                style="animation-delay: 0.6s;">
                <div class="card-secondary d-flex flex-row align-items-center justify-content-between">
                    <div>
                        @if($groupedSections["box-7"]["contents"])
                            {!! $groupedSections["box-7"]["contents"][0]["content"] !!}
                        @else
                        <h5>Multi-Vitamins</h5>
                        <p>Lorem ipsum dolor sit amet.</p>
                        @endif
                        <button class="btn btn-success custom-btn2"
                            onclick="window.location.href='{{route('pharmacy.category',['slug'=>'multivitamins'])}}'">View
                            More</button>
                        <div class="custom-card-img">
                            <img width="60" height="60" src="{{ asset('assets/new_frontend/bottom4.png') }}"
                                alt="Image 1" />
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>


    <section id="steps-section">
        <div id="steps-wrapper">
            <div id="step-heading">
                <h2>
                    Our Guide for
                    <span class="red">New Users</span>
                </h2>
                <div class="underline"></div>
            </div>
            <div id="steps">
                <div class="step" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <div class="step-icon">
                        1
                    </div>
                    <span class="num"> <span class="vr"></span> </span>
                    <div class="step-heading">
                        <h3> LOGIN / REGISTER</h3>
                    </div>
                </div>
                <div class="arrow arrow1">
                    <i class="right_arrow fa-solid fa-angle-right mx-2"></i>
                    <i class="down_arrow fa-solid fa-angle-down"></i>
                </div>
                <div class="step" data-bs-toggle="modal" data-bs-target="#e-visitModal">
                    <div class="step-icon">
                        2
                    </div>
                    <span class="num"><span class="vr"></span> </span>
                    <div class="step-heading">
                        <h3> E-VISIT/ LABTEST/ PHARMACY</h3>
                    </div>
                </div>
                <div class="arrow arrow1">
                    <i class="right_arrow fa-solid fa-angle-right mx-2"></i>
                    <i class="down_arrow fa-solid fa-angle-down"></i>
                </div>
                <div class="step" onclick="window.location.href='{{ route('login') }}'">
                    <div class="step-icon">
                        3
                    </div>
                    <span class="num"><span class="vr"></span> </span>
                    <div class="step-heading">
                        <h3>CONFIRM ORDER</h3>
                    </div>
                </div>
                <div class="arrow arrow1">
                    <i class="right_arrow fa-solid fa-angle-right mx-2"></i>
                    <i class="down_arrow fa-solid fa-angle-down"></i>
                </div>
                <div class="step" onclick="window.location.href='{{ route('login') }}'">
                    <div class="step-icon">
                        4
                    </div>
                    <span class="num"> <span class="vr"></span> </span>
                    <div class="step-heading">
                        <h3>CHECKOUT</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>


 {{--
     <section class="mx-3 my-4 doctor-carousel-container">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex flex-column">
            <h2 class="">
              List of
              <span class="red">Doctors</span>
            </h2>
            <div class="underline w-50"></div>
          </div>
          <div class="d-flex align-items-center justify-content-end gap-3">
            <a
              class="carousel-control-prev bg-transparent w-aut"
              href="#recipeCarousel"
              role="button"
              data-bs-slide="prev"
            >
              <span
                class="carousel-control-prev-icon"
                aria-hidden="true"
              ></span>
            </a>
            <a
              class="carousel-control-next bg-transparent w-aut"
              href="#recipeCarousel"
              role="button"
              data-bs-slide="next"
            >
              <span
                class="carousel-control-next-icon"
                aria-hidden="true"
              ></span>
            </a>
          </div>
        </div>
        <div class="row mx-auto my-auto justify-content-center">
          <div
            id="recipeCarousel"
            class="carousel slide"
            data-bs-ride="carousel"
          >
            <div class="carousel-inner px-0 py-3 px-0" role="listbox">
              <div class="carousel-item active">
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 px-sm-1 px-md-3">
                  <div
                    class="doctor-carousel-card-container d-flex flex-column align-items-center justify-content-center text-center rounded-2 py-1"
                  >
                    <div class="carousel-pic-container rounded-circle p-1">
                      <img
                        src="{{ asset('assets/new_frontend/doctor-3.png') }}"
                        alt="Doctor Page"
                        class="rounded-circle object-fit-cover w-100 h-100"
                      />
                    </div>
                    <div class="carousel-doctor-ratings mt-1">
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="d-flex flex-column mt-1">
                      <h5 class="mb-0">Dr. Allama Iqbal</h5>
                      <p class="">M.B.B.S, B.D.S.</p>
                    </div>
                    <h6 class="mt-1 rounded-5 px-2 py-1">DERMATOLOGY</h6>
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 px-sm-1 px-md-3">
                  <div
                    class="doctor-carousel-card-container d-flex flex-column align-items-center justify-content-center text-center rounded-2 py-1"
                  >
                    <div class="carousel-pic-container rounded-circle p-1">
                      <img
                        src="{{ asset('assets/new_frontend/doctor-3.png') }}"
                        alt="Doctor Page"
                        class="rounded-circle object-fit-cover w-100 h-100"
                      />
                    </div>
                    <div class="carousel-doctor-ratings mt-1">
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="d-flex flex-column mt-1">
                      <h5 class="mb-0">Dr. Allama Iqbal</h5>
                      <p class="">M.B.B.S, B.D.S.</p>
                    </div>
                    <h6 class="mt-1 rounded-5 px-2 py-1">DERMATOLOGY</h6>
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 px-sm-1 px-md-3">
                  <div
                    class="doctor-carousel-card-container d-flex flex-column align-items-center justify-content-center text-center rounded-2 py-1"
                  >
                    <div class="carousel-pic-container rounded-circle p-1">
                      <img
                        src="{{ asset('assets/new_frontend/doctor-3.png') }}"
                        alt="Doctor Page"
                        class="rounded-circle object-fit-cover w-100 h-100"
                      />
                    </div>
                    <div class="carousel-doctor-ratings mt-1">
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="d-flex flex-column mt-1">
                      <h5 class="mb-0">Dr. Felix Cadreal</h5>
                      <p class="">M.B.B.S, B.D.S.</p>
                    </div>
                    <h6 class="mt-1 rounded-5 px-2 py-1">DERMATOLOGY</h6>
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 px-sm-1 px-md-3">
                  <div
                    class="doctor-carousel-card-container d-flex flex-column align-items-center justify-content-center text-center rounded-2 py-1"
                  >
                    <div class="carousel-pic-container rounded-circle p-1">
                      <img
                        src="{{ asset('assets/new_frontend/doctor-3.png') }}"
                        alt="Doctor Page"
                        class="rounded-circle object-fit-cover w-100 h-100"
                      />
                    </div>
                    <div class="carousel-doctor-ratings mt-1">
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="d-flex flex-column mt-1">
                      <h5 class="mb-0">Dr. Allama Iqbal</h5>
                      <p class="">M.B.B.S, B.D.S.</p>
                    </div>
                    <h6 class="mt-1 rounded-5 px-2 py-1">DERMATOLOGY</h6>
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 px-sm-1 px-md-3">
                  <div
                    class="doctor-carousel-card-container d-flex flex-column align-items-center justify-content-center text-center rounded-2 py-1"
                  >
                    <div class="carousel-pic-container rounded-circle p-1">
                      <img
                        src="{{ asset('assets/new_frontend/doctor-3.png') }}"
                        alt="Doctor Page"
                        class="rounded-circle object-fit-cover w-100 h-100"
                      />
                    </div>
                    <div class="carousel-doctor-ratings mt-1">
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="d-flex flex-column mt-1">
                      <h5 class="mb-0">Dr. Allama Iqbal</h5>
                      <p class="">M.B.B.S, B.D.S.</p>
                    </div>
                    <h6 class="mt-1 rounded-5 px-2 py-1">DERMATOLOGY</h6>
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 px-sm-1 px-md-3">
                  <div
                    class="doctor-carousel-card-container d-flex flex-column align-items-center justify-content-center text-center rounded-2 py-1"
                  >
                    <div class="carousel-pic-container rounded-circle p-1">
                      <img
                        src="{{ asset('assets/new_frontend/doctor-3.png') }}"
                        alt="Doctor Page"
                        class="rounded-circle object-fit-cover w-100 h-100"
                      />
                    </div>
                    <div class="carousel-doctor-ratings mt-1">
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="d-flex flex-column mt-1">
                      <h5 class="mb-0">Dr. Allama Iqbal</h5>
                      <p class="">M.B.B.S, B.D.S.</p>
                    </div>
                    <h6 class="mt-1 rounded-5 px-2 py-1">DERMATOLOGY</h6>
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 px-sm-1 px-md-3">
                  <div
                    class="doctor-carousel-card-container d-flex flex-column align-items-center justify-content-center text-center rounded-2 py-1"
                  >
                    <div class="carousel-pic-container rounded-circle p-1">
                      <img
                        src="{{ asset('assets/new_frontend/doctor-3.png') }}"
                        alt="Doctor Page"
                        class="rounded-circle object-fit-cover w-100 h-100"
                      />
                    </div>
                    <div class="carousel-doctor-ratings mt-1">
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="d-flex flex-column mt-1">
                      <h5 class="mb-0">Dr. Allama Iqbal</h5>
                      <p class="">M.B.B.S, B.D.S.</p>
                    </div>
                    <h6 class="mt-1 rounded-5 px-2 py-1">DERMATOLOGY</h6>
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="col-12 col-sm-6 col-md-3 col-lg-2 px-sm-1 px-md-3">
                  <div
                    class="doctor-carousel-card-container d-flex flex-column align-items-center justify-content-center text-center rounded-2 py-1"
                  >
                    <div class="carousel-pic-container rounded-circle p-1">
                      <img
                        src="{{ asset('assets/new_frontend/doctor-3.png') }}"
                        alt="Doctor Page"
                        class="rounded-circle object-fit-cover w-100 h-100"
                      />
                    </div>
                    <div class="carousel-doctor-ratings mt-1">
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                      <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="d-flex flex-column mt-1">
                      <h5 class="mb-0">Dr. Allama Iqbal</h5>
                      <p class="">M.B.B.S, B.D.S.</p>
                    </div>
                    <h6 class="mt-1 rounded-5 px-2 py-1">DERMATOLOGY</h6>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
     </section>
     --}}



    <section id="buttons-section" class="container-fluid mt-5">
        <div class="button-section nav nav-tabs" id="nav-tab" role="tablist">
            <div id="e-visit-tab" class="nav-link active" data-bs-toggle="tab" data-bs-target="#e-visit" type="button"
                role="tab" aria-controls="e-visit" aria-selected="true">
                <i class="fa-solid fa-desktop "></i>
                <div>E-Visit</div>
            </div>
            <div id="pharmacy-tab" class="nav-link pharmacy-penal" data-bs-toggle="tab" data-bs-target="#pharmacy"
                type="button" role="tab" aria-controls="pharmacy" aria-selected="false">
                <i class="fa-solid fa-pills "></i>
                <div>Pharmacy</div>
            </div>
            <div id="tests-tab" class="nav-link labtest-penal" data-bs-toggle="tab" data-bs-target="#tests"
                type="button" role="tab" aria-controls="tests" aria-selected="false">
                <i class="fa-solid fa-flask "></i>
                <div>Lab Tests</div>
            </div>
            <div id="imaging-tab" class="nav-link imaging-penal" data-bs-toggle="tab" data-bs-target="#imaging"
                type="button" role="tab" aria-controls="imaging" aria-selected="false">
                <i class="fa-solid fa-flask-vial "></i>
                <div>Imaging</div>
            </div>
            <div id="primary-care-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#primary-care"
                type="button" role="tab" aria-controls="primary-care" aria-selected="false">
                <i class="fa-solid fa-hand-holding-medical "></i>
                <div>Primary Care</div>
            </div>
            <div id="psychiatry-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#psychiatry" type="button"
                role="tab" aria-controls="psychiatry" aria-selected="false">
                <i class="fa-solid fa-user-doctor "></i>
                <div>Psychiatry</div>
            </div>
            {{--<div id="pain-management-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#pain-management"
                type="button" role="tab" aria-controls="pain-management" aria-selected="false">
                <div>Pain Management</div>
            </div>
            <div id="substance-abuse-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#substance-abuse"
                type="button" role="tab" aria-controls="substance-abuse" aria-selected="false">
                <div>Substance Abuse</div>
            </div>--}}
        </div>
    </section>
    <div class="container1 container-fluid tab-content" id="nav-tabContent">
        <section class="tab-pane fade show active" id="e-visit" role="tabpanel" aria-labelledby="e-visit-tab"
            tabindex="0">
            <div class="tabs-section-container e-visit-section">
                <div class="e-visit-content">
                    <div class="left">
                        @if($groupedSections["E-visit"]["contents"])
                            {!! $groupedSections["E-visit"]["contents"][0]["content"] !!}
                        @else
                        <p>
                            Umbrella Health Care Systems provide you with facility to
                            visit doctors, therapist, or medical expert online. Find
                            best Doctors to get instant medical advice for your health
                            problems. Ask the doctors online and consult them on
                            face-to-face video calls, chat, or voice calls at your
                            convenience. Umbrella Health Care Systems offer a wide range
                            of specialists in various medical fields, including general
                            physicians, dermatologists, pediatricians, psychiatrists,
                            and more. Whether you're seeking advice on minor health
                            concerns, mental health support, or urgent medical queries,
                            you can easily connect with qualified healthcare
                            professionals from the comfort of your home.
                        </p>
                        @endif
                        <div class="doc-btn-container">
                            <div class="doctor-button">
                                <i class="fa-solid fa-user-doctor"></i>
                                <a data-bs-toggle="modal" data-bs-target="#loginModal" href="#">TALK TO
                                    DOCTORS</a>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <img src=" {{ asset('assets/new_frontend/Evisit.jpg') }}" alt="" />
                    </div>
                </div>
            </div>
        </section>
        <section class="tab-pane fade" id="pharmacy" role="tabpanel" aria-labelledby="pharmacy-tab" tabindex="0">
            <div class="tabs-section-container">
                @if($groupedSections["Pharmacy"]["contents"])
                    {!! $groupedSections["Pharmacy"]["contents"][0]["content"] !!}
                @else
                <div class="tabs-section-heading">
                    <h2>Pharmacy</h2>
                    {{-- <div class="underline"></div> --}}
                </div>
                <p class="tabs-section-para">
                    Our Pharmacy Offers prescription drugs at discounted prices
                </p>
                @endif

                <div class="pharmacy-content">
                    <div class="pharmacy-categories">
                        @foreach ($data['prescribed_medicines_category'] as $item)
                        <div class="pharmacy-category" onclick="getPharmacyProductByCategory({{ $item->id }},8)">
                            <i class="fa-solid fa-pills"></i>
                            <div title="{{ $item->title }}">{{ \Str::limit($item->title, 15, '...') }}</div>
                        </div>
                        @endforeach
                        <div class="pharmacy-category">
                            <i class="fa-solid fa-pills"></i>
                            <div onclick="location.href='{{ route('pharmacy') }}'">View More</div>
                        </div>
                    </div>

                    <hr />

                    <div class="medicines-container" id="load_pharmacy_item_by_category">
                        <div class="card">
                            <div class="prescription">
                                <p style="background: red">prescription required</p>
                            </div>
                            <div class="price">
                                <p>Rs: 200</p>
                            </div>
                            <div class="med-img"><img src="{{ asset('assets/new_frontend/panadol.png') }}" alt="img">
                            </div>
                            <h4 class="truncate m-0 p-0">Niacin ER tablet</h4>
                            <h6 class="truncate m-0 p-0">Heart Disease</h6>
                            <div class="pharmacy_btn">
                                <a class="read-more btn btn-outline-danger" href="#">Read More <i
                                        class="fa-solid fa-sheet-plastic mx-2"></i></a>
                                <a class="add-to-cart" href="#">Add to Cart <i
                                        class="fa-solid fa-cart-shopping mx-2"></i></a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="prescription">
                                <p style="background: #35b518">prescription required</p>
                            </div>
                            <div class="med-img"><img src="https://placehold.co/70" alt="img"></div>
                            <h4 class="truncate">Niacin ER tablet</h4>
                            <h6 class="truncate">Heart Disease</h6>
                            <div class="pharmacy_btn">
                                <a class="read-more btn btn-outline-danger" href="#">Read More <i
                                        class="fa-solid fa-sheet-plastic mx-2"></i></a>
                                <a class="add-to-cart" href="#">Add to Cart <i
                                        class="fa-solid fa-cart-shopping mx-2"></i></a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="prescription">
                                <p>prescription required</p>
                            </div>
                            <div class="med-img"><img src="https://placehold.co/70" alt="img"></div>
                            <h4 class="truncate">Niacin ER tablet</h4>
                            <h6 class="truncate">Heart Disease</h6>
                            <div class="pharmacy_btn">
                                <a class="read-more btn btn-outline-danger" href="#">Read More <i
                                        class="fa-solid fa-sheet-plastic mx-2"></i></a>
                                <a class="add-to-cart" href="#">Add to Cart <i
                                        class="fa-solid fa-cart-shopping mx-2"></i></a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="prescription">
                                <p>prescription required</p>
                            </div>
                            <div class="med-img"><img src="https://placehold.co/70" alt="img"></div>
                            <h4 class="truncate">Niacin ER tablet</h4>
                            <h6 class="truncate">Heart Disease</h6>
                            <div class="pharmacy_btn">
                                <a class="read-more btn btn-outline-danger" href="#">Read More <i
                                        class="fa-solid fa-sheet-plastic mx-2"></i></a>
                                <a class="add-to-cart" href="#">Add to Cart <i
                                        class="fa-solid fa-cart-shopping mx-2"></i></a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="prescription">
                                <p>prescription required</p>
                            </div>
                            <div class="med-img"><img src="https://placehold.co/70" alt="img"></div>
                            <h4 class="truncate">Niacin ER tablet</h4>
                            <h6 class="truncate">Heart Disease</h6>
                            <div class="pharmacy_btn">
                                <a class="read-more btn btn-outline-danger" href="#">Read More <i
                                        class="fa-solid fa-sheet-plastic mx-2"></i></a>
                                <a class="add-to-cart" href="#">Add to Cart <i
                                        class="fa-solid fa-cart-shopping mx-2"></i></a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="prescription">
                                <p>prescription required</p>
                            </div>
                            <div class="med-img"><img src="https://placehold.co/70" alt="img"></div>
                            <h4 class="truncate">Niacin ER tablet</h4>
                            <h6 class="truncate">Heart Disease</h6>
                            <div class="pharmacy_btn">
                                <a class="read-more btn btn-outline-danger" href="#">Read More <i
                                        class="fa-solid fa-sheet-plastic mx-2"></i></a>
                                <a class="add-to-cart" href="#">Add to Cart <i
                                        class="fa-solid fa-cart-shopping mx-2"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center no-product-loader">
                        <i class="fa fa-spinner fa-spin fs-1"></i>
                    </div>

                    <div class="btn-div">
                        <button onclick="location.href='{{ route('pharmacy') }}'" class="view_all">View All</button>
                    </div>
                </div>
            </div>
        </section>
        <section class="tab-pane fade" id="tests" role="tabpanel" aria-labelledby="tests-tab" tabindex="0">
            <div class="tabs-section-container">
                @if($groupedSections["lab-test"]["contents"])
                    {!! $groupedSections["lab-test"]["contents"][0]["content"] !!}
                @else
                <div class="tabs-section-heading">
                    <h2>Lab Tests</h2>
                    {{-- <div class="underline"></div> --}}
                </div>
                <p class="tabs-section-para">
                    Umbrella Health Care Systems medical labs are state of the art
                    lab services , we several reference labs to bring you best price
                    and precise lab work.
                </p>
                @endif
                {{--<div class="pharmacy-categories">
                    @foreach ($data['labtest_category'] as $item)
                    <div class="pharmacy-category" onclick="getLabtestProductByCategory({{ $item->id }},5)">
                        <i class="fa-solid fa-flask"></i>
                        <div title="{{ $item->product_parent_category }}">
                            {{ \Str::limit($item->product_parent_category, 15, '...') }}</div>
                    </div>
                    @endforeach
                    <div class="pharmacy-category">
                        <i class="fa-solid fa-flask"></i>
                        <div onclick="location.href='{{ route('labs') }}'">View More</div>
                    </div>
                </div>--}}


                <hr class="hr" />

                <h2 class="text-center">Most Popular Labtests</h2>
                <div class="tests-container" id="load_labtest_item_by_category">
                    {{-- <div class="tests-card">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    Learn More
                                </button>
                            </div>
                            <h4 class="truncate">Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <div class="test-card-price">Rs. 2000</div>
                            <button class="learn_btn">Add To Cart <i
                                    class="fa-solid fa-cart-shopping mx-2"></i></button>
                        </div>
                    </div>
                    <div class="tests-card">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    Learn More
                                </button>
                            </div>
                            <h4 class="truncate">Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <button class="learn_btn">Add To Cart <i
                                    class="fa-solid fa-cart-shopping mx-2"></i></button>
                        </div>
                    </div>
                    <div class="tests-card">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    Learn More
                                </button>
                            </div>
                            <h4 class="truncate">Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <button class="learn_btn">Add To Cart <i
                                    class="fa-solid fa-cart-shopping mx-2"></i></button>
                        </div>
                    </div>
                    <div class="tests-card">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    Learn More
                                </button>
                            </div>
                            <h4 class="truncate">Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <button class="learn_btn">Add To Cart <i
                                    class="fa-solid fa-cart-shopping mx-2"></i></button>
                        </div>
                    </div>
                    <div class="tests-card">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    Learn More
                                </button>
                            </div>
                            <h4 class="truncate">Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <button class="learn_btn">Add To Cart <i
                                    class="fa-solid fa-cart-shopping mx-2"></i></button>
                        </div>
                    </div> --}}
                    <div class="no-product-text">
                        <i class="fa fa-spinner fa-spin fs-1"></i>
                        <p>Loading</p>
                    </div>
                </div>
                <div class="btn-div">
                    <button class="view_all" onclick="location.href='{{ route('labs') }}'">View All</button>
                </div>
            </div>
        </section>
        <section class="tab-pane fade" id="imaging" role="tabpanel" aria-labelledby="imaging-tab" tabindex="0">
            <div class="tabs-section-container">
                @if($groupedSections["imaging"]["contents"])
                    {!! $groupedSections["imaging"]["contents"][0]["content"] !!}
                @else
                <div class="tabs-section-heading">
                    <h2>Imaging</h2>
                    {{-- <div class="underline"></div> --}}
                </div>
                <p class="tabs-section-para">
                    Umbrella Health Care Systems medical labs are state of the art
                    lab services , we several reference labs to bring you best price
                    and precise lab work.
                </p>
                @endif
                <div class="pharmacy-categories">
                    @foreach ($data['imaging_category'] as $item)
                    <div class="pharmacy-category" onclick="getImagingProductByCategory({{ $item->id }},10)">
                        <i class="fa-solid fa-flask"></i>
                        <div title="{{ $item->product_parent_category }}">
                            {{ \Str::limit($item->product_parent_category, 15, '...') }}</div>
                    </div>
                    @endforeach
                    <div class="pharmacy-category">
                        <i class="fa-solid fa-flask"></i>
                        <div onclick="location.href='{{ route('imaging') }}'">View More</div>
                    </div>
                </div>

                <hr class="hr" />

                <h2 class="text-center">Most Popular Labtests</h2>
                <div class="tests-container" id="load_imaging_item_by_category">
                    {{--<div class="tests-card">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>
                            </div>
                            <h4 class="truncate">Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <button class="learn_btn">Learn More</button>
                        </div>
                    </div>
                    <div class="tests-card">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>
                            </div>
                            <h4 class="truncate">Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <button class="learn_btn">Learn More</button>
                        </div>
                    </div>
                    <div class="tests-card">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>
                            </div>
                            <h4 class="truncate">Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <button class="learn_btn">Learn More</button>
                        </div>
                    </div>
                    <div class="tests-card">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>
                            </div>
                            <h4 class="truncate">Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <button class="learn_btn">Learn More</button>
                        </div>
                    </div>
                    <div class="tests-card">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>
                            </div>
                            <h4 class="truncate">Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <button class="learn_btn">Learn More</button>
                        </div>
                    </div>--}}
                    <div class="no-product-text">
                        <i class="fa fa-spinner fa-spin fs-1"></i>
                        <p>Loading</p>
                    </div>
                </div>

                <div class="btn-div">
                    <button class="view_all" onclick="location.href='{{ route('imaging') }}'">View All</button>
                </div>
            </div>
        </section>
        <section class="tab-pane fade" id="primary-care" role="tabpanel" aria-labelledby="primary-care-tab"
            tabindex="0">
            <div class="tabs-section-container">
                <div class="e-visit-content">
                    <div class="left">
                        @if($groupedSections["primary-care"]["contents"])
                            {!! $groupedSections["primary-care"]["contents"][0]["content"] !!}
                        @else
                        <p>
                            Umbrella Health Care Systems provide you with facility to
                            visit doctors, therapist, or medical expert online. Find
                            best Doctors to get instant medical advice for your health
                            problems. Ask the doctors online and consult them on
                            face-to-face video calls, chat, or voice calls at your
                            convenience. Umbrella Health Care Systems offer a wide range
                            of specialists in various medical fields, including general
                            physicians, dermatologists, pediatricians, psychiatrists,
                            and more. Whether you're seeking advice on minor health
                            concerns, mental health support, or urgent medical queries,
                            you can easily connect with qualified healthcare
                            professionals from the comfort of your home.
                        </p>
                        @endif
                        <div class="doc-btn-container">
                            <div class="doctor-button">
                                <i class="fa-solid fa-user-doctor"></i>
                                <a href="">TALK TO DOCTORS</a>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <img width="500px" src=" {{ asset('assets/new_frontend/primary-care.jpg') }}" alt="" />
                    </div>
                </div>
            </div>
        </section>
        <section class="tab-pane fade" id="psychiatry" role="tabpanel" aria-labelledby="psychiatry-tab" tabindex="0">
            <div class="tabs-section-container">
                <div class="tabs-section-heading">
                    <h2>Psychiatry</h2>
                    {{-- <div class="underline"></div> --}}
                </div>
                @if($groupedSections["psychiatry"]["contents"])
                    {!! $groupedSections["psychiatry"]["contents"][0]["content"] !!}
                @else
                <p class="tabs-section-para">
                    Getting the support you need has never been simpler thanks to
                    Umbrella Health Care System’s skilled team of psychiatrists, who
                    are known for offering their patients compassionate.
                </p>
                @endif
                <hr />

                <div class="psychiatry-container">
                    @foreach ($data['psychiatrist'] as $key => $item)
                    <div class="psychiatry-box">
                        <img src=" {{ asset('assets/new_frontend/depression.png') }}" alt="{{ $item->title }}" />
                        <p>{{ $item->title }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="btn-div">
                    <button class="view_all" onclick="location.href='{{ route('psychiatry',['slug'=>" anxiety"])
                        }}'">View All</button>
                </div>
            </div>
        </section>
        {{--
            <section class="tab-pane fade" id="pain-management" role="tabpanel" aria-labelledby="pain-management-tab"
            tabindex="0">
            <div class="tabs-section-container">
                <div class="tabs-section-heading">
                    <h2>Pain Management</h2>
                    <div class="underline"></div>
                </div>
                @if($groupedSections["pain-management"]["contents"])
                    {!! $groupedSections["pain-management"]["contents"][0]["content"] !!}
                @else
                <p class="tabs-section-para">
                    Getting the support you need has never been simpler thanks to
                    Umbrella Health Care System’s skilled team of psychiatrists, who
                    are known for offering their patients compassionate.
                </p>
                @endif
                <hr />

                <div class="pain-management-container">
                    @foreach ($data['pain_categories'] as $key => $item)
                    <div class="pain-management-box">
                        <img src=" {{ asset('assets/new_frontend/cancer-icon-umbrella.png') }}"
                            alt="{{ $item->title }}" />
                        <p>{{ $item->title }}</p>
                    </div>
                    @endforeach

                </div>

                <div class="btn-div">
                    <button class="view_all" onclick="location.href='{{ route('pain.management') }}'">View All</button>
                </div>
            </div>
            </section>
            <section class="tab-pane fade" id="substance-abuse" role="tabpanel" aria-labelledby="substance-abuse-tab"
                tabindex="0">
                <div class="tabs-section-container">
                    <div class="tabs-section-heading">
                        <h2>Substance Abuse</h2>
                        <div class="underline"></div>
                    </div>
                    @if($groupedSections["substance-abuse"]["contents"])
                        {!! $groupedSections["substance-abuse"]["contents"][0]["content"] !!}
                    @else
                    <p class="tabs-section-para">
                        Getting the support you need has never been simpler thanks to
                        Umbrella Health Care System’s skilled team of psychiatrists, who
                        are known for offering their patients compassionate.
                    </p>
                    @endif
                    <hr />

                    <div class="substance-abuse-container">
                        @foreach ($data['substance_categories'] as $item)
                        <div class="substance-abuse-box">
                            <img src=" {{ asset('assets/new_frontend/self-pay.png') }}" alt="{{ $item->title }}" />
                            <p>{{ $item->title }}</p>
                        </div>
                        @endforeach

                    </div>

                    <div class="btn-div">
                        <button class="view_all"
                            onclick="location.href='{{ route('substance',['slug'=>'first-visit']) }}'">View All</button>
                    </div>
                </div>
            </section>
        --}}
    </div>

    <section id="problems-section" class="py-2">
        <div class="blob position-absolute">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="#E8DAFF"
                    d="M37.8,-66.6C45.1,-61.4,44.4,-43.3,47,-30C49.7,-16.8,55.7,-8.4,62.3,3.8C68.8,15.9,75.8,31.9,69.9,39.5C64.1,47.2,45.3,46.6,31.5,46.7C17.7,46.8,8.9,47.5,-0.6,48.7C-10.1,49.8,-20.3,51.2,-28,47.6C-35.8,44,-41.1,35.4,-48.7,26.7C-56.4,17.9,-66.3,8.9,-72.3,-3.5C-78.3,-15.9,-80.5,-31.8,-76,-46C-71.4,-60.2,-60.3,-72.6,-46.5,-74.2C-32.8,-75.7,-16.4,-66.3,-0.5,-65.4C15.3,-64.4,30.6,-71.9,37.8,-66.6Z"
                    transform="translate(100 100)" />
            </svg>
        </div>
        <div class="blob2 position-absolute">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="#E8DAFF"
                    d="M42.5,-52.1C58.1,-47.2,75.8,-38.8,84.6,-24.5C93.4,-10.1,93.4,10.1,83.4,22.9C73.5,35.8,53.7,41.2,38.1,47.5C22.6,53.9,11.3,61.1,-1.7,63.4C-14.7,65.8,-29.4,63.2,-37.9,54.6C-46.4,46,-48.8,31.3,-54.2,17.1C-59.6,2.8,-68,-11,-68.4,-26C-68.7,-41,-61,-57.1,-48.2,-62.9C-35.5,-68.7,-17.7,-64,-2.1,-61.1C13.5,-58.2,27,-56.9,42.5,-52.1Z"
                    transform="translate(100 100)" />
            </svg>
        </div>
        <div class="container-fluid">
            <div class="row align-items-center w-100">
                <div class="col-md-5 col-xl-6 p-4 border-right">
                    <div>
                        <h1 class="font-weight-bold partner-heading">Our Collaborating <br><span
                                class="text-danger">Partners</span></h1>
                        <div class="underline bg-danger w-25"></div>
                    </div>
                </div>
                <div class="col-md-7 col-xl-6">
                    <div class="d-flex flex-wrap gap-3 justify-content-center align-items-start partners_logos">
                        <img src="{{ asset('assets/new_frontend/partner2.png') }}"
                            alt="Partner 2 Logo" />
                        <img src="{{ asset('assets/new_frontend/partner1.png') }}"
                            alt="Partner 1 Logo" />
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="solution-section" class="image-content">
        <div class="content">
            <div id="solution-content" class="last-content">
                <div id="solution-heading" class="heading">
                    @if($groupedSections["last-section-header"]["contents"])
                        {!! $groupedSections["last-section-header"]["contents"][0]["content"] !!}
                    @else
                    <h2>
                        Umbrella Health Care Systems is the
                        <span class="red">Best Health Care Website</span>
                    </h2>
                    @endif
                    <div class="underline"></div>
                </div>
                <div id="solution-para" class="para">
                    @if($groupedSections["last-section-description"]["contents"])
                        {!! $groupedSections["last-section-description"]["contents"][0]["content"] !!}
                    @else
                    <p>
                        Get started now! Doctors are ready to help you get the care
                        you need, anywhere and anytime in the United States. Access to
                        doctors, psychiatrists, psychologists, therapists and other
                        medical experts, care is available from 07:00 AM to 08:00 PM.
                    </p>
                    @endif
                </div>
                <a href="">TALK TO DOCTORS</a>
            </div>
            <aside id="solution-image" class="first-content">
                <img src=" {{ asset('assets/new_frontend/AmericanDoc.jpg') }}" alt="conference-image" />
            </aside>
        </div>
        <div class="image-bg"></div>
    </section>
    <section id="faqs">
        <div>
            <h2>Frequently Asked <span class="red">Questions</span></h2>
            <div class="underline"></div>
        </div>
        <div id="faq-content">
            @foreach ($faqs as $faq)
            <div class="faq">
                <div class="faq-question">
                    <h3>{{ $faq->question }}</h3>
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div class="faq-answer">
                    <p>{!! $faq->answer !!}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</main>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Registration Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
<div class="modal fade" id="e-visitModal" tabindex="-1" aria-labelledby="e-visitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-e-visit-btn my-3">
                    <div>
                        <a href="{{ route('e-visit') }}"><button> E-VISIT</button></a>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <!-- 16:9 aspect ratio -->
                    <div class="embed-responsive embed-responsive-16by9" id="yt-player">
                        <iframe title="UHCS-Video" srcdoc="
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
                                            </a>" style="max-width: 640px; width: 100%; aspect-ratio: 16/9;"
                            frameborder="0">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade cart-modal" id="afterLogin" tabindex="-1" aria-labelledby="afterLoginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="afterLoginLabel">Item Added</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="custom-modal">
                    <div class="succes succes-animation icon-top"><i class="fa fa-check"></i></div>
                    <div class="content flex-column align-items-center justify-content-center w-100 gap-1">
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="custom-modal1">
                    <div class="succes succes-animation icon-top"><i class="fa fa-check"></i></div>
                    <div class="content flex-column align-items-center justify-content-center w-100 gap-1">
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
<div class="modal fade cart-modal" id="beforeLogin" tabindex="-1" aria-labelledby="beforeLoginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="beforeLoginLabel">Not Logged In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

                                </div>
                            </div>
                            <div class="main_cards_scroll px-3">
                                <div class="row locations_data">
                                    {{--@forelse ($locations as $item)
                                    <div class="col-md-6 mb-2">
                                        <div class="address_phone_card">
                                            <div class="px-2 mb-2">
                                                <p><i class="fa-solid fa-location-dot"
                                                        style="color: rgb(255, 53, 53)"></i><span>
                                                        {{ $item->name }}</span></p>
                                                <p><i class="fa-solid fa-phone" style="color: rgb(12, 180, 12)"></i>
                                                    {{ $item->phone_number }}
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
                                    @endforelse--}}
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
<script>
      let carouselParent = document.querySelector(".doctor-carousel-container");
      let newItems = carouselParent.querySelectorAll(".carousel .carousel-item");

      newItems.forEach((el) => {
        const minPerSlide = 6;
        let next = el.nextElementSibling;
        for (var i = 1; i < minPerSlide; i++) {
          if (!next) {
            next = newItems[0];
          }
          let cloneChild = next.cloneNode(true);
          el.appendChild(cloneChild.children[0]);
          next = next.nextElementSibling;
        }
      });
</script>
<!-- ----------symptoms Checker Modal------- -->
@endsection



