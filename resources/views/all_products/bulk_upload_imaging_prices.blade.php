@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/allproduct.css')}}">

@section('content')

    <section class="content home">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted">Welcome to Umbrellamd</small>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    @include('flash::message')
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2>Upload Bulk Imaging Prices</small> </h2>
                            {{-- <h1 class="pull-right" style=" margin-top: -40px; ">
                            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('allProducts.create') }}">Add New</a>
                        </h1> --}}
                        </div>
                        <div class="body">
                            {!! Form::open(['route' => 'bulkUploadImagingPricesStore', 'files' => true]) !!}
                            <div class="row clearfix">
                                <!-- Service Name -->
                                <div class="form-group col-sm-4">
                                    {!! Form::label('name', 'Service Name:') !!}
                                    <select class="form-control imagingServicesSelect product-input" name="name" id="name" required>
                                    </select>
                                </div>

                                <div class="form-group col-sm-4">
                                    <label for="city">Location</label>
                                    <select class="form-control imagingLocationSelect product-input" multiple="multiple" name="city[]"
                                        id="city" required>
                                    </select>
                                </div>

                                <div class="form-group col-sm-4">
                                    {!! Form::label('price', 'Price:') !!}
                                    <input style="padding-left: 10px;" class="form-control product-input" name="price"
                                        type="text" id="price" required>
                                </div>

                                <div class="form-group d-flex justify-content-center col-sm-12">
                                    {!! Form::submit('Save', ['class' => 'btn save-btn']) !!}

                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.imagingServicesSelect').select2({
                placeholder: 'Select Services',
                ajax: {
                    url: '/getImagingServicesSelect',
                    type: "GET",
                    delay: 250,
                    quietMillis: 100,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            term: params.term,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.text
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            $('.imagingLocationSelect').select2({
                placeholder: 'Select Location',
                ajax: {
                    url: '/getImagingLocationSelect',
                    type: "GET",
                    delay: 250,
                    quietMillis: 100,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            term: params.term,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.text
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

        });
    </script>
@endsection
