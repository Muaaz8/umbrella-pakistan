@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="copyright" content="© {{ date('Y') }} All Rights Reserved. Powered By UmbrellaMd">
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
    <div class="about_heading_img">
      <div class="about_heading_inner_div">
        <h1>ABOUT US</h1>
        <div class="underline3"></div>
      </div>
    </div>
    <section class="about-section">
      <div class="about-content">
        <h2 class="about-main-heading">
          We Are <span class="about-highlight">Community Healthcare</span> Clinics
        </h2>
        <p class="about-para">
          Umbrella Health Care Systems is bringing the hospital to your home.
          Physicians and patients can share information in real time from a
          single computer screen, enabling remote consultations, monitoring,
          and diagnosis. This system allows healthcare providers to offer
          personalized care from a distance, improving accessibility and
          convenience for patients while maintaining a high standard of
          medical support.
        </p>
        <div class="line"></div>
      </div>
      <div class="about-image">
        <img
          width="500px"
          src="{{ asset('assets/new_frontend/pngegg.png') }}"
          alt="doctor-image"
        />
      </div>
      <div class="tablets"></div>
      <div class="medkit"></div>
    </section>
    <section class="bar-section">
      <div class="bar-content">
        <div class="bar-container">
          <div class="bar-items">
            <div class="bar">
              <img class="icon" src="{{ asset('assets/new_frontend/heart-icon.svg') }}" alt="heart-icon" />
              <h3 class="icon-heading">2147</h3>
              <p class="icon-para">Happy Patients</p>
            </div>
            <div class="bar">
              <img class="icon" src="{{ asset('assets/new_frontend/doctor-with-stethoscope-icon.svg') }}" alt="doctor-icon" />
              <h3 class="icon-heading">2147</h3>
              <p class="icon-para">Qualified Doctors</p>
            </div>
            <div class="bar">
              <img
                class="icon"
                src="{{ asset('assets/new_frontend/medicine-icon.svg') }}"
                alt="medicine-icon"
              />
              <h3 class="icon-heading">2147</h3>
              <p class="icon-para">Pharmacy Products</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="desc-about-section">
      <div class="desc-container">
        <h2 class="desc-main-heading">“ Community Health Clinics provides a complete solution for health-related needs. ”</h2>
        <p class="desc-para">Community Health Clinics E-Pharmacy offers a wide variety of prescription and over-the-counter medications, ensuring convenient access to essential health products for patients directly from their homes.</p>
      </div>
      <div class="desc-bg"></div>
    </section>
    <section id="disclaimer">
      <div class="disclaimer-box"></div>
      <div id="disclaimer-content">
        <div>
          <h2>DISCLAIMER</h2>
          <div class="underline"></div>
        </div>
        <div>
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
            regarding any medical condition or treatment with your physician.
          </p>
          <p>
            Never disregard professional medical advice or delay seeking
            medical treatment because of something you have read on or
            accessed through this web site. umbrella health care systems not
            responsible nor liable for any advice, course of treatment,
            diagnosis or any other information, services or products that you
            obtain through this website.
          </p>
        </div>
      </div>
      <div class="custom-shape-divider-bottom-1731257443">
        <svg
          data-name="Layer 1"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 1200 120"
          preserveAspectRatio="none"
        >
          <path
            d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"
            class="shape-fill"
          ></path>
        </svg>
      </div>
      <div class="disclaimer-blob">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
          <path
            fill="gray"
            d="M46,-39.1C56.3,-35.6,59.3,-17.8,54.9,-4.4C50.5,9.1,38.9,18.1,28.5,30.5C18.1,42.9,9.1,58.5,-2.6,61.1C-14.2,63.7,-28.4,53.2,-43.7,40.8C-59.1,28.4,-75.5,14.2,-75.6,-0.1C-75.6,-14.3,-59.3,-28.6,-44,-32.2C-28.6,-35.7,-14.3,-28.4,1.7,-30.2C17.8,-31.9,35.6,-42.7,46,-39.1Z"
            transform="translate(100 100)"
          />
        </svg>
      </div>
    </section>
  </main>

@endsection
