@extends('layouts.admin')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Available Doctors</h2>
        </div>
        <div class="row clearfix">
        @forelse($doctors as $doctor)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">
                            <div class="thumb-xl member-thumb ">

                            <a href="#" class="p-profile-pix">
                                <img src="{{$doctor->user_image}}" alt="user" class="img-thumbnail rounded-circle" height="70" style="height:100px;width:100px">
                            </a>


                            <!-- <img src="asset_admin/images/random-avatar3.jpg" class="img-thumbnail rounded-circle  " alt="profile-image"> -->
                                <!-- <span class="online"></span>                                -->
                            </div>
                            <div class="">
                                <h4 class="m-b-5 m-t-20">Dr. {{$doctor->name." ".$doctor->last_name}}</h4>
                                <p class="text-muted">
                                    {{ $doctor->sp_name }}
                                </p>
                            </div>
                            <button  onclick="window.location.href='{{ route('reschedule.form',['app_id'=>$app,'spec_id'=>$doctor->specialization,'doc_id'=>$doctor->id]) }}'" class="btn btn-raised btn-sm">Book Appointment</button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <p class="mx-5">No Doctor Available.</p>

            @endforelse
        </div>
        @if(count($refered_doctors) > 0)

        <div class="block-header">
            <h2>Refered Doctors</h2>
        </div>
        <div class="row clearfix">
            @forelse($refered_doctors as $r_d)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="body">
                            <div class="member-card verified">
                                <div class="thumb-xl member-thumb ">

                                <a href="#" class="p-profile-pix">
                                    <img src="{{$r_d->user_image}}" alt="user" class="img-thumbnail rounded-circle" height="70" style="height:100px;width:100px">
                                </a>



                                <!-- <img src="asset_admin/images/random-avatar3.jpg" class="img-thumbnail rounded-circle  " alt="profile-image"> -->
                                    <!-- <span class="online"></span>                                -->
                                </div>
                                <div class="">
                                    <h4 class="m-b-5 m-t-20">Dr. {{$r_d->name." ".$r_d->last_name}}</h4>
                                    <p class="text-muted">
                                        {{ $r_d->sp_name }}
                                    </p>
                                </div>
                                <button  onclick="window.location.href='{{ route('reschedule.form',['app_id'=>$app_id,'spec_id'=>$r_d->specialization,'doc_id'=>$r_d->id]) }}'" class="btn btn-raised btn-sm">Book Appointment</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            <p class="mx-5">No Any Doctor Refered To You.</p>

            @endforelse
        </div>
        @endif
        @if(count($already_session_did) > 0)

        <div class="block-header">
            <h2>Previous Doctors</h2>
        </div>
        <div class="row clearfix">
            @forelse($already_session_did as $a_s_d)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="body">
                            <div class="member-card verified">
                                <div class="thumb-xl member-thumb ">

                                <a href="#" class="p-profile-pix">
                                    <img src="{{$a_s_d->user_image}}" alt="user" class="img-thumbnail rounded-circle" height="70" style="height:100px;width:100px">
                                </a>

                                <!-- <img src="asset_admin/images/random-avatar3.jpg" class="img-thumbnail rounded-circle  " alt="profile-image"> -->
                                    <!-- <span class="online"></span>                                -->
                                </div>
                                <div class="">
                                    <h4 class="m-b-5 m-t-20">Dr. {{$a_s_d->name." ".$a_s_d->last_name}}</h4>
                                    <p class="text-muted">
                                        {{ $a_s_d->sp_name }}
                                    </p>
                                </div>
                                <button  onclick="window.location.href='{{ route('reschedule.form',['app_id'=>$app,'spec_id'=>$a_s_d->specialization,'doc_id'=>$a_s_d->id]) }}'" class="btn btn-raised btn-sm">Book Appointment</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            <p class="mx-5">You Have Not Any Previous Doctor.</p>

            @endforelse
        </div>
        @endif
    </div>
</section>

@endsection
@section('script')

@endsection
