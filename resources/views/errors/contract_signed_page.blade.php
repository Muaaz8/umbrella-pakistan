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
.view-pat-btn{
    border:none;
    background-color: #08295a;
    color: white;
    padding: 5px 15px ;
    border-radius: 30px;
}
.card_style img{
    width: 70px;
    margin-bottom: 10px;
}

</style>
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <section>
        <div class="card_style col-md-6 col-11 my-5">
            <img src="{{ asset('./assets/images/contracts.png') }}" alt="">
            <h5 class="fw-bold mb-2">Community Healthcare Clinics</h5>
            <h6>You have Signed the Contract with Community Healthcare Clinics</h6>
            <p class="my-2">You can now have sessions with your patients</p>
            <button class="view-pat-btn" onclick="location.href='{{ route('home') }}'">View Online Patients</button>
        </div>
    </section>
@endsection
