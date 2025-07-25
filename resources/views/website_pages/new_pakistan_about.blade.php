@extends('layouts.new_pakistan_layout')

@section('meta_tags')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="copyright" content="© {{ date('Y') }} All Rights Reserved. Powered By Community Healthcare Clinics">
@foreach ($tags as $tag)
<meta name="{{ $tag->name }}" content="{{ $tag->content }}">
@endforeach
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
<style>
    .about-content p {
        text-align: center;
        width: 65%;
        font-size: 13px;
    }

    .desc-container h2 {
        width: 74%;
        font-size: 40px;
        font-weight: normal;
        color: #ffff;
        z-index: 2;
    }

    .desc-container p {
        width: 45%;
        color: #ffff;
        font-weight: 400;
        font-size: 15px;
        z-index: 2;
    }
</style>
@endsection


@section('page_title')
@if ($title != null)
<title>{{ $title->content }} </title>
@else
<title>Community Healthcare Clinics</title>
@endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
    <?php
        header('Access-Control-Allow-Origin: *');
        ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
</script>
@endsection
@section('content')
@php
$page = DB::table('pages')->where('url', '/about-us')->first();
@endphp
<main>
    <section class="new-header w-85 mx-auto rounded-3" data-aos="fade-down" data-aos-duration="800">
        <div class="new-header-inner p-4">
            <h1 class="fs-30 mb-0 fw-semibold">About Us</h1>
            <div>
                <a class="fs-12" href="{{ url('/') }}">Home</a>
                <span class="mx-1 align-middle">></span>
                <a class="fs-12" href="{{ route('about_us') }}">About Us</a>
            </div>
        </div>
    </section>
    <section class="py-3 pt-sm-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
        <div class="container-fluid px-0 w-85">
            <div class="row gx-4 gy-1 mx-auto">
                <div class="px-0 ps-sm-0 px-md-2 ps-xl-0 col-12 col-lg-7" data-aos="fade-right" data-aos-duration="800" data-aos-delay="200">
                    <div class="row g-3">
                        <div class="col-6">
                            <img class="rounded-3 w-100 object-fit-cover" data-aos="zoom-in" data-aos-duration="700" data-aos-delay="300" height="382px"
                                src="{{ asset('assets/new_frontend/about-1.png') }}" alt="" />
                            <img class="rounded-3 object-fit-cover h-25 w-100 mt-3" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400" height="162px"
                                src="{{ asset('assets/new_frontend/about-2.png') }}" alt="" />
                        </div>
                        <div class="col-6">
                            <div
                                class="bg-blue text-white p-4 rounded-3 d-flex flex-column justify-content-center text-center h-25 w-100" height="162px" data-aos="flip-up" data-aos-duration="800" data-aos-delay="350">
                                <h3 class="text-capitalize fw-semibold">
                                    Over 25+ years experience
                                </h3>
                            </div>
                            <img class="rounded-3 object-fit-cover w-100 mt-3"
                            height="382px" data-aos="zoom-in" data-aos-duration="700" data-aos-delay="500"
                                src="{{ asset('assets/new_frontend/about-3.png') }}" alt="" />
                        </div>
                    </div>
                </div>
                <div class="px-0 pe-sm-0 px-md-2 pe-xl-0 col-12 col-lg-5">
                    <div>
                        @php
                        $section = DB::table('section')
                        ->where('page_id', $page->id)
                        ->where('section_name', 'top section')
                        ->where('sequence_no', '1')
                        ->first();
                        $top_content = DB::table('content')
                        ->where('section_id', $section->id)
                        ->first();
                        $image_content = DB::table('images_content')
                        ->where('section_id', $section->id)
                        ->first();
                        @endphp
                        <h2 class="text-capitalize fw-semibold fs-30 mb-3" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400">
                            We Are Community Healthcare Clinics
                        </h2>
                        @if ($top_content)
                        <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="550">{!! $top_content->content !!}</div>
                        @else
                        <p class="mt-4" data-aos="fade-up" data-aos-duration="600" data-aos-delay="550">
                            At Community Healthcare Clinics, we believe that healthcare
                            should be accessible, affordable, and convenient for
                            everyone—no matter where you are. Our digital healthcare
                            platform connects you with top American and Pakistani doctors
                            from the comfort of your home. Whether you need a virtual
                            consultation, a lab test, expert advice, or discounted
                            prescription drugs through our online pharmacy, we’re here to
                            bring international healthcare expertise directly to your
                            doorstep.&nbsp;
                        </p>
                        <p class="mt-4" data-aos="fade-up" data-aos-duration="600" data-aos-delay="750">
                            Doctors are ready to help you get the care you need, anywhere
                            in Karachi. Access to doctors, psychiatrists, psychologists,
                            therapists, and other medical experts care is available.
                            Select and see your favourite providers again and again, right
                            from your smartphone, tablet or computer.
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-4 bg-light-gray-var" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
        <div class="row w-85 mx-auto join-us-cards gx-4 gy-4">
            <div class="col-12">
                <h2 class="text-center fw-semibold fs-30 mb-0" data-aos="fade-down" data-aos-duration="700" data-aos-delay="200">
                    Why Should You Join
                </h2>
                <h2 class="text-center fw-semibold fs-30" data-aos="fade-down" data-aos-duration="700" data-aos-delay="400">
                    Community Healthcare Clinics Today?
                </h2>
            </div>
            <div class="col-lg-3 col-12 col-sm-6 ps-lg-0" data-aos="flip-left" data-aos-duration="800" data-aos-delay="300">
                <div class="card border-blue-2 rounded-5">
                    <div class="card-body p-4 p-md-3 p-xl-4">
                        <div
                            class="icon-join bg-blue rounded-circle d-flex align-items-center justify-content-center mb-4">
                            <img class="w-50 h-50 object-fit-contain"
                                src="{{ asset('assets/new_frontend/book-consult-icon.png') }}"
                                alt="Book Consultation Icon" />
                        </div>
                        <h5 class="card-title mb-4 fs-18">Book a Consultation</h5>
                        <p class="card-text fs-14">
                            Browse our list of top doctors and specialists. Choose the
                            healthcare professional that fits your needs, and schedule
                            your consultation in just a few clicks.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12 col-sm-6" data-aos="flip-left" data-aos-duration="800" data-aos-delay="400">
                <div class="card border-blue-2 rounded-5">
                    <div class="card-body p-4 p-md-3 p-xl-4">
                        <div
                            class="icon-join bg-blue rounded-circle d-flex align-items-center justify-content-center mb-4">
                            <img class="w-50 h-50 object-fit-contain"
                                src="{{ asset('assets/new_frontend/e-visit-icon-new.png') }}"
                                alt="Book Consultation Icon" />
                        </div>
                        <h5 class="card-title mb-4 fs-18">Consult Virtually</h5>
                        <p class="card-text fs-14">
                            Speak with your doctor via secure video call or chat. No
                            waiting rooms, no travel—just personalized care from the
                            comfort of your own home.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12 col-sm-6" data-aos="flip-left" data-aos-duration="800" data-aos-delay="500">
                <div class="card border-blue-2 rounded-5">
                    <div class="card-body p-4 p-md-3 p-xl-4">
                        <div
                            class="icon-join bg-blue rounded-circle d-flex align-items-center justify-content-center mb-4">
                            <img class="w-50 h-50 object-fit-contain"
                                src="{{ asset('assets/new_frontend/new-test-icon.png') }}"
                                alt="Book Consultation Icon" />
                        </div>
                        <h5 class="card-title mb-4 fs-18">Access Test Results & Care Plans</h5>
                        <p class="card-text fs-14">
                            After your consultation, you’ll receive a detailed care plan
                            and prescriptions, if necessary. Schedule lab tests easily and
                            receive results online.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12 col-sm-6 pe-lg-0" data-aos="flip-left" data-aos-duration="800" data-aos-delay="600">
                <div class="card border-blue-2 rounded-5">
                    <div class="card-body p-4 p-md-3 p-xl-4">
                        <div
                            class="icon-join bg-blue rounded-circle d-flex align-items-center justify-content-center mb-4">
                            <img class="w-50 h-50 object-fit-contain"
                                src="{{ asset('assets/new_frontend/order-icon-new.png') }}"
                                alt="Book Consultation Icon" />
                        </div>
                        <h5 class="card-title mb-4 fs-18">Order your Medications Conveniently</h5>
                        <p class="card-text fs-14">
                            Order your medications conveniently from our online pharmacy
                            and have them delivered right to your doorstep. Enjoy fast,
                            secure prescriptions and expert guidance for all your health
                            needs
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection