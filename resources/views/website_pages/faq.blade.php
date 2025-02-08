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
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    <title>FAQs | Umbrella Health Care Systems</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
{{-- {{ dd($tblFaqs) }} --}}
    <!-- ******* FAQS STATRS ******** -->
    <section class="about-bg">
        <div class="container">
            <div class="row">
                <div class="back-arrow-about">
                    <!-- <i class="fa-solid fa-circle-arrow-left" onclick="history.back()"></i> -->
                    {{-- <i class="fa-solid fa-circle-arrow-left go-back" onclick="window.location=document.referrer;"></i> --}}

                    <h1>FAQS</h1>
                    <nav aria-label="breadcrumb">
                        <i class="fa-solid fa-arrow-left"></i>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">FAQs</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <section style="background-color:#08295a ;">
        <div class="container">
            <div class="row py-5">
                <div class="col-md-6">
                    <div class="accordion" id="accordionExample">
                        @php
                            $temp = 0;
                        @endphp
                        @foreach ($tblFaqs as $faq)
                        @if ($temp % 2 == 0)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $faq->id }}" aria-expanded="false" aria-controls="collapse{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $faq->id }}"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p>{!! $faq->answer !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @php
                            $temp++;
                        @endphp
                        @endforeach
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="accordion" id="accordionExample1">
                        @php
                            $temp = 0;
                        @endphp
                        @foreach ($tblFaqs as $faq)
                        @if ($temp % 2 != 0)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $faq->id }}" aria-expanded="false" aria-controls="collapse{{ $faq->id }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $faq->id }}"
                                data-bs-parent="#accordionExample1">
                                <div class="accordion-body">
                                    <p>{!! $faq->answer !!}</p>
                                </div>
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
    </section>
    <!-- ******* FAQS ENDS ******** -->
@endsection
