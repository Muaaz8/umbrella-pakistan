@extends('layouts.admin')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h1 style="font-size: 2.0rem; line-height: 2em;">Online Doctors</h1>
            @if($session!=null)
            <h5 style="font-size: 1.0rem; line-height: 1em;color:red">Since you previously visited {{ $session->sp_name }}, charges will be Rs. {{ $session->price }}.00 only</h5>
            @endif
        </div>
        <div class="row clearfix" id="loadOnlineDoctor">
        @forelse($doctors as $doctor)
            <input type="hidden" id="loadOnlineDoctorUrl" value="{{ route('load.online.doctors',['id'=>$id]) }}">
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">
                            <div class="thumb-xl member-thumb ">

                            <a href="#" class="p-profile-pix"><img
                                    src="{{ $doctor->user_image }}" alt="{{ $doctor->name }}"
                                    class="img-thumbnail rounded-circle" height="70" style="height:100px;width:100px"></a>



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
        </div>

    </div>
</section>
<div class="modal fade" id="inquiryModal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="symp">Inquiry Form<br>
                <small>Please fill this form to continue</small></h4>
                <div class="col-md-5">
                {{-- <p class="float-left mb-0" style="color:red">Price: $50</p>
                <p  class="float-left mb-0"  style="color:red">Duration: 15 minutes</p> --}}
                </div>
            </div>
            <div class="modal-body" style="height: 150px;">
            <form action="{{route('inquiry.store')}}" method="POST" onsubmit="return checkForm(this)">
                @csrf
                @if($session!=null)
                <input type="hidden" id="price" name="price" value="{{ $session->price }}">
                @else
                <input type="hidden" id="price" name="price" value="">
                @endif
                <div class="">
                    <input type="hidden" id="doc_sp_id" name="doc_sp_id">
                    <input type="hidden" name="doc_id" id="doc_id">
                    <input type='hidden' value="0" name='Headache'>
                    <input type='hidden' value="0" name='Flu'>
                    <input type='hidden' value="0" name='Fever'>
                    <input type='hidden' value="0" name='Nausea'>
                    <input type='hidden' value="0" name='Others'>
                <h6>Symptoms</h6>
                <input type='hidden' value="0" id='sympt' name='sympt'>
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" id="s1" name="Headache" value="1">
                    <label class="form-check-label" for="s1" >
                    Headache</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" id="s2" name="Flu" value="1">
                    <label class="form-check-label" for="s2">Flu</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" id="s3" name="Fever" value="1">
                    <label class="form-check-label" for="s3">Fever</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" id="s4" name="Nausea" value="1">
                    <label class="form-check-label" for="s4">Nausea</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" id="s5" name="Others" value="1">
                    <label class="form-check-label" for="s5">Others</label>
                </div>
                </div>
                <div>
                    <h6>Description</h6>
                    <textarea required="" rows="4" id="symp_text" name="problem" class="form-control no-resize" placeholder="Add Description..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                    <button type="submit" name="submit_btn" id="submit_btn" class="btn btn-link waves-effect" style="background-color:#3a1f79e8; color:white; border:none; padding:10px;">SUBMIT</button> &nbsp;
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal" style="background-color:red; color:white; border:none; padding:10px;">CLOSE</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')

<script src="{{ asset('/js/app.js') }}"></script>
<script type="text/javascript">
<?php header("Access-Control-Allow-Origin: *"); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    function inquiryform(doc_id)
    {
        var doc_id=$(doc_id).attr('id');
        var sp_id=$("#sp_id"+doc_id).val();

        $("#doc_id").val(doc_id);
        $("#doc_sp_id").val(sp_id);
        $('#inquiryModal').modal('show');
    }

    $('#submit_btn').click(function()
{
    var temp = "";
    if ($('#s1').is(":checked") || $('#s2').is(":checked") || $('#s3').is(":checked") || $('#s4').is(":checked") || $('#s5').is(":checked"))
    {
        return true;
    }
    else{
        $('#submit_btn').type='';
        alert("Error: Please select atleast one of these symptoms");
        return false;
    }
    $('#sympt').val(temp);
});


Echo.channel('load-online-doctor')
.listen('loadOnlineDoctor', (e) => {
    var url=$('#loadOnlineDoctorUrl').val();
    $('#loadOnlineDoctor').load(url);
  });
</script>
@endsection
