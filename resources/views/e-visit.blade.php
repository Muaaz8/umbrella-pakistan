@extends('layouts.frontend')

@section('content')
<div id="breadcrumb" class="division">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class=" breadcrumb-holder">

                    <!-- Breadcrumb Nav -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">E-visit</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">E-visit</h4>

                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END BREADCRUMB -->
<section id="info-4" class="wide-100 info-section division">
    <div class="container">


        <!-- TOP ROW -->
        <div class="top-row mb-80">
            <div class="row d-flex align-items-center">


                <!-- INFO IMAGE -->
                <div class="col-lg-6">
                    <div class="info-4-img text-center wow fadeInUp" data-wow-delay="0.6s">
                        <img class="img-fluid" src="asset_frontend/images/image-03.png" alt="info-image">
                    </div>
                </div>


                <!-- INFO TEXT -->
                <div class="col-lg-6">
                    <div class="txt-block pc-30 wow fadeInUp" data-wow-delay="0.4s" style="font-weight: 400;
    font-size: 1.7rem;
    line-height: 30px;">

                        <!-- Section ID -->

                        <!-- Title -->
                        <h3 class="h3-md steelblue-color">Talk To Our Doctors</h3>

                        <!-- Text -->
                        Umbrella Health Care Systems provide you
                        with facility to visit doctors, therapist, or medical expert
                        online.<br>
                        Talk to a doctor, therapist, or medical expert anywhere you are by phone or video.

                        Our telehealth solutions make it easy for people to access best-in-class care whenever and
                        wherever, while driving down overall healthcare costs.
                        <br>
                        High quality, convenient healthcare on your schedule.
                        We offer telehealth services for common medical issues, as well as telebehavioral health
                        services for emotional and mental health concerns.
                        <br>
                        We leverage the latest technology to simplify and personalize both the organization’s and the
                        member’s experience.
                        <br>
                        Our dedication to clinical excellence ensures that you have a safe and secure consultation with
                        every patient.
                        <br>

                        Talk with a doctor using our highly secured HIPAA complaint end-to-end encrypted video call. Our
                        video call service helps you to speak about your health issues with a doctor on a face to face
                        live interaction.

                        @auth
                        @php $user_type=auth()->user()->user_type; @endphp
                        @if($user_type=='patient')
                        <a href="{{route('patient_evisit_specialization')}}"><button class="btn mt-2 col-12 "
                                style="font-size:20px;background-color:#08295A !important;color:white !important">
                                Talk To Doctor</button></a>
                        @elseif($user_type=='doctor')
                        <a href="{{route('doc_waiting_room')}}"><button class="btn mt-2 col-12 "
                                style="font-size:20px;background-color:#08295A !important;color:white !important">
                                Online Patients</button></a>
                        @else
                        <a href="/allProducts"><button class="btn mt-2 col-12 "
                                style="font-size:20px;background-color:#08295A !important;color:white !important">
                                Talk To Doctor</button></a>

                        @endif
                        @endauth
                        @guest
                        <a href="{{url('/patient_register')}}"><button class="btn mt-2 col-12 "
                                style="font-size:20px;background-color:#08295A !important;color:white !important">
                                Talk To Doctor</button></a>
                        <!-- <a href="{{url('register?type=doctor')}}"></a><button class="btn theme-color mt-2 col-10 ml-5"
                                                        style="padding:10px 50px;font-size:20px">Doctors</button> -->
                        @endguest
                        <!-- Singnature -->
                    </div>
                </div> <!-- END TEXT BLOCK -->


            </div> <!-- End row -->
        </div> <!-- END TOP ROW -->

    </div>
</section>

@endsection
