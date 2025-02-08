@extends('layouts.new_web_layout')

@section('meta_tags')
<meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
<meta charset="utf-8" />
<meta name="p:domain_verify" content="d2ee0f53ab0b3c0147e662b58a2d26c3" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="language" content="en-us">
<meta name="robots" content="index,follow" />
<meta name="copyright" content="© {{ date('Y') }} All Rights Reserved. Powered By Community Healthcare Clinics">
<meta name="url" content="https://www.communityhealthcareclinics.com">
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://www.umbrellamd.com" />
<meta property="og:site_name" content="Umbrellamd.com" />
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
    <title>{{ $title->content }} | Umbrellamd.com</title>
@else
    <title>Umbrellamd.com</title>
@endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script type="text/javascript">
    <?php
header('Access-Control-Allow-Origin: *');
        ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{ asset('assets/js/home.js?n=1') }}"></script>
@endsection
@section('content')
@php
    $page = DB::table('pages')->where('url', '/landing-page')->first();
    $section = DB::table('section')
        ->where('page_id', $page->id)
        ->where('section_name', 'main section')
        ->where('sequence_no', '1')
        ->first();
    $top_content = DB::table('content')
        ->where('section_id', $section->id)
        ->first();
    $image_content = DB::table('images_content')
        ->where('section_id', $section->id)
        ->first();
@endphp
<!-- ******* SLIDER STARTS ******** -->

<section class="mb-3 landing_bg">
        <div class="container py-5">
            <div class="row section_custom_height py-4">

                <div class="col-md-6 col-12 d-flex align-items-start justify-content-center flex-column section_height">
                    @if($top_content)
                        {!! $top_content->content !!}
                    @else
                        <h1 class="text-left fw-bold">Main section Heading</h1>
                        <div class="section_content">
                            <p class="my-3">Lorem ipsum dolor sit amet consectetur adipisicing elitasd. Assumenda cupiditate impedit, voluptate,
                        </div>
                    @endif
                    <a href="{{ route('about_us') }}" class="section_custom_btn">Read More</a>
                </div>

                <div class="col-md-6 col-12 d-flex align-items-center justify-content-center section_height hide_image">
                    <img width="500" height="auto" src="{{ asset('assets/images/section.png') }}"  alt='{{ $image_content? $image_content->alt :'UMBRELLA HEALTH CARE SYSTEMS' }}'>
                </div>

            </div>

        </div>
    </section>

<!-- ******* SLIDER ENDS ******** -->

<!-- ******* STEPS STARTS ******** -->
<section class="steps-wrapper">
    @if (Auth()->user())
    @else
        <div class="container">
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="row">
                        <div class="text-center mb-4 heading-bg">
                            <h1 class="py-4">Steps For New User</h1>
                        </div>
                        <div class="col-lg-3 mb-5 col-sm-6">
                            <!-- <a data-bs-toggle="modal" data-bs-target="#loginModal" title="LOGIN | REGISTER"> -->
                            <div class="steps-inner-div" data-bs-toggle="modal" data-bs-target="#loginModal"
                                title="LOGIN | REGISTER">
                                <div class="">
                                    <img src="{{ asset('assets/images/step1.png ') }}" width="60" height="60"
                                        alt="LOGIN | REGISTER" />
                                    <h1>LOGIN | REGISTER</h1>
                                </div>
                                <div class="for-steps">
                                    <p>STEP 1</p>
                                </div>
                            </div>
                            <!-- </a> -->
                        </div>

                        <div class="col-lg-3 mb-5 col-sm-6">
                            <!-- <a data-bs-toggle="modal" data-bs-target="#e-visitModal" title="E-VISIT | PHARMACY | LAB TEST"> -->
                            <div class="steps-inner-div" data-bs-toggle="modal" data-bs-target="#e-visitModal"
                                title="E-VISIT | PHARMACY | LAB TEST">
                                <div class="">
                                    <img src="{{ asset('assets/images/step2.png ') }}" width="60" height="60"
                                        alt="E-VISIT | PHARMACY" />
                                    <h1>E-VISIT | PHARMACY <br />LAB TEST</h1>
                                </div>
                                <div class="for-steps">
                                    <p>STEP 2</p>
                                </div>
                            </div>
                            <!-- </a> -->
                        </div>

                        <div class="col-lg-3 mb-5 col-sm-6">
                            <a href="{{ route('login') }}" title="CONFIRM ORDER">
                                <div class="steps-inner-div">
                                    <div class="">
                                        <img src="{{ asset('assets/images/step3.png ') }}" width="60" height="60"
                                            alt=">CONFIRM ORDER" />
                                        <h1>CONFIRM ORDER</h1>
                                    </div>
                                    <div class="for-steps">
                                        <p>STEP 3</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-3 mb-5 col-sm-6">
                            <a href="{{ route('login') }}" title="CHECKOUT">
                                <div class="steps-inner-div">
                                    <div class="">
                                        <img src="{{ asset('assets/images/step4.png ') }}" width="60" height="60"
                                            alt="CHECKOUT" />
                                        <h1>CHECKOUT</h1>
                                    </div>
                                    <div class="for-steps">
                                        <p>STEP 4</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
<!-- ******* STEPS ENDS ******** -->
@if (session()->get('error_message'))
    <div id="errorDiv1" class="alert alert-danger col-12 col-md-6 offset-md-3 mt-2 text-center">
        @php
            $es = session()->get('error_message');
        @endphp
        <span role="alert"> <strong>{{ $es }}</strong></span>

    </div>
@endif
<section class="mt-3">
    <div class="container" style="cursor:pointer;" id="banner1"
        onclick="window.location.href='/add_discount_in_cart/cbc---complete-blood-count-h-h-rbc-indices-wbc-plt'">
        <!-- <img class="adds_img" src="{{ asset('assets/images/ad_banner.jpg') }}" width="100%" height="100%" alt="Adds"
                              loading="lazy"> -->
    </div>
</section>

