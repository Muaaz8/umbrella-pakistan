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
@foreach($tags as $tag)
<meta name="{{$tag->name}}" content="{{$tag->content}}">
@endforeach
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
@if($title != null)
    <title>{{$title->content}} | Umbrella Health Care Systems</title>
@else
    <title>Contact Us | Umbrella Health Care Systems</title>
@endif
@endsection

@section('top_import_file')
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
@endsection


@section('bottom_import_file')
<script>
  <?php header('Access-Control-Allow-Origin: *'); ?>
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  var onloadCallback = function() {
    grecaptcha.render('google_recaptcha', {
        'sitekey' : '6Lfx12gjAAAAAFk6DNZK-O5frG--VljoUTj1zko9'
      });
  };

  $('#contact-us_form').submit(function(e){
      var rcres = grecaptcha.getResponse();
      if(!rcres.length)
      {
        alert('Please verify reCAPTCHA');
        return false;
      }
  });

  function refresh_recaptcha() {
      grecaptcha.reset();
  }
</script>
@endsection

@section('content')


<!-- ******* CONTACT-FORM STATRS ******** -->
<section class="contact-wrapper">
      <div class="container">
        <div class="card" style="height:630px;">
          <form id="contact-us_form" class="row form" action="/contact" method="POST">
            @csrf
            <div class="col-md-4 left-side">
              <div class="top">
                <h4>Have a Question? Contact Us.</h4>
                <p>
                  Give us a call or send an email. Our team is always ready to
                  provide customer care help. For more information, visit us.
                </p>
              </div>
              <div class="medium">
                <i class="fa fa-phone"></i>
                <p>+1 (407) 693-8484</p>
                <i class="fa fa-envelope"></i>
                <p id="SendMail">contact@umbrellamd.com</p>
                <!-- <i class="fa fa-map-marker"></i> -->
                <!-- <p>9914 Kennerly Rd, Saint Louis, MO, 63128</p> -->
              </div>
              <div class="last">
                <span><i class="fa-brands fa-facebook"></i></span>
                <span> <i class="fa-brands fa-twitter"></i></span>
                <span><i class="fa-brands fa-instagram"></i></span>
                <span><i class="fa-brands fa-linkedin"></i></span>
              </div>
            </div>
            <div class="col-md-8 right-side">
              <div class="card-details">
                <div class="input-group">
                  <div class="input">
                    <input type="text" name="fname" required="required" maxlength="15" />
                    <span>First Name</span>
                  </div>
                  <div class="input">
                    <input type="text" name="lname" required="required" maxlength="15" />
                    <span>Last Name</span>
                  </div>
                </div>
                <div class="input-group">
                  <div class="input">
                    <input class="input" type="email" name="email" required="required" placeholder="abc@xyz.com" maxlength="20"/>
                    <span>E-mail</span>
                  </div>
                  <div class="input">
                    <input type="text" name="ph" required="required" maxlength="11"/>
                    <span>Phone no.</span>
                  </div>
                </div>
              </div>
              <div class="below-content">
                <div class="input">
                    <input type="text" name="subject" required="required" maxlength="190"/>
                    <span>Subject</span>
                </div>
                <div class="text-area mt-4">
                  <textarea name="message" required="required"></textarea> <span>Message</span>
                </div>
                <div class="text-area">
                  <div class="d-flex align-items-center">
                      <div id="google_recaptcha" class="g-recaptcha"></div>
                      <button type="button" class="btn"><i onclick="refresh_recaptcha()" class="ms-2 fa fa-rotate-right"
                          aria-hidden="true"
                          style="background-color: #08295a; color: #fff; padding: 10px; border-radius: 100%; cursor: pointer;"></i>Recaptcha</button>
                  </div>
                </div>
                <div class="button"><button type="submit">Send Message</button></div>
              </div>
            </div>
        </form>
        </div>
      </div>
    </section>
<!-- ******* CONTACT-FORM ENDS ******** -->
@endsection
