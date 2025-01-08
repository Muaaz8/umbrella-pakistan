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

<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
<title>American Doctors| Community Healthcare Clinics</title>
@endsection

@section('content')

<main class="w-100">
    <section class="w-100 flex align-items-center justify-content-center">
        <div class="hero-section-doctor flex align-items-center justify-content-center">
            <div
                class="hero-doctor-container my-3 flex flex-column gap-4 align-items-center justify-content-center text-white position-relative z-1 text-center">
                <h4 class="hero-small-heading bg-white px-3 py-1 rounded-5">
                    We are here to take care of you
                </h4>
                <h2 class="hero-doctor-heading">
                    Get an American Doctor Consultation on Demand
                </h2>
                <p class="hero-doctor-para">
                    Our platform connects you with top American doctors, renowned for
                    their expertise and compassionate care. Get expert medical advice,
                    second opinions, and personalized care from the comfort of your
                    own home.
                </p>
            </div>
        </div>
    </section>
    <section class="w-100 d-flex align-items-center justify-content-center">
        <div class="specialization-section fw-bold d-flex align-items-center justify-content-center flex-column my-3">
            <div class="specialization-container d-flex flex-column gap-4">
                <div class="flex flex-column w-100">
                    <h2 class="text-center">
                        Our American Doctor
                        <span class="red">Specializes in</span>
                    </h2>
                    <div class="underline w-25"></div>
                </div>
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="specialized-column ps-4 pt-4 d-flex flex-column gap-1 shadow rounded-3">
                            <h3 class="pe-4">Primary Care</h3>
                            <p class="fw-normal pe-4 truncate-overflow">
                                Routine check-ups, chronic disease management, and
                                preventive care.
                            </p>
                            <div class="d-flex align-content-center justify-content-end specialized-icons">
                                <i class="fa-solid fa-hand-holding-medical"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="specialized-column ps-4 pt-4 d-flex flex-column gap-1 shadow rounded-3">
                            <h3 class="pe-4">Cardiology</h3>
                            <p class="fw-normal pe-4 truncate-overflow">
                                Expert advice on heart health, high blood pressure, and
                                coronary artery disease.
                            </p>
                            <div class="d-flex align-content-center justify-content-end specialized-icons">
                                <i class="fa-solid fa-hand-holding-medical"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="specialized-column ps-4 pt-4 d-flex flex-column gap-1 shadow rounded-3">
                            <h3 class="pe-4">Pain Management</h3>
                            <p class="fw-normal pe-4 truncate-overflow">
                                Specialized care for chronic pain, fibromyalgia, and other
                                pain-related conditions.
                            </p>
                            <div class="d-flex align-content-center justify-content-end specialized-icons">
                                <i class="fa-solid fa-hand-holding-medical"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="specialized-column ps-4 pt-4 d-flex flex-column gap-1 shadow rounded-3">
                            <h3 class="pe-4">Drugs/Substance Management</h3>
                            <p class="fw-normal pe-4 truncate-overflow">
                                Expert help for addiction and substance abuse.
                            </p>
                            <div class="d-flex align-content-center justify-content-end specialized-icons">
                                <i class="fa-solid fa-hand-holding-medical"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="w-100 d-flex align-items-center justify-content-center">
        <div
            class="specialization-section w-100 fw-bold d-flex align-items-center justify-content-center flex-column my-3">
            <div class="specialization-container d-flex flex-column gap-4">
                <div class="flex flex-column w-100">
                    <h2>
                        How it
                        <span class="red">Works</span>
                    </h2>
                    <div class="underline w-25"></div>
                </div>
                <div class="row g-4">
                    <div @if (Auth::check()) data-bs-toggle="modal" data-bs-target="#appointmentModal" @else
                        data-bs-toggle="modal" data-bs-target="#loginModal" @endif
                        class="d-flex pointer gap-2 col-12 col-md-6 col-lg-4">
                        <div class="work-column w-100 d-flex gap-1 align-items-center shadow-sm rounded-3">
                            <div class="d-flex align-items-center justify-content-center work-icons">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="work-headings px-2">
                                <h3 class="">Book Your Consultation</h3>
                                <p class="fw-normal">
                                    Schedule a video call with an American doctor.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div @if (Auth::check()) data-bs-toggle="modal" data-bs-target="#appointmentModal" @else
                        data-bs-toggle="modal" data-bs-target="#loginModal" @endif
                        class="d-flex pointer gap-2 col-12 col-md-6 col-lg-4">
                        <div class="work-column w-100 d-flex gap-1 align-items-center shadow-sm rounded-3">
                            <div class="d-flex align-items-center justify-content-center work-icons">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <div class="work-headings px-2">
                                <h3 class="">Choose Your Specialty</h3>
                                <p class="fw-normal">
                                    Select the area of expertise you need guidance on.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div @if (Auth::check()) data-bs-toggle="modal" data-bs-target="#appointmentModal" @else
                        data-bs-toggle="modal" data-bs-target="#loginModal" @endif
                        class="d-flex pointer gap-2 col-12 col-md-6 mx-md-auto col-lg-4">
                        <div
                            class="work-column w-100 d-flex gap-1 align-items-center justify-content-md-center shadow-sm rounded-3">
                            <div class="d-flex align-items-center justify-content-center work-icons">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div class="work-headings px-2">
                                <h3 class="">Get Personalized Advice</h3>
                                <p class="fw-normal">
                                    Consult with your American doctor and receive tailored
                                    guidance.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="w-100 d-flex align-items-center justify-content-center">
        <div class="healthcare-section my-3 row justify-content-center align-items-center">
            <div class="col-md-6 col-12">
                <div class="d-flex flex-column gap-3 justify-content-center h-100">
                    <h4 class="healthcare-small-heading bg-white px-2 py-1 rounded-5">
                        Your health and happiness are our top priority.
                    </h4>
                    <h2 class="healthcare-large-heading fw-bolder">
                        <span class="red">Empowering Patients with</span> Virtual
                        Healthcare Solutions
                    </h2>
                    <p>
                        We connect you with top American doctors for personalized care
                        and expert advice, empowering you to take control of your health
                        and well-being. With the latest advancements in healthcare
                        technology, we provide timely, reliable, and compassionate
                        solutions tailored to your unique needs.
                    </p>
                    <p>
                        At Community Healthcare Clinics, we understand the importance
                        of accessing quality healthcare, regardless of your geographical
                        location. That's why we've bridged the gap between you and top
                        American doctors, providing a seamless and secure
                        teleconsultation experience. Our commitment to excellence drives
                        us to deliver exceptional healthcare services that prioritize
                        your well-being. With our virtual consultations, you can:
                    </p>
                    <ul>
                        <li>Access expert medical advice from top American doctors</li>
                        <li>
                            Receive personalized care and treatment plans tailored to your
                            needs
                        </li>
                        <li>
                            Enjoy the convenience and flexibility of online consultations
                        </li>
                        <li>
                            Benefit from the latest advancements in healthcare technology
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-6 side-container-image d-none d-md-block">
                <div class="object-fit-cover image-container-virtual">
                    <img src="{{ asset('assets/new_frontend/american-doc-virtual-img.jpg')}}" alt="" />
                </div>
            </div>
        </div>
    </section>
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



<div class="modal fade appointmentModal" id="appointmentModal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                    Book Appointment
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column gap-3">
                <div class="w-100 d-flex gap-3">
                    <div class="d-flex flex-column align-items-start w-100">
                        <label for="time">Your Preferred Time:</label>
                        <input type="time" class="time w-100 py-1 px-1 rounded-1 border-1 border-black" name="time"
                            id="time" />
                    </div>
                    <div class="d-flex flex-column align-items-start w-100">
                        <label for="time">Your Preferred Date:</label>
                        <input type="date" class="date w-100 py-1 px-1 rounded-1 border-1 border-black" name="time"
                            id="time" />
                    </div>
                </div>
                <div class="w-100">
                    <textarea class="reason w-100 px-1 py-1 rounded-1 border-1 border-black" name="reason" id="reason"
                        rows="4" placeholder="Reason"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
