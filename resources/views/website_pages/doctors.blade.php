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
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    <title>Our Doctors | Umbrella Health Care Systems</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')

<!-- ******* ALL-DOCTORS STATRS ******** -->
<section class="about-bg">
    <div class="container">
      <div class="row">
      <div class="back-arrow-about">
          <!-- <i class="fa-solid fa-circle-arrow-left" onclick="history.back()"></i> -->
          {{-- <i class="fa-solid fa-circle-arrow-left go-back" onclick="window.location=document.referrer;"></i> --}}
          <h1>MEET OUR DOCTORS</h1>
          <nav aria-label="breadcrumb">
          <i class="fa-solid fa-arrow-left"></i>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Our Doctors</a></li>
          </ol>
        </nav>
        </div>
      </div>
    </div>
  </section>

<section>
<div class="container">
    <div class="row pharmacy-tabs-cards my-5">
    @foreach ($doctors as $doctor)
    <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
        <div class="card p-0">
            <div class="card-image">
                <img src="{{$doctor->user_image}}" alt="" class="profile"/>
            </div>
            <div class="card-content d-flex flex-column align-items-center">
                <h4 class="pt-2">Dr. {{ $doctor->name . ' ' . $doctor->last_name }}</h4>
                <h5>Primary Care</h5>
                <h6>
                    @if(Auth::check())
                        <a href="{{ route('patient_evisit_specialization') }}"><i class="fa-solid fa-phone"></i>Talk To Doctor</a>
                    @else
                        <a href="{{ route('login') }}"><i class="fa-solid fa-phone"></i>Talk To Doctor</a>
                    @endif

                </h6>
            </div>
        </div>
    </div>
    @endforeach

    </div>
    <div class="text-center mt-5 mb-3 prescription-req-view-btn">
        {!! $doctors->links() !!}
    </div>
</div>
</section>

<!-- ******* ALL-DOCTORS ENDS ******** -->

@endsection
