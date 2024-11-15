@extends('layouts.dashboard_doctor')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('top_import_file')
@endsection
@section('page_title')
    <title>UHCS - Final Prescription</title>
@endsection
@section('bottom_import_file')
    <script type="text/javascript">
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function del_med() {
            $("#delForm_med").submit();
        }

        function del_lab() {
            $("#delForm_lab").submit();
        }

        function del_img() {
            $("#delForm_img").submit();
        }

        function dosage(a) {
            $('#days').prop('selectedIndex', 0);
            $('#units').prop('selectedIndex', 0);
            $('#dose').prop('selectedIndex', 0);
            $('#instructions').val('');
            $('#med_current_price').val('');
            $('#med_id').val('');
            var session_id = $('#session_id').val();
            id = a;
            med_id = id;
            $.ajax({
                type: 'POST',
                url: '/get_med_detail',
                data: {
                    id: med_id,
                    session_id: session_id,
                },
                success: function(response) {
                    var res = response.hasOwnProperty("update");
                    if (res == true) {
                        var splitTime = response.update['time'].split("hrs");
                        if (splitTime[0] == 6) {
                            $('#dose').prop('selectedIndex', 1);
                        } else if (splitTime[0] == 8) {
                            $('#dose').prop('selectedIndex', 2);
                        } else if (splitTime[0] == 12) {
                            $('#dose').prop('selectedIndex', 3);
                        } else if (splitTime[0] == 24) {
                            $('#dose').prop('selectedIndex', 4);
                        }


                        $('#med_name').text(response.product.name);

                        $('#instructions').val(response.update['comment']);

                        $('#med_current_price').val(response.product.sale_price);

                        $('#units').text('');
                        $('#units').append('<option value="">Choose Unit</option>');
                        $.each(response.units, function(key, value) {
                            $('#units').append('<option value="' + value.unit + '">' + value.unit +
                                '</option>')
                        });
                        $('#units option[value=' + response.update['units'] + ']').attr('selected', 'selected');

                        $('#days').text('');
                        $('#days').val(response.update['days']);
                        // $('#days').append('<option value="">Choose Number of Days</option>')
                        // $.each(response.days, function(key, value) {
                        //     $('#days').append('<option value="' + value.days + '">' + value.days + '</option>')
                        // });
                        // $('#days option[value="'+response.update['days']+'"]').attr('selected','selected');

                    } else {
                        $('#med_name').text(response.product.name);
                        $('#med_current_price').val(response.product.sale_price);

                        $('#units').text('');
                        $('#units').append('<option value="">Choose Unit</option>');
                        $.each(response.units, function(key, value) {
                            $('#units').append('<option value="' + value.unit + '">' + value.unit +
                                '</option>')
                        });

                        $('#days').text('');
                        $('#days').append('<option value="">Choose Number of Days</option>')
                        $.each(response.days, function(key, value) {
                            $('#days').append('<option value="' + value.days + '">' + value.days +
                                '</option>')
                        });
                    }

                }
            });
            // console.log(med_id[1]);
            $('#med_id').val(med_id);
            $('#session_id_modal').val(session_id);
            $('#pro_id_modal').val(med_id);
            $('#type_modal').val('medicine');
            $('#dosage_modal').modal('show');

        }

        function add_notes() {
            var session_id = $('#session_id').val();
            var diagnosis = $('#diagnosis').val();
            var notes = $('#notes').val();
            $.ajax({
                type: 'POST',
                url: '/add_diagnosis_and_notes',
                data: {
                    session_id: session_id,
                    diagnosis: diagnosis,
                    notes: notes,
                },
                success: function(response) {}
            });
        }

        $('#recommendation').submit(function() {
            $('#buton').attr('disabled', true);
            var element = $(".butn");
            // element.addClass("buttonload");
            element.html('<i class="fa fa-spinner fa-spin"></i>Processing...');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets\js\doctor_dashboard_script\recommendations.js') }}"></script>
@endsection
@section('content')
    <div class="dashboard-content">
        <div class="container">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-end p-0">
                            <div class="m-auto">
                                <h3>Final Prescription And Diagnosis</h3>
                            </div>
                        </div>
                        <form method="post" id="recommendation" action="{{ route('recommendations.store') }}">
                            @csrf
                            @php
                                $added = true;
                            @endphp
                            <input hidden="" id="session_id" type="text" name="session_id"
                                value="{{ $getSession->id }}" />
                            <div class="wallet-table p-3">
                                <div class="row">
                                    <div class="col-lg-6">

                                        <div class="text-areas-sec">
                                            <label for="diagnosis" class="form-label">Diagnosis</label>
                                            <textarea class="form-control" onchange="add_notes()" id="diagnosis" name="diagnosis" rows="4">{{ $getSession->diagnosis }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="text-areas-sec">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea class="form-control" onchange="add_notes()" id="notes" name="note" rows="4">{{ $getSession->provider_notes }}</textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="pres-item-sec mt-2">
                                    <div class="d-flex justify-content-between mb-2 mt-4">
                                        <h4 id="pres_item">Prescribed Items</h4>

                                        <!-- <button class="btn btn-secondary btn-sm con-recomm-btn py-1 px-4" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                              ADD ITEMS
                            </button> -->
                                    </div>
                                </div>
                                <div class="pres-item-sec mt-2" id="pres">
                                    <input hidden="" id="pat_id" type="text" name="patient_id"
                                        value="{{ $getSession->patient_id }}" />
                                    <input hidden="" id="doc_id" type="text" name="doc_id"
                                        value="{{ $getSession->doctor_id }}" />
                                    <input hidden="" id="session_id" type="text" name="session_id"
                                        value="{{ $getSession->id }}" />
                                    @foreach ($pres as $pre)
                                        @if ($pre->mode == 'medicine')
                                            <div class="pharmacy-item">
                                                <div class="d-flex align-items-center">
                                                    <div class="d-item1">
                                                        <i class="fa-solid fa-capsules fs-3"></i>
                                                        <div class="pharmacy-tag">{{ $pre->mode }}</div>
                                                    </div>
                                                    <div class="d-item2 d-flex align-items-center">
                                                        <p class="fs-5">{{ $pre->name }}</p>&nbsp;<p
                                                            class="me-3 ps-3">{{ $pre->usage }}</p>
                                                    </div>
                                                    <div class="d-item3"><button type="button"
                                                            onclick="dosage({{ $pre->id }})"
                                                            class="btn add-dosage-btn">Add Dosage</button></div>
                                                    <input type="hidden" name="session_id" value="{{ $getSession->id }}">
                                                    <input type="hidden" name="pro_id" value="{{ $pre->id }}">
                                                    <input type="hidden" name="type" value="{{ $pre->mode }}">
                                                    <div class="d-item4"><a type="button"
                                                            href="/delete_prescribe_item_from_recom/{{ $pre->pres_id }}"><i
                                                                class="fa-solid fa-circle-xmark fs-4 text-danger"></i></a>
                                                    </div>
                                                </div>
                                                @php
                                                    $added = false;
                                                    if ($pre->usage != '') {
                                                        $added = true;
                                                    }
                                                @endphp
                                            </div>
                                        @elseif($pre->mode == 'lab-test')
                                            <div class="pharmacy-item">
                                                <div class="d-flex align-items-center">
                                                    <div class="d-item1">
                                                        <i class="fa-solid fa-capsules fs-3"></i>
                                                        <div class="lab-tag">{{ $pre->mode }}</div>
                                                    </div>
                                                    <div class="d-item2">
                                                        <p class="fs-5">{{ $pre->TEST_NAME }}</p>
                                                    </div>
                                                    <div class="d-item3"></div>
                                                    <input type="hidden" name="session_id" value="{{ $getSession->id }}">
                                                    <input type="hidden" name="pro_id" value="{{ $pre->TEST_CD }}">
                                                    <input type="hidden" name="type" value="{{ $pre->mode }}">
                                                    <div class="d-item4"><a type="button"
                                                            href="/delete_prescribe_item_from_recom/{{ $pre->pres_id }}"><i
                                                                class="fa-solid fa-circle-xmark fs-4 text-danger"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($pre->mode == 'imaging')
                                            <div class="pharmacy-item">
                                                <div class="d-flex align-items-center">
                                                    <div class="d-item1">
                                                        <i class="fa-solid fa-capsules fs-3"></i>
                                                        <div class="imaging-tag">{{ $pre->mode }}</div>
                                                    </div>
                                                    <div class="d-item2 d-flex align-items-center">
                                                        <p class="fs-5">{{ $pre->name }}</p>&nbsp;<p class="">
                                                            (Location: {{ $pre->location }})</p>
                                                    </div>
                                                    <div class="d-item3"></div>
                                                    <input type="hidden" name="session_id"
                                                        value="{{ $getSession->id }}">
                                                    <input type="hidden" name="pro_id" value="{{ $pre->id }}">
                                                    <input type="hidden" name="type" value="{{ $pre->mode }}">
                                                    <div class="d-item4"><a type="button"
                                                            href="/delete_prescribe_item_from_recom/{{ $pre->pres_id }}"><i
                                                                class="fa-solid fa-circle-xmark fs-4 text-danger"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="pharmacy-item">
                                    @if ($added)
                                        <div class="text-center mt-3"><button id="buton" type="submit"
                                                class="btn con-recomm-btn butn">CONFIRM RECOMMENDATIONS</button></div>
                                    @else
                                        <div class="text-center mt-3"><button type="button"
                                                onclick="alert('Dosage Required');" class="btn con-recomm-btn">CONFIRM
                                                RECOMMENDATIONS</button></div>
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

    <!-- ------------------Dosage-Modal-start------------------ -->
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="dosage_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="med_name">Bicalocatamide Tablet</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="dosage-body">
                        <form action="{{ url('/add_dosage') }}" method="POST">
                            @csrf
                            <input id="med_id" hidden="">
                            <input id="med_current_price" name="price" hidden="">
                            <input hidden="" id="session_id_modal" type="text" name="session_id"
                                value="" />
                            <input type="hidden" id="pro_id_modal" name="pro_id" value="">
                            <input type="hidden" id="type_modal" name="type" value="">
                            <h5>Dosage</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="Expirationmonth">Choose Time For Dose</label>
                                    <select id="dose" name="med_time" class="form-select" required>
                                        <option value="">Choose Time</option>
                                        <option value="6">6hrs</option>
                                        <option value="8">8hrs</option>
                                        <option value="12">12hrs</option>
                                        <option value="24">24hrs</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="Expirationmonth">Choose Unit</label>
                                    <select id="units" name="units" class="form-select" required>
                                        <option selected>1</option>
                                        <option value="1">2</option>
                                        <option value="2">3</option>
                                        <option value="3">4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2 mb-2">
                                <div class="col-md-6">
                                    <label for="Expirationmonth">Choose Number of Days</label>
                                    <input id="days" name="days" class="form-control" type="number">
                                </div>
                                <div class="col-md-6">
                                    <label for="instructions">Special Instructions</label>
                                    <input type="text" id="instructions" name="instructions"
                                        class="bg-light form-control" placeholder="Special Instructions" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button onclick="add_dose()" data-dismiss="modal"
                                    class="btn con-recomm-btn">DONE</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- ------------------Dosage-Modal-end------------------ -->

    <!-- ------------------location-Modal-start------------------ -->
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="add_location" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Zip Code To Nearest Imaging Location</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="dosage-body">
                        <form action="">
                            <div class="row m-auto">
                                <h5>Zip Code</h5>
                                <div class="col-lg-12 d-flex align-items-center">
                                    <input type="text" class="bg-light form-control" placeholder="Add Zip Code Here">
                                    <button class="btn zip-search" type="button"><i
                                            class="fa-solid fa-magnifying-glass fs-5"></i></button>
                                </div>
                                <!-- <div class="col-lg-2">
                          <div class="px-2 py-2">
                            <i class="fa-solid fa-magnifying-glass fs-5"></i>
                          </div>
                        </div> -->
                            </div>
                            <div class="pt-2 location_content">
                                <div class="row m-auto mb-2">
                                    <div class="col-lg-6">
                                        <div class="locations">
                                            <h6 class="m-0">Illinois</h6>
                                            <p>Greenville Hospital,62246</p>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="locations">
                                            <h6 class="m-0">Illinois</h6>
                                            <p>Greenville Hospital,62246</p>
                                        </div>

                                    </div>
                                </div>
                                <div class="row m-auto mb-2">
                                    <div class="col-lg-6">
                                        <div class="locations">
                                            <h6 class="m-0">Illinois</h6>
                                            <p>Greenville Hospital,62246</p>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="locations">
                                            <h6 class="m-0">Illinois</h6>
                                            <p>Greenville Hospital,62246</p>
                                        </div>

                                    </div>
                                </div>
                                <div class="row m-auto mb-2">
                                    <div class="col-lg-6">
                                        <div class="locations">
                                            <h6 class="m-0">Illinois</h6>
                                            <p>Greenville Hospital,62246</p>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="locations">
                                            <h6 class="m-0">Illinois</h6>
                                            <p>Greenville Hospital,62246</p>
                                        </div>

                                    </div>
                                </div>
                                <div class="row m-auto mb-2">
                                    <div class="col-lg-6">
                                        <div class="locations">
                                            <h6 class="m-0">Illinois</h6>
                                            <p>Greenville Hospital,62246</p>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="locations">
                                            <h6 class="m-0">Illinois</h6>
                                            <p>Greenville Hospital,62246</p>
                                        </div>

                                    </div>
                                </div>
                                <div class="row m-auto mb-2">
                                    <div class="col-lg-6">
                                        <div class="locations">
                                            <h6 class="m-0">Illinois</h6>
                                            <p>Greenville Hospital,62246</p>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="locations">
                                            <h6 class="m-0">Illinois</h6>
                                            <p>Greenville Hospital,62246</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn con-recomm-btn">DONE</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ------------------location-Modal-end------------------ -->


    <!-- ------------------Add_Items-Modal-start------------------ -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md 12">
                        <div class="">
                            <div class="assad prescription-button-wrap p-2">

                                <div class="btn-wrapper-div">
                                    <button class="but btn-1">Medicines</button>
                                    <div class="content">
                                        <div class="prescriptio-card-wrapper">
                                            <div class="card">
                                                <div class="card-header fw-bold"><i class="fa-solid fa-capsules"></i> ADD
                                                    MEDICINES <i class="fa-solid  but"></i></div>
                                                <div class="card-body">
                                                    <div class="wrap">
                                                        <div class="search">
                                                            <input type="text" class="searchTerm"
                                                                placeholder="What are you looking for?" />
                                                            <button type="submit" class="searchButton">
                                                                <i class="fa fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <button class="but1">AHAD</button>

                                                                <div class="content1">
                                                                    <div class="prescriptio-card-wrapper">
                                                                        <div class="card">
                                                                            <div class="card-header fw-bold"><i
                                                                                    class="fa-solid fa-circle-arrow-left but1"></i>
                                                                                AHAD <i class="fa-solid  but1"></i></div>
                                                                            <div class="card-body">
                                                                                <div class="wrap">
                                                                                    <div class="search">
                                                                                        <input type="text"
                                                                                            class="searchTerm"
                                                                                            placeholder="What are you looking for?" />
                                                                                        <button type="submit"
                                                                                            class="searchButton">
                                                                                            <i class="fa fa-search"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <button>AHAD</button>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <button>Antithyroid</button>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <button>Appetite</button>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <button>Appetite</button>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <button>Arthritis</button>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <button>Antiviral</button>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <button>Anxiety</button>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <button>Antifungal</button>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <button>Allergies</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button>Antithyroid</button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button>Appetite</button>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <button>Appetite</button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button>Arthritis</button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button>Antiviral</button>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <button>Anxiety</button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button>Antifungal</button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button>Allergies</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="btn-wrapper-div">
                                    <button class="but btn-2">Lab-Tests</button>
                                    <div class="content">
                                        <div class="prescriptio-card-wrapper">
                                            <div class="card">
                                                <div class="card-header fw-bold"><i class="fa-solid fa-flask"></i> ADD LAB
                                                    TESTS <i class="fa-solid  but"></i></div>
                                                <div class="card-body">
                                                    <div class="wrap">
                                                        <div class="search">
                                                            <input type="text" class="searchTerm"
                                                                placeholder="What are you looking for?" />
                                                            <button type="submit" class="searchButton">
                                                                <i class="fa fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <button>Digestive Health</button>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <button>General Health</button>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <button>Heart Health</button>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <button>Infectious Disease</button>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <button>STD</button>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <button>Men's Health</button>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <button>Others</button>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <button>Women's Health</button>
                                                            </div>
                                                            <!-- <div class="col-md-6">
                                    <button class="btn-3">Allergies</button>
                                  </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="btn-wrapper-div">
                                    <button class="but btn-3">Imagings</button>
                                    <div class="content">
                                        <div class="prescriptio-card-wrapper">
                                            <div class="card">
                                                <div class="card-header fw-bold"><i class="fa-solid fa-x-ray"></i>ADD
                                                    IMAGINGS <i class="fa-solid  but"></i></div>
                                                <div class="card-body">
                                                    <div class="wrap">
                                                        <div class="search">
                                                            <input type="text" class="searchTerm"
                                                                placeholder="What are you looking for?" />
                                                            <button type="submit" class="searchButton">
                                                                <i class="fa fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <button>CT SCAN</button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button>MR</button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button>MRI</button>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <button>MRA</button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button>ULTRASOUND</button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button>OTHERS</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>







                </div>

            </div>
        </div>
    </div>
@endsection
<!-- ------------------Add_Items-Modal-end------------------ -->
