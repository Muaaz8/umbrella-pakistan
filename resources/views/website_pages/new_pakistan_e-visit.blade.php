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
    <style>
        .gallery {
          --s: 150px; /* control the size */
          --g: 10px;  /* control the gap */
          --f: 1.5;   /* control the scale factor */

          display: grid;
          gap: var(--g);
          width: calc(3*var(--s) + 2*var(--g));
          aspect-ratio: 1;
          grid-template-columns: repeat(3,auto);
        }

        .gallery > img {
          width: 0;
          height: 0;
          min-height: 100%;
          min-width: 100%;
          object-fit: cover;
          cursor: pointer;
          filter: grayscale(40%);
          transition: .35s linear;
        }

        .gallery img:hover{
          filter: grayscale(0);
          width:  calc(var(--s)*var(--f));
          height: calc(var(--s)*var(--f));
        }

      </style>
@endsection


@section('page_title')
    @if ($title != null)
        <title>{{ $title->content }}</title>
    @else
        <title>Community Healthcare Clinics</title>
    @endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
@php
    $page = DB::table('pages')->where('url', '/e-visit')->first();
@endphp
    <main>
        <div class="contact-section">
            <div class="contact-content">
                <h1>E-visit</h1>
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

        <div class="container pt-4 px-5 bg-white rounded pharmacy-page-container">
            @php
                $section = DB::table('section')
                    ->where('page_id', $page->id)
                    ->where('section_name', 'top-section')
                    ->where('sequence_no', '1')
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
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe
                    consequatur delectus molestias at laboriosam, exercitationem, omnis
                    sunt distinctio a quisquam provident blanditiis pariatur laborum
                    quidem nesciunt, perferendis voluptatibus quas commodi.
                </p>
            @endif
            <hr>

            <div class="row gy-2 gy-md-0 mt-3">
                <div class="col-md-4 d-flex align-items-center justify-content-center">
                    <div class="e_visit_btns w-100">
                        @if (Auth::check())
                            <button class="btn btn-outline-danger w-100" onclick="window.location.href='/specializations'">Book an Appointment</button>
                        @else
                            <button class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Book an Appointment</button>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-center">
                    <div class="e_visit_btns w-100">
                        @if (!Auth::check())
                            <button class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Talk To Doctor</button>
                        @elseif(Auth::user()->user_type == 'patient')
                            <button class="btn btn-outline-danger w-100" onclick="window.location.href='/patient/evisit/specialization'" >Talk To Doctor</button>
                        @elseif(Auth::user()->user_type == 'doctor')
                            <button class="btn btn-outline-danger w-100" onclick="window.location.href='/doctor/patient/queue'">Talk To Doctor</button>
                        @else
                            <button class="btn btn-outline-danger w-100" onclick="window.location.href='/login'">Talk To Doctor</button>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-center">
                    <div class="e_visit_btns w-100">
                        <button class="btn btn-outline-danger w-100" onclick="window.location.href='/labtests'">Order Labtest</button>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row mt-5">
                    <div class="col-lg-6 col-md-12 gallery-container">
                        <div class="gallery">
                            <img src="{{ asset("assets/new_frontend/e-pic9.png") }}" alt="a forest after an apocalypse">
                            <img src="{{ asset("assets/new_frontend/e-pic6.png") }}" alt="a waterfall and many rocks">
                            <img src="{{ asset("assets/new_frontend/e-pic2.png") }}" alt="a house on a mountain">
                            <img src="{{ asset("assets/new_frontend/e-pic3.png") }}" alt="sime pink flowers">
                            <img src="{{ asset("assets/new_frontend/e-pic4.png") }}" alt="big rocks with some trees">
                            <img src="{{ asset("assets/new_frontend/e-pic6.png") }}" alt="a waterfall, a lot of tree and a great view from the sky">
                            <img src="{{ asset("assets/new_frontend/e-pic1.png") }}" alt="a cool landscape">
                            <img src="{{ asset("assets/new_frontend/e-pic7.png") }}" alt="inside a town between two big buildings">
                            <img src="{{ asset("assets/new_frontend/e-pic8.png") }}"
                                alt="a great view of the sea above the mountain">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 d-flex align-items-start justify-content-center flex-column">
                        @php
                            $section = DB::table('section')
                                ->where('page_id', $page->id)
                                ->where('section_name', 'pharmacy')
                                ->where('sequence_no', '1')
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
                            <h2>Pharmacy</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione nisi corrupti facilis aut
                                soluta saepe amet voluptatem..</p>
                        @endif


                        @php
                            $section = DB::table('section')
                                ->where('page_id', $page->id)
                                ->where('section_name', 'lab-test')
                                ->where('sequence_no', '1')
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
                            <h2>Labtest</h2>
                            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Animi eaque ullam possimus .</p>
                        @endif


                        @php
                            $section = DB::table('section')
                                ->where('page_id', $page->id)
                                ->where('section_name', 'imaging')
                                ->where('sequence_no', '1')
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
                            <h2>Imaging</h2>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit</p>
                        @endif


                        @php
                            $section = DB::table('section')
                                ->where('page_id', $page->id)
                                ->where('section_name', 'substance-abuse')
                                ->where('sequence_no', '1')
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
                            <h2>Subtance abuse</h2>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione nisi corrupti facilis aut soluta
                                saepe amet voluptatem..</p>
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </main>


    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Registration Type</h5>
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
