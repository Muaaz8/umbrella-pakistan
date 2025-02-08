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
    <title>Order Complete | Umbrella Health Care Systems</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')

    <!-- ******* THANK-YOU STATRS ******** -->
    <div class="container">
      <div class="row">
        <div class=thankyoucontent>
          <div class="wrapper-1">
             <div class="wrapper-2">
                <img src="{{ asset('assets/images/thankyou.png') }}" alt="thank-you-umbrella">
              <h1>Thank you!</h1>
               <p>We received your order request.</p>
               <h3>Your Order ID # <b> {{ $id }} </b></h3>
               <p>Please check you email for invoice</p>
               <button class="go-home"><a href="{{ env('APP_URL') }}">Go to home page</a></button><b class="px-4"> or </b>
               <button class="go-home"><a href="{{ route('home') }}">Go to dashboard</a></button>
             </div>

         </div>
        </div>
      </div>
    </div>

@endsection
