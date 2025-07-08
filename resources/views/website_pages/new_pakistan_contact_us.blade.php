@extends('layouts.new_pakistan_layout') @section('meta_tags')
<meta charset="utf-8" />
<meta
    name="google-site-verification"
    content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0"
/>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="language" content="en-us" />
<meta name="robots" content="index,follow" />
<meta
    name="copyright"
    content="© 2022 All Rights Reserved. Powered By Community Healthcare Clinics"
/>
<meta name="url" content="https://www.communityhealthcareclinics.com" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://www.umbrellamd.com" />
<meta
    property="og:site_name"
    content="Community Healthcare Clinics | communityhealthcareclinics.com"
/>
<meta name="twitter:site" content="@umbrellamd	" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="author" content="Umbrellamd" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
@foreach ($tags as $tag)
<meta name="{{ $tag->name }}" content="{{ $tag->content }}" />
@endforeach
<link
    rel="icon"
    href="{{ asset('asset_frontend/images/logo.ico') }}"
    type="image/x-icon"
/>
@endsection @section('page_title') @if ($title != null)
<title>{{ $title->content }}</title>
@else
<title>Community Healthcare Clinics</title>
@endif @endsection @section('top_import_file')
<script
    src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
    async
    defer
></script>
<style>
    .contact-card {
        background: white;
        border-radius: 20px;
        padding: 10px 0px;
        text-align: center;
        border: 1px solid #023afe;
        height: 100%;
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .contact-card:hover {
        transform: translateY(-5px);
    }

    .contact-icon {
        width: 35px;
        height: 35px;
        background: #2c5cdd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 10px;
    }

    .contact-icon i {
        color: white;
        font-size: 18px;
    }

    .contact-card h5 {
        color: #333;
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .contact-card p {
        color: #666;
        margin: 0;
        font-size: 13px;
    }

    .section-title {
        text-align: center;
    }

    .section-title h2 {
        color: #333;
        font-weight: 600;
        font-size: 30px;
    }

    .section-title p {
        color: #666;
        max-width: 600px;
        margin: 0 auto;
        font-size: 14px;
        line-height: 1.6;
    }

    .contact-form-section {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        margin-top: 30px;
    }

    .image-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .team-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        margin-bottom: 10px;
    }

    .form-control:focus {
        border-color: #2c5cdd;
        box-shadow: 0 0 0 0.2rem rgba(44, 92, 221, 0.25);
    }

    .submit-btn {
        background: #333;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 0.3s ease;
    }

    .submit-btn:hover {
        background: #555;
        color: white;
    }

    .submit-btn i {
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .contact-section2 {
            padding: 40px 0;
        }

        .section-title h2 {
            font-size: 28px;
        }

        .contact-card {
            margin-bottom: 20px;
        }

        .form-container {
            padding: 30px 20px;
        }

        .image-container {
            min-height: 300px;
        }
    }
</style>
@endsection @section('bottom_import_file')
<script>
    var onloadCallback = function () {
        grecaptcha.render("google_recaptcha", {
            sitekey: "6LctFXkqAAAAAHG3mAMi56uxbdOJ3iOjAKXhyeyW",
        });
    };

    $("#contact-form").submit(function (e) {
        var rcres = grecaptcha.getResponse();
        if (rcres.length) {
            console.log(rcres);
        } else {
            alert("Please verify reCAPTCHA");
            return false;
        }
    });
</script>

@endsection @section('content')
<div class="contact-section2">
    <section class="new-header w-85 mx-auto rounded-3">
        <div class="new-header-inner p-4">
            <h1 class="fs-30 mb-0 fw-semibold">Contact Us</h1>
            <div>
                <a class="fs-12" href="{{ url('/') }}">Home</a>
                <span class="mx-1 align-middle">></span>
                <a class="fs-12"
                    href="{{ route('contact_us') }}">
                    Contact Us
                </a>

            </div>
        </div>
    </section>
    <div class="container-fluid w-85 mt-1 py-3 px-0">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h5>Address:</h5>
                    <p>
                        Progressive Center, 4th Floor Suite#410,<br />Main
                        Shahrah Faisal, Karachi
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h5>Phone:</h5>
                    <p>0337-2356994 </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h5>Email:</h5>
                    <p>contact@communityhealthcareclinics.com</p>
                </div>
            </div>
        </div>

        <div class="section-title">
            <h2>Have A Question? Contact Us.</h2>
            <p>
                Give Us A Call Or Send An Email. Our Team Is Always Ready To
                Provide Customer Care Help. For More Information, Visit Us.
            </p>
        </div>

        <!-- Contact Form Section -->
        <div class="contact-form-section">
            <div class="row no-gutters">
                <div class="col-lg-4">
                    <div class="image-container rounded-4">
                        <img
                            src="{{
                                asset(
                                    'asset_frontend/images/contact-form-img.png'
                                )
                            }}"
                            alt="Medical Team"
                            class="team-image rounded-4"
                        />
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="form-container">
                        <form id="contact-form" action="/contact" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="fname"
                                            id="firstName"
                                            placeholder="First Name"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="lname"
                                            id="lastName"
                                            placeholder="Last Name"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input
                                            type="tel"
                                            class="form-control"
                                            name="ph"
                                            id="phone"
                                            placeholder="Phone"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            id="email"
                                            placeholder="E-Mail"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    name="subject"
                                    id="subject"
                                    placeholder="Subject"
                                />
                            </div>

                            <div class="form-group">
                                <textarea
                                    class="form-control"
                                    id="message"
                                    name="message"
                                    rows="5"
                                    placeholder="Message"
                                ></textarea>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="d-flex align-items-center">
                                    <div id="google_recaptcha" class="g-recaptcha"></div>
                                </div>
                            </div>

                            <button
                                class="cursor-pointer d-none d-sm-flex mt-4 px-4 py-2 bg-zinc d-flex align-items-center gap-2 rounded-5 text-white consult-btn"
                                type="submit">
                                <span>Submit Now</span>
                                <span
                                    class="bg-blue rounded-circle new-arrow-icon d-flex align-items-center justify-content-center"
                                    ><i class="fa-solid fa-arrow-right"></i
                                ></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
