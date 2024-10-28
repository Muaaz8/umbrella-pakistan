@extends('layouts.frontend')

@section('content')
<!-- BREADCRUMB
			============================================= -->
<div id="breadcrumb" class="division">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class=" breadcrumb-holder">

                    <!-- Breadcrumb Nav -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">About Us</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">About Us</h4>

                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END BREADCRUMB -->




<!-- INFO-4
			============================================= -->
<section id="info-4" class="wide-100 info-section division">
    <div class="container">


        <!-- TOP ROW -->
        <div class="top-row mb-80">
            <div class="row d-flex align-items-center">


                <!-- INFO IMAGE -->
                <div class="col-lg-6">
                    <div class="info-4-img text-center wow fadeInUp" data-wow-delay="0.6s">
                        <img class="img-fluid" src="asset_frontend/images/chief_doctor_700x800.jpg" alt="info-image">
                    </div>
                </div>


                <!-- INFO TEXT -->
                <div class="col-lg-6">
                    <div class="txt-block pc-30  fadeInUp" data-wow-delay="0.4s">

                        <!-- Section ID -->
                        <span class="section-id blue-color">Welcome to Umbrella Health Care Systems</span>

                        <!-- Title -->
                        <h3 class="h3-md steelblue-color">Telemedicine with latest technology</h3>

                        <!-- Text -->
                        <p>Umbrella Health Care Systems is bringing hospital to your house. Physicians and patients can
                            share information in real time from one computer screen to
                            another. Using Umbrella Health Care Systems services, patients can see a doctor for
                            diagnosis and
                            treatment
                            without having to actually go to clinic or hospital. Patients can consult a physician at the
                            comfort
                            of their home.
                        </p>

                        <!-- Text -->
                        <p> Doctors are ready to help you get the care you need,
                                        anywhere in the United States.


                                        Access to doctors, psychiatrists, psychologists,
                                        therapists and other medical experts,
                                        care is available from 07:00 AM to 08:00 PM. Select and
                                        see your favorite providers again and
                                        again, right from your smartphone, tablet or computer.
                        </p>
                        <!-- Singnature -->
                        <!-- <div class="singnature mt-35">

                            <p class="p-small mb-15">Randon Pexon, Head of Clinic</p>

                            <img class="img-fluid" src="asset_frontend/images/signature.png" width="200" height="34"
                                alt="signature-image" />

                        </div> -->

                    </div>
                </div> <!-- END TEXT BLOCK -->


            </div> <!-- End row -->
        </div> <!-- END TOP ROW -->


        <!-- BOTTOM ROW -->
        <div class="bottom-row">
            <div class="row d-flex align-items-center">


                <!-- INFO TEXT -->
                <div class="col-lg-6">
                    <div class="txt-block pc-30  fadeInUp" data-wow-delay="0.4s">

                        <!-- Section ID -->
                        <span class="section-id blue-color">Highest Quality Care</span>

                        <!-- Title -->
                        <h3 class="h3-md steelblue-color">Complete Medical Solutions in One Place</h3>

                        <!-- Text -->
                        <p class="mb-30">Umbrella Health Care Systems provide complete solution for health related
                            problems.Umbrella E-Pharmacy contains variety of prescribed and over the counter medicines
                            with efficient shipping services.
                            Umbrella E-Lab Are State Of The Art Lab Services. Imaging, Substance Abuse and Dermatology
                            services are also coming soon.
                        </p>

                        {{--      <!-- Options List -->
                        <div class="row">

                            <div class="col-xl-6">

                                <!-- Option #1 -->
                                <div class="box-list">
                                    <div class="box-list-icon blue-color"><i class="fas fa-angle-double-right"></i>
                                    </div>
                                    <p class="p-sm">Nemo ipsam egestas volute and turpis dolores quaerat</p>
                                </div>

                                <!-- Option #2 -->
                                <div class="box-list">
                                    <div class="box-list-icon blue-color"><i class="fas fa-angle-double-right"></i>
                                    </div>
                                    <p class="p-sm">Magna luctus tempor</p>
                                </div>

                                <!-- Option #3 -->
                                <div class="box-list">
                                    <div class="box-list-icon blue-color"><i class="fas fa-angle-double-right"></i>
                                    </div>
                                    <p class="p-sm">An enim nullam tempor at pretium purus blandit</p>
                                </div>

                            </div>

                            <div class="col-xl-6">

                                <!-- Option #4 -->
                                <div class="box-list">
                                    <div class="box-list-icon blue-color"><i class="fas fa-angle-double-right"></i>
                                    </div>
                                    <p class="p-sm">Magna luctus tempor blandit a vitae suscipit mollis</p>
                                </div>

                                <!-- Option #5 -->
                                <div class="box-list">
                                    <div class="box-list-icon blue-color"><i class="fas fa-angle-double-right"></i>
                                    </div>
                                    <p class="p-sm">Nemo ipsam egestas volute turpis dolores quaerat</p>
                                </div>

                                <!-- Option #6 -->
                                <div class="box-list">
                                    <div class="box-list-icon blue-color"><i class="fas fa-angle-double-right"></i>
                                    </div>
                                    <p class="p-sm">An enim nullam tempor</p>
                                </div>

                            </div>

                        </div> <!-- End Options List -->
--}}
                    </div>
                </div> <!-- END INFO TEXT -->


                <!-- INFO IMAGE -->
                <div class="col-lg-6">
                    <div class="info-4-img text-center  fadeInUp" data-wow-delay="0.6s">
                        <img class="img-fluid" src="asset_frontend/images/emergency_help_700x800.jpg" alt="info-image">
                    </div>
                </div>


            </div> <!-- End row -->
        </div> <!-- END BOTTOM ROW -->


    </div> <!-- End container -->
</section> <!-- END INFO-4 -->




<!-- STATISTIC-1
			============================================= -->
<div id="statistic-1" class="bg-scroll statistic-section division">
    <div class="container white-color">
        <div class="row">


            <!-- STATISTIC BLOCK #1 -->
            <div class="col-md-6 col-lg-4">
                <div class="statistic-block icon-lg  fadeInUp" data-wow-delay="0.4s">

                    <!-- Icon  -->
                    <span class="">
                        <i class="fas fa-heartbeat"  style="font-size:70px"></i>
                    </span>

                    <!-- Text -->
                    <h5 class="statistic-number">9,<span class="count-element">632</span></h5>
                    <p class="txt-400">Happy Patients</p>

                </div>
            </div>


            <!-- STATISTIC BLOCK #2 -->
            <div class="col-md-6 col-lg-4">
                <div class="statistic-block icon-lg  fadeInUp" data-wow-delay="0.6s">

                    <!-- Icon  -->
                    <span class="">
                        <i class="fas fa-user-md"  style="font-size:70px"></i>
                    </span>

                    <!-- Text -->
                    <h5 class="statistic-number"><span class="count-element">178</span></h5>
                    <p class="txt-400">Qualified Doctors</p>

                </div>
            </div>


            <!-- STATISTIC BLOCK #3 -->
            <div class="col-md-6 col-lg-4">
                <div class="statistic-block icon-lg  fadeInUp" data-wow-delay="0.8s">

                    <!-- Icon  -->
                    <span class=""><i class="fas fa-capsules"  style="font-size:70px"></i></span>
                    <!-- Text -->
                    <h5 class="statistic-number">1,<span class="count-element">864</span></h5>
                    <p class="txt-400">Products In Pharmacy</p>

                </div>
            </div>


            <!-- STATISTIC BLOCK #4 -->
            {{-- <div class="col-md-6 col-lg-3">
                <div class="statistic-block icon-lg  fadeInUp" data-wow-delay="1s">

                    <!-- Icon  -->
                    <span class="flaticon-040-placeholder"></span>

                    <!-- Text -->
                    <h5 class="statistic-number"><span class="count-element">473</span></h5>
                    <p class="txt-400">Local Partners</p>

                </div>
            </div> --}}


        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END STATISTIC-1 -->




