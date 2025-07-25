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
    <section class="new-header w-85 mx-auto rounded-3" data-aos="fade-down" data-aos-duration="800">
        <div class="new-header-inner p-4">
            <h1 class="fs-30 mb-0 fw-semibold">FAQs</h1>
            <div>
                <a class="fs-12" href="{{ url('/') }}">Home</a>
                <span class="mx-1 align-middle">></span>
                <a class="fs-12" href="{{ route('faq') }}">
                    FAQs
                </a>

            </div>
        </div>
    </section>
    <section class="page-para my-3 px-5 w-85 mx-auto text-center">
        <h2 class="fs-30 fw-semibold text-center mb-2" data-aos="fade-up" data-aos-delay="300" data-aos-duration="800">
            Frequently Asked Questions
        </h2>
    </section>

    <section class="container-fluid px-0 w-85 my-3 rounded-3">
        <div class="row">
            <div class="col-lg-6">
                <div class="accordion accordion-flush" id="faqsSection" data-aos="fade-up" data-aos-delay="300"
                    data-aos-duration="800">
                    @php
                    $temp = 0;
                    @endphp
                    @foreach ($tblFaqs as $faq)
                    @if ($temp % 2 == 0)
                    <div class="accordion-item border-blue-2 rounded-3 my-1 overflow-hidden" data-aos="zoom-in" data-aos-delay="{{ $temp * 100 }}"
                    data-aos-duration="500">
                        <h2 class="accordion-header border-none">
                            <button class="accordion-button fw-medium fs-14 collapsed border-none" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq-{{ $faq->id }}" aria-expanded="false"
                                aria-controls="flush-collapseOne">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="faq-{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqsSection">
                            <div class="accordion-body border-none fs-14">
                                {!! $faq->answer !!}
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
            <div class="col-lg-6">
                <div class="accordion accordion-flush" id="faqsSection" data-aos="fade-up" data-aos-delay="300"
                    data-aos-duration="800">
                    @php
                    $temp = 0;
                    @endphp
                    @foreach ($tblFaqs as $faq)
                    @if ($temp % 2 != 0)
                    <div class="accordion-item border-blue-2 rounded-3 my-1 overflow-hidden" data-aos="zoom-in" data-aos-delay="{{ $temp * 100 }}"
                    data-aos-duration="500">
                        <h2 class="accordion-header border-none">
                            <button class="accordion-button fw-medium fs-14 collapsed border-none" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq-{{ $faq->id }}" aria-expanded="false"
                                aria-controls="flush-collapseOne">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="faq-{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqsSection">
                            <div class="accordion-body border-none fs-14">
                                {!! $faq->answer !!}
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
    </section>

</main>
@endsection