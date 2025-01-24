@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="copyright" content="© {{ date('Y') }} All Rights Reserved. Powered By UmbrellaMd">
    @foreach ($tags as $tag)
        <meta name="{{ $tag->name }}" content="{{ $tag->content }}">
    @endforeach
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <style>
        .about-content p{
            text-align: center;
            width: 65%;
            font-size: 13px;
        }
        .desc-container h2{
            width: 74%;
            font-size: 40px;
            font-weight: normal;
            color: #ffff;
            z-index: 2;
        }
        .desc-container p{
            width: 45%;
            color: #ffff;
            font-weight: 400;
            font-size: 15px;
            z-index: 2;
        }
    </style>
@endsection


@section('page_title')
    @if ($title != null)
        <title>{{ $title->content }} </title>
    @else
        <title>Community Healthcare Clinics</title>
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
@php
    $page = DB::table('pages')->where('url', '/about-us')->first();
@endphp
    <main class="about-page-container">
        <div class="about_heading_img">
            <div class="about_heading_inner_div">
                <h1>ABOUT US</h1>
                <div class="underline3"></div>
            </div>
        </div>
        <section class="about-section">
            <div class="about-content">
                @php
                    $section = DB::table('section')
                        ->where('page_id', $page->id)
                        ->where('section_name', 'top section')
                        ->where('sequence_no', '1')
                        ->first();
                    $top_content = DB::table('content')
                        ->where('section_id', $section->id)
                        ->first();
                    $image_content = DB::table('images_content')
                        ->where('section_id', $section->id)
                        ->first();
                @endphp
                <h2 class="about-main-heading">
                    We Are <span class="about-highlight">Community Healthcare</span> Clinics
                </h2>
                @if ($top_content)
                    {!! $top_content->content !!}
                @else
                    <p class="about-para">
                        Umbrella Health Care Systems is bringing the hospital to your home.
                        Physicians and patients can share information in real time from a
                        single computer screen, enabling remote consultations, monitoring,
                        and diagnosis. This system allows healthcare providers to offer
                        personalized care from a distance, improving accessibility and
                        convenience for patients while maintaining a high standard of
                        medical support.
                    </p>
                @endif
                <div class="line"></div>
            </div>
            <div class="about-image">
                <img width="500px" src="{{ asset('assets/new_frontend/pngegg.png') }}" alt="doctor-image" />
            </div>
            <div class="tablets"></div>
            <div class="medkit"></div>
        </section>
        <section class="bar-section">
            <div class="bar-content">
                <div class="bar-container">
                    <div class="bar-items">
                        <div class="bar">
                            <i class="fa-solid fa-users"></i>
                            <h3 class="icon-heading">2147</h3>
                            <p class="icon-para">Happy Patients</p>
                        </div>
                        <div class="bar">
                            <i class="fa-solid fa-user-doctor"></i>
                            <h3 class="icon-heading">2147</h3>
                            <p class="icon-para">Qualified Doctors</p>
                        </div>
                        <div class="bar">
                            <i class="fa-solid fa-pills"></i>
                            <h3 class="icon-heading">2147</h3>
                            <p class="icon-para">Pharmacy Products</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="desc-about-section">
            <div class="desc-container">
                @php
                    $section = DB::table('section')
                        ->where('page_id', $page->id)
                        ->where('section_name', 'bottom section')
                        ->where('sequence_no', '2')
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
                    <h2 class="desc-main-heading">“ Community Health Clinics provides a complete solution for health-related
                        needs. ”</h2>
                    <p class="desc-para">Community Health Clinics E-Pharmacy offers a wide variety of prescription and
                        over-the-counter medications, ensuring convenient access to essential health products for patients
                        directly from their homes.</p>
                @endif
            </div>
            <div class="desc-bg"></div>
        </section>
    </main>
@endsection
