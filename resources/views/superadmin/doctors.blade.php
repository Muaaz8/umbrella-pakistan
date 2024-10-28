@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css') }}">

@section('content')
<section class="content ovrflw">
    <div class="container-fluid">
        <div class="block-header">
            <h2>All Doctors</h2>
            <ul class="breadcrumb mb-0 pb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0);">Doctors</a></li>
            </ul>
        </div>
        <div class="card">
        <div class="row clearfix">
            <div class="col-sm-12 text-center">
                <a href="{{ route('pending_doctors') }}" class="btn callbtn">Add Doctors</a>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table
                    class="table table-bordered table-responsive table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <th>Name</th>
                        <th>Email</th>
                        <th>State</th>
                        <th>UPIN</th>
                        <th>NPI</th>
                        <th>Status</th>
                        <th>Percentage</th>
                        <th>
                            <center>Action</center>
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->name . ' ' . $doctor->last_name }}</td>
                            <td>{{ $doctor->email }}</td>
                            <td>{{ $doctor->state }}</td>
                            <td>{{ $doctor->upin }}</td>
                            <td>{{ $doctor->nip_number }}</td>
                            <td>{{ ucwords($doctor->status) }}</td>
                            <td>{{ $doctor->percentage_doctor  }}</td>
                            <td>
                                <div class="d-flex">
                                    <div class="doc_icon-eye">
                                        <a href="{{ route('doctor_full_details', $doctor->id) }}" title="View Details">
                                            <i class="fa fa-eye doc-icon"></i>
                                        </a>
                                    </div>
                                    {{-- <a href="{{ route('activity_log_doctor',$doctor->id)}}">
                                    <button class="btn btn-raised g-bg-grey px-2 py-2"><i class="fa fa-eye"></i>
                                        View
                                        Activity Log</button>
                                    </a>
                                    <a href="{{ route('doc_pay_details',$doctor->id)}}">
                                        <button class="btn btn-raised bg-warning px-2 py-2"> Payment</button>
                                    </a> --}}
                                    <div class="doc_icon-block">
                                        <a href="{{ route('ban_doctor', $doctor->id) }}"  title="Block">
                                            <i class="fa fa-ban doc-icon"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex">
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
                                </div>
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
@section('script')
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
</script>
@endsection
