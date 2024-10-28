@extends('layouts.frontend')

@section('content')
    @include('site.welcome_sections.slider')

    <section id="tabs-1" class="tabs-section division" style="margin-top:20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @include('site.welcome_sections.steps')
                    @include('site.welcome_sections.tabs_nav')

                    <div class="tab-content" id="pills-tabContent">
                        @include('site.welcome_sections.e-visit')
                        @include('site.welcome_sections.pharmacy')
                        @include('site.welcome_sections.labs')
                        @include('site.welcome_sections.imaging')
                        @include('site.welcome_sections.dermotology')
                        @include('site.welcome_sections.cardiology')

                        <!-- <section id="video-1" class="wide-10 video-section division">
                                                                                                        <div class="container">
                                                                                                            <div class="row d-flex align-items-center">
                                                                                                                <div class="col-lg-6">
                                                                                                                    <div class="txt-block pc-30 mb-40 wow fadeInUp" data-wow-delay="0.4s">
                                                                                                                        <div class="box-list">
                                                                                                                            <p class="sub_heading">Pharmacy</p>
                                                                                                                            <p class="sub_heading_desc">Umbrella Health Care Systems pharmacy is
                                                                                                                                competitively priced
                                                                                                                                retail medical and general store, our online professional
                                                                                                                                services are best available in the market place, we ship all
                                                                                                                                your orders same day.</p>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="col-lg-6">
                                                                                                                    <div class="video-preview mb-40 text-center wow fadeInUp"
                                                                                                                        data-wow-delay="0.6s">
                                                                                                                        <a class="video-popup1"
                                                                                                                            href="https://www.youtube.com/embed/SZEflIVnhH8">
                                                                                                                            <div class="video-btn play-icon-blue">
                                                                                                                                <div class="video-block-wrapper">
                                                                                                                                    <i class="fas fa-play"></i>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <img class="img-fluid" src="asset_frontend/images/pharmacy.jpg"
                                                                                                                                alt="video-photo" />
                                                                                                                        </a>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </section> -->

                        <!-- <section id="video-1" class="wide-10 video-section division">
                                                                                                        <div class="container">
                                                                                                            <div class="row d-flex align-items-center">
                                                                                                                <div class="col-lg-6">
                                                                                                                    <div class="txt-block pc-30 mb-40 wow fadeInUp" data-wow-delay="0.4s">
                                                                                                                        <div class="box-list">
                                                                                                                            <span class="sub_heading_imaging">Best Quality
                                                                                                                                Imaging Services</span>
                                                                                                                            <p class="sub_heading_desc mt-2">We provide full imaging centres
                                                                                                                                across
                                                                                                                                the
                                                                                                                                country. Today more
                                                                                                                                then
                                                                                                                                ever patients and Doctors are choosing imaging with our high
                                                                                                                                quality latest equipments to provide an accurate diagnostics.
                                                                                                                            </p>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="col-lg-6">
                                                                                                                    <div class="video-preview mb-40 text-center wow fadeInUp"
                                                                                                                        data-wow-delay="0.6s">
                                                                                                                        <a class="video-popup1"
                                                                                                                            href="https://www.youtube.com/embed/SZEflIVnhH8">
                                                                                                                            <div class="video-btn play-icon-blue">
                                                                                                                                <div class="video-block-wrapper">
                                                                                                                                    <i class="fas fa-play"></i>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <img class="img-fluid" src="asset_frontend/images/video-1.png"
                                                                                                                                alt="video-photo" />
                                                                                                                        </a>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </section> -->
                        <!-- END DOCTORS-3 -->

                        <!-- <div class="col-lg-3 col-6">
                    <div class="pricing-table icon-xl" style="border-radius:5px !important;border 1px solid black;">
                        <i class="fa fa-vials" style="color:#004861;;font-size:70px"></i>
                <h5 class="substance-color box-title mb-2" style=" height:40px;color:#004861;">Children</h5>
                <a href="{{ url('/substance_abuse/children') }}" class="ui theme-color button py-3" style="">View
                    Detail</a>
                </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="pricing-table icon-xl" style="border-radius:5px !important;border 1px solid black;">
                        <i class="fa fa-vials" style="color:#004861;;font-size:70px"></i>
                        <h5 class="substance-color box-title mb-2" style=" height:40px;color:#004861;">
                            Adolescent</h5>
                        <a href="{{ url('/substance_abuse/adolescent') }}" class="ui theme-color button py-3" style="">View
                            Detail</a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="pricing-table icon-xl" style="border-radius:5px !important;border 1px solid black;">
                        <i class="fa fa-vials" style="color:#004861;;font-size:70px"></i>
                        <h5 class="substance-color box-title mb-2" style=" height:40px;color:#004861;">
                            Women</h5>
                        <a href="{{ url('/substance_abuse/women') }}" class="ui theme-color button py-3" style="">View
                            Detail</a>
                    </div>
                </div>
                 <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-lg-12 text-center">
                                                                <div class="all-pricing-btn mb-40 align-items-right">
                                    <a href="{{ url('/mental_conditions') }}" class="ui theme-color button py-3" style="">Read
                                        About Conditions<i class="far fa-angle-right white-color"></i></a>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    </div> <!-- END TAB-4 CONTENT -->

                </div>
            </div> <!-- End row -->
        </div> <!-- End container -->
    </section>
    <!-- SERVICES-7
                   ============================================= -->
@endsection
@section('script')
    <script>
        $('#tab1-list').click(function() {
            $(this).css('background-color', 'white');
            $(this).css('color', 'red');

        });
    </script>
@endsection
