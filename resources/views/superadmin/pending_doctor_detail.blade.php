@extends('layouts.admin')

@section('content')
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Doctor Profile</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">

                <div class="card">
                    <div class="header">
                        <h2>About Doctor</h2>
                    </div>
                    <div class="body">
                        <div class="row ">
                            <div class="col-sm-2">
                                <strong>Name</strong>
                            </div>
                            <div class="col-sm-10">
                                <p>{{\App\User::getName($doctor->id)}}</p>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-2">
                                <strong>Date Of Birth</strong>
                            </div>
                            <div class="col-sm-10">
                                <p>{{$doctor->date_of_birth}}</p>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-2">
                                <strong>Email</strong>
                            </div>
                            <div class="col-sm-10">
                                <p>{{$doctor->email}}</p>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-2">
                                <strong>Phone</strong>
                            </div>
                            <div class="col-sm-10">
                                <p>{{$doctor->phone_number}}</p>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-2">
                                <strong>Office Address</strong>
                            </div>
                            <div class="col-sm-10">
                                <address>{{ucwords($doctor->office_address)}}</address>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-2">
                                <strong>State</strong>
                            </div>
                            <div class="col-sm-10">
                                <p>{{ucwords($doctor->state)}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row ">
                            <div class="col-sm-2">
                                <strong>NPI</strong>
                            </div>
                            <div class="col-sm-10">
                                <p>{{$doctor->nip_number}}</p>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-2">
                                <strong>UPIN</strong>
                            </div>
                            <div class="col-sm-10">
                                <p>{{$doctor->upin}}</p>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-2">
                                <strong>Specalization</strong>
                            </div>
                            <div class="col-sm-10">
                                <p>{{ucwords($doctor->specialization)}}</p>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-2">
                                <strong>Signature</strong>
                            </div>
                            <div class="col-sm-10">
                                <img src="{{$doctor->signature}}" height="70">
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-2">
                                <strong>Driver License/ Natiional ID Card</strong>
                            </div>
                            <div class="col-sm-10">
                                <a class="btn btn-primary" href="{{ \App\Helper::get_files_url($doctor->id_card_front) }}" target="_blank">Front</a>
                                <a class="btn btn-primary" href="{{ \App\Helper::get_files_url($doctor->id_card_back) }}" target="_blank">Back</a>
                            </div>
                        </div>
                        <form name="send_contract" action="{{route('send_contract',$doctor->id)}}" method="get">
                            @csrf
                            <input type="hidden" name="doctor_id" value="{{$doctor->id}}">
                            <div class="row ">
                                <div class="col-sm-2">
                                    <strong>Licensed States</strong>
                                </div>
                                <div class="col-sm-10">
                                    <table class="table-responsive table-striped table-bordered">
                                        <tr style="font-weight:bold">
                                            <td width="20%">State</td>
                                            <td width="20%">Verified</td>
                                        </tr>
                                        @forelse($doctor->licenses as $state)
                                        <tr>
                                            <td>{{$state->state}}</td>
                                            <td>
                                                <center>
                                                    <input type="checkbox" name="{{$state->state_id}}"
                                                        value="{{$state->state}}"
                                                        style="opacity:1;position:unset;height: 25px;width: 25px;">
                                                </center>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2">No states added</td>
                                        </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-2">
                                <div class="col-sm-2">
                                    <strong>Contract Date</strong>
                                </div>
                                <div class="col-sm-10">
                                    <input name="date" class="form-control col-sm-5 px-2" type="date" required>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-sm-2">
                                    <strong>Percentage per Session</strong>
                                </div>
                                <div class="col-sm-10">
                                    <input name="session_percentage" class="form-control col-sm-5 px-2" type="number"
                                        required>
                                </div>
                            </div>
                            <div class="row">
                                <span class="alert alert-danger err_msg"></span>
                            </div>
                            <div>
                                <button id="submit" type="submit" class="btn send_contract">Send Contract</button>
                            </div>
                            <!-- <div class="col-sm-12">
                            <button type="submit" class="btn btn-raised g-bg-cyan">Approve</button>
                            <button class="btn btn-raised">Reject</button>
                        </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
$(document).ready(function() {
    $('.alert').hide();
})
$('#submit').click(function(e) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    var checkedOne = Array.prototype.slice.call(checkboxes).some(x => x.checked);
    console.log(checkedOne);
    if (checkedOne != true) {
        $('.err_msg').text('You must select a Licensed State to send contract');
        $('.alert').show();
        return false;
    } else {
        $('.err_msg').text('');
        $('.alert').hide();
        return true;
    }
})
</script>
@endsection