<!-- ///////////////////////////// -->
<!-- ///////////////////////////// -->
<!-- ///////////////////////////// -->



<section class="tabs-wrapper">
    <div class="container py-5">
        <div class="text-center mb-4 heading-bg">
            <h1 class="py-4">Our Services</h1>
        </div>

        <div class="card-container2">

            <div class="text-left service_card shadow">
                <div class="svg_div">

                    <svg class="service_card_svg mb-2" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 122.88 104.28">
                    <defs>
                        <style>
                            .cls-1 {
                                fill-rule: evenodd;
                            }
                        </style>
                    </defs>
                    <title>public-health</title>
                    <path class="cls-1"
                        d="M61.11,9.2C65.4,4.73,68.4.87,75,.11,87.4-1.32,98.8,11.38,92.54,23.87c-1.78,3.57-5.41,7.8-9.42,12-4.41,4.56-9.28,9-12.7,12.41l-9.31,9.24-7.69-7.41c-9.25-8.91-24.35-20.14-24.85-34C28.23,6.29,35.91.05,44.75.16c7.9.11,11.22,4,16.36,9ZM90.55,93.69l.77,0a1.08,1.08,0,0,1,1.08,1.08v8.47a1.08,1.08,0,0,1-1.08,1.08H66.61a1.08,1.08,0,0,1-1.08-1.08V94.73a1.08,1.08,0,0,1,1.08-1.08c7.24,0,17-.57,23.94,0Zm-.83-2.18H66.62a1.07,1.07,0,0,1-1-1.12c.09-2,.27-3.93.47-5.93.2-1.67.37-3.13.59-4.4a17.2,17.2,0,0,1,.93-3.52v0a11.77,11.77,0,0,1,1.81-3.07,22.2,22.2,0,0,1,3.11-3.12c.83-.69,1.57-1.28,2.45-1.9l.88-.53L79.31,66a58.72,58.72,0,0,0,6.14-3.53,58.73,58.73,0,0,0,7.11-5.94C94.33,55,96.83,53,98.4,51.37a15.15,15.15,0,0,1,3.16-2.9,5.2,5.2,0,0,1,3.2-1h0a2.5,2.5,0,0,1,.75.19,2.57,2.57,0,0,1,.57.35l.09.09a2.27,2.27,0,0,1,.49.65,3.33,3.33,0,0,1,.26.67v0a4.53,4.53,0,0,1-.3,2.71A7.27,7.27,0,0,1,105,54.58L90.29,71a1.06,1.06,0,0,0,.86,1.77,1.08,1.08,0,0,0,.69-.3q7.26-8.18,14.62-16.3a10.44,10.44,0,0,0,1-1.11,9.12,9.12,0,0,0,.75-1.12c2.56-4.07,6.34-14.9,8.45-19.93l.2-.31a6.54,6.54,0,0,1,1.31-1.09l.06,0a3.84,3.84,0,0,1,1.55-.54h0a2.4,2.4,0,0,1,.76,0,2.48,2.48,0,0,1,.64.24l.11.06A2.66,2.66,0,0,1,122,33a4.23,4.23,0,0,1,.44.85A9.23,9.23,0,0,1,122.8,38a26.24,26.24,0,0,1-1.29,5.37c-1.33,4.55-2.62,9.14-3.86,13.73a37.71,37.71,0,0,1-2.43,7.26A34.86,34.86,0,0,1,110,71.72c-2.35,2.7-4.66,5.13-6.91,7.38s-4.48,4.36-6.64,6.4l-5.85,5.62a1.08,1.08,0,0,1-.83.39Zm-33.51,0H33.16a1.08,1.08,0,0,1-.83-.39l-2.8-2.73-3.09-2.93c-2.2-2.08-4.44-4.2-6.6-6.36l0,0c-2.25-2.24-4.54-4.66-6.87-7.34a35,35,0,0,1-5.28-7.37,37.74,37.74,0,0,1-2.42-7.26C4,52.52,2.74,47.89,1.37,43.37A26,26,0,0,1,.08,38a9.11,9.11,0,0,1,.34-4.14A3.85,3.85,0,0,1,.86,33a2.63,2.63,0,0,1,.68-.69l.12-.06A2.23,2.23,0,0,1,2.3,32a2.38,2.38,0,0,1,.76,0h0a3.74,3.74,0,0,1,1.55.54l.07,0A6.43,6.43,0,0,1,6,33.66l.2.32c2.1,5,5.93,15.94,8.46,19.93A7.63,7.63,0,0,0,15.42,55l0,0a9.57,9.57,0,0,0,1,1.06c5,5,9.72,11.1,14.63,16.3a1.08,1.08,0,0,0,1.45,0,1,1,0,0,0,.36-.74,1.05,1.05,0,0,0-.26-.77L17.88,54.58a7.12,7.12,0,0,1-1.68-2.37,4.38,4.38,0,0,1-.3-2.71v0a2.83,2.83,0,0,1,.26-.67,2.19,2.19,0,0,1,.49-.66,2.58,2.58,0,0,1,.66-.43,2.77,2.77,0,0,1,.74-.19h.06a5.2,5.2,0,0,1,3.2,1,14.89,14.89,0,0,1,3.15,2.9c1.69,1.77,4,3.47,5.84,5.19a60.32,60.32,0,0,0,7.11,5.94A59.77,59.77,0,0,0,43.56,66L47,67.86l.87.54c.88.62,1.63,1.22,2.45,1.9a21.79,21.79,0,0,1,3.12,3.12,11.73,11.73,0,0,1,1.8,3.07l0,0a17.87,17.87,0,0,1,.93,3.52c.21,1.27.39,2.73.58,4.4.21,2,.37,3.92.48,5.92a1.07,1.07,0,0,1-1,1.13ZM32.12,93.67c6.87.61,16.92,0,24.14,0a1.08,1.08,0,0,1,1.08,1.08v8.47a1.08,1.08,0,0,1-1.08,1.08H31.55a1.07,1.07,0,0,1-1.07-1.08V94.73a1.07,1.07,0,0,1,1.07-1.08l.57,0ZM59,17.52h4.79a1.63,1.63,0,0,1,1.63,1.62v5.21h5.21A1.63,1.63,0,0,1,72.29,26v4.78a1.63,1.63,0,0,1-1.62,1.63H65.46v5.07a1.64,1.64,0,0,1-1.63,1.63H59a1.63,1.63,0,0,1-1.62-1.63V32.39H52.21a1.64,1.64,0,0,1-1.63-1.63V26a1.63,1.63,0,0,1,1.63-1.62h5.21V19.14A1.63,1.63,0,0,1,59,17.52Z" />
                </svg>
            </div>

                <div class="content_div">
                    <h3>Primary Care</h3>
                    <hr class="my-2">
                    @php
                        $section = DB::table('section')
                            ->where('page_id', $page->id)
                            ->where('section_name', 'primary-care')
                            ->where('sequence_no', '1')
                            ->first();
                        $top_content = DB::table('content')
                            ->where('section_id', $section->id)
                            ->first();
                    @endphp
                    @if($top_content)
                        {!! $top_content->content !!}
                    @else
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda cupiditate impedit, voluptate,
                            incidunt odio atque ea aliquid vitae tempora repudiandae nesciunt.</p>
                    @endif
                    <a href="{{ route('primary') }}" class="service_card_btn">Read more</a>
                </div>

            </div>

            <div class="text-left service_card shadow">
                <div class="svg_div">

                    <svg class="service_card_svg mb-2" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 106.58 122.88">
                    <title>psychological</title>
                    <path
                    d="M21.56,91.08a1.86,1.86,0,1,1,1.61,3.35,10.09,10.09,0,0,1-5.53.83,18.85,18.85,0,0,1-3.52-.75c.05.48.1.95.16,1.43.18,1.34.42,2.72.73,4.13a1.88,1.88,0,0,1-.23,1.38,14.72,14.72,0,0,0-1.67,5.33,4.33,4.33,0,0,0,1.11,3.48,7.68,7.68,0,0,0,4.06,1.84,29.79,29.79,0,0,0,10.36-.34,65.86,65.86,0,0,0,11.85-3.26,32,32,0,0,0,8.3-4.5c2.18-1.74,3.18-4.59,4-7,.18-.51.35-1,.43-1.2l.25-.67c1.07-2.94,2.93-8,5.34-9.9a1.86,1.86,0,1,1,2.27,3c-1.56,1.2-3.18,5.65-4.12,8.23-.19.53-.15.43-.25.69-.2.54-.31.84-.41,1.14-1,2.91-2.21,6.31-5.23,8.72a35.61,35.61,0,0,1-9.27,5c-.79.31-1.6.6-2.41.87a29.69,29.69,0,0,1-.13,3.65,30,30,0,0,1-.91,5,1.86,1.86,0,0,1-3.6-1,27.26,27.26,0,0,0,.81-4.39c.07-.72.11-1.44.12-2.17-2.19.6-4.37,1.08-6.41,1.45a33.47,33.47,0,0,1-11.7.34,11.24,11.24,0,0,1-6-2.88,7.89,7.89,0,0,1-2.17-6.32,17.58,17.58,0,0,1,1.84-6.3c-.25-1.23-.47-2.5-.64-3.82-.19-1.47-.32-3-.38-4.45a.81.81,0,0,1,0-.15l0-.19c-.27-2.81-.3-5.55-.34-8.32V82.7l-4.3-.25h0a5.76,5.76,0,0,1-3-.92,5.34,5.34,0,0,1-1.9-2.15,6.74,6.74,0,0,1-.61-3,9.4,9.4,0,0,1,1.62-4.85l4.6-9.41c2.26-4.61,2.23-5.22,2.14-7.64,0-1.09-.1-2.48,0-4.57a34.89,34.89,0,0,1,.9-7.09,23.89,23.89,0,0,1,1.55-4.5L2.41,34.71a1.86,1.86,0,0,1-1-2.45,3.07,3.07,0,0,1,.2-.36h0c20-27.26,42.58-34.75,61.69-31A52.88,52.88,0,0,1,92,17.4a55.62,55.62,0,0,1,14.15,29.51C108.6,64.81,101.41,84,79.28,97a1.68,1.68,0,0,1-.6.22,67.73,67.73,0,0,0,.49,7.91,61.56,61.56,0,0,0,3.48,14.27,1.86,1.86,0,0,1-3.49,1.31,64.92,64.92,0,0,1-3.7-15.13,76,76,0,0,1-.08-17,1.86,1.86,0,1,1,3.7.38c-.14,1.36-.24,2.7-.31,4,19.53-12,25.89-29.36,23.65-45.51a51.81,51.81,0,0,0-13.2-27.5A49.28,49.28,0,0,0,62.63,4.56c-17.37-3.41-38,3.33-56.58,27.68l8,3.47A1.85,1.85,0,0,1,15,38.42a18.62,18.62,0,0,0-2.12,5.31,31.76,31.76,0,0,0-.8,6.34c-.06,2,0,3.28,0,4.31.13,3.16.16,3.95-2.51,9.42L4.93,73.24a1.91,1.91,0,0,1-.15.28,5.85,5.85,0,0,0-1.08,3,2.51,2.51,0,0,0,.23,1.18,1.64,1.64,0,0,0,.59.68,2.1,2.1,0,0,0,1.06.32h.15l5.33.31a1.61,1.61,0,0,1,.54-.09h.19l4.91.42A1.86,1.86,0,1,1,16.39,83h-.1l-2.77-.16v.38c0,2.42.06,4.81.26,7.22A17.41,17.41,0,0,0,18,91.54a6.34,6.34,0,0,0,3.56-.46ZM68.91,34.39a5.41,5.41,0,0,1,1.76.29,7,7,0,0,1,3,2.12,6.79,6.79,0,0,1,1.59,3.63,6.45,6.45,0,0,1-.09,2,8,8,0,0,1,1.52,1.84,6.23,6.23,0,0,1,.87,3.32A5.91,5.91,0,0,1,76.41,51l0,.05a8.53,8.53,0,0,1-2,1.83,7.5,7.5,0,0,1-1.29,4.61,6,6,0,0,1-3.77,2.59,5.92,5.92,0,0,1-6.93,4.1,4.81,4.81,0,0,1-3-2.44A5.17,5.17,0,0,1,56,64.26a5.36,5.36,0,0,1-4.37-1.1,5.29,5.29,0,0,1-2-3,5.38,5.38,0,0,1-1.48-.28,7,7,0,0,1-3-2.06,6.84,6.84,0,0,1-1.63-3.51,6.43,6.43,0,0,1,.07-2.18,7.71,7.71,0,0,1-1.38-1.65,6.22,6.22,0,0,1-1-3.37,6,6,0,0,1,1.22-3.54,8.33,8.33,0,0,1,1.92-1.86V41a7.42,7.42,0,0,1,1.65-4.44,5.84,5.84,0,0,1,3.58-2.15,6.13,6.13,0,0,1,.18-.61A5.58,5.58,0,0,1,52.31,31,5.29,5.29,0,0,1,56,30.36a5.14,5.14,0,0,1,3.28,2.33,5.09,5.09,0,0,1,3.28-2.33,5.18,5.18,0,0,1,3.42.49,5.66,5.66,0,0,1,2.43,2.34,5.16,5.16,0,0,1,.47,1.2Zm-8.22.82v23a1.07,1.07,0,0,1,.21.5c.33,2,1.18,2.93,2.14,3.19a3.31,3.31,0,0,0,2.72-.63A3,3,0,0,0,67.13,59c0-1-.73-2.14-2.71-3.15a1.18,1.18,0,1,1,1.08-2.1c2.2,1.13,3.37,2.51,3.79,3.91a3.84,3.84,0,0,0,1.89-1.5A5.26,5.26,0,0,0,72,52.49v-.08a1.18,1.18,0,0,1,.57-1.17,6.43,6.43,0,0,0,1.91-1.63l0,0a3.68,3.68,0,0,0,.75-2.07,3.83,3.83,0,0,0-.57-2.06,5.6,5.6,0,0,0-1.47-1.62,1.19,1.19,0,0,1-.46-1.36A4.17,4.17,0,0,0,73,40.69a4.53,4.53,0,0,0-1-2.37,4.61,4.61,0,0,0-2-1.4,3.11,3.11,0,0,0-1.07-.17,5.29,5.29,0,0,1-1.08,2A1.17,1.17,0,1,1,66,37.19a2.49,2.49,0,0,0,.41-2.88A3.36,3.36,0,0,0,64.94,33a2.91,2.91,0,0,0-1.87-.28,3.48,3.48,0,0,0-2.38,2.54Zm5.57,7.6a1.19,1.19,0,0,1,1.39-1.92c.21.16.43.33.65.53a7.47,7.47,0,0,1,2.46,5,8,8,0,0,1-1.51,5.29l-.06.07c-.17.22-.33.42-.49.6A1.18,1.18,0,1,1,67,50.76l.39-.48a5.56,5.56,0,0,0,1.06-3.71,5.12,5.12,0,0,0-1.68-3.4,5,5,0,0,0-.46-.36Zm-7.93-6.43,0-.06h0v0h0v0h0v0h0v0c-.53-2.13-1.55-3.11-2.61-3.33a3,3,0,0,0-2.06.38,3.18,3.18,0,0,0-1.39,1.6,2.68,2.68,0,0,0,1,3,1.18,1.18,0,0,1-1.6,1.73,5.87,5.87,0,0,1-1.68-2.52,3.65,3.65,0,0,0-1.85,1.27,5.11,5.11,0,0,0-1.12,3,5.93,5.93,0,0,0,0,.93,1.2,1.2,0,0,1-.54,1.24,6.78,6.78,0,0,0-1.88,1.65,3.76,3.76,0,0,0-.77,2.18,4,4,0,0,0,.63,2.11,5.47,5.47,0,0,0,1.37,1.48,1.19,1.19,0,0,1,.45,1.35A4,4,0,0,0,45.94,54,4.43,4.43,0,0,0,47,56.25a4.59,4.59,0,0,0,2,1.36,3.8,3.8,0,0,0,.84.17,7.18,7.18,0,0,1,3.94-4.05,1.18,1.18,0,1,1,1.07,2.1C52.73,56.9,52,58.12,52,59.17a2.79,2.79,0,0,0,1.16,2.14,3,3,0,0,0,2.44.64c1.06-.22,2.09-1.2,2.61-3.35a1.19,1.19,0,0,1,.17-.36V36.38Zm-8.25,5.27a1.18,1.18,0,1,1,1.65,1.68,5.11,5.11,0,0,0-1.53,3.45,5.59,5.59,0,0,0,1.26,3.73A1.18,1.18,0,0,1,49.62,52a7.92,7.92,0,0,1-1.77-5.3,7.39,7.39,0,0,1,2.23-5ZM33,28.41l-.19.28c-.23.33-.46.67-.66,1s-.43.71-.64,1.09h0a32.3,32.3,0,0,0-3.37,8.89,31.24,31.24,0,0,0-.6,9.45,31.88,31.88,0,0,0,.76,4.66,30.73,30.73,0,0,0,1.47,4.47,30.39,30.39,0,0,0,2.12,4.18,31.61,31.61,0,0,0,2.75,3.83c.38.45.77.89,1.15,1.3s.76.81,1.18,1.21.83.81,1.24,1.17.83.72,1.27,1.07a32.79,32.79,0,0,0,6.63,4.14,31.33,31.33,0,0,0,3.61,1.41,30.08,30.08,0,0,0,3.71.94l.13,0V73.38h-.09a28.17,28.17,0,0,1-3-.86,27.4,27.4,0,0,1-2.91-1.19l0,0a26.54,26.54,0,0,1-2.8-1.56,29.1,29.1,0,0,1-2.64-1.89c-.4-.32-.79-.65-1.15-1s-.74-.67-1.09-1-.71-.71-1-1.07-.67-.74-1-1.11l0,0a25.94,25.94,0,0,1-4.21-6.92,27.47,27.47,0,0,1-1.27-3.88,26.72,26.72,0,0,1-.71-8.18,25.89,25.89,0,0,1,.57-4.11,27.53,27.53,0,0,1,1.18-4,27.85,27.85,0,0,1,1.76-3.76c.21-.38.41-.72.6-1l.62-.93a.73.73,0,0,1,.56-.31.72.72,0,0,1,.74.69c.07,1.79.07,3.72.2,5.49a2,2,0,0,0,.19.78,2,2,0,0,0,.42.6l0,0a2,2,0,0,0,.67.41,2,2,0,0,0,.73.13H40a2.15,2.15,0,0,0,1.35-.61l0,0a2.07,2.07,0,0,0,.54-1.45l-.21-6.21-.24-6.2a2.23,2.23,0,0,0-.18-.78,2.4,2.4,0,0,0-.43-.61l0,0a2,2,0,0,0-1.43-.54c-3.41.13-8.68,1.21-12.25,1.71a2,2,0,0,0-.75.26,1.91,1.91,0,0,0-.6.53,1.79,1.79,0,0,0-.35.72,1.9,1.9,0,0,0-.05.79,2.14,2.14,0,0,0,.26.76,2.12,2.12,0,0,0,1.25,1,2.09,2.09,0,0,0,.79,0l4.66-.66a.7.7,0,0,1,.5.12.71.71,0,0,1,.19,1Zm34.64,48.4a31,31,0,0,0,3.27-1.17,31.4,31.4,0,0,0,3.14-1.54,35.07,35.07,0,0,0,3-1.89l.11-.07L74.2,69.21l-.07.05c-.77.52-1.54,1-2.33,1.43s-1.6.82-2.43,1.18-1.69.66-2.54.92-1.69.48-2.55.66l-.12,0v4.15l.13,0a32.13,32.13,0,0,0,3.36-.79Zm19-15.2a32.92,32.92,0,0,0,1.52-3.1l0,0a32,32,0,0,0,1.17-3.28,30,30,0,0,0,.79-3.4l0-.13H86l0,.09c-.18.88-.4,1.75-.67,2.63a24.61,24.61,0,0,1-.92,2.53,26.45,26.45,0,0,1-1.2,2.45,25.35,25.35,0,0,1-1.42,2.29l0,.08,2.92,2.93.08-.11a31.23,31.23,0,0,0,1.9-2.94Zm3.13-22c-.1-.45-.22-.9-.34-1.33s-.25-.86-.4-1.32-.3-.91-.45-1.31c-.24-.64-.51-1.3-.81-2s-.58-1.27-.91-1.92-.67-1.26-1-1.83-.71-1.16-1.1-1.75l-.08-.11L81.72,31l0,.07c.28.45.56.91.83,1.38l0,0c.26.47.53,1,.78,1.48s.48,1,.7,1.53.45,1.07.64,1.58.44,1.27.65,1.94.36,1.35.51,2.06h4.15l0-.13c-.08-.43-.17-.87-.28-1.34ZM74.92,18.86c-.76-.44-1.57-.85-2.4-1.23s-1.64-.71-2.49-1h0c-.85-.29-1.73-.56-2.62-.77l-1.2-.27a30.74,30.74,0,0,0-3.58-.46c-.41,0-.82,0-1.2,0H60.14l-1.24.06h0l-1.27.11a2.12,2.12,0,0,0-.76.24,2,2,0,0,0-.61.51,2.1,2.1,0,0,0-.37.69v0A2.1,2.1,0,0,0,56,18.21l0,0a2.07,2.07,0,0,0,.52.62,2.11,2.11,0,0,0,.68.36h0a2,2,0,0,0,.75.07l1.14-.1c.34,0,.7,0,1.07-.05h1.06c.73,0,1.36.07,2.08.13s1.35.19,2,.31l1,.22c.72.18,1.42.38,2.1.61s1.37.5,2,.78,1.3.61,1.94,1,1.22.71,1.8,1.1l.12.08,2.93-2.94-.11-.07c-.74-.53-1.5-1-2.28-1.48Zm9.25,18.72h0Z" />
                </svg>
            </div>
            <div class="content_div">
                <h3>PSYCHIATRY</h3>
                <hr class="my-2">
                @php
                    $section = DB::table('section')
                        ->where('page_id', $page->id)
                        ->where('section_name', 'psychiatry')
                        ->where('sequence_no', '1')
                        ->first();
                    $top_content = DB::table('content')
                        ->where('section_id', $section->id)
                        ->first();
                @endphp
                @if($top_content)
                    {!! $top_content->content !!}
                @else
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda cupiditate impedit, voluptate,
                        incidunt odio atque ea aliquid vitae tempora repudiandae nesciunt.</p>
                @endif
                <a href="{{ route('psychiatry', ['slug' => 'anxiety']) }}" class="service_card_btn">Read more</a>
            </div>
            </div>

            <div class="text-left service_card shadow">
                <div class="svg_div">

                    <svg class="service_card_svg mb-2" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 99.75 122.88">
                    <title>symptoms-body-pain</title>
                    <path
                        d="M26.68,77.37A8.95,8.95,0,1,1,20.35,80a9,9,0,0,1,6.33-2.62Zm0,4.52a4.43,4.43,0,1,1-4.43,4.43,4.42,4.42,0,0,1,4.43-4.43Zm4.69-.27a6.65,6.65,0,1,0,1.94,4.7,6.61,6.61,0,0,0-1.94-4.7ZM35.27,0A8.95,8.95,0,1,1,29,2.62,8.93,8.93,0,0,1,35.27,0Zm0,4.52A4.43,4.43,0,1,1,30.85,9a4.43,4.43,0,0,1,4.42-4.43ZM40,4.25A6.65,6.65,0,1,0,41.91,9,6.64,6.64,0,0,0,40,4.25Zm27.3,53.54A8.95,8.95,0,1,1,61,60.41a8.93,8.93,0,0,1,6.32-2.62Zm0,4.52a4.43,4.43,0,1,1-4.42,4.43,4.43,4.43,0,0,1,4.42-4.43ZM72,62a6.65,6.65,0,1,0,1.94,4.7A6.62,6.62,0,0,0,72,62ZM5.39,36.85C1.07,34.12-.75,31.86.28,28A7.23,7.23,0,0,1,9.13,22.9L35.41,38l5.27,1.25a31.6,31.6,0,0,0,17.72.16l7-1.63L90.67,23.27a7.24,7.24,0,0,1,8.85,5.11c1,3.85-1.44,6.75-5.11,8.85l-27,15.48.06,1.49h-.19a12.57,12.57,0,0,0-4.58.88l-.21.08a12.55,12.55,0,0,0-4.06,2.72,12.74,12.74,0,0,0-2.64,3.86l-.09.21a12.54,12.54,0,0,0,6.58,16.29l.21.09a12.41,12.41,0,0,0,4.79,1,11.89,11.89,0,0,0,2.16-.19c1.26,13.28,2.6,26.63,2.6,38.68,0,6.23-13.25,7.37-14.5,0L52.71,82H47.06l-4.82,35.74c-.82,7-14.93,6.59-14.51,0L29,98.64a12.62,12.62,0,0,0,2.45-.74,12.45,12.45,0,0,0,3.84-2.51l.23-.21a12.38,12.38,0,0,0,2.62-3.86l.1-.21a12.45,12.45,0,0,0,1-4.79,12.29,12.29,0,0,0-1-4.8,12.46,12.46,0,0,0-2.71-4.06h0a12.63,12.63,0,0,0-3.85-2.62l-.21-.1c-.27-.11-.54-.21-.82-.3l1.5-22.24L5.39,36.85ZM49.9,4.59A14.56,14.56,0,1,1,35.53,21.48,12.45,12.45,0,0,0,43.91,18l.23-.21A12.55,12.55,0,0,0,46.76,14l.1-.21a12.63,12.63,0,0,0,1-4.79,12.32,12.32,0,0,0-.69-4.09,14.17,14.17,0,0,1,2.78-.27Z" />
                </svg>
            </div>
            <div class="content_div">

                <h3>PAIN MANAGEMENT</h3>
                <hr class="my-2">
                @php
                    $section = DB::table('section')
                        ->where('page_id', $page->id)
                        ->where('section_name', 'pain-management')
                        ->where('sequence_no', '1')
                        ->first();
                    $top_content = DB::table('content')
                        ->where('section_id', $section->id)
                        ->first();
                @endphp
                @if($top_content)
                    {!! $top_content->content !!}
                @else
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda cupiditate impedit, voluptate,
                        incidunt odio atque ea aliquid vitae tempora repudiandae nesciunt.</p>
                @endif
                <a href="{{ route('pain.management') }}" class="service_card_btn">Read more</a>
            </div>
            </div>

            <div class="text-left service_card shadow">
            <div class="svg_div">

                <svg class="service_card_svg mb-2" version="1.1" viewBox="144 144 512 512"
                xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <g>
                            <path
                                d="m566.89 444.08-80.293 24.402c0-1.5742 0.78906-3.9375 0.78906-5.5117 0-16.531-13.383-29.914-29.914-29.914h-44.082c-3.9375-25.977-25.977-45.656-52.742-45.656h-76.359v-10.234c0-4.7227-3.1484-7.8711-7.8711-7.8711h-72.43c-4.7227 0-7.8711 3.1484-7.8711 7.8711v162.16c0 4.7227 3.1484 7.8711 7.8711 7.8711h72.422c4.7227 0 7.8711-3.1484 7.8711-7.8711v-10.234l129.89 33.852c6.2969 1.5742 12.594 2.3633 19.68 2.3633 11.809 0 23.617-2.3633 34.637-7.8711l121.23-59.828c7.0859-3.9375 12.594-9.4453 14.957-17.32 2.3633-7.8711 1.5742-15.742-2.3633-22.828-8.6562-11.809-22.824-17.32-35.418-13.383zm-299.14 88.168h-55.895v-146.42h56.68v146.42zm320.39-55.895c-0.78906 3.9375-3.9375 6.2969-7.0859 7.8711l-121.23 59.828c-13.383 6.2969-28.34 7.8711-42.508 4.7227l-133.82-34.637v-110.21h76.359c21.254 0 37.785 17.32 37.785 37.785 0 4.7227 3.1484 7.8711 7.8711 7.8711h51.168c7.8711 0 14.168 6.2969 14.168 14.168 0 7.8711-6.2969 14.168-14.168 14.168l-72.422 0.003907c-4.7227 0-7.8711 3.1484-7.8711 7.8711 0 4.7227 3.1484 7.8711 7.8711 7.8711h72.422c3.9375 0 7.0859-0.78906 10.234-2.3633l103.91-31.488c6.2969-1.5742 12.594 0.78906 15.742 6.2969 2.3633 2.3672 2.3633 6.3047 1.5781 10.238z">
                            </path>
                            <path
                            d="m547.99 345.68-65.336-33.062c-5.5117-2.3633-11.02-3.9375-16.531-3.9375-14.168 0-26.766 7.8711-33.062 20.469-9.4453 18.105-1.5742 40.934 16.531 49.594l65.336 33.062c5.5117 2.3633 11.02 3.9375 16.531 3.9375 14.168 0 26.766-7.8711 33.062-20.469 4.7227-8.6602 5.5117-18.895 2.3633-28.34-3.1484-9.4453-10.234-16.527-18.895-21.254zm-101.55-9.4453c3.9375-7.0859 11.02-11.809 18.895-11.809 3.1484 0 6.2969 0.78906 9.4453 2.3633l25.977 12.594-19.68 38.574-25.977-12.594c-9.4492-5.5117-13.387-18.895-8.6602-29.129zm103.91 51.957c-5.5117 10.234-18.105 14.957-29.125 9.4453l-25.977-12.594 19.68-38.574 25.977 12.594c10.23 5.5117 14.953 18.895 9.4453 29.129z">
                            </path>
                            <path
                                d="m299.24 294.51c0 33.852 27.551 60.613 60.613 60.613 33.852 0 60.613-27.551 60.613-60.613 0-33.852-27.551-60.613-60.613-60.613s-60.613 27.551-60.613 60.613zm15.742 0c0-22.043 15.742-40.934 37-44.082v88.953c-20.469-3.9336-37-22.039-37-44.871zm90.527 0c0 22.043-15.742 40.934-37 44.082l0.003907-88.164c20.465 3.9375 36.996 22.043 36.996 44.082z">
                            </path>
                        </g>
                    </g>
                </svg>
            </div>
            <div class="content_div">

                <h3>SUBSTANCE ABUSE</h3>
                <hr class="my-2">
                @php
                    $section = DB::table('section')
                        ->where('page_id', $page->id)
                        ->where('section_name', 'substance-abuse')
                        ->where('sequence_no', '1')
                        ->first();
                    $top_content = DB::table('content')
                        ->where('section_id', $section->id)
                        ->first();
                @endphp
                @if($top_content)
                    {!! $top_content->content !!}
                @else
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda cupiditate impedit, voluptate,
                        incidunt odio atque ea aliquid vitae tempora repudiandae nesciunt.</p>
                @endif
                <a href="{{ route('substance', ['slug' => 'first-visit']) }}" class="service_card_btn">Read more</a>
            </div>
            </div>

        </div>

        </div>


    </div>
