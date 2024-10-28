@forelse($doctors as $doctor)
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
    <input type="hidden" id="loadOnlineDoctorUrl" value="{{ route('load.online.doctors',['id'=>$id]) }}">
    <div class="card">
        <div class="body">
            <div class="member-card verified">
                <div class="thumb-xl member-thumb ">

                <a href="#" class="p-profile-pix">
                    <img src="{{$doctor->user_image}}" alt="user" class="img-thumbnail rounded-circle" height="70" style="height:100px;width:100px"></a>


                <!-- <img src="asset_admin/images/random-avatar3.jpg" class="img-thumbnail rounded-circle  " alt="profile-image"> -->
                    <!-- <span class="online"></span>                                -->
                </div>
                <div class="">
                    <h4 class="m-b-5 m-t-20">Dr. {{$doctor->name." ".$doctor->last_name}}</h4>
                    <p class="text-muted">
                        {{ $doctor->sp_name }}
                    </p>
                </div>
                <input type="hidden" id="sp_id{{ $doctor->id }}" value="{{ $doctor->specialization }}">
                <button id="{{$doctor->id}}" onclick="javascript:inquiryform(this)" class="btn btn-raised btn-sm">Talk to Doctor</button>
            </div>
        </div>
    </div>
</div>
@empty
<input type="hidden" id="loadOnlineDoctorUrl" value="{{ route('load.online.doctors',['id'=>$id]) }}">
<p class="mx-5" style="margin-left: 1rem!important; font-size: 22px;">No Doctor Available. You can set an appointment <a href="{{route('select.specialization')}}">here</a></p>

@endforelse
