@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - My Profile</title>
@endsection

@section('top_import_file')

@endsection


@section('bottom_import_file')
<script>
$(document).ready(function(){
    $(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
    });

    function fetch_data(page)
    {
        $.ajax({
        url:"/pagination/fetch_data?page="+page,
        success:function(data)
        {
        $('#table_data').html(data);
        }
        });
    }
});
</script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row profile-row-wrapper m-auto">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="profile-box bg-white">
                                <a href="{{ route('editPatientDetail') }}">
                                    <i class="fa-solid fa-pen-to-square float-end fs-3"></i>
                                </a>
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ $patient->user_image }}" class="img-fluid rounded profile_picture"
                                        alt="">

                                    <span class="profile_name"></span>
                                    <p class="fw-bold h4 mt-3">{{ $patient->name . ' ' . $patient->last_name }}</p>
                                </div>

                                <div class="">
                                    <div class="px-3 py-2">
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Email</p>
                                            <p class="py-2 text-muted">{{ $patient->email }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Phone</p>
                                            <p class="py-2 text-muted">{{ $patient->phone_number }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Date Of Birth</p>
                                            @php
                                            $date = str_replace('-', '/', $patient->date_of_birth);
                                            $newd_o_b = date('m/d/Y', strtotime($date));
                                            //    dd($newd_o_b);
                                            @endphp
                                            <p class="py-2 text-muted">{{ $newd_o_b }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Country</p>
                                            <p class="py-2 text-muted">{{ $patient->country }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Zip Code</p>
                                            <p class="py-2 text-muted">{{ $patient->zip_code }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">State</p>
                                            <p class="py-2 text-muted">{{ $patient->state }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">City</p>
                                            <p class="py-2 text-muted">{{ $patient->city }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom">
                                            <p class="py-2">Address</p>
                                            <p class="py-2 ps-2 text-muted text-break text-end">{{ $patient->office_address }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom">
                                            <p class="py-2">Timezone</p>
                                            <p class="py-2 text-muted text-break text-end"> {{ $patient->timeZone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-12 mb-3 bg-white profile-box">
                            <div class="py-3">
                                <!-- <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="col-6 nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                            aria-selected="true">
                                            Bio
                                        </button>
                                    </li>
                                    <li class="col-6 nav-item active">
                                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#contact" type="button" role="tab" aria-controls="contact"
                                            aria-selected="true">
                                            Activities
                                        </button>
                                    </li>
                                </ul> -->
                                <!-- <div class="tab-content" id="myTabContent"> -->
                                    <!-- <div class="tab-pane fade show active" id="home" role="tabpanel"
                                        aria-labelledby="home-tab">
                                        <div class="page-content page-container bio-content">
                                            <div class="padding">
                                                <div class="row">
                                                    <h1>{{ $patient->bio }}</h1>
                                                    <div class="seven">
                                                        <h1>Past History</h1>
                                                    </div>
                                                    @forelse($patient->sessions as $session)
                                                        <div class="col-sm-6  mb-2 d-flex">
                                                            <div class="card flex">
                                                                <div class="card-body">
                                                                    <p><b>Provider : </b>{{ $session->doc }}</p>
                                                                    <p><b>Date : </b>{{ $session->date }} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <a href="javascript:void(0);" class="list-group-item">No visits</a>
                                                        <div class="" style="">
                                                        </div>
                                                    @endforelse
                                                    {{-- <div class="col-md-6 d-flex">
                                                        <div class="card flex">
                                                        <div class="card-body">
                                                            <p><b>Provider : </b>Anas Murtaza</p>
                                                            <p><b>Date : </b>Sep, 05th 2022 </p>
                                                        </div>
                                                        </div>
                                                    </div> --}}
                                                </div>

                                            </div>
                                        </div>
                                    </div> -->

                                    <!-- <div class="tab-pane fade show active" id="contact" role="tabpanel" aria-labelledby="contact-tab"> -->
                                        {{-- <div class="widget">
                                            <div class="widget-content">
                                                <div class="column-wrap">
                                                    <div class="coloumn">
                                                        <div class="activity">
                                                            <h3>LAST ACTIVITIES</h3>
                                                            @forelse ($patient->activities as $item)
                                                            <div class="activity-items">
                                                                <div class="activity-item-wrap activity-date">
                                                                        <h4 class="activity-date">
                                                                            {{ $item->created_at }}
                                                                        </h4>
                                                                </div>

                                                                <!-- start activity -->
                                                                <div class="activity-item-wrap activity-call">
                                                                    {{-- <div class="activity-item-badge">
                                                                        <i class="aroicon-action-send-message"
                                                                             {{ $item->color }}></i
                                                                    </div> --}}
                                                                    {{-- <div class="activity-item">
                                                                        <div class="activity-item-meta">
                                                                            <div class="activity-user">
                                                                                <i class="aroicon-entity-contacts"></i>
                                                                            </div>
                                                                            {{-- <p class="activity-summary">
                                                                                <strong>{{ $item->heading }}</strong>
                                                                            </p> --}}
                                                                        {{--    <p class="activity-timestamp">
                                                                                {{-- Jan 7 at 11:35am --}}
                                                                            {{--</p>
                                                                        </div>
                                                                        <div class="activity-item-details">
                                                                            {{ $item->activity }} @ <a
                                                                                href="javascript:void(0);"
                                                                                data-abc="true">
                                                                                {{ $item->created_at }}</div>
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <h4>No Activities Found.</h4>
                                                                @endforelse
                                                            </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="container">
                                              <h4 class="py-2">Activities</h4>
                                            <div id="table_data">
                                                @include('dashboard_patient.Profile.pagination_data')
                                            </div>
                                        </div>
                                    <!-- </div> -->
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