</section>

<section class="my-5">
    <div class="container pt-3">
        <div class="my-3">
            <h1 class="text-decoration-underline">What more umbrella has so offer ?</h1>
        </div>
        <div class="row">

            <div class="col-12 col-lg-4">
                <div class="required-cards">
                    <div class="card_container prescription-req-div">
                        <div class="card" data-label="Concern with doctor">
                            <div class="card-container prescription-req-content">
                                <div class="d-flex align-items-center pt-3">
                                    <div class="prescription-req-heading md:w-100">
                                        <h3 class="text-center">E-Visit</h3>
                                    </div>
                                </div>
                                <hr class="my-2">
                                @php
                                    $section = DB::table('section')
                                        ->where('page_id', $page->id)
                                        ->where('section_name', 'e-visit')
                                        ->where('sequence_no', '1')
                                        ->first();
                                    $top_content = DB::table('content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                @if($top_content)
                                    {!! $top_content->content !!}
                                @else
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda cupiditate impedit, voluptate,
                                        incidunt odio atque ea aliquid vitae tempora repudiandae nesciunt.</p>
                                @endif
                            </div>
                            <div class="prescription-req-btn">
                                <a href="#" title="Learn More"><button>Learn More</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="required-cards">
                    <div class="card_container prescription-req-div">
                        <div class="card" data-label="Concern with doctor">
                            <div class="card-container prescription-req-content">
                                <div class="d-flex align-items-center pt-3">
                                    <div class="prescription-req-heading md:w-100">
                                        <h3 class="text-center">Lab Testing</h3>
                                    </div>
                                </div>
                                <hr class="my-2">
                                @php
                                    $section = DB::table('section')
                                        ->where('page_id', $page->id)
                                        ->where('section_name', 'lab-test')
                                        ->where('sequence_no', '1')
                                        ->first();
                                    $top_content = DB::table('content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                @if($top_content)
                                    {!! $top_content->content !!}
                                @else
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda cupiditate impedit, voluptate,
                                        incidunt odio atque ea aliquid vitae tempora repudiandae nesciunt.</p>
                                @endif
                            </div>
                            <div class="prescription-req-btn">
                                <a href="#" title="Learn More"><button>Learn More</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="required-cards">
                    <div class="card_container prescription-req-div">
                        <div class="card" data-label="Concern with doctor">
                            <div class="card-container prescription-req-content">
                                <div class="d-flex align-items-center pt-3">
                                    <div class="prescription-req-heading lg:w-100">
                                        <h3 class="text-center">Imaging</h3>
                                    </div>
                                </div>
                                <hr class="my-2">
                                @php
                                    $section = DB::table('section')
                                        ->where('page_id', $page->id)
                                        ->where('section_name', 'imaging')
                                        ->where('sequence_no', '1')
                                        ->first();
                                    $top_content = DB::table('content')
                                        ->where('section_id', $section->id)
                                        ->first();
                                @endphp
                                @if($top_content)
                                    {!! $top_content->content !!}
                                @else
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda cupiditate impedit, voluptate,
                                        incidunt odio atque ea aliquid vitae tempora repudiandae nesciunt.</p>
                                @endif
                            </div>
                            <div class="prescription-req-btn">
                                <a href="#" title="Learn More"><button>Learn More</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ///////////////////////////// -->
<!-- ///////////////////////////// -->
<!-- ///////////////////////////// -->

<!-- ******* TABS STARTS ******** -->
<!-- put the tab code here if want to revert the code -->
<!-- ******* TABS ENDS ******** -->

<section>
    <div class=" my-4">
        <section>
            <div class="container my-4" id="banner3">
                <!-- <img class="adds_img" src="{{ asset('assets/images/ad_banner.jpg') }}" width="100%" height="100%" alt="Adds"
                                  loading="lazy"> -->
            </div>
        </section>

        <section class="problem-wrapper care-sec ">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="text-center mb-3 mt-5 home-headings heading-bg">
                        @php
                            $section = DB::table('section')
                                ->where('page_id', $page->id)
                                ->where('section_name', 'why-header')
                                ->first();
                            $top_content = DB::table('content')
                                ->where('section_id', $section->id)
                                ->first();
                        @endphp
                        @if ($top_content)
                            {!! $top_content->content !!}
                        @else
                            <h2 class="py-2"><strong>Why Choose Umbrella</strong></h2>
                        @endif
                    </div>
                    @php
                        $section = DB::table('section')
                            ->where('page_id', $page->id)
                            ->where('section_name', 'why-description')
                            ->first();
                        $top_content = DB::table('content')
                            ->where('section_id', $section->id)
                            ->first();
                        $image_content = DB::table('images_content')
                            ->where('section_id', $section->id)
                            ->first();
                    @endphp

                    <div class="col-md-6 prblem-wrapper-inner-div mt-3">
                        <div>
                            @if ($top_content)
                                {!! $top_content->content !!}
                            @else
                                <p class="p-4 text-sm">Get started now! Doctors are ready to help you get the care you need,
                                    anywhere and anytime in the United States. Access to doctors, psychiatrists,
                                    psychologists, therapists and other medical experts, care is available from 07:00 AM
                                    to 08:00 PM. Select and see your favorite providers again and again, right from your
                                    smartphone,
                                    tablet or computers.</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div>
                            <img class="border_image" src="{{ asset('assets/images/happy-doc-2.png ') }}" width="90%" height="90%"
                                alt='{{ $image_content? $image_content->alt :'UMBRELLA HEALTH CARE SYSTEMS' }}'/>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</section>

<!-- /////////////////////////////////  -->
<!-- /////////////////////////////////  -->
<!-- /////////////////////////////////  -->
<section class="mt-3 mb-5">
    <div class="container pt-3">
        <div class="mb-4 ">
            <h1 class="p2-4 text-decoration-underline">FAQ's</h1>
        </div>
        <div class="accordion" id="accordionExample">
            @foreach ($faqs as $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{$faq->id}}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{$faq->id}}" aria-expanded="false" aria-controls="collapse{{$faq->id}}">
                            <strong>Q. {{ $faq->question }}</strong>
                        </button>
                    </h2>
                    <div id="collapse{{$faq->id}}" class="accordion-collapse collapse" aria-labelledby="heading{{$faq->id}}"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p>{!! $faq->answer !!}</p>
                        </div>
                    </div>
                </div>
            @endforeach
            {{--
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <strong>Q. What can I be treated for?</strong>
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <p>Our doctors can treat many conditions, including cold and flu symptoms, allergies, asthma,
                            bronchitis, sinus infections, and much more. They can also provide prescription refills and
                            help manage chronic conditions.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <strong>Q. Can I get a prescription?</strong>
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <p>Yes, our doctors can write prescriptions for many medications. They can also provide
                            prescription refills and help manage chronic conditions.</p>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
</section>

<!-- //////////////////////////////// -->
<!-- //////////////////////////////// -->
<!-- //////////////////////////////// -->

<!-- ******* MEDICAL-SPECIALIST STARTS ******** -->
{{-- <section class="medical-specialist-sec animatable fadeInUp ">
    <div class="container">
        <div class="row pb-5">
            <div class="text-center mb-5 mt-2 heading-bg">
                <h1>Our Medical Specialists</h1>
            </div>
            @foreach ($doctors as $doctor)

            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="face front-face">
                        <img src="{{$doctor->user_image}}" alt="Our Medical Specialists" class="profile"
                            loading="lazy" />

                        <div class="pt-3 text-uppercase name">Dr. {{ $doctor->name . ' ' . $doctor->last_name }}
                        </div>
                        <div class="designation">General</div>
                    </div>
                    <div class="face back-face">
                        <span class="fas fa-quote-left"></span>
                        <div class="testimonial text-break">
                            {{ $doctor->bio ?? ''}}
                        </div>
                        <span class="fas fa-quote-right"></span>
                    </div>
                </div>
            </div>

            @endforeach

        </div>
        <div class="text-center">
            <a href="{{ route('our_doctors') }}"><button>Meet All Doctors</button></a>
        </div>
    </div>
</section> --}}
<!-- ******* MEDICAL-SPECIALIST ENDS ******** -->

<!-- *******PARTNERS SECTION STARTS ******** -->
<!-- <section class="partner-sec  animatable fadeInDown">
                              <div class="container">
                                <div class="row partners-wrapper justify-content-between">
                                  <div class="col-md-3 col-sm-4 ">
                                    <div class="partners-div">
                                      <img src="{{ asset('assets/images/partner (1).svg') }}" alt="">
                                    </div>
                                  </div>
                                  <div class="col-md-3 col-sm-4 ">
                                    <div class="partners-div">
                                      <img src="{{ asset('assets/images/partner(2).png ') }}" alt="" style="width: 55%;">
                                    </div>
                                  </div>
                                  <div class="col-md-3 col-sm-4 ">
                                    <div class="partners-div">
                                      <img src="{{ asset('assets/images/partner(3).png ') }}" alt="">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </section> -->
<!-- *******PARTNERS SECTION ENDS ******** -->



{{-- <div class="container-fluid">
    <div class="row">

        <div class="col-md-2">
            <div class="text-center">
                <a class="btn btn-info notification">Info</a>
            </div>
        </div>
    </div>
</div> --}}



<!-- =============== Calling Modal Start ==========================  -->
<!-- Button trigger modal -->




<!-- =============== Calling Modal Ends ==========================  -->






<!-- ******* LOGIN-REGISTER-MODAL STARTS ******** -->
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
                        <a href="{{ route('e-visit') }}"><button>E-VISIT</button></a>
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
                  </a>" style="max-width: 640px; width: 100%; aspect-ratio: 16/9;" frameborder="0">
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
                    <div class="content">
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
                <div class="custom-modal">
                    <div class="succes succes-animation icon-top"><i class="fa fa-times"></i></div>
                    <div class="content">
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


<!-- ******* LABTEST ENDS ******** -->

<!-- Modal -->
<div class="modal fade" id="therapy_login" tabindex="-1" aria-labelledby="therapy_loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Register To Get Enrolled</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-login-reg-btn my-3">
                    <a href="{{ route('pat_register') }}"> REGISTER AS A PATIENT</a>
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

@endsection
