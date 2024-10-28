@extends('layouts.admin')

@section('content')
<section class="content patients">
    <div class="container-fluid">
        <div class="block-header">
            <h2>{{ucwords($pat_name)}}</h2>
            <ul class="breadcrumb mb-0 pb-0">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin_patients')}}">All Patients</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0);">{{ucwords($pat_name)}}</a></li>
            </ul>

        </div>
        <div class="row clearfix">

                <div class="col-lg-4 col-md-4 col-sm-6 border border-primary rounded">

                        <div class="info-box-4 hover-zoom-effect " style="height: 110px">
                            <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                            <div class="content">
                                <div class="text">Lab Total </div>
                                <div class="number">$ {{ number_format($lab,2) }}</div>
                                <a href="{{ route('order.details',['type'=>'lab','id'=>$patient->id]) }}">
                                    <div class="text">View Details</div>
                                </a>

                            </div>
                        </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="info-box-4 hover-zoom-effect" style="height: 110px">
                            <div class="icon"> <i class="zmdi zmdi-account col-green"></i> </div>
                            <div class="content">
                                <div class="text">Pharmacy Total</div>
                                <div class="number">$ {{ number_format($medicien,2) }}</div>
                                <a href="{{ route('order.details',['type'=>'medicine','id'=>$patient->id]) }}">
                                    <div class="text">View Details</div>
                                </a>
                            </div>
                        </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="info-box-4 hover-zoom-effect" style="height: 110px">
                            <div class="icon"> <i class="zmdi zmdi-bug col-blush"></i> </div>
                            <div class="content">
                                <div class="text">Imging Total</div>
                                <div class="number">$ {{ number_format($imaging,2) }}</div>
                                <a href="{{ route('order.details',['type'=>'imaging','id'=>$patient->id]) }}">
                                    <div class="text">View Details</div>
                                </a>
                            </div>
                    </div>
                </div>
        </div>
    </div>
</section>
@endsection
