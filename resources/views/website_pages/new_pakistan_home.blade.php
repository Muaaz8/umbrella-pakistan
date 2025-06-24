@extends('layouts.new_pakistan_layout')

@section('meta_tags')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="copyright" content="© {{ date('Y') }} All Rights Reserved. Powered By Community Healthcare Clinics">
@foreach ($tags as $tag)
<meta name="{{ $tag->name }}" content="{{ $tag->content }}">
@endforeach
<link rel="icon" href="{{ asset('asset_frontend/new_frontend/fav_ico.png') }}" type="image/x-icon">
{{-- <style>
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
  .tabs-section-container>h4 {
    text-align: left;
    font-size: 1.7rem;
    color: #082755;
    width: max-content;
  }

  .tabs-section-container>.e-visit-content h1,
  .tabs-section-container>.e-visit-content h2,
  .tabs-section-container>.e-visit-content h3,
  .tabs-section-container>.e-visit-content h4 {
    text-align: left;
    font-size: 1.7rem;
    color: #082755;
  }

  #solution-para figure {
    display: flex;
  }

  #solution-para figure img {
    width: 20px;
    height: 20px;
    margin-right: 10px;
  }

  .doc-btn {
    color: #333
  }

  .doc-btn:hover {
    color: #ffffff
  }
</style> --}}
<style>
  .card {
    display: block !important;
    max-width: none !important;
    height: auto !important;
    background-color: white !important;
    color: rgb(0, 0, 0) !important;
    text-align: left !important;
    position: static !important;
    box-shadow: none !important;
    margin-bottom: auto !important;
    padding: initial !important;
  }

  @media only screen and (min-width: 1400px) {
    .card {
      min-height: auto !important;
      max-width: none !important;
    }
  }

  @media only screen and (max-width: 1200px) {
    .card {
      max-width: none !important;
    }
  }
</style>
@endsection


