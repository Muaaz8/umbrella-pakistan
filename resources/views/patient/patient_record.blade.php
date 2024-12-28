@extends('layouts.admin')
{{-- {{dd($pat_info)}} --}}
@section('content')
<section class="content patients">
    <div class="container-fluid">
        <div class="block-header mb-0 pb-0">
            <h2>{{ucwords($pat_name)}}</h2>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                @if(auth()->user()->user_type=='doctor')
                <li class="breadcrumb-item"><a href="{{route('patients')}}">All Patients</a></li>
                @else
                <li class="breadcrumb-item"><a href="{{route('admin_patients')}}">All Patients</a></li>
                @endif
                <li class="breadcrumb-item active"><a href="javascript:void(0);">{{ucwords($pat_name)}}</a></li>
            </ul>

        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card" style="height:auto">
                    <div class="body">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="nav-session" data-toggle="tab" href="#session">Session history</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" id="nav-dis" href="#med-profile">Medical Profile</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#timeline">Patient Info</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#current_meds">Medication history</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#lab_history">Lab history</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#imaging_history">Imaging history</a></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane tab-med in" id="med-profile">
                                    <div class="wrap-reset">
                                        <div class="row col-md">
                                            <div class="col-md ">
                                                <h5 class="mt-2">Medical Profile</h5>
                                                <hr>
                                                @if(isset($medical_profile))
                                                <span class="mb-2"><strong>Last updated:
                                                    </strong>{{$last_updated}}</span>
                                                @endif
                                                @php
                                                $diseases=['hypertension'=>'Hypertension','diabetes'=>'Diabetes',
                                                'cancer'=>'Cancer','heart'=>'Heart Disease',
                                                'chest'=>'Chest Pain/chest tightness',
                                                'shortness'=>'Shortness of breath',
                                                'swollen'=>'Swollen Ankles',
                                                'palpitation'=>'Palpitation/Irregular Heartbeat',
                                                'stroke'=>'Stroke'];
                                                @endphp
                                                @foreach($diseases as $key=>$disease)
                                                <div class="form-check row col-12 ml-4">

                                                    @if(isset($medical_profile))
                                                    @if(strpos($medical_profile['previous_symp'],$key)=== false)
                                                    <input type="checkbox" readonly="" disabled=""
                                                        class="form-check-input" id="s1" name="$key" value="1">
                                                    @else
                                                    <input type="checkbox" readonly="" disabled=""
                                                        class="form-check-input" id="s1" name="$key" value="1" checked>
                                                    @endif
                                                    @else
                                                    <input type="checkbox" readonly="" disabled=""
                                                        class="form-check-input" id="s1" name="$key" value="1">
                                                    @endif
                                                    <label class="form-check-label" for="s1">{{$disease}}</label>

                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md">
                                                <h4 class=" mt-2">Family History</h4>
                                                <hr>
                                                @if(isset($medical_profile))
                                                <table class="table table-bordered table-hover">
                                                    <th>Disease</th>
                                                    <th>Family Member</th>
                                                    <th>Aprox. Age</th>
                                                    @php
                                                    $fam_arr=json_decode($medical_profile['family_history']);
                                                    @endphp
                                                    @forelse($fam_arr as $fam)
                                                    <tr>
                                                        <td>
                                                            @if($fam->disease=='heart')
                                                            Heart Disease
                                                            @elseif($fam->disease=='mental')
                                                            Mental Disease
                                                            @elseif($fam->disease=='drugs')
                                                            Drugs/Alcohol Addiction
                                                            @elseif($fam->disease=='bleeding')
                                                            Bleeding Disease
                                                            @else
                                                            {{ucwords($fam->disease)}}
                                                            @endif
                                                        </td>
                                                        <td>@if($fam->family=='grand')
                                                            Grandparent
                                                            @else
                                                            {{ucwords($fam->family)}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ucwords($fam->age)}}

                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="3">
                                                            <center>No family history</center>
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </table>
                                                @else
                                                <p>Patient did not fill medical profile
                                                <p>
                                                    @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @hasanyrole('admin')
                                <div role="tabpanel" class="tab-pane" id="timeline">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a href="#" class="p-profile-pix"> <img src="{{$pat_info->user_image}}" alt="user" class="img-fluid" width="200"></a>
                                        </div>
                                        <div class="timeline-body col-md-4">
                                            <strong>Patient Name</strong>
                                            <p>{{ucwords($pat_info->name." ". $pat_info->last_name)}}</p>
                                            <strong>Date Of Birth</strong>
                                            <p>{{$pat_info->date_of_birth}}</p>
                                            <strong>Email</strong>
                                            <p>{{$pat_info->email}}</p>
                                        </div>
                                        <div class="timeline-body col-md-4">
                                            <strong>Phone Number</strong>
                                            <p>{{$pat_info->phone_number}}</p>
                                            <strong>Address</strong>
                                            <p>{{$pat_info->office_address}}</p>
                                            <strong>Bio</strong>
                                            <p>{{$pat_info->bio}}</p>

                                        </div>
                                    </div>
                                </div>
                                @endhasanyrole

                                @hasanyrole('doctor')
                                <div role="tabpanel" class="tab-pane" id="timeline">
                                    <div class="timeline-body">
                                        <strong>Patient Name</strong>
                                        <p>{{ucwords($pat_info->name." ". $pat_info->last_name)}}</p>
                                        <strong>Bio</strong>
                                        <p>{{$pat_info->bio}}</p>
                                    </div>
                                </div>
                                @endhasanyrole

                                <div role="tabpanel" class="tab-pane tab-session active" id="session">
                                    <div class="timeline-body">
                                        <div class="col-md-12">
                                            <h4 class=" mt-2">Session History
                                                @if($user_type=='admin')
                                                <small class="pull-right mr-4"><b>Total Session
                                                        Earnings:Rs. {{count($sessionss)*30}}</b></small>
                                                @endif
                                            </h4>
                                            <hr>

                                            <!-- session history -->
                                            <div class="panel-group" id="accordion_10" role="tablist"
                                                aria-multiselectable="true">

                                                @forelse($sessionss as $session)
                                                <div class="panel ">
                                                    <div class="panel-heading bg-blue" role="tab" id="headingTwo_10">
                                                        <h4 class="panel-title"> <a class="collapsed" role="button"
                                                                data-toggle="collapse" data-parent="#accordion_10"
                                                                href="#session_{{$session->id}}" aria-expanded="false"
                                                                aria-controls="session_{{$session->id}}"
                                                                style="font-weight:bold">{{ucwords($pat_name)}} session
                                                                with
                                                                Dr. {{ucwords($session->doc_name)}}<span
                                                                    class="float-right">{{$session->date}}</span> </a>
                                                        </h4>
                                                    </div>
                                                    <div id="session_{{$session->id}}" class="panel-collapse collapse"
                                                        role="tabpanel" aria-labelledby="headingTwo_10">
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <span><b>{{ucwords($pat_name)}} session with Dr.
                                                                        {{ucwords($session->doc_name)}}</b></span>
                                                                <table class="col-12">
                                                                    <tr class="row ml-3">
                                                                        <!-- <div class="col-md-12"> -->
                                                                        <td class="col-md-2 px-0" style="width:60%">
                                                                            <b>Diagnosis: </b>
                                                                        </td>
                                                                        <td class="pl-2" colspan="3">
                                                                            @if($session->diagnosis!=null)
                                                                            {{ucfirst($session->diagnosis)}}
                                                                            @else
                                                                            No Diagnosis
                                                                            @endif
                                                                        </td>

                                                                        <!-- </div> -->
                                                                    </tr>

                                                                    <tr class="row ml-3">
                                                                        <!-- <div class="col-md-12"> -->
                                                                        <td class="col-md-2 px-0"><b>Provider: </b></td>
                                                                        <td class="col-md-3">
                                                                            {{ucwords($session->doc_name)}}
                                                                        </td>
                                                                        <td class="col-md-2"><b>Start Time: </b></td>
                                                                        <td class="col-md-3">{{$session->start_time}}
                                                                        </td>

                                                                        <!-- </div> -->
                                                                        <!-- <div class="col-md-12"> -->
                                                                    </tr>
                                                                    <tr class="row ml-3">
                                                                        <td class="col-md-2 px-0"><b>Date: </b></td>
                                                                        <td class="col-md-3">{{$session->date}}</td>
                                                                        <td class="col-md-2"><b>End Time: </b></td>
                                                                        <td class="col-md-3">{{$session->end_time}}</td>
                                                                        <!-- </div> -->
                                                                        <!-- <div class="col-md-12 row clearfix"> -->
                                                                    </tr>
                                                                    @if($user_type=='admin')
                                                                    <tr class="row ml-3">
                                                                        <td class="col-md-2 px-0"><b>Amount Paid: </b>
                                                                        </td>
                                                                        <td class="col-md-3">$30</td>
                                                                        <td class="col-md-2"><b>Doctor's Share: </b>
                                                                        </td>
                                                                        <td class="col-md-3">$24</td>

                                                                        <!-- </div> -->
                                                                        <!-- <div class="col-md-12 row clearfix"> -->
                                                                    </tr>

                                                                    <tr class="row ml-3 bg-teal mr-4">
                                                                        <td class="col-md-2 px-0"><b>Earning: </b></td>
                                                                        <td class="col-md-3">$6</td>

                                                                        <!-- </div> -->
                                                                        <!-- <div class="col-md-12 row clearfix"> -->
                                                                    </tr>
                                                                    <tr class="row ml-3">
                                                                        <td class="col-md-2 px-0"><b>Patient's Feedback:
                                                                            </b></td>
                                                                        <td class="pl-2" colspan="3">
                                                                            @if($session->patient_suggestions!=null)
                                                                            {{ucfirst(trim($session->patient_suggestions))}}
                                                                            @else
                                                                            No Feedback
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                </table>
                                                                <!-- </div> -->
                                                                <!-- </div>  -->
                                                                <div class="body table-responsive">
                                                                    <table
                                                                        class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Recommendateion</th>
                                                                                <th>Dosage</th>
                                                                                <th>Comment</th>
                                                                                <th>Type</th>
                                                                                <th>Status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @forelse($session->pres as $pres)
                                                                            @if($pres->prod_detail!=null)
                                                                            @if($pres->prod_detail->mode=='medicine')
                                                                            <tr class="medicine-bg">
                                                                                @elseif($pres->prod_detail->mode=='lab-test')
                                                                            <tr class="lab-bg">
                                                                                @elseif($pres->prod_detail->mode=='imaging')
                                                                            <tr class="imaging-bg">
                                                                                @endif
                                                                                @if($pres->prod_detail->mode=='lab-test')
                                                                                <td>{{ucfirst($pres->prod_detail->DESCRIPTION)}}
                                                                                </td>
                                                                                @else
                                                                                <td>{{ucfirst($pres->prod_detail->name)}}
                                                                                </td>
                                                                                @endif
                                                                                <td>{{ucfirst($pres->usage)}}</td>
                                                                                <td>{{ucfirst($pres->comment)}}</td>
                                                                                <td>{{ucfirst($pres->type)}}</td>
                                                                                <!-- Status from Cart table -->
                                                                                <td>{{ucfirst($pres->status)}}</td>
                                                                            </tr>
                                                                            @endif
                                                                            @empty
                                                                            <tr>
                                                                                <td colspan="5">
                                                                                    <center>
                                                                                        No Recommendations</center>
                                                                                </td>
                                                                            </tr>
                                                                            @endforelse
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @empty
                                                <span class="text-capitalize">No session attended</span>
                                                @endforelse
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="paginateCounter link-paginate">
                                                        {{$sessionss->links('pagination::bootstrap-4') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end session history -->
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="current_meds">
                                    <div class="timeline-body">
                                        <div class="col-md-12">
                                            <h4 class=" mt-2">Medication History
                                            </h4>
                                        </div>
                                        <hr>
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="offset-md-1 col-md-6 font-weight-bold">
                                                    Medicine Name
                                                </div>
                                                <h6 class="col-md-3 font-weight-bold">Usage</h6>
                                                <h6 class="col-md-2 font-weight-bold">Session Date</h6>
                                            </li>
                                            @forelse($history['patient_meds'] as $med)
                                            <li class="list-group-item">
                                                <div class="col-md-1">
                                                    @if(isset($med->prod_img))
                                                    <img src="{{url('/uploads/'.$med->prod_img)}}" height="30"
                                                        width="30">
                                                    @else
                                                    <img src="{{url('/uploads/'.$med->prod->featured_image)}}"
                                                        height="30" width="30">
                                                    @endif
                                                </div>
                                                <h6 class="col-md-6">{{$med->prod->name}}</h6>
                                                <h6 class="col-md-3"><small>{{$med->usage}}</small></h6>
                                                <h6 class="col-md-2"><small>{{$med->date}}</small></h6>
                                            </li>
                                            @empty
                                            <li class="list-group-item">
                                                <div class="col-md-12 text-align-center">
                                                    No Medications Found
                                                </div>
                                            </li>
                                            @endforelse
                                        </ul>

                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="lab_history">
                                    <div class="timeline-body">
                                        <div class="col-md-12">
                                            <h4 class=" mt-2">Lab History
                                            </h4>
                                        </div>
                                        <hr>
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="col-md-9 font-weight-bold">
                                                    Test Name
                                                </div>
                                                <h6 class="col-md-2 font-weight-bold">Result Date</h6>
                                                <h6 class="col-md-1 font-weight-bold">Action</h6>
                                            </li>
                                            @forelse($history['patient_labs'] as $lab)
                                            <li class="list-group-item">
                                                <div class="col-md-9">
                                                    {{$lab->test_names}}
                                                </div>
                                                <h6 class="col-md-2">{{$lab->result_date}}</h6>
                                                <h6 class="col-md-1"><a href="{{$lab->file}}" target="_blank"><i class="fa fa-eye"></i></a></h6>
                                            </li>
                                            @empty
                                            <li class="list-group-item">
                                                <div class="col-md-12 text-align-center">
                                                    No Lab Tests Found
                                                </div>
                                            </li>
                                            @endforelse
                                        </ul>

                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="imaging_history">
                                    <div class="timeline-body">
                                        <div class="col-md-12">
                                            <h4 class=" mt-2">Imaging History
                                            </h4>
                                        </div>
                                        <hr>
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="col-md-9 font-weight-bold">
                                                    Service Name
                                                </div>
                                                <h6 class="col-md-2 font-weight-bold">Result Date</h6>
                                                <h6 class="col-md-1 font-weight-bold">Action</h6>
                                            </li>
                                            @forelse($history['patient_imaging'] as $img)
                                            <li class="list-group-item">
                                                <div class="col-md-9">
                                                    {{$img->name}}
                                                </div>
                                                <h6 class="col-md-2">{{$img->updated_at}}</h6>
                                                <h6 class="col-md-1"><a href="{{$img->report}}" target="_blank"><i
                                                            class="fa fa-eye"></i></a></h6>
                                            </li>
                                            @empty
                                            <li class="list-group-item">
                                                <div class="col-md-12 text-align-center">
                                                    No Service Found
                                                </div>
                                            </li>
                                            @endforelse
                                        </ul>

                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="order_history">
                                    <div class="timeline-body">
                                        <div class="col-md-12">
                                            <h4 class=" mt-2">Order History
                                            </h4>
                                        </div>
                                        <hr>
                                        {{-- {{ dd($tblOrders) }} --}}
                                        @include('tbl_orders.table')

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
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.collapse .show').css('display', 'block');
});
$('.collapse .show').click(function() {
    if ($('.collapse .show').css('display') == 'block')
        $('.collapse .show').css('display', 'none');
    else
        $('.collapse .show').css('display', 'block');
}) -->
<script>
var currenturl = window.location.href;
var url = currenturl.split("/");
var page = url[4];
var split = page.split("=")
var check = split[0]
var flag = check.split("?")
var final = flag[1];
var page = "page"
paginate = final.includes(page);
$(document).ready(function() {
    if (paginate == true) {
        // if(isArray == true){
        // alert('value is Array!');
        $("#nav-dis").removeClass("active");
        $("#med-profile").removeClass("active");
        $("#session").addClass("active");
        $("#nav-session").addClass("active");
    }
});

