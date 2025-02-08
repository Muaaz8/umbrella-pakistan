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
@foreach($tags as $tag)
    <meta name="{{$tag->name}}" content="{{$tag->content}}">
    @endforeach
@endsection


@section('page_title')
@if($title != null)
    <title>{{$title->content}} | {{ $slug }} | Umbrella Health Care Systems</title>
@else
    <title> Psychiatry | {{ $slug }} | Umbrella Health Care Systems</title>
@endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <!-- ******* SUBSTANCE-ABUSE STATRS ******** -->

    {{-- {{ dd($slug) }} --}}
    <section class="about-bg">
        <div class="container">
            <div class="row">
                <div class="back-arrow-about">
                    {{-- <i class="fa-solid fa-circle-arrow-left go-back" onclick="window.location=document.referrer;"></i> --}}
                    <!-- <i class="fa-solid fa-circle-arrow-left" onclick="history.back()"></i> -->
                    <h1>Psychiatry</h1>
        <nav aria-label="breadcrumb">
          <i class="fa-solid fa-arrow-left"></i>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>

            <li class="breadcrumb-item"><a href="{{ route('psychiatry', ['slug' => 'anxiety']) }}">Psychiatry</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $slug }}</li>



          </ol>
        </nav>
                </div>
            </div>
        </div>
    </section>


    <section>
        <div class="container">
            <div class="row mt-5">
                <div class="py-3 ">
                    @php
                        $page = DB::table('pages')->where('url','/psychiatry')->first();
                        $section = DB::table('section')->where('page_id',$page->id)->where('section_name','top-section')->where('sequence_no','1')->first();
                        $top_content = DB::table('content')->where('section_id',$section->id)->first();
                    @endphp
                    @if ($top_content)
                        {!! $top_content->content !!}
                    @else
                    <h2><strong>UMBRELLA HEALTH CARE SYSTEMS - Psychiatry</strong></h2><p>Getting the support you need has never been simpler thanks to Umbrella Health Care System’s skilled team of psychiatrists, who are known for offering their patients compassionate, holistic care for a wide range of psychiatric challenges at convenience in the palm of your hand.</p>
                    @endif
                </div>

            </div>
    </section>


    <section>
        <div class="container">
            <div class="row catalog-wrapper my-5">
                <div class="px-0 px-md-5 ">


                    <div class="vertical-tab-content readmore " id="demo">

                        <article>
                            <div class="psychiatrist-div">
                                <h2> Psychiatry </h2>
                                <div><h4>About Us </h4> <p>Getting the support you need has never been simpler thanks to Umbrella Health Care System&rsquo;s skilled team of psychiatrists, our team of psychiatrists are known for offering compassionate, holistic care for a wide range of challenges at convenience in the palm of your hand.</p>
                                    <p>Each healthcare professional at Umbrella Health Care Systems offers their unique skills and approach to patients, ensuring that each person receives the care that is best for them. The team has expertise in a variety of psychiatric conditions, including anxiety, bipolar disorder, depression, attention-deficit/hyperactivity disorder (ADHD), and post-traumatic stress disorder (PTSD), in addition to numerous therapies like transcranial magnetic stimulation, medication management, and medical marijuana.</p>
                                    <p>Umbrella Health Care Systems&#39; physicians treat patients as young as three years old. Every patient at Umbrella Health Care Systems receives the comprehensive support they require thanks to the compassionate, personalised care provided by our psychiatrists .</p>
                                    <p>Make your initial appointment at the Umbrella Health Care Systems using the online booking system to learn how you can receive the psychiatric care you need.</p>
                                    <h4>Our Mission | Telemedicine</h4>
                                    <p>We believe that when comprehensive care is combined with uncompromising compassion, it works best. With the help of our online system, we are pleased to provide video counselling services to our patients from the convenience of their homes. The success and happiness of our patients is something that our doctors and psychiatrists sincerely care about. We make sure that every patient receives a professional, comforting, and supportive therapy.</p>
                                      <h4>Comprehensive Psychiatric Evaluations </h4> <p>To diagnose conditions such as: </p>
                                    <ul>
                                      <li>Alzheimer&rsquo;s Disease </li>
                                      <li>Anxiety Disorders </li>
                                      <li>Attention Deficit Disorders </li>
                                      <li>Complex Physical / Psychosomatic conditions </li>
                                      <li>Bipolar Disorder </li>
                                      <li>Depression </li>
                                      <li>Addiction/ Substance Use Disorders </li>
                                      <li>Eating Disorders </li>
                                      <li>Obsessive-Compulsive Disorder (OCD) </li>
                                      <li>Panic Disorder </li>
                                      <li>Schizophrenia/ Schizoaffective/ Schizophreniform </li>
                                      <li>Personality Disorders </li>
                                      <li>PTSD </li>
                                      <li>TBI/ Head Injury with secondary behavioral disturbances</li>
                                    </ul>
                                    <h4>Counseling</h4>
                                    <p>We offer counseling services for</p>
                                    <ul>
                                        <li>cognitive behavioral therapy</li>
                                        <li>psychotherapy</li>
                                        <li>talk therapy</li>
                                        <li>anger management</li>
                                        <li>couples & marriage counseling</li>
                                        <li>parenting classes</li>
                                    </ul>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
            @if ($slug != 'first-visit')
                <div class="row catalog-wrapper my-5 scroll-substance">
                    <div class="px-0 px-md-5">
                        <div class="vertical-tab-content " >
                            <!-- <article> -->
                                <div>
                                    <h2>{{ $data['products'][0]->title; }}</h2>
                                    @php
                                        $new = htmlentities( $data['products'][0]->description, ENT_QUOTES);
                                        echo html_entity_decode($new);
                                    @endphp
                                </div>
                            <!-- </article> -->
                        </div>
                    </div>
                </div>
            @endif
        </div>
        </div>
    </section>





    <section>
        <div class="container ">
            <div class="row mb-3">
                {{-- {{ dd($data); }} --}}
                @foreach ($data["sidebar"]["sideMenus"]["Psychiatrist|psychiatry"] as $item)
                <div class="col-lg-3 col-sm-6 mb-2 labsbutton">
                    <button class="grow_ellipse" onclick="location.href='{{ route('psychiatry',['slug'=>(explode('|',$item)[2])]) }}'">
                        <img src="{{ asset('assets/images/'.(explode('|',$item)[3])) }}" alt="" />
                        <span class="m-auto">{{ (explode('|',$item)[1]) }}</span>
                    </button>
                </div>
                @endforeach

            </div>
        </div>
        {{-- <div class="text-center my-5 substance-read-btn">
            <a href="{{ route('health_topic') }}"><button>View Health Topics</button></a>
        </div> --}}
    </section>
    <!-- ******* SUBSTANCE-ABUSE ENDS ******** -->
@endsection
