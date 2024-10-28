@extends('layouts.admin')

@section('content')
<section class="content home">
    <div class="container-fluid">

        <div class="block-header">
            <h2>Dashboard</h2>
            <small class="text-muted">Umbrellamd</small>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2>FAQ For Test<small>FAQ Details</small> </h2>

                    </div>
                    <div class="row" style="padding-left: 40px">
                        @include('tbl_faqs.show_fields')
                    </div>

                    <div class="row clearfix" style="padding-left: 20px">
                        <div class="col-md-3">
                            <a href="{{ route('faqs.index') }}" class="btn btn-default">Back</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    </div>



</section>

@endsection