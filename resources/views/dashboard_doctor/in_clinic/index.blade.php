@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection
@section('top_import_file')
@endsection
@section('page_title')
    <title>Inclinic</title>
@endsection
@section('bottom_import_file')
    <script type="text/javascript">
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function start(id) {
            var name = $('#name-' + id).html()
            var reason = $('#reason-' + id).html()
            $('#consulation-name').html(name)
            $('#patient-name').html(name)
            $('#patient-age').html()
            $('#patient-reason').html(reason)
            $('.consultation-side').removeClass('d-none');
        }

        Echo.channel('inclinic-patient-update')
            .listen('InClinicPatientUpdate', (e) => {
                var keys = $('.key').length+1;
                $.ajax({
                    type: "get",
                    url: "{{route('doctor_in_clinic')}}",
                    data: {
                        id:e.session_id,
                    },
                    success: function (response) {
                        $('.waiting-patients').append(
                            `<div class="accordion-item rounded-3">
                                <h2 class="accordion-header rounded-3" id="flush-heading${response.id }">
                                    <div class="accordion-button collapsed rounded-3" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapse${response.id }"
                                        aria-expanded="false" aria-controls="flush-collapse${response.id }">
                                        <div class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                            <span class="key">${keys})</span>
                                            <h5 id="name-${response.id }">
                                                ${response.user.name} ${response.user.last_name}</h5>
                                        </div>
                                    </div>
                                </h2>
                                <div id="flush-collapse${response.id }" class="accordion-collapse collapse"
                                    aria-labelledby="flush-heading${response.id }"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <p id="reason-${response.id }">${response.reason }</p>

                                        <div class="d-flex gap-2 justify-content-center align-items-center">
                                            <button class="btn btn-outline-success w-100"
                                                onclick="start(${response.id })">Start Consultation</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                        );
                    }
                });
        });

        function getMedicienByCategory(med_id) {
            $('.loadMedicienProduct').html('');
            $('.medicine_category').addClass('d-none');
            $('.loadMedicienProduct').removeClass('d-none');
            $('#selected_med_cat').val(med_id);
            var name = '';
            $.ajax({
                type: 'POST',
                url: "{{URL('/inclinic_new_get_products_by_category')}}",
                data: {
                    med_id: med_id,
                    name: name,
                    type: 'medicine'
                },
                success: function (response) {
                    if (response.length != 0) {
                        var type = 'med';
                        $.each(response, function (key, value) {
                            if (value.added == 'yes') {
                                $('.loadMedicienProduct').append(
                                    `<div class="col-4"><button class="btn w-100" onclick="javascript:void(0)"
                                        title="${value.name }"> ${value.name} </button>
                                    </div>`
                                );
                            } else {
                                $('.loadMedicienProduct').append(
                                    `<div class="col-4"><button class="btn w-100" onclick="javascript:void(0)"
                                        title="${value.name }"> ${value.name} </button>
                                    </div>`
                                );
                            }
                        });
                    } else {

                        $('.loadMedicienProduct').append(
                            '<div class="col-md-4">' +
                            '<button onclick="javascript:void(0)">No products found in this category</button>' +
                            '</div>'
                        );
                    }
                }
            });
        }

        function loadLabItems() {
            var name = $('#Lab_search').val();
            $('#loadLabItems').html('');
            if (name == null || name == '') {
                name = '';
            }
            $.ajax({
                type: 'POST',
                url: "{{URL('/inclinic_new_get_lab_products_video_page')}}",
                data: {
                    name: name,
                },
                success: function (response) {
                    $('#loadLabItems').html('');
                    if (response.length != 0) {

                        $.each(response, function (key, value) {


                            if (value.added == 'yes') {
                                $('#loadLabItems').append(
                                    `<div class="col-4"><button class="btn w-100" onclick="javascript:void(0)" title="${value.TEST_NAME}">${value.TEST_NAME}</button></div>`
                                );
                            } else {
                                $('#loadLabItems').append(
                                    `<div class="col-4"><button class="btn w-100" onclick="javascript:void(0)" title="${value.TEST_NAME}">${(value.TEST_NAME).substring(0,12)+"..."}</button></div>`
                                );
                            }



                        });
                    } else {

                        $('#loadLabItems').append(
                            '<div class="col-md-4">' +
                            '<button onclick="javascript:void(0)">No products found </button>' +
                            '</div>'
                        );
                    }
                }
            });
        }
    </script>
@endsection
@section('content')
    <section class="d-flex align-items-center justify-content-center">
        <div
            class="row p-3 w-100 d-flex flex-wrap flex-column-reverse flex-sm-row  flex-sm-nowrap gap-2 waiting-room-container align-items-start justify-content-center">
            <section class="col-12 col-sm-4 d-flex flex-column bg-white p-3 rounded-3 shadow-sm">
                <div class="d-flex flex-column gap-3 waiting-patients-section">
                    <h4>Waiting Patients</h4>
                    <div class="accordion accordion-flush waiting-patients-accordion rounded-3 d-flex flex-column gap-2 pe-2 waiting-patients"
                        id="accordionFlushExample">
                        @foreach ($patients as $key => $pat)
                            <div class="accordion-item rounded-3">
                                <h2 class="accordion-header rounded-3" id="flush-heading{{ $pat->id }}">
                                    <div class="accordion-button collapsed rounded-3" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $pat->id }}"
                                        aria-expanded="false" aria-controls="flush-collapse{{ $pat->id }}">
                                        <div class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                            <span class="key">{{ ++$key }})</span>
                                            <h5 id="name-{{ $pat->id }}">
                                                {{ $pat->user->name . ' ' . $pat->user->last_name }}</h5>
                                        </div>
                                    </div>
                                </h2>
                                <div id="flush-collapse{{ $pat->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="flush-heading{{ $pat->id }}"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <p id="reason-{{ $pat->id }}">{{ $pat->reason }}</p>

                                        <div class="d-flex gap-2 justify-content-center align-items-center">
                                            <button class="btn btn-outline-success w-100"
                                                onclick="start({{ $pat->id }})">Start Consultation</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </section>
            <aside class="col-12 col-sm-7 ps-sm-2 pe-sm-2 ps-0 pe-0 d-none consultation-side">
                <div class="d-flex flex-column gap-2 w-100 h-100">
                    <section class="shadow-sm rounded-3 next-patient-section bg-white">
                        <div class="bg-white rounded-3 p-3 d-flex flex-column gap-1 patient-info">
                            <h4>Next Patient</h4>
                            <div class="accordion accordion-flush waiting-patients-accordion rounded-3 d-flex flex-column gap-2 pe-2"
                                id="accordionFlushExample">
                                <div class="accordion-item rounded-3  shadow-hide">
                                    <h2 class="accordion-header rounded-3" id="flush-headingOne">
                                        <div class="accordion-button collapsed rounded-3" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#next-patient-collapse"
                                            aria-expanded="false" aria-controls="next-patient-collapse">
                                            <div
                                                class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                                <h5 id="consulation-name">John Doe</h5>
                                            </div>
                                        </div>
                                    </h2>
                                    <div id="next-patient-collapse" class="accordion-collapse collapse"
                                        aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body d-flex flex-column gap-1 p-2">
                                            <div class="d-flex justify-content-between align-items-center gap-1">
                                                <div>
                                                    <label for="patient-name">Name:</label>
                                                    <h6 id="patient-name">John Doe</h6>
                                                </div>
                                                <div>
                                                    <label for="patient-age">Age:</label>
                                                    <h6 id="patient-age">31</h6>
                                                </div>
                                                <div>
                                                    <label for="patient-city">City:</label>
                                                    <h6 id="patient-city">Karachi</h6>
                                                </div>
                                            </div>
                                            <div>
                                                <label for="patient-reason">Reason:</label>
                                                <p id="patient-reason">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing
                                                    elit. Esse culpa quis maxime dolore libero vitae
                                                    deleniti iste saepe facere repellendus!
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 justify-content-center align-items-center">
                                            <button class="btn btn-outline-danger w-50 rounded-3">End Consultation</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="prescription-section rounded-3 bg-white shadow-sm h-100">
                        <div
                            class="prescription-container bg-white rounded-3 p-3 d-flex flex-column gap-2 h-100 overflow-y-auto">
                            <h4>Prescription</h4>
                            <ul class="nav nav-pills toggle-buttons d-flex gap-2 justify-content-around" id="pills-tab"
                                role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-home" type="button" role="tab"
                                        aria-controls="pills-home" aria-selected="true">
                                        <i class="fa-solid fa-pills"></i>
                                        <span>Medicines</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-profile" type="button" role="tab"
                                        aria-controls="pills-profile" aria-selected="false" onclick="loadLabItems()">
                                        <i class="fa-solid fa-flask"></i>
                                        <span>Lab tests</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-contact" type="button" role="tab"
                                        aria-controls="pills-contact" aria-selected="false">
                                        <i class="fa-solid fa-flask-vial"></i>
                                        <span>Imaging</span>
                                    </button>
                                </li>
                            </ul>
                            {{-- <div class="search-bar-container w-100 form-control px-2 mt-1">
                            <form class="d-flex align-items-center justify-content-between">
                                <input type="search" name="search" placeholder="Search Doctor Name"
                                    class="search-field w-100" id="search" />
                                <button type="button" class="search-btn">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </div> --}}
                            <div class="tabs-medicine tab-content px-3 py-2" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <div class="row gx-3 gy-2 medicine_category">
                                        @foreach ($med as $cat)
                                            <div class="col-4"><button class="btn w-100"
                                                    title="{{ $cat->title }}" onclick="getMedicienByCategory('{{ $cat->id }}')">{{ \Str::limit($cat->title, 12, '...') }}</button>
                                            </div>
                                            {{-- onclick="getMedicienByCategory('{{ $cat->id }}')"> --}}
                                        @endforeach
                                    </div>
                                    <div class="row gx-3 gy-2 d-none loadMedicienProduct">

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                    aria-labelledby="pills-profile-tab">
                                    <div class="row gx-3 gy-2" id="loadLabItems">
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                        <div class="col-4"><button class="btn w-100">Pain & Fever</button></div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                    aria-labelledby="pills-contact-tab">
                                    <div class="row gx-3 gy-2">
                                        @foreach ($img as $cat)
                                            <div class="col-4"><button class="btn w-100"
                                                    title="{{ $cat->name }}">{{ \Str::limit($cat->name,12,'...') }}</button>
                                            </div>
                                            {{-- onclick="getImagingProduct('{{$cat->id}}')">{{$cat->name}} --}}
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </aside>
        </div>
    </section>
@endsection

@section('script')
@endsection
