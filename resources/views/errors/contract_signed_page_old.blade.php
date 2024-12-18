@extends('layouts.new_pakistan_layout')
@section('content')
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">

        </div>
        <div class="card">
            <div class="body">
                <!-- <h4>Inactive User</h4> -->

                <div class="col-md-12 col-sm-12 text-center" style="margin:10% 1%">
                    <h2 >Community Health Care Clinics</h2>
                    <h4 >You Signed Contract with Community Healthcare Clinics.</h4>
                    @auth
                    <p  style="font-size: 15px;">You can now have sessions with your patients </p>
                    <a href="{{ route('doctor_dashboard') }}" class="btn btn-primary callbtn">View Online Patients</a>
                    @endauth
                    @guest
                    <p  style="font-size: 15px;">You can now log into your account and have sessions with your patients</p>
                    @endguest
                </div>
            </div>
        </div>
</section>
@endsection
