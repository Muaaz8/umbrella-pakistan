<div class="tab-pane fade" id="tab-4" role="tabpanel" aria-labelledby="tab4-list">
    <div class="container">
        <div class="row d-flex align-items-center">
            <!-- <section id="video-1" class=" video-section division">
                <div class="container">
                    <div class="row d-flex align-items-center">
                        <div class="col-lg-6">
                            <div class="txt-block pc-30 mb-40 wow fadeInUp" data-wow-delay="0.4s">
                                <div class="box-list">
                                    <span class="sub_heading_substance">Substance
                                        Abuse Treatment</span>
                                    <p class="sub_heading_desc">Nemo ipsam egestas volute turpis dolores
                                        ut aliquam quaerat
                                        sodales sapien undo pretium
                                        purus feugiat dolor impedit
                                    </p>
                                </div>
                            </div>
                        </div>
                        <img class="img-fluid" src="asset_frontend/images/substance-abuse.jpeg" alt="video-photo" />
                        </a>
                    </div>
                </div>
        </div>
    </div>
    </section> -->
            <section id="pricing-1" class=" pricing-section division mt-4" style="width:100%">
                <div class="container section-bg mb-5">
                    {{--<div class="row d-flex align-items-center">
                        <div class="col-lg-6">
                            <div class="txt-block pc-30 mb-40 fadeInUp" data-wow-delay="0.4s">
                                <div class="box-list">
                                    <!-- <h3 class="h3-md align-items-center"
                                                                                        style="color:#004861;margin-top: 20px;width:100%" align="center">E-Visit
                                                                                    </h3> -->

                                     <p class="sub_heading h3-md align-items-center" style="color: #004861 !important;">
                                        Cardiology</p>
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
                                    <a href="{{ route('online_doctors') }}"><button class="btn mt-2 col-12"
                        style="margin: 0px 0px 20px 0px; padding:10px 50px;font-size:20px;background-color:#08295A !important;color:white !important">
                        Talk To Cardiologist</button></a>
                    @elseif($user_type == 'doctor')
                    <a href="{{ route('doc_waiting_room') }}"><button class="btn mt-2 col-12"
                            style="padding:10px 50px;font-size:20px;background-color:#08295A !important;color:white !important">
                            Online Patients</button></a>
                    @else
                    <a href="{{ url('/allProducts') }}"><button class="btn mt-2 col-12"
                            style="margin: 0px 0px 20px 0px;padding:10px 50px;font-size:20px;background-color:#08295A !important;color:white !important">
                            Talk To Cardiologist</button></a>
                    @endif
                    @endauth
                    @guest
                    <a href="{{ url('/patient_register') }}"><button class="btn mt-2 col-12"
                            style="margin: 0px 0px 20px 0px; padding:10px 50px;font-size:20px;background-color:#08295A !important;color:white !important">
                            Talk To Cardiologist</button></a>
                    <!-- <a href="{{ url('register?type=doctor') }}"></a><button class="btn theme-color mt-2 col-10 ml-5"
                                                                                                                                                style="padding:10px 50px;font-size:20px">Doctors</button> -->
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
                <img class="img-fluid mt-5" src="asset_frontend/images/gallery/image-1.jpg" alt="video-photo" />
            </a>
        </div>
    </div>
</div>
</div>--}}
{{-- substance abuses --}}
<div class="container section-bg" align="center" style="">
    <div class="row">
        <div class="col-md-12 pt-5">
            <h2 class="text-center">Umbrella Health Care Systems - Substance Abuse</h2>
            <p class="text-center lead"><strong>Umbrella Health Care Systems
                    provide best quality psychiatric services and consultations to all age
                    groups.We are a staff of
                    professionals committed to helping patients through all stages of their
                    lives. We see children,
                    adolescent, general adults, and older adults. Explore our site to learn
                    about our services,
                    useful resources on various health topics, our contact information, and how
                    to prepare for your
                    first visit.</strong>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-5 mb-5">
            <div class="categoryBar text-center">
                {{--<div class="category_item">
                        <a href="{{ route('imaging') }}">
                <button class="labsbutton">
                    <img src="/uploads/default-imaging.png" />
                </button>
                </a>
                <p class="text-center">
                    <a href="{{ route('imaging') }}" class="labs-service">All </a>
                </p>
            </div>--}}
            @foreach ($data['substance_products'] as $key => $item)
            <div class="category_item">
                <a href="{{ url('substance-abuse') }}/{{ $item->slug }}" class="labs-service">
                    <button class="labsbutton">
                        <img src="/asset_frontend/images/{{ $item->featured_image }}" />
                    </button>
                </a>
                <p class="text-center">
                    <a href="{{ url('substance-abuse') }}/{{ $item->slug }}" class="labs-service">{{ $item->name }}
                    </a>
                </p>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- <div class="row pricing-row" style="margin-right:0px;">
        <div class="col-lg-3 col-6">
            <div class="pricing-table icon-xl" style="border-radius:5px !important;border 1px solid black;">
                <i class="fa fa-vials" style="color:#004861;font-size:70px"></i>
                <h5 class="box-title mb-2" style=" height:40px;color:#004861;">General
                    Adults </h5>
                <a href="{{ url('/substance-abuse/general-adults') }}" class="ui theme-color button py-3" style="">View
                    Detail</a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="pricing-table icon-xl" style="border-radius:5px !important;border 1px solid black;">
                <i class="fa fa-vials" style="color:#004861;;font-size:70px"></i>
                <h5 class="substance-color box-title mb-2" style=" height:40px;color:#004861;">Children
                </h5>
                <a href="{{ url('/substance-abuse/children') }}" class="ui theme-color button py-3" style="">View
                    Detail</a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="pricing-table icon-xl" style="border-radius:5px !important;border 1px solid black;">
                <i class="fa fa-vials" style="color:#004861;;font-size:70px"></i>
                <h5 class="substance-color box-title mb-2" style=" height:40px;color:#004861;">
                    Adolescent</h5>
                <a href="{{ url('/substance-abuse/adolescent') }}" class="ui theme-color button py-3" style="">View
                    Detail</a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="pricing-table icon-xl" style="border-radius:5px !important;border 1px solid black;">
                <i class="fa fa-vials" style="color:#004861;;font-size:70px"></i>
                <h5 class="substance-color box-title mb-2" style=" height:40px;color:#004861;">
                    Women</h5>
                <a href="{{ url('/substance-abuse/women') }}" class="ui theme-color button py-3" style="">View
                    Detail</a>
            </div>
        </div>
    </div> -->
<!-- END PRICING TABLES -->
</div> <!-- End container -->
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="all-pricing-btn mb-40 align-items-right">
                <!-- Price Notice -->
                <a href="{{ url('/mental_conditions') }}" class="ui theme-color button py-3" style="">Read
                    About Heath Issues<i class="far fa-angle-right white-color"></i></a>
            </div>
        </div>
    </div>
</div>

</section>
</div>
</div>
</div>