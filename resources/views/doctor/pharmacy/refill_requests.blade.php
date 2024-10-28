@extends('layouts.admin')
@section('css')
<style>
p {
    margin-bottom: 0px;
}

button {
    margin: 6px !important;
    padding: 6px !important;
}
</style>
@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Umbrella Health Care Systems</h2>
            <small class="text-muted">All refill requests are listed here</small>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Refill Requests<small>All refill requests are listed here</small> </h2>
                    </div>
                    <style>

                    </style>
                    <div class="body">
                        <div class="row clearfix col-12">
                            @forelse($refills as $refill)
                            <div class="rounded col-12" style="border:1px solid #4284c4;width:50%;
                            box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.2), 0 4px 20px 0 rgba(0, 0, 0, 0.19);">
                                <a href="#" class="col-12 row clearfix">
                                    <!-- Icon -->
                                    <div class="col-2 p-3">
                                        <img src="{{url('/uploads/'.$refill->product->featured_image)}}" height="100"
                                            width="100">
                                    </div>
                                    <!-- Text -->
                                    <div class="sbox-7-txt col-9 px-5" style="float:left;overflow: hidden;">
                                        <!-- Title -->
                                        <h5 class="h5-sm steelblue-color mb-0 mt-1">{{$refill->product->name}}</h5>
                                        <!-- Text -->
                                        <p class="p-sm"><strong>Prescribed To:
                                            </strong>{{$refill->patient->name.' '.$refill->patient->last_name}}
                                        <p>
                                        <p class="p-sm">{{ucfirst($refill->prescription->usage)}}</p>
                                        <p class="p-sm"><strong>Quantity: </strong>{{ucfirst($refill->prescription->quantity)}}</p>
                                        <p class="p-sm"><strong>Session Date: </strong>{{$refill->session->date}}</p>
                                        <p class="p-sm"><strong>Purchase Date: </strong>{{$refill->session->date}}</p>
                                        <p class="p-sm"><strong>Patient Comment: </strong>{!!$refill->comment!!}</p>
                                    </div>
                                </a>
                                <div class="row col-12">
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
                                </div>
                            </div>

                            @empty
                            <p class="col-md-12">No refill request</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Add Medicine Modal -->
<div class="modal fade" id="add_med_modal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="med_name">Medicine</h4>
            </div>
            <div class="modal-body row">
                <div class="container ml-4">
                    <h6 class="modal-title" style='color:black' id="pres_d"></h6>
                    <form action="{{route('grant_refill')}}" method="post">
                        @csrf
                        <h5>Dosage</h5>
                        <input id="refill_id" name="refill_id" hidden="">
                        {{-- <div class="form-check form-check-inline ">
                            <input type="radio" name="dose" value="24" class="form-check-input" id="op_24" readonly>
                            <label class="form-check-label" for="op_24">
                                24 hrs</label>
                        </div>
                        <div class="form-check form-check-inline ">
                            <input type="radio" name="dose" value="12" class="form-check-input" id="op_12" readonly>
                            <label class="form-check-label" for="op_12">
                                12 hrs</label>
                        </div>
                        <div class="form-check form-check-inline  ">
                            <input type="radio" name="dose" value="8" class="form-check-input" id="op_8" readonly>
                            <label class="form-check-label" for="op_8">8 hrs</label>
                        </div>
                        <div class="form-check form-check-inline  ">
                            <input type="radio" name="dose" value="6" class="form-check-input" id="op_6" readonly>
                            <label class="form-check-label" for="op_6">6 hrs</label>
                        </div> --}}
                        <div class="col-md-12 pl-0">
                            <div class="form-group">
                                <div class="form-line">
                                    <input id="dosage" name="doseage" placeholder="Dose"
                                        class="form-control border pt-0 pb-2 mr-0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pl-0">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="days">Number of days</label>
                                    <input id="days" name="days" placeholder="Number of days"
                                        class="form-control border pt-0 pb-2 mr-0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pl-0">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="units">Unit</label>
                                    <input id="units" name="units" placeholder="Units"
                                        class="form-control border pt-0 pb-2 mr-0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pl-0">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="hidden" id="quantity" max="10" name="qauntity" placeholder="Quantity"
                                        class="form-control border pt-0 pb-2 mr-0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pl-0">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="comments">Patient Comments On Request</label>
                                    <input id="comments" name="comments" placeholder="Patient's Comments"
                                        class="form-control border pt-0 pb-2 mr-0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pl-0">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="instructions">Special Instructions</label>
                                    <input type="text" id="instructions" name="instructions"
                                        placeholder="Special Instructions" class="form-control border pt-0 pb-2 mr-0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pl-0 pr-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <button type="submit" class="btn btn-primary btn-raised col-12 ml-0">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
<?php header("Access-Control-Allow-Origin: *"); ?>
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
        url: "{{URL('/get_dosage_info')}}",
        data: {
            refill_id: id_sp[1],
        },
        success: function(response) {
            console.log(response);
            $('#med_name').text(response['product']['name']);
            usage = response['prescription']['usage'];
            if (usage != null) {
                if (usage.length > 1) {
                    usage_sp = usage.split(' ');
                    dose = usage_sp[2].split('hrs')
                    console.log(dose[0])
                }
                $('#days').val(usage_sp[4]);
                $('#dosage').val(dose[0]+'hrs');

            }
            $('#pres_d').text('Prescribed On '+moment(response['prescription']['updated_at']).format('MM-DD-YYYY'));
            $('#refill_id').val(id_sp[1]);
            usage = response['comment'];
            usage = usage.split('>');
            usage = usage[1].split('<');
            $('#comments').val(usage[0]);
            $('#units').val(response['prescription']['med_unit']);
            $('#quantity').val(response['prescription']['quantity']);
            $('#instructions').val(response['prescription']['comment']);
            $('#add_med_modal').modal('show');

        }
    })
});
$(document).ready(function(){
    $('.alert').hide();
})
</script>
@endsection
