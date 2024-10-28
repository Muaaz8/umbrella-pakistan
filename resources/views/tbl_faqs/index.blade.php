{{-- @extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Tbl Faqs</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" 
           style="margin-top: -10px;margin-bottom: 5px;margin-left: 979px !important;" 
           href="{{ route('faqs.create') }}">Add New</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('tbl_faqs.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

 --}}
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
                        <h2>FAQs 
                        @if(auth()->user()->user_type!='admin')
                            <small>For Lab Test</small> 
                        @endif
                        </h2>
                         <h1 class="pull-right d-flex justify-content-end" style=" margin-top: -40px; ">
                            <a class="btn btn-primary pull-right" 
                            style="color:#fff;margin-bottom: 5px" 
                            href="{{ route('faqs.create') }}">Add New</a>
                         </h1>
                    </div>
                    <div class="body table-responsive">

                    @include('tbl_faqs.table')
                    </div>
                    </div>
                </div>
            </div>

        </div>
</section>
@endsection

<style>

</style>