@section('page_title')
    @if ($title != null)
        <title>{{ $title->content }}</title>
    @else
        <title>Community Healthcare Clinics</title>
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
  <!-- Section 1: Hero Section -->
  <section class="new-hero-section container-fluid px-0 px-md-auto d-flex justify-content-center pt-2 pb-4">
    <div class="row gy-3 gx-0 g-sm-4 w-85">
      <div class="ps-md-0 col-md-8">
        <div id="mainBanner" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainBanner" data-bs-slide-to="0" class="active" aria-current="true"
              aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#mainBanner" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#mainBanner" data-bs-slide-to="2" aria-label="Slide 3"></button>
          </div>
          <div class="carousel-inner rounded-3">
            <div class="carousel-item active">
              <img src="{{ asset('assets/new_frontend/new-banner.webp') }}" class="d-block w-100" alt="..." />
            </div>
            <div class="carousel-item">
              <img src="{{ asset('assets/new_frontend/new-banner.webp') }}" class="d-block w-100" alt="..." />
            </div>
            <div class="carousel-item">
              <img src="{{ asset('assets/new_frontend/new-banner.webp') }}" class="d-block w-100" alt="..." />
            </div>
          </div>
        </div>
      </div>
      <div class="pe-md-0 col-md-4">
        <div
          class="p-4 p-sm-5 py-md-3 px-md-4 w-100 promo-card d-flex flex-column align-items-center justify-content-center gap-2 rounded-3 h-100">
          <h4 class="text-center fw-semibold">
            Get upto 30% Discount on Lab Tests at
            <span class="highlight-blue">Essa Lab!</span>
          </h4>
          <img class="w-75 w-sm-50 w-md-100" src="{{ asset('assets/new_frontend/dr-essa-logo.webp') }}" alt="" />
        </div>
      </div>
      <div class="ps-md-0 col-md-4">
        <div class="w-100 d-flex align-items-stretch justify-content-between service-card p-3 p-sm-4 p-md-2 p-lg-4 rounded-3 h-100">
          <div class="w-60 card-text-cont">
            <h5 class="fs-6 fw-semibold">Consult Doctor Online</h5>
            <p class="service-card-para fs-14">
              Consult a doctor online, book now!
            </p>
            <a href=""
              class="card-btn bg-blue d-flex align-items-center gap-2 my-3 px-3 py-1 rounded-2 text-white fs-12"><span
                class="fw-semibold">E-Visit</span><i class="fa-solid fa-arrow-right fs-14"></i></a>
          </div>
          <div class="d-flex align-items-end justify-content-center w-40">
            <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/e-visit-img.png') }}"
              alt="" />
          </div>
        </div>
      </div>
      <div class="px-md-auto col-md-4">
        <div class="w-100 d-flex align-items-stretch justify-content-between service-card p-3 p-sm-4 p-md-2 p-lg-4 rounded-3 h-100">
          <div class="w-60 card-text-cont">
            <h5 class="fs-6 fw-semibold">Order Medicines Online</h5>
            <p class="service-card-para fs-14">
              Order medicines online, get them today!
            </p>
            <a href=""
              class="card-btn bg-green d-flex align-items-center gap-2 my-3 px-3 py-1 rounded-2 text-white fs-12"><span
                class="fw-semibold">Visit our Store</span><i class="fa-solid fa-arrow-right fs-14"></i></a>
          </div>
          <div class="d-flex align-items-end justify-content-center w-40 ps-md-3 ps-lg-2">
            <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/tab-cart.png') }}" alt="" />
          </div>
        </div>
      </div>
      <div class="pe-md-0 col-md-4">
        <div class="w-100 d-flex align-items-stretch justify-content-between service-card rounded-3 h-100">
          <div class="w-60 p-3 p-sm-4 p-md-2 p-lg-4 pe-0 card-text-cont">
            <h5 class="fs-6 fw-semibold">Online Lab Test</h5>
            <p class="service-card-para fs-14">
              Book lab tests online, Schedule now!
            </p>
            <a href=""
              class="card-btn bg-red d-flex align-items-center gap-2 my-3 px-3 py-1 rounded-2 text-white fs-12"><span
                class="fw-semibold">Online Test</span><i class="fa-solid fa-arrow-right fs-14"></i></a>
          </div>
          <div class="d-flex align-items-end justify-content-center w-40 pe-3 pe-sm-4 pe-md-2 pe-lg-4">
            <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/person.png') }}" alt="" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 2: Our Guide Section -->
  <section
    class="our-guide-section container-fluid px-0 d-flex flex-column align-items-center justify-content-center py-2">
    <div class="bg-light-blue rounded-3 pt-3 pb-2 px-2 w-85">
      <h2 class="text-center">
        Our Guide for <span class="fw-bold">New Users</span>
      </h2>
      <div class="d-none d-sm-block">
        <ul class="nav justify-content-center nav-pills my-3 bg-white p-2 rounded-2" id="pills-tab" role="tablist">
          <li class="nav-item px-4" role="presentation">
            <button class="text-navy-blue py-2 px-4 fw-semibold nav-link active" id="pills-register-patient-tab"
              data-bs-toggle="pill" data-bs-target="#pills-register-patient" type="button" role="tab"
              aria-controls="pills-register-patient" aria-selected="true">
              Register as Patient / Doctor
            </button>
          </li>
        {{--<li class="nav-item" role="presentation">
            <button class="text-navy-blue py-2 px-4 fw-semibold nav-link" id="pills-register-doctor-tab"
              data-bs-toggle="pill" data-bs-target="#pills-register-doctor" type="button" role="tab"
              aria-controls="pills-register-doctor" aria-selected="false">
              Register as a Doctor
            </button>
          </li>--}}
          <li class="nav-item px-4" role="presentation">
            <button class="text-navy-blue py-2 px-4 fw-semibold nav-link" id="pills-order-medicine-tab"
              data-bs-toggle="pill" data-bs-target="#pills-order-medicine" type="button" role="tab"
              aria-controls="pills-order-medicine" aria-selected="false">
              Order Medicine
            </button>
          </li>
          <li class="nav-item px-4" role="presentation">
            <button class="text-navy-blue py-2 px-4 fw-semibold nav-link" id="pills-order-lab-tab" data-bs-toggle="pill"
              data-bs-target="#pills-order-lab" type="button" role="tab" aria-controls="pills-order-lab-tab"
              aria-selected="false">
              Order Lab
            </button>
          </li>
          <li class="nav-item px-4" role="presentation">
            <button class="text-navy-blue py-2 px-4 fw-semibold nav-link" id="pills-checkout-tab" data-bs-toggle="pill"
              data-bs-target="#pills-checkout" type="button" role="tab" aria-controls="pills-checkout"
              aria-selected="false">
              Checkout
            </button>
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="pills-register-patient" role="tabpanel"
            aria-labelledby="pills-register-patient" tabindex="0">
            <div class="guide-img-container d-flex align-items-center justify-content-between position-relative p-3">
              <div class="wave-arrow">
                <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/arrow-wave.png') }}" alt="" />
              </div>
              <div class="row w-100 align-items-center justify-content-between position-relative flex-row gx-4">
                <div class="col-md-3">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 01
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/r-step-1.png') }}"
                      alt="" />
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 02
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/laptop.webp') }}" alt="" />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 03
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/email-verify.png') }}"
                      alt="" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="pills-register-doctor" role="tabpanel" aria-labelledby="pills-register-doctor"
            tabindex="0">
            <div class="guide-img-container d-flex align-items-center justify-content-between position-relative p-3">
              <div class="wave-arrow">
                <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/arrow-wave.png') }}" alt="" />
              </div>
              <div class="row w-100 align-items-center justify-content-between position-relative flex-row gx-4">
                <div class="col-md-3">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 01
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/r-step-1.png') }}"
                      alt="" />
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 02
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/laptop.webp') }}" alt="" />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 03
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/email-verify.png') }}"
                      alt="" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="pills-order-medicine" role="tabpanel" aria-labelledby="pills-order-medicine"
            tabindex="0">
            <div class="guide-img-container d-flex position-relative p-3">
              <div class="wave-arrow min-w-80">
                <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/arrow-wave-2.png') }}"
                  alt="" />
              </div>
              <div class="row w-100 d-flex align-items-center justify-content-between position-relative flex-row gx-5">
                <div class="col-md-3 align-self-center">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 01
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-med-1.webp') }}"
                      alt="" />
                  </div>
                </div>
                <div class="col-md-3 align-self-start">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 02
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-med-2.webp') }}"
                      alt="" />
                  </div>
                </div>
                <div class="col-md-3 align-self-end">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 03
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-med-3.webp') }}"
                      alt="" />
                  </div>
                </div>
                <div class="col-md-3 align-self-center">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 04
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-med-4.webp') }}"
                      alt="" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="pills-order-lab" role="tabpanel" aria-labelledby="pills-order-lab"
            tabindex="0">
            <div class="guide-img-container d-flex position-relative p-3">
              <div class="wave-arrow min-w-80">
                <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/arrow-wave-3.png') }}"
                  alt="" />
              </div>
              <div class="row w-100 d-flex align-items-center justify-content-between position-relative flex-row gx-3">
                <div class="col-md-5">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 01
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-lab-1.webp') }}"
                      alt="" />
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 02
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-lab-2.webp') }}"
                      alt="" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="pills-checkout" role="tabpanel" aria-labelledby="pills-checkout" tabindex="0">
            <div class="guide-img-container d-flex position-relative p-3">
              <div class="wave-arrow">
                <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/arrow-wave-4.png') }}"
                  alt="" />
              </div>
              <div class="row w-100 d-flex align-items-center justify-content-between position-relative flex-row">
                <div class="col-md-3">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 01
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/checkout-1.webp') }}"
                      alt="" />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 02
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/checkout-2.webp') }}"
                      alt="" />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="w-100 h-100">
                    <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                      Step 03
                    </h5>
                    <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/checkout-3.webp') }}"
                      alt="" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="d-block d-sm-none">
        <div id="guideCarousel" class="carousel slide">
          <div class="d-flex align-items-center justify-content-between bg-navy-blue rounded-2 gap-2 p-2">
            <button class="carousel-control-prev position-static w-auto opacity-100" type="button"
              data-bs-target="#guideCarousel" data-bs-slide="prev">
              <span
                class="bg-blue rounded-circle new-arrow-icon d-flex align-items-center justify-content-center text-white"><i
                  class="fa-solid fa-arrow-left"></i></span>
            </button>
            <h5 id="carousel-title" class="text-white mb-0 text-center">Register as a Patient</h5>
            <button class="carousel-control-next position-static w-auto opacity-100" type="button"
              data-bs-target="#guideCarousel" data-bs-slide="next">
              <span
                class="bg-blue rounded-circle new-arrow-icon d-flex align-items-center justify-content-center text-white"><i
                  class="fa-solid fa-arrow-right"></i></span>
            </button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active" data-title="Register as a Patient">
              <div class="mt-3 d-flex align-items-center justify-content-between position-relative p-3">
                <div class="w-100 d-flex flex-column align-items-center justify-content-between gap-4">
                  <div class="col-md-3">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 01
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/r-step-1.png') }}"
                        alt="" />
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 02
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/laptop.webp') }}"
                        alt="" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 03
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/email-verify.png') }}"
                        alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="carousel-item" data-title="Register as a Doctor">
              <div class="mt-3 d-flex align-items-center justify-content-between position-relative p-3">
                <div class="w-100 d-flex flex-column align-items-center justify-content-between gap-4">
                  <div class="col-md-3">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 01
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/r-step-1.png') }}"
                        alt="" />
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 02
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/laptop.webp') }}"
                        alt="" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 03
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/email-verify.png') }}"
                        alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="carousel-item" data-title="Order Medicine">
              <div class="mt-3 d-flex position-relative p-3">
                <div class="w-100 d-flex flex-column align-items-center justify-content-between gap-4">
                  <div class="col-md-3 align-self-center">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 01
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-med-1.webp') }}"
                        alt="" />
                    </div>
                  </div>
                  <div class="col-md-3 align-self-start">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 02
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-med-2.webp') }}"
                        alt="" />
                    </div>
                  </div>
                  <div class="col-md-3 align-self-end">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 03
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-med-3.webp') }}"
                        alt="" />
                    </div>
                  </div>
                  <div class="col-md-3 align-self-center">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 04
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-med-4.webp') }}"
                        alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="carousel-item" data-title="Order Lab">
              <div class="mt-3 d-flex align-items-center justify-content-center p-3">
                <div class="w-100 d-flex flex-column align-items-center justify-content-between gap-4">
                  <div class="col-md-5">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 01
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-lab-1.webp') }}"
                        alt="" />
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 02
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/order-lab-2.webp') }}"
                        alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="carousel-item" data-title="Checkout">
              <div class="mt-3 d-flex align-items-center justify-content-center p-3">
                <div class="w-100 d-flex flex-column align-items-center justify-content-between gap-4">
                  <div class="col-md-3">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 01
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/checkout-1.webp') }}"
                        alt="" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 02
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/checkout-2.webp') }}"
                        alt="" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="w-100 h-100">
                      <h5 class="bg-navy-blue px-4 fs-14 py-1 rounded-2 text-white mb-2 w-max mx-auto">
                        Step 03
                      </h5>
                      <img class="w-100 object-fit-contain" src="{{ asset('assets/new_frontend/checkout-3.webp') }}"
                        alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 3: Promotional Products Section -->
  <section class="container-fluid px-0 px-md-auto d-flex justify-content-center pt-2 pb-4 promotion-section">
    <div class="w-85 px-sm-0">
      <div class="row gx-0 gy-3 gx-sm-3 gy-sm-3">
        <div class="col-md-7 px-sm-auto">
          <div class="row gy-3 gx-0 g-md-3">
            <div class="col-md-12">
              <div
                class="w-100 h-100 p-3 p-sm-4 bg-blue text-white rounded-4 rounded-sm-3 d-flex flex-column flex-sm-row align-items-center justify-content-sm-between position-relative gap-3 gap-sm-0">
                <div
                  class="w-65 d-flex flex-column text-sm-start text-center align-items-center justify-content-center">
                  <h2 class="fs-28">
                    Community Healthcare Clinics - Medicines
                  </h2>
                  <p class="fs-15">
                    Our pharmacy offers Over the Counter medicines and
                    wellbeing products.
                  </p>
                  <a class="mt-4 px-xxl-4 py-xxl-2 px-3 py-1 bg-white d-flex align-items-center gap-2 rounded-5 consult-btn"
                    href="">
                    <span>Explore More</span>
                    <span
                      class="bg-white rounded-circle border-blue new-arrow-icon text-blue d-flex align-items-center justify-content-center"><i
                        class="fa-solid fa-arrow-right"></i></span>
                  </a>
                </div>
                <div class="w-35 d-flex align-items-end justify-content-center">
                  <img class="w-75 w-sm-100" src="{{ asset('assets/new_frontend/product.webp') }}" alt="" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div
                class="w-100 h-100 bg-light-red p-3 rounded-4 rounded-sm-3 d-flex flex-column align-items-center justify-content-end">
                <img class="w-100" src="{{ asset('assets/new_frontend/baby-care.webp') }}" alt="" />
                <div class="bg-white text-center px-4 py-3 rounded-3">
                  <h5 class="m-0 promotion-product-name">
                    Baby & Mothercare
                  </h5>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div
                class="w-100 h-100 bg-light-sky-blue p-3 rounded-4 rounded-sm-3 d-flex flex-column align-items-center justify-content-end">
                <img class="w-100" src="{{ asset('assets/new_frontend/multi-vitamins.webp') }}" alt="" />
                <div class="bg-white text-center px-4 py-3 rounded-3">
                  <h5 class="m-0 promotion-product-name">Multi-Vitamins</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-5 px-sm-auto">
          <div
            class="w-100 h-100 bg-light-sky-blue p-3 rounded-4 rounded-sm-3 d-flex flex-column align-items-center justify-content-end">
            <img class="w-75" src="{{ asset('assets/new_frontend/personal-care.webp') }}" alt="" />
            <div class="bg-white text-center px-4 py-3 rounded-3">
              <h5 class="m-0 promotion-product-name">Personal Care</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 4: Mission Statement Section -->
  <section class="mission-section container-fluid px-0 d-flex justify-content-center py-sm-5 bg-light-gray-var">
    <div class="w-85">
      <h6 class="d-none d-sm-block fs-14 fw-semibold mb-1 small-head">Mission Statement</h6>
      <h2 class="d-none d-sm-block fw-bold fs-3">
        Consult the best Pakistani and American Doctors Online!
      </h2>
      <div class="row gx-3 mt-3 position-relative">
        <div class="col-12 d-block d-sm-none">
          <div class="w-100">
            <img class="w-100 rounded-3 object-fit-contain" src="{{ asset('assets/new_frontend/mission-2.webp') }}"
              alt="" />
          </div>
        </div>
        <h6 class="d-block d-sm-none mt-4 fs-12 fw-semibold mb-2 small-head">Mission Statement</h6>
        <h2 class="d-block d-sm-none fw-bold fs-22">
          Consult the best Pakistani and American Doctors Online!
        </h2>
        <div class="d-none d-sm-block col-md-3">
          <div class="w-100 h-100">
            <img class="w-100 h-100 rounded-3 object-fit-cover" src="{{ asset('assets/new_frontend/mission-1.webp') }}"
              alt="" />
          </div>
        </div>
        <div class="col-md-5">
          <div class="w-100 h-100">
            <p class="fs-14">
              At Community Healthcare Clinics, we are redefining how
              healthcare is accessed and delivered. As a next-generation
              telemedicine platform, our mission is to bridge the gap
              between patients and quality medical care anytime, anywhere.
              We specialize in building meaningful B2B partnerships with
              hospitals, empowering them to digitize their services by
              onboarding doctors and pharmacy units onto our secure,
              user-friendly platform. With a growing network of patients
              seeking convenient, affordable, and trusted healthcare, we
              offer hospitals the opportunity to expand their reach, reduce
              operational pressure, and enhance care delivery through
              virtual consultations and integrated pharmacy support.
              Together, we aim to build a stronger, tech-enabled healthcare
              ecosystem that prioritizes accessibility and impact.
            </p>
            <a class="mt-4 px-4 py-2 bg-zinc d-flex align-items-center gap-2 rounded-5 text-white consult-btn" href="">
              <span>Consult Now</span>
              <span class="bg-blue rounded-circle new-arrow-icon d-flex align-items-center justify-content-center"><i
                  class="fa-solid fa-arrow-right"></i></span>
            </a>
          </div>
        </div>
        <div class="col-md-4 d-none d-sm-block">
          <div class="w-100 h-100 d-flex flex-column justify-content-between gap-2">
            <img class="w-100 h-50 rounded-3 object-fit-cover" src="{{ asset('assets/new_frontend/mission-2.webp') }}"
              alt="" />
            <img class="w-100 h-50 rounded-3 object-fit-cover" src="{{ asset('assets/new_frontend/mission-3.webp') }}"
              alt="" />
          </div>
        </div>
        <div class="col-12 mt-3 d-block d-sm-none">
          <div class="w-100">
            <img class="w-100 h-50 rounded-3 object-fit-cover" src="{{ asset('assets/new_frontend/mission-3.webp') }}"
              alt="" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 5: Our Partners -->
  <section class="our-partners-section container-fluid px-0 d-flex justify-content-center py-5">
    <div class="row w-85 text-center position-relative z-1">
      <h5 class="fs-14">Our Partners</h5>
      <h2 class="fs-22 fw-bold">Collaborating Partners</h2>
      <div class="partner-scroll w-100 d-flex gap-4 overflow-hidden px-0 mt-4">
        <div class="partner-logo-container w-100 d-flex align-items-center justify-content-between gap-4">
          <img src="{{ asset('assets/new_frontend/adviyaat-logo.webp') }}" alt="" />
          <img src="{{ asset('assets/new_frontend/AGP-Logo.webp') }}" alt="" />
          <img src="{{ asset('assets/new_frontend/dr-essa-logo.webp') }}" alt="" />
          <img src="{{ asset('assets/new_frontend/HHS-Logo.webp') }}" alt="" />
          <img src="{{ asset('assets/new_frontend/khayal-rakhna-logo.webp') }}" alt="" />
          <img src="{{ asset('assets/new_frontend/peridots-logo.webp') }}" alt="" />
        </div>
        <div aria-hidden="true"
          class="partner-logo-container w-100 d-flex align-items-center justify-content-between gap-4">
          <img src="{{ asset('assets/new_frontend/adviyaat-logo.webp') }}" alt="" />
          <img src="{{ asset('assets/new_frontend/AGP-Logo.webp') }}" alt="" />
          <img src="{{ asset('assets/new_frontend/dr-essa-logo.webp') }}" alt="" />
          <img src="{{ asset('assets/new_frontend/HHS-Logo.webp') }}" alt="" />
          <img src="{{ asset('assets/new_frontend/khayal-rakhna-logo.webp') }}" alt="" />
          <img src="{{ asset('assets/new_frontend/peridots-logo.webp') }}" alt="" />
        </div>
      </div>
    </div>
  </section>
  <!-- Section 6: Client Testimonials -->
  <section class="container-fluid px-0 d-flex justify-content-center py-5 bg-light-blue">
    <div class="row px-0 gy-3 g-sm-5 align-items-center flex-row w-85">
      <div class="px-0 ps-sm-0 col-md-4">
        <div class="w-100 d-flex flex-column gap-3">
          <div class="text-center text-md-start">
            <h2 class="m-0 fs-28 me-md-1 d-inline d-lg-block d-xl-inline">
              What Our
            </h2>
            <span class="fw-bold fs-28">Client Says</span>
          </div>
          <p class="new-para text-center text-md-start">
            Lorem, ipsum dolor sit amet consectetur adipisicing elit.
          </p>
          <div class="d-none d-md-flex align-items-center gap-2">
            <button class="carousel-control-prev position-static w-auto opacity-100" type="button"
              data-bs-target="#clientTestimonial" data-bs-slide="prev">
              <span
                class="bg-blue rounded-circle new-arrow-icon d-flex align-items-center justify-content-center text-white"><i
                  class="fa-solid fa-arrow-left"></i></span>
            </button>
            <button class="carousel-control-next position-static w-auto opacity-100" type="button"
              data-bs-target="#clientTestimonial" data-bs-slide="next">
              <span
                class="bg-blue rounded-circle new-arrow-icon d-flex align-items-center justify-content-center text-white"><i
                  class="fa-solid fa-arrow-right"></i></span>
            </button>
          </div>
        </div>
      </div>
      <div class="px-0 pe-sm-0 col-md-8">
        <div class="w-100 d-flex gap-3 justify-content-between align-items-center">
          <div id="clientTestimonial" class="carousel w-100" data-bs-ride="carousel">
            <div class="carousel-inner w-100">
              <div class="carousel-item px-2">
                <div class="card w-100">
                  <div class="card-body w-100 card-min-size">
                    <div class="w-100 d-flex align-items-center justify-content-between">
                      <div>
                        <h5 class="card-title mb-0 fs-14">Irfan Khan</h5>
                        <span
                          class="card-subtitle client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                        </span>
                      </div>
                      <div class="client-pic rounded-circle overflow-hidden">
                        <img class="object-fit-cover w-100 h-100"
                          src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8dXNlcnxlbnwwfHwwfHx8MA%3D%3D"
                          alt="" />
                      </div>
                    </div>
                    <p class="card-text text-overflow mt-3 fs-12">
                      Community healthcare clinics provide an excellent
                      platform for both online consultations and in-person
                      meetings.Online or inclinics sessions the process went
                      smoothly for both sides.The physicians treat their
                      patients with professionalism and good manners.Highly
                      recommend for anyone.
                    </p>
                  </div>
                </div>
              </div>
              <div class="carousel-item px-2">
                <div class="card w-100">
                  <div class="card-body w-100 card-min-size">
                    <div class="w-100 d-flex align-items-center justify-content-between">
                      <div>
                        <h5 class="card-title mb-0 fs-14">Irfan Khan</h5>
                        <span
                          class="card-subtitle client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                        </span>
                      </div>
                      <div class="client-pic rounded-circle overflow-hidden">
                        <img class="object-fit-cover w-100 h-100"
                          src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8dXNlcnxlbnwwfHwwfHx8MA%3D%3D"
                          alt="" />
                      </div>
                    </div>
                    <p class="card-text mt-3 fs-12 text-overflow">
                      Community healthcare clinics provide an excellent
                      platform for both online consultations and in-person
                      meetings.Online or inclinics sessions the process went
                      smoothly for both sides.The physicians treat their
                      patients with professionalism and good manners.Highly
                      recommend for anyone.
                    </p>
                  </div>
                </div>
              </div>
              <div class="carousel-item px-2">
                <div class="card w-100">
                  <div class="card-body w-100 card-min-size">
                    <div class="w-100 d-flex align-items-center justify-content-between">
                      <div>
                        <h5 class="card-title mb-0 fs-14">Irfan Khan</h5>
                        <span
                          class="card-subtitle client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                        </span>
                      </div>
                      <div class="client-pic rounded-circle overflow-hidden">
                        <img class="object-fit-cover w-100 h-100"
                          src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8dXNlcnxlbnwwfHwwfHx8MA%3D%3D"
                          alt="" />
                      </div>
                    </div>
                    <p class="card-text mt-3 fs-12 text-overflow">
                      Community healthcare clinics provide an excellent
                      platform for both online consultations and in-person
                      meetings.Online or inclinics sessions the process went
                      smoothly for both sides.The physicians treat their
                      patients with professionalism and good manners.Highly
                      recommend for anyone.
                    </p>
                  </div>
                </div>
              </div>
              <div class="carousel-item px-2">
                <div class="card w-100">
                  <div class="card-body w-100 card-min-size">
                    <div class="w-100 d-flex align-items-center justify-content-between">
                      <div>
                        <h5 class="card-title mb-0 fs-14">Irfan Khan</h5>
                        <span
                          class="card-subtitle client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                          <span class="">★</span>
                        </span>
                      </div>
                      <div class="client-pic rounded-circle overflow-hidden">
                        <img class="object-fit-cover w-100 h-100"
                          src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8dXNlcnxlbnwwfHwwfHx8MA%3D%3D"
                          alt="" />
                      </div>
                    </div>
                    <p class="card-text mt-3 fs-12 text-overflow">
                      Community healthcare clinics provide an excellent
                      platform for both online consultations and in-person
                      meetings.Online or inclinics sessions the process went
                      smoothly for both sides.The physicians treat their
                      patients with professionalism and good manners.Highly
                      recommend for anyone.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex d-md-none align-items-center justify-content-center gap-2">
        <button class="carousel-control-prev position-static w-auto opacity-100" type="button"
          data-bs-target="#clientTestimonial" data-bs-slide="prev">
          <span
            class="bg-blue rounded-circle new-arrow-icon d-flex align-items-center justify-content-center text-white"><i
              class="fa-solid fa-arrow-left"></i></span>
        </button>
        <button class="carousel-control-next position-static w-auto opacity-100" type="button"
          data-bs-target="#clientTestimonial" data-bs-slide="next">
          <span
            class="bg-blue rounded-circle new-arrow-icon d-flex align-items-center justify-content-center text-white"><i
              class="fa-solid fa-arrow-right"></i></span>
        </button>
      </div>
    </div>
  </section>
  <!-- Section 7: FAQs -->
  <section class="container-fluid py-5 new-faq-section px-0 w-85">
    <div class="row py-3">
      <div class="col-md-8">
        <div class="w-100">
          <h2 class="fs-22 fw-bold text-center text-md-start">Frequently Asked Questions</h2>
          <div class="accordion accordion-flush mt-4" id="faqsSection">
            @foreach ($faqs as $faq)
            <div class="accordion-item border-blue-2 rounded-2 my-1">
              <h2 class="accordion-header border-none my-1">
                <button class="accordion-button collapsed border-none" type="button" data-bs-toggle="collapse"
                  data-bs-target="#faq-{{ $faq->id }}" aria-expanded="false" aria-controls="flush-collapseOne">
                  {{ $faq->question }}
                </button>
              </h2>
              <div id="faq-{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqsSection">
                <div class="accordion-body border-none pt-0">
                  {!! $faq->answer !!}
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Section 8: Mobile App Promotion -->
  <section class="container-fluid py-5 mob-app-section px-0 d-flex justify-content-center align-items-end">
    <div class="row gx-md-5 bg-white px-1 py-3 p-md-4 w-100 w-80 position-relative z-1 rounded-4">
      <div class="col-md-4 position-relative">
        <div class="w-100 position-absolute d-flex align-items-center justify-content-center bottom-0 app-img-cont">
          <img class="w-100 app-img" src="{{ asset('assets/new_frontend/mob-app.webp') }}" alt="" />
        </div>
      </div>
      <div class="col-md-8 px-0 px-md-auto">
        <div class="w-100 px-1 px-md-4">
          <h2 class="fs-22 fw-semibold text-capitalize app-head text-center text-md-start">
            <span class="highlight-blue">Community HealthCare Clinics</span>
            Medical Apps that make Personal Health Easier
          </h2>
          <p class="text-capitalize new-para mt-3 text-center text-md-start">
            “to build an effective healthcare app, start by identifying the
            needs of your patients and the features that will bring them the
            most value."
          </p>
          <div class="row align-items-center justify-content-sm-center justify-content-md-start justify-content-between w-100 gap-3 gap-md-2 mt-4 app-link-container mx-0 mx-sm-auto">
            <div class="col-8 col-sm-6 px-0 px-md-auto">
              <button class="w-100 d-flex align-items-center gap-3 bg-navy-blue rounded-3 p-3">
                <img class="app-icon object-fit-contain" src="{{ asset('assets/new_frontend/apple-logo.webp') }}"
                  alt="" />
                <div class="text-start text-white d-flex flex-column justify-content-between h-100">
                  <h6 class="m-0 fs-12">Download on the</h6>
                  <h5 class="m-0 promotion-product-name">App Store</h5>
                </div>
              </button>
              <button class="mt-2 w-100 d-flex align-items-center gap-3 bg-navy-blue rounded-3 p-3">
                <img class="app-icon object-fit-contain" src="{{ asset('assets/new_frontend/google-play-logo.webp') }}"
                  alt="" />
                <div class="text-start text-white d-flex flex-column justify-content-between h-100">
                  <h6 class="m-0 fs-12">Get it on</h6>
                  <h5 class="m-0 promotion-product-name">Google Play</h5>
                </div>
              </button>
            </div>
            <div class="col col-sm-2 px-0">
              <div class="w-100">
                <h6 class="text-uppercase fw-semibold mb-0 scan-btn">
                  Scan to Download
                </h6>
                <img class="mt-1 w-75 w-sm-100 object-fit-contain scan-img" src="{{ asset('assets/new_frontend/scan.webp') }}" alt="" />
              </div>
            </div>
          </div>
        </div>
      </div>
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
                        <p><i class="fa-solid fa-location-dot" style="color: rgb(255, 53, 53)"></i><span>
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
                  <h5 class="heading">Progressive Center, 4th Floor Suite#410, Main Shahrah Faisal, Karachi</h5>

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
@endsection
