@extends('layouts.new_pakistan_layout')

@section('page_title')
    <title>Terms of Use | Community Healthcare Clinics</title>
@endsection

@section('content')
<!-- BREADCRUMB
			============================================= -->
<div class="w-85 mx-auto mt-2">
    {!!$term_of_use->content!!}
</div>



@endsection