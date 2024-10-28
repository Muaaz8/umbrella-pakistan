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
                            <li class="breadcrumb-item active" aria-current="page">Meet Our Doctors</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">Meet Our Doctors</h4>

                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END BREADCRUMB -->




<!-- DOCTORS-3============================================= -->
<section id="doctors-3" class="bg-lightgrey wide-60 doctors-section division pt-5">
    <div class="container">
        <div class="row">

        @foreach($doctors as $doctor)
            <!-- DOCTOR #1 -->
            <div class="col-md-6 col-lg-4">
                <div class="doctor-2">

                    <!-- Doctor Photo -->
                    <div class="hover-overlay">


                        <img src="{{$doctor->user_image}}" class="img-fluid" alt="" width="117" style="height:117px">


                        <!-- <img class="img-fluid" src="images/{{$doctor->user_image}}" alt="doctor-foto"> -->
                    </div>

                    <!-- Doctor Meta -->
                    <div class="doctor-meta">

                        <h5 class="h5-xs blue-color">Dr. {{$doctor->name.' '.$doctor->last_name}}</h5>
                        @if($doctor->spec=='None')
                        <span>General</span>
                        @else
                        <span>{{$doctor->spec}}</span>
                        @endif
                        <!-- Button -->
                        @auth
                        @php $user_type=auth()->user()->user_type; @endphp
                        @if($user_type=='patient')
                        <a class="btn btn-sm btn-blue blue-hover mt-15" href="{{route('book.appointment',['id'=>$doctor->specialization,'doc'=>$doctor->id])}}">Talk To Doctor</a>
                        @endif
                        @endauth
                        @guest
                        <a class="btn btn-sm btn-blue blue-hover mt-15" href="{{url('/patient_register')}}" title="">Talk To Doctor</a>
                        @endguest

                    </div>

                </div>
            </div> <!-- END DOCTOR #1 -->
            @endforeach

        </div> <!-- End row -->
        <div class="row d-flex justify-content-center">
            <div class="paginateCounter">
                {{$doctors->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div> <!-- End container -->
</section> <!-- END DOCTORS-3 -->

@endsection
