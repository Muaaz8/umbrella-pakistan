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
    @foreach ($tags as $tag)
        <meta name="{{ $tag->name }}" content="{{ $tag->content }}">
    @endforeach
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    @if ($title != null)
        <title>{{ $title->content }} | Umbrella Health Care Systems</title>
    @else
        <title>E-visit | Umbrella Health Care Systems</title>
    @endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <!-- -------upd-evisit-start--- -->
    <main>
        <section class="eVisit__main_">
            <div class="container">
                <div class="eVisit__Content">
                    <span class="e_visit_heading">E-Visit</span>

                    <p>
                        Umbrella Health Care Systems provide you with facility to visit
                        doctors, therapist, or medical expert online. Find best Doctors to
                        get instant medical advice for your health problems. Ask the doctors
                        online and consult them on face-to-face video chat and get solution
                        to your medical problems from home.
                    </p>
                    <div class="hr__red_line"></div>
                </div>
            </div>
        </section>
        <section class="my-3">
            <div class="container">
                <div class="row">
                    @if (!Auth::check() || Auth::user()->user_type != 'doctor')
                        <div class="col-lg-4 col-sm-6">
                            <button class="btn e__visits_btn" onclick="window.location.href='/specializations'">Book An
                                Appointment</button>
                        </div>
                        {{-- @elseif()
            <div class="col-lg-4 col-sm-6">
                <button class="btn e__visits_btn" onclick="window.location.href='/specializations'">Book An Appointment</button>
            </div> --}}
                    @endif
                    @if (!Auth::check())
                        <div class="col-lg-4 col-sm-6">
                            <button class="btn e__visits_btn" data-bs-toggle="modal" data-bs-target="#loginModal">Talk To
                                Doctor</button>
                        </div>
                    @elseif(Auth::user()->user_type == 'patient')
                        <div class="col-lg-4 col-sm-6">
                            <button class="btn e__visits_btn"
                                onclick="window.location.href='/patient/evisit/specialization'">Talk To Doctor</button>
                        </div>
                    @elseif(Auth::user()->user_type == 'doctor')
                        <div class="col-lg-4 col-sm-6">
                            <button class="btn e__visits_btn" onclick="window.location.href='/doctor/patient/queue'">Go To
                                Waiting Room</button>
                        </div>
                    @endif
                    <div class="col-lg-4 col-sm-6">
                        <button class="btn e__visits_btn" onclick="window.location.href='/labtests'">Order Labtests</button>
                    </div>
                </div>
            </div>
        </section>

        <div class="mb-3">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators main__indi">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                        class="active indi__style" aria-current="true" aria-label="Slide 1"></button>
                    <button class="indi__style" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button class="indi__style" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide-to="2" aria-label="Slide 3"></button>
                    <button class="indi__style" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide-to="3" aria-label="Slide 4"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        @php
                            $page = DB::table('pages')->where('url', '/e-visit')->first();
                            $section = DB::table('section')
                                ->where('page_id', $page->id)
                                ->where('section_name', 'slider-1')
                                ->where('sequence_no', '1')
                                ->first();
                            $top_content = DB::table('content')
                                ->where('section_id', $section->id)
                                ->first();
                            $image_content = DB::table('images_content')
                                ->where('section_id', $section->id)
                                ->first();
                        @endphp
                        <section>
                            <div class="container">
                                <div class="blue__hr"></div>
                                <div class="py-3">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="sli__img">
                                                <img src="{{ asset('assets/images/lab_test__.png') }}" alt='{{ $image_content?$image_content->alt:'E visit Lab Test' }}' />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div>
                                                @if ($top_content)
                                                    {!! $top_content->content !!}
                                                @else
                                                    <h2><strong>LAB TEST</strong></h2>
                                                    <p>Umbrella Health Care Systems medical labs are state of the art lab
                                                        services , we use several reference labs to bring you best price and
                                                        precise lab work.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="blue__hr"></div>
                            </div>
                        </section>
                    </div>
                    <div class="carousel-item">
                        @php
                            $section = DB::table('section')
                                ->where('page_id', $page->id)
                                ->where('section_name', 'slider-2')
                                ->where('sequence_no', '1')
                                ->first();
                            $top_content = DB::table('content')
                                ->where('section_id', $section->id)
                                ->first();
                            $image_content = DB::table('images_content')
                                ->where('section_id', $section->id)
                                ->first();
                        @endphp
                        <section>
                            <div class="container">
                                <div class="blue__hr"></div>
                                <div class="py-3">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="sli__img">
                                                <img src="{{ asset('assets/images/pain__management.png') }}"
                                                    alt='{{ $image_content?$image_content->alt:'E visit Pain Management' }}' />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div>
                                                @if ($top_content)
                                                    {!! $top_content->content !!}
                                                @else
                                                    <h2><strong>PAIN MANAGEMENT</strong></h2>
                                                    <p>If pain becomes chronic, it can disrupt your life and affect the
                                                        people you love and care about. Our doctors at Umbrella Health Care
                                                        Systems offers online pain management, nonnarcotic treatments that
                                                        minimize the effects of pain on your life. We have physicians who
                                                        specialize in managing and treating chronic pain</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="blue__hr"></div>
                            </div>
                        </section>
                    </div>
                    <div class="carousel-item">
                        @php
                            $section = DB::table('section')
                                ->where('page_id', $page->id)
                                ->where('section_name', 'slider-3')
                                ->where('sequence_no', '1')
                                ->first();
                            $top_content = DB::table('content')
                                ->where('section_id', $section->id)
                                ->first();
                            $image_content = DB::table('images_content')
                                ->where('section_id', $section->id)
                                ->first();
                        @endphp
                        <section>
                            <div class="container">
                                <div class="blue__hr"></div>
                                <div class="py-3">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="sli__img">
                                                <img src="{{ asset('assets/images/pharmacy__.png') }}" alt='{{ $image_content?$image_content->alt:'E visit Pharmacy' }}' />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div>
                                                @if ($top_content)
                                                    {!! $top_content->content !!}
                                                @else
                                                    <h2><strong>PHARMACY</strong></h2>
                                                    <p>Our Pharmacy Offers prescription drugs at discounted prices.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="blue__hr"></div>
                            </div>
                        </section>
                    </div>
                    <div class="carousel-item">
                        <section>
                            @php
                                $section = DB::table('section')
                                    ->where('page_id', $page->id)
                                    ->where('section_name', 'slider-4')
                                    ->where('sequence_no', '1')
                                    ->first();
                                $top_content = DB::table('content')
                                    ->where('section_id', $section->id)
                                    ->first();
                                $image_content = DB::table('images_content')
                                    ->where('section_id', $section->id)
                                    ->first();
                            @endphp
                            <div class="container">
                                <div class="blue__hr"></div>
                                <div class="py-3">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="sli__img">
                                                <img src="{{ asset('assets/images/subtance_abuse.png') }}"
                                                    alt='{{ $image_content?$image_content->alt:'E visit substance Abuse' }}' />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div>
                                                @if ($top_content)
                                                    {!! $top_content->content !!}
                                                @else
                                                    <h2><strong>SUBSTANCE ABUSE</strong></h2>
                                                    <p>Umbrella Health Care Systems provide best quality psychiatric
                                                        services and consultations to all age groups.We are a staff of
                                                        professionals committed to helping patients through all stages of
                                                        their lives.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="blue__hr"></div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- -------upd-evisit-end--- -->
    <!-- ******* LOGIN-REGISTER-MODAL STARTS ******** -->
    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Select Registration Type</h5>
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
