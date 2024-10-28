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
                            <!-- <li class="breadcrumb-item">Substance Abuse</li> -->
                            <li class="breadcrumb-item active" aria-current="page">Heath Issues</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">Heath Issues</h4>

                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END BREADCRUMB -->
<section id="info-4" class="wide-100 info-section division pt-4">
    <div class="container">


        <!-- TOP ROW -->
        <div class="top-row mb-80">
            <div class="row d-flex align-items-center">
                @foreach($mentalConditions as $cond)
                <div class="sbox-7 icon-xs wow ml-5 col-8" style="padding:10px 2px;margin:5px 10px" data-wow-delay="0.4s">
                    <a href="{{route('condition', [$cond->id])}}">
                        <!-- Icon -->
                        <!-- <span class="flaticon-137-doctor blue-color"></span> -->
                        <!-- Text -->
                        <div class="sbox-7-txt col-12">
                            <!-- Title -->
                            <h5 class="h5-sm steelblue-color" style="display:block"><i class="fas fa-caret-right"></i>  {{$cond->name}}</h5>
                            <!-- Text -->
                        </div>
                    </a>
                </div>
                @endforeach

            </div> <!-- End row -->
        </div> <!-- END TOP ROW -->
    </div>
</section>


@endsection