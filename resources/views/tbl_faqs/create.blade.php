@extends('layouts.admin')

@section('content')
<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Dashboard</h2>
            <small class="text-muted">Welcome to Umbrellamd </small>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    @include('adminlte-templates::common.errors')
                    <div class="header">
                        <h2>Create FAQ <small>Fill the form below</small> </h2>
                    </div>
                    <div class="body">

                        <div class="row fieldsBox">
                            {!! Form::open(['route' => 'faqs.store']) !!}

                            @include('tbl_faqs.fields')

                            {!! Form::close() !!}
                        </div>

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

@section('script')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {

        $('.testFAQ').select2({
            placeholder: 'Select Lab Tests',
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

<script src='{{ asset('asset_admin/plugins/tinymce/tinymce.min.js') }}'></script>
<script>
    tinymce.init({
        selector: '#answer'
    });
</script>

@endsection