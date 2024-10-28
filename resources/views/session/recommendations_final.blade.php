@extends('layouts.admin')
@section('css')
<link href="asset_admin/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
<style>
/* p{
    width:50%;
    margin-bottom:0px;
     margin-left:px;
} */
</style>

@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Session</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Final Prescription <small class="text-muted">Recommended treatment is listed here</small>
                        </h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Diagnosis</th>
                                    <th>Notes</th>
                                    <th>Earning</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ucwords($pat['name'].' '.$pat['last_name'])}}</td>
                                    <td>{{ucfirst($session->diagnosis)}}</td>
                                    <input id="notes_text" hidden="" value="{{ucfirst($session->provider_notes)}}">
                                    <td id="notes"></td>
                                    <td>${{ number_format($session->price,2) }}</td>
                                    <td>{{$session->date}}</td>
                                    <td>{{$session->start_time}}</td>
                                    <td>{{$session->end_time}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>



                    <div class="body table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="lab-bg">
                                    <th>Product Name</th>
                                    <th>Dosage</th>
                                    <th>Comment</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    @if($user_type=='patient')
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $prod)
                                @if($prod->mode=='medicine')
                                <tr>
                                    @elseif($prod->mode=='lab-test')
                                <tr>
                                    @elseif($prod->mode=='imaging')
                                <tr>
                                    @endif
                                    <td>{{$prod->name}}</td>
                                    @php
                                    $t ="<div class=''>N/A</div>";
                                    @endphp
                                    <td>{!! ($prod->usage!= "") ? $prod->usage: $t !!}</td>

                                    <!-- <td>{{$prod->usage}}</td> -->
                                    @php
                                    $t ="<div class=''>N/A</div>";
                                    @endphp
                                    <td>{!! ($prod->comment!= "") ? $prod->comment : $t !!}</td>

                                    <!-- <td>{{ucfirst($prod->comment)}}</td> -->
                                    <td>{{ucwords($prod->mode)}}</td>
                                    <td>Prescribed </td>
                                    @if($user_type=='patient')
                                    <td>{{$prod->status}}</td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <center>No products added</center>
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>

                        </table>
                    </div>
                    <div class="col-12">
                        <a href="{{route('doc_waiting_room')}}">
                            <button class="btn btn-success btn-raised items-align-center col-3"
                                style="margin-left:40%">Back to waiting
                                room</button>
                        </a>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="asset_admin/js/pages/tables/jquery-datatable.js"></script>

<script src="asset_admin/plugins/bootstrap-notify/bootstrap-notify.js"></script> <!-- Bootstrap Notify Plugin Js -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="asset_admin/js/pages/ui/dialogs.js"></script>
<script>
$(document).ready(function() {
    $('#notes').append($('#notes_text').val());
})
</script>
@endsection