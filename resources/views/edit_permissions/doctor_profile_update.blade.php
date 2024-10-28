@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css') }}">

@section('content')
{{-- {{ dd($doctor_profile_update) }} --}}
<section class="content ovrflw">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Doctor Edit Request</h2>
            <ul class="breadcrumb mb-0 pb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0);">Patient Records</a></li>
            </ul>
        </div>
        <div class="card">
        <div class="row clearfix">
            <div class="col-sm-12 text-center">
                <a href="{{ route('pending_doctors') }}" hidden class="btn callbtn">Add Doctors</a>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table
                    class="table table-bordered table-responsive table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <th>Name</th>
                        <th>Last Name</th>
                        <th>Date Of Birth</th>
                        {{-- <th>Phone</th> --}}
                        <th>Email</th>
                        <th>Address</th>
                        <th>Zipcode</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Bio</th>
                        <th>
                            <center>Action</center>
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($doctor_profile_update as $records)
                        <tr>
                            <td>{{ $records->name }}</td>
                            <td>{{ $records->last_name }}</td>
                            <td>{{ $records->date_of_birth }}</td>
                            {{-- <td>{{ $records->phone_number }}</td> --}}
                            <td>{{ $records->email }}</td>
                            <td>{{ $records->office_address }}</td>
                            <td>{{ $records->zip_code }}</td>
                            <td>{{ $records->state_id }}</td>
                            <td>{{ $records->city_id  }}</td>
                            <td>{{ $records->bio }}</td>
                            <td>
                                {{--  <form method="post" action="{{ route('updatePatientRecord',$records->id) }}">
                                    @csrf
                                <div class="d-flex">
                                    <div class="">
                                        <input class="form-control"  id="inputFirstName" type="text" name="fname" placeholder="Enter your first name" hidden>
                                        <input class="form-control" id="inputLastName" type="text" name="lname" placeholder="Enter your last name"  hidden>
                                        <input class="form-control" id="inputOrgName" type="date" name="dob" placeholder="Enter your organization name" hidden>
                                        <input class="form-control" id="inputLocation" name="phoneNumber" type="phoneNumber" placeholder="Enter your Number" hidden>
                                        <input class="form-control" id="inputEmailAddress" disabled type="text" name="address" placeholder="Enter your Address" hidden>
                                        <input class="form-control zip_code" id="inputPhone" type="tel" name="zip_code" placeholder="Enter your Zip Code" hidden >
                                        <select class="form-select state" name="state" readonly aria-label="Default select example" hidden>
                                        <select class="form-select city" name="city" aria-label="Default select example" hidden>
                                        <input class="form-control" id="inputEmailAddress"  name="bio" type="text" placeholder="Enter your Bio" hidden>  --}}

                                        <a href="{{route('updateRecord',$records->id)}}" class="btn btn-primary btn-sm">Approve</a>
                                        {{--  <button  type="submit" class="btn btn-primary">Approve</button>  --}}
                                            {{--  <button class="btn primary_btn">Permissions</button>  --}}
                                        </td>

                                    {{--  </div>
                                </form>  --}}
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                          @if (Session::has('success'))
                                <div class="alert alert-success">
                                    <ul>
                                        <li>{!! \Session::get('success') !!}</li>
                                    </ul>
                                </div>
                            @endif
                                    {{-- <a href="{{ route('activity_log_doctor',$doctor->id)}}">
                                    <button class="btn btn-raised g-bg-grey px-2 py-2"><i class="fa fa-eye"></i>
                                        View
                                        Activity Log</button>
                                    </a>
                                    <a href="{{ route('doc_pay_details',$doctor->id)}}">
                                        <button class="btn btn-raised bg-warning px-2 py-2"> Payment</button>
                                    </a> --}}
                                    {{--  <div class="doc_icon-block">
                                        <a href="{{ route('ban_doctor', $doctor->id) }}"  title="Block">
                                            <i class="fa fa-ban doc-icon"></i>
                                        </a>
                                    </div>
                                </div>  --}}
                                {{--  <div class="d-flex">
                                    <div class="doc_icon-email">
                                        <a class="{{ $doctor->email }}" href="#" id="send_email_btn" onclick="email_modal_function(this)" title="Send Email">
                                            <i class="fa fa-envelope doc-icon"></i>
                                        </a>
                                    </div>
                                    <div class="doc_icon-rate">
                                        <a href="{{ route('doctor.percentage', $doctor->id) }}"  title="Dollars Rate">
                                            <i class="fa fa-dollar-sign doc-icon"></i>
                                        </a>
                                    </div>
                                </div>  --}}
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
</section>
<!-- Email Send Modal -->
<div class="modal fade" id="send_email_modal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Send Email
                </h4>
            </div>
            <div class="modal-body">
                <form name="send_email" id="send_email" action="/send_email" method="POST">
                    @csrf
                    <input type="hidden" name="id" >
                    <div class="form-group my-1">
                        <label>To (Email Address)</label>
                        <div class="form-line m-1 p-0">
                            <input  type="email" class="col-md-12  form-control" name="email" id="email" value="" placeholder="Enter Subject...">
                        </div>
                    </div>
                    <div class="form-group my-1">
                        <label>Subject</label>
                        <div class="form-line m-1 p-0">
                            <input  type="text" class="col-md-12  form-control" name="subject" id="subject" placeholder="Enter Subject...">
                        </div>
                    </div>
                    <div class="form-group my-1">
                        <label>Body</label>
                        <div class="form-line m-1 p-0">
                            <textarea name="ebody" id="ebody" class="col-md-12  form-control" placeholder="Compose Email..." ></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="submit" style="color:white"
                                    class="btn btn-raised bg-green waves-effect col-md-12">Send</button>
                        </div>
                        <div class="col-sm-6">
                            <button id="cancel" name="cancel" style="color:white"
                                class="btn btn-raised bg-danger waves-effect col-md-12">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
{{--  @section('script')
<script>
// $('#send_email_btn').click(function() {
//     var email = $('#send_email_btn').attr('class');
//     alert(email);
//     // $('#send_email_modal').modal('show');

// });
$('#cancel').click(function() {
    $('#send_email_modal').modal('hide');

});
function email_modal_function(a){
    var email = $(a).attr('class');
    $('#email').val(email);
    $('#send_email_modal').modal('show');}
</script>  --}}
{{--  @endsection  --}}
