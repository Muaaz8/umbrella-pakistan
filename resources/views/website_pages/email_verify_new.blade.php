@extends('layouts.new_pakistan_layout')
@section('meta_tags')
<meta charset="utf-8" />
<meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="language" content="en-us">
<meta name="robots" content="index,follow" />
<meta name="copyright" content="© 2022 All Rights Reserved. Powered By UmbrellaMd">
<meta name="url" content="https://www.umbrellamd.com">
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://www.umbrellamd.com" />
<meta property="og:site_name" content="Umbrella Health Care Systems | Umbrellamd.com" />
<meta name="twitter:site" content="@umbrellamd	">
<meta name="twitter:card" content="summary_large_image" />
<meta name="author" content="Umbrellamd">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
<style>
    #resendMail{
        border: 2px solid var(--green);
        border-radius: 30px;
        background: #ffff;
        padding: 3px 15px;
    }
</style>
@endsection


@section('page_title')
    <title>Email Verfication</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<script>

    document.getElementById("resendMail").onclick = function() {
        $('#resendMail').attr('disabled', true);
        $('#form_send').submit();
    }

    document.getElementById("resendMail").disabled = true;
    var emailCounter=60;
    var emailResendInterval=setInterval(() => {
    emailCounter--;

    document.getElementById("resendMail").disabled = true;
        if(emailCounter<1)
          {
            clearInterval(emailResendInterval);

            $("#emailCounter").hide();
            document.getElementById("resendMail").disabled = false;

          }else{
            $('#emailCounter').html('('+emailCounter+")");
          }
    }, 1000);

    </script>
@endsection

@section('content')

    <!-- ******* 404-ERROR STATRS ******** -->

    <section class="Email-confirm-sec">
        <div class="container">
            <div class="row my-5">
              <div class="col-md-12 Email-confirm-wrap text-center">
                <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_1jbmcfmf.json"  background="transparent"  speed="1"  style="width: auto; height: 200px;margin-bottom: -70px;    margin-top: -40px; " loop autoplay></lottie-player>
              <h1>Email Verification</h1>
              @if(Session::has('message'))
                <div class="alert alert-success">
                {{ Session::get('message')}}
                </div>
              @endif
              <p>Please verify your email address to accesss Community Health Care Clinics web portal.</p>
             <div>
              <p>If you did not get it.</p>
              <form action="{{ route('resend') }}" id="form_send" method="POST">
                @csrf
                <button type="submit" id="resendMail"><span id="emailCounter"></span> Resend Email</button>
              </form>
              </div>
              </div>
              </div>
        </div>
    </section>


    <!-- ******* 404-ERROR ENDS ******** -->

@endsection
