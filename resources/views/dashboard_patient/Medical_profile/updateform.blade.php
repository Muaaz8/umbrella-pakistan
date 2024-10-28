@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Medical Profile</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
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
                element = $(this).closest('td').next().find('.age');
                element.removeAttr('readonly');
                element.attr('required', 'required');
            } else {
                element = $(this).closest('td').next().find('.age');
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

        function redirect() {
            $('#redirect_modal').modal('hide');
            $("#medical_profile").submit();
        }
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="col-11 m-auto">
            <div class="account-setting-wrapper edit_med_profile bg-white">
                <div class="d-flex justify-content-between flex-wrap align-items-center border-bottom">
                    <div>
                        <h4>MEDICAL HISTORY <br>
                            <p class="fs-6 fw-normal">All your medical details are here</p>
                        </h4>
                    </div>
                    <form id="medical_profile" name="medical_profile" action="{{ route('add_medical_profile') }}"
                        enctype="multipart/form-data" method="post">
                        @csrf
                        <div>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#redirect_modal"
                                class="btn process-pay">Update Record</button>
                        </div>
                </div>
                <div class="pb-3">
                    <div class="row py-3">
                        <div class="col-md-12">
                            <label for="firstname">Allergies to medications, radiations, dyes or other substances</label>
                            @if (isset($profile))
                                <input type="text" name='allergies' class="bg-light form-control"
                                    value="{{ $profile['allergies'] }}">
                            @else
                                <input type="text" name='allergies' class="bg-light form-control" value=""
                                    required>
                            @endif
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-md-5 pt-md-0 pt-3">
                            <div class="card" style="width: 100%;">
                                <div class="card-header">
                                    Medical History And Review Of Symptoms
                                </div>
                                <div class="inquiry-form-checkbox">
                                    @if (isset($profile))
                                        @if (strpos($profile['previous_symp'], 'hypertension') === false)
                                            <input id="m1" class="form-check-input" name="symp[]"
                                                value="hypertension" type="checkbox"><label
                                                for="m1">Hypertension</label>
                                        @else
                                            <input id="m1" class="form-check-input" name="symp[]"
                                                value="hypertension" checked="" type="checkbox"><label
                                                for="m1">Hypertension</label>
                                        @endif
                                    @else
                                        <input id="m1" class="form-check-input" name="symp[]" value="hypertension"
                                            type="checkbox"><label for="m1">Hypertension</label>
                                    @endif
                                </div>
                                <div class="inquiry-form-checkbox">
                                    @if (isset($profile))
                                        @if (strpos($profile['previous_symp'], 'diabetes') === false)
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
                                <div class="inquiry-form-checkbox">
                                    @if (isset($profile))
                                        @if (strpos($profile['previous_symp'], 'cancer') === false)
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
                                <div class="inquiry-form-checkbox">
                                    @if (isset($profile))
                                        @if (strpos($profile['previous_symp'], 'heart') === false)
                                            <input id="m4" class="form-check-input" name="symp[]" value="heart"
                                                type="checkbox"><label for="m4">Heart
                                                Disease</label>
                                        @else
                                            <input id="m4" class="form-check-input" name="symp[]" value="heart"
                                                checked="" type="checkbox"><label for="m4">Heart
                                                Disease</label>
                                        @endif
                                    @else
                                        <input id="m4" class="form-check-input" name="symp[]" value="heart"
                                            type="checkbox"><label for="m4">Heart
                                            Disease</label>
                                    @endif
                                </div>
                                <div class="inquiry-form-checkbox">
                                    @if (isset($profile))
                                        @if (strpos($profile['previous_symp'], 'chest') === false)
                                            <input id="m5" class="form-check-input" name="symp[]" value="chest"
                                                type="checkbox"><label for="m5">Chest
                                                Pain/chest tightness</label>
                                        @else
                                            <input id="m5" class="form-check-input" name="symp[]" value="chest"
                                                checked="" type="checkbox"><label for="m5">Chest Pain/chest
                                                tightness</label>
                                        @endif
                                    @else
                                        <input id="m5" class="form-check-input" name="symp[]" value="chest"
                                            type="checkbox"><label for="m5">Chest
                                            Pain/chest tightness</label>
                                    @endif
                                </div>
                                <div class="inquiry-form-checkbox">
                                    @if (isset($profile))
                                        @if (strpos($profile['previous_symp'], 'shortness') === false)
                                            <input id="m6" class="form-check-input" name="symp[]"
                                                value="shortness" type="checkbox"><label for="m6">Shortness of
                                                breath</label>
                                        @else
                                            <input id="m6" class="form-check-input" name="symp[]"
                                                value="shortness" checked="" type="checkbox"><label
                                                for="m6">Shortness of breath</label>
                                        @endif
                                    @else
                                        <input id="m6" class="form-check-input" name="symp[]" value="shortness"
                                            type="checkbox"><label for="m6">Shortness
                                            of breath</label>
                                    @endif
                                </div>
                                <div class="inquiry-form-checkbox">
                                    @if (isset($profile))
                                        @if (strpos($profile['previous_symp'], 'swollen') === false)
                                            <input id="m7" class="form-check-input" name="symp[]"
                                                value="swollen" type="checkbox"><label for="m7">Swollen
                                                Ankles</label>
                                        @else
                                            <input id="m7" class="form-check-input" name="symp[]"
                                                value="swollen" checked="" type="checkbox"><label
                                                for="m7">Swollen Ankles</label>
                                        @endif
                                    @else
                                        <input id="m7" class="form-check-input" name="symp[]" value="swollen"
                                            type="checkbox"><label for="m7">Swollen
                                            Ankles</label>
                                    @endif
                                </div>
                                <div class="inquiry-form-checkbox">
                                    @if (isset($profile))
                                        @if (strpos($profile['previous_symp'], 'palpitation') === false)
                                            <input id="m8" class="form-check-input" name="symp[]"
                                                value="palpitation" type="checkbox"><label
                                                for="m8">Palpitation/Irregular Heartbeat</label>
                                        @else
                                            <input id="m8" class="form-check-input" name="symp[]"
                                                value="palpitation" checked="" type="checkbox"><label
                                                for="m8">Palpitation/Irregular
                                                Heartbeat</label>
                                        @endif
                                    @else
                                        <input id="m8" class="form-check-input" name="symp[]"
                                            value="palpitation" type="checkbox"><label
                                            for="m8">Palpitation/Irregular Heartbeat</label>
                                    @endif
                                </div>
                                <div class="inquiry-form-checkbox">
                                    @if (isset($profile))
                                        @if (strpos($profile['previous_symp'], 'stroke') === false)
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
                        <div class="col-md-7">
                            <div class="card" style="width: 100%">
                                <div class="card-header">
                                    IMMUNIZATION HISTORY
                                </div>
                                <div class="card-body p-0">
                                    <div class="col-md-12">


                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item justify-content-between">
                                                @php
                                                    $pneumovax = false;
                                                @endphp
                                                <div>
                                                    @if (isset($profile))
                                                        @if ($profile->immunization_history != '')
                                                            @foreach ($profile->immunization_history as $imm)
                                                                @if ($imm->name == 'pneumovax' && $pneumovax == false)
                                                                    @php $pneumovax=true; @endphp
                                                                    <div class="d-flex justify-content-between flex-wrap">
                                                                        <div class="checkbox form-check ">
                                                                            @if ($imm->flag == 'yes')
                                                                                <input id="pneumovax"
                                                                                    class="form-check-input immunization-pneumovax"
                                                                                    name="immunization_history[]"
                                                                                    value="pneumovax" type="checkbox"
                                                                                    checked><label
                                                                                    for="pneumovax">Pneumovax</label>
                                                                            @else
                                                                                <input id="pneumovax"
                                                                                    class="form-check-input immunization-pneumovax"
                                                                                    name="immunization_history[]"
                                                                                    value="pneumovax"
                                                                                    type="checkbox"><label
                                                                                    for="pneumovax">Pneumovax</label>
                                                                            @endif
                                                                        </div>
                                                                        <div class="checkbox form-check">
                                                                            <label for=""
                                                                                class="mr-5 ml-5">When</label>
                                                                            <input id="when_pneumovax"
                                                                                name="when_pneumovax"
                                                                                value="{{ $imm->when }}" type="month"
                                                                                disabled="disabled" required>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif

                                                    @if ($pneumovax == false)
                                                        <div class="d-flex justify-content-between flex-wrap">
                                                            <div class="checkbox form-check">
                                                                <input id="pneumovax"
                                                                    class="form-check-input immunization-pneumovax"
                                                                    name="immunization_history[]" value="pneumovax"
                                                                    type="checkbox"><label
                                                                    for="pneumovax">Pneumovax</label>
                                                            </div>
                                                            <div class="checkbox form-check">
                                                                <label for="" class="mr-5 ml-5">When</label>
                                                                <input id="when_pneumovax" name="when_pneumovax"
                                                                    value="" type="month">
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                            <li class="list-group-item justify-content-between">
                                                @php
                                                    $h1n1 = false;
                                                @endphp
                                                <div>
                                                    @if (isset($profile))
                                                        @if ($profile->immunization_history != '')
                                                            @foreach ($profile->immunization_history as $imm)
                                                                @if ($imm->name == 'h1n1' && $h1n1 == false)
                                                                    @php $h1n1=true; @endphp
                                                                    <div class="d-flex justify-content-between flex-wrap">
                                                                        <div class="checkbox form-check">
                                                                            @if ($imm->flag == 'yes')
                                                                                <input id="h1n1"
                                                                                    class="form-check-input immunization-h1n1"
                                                                                    name="immunization_history[]"
                                                                                    value="h1n1" type="checkbox"
                                                                                    checked><label
                                                                                    for="h1n1">H1N1</label>
                                                                            @else
                                                                                <input id="h1n1"
                                                                                    class="form-check-input  immunization-h1n1"
                                                                                    name="immunization_history[]"
                                                                                    value="h1n1" type="checkbox"><label
                                                                                    for="h1n1">H1N1</label>
                                                                            @endif
                                                                        </div>
                                                                        <div class="checkbox form-check">
                                                                            <label for=""
                                                                                class="mr-5 ml-5">When</label>
                                                                            <input id="when_h1n1" name="when_h1n1"
                                                                                value="{{ $imm->when }}" type="month"
                                                                                disabled="disabled" required>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                    @if ($h1n1 == false)
                                                        <div class="d-flex justify-content-between flex-wrap">
                                                            <div class="checkbox form-check ">
                                                                <input id="h1n1"
                                                                    class="form-check-input immunization-h1n1"
                                                                    name="immunization_history[]" value="h1n1"
                                                                    type="checkbox"><label for="h1n1">H1N1</label>
                                                            </div>
                                                            <div class="checkbox form-check ">
                                                                <label for="" class="mr-5 ml-5">When</label>
                                                                <input id="when_h1n1" name="when_h1n1" value=""
                                                                    type="month">
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                            <li class="list-group-item justify-content-between">
                                                @php
                                                    $annual_flu = false;
                                                @endphp
                                                <div>
                                                    @if (isset($profile))
                                                        @if ($profile->immunization_history != '')
                                                            @foreach ($profile->immunization_history as $imm)
                                                                @if ($imm->name == 'annual_flu' && $annual_flu == false)
                                                                    @php $annual_flu=true; @endphp
                                                                    <div class="d-flex justify-content-between flex-wrap">

                                                                        <div class="checkbox form-check ">
                                                                            @if ($imm->flag == 'yes')
                                                                                <input id="annual_flu"
                                                                                    class="form-check-input  immunization-annual"
                                                                                    name="immunization_history[]"
                                                                                    value="annual_flu" type="checkbox"
                                                                                    checked><label for="annual_flu">Annual
                                                                                    flu</label>
                                                                            @else
                                                                                <input id="annual_flu"
                                                                                    class="form-check-input  immunization-annual"
                                                                                    name="immunization_history[]"
                                                                                    value="annual_flu"
                                                                                    type="checkbox"><label
                                                                                    for="annual_flu">Annual
                                                                                    flu</label>
                                                                            @endif

                                                                        </div>

                                                                        <div class="checkbox form-check ">
                                                                            <label for=""
                                                                                class="mr-5 ml-5">When</label>
                                                                            <input id="when_annual_flu"
                                                                                name="when_annual_flu"
                                                                                value="{{ $imm->when }}" type="month"
                                                                                disabled="disabled" required>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                    @if ($annual_flu == false)
                                                        <div class="d-flex justify-content-between flex-wrap">

                                                            <div class="checkbox form-check">
                                                                <input id="annual_flu"
                                                                    class="form-check-input  immunization-annual"
                                                                    name="immunization_history[]" value="annual_flu"
                                                                    type="checkbox"><label for="annual_flu">Annual
                                                                    flu</label>
                                                            </div>
                                                            <div class="checkbox form-check">

                                                                <label for="" class="mr-5 ml-5">When</label>
                                                                <input id="when_annual_flu" name="when_annual_flu"
                                                                    value="" type="month">
                                                            </div>
                                                        </div>
                                                    @endif
                                            </li>
                                            <li class="list-group-item justify-content-between">
                                                @php
                                                    $hepatitis_b = false;
                                                @endphp
                                                @if (isset($profile))
                                                    @if ($profile->immunization_history != '')
                                                        @foreach ($profile->immunization_history as $imm)
                                                            @if ($imm->name == 'hepatitis_b' && $hepatitis_b == false)
                                                                @php $hepatitis_b=true; @endphp
                                                                <div class="d-flex justify-content-between flex-wrap">
                                                                    @if ($imm->flag == 'yes')
                                                                        <div class="checkbox form-check">
                                                                            <input id="hepatitis_b"
                                                                                class="form-check-input  immunization-hep"
                                                                                name="immunization_history[]"
                                                                                value="hepatitis_b" type="checkbox"
                                                                                checked><label for="hepatitis_b">Hepatitis
                                                                                B</label>
                                                                        @else
                                                                            <div class="checkbox form-check">
                                                                                <input id="hepatitis_b"
                                                                                    class="form-check-input  immunization-hep"
                                                                                    name="immunization_history[]"
                                                                                    value="hepatitis_b"
                                                                                    type="checkbox"><label
                                                                                    for="hepatitis_b">Hepatitis B</label>
                                                                    @endif

                                                                </div>
                                                                <div class="checkbox form-check">

                                                                    <label for="" class="mr-5 ml-5">When</label>
                                                                    <input id="when_hepatits_b" name="when_hepatits_b"
                                                                        value="{{ $imm->when }}" type="month"
                                                                        disabled="disabled" required>
                                                                </div>

                                    </div>
                                    @endif
                                    @endforeach
                                    @endif
                                    @endif
                                    @if ($hepatitis_b == false)
                                        <div class="d-flex justify-content-between flex-wrap">

                                            <div class="checkbox form-check">
                                                <input id="hepatitis_b" class="form-check-input  immunization-hep"
                                                    name="immunization_history[]" value="hepatitis_b"
                                                    type="checkbox"><label for="hepatitis_b">Hepatitis B</label>
                                            </div>
                                            <div class="checkbox form-check">

                                                <label for="" class="mr-5 ml-5">When</label>
                                                <input id="when_hepatits_b" name="when_hepatits_b" value=""
                                                    type="month" disabled="disabled">
                                            </div>
                                        </div>
                                    @endif
                                    </li>
                                    <li class="list-group-item justify-content-between">
                                        @php
                                            $tetanus = false;
                                        @endphp

                                        @if (isset($profile))
                                            @if ($profile->immunization_history != '')
                                                @foreach ($profile->immunization_history as $imm)
                                                    @if ($imm->name == 'tetanus' && $tetanus == false)
                                                        @php $tetanus=true; @endphp
                                                        <div class="d-flex justify-content-between flex-wrap">
                                                            <div class="checkbox form-check">
                                                                @if ($imm->flag == 'yes')
                                                                    <input id="tetanus"
                                                                        class="form-check-input immunization-tetanus"
                                                                        name="immunization_history[]" value="tetanus"
                                                                        type="checkbox" checked><label
                                                                        for="tetanus">Tetanus</label>
                                                                @else
                                                                    <input id="tetanus"
                                                                        class="form-check-input  immunization-tetanus"
                                                                        name="immunization_history[]" value="tetanus"
                                                                        type="checkbox"><label
                                                                        for="tetanus">Tetanus</label>
                                                                @endif
                                                            </div>
                                                            <div class="checkbox form-check">
                                                                <label for="" class="mr-5 ml-5">When</label>
                                                                <input id="when_tetanus" name="when_tetanus"
                                                                    value="{{ $imm->when }}" disabled="disabled"
                                                                    type="month" required>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                        @if ($tetanus == false)
                                            <div class="d-flex justify-content-between flex-wrap">
                                                <div class="checkbox form-check">
                                                    <input id="tetanus" class="form-check-input  immunization-tetanus"
                                                        name="immunization_history[]" value="tetanus"
                                                        type="checkbox"><label for="tetanus">Tetanus</label>
                                                </div>
                                                <div class="checkbox form-check">
                                                    <label for="" class="mr-5 ml-5">When</label>
                                                    <input id="when_tetanus" name="when_tetanus" value=""
                                                        type="month" disabled="disabled">
                                                </div>
                                            </div>
                                        @endif
                                    </li>
                                    <li class="list-group-item justify-content-between">
                                        @php
                                            $others = false;
                                        @endphp
                                        @if (isset($profile))
                                            @if ($profile->immunization_history != '')
                                                @foreach ($profile->immunization_history as $imm)
                                                    @if ($imm->name == 'others' && $others == false)
                                                        @php $others=true; @endphp

                                                        <div class="d-flex justify-content-between flex-wrap">
                                                            <div class="checkbox form-check">
                                                                @if ($imm->flag == 'yes')
                                                                    <input id="others"
                                                                        class="form-check-input  immunization-other"
                                                                        name="immunization_history[]" value="others"
                                                                        type="checkbox" checked><label
                                                                        for="others">Others</label>
                                                                @else
                                                                    <input id="others"
                                                                        class="form-check-input immunization-other"
                                                                        name="immunization_history[]" value="others"
                                                                        type="checkbox"><label
                                                                        for="others">Others</label>
                                                                @endif

                                                            </div>
                                                            <div class="checkbox form-check">
                                                                <label for="" class="mr-5 ml-5">When</label>
                                                                <input id="when_others" name="when_others"
                                                                    value="{{ $imm->when }}" type="month"
                                                                    disabled="disabled" required>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                        @if ($others == false)
                                            <div class="d-flex justify-content-between flex-wrap">

                                                <div class="checkbox form-check">
                                                    <input id="others" class="form-check-input immunization-other"
                                                        name="immunization_history[]" value="others"
                                                        type="checkbox"><label for="others">Others</label>
                                                </div>
                                                <div class="checkbox form-check">

                                                    <label for="" class="mr-5 ml-5">When</label>
                                                    <input id="when_others" name="when_others" value=""
                                                        type="month" disabled="disabled">
                                                </div>
                                            </div>
                                        @endif
                                    </li>
                                </div>
                            </div>
                            <div>

                            </div>
                        </div>

                        <!-- <div class="col-md-6 pt-md-0 pt-3">
                                    <label for="phone">Phone Number</label>
                                    <input type="number" class="bg-light form-control" placeholder="+123 2324 2323">
                                </div> -->
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="m-0">FAMILY HISTORY</h5>
                            <p>Your family members (e.g parents, grandparents, and siblings) ever had any of the
                                following
                                diseases</p>
                            <div class="wallet-table update-med-history-table mt-2">
                                <table class="table">
                                    <thead class="table-success">
                                        <tr>
                                            <th scope="col">Disease</th>
                                            <th scope="col">Which family member?</th>
                                            <th scope="col">Approx. age when diagnosed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $hyp_done = false;
                                        @endphp
                                        <tr>
                                            <td data-label="Disease" scope="row"> Hypertension </td>
                                            @if (isset($profile))
                                                @foreach ($profile->family_history as $history)
                                                    @if ($history->disease == 'hypertension' && $hyp_done == false)
                                                        @php $hyp_done=true; @endphp
                                                        <td data-label="Which family member?">
                                                            <select class="form-select relative" name="f_hypertension">
                                                                <option value="">None</option>
                                                                <option
                                                                    {{ $history->family == 'parent' ? "selected = 'selected'" : '' }}
                                                                    value="parent">Parent</option>
                                                                <option
                                                                    {{ $history->family == 'grand' ? "selected = 'selected'" : '' }}
                                                                    value="grand">Grandparent</option>
                                                                <option
                                                                    {{ $history->family == 'sibling' ? "selected = 'selected'" : '' }}
                                                                    value="sibling">Sibling</option>
                                                                <option
                                                                    {{ $history->family == 'others' ? "selected = 'selected'" : '' }}
                                                                    value="others">Others</option>
                                                            </select>
                                                        </td>
                                                        <td data-label="Approx. age when diagnosed">
                                                            <input type="number" name="f_hypertension_age"
                                                                class="form-control age" min="1" max="150"
                                                                placeholder="Age" readonly value="{{ $history->age }}">
                                                        </td>
                                                    @endif
                                                @endforeach
                                            @endif
                                            </td>
                                            @if ($hyp_done == false)
                                                <td data-label="Which family member?">
                                                    <select class="form-select relative" name="f_hypertension">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </td>
                                                <td data-label="Approx. age when diagnosed">
                                                    <input type="number" name="f_hypertension_age"
                                                        class="form-control age" min="1" max="150"
                                                        placeholder="Age" readonly>
                                                </td>
                                        </tr>
                                        @endif

                                        @php
                                            $heart_done = false;
                                        @endphp
                                        <tr>
                                            <td data-label="Disease" scope="row">Heart Disease</td>
                                            @if (isset($profile))
                                                @foreach ($profile->family_history as $history)
                                                    @if ($history->disease == 'heart' && $heart_done == false)
                                                        @php $heart_done=true; @endphp
                                                        <td data-label="Which family member?">
                                                            <select class="form-select relative" name="f_heart">
                                                                <option value="">None</option>
                                                                <option
                                                                    {{ $history->family == 'parent' ? "selected = 'selected'" : '' }}
                                                                    value="parent">Parent</option>
                                                                <option
                                                                    {{ $history->family == 'grand' ? "selected = 'selected'" : '' }}
                                                                    value="grand">Grandparent</option>
                                                                <option
                                                                    {{ $history->family == 'sibling' ? "selected = 'selected'" : '' }}
                                                                    value="sibling">Sibling</option>
                                                                <option
                                                                    {{ $history->family == 'others' ? "selected = 'selected'" : '' }}
                                                                    value="others">Others</option>
                                                            </select>
                                                        </td>
                                                        <td data-label="Approx. age when diagnosed">
                                                            <input type="number" name="f_heart_age"
                                                                class="form-control age" min="1" max="150"
                                                                placeholder="Age" readonly value="{{ $history->age }}">
                                                        </td>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if ($heart_done == false)
                                                <td data-label="Which family member?">
                                                    <select class="form-select relative" name="f_heart">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </td>
                                                <td data-label="Approx. age when diagnosed">
                                                    <input type="number" name="f_heart_age" class="form-control age"
                                                        min="1" max="150" placeholder="Age" readonly>
                                                </td>
                                        </tr>
                                        @endif

                                        @php
                                            $diabetes_done = false;
                                        @endphp
                                        <tr>
                                            <td data-label="Disease">Diabetes</td>

                                            @if (isset($profile))
                                                @foreach ($profile->family_history as $history)
                                                    @if ($history->disease == 'diabetes' && $diabetes_done == false)
                                                        @php $diabetes_done=true; @endphp
                                                        <td data-label="Which family member?">
                                                            <select class="form-select relative" name="f_diabetes">
                                                                <option value="">None</option>
                                                                <option
                                                                    {{ $history->family == 'parent' ? "selected = 'selected'" : '' }}
                                                                    value="parent">Parent</option>
                                                                <option
                                                                    {{ $history->family == 'grand' ? "selected = 'selected'" : '' }}
                                                                    value="grand">Grandparent</option>
                                                                <option
                                                                    {{ $history->family == 'sibling' ? "selected = 'selected'" : '' }}
                                                                    value="sibling">Sibling</option>
                                                                <option
                                                                    {{ $history->family == 'others' ? "selected = 'selected'" : '' }}
                                                                    value="others">Others</option>
                                                            </select>
                                                        </td>
                                                        <td data-label="Approx. age when diagnosed">
                                                            <input type="number" name="f_diabetes_age"
                                                                class="form-control age" min="1" max="150"
                                                                placeholder="Age" readonly value="{{ $history->age }}">
                                                        </td>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if ($diabetes_done == false)
                                                <td data-label="Which family member?">
                                                    <select class="form-select relative" name="f_diabetes">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </td>
                                                <td data-label="Approx. age when diagnosed">
                                                    <input type="number" name="f_diabetes_age" class="form-control age"
                                                        min="1" max="150" placeholder="Age" readonly>
                                                </td>
                                        </tr>
                                        @endif

                                        @php
                                            $stroke_done = false;
                                        @endphp
                                        <tr>
                                            <td data-label="Disease">Stroke</td>
                                            @if (isset($profile))
                                                @foreach ($profile->family_history as $history)
                                                    @if ($history->disease == 'stroke' && $stroke_done == false)
                                                        @php $stroke_done=true; @endphp
                                                        <td data-label="Which family member?">
                                                            <select class="form-select relative" name="f_stroke">
                                                                <option value="">None</option>
                                                                <option
                                                                    {{ $history->family == 'parent' ? "selected = 'selected'" : '' }}
                                                                    value="parent">Parent</option>
                                                                <option
                                                                    {{ $history->family == 'grand' ? "selected = 'selected'" : '' }}
                                                                    value="grand">Grandparent</option>
                                                                <option
                                                                    {{ $history->family == 'sibling' ? "selected = 'selected'" : '' }}
                                                                    value="sibling">Sibling</option>
                                                                <option
                                                                    {{ $history->family == 'others' ? "selected = 'selected'" : '' }}
                                                                    value="others">Others</option>
                                                            </select>
                                                        </td>
                                                        <td data-label="Approx. age when diagnosed">
                                                            <input type="number" name="f_stroke_age"
                                                                class="form-control age" min="1" max="150"
                                                                placeholder="Age" readonly value="{{ $history->age }}">
                                                        </td>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if ($stroke_done == false)
                                                <td data-label="Which family member?">
                                                    <select class="form-select relative" name="f_stroke">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </td>
                                                <td data-label="Approx. age when diagnosed">
                                                    <input type="number" name="f_stroke_age" class="form-control age"
                                                        min="1" max="150" placeholder="Age" readonly>
                                                </td>
                                            @endif
                                        </tr>

                                        @php
                                            $mental_done = false;
                                        @endphp
                                        <tr>
                                            <td data-label="Disease"> Mental Disease</td>
                                            @if (isset($profile))
                                                @foreach ($profile->family_history as $history)
                                                    @if ($history->disease == 'mental' && $mental_done == false)
                                                        @php $mental_done=true; @endphp
                                                        <td data-label="Which family member?">
                                                            <select class="form-select relative" name="f_mental">
                                                                <option value="">None</option>
                                                                <option
                                                                    {{ $history->family == 'parent' ? "selected = 'selected'" : '' }}
                                                                    value="parent">Parent</option>
                                                                <option
                                                                    {{ $history->family == 'grand' ? "selected = 'selected'" : '' }}
                                                                    value="grand">Grandparent</option>
                                                                <option
                                                                    {{ $history->family == 'sibling' ? "selected = 'selected'" : '' }}
                                                                    value="sibling">Sibling</option>
                                                                <option
                                                                    {{ $history->family == 'others' ? "selected = 'selected'" : '' }}
                                                                    value="others">Others</option>
                                                            </select>
                                                        </td>
                                                        <td data-label="Approx. age when diagnosed">
                                                            <input type="number" name="f_mental_age"
                                                                class="form-control age" min="1" max="150"
                                                                placeholder="Age" readonly value="{{ $history->age }}">
                                                        </td>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if ($mental_done == false)
                                                <td data-label="Which family member?">
                                                    <select class="form-select relative" name="f_mental">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </td>
                                                <td data-label="Approx. age when diagnosed">
                                                    <input type="number" name="f_mental_age" class="form-control age"
                                                        min="1" max="150" placeholder="Age" readonly>
                                                </td>
                                            @endif
                                        </tr>

                                        @php
                                            $drugs_done = false;
                                        @endphp
                                        <tr>
                                            <td data-label="Disease">Drugs/Alcohol Addiction</td>
                                            @if (isset($profile))
                                                @foreach ($profile->family_history as $history)
                                                    @if ($history->disease == 'drugs' && $drugs_done == false)
                                                        @php $drugs_done=true; @endphp
                                                        <td data-label="Which family member?">
                                                            <select class="form-select relative" name="f_drugs">
                                                                <option value="">None</option>
                                                                <option
                                                                    {{ $history->family == 'parent' ? "selected = 'selected'" : '' }}
                                                                    value="parent">Parent</option>
                                                                <option
                                                                    {{ $history->family == 'grand' ? "selected = 'selected'" : '' }}
                                                                    value="grand">Grandparent</option>
                                                                <option
                                                                    {{ $history->family == 'sibling' ? "selected = 'selected'" : '' }}
                                                                    value="sibling">Sibling</option>
                                                                <option
                                                                    {{ $history->family == 'others' ? "selected = 'selected'" : '' }}
                                                                    value="others">Others</option>
                                                            </select>
                                                        </td>
                                                        <td data-label="Approx. age when diagnosed">
                                                            <input type="number" name="f_drugs_age"
                                                                class="form-control age" min="1" max="150"
                                                                placeholder="Age" readonly value="{{ $history->age }}">
                                                        </td>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if ($drugs_done == false)
                                                <td data-label="Which family member?">
                                                    <select class="form-select relative" name="f_drugs">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </td>
                                                <td data-label="Approx. age when diagnosed">
                                                    <input type="number" name="f_drugs_age" class="form-control age"
                                                        min="1" max="150" placeholder="Age" readonly>
                                                </td>
                                            @endif
                                        </tr>

                                        @php
                                            $Glaucoma_done = false;
                                        @endphp
                                        <tr>
                                            <td data-label="Disease">Glaucoma</td>
                                            @if (isset($profile))
                                                @foreach ($profile->family_history as $history)
                                                    @if ($history->disease == 'glaucoma' && $Glaucoma_done == false)
                                                        @php $Glaucoma_done=true; @endphp
                                                        <td data-label="Which family member?">
                                                            <select class="form-select relative" name="f_glaucoma">
                                                                <option value="">None</option>
                                                                <option
                                                                    {{ $history->family == 'parent' ? "selected = 'selected'" : '' }}
                                                                    value="parent">Parent</option>
                                                                <option
                                                                    {{ $history->family == 'grand' ? "selected = 'selected'" : '' }}
                                                                    value="grand">Grandparent</option>
                                                                <option
                                                                    {{ $history->family == 'sibling' ? "selected = 'selected'" : '' }}
                                                                    value="sibling">Sibling</option>
                                                                <option
                                                                    {{ $history->family == 'others' ? "selected = 'selected'" : '' }}
                                                                    value="others">Others</option>
                                                            </select>
                                                        </td>
                                                        <td data-label="Approx. age when diagnosed">
                                                            <input type="number" name="f_glaucoma_age"
                                                                class="form-control age" min="1" max="150"
                                                                placeholder="Age" readonly value="{{ $history->age }}">
                                                        </td>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if ($Glaucoma_done == false)
                                                <td data-label="Which family member?">
                                                    <select class="form-select relative" name="f_glaucoma">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </td>
                                                <td data-label="Approx. age when diagnosed">
                                                    <input type="number" name="f_glaucoma_age" class="form-control age"
                                                        min="1" max="150" placeholder="Age" readonly>
                                                </td>
                                            @endif
                                        </tr>

                                        @php
                                            $bleeding_done = false;
                                        @endphp
                                        <tr>
                                            <td data-label="Disease">Bleeding Disease</td>
                                            @if (isset($profile))
                                                @foreach ($profile->family_history as $history)
                                                    @if ($history->disease == 'bleeding' && $bleeding_done == false)
                                                        @php $bleeding_done=true; @endphp
                                                        <td data-label="Which family member?">
                                                            <select class="form-select relative" name="f_bleeding">
                                                                <option value="">None</option>
                                                                <option
                                                                    {{ $history->family == 'parent' ? "selected = 'selected'" : '' }}
                                                                    value="parent">Parent</option>
                                                                <option
                                                                    {{ $history->family == 'grand' ? "selected = 'selected'" : '' }}
                                                                    value="grand">Grandparent</option>
                                                                <option
                                                                    {{ $history->family == 'sibling' ? "selected = 'selected'" : '' }}
                                                                    value="sibling">Sibling</option>
                                                                <option
                                                                    {{ $history->family == 'others' ? "selected = 'selected'" : '' }}
                                                                    value="others">Others</option>
                                                            </select>
                                                        </td>
                                                        <td data-label="Approx. age when diagnosed">
                                                            <input type="number" name="f_bleeding_age"
                                                                class="form-control age" min="1" max="150"
                                                                placeholder="Age" readonly value="{{ $history->age }}">
                                                        </td>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if ($bleeding_done == false)
                                                <td data-label="Which family member?">
                                                    <select class="form-select relative" name="f_bleeding">
                                                        <option value="">None</option>
                                                        <option value="parent">Parent</option>
                                                        <option value="grand">Grandparent</option>
                                                        <option value="sibling">Sibling</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </td>
                                                <td data-label="Approx. age when diagnosed">
                                                    <input type="number" name="f_bleeding_age" class="form-control age"
                                                        min="1" max="150" placeholder="Age" readonly>
                                                </td>
                                            @endif
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row py-2">
                    <div class="col-md-12">
                        <div class="">
                            @php
                                $user = auth()->user();
                                $record = $user->med_record_file;
                            @endphp
                            @if ($record != null)
                                <div class="row py-2">
                                    <div class="col-md-6 mb-3">
                                        <label for="firstname">Comments</label>
                                        <input type="text" class="bg-light form-control"
                                            value="{{ $profile['comment'] ?? '' }}"
                                            placeholder="Comments about your health (optional)">
                                    </div>
                                    <div class="col-md-6">
                                        <div>
                                            <label for="firstname">Medical Record</label>
                                            <input class="form-control custom-file-input customFile" type="file"
                                                id="formFile" name="med_record" value="{{ $record }}"
                                                id="customFile" accept="application/pdf">
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row py-2">
                                    <div class="col-md-6">
                                        <label for="firstname">Comments</label>
                                        <input type="text" class="bg-light form-control"
                                            placeholder="Comments about your health (optional)">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname">Medical Record</label>
                                            <input class="form-control custom-file-input customFile" type="file"
                                                id="formFile" name="med_record" value="" id="customFile"
                                                accept="application/pdf">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#redirect_modal"
                                class="btn process-pay">Update Record</button>
                        </div>
                    </div>
                </div>
                </form>


                <!-- <div class="py-3 pb-4">
                                <button class="btn btn-primary mr-3">Save Changes</button>
                                <button class="btn border button">Cancel</button>
                            </div> -->

            </div>
        </div>
    </div>
    </div>

    <!-- ------------------Delete-Button-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="redirect_modal" tabindex="-1" aria-labelledby="redirect_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="redirect_modalLabel">Update Medical Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ask_change_status-modal-body text-dark p-5">
                        Are you sure?
                        you want to update these records?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn process-pay" onclick="redirect()">Ok</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Delete-Button-Modal-start------------------ -->
@endsection
