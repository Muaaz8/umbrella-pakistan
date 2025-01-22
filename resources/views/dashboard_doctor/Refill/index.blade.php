@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Doctor Refill Requests</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script>
        function sure(id) {
            $('#refill_id_schedule').val(id);
            // console.log($(this));
            $.ajax({
                type: "get",
                url: "/req_session/"+id,
                success: function (response) {
                    if (response == '1') {
                        $('#add_schedule_modal').modal('show');
                    }else{
                        $.ajax({
                            type: "get",
                            url: "/send/session/req/"+id,
                            beforeSend: function() {
                                $('.request-session-btn'+id).attr('disabled', true);
                                $('.request-session-btn'+id).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                            },
                            success: function (response) {
                                window.location = '/patient/refill/requests';
                            }
                        });
                    }
                }
            });
        }

        $('#refill_form').submit(function () {
            $('#add_button').attr('disabled', true);
            var element = $("#add_button");
            // element.addClass("buttonload");
            element.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        });

        $('#add_button').click(function(){
            // refill_form
            // $('#add_button').attr('disabled', true);
            // $('#add_button').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        });
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-end p-0">
                            <div>
                                <h4>Refill Requests</h4>
                                <p>All Refill Requests Are Listed Here</p>
                            </div>
                        </div>
                        <div class="refill mt-3 p-0">
                            <div class="row">
                                @forelse ($refills as $refill)
                                    <div class="col-lg-6 mb-5">
                                        <div class="refill-first">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <div class="refill-data">
                                                        <i class="fa-solid fa-capsules refil-card-icon"></i>
                                                        <h6 title="{{ $refill->product->name }}" class="fw-bold">
                                                            {{ $refill->product->name }}</h6>
                                                        <p><span>Precribed
                                                                To:</span>&nbsp;<span>{{ $refill->patient->name . ' ' . $refill->patient->last_name }}</span>
                                                        </p>
                                                        <p><span>Dosage:</span>&nbsp;<span>{{ ucfirst($refill->prescription->usage) }}</span>
                                                        </p>
                                                        <p><span>Quantity:</span>&nbsp;<span>{{ ucfirst($refill->prescription->quantity) }}</span>
                                                        </p>
                                                        <p><span>Session
                                                                Date:</span>&nbsp;<span>{{ $refill->session->date }}</span>
                                                        </p>
                                                        <p><span>Purchase
                                                                Date:</span>&nbsp;<span>{{ $refill->session->date }}</span>
                                                        </p>
                                                        <!-- <p><span>Patient Comment:</span><br><span>{!! $refill->comment !!}</span></p> -->
                                                        <div id="refil_comment">
                                                            <p><span>Patient
                                                                    Comment:</span><span>{!! $refill->comment !!}</span></p>
                                                            <span id="refil_comment_content">{!! $refill->comment !!} </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="row col-12">
                                        <a href="#" class="col">
                                            <button id="adjust_{{$refill->id}}"
                                                class="btn btn-primary btn-raised col-12 adjust">Refill</button>
                                        </a>
                                        <a href="{{route('req_session',$refill->id)}}" class="col ">
                                            <button class="btn btn-success btn-raised px-2 col-12">Request Session</button>
                                        </a>
                                        <a href="{{route('session_detail_current',$refill->session_id)}}" class="col ">

                                            <button class="btn btn-warning btn-raised col-12">Details </button>
                                        </a>
                                    </div> --}}
                                                <div class="col-lg-4">
                                                    <div class="format-btn gap-2">
                                                        <a href="#" class="">
                                                            <button class="btn refill-btn adjust"
                                                                id='adjust_{{ $refill->id }}'
                                                                type="button">REFILL</button>
                                                        </a>
                                                        {{-- {{route('req_session',$refill->id)}} --}}
                                                        <button class="btn request-session-btn request-session-btn{{ $refill->id }}"
                                                            onclick="sure ({{ $refill->id }})" type="button">REQUEST
                                                            SESSION</button>
                                                        <a href="{{ route('sessionDetail', $refill->session_id) }}"
                                                            class="">
                                                            <button class="btn details-btn" type="button">DETAILS</button>
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>




                                    </div>
                                @empty
                                    <div class="text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6> No Refill Requests</h6>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- *********REFILL MODAL*********** -->



    <div class="modal fade" id="refill-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="med_name"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="mod-body p-4">
                    <div class="modal-body">
                        <form action="{{ route('grant_refill') }}" id="refill_form" method="post">
                            @csrf
                            <h6 id="date"></h6>
                            <div class="mod-two">
                                <div class="px-1">
                                    <input id="refill_id" name="refill_id" hidden="">
                                    <label for="recipient-name" name="days" class="col-form-label">Number of
                                        days</label>
                                    <input type="text" class="form-control" id="days" readonly>
                                </div>
                                <div class="px-1">
                                    <label for="recipient-name" name="units" class="col-form-label">Unit</label>
                                    <input type="text" class="form-control" id="units" readonly>
                                </div>
                                <div class="px-1">
                                    <label for="recipient-name" class="col-form-label">Quantity</label>
                                    <input type="text" class="form-control" max="10" name="qauntity" id="quantity"
                                        readonly>
                                </div>
                            </div>
                            <div class="px-1">
                                <label for="recipient-name" class="col-form-label">Dosage</label>
                                <input type="text" class="form-control" id="dosage" name="doseage" readonly>
                            </div>
                            <div class="px-1">
                                <label for="recipient-name" class="col-form-label">Patient Comments On
                                    Request</label>
                                <input type="text" class="form-control" name="comments" id="comments" readonly>
                            </div>
                            <div class="px-1">
                                <label for="recipient-name" class="col-form-label">Special
                                    Instructions</label>
                                <input type="text" class="form-control" id="instructions" name="instructions">
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn modal-btn" id="add_button">ADD</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!-- ================= Delete Modal Starts ================ -->

    <div class="modal fade" id="deleteeModal" tabindex="-1" aria-labelledby="deleteeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteeModalLabel">
                        Are sure you want to request session ?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="del_schedule">
                </div>
            </div>
        </div>
    </div>
    <!-- ================= Delete Modal Ends ================ -->
    <!-- ================= Add schedule Modal start ================ -->
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="add_schedule_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="POST" action="{{ route('request_session_schedule') }}">
            @csrf
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Add Schedule
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="input-group mb-3">
                                <input id="date" name="date" type="date" class="form-control"
                                    min="<?php date('Y-m-d') ?>" placeholder="Username" aria-label="Username"
                                    aria-describedby="basic-addon1" />
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="hidden" name="AvailabilityTitle" value="Availability"
                                            id="title" placeholder="Title">
                                        <input type="hidden" value="#008000" name="AvailabilityColor" id="color" />
                                        <input type="hidden" value="" name="id" id="refill_id_schedule" />
                                        <input type='hidden' class="form-control" name="AvailabilityStart"
                                            class="form-control" id="start" />
                                        {{-- <input type='text' class="form-control"  name="startTimePicker" id="timepicker1" value="12:00 PM" />  --}}
                                        <select class="form-control" name="startTimePicker">
                                            <option value="1:00 AM">01:00 AM</option>
                                            <option value="1:30 AM">01:30 AM</option>
                                            <option value="2:00 AM">02:00 AM</option>
                                            <option value="2:30 AM">02:30 AM</option>
                                            <option value="3:00 AM">03:00 AM</option>
                                            <option value="3:30 AM">03:30 AM</option>
                                            <option value="4:00 AM">04:00 AM</option>
                                            <option value="4:30 AM">04:30 AM</option>
                                            <option value="5:00 AM">05:00 AM</option>
                                            <option value="5:30 AM">05:30 AM</option>
                                            <option value="6:00 AM">06:00 AM</option>
                                            <option value="6:30 AM">06:30 AM</option>
                                            <option value="7:00 AM">07:00 AM</option>
                                            <option value="7:30 AM">07:30 AM</option>
                                            <option value="8:00 AM">08:00 AM</option>
                                            <option value="8:30 AM">08:30 AM</option>
                                            <option value="9:00 AM">09:00 AM</option>
                                            <option value="9:30 AM">09:30 AM</option>
                                            <option value="10:00 AM">10:00 AM</option>
                                            <option value="10:30 AM">10:30 AM</option>
                                            <option value="11:00 AM">11:00 AM</option>
                                            <option value="11:30 AM">11:30 AM</option>
                                            <option value="12:00 AM">12:00 AM</option>
                                            <option value="12:30 AM">12:30 AM</option>


                                            <option value="1:00 PM">01:00 PM</option>
                                            <option value="1:30 PM">01:30 PM</option>
                                            <option value="2:00 PM">02:00 PM</option>
                                            <option value="2:30 PM">02:30 PM</option>
                                            <option value="3:00 PM">03:00 PM</option>
                                            <option value="3:30 PM">03:30 PM</option>
                                            <option value="4:00 PM">04:00 PM</option>
                                            <option value="4:30 PM">04:30 PM</option>
                                            <option value="5:00 PM">05:00 PM</option>
                                            <option value="5:30 PM">05:30 PM</option>
                                            <option value="6:00 PM">06:00 PM</option>
                                            <option value="6:30 PM">06:30 PM</option>
                                            <option value="7:00 PM">07:00 PM</option>
                                            <option value="7:30 PM">07:30 PM</option>
                                            <option value="8:00 PM">08:00 PM</option>
                                            <option value="8:30 PM">08:30 PM</option>
                                            <option value="9:00 PM">09:00 PM</option>
                                            <option value="9:30 PM">09:30 PM</option>
                                            <option value="10:00 PM">10:00 PM</option>
                                            <option value="10:30 PM">10:30 PM</option>
                                            <option value="11:00 PM">11:00 PM</option>
                                            <option value="11:30 PM">11:30 PM</option>
                                            <option value="12:00 PM">12:00 PM</option>
                                            <option value="12:30 PM">12:30 PM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type='hidden' class="form-control" name="AvailabilityEnd"
                                            class="form-control" id="end" />

                                        {{-- <input type='text' class="form-control"  name="endTimePicker" id="timepicker1" value="12:00 PM" />  --}}
                                        <select class="form-control" name="endTimePicker">
                                            <option value="1:00 AM">01:00 AM</option>
                                            <option value="1:30 AM">01:30 AM</option>
                                            <option value="2:00 AM">02:00 AM</option>
                                            <option value="2:30 AM">02:30 AM</option>
                                            <option value="3:00 AM">03:00 AM</option>
                                            <option value="3:30 AM">03:30 AM</option>
                                            <option value="4:00 AM">04:00 AM</option>
                                            <option value="4:30 AM">04:30 AM</option>
                                            <option value="5:00 AM">05:00 AM</option>
                                            <option value="5:30 AM">05:30 AM</option>
                                            <option value="6:00 AM">06:00 AM</option>
                                            <option value="6:30 AM">06:30 AM</option>
                                            <option value="7:00 AM">07:00 AM</option>
                                            <option value="7:30 AM">07:30 AM</option>
                                            <option value="8:00 AM">08:00 AM</option>
                                            <option value="8:30 AM">08:30 AM</option>
                                            <option value="9:00 AM">09:00 AM</option>
                                            <option value="9:30 AM">09:30 AM</option>
                                            <option value="10:00 AM">10:00 AM</option>
                                            <option value="10:30 AM">10:30 AM</option>
                                            <option value="11:00 AM">11:00 AM</option>
                                            <option value="11:30 AM">11:30 AM</option>
                                            <option value="12:00 AM">12:00 AM</option>
                                            <option value="12:30 AM">12:30 AM</option>


                                            <option value="1:00 PM">01:00 PM</option>
                                            <option value="1:30 PM">01:30 PM</option>
                                            <option value="2:00 PM">02:00 PM</option>
                                            <option value="2:30 PM">02:30 PM</option>
                                            <option value="3:00 PM">03:00 PM</option>
                                            <option value="3:30 PM">03:30 PM</option>
                                            <option value="4:00 PM">04:00 PM</option>
                                            <option value="4:30 PM">04:30 PM</option>
                                            <option value="5:00 PM">05:00 PM</option>
                                            <option value="5:30 PM">05:30 PM</option>
                                            <option value="6:00 PM">06:00 PM</option>
                                            <option value="6:30 PM">06:30 PM</option>
                                            <option value="7:00 PM">07:00 PM</option>
                                            <option value="7:30 PM">07:30 PM</option>
                                            <option value="8:00 PM">08:00 PM</option>
                                            <option value="8:30 PM">08:30 PM</option>
                                            <option value="9:00 PM">09:00 PM</option>
                                            <option value="9:30 PM">09:30 PM</option>
                                            <option value="10:00 PM">10:00 PM</option>
                                            <option value="10:30 PM">10:30 PM</option>
                                            <option value="11:00 PM">11:00 PM</option>
                                            <option value="11:30 PM">11:30 PM</option>
                                            <option value="12:00 PM">12:00 PM</option>
                                            <option value="12:30 PM">12:30 PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center" id="error">
                            </div>
                            <div class="text-center">
                                <button id="addTiming" class="w-100 add-schedule">Add Schedule</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- ================= Add schedule Modal Ends ================ -->

    <script>
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#dose').click(function() {
            $('#add_med_modal').modal('hide');
        })
        $('.adjust').click(function() {
            id = $(this).attr('id');
            id_sp = id.split('adjust_');
            console.log(id_sp[1]);
            $.ajax({
                type: "POST",
                url: "{{ URL('/get_dosage_info') }}",
                data: {
                    refill_id: id_sp[1],
                },
                success: function(response) {
                    console.log(response);
                    $('#med_name').text(response['product']['name']);
                    $('#date').text(response['session']['date']);
                    usage = response['prescription']['usage'];
                    if (usage != null) {
                        if (usage.length > 1) {
                            usage_sp = usage.split(' ');
                            dose = usage_sp[2].split('hrs')
                            console.log(usage_sp)
                        }
                        $('#days').val(usage_sp[5]);
                        $('#dosage').val(dose[0] + 'hrs');

                    }
                    $('#refill_id').val(id_sp[1]);
                    $('#comments').val(response['comment']);
                    $('#units').val(response['prescription']['med_unit']);
                    $('#quantity').val(response['prescription']['quantity']);
                    $('#instructions').val(response['prescription']['comment']);
                    $('#refill-modal').modal('show');

                }
            })
        });
        $(document).ready(function() {
            $('.alert').hide();
        })
    </script>
@endsection
