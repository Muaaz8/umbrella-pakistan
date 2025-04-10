@extends('layouts.new_pakistan_layout')

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
        <title>{{ $title->content }}</title>
    @else
        <title>Community Healthcare Clinics</title>
    @endif
@endsection


@section('top_import_file')
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
@endsection


@section('bottom_import_file')
<script>
    var onloadCallback = function() {
        grecaptcha.render('google_recaptcha', {
            'sitekey' : '6LctFXkqAAAAAHG3mAMi56uxbdOJ3iOjAKXhyeyW'
          });
    };

    $('#contact-form').submit(function(e)
    {
        var rcres = grecaptcha.getResponse();
        if(rcres.length){
            console.log(rcres);
        }else{
            alert('Please verify reCAPTCHA');
            return false;
        }
    });
</script>
@endsection

@section('content')
    <main>
        <div class="contact-section">
            <div class="contact-content">
                <h1>Contact Us</h1>
                <div class="underline3"></div>
            </div>
            <div class="custom-shape-divider-bottom-17311915372">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                    preserveAspectRatio="none">
                    <path
                        d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                        opacity=".25" class="shape-fill"></path>
                    <path
                        d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                        opacity=".5" class="shape-fill"></path>
                    <path
                        d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                        class="shape-fill"></path>
                </svg>
            </div>
        </div>

        <div class="container px-3 px-sm-5 bg-white rounded">
            <div class="row px-2">
                <!-- Left Side -->
                <div class="col-md-5 col-lg-4 contact-left py-5">
                    <div class="w-100 px-lg-2">
                        <div class="text-center contact-info">
                            <i class="fa-solid fa-location-dot"></i>
                            <div class="d-flex flex-column align-items-start justify-content-center px-3">
                                <div class="fw-bold">Address</div>
                                <p>Progressive Center, 4th Floor Suite#410, Main Shahrah Faisal, Karachi</p>
                            </div>
                        </div>
                        <hr class="hr">
                        <div class="text-center contact-info my-2">
                            <i class="fa-solid fa-phone"></i>
                            <div class="d-flex flex-column align-items-start justify-content-center px-3">
                                <div class="fw-bold">Phone</div>
                                <p>0337-2350684</p>
                            </div>
                        </div>
                        <hr class="hr">
                        <div class="text-center contact-info">
                            <i class="fa-regular fa-envelope-open"></i>
                            <div class="d-flex flex-column align-items-start justify-content-center px-3">
                                <div class="fw-bold">Email</div>
                                <p class="contact-email-para">contact@communityhealthcareclinics.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-icons container">
                        <a href="https://www.facebook.com/CommunityHealthcareClinics"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    </div>


                </div>
                <!-- Right Side -->
                <div class="col-md-7 col-lg-8 p-3 p-lg-5 contact-right">
                    <h4 class="fw-bold">Have a Question? Contact Us.</h4>
                    <p class="py-2">Give us a call or send an email. Our team is always ready to provide customer care
                        help. For more information, visit us.</p>
                    <form class="row form" id="contact-form" action="/contact" method="POST">
                        @csrf
                        <div class="row gx-4 gy-2">
                            <!-- First Name and Last Name -->
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="fname" placeholder="First Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="lname" placeholder="Last Name" required>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <!-- Phone -->
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="ph" placeholder="Phone" required>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <!-- Email -->
                            <div class="col-md-12">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <!-- Subject -->
                            <div class="col-md-12">
                                <input type="subject" class="form-control" name="subject" placeholder="Subject">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <!-- Message -->
                            <div class="col-md-12">
                                <textarea class="form-control" rows="4" name="message" placeholder="Message" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="d-flex align-items-center">
                                <div id="google_recaptcha" class="g-recaptcha"></div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="contact-btn">Send Now</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>
@endsection
