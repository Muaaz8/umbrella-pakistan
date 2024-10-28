@extends('layouts.admin')
@section('css')
<link href="asset_admin/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
<style>
    .meds {
        border: solid red 1px;
        background-color: red;
        color: white;
    }

    .labs {
        border: solid #3a1f79e8 1px;
        background-color: #3a1f79e8;
        color: white;
    }

    .imagings {
        border: solid #f2711c 1px;
        background-color: #f2711c;
        color: white;
    }

    .disabled_btn {
        color: black;
    }
</style>
@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Final Prescription</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Prescribed Treatment<small class="col-md">All the prescription scheduled are listed
                                here</small></h2>
                    </div>
                    <div class="card-body">

                        <form method="post" action="{{route('recommendations.store')}}">
                            @csrf
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4">
                                        <a class="btn btn-block border-0" href="javascript:void(0);" data-toggle="modal"
                                            data-target="#pharmacyModal"
                                            style="background-color:red; color:white;">Pharmacy</a>
                                    </div>
                                    <div class="col-4">
                                        <a class="btn btn-block border-0" onclick="javascript:void(0);"
                                            data-toggle="modal" data-target="#labModal"
                                            style="background-color:#3a1f79e8; color:white;">Lab</a>
                                    </div>
                                    <div class="col-4">
                                        <a class="btn btn-block border-0" onclick="javascript:void(0);"
                                            data-toggle="modal" data-target="#imagingModal"
                                            style="background-color:#f2711c; color:white;">Imaging</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <h6><strong class="mt-2">Diagnosis</strong></h6>
                                        <textarea id="diagnosis" name="diagnosis" cols="92" rows="5"
                                            placeholder="write diagnosis here"
                                            required>{{ $getSession->diagnosis ?? ''}}</textarea>
                                    </div>
                                    <div class="col-6">
                                        <h6><strong class="mt-2">Notes</strong></h6>
                                        <textarea id="note" name="note" cols="92" rows="5"
                                            placeholder="write notes here"
                                            required>{{ $getSession->provider_notes ?? ''}}</textarea>
                                    </div>
                                </div>
                            </div>

                            @php
                            $arr=array();
                            foreach($refered as $key=>$refer){
                            array_push($arr,$refer->name.' '.$refer->last_name.' ('.$refer->spec_name.') ');
                            }
                            $ref_docs= implode(', ', $arr);
                            @endphp
                            @if($refered->count()>0)
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <h6><strong class="mt-2">Refered</strong></h6>
                                        @if($refered->count()>1)
                                        <p>Refered to <b>{{$refered['0']->name.' '.$refered['0']->last_name.'
                                                '}}</b>({{$refered['0']->spec_name}}) and
                                            <a href="#" data-toggle="tooltip" title="{{$ref_docs}}">
                                                @if($refered->count()-1==1)
                                                {{$refered->count()-1}} other.
                                        </p>
                                        @else
                                        {{$refered->count()-1}} others.</p>
                                        @endif
                                        </a>
                                        @else
                                        <p>
                                            Refered to <b>{{$refered['0']->name.'
                                                '.$refered['0']->last_name}}</b>({{$refered['0']->spec_name}})
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <br>
                            @endif

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <h6><strong class="mt-2">Prescription</strong></h6>
                                        <h5 id="imaging_name" class="mt-1 mb-1" style="color:red;"></h5>
                                        <input hidden="" id="pat_id" type="text" name="patient_id"
                                            value="{{$getSession->patient_id}}" />
                                        <input hidden="" id="doc_id" type="text" name="doc_id"
                                            value="{{$getSession->doctor_id}}" />
                                        <input hidden="" id="session_id" type="text" name="session_id"
                                            value="{{$getSession->id}}" />
                                        <div class="pres">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success text-white" role="button">Confirm
                                            Recommendationsssss</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Pharmacy Modal -->
<div class="modal fade" id="pharmacyModal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="min-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-2" style="font-size:28px;color:red" id="pharmacy">Pharmacy</h4>
                <button class="btn btn-default btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:black;font-size:24px" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-6 float-left" style="overflow-y:auto;height:500px">
                    <div class="row">
                        <div id="med_categories" class="col-12" style="overflow-y:auto;height:500px">
                            <h4>Pharmacy Products<br><small>Select Category</small></h4>
                            <div class="row col-12">
                                @foreach($medicines['category'] as $cat)
                                <a data-id="{{$cat->id}}" class="med_categories col-6">
                                    <div class="jumbotron text-center col-12"
                                        style="cursor: pointer; padding: 0.5rem 0.2rem;">
                                        {{$cat->title}}
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        <div id="med_products" class="col-12">
                            <input class="form-control" id="search_phar" placeholder="Search Medicines..">
                            <div class="row col-12">
                                <button id="med_back_btn"
                                    style="color: white !important;font-size: 15px;padding: 5px 11px;background-color: #4f4c4c;"
                                    class="btn btn-grey mt-1">
                                    <i class="fas fa-caret-left" style="font-size:16px"></i> Back
                                </button>
                            </div>
                            <div class="table table-hover table-bordered mt-2" style="overflow-y:auto;height:400px">
                                <table id="table_phar">
                                    <thead>
                                        <th style="width:421px">Medicine</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody id="med_tbl">
                                    </tbody>
                                </table>
                            </div>
                            <div id="search_res_phar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 float-right" style="overflow-y:auto;height:500px">
                    <h4>Current Medications</h4>
                    @forelse($patient_meds as $pr)
                    <div class="sbox-7 icon-xs wow "
                        style="background-color: #e0e0e0;padding: 10px 10px;margin: 5px 10px;border: 1px solid;border-radius: 17px;"
                        data-wow-delay="0.4s">
                        <a href="#">
                            <!-- Icon -->
                            <!-- <span class="flaticon-137-doctor blue-color"></span> -->
                            <!-- Text -->
                            <div class="sbox-7-txt">
                                <!-- Title -->
                                <h5 class="h5-sm steelblue-color">{{$pr->prod->name}}</h5>
                                <!-- Text -->
                                <p class="p-sm"><strong>Prescribed By:</strong> {{$pr->doc}}
                                <p>
                                <p class="p-sm"><strong>Status:</strong>{{ucfirst($pr->status)}}</p>
                                <p class="p-sm"><strong>Session Date: </strong>{{$pr->date}}</p>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="sbox-7 icon-xs wow"
                        style="background-color: #e0e0e0;padding: 10px 10px;margin: 5px 10px;border: 1px solid;border-radius: 17px;"
                        data-wow-delay="0.4s">
                        <a href="#">
                            <!-- Icon -->
                            <!-- <span class="flaticon-137-doctor blue-color"></span> -->
                            <!-- Text -->
                            <div class="sbox-7-txt">
                                <!-- Title -->
                                <h5 class="h5-sm steelblue-color">No Current Medicines</h5>
                                <!-- Text -->
                            </div>
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Lab Modal -->
<div class="modal fade" id="labModal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog" style="margin-top:5%; min-width:1000px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-2" style="font-size:28px;color:#3a1f79e8">Lab</h4>
                <button id="close_lab" class="btn btn-default btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:black;font-size:24px" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <div class="col-6">
                    <input class="form-control" id="search_lab" placeholder="Search Lab..">
                    <div style="overflow-y:auto;height:330px">
                        <div class="table table-hover table-bordered mt-2">
                            <table id="table_lab">
                                <thead>
                                    <th style="width:400px">Lab Tests</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($labs as $lab)
                                    <tr>
                                        <td>{{$lab->DESCRIPTION}}</td>
                                        <td>
                                            @if($lab->added=='yes')
                                            <button onclick="add_lab(this)"
                                                style="color:white; background-color:#0069d9;cursor: pointer;"
                                                class="btn btn-primary {{$lab->TEST_CD}} prod_{{$lab->TEST_CD}} {{$lab->DESCRIPTION}} {{$getSession->id}}"
                                                disabled>Added
                                            </button>
                                            @else
                                            <button onclick="add_lab(this)"
                                                style="color:white; background-color:#0069d9;cursor: pointer;"
                                                class="btn btn-primary {{$lab->TEST_CD}} prod_{{$lab->TEST_CD}} {{$lab->DESCRIPTION}} {{$getSession->id}}">Add
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6 float-right" style="overflow-y:auto;height:400px">
                    <div class="row">
                        <div class="col-12 float-right">
                            <h4>Latest Reports</h4>
                            @forelse($patient_labs as $pr)
                            <a href="javascript:void(0);" class="list-group-item">
                                <div class="row">
                                    <div class="col-8">
                                        <p>{{$pr->test_names}}</p>
                                        <p><strong>Date: </strong>{{$pr->result_date}}
                                        </p>
                                    </div>
                                    <!-- report id -->
                                    <input id="report_file_{{$pr->id}}" hidden value="{{$pr->file}}">
                                    <input id="report_date_{{$pr->id}}" hidden value="{{$pr->result_date}}">
                                    <div class="col-4"><button id="report_{{$pr->id}}" onclick="load_report(this)"
                                            class="btn btn-primary " data-dismiss="modal"
                                            style="color:white; background-color:#0069d9;" data-toggle="modal"
                                            data-target="#test_report_modal">View Report</button></div>
                                </div>
                            </a>
                            @empty
                            <a href="javascript:void(0);" class="list-group-item">
                                <div class="row">
                                    <div class="col-12">
                                        <p>No Labs Reports</p>
                                    </div>
                                </div>
                            </a>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Imaging Modal -->
<div class="modal fade" id="imagingModal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog " style="margin-top:5%;min-width:1000px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-2" style="font-size:28px;color:orange">Imaging</h4>
                <button id="close_img" class="btn btn-default btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:black;font-size:24px" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <div class="col-6">
                    <!-- <input class="form-control" placeholder="Search Pharmacy.." onkeyup="showResult(this.value)"> -->
                    <div id="img_categories">
                        <h4>Imaging Services<br><small>Select Category</small></h4>
                        <div class="row col-12">
                            @foreach($img_categories as $cat)
                            <a data-id="{{$cat->id}}" class="img_categories col-6 ">
                                <div class="jumbotron p-3 text-center col-12" style="cursor: pointer">
                                    {{$cat->name}}
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    <div id="img_products">
                        <input class="form-control" id="search_imaging" placeholder="Search Imaging..">
                        <div class="row col-12">
                            <button id="back_btn"
                                style="color: white !important;font-size: 15px;padding: 5px 11px;background-color: #4f4c4c;"
                                class="btn btn-grey mt-1">
                                <i class="fas fa-caret-left" style="font-size:16px"></i> Back
                            </button>
                        </div>
                        <div class="table table-hover table-bordered mt-2" style="overflow-y:auto;height:350px">
                            <table id="table_imaging">
                                <thead>
                                    <th style="width:400px">Imaging</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="img_tbl">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6 float-right" style="overflow-y:auto;height:400px">
                    <h4>Latest Reports</h4>
                    @forelse($img_reports as $report)
                    <a href="javascript:void(0);" class="list-group-item">
                        <div class="row">
                            <div class="col-12">
                                <p>{{$report->name}}</p>
                                <!-- <p><strong>Date:</strong>{{$report->created_at}} -->
                                <p>
                            </div>
                            <div class="col-12 text-center">
                                <button class="btn btn-primary img_report" data-dismiss="modal" data-toggle="modal"
                                    data-target="#img_report_modal" data-id="{{$report->id}}"
                                    style="color:white; background-color:#0069d9;">View</button>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="sbox-7 icon-xs wow " style="padding:10px 2px;margin:5px 10px" data-wow-delay="0.4s">
                        <a href="#">
                            <div class="sbox-7-txt">
                                <h5 class="h5-sm steelblue-color">No Imaging Reports</h5>
                            </div>
                        </a>
                    </div>

                    @endforelse

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Medicine Modal -->
<div class="modal fade m-5 p-5" id="add_med_modal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="med_name"></h4>
                <button id="close_dose" class="btn btn-default p-1 btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:rgb(0, 0, 0);font-size:22px" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <div class="container">
                    <h5>Dosage</h5>
                    <input id="med_id" hidden="">
                    <input id="med_current_price" hidden="">
                    <div class="col-md-12 pl-0">
                        <select id="dose" class="form-control col-md-12 border pt-0 pb-2 mr-0 mt-1">
                            <option value="">Choose Time For Dose</option>
                            <option value="6">6hrs</option>
                            <option value="8">8hrs</option>
                            <option value="12">12hrs</option>
                            <option value="24">24hrs</option>
                        </select>
                        <select id="days" class="form-control col-md-12 border pt-0 pb-2 mr-0 mt-1"></select>
                        <select id="units" class="form-control col-md-12 border pt-0 pb-2 mr-0 mt-1"></select>
                        <input type="text" id="instructions" placeholder="Special Instructions"
                            class="form-control col-md-12 border pt-0 pb-2 mr-0 mt-1">
                    </div>
                    <button type="button" onclick="add_dosage(this)" class="btn btn-primary btn-raised col-12"
                        style="color:white;background-color:#0069d9;" data-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- aoes modal -->
<div class="modal fade m-5 p-5" id="aoes_med_modal" style="font-weight:normal; overflow-x: hidden; overflow-y: hidden;"
    tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button id="aoe_close_dose"
                    class="btn btn-default p-1 btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:rgb(3, 0, 0);font-size:22px" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <div class="col-12 float-right" id="load_aoes">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Zip modal -->
<div class="modal fade m-2 p-5" id="add_imaging_zip_code_modal" style="font-weight: normal; " tabindex="-1"
    role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Zip Code To Nearest Imaging Location</h4>
                <button id="close_add_imaging_zip_code_modal"
                    class="btn btn-default p-1 btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:rgb(15, 1, 1);font-size:22px" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="container" style="overflow-y:auto;height:450px; ">
                    <h5>Zip Code</h5>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-10">
                                <input id="zip_code" class="form-control" placeholder="Add Zip Code Here..">
                            </div>
                            <div class="col-2">
                                <button onclick="getImagingLocationByZipCode(this)" style="color:black;font-size:24px">
                                    <i class="fa fa-caret-right p-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="imging_id_get">
                    <div id="imging_message"></div>
                    <div id="imaging_list" class="p-2" data-dismiss="modal">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="{{ asset('/js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="{{ asset('asset_admin/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{asset('asset_admin/js/pages/ui/modals.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript">
<? php header("Access-Control-Allow-Origin: *");?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    var prod_list = [];
    var lab_list = [];
    var meds = 0;
    var time;

    $(document).ready(function () {
        $("#img_products").hide();
        $('#imaging_msg').hide();
        $("#med_products").hide();
        $('#imaging_list').hide();
        $(".checkout_loader").hide();
        onPageLoadPrescribeItemLoad();
    });
    function recommendationsDone() {
        if ($('#diagnosis').val() == '' && $('#note').val() == '') {
            alert('please fill diagnosis and notes first');
            return false;
        }
        var id = $('#session_id').val();
        $.ajax({
            type: 'POST',
            url: '/check_prescription_completed',
            data: {
                id: id,
            },
            success: function (status) {
                if (status != 'success') {
                    var error = status.split('_');
                    if (error[0] == "lab-error") {
                        Swal.fire({
                            title: 'Incomplete',
                            text: "Please Fill " + error[1] + " Lab Test AOEs",
                            icon: 'error',
                            showCancelButton: true,
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Close',
                        });
                    }
                    else if (error[0] == "imaging-error") {
                        Swal.fire({
                            title: 'Incomplete',
                            text: "Please fill zipcode for " + error[1] + " imaging",
                            icon: 'error',
                            showCancelButton: true,
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Close',
                        });
                    }

                }
                else {

                    $("#recommendationForm").submit();

                }

            }
        });
    }
    Echo.channel('load-prescribe-item-list')
        .listen('LoadPrescribeItemList', (e) => {
            var session_id = $('#session_id').val();
            var user_id = {{ Auth:: user() -> id ?? '0'
        }};
    if (session_id == e.session_id && user_id == e.user_id) {
        $('.pres').html('');
        $.ajax({
            type: 'POST',
            url: "{{URL('/get_prescribe_item_list')}}",
            data: {
                session_id: e.session_id
            },
            success: function (products) {
                if (products.length > 0) {
                    $.each(products, function (key, product) {
                        if (product.mode == 'medicine') {


                            meds++;
                            $('#hid_phar_id').attr('required', true);
                            $('.pres').append('<a href="javascript:void(0);" id="medicine ' + product.id +
                                '" class="list-group-item mt-1 rounded" style="border:solid red 1px;background-color:red;color:white"><div class="row col-12"><span class="col-8">' +
                                product.name +
                                '<br>' +
                                (product.usage == "nothing" ? '' : product.usage) +
                                '</span><span class="float-right col-3"><button class="aoesBTN btn btn-md" style="color: black;padding: 5px;background: white;" id="dosage_' +
                                product.id +
                                '" type="button" onclick="dosage(this)" data-toggle="modal" data-target="#add_med_modal">Add Dosage</button></span><span class="float-right col-1"><button type="button"' +
                                ' onclick="remove(this)" style="color:white" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></span>' +
                                '<span class="ml-3" style="color:white" id="' + product.id + '_time"></span></div></a>'
                            );
                        }
                        else if (product.mode == 'lab-test') {
                            if (product.aoes == 1) {
                                $('.pres').append('<a href="javascript:void(0);" id="lab-test ' + product.TEST_CD +
                                    '" class="list-group-item mt-1 rounded" style="border:solid #3a1f79e8 1px;background-color:#3a1f79e8;color:white"><div class="row col-12"><span class="col-8">' +
                                    product
                                        .DESCRIPTION +
                                    '</span><span class="float-right col-3"><button type="button" style="color: black;padding: 5px;background: white;"' +
                                    ' onclick="aoesModalOpen(this)" data-toggle="modal" data-target="#aoes_med_modal" class="aoesBTN btn btn-md ' + product.TEST_CD + ' ' + product.DESCRIPTION + ' "' +
                                    'aria-label="Close">Test AOES <span style="color:red;">*</span></button></span>' +
                                    '<span class="float-right col-1"><button type="button"  style="color:white" onclick="remove(this)" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></span>' +
                                    '<input id="' +
                                    product.TEST_CD +
                                    '_comment" name="' + product.TEST_CD + '_comment" hidden="" ></div></a>'
                                );
                            } else {
                                $('.pres').append('<a href="javascript:void(0);" id="lab-test ' + product.TEST_CD +
                                    '" class="list-group-item mt-1 rounded" style="border:solid #3a1f79e8 1px;background-color:#3a1f79e8;color:white"><div class="row col-12"><span class="col-8">' +
                                    product
                                        .DESCRIPTION +
                                    '</span><span class="float-right col-3"></span>' +
                                    '<span class="float-right col-1"><button type="button" style="color:white" onclick="remove(this)" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></span>' +
                                    '<input id="' +
                                    product.TEST_CD +
                                    '_comment" name="' + product.TEST_CD + '_comment" hidden="" ></div></a>'
                                );
                            }


                            lab_list.push(product.TEST_CD);
                            $('#lab_products').val(lab_list);
                        }
                        else if (product.mode == 'imaging') {
                            if (product.location == "nothing") {
                                $('.pres').append('<a href="javascript:void(0);" id="imaging ' + product.id +
                                    '" class="list-group-item mt-1 rounded " style="border:solid #f26202 1px;background-color:#f26202;color:white"><div class="row col-12"><span class="col-8">' +
                                    product
                                        .name +
                                    '</span><span class="float-right col-3"><button type="button" style="color: black;padding: 5px;background: white;"' +
                                    'onclick="zipcodeModalOpen(this)" data-toggle="modal" data-target="#add_imaging_zip_code_modal" class="aoesBTN btn btn-md ' + product.id + ' ' + product.name + ' "' +
                                    'aria-label="Close">Add Location<span style="color:red;">*</span></button></span>' +
                                    '<span class="float-right col-1"><button type="button" style="color:white" onclick="remove(this)" class="close" aria-label="Close">' +
                                    '<span aria-hidden="true">&times;</span></button></span>' +
                                    '</div></a>'
                                );

                            } else {
                                $('.pres').append('<a href="javascript:void(0);" id="imaging ' + product.id +
                                    '" class="list-group-item mt-1 rounded " style="border:solid #f26202 1px;background-color:#f26202;color:white"><div class="row col-12"><span class="col-8">' +
                                    product
                                        .name +
                                    '<br>Location:<span id="choosanLocation">' + product.location + '</span></span><span class="float-right col-3"><button type="button" style="color: black;padding: 5px;background: white;"' +
                                    'onclick="zipcodeModalOpen(this)" data-toggle="modal" data-target="#add_imaging_zip_code_modal" class="aoesBTN btn btn-md ' + product.id + ' ' + product.name + ' "' +
                                    'aria-label="Close">Add Location<span style="color:red;">*</span></button></span>' +
                                    '<span class="float-right col-1"><button type="button" style="color:white" onclick="remove(this)" class="close" aria-label="Close">' +
                                    '<span aria-hidden="true">&times;</span></button></span>' +
                                    '</div></a>'
                                );
                            }
                            prod_list.push(product.id);
                            $('#products').val(prod_list);
                        }
                        if (product.mode != 'imaging') {
                            prod_list.push(product.id);
                            $('#products').val(prod_list);
                        }
                    });
                } else {
                    $('.pres').html('<div id="empty"><p>No prescription added.</p></div>');
                }
            }
        });
    }
});

    function onPageLoadPrescribeItemLoad() {
        var session_id = $('#session_id').val();
        $('.pres').html('');
        $.ajax({
            type: 'POST',
            url: "{{URL('/get_prescribe_item_list')}}",
            data: {
                session_id: session_id
            },
            success: function (products) {

                if (products.length > 0) {
                    $.each(products, function (key, product) {
                        if (product.mode == 'medicine') {
                            meds++;
                            $('#hid_phar_id').attr('required', true);
                            $('.pres').append('<a href="javascript:void(0);" id="medicine ' + product.id +
                                '" class="list-group-item mt-1 rounded" style="border:solid red 1px;background-color:red;color:white"><div class="row col-12"><span class="col-8">' +
                                product.name +
                                '<br>' +
                                (product.usage == "nothing" ? '' : product.usage) +
                                '</span><span class="float-right col-3"><button class="aoesBTN btn btn-md" style="color: black;padding: 5px;background: white;" id="dosage_' +
                                product.id +
                                '" type="button" onclick="dosage(this)" data-toggle="modal" data-target="#add_med_modal">Add Dosage</button></span><span class="float-right col-1"><button type="button"' +
                                ' onclick="remove(this)" style="color:white" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></span>' +
                                '<span class="ml-3" style="color:white" id="' + product.id + '_time"></span></div></a>'
                            );
                        }
                        else if (product.mode == 'lab-test') {
                            if (product.aoes == 1) {
                                $('.pres').append('<a href="javascript:void(0);" id="lab-test ' + product.TEST_CD +
                                    '" class="list-group-item mt-1 rounded" style="border:solid #3a1f79e8 1px;background-color:#3a1f79e8;color:white"><div class="row col-12"><span class="col-8">' +
                                    product
                                        .DESCRIPTION +
                                    '</span><span class="float-right col-3"><button type="button" style="color: black;padding: 5px;background: white;"' +
                                    ' onclick="aoesModalOpen(this)" data-toggle="modal" data-target="#aoes_med_modal" class="aoesBTN btn btn-md ' + product.TEST_CD + ' ' + product.DESCRIPTION + ' "' +
                                    'aria-label="Close">Test AOES <span style="color:red;">*</span></button></span>' +
                                    '<span class="float-right col-1"><button type="button"  style="color:white" onclick="remove(this)" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></span>' +
                                    '<input id="' +
                                    product.TEST_CD +
                                    '_comment" name="' + product.TEST_CD + '_comment" hidden="" ></div></a>'
                                );
                            } else {
                                $('.pres').append('<a href="javascript:void(0);" id="lab-test ' + product.TEST_CD +
                                    '" class="list-group-item mt-1 rounded" style="border:solid #3a1f79e8 1px;background-color:#3a1f79e8;color:white"><div class="row col-12"><span class="col-8">' +
                                    product
                                        .DESCRIPTION +
                                    '</span><span class="float-right col-3"></span>' +
                                    '<span class="float-right col-1"><button type="button" style="color:white" onclick="remove(this)" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></span>' +
                                    '<input id="' +
                                    product.TEST_CD +
                                    '_comment" name="' + product.TEST_CD + '_comment" hidden="" ></div></a>'
                                );
                            }


                            lab_list.push(product.TEST_CD);
                            $('#lab_products').val(lab_list);
                        }
                        else if (product.mode == 'imaging') {
                            if (product.location == "nothing") {
                                $('.pres').append('<a href="javascript:void(0);" id="imaging ' + product.id +
                                    '" class="list-group-item mt-1 rounded " style="border:solid #f26202 1px;background-color:#f26202;color:white"><div class="row col-12"><span class="col-8">' + product.name +
                                    '</span><span class="float-right col-3"><button type="button" style="color: black;padding: 5px;background: white;"' +
                                    'onclick="zipcodeModalOpen(this)" data-toggle="modal" data-target="#add_imaging_zip_code_modal" class="aoesBTN btn btn-md ' + product.id + ' ' + product.name + ' "' +
                                    'aria-label="Close">Add Location<span style="color:red;">*</span></button></span>' +
                                    '<span class="float-right col-1"><button type="button" style="color:white" onclick="remove(this)" class="close" aria-label="Close">' +
                                    '<span aria-hidden="true">&times;</span></button></span>' +
                                    '</div></a>'
                                );

                            } else {
                                $('.pres').append('<a href="javascript:void(0);" id="imaging ' + product.id +
                                    '" class="list-group-item mt-1 rounded " style="border:solid #f26202 1px;background-color:#f26202;color:white"><div class="row col-12"><span class="col-8">' +
                                    product
                                        .name +
                                    '<br>Location:<span id="choosanLocation">' + product.location + '</span></span><span class="float-right col-3"><button type="button" style="color: black;padding: 5px;background: white;"' +
                                    'onclick="zipcodeModalOpen(this)" data-toggle="modal" data-target="#add_imaging_zip_code_modal" class="aoesBTN btn btn-md ' + product.id + ' ' + product.name + ' "' +
                                    'aria-label="Close">Add Location<span style="color:red;">*</span></button></span>' +
                                    '<span class="float-right col-1"><button type="button" style="color:white" onclick="remove(this)" class="close" aria-label="Close">' +
                                    '<span aria-hidden="true">&times;</span></button></span>' +
                                    '</div></a>'
                                );
                            }
                            prod_list.push(product.id);
                            $('#products').val(prod_list);
                        }
                        if (product.mode != 'imaging') {
                            prod_list.push(product.id);
                            $('#products').val(prod_list);
                        }
                    });
                } else {
                    $('.pres').html('<div id="empty"><p>No prescription added.</p></div>');
                }
            }
        });
    }
    function aoesModalOpen(a) {
        //$('#aoes_med_modal').modal('open');
        var session_id = $('#session_id').val();
        classes = $(a).attr('class');
        class_split = classes.split(' ');
        test_id = class_split[3];
        name_lab = class_split[4];

        $.ajax({
            type: 'POST',
            url: "{{URL('/get_lab_test_aoes_during_session')}}",
            data: {
                id: test_id,
                session_id: session_id,
            },
            success: function (aoes) {
                if (aoes == 'nothing') {
                    var res = '<tr><td>AOEs not found for ' + name_lab + '</td></tr>';
                    $('#load_aoes').html('');

                    $('#load_aoes').html(
                        '<h4>AOEs for ' + name_lab + '</h4>' +
                        '<div class="col-12">' +
                        '<div class="alert alert-danger" id="aoes_error" style="display:none;">All AOEs Answer Required</div>' +
                        '<div class="alert alert-success" id="aoes_suceess" style="display:none;"> AOEs Answer Submited Successfully</div>' +
                        '<form id="aoes_form">' +
                        '<div>' +
                        '<div class="table table-hover table-bordered mt-2" style="height: 500px;overflow-y: scroll;">' +
                        '<table class="col-12">' +
                        '<tr>' +
                        '<th>Question </th>' +
                        '<th>Answer </th>' +
                        '</tr>' +
                        '<tbody>'
                        + res +
                        '</tbody>' +
                        '</table>' +
                        '</div>' +
                        '</div>' +
                        '</form>' +
                        '</div>'
                    );
                }
                else {
                    if (aoes.answer == 1) {
                        var res = '';
                        $('#load_aoes').html('');
                        $.each(aoes.aoes, function (key, value) {
                            res += '<tr>' + '<td>' + value.question + '</td>' +
                                '<td><input class="' + value.TestCode + ' ' + value.ques_id + ' form-control" type="text" name="array[]" id="' + value.question + '" value="' + value.answer + '" placeholder="type your answer here" required></td>' +
                                '</tr>';
                        });
                        $('#load_aoes').html(
                            '<h4>AOEs for ' + name_lab + '</h4>' +
                            '<div class="col-12">' +
                            '<div class="alert alert-danger" id="aoes_error" style="display:none;">All AOEs Answer Required</div>' +
                            '<div class="alert alert-success" id="aoes_suceess" style="display:none;"> AOEs Answer Submited Successfully</div>' +
                            '<form id="aoes_form">' +
                            '<div>' +
                            '<div class="table table-hover table-bordered mt-2" style="height: 500px;overflow-y: scroll;">' +
                            '<table class="col-12">' +
                            '<tr>' +
                            '<th>Question </th>' +
                            '<th>Answer </th>' +
                            '</tr>' +
                            '<tbody>'
                            + res +
                            '</tbody>' +
                            '<input type="hidden" id="getTestCode" value="' + test_id + '">' +
                            '<tfoot>' +
                            '<tr>' +
                            '<td>' +
                            '<input class="btn btn-primary" type="button" value="Submit AOEs" style="color:white; background-color:#0069d9;" onClick="submitAoesDetailsForm()"></input>' +
                            '</td>' +
                            '</tr>' +
                            '</tfoot>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</form>' +
                            '</div>' +
                            '<p id="par"></p>'
                        );

                    } else {
                        var res = '';
                        $('#load_aoes').html('');
                        $.each(aoes.aoes, function (key, value) {
                            res += '<tr>' + '<td>' + value.QuestionLong + '</td>' +
                                '<td><input class="' + value.TestCode + ' ' + value.ques_id + ' form-control" type="text" name="array[]" id="' + value.QuestionLong + '" placeholder="type your answer here" required></td>' +
                                '</tr>';
                        });
                        $('#load_aoes').html(
                            '<h4>AOEs for ' + name_lab + '</h4>' +
                            '<div class="col-12">' +
                            '<div class="alert alert-danger" id="aoes_error" style="display:none;">All AOEs Answer Required</div>' +
                            '<div class="alert alert-success" id="aoes_suceess" style="display:none;"> AOEs Answer Submited Successfully</div>' +
                            '<form id="aoes_form">' +
                            '<div>' +
                            '<div class="table table-hover table-bordered mt-2" style="height: 500px;overflow-y: scroll;">' +
                            '<table class="col-12">' +
                            '<tr>' +
                            '<th>Question </th>' +
                            '<th>Answer </th>' +
                            '</tr>' +
                            '<tbody>'
                            + res +
                            '</tbody>' +
                            '<input type="hidden" id="getTestCode" value="' + test_id + '">' +
                            '<tfoot>' +
                            '<tr>' +
                            '<td>' +
                            '<input class="btn btn-primary" type="button" value="Submit AOEs" style="color:white; background-color:#0069d9;" onClick="submitAoesDetailsForm()"></input>' +
                            '</td>' +
                            '</tr>' +
                            '</tfoot>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</form>' +
                            '</div>' +
                            '<p id="par"></p>'
                        );
                    }
                }
            }
        });

    }
    function remove(a) {
        var session_id = $('#session_id').val();
        var user_id = {{ Auth:: user() -> id ?? '0'
    }};
    getID = $(a).parent().parent().parent().attr('id');
    id_split = getID.split(' ');
    type = id_split[0];
    pro_id = id_split[1];

    $('.prod_' + pro_id).prop('disabled', false);
    $('.prod_' + pro_id).text('Add');

    $.ajax({
        type: 'POST',
        url: "{{URL('/delete_prescribe_item_from_session')}}",
        data: {
            pro_id: pro_id,
            type: type,
            session_id: session_id,
            user_id: user_id
        },
        success: function (result) {
        }
    });
}
    function dosage(a) {
        $('#days').prop('selectedIndex', 0);
        $('#units').prop('selectedIndex', 0);
        $('#dose').prop('selectedIndex', 0);
        $('#instructions').val('');
        $('#med_current_price').val('');
        $('#med_id').val('');
        var session_id = $('#session_id').val();
        id = $(a).attr('id');
        med_id = id.split('dosage_');
        $.ajax({
            type: 'POST',
            url: '/get_med_detail',
            data: {
                id: med_id[1],
                session_id: session_id,
            },
            success: function (response) {
                var res = response.hasOwnProperty("update");
                if (res == true) {

                    var splitTime = response.update['time'].split("hrs");
                    if (splitTime[0] == 6) {
                        $('#dose').prop('selectedIndex', 1);
                    }
                    else if (splitTime[0] == 8) {
                        $('#dose').prop('selectedIndex', 2);
                    }
                    else if (splitTime[0] == 12) {
                        $('#dose').prop('selectedIndex', 3);
                    }
                    else if (splitTime[0] == 24) {
                        $('#dose').prop('selectedIndex', 4);
                    }


                    $('#med_name').text(response.product.name);

                    $('#instructions').val(response.update['comment']);

                    $('#med_current_price').val(response.product.sale_price);

                    $('#units').text('');
                    $('#units').append('<option value="">Choose Unit</option>');
                    $.each(response.units, function (key, value) {
                        $('#units').append('<option value="' + value.unit + '">' + value.unit + '</option>')
                    });
                    $('#units option[value=' + response.update['units'] + ']').attr('selected', 'selected');

                    $('#days').text('');
                    $('#days').append('<option value="">Choose Number of Days</option>')
                    $.each(response.days, function (key, value) {
                        $('#days').append('<option value="' + value.days + '">' + value.days + '</option>')
                    });
                    $('#days option[value="' + response.update['days'] + '"]').attr('selected', 'selected');

                }
                else {
                    $('#med_name').text(response.product.name);
                    $('#med_current_price').val(response.product.sale_price);

                    $('#units').text('');
                    $('#units').append('<option value="">Choose Unit</option>');
                    $.each(response.units, function (key, value) {
                        $('#units').append('<option value="' + value.unit + '">' + value.unit + '</option>')
                    });

                    $('#days').text('');
                    $('#days').append('<option value="">Choose Number of Days</option>')
                    $.each(response.days, function (key, value) {
                        $('#days').append('<option value="' + value.days + '">' + value.days + '</option>')
                    });
                }

            }
        });
        // console.log(med_id[1]);
        $('#med_id').val(med_id[1]);

    }
    function add_dosage() {
        var user_id = {{ Auth:: user() -> id ?? '0'
    }};
    var session_id = $('#session_id').val();
    var med_time = $('#dose').val();
    var days = $('#days').val();
    var units = $('#units').val();
    var inst = $('#instructions').val();
    var pro_id = $('#med_id').val();
    var price = $('#med_current_price').val();
    $.ajax({
        type: 'POST',
        url: '/add_dosage',
        data: {
            med_time: med_time + 'hrs',
            days: days,
            units: units,
            instructions: inst,
            pro_id: pro_id,
            user_id: user_id,
            price: price,
            session_id: session_id,
            usage: 'Dosage: Every ' + med_time + 'hrs for ' + days,
        },
        success: function (response) {
            if (response == "ok") {
                $('#add_med_modal').modal('hide');
                $("#dose").val("");
                $('#days').val('');
                $('#instructions').val('');
                $('#units').val('');
                $('#med_id').val('');
                $('#med_current_price').val();

            }
        }
    });

}


    function lab_comments(a) {
        var user_id = {{ Auth:: user() -> id ?? '0'
    }};
    id = $(a).attr('id');
    med_id = id.split('test_');
    $.ajax({
        type: 'POST',
        url: '/get_lab_details',
        data: {
            id: med_id[1],
            user_id: user_id,
        },
        success: function (product) {
            // console.log(product);
            $('#test_name').text(product.DESCRIPTION);

        }
    });
    // console.log(med_id[1]);
    $('#test_id').val(med_id[1]);
    //$('#lab_comment_modal').modal('open');

}
    function add_comment() {
        id = $('#test_id').val();
        comment = $('#comment').val();
        $('#' + id + '_comment').val(comment);
        $('#lab_comment_modal').modal('close');
        $('#comment').val('');

    }
    function load_report(e) {
        id = $(e).attr('id');
        id_sp = id.split('_');
        age = $('#report_age_' + id_sp[1]).val();
        rep_date = $('#report_date_' + id_sp[1]).val();
        rep_file = $('#report_file_' + id_sp[1]).val();
        $('#test_report_age').text(age);
        $('#test_report_date').text(rep_date);
        $('#test_report_file').attr('src', rep_file);
    }
    function select_imaging_location(a) {
        var user_id = {{ Auth:: user() -> id ?? '0'
    }};
    var session_id = $('#session_id').val();
    var pro_id = $("#imging_id_get").val();
    var location_id = $(a).attr('id');
    $.ajax({
        type: 'POST',
        url: "{{URL('/get_marker_by_id_imaging')}}",
        data: {
            location_id: location_id,
            pro_id: pro_id,
            user_id: user_id,
            session_id: session_id,
        },
        success: function (response) {
            $('#imaging_list').text('');
            $('#imging_id_get').val('');
            $('#add_imaging_zip_code_modal').modal('close');
        }
    });
}
    function getImagingLocationByZipCode(a) {
        var pro_id = $('#imging_id_get').val();
        var zip = $('#zip_code').val();
        var user_id = {{ Auth:: user() -> id ?? '0'
    }};
    $.ajax({
        type: 'POST',
        url: "{{URL('/get_locations_imaging')}}",
        data: {
            zip: zip,
            pro_id: pro_id,
            user_id: user_id,
        },
        success: function (response) {
            $('#imaging_list').text('');
            if (user_id == response.user_id) {
                if (response.locations == 'no location found') {
                    $('#imaging_list').html('<a href="javascript:void(0);" class="list-group-item "><p class=" text-uppercase">no location found<br></p></a>');
                }
                else {
                    $.each(response.locations, function (key, value) {
                        if (value.zip_code != zip) {
                            $('#imaging_list').append('<a href="javascript:void(0);" id=' + value.id + ' class="list-group-item" onclick="select_imaging_location(this)"><p>' +
                                value.clinic_name + '<br><small>' + value.city + ', <span class="text-uppercase">' + value.zip_code + '</span></small></p></a>');
                        }
                    });
                }
            }
            $('#imaging_list').show();
        }
    });
}
    $('#days').on('change', function () {
        day_id = $(this).val();
        unit_id = $('#units').val();
        product_id = $('#med_id').val();
        if (day_id != '' && unit_id != '') {
            $.ajax({
                type: 'POST',
                url: "{{URL('/get_medicine_price')}}",
                data: {
                    product_id: product_id,
                    day_id: day_id,
                    unit_id: unit_id,
                },
                success: function (response) {
                    console.log(response)
                    $('#' + product_id + '_med_price').val(response.sale_price);
                }
            })
        }
    });
    $('#units').on('change', function () {
        day_id = $('#days').val();
        unit_id = $('#units').val();
        product_id = $('#med_id').val();
        if (day_id != '' && unit_id != '') {
            $.ajax({
                type: 'POST',
                url: "{{URL('/get_medicine_price')}}",
                data: {
                    product_id: product_id,
                    day_id: day_id,
                    unit_id: unit_id,
                },
                success: function (response) {
                    console.log(response)
                    $('#' + product_id + '_med_price').val(response.sale_price);
                }
            })
        }
    });
    $('.test_reports').on('click', function () {
        // console.log('jhj');
        $('#test_report_modal').show();
    });
    $("#search_phar").keyup(function () {
        var rex = new RegExp($(this).val(), 'i');
        $('#table_phar tr').hide();
        //Recusively filter the jquery object to get results.
        $('#table_phar tr ').filter(function (i, v) {
            //Get the 3rd column object here which is userNamecolumn
            var $t = $(this).children(":eq(" + "0" + ")");
            return rex.test($t.text());
        }).show();
    });
    $("#search_lab").keyup(function () {
        var rex = new RegExp($(this).val(), 'i');
        $('#table_lab tr').hide();
        //Recusively filter the jquery object to get results.
        $('#table_lab tr ').filter(function (i, v) {
            //Get the 3rd column object here which is userNamecolumn
            var $t = $(this).children(":eq(" + "0" + ")");
            return rex.test($t.text());
        }).show();
    });
    $("#search_imaging").keyup(function () {
        var rex = new RegExp($(this).val(), 'i');
        $('#table_imaging tr').hide();
        //Recusively filter the jquery object to get results.
        $('#table_imaging tr ').filter(function (i, v) {
            //Get the 3rd column object here which is userNamecolumn
            var $t = $(this).children(":eq(" + "0" + ")");
            return rex.test($t.text());
        }).show();
    });
    $('#close_add_phar').click(function () {
        $('#add_phar_modal').modal('close');
    });
    $(".pharmacy").click(function () {
        // text=$(this).html();
        id = $(this).attr('id');
        // console.log(id);
        $.ajax({
            type: 'POST',
            url: "{{URL('/get_marker_by_id')}}",
            data: {
                id: id,
            },
            success: function (response) {
                // console.log(response);
                $('#phar_name').text('Pharmacy: ' + response.address + ' ,' + response.name);
                $('#hid_phar_id').val(response.id);
                $('#add_phar_modal').modal('close');

            }
        })
    });
    $('.img_categories').click(function (a) {
        var session_id = $('#session_id').val();
        id = $(this).data("id");
        $.ajax({
            type: 'POST',
            url: "{{URL('/get_products_by_category')}}",
            data: {
                id: id,
                session_id: session_id,
                type: 'imaging',
            },
            success: function (response) {
                $('#img_categories').hide();
                $('#img_tbl').text('');
                if (response.length != 0) {

                    $.each(response, function (key, value) {
                        if (value.added == 'yes') {
                            $('#img_tbl').append('<tr><td>' + value.name +
                                '</td><td><button onclick="add_med(this)" ' +
                                'style="color:white;cursor: pointer;background-color:#0069d9;cursor: pointer;"class="btn btn-primary ' +
                                value.id + ' prod_' + value.id + ' img" disabled>Added</button></td></tr>');
                        } else {
                            $('#img_tbl').append('<tr><td>' + value.name +
                                '</td><td><button onclick="add_med(this)" ' +
                                'style="color:white;cursor: pointer; background-color:#0069d9;"class="btn btn-primary ' +
                                value.id + ' prod_' + value.id + ' img">Add</button></td></tr>');
                        }

                    });
                } else {
                    $('#img_tbl').append(
                        '<tr><td colspan="2"><center>No services in this category</center></td></tr>'
                    );
                }
                $('#img_products').show();
            }
        });
    });
    $('#back_btn').click(function () {
        $('#img_categories').show();
        $('#img_products').hide();

    });
    $('.img_report').click(function (a) {
        // console.log($(this).data("id"));
        id = $(this).data("id");
        $.ajax({
            type: 'POST',
            url: "{{URL('/get_img_report')}}",
            data: {
                id: id,
            },
            success: function (response) {
                $('#img_report_date').text(response.updated_at);
                $('#img_report_file').attr('src', response.report);
            }
        });
    });
    $('.med_categories').click(function (a) {
        // console.log($(this).data("id"));
        var session_id = $('#session_id').val();
        id = $(this).data("id");
        $.ajax({
            type: 'POST',
            url: "{{URL('/get_products_by_category')}}",
            data: {
                id: id,
                session_id: session_id,
                type: 'medicine',
            },
            success: function (response) {
                // console.log(response);
                $('#med_categories').hide();
                $('#med_tbl').text('');
                if (response.length != 0) {
                    $.each(response, function (key, value) {
                        if (value.added == 'yes') {
                            $('#med_tbl').append('<tr><td>' + value.name +
                                '</td><td><a onclick="add_med(this)" ' +
                                'style="color:white; background-color:#0069d9;cursor: pointer;"class="btn btn-primary ' +
                                value.id + ' prod_' + value.id + ' med" disabled>Added</a></td></tr>');
                        } else {
                            $('#med_tbl').append('<tr><td>' + value.name +
                                '</td><td><a onclick="add_med(this)" ' +
                                'style="color:white; background-color:#0069d9;cursor: pointer;"class="btn btn-primary ' +
                                value.id + ' prod_' + value.id + ' med">Add</a></td></tr>');
                        }


                    });
                } else {
                    $('#img_tbl').append(
                        '<tr><td colspan="2"><center>No products in this category</center></td></tr>'
                    );
                }
                $('#med_products').show();
            }
        });
    });
    $('#med_back_btn').click(function () {
        $('#med_categories').show();
        $('#med_products').hide();

    });



</script>
@endsection
