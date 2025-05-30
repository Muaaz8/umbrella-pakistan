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
                            <li class="breadcrumb-item">Substance Abuse</li>
                            <li class="breadcrumb-item active" aria-current="page">Women</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">Women</h4>

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
                        <img class="img-fluid" src="asset_frontend/images/women_abuse.png" alt="info-image">
                    </div>
                </div>


                <!-- INFO TEXT -->
                <div class="col-lg-6">
                    <div class="txt-block pc-30 wow fadeInUp" data-wow-delay="0.4s">

                        <!-- Section ID -->
                        <span class="section-id blue-color">Welcome to Umbrella Health Care Systems</span>

                        <!-- Title -->
                        <h3 class="h3-md steelblue-color">Talk To Our Doctors</h3>
                        <p class="mb-2">We evaluate and develop treatment plans for the following common conditions:</p>
                        <!-- Text -->
                        <ul>
                            <li><i class="fas fa-genderless"></i> Emotional Dysregulation associated with life-cycle
                                changes</li>
                            <li><i class="fas fa-genderless"></i> Depression and other mood disorders</li>
                            <li><i class="fas fa-genderless"></i> Anxiety and Panic Disorders</li>
                            <li><i class="fas fa-genderless"></i> Post-traumatic stress disorder</li>
                            <li><i class="fas fa-genderless"></i> Sleep issues</li>
                            <li><i class="fas fa-genderless"></i> Attention and focus issues</li>
                        </ul>
                        <br>
                        @auth
                        @php $user_type=auth()->user()->user_type; @endphp
                        @if($user_type=='patient')
                        <a href="{{route('online_doctors')}}"><button class="btn mt-2 col-10 ml-5"
                                style="padding:10px 50px;font-size:20px;background-color:#ecdf41 !important;color:black !important">
                                Talk To Doctor</button></a>
                        @elseif($user_type=='doctor')
                        <a href="{{route('doc_waiting_room')}}"><button class="btn mt-2 col-10 ml-5"
                                style="padding:10px 50px;font-size:20px;background-color:#ecdf41 !important;color:black !important">
                                Online Patients</button></a>
                        @else
                        <a href="/allProducts"><button class="btn mt-2 col-10 ml-5"
                                style="padding:10px 50px;font-size:20px;background-color:#ecdf41 !important;color:black !important">
                                Talk To Doctor</button></a>

                        @endif
                        @endauth
                        @guest
                        <a href="{{url('register?type=patient')}}"><button class="btn mt-2 col-10 ml-5"
                                style="padding:10px 50px;font-size:20px;background-color:#ecdf41 !important;color:black !important">
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