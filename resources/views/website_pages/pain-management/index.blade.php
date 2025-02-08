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
@foreach($tags as $tag)
<meta name="{{$tag->name}}" content="{{$tag->content}}">
@endforeach
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
@if($title != null)
    <title>{{$title->content}} | Umbrella Health Care Systems</title>
@else
    <title>Pain Management | Umbrella Health Care Systems</title>
@endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')

<!-- ==============================Pain Management Starts======================== -->
<section class="about-bg pain-bg">
  <div class="container">
    <div class="row">
        <div class="back-arrow-about">
          <!-- <i class="fa-solid fa-circle-arrow-left" onclick="history.back()"></i> -->
          {{-- <i class="fa-solid fa-circle-arrow-left go-back" onclick="window.location=document.referrer;"></i> --}}

        <h1>Pain Management</h1>
        <nav aria-label="breadcrumb">
          <i class="fa-solid fa-arrow-left"></i>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pain Management</a></li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="row mt-2">
      <div class="py-3">
        @php
            $page = DB::table('pages')->where('url','/pain-management')->first();
            $section = DB::table('section')->where('page_id',$page->id)->where('section_name','top-section')->where('sequence_no','1')->first();
            $top_content = DB::table('content')->where('section_id',$section->id)->first();
        @endphp
        @if ($top_content)
            {!! $top_content->content !!}
        @else
        <h2><strong>UMBRELLA HEALTH CARE SYSTEMS - PAIN MANAGEMENT</strong></h2><p>If pain becomes chronic, it can disrupt your life and affect the people you love and care about. Our doctors at Umbrella Health Care Systems offers online pain management, nonnarcotic treatments that minimize the effects of pain on your life. We have physicians who specialize in managing and treating chronic pain.</p>
        @endif
      </div>

    </div>
  </div>
</section>




<!-- ==============================Pain Management Ends======================== -->




<section>
  <div class="container">
  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="col-lg-3 col-sm-6 mb-2 p-2 labsbutton" role="presentation">
    <a href="#pills-home-tab">
    <button class="grow_ellipse active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
    <img src="{{ asset('assets/images/cancer-icon-umbrella.png') }}" alt="" /> Cancer-Related Pain
  </button>
  </a>
  </li>
  <li class="col-lg-3 col-sm-6 mb-2 p-2 labsbutton" role="presentation">
  <a href="#pills-profile-tab">
  <button class=" grow_ellipse" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
    <img src="{{ asset('assets/images/muscle-icon-umbrella.png') }}" alt="" /> Joint And Muscle Pain
    </button>
    </a>
  </li>
  <li class="col-lg-3 col-sm-6 mb-2 p-2 labsbutton" role="presentation">
  <a href="#pills-contact-tab">
  <button class="grow_ellipse" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
    <img src="{{ asset('assets/images/back-icon-umbrella.png') }}" alt="" /> Neck And Back Pain
    </button>
    </a>
  </li>
  <li class="col-lg-3 col-sm-6 mb-2 p-2 labsbutton" role="presentation">
  <a href="#pills-nerve-tab">
  <button class="grow_ellipse" id="pills-nerve-tab" data-bs-toggle="pill" data-bs-target="#pills-nerve" type="button" role="tab" aria-controls="pills-nerve" aria-selected="false">
    <img src="{{ asset('assets/images/nerve-icon-umbrella.png') }}" alt="" /> Nerve-Related Pain
    </button>
    </a>
  </li>
