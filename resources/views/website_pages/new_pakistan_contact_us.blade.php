@extends('layouts.new_pakistan_layout')

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
        <title>Contact Us | Umbrella Health Care Systems</title>
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
                <h1>Contact Us</h1>
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

        <div class="container pt-4 px-5 bg-white rounded">
            <div class="row">
                <!-- Left Side -->
                <div class="col-md-4 contact-left py-5">
                    <div class="w-100 px-2">
                        <div class="text-center contact-info">
                            <i class="fa-solid fa-location-dot"></i>
                            <div class="d-flex flex-column align-items-start justify-content-center px-3">
                                <div class="fw-bold">Address</div>
                                <p>Progressive Center, Main Shahrah Faisal, Karachi</p>
                            </div>
                        </div>
                        <hr class="hr">
                        <div class="text-center contact-info my-2">
                            <i class="fa-solid fa-phone"></i>
                            <div class="d-flex flex-column align-items-start justify-content-center px-3">
                                <div class="fw-bold">Phone</div>
                                <p>0337-2350684</p>
                            </div>
                        </div>
                        <hr class="hr">
                        <div class="text-center contact-info">
                            <i class="fa-regular fa-envelope-open"></i>
                            <div class="d-flex flex-column align-items-start justify-content-center px-3">
                                <div class="fw-bold">Email</div>
                                <p>contact@communityhealthcareclinics.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-icons container">
                        <a href="https://www.facebook.com/CommunityHealthcareClinics"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                    </div>


                </div>
                <!-- Right Side -->
                <div class="col-md-8 p-5 contact-right">
                    <h4 class="fw-bold">Have a Question? Contact Us.</h4>
                    <p class="py-2">Give us a call or send an email. Our team is always ready to provide customer care
                        help. For more information, visit us.</p>
                    <form class="row form" action="/contact" method="POST">
                        @csrf
                        <div class="row g-4">
                            <!-- First Name and Last Name -->
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="fname" placeholder="First Name">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="lname" placeholder="Last Name">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <!-- Phone -->
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="ph" placeholder="Phone">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <!-- Email -->
                            <div class="col-md-12">
                                <input type="email" class="form-control" name="email" placeholder="Email">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <!-- Subject -->
                            <div class="col-md-12">
                                <input type="subject" class="form-control" name="subject" placeholder="Subject">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <!-- Message -->
                            <div class="col-md-12">
                                <textarea class="form-control" rows="4" name="message" placeholder="Message"></textarea>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="contact-btn">Send Now</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <section id="disclaimer">
            <div class="disclaimer-box"></div>
            <div id="disclaimer-content">
                <div>
                    <h2>DISCLAIMER</h2>
                    <div class="underline"></div>
                </div>
                <div>
                    @php
                        $page = DB::table('pages')->where('url', '/')->first();
                        $section = DB::table('section')
                            ->where('page_id', $page->id)
                            ->where('section_name', 'disclaimer')
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
                            The information on this site is not intended or implied to be a
                            substitute for professional medical advice, diagnosis or
                            treatment. All content, including text, graphics, images and
                            Information, contained on or available through this web site is
                            for general information purposes only. Umbrellamd.com makes no
                            representation and assumes no responsibility for the accuracy of
                            information contained on or available through this web site, and
                            such information is subject to change without notice. You are
                            encouraged to confirm any information obtained from or through
                            this web site with other sources, and review all information
                            regarding any medical condition or treatment with your
                            physician.
                        </p>
                        <p>
                            Never disregard professional medical advice or delay seeking
                            medical treatment because of something you have read on or
                            accessed through this web site. umbrella health care systems not
                            responsible nor liable for any advice, course of treatment,
                            diagnosis or any other information, services or products that
                            you obtain through this website.
                        </p>
                    @endif
                </div>
            </div>
            <div class="custom-shape-divider-bottom-1731257443">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                    preserveAspectRatio="none">
                    <path
                        d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"
                        class="shape-fill"></path>
                </svg>
            </div>
            <div class="disclaimer-blob">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="gray"
                        d="M46,-39.1C56.3,-35.6,59.3,-17.8,54.9,-4.4C50.5,9.1,38.9,18.1,28.5,30.5C18.1,42.9,9.1,58.5,-2.6,61.1C-14.2,63.7,-28.4,53.2,-43.7,40.8C-59.1,28.4,-75.5,14.2,-75.6,-0.1C-75.6,-14.3,-59.3,-28.6,-44,-32.2C-28.6,-35.7,-14.3,-28.4,1.7,-30.2C17.8,-31.9,35.6,-42.7,46,-39.1Z"
                        transform="translate(100 100)" />
                </svg>
            </div>
        </section>
    </main>
@endsection
