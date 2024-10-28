@extends('layouts.admin')

@section('css')
<style>
.bio {
    padding: 3%;
    background: linear-gradient(to bottom, #08295a, #5a80a6);
    box-shadow: rgb(0 0 0 / 25%) 0px 14px 28px, rgb(0 0 0 / 22%) 0px 10px 10px !important;
    border-radius: 20px !important;
    color: white;
}
.profile_name{
    padding: 5%;
    margin: 5%;
    font-size: 22px;
    display: block;
    text-align: center;
}
.profile_picture{
    display: block;
    width:100%;
    height:200px;
    padding:2%;
    border-radius: 1rem !important;
}
.nav-tabs .nav-link {
    text-align: center;
}

.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    background: #08295a !important;
    border-color: #08295a !important;
    color: #fff !important;
}
.nav-tabs .nav-item {
    width: 33%;
}
</style>
@endsection
@section('content')

<script>
setTimeout(function() {
    $('.messageDiv').fadeOut('fast');
}, 5000);
</script>

<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            @include('flash::message')
            <div class="row d-flex justify-content-between">
                <div class="col-6 col-lg-6  col-md-6 col-xs-6">
                    <h6 class="d-inline-block mt-3">Profile</h6>
                </div>
                <div class="col-6  col-lg-6  col-md-6  col-xs-6"
                    style="padding-left: 20px; text-align:right !important">
                    <a href="{{ route('home') }}" class="btn callbtn">Back</a>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-3 col-md-12 col-sm-12">
                <div class=" card col-lg-12 col-md-4 col-sm-4 offset-lg-0 offset-md-4 offset-sm-4 patient-profile">

                    <img src="{{ $doctor->user_image }}" class="img-fluid rounded profile_picture" alt="">
                    <span class="profile_name">{{ $doctor->name . ' ' . $doctor->last_name }}</span>
                </div>
                <div class="card ">
                    <div class="header d-flex justify-content-between">
                        <div class="">
                            <h2>About Me</h2>
                        </div>
                        <div>
                            <a href="{{ route('edit_doctor_profile') }}" role="button"
                                style="color:green;font-size:28px"><i class="fas fa-edit"></i></a>

                        </div>
                    </div>
                    <div class="body">

                        <strong class="mt-2">Specialization</strong>
                        <p>{{ $doctor->spec->name }}
                        </p>
                        <strong>D.O.B</strong>
                        <?php

                            $date = str_replace('-', '/', $doctor->date_of_birth);
                            $newd_o_b = date('m/d/Y', strtotime($date));

                            ?>
                        <p>{{ $newd_o_b }}</p>
                        <strong>Email ID</strong>
                        <p>{{ $doctor->email }}</p>
                        <strong>NPI Number</strong>
                        <p>{{ $doctor->nip_number }}</p>
                        <strong>Upin</strong>
                        <p>{{ $doctor->upin }}</p>
                        <strong>Phone</strong>
                        <p>{{ $doctor->phone_number }}</p>
                        <hr>
                        <strong>Country</strong>
                        <p>{{ $doctor->country->name }}</p>
                        <strong>State</strong>
                        <p>{{ $doctor->state->name }}</p>
                        <strong>City</strong>
                        <p>{{ $doctor->city->name }}</p>
                        <strong>Address</strong>
                        <address>{{ $doctor->office_address }}</address>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12">
                <div class="card" style="height:1100px">
                    <div class="body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#report"
                                    style="">Bio</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#certificate"
                                    style="">Certificates</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#timeline"
                                    style="">Activities</a></li>
                        </ul>


                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="report">
                                <div class="wrap-reset">
                                    <div class="mypost-list">
                                        <div class="post-box">
                                            @if ($doctor->bio == null)
                                            <a href="{{ route('edit_doctor_profile') }}">
                                                <button class="btn btn-raised g-bg-cyan">Add Bio</button>
                                            </a>
                                            @else
                                            <p class="bio card" style="word-break: break-word;">{{ $doctor->bio }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h4 style="text-align:center">My Patients</h4>
                                <div class="col-12 row">
                                    @forelse($doctor->all_patients as $patient)
                                    <div class="col-3 text-center mb-1">
                                            <a href="{{ url('/patient_record/' . $patient->id) }}" class="p-profile-pix">
                                                <img style="height: 140px; width: 140px;" src="{{ $patient->user_image }}" alt="user" class="img-thumbnail img-fluid">
                                                <h4>{{ ucwords($patient->name . ' ' . $patient->last_name) }} </h4>
                                            </a>
                                    </div>
                                    @empty
                                    <div class="col-md-12">No patients</div>
                                    @endforelse
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="certificate">
                                <ul class="list-group">
                                    @forelse($doctor->certificates as $cert)
                                    <li class="list-group-item ">
                                        <img src="asset_admin/images/view-icon.png" alt="View icon" height=50 width=50>
                                        <a href="{{ $cert->certificate_file }}" target="blank" class="ml-3">View
                                            File</a>
                                    </li>
                                    <!-- <div class="row">
                                    <div class="col-md-12">
                                        <img src="{{$cert->certificate_file}}" alt="View Document" height=100 width=100>
                                    </div>
                                </div> -->
                                    @empty
                                    <p class="text-center"> No documents added </p>
                                    @endforelse
                                </ul>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="timeline">
                                <div class="row d-flex justify-content-center mt-70 mb-70">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"
                                                style="overflow-y: scroll;height: 100vh;overflow-x: hidden;">
                                                <h6 class="card-title text-center">Easily follow up with your last
                                                    activities
                                                </h6>
                                                <div
                                                    class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                    @forelse ($doctor->activities as $item)
                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                        <div>
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i
                                                                    class="badge badge-dot badge-dot-xl {{ $item->color }}">
                                                                </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title">{{ $item->heading }}
                                                                </h4>
                                                                <p>{{ $item->activity }} @ <a href="javascript:void(0);"
                                                                        data-abc="true">
                                                                        {{ $item->created_at}}
                                                                    </a>
                                                                </p>
                                                                <span class="vertical-timeline-element-date"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @empty
                                                </div>
                                                <h4>No Activities Found.</h4>
                                                @endforelse
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

@endsection
