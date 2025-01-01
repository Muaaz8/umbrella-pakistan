@extends('layouts.new_pakistan_layout')
@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection


@section('page_title')
    <title>Community Healthcare Clinics</title>
@endsection

@section('top_import_file')
<style>
    .card_style{
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    padding: 35px 0px;
    text-align: center;
    border-radius: 10px;
    margin: auto;
}
.card_style h6{
    font-weight: 600;
}
.card_style img{
    width: 70px;
    margin-bottom: 10px;
}
.view-pat-btn{
    border:none;
    background-color: #08295a;
    color: white;
    padding: 5px 15px ;
    border-radius: 30px;
}
</style>
@endsection


@section('bottom_import_file')
@endsection

@section('content')

    <section>
        <div class="card_style col-md-6 col-11 my-5">
            <img src="{{ asset('/assets/images/check-mark.png') }}" alt="">
            <h5 class="fw-bold my-2">Email Verification</h5>
            <p>Your email is verified successfully</p>
            <button class="view-pat-btn mt-3" onclick="location.href='{{ route('home') }}'">Go to dashboard</button>
        </div>
    </section>


@endsection
