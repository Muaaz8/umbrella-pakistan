<div class="col-md-6" height="600">
    @include('./frontend/_agora_video')
</div>
<input hidden="" value="{{$getSession->id}}" id="session_id_pat">
<input hidden="" value="{{$user_type}}" id="user_type">
<div class="col-md-6 float-right" style="height:640px;overflow-y:auto">
    <h5 class="mt-2" style="text-align:center">My history</h5>
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#curr_meds">Medications</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#curr_labs">Labs</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#curr_imaging">Imaging</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#pat_visit_history">Visit
            </a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#pat_med_history">Medical
            </a></li>
    </ul>
    <div class="tab-content" style="overflow-y:auto;overflow-x:hidden;">
        <div role="tabpanel" class="tab-pane active container" id="curr_meds">
            <h4 class="mt-2">Current Medications</h4>
            @forelse($history['medicines'] as $pr)
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
                    <div class="sbox-7-txt">
                        <h5 class="h5-sm steelblue-color">No Current Medicines</h5>
                    </div>
                </a>
            </div>
            @endforelse
        </div>
        <div role="tabpanel" class="tab-pane container" id="curr_labs">
            <h4 class="mt-2">Latest Reports</h4>
            @forelse($history['labs'] as $pr)
            <a href="javascript:void(0);" class="list-group-item">
                <div class="row">
                    <div class="col-9">
                        <p>{{$pr->test_names}}</p>
                        <p><strong>Date: </strong>{{$pr->result_date}}
                        </p>
                    </div>
                    <!-- report id -->
                    <input id="report_file_{{$pr->id}}" hidden value="{{$pr->file}}">
                    <input id="report_date_{{$pr->id}}" hidden value="{{$pr->result_date}}">
                    <input id="report_age_{{$pr->id}}" hidden value="{{$pr->age}}">
                    <div class="col-3"><button id="report_{{$pr->id}}" onclick="load_report(this)"
                            style="color:white; background-color:#0069d9;" class="btn btn-primary " data-dismiss="modal"
                            data-toggle="modal" data-target="#test_report_modal">View Report</button></div>
                </div>
            </a>
            @empty
            <a href="javascript:void(0);" class="list-group-item">
                <div class="row">
                    <div class="col-12">
                        <p>No Labs Added</p>
                    </div>
                </div>
            </a>
            @endforelse

            <!-- <a href="javascript:void(0);" class="list-group-item">
                            <div class="row">
                                <div class="col-8">
                                    <p>Liver Function Test</p>
                                    <p><strong>Date:</strong>Jan, 04th 2021
                                    <p>
                                </div>
                                <div class="col-4"><button class="btn btn-primary " data-toggle="modal"
                                        data-target="#test_report_modal" style="color:grey">View Results</button></div>
                            </div>
                        </a>
                        <a href="javascript:void(0);" class="list-group-item">
                            <div class="row">
                                <div class="col-8">
                                    <p>Renal Function Test</p>
                                    <p><strong>Date:</strong>Jan, 04th 2021
                                    <p>
                                </div>
                                <div class="col-4"><button class="btn btn-primary " data-toggle="modal"
                                        data-target="#test_report_modal" style="color:grey">View Results</button></div>
                            </div>
                        </a>
                        <a href="javascript:void(0);" class="list-group-item">
                            <div class="row">
                                <div class="col-8">
                                    <p>Complete Blood Count</p>
                                    <p>
                                        <strong>Date:</strong>Jan, 04th 2021<p>
                                </div>
                                <div class="col-4"><button class="btn btn-primary " data-toggle="modal"
                                        data-target="#test_report_modal" style="color:grey">View Results</button></div>
                            </div>
                        </a> -->
        </div>
        
        <div role="tabpanel" class="tab-pane container" id="curr_imaging">
            <h4 class="mt-2">Latest Reports</h4>
            @forelse($history['imaging'] as $report)
            <a href="javascript:void(0);" class="list-group-item">
                <div class="row">
                    <div class="col-12">
                        <p>{{$report->name}}</p>
                    </div>
                    <div class="col-12 text-right">
                        <input id="img_report_file_{{$report->id}}" hidden value="{{$report->report}}">
                        <button class="btn btn-primary img_report" data-dismiss="modal" data-toggle="modal"
                            data-target="#img_report_modal" data-id="{{$report->id}}" onclick="load_img_report(this)"
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
        <!-- </div> -->
        <div role="tabpanel" class="tab-pane container" id="pat_visit_history">
            <div class="row mt-2 mb-2">
                <h6 class="col-7"><strong>Last visit: </strong>
                    @if($history['sessions']->count()<1) None @else {{$history['sessions'][0]->date}} @endif </h6>
            </div>
            <div class="bg-secondary">
                @forelse($history['sessions'] as $session)
                <a href="javascript:void(0);" class="list-group-item">
                    <div class="row">
                        <div class="col-9">
                            <p><strong>Provider: </strong> {{$session->doc}}</p>
                            <p><strong>Date:</strong>{{$session->date}}
                            <p>
                        </div>
                        <div class="col-3"><button id="sess_{{$session->id}}" class="btn btn-primary prev_session"
                                data-toggle="modal" data-target="#session_modal" type="button"
                                onclick="sessionDetails(this)" style="color:white;background-color:#0069d9;">
                                Details</button></div>
                    </div>
                </a>
                @empty
                <a href="javascript:void(0);" class="list-group-item">No visits</a>
                @endforelse
                <!-- </div> -->
            </div>
        </div>
        <div role="tabpanel" class="tab-pane container" id="pat_med_history">
            <label class="mt-2">Name</label>
            <input readonly="" class="form-control" value="{{ucwords($patUser->name.' '.$patUser->last_name)}}">
            <label>Age</label>
            <input readonly="" class="form-control" value="{{$pat_age}}">    
            <h4 class="mt-2">Symptoms</h4>
            <textarea readonly="" class="form-control" row="3">{{$symptoms}}&#13;&#10;{{$symp_desc}}</textarea>
            <h4 class="">Medical History
                @if($patUser->med_record_file!=null)
                <span><a class="float-right" target="_blank" href="{{$patUser->med_record_file}}">View EMR</a></span>
                @endif
            </h4>
            <hr>
            <!-- <input class="form-control" placeholder="Search Pharmacy.." onkeyup="showResult(this.value)"> -->
            @if(isset($medical_profile))
            @php
            $updated=explode(' ',$medical_profile['updated_at']);
            $date_sp=explode('-',$updated[0]);
            $date=$date_sp[2];
            $month=$date_sp[1];
            $year=$date_sp[0];
            $last_updated=$month.'-'.$date.'-'.$year;
            @endphp
            <h6 class=""><strong>Last updated: </strong>{{$last_updated}}</h6>
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
                <input type="checkbox" readonly="" disabled="" class="form-check-input" id="s1" name="$key" value="1">
                @else
                <input type="checkbox" readonly="" disabled="" class="form-check-input" id="s1" name="$key" value="1"
                    checked>
                @endif
                @else
                <input type="checkbox" readonly="" disabled="" class="form-check-input" id="s1" name="$key" value="1">
                @endif
                <label class="form-check-label" for="s1">{{$disease}}</label>
            </div>
            @endforeach
            <div>
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
                            <center>No Family History</center>
                        </td>
                    </tr>
                    @endforelse
                </table>
                @else
                <p>Patient did not fill medical profile</p>
                @endif
            </div>
        </div>
    </div>
    <!-- End tabs -->

</div>
<script>
function load_report(e) {
    id = $(e).attr('id');
    id_sp = id.split('_');
    // console.log(id_sp[1]);
    age = $('#report_age_' + id_sp[1]).val();
    rep_date = $('#report_date_' + id_sp[1]).val();
    rep_file = $('#report_file_' + id_sp[1]).val();
    // console.log(age);
    // console.log(rep_date);
    // console.log(rep_file);

    $('#test_report_age').text(age);
    $('#test_report_date').text(rep_date);
    $('#test_report_file').attr('src', rep_file);
}
function load_img_report(e) {
    id = $(e).data('id');
    // id_sp = id.split('_');
    console.log(id);
    rep_file = $('#report_img_file_' +id).val();
    // console.log(age);
    // console.log(rep_date);
    // console.log(rep_file);

    $('#img_report_file').attr('src', rep_file);
}
</script>