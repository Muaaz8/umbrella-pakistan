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
    <title>{{$title->content}} | Umbrella Health Care Systems</title>
@else
    <title>Substance Abuse | Umbrella Health Care Systems</title>
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
                    <h1>Substance Abuse</h1>
        <nav aria-label="breadcrumb">
          <i class="fa-solid fa-arrow-left"></i>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
            @if ($slug == 'first-visit')
            <li class="breadcrumb-item"><a href="#">Substance Abuse</a></li>
            @else
            <li class="breadcrumb-item"><a href="{{ route('substance', ['slug' => 'first-visit']) }}">Substance Abuse</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $slug }}</li>
            @endif


          </ol>
        </nav>
                </div>
            </div>
        </div>
    </section>


    <section>
        <div class="container">
            <div class="row mt-2">
                <div class="py-3 ">
                    @php
                        $page = DB::table('pages')->where('url','/substance-abuse')->first();
                        $section = DB::table('section')->where('page_id',$page->id)->where('section_name','top-section')->where('sequence_no','1')->first();
                        $top_content = DB::table('content')->where('section_id',$section->id)->first();
                    @endphp
                    @if ($top_content)
                        {!! $top_content->content !!}
                    @else
                    <h2><strong>UMBRELLA HEALTH CARE SYSTEMS - SUBSTANCE ABUSE</strong></h2><p>Umbrella Health Care Systems provide best quality psychiatric services and consultations to all age groups.We are a staff of professionals committed to helping patients through all stages of their lives. We see children, adolescent, general adults, and older adults. Explore our site to learn about our services, useful resources on various health topics, our contact information, and how to prepare for your first visit.</p>
                    @endif
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
                                <h2> First Visit </h2>
                                <div><p>The purpose of the initial psychiatric visit is to thoroughly assess the presenting condition and then make appropriate treatment recommendations. Here are some of the commonly asked questions about the first visit.</p>
                                    <h3 id="what-happens-during-the-initial-visit-how-is-the-assessment-done">What happens during the initial visit? How is the assessment done?</h3>
                                    <p>The initial assessment is problem-focused. The clinician collects and reviews detailed information collected from the patient, and family interview where applicable, and the intake paperwork filled out before the assessment. The information collected includes but not limited to the nature, duration, and severity of the problem, prior treatments, personal and medical history, family history, internal or external factors affecting the presenting condition, etc. Based on the assessment, the clinician will order appropriate testing or evaluations, present treatment options, and discuss prognosis.</p>
                                    <h3 id="why-do-i-have-to-fill-out-all-this-paperwork-why-cant-i-see-the-doctor-and-tell-him-about-my-problem">Why do I have to fill out all this paperwork? Why can&rsquo;t I see the doctor and tell him about my problem?</h3>
                                    <p>As noted above, to conduct a proper assessment and to develop an appropriate treatment plan, the healthcare provider needs detailed information from various aspects of your life relating to your current problem. The initial paperwork helps you organize this information in a format that is useful for the provider and makes your visit more productive. The provider reviews this information with you and does further assessment.</p>
                                    <p>Some examples of the information you are required to fill out include past and present treatment history, family history, general health history, developmental history, current medications, allergies, brief screening questionnaires, etc.</p>
                                    <h3 id="i-have-been-advised-to-see-a-psychiatrist-however-i-dont-think-i-need-any-medications-should-i-still-schedule-a-visit">I have been advised to see a psychiatrist. However, I don&rsquo;t think I need any medications. Should I still schedule a visit?</h3>
                                    <p>Medication is one of the treatment options among many options, such as individual counseling, family counseling, group therapy, stress management, lifestyle changes, exercise, etc. Seeing a psychiatrist doesn&rsquo;t necessarily mean that you will be prescribed medication automatically.</p>
                                    <h3 id="i-have-done-some-research-and-i-think-i-know-what-medicine-i-need--will-the-doctor-prescribe-me-the-drug-i-want">I have done some research, and I think I know what medicine I need. Will the doctor prescribe me the drug I want?</h3>
                                    <p>Treatment planning is a collaborative process where the clinician presents various treatment options, answer questions regarding those options, and listens to the patient for their input. Although the clinician will be open to discuss any treatment suggestions from the patient, he or she will not prescribe any medicine that is not appropriate or safe for the condition.</p>
                                    <h3 id="how-early-should-i-arrive-for-my-appointment">How early should I arrive for my appointment?</h3>
                                    <p>If this is your first visit, please come at least one hour earlier to fill out all the appropriate paperwork. For follow-up appointments, just try to be there on time.</p></div>
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
                                    <h2>{{ $data['products'][0]->name; }}</h2>
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


                <div class="col-lg-3 col-sm-6 mb-2 labsbutton">
                    <button class="grow_ellipse" onclick="window.location='/substance-abuse/self-pay-fees'">
                        <img src="{{ asset('assets/images/self-pay-fees-umbrella.png') }}" alt="" />
                        <span class="m-auto">Self-pay Fees</span>
                    </button>
                </div>
                <div class="col-lg-3 col-sm-6 mb-2 labsbutton">
                    <button class="grow_ellipse" onclick="window.location='/substance-abuse/preparing-first-visit'">
                        <img src="{{ asset('assets/images/prepare-first-visit-umbrella.png') }}" alt="" />
                        <span class="m-auto">Preparing Your First Visit</span>
                    </button>
                </div>
                <div class="col-lg-3 col-sm-6 mb-2 labsbutton">
                    <button class="grow_ellipse" onclick="location.href='/substance-abuse/adolescent'">
                        <img src="{{ asset('assets/images/adolcent-umbrella.png') }}" alt="" />
                        <span class="m-auto">Adolescent</span>
                    </button>
                </div>
                <div class="col-lg-3 col-sm-6 mb-2 labsbutton">
                    <button class="grow_ellipse" onclick="location.href='/substance-abuse/children'">
                        <img src="{{ asset('assets/images/children-umbrella.png') }}" alt="" />
                        <span class="m-auto">Children</span>
                    </button>
                </div>
                <div class="col-lg-3 col-sm-6 mb-2 labsbutton">
                    <button class="grow_ellipse" onclick="location.href='/substance-abuse/general-adults'">
                        <img src="{{ asset('assets/images/adults-umbrella.png') }}" alt="" />
                        <span class="m-auto">General Adults</span>
                    </button>
                </div>
                <div class="col-lg-3 col-sm-6 mb-2 labsbutton">
                    <button class="grow_ellipse" onclick="location.href='/substance-abuse/telemedicine'">
                        <img src="{{ asset('assets/images/telemedicine-umbrella.png') }}" alt="" />
                        <span class="m-auto">Telemedicine</span>
                    </button>
                </div>
                <div class="col-lg-3 col-sm-6 mb-2 labsbutton">
                    <button class="grow_ellipse" onclick="location.href='/substance-abuse/women'">
                        <img src="{{ asset('assets/images/women-abuse-umbrella.png') }}" alt="" />
                        <span class="m-auto">Women</span>
                    </button>
                </div>
                <div class="col-lg-3 col-sm-6 mb-2 labsbutton">
                    <button class="grow_ellipse" onclick="window.location='/substance-abuse/other-adults'">
                        <img src="{{ asset('assets/images/prepare-first-visit-umbrella.png') }}" alt="" />
                        <span class="m-auto">Other Adults</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="text-center my-5 substance-read-btn">
            <a href="{{ route('health_topic') }}"><button>View Health Topics</button></a>
        </div>
    </section>
    <!-- ******* SUBSTANCE-ABUSE ENDS ******** -->
@endsection
