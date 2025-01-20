@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Waiting Room</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script src="{{ asset('/js/app.js') }}"></script>
    <script>
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        Echo.channel('updateQueEvent')
            .listen('updateQuePatient', (e) => {
                if (e.message == "update patient que") {
                    checkSessionStatus();
                }
            });

        Echo.channel('load-online-doctor')
            .listen('loadOnlineDoctor', (e) => {
                var doc_id = '{{ $doctor->id }}' ;
                var session_id = $('#session_id').val();
                $.ajax({
                    type: "post",
                    url: "/patient_check_status",
                    data: {
                        id: doc_id,
                        session_id: session_id
                    },
                    success: function (response) {
                        console.log(response);
                        if (response == 'offline') {
                            // $('#offlineDocModal').modal({backdrop: 'static', keyboard:false});
                            $('#offlineDocModal').modal('show');
                        } else {
                            $('#offlineDocModal').modal('hide');
                            return false;
                        }
                    }
                });
            });
        var requested = false;
        var docid = $('#doc_id').attr('class');
        var session_id = $('#session_id').val();

        $(document).ready(function() {
            checkSessionStatus();
        });

        function checkSessionStatus() {
            $.ajax({
                type: "POST",
                url: "{{ route('session.status.check') }}",
                data: {
                    session_id: session_id
                },
                dataType: 'json',
                success: function(data) {
                    var status = data.data.status;
                    var appointment_id = data.data.appointment_id;
                    var que_message = data.data.que_message;
                    if (appointment_id == "" || appointment_id == null) {
                        if (status == "paid") {
                            $("#loadingButton").css('display', 'none');
                            $("#invite").css('display', 'block');
                            $("#farworded").css('display', 'none');
                        } else {
                            $("#loadingButton").css('display', 'none');
                            $("#invite").css('display', 'none');
                            $("#timer").html(que_message);
                            $("#farworded").css('display', 'block');
                        }
                    } else {

                        $("#loadingButton").css('display', 'none');
                        $("#invite").css('display', 'none');
                        $("#timer").html(que_message);
                        $("#farworded").css('display', 'block');

                    }

                }
            });
        }

        function sendInviteClick() {
            $("#loadingButton").css('display', 'block');
            $("#invite").css('display', 'none');
            $.ajax({
                type: "POST",
                url: "{{ route('invite.session') }}",
                data: {
                    session_id: session_id
                },
                dataType: 'json',
                success: function(data) {
                    var status = data.data.status;
                    var que_message = data.data.que_message;
                    if (status == "invitation sent") {
                        $("#loadingButton").css('display', 'none');
                        $("#farworded").css('display', 'block');
                        $("#timer").html(que_message);
                        $("#mesg").hide();
                    }
                }
            });
        }
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">

            <div class="col-lg-7 col-md-10 waiting-main">
                <div class="waiting-card">
                    <div class="p-2"><img src="{{ $doctor->user_image }}" class="card-img-top waiting-img"
                            alt="..."></div>
                    <div>
                        <h5><b>Dr. {{ ucwords($doctor->name . ' ' . $doctor->last_name) }}</b></h5>
                        <p id="mesg">Click on the "Send Invite" button to send request for session</p>
                        <p class="font-22" id="timer">
                            <span id="display" class="font-weight-bold" style="color:green;font-size:26px"></span>
                        </p>
                        <input hidden id="session_id" value="{{ $session_id }}">
                        <input hidden id="user_session_id" value="{{ $session_id }}">

                        <center id="loadingButton"> <img src="{{ asset('images/loading-file.gif') }}"
                                alt="Loading Please Wait..." style="height:50px ;width:50px;"></center>
                        <button type="button" id="invite" style="display:none;" onclick="sendInviteClick()"
                            class="btn btn-primary mt-2">Send Invite</button>
                        <button type="button" id="farworded" style="display:none;" onclick="javscript.void(0);"
                            class="btn btn-primary mt-2">Invitation Already Forwarded</button>

                        <input id="total_sec" type="hidden">
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="offlineDocModal" tabindex="-1"  data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Doctor Offline</h5>
                        </div>
                        <div class="modal-body">
                            <div class="p-3 fs-5">Your doctor is offline right now.... Please select one of the following options.</div>
                            <div class="p-3 fs-5" id="cancel_msg">If you cancel this appointment then you will not get refund.</div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <a href="{{ route('dash_paid_getonlinedoctors',['id'=>$doctor->specialization,'ses_id'=>$session_id]) }}"><button type="button" class="btn process-pay" >Choose Another Doctor for E-Visit</button></a>
                            <a href="/paid/book/appointment/{{$doctor->specialization}}/{{$session_id}}"><button type="button" class="btn process-pay">Book Appointment</button></a>
                            <a href="/patient/non_refund/appointment_cancel/{{$session_id}}"><button type="button" class="btn process-pay">Cancel</button></a>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
