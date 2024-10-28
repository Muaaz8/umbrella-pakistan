@extends('layouts.admin')

@section('content')

<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Umbrella Health Care Systems</h2>
            <small class="text-muted">Welcome to Umbrella Health Care Systems</small>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Mental Condition<small>Fill the form below</small> </h2>
                    </div>
                    <div class="body">
                        {!! Form::model($mentalConditions, ['route' => ['mentalConditions.update',
                        $mentalConditions->id], 'method' => 'patch']) !!}

                        @include('mental_conditions.fields')

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
@section('script')
<script src="{{ asset('asset_admin/plugins/ckeditor/ckeditor.js') }}"></script>
<script>
CKEDITOR.replace('content');
CKEDITOR.editorConfig = function(config) {};
</script>

@endsection