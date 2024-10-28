{{-- {{dd($patUser)}} --}}
@extends('video.layout')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<style>
    @keyframes blinkingText {
        0% {
            color: red;
        }

        49% {
            color: transparent;
        }

        50% {
            color: transparent;
        }

        99% {
            color: transparent;
        }

        100% {
            color: red;
        }
    }

    .bold {
        font-weight: bold;
    }

    .big {
        font-size: 20px;
        color: white;
    }

    .modal-header {
        background-color: #08295a !important;
    }

    button:hover {
        color: white !important;
    }
</style>
@endsection
@section('content')
@php
if(isset($_GET['session']))
$id=$_GET['session'];
@endphp
<nav class="navbar navbar-expand-sm justify-content-between"
    style=" background:linear-gradient(45deg, #08295a , #5e94e4);height:55px">
    <!-- <a class="navbar-brand " href="#">Video Session
  </a> -->
    <ul class="nav navbar-nav" id="clock_div" style="color:#fff;font-size:24px;font-weight:bold">
        <li><span id="clock" class="ml-5 mt-2 mr-5 round"></span></li>
    </ul>
    <input hidden id="time">
    <input hidden id="min_flag" hidden value="{{$getSession['less_min_flag']}}">
    @if($user_type=='doctor')
    <div class="form-inline float-right col-md-8">
        <button id="pharmcay_btn" class="mr-2 ml-2 col-md-3 btn ui red button" data-toggle="modal"
            data-target="#pharmacyModal"> Medicines</button>
        <button id="lab_btn" class="mr-2 ml-2 col-md-3 btn ui purple button" data-toggle="modal"
            data-target="#labModal">Lab</button>
        <button id="img_btn" class="mr-2 ml-2 col-md-3 btn ui orange button" data-toggle="modal"
            data-target="#imagingModal">Imaging</button>
    </div>
    @else
    <div class="form-inline float-right col-md-6">
        <span class="bold big">Doctor Name:</span><span class="col-md-2 big">{{ucwords($docUser->name.'
            '.$docUser->last_name)}}</span>
        <span class="bold big">Speciality:</span><span class="col-md-2 big">
            @if($docUser->specialization=='None')
            General
            @else
            {{ucwords($docUser->specialization)}}
            @endif
        </span>
        <!-- <span class="bold big">Education:</span><span class="col-md-2 big"> M.B.B.S</span> -->
    </div>
    @endif
</nav>
<!-- <section class="content"> -->
<div class="container-fluid pt-1">
    <div class="block-header">
        <input hidden value="{{$getSession->remaining_time}}" id="session_rem_time">
        <!-- <h2>Doctor Session</h2> -->
        <div class="row clearfix">
            @if($user_type=='patient')
            @include('video.patient_video_page')
        </div>
    </div>
    @elseif($user_type=='doctor')
    <div class="checkout_loader">Loading&#8230;</div>

    @include('video.doctor_video_page')


</div>
@endif
</div>
<!-- </section> -->
<!-- Lab Test Report Modal -->
<div class="modal fade" id="test_report_modal" style="font-weight: normal; " tabindex="-1" role="dialog" width="">
    <div class="modal-dialog modal-lg" role="document" style=" max-width: 80% !important;overflow-y:auto;height:700px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Test Report</h4>
                <button class="btn btn-default btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:white;font-size:22px" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body row">
                <div class="row col-12">
                    <h2 class="col-3 ml-1">Age: <span id="test_report_age"></span></h2>
                    <h2 class="col-3 mt-0 float-right">Date: <span id="test_report_date"></span></h2>
                </div>
                <!-- <input class="form-control" placeholder="Search Pharmacy.." onkeyup="showResult(this.value)"> -->
                <div class="col-md-12">
                    <embed id="test_report_file" width="1200px" height="700px" />
                    <!-- <img id="test_report_file" width="100%" height="100%"> -->
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Imaging Report Modal -->
<div class="modal fade" id="img_report_modal" style="font-weight: normal; " tabindex="-1" role="dialog" width="">
    <div class="modal-dialog modal-lg" role="document" style=" max-width: 80% !important;overflow-y:auto;height:700px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Imaging Report</h4>
                <button class="btn btn-default btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:white;font-size:22px" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body row">
                <div class="row col-12">
                    <!-- <h2 class="col-3 mt-0 float-right">Date: <span id="img_report_date"></span></h2> -->
                </div>
                <!-- <input class="form-control" placeholder="Search Pharmacy.." onkeyup="showResult(this.value)"> -->
                <div class="col-md-12">
                    <!-- <img id="img_report_file" width="100%" height="100%"> -->
                    <embed id="img_report_file" width="1200px" height="700px" />
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Session Details Modal -->
<div class="modal fade" id="session_modal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="symp">Session Details</h4>
                <button id="session_close_btn"
                    class="btn btn-default btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:white;font-size:22px" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body row">
                <div class="row col-12">
                    <h6 class="col-6 ml-3">Provider: <span id="prev_session_doc"></span> </h6>
                    <h6 class="col-4">Dated: <span id="prev_session_date"></span></h6>
                </div>
                <div class="row col-12">
                    <h6 class="col-6 ml-3">Start Time: <span id="prev_session_start_time"></span> </h6>
                    <h6 class="col-4">End Time: <span id="prev_session_end_time"></span></h6>
                </div>
                <div class="container pt-2">
                    <h5 class="col-6 mb-0">Symptoms</h5>
                    <div class="col-12" id="prev_session_symps_list">
                    </div>
                </div>
                <div class="container pt-2">
                    <h5 class="col-6 mb-0">Diagnosis</h5><span class="col-6" id="prev_session_diagnosis"></span>
                </div>
                <div class="container pt-2">
                    <h5 class="col-12 mb-0">Prescriptions</h5>
                    <div class="col-12 col-lg-12" id="prev_session_meds">
                    </div>
                </div>
                <div class="container pt-2">
                    <h5 class="col-6 mb-0">Provider Notes</h5>
                    <p class="col-12" id="prev_session_notes"></p>
                </div>
            </div>
        </div>
    </div>
</div>
@if($user_type=='doctor')
<!-- Pharmacy Modal -->
<div class="modal fade" id="pharmacyModal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="max-width:1100px !important" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-2" style="font-size:28px;color:red" id="pharmacy">Pharmacy</h4>
                <button class="btn btn-default btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:white;font-size:22px" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <!-- <input class="form-control" placeholder="Search Pharmacy.." onkeyup="showResult(this.value)"> -->
                <div class="col-6" style="overflow-y:auto;height:500px">
                    <div id="med_categories">
                        <h3>Pharmacy Products<br><small>Select Category</small></h3>
                        <div class="row col-12">
                            @foreach($medicines['category'] as $cat)
                            <a data-id="{{$cat->id}}" class="med_categories col-6">
                                <div class="jumbotron p-3 text-center col-12" style="cursor: pointer;">
                                    {{$cat->title}}
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    <div id="med_products">
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
                                    {{--@foreach($medicines as $product)
                                    <tr>
                                        <td>{{$product->name}}</td>
                                        <td><button onclick="add_med(this)"
                                                style="color:white; background-color:#0069d9;"
                                                class="btn btn-primary {{$product->id}} prod_{{$product->id}}">Add</button>
                                        </td>
                                    </tr>
                                    @endforeach--}}
                                </tbody>
                            </table>
                        </div>
                        <div id="search_res_phar"></div>
                    </div>
                </div>
                <div class="col-6 float-right" style="overflow-y:auto;height:500px">
                    <h4>Current Medications</h4>
                    @forelse($patient_meds as $pr)
                    <div class="sbox-7 icon-xs wow " style="padding:10px 2px;margin:5px 10px" data-wow-delay="0.4s">
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
                    <div class="sbox-7 icon-xs wow " style="padding:10px 2px;margin:5px 10px" data-wow-delay="0.4s">
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
    <div class="modal-dialog modal-xl" style="margin-top:5%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-2" style="font-size:28px;color:#3a1f79e8">Lab</h4>
                <button id="close_lab" class="btn btn-default btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:white;font-size:22px" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <div class="col-6">
                    <!-- <input class="form-control" placeholder="Search Pharmacy.." onkeyup="showResult(this.value)"> -->
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
                                                style="color:white; background-color:#0069d9;"
                                                class="btn btn-primary {{$lab->TEST_CD}} prod_{{$lab->TEST_CD}} {{$lab->DESCRIPTION}} {{$getSession->id}}"
                                                disabled>Added
                                            </button>


                                            @else
                                            <button onclick="add_lab(this)"
                                                style="color:white; background-color:#0069d9;"
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
                                    <input id="report_age_{{$pr->id}}" hidden value="{{$pat_age}}">
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
    <div class="modal-dialog modal-lg" style="margin-top:5%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-2" style="font-size:28px;color:orange">Imaging</h4>
                <button id="close_img" class="btn btn-default btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:white;font-size:22px" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body row">
                <div class="col-6">
                    <!-- <input class="form-control" placeholder="Search Pharmacy.." onkeyup="showResult(this.value)"> -->
                    <div id="img_categories">
                        <h3>Imaging Services<br><small>Select Category</small></h3>
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
                                    {{--@foreach($imaging as $img)
                                    <tr>
                                        <td>{{$img->name}}</td>
                                        <td><button onclick="add_med(this)"
                                                style="color:white; background-color:#0069d9;"
                                                class="btn btn-primary {{$img->id}} prod_{{$img->id}}">Add</button></td>
                                    </tr>
                                    @endforeach--}}
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
                    style="color:white;font-size:22px" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <div class="container ">
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
                        style="color:white;background-color:#0069d9;">Done</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- aoesmodal -->
<div class="modal fade m-5 p-5" id="aoes_med_modal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="med_name"></h4>
                <button id="aoe_close_dose"
                    class="btn btn-default p-1 btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:white;font-size:22px" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <div class="col-12 float-right" id="load_aoes">

                </div>
            </div>
        </div>
    </div>
</div>




<!-- Zip code -->
<div class="modal fade m-2 p-5" id="add_imaging_zip_code_modal" style="font-weight: normal; " tabindex="-1"
    role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Zip Code To Nearest Imaging Location</h4>
                <button id="close_add_imaging_zip_code_modal"
                    class="btn btn-default p-1 btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:white;font-size:22px" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <div class="container" style="overflow-y:auto;height:450px; ">
                    <h5>Zip Code</h5>
                    <div class="row clearfix">
                        <div class="row col-12 pl-5">
                            <input id="zip_code" class="form-control col-11" placeholder="Add Zip Code Here..">
                            <button onclick="getImagingLocationByZipCode(this)" class="col-1 btn px-1 py-1"
                                style="color:black;font-size:24px">
                                <i class="fa fa-caret-right"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="imging_id_get">
                    <div id="imging_message"></div>
                    <div id="imaging_list" class="p-2">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- refer modal -->
<div class="modal fade m-2 p-5" id="refer_doc_modal" style="font-weight: normal; " tabindex="-1" role="dialog"
    style="z-index: 998 !important;">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="rf_title">Refer</h4>
                <button class="btn btn-default p-1 btn-circle waves-effect waves-circle waves-float p-0"
                    style="color:white;font-size:22px; background-color:#0069d9;" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <div class="container" style="overflow-y:auto;height:500px; ">
                    <!-- <h5>Zip Code</h5> -->

                    <div id="rf_list" class="p-2">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default " data-dismiss="modal" style="color:grey">Close</button>
            </div>
        </div>
    </div>
</div>
</div>
@endif
@endsection
@section('script')
<script src="{{ asset('/js/app.js') }}"></script>
<script src="{{ asset('asset_admin/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{asset('asset_admin/js/pages/ui/modals.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    CKEDITOR.replace('notes');
    CKEDITOR.editorConfig = function (config) { };
</script>
<script type="text/javascript">
    @php header("Access-Control-Allow-Origin: *"); @endphp
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.modal').modal({
        dismissible: true //only closes when click on close button if false
    });

    var prod_list = [];
    var lab_list = [];
    var meds = 0;
    var time;

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
                $('#empty').hide();
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
                            '" type="button" onclick="dosage(this)" >Add Dosage</button></span><span class="float-right col-1"><button type="button"' +
                            ' onclick="remove(this)" style="color:white" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            '</span><span class="ml-3" style="color:white" id="' +
                            product.id + '_time"></span></div></a>'
                        );
                    }
                    else if (product.mode == 'lab-test') {
                        if (product.aoes == 1) {
                            $('.pres').append('<a href="javascript:void(0);" id="lab-test ' + product.TEST_CD +
                                '" class="list-group-item mt-1 rounded" style="border:solid #3a1f79e8 1px;background-color:#3a1f79e8;color:white"><div class="row col-12"><span class="col-8">' +
                                product
                                    .DESCRIPTION +
                                '</span><span class="float-right col-3"><button type="button" style="color: black;padding: 5px;background: white;"' +
                                ' onclick="aoesModalOpen(this)" class="aoesBTN btn btn-md ' + product.TEST_CD + ' ' + product.DESCRIPTION + ' "' +
                                'aria-label="Close">Test AOES <span style="color:red;">*</span></button></span>' +
                                '<span class="float-right col-1"><button type="button" style="color:white" onclick="remove(this)" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></span>' +
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
                                'onclick="zipcodeModalOpen(this)" class="aoesBTN btn btn-md ' + product.id + ' ' + product.name + ' "' +
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
                                'onclick="zipcodeModalOpen(this)" class="aoesBTN btn btn-md ' + product.id + ' ' + product.name + ' "' +
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
                $('#empty').hide();
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
                            '" type="button" onclick="dosage(this)" >Add Dosage</button></span><span class="float-right col-1"><button type="button"' +
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
                                ' onclick="aoesModalOpen(this)" class="aoesBTN btn btn-md ' + product.TEST_CD + ' ' + product.DESCRIPTION + ' "' +
                                'aria-label="Close">Test AOES <span style="color:red;">*</span></button></span>' +
                                '<span class="float-right col-1"><button type="button" style="color:white" onclick="remove(this)" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></span>' +
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
                                'onclick="zipcodeModalOpen(this)" class="aoesBTN btn btn-md ' + product.id + ' ' + product.name + ' "' +
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
                                'onclick="zipcodeModalOpen(this)" class="aoesBTN btn btn-md ' + product.id + ' ' + product.name + ' "' +
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
            }
        });
    }
    function aoesModalOpen(a) {
        $('#aoes_med_modal').modal('open');
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

                    }
                    else {
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
    $(document).ready(function () {
        // $("#end_meeting").hide();
        // $("#exit_meeting_div").hide();
        $("#img_products").hide();
        $('#imaging_msg').hide();
        $("#med_products").hide();
        $('#imaging_list').hide();
        $(".checkout_loader").hide();
        //if doc video page then get patient id by session id
        var doc_id = $('#doc_id').val();
        var id = $('#session_id').val();

        $.ajax({
            type: 'POST',
            url: '/set_session_start_time',
            data: {
                id: id,
            },
            success: function (session) {
                console.log(session);
            }
        });

        if (doc_id != 'null' || doc_id != '') {
            $.ajax({
                type: 'POST',
                url: '/get_patient_id',
                data: {
                    id: id,
                },
                success: function (session) {
                    $('#pat_id').val(session.patient_id);
                    checkstatus();
                }
            });
        }
        rem_time = $('#session_rem_time').val();
        if (rem_time == 'full') {
            var sec = 15 * 60;
        } else {
            time = rem_time;
            time_sp = time.split('m');
            min = time_sp[0];
            min = min * 60;
            // console.log(min);
            sec_sp = time_sp[1].split(": ");
            // console.log(sec_sp);
            sec_split = sec_sp[1].split('s');
            // console.log(sec_split);
            sec = parseInt(sec_split[0]) + parseInt(min);
        }
        CountDown(sec);
        onPageLoadPrescribeItemLoad();

    });
    function add_med(a) {
        var session_id = $('#session_id').val();
        var user_id = {{ Auth:: user() -> id ?? '0'
    }};
    classes = $(a).attr('class');
    $(a).prop('disabled', 'true');
    $(a).text('Added');
    class_split = classes.split(' ');
    id = class_split[2];
    type = class_split[4];
    // console.log(id);
    $.ajax({
        type: 'POST',
        url: "{{URL('/get_product_details')}}",
        data: {
            id: id,
            type: type,
            session_id: session_id,
            user_id: user_id,
        },
        success: function (product) {
        }
    });
}
    function add_lab(a) {
        var session_id = $('#session_id').val();
        var user_id = {{ Auth:: user() -> id ?? '0'
    }};
    classes = $(a).attr('class');
    class_split = classes.split(' ');
    id = class_split[2];
    name_lab = class_split[4];

    $(a).prop('disabled', 'true');
    $(a).text('Added');
    $.ajax({
        type: 'POST',
        url: "{{URL('/get_lab_details')}}",
        data: {
            id: id,
            session_id: session_id,
            user_id: user_id,
        },
        success: function (product) {

        }
    });
}
    function submitAoesDetailsForm() {
        var session_id_aoe = $('#session_id').val();
        var getTestCode = $('#getTestCode').val();

        var input = document.getElementsByName('array[]');

        var inputValue = [];
        var geterror = 0;
        for (var i = 0; i < input.length; i++) {
            var a = input[i];

            if (a.value == "") {
                geterror = 1;
                $('#aoes_error').css('display', 'block');
            }
            else {
                var allClassesName = a.className;
                var classesName = allClassesName.split(" ");
                $('#aoes_error').css('display', 'none');
                var newItems = [{ 'test_cd': classesName[0], 'ques_id': classesName[1], 'ques': a.id, 'ans': a.value }];
                inputValue.push(...newItems);
            }
        }

        if (geterror == 0) {
            $.ajax({
                type: 'POST',
                url: "{{URL('/add_labtest_aoes_into_db')}}",
                data: {
                    session_id: session_id_aoe,
                    inputValue: inputValue,
                    getTestCode: getTestCode,
                },
                success: function (res) {

                    var res = '<tr><td><div class="alert alert-success"> AOEs Answer Submited Successfully</div></td></tr>';
                    $('#load_aoes').html('');

                    $('#load_aoes').html(
                        '<div class="col-12">' +
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
                        '</div>'
                    );
                }
            });
        }
    }
    function zipcodeModalOpen(a) {
        $('#add_imaging_zip_code_modal').modal('open');
        classes = $(a).attr('class');
        class_split = classes.split(' ');
        imging_id = class_split[3];
        $('#imging_id_get').val(imging_id);

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

                } else {
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
        $('#add_med_modal').modal('open');
        // $('#session_modal').show();
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
            user_id: user_id,
            instructions: inst,
            pro_id: pro_id,
            price: price,
            session_id: session_id,
            usage: 'Dosage: Every ' + med_time + 'hrs for ' + days,
        },
        success: function (response) {
            if (response == "ok") {
                $('#add_med_modal').modal('close');
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
    $('#lab_comment_modal').modal('open');

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
    function CountDown(duration) {
        session_id = $('#session_id_pat').val();
        if (!isNaN(duration)) {
            session = $('#session_id_pat').val();
            var timer = duration,
                minutes, seconds;
            var interVal = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                $('#time').val(minutes + " minutes : " + seconds + " seconds");
                $('#clock').text(minutes + " minutes : " + seconds + " seconds");
                --timer;
                if (timer < 20) {
                    $('#clock_div').css({
                        "animation": "blinkingText 0.8s infinite"
                    });
                }
                if (timer < 0) {
                    timer = duration;
                    time = null;
                    var doc_id = $('#doc_id').val();
                    if (doc_id != null || doc_id != '') {
                        session = $('#session_id').val();
                        $.ajax({
                            type: 'POST',
                            url: "{{url('/doctor_end_session')}}",
                            data: {
                                id: session,
                            },
                            success: function (msg) {
                                $("#recommendationForm").submit();
                            }
                        });
                    }
                    var patient_id = $('#pat_id').val();
                    if (patient_id != null || patient_id != '') {
                        session = $('#session_id_pat').val();
                        $.ajax({
                            type: 'POST',
                            url: "{{url('/doctor_end_session')}}",
                            data: {
                                id: session
                            },
                            success: function (msg) {
                                localStorage.setItem("time", 'null');
                                window.location = "{{ url('waiting_page/')}}/" + session;
                            }
                        });
                    }
                    $('#clock').hide();
                }
            }, 1000);
        }
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
                    $('#imaging_list').html('<a href="javascript:void(0);" class="list-group-item"><p class="text-uppercase">no location found.<br></p></a>');
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
    $('#end_session').click(function () {
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
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to end this session and submit prescription",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'No',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: 'POST',
                                url: '/doctor_end_session',
                                data: {
                                    id: id,
                                },
                                success: function (status) {
                                    // console.log(status);
                                    endCall();
                                    leave();
                                    $("#recommendationForm").submit();
                                }
                            });
                        }
                    });
                }

            }
        });
    });
    $('#close_dose').click(function () {
        $('#add_med_modal').modal('close');
    });
    $('#aoe_close_dose').click(function () {
        $('#aoes_med_modal').modal('close');
    });
    $('#add_phar_btn').click(function () {
        $('#add_phar_modal').modal('open');
    });
    $('#close_add_imaging_zip_code_modal').click(function () {
        $('#add_imaging_zip_code_modal').modal('close');
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
                                'style="color:white; background-color:#0069d9;"class="btn btn-primary ' +
                                value.id + ' prod_' + value.id + ' img" disabled>Added</button></td></tr>');
                        } else {
                            $('#img_tbl').append('<tr><td>' + value.name +
                                '</td><td><button onclick="add_med(this)" ' +
                                'style="color:white; background-color:#0069d9;"class="btn btn-primary ' +
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
                                '</td><td><button onclick="add_med(this)" ' +
                                'style="color:white; background-color:#0069d9;"class="btn btn-primary ' +
                                value.id + ' prod_' + value.id + ' med" disabled>Added</button></td></tr>');
                        } else {
                            $('#med_tbl').append('<tr><td>' + value.name +
                                '</td><td><button onclick="add_med(this)" ' +
                                'style="color:white; background-color:#0069d9;"class="btn btn-primary ' +
                                value.id + ' prod_' + value.id + ' med">Add</button></td></tr>');
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
    function checkstatus() {
        var session_id = $('#session_id_pat').val();
        var user_type = '<?php echo $user_type;?>';
        if (user_type == "patient") {

            timer = setInterval(function () {
                time = $('#time').val();
                $.ajax({
                    type: "POST",
                    url: "{{URL('/check_session_video_status')}}",
                    data: {
                        session_id: session_id,
                        time: time,
                    },
                    success: function (message) {
                        if (message == 'ended') {
                            window.location.href = "/waiting/page/" + session_id;
                        }
                    }
                });
            }, 3000);
        }
    }
    function getSpecializedDoctors(a) {
        id = $(a).attr('id');
        id_sp = id.split('spec_');
        id = id_sp[1];
        text = $(a).children().children().attr('spec_name');
        // console.log(text);
        if (($('#doc_id').val()) != null) {
            session = $('#session_id').val();
        } else {
            session = $('#session_id_pat').val();
        }
        $.ajax({
            type: "POST",
            url: "{{URL('/getSpecializedDoctors')}}",
            data: {
                session: session,
                id: id,
            },
            success: function (doctors) {
                $('#rf_list').text('');
                $('#rf_title').text(text);
                if (doctors[0].length != 0) {
                    $.each(doctors[0], function (key, value) {

                        if (doctors[1] == 1) {
                            if (value.refered) {
                                $('#rf_list').append('<a href="javascript:void(0);"' +
                                    'class="list-group-item sp_doc"><div class="row"><img src="' + value.user_image + '" class="col-4 rounded-circle" height="80">' +
                                    '<div class="col-8"><p style="margin-bottom: 0px;" class="col-12 p-0">' +
                                    value.name + ' ' + value.last_name +
                                    '</p><p style="" class="col-12 p-0">NPI: ' + value.nip_number + '</p>' +
                                    '<button style="color:white; background-color:red;" onclick="cancelReferal(this)" id="referID_' + value.refer_id + '" class="btn btn-primary ml-1">Cancel the Referral</button>' +
                                    '</div></div></a>'
                                );
                            }
                            else {
                                $('#rf_list').append('<a href="javascript:void(0);"' +
                                    'class="list-group-item sp_doc"><div class="row"><img src="' + value.user_image + '" class="col-4 rounded-circle" height="80">' +
                                    '<div class="col-8"><p style="margin-bottom: 0px;" class="col-12 p-0">' +
                                    value.name + ' ' + value.last_name +
                                    '</p><p style="" class="col-12 p-0">NPI: ' + value.nip_number +
                                    '</p><button style="color:white;background-color:#0069d9;" id="sp_doc_' + value.id + '" class="btn btn-primary" disabled>Refer</button></div></div></a>'
                                );
                            }

                        }
                        else {
                            $('#rf_list').append('<a href="javascript:void(0);"' +
                                'class="list-group-item sp_doc"><div class="row"><img src="' + value.user_image + '" class="col-4 rounded-circle" height="80">' +
                                '<div class="col-8"><p style="margin-bottom: 0px;" class="col-12 p-0">' +
                                value.name + ' ' + value.last_name +
                                '</p><p style="" class="col-12 p-0">NPI: ' + value.nip_number + '</p><input placeholder="Short comment" class="form-control mb-2" id="referal_comment_' + value.id +
                                '">' +
                                '<button style="color:white;background-color:#0069d9;" id="sp_doc_' + value.id + '" class="btn btn-primary" onclick="sendReferal(this)" >Refer</button></div></div></a>'
                            );
                        }

                    })
                } else {
                    $('#rf_list').append('<a href="javascript:void(0);"' + 'class="list-group-item sp_doc">No doctors of this specialization available</a>');
                }
            }
        });
    }
    function cancelReferal(b) {
        id = $(b).attr('id');
        id_ref = id.split("referID_");
        id = id_ref[1];
        $.ajax({
            type: "POST",
            url: "{{URL('/cancelReferal')}}",
            data: {
                id: id
            },
            beforeSend: function () {
                $(".checkout_loader").show();
            },
            success: function (doctors) {
                $(".checkout_loader").hide();
                $('#rf_list').text('');
                $('#rf_title').text(text);
                if (doctors[0].length != 0) {
                    $.each(doctors[0], function (key, value) {
                        if (doctors[1] == 1) {
                            if (value.refered) {
                                $('#rf_list').append('<a href="javascript:void(0);"' +
                                    'class="list-group-item sp_doc"><div class="row"><img src="' + value.user_image + '" class="col-4 rounded-circle" height="80">' +
                                    '<div class="col-8"><p style="margin-bottom: 0px;" class="col-12 p-0">' +
                                    value.name + ' ' + value.last_name +
                                    '</p><p style="" class="col-12 p-0">NPI: ' + value.nip_number + '</p>' +
                                    '<button style="color:white; background-color:red;" onclick="cancelReferal(this)" id="referID_' + value.refer_id + '" class="btn btn-primary ml-1">Cancel the Referral</button>' +
                                    '</div></div></a>'
                                );
                            }
                            else {
                                $('#rf_list').append('<a href="javascript:void(0);"' +
                                    'class="list-group-item sp_doc"><div class="row"><img src="' + value.user_image + '" class="col-4 rounded-circle" height="80">' +
                                    '<div class="col-8"><p style="margin-bottom: 0px;" class="col-12 p-0">' +
                                    value.name + ' ' + value.last_name +
                                    '</p><p style="" class="col-12 p-0">NPI: ' + value.nip_number +
                                    '</p><button style="color:white;background-color:#0069d9;" id="sp_doc_' + value.id + '" class="btn btn-primary" disabled>Refer</button></div></div></a>'
                                );
                            }

                        }
                        else {
                            $('#rf_list').append('<a href="javascript:void(0);"' +
                                'class="list-group-item sp_doc"><div class="row"><img src="' + value.user_image + '" class="col-4 rounded-circle" height="80">' +
                                '<div class="col-8"><p style="margin-bottom: 0px;" class="col-12 p-0">' +
                                value.name + ' ' + value.last_name +
                                '</p><p style="" class="col-12 p-0">NPI: ' + value.nip_number + '</p><input placeholder="Short comment" class="form-control mb-2" id="referal_comment_' + value.id +
                                '">' +
                                '<button style="color:white;background-color:#0069d9;" id="sp_doc_' + value.id + '" class="btn btn-primary" onclick="sendReferal(this)" >Refer</button></div></div></a>'
                            );
                        }

                    })
                } else {
                    $('#rf_list').append('<a href="javascript:void(0);"' + ' class="list-group-item sp_doc">No doctors of this specialization available</a>');
                }
            }
        });
    }
    function sendReferal(a) {
        id = $(a).attr('id');
        id_sp = id.split("sp_doc_");
        id = id_sp[1];
        comment = $('#referal_comment_' + id).val();
        if (($('#doc_id').val()) != null) {
            session = $('#session_id').val();
        } else {
            session = $('#session_id_pat').val();
        }
        $.ajax({
            type: "POST",
            url: "{{URL('/sendReferal')}}",
            data: {
                id: id,
                session: session,
                comment: comment
            },
            beforeSend: function () {
                $(".checkout_loader").show();
            },
            success: function (doctors) {
                $(".checkout_loader").hide();
                $('#rf_list').text('');
                $('#rf_title').text(text);
                if (doctors[0].length != 0) {
                    $.each(doctors[0], function (key, value) {
                        if (doctors[1] == 1) {
                            if (value.refered) {
                                $('#rf_list').append('<a href="javascript:void(0);"' +
                                    'class="list-group-item sp_doc"><div class="row"><img src="' + value.user_image + '" class="col-4 rounded-circle" height="80">' +
                                    '<div class="col-8"><p style="margin-bottom: 0px;" class="col-12 p-0">' +
                                    value.name + ' ' + value.last_name +
                                    '</p><p style="" class="col-12 p-0">NPI: ' + value.nip_number + '</p>' +
                                    '<button style="color:white; background-color:red;" onclick="cancelReferal(this)" id="referID_' + value.refer_id + '" class="btn btn-primary ml-1">Cancel the Referral</button>' +
                                    '</div></div></a>'
                                );
                            }
                            else {

                                $('#rf_list').append('<a href="javascript:void(0);"' +
                                    'class="list-group-item sp_doc"><div class="row"><img src="' + value.user_image + '" class="col-4 rounded-circle" height="80">' +
                                    '<div class="col-8"><p style="margin-bottom: 0px;" class="col-12 p-0">' +
                                    value.name + ' ' + value.last_name +
                                    '</p><p style="" class="col-12 p-0">NPI: ' + value.nip_number +
                                    '</p><button style="color:white;background-color:#0069d9;" id="sp_doc_' + value.id + '" class="btn btn-primary" disabled>Refer</button></div></div></a>'
                                );
                            }

                        }
                        else {
                            $('#rf_list').append('<a href="javascript:void(0);"' +
                                'class="list-group-item sp_doc"><div class="row"><img src="' + value.user_image + '" class="col-4 rounded-circle" height="80">' +
                                '<div class="col-8"><p style="margin-bottom: 0px;" class="col-12 p-0">' +
                                value.name + ' ' + value.last_name +
                                '</p><p style="" class="col-12 p-0">NPI: ' + value.nip_number + '</p><input placeholder="Short comment" class="form-control mb-2" id="referal_comment_' + value.id +
                                '">' +
                                '<button style="color:white;background-color:#0069d9;" id="sp_doc_' + value.id + '" class="btn btn-primary" onclick="sendReferal(this)" >Refer</button></div></div></a>'
                            );
                        }
                    })
                } else {
                    $('#rf_list').append('<a href="javascript:void(0);"' + ' class="list-group-item sp_doc">No doctors of this specialization available</a>');
                }
            }
        });
    }

    function sessionDetails(a) {
        id = $(a).attr('id');
        id_sp = id.split("sess_");
        id = id_sp[1];
        // console.log(id);
        $.ajax({
            type: "POST",
            url: "{{URL('/getSessionDetails')}}",
            data: {
                id: id,
                video: true,
            },
            success: function (session) {
                console.log(session);
                $('#prev_session_doc').text(session.doc_name);
                $('#prev_session_date').text(session.date);
                $('#prev_session_start_time').text(session.start_time);
                $('#prev_session_end_time').text(session.end_time);
                if (session.diagnosis == null) {
                    $('#prev_session_diagnosis').text("No diagnosis added");
                } else {
                    $('#prev_session_diagnosis').text(session.diagnosis);
                }
                $('#prev_session_notes').text('');
                if (session.provider_notes == null) {
                    $('#prev_session_notes').append("No notes added");
                } else {
                    $('#prev_session_notes').append(session.provider_notes);
                }
                $('#prev_session_meds').text('');
                if (session.prescription.length == 0) {
                    $('#prev_session_meds').append("No prescription added");
                } else {
                    $('#prev_session_meds').append('<ul>');
                    $.each(session.prescription, function (key, value) {
                        $('#prev_session_meds').append('<li class="mb-0">' + value.prod_detail.name +
                            '</li>')
                    })
                    $('#prev_session_meds').append('</ul>');
                }
                $('#prev_session_symps_list').text('');
                $('#prev_session_symps_list').append('<ul>');
                if (session.symptom.flu == '1')
                    $('#prev_session_symps_list').append('<li>Flu</li>');
                if (session.symptom.fever == '1')
                    $('#prev_session_symps_list').append('<li>Fever</li>');
                if (session.symptom.headache == '1')
                    $('#prev_session_symps_list').append('<li>Headache</li>');
                if (session.symptom.nausea == '1')
                    $('#prev_session_symps_list').append('<li>Nausea</li>');
                if (session.symptom.others == '1')
                    $('#prev_session_symps_list').append('<li>Others</li>');
                $('#prev_session_symps_list').append('</ul>');
            }
        });
    }

    function aquire_start() {

        @php
        if (env('APP_TYPE') == 'local') {
            @endphp
                var aynalyticsUrl = 'http://127.0.0.1:8000/api/aynalatics';
            @php
        }
        if (env('APP_TYPE') == 'testing') {
            @endphp
                var aynalyticsUrl = 'https://demo.umbrellamd-video.com/api/aynalatics';
            @php
        }
        if (env('APP_TYPE') == 'staging') {
            @endphp
                var aynalyticsUrl = 'https://www.umbrellamd-video.com/api/aynalatics';
            @php
        }
        if (env('APP_TYPE') == 'production') {
            @endphp
                var aynalyticsUrl = 'https://www.umbrellamd.com/api/aynalatics';
            @php
        }
        @endphp
            var type = "<?php echo $user_type; ?>";
        if (type == 'doctor') {
            var channelName = $("#channel").val();
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'POST',
                dataType: "text",
                data: {
                    _token: _token,
                    channel: channelName
                },
                url: aynalyticsUrl,
                success: function (data) {
                    if (data == 0) {
                        @php
                        if (env('APP_TYPE') == 'local') {
                            @endphp
                                var aquireUrl = 'http://127.0.0.1:8000/api/aquire';
                            @php
                        }
                        if (env('APP_TYPE') == 'testing') {
                            @endphp
                                var aquireUrl = 'https://demo.umbrellamd-video.com/api/aquire';
                            @php
                        }
                        if (env('APP_TYPE') == 'staging') {
                            @endphp
                                var aquireUrl = 'https://www.umbrellamd-video.com/api/aquire';
                            @php
                        }
                        if (env('APP_TYPE') == 'production') {
                            @endphp
                                var aquireUrl = 'https://www.umbrellamd.com/api/aquire';
                            @php
                        }
                        @endphp

                            var userid = $("#uid").val();
                        var channelName = $("#channel").val();
                        var _token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            type: 'POST',
                            dataType: "text",
                            data: {
                                _token: _token,
                                channel: channelName,
                                userid: userid
                            },
                            url: aquireUrl,
                            success: function (data) {
                                console.log('video recording start');
                            }
                        });
                    }
                }
            });
        }
    }

    function endCall() {
        @php
        if (env('APP_TYPE') == 'local') {
            @endphp
                var stopUrl = 'http://127.0.0.1:8000/api/stop';
            @php
        }
        if (env('APP_TYPE') == 'testing') {
            @endphp
                var stopUrl = 'https://demo.umbrellamd-video.com/api/stop';
            @php
        }
        if (env('APP_TYPE') == 'staging') {
            @endphp
                var stopUrl = 'https://www.umbrellamd-video.com/api/stop';
            @php
        }
        if (env('APP_TYPE') == 'production') {
            @endphp
                var stopUrl = 'https://www.umbrellamd.com/api/stop';
            @php
        }
        @endphp

            var type = $("#type").val();
        if (type == 'doctor') {
            var channel = $('#channel').val();
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'POST',
                dataType: "text",
                data: {
                    _token: _token,
                    channel: channel
                },
                url: stopUrl,
                success: function (data) {
                    console.log('video recording stop');
                }
            });
        }
    }
    var client = AgoraRTC.createClient({
        mode: "rtc",
        codec: "vp8"
    });
    var localTracks = {
        videoTrack: null,
        audioTrack: null
    };
    var remoteUsers = {};
    const joincall = async function join() {
        aquire_start();
        // Add an event listener to play remote tracks when remote user publishes.
        client.on("user-published", handleUserPublished);
        client.on("user-unpublished", handleUserUnpublished);
        // Join a channel and create local tracks. Best practice is to use Promise.all and run them concurrently.
        [uid, localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
            // Join the channel.
            client.join(appid, channel, token || null, uid || null),
            // Create tracks to the local microphone and camera.
            AgoraRTC.createMicrophoneAudioTrack(),
            AgoraRTC.createCameraVideoTrack()
        ]);
        // Play the local video track to the local browser and update the UI with the user ID.
        localTracks.videoTrack.play("local-player");
        // Publish the local video and audio tracks to the channel.
        await client.publish(Object.values(localTracks));
        //    console.log("publish success");
    }

    var appid = $("#appid").val();
    var channel = $("#channel").val();
    var uid = $("#uid").val();
    var token = '';
    if (appid != null && channel != null) {
        joincall();
    }

    function leave() {
        for (trackName in localTracks) {
            var track = localTracks[trackName];
            if (track) {
                track.stop();
                track.close();
                localTracks[trackName] = undefined;
            }
        }
        // Remove remote users and player views.
        remoteUsers = {};
        $("#remote-playerlist").html("");
        // leave the channel
        client.leave();
        $("#local-player-name").text("");
        $("#recommendationForm").submit();
    }

    async function subscribe(user, mediaType) {
        const uid = user.uid;
        // subscribe to a remote user
        await client.subscribe(user, mediaType);
        console.log("subscribe success");
        if (mediaType === 'video') {
            const player = $(`
      <div id="player-wrapper-${uid}" style="height:100%;">
      <div id="player-${uid}" class="player" style="height:100%;"></div>
      </div>
   `);
            $("#remote-playerlist").append(player);
            user.videoTrack.play(`player-${uid}`);
        }
        if (mediaType === 'audio') {
            user.audioTrack.play();
        }
    }

    function handleUserPublished(user, mediaType) {
        const id = user.uid;
        remoteUsers[id] = user;
        subscribe(user, mediaType);
    }

    function handleUserUnpublished(user, mediaType) {
        if (mediaType === 'video') {
            const id = user.uid;
            delete remoteUsers[id];
            $(`#player-wrapper-${id}`).remove();
        }
    }


    function runSpeechRecognition() {

        // get action element reference
        var action = document.getElementById("start-record-btn");
        // new speech recognition object
        var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
        var recognition = new SpeechRecognition();

        // This runs when the speech recognition service starts
        recognition.onstart = function () {
            $('#start-record-btn').css("background-color", "#2185d0");
            if ($("#mic_icon").hasClass('fa-microphone')) {
                localTracks.audioTrack.setEnabled(false);
                $('#mic_mute').prop('title', 'unmute');
                $("#mic_icon").toggleClass('fa-microphone fa-microphone-slash');
            }
            action.innerHTML = "listening, please speak...";
        };
        recognition.onspeechend = function () {
            action.innerHTML = "stopped listening, hope you are done...";
            recognition.stop();
            if ($("#mic_icon").hasClass('fa-microphone-slash')) {
                localTracks.audioTrack.setEnabled(true);
                $('#mic_mute').prop('title', 'mute');
                $("#mic_icon").toggleClass('fa-microphone-slash fa-microphone');
            }
        }
        // This runs when the speech recognition service returns result
        recognition.onresult = function (event) {
            var previousData = $('#note-textarea').val();
            var transcript = event.results[0][0].transcript;
            $('#start-record-btn').css("background-color", "red");
            $('#note-textarea').val(previousData + " " + transcript);

        };
        // start recognition
        recognition.start();
    }

    $("#pat_mic_mute").click(function () {
        if ($("#pat_mic_icon").hasClass('fa-microphone')) {
            localTracks.audioTrack.setEnabled(false);
            $('#pat_mic_mute').prop('title', 'unmute');
            $("#pat_mic_icon").toggleClass('fa-microphone fa-microphone-slash');
        }
        else {
            localTracks.audioTrack.setEnabled(true);
            $('#pat_mic_mute').prop('title', 'mute');
            $("#pat_mic_icon").toggleClass('fa-microphone-slash fa-microphone');
        }
    });
    $("#mic_mute").click(function () {
        if ($("#mic_icon").hasClass('fa-microphone')) {
            localTracks.audioTrack.setEnabled(false);
            $('#mic_mute').prop('title', 'unmute');
            $("#mic_icon").toggleClass('fa-microphone fa-microphone-slash');
        }
        else {
            localTracks.audioTrack.setEnabled(true);
            $('#mic_mute').prop('title', 'mute');
            $("#mic_icon").toggleClass('fa-microphone-slash fa-microphone');
        }
    });


</script>
@endsection
