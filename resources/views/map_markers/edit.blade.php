@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/allproduct.css')}}">

@section('content')
<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Dashboard</h2>
            <small class="text-muted">Welcome to Umbrellamd</small>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    @include('adminlte-templates::common.errors')
                    <div class="header">
                        <h2>Edit Map Marker <small>Fill the form below</small> </h2>
                    </div>
                    <div class="body">

                        {!! Form::model($mapMarkers, ['route' => ['mapMarkers.update', $mapMarkers->id], 'method' => 'patch', 'files' => true]) !!}
                        <div class="row fieldsBox">
                            @include('map_markers.fields')
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>

    </div>

</section>
<style>
    .fieldsBox .form-group .form-control {
        border: 1px solid #c1c1c1;
    }
</style>
@endsection