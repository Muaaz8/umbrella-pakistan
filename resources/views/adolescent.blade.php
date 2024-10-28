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
                            <li class="breadcrumb-item active" aria-current="page">Adolescent</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">Adolescent</h4>

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
                        <img class="img-fluid" src="asset_frontend/images/adolescent.jpg" alt="info-image">
                    </div>
                </div>


                <!-- INFO TEXT -->
                <div class="col-lg-6">
                    <div class="txt-block pc-30 wow fadeInUp" data-wow-delay="0.4s">

                        <!-- Section ID -->
                        <span class="section-id blue-color">Welcome to Umbrella Health Care Systems</span>

                        <!-- Title -->
                        <h3 class="h3-md steelblue-color">Talk To Our Doctors</h3>

                        <!-- Text -->
                        <p class="mb-2">We evaluate and develop treatment plans for the following common conditions:</p>
                        <ul>
                            <li><i class="fas fa-genderless"></i> Depression</li>
                            <li><i class="fas fa-genderless"></i> Bipolar Disorder</li>
                            <li><i class="fas fa-genderless"></i> Emotional Instability</li>
                            <li><i class="fas fa-genderless"></i> Attention-Deficit/Hyperactivity Disorder</li>
                            <li><i class="fas fa-genderless"></i> Anxiety Disorders</li>
                            <li><i class="fas fa-genderless"></i> Panic Disorder</li>
                            <li><i class="fas fa-genderless"></i> Obsessive-compulsive disorder</li>
                        </ul>
                        <br>
                        <p class="mb-2">We also see adolescents with developmental disorders, such as Autism, PDD, and
                            Asperger's,
                            who have one or more of the following issues:</p>
                        <ul>
                            <li><i class="fas fa-genderless"></i> Behavior Issues</li>
                            <li><i class="fas fa-genderless"></i> HyperactivityAggression</li>
                            <li><i class="fas fa-genderless"></i> Focus Issues</li>
                            <li><i class="fas fa-genderless"></i> Emotional Instability</li>
                            <li><i class="fas fa-genderless"></i> Psychotic symptoms</li>
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