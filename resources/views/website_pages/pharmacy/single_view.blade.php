@extends('layouts.new_web_layout')

@section('meta_tags')
@foreach($meta_tags as $tags)
<meta name="{{$tags->name}}" content="{{$tags->content}}">
@endforeach
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
<style>
    .detail-pharmcy-content li{
      list-style: disc;
    }
  </style>
@endsection


@section('page_title')
    @if($title!=null)
        <title>{{$title->content}}</title>
    @else
        <title>{{$products[0]->name}} | Pharmacy | Umbrella Health Care Systems</title>
    @endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')


    <!-- ******* DETAIIl-PHARMACY STATRS ******** -->
    <section class="detail-pharmacy-bg">
        <div class="container">
          <div class="row">
            <div class="back-arrow-detail-pharmacy">
            <?php $item = $products[0]; ?>
              <h1>{{ $item->name }}</h1>
              <!-- <i class="fa-solid fa-circle-arrow-left" onclick="history.back()"></i> -->
              {{-- <i class="fa-solid fa-circle-arrow-left go-back" onclick="window.location=document.referrer;"></i> --}}
              <nav aria-label="breadcrumb">
                <i class="fa-solid fa-arrow-left"></i>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('pharmacy') }}">Pharmacy</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </section>


<section>
  <div class="container">
    <div class=" py-3">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
      <div class="text-danger">
      <h5>Prescription Required for this Medicine</h5>
      <small>if you need a prescription please do an E-visit with our online doctor’s </small>
    </div>
    <div>
      <!-- <button class="btn detailed_talk_doc">Talk To Doctors</button> -->
      @if(!Auth::check())
              <button class="btn detailed_talk_doc" data-bs-toggle="modal" data-bs-target="#loginModal">Talk To Doctor</button>
            @elseif(Auth::user()->user_type == 'patient')
              <button class="btn detailed_talk_doc" onclick="window.location.href='/patient/evisit/specialization'">Talk To Doctor</button>
            @elseif(Auth::user()->user_type == 'doctor')
              <button class="btn detailed_talk_doc" onclick="window.location.href='/doctor/patient/queue'">Go To Waiting Room</button>
            @endif
    </div>
  </div>
      </div>
    <div class="row detail-pharmcy-wrapper">
      <div class="add-to-cart-detail">
          <div>

         <?php $item = $products[0]; ?>

            <h3>{{ $item->name }}</h3>

          </div>

      </div>

      <div class="detail-pharmcy-content">
        <div>
          <h4 class="">Detail Description</h4>
        </div>

        @if (!empty($item->description))
                        {!! $item->description !!}
                    @endif
      </div>
    </div>
  </div>
</section>

    <!-- ******* DETAIIl-PHARMACY ENDS ******** -->
    <!-- ******* LOGIN-REGISTER-MODAL STARTS ******** -->
<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="loginModalLabel">Select Registration Type</h5>
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








@endsection
