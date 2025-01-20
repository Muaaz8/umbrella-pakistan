@extends('layouts.new_web_layout')

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
@endsection


@section('page_title')
    <title>CHCC - Substance Abuse</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <section class="detail-pharmacy-bg">
        <div class="container">
            <div class="row">
                <div class="back-arrow-detail-pharmacy">
                    <h1>{{ $content->name }}</h1>
                    <!-- <i class="fa-solid fa-circle-arrow-left" onclick="history.back()"></i> -->
                    {{-- <i class="fa-solid fa-circle-arrow-left go-back" onclick="window.location=document.referrer;"></i> --}}
                    <nav aria-label="breadcrumb">
                        <i class="fa-solid fa-arrow-left"></i>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('substance', ['slug' => 'first-visit']) }}">Substance Abuse</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('health_topic') }}">Health Topic</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $content->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>


    <section>
        <div class="container">
            <div class="row detail-pharmcy-wrapper">
                <div class="add-to-cart-detail">
                    <div>
                        <h3>{{ $content->name }}</h3>

                    </div>

                </div>

                <div class="detail-pharmcy-content">
                    <div>
                        <h4 class="pb-3">Detail Description</h4>
                    </div>
                        {!! $content->content !!}

                </div>
            </div>
        </div>
    </section>
@endsection
