@extends('layouts.admin')

@section('content')
<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Umbrella Health Care Systems</h2>
            <small class="text-muted">Welcome to Umbrella Health Care Systems</small>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2>Mental Condition<small>All Mental Condition</small> </h2>
                        <h1 class="pull-right d-flex justify-content-end" style=" margin-top: -40px; ">
                            <a class="btn btn-primary pull-right" 
                            style="color:#fff;margin-top: -10px;margin-bottom: 5px" 
                            href="{{ route('mentalConditions.create') }}">Add New</a>
                         </h1>
                    </div>
                    <div class="body table-responsive">

                    @include('mental_conditions.table')
                    </div>
                    </div>
                </div>
            </div>

        </div>
</section>
@endsection

