<div class="tab-pane fade show active" id="tab-0" role="tabpanel" aria-labelledby="tab0-list">
    <div class="row d-flex align-items-center">
        <section id="video-1" class="wide-10 video-section division mt-4">
            <div class="container">
                <div class="container section-bg mb-5">
                    <div class="row d-flex align-items-center section-bg">
                        <div class="col-lg-6">
                            <div class="txt-block pc-30 mb-40 fadeInUp" data-wow-delay="0.4s">
                                <div class="box-list">

                                    <p class="sub_heading h3-md align-items-center" style="color: #004861 !important;">
                                        E-Visit</p>
                                    <p class="sub_heading_desc"
                                        style="font-weight:400; font-size:1.7rem; line-height:30px;">
                                        Umbrella Health Care Systems provide you
                                        with facility to visit doctors, therapist, or medical expert
                                        online.
                                        Find best Doctors to get instant medical
                                        advice for your health problems.<br> Ask the
                                        doctors online and consult them on face-to-face video chat and
                                        get solution to your medical problems from home.</p>

                                    @auth
                                    @php $user_type=auth()->user()->user_type; @endphp
                                    @if ($user_type == 'patient')
                                    <a href="{{ route('evisit.specialization') }}"><button class="btn mt-2 col-12"
                                            style="margin: 0px 0px 20px 0px; padding:10px 50px;font-size:20px;background-color:#08295A !important;color:white !important">
                                            Talk To Doctor</button></a>
                                    @elseif($user_type == 'doctor')
                                    <a href="{{ route('doc_waiting_room') }}"><button class="btn mt-2 col-12"
                                            style="padding:10px 50px;font-size:20px;background-color:#08295A !important;color:white !important">
                                            Online Patients</button></a>
                                    @else
                                    <a href="{{ url('/allProducts') }}"><button class="btn mt-2 col-12"
                                            style="margin: 0px 0px 20px 0px;padding:10px 50px;font-size:20px;background-color:#08295A !important;color:white !important">
                                            Talk To Doctor</button></a>
                                    @endif
                                    @endauth
                                    @guest
                                    <a href="{{ url('patient_register') }}"><button class="btn mt-2 col-12"
                                            style="margin: 0px 0px 20px 0px; padding:20px 50px;font-size:20px;background-color:#08295A !important;color:white !important">
                                            Talk To Doctor</button></a>
                                    @endguest
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-5 mb-5">
                            <div class="video-preview mb-40 text-center  fadeInUp" style="" data-wow-delay="0.6s">
                                <a class="video-popup1" href="https://www.youtube.com/embed/SZEflIVnhH8">
                                    <div class="video-btn play-icon-blue"
                                        style="background-color:#08295a!important;border-color:#08295a!important">
                                        <div class="video-block-wrapper">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                    <img class="img-fluid mt-5" src="asset_frontend/images/gallery/image-1.jpg"
                                        alt="video-photo" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="statistic-3" class="wide-60 statistic-section division ">
                    <div class="container section-bg mb-5">
                        <div class="row d-flex align-items-center">
                            <div class="col-lg-6 mb-5">
                                <div class="txt-block pc-30 mb-40 fadeInUp" data-wow-delay="0.4s"
                                    style="visibility: visible !imporant;">

                                    <!-- Section ID
                                                                            <span class="box-title font-weight-bold blue-color">Best Practices</span>
                                                        -->
                                    <!-- Title -->
                                    <h3 class="h3-md steelblue-color mb-5 mt-5">Solutions to Complex
                                        Medical Problems</h3>

                                    <!-- Text -->
                                    <p style="font-size:1.7rem; font-weight:400; text-align:justify;">
                                        Talk to a doctor, therapist, or
                                        medical expert anywhere you are by phone
                                        or video.
                                        Our telehealth solutions make it easy for people to access
                                        best-in-class care whenever and
                                        wherever, while driving down overall healthcare costs.
                                        High quality, convenient healthcare on your schedule.
                                        We offer telehealth services for common medical issues, as
                                        well as telebehavioral health
                                        services for emotional and mental health concerns.
                                        We leverage the latest technology to simplify and
                                        personalize both the organization’s and the
                                        member’s experience.
                                        Our dedication to clinical excellence ensures that you have
                                        a safe and secure consultation with
                                        every patient.
                                        <!-- Talk with a doctor using our highly secured HIPAA complaint end-to-end encrypted video call.  -->
                                        Our
                                        video call service helps you to speak about your health
                                        issues with a doctor on a face to face
                                        live interaction.
                                    </p>

                                </div>
                            </div> <!-- END TEXT BLOCK -->


                            <!-- STATISTIC IMAGE -->
                            <div class="col-lg-6">
                                <div class="statistic-img text-center fadeInUp" style="left:-8% !important;"
                                    data-wow-delay="0.6s">
                                    <img class="img-fluid" src="asset_frontend/images/image-04.png"
                                        alt="statistic-image" />
                                </div>
                            </div>


                        </div> <!-- End row -->
                    </div> <!-- End container -->
                </div> <!-- END STATISTIC-3 -->


                <section id="doctors-1" class="wide-40 doctors-section division">
                    <div class="container section-bg mb-5 mt-3">


                        <!-- SECTION TITLE -->
                        <div class="row d-flex justify-content-center">

                            <div class="col-lg-12 section-title">

                                <!-- Title 	-->
                                <h3 class="h3-md steelblue-color mt-5">Our Medical Specialists</h3>

                                <!-- Text -->
                                <div class="txt-block pc-30" data-wow-delay="0.4s">
                                    <p
                                        style="font-weight:400; font-size:1.7rem; text-align:justify; color:black;padding:0px">
                                        Doctors are ready to help you get the care you need,
                                        anywhere in the United States.

                                        Our doctors, psychiatrists, psychologists,
                                        therapists and other medical experts are available from 07:00 am to 08:00 pm.
                                    </p>
                                </div>
                            </div> <!-- END SECTION TITLE -->
                        </div>
                        <div class="container p-5">
                            <div class="row">

                                @foreach ($doctors as $doctor)
                                <!-- DOCTOR #1 -->
                                <div class="col-md-6 col-lg-3">

                                    <div class="doctor-1">

                                        <!-- Doctor Photo -->
                                        <div class="hover-overlay text-center">
                                            <!-- Photo -->
                                            <img class="img-fluid wel-img"
                                                src="{{$doctor->user_image }}"
                                                alt="{{ $doctor->name }}">
                                            <div class="item-overlay"></div>

                                        </div>

                                        <!-- Doctor Meta -->
                                        <div class="doctor-meta text-center">

                                            <h5 class="h5-sm steelblue-color">Dr.
                                                {{ $doctor->name . ' ' . $doctor->last_name }}
                                            </h5>
                                            <span class="blue-color">General </span>

                                            <!-- <p class="p-sm grey-color">Donec vel sapien augue integer turpis cursus porta, mauris sed
                                                                                    augue luctus magna dolor luctus ipsum neque
                                                                                </p> -->

                                        </div>

                                    </div>
                                </div> <!-- END DOCTOR #1 -->
                                @endforeach

                            </div> <!-- End row -->


                            <!-- ALL DOCTORS BUTTON -->
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="all-doctors mb-40">
                                        <a href="{{ route('our_doctors') }}" class="btn theme-color py-3"
                                            style="font-size:22px; width: 300px;">Meet All
                                            Doctors</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div> <!-- End container -->
                </section> <!-- END DOCTORS-1 -->




                <!-- ABOUT-6
                                                           ============================================= -->
                <section id="about-6" class="pt-100 about-section division">
                    <div class="container section-bg mb-5">
                        <div class="row d-flex align-items-center">


                            <!-- TEXT BLOCK -->
                            <div class="col-lg-6">
                                <div class="txt-block pc-30  fadeInUp" data-wow-delay="0.4s">

                                    <!-- Section ID
                                                                            <span class="font-weight-bold blue-color box-title">Highest Quality Care</span>
                                                        -->
                                    <!-- Title -->
                                    <h3 class="h3-md steelblue-color mb-5 mt-5">Umbrella Health Care
                                        Systems is the Best Health Care
                                        Website</h3>
                                    <p style="font-weight:400; font-size:1.7rem; text-align:justify;">
                                        Get started now!

                                        Doctors are ready to help you get the care you need,
                                        anywhere and anytime in the United States.


                                        Access to doctors, psychiatrists, psychologists,
                                        therapists and other medical experts,
                                        care is available from 07:00 AM to 08:00 PM. Select and
                                        see your favorite providers again and
                                        again, right from your smartphone, tablet or computer.
                                    </p>

                                    <!-- Button -->
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div class="all-doctors mb-40">
                                                <a href="{{ route('about_us') }}" class="btn theme-color py-3 mt-3"
                                                    style="width: 300px;font-size:22px;">Who We
                                                    Are</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div> <!-- END TEXT BLOCK -->


                            <!-- IMAGE BLOCK -->
                            <div class="col-lg-6">
                                <div class="about-img text-center  fadeInUp" data-wow-delay="0.6s">
                                    <img class="img-fluid" src="asset_frontend/images/image-02.png"
                                        style="height:360px;" alt="about-image">
                                </div>
                            </div>


                        </div> <!-- End row -->
                    </div> <!-- End container -->
                </section> <!-- END ABOUT-6 -->
                <div class="container section-bg mb-5">
                    <div class="row">
                        <div class="col-12 col-md-4 mt-5 mb-5 logo-spacing">
                            <img src="asset_frontend/images/partners/logo.png" alt="Green Imaging" width="50%">
                        </div>
                        <div class="col-12 col-md-4 logo-spacing mt-5 mb-5">
                            <img src="asset_frontend/images/partners/quest.png" alt="Quest Diagnostic">
                        </div>
                        <div class="col-12 col-md-4 logo-spacing mt-5 mb-5">
                            <img src="asset_frontend/images/partners/RXO-Logo.png" alt="RX Outreach" width="50%">
                        </div>
                    </div>
                </div>
                <div class="container mb-5">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-center help_call">For assistance or help please call us on +1 (407) 693-8484
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
