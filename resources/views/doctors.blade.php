@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>All Doctors</h2>
        </div>
        <div class="row clearfix">
            @foreach ($doctors as $doctor)
            @if ($doctor->id == $user_id)
            @continue
            @endif
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar3.jpg" class="img-thumbnail rounded-circle"
                                    alt="profile-image">
                            </div>
                            <div class="">

                                <h6 class=" doctor-text m-b-5 m-t-20">Dr.
                                    {{ $doctor->name . ' ' . $doctor->last_name }}</h6>
                                <p class="text-muted">
                                    @if ($doctor->spec == 'None')
                                    {{ 'General' }}
                                    @else
                                    {{ $doctor->spec }}
                                    @endif
                                    <!-- <span> <a href="#" class="text-pink">websitename.com</a> </span></p> -->
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    @if ($doctor->rating == '')
                                    <h5 class="mb-0"> <span>5</span> <i
                                            style="font-size:11px;color:orange;top: 4px;position: absolute;"
                                            class="fa fa-star"></i>
                                        <span class="ml-3" style="font-size:12px">Rating</span>
                                    </h5>
                                    @else
                                    <h5 class="mb-0"> <span>{{ $doctor->rating }}</span><i
                                            style="font-size:11px;color:orange;top: 4px;position: absolute;"
                                            class="fa fa-star"></i> <span class="ml-3"
                                            style="font-size:12px">Rating</span></h5>
                                    @endif
                                    <!-- <h5 class="mt-0"
                                                style="font-size:12px">Rating</h5> -->

                                </div>
                                <!-- <div class="col-12">
                                        <p class="float-right mr-1">Rating</p>
                                    </div> -->

                            </div>
                            <a href="{{ route('book.appointment',['id'=>$doctor->specialization,'doc'=>$doctor->id]) }}"
                                class="btn btn-raised btn-sm">Book Appointment</a>
                            @if ($user->user_type == 'patient')
                            <a href="{{ route('appointment.index', ['id' => $doctor->id]) }}"
                                class="btn btn-raised btn-sm">My Appointments</a>
                            @else
                            <!-- <a href="{{ route('doc_profile') }}" class="btn btn-raised btn-sm">View Profile</a> -->
                            @endif


                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="paginateCounter link-paginate">
                    {{$doctors->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        <div class="row clearfix col-sm-12">
            @if ($user->user_type == 'admin')
            <div class="col-sm-6 text-right">
                <!-- <a href="#" class="btn btn-raised g-bg-cyan">All Doctor</a> -->
            </div>
            <div class="col-sm-6 ">
                <a href="#" class="btn btn-raised g-bg-cyan">Add Doctor</a>
            </div>
            @else
            <div class="col-sm-12 text-center">
                <!-- <a href="#" class="btn btn-raised g-bg-cyan">All Doctor</a> -->
            </div>
            @endif
        </div>
    </div>
</section>
@endsection