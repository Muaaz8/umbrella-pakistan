@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/allproduct.css')}}">

@section('content')
    @php
    if (isset($_GET['form_type'])) {
        $form_type = $_GET['form_type'];
    }
    @endphp
    <section class="content home">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted">Welcome to Umbrellamd</small>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2><?php
                            if ($form_type === 'panel-test') {
                                echo 'Add Panel Test';
                            } elseif ($form_type === 'lab-test') {
                                echo 'Add Labtest';
                            } elseif ($form_type === 'medicine') {
                                echo 'Add Medicines';
                            }
                            
                            ?><small>Fill the form below</small> </h2>
                        </div>
                        <div class="body">
                            {!! Form::open(['route' => 'allProducts.store', 'files' => true]) !!}
                            <div class="row clearfix">
                                @include('all_products.fields')
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.testSelect2').select2({
                placeholder: 'Select Multiple Test',
                ajax: {
                    url: '/get_products_name/123',
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
                                    id: obj.text,
                                    text: obj.text
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            $('.parent_category_products').select2({
                placeholder: 'Select Categories',
                ajax: {
                    url: '/get_parent_category_names/123',
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
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    {{-- <script>
        CKEDITOR.replace('description');
        CKEDITOR.replace('short_description');
        CKEDITOR.replace('test_details');
    </script> --}}
    <script type="text/javascript">
        CKEDITOR.replace('short_description');
        CKEDITOR.add
    </script>
    <script type="text/javascript">
        CKEDITOR.replace('description');
        CKEDITOR.add
    </script>
    <script type="text/javascript">
        CKEDITOR.replace('test_details');
        CKEDITOR.add
    </script>
@endsection
