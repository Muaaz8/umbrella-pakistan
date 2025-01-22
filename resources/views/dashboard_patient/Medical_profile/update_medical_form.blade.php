@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Medical Profile</title>
@endsection

@section('top_import_file')

@endsection


@section('bottom_import_file')
<script>
    $(".js-select2").select2({
        closeOnSelect: false,
        placeholder: "Enter Symptoms",
        allowHtml: true,
        allowClear: true,
        tags: true,
    });
</script>
<script src="{{ asset('/js/app.js') }}"></script>
    <script type="text/javascript">
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    //Adding data
    $(document).on("click", '.add_medical_condition_button', function(e) {
        e.preventDefault();
        // var smm = $("#select_sym").val();
        var testval = $("#select_sym").val();
        console.log(testval.length);
        if(testval.length > 0){
            $('#medical_conditions').html('');
            $.each(testval, function (indexInArray, valueOfElement) {
                $('#medical_conditions').append(
                    '<input type="hidden" name="symp[]" value="'+valueOfElement+'">'
                    +'<span class="medical_condition_selection">'+valueOfElement+'<span>'
                    +'<i class="fa-solid fa-circle-xmark prev_symp_del"></i></span></span>'
                );
            });
            $('#add_medical_condition').modal('hide');
        }else{
            $('#mencont').html('');
            $('#mencont').html('<small> Please Select atleast 1 option</small>');
        }
    });

    $(document).on("click", '.add_family_button', function(e) {
        e.preventDefault();
        var disease = $('#family_disease').val();
        var member = $('#family_member').val();
        var age = $('#family_age').val();
        console.log(disease,member,age);
        if(member == 'Select Family Member' || age == '' || disease == 'Select Disease'){
            $('#add-fam-error').html('');
            $('#add-fam-error').html('<small> Please Fill the Form</small>');
        }else if (age >= 100 ||  age <0){
            $('#add-fam-error').html('');
            $('#add-fam-error').html('<small> Age cannot be equal to or more than 100</small>');
        }else{
            $('#family_history_cont').append(
                '<div class="family_history_cont d-flex justify-content-between">'
                +'<input type="hidden" name="family[]" value="'+member+'">'
                +'<input type="hidden" name="disease[]" value="'+disease+'">'
                +'<input type="hidden" name="age[]" value="'+age+'">'
                +'<p><b>'+member+'</b> had <b>'+disease+'</b> at '
                +'<b>'+age+'</b></p><div class="dropdown ">'
                +'<button type="button" class="btn option-view-btn dropdown-toggle" type="button"'
                +'id="dropdownMenuButton1" data-bs-toggle="dropdown"aria-expanded="false">'
                +'Options</button><ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">'
                +'<li><a class="dropdown-item" href="#" data-bs-toggle="modal"'
                +'data-bs-target="#edit_family_member">Edit</a></li>'
                +'<li><a class="dropdown-item fam_his_del">delete</a></li></ul></div></div>'
            );
            $('#add-fam-error').html('');
            $('#family_disease').val('Select Disease');
            $('#family_member').val('Select Family Member');
            $('#family_age').val('');
            $('#add_family_member').modal('hide');
        }
    });

    $(document).on("click", '.immu_add', function(e) {
        e.preventDefault();
        var immunization = $('#immu_name').val();
        var when = $('#immu_date').val();
        when = moment(when).format('MM-DD-YYYY');
        // console.log(when);
        if (immunization == 'Select Immunization' || when == '' || when == 'Invalid date') {
            $('#immu_error').html('');
            $('#immu_error').html('<small>Please Fill the form</small>');
        }else{
            $('#immu_cont').append(
                '<input type="hidden" name="immun_name[]" id="immun_name" value="'+immunization+'">'
                +'<input type="hidden" name="immun_when[]" id="immun_when" value="'+when+'">'
                +'<div class="family_history_cont d-flex justify-content-between">'
                +'<p><b>Immunization: </b>'+immunization+'</p><p><b>When: </b>'+when+'</p>'
                +'<div><i class="fa-solid fa-circle-xmark immun_del"></i>'
                +'</div></div>'
            );
            // ('#add_immunization_modal').modal('hide');
            $('#immu_error').html('');
            $('#immu_name').val('Select Immunization');
            $('#immu_date').val();
            $('#add_immunization_modal').modal('hide');
        }
    });

    $(document).on("click", '.add_meds_hist', function(e) {
        e.preventDefault();
        var med_name = $('#drug_name').val();
        var med_dosage = $('#drug_dosage').val();
        console.log(med_name,med_dosage);
        if (med_name == '' || med_dosage == '') {
            $('#med_his_error').html('');
            $('#med_his_error').html('<small> Please Fill the form </small>');
        }else{
            $('#medication_div').append(
                '<input type="hidden" name="med_name[]" id="med_name" value="'+med_name+'">'
                +'<input type="hidden" name="med_dosage[]" id="med_dosage" value="'+med_dosage+'">'
                +'<div class="family_history_cont d-flex justify-content-between">'
                +'<p><b>Drug Name: </b>'+med_name+'</p>'
                +'<p><b>Dosage: </b>'+med_dosage+'</p>'
                +'<div><i class="fa-solid fa-circle-xmark medication_del"></i></div>'
                +'</div>'
            );
            $('#drug_name').val('');
            $('#drug_dosage').val('');
            $('#add_medication_history').modal('hide');
        }
    });

    //Deleting Data
    $(document).on("click", '.prev_symp_del', function(e) {
        e.preventDefault();
        var abc = $(this).parent().parent();
        var inp = abc.prev();
        $('#delete').modal('show');
        $('#yes').click(function(){
            $(abc).remove();
            $(inp).remove();
            $('#delete').modal('hide');
        })
    });

    $(document).on("click", '.fam_his_del', function(e) {
        e.preventDefault();
        var abc = $(this).parent().parent().parent().parent();
        console.log(abc);
        $('#delete').modal('show');
        $('#yes').click(function(){
            $(abc).remove();
            $('#delete').modal('hide');
        })
    });

    $(document).on("click", '.immun_del', function(e) {
        e.preventDefault();
        var abc = $(this).parent().parent();
        var inp = abc.prev();
        var inp2 = abc.prev().prev();
        // var inp = abc.prev();
        // console.log(inp);
        $('#delete').modal('show');
        $('#yes').click(function(){
            $(abc).remove();
            $(inp).remove();
            $(inp2).remove();
            $('#delete').modal('hide');
        })
    });

    $(document).on("click", '.medication_del', function(e) {
        e.preventDefault();
        var abc = $(this).parent().parent();
        var inp = abc.prev();
        var inp2 = abc.prev().prev();
        // var inp = abc.prev();
        console.log(abc);
        console.log(inp);
        console.log(inp2);
        $('#delete').modal('show');
        $('#yes').click(function(){
            $(abc).remove();
            $(inp).remove();
            $(inp2).remove();
            $('#delete').modal('hide');
        })
    });

    //Edit Data
    $(document).on("click", '.fam_his_edit', function(e) {
        e.preventDefault();
        var abc = $(this).parent().parent().parent().parent();
        var relative = abc.children('input')[0].value;
        var disease = abc.children('input')[1].value;
        var age = abc.children('input')[2].value;
        $('select[name="edit_family"]').find('option[value="'+relative+'"]').attr("selected",true);
        $('select[name="family_history_select"]').find('option[value="'+disease+'"]').attr("selected",true);
        $('#edit_family_age').val(age);
        $('#edit_family_member').modal('show');
        $('#update_family').click(function(){
            var new_disease = $('#edit_family_disease').val();
            var new_relative = $('#edit_family').val();
            var new_age = $('#edit_family_age').val();
            console.log(new_relative,new_disease,new_age)
            if(new_relative == 'Select Family Member' || new_age == ''){
            $('#edit-fam-error').html('');
            $('#edit-fam-error').html('<small> Please Fill the Form</small>');
            }else if (new_age >= 100 ||  new_age <0){
                $('#edit-fam-error').html('');
                $('#edit-fam-error').html('<small> Age cannot be more than 100</small>');
            }else{
                $('#family_history_cont').prepend(
                    '<div class="family_history_cont d-flex justify-content-between">'
                    +'<input type="hidden" name="family[]" value="'+new_relative+'">'
                    +'<input type="hidden" name="disease[]" value="'+new_disease+'">'
                    +'<input type="hidden" name="age[]" value="'+new_age+'">'
                    +'<p><b>'+new_relative+'</b> had <b>'+new_disease+'</b> at <b>'+new_age+'</b></p>'
                    +'<div class="dropdown "><button type="button" class="btn option-view-btn dropdown-toggle"'
                    +'id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Options</button>'
                    +'<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style=""><li>'
                    +'<a class="dropdown-item fam_his_edit">Edit</a></li>'
                    +'<li><a class="dropdown-item fam_his_del">delete</a></li>'
                    +'</ul></div></div>'
                );
                $(abc).remove();
                $('#edit-fam-error').html('');
                $('#edit_family_member').modal('hide');
            }
        });
    });
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
            <form action="{{ route('new_store_medical_profile') }}" id="medical_profile" enctype="multipart/form-data" method="post">
                @csrf
                <div class="">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>MEDICAL HISTORY <br>
                                <p class="fs-6 fw-normal">All Your Medical Details Here</p>
                            </h4>
                        </div>
                        <div>
                            <button type="button" class="btn process-pay" data-bs-toggle="modal" data-bs-target="#redirect_modal">Update Record</button>
                        </div>
                    </div>
                    <div class="pb-3">

                        <div class="row py-2">
                            <div class="col-md-6 pt-md-0 pt-3">
                                <div class="card card-Shadow" style="width: 100%;">
                                    <div class="card-header p-3">
                                        <div>
                                            <h5> Allergies you have</h5>
                                        </div>
                                    </div>
                                    <div class="px-1 py-3">
                                        <form>
                                            @if (isset($profile))
                                                {{-- <input type="text" name='allergies' class="bg-light form-control"
                                    value="{{ $profile['allergies'] }}"> --}}
                                                <textarea class="form-control" type="text" name='allergies' placeholder="Enter your allergies here" rows="2">{{ $profile->allergies }}</textarea>
                                            @else
                                                {{-- <input type="text" name='allergies' class="bg-light form-control" value="" required> --}}
                                                <textarea type="text" name='allergies' class="form-control" placeholder="Enter your allergies here" rows="2"></textarea>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-Shadow" style="width: 100%">
                                    <div class="card-header p-3 justify-content-between d-flex">
                                        <div>
                                            <h5> Medical Conditions</h5>
                                        </div>
                                        <div><button type="button" class="btn update_medical_add_btn" data-bs-toggle="modal"
                                                data-bs-target="#add_medical_condition">Add</button></div>
                                    </div>
                                    <div>
                                        <div id="medical_conditions" class="medical_conditions p-2 d-flex flex-wrap">
                                            {{-- {{ dd(strpos($profile['previous_symp'], 'hypertension')); }} --}}
                                            @if (isset($profile))
                                                @foreach ($profile->previous_symp as $item)
                                                    <input type="hidden" name="symp[]" value="{{ $item }}"><span class="medical_condition_selection">{{ $item }}<span>
                                                            <i class="fa-solid fa-circle-xmark prev_symp_del"></i>
                                                        </span></span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div>

                                </div>
                            </div>
                        </div>

                        <div class="py-4 ">
                            <div class="mt-2 d-flex justify-content-between">
                                <h5>FAMILY HISTORY</h5>
                                <div><button type="button" class="btn update_medical_add_btn" data-bs-toggle="modal"
                                        data-bs-target="#add_family_member">Add</button></div>
                            </div>
                            <p>Please add if your family members over had some diseases</p>
                            <div id="family_history_cont" >
                                @if (isset($profile))
                                    @foreach ($profile->family_history as $history)
                                        <div class="family_history_cont d-flex justify-content-between">
                                            <input type="hidden" name="family[]" value="{{ $history->family }}">
                                            <input type="hidden" name="disease[]" value="{{ $history->disease }}">
                                            <input type="hidden" name="age[]" value="{{ $history->age }}">
                                            <p><b>{{ $history->family }}</b> had <b>{{ $history->disease }}</b> at
                                                <b>{{ $history->age }}</b>
                                            </p>
                                            <div class="dropdown ">
                                                <button type="button" class="btn option-view-btn dropdown-toggle" type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Options
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li><a class="dropdown-item fam_his_edit">Edit</a></li>
                                                    <li><a class="dropdown-item fam_his_del">delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="row py-2">
                            <div class="col-md-6 pt-md-0 pt-3">
                                <div class="card card-Shadow  style="width: 100%">
                                    <div class="card-header p-3 justify-content-between d-flex">
                                        <div>
                                            <h5>Immunization History</h5>
                                        </div>
                                        <div><button type="button" class="btn update_medical_add_btn" data-bs-toggle="modal"
                                                data-bs-target="#add_immunization_modal">Add</button></div>
                                    </div>
                                    <div id="immu_cont">
                                        @if (isset($profile))
                                            @if ($profile->immunization_history != '')
                                                @foreach ($profile->immunization_history as $imm)
                                                    @if ($imm->flag == 'yes')
                                                    <input type="hidden" name="immun_name[]" id="immun_name" value="{{ $imm->name }}">
                                                    <input type="hidden" name="immun_when[]" id="immun_when" value="{{ $imm->when }}">
                                                    <div class="family_history_cont d-flex justify-content-between">
                                                        <p><b>Immunization: </b>{{ $imm->name }}</p>
                                                        <p><b>When: </b>{{ $imm->when }}</p>
                                                        <div><i class="fa-solid fa-circle-xmark immun_del"></i>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 pt-md-0 pt-3">
                                <div class="card card-Shadow  style="width: 100%">
                                    <div class="card-header p-3 justify-content-between d-flex">
                                        <div>
                                            <h5>Medication History</h5>
                                        </div>
                                        <div><button type="button" class="btn update_medical_add_btn" data-bs-toggle="modal"
                                                data-bs-target="#add_medication_history">Add</button></div>
                                    </div>
                                    <div id="medication_div">
                                        @if (isset($profile->medication))
                                            @foreach ($profile->medication as $med)
                                                <input type="hidden" name="med_name[]" id="med_name" value="{{ $med->med_name }}">
                                                <input type="hidden" name="med_dosage[]" id="med_dosage" value="{{ $med->med_dosage }}">
                                                <div class="family_history_cont d-flex justify-content-between">
                                                <p><b>Drug Name: </b>{{ $med->med_name }}</p>
                                                <p><b>Dosage: </b>{{ $med->med_dosage }}</p>
                                                <div><i class="fa-solid fa-circle-xmark medication_del"></i></div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row py-2">
                            <div class="col-md-6 pt-md-0 pt-3">
                                <div class="card card-Shadow" style="width: 100%;">
                                    <div class="card-header p-3">
                                        <div>
                                            <h5>If you had any surgeries or operations</h5>
                                        </div>
                                    </div>
                                    <div class="px-1 py-3">
                                        <textarea class="form-control" name="surgeries" placeholder="Enter your surgeries or operations" rows="2">@if (isset($profile->surgeries)){{ $profile->surgeries }}@endif</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-Shadow" style="width: 100%">
                                    <div class="card-header p-3 justify-content-between d-flex">
                                        <div>
                                            <h5>Previous Medical Record</h5>
                                        </div>

                                    </div>
                                    <div>
                                        <div class="medical_conditions p-2">
                                            <div class="mb-3">
                                                <input class="form-control" name="certificate[]" type="file" id="formFile" multiple>
                                            </div>
                                            @php $count = 1; @endphp
                                            @forelse($med_files as $med_file)
                                            <div class="pb-2 d-flex flex-wrap">
                                                @if (isset($med_file->record_file) && $med_file->record_file != "")
                                                    <a href="{{ \App\Helper::check_bucket_files_url($med_file->record_file) }}"
                                                    target="_blank" class="view_Record_file m-1"><span><i
                                                            class="fa-solid fa-eye"></i> View Record {{$count}}</span></a> <i class="fa-solid fa-trash" style="cursor:pointer;" onclick="window.location.href='/delete/patient/medical/record/{{$med_file->id}}'"></i>
                                                @endif
                                            </div>
                                            @php $count = $count+1; @endphp
                                            @empty
                                            @endforelse

                                        </div>

                                    </div>
                                </div>
                                <div>

                                </div>
                            </div>
                        </div>


                        <div class="py-2 px-2 card-Shadow">
                            <div class="col-md-12">
                                <h5>Comments</h5>
                                @if (isset($profile->comment))
                                    <input type="text" class="bg-light form-control" name="comm"
                                        placeholder="Enter comments about your health (optional)"
                                        value="{{ $profile->comment }}">
                                @else
                                    <input type="text" class="bg-light form-control" name="comm"
                                        placeholder="Enter comments about your health (optional)"
                                        value="">
                                @endif
                            </div>
                        </div>

                        <div class="mt-2 text-center">
                            <button type="button" class="btn process-pay" data-bs-toggle="modal" data-bs-target="#redirect_modal">Update Record</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ------------------Medical-Condition-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="add_medical_condition" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Medical Condition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- <div class="d-flex search-Style">
                                        <input type="search" class="form-control" name="" id=""
                                            placeholder="Search">
                                        <button type="button" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    </div> -->
                                    <div>
                                        <div class="inquiry-form-checkbox">
                                            <select class="js-select2" name="symptoms[]" id="select_sym" multiple="multiple" required>
                                                @foreach ($is_diseases as $s)
                                                @if (isset($profile))
                                                    @if (!in_array($s->symptom_name,$profile->previous_symp))
                                                        <option value="{{ $s->symptom_name }}" data-badge="">
                                                            {{ $s->symptom_name }}</option>
                                                    @else
                                                        <option value="{{ $s->symptom_name }}"  data-badge="" selected="selected">{{ $s->symptom_name }}</option>
                                                    @endif
                                                @else
                                                    <option value="{{ $s->symptom_name }}" data-badge="">{{ $s->symptom_name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="my-2 medical_cond_checks">
                                        @foreach ($diseases as $item)
                                            <div class="form-check add_MC">
                                                <input class="form-check-input hobbies_class" type="checkbox" value="{{ $item }}"
                                                    id="{{ $item }}">
                                                <label class="form-check-label" for="a">
                                                    {{ $item }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div> --}}
                                </div>

                            </div>

                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <div id="mencont" class="text-danger text-center"></div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn process-pay add_medical_condition_button">Add</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Medical-Condition-Modal-end------------------ -->


    <!-- ------------------Family-History-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="add_family_member" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Family History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="#">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Disease</label>
                                    <select class="form-select" name="family_history_select" id="family_disease" required>
                                        <option value="Select Disease">Select Disease</option>
                                        @foreach ($diseases as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Family Member</label>
                                    <select class="form-select" name="family_member" id="family_member" required>
                                        <option>Select Family Member</option>
                                        <option value="father">Father</option>
                                        <option value="mother">Mother</option>
                                        <option value="grandfather">Grand Father</option>
                                        <option value="grandmother">Grand Mother</option>
                                        <option value="brother">Brother</option>
                                        <option value="sister">Sister</option>
                                        <option value="son">Son</option>
                                        <option value="daugther">Daugther</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="email_body">Age</label>
                                    <input type="number" id="family_age" max="100" maxlength="3" name="family_age" class="form-control" placeholder="Enter Age.." required>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div id="add-fam-error" class="text-center text-danger"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn process-pay add_family_button">Add</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Family-History-Modal-end------------------ -->

    <!-- ------------------Edit-Family-History-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="edit_family_member" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Family History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Disease</label>
                                    <select class="form-select" name="family_history_select" id="edit_family_disease">
                                        @foreach ($diseases as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Family Member</label>
                                    <select class="form-select" name="edit_family" id="edit_family">
                                        <option selected>Select Family Member</option>
                                        <option value="father">Father</option>
                                        <option value="mother">Mother</option>
                                        <option value="grandfather">Grandfather</option>
                                        <option value="grandmother">Grandmother</option>
                                        <option value="brother">Brother</option>
                                        <option value="sister">Sister</option>
                                        <option value="son">Son</option>
                                        <option value="daugther">Daugther</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="email_body">Age</label>
                                    <input type="number" id="edit_family_age" maxlength="3" name="family_age" class="form-control" placeholder="Enter Age.." value="55">
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div id="edit-fam-error" class="text-center text-danger"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn process-pay" id="update_family">Update</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Edit-Family-History-Modal-end------------------ -->

    <!-- ------------------Add-Immunization-History-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="add_immunization_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Immunization History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Immunization</label>
                                    <select class="form-select" id="immu_name" name="immu_name">
                                        <option value="Select Immunization">Select Immunization</option>
                                        @foreach ($immunization as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">When</label>
                                    <input type="date" max="<?= date('Y-m-d'); ?>" name="immu_date" id="immu_date" class="form-control">
                                </div>

                            </div>

                        </div>
                    </form>

                </div>
                <div id="immu_error" class="text-danger text-center"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn process-pay immu_add">Add</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Add-Immunization-History-Modal-end------------------ -->

    <!-- ------------------Add-Medication-History-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="add_medication_history" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Medication History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Drug Name</label>
                                    <input type="text" id="drug_name" class="form-control">

                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Dosage</label>
                                    <input type="text" id="drug_dosage" class="form-control">
                                </div>

                            </div>

                        </div>
                    </form>

                </div>
                <div id="med_his_error" class="text-center text-danger"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn process-pay add_meds_hist">Add</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Add-Medication-History-Modal-end------------------ -->

    <!-- ------------------Delete-Button-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-modal-body">
                        Are you sure you want to delete?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="yes" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Delete-Button-Modal-start------------------ -->

    <!-- ------------------Confirmation-Modal-start------------------ -->

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


    <!-- ------------------Confirmation-Modal-start------------------ -->

@endsection
