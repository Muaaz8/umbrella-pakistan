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
<style>
    .therapy__desccr ul li{
        list-style: inside;
    }
    .therapy__desccr ol li{
        list-style: auto;
    }
</style>
@foreach($tags as $tag)
    <meta name="{{$tag->name}}" content="{{$tag->content}}">
@endforeach
@endsection


@section('page_title')
@if($title != null)
    <title>{{$title->content}} | Umbrella Health Care Systems</title>
@else
    <title>Therapy Session | Umbrella Health Care Systems</title>
@endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <!-- ******* SUBSTANCE-ABUSE STATRS ******** -->
    <section class="about-bg">
        <div class="container">
            <div class="row">
                <div class="back-arrow-about">
                    {{-- <i class="fa-solid fa-circle-arrow-left go-back" onclick="window.location=document.referrer;"></i> --}}
                    <!-- <i class="fa-solid fa-circle-arrow-left" onclick="history.back()"></i> -->
                    <h1>Therapy Session</h1>
        <nav aria-label="breadcrumb">
          <i class="fa-solid fa-arrow-left"></i>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
            @if (!isset($data->sub))
                <li class="breadcrumb-item"><a href="#">Therapy Session</a></li>
            @else
                <li class="breadcrumb-item"><a href="/therapy-session/view">Therapy Session</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::ucfirst($data->sub->slug) }}</li>
            @endif


          </ol>
        </nav>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container my-3">
            <div class="row mb-3">
                @foreach ($data->therapy as $therapy)
                <div class="col-lg-3 col-sm-6 mb-2 labsbutton">
                    <button class="grow_ellipse" onclick="window.location='/therapy-session/{{ $therapy->slug }}'">
                        {{-- <img src="{{ asset('assets/images/self-pay-fees-umbrella.png') }}" alt="" /> --}}
                        <span class="m-auto">{{ $therapy->title }}</span>
                    </button>
                </div>
            @endforeach

            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row catalog-wrapper my-2">
                <div class="px-0 px-md-5 ">
                    <div class="vertical-tab-content readmore " id="demo">
                        <article>
                            <div>
                                <h2> Therapy Session </h2>
                                <div>{{ $data->description }}</div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if (isset($data->sub))
        <section>
            <div class="container">
                <div class="row catalog-wrapper my-2">
                    <div class="px-0 px-md-5 ">
                        <div class="vertical-tab-content readmore " id="demo">
                            <article>
                                <div>
                                    <h2>{{ $data->sub->title }}</h2>
                                    <div class="therapy__desccr">{!! $data->sub->description !!}</div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif







    <!-- ******* SUBSTANCE-ABUSE ENDS ******** -->
@endsection
