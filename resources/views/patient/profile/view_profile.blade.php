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

.profile_name {
    padding: 5%;
    margin: 5%;
    font-size: 22px;
    display: block;
    text-align: center;
}

.profile_picture {
    display: block;
    width: 100%;
    height: 200px;
    padding: 2%;
    border-radius: 1rem !important;
}

.nav-tabs .nav-link {
    text-align: center;
}

.nav-tabs .nav-item.show .nav-link,
.nav-tabs .nav-link.active {
    background: #08295a !important;
    border-color: #08295a !important;
    color: #fff !important;
}

.nav-tabs .nav-item {
    width: 50%;
}

.list-group-item {
    border-radius: 5px;
}
</style>
@endsection
@section('content')


<script>
setTimeout(function() {
    $('.messageDiv').fadeOut('fast');
}, 2000);
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
                    <a href="{{ route('home') }}" class="btn callbtn py-3">Back</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12">
                <div class=" card col-lg-12 col-md-4 col-sm-4 offset-lg-0 offset-md-4 offset-sm-4 patient-profile">

                    <img src="{{ $patient->user_image }}" class="img-fluid rounded profile_picture" alt="">

                    <span class="profile_name">{{ $patient->name . ' ' . $patient->last_name }}</span>
                </div>
                <div class="card">
                    <div class="header d-flex justify-content-between">
                        <div class="">
                            <h2>About Me</h2>
                        </div>
                        <div class="">
                            <a href="{{ route('edit_patient_profile') }}" role="button"
                                style=" color:green;font-size:28px"><i class="fas fa-edit"></i></a>
                        </div>
                    </div>
                    <div class="body">
                        <strong>Date of Birth</strong>
                        <?php

                            $date = str_replace('-', '/', $patient->date_of_birth);
                            $newd_o_b = date('m/d/Y', strtotime($date));
                            //    dd($newd_o_b);
                            ?>
                        <p>{{ $newd_o_b }}</p>
                        <strong>Email ID</strong>
                        <p>{{ $patient->email }}</p>
                        <strong>Phone</strong>
                        <p>{{ $patient->phone_number }}</p>
                        <strong>Country</strong>
                        <p>{{ $patient->country }}</p>
                        <strong>State</strong>
                        <p>{{ $patient->state }}</p>
                        <strong>City</strong>
                        <p>{{ $patient->city }}</p>
                        <hr>
                        <strong>Address</strong>
                        <address>{{ $patient->office_address }}</address>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12">
                <div class="card">
                    <div class="body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#report"
                                    style="">Bio</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#timeline"
                                    style="">Activity Timeline</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane in active" id="report">
                                <div class="wrap-reset">
                                    <div class="mypost-list">
                                        <div class="post-box">
                                            <p class="bio card" style="word-wrap: break-word;">{{ $patient->bio }}</p>
                                        </div>
                                        <hr>
                                        <h4 style="text-align: center;">Past Visit History</h4>
                                        @forelse($patient->sessions as $session)
                                        <a href="javascript:void(0);" class="list-group-item">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p><strong>Provider: </strong> {{ $session->doc }}</p>
                                                    <p><strong>Date:</strong>{{ $session->date }}
                                                    <p>
                                                </div>
                                            </div>
                                        </a>
                                        @empty
                                        <a href="javascript:void(0);" class="list-group-item">No visits</a>
                                        <div class="col-12"
                                            style="padding-left: 20px; text-align:right !important;float:right">
                                        </div>
                                        @endforelse
                                    </div>
                                    @if ($patient->sessions != '')
                                    <div class="col-12"
                                        style="padding-left: 20px; text-align:right !important;float:right">
                                        <a href="{{ url('/sessions_record') }}" class="btn callbtn">View
                                            All</a>
                                    </div>
                                    @else
                                    <div class="col-12"
                                        style="padding-left: 20px; text-align:right !important;float:right">
                                        <button class="btn callbtn" disabled="disabled">View
                                            All</button>
                                    </div>
                                    @endif
                                </div>
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
                                                    @forelse ($patient->activities as $item)
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
                                                                        {{ $item->created_at }}
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
    </div>
</section>
@endsection
