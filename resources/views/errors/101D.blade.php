@extends('layouts.dashboard_doctor')
@section('meta_tags')
@endsection
@section('top_import_file')
@endsection
@section('page_title')
    <title>CHCC - Umbrella Health Care Systems</title>
@endsection
@section('bottom_import_file')
@endsection
@section('content')

<section>
    <div class="container-fluid">
        <div class="row my-5">
            <div class="text-center">
                <img src="{{ asset('assets/images/404-umbrella.jpg') }}" alt="" class="w-50">
                <h5>Coming soon in your state.</h5>
                <h3 class="text-uppercase">This service is currently not available in your state</h3>
            </div>
        </div>
    </div>
</section>

@endsection
