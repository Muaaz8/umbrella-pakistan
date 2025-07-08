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
<style>
    .gallery {
        --s: 150px;
        /* control the size */
        --g: 10px;
        /* control the gap */
        --f: 1.5;
        /* control the scale factor */

        display: grid;
        gap: var(--g);
        width: calc(3*var(--s) + 2*var(--g));
        aspect-ratio: 1;
        grid-template-columns: repeat(3, auto);
    }

    .gallery>img {
        width: 0;
        height: 0;
        min-height: 100%;
        min-width: 100%;
        object-fit: cover;
        cursor: pointer;
        filter: grayscale(40%);
        transition: .35s linear;
    }

    .gallery img:hover {
        filter: grayscale(0);
        width: calc(var(--s)*var(--f));
        height: calc(var(--s)*var(--f));
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
@endsection

@section('content')
@php
$page = DB::table('pages')->where('url', '/e-visit')->first();
@endphp
<main class="e-visit-page">
    <section class="new-header w-85 mx-auto rounded-3">
        <div class="new-header-inner p-4">
            <h1 class="fs-30 mb-0 fw-semibold">E-Visit</h1>
            <div>
                <a class="fs-12" href="{{ url('/') }}">Home</a>
                <span class="mx-1 align-middle">></span>
                <a class="fs-12" href="{{ route('e-visit') }}">E-Visit</a>
            </div>
        </div>
    </section>
    <section class="page-para fs-14 my-3 px-5 mx-2 w-85 mx-auto text-center">
        @php
        $section = DB::table('section')
        ->where('page_id', $page->id)
        ->where('section_name', 'top-section')
        ->where('sequence_no', '1')
        ->first();
        $top_content = DB::table('content')
        ->where('section_id', $section->id)
        ->first();
        $image_content = DB::table('images_content')
        ->where('section_id', $section->id)
        ->first();
        @endphp
        @if ($top_content)
        {!! $top_content->content !!}
        @else
        <p>Community Healthcare Clinics provide you with facility to visit doctors,
            therapist, or medical expert online. Find best Doctors to get instant medical advice for your health
            problems. Ask the doctors online and consult them on face-to-face video chat and get solution to your
            medical problems from home.</p>
        @endif
    </section>
    <section class="main-buttons">
        <div class="row text-navy-blue gx-5 justify-content-between py-1 bg-light-blue rounded-3 w-85 mx-auto">
            <div class="col-md-4 ps-1">
                @if (Auth::check())
                <button onclick="window.location.href='/specializations'"
                    class="btn w-100 py-2 rounded-3 fs-18 fw-semibold" href="{{ route('e_visit') }}">
                    Book an Appointment
                </button>
                @else
                <button class="btn w-100 py-2 rounded-3 fs-18 fw-semibold" data-bs-toggle="modal"
                    data-bs-target="#loginModal">
                    Book an Appointment</button>
                @endif
            </div>
            <div class="col-md-4">
                @if(!Auth::check())
                <button class="btn w-100 py-2 rounded-3 fs-18 fw-semibold" data-bs-toggle="modal"
                    data-bs-target="#loginModal">
                    Talk to Doctor
                </button>
                @elseif(Auth::user()->user_type == 'patient')
                <button class="btn w-100 py-2 rounded-3 fs-18 fw-semibold"
                    onclick="window.location.href='/patient/evisit/specialization'">
                    Talk to Doctor
                </button>
                @elseif(Auth::user()->user_type == 'doctor')
                <button class="btn w-100 py-2 rounded-3 fs-18 fw-semibold"
                    onclick="window.location.href='/doctor/patient/queue'">
                    Talk to Doctor
                </button>
                @endif
            </div>
            <div class="col-md-4 pe-1">
                <button class="btn w-100 py-2 rounded-3 fs-18 fw-semibold" onclick="window.location.href='/labtests'">
                    Online Labtest
                </button>
            </div>
        </div>
    </section>
    <section class="py-2">
        <div class="row w-85 mx-auto align-items-center gx-4 gy-3">
            <div class="col-sm-6 ps-0">
                <img height="260px" class="e-visit-new-image rounded-4 w-100 object-fit-cover" src="{{ asset('assets/new_frontend/pharmacy-image.webp') }}"
                    alt="">
            </div>
            <div class="col-sm-6 pe-0">
                <div>
                    @php
                    $section = DB::table('section')
                    ->where('page_id', $page->id)
                    ->where('section_name', 'pharmacy')
                    ->where('sequence_no', '1')
                    ->first();
                    $top_content = DB::table('content')
                    ->where('section_id', $section->id)
                    ->first();
                    $image_content = DB::table('images_content')
                    ->where('section_id', $section->id)
                    ->first();
                    @endphp
                    @if ($top_content)
                    {!! $top_content->content !!}
                    @else
                    <h2 class="fs-30 fw-semibold text-uppercase">Pharmacy</h2>
                    <p>Our Pharmacy Offers prescription drugs at discounted prices.</p>
                    @endif
                </div>
            </div>
            <div class="col-sm-6 ps-0">
                <img height="260px" class="e-visit-new-image rounded-4 w-100 object-fit-cover" src="{{ asset('assets/new_frontend/lab-image.webp') }}" alt="">
            </div>
            <div class="col-sm-6 pe-0">
                <div>
                    @php
                    $section = DB::table('section')
                    ->where('page_id', $page->id)
                    ->where('section_name', 'lab-test')
                    ->where('sequence_no', '1')
                    ->first();
                    $top_content = DB::table('content')
                    ->where('section_id', $section->id)
                    ->first();
                    $image_content = DB::table('images_content')
                    ->where('section_id', $section->id)
                    ->first();
                    @endphp
                    @if ($top_content)
                    {!! $top_content->content !!}
                    @else
                    <h2 class="fs-30 fw-semibold text-uppercase">LAB TEST</h2>
                    <p>Community Healthcare Clinics medical labs are state of the art lab services , we use several
                        reference labs to bring you best price and precise lab work.</p>
                    @endif
                </div>
            </div>
            <div class="col-sm-6 ps-0">
                <img height="260px" class="e-visit-new-image rounded-4 w-100 object-fit-cover" src="{{ asset('assets/new_frontend/pain-image.webp') }}" alt="">
            </div>
            <div class="col-sm-6 pe-0">
                <div>
                    @php
                    $section = DB::table('section')
                    ->where('page_id', $page->id)
                    ->where('section_name', 'imaging')
                    ->where('sequence_no', '1')
                    ->first();
                    $top_content = DB::table('content')
                    ->where('section_id', $section->id)
                    ->first();
                    $image_content = DB::table('images_content')
                    ->where('section_id', $section->id)
                    ->first();
                    @endphp
                    @if ($top_content)
                    {!! $top_content->content !!}
                    @else
                    <h2 class="fs-30 fw-semibold text-uppercase">PAIN MANAGEMENT</h2>
                    <p>If pain becomes chronic, it can disrupt your life and affect the people you love and care about.
                        Our doctors at Community Healthcare Clinics offers online pain management, nonnarcotic
                        treatments that minimize the effects of pain on your life. We have physicians who specialize in
                        managing and treating chronic pain</p>
                    @endif
                </div>
            </div>
            <div class="col-sm-6 ps-0">
                <img height="260px" class="e-visit-new-image rounded-4 w-100 object-fit-cover" src="{{ asset('assets/new_frontend/abuse-image.webp') }}" alt="">
            </div>
            <div class="col-sm-6 pe-0">
                <div>
                    @php
                    $section = DB::table('section')
                    ->where('page_id', $page->id)
                    ->where('section_name', 'substance-abuse')
                    ->where('sequence_no', '1')
                    ->first();
                    $top_content = DB::table('content')
                    ->where('section_id', $section->id)
                    ->first();
                    $image_content = DB::table('images_content')
                    ->where('section_id', $section->id)
                    ->first();
                    @endphp
                    @if ($top_content)
                    {!! $top_content->content !!}
                    @else
                    <h2 class="fs-30 fw-semibold text-uppercase">Substance Abuse</h2>
                    <p>Community Healthcare Clinics provide best quality psychiatric services and consultations to all
                        age groups.We are a staff of professionals committed to helping patients through all stages of
                        their lives.</p>
                    @endif
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
@endsection