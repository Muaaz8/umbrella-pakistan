@extends('layouts.admin')
@section('content')
<section class="content">

    <div class="container-fluid">
        <div class="block-header">
            <h2>Medical Profile</h2>
        </div>
        <div class="row clearfix">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Medical History<small>Share all your medical details here</small></h2>
                    </div>
                    <div class="card-body p-0">
                        <div class="col-md-12">
                            <form name="medical_profile" action="{{route('add_medical_profile')}}"
                                enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <p><b>Allergies to medications, radiations, dyes or other substances</b></p>
                                        <div class="form-line">
                                            @if(isset($profile))
                                            <input type="text" name="allergies" value="{{$profile['allergies']}}"
                                                class="form-control">
                                            @else
                                            <input type="text" name="allergies" class="form-control"
                                                placeholder="No/Yes. (If yes, then please provide list of medicines and type of reactions)">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <p><b>Medical History And Review Of Symptoms</b></p>
                                        <div class="checkbox form-check">
                                            @if(isset($profile))
                                            @if(strpos($profile['previous_symp'],'hypertension')=== false)
                                            <input id="m1" class="form-check-input" name="symp[]" value="hypertension"
                                                type="checkbox"><label for="m1">Hypertension</label>
                                            @else
                                            <input id="m1" class="form-check-input" name="symp[]" value="hypertension"
                                                checked="" type="checkbox"><label for="m1">Hypertension</label>
                                            @endif
                                            @else
                                            <input id="m1" class="form-check-input" name="symp[]" value="hypertension"
                                                type="checkbox"><label for="m1">Hypertension</label>
                                            @endif
                                        </div>
                                        <div class="checkbox form-check">
                                            @if(isset($profile))
                                            @if(strpos($profile['previous_symp'],'diabetes')=== false)
                                            <input id="m2" class="form-check-input" name="symp[]" value="diabetes"
                                                type="checkbox"><label for="m2">Diabetes</label>
                                            @else
                                            <input id="m2" class="form-check-input" name="symp[]" value="diabetes"
                                                checked="" type="checkbox"><label for="m2">Diabetes</label>
                                            @endif
                                            @else
                                            <input id="m2" class="form-check-input" name="symp[]" value="diabetes"
                                                type="checkbox"><label for="m2">Diabetes</label>
                                            @endif
                                        </div>
                                        <div class="checkbox form-check">
                                            @if(isset($profile))
                                            @if(strpos($profile['previous_symp'],'cancer')=== false)
                                            <input id="m3" class="form-check-input" name="symp[]" value="cancer"
                                                type="checkbox"><label for="m3">Cancer</label>
                                            @else
                                            <input id="m3" class="form-check-input" name="symp[]" value="cancer"
                                                checked="" type="checkbox"><label for="m3">Cancer</label>
                                            @endif
                                            @else
                                            <input id="m3" class="form-check-input" name="symp[]" value="cancer"
                                                type="checkbox"><label for="m3">Cancer</label>
                                            @endif
                                        </div>
                                        <div class="checkbox form-check">
                                            @if(isset($profile))
                                            @if(strpos($profile['previous_symp'],'heart')=== false)
                                            <input id="m4" class="form-check-input" name="symp[]" value="heart"
                                                type="checkbox"><label for="m4">Heart Disease</label>
                                            @else
                                            <input id="m4" class="form-check-input" name="symp[]" value="heart"
                                                checked="" type="checkbox"><label for="m4">Heart Disease</label>
                                            @endif
                                            @else
                                            <input id="m4" class="form-check-input" name="symp[]" value="heart"
                                                type="checkbox"><label for="m4">Heart Disease</label>
                                            @endif
                                        </div>
                                        <div class="checkbox form-check">
                                            @if(isset($profile))
                                            @if(strpos($profile['previous_symp'],'chest')=== false)
                                            <input id="m5" class="form-check-input" name="symp[]" value="chest"
                                                type="checkbox"><label for="m5">Chest Pain/chest tightness</label>
                                            @else
                                            <input id="m5" class="form-check-input" name="symp[]" value="chest"
                                                checked="" type="checkbox"><label for="m5">Chest Pain/chest
                                                tightness</label>
                                            @endif
                                            @else
                                            <input id="m5" class="form-check-input" name="symp[]" value="chest"
                                                type="checkbox"><label for="m5">Chest Pain/chest tightness</label>
                                            @endif
                                        </div>
                                        <div class="checkbox form-check">
                                            @if(isset($profile))
                                            @if(strpos($profile['previous_symp'],'shortness')=== false)
                                            <input id="m6" class="form-check-input" name="symp[]" value="shortness"
                                                type="checkbox"><label for="m6">Shortness of breath</label>
                                            @else
                                            <input id="m6" class="form-check-input" name="symp[]" value="shortness"
                                                checked="" type="checkbox"><label for="m6">Shortness of breath</label>
                                            @endif
                                            @else
                                            <input id="m6" class="form-check-input" name="symp[]" value="shortness"
                                                type="checkbox"><label for="m6">Shortness of breath</label>
                                            @endif
                                        </div>
                                        <div class="checkbox form-check">
                                            @if(isset($profile))
                                            @if(strpos($profile['previous_symp'],'swollen')=== false)
                                            <input id="m7" class="form-check-input" name="symp[]" value="swollen"
                                                type="checkbox"><label for="m7">Swollen Ankles</label>
                                            @else
                                            <input id="m7" class="form-check-input" name="symp[]" value="swollen"
                                                checked="" type="checkbox"><label for="m7">Swollen Ankles</label>
                                            @endif
                                            @else
                                            <input id="m7" class="form-check-input" name="symp[]" value="swollen"
                                                type="checkbox"><label for="m7">Swollen Ankles</label>
                                            @endif
                                        </div>
                                        <div class="checkbox form-check">
                                            @if(isset($profile))
                                            @if(strpos($profile['previous_symp'],'palpitation')=== false)
                                            <input id="m8" class="form-check-input" name="symp[]" value="palpitation"
                                                type="checkbox"><label for="m8">Palpitation/Irregular Heartbeat</label>
                                            @else
                                            <input id="m8" class="form-check-input" name="symp[]" value="palpitation"
                                                checked="" type="checkbox"><label for="m8">Palpitation/Irregular
                                                Heartbeat</label>
                                            @endif
                                            @else
                                            <input id="m8" class="form-check-input" name="symp[]" value="palpitation"
                                                type="checkbox"><label for="m8">Palpitation/Irregular Heartbeat</label>
                                            @endif
                                        </div>
                                        <div class="checkbox form-check">
                                            @if(isset($profile))
                                            @if(strpos($profile['previous_symp'],'stroke')=== false)
                                            <input id="m9" class="form-check-input" name="symp[]" value="stroke"
                                                type="checkbox"><label for="m9">Stroke</label>
                                            @else
                                            <input id="m9" class="form-check-input" name="symp[]" value="stroke"
                                                checked="" type="checkbox"><label for="m9">Stroke</label>
                                            @endif
                                            @else
                                            <input id="m9" class="form-check-input" name="symp[]" value="stroke"
                                                type="checkbox"><label for="m9">Stroke</label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card pl-4">
                                <div class="header">
                                    <h2>Immunization History</h2>
                                </div>
                                <div class="card-body p-0">
                                    <div class="col-md-12">


                                        @php
                                        $pneumovax=false;
                                        @endphp
                                        @if(isset($profile))
                                        @if($profile->immunization_history != "")
                                        @foreach($profile->immunization_history as $imm)
                                        @if($imm->name=='pneumovax'&& $pneumovax==false)
                                        @php $pneumovax=true; @endphp
                                        <div class="d-flex">
                                            <div class="checkbox form-check col-md-6">
                                                @if($imm->flag == "yes")
                                                <input id="pneumovax" class="form-check-input immunization-pneumovax"
                                                    name="immunization_history[]" value="pneumovax" type="checkbox"
                                                    checked><label for="pneumovax">Pneumovax</label>
                                                @else
                                                <input id="pneumovax" class="form-check-input immunization-pneumovax"
                                                    name="immunization_history[]" value="pneumovax"
                                                    type="checkbox"><label for="pneumovax">Pneumovax</label>

                                                @endif
                                            </div>
                                            <div class="checkbox form-check col-md-6">
                                                <label for="" class="mr-5 ml-5">When</label>
                                                <input class="form-check-input" id="when_pneumovax"
                                                    name="when_pneumovax" value="{{$imm->when}}" type="month"
                                                    disabled="disabled">
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                        @endif
                                        @endif

                                        @if($pneumovax==false)
                                        <div class="d-flex">
                                            <div class="checkbox form-check col-md-6">
                                                <input id="pneumovax" class="form-check-input immunization-pneumovax"
                                                    name="immunization_history[]" value="pneumovax"
                                                    type="checkbox"><label for="pneumovax">Pneumovax</label>
                                            </div>
                                            <div class="checkbox form-check col-md-6">
                                                <label for="" class="mr-5 ml-5">When</label>
                                                <input id="when_pneumovax" class="form-check-input"
                                                    name="when_pneumovax" value="" type="month">
                                            </div>
                                        </div>
                                        @endif


                                        @php
                                        $h1n1=false;
                                        @endphp
                                        @if(isset($profile))
                                        @if($profile->immunization_history != "")
                                        @foreach($profile->immunization_history as $imm)
                                        @if($imm->name=='h1n1'&& $h1n1==false)
                                        @php $h1n1=true; @endphp
                                        <div class="d-flex">
                                            <div class="checkbox form-check col-md-6">
                                                @if($imm->flag == "yes")
                                                <input id="h1n1" class="form-check-input immunization-h1n1"
                                                    name="immunization_history[]" value="h1n1" type="checkbox"
                                                    checked><label for="h1n1">H1N1</label>
                                                @else
                                                <input id="h1n1" class="form-check-input  immunization-h1n1"
                                                    name="immunization_history[]" value="h1n1" type="checkbox"><label
                                                    for="h1n1">H1N1</label>
                                                @endif
                                            </div>
                                            <div class="checkbox form-check col-md-6">
                                                <label for="" class="mr-5 ml-5">When</label>
                                                <input id="when_h1n1" class="form-check-input" name="when_h1n1"
                                                    value="{{$imm->when}}" type="month" disabled="disabled">
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                        @endif
                                        @endif
                                        @if($h1n1==false)
                                        <div class="d-flex">
                                            <div class="checkbox form-check col-md-6">
                                                <input id="h1n1" class="form-check-input immunization-h1n1"
                                                    name="immunization_history[]" value="h1n1" type="checkbox"><label
                                                    for="h1n1">H1N1</label>
                                            </div>
                                            <div class="checkbox form-check col-md-6">
                                                <label for="" class="mr-5 ml-5">When</label>
                                                <input id="when_h1n1" class="form-check-input" name="when_h1n1" value=""
                                                    type="month">
                                            </div>
                                        </div>
                                        @endif
                                        @php
                                        $annual_flu=false;
                                        @endphp
                                        @if(isset($profile))
                                        @if($profile->immunization_history != "")
                                        @foreach($profile->immunization_history as $imm)
                                        @if($imm->name=='annual_flu'&& $annual_flu==false)
                                        @php $annual_flu=true; @endphp
                                        <div class="d-flex">

                                            <div class="checkbox form-check col-md-6">
                                                @if($imm->flag == "yes")
                                                <input id="annual_flu" class="form-check-input  immunization-annual"
                                                    name="immunization_history[]" value="annual_flu" type="checkbox"
                                                    checked><label for="annual_flu">Annual flu</label>
                                                @else
                                                <input id="annual_flu" class="form-check-input  immunization-annual"
                                                    name="immunization_history[]" value="annual_flu"
                                                    type="checkbox"><label for="annual_flu">Annual flu</label>
                                                @endif

                                            </div>

                                            <div class="checkbox form-check col-md-6">
                                                <label for="" class="mr-5 ml-5">When</label>
                                                <input id="when_annual_flu" class="form-check-input"
                                                    name="when_annual_flu" value="{{$imm->when}}" type="month"
                                                    disabled="disabled">
                                            </div>
                                        </div>

                                        @endif
                                        @endforeach
                                        @endif
                                        @endif
                                        @if($annual_flu==false)
                                        <div class="d-flex">

                                            <div class="checkbox form-check col-md-6">
                                                <input id="annual_flu" class="form-check-input  immunization-annual"
                                                    name="immunization_history[]" value="annual_flu"
                                                    type="checkbox"><label for="annual_flu">Annual flu</label>
                                            </div>
                                            <div class="checkbox form-check col-md-6">

                                                <label for="" class="mr-5 ml-5">When</label>
                                                <input id="when_annual_flu" class="form-check-input"
                                                    name="when_annual_flu" value="" type="month">
                                            </div>
                                        </div>
                                        @endif
                                        @php
                                        $hepatitis_b=false;
                                        @endphp
                                        @if(isset($profile))
                                        @if($profile->immunization_history != "")
                                        @foreach($profile->immunization_history as $imm)
                                        @if($imm->name=='hepatitis_b'&& $hepatitis_b==false)
                                        @php $hepatitis_b=true; @endphp
                                        <div class="d-flex">
                                            @if($imm->flag == "yes")
                                            <div class="checkbox form-check col-md-6">
                                                <input id="hepatitis_b" class="form-check-input  immunization-hep"
                                                    name="immunization_history[]" value="hepatitis_b" type="checkbox"
                                                    checked><label for="hepatitis_b">Hepatitis B</label>
                                                @else
                                                <div class="checkbox form-check col-md-6">
                                                    <input id="hepatitis_b" class="form-check-input  immunization-hep"
                                                        name="immunization_history[]" value="hepatitis_b"
                                                        type="checkbox"><label for="hepatitis_b">Hepatitis B</label>
                                                    @endif

                                                </div>
                                                <div class="checkbox form-check col-md-6">

                                                    <label for="" class="mr-5 ml-5">When</label>
                                                    <input id="when_hepatits_b" class="form-check-input"
                                                        name="when_hepatits_b" value="{{$imm->when}}" type="month"
                                                        disabled="disabled">
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                            @endif
                                            @endif
                                            @if($hepatitis_b==false)
                                            <div class="d-flex ">

                                                <div class="checkbox form-check col-md-6">
                                                    <input id="hepatitis_b" class="form-check-input  immunization-hep"
                                                        name="immunization_history[]" value="hepatitis_b"
                                                        type="checkbox"><label for="hepatitis_b">Hepatitis B</label>
                                                </div>
                                                <div class="checkbox form-check col-md-6">

                                                    <label for="" class="mr-5 ml-5">When</label>
                                                    <input id="when_hepatits_b" class="form-check-input"
                                                        name="when_hepatits_b" value="" type="month"
                                                        disabled="disabled">
                                                </div>
                                            </div>
                                            @endif
                                            @php
                                            $tetanus=false;
                                            @endphp
                                            @if(isset($profile))
                                            @if($profile->immunization_history != "")
                                            @foreach($profile->immunization_history as $imm)
                                            @if($imm->name=='tetanus'&& $tetanus==false)
                                            @php $tetanus=true; @endphp
                                            <div class="d-flex">
                                                <div class="checkbox form-check col-md-6">
                                                    @if($imm->flag == "yes")
                                                    <input id="tetanus" class="form-check-input immunization-tetanus"
                                                        name="immunization_history[]" value="tetanus" type="checkbox"
                                                        checked><label for="tetanus">Tetanus</label>
                                                    @else
                                                    <input id="tetanus" class="form-check-input  immunization-tetanus"
                                                        name="immunization_history[]" value="tetanus"
                                                        type="checkbox"><label for="tetanus">Tetanus</label>
                                                    @endif
                                                </div>
                                                <div class="checkbox form-check col-md-6">
                                                    <label for="" class="mr-5 ml-5">When</label>
                                                    <input id="when_tetanus" class="form-check-input"
                                                        name="when_tetanus" value="{{$imm->when}}" disabled="disabled"
                                                        type="month">
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                            @endif
                                            @endif
                                            @if($tetanus==false)
                                            <div class="d-flex">
                                                <div class="checkbox form-check col-md-6">
                                                    <input id="tetanus" class="form-check-input  immunization-tetanus"
                                                        name="immunization_history[]" value="tetanus"
                                                        type="checkbox"><label for="tetanus">Tetanus</label>
                                                </div>
                                                <div class="checkbox form-check col-md-6">
                                                    <label for="" class="mr-5 ml-5">When</label>
                                                    <input id="when_tetanus" class="form-check-input"
                                                        name="when_tetanus" value="" type="month" disabled="disabled">
                                                </div>
                                            </div>
                                            @endif
                                            @php
                                            $others=false;
                                            @endphp
                                            @if(isset($profile))
                                            @if($profile->immunization_history != "")
                                            @foreach($profile->immunization_history as $imm)
                                            @if($imm->name=='others'&& $others==false)
                                            @php $others=true; @endphp

                                            <div class="d-flex">
                                                <div class="checkbox form-check col-md-6">
                                                    @if($imm->flag == "yes")

                                                    <input id="others" class="form-check-input  immunization-other"
                                                        name="immunization_history[]" value="others" type="checkbox"
                                                        checked><label for="others">Others</label>
                                                    @else
                                                    <input id="others" class="form-check-input immunization-other"
                                                        name="immunization_history[]" value="others"
                                                        type="checkbox"><label for="others">Others</label>
                                                    @endif

                                                </div>
                                                <div class="checkbox form-check col-md-6">
                                                    <label for="" class="mr-5 ml-5">When</label>
                                                    <input id="when_others" class="form-check-input" name="when_others"
                                                        value="{{$imm->when}}" type="month" disabled="disabled">
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                            @endif
                                            @endif
                                            @if($others==false)
                                            <div class="d-flex">

                                                <div class="checkbox form-check col-md-6">
                                                    <input id="others" class="form-check-input immunization-other"
                                                        name="immunization_history[]" value="others"
                                                        type="checkbox"><label for="others">Others</label>
                                                </div>
                                                <div class="checkbox form-check col-md-6">

                                                    <label for="" class="mr-5 ml-5">When</label>
                                                    <input id="when_others" class="form-check-input" name="when_others"
                                                        value="" type="month" disabled="disabled">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="header">
                                        <h2>Family History<small></small></h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12">



                                            <!-- <p>Patient did not fill medical profile<p> -->

                                            <p class="text-bold"><b>Has any of your family member (including parents,
                                                    grandparents, and siblings) ever had the following</b></p>
                                            <div class="col-md-12 row">
                                                <div class="col-md-4">
                                                    <p><b>Disease</b></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><b>Which family member?</b></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><b>Approx. age when diagnosed</b></p>
                                                </div>
                                            </div>
                                            <hr>
                                            @php
                                            $hyp_done=false;
                                            @endphp
                                            <div class="col-md-12 row">
                                                <div class="col-md-4">
                                                    <span><b>Hypertension</b></span>
                                                </div>
                                                @if(isset($profile))
                                                @foreach($profile->family_history as $history)
                                                @if($history->disease=='hypertension'&&$hyp_done==false)
                                                @php $hyp_done=true; @endphp
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_hypertension">
                                                        <option value="">None</option>
                                                        <option
                                                            {{($history->family == "parent") ? "selected = 'selected'" : ''}}
                                                            value="parent">Parent</option>
                                                        <option
                                                            {{($history->family == "grand") ? "selected = 'selected'" : ''}}
                                                            value="grand">Grandparent</option>
                                                        <option
                                                            {{($history->family == "sibling") ? "selected = 'selected'" : ''}}
                                                            value="sibling">Sibling</option>
                                                        <option
                                                            {{($history->family == "others") ? "selected = 'selected'" : ''}}
                                                            value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_hypertension_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly value="{{$history->age}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                                @endif
                                                @if($hyp_done==false)
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_hypertension">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_hypertension_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @php
                                            $heart_done=false;
                                            @endphp
                                            <div class="col-md-12 row">
                                                <div class="col-md-4">
                                                    <span><b>Heart Disease</b></span>
                                                </div>
                                                @if(isset($profile))
                                                @foreach($profile->family_history as $history)
                                                @if($history->disease=='heart'&&$heart_done==false)
                                                @php $heart_done=true; @endphp
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_heart">
                                                        <option value="">None</option>
                                                        <option
                                                            {{($history->family == "parent") ? "selected = 'selected'" : ''}}
                                                            value="parent">Parent</option>
                                                        <option
                                                            {{($history->family == "grand") ? "selected = 'selected'" : ''}}
                                                            value="grand">Grandparent</option>
                                                        <option
                                                            {{($history->family == "sibling") ? "selected = 'selected'" : ''}}
                                                            value="sibling">Sibling</option>
                                                        <option
                                                            {{($history->family == "others") ? "selected = 'selected'" : ''}}
                                                            value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_heart_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly value="{{$history->age}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                                @endif
                                                @if($heart_done==false)
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_heart">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_heart_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @php
                                            $diabetes_done=false;
                                            @endphp
                                            <div class="col-md-12 row">
                                                <div class="col-md-4">
                                                    <span><b>Diabetes</b></span>
                                                </div>
                                                @if(isset($profile))
                                                @foreach($profile->family_history as $history)
                                                @if($history->disease=='diabetes'&& $diabetes_done==false)
                                                @php $diabetes_done=true; @endphp
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_diabetes">
                                                        <option value="">None</option>
                                                        <option
                                                            {{($history->family == "parent") ? "selected = 'selected'" : ''}}
                                                            value="parent">Parent</option>
                                                        <option
                                                            {{($history->family == "grand") ? "selected = 'selected'" : ''}}
                                                            value="grand">Grandparent</option>
                                                        <option
                                                            {{($history->family == "sibling") ? "selected = 'selected'" : ''}}
                                                            value="sibling">Sibling</option>
                                                        <option
                                                            {{($history->family == "others") ? "selected = 'selected'" : ''}}
                                                            value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_diabetes_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly value="{{$history->age}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                                @endif
                                                @if($diabetes_done==false)
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_diabetes">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_diabetes_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @php
                                            $stroke_done=false;
                                            @endphp
                                            <div class="col-md-12 row">
                                                <div class="col-md-4">
                                                    <span><b>Stroke</b></span>
                                                </div>
                                                @if(isset($profile))
                                                @foreach($profile->family_history as $history)
                                                @if($history->disease=='stroke'&& $stroke_done==false)
                                                @php $stroke_done=true; @endphp
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_stroke">
                                                        <option value="">None</option>
                                                        <option
                                                            {{($history->family == "parent") ? "selected = 'selected'" : ''}}
                                                            value="parent">Parent</option>
                                                        <option
                                                            {{($history->family == "grand") ? "selected = 'selected'" : ''}}
                                                            value="grand">Grandparent</option>
                                                        <option
                                                            {{($history->family == "sibling") ? "selected = 'selected'" : ''}}
                                                            value="sibling">Sibling</option>
                                                        <option
                                                            {{($history->family == "others") ? "selected = 'selected'" : ''}}
                                                            value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_stroke_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly value="{{$history->age}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                                @endif
                                                @if($stroke_done==false)
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_stroke">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_stroke_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @php
                                            $mental_done=false;
                                            @endphp
                                            <div class="col-md-12 row">
                                                <div class="col-md-4">
                                                    <span><b>Mental Disease</b></span>
                                                </div>
                                                @if(isset($profile))
                                                @foreach($profile->family_history as $history)
                                                @if($history->disease=='mental'&&$mental_done==false)
                                                @php $mental_done=true; @endphp
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_mental">
                                                        <option value="">None</option>
                                                        <option
                                                            {{($history->family == "parent") ? "selected = 'selected'" : ''}}
                                                            value="parent">Parent</option>
                                                        <option
                                                            {{($history->family == "grand") ? "selected = 'selected'" : ''}}
                                                            value="grand">Grandparent</option>
                                                        <option
                                                            {{($history->family == "sibling") ? "selected = 'selected'" : ''}}
                                                            value="sibling">Sibling</option>
                                                        <option
                                                            {{($history->family == "others") ? "selected = 'selected'" : ''}}
                                                            value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_mental_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly value="{{$history->age}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                                @endif
                                                @if($mental_done==false)
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_mental">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_mental_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @php
                                            $drugs_done=false;
                                            @endphp
                                            <div class="col-md-12 row">
                                                <div class="col-md-4">
                                                    <span><b>Drugs/Alcohol Addiction</b></span>
                                                </div>
                                                @if(isset($profile))
                                                @foreach($profile->family_history as $history)
                                                @if($history->disease=='drugs'&& $drugs_done==false)
                                                @php $drugs_done=true; @endphp
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_drugs">
                                                        <option value="">None</option>
                                                        <option
                                                            {{($history->family == "parent") ? "selected = 'selected'" : ''}}
                                                            value="parent">Parent</option>
                                                        <option
                                                            {{($history->family == "grand") ? "selected = 'selected'" : ''}}
                                                            value="grand">Grandparent</option>
                                                        <option
                                                            {{($history->family == "sibling") ? "selected = 'selected'" : ''}}
                                                            value="sibling">Sibling</option>
                                                        <option
                                                            {{($history->family == "others") ? "selected = 'selected'" : ''}}
                                                            value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_drugs_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly value="{{$history->age}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                                @endif
                                                @if($drugs_done==false)
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_drugs">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_drugs_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>

                                            @php
                                            $Glaucoma_done=false;
                                            @endphp
                                            <div class="col-md-12 row">
                                                <div class="col-md-4">
                                                    <span><b>Glaucoma</b></span>
                                                </div>
                                                @if(isset($profile))
                                                @foreach($profile->family_history as $history)
                                                @if($history->disease=='glaucoma'&& $Glaucoma_done==false)
                                                @php $Glaucoma_done=true; @endphp
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_glaucoma">
                                                        <option value="">None</option>
                                                        <option
                                                            {{($history->family == "parent") ? "selected = 'selected'" : ''}}
                                                            value="parent">Parent</option>
                                                        <option
                                                            {{($history->family == "grand") ? "selected = 'selected'" : ''}}
                                                            value="grand">Grandparent</option>
                                                        <option
                                                            {{($history->family == "sibling") ? "selected = 'selected'" : ''}}
                                                            value="sibling">Sibling</option>
                                                        <option
                                                            {{($history->family == "others") ? "selected = 'selected'" : ''}}
                                                            value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_glaucoma_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly value="{{$history->age}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                                @endif
                                                @if($Glaucoma_done==false)
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_glaucoma">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_glaucoma_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @php
                                            $bleeding_done=false;
                                            @endphp
                                            <div class="col-md-12 row">
                                                <div class="col-md-4">
                                                    <span><b>Bleeding Disease</b></span>
                                                </div>
                                                @if(isset($profile))
                                                @foreach($profile->family_history as $history)
                                                @if($history->disease=='bleeding'&& $bleeding_done==false)
                                                @php $bleeding_done=true; @endphp
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_bleeding">
                                                        <option value="">None</option>
                                                        <option
                                                            {{($history->family == "parent") ? "selected = 'selected'" : ''}}
                                                            value="parent">Parent</option>
                                                        <option
                                                            {{($history->family == "grand") ? "selected = 'selected'" : ''}}
                                                            value="grand">Grandparent</option>
                                                        <option
                                                            {{($history->family == "sibling") ? "selected = 'selected'" : ''}}
                                                            value="sibling">Sibling</option>
                                                        <option
                                                            {{($history->family == "others") ? "selected = 'selected'" : ''}}
                                                            value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_bleeding_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly value="{{$history->age}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                                @endif
                                                @if($bleeding_done==false)
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_bleeding">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_bleeding_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @php
                                            $other_done=false;
                                            @endphp
                                            @if(isset($profile))
                                            @if($profile->family_history != "" && !empty($profile->family_history))
                                            @if(!in_array($profile->family_history[array_key_last($profile->family_history)]->disease,
                                            $diseases)&& $other_done==false)
                                            @php $other_done=true; @endphp
                                            <div class=" row">
                                                <div class="mt-3">
                                                    <span><b>Others</b></span>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="text" name="f_others_name"
                                                                value="{{$profile->family_history[array_key_last($profile->family_history)]->disease}}"
                                                                placeholder="Name of disease" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <select class="form-control relative" name="f_others">
                                                        <option value="">None</option>
                                                        <option
                                                            {{($history->family == "parent") ? "selected = 'selected'" : ''}}
                                                            value="parent">Parent</option>
                                                        <option
                                                            {{($history->family == "grand") ? "selected = 'selected'" : ''}}
                                                            value="grand">Grandparent</option>
                                                        <option
                                                            {{($history->family == "sibling") ? "selected = 'selected'" : ''}}
                                                            value="sibling">Sibling</option>
                                                        <option
                                                            {{($history->family == "others") ? "selected = 'selected'" : ''}}
                                                            value="others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mt-0">
                                                        <div class="form-line">
                                                            <input type="number" name="f_others_age"
                                                                class="form-control age" min="0" max="150"
                                                                placeholder="Age" readonly value="{{$history->age}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endif
                                            @endif
                                            @if($other_done==false)
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-2 mt-2">
                                                        <span><b>Others</b></span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mt-0">
                                                            <div class="form-line">
                                                                <input type="text" name="f_others_name"
                                                                    placeholder="Name of disease" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select class="form-control relative" name="f_others">
                                                            <option value="">None</option>
                                                            <option value="parent">Parent</option>
                                                            <option value="grand">Grandparent</option>
                                                            <option value="sibling">Sibling</option>
                                                            <option value="others">Others</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mt-0">
                                                            <div class="form-line">
                                                                <input type="number" name="f_others_age"
                                                                    class="form-control age" min="0" max="150"
                                                                    placeholder="Age" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        @endif
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mt-4">
                                            <div class="form-line">
                                                <h3>Comment</h3>
                                                @if(isset($profile))
                                                <textarea type="text" name="comment" maxlength="100"
                                                    class="form-control"
                                                    placeholder="Comment Other">{{$profile['comment']}}</textarea>
                                                @else
                                                <textarea type="text" name="comment" class="form-control"
                                                    placeholder="Comment Other"></textarea>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <!-- <div class="form-group mt-4"> -->
                                        <!-- <div class="form-line"> -->
                                        <h3>Medical Record</h3>
                                        @php
                                        $user=auth()->user();
                                        $record=$user->med_record_file;
                                        @endphp
                                        @if($record!=null)
                                        <div class="row">
                                            <div class="col-12 custom-file">
                                                <input type="file" hidden class="custom-file-input customFile"
                                                    name="med_record" value="{{$record}}" id="customFile"
                                                    accept="application/pdf">
                                                <label class="custom-file-label col-12" for="customFile"
                                                    style="padding:10px 20px;
                                                            border-radius:12px; border:grey 1px solid">{{$record}}</label>

                                            </div>
                                            <div class="p-0" style="    position: absolute;
                                                top: 58px;
                                                right: 30px;
                                                border-radius: 50px;">
                                                <button type="button" id="cancel_file" class="cancel "
                                                    style="border-radius:60px">
                                                    <i style="" class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>

                                        @else
                                        <div class="row">
                                            <div class="custom-file col-12 ">
                                                <input type="file" hidden class="custom-file-input customFile"
                                                    name="med_record" id="customFile" accept="application/pdf">
                                                <label class="custom-file-label med-record col-12"
                                                    for="customFile">Select
                                                    File</label>

                                            </div>
                                            <div class="p-0 med-btn" style="">
                                                <button type="button" id="cancel_file" class="cancel btn btn-raised
                                                                btn-circle waves-effect waves-circle
                                                                waves-float m-0">
                                                    <i class="fa fa-times med-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                        <!-- </div> -->
                                        <!-- </div> -->
                                    </div>
                                    @if($update)
                                    <!-- <button type="submit" class="col-12
                                         btn btn-raised btn-success form-control"
                                        style="color:white;background-color:#70CEBE !important;">Update</button> -->
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-center mt-3">
                                            <button type="submit" class="callbtn">Update</button>
                                        </div>
                                    </div>
                                    @else
                                    <button type="submit" class="col-9 btn btn-raised btn-success form-control"
                                        style="color:white;background-color:#70CEBE !important;">Next</button>
                                    @endif
                                </div>
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
@endsection
@section('script')
<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
$('.cancel').click(function() {
    console.log('ghjkl');
    $('.customFile').val('');
    $('.customFile').siblings(".custom-file-label").removeClass("selected").html(
        'No File Added');
})
$('.relative').on('change', function() {
    console.log($(this).val())
    rel = $(this).val();
    if (rel != '') {
        // console.log($(this).closest('div').next().find('.age').val())
        element = $(this).closest('div').next().find('.age');
        element.removeAttr('readonly');
        element.attr('required', 'required');
    } else {
        element = $(this).closest('div').next().find('.age');
        element.val('');
        element.attr('readonly', 'readonly');

    }

});
$(document).ready(function() {

    $(".immunization-pneumovax").on('change', function() {
        if ($('#pneumovax').prop('checked')) {
            // alert("Check box in Checked");
            $("#when_pneumovax").prop('disabled', false);

        } else {
            // alert("Check box is Unchecked");
            $("#when_pneumovax").prop('disabled', true);
            $("#when_pneumovax").val("");
        }
    }).change();
    $(".immunization-h1n1").on('change', function() {
        if ($('#h1n1').prop('checked')) {
            // alert("Check box in Checked");
            $("#when_h1n1").prop('disabled', false);
        } else {
            // alert("Check box is Unchecked");
            $("#when_h1n1").prop('disabled', true);
            $("#when_h1n1").val("");

        }
    }).change();
    $(".immunization-annual").on('change', function() {
        if ($('#annual_flu').prop('checked')) {
            // alert("Check box in Checked");
            $("#when_annual_flu").prop('disabled', false);
        } else {
            // alert("Check box is Unchecked");
            $("#when_annual_flu").prop('disabled', true);
            $("#when_annual_flu").val("");

        }
    }).change();
    $(".immunization-hep").on('change', function() {
        if ($('#hepatitis_b').prop('checked')) {
            // alert("Check box in Checked");
            $("#when_hepatits_b").prop('disabled', false);
        } else {
            // alert("Check box is Unchecked");
            $("#when_hepatits_b").prop('disabled', true);
            $("#when_hepatits_b").val("");

        }
    }).change();
    $(".immunization-tetanus").on('change', function() {
        if ($('#tetanus').prop('checked')) {
            // alert("Check box in Checked");
            $("#when_tetanus").prop('disabled', false);
        } else {
            // alert("Check box is Unchecked");
            $("#when_tetanus").prop('disabled', true);
            $("#when_tetanus").val("");

        }
    }).change();
    $(".immunization-other").on('change', function() {
        if ($('#others').prop('checked')) {
            // alert("Check box in Checked");
            $("#when_others").prop('disabled', false);
        } else {
            // alert("Check box is Unchecked");
            $("#when_others").prop('disabled', true);
            $("#when_others").val("");

        }
    }).change();
});
</script>
@endsection