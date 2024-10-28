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
                        <h2>Mental Conditions
                        <!-- <small>All recent categories</small>  -->
                        </h2>

                    </div>
                    <div class="row col-12 ml-2" style="padding-left: 20px">
                        @include('mental_conditions.show_fields')
                    </div>

                    <div class="row clearfix" style="padding-left: 20px">
                        <div class="col-md-3">
                            <a href="{{ route('mentalConditions.index') }}" class="btn btn-default">Back</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>

@endsection
@section('script')
<script>
$(document).ready(function(){
    $('#content').append($('#content_text').val());
})
</script>
@endsection