<!-- VIDEO-2
			============================================= -->
{{--<section id="video-2" class="wide-60 video-section division">
    <div class="container">
        <div class="row d-flex align-items-center">


            <!-- VIDEO LINK -->
            <div class="col-lg-6">
                <div class="video-preview mb-40 text-center  fadeInUp" data-wow-delay="0.6s">

                    <!-- Change the link HERE!!! -->
                    <a class="video-popup1" href="https://www.youtube.com/embed/SZEflIVnhH8">

                        <!-- Play Icon -->
                        <div class="video-btn play-icon-blue">
                            <div class="video-block-wrapper">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>

                        <!-- Preview -->
                        <img class="img-fluid" src="asset_frontend/images/video-1.png" alt="video-photo" />

                    </a>

                </div>
            </div> <!-- END VIDEO LINK -->


            <!-- VIDEO TEXT -->
            <div class="col-lg-6">
                <div class="txt-block pc-30 mb-40 wow fadeInUp" data-wow-delay="0.4s">

                    <!-- Section ID -->
                    <span class="section-id blue-color">Modern Medicine</span>

                    <!-- Title -->
                    <h3 class="h3-md steelblue-color">Better Technologies for Better Healthcare</h3>

                    <!-- CONTENT BOX #1 -->
                    <div class="box-list m-top-15">
                        <div class="box-list-icon"><i class="fas fa-genderless"></i></div>
                        <p>Nemo ipsam egestas volute turpis dolores ut aliquam quaerat sodales sapien undo pretium
                            purus feugiat dolor impedit
                        </p>
                    </div>

                    <!-- CONTENT BOX #2 -->
                    <div class="box-list">
                        <div class="box-list-icon"><i class="fas fa-genderless"></i></div>
                        <p>Gravida quis vehicula magna luctus tempor quisque vel laoreet turpis urna augue,
                            viverra a augue eget dictum
                        </p>
                    </div>

                    <!-- CONTENT BOX #3 -->
                    <div class="box-list">
                        <div class="box-list-icon"><i class="fas fa-genderless"></i></div>
                        <p>Nemo ipsam egestas volute turpis dolores ut aliquam quaerat sodales sapien undo pretium
                            purus feugiat dolor impedit
                        </p>
                    </div>

                </div>
            </div>


        </div> <!-- End row -->
    </div> <!-- End container -->
</section> <!-- END VIDEO-2 -->




<!-- SERVICES-7
			============================================= -->
<section id="services-7" class="bg-lightgrey wide-70 servicess-section division">
    <div class="container">
        <div class="row">


            <!-- SERVICE BOXES -->
            <div class="col-lg-8">
                <div class="row">


                    <!-- SERVICE BOX #1 -->
                    <div class="col-md-6">
                        <div class="sbox-7 icon-xs wow fadeInUp" data-wow-delay="0.4s">
                            <a href="service-1.html">

                                <!-- Icon -->
                                <span class="flaticon-137-doctor blue-color"></span>

                                <!-- Text -->
                                <div class="sbox-7-txt">

                                    <!-- Title -->
                                    <h5 class="h5-sm steelblue-color">Top Level Doctors</h5>

                                    <!-- Text -->
                                    <p class="p-sm">Porta semper lacus at cursus primis ultrice in ligula risus an
                                        auctor tempus feugiat dolor
                                    </p>

                                </div>

                            </a>
                        </div>
                    </div> <!-- END SERVICE BOX #1 -->


                    <!-- SERVICE BOX #2 -->
                    <div class="col-md-6">
                        <div class="sbox-7 icon-xs wow fadeInUp" data-wow-delay="0.6s">
                            <a href="service-2.html">

                                <!-- Icon -->
                                <span class="flaticon-076-microscope blue-color"></span>

                                <!-- Text -->
                                <div class="sbox-7-txt">

                                    <!-- Title -->
                                    <h5 class="h5-sm steelblue-color">Modern Equipment</h5>

                                    <!-- Text -->
                                    <p class="p-sm">Porta semper lacus at cursus primis ultrice in ligula risus an
                                        auctor tempus feugiat dolor
                                    </p>

                                </div>

                            </a>
                        </div>
                    </div> <!-- END SERVICE BOX #2 -->


                    <!-- SERVICE BOX #3 -->
                    <div class="col-md-6">
                        <div class="sbox-7 icon-xs wow fadeInUp" data-wow-delay="0.8s">
                            <a href="service-1.html">

                                <!-- Icon -->
                                <span class="flaticon-065-hospital-bed blue-color"></span>

                                <!-- Text -->
                                <div class="sbox-7-txt">

                                    <!-- Title -->
                                    <h5 class="h5-sm steelblue-color">Qualified Facilities</h5>

                                    <!-- Text -->
                                    <p class="p-sm">Porta semper lacus at cursus primis ultrice in ligula risus an
                                        auctor tempus feugiat dolor
                                    </p>

                                </div>

                            </a>
                        </div>
                    </div> <!-- END SERVICE BOX #3-->


                    <!-- SERVICE BOX #4 -->
                    <div class="col-md-6">
                        <div class="sbox-7 icon-xs wow fadeInUp" data-wow-delay="1s">
                            <a href="service-2.html">

                                <!-- Icon -->
                                <span class="flaticon-058-blood-transfusion-2 blue-color"></span>

                                <!-- Text -->
                                <div class="sbox-7-txt">

                                    <!-- Title -->
                                    <h5 class="h5-sm steelblue-color">Professional Services</h5>

                                    <!-- Text -->
                                    <p class="p-sm">Porta semper lacus at cursus primis ultrice in ligula risus an
                                        auctor tempus feugiat dolor
                                    </p>

                                </div>

                            </a>
                        </div>
                    </div> <!-- END SERVICE BOX #4 -->


                    <!-- SERVICE BOX #5 -->
                    <div class="col-md-6">
                        <div class="sbox-7 icon-xs wow fadeInUp" data-wow-delay="1.2s">
                            <a href="service-1.html">

                                <!-- Icon -->
                                <span class="flaticon-141-clinic-history blue-color"></span>

                                <!-- Text -->
                                <div class="sbox-7-txt">

                                    <!-- Title -->
                                    <h5 class="h5-sm steelblue-color">Medical Counseling</h5>

                                    <!-- Text -->
                                    <p class="p-sm">Porta semper lacus at cursus primis ultrice in ligula risus an
                                        auctor tempus feugiat dolor
                                    </p>

                                </div>

                            </a>
                        </div>
                    </div> <!-- END SERVICE BOX #5 -->


                    <!-- SERVICE BOX #6 -->
                    <div class="col-md-6">
                        <div class="sbox-7 icon-xs wow fadeInUp" data-wow-delay="1.4s">
                            <a href="service-2.html">

                                <!-- Icon -->
                                <span class="flaticon-008-ambulance-6 blue-color"></span>

                                <!-- Text -->
                                <div class="sbox-7-txt">

                                    <!-- Title -->
                                    <h5 class="h5-sm steelblue-color">Emergency Help</h5>

                                    <!-- Text -->
                                    <p class="p-sm">Porta semper lacus at cursus primis ultrice in ligula risus an
                                        auctor tempus feugiat dolor
                                    </p>

                                </div>

                            </a>
                        </div>
                    </div> <!-- END SERVICE BOX #6 -->


                </div>
            </div> <!-- END SERVICE BOXES -->


            <!-- INFO TABLE -->
            <div class="col-lg-4">
                <div class="services-7-table blue-table mb-30 wow fadeInUp" data-wow-delay="0.6s">

                    <!-- Title -->
                    <h4 class="h4-xs">Opening Hours:</h4>

                    <!-- Text -->
                    <p class="p-sm">Porta semper lacus cursus and feugiat primis ultrice ligula risus auctor
                        tempus feugiat and dolor lacinia cursus
                    </p>

                    <!-- Table -->
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Mon – Wed</td>
                                <td> - </td>
                                <td class="text-right">9:00 AM - 7:00 PM</td>
                            </tr>
                            <tr>
                                <td>Thursday</td>
                                <td> - </td>
                                <td class="text-right">9:00 AM - 6:30 PM</td>
                            </tr>
                            <tr>
                                <td>Friday</td>
                                <td> - </td>
                                <td class="text-right">9:00 AM - 6:00 PM</td>
                            </tr>
                            <tr class="last-tr">
                                <td>Sun - Sun</td>
                                <td>-</td>
                                <td class="text-right">CLOSED</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Title -->
                    <h5 class="h5-sm">Need a personal health plan?</h5>

                    <!-- Text -->
                    <p class="p-sm">Porta semper lacus cursus, and feugiat primis ultrice ligula at risus auctor</p>

                </div>
            </div> <!-- END INFO TABLE -->


        </div> <!-- End row -->
    </div> <!-- End container -->
</section> <!-- END SERVICES-7 -->--}}




<!-- BANNER-5
			============================================= -->
<section id="banner-5" class="pt-100 banner-section division d-none">
    <div class="container">


        <!-- SECTION TITLE -->
        <div class="row">
            <div class="col-lg-10 offset-lg-1 section-title">

                <!-- Title 	-->
                <h3 class="h3-md steelblue-color">Certified and Experienced Doctors</h3>

                <!-- Text -->
                <p>Aliquam a augue suscipit, luctus neque purus ipsum neque dolor primis libero at tempus,
                    blandit posuere ligula varius congue cursus porta feugiat
                </p>

            </div>
        </div>


        <!-- BANNER IMAGE -->
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="banner-5-img wow fadeInUp" data-wow-delay="0.4s">
                    <img class="img-fluid" src="asset_frontend/images/image-07.png" alt="banner-image" />
                </div>
            </div>
        </div>


    </div> <!-- End container -->
</section> <!-- END BANNER-5 -->


@endsection