<div style="background:linear-gradient(45deg, #08295a, #5e94e4)" class="col-md-7 pr-0 row mb-0">
    @include('./frontend/_agora_video')
</div>

<div class="col-md-5 float-right pl-4 pt-2" style="height:700px">
    <!-- <div style="overflow-y:auto;height:200px">
            <h5>Symptoms Checker</h5>
        </div> -->
    <div class="mt-2">
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#patient_profile">Patient</a>
            </li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#pres">Prescription</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#history">Visit History</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#notes_tab">Notes</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#refer_tab">Refer</a></li>
        </ul>
        <!-- Tab panes -->
        <!-- <div class="tab-content" style="height:450px;overflow-y:auto"> -->
        <div class="tab-content" style="height:650px;overflow-y:auto">
            <div role="tabpanel" class="tab-pane active container" id="patient_profile">
                <label class="mt-2">Patient Name</label>
                <input readonly="" class="form-control" value="{{ucwords($patUser->name.' '.$patUser->last_name)}}">
                <label>Age</label>
                <input readonly="" class="form-control" value="{{$pat_age}}">
                <label>Symptoms</label>
                <textarea readonly="" class="form-control" row="3">{{$symptoms}}&#13;&#10;{{$symp_desc}}</textarea>
                <h4 class="">Patient Medical History
                    @if($patUser->med_record_file!=null)
                    <span><a class="float-right" target="_blank" href="{{$patUser->med_record_file}}">View
                            EMR</a></span>
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
                    <input type="checkbox" readonly="" disabled="" class="form-check-input" id="s1" name="$key"
                        value="1">
                    @else
                    <input type="checkbox" readonly="" disabled="" class="form-check-input" id="s1" name="$key"
                        value="1" checked>
                    @endif
                    @else
                    <input type="checkbox" readonly="" disabled="" class="form-check-input" id="s1" name="$key"
                        value="1">
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
                    <p>Patient did not fill medical profile
                    <p>
                        @endif
                </div>
            </div>
            <div role="tabpanel" class="tab-pane container mt-1" id="pres">
                <form id="recommendationForm" action="{{route('recommendations.store.pres')}}" method="post">
                    <h6>
                        <strong class="mt-2">Prescription</strong>


                    </h6>
                    <h5 id="imaging_name" class="mt-1 mb-1" style="color:red;"></h5>
                    <p id="imaging_msg" class="mt-1 mb-1 alert alert-warning"></p>
                    <p style="color:red" id="imaging_err"></p>
                    @csrf
                    <input hidden name="imaging_id" id="hid_imaging_id">
                    <input id="products" hidden="" type="text" name="product_list" value="0" />
                    <input id="lab_products" hidden="" type="text" name="lab_product_list" value="0" />
                    <input hidden="" id="pat_id" type="text" name="patient_id" value="{{123}}" />
                    <input hidden="" id="doc_id" type="text" name="doc_id" value="{{$getSession->doctor_id}}" />
                    <input hidden="" id="session_id" type="text" name="session_id" value="{{$getSession->id}}" />
                    <div class="pres">
                        <div id="empty">
                            <p>No prescription added.</p>
                        </div>
                    </div>
            </div>
            <div role="tabpanel" class="tab-pane container" id="history">
                <!-- <p class="p-sm mt-1">No Patient History Available</p> -->
                <!-- <p><strong>Last visit:</strong>29-feb-2020</p> -->
                <div class="row mt-2 mb-2">
                    <h6 class="col-7"><strong>Last visit: </strong>
                        @if($prev_sessions->count()<1) None @else {{$prev_sessions[0]->date}} @endif </h6>
                            <!-- <button class="float-right   btn btn-primary" data-toggle="modal" type="button" data-target= "#med_profile_modal">Medical Profile</button> -->
                </div>
                <div class="bg-secondary">
                    @forelse($prev_sessions as $session)
                    <a href="javascript:void(0);" class="list-group-item">
                        <div class="row">
                            <div class="col-8">
                                <p><strong>Provider: </strong> {{$session->doc}}</p>
                                <p><strong>Date:</strong>{{$session->date}}
                                <p>
                            </div>
                            <div class="col-4">
                                <button id="sess_{{$session->id}}" class="btn btn-primary prev_session" type="button"
                                    style="color:white;background-color:#0069d9;" data-toggle="modal"
                                    data-target="#session_modal" onclick="sessionDetails(this)">Details</button>
                            </div>
                        </div>
                    </a>
                    @empty
                    <a href="javascript:void(0);" class="list-group-item">No visits</a>
                    @endforelse

                </div>
            </div>
            <div role="tabpanel" class="tab-pane container mt-2" id="notes_tab">
                <label for="diagnosis" class="mt-2">Diagnosis</label>
                <input id="diagnosis" name="diagnosis" class="form-control mb-1" placeholder="Add diagnosis here...">
                <br>
                <textarea class="form-control mb-3" id="note-textarea" placeholder="Create a new note by typing or using voice recording." rows="10" name="note"></textarea>
                <center>
                <button id="start-record-btn" class="btn ui blue button" onclick="runSpeechRecognition()" type="button" title="Start Recording"><i class="fa fa-microphone"></i> Start Recording</button>
                <br>
                <br>
                <p id="recording-instructions">Press the <strong>Start Recording </strong> button and allow access.</p>
                </center>
            </div>
            <div role="tabpanel" class="tab-pane container mt-2" id="refer_tab">
                <h5>Select Specialization</h5>
                @foreach($specs as $spec)
                <a href="javascript:void(0);" data-toggle="modal" data-target="#refer_doc_modal" id="spec_{{$spec->id}}" onclick="getSpecializedDoctors(this)" class="list-group-item">
                    <div class="row">
                        <h5 spec_name="{{$spec->name}}" class="h5-sm steelblue-color pl-3">{{$spec->name}}</h5>
                    </div>
                </a>
                @endforeach
                </form>
            </div>

        </div>
    </div>
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

</script>