</script>
<script src="{{ asset('asset_admin/js/pages/index.js') }}"></script>
<script src="{{ asset('asset_admin/js/pages/charts/sparkline.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('asset_admin/js/datatables/datatables.min.css') }}" />

<script type="text/javascript" src="{{ asset('asset_admin/js/datatables/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('asset_admin/js/datatables/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ asset('asset_admin/js/datatables/datatables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#tblOrders-table').DataTable();
    });

    $('#filters').on('change', function() {
        console.log();
        filter = $(this).val();
        $.get('/imaging_orders_filter?filter=' + filter, function(data) {
            var table = $('#tblOrders-table').DataTable();
            table.clear();
            table.destroy();
            console.log(data);
            appendData(data);
            //   $('#loading').hide();
        })
    })

    function appendData(data) {
        $(() => {
            $("#tblOrders-table").DataTable({
                data: data,
                columns: [{
                        data: "order_state"
                    },
                    {
                        data: "order_city"
                    },
                    {
                        data: "order_id"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.fname + ' ' + row.lname;
                        }
                    },
                    {
                        data: "address"
                    },
                    {
                        data: "name"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.lab_name + ', ' + row.lab_address;
                        }
                    },
                    {
                        data: "date"
                    },
                    {
                        data: "time"
                    },
                    {
                        data: "total"
                    },
                    {
                        data: "order_status",
                        render: function(data, type, row) {
                            return row.order_status.charAt(0).toUpperCase() + row.order_status
                                .slice(1);
                        }
                    },
                    {
                        data: "id",
                        render: function(data, type, row) {
                            return '<div class="btns-group"><a href="/lab_order/' + row.id +
                                '" class="action-btn"><i class="fa fa-eye"></i></a></div>'

                        }
                    }


                ]
            });
        });


    }
</script>
