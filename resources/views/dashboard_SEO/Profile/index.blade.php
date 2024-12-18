@extends('layouts.dashboard_SEO')
@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection
@section('page_title')
    <title>Community Health Care Systems</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script src="//cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"
        integrity="sha512-xys0/1s37nkbrmRkFuiECm8XDvUsTnz6ri9PzcL7ECWL2wLZ0oWFaLjMPXUMJ5MvNourivgTmW+WJiNKMzuKNQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css"
        integrity="sha512-2L0dEjIN/DAmTV5puHriEIuQU/IL3CoPm/4eyZp3bM8dnaXri6lK8u6DC5L96b+YSs9f3Le81dDRZUSYeX4QcQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="{{ asset('assets/js/chatbot.js') }}"></script>
    <script src="{{ asset('assets/js/pateint_form.js') }}"></script>
    <script type="text/javascript">
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#doctor').change(function (e) {
            var doc_id = $('#doctor').val();
            console.log(doc_id);
            $.ajax({
            type: "GET",
            url: "/get_doctor_details/"+doc_id,
            success: function (response) {
                if(response){
                    $('#certificates').html('');
                    $.each(response.doctor.certificates, function (indexInArray, valueOfElement) {
                        $('#certificates').append('<option value="'+valueOfElement+'">'+valueOfElement+'</option>');
                    });
                    let certificates = response.doctor.certificates;
                    $('#certificates').val(null).trigger('change');
                    $('#certificates').val(certificates).trigger('change');

                    $('#conditions').html('');
                    $.each(response.doctor.conditions, function (indexInArray, valueOfElement) {
                        $('#conditions').append('<option value="'+valueOfElement+'">'+valueOfElement+'</option>');
                    });
                    let conditions = response.doctor.conditions;
                    $('#conditions').val(null).trigger('change');
                    $('#conditions').val(conditions).trigger('change');

                    $('#procedures').html('');
                    $.each(response.doctor.procedures, function (indexInArray, valueOfElement) {
                        $('#procedures').append('<option value="'+valueOfElement+'">'+valueOfElement+'</option>');
                    });
                    let procedures = response.doctor.procedures;
                    $('#procedures').val(null).trigger('change');
                    $('#procedures').val(procedures).trigger('change');

                    $('#location').val(response.doctor.location);
                    $('#latitude').val(response.doctor.latitude);
                    $('#longitude').val(response.doctor.longitude);
                    $('#about').val(response.doctor.about);
                    $('#education').val(response.doctor.education);
                    $('#helping').val(response.doctor.helping);
                    $('#experience').val(response.doctor.experience);
                    $('#issue').val(response.doctor.issue);
                    $('#specialties').val(response.doctor.specialties);
                }else{
                    $('#certificates').html('');
                    $('#conditions').html('');
                    $('#procedures').html('');
                    $('#location').val("");
                    $('#latitude').val("");
                    $('#longitude').val("");
                    $('#about').val("");
                    $('#education').val("");
                    $('#helping').val("");
                    $('#issue').val("");
                    $('#specialties').val("");

                }
                }
            });
        });
        $(".js-select2").select2({
            closeOnSelect: false,
            scrollAfterSelect: false,
            placeholder: "  Select Option",
            multiple: true,
            allowHtml: false,
            allowClear: true,
            tags: true,
        }).on('select2:selecting', function(e) {
            var cur = e.params.args.data.id;
            var old = (e.target.value == '') ? [cur] : $(e.target).val().concat([cur]);
            $(e.target).val(old).trigger('change');
            $(e.params.args.originalEvent.currentTarget).attr('aria-selected', 'true');
            return false;
        });
        $('#event_form').on('submit', function() {
            $('#submit_btn').attr('disabled', 'disabled');
        });

        document.getElementById("event_form").onkeypress = function(e) {
            var key = e.charCode || e.keyCode || 0;
            if (key == 13) {
                e.preventDefault();
            }
        }
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-end p-0">
                            <div>
                                <h3>Profile Management</h3>
                            </div>

                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="wallet-table" style="border-radius: 18px;">
                            <form id="event_form" action="{{ route('admin_add_doctor_details') }}" method="post">
                                @csrf
                                <div class=" p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="selectmedicine">Select Doctor</label>
                                            <select class="form-control" name="doctor_id" id="doctor">
                                                <option value="">Select Doctor</option>
                                                @foreach ($doctors as $doc)
                                                    <option value="{{ $doc->id }}">{{ $doc->name." ".$doc->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="selectmedicine">Certifications and
                                                Licensing</label>
                                            <select class="js-select2" name="certificates[]" id="certificates" multiple="multiple"></select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="selectmedicine">Condition Treated</label>
                                            <select class="js-select2" name="conditions[]" id="conditions" multiple="multiple">
                                                <option value="Hyperthyroidism">Hyperthyroidism</option>
                                                <option value="Disorders of Lipoid Metabolism">Disorders of Lipoid
                                                    Metabolism</option>
                                                <option value="Hearing Loss">Hearing Loss</option>
                                                <option value="Osteoarthritis">Osteoarthritis</option>
                                                <option value="Telehealth">Polycystic Ovarian Syndrome (PCOS)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="selectmedicine">Procedures Performed</label>
                                            <select class="js-select2" name="procedures[]" id="procedures" multiple="multiple">
                                                <option value="Physical Therapy">Physical Therapy</option>
                                                <option value="Venipuncture">Venipuncture</option>
                                                <option value="Occupational Therapy Evaluation">Occupational Therapy Evaluation</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="selectmedicine">Location</label>
                                            <input type="text" name="location" class="form-control" id="location" placeholder="Enter Your Location">
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="selectmedicine">Latitude</label>
                                            <input type="text" name="latitude" class="form-control" id="latitude" placeholder="Enter Latitude of your Location">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="selectmedicine">Lonigtude</label>
                                            <input type="text" name="longitude" class="form-control" id="longitude" placeholder="Enter Longitude of your Location">
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="selectmedicine">Experience</label>
                                            <input type="text" name="experience" class="form-control" id="experience" placeholder="Enter Work Experience in Years">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="about">About:</label>
                                            <textarea class="form-control" name="about" id="about" rows="4"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="education">Medical Education</label>
                                            <textarea class="form-control" name="education" id="education" rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="helping">Approach to Helping:</label>
                                            <textarea class="form-control" name="helping" id="helping" rows="4"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="issue">Specific Issues Skilled at Helping With</label>
                                            <textarea class="form-control" name="issue" id="issue" rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label class="fw-bolder" for="specialties">Specialties:</label>
                                            <textarea class="form-control" name="specialties" id="specialties" rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="text-end">
                                            <button type="submit" id="submit_btn"
                                                class="btn descrip-save-btn">Save</button>
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
    </div>
@endsection
