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
        <title>Faqs | Community Healthcare Clinics</title>
    @endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <main>
        <div class="contact-section">
            <div class="contact-content">
                <h1>Faq's</h1>
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

        <div class="container my-3 z-3 pharmacy-page-container border border-1 rounded-3">

            <div class="row">
                <div class="col-lg-12 d-flex align-items-center justify-content-center pt-4">
                    <div>
                        <h2>Frequently Asked <span class="red">Questions</span></h2>
                        <div class="underline"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div id="faqs" class="py-1">
                        <div id="faq-content">
                            @php
                                $temp = 0;
                            @endphp
                            @foreach ($tblFaqs as $faq)
                                @if ($temp % 2 == 0)
                                    <div class="faq">
                                        <div class="faq-question">
                                            <h3>{{ $faq->question }}</h3>
                                            <i class="fa-solid fa-plus"></i>
                                        </div>
                                        <div class="faq-answer">
                                            <p>{!! $faq->answer !!}</p>
                                        </div>
                                    </div>
                                @endif
                                @php
                                    $temp++;
                                @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div id="faqs" class="py-1">
                        <div id="faq-content">
                            @php
                                $temp = 0;
                            @endphp
                            @foreach ($tblFaqs as $faq)
                                @if ($temp % 2 != 0)
                                    <div class="faq">
                                        <div class="faq-question">
                                            <h3>{{ $faq->question }}</h3>
                                            <i class="fa-solid fa-plus"></i>
                                        </div>
                                        <div class="faq-answer">
                                            <p>{!! $faq->answer !!}</p>
                                        </div>
                                    </div>
                                @endif
                                @php
                                    $temp++;
                                @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
