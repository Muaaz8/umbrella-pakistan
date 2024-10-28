@extends('layouts.frontend')

@section('content')
<!-- BREADCRUMB============================================= -->
<div id="breadcrumb" class="division">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class=" breadcrumb-holder">

                    <!-- Breadcrumb Nav -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Membership Plans</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">Membership Plans</h4>

                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END BREADCRUMB -->


<!-- PRICING-1
			============================================= -->
<section id="pricing-1" class="wide-60-pricing pricing-section division">
    <div class="container">


        <!-- SECTION TITLE -->
        <div class="row">
            <div class="col-lg-10 offset-lg-1 section-title">

                <!-- Title 	-->
                <h3 class="h3-md steelblue-color">Best Quality Medical Treatment</h3>

                <!-- Text -->
                <p style="font-size: 18px;padding:0px"> Direct subscription model is designed for patient convenience at a very affordable monthly subscription price you will be able to visit  your primary care doctors for any number of times it is important to know subscription model only applys for primary healthcare services.
                <!-- Concierge healthcare model are also available. -->
                </p>

            </div>
        </div>


        <!-- PRICING TABLES -->
        <div class="row pricing-row">


            <!-- <div class="col-lg-4" id="pack_one">
                <div class="pricing-table icon-xl">

                    <span class="flaticon-072-hospital-5 blue-color"></span>

                    <h5 class="h5-lg">Silver Plan</h5>

                    <div class="pricing-plan">
                        <sup class="steelblue-color">$</sup>
                        <span class="price steelblue-color">600</span>
                        <p class="p-md">6 Months</p>
                    </div>

                    <ul class="features">
                        <li>Unlimited E-visits</li>
                        <li>Medical Specialties</li>
                        <li>Medical Consultation</li>
                        <li>Investigations</li>
                        <li>Medical Treatments</li>
                    </ul>

                    <a href="{{url('patient_register')}}" class="btn btn-md btn-tra-black blue-hover">Select Plan</a>

                </div>
            </div>  -->
            <!-- END PRICING TABLE #1 -->


            <!-- PRICING TABLE #2 -->
            <div class="offset-4 col-lg-4" id="pack_two">
                <div class="pricing-table icon-xl">

                    <!-- Icon -->
                    <span class="flaticon-072-hospital-5 blue-color"></span>

                    <!-- Title -->
                    <h5 class="h5-lg">Membership Plan</h5>

                    <!-- Plan Price  -->
                    <div class="pricing-plan">
                        <sup class="steelblue-color">$</sup>
                        <span class="price steelblue-color">60</span>
                        <p class="p-md">Monthly</p>
                    </div>

                    <!-- Pricing Plan Features  -->
                    <ul class="features">
                        <li>Unlimited E-visits</li>
                        <li>Medical Specialties</li>
                        <li>Medical Consultation</li>
                        <li>Investigations</li>
                        <li>Medical Treatments</li>
                    </ul>

                    <!-- Pricing Table Button  -->
                    <a href="{{url('patient_register')}}" class="btn btn-md btn-blue blue-hover">Select Plan</a>

                </div>
            </div> <!-- END PRICING TABLE #1 -->


            <!-- PRICING TABLE #3 -->
            <!-- <div class="col-lg-4" id="pack_three">
                <div class="pricing-table icon-xl">

                    <span class="flaticon-072-hospital-5 blue-color"></span>

                    <h5 class="h5-lg">Premium Plan</h5>

                    <div class="pricing-plan">
                        <sup class="steelblue-color">$</sup>
                        <span class="price steelblue-color">1500</span>
                        <p class="p-md">18 Months</p>
                    </div>

                    <ul class="features">
                        <li>Unlimited E-visits</li>
                        <li>Medical Specialties</li>
                        <li>Medical Consultation</li>
                        <li>Investigations</li>
                        <li>Medical Treatments</li>
                    </ul>

                    <a href="{{url('patient_register')}}" class="btn btn-md btn-tra-black blue-hover">Select Plan</a>

                </div>
            </div>  -->
            <!-- END PRICING TABLE #3 -->


        </div> <!-- END PRICING TABLES -->


    </div> <!-- End container -->
</section> <!-- END PRICING-1 -->

<!-- BANNER-5============================================= -->
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
