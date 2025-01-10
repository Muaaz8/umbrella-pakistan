@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>Community Healthcare Clinics - Admin Dashboard</title>
@endsection

<style>
    .online-dot{
    position: absolute;
    top: 10px;
    right: 25px;
    z-index: 2;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #2cbc63;
}

</style>

@section('content')
<div class="dashboard-content">
    <div class="container-fluid">
        <div class="col-md-12 p-3">
            <div class="row m-auto all-doc-wrap">
                <h2 class="pb-2">Online Doctors</h2>
                @forelse ($doctors as $doctor)
                <div class="col-md-4 col-lg-3 col-sm-6 mb-3 position-relative">
                    <div class="online-dot"></div>
                    <div class="card">
                        <div class="additional">
                            <div class="user-card">
                                <img src="{{ $doctor->user_image }}" alt=""/>
                            </div>
                        </div>

                        <div class="general">
                            <h4>Dr. {{ucfirst( $doctor->name)." ".ucfirst( $doctor->last_name) }}</h4>
                            <h6>{{ $doctor->sp_name }}</h6>
                            @if($doctor->rating > 0)
                            <div class="star-ratings">
                              <div class="fill-ratings" style="width: {{$doctor->rating}}%;">
                                <span>★★★★★</span>
                              </div>
                              <div class="empty-ratings">
                                <span>★★★★★</span>
                              </div>
                            </div>
                            @else
                            <div class="star-ratings">
                              <div class="fill-ratings" style="width: 0%;">
                                <span>★★★★★</span>
                              </div>
                              <div class="empty-ratings">
                                <span>★★★★★</span>
                              </div>
                            </div>
                            @endif
                            <div class="appoint-btn">
                                <a href="{{ route('all_doctor_view', ['id' => $doctor->id]) }}" class="btn btn-primary">
                                    View Profile
                                </a>
                                <a href="{{ route('all_doctor_view', ['id' => $doctor->id, 'tab' => 'activity']) }}" class="btn btn-primary">
                                    View Activity
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="text-center for-empty-div">
                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                        <h6> No Doctors</h6>
                    </div>
                @endforelse
                <div class="row d-flex justify-content-center">
                <div class="paginateCounter">
                    {{ $doctors->links('pagination::bootstrap-4') }}
                </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection
