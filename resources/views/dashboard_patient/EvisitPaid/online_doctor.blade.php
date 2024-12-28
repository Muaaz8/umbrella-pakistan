@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Online Doctors</title>
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

        $(document).ready(function(){
            let doc_count = "{{count($doctors)}}";
            let loc_id = "{{$loc_id}}";
            let spec_id = "{{$id}}";
            if(doc_count == 0)
            {
                $.ajax({
                    type: "POST",
                    url: "/send/doctors/online/alert",
                    data: {
                        spec_id: spec_id,
                        loc_id: loc_id,
                    },
                    success: function(data) {

                    }
                });
            }
        });

        function inquiryform(doc_id) {
            // alert('ok');
            var doc_id = $(doc_id).attr('id');
            var sp_id = $("#sp_id" + doc_id).val();

            $("#doc_id").val(doc_id);
            $("#doc_sp_id").val(sp_id);
            $('#inquiryModal').modal('show');
        }

        function newinquiryform(doc_id) {
            // alert('ok');
            var doc_id = $(doc_id).attr('id');
            var sp_id = $("#sp_id" + doc_id).val();

            $("#new_doc_id").val(doc_id);
            $("#new_doc_sp_id").val(sp_id);
            $('#exampleModal').modal('show');
        }

        $('#submit_btn').click(function() {
            var temp = "";
            // alert($('.select2-selection__rendered'));
            // console.log($('.select2-selection__rendered'));
            if ($('#s1').is(":checked") || $('#s2').is(":checked") || $('#s3').is(":checked") || $('#s4').is(
                    ":checked") || $('#s5').is(":checked")) {
                return true;
            } else {
                $('#submit_btn').type = '';
                alert("Error: Please select atleast one of these symptoms");
                return false;
            }
            $('#sympt').val(temp);
        });

        $('#inqury_form').submit(function () {
            $('#buton').attr('disabled', true);
            var element = $(".butn");
            // element.addClass("buttonload");
            element.html('<i class="fa fa-spinner fa-spin"></i>Processing...');
    });

        Echo.channel('load-online-doctor')
            .listen('loadOnlineDoctor', (e) => {
                var spec_id = "{{$id}}";
                var loc_id = "{{$loc_id}}";
                $.ajax({
                    type: "POST",
                    url: "/get/patient/online/doctors/"+spec_id+"/"+loc_id+"",
                    data: {
                        spec_id: spec_id,
                        loc_id: loc_id,
                    },
                    success: function(data) {
                        $('#load_OnlineDoctors').html(data);
                    }
                });
            });
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="row m-auto all-doc-wrap">
                <div class="spec__loCation meet__new d-flex justify-content-center meet_select_loca d-flex">
                        <p id="selected_state">{{$state->name}}</p>
                        <i class="fa-solid fa-sort-down pt-1"></i>
                    </div>
                    <h3 class="pb-2">Online Doctors</h3>
                    <input type="hidden" id="load_online_doctors"
                        value="{{ route('load_online_doctors', ['id' => $id]) }}">
                    <div class="row clearfix" id="load_OnlineDoctors">
                        @forelse ($doctors as $doctor)
                            <div class="col-md-4 col-lg-3 col-sm-6 mb-3">
                                <div class="card">
                                    <div class="additional">
                                        <div class="user-card">
                                            <img src="{{ $doctor->user_image }}" alt="" />
                                        </div>
                                    </div>

                                    <div class="general">
                                        <h4 class="fs-5">Dr.
                                            {{ ucfirst($doctor->name) . ' ' . ucfirst($doctor->last_name) }}
                                        </h4>
                                        <h6 class="m-0">{{ $doctor->sp_name }}</h6>
                                        <h6 class="m-0 all__doc__ini_pr pt-2"><span>Initial Price:</span> Rs. {{$price->initial_price}}</h6>
                                        @if($price->follow_up_price != null)
                                        <h6 class="m-0 all__doc__ini_pr"><span>Follow-up Price:</span> Rs. {{$price->follow_up_price}}</h6>
                                        @else
                                        <h6 class="m-0 all__doc__ini_pr"><span>Follow-up Price:</span> Rs. {{$price->initial_price}}</h6>
                                        @endif
                                        <input type="hidden" id="sp_id{{ $doctor->id }}"
                                            value="{{ $doctor->specialization }}">
                                        @if ($doctor->rating > 0)
                                            <div class="star-ratings">
                                                <div class="fill-ratings" style="width: {{ $doctor->rating }}%;">
                                                    <span>★★★★★</span>
                                                </div>
                                                <div class="empty-ratings">
                                                    <span>★★★★★</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="star-ratings">
                                                <div class="fill-ratings" style="width: 0%;">
                                                    <span>★★★★★</span>
                                                </div>
                                                <div class="empty-ratings">
                                                    <span>★★★★★</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="appoint-btn">
                                            <button type="button" class="btn btn-primary"
                                                onclick="window.location.href='/view/doctor/{{ $doctor->id }}'"> View
                                                Profile </button>
                                            <button id="{{ $doctor->id }}" type="button" class="btn btn-primary"
                                                onclick="newinquiryform(this)">
                                                TALK TO DOCTOR
                                            </button>
                                            {{-- <button id="{{ $doctor->id }}" class="btn btn-primary"
                                                onclick="inquiryform(this)">
                                                TALK TO DOCTOR
                                            </button> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @if($id==21)
                            <p class="mx-5" style="margin-left: 1rem!important; font-size: 22px;">No Doctor Available. You
                                can set an appointment <a href="/psych/book/appointment/{{$id}}/{{$state->id}}">here</a></p>
                        @else
                            <p class="mx-5" style="margin-left: 1rem!important; font-size: 22px;">No Doctor Available. You
                                can set an appointment <a href="/book/appointment/{{$id}}/{{$state->id}}">here</a></p>
                        @endif
                        @endforelse
                        <div class="row d-flex justify-content-center">
                            <div class="paginateCounter">
                                {{ $doctors->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- ////////////////// MODAL ///////////// -->

        <div class="modal fade" id="inquiryModal" tabindex="-1" style="font-weight: normal;" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h5 class="modal-title" id="symp">Inquiry Form <br>
                            <p class="fs-6 fw-light">Please fill this form to continue</p>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('patient_inquiry_store') }}" method="POST"
                            onsubmit="return checkForm(this)">
                            @csrf
                            <div class="row p-3">
                                <div class="col-md-12 mb-2">
                                    <div class="inquiry-form-checkbox">
                                        @if ($session != null)
                                            <input type="hidden" id="price" name="price"
                                                value="{{ $price->initial_price }}">
                                        @else
                                            <input type="hidden" id="price" name="price"
                                                value="{{ $price->initial_price }}">
                                        @endif
                                        <h6>Symptoms</h6>
                                        <div class="d-flex flex-wrap">
                                            <input type="hidden" id="doc_sp_id" name="doc_sp_id">
                                            <input type="hidden" name="doc_id" id="doc_id">
                                            <input type='hidden' value="0" name='Headache'>
                                            <input type='hidden' value="0" name='Flu'>
                                            <input type='hidden' value="0" name='Fever'>
                                            <input type='hidden' value="0" name='Nausea'>
                                            <input type='hidden' value="0" name='Others'>
                                            <input type='hidden' value="0" id='sympt' name='sympt'>

                                            <input type="checkbox" id="s1" name="Headache" value="1">
                                            <label for="s1">Headache</label>

                                            <input type="checkbox" id="s2" name="Flu" value="1">
                                            <label for="s2">Flu</label>

                                            <input type="checkbox" id="s3" name="Fever" value="1">
                                            <label for="s3">Fever</label>

                                            <input type="checkbox" id="s4" name="Nausea" value="1">
                                            <label for="s4">Nausea</label>

                                            <input type="checkbox" id="s5" name="Others" value="1">
                                            <label for="s5">Others</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="">
                                        <h6>Description</h6>
                                        <textarea required="" rows="4" id="symp_text" name="problem" class="form-control no-resize"
                                            placeholder="Add Description..." style="line-height: 2.3;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="submit_btn" id="submit_btn"
                                    class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- =================== NEW MODAL =========================== -->

        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="symp">Inquiry Form <br>
                            <p class="fs-6 fw-light">Please fill this form to continue</p>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="inqury_form" action="{{ route('isabel_inquiry') }}" method="POST">
                            @csrf
                            <div class="row p-3">
                                <div class="col-md-12 mb-2">
                                    <div class="inquiry-form-checkbox">
                                        <h6>Symptoms</h6>
                                        @if ($session != null)
                                            <input type="hidden" id="price" name="price"
                                                value="{{ $price->initial_price }}">
                                        @else
                                            <input type="hidden" id="price" name="price"
                                                value="{{ $price->initial_price }}">
                                        @endif
                                        <input type="hidden" name="ses_id" value="{{ $ses_id }}">
                                        <input type="hidden" id="new_doc_sp_id" name="doc_sp_id">
                                        <input type="hidden" name="doc_id" id="new_doc_id">
                                        <div class="evist-sel">
                                            <select class="js-select2" name="symptoms[]" multiple="multiple" required>
                                                @foreach ($symp as $s)
                                                    <option value="{{ $s->symptom_name }}" data-badge="">
                                                        {{ $s->symptom_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="">
                                        <h6>Description</h6>
                                        <textarea required="" rows="4" id="symp_text" name="problem" class="form-control no-resize"
                                            placeholder="Add Description..." style="line-height: 2;"></textarea>
                                    </div>
                                    @if (Auth::user()->gender == 'female')
                                        <div class="d-flex mt-3">
                                            <p><b> Are you pregnant? </b></p>
                                            <div class="form-check mx-3">
                                                <input class="form-check-input" type="radio" name="Pregnancy"
                                                    id="flexRadioDefault1" value="y">
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    yes
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="Pregnancy"
                                                    id="flexRadioDefault2" value="n">
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    no
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" id="buton" class="btn btn-primary butn">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>





    </div>
@endsection
