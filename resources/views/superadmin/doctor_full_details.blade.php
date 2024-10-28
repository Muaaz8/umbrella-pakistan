@extends('layouts.admin')

@section('content')
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
                    <input type="hidden" name="id" value={{ $doctor->id }}>
                    <div class="form-group my-1">
                        <label>To (Email Address)</label>
                        <div class="form-line m-1 p-0">
                            <input  type="email" class="col-md-12  form-control" name="email" value="{{ $doctor->email }}">
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
<section class="content">
    <div class="container-fluid">
        <div class="block-header mb-0 pb-0">
            <h2>{{ucwords($doctor->name." ".$doctor->last_name)}}
            </h2>
            <ul class="breadcrumb mb-0 pb-0">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin_doctors')}}">Doctors</a></li>
                <li class="breadcrumb-item active"><a
                        href="javascript:void(0);">{{ucwords($doctor->name." ".$doctor->last_name)}}</a></li>
            </ul>
        </div>
        <div class="row clearfix">
            <div class="col-md-12 clearfix row mb-0">
                <a class="col-md-2 offset-8" href="{{ route('ban_doctor',$doctor->id)}}">
                    <button class="btn btn-raised btn-danger col-md-12 px-2 py-2"><i class="fa fa-ban"></i>
                        Block</button>
                </a>
                <a class="col-md-2" href="#">
                    <button class="btn btn-raised btn-primary col-md-12 px-2 py-2" id="send_email_btn" name="send_email_btn"><i class="fa fa-envelope"></i> Send
                        Email</button>
                </a>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Doctor Details </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                            href="#personal">Personal Information </a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#certificate"
                                            style="">Doctor
                                            Certificate</a></li>
                                    <li class="nav-item"><a class="nav-link " data-toggle="tab"
                                            href="#activity">Activity Log </a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#payment">Payment
                                            History</a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="col-md-12">
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="personal">
                                            <!-- <b>Profile Content</b> -->
                                            <table class="table table-borderless">
                                                <thead>
                                                    <th width="20%"></th>
                                                    <th></th>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td>Name</td>
                                                        <td>{{ucwords($doctor->name." ".$doctor->last_name)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>State</td>
                                                        <td>{{ucwords($doctor->state)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Specialization</td>
                                                        <td>{{ucwords($doctor->specialization)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone</td>
                                                        <td>{{ucwords($doctor->phone_number)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Address</td>
                                                        <td>{{ucwords($doctor->office_address)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>NPI</td>
                                                        <td>{{ucwords($doctor->nip_number)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>UPIN</td>
                                                        <td>{{ucwords($doctor->upin)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Driving License/Id Card</td>
                                                        <td>
                                                            <div class="col-sm-10">
                                                            <a class="btn btn-primary" href="{{ \App\Helper::get_files_url($doctor->id_card_front) }}" target="_blank">Front</a>
                                                            <a class="btn btn-primary" href="{{ \App\Helper::get_files_url($doctor->id_card_back) }}" target="_blank">Back</a>
                                                            </div>
                                                        </td>
                                                    </tr>


                                                </tbody>
                                            </table>
                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="certificate">
                                            <ul class="list-group">
                                                @forelse($certificate as $cert)
                                                <li class="list-group-item ">
                                                    <img src="../asset_admin/images/view-icon.png" alt="View icon"
                                                        height=30 width=30>
                                                    <a href="{{$cert->certificate_file}}" target="blank"
                                                        class="ml-3">View File</a>
                                                </li>

                                                @empty
                                                <li class="list-group-item mt-5">
                                                    No documents added
                                                </li>
                                                @endforelse
                                            </ul>
                                        </div>

                                        <div role="tabpanel" class="tab-pane in" id="activity">
                                            <!-- <b>Activity Log</b> -->
                                            <div class="body table-responsive">
                                                <table
                                                    class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                    <thead>
                                                        <th>Activity</th>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($activities as $activity)
                                                        <tr>
                                                            <td>{{ucfirst($activity->activity)}}</td>
                                                            <td>{{$activity->date}}</td>
                                                            <td>{{$activity->time}}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="3">
                                                                <center>No Activity Yet</center>
                                                            </td>
                                                        </tr>
                                                        @endforelse

                                                    </tbody>
                                                </table>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="paginateCounter link-paginate">
                                                    {{$activities->links('pagination::bootstrap-4') }}
                                                </div>
                                            </div>
                                        </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="payment">
                                            <!-- <b>Profile Content</b> -->
                                            <div class="body table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <th width="20%">Type</th>
                                                        <th width="20%">Date</th>
                                                        <!-- <th width="20%">Amount Paid</th> -->
                                                        <!-- <th width="20%">Other deductions</th> -->
                                                        <th width="20%">Earning</th>
                                                    </thead>
                                                    <tbody>
                                                        @if($doctor->id=='6')
                                                        <tr class="bg-teal">
                                                            <td>Sessions</td>
                                                            <td>Nov,10 2020</td>
                                                            <td class="font-weight-bold">$24</td>
                                                        </tr>
                                                        <tr class="bg-teal">
                                                            <td>Sessions</td>
                                                            <td>Nov,10 2020</td>
                                                            <td class="font-weight-bold">$24</td>
                                                        </tr>
                                                        <tr class="bg-teal">
                                                            <td>Sessions</td>
                                                            <td>Nov,10 2020</td>
                                                            <td class="font-weight-bold">$24</td>
                                                        </tr>
                                                        <tr class="bg-teal">
                                                            <td>Sessions</td>
                                                            <td>Nov,10 2020</td>
                                                            <td class="font-weight-bold">$24</td>
                                                        </tr>
                                                        <tr class="bg-teal">
                                                            <td>Sessions</td>
                                                            <td>Nov,10 2020</td>
                                                            <td class="font-weight-bold">$24</td>
                                                        </tr>
                                                        <tr class="bg-teal">
                                                            <td>Sessions</td>
                                                            <td>Nov,10 2020</td>
                                                            <td class="font-weight-bold">$24</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">Total</td>
                                                            <td class="font-weight-bold">$306</td>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <td colspan="5" class="text-center bg-grey">No Payment
                                                                History</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>

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
@section('script')
<script>
$('#send_email_btn').click(function() {
    $('#send_email_modal').modal('show');

});
$('#cancel').click(function() {
    $('#send_email_modal').modal('hide');

});
</script>
@endsection
