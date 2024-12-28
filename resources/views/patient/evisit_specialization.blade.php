@extends('layouts.admin')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Specializations</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2> Choose Your Desire Specialize To Evisit </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            @forelse($spe as $specialization)


                                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                    <div class="card font-20 p-15 text-center text-white" style="background-color: #406eb2!important;   border: 1px solid ; border-radius: 16px">
                                        @if($specialization->follow_up_price!=null && $specialization->initial_price!=null)
                                            <p>Initial Visit Price Rs. {{ $specialization->initial_price }}</p>
                                            <p>FollowUp Visit Price Rs. {{ $specialization->follow_up_price }}</p>
                                        @else
                                            <p>E-visit Price Rs. {{ $specialization->initial_price }}</p>
                                        @endif

                                        <a href="{{ route('online_doctors',['id'=>$specialization->id]) }}"><button onclick="" class="btn bg-success text-white btn-lg btn-block waves-effect" type="button">{{ $specialization->name }}</button></a>

                                    </div>
                                </div>
                                @empty
                                <p class="mx-5">No Specialization Available.</p>

                                @endforelse



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