</ul>
  </div>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
  <div class="container">

                <div class="row catalog-wrapper my-5">
                  <div class="px-0 px-md-5 ">


                        <div class="vertical-tab-content">
                          <div>
                            <h2>Cancer-Related Pain</h2>
                          </div>
                          <div>
                            <h6>
                            Pain can result from cancer or its treatments — even after treatment, in some cases. If you have pain during or after active treatment, we can help:
                            </h6>
                            <p>Relieve pain from cancer itself, especially if the cancer is advanced</p>
                            <p>Relieve pain due to cancer treatments, such as surgery, radiation therapy or chemotherapy</p>
                            <p>Manage chronic pain after cancer</p>
                          </div>

                        </div>

                  </div>
                </div>
       </div>

   </div>



   <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

  <div class="container">
        <div class="row catalog-wrapper my-5">
          <div class="px-0 px-md-5 ">


                <div class="vertical-tab-content">
                  <div>
                    <h2>Joint And Muscle Pain</h2>
                    <p>
                    Musculoskeletal pain affects bones, muscles and connective tissue such as cartilage, ligaments and tendons. The most common causes of this pain are arthritis and injury. Musculoskeletal pain that we treat includes:
                    </p>
                  </div>
                  <div>
                    <h6>
                    Joint pain:
                    </h6>
                    <p>
                    Inflammation (pain and swelling) in major joints such as the hip, knee and shoulder
                    </p>
                  </div>
                  <div>
                    <h6>
                    Malignant compression fractures:
                    </h6>
                    <p>
                    Fractures in vertebrae that result from cancerous tumors, usually metastatic (tumors that start in another part of the body and spread to the vertebrae)
                    </p>
                  </div>
                  <div>
                    <h6>
                    Benign compression fractures:
                    </h6>
                    <p>
                    Vertebral fractures typically due to osteoporosis, a condition that causes bones to become weak and brittle
                    </p>
                  </div>
                  <div>
                    <h6>
                    Sacroiliac (SI) joint pain:
                    </h6>
                    <p>
                    Pain in the low back and buttocks due to arthritis, injury or another problem with the SI joint
                    </p>
                  </div>
                </div>

          </div>
        </div>
      </div>

  </div>
  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
  <div class="container">
                <div class="row catalog-wrapper my-5">
                  <div class="px-0 px-md-5 ">


                        <div class="vertical-tab-content">
                          <div>
                            <h2>Neck And Back Pain</h2>
                          </div>
                          <div>
                            <h6>
                            One of the most common causes of chronic pain is back and neck pain. Sharp pain or dull aches can result from injury, strain, poor posture, wear and tear or many other reasons. We treat pain all along the spine, from the top of your neck to your tailbone,including:
                            </h6>
                           <p><b>Degenerative disc disease: </b>Vertebrae (spine bones) that rub together as the discs (rubbery tissue between vertebrae) wear out due to age or injury</p>
                            <P><b>Facet joint syndrome: </b>Damage to facet joints (connections between vertebrae) due to injury, disc deterioration or wear and tear</P>
                            <P><b>Failed back syndrome: </b>Back pain that returns after surgery</P>
                            <P><b>Herniated disc: </b>A disc that breaks open (ruptures) or moves out of place due to injury or strenuous activity</P>
                            <P><b>Radiculopathy (pinched nerve): </b>A damaged or compressed nerve root in the spine, causing pain and other symptoms in various areas of the body depending on where it develops</P>
                            <p><b>Sciatica: </b> Radiculopathy that develops in the low back</p>
                            <p><b>Spinal stenosis: </b>A narrowed area of the spinal canal that causes pain due to compression of the spinal cord or nerve roots</p>
                          </div>

                        </div>

                  </div>
                </div>
       </div>

  </div>
  <div class="tab-pane fade" id="pills-nerve" role="tabpanel" aria-labelledby="pills-nerve-tab">

  <div class="container">
                <div class="row catalog-wrapper my-5">
                  <div class="px-0 px-md-5 ">


                        <div class="vertical-tab-content">
                          <div>
                            <h2>Nerve-Related Pain</h2>
                          </div>
                          <div>
                            <h6>
                            Neuropathy, also called neuralgia, is nerve damage that causes pain, tingling, numbness and muscle weakness. Nerve-related pain can result from injury or conditions such as diabetes and atherosclerosis. Some common types of neuropathy include:
                            </h6>
                              <p><b>Complex regional pain syndrome:</b>Chronic pain, usually in one limb, due to a problem with pain nerves that sometimes develops after an injury</p>
                              <p><b>Peripheral neuropathy: </b>Neuropathy that affects nerves outside the brain and spinal cord, most commonly in the legs and feet; includes diabetic neuropathy</p>
                              <p><b>Postherpetic neuralgia: </b>Nerve damage that develops after shingles, a viral infection that causes a blistering rash, usually on one side of the torso </p>
                          </div>

                        </div>

                  </div>
                </div>
       </div>



  </div>
</div>
</section>










@endsection
