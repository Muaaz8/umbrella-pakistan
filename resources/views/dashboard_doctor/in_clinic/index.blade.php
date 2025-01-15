@extends('layouts.dashboard_doctor')

@section('meta_tags')
<!-- Required meta tags -->
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
<style>
    .selected_medi {
        background: linear-gradient(to bottom, #2964bc, #082755);
        color: white !important;
    }

    .selected-value-bydoc {
        border: 1px solid #16de81;
        border-radius: 30px;
        font-size: 0.8rem;
        padding: 2px 13px;
        position: relative;
        /* margin: 0 10px 5px 0; */
        cursor: pointer;
    }

    .selected-value-bydoc i {
        position: absolute;
        right: -11px;
        font-size: 18px;
        color: red;
        cursor: ;
        top: -5px;
    }

    .nav-item {
        width: 25%;
    }

    .nav-link {
        width: 100%;
    }
    .videoLoaderDiv{
        width: 100%;
        height: 100vh;
        position: fixed;
        z-index: 999999;
        background-color: black;
        opacity: 0.8;
        align-items: center;
        justify-content: center;"
    }
    p{
        word-wrap: break-word;
    }

    .prescription-container .nav-link {
        padding: 0.25rem 0.5rem;
    }
</style>
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
            var user_id = $('#id-' + id).html()
            var name = $('#name-' + id).html()
            var reason = $('#reason-' + id).html()
            var age = $('#age-' + id).html()
            var phone = $('#phone-' + id).html()
            $('#session-id').html(id)
            $('#patient-id').html(user_id)
            $('#consulation-name').html(name)
            $('#patient-name').html(name)
            $('#patient-age').html(age)
            $('#patient-reason').html(reason)
            $('#patient-phone').html(phone)
            $('.consultation-side').removeClass('d-none');
            $('.start-button').attr('disabled','true');
            onPageLoadPrescribeItemLoad();
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

        Echo.channel('load-prescribe-item-list')
        .listen('LoadPrescribeItemList', (e) => {
            var session_id =  $('#session-id').html();
            var user_id = $('#patient-id').html();

            if (session_id == e.session_id && user_id == e.user_id) {
                $(".prescribed_items").html("");
                $.ajax({
                    type: "POST",
                    url: "{{URL('/inclinic_get_prescribe_item_list')}}",
                    data: {
                        session_id: session_id,
                    },
                    success: function (products) {
                        $(".prescribed_items").html("");
                        if (products.length > 0) {
                            $.each(products, function (key, product) {
                                if (product.mode == "medicine") {
                                    $(".prescribed_items").append(
                                        '<span class="selected-value-bydoc " onclick="dosage(' + product.id + ')">' +
                                            product.name +
                                            '<a class="position-absolute top-0 end-0 " style="z-index:100;" onclick="med_remove(' + product.id + '); event.stopPropagation();">' +
                                                '<i class="fa-solid fa-circle-xmark"></i>' +
                                            '</a>' +
                                        '</span>'
                                    );
                                } else if (product.mode == "lab-test") {
                                    $(".prescribed_items").append(
                                        '<span class="selected-value-bydoc ">' +
                                        product.TEST_NAME +
                                        '<a class="position-absolute top-0 end-0" onclick="lab_remove(' + product.TEST_CD + ')">' +
                                        '<i class="fa-solid fa-circle-xmark"></i>' +
                                        "</a>" +
                                        "</span>"
                                    );
                                } else if (product.mode == "imaging") {
                                    $(".prescribed_items").append(
                                        '<span class="selected-value-bydoc ">' +
                                        product.TEST_NAME +
                                        '<a class="position-absolute top-0 end-0" onclick="img_remove(' + product.TEST_CD + ')">' +
                                        '<i class="fa-solid fa-circle-xmark"></i>' +
                                        "</a>" +
                                        "</span>"
                                    );
                                }
                            });
                        } else {
                            $(".prescribed_items").append(
                                '<span class="selected-value-bydoc">Not Found Any Prescribed Item!!</span>'
                            );
                        }
                    },
                });
            }
        });

        function onPageLoadPrescribeItemLoad() {
            $(".prescribed_items").html("");
            var session_id =  $('#session-id').html();
            $.ajax({
                type: "POST",
                url: "{{URL('/inclinic_get_prescribe_item_list')}}",
                data: {
                    session_id: session_id,
                },
                success: function (products) {
                    if (products.length > 0) {
                        $.each(products, function (key, product) {
                            if (product.mode == "medicine") {
                                $(".prescribed_items").append(
                                    '<span class="selected-value-bydoc " onclick="dosage(' + product.id + ')">' +
                                        product.name +
                                        '<a class="position-absolute top-0 end-0 " style="z-index:100;" onclick="med_remove(' + product.id + '); event.stopPropagation();">' +
                                            '<i class="fa-solid fa-circle-xmark"></i>' +
                                        '</a>' +
                                    '</span>'
                                );
                            } else if (product.mode == "lab-test") {
                                $(".prescribed_items").append(
                                    '<span class="selected-value-bydoc ">' +
                                    product.TEST_NAME +
                                    '<a class="position-absolute top-0 end-0" onclick="lab_remove(' + product.TEST_CD + ')">' +
                                    '<i class="fa-solid fa-circle-xmark"></i>' +
                                    "</a>" +
                                    "</span>"
                                );
                            } else if (product.mode == "imaging") {
                                $(".prescribed_items").append(
                                    '<span class="selected-value-bydoc ">' +
                                    product.TEST_NAME +
                                    '<a class="position-absolute top-0 end-0" onclick="img_remove(' + product.TEST_CD + ')">' +
                                    '<i class="fa-solid fa-circle-xmark"></i>' +
                                    "</a>" +
                                    "</span>"
                                );
                            }
                        });
                    } else {
                        $(".prescribed_items").append(
                            '<span class="selected-value-bydoc">Not Found Any Prescribed Item!!</span>'
                        );
                    }
                },
            });
        }
        // Medicine
        function getMedicienByCategory(med_id) {
            $('.loadMedicienProduct').html('');
            $('.medicine_category').addClass('d-none');
            $('.back-button-medicine').removeClass('d-none');
            $('.loadMedicienProduct').removeClass('d-none');
            var session_id = $('#session-id').html();
            $('#selected_med_cat').val(med_id);

            var name = '';
            $.ajax({
                type: 'POST',
                url: "{{URL('/inclinic_new_get_products_by_category')}}",
                data: {
                    med_id: med_id,
                    session_id: session_id,
                    name: name,
                    type: 'medicine'
                },
                success: function (response) {
                    console.log(response);
                    if (response.length != 0) {
                        var type = 'med';
                        $.each(response, function (key, value) {
                            if (value.added == 'yes') {
                                $('.loadMedicienProduct').append(
                                    `<div class="col-4"><button class="btn w-100 selected_medi" onclick="javascript:void(0)"
                                        title="${value.name}"> ${(value.name).length > 12 ? (value.name).substring(0,12)+"..." : value.name} </button>
                                    </div>`
                                );
                            } else {
                                $('.loadMedicienProduct').append(
                                    `<div class="col-4"><button class="btn w-100" title="${value.name}"
                                        onclick="add_med(${value.id})" id="med_${value.id}">${(value.name).length > 12 ? (value.name).substring(0,12)+"..." : value.name}</button>
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

        $('.back-button-medicine').click(function (e) {
            e.preventDefault();
            $('.loadMedicienProduct').html('');
            $('.medicine_category').removeClass('d-none');
            $('.back-button-medicine').addClass('d-none');
            $('.loadMedicienProduct').addClass('d-none');
        });

        function add_med(product_id) {
            var user_id = $('#patient-id').html();
            var session_id = $('#session-id').html();
            console.log(user_id);
            $.ajax({
                type: 'POST',
                url: "{{URL('/inclinic_get_product_details')}}",
                data: {
                    id: product_id,
                    type: 'med',
                    user_id: user_id,
                    session_id: session_id,
                },
                success: function (product) {
                    $('#med_' + product_id).addClass('selected_medi');
                }
            });
        }

        function med_remove(pro_id) {
            var user_id = $('#patient-id').html();
            var session_id = $('#session-id').html();
            $.ajax({
                type: 'POST',
                url: "{{URL('/inclinic_delete_prescribe_item_from_session')}}",
                data: {
                    pro_id: pro_id,
                    type: 'medicine',
                    session_id: session_id,
                    user_id: user_id
                },
                success: function (result) {
                    $('#med_' + pro_id).removeClass('selected_medi');
                }
            });
        }
        // Lab
        function loadLabItems() {
            var name = $('#Lab_search').val();
            var user_id = $('#patient-id').html();
            var session_id = $('#session-id').html();
            $('#loadLabItems').html('');
            if (name == null || name == '') {
                name = '';
            }
            $.ajax({
                type: 'POST',
                url: "{{URL('/inclinic_new_get_lab_products_video_page')}}",
                data: {
                    name: name,
                    session_id:session_id,
                },
                success: function (response) {
                    $('#loadLabItems').html('');
                    if (response.length != 0) {
                        $.each(response, function (key, value) {
                            if (value.added == 'yes') {
                                $('#loadLabItems').append(
                                    `<div class="col-4"><button class="btn w-100 selected_medi" onclick="javascript:void(0)" title="${value.TEST_NAME}">${(value.TEST_NAME).length > 12 ? (value.TEST_NAME).substring(0,12)+"..." : value.TEST_NAME}</button></div>`
                                );
                            } else {
                                $('#loadLabItems').append(
                                    `<div class="col-4"><button class="btn w-100" id="lab_${value.TEST_CD}" onclick="add_lab(${value.TEST_CD})" title="${value.TEST_NAME}">${(value.TEST_NAME).length > 12 ? (value.TEST_NAME).substring(0,12)+"..." : value.TEST_NAME}</button></div>`
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

        function lab_remove(pro_id) {
            var user_id = $('#patient-id').html();
            var session_id = $('#session-id').html();
            $.ajax({
                type: 'POST',
                url: "{{URL('/inclinic_delete_prescribe_item_from_session')}}",
                data: {
                    pro_id: pro_id,
                    type: 'lab-test',
                    session_id: session_id,
                    user_id: user_id
                },
                success: function (result) {
                    $("#" + pro_id).removeClass('selected_medi');
                }
            });
        }

        function add_lab(product_id) {
            var user_id = $('#patient-id').html();
            var session_id = $('#session-id').html();
            $.ajax({
                type: 'POST',
                url: "{{URL('/inclinic_get_lab_details')}}",
                data: {
                    id: product_id,
                    session_id: session_id,
                    user_id: user_id,
                },
                success: function (product) {
                    $('#lab_' + product_id).addClass('selected_medi');

                }
            });
        }


        // Imaging
        function getImagingProduct(cat_id) {
            var name = '';
            var session_id = $('#session-id').html();
            $('#load_imaging_product').html('');
            $('.imaging_category').addClass('d-none');
            $('.back-button-imaging').removeClass('d-none');
            $('#load_imaging_product').removeClass('d-none');
            $.ajax({
                type: 'POST',
                url: "{{URL('/inclinic_new_get_imaging_products_by_category')}}",
                data: {
                    cat_id: cat_id,
                    name: name,
                    session_id: session_id,
                },
                success: function (response) {
                    if (response == 'notfound') {
                        $('#load_imaging_product').append(
                            `<div class="col-4"><button class="btn w-100" onclick="javascript:void(0)">No Products Found</button></div>`
                        );
                    } else {
                        if (response.length != 0) {
                            console.log(response);
                            $.each(response, function (key, value) {
                                if (value.added == 'yes') {
                                    $('#load_imaging_product').append(
                                        `<div class="col-4">
                                        <button title="${value.TEST_NAME}" class="btn w-100 selected_medi" onclick="javascript:void(0)"> ${(value.TEST_NAME).length > 12 ? (value.TEST_NAME).substring(0,12)+"..." : value.TEST_NAME} </button>
                                        </div>`
                                    );
                                } else {
                                    $('#load_imaging_product').append(
                                        `<div class="col-4">
                                        <button title="${value.TEST_NAME}" class="btn w-100" id="img_${value.TEST_CD}" onclick="add_img(${value.TEST_CD})"> ${(value.TEST_NAME).length > 12 ? (value.TEST_NAME).substring(0,12)+"..." : value.TEST_NAME} </button>
                                        </div>`
                                    );
                                }
                            });
                        } else {
                            $('#load_imaging_product').append(
                                `<div class="col-4"><button class="btn w-100" onclick="javascript:void(0)">No Products Found</button></div>`
                            );
                        }
                    }
                }
            });
        }

        $('.back-button-imaging').click(function (e) {
            e.preventDefault();
            $('.imaging_category').removeClass('d-none');
            $('.back-button-imaging').addClass('d-none');
            $('#load_imaging_product').addClass('d-none');
        });

        function add_img(product_id) {
            var user_id = $('#patient-id').html();
            var session_id = $('#session-id').html();
            $.ajax({
                type: 'POST',
                url: "{{URL('/inclinic_add_imging_pro')}}",
                data: {
                    id: product_id,
                    type: 'img',
                    session_id: session_id,
                    user_id: user_id,
                },
                success: function (product) {
                    $('#img_' + product_id).addClass('selected_medi');
                }
            });
        }

        function img_remove(pro_id) {
            var user_id = $('#patient-id').html();
            var session_id = $('#session-id').html();
            $.ajax({
                type: 'POST',
                url: "{{URL('/inclinic_delete_prescribe_item_from_session')}}",
                data: {
                    pro_id: pro_id,
                    type: 'imaging',
                    session_id: session_id,
                    user_id: user_id
                },
                success: function (result) {
                    $('#img_' + pro_id).removeClass('selected_medi');
                }
            });
        }

        //Dosage
        function dosage(a) {
            $('#days').prop('selectedIndex', 0);
            $('#units').prop('selectedIndex', 0);
            $('#dose').prop('selectedIndex', 0);
            $('#instructions').val('');
            $('#med_current_price').val('');
            $('#med_id').val('');
            var session_id = $('#session-id').html();
            med_id = a;
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
                        $('#days').val('');
                    } else {
                        $('#med_name').text(response.product.name);
                        $('#med_current_price').val(response.product.sale_price);
                        $('#units').text('');
                        $('#units').append('<option value="">Choose Unit</option>');
                        $.each(response.units, function(key, value) {
                            $('#units').append('<option value="' + value.unit + '">' + value.unit +
                                '</option>')
                        });
                        $('#days').val('');
                    }
                }
            });
            $('#med_id').val(med_id);
            $('#session_id_modal').val(session_id);
            $('#pro_id_modal').val(med_id);
            $('#type_modal').val('medicine');
            $('#dosage_modal').modal('show');

        }

        $('#add_dose_form').on('submit', function (event) {
            event.preventDefault();
            let formData = $(this).serialize(); // Get form data as a query string
            $.ajax({
                type: "post",
                url: "/inclinic_add_dosage",
                data: formData,
                success: function (response) {
                    $('#dosage_modal').modal('hide');
                }
            });
        })

        function end(){
            var session_id = $('#session-id').html();
            $.ajax({
                type: 'POST',
                url: '/inclinic_check_prescription_completed',
                data: {
                    session_id: session_id,
                },
                success: function (status) {
                    if(status == "success"){
                        $.ajax({
                            type: 'POST',
                            url: '/inclinic_doctor_end_session',
                            data: {
                                session_id: session_id,
                            },
                            beforeSend: function () {
                                $(".end-consultation").html('<i class="fas fa-spinner fa-spin"></i>');
                            },
                            success: function (status) {
                                location.reload();
                            }
                        });
                    }else{
                        alert("Add Dosage for "+status);
                    }
                }
            });
        }

</script>
@endsection
@section('content')
<section class="d-flex align-items-center justify-content-center">
    <div
        class="row px-2 w-100 d-flex flex-wrap flex-column-reverse flex-sm-row  flex-sm-nowrap waiting-room-container align-items-start justify-content-center">
        <section class="col-12 col-sm-4 d-flex flex-column bg-white px-2 rounded-3 shadow-sm">
            <div class="d-flex flex-column waiting-patients-section">
                <h4>Waiting Patients</h4>
                <div class="accordion accordion-flush waiting-patients-accordion rounded-3 d-flex flex-column gap-2 waiting-patients"
                    id="accordionFlushExample">
                    @foreach ($patients as $key => $pat)
                    <div class="accordion-item rounded-3">
                        <h2 class="accordion-header rounded-3" id="flush-heading{{ $pat->id }}">
                            <div class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapse{{ $pat->id }}" aria-expanded="false"
                                aria-controls="flush-collapse{{ $pat->id }}">
                                <div class="patient-detail d-flex gap-2 align-items-start justify-content-center">
                                    <span class="key">{{ ++$key }}</span>
                                    <h5 id="name-{{ $pat->id }}">
                                        {{ $pat->user->name . ' ' . $pat->user->last_name }}</h5>
                                    <h5 class="d-none" id="age-{{ $pat->id }}">{{$pat->user->get_age($pat->user->id)}}
                                    </h5>
                                    <h5 class="d-none" id="phone-{{ $pat->id }}">{{$pat->user->phone_number}}</h5>
                                    <h5 class="d-none" id="id-{{ $pat->id }}">{{$pat->user->id}}</h5>
                                    <h5 class="d-none" id="session_id-{{ $pat->id }}">{{$pat->id}}</h5>
                                </div>
                            </div>
                        </h2>
                        <div id="flush-collapse{{ $pat->id }}" class="accordion-collapse collapse"
                            aria-labelledby="flush-heading{{ $pat->id }}" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <p id="reason-{{ $pat->id }}">{{ $pat->reason }}</p>

                                <div class="d-flex gap-2 justify-content-center align-items-center">
                                    <button class="btn btn-outline-success w-100 start-button"
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
                    <div class="bg-white rounded-3 px-2 d-flex flex-column patient-info">
                        <h4>Current Patient</h4>
                        <div class="accordion waiting-patients-accordion rounded-3 d-flex flex-column gap-2 pe-2"
                            id="accordionFlushExample1">
                            <div class="accordion-item rounded-3 shadow-hide">
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
                                <div id="next-patient-collapse" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body d-flex flex-column px-2">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <h6 class="d-none" id="patient-id">John Doe</h6>
                                            <h6 class="d-none" id="session-id">John Doe</h6>
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
                                            <div>
                                                <label for="patient-phone">Phone:</label>
                                                <h6 id="patient-phone">+923001234567</h6>
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

                                        <div class="row">
                                            <div class="selected-value-div-wrap">
                                                <div class="d-flex align-items-center justify-content-between pt-1">
                                                    <h6>Prescribed items:</h6>
                                                    <p class="dose-para">(click on medicine to add dose)</p>
                                                </div>
                                                <div class="prescribed_items_main">
                                                    <div class="d-flex flex-wrap prescribed_items">
                                                        <span class="selected-value-bydoc ">Not Found Any Prescribed
                                                            Item!!</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="d-flex gap-2 justify-content-center align-items-center">
                                        <button class="btn btn-outline-danger w-50 rounded-3 mb-1 end-consultation" onclick="end()">End Consultation</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="prescription-section rounded-3 bg-white shadow-sm h-100">
                    <div
                        class="prescription-container bg-white rounded-3 px-2 d-flex flex-column gap-1 h-100 overflow-y-auto">
                        <h4>Prescription</h4>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="col-12 col-sm-4 nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">Medicines</button>
                                    </li>
                                    <li class="col-12 col-sm-4 nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile" type="button" role="tab"
                                    aria-controls="pills-profile" aria-selected="false" onclick="loadLabItems()">Lab tests</button>
                                    </li>
                                    <li class="col-12 col-sm-4 nav-item" role="presentation">
                                        <button class="nav-link" id="contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab"
                                    aria-controls="pills-contact" aria-selected="false">Imaging</button>
                                    </li>
                                </ul>
                        <!-- <ul class="nav nav-pills toggle-buttons d-flex gap-2 justify-content-around" id="pills-tab"
                            role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">
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
                        </ul> -->
                        {{-- <div class="search-bar-container w-100 form-control px-2 mt-1">
                            <form class="d-flex align-items-center justify-content-between">
                                <input type="search" name="search" placeholder="Search Doctor Name"
                                    class="search-field w-100" id="search" />
                                <button type="button" class="search-btn">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </div> --}}
                        <div class="tabs-medicine tab-content px-2 py-1" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab">
                                <div class="searchbar d-flex">
                        <input type="text" class="form-control custom-input" placeholder="Search for products" id="pharmacySearchText">
                        <button class="btn custom-btn searchPharmacyProduct"><i class="fa-solid fa-search"></i></button>
                    </div>
                                <div class="row gx-3 gy-2 medicine_category">
                                    @foreach ($med as $cat)
                                    <div class="col-4"><button class="btn w-100" title="{{ $cat->title }}"
                                            onclick="getMedicienByCategory('{{ $cat->id }}')">{{
                                            \Str::limit($cat->title, 12, '...') }}</button>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="d-none back-button-medicine">
                                    <i class="fa-solid fa-circle-arrow-left toggleSubCategory"></i>
                                    <h6 class="m-auto"></h6>
                                </div>
                                <div class="row gx-3 gy-2 d-none loadMedicienProduct">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab">
                                <div class="searchbar d-flex">
                        <input type="text" class="form-control custom-input" placeholder="Search for products" id="pharmacySearchText">
                        <button class="btn custom-btn searchPharmacyProduct"><i class="fa-solid fa-search"></i></button>
                    </div>
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
                                <div class="searchbar d-flex">
                        <input type="text" class="form-control custom-input" placeholder="Search for products" id="pharmacySearchText">
                        <button class="btn custom-btn searchPharmacyProduct"><i class="fa-solid fa-search"></i></button>
                    </div>
                                <div class="row gx-3 gy-2 imaging_category">
                                    @foreach ($img as $cat)
                                    <div class="col-4"><button class="btn w-100" title="{{ $cat->name }}"
                                            onclick="getImagingProduct('{{$cat->id}}')">{{
                                            \Str::limit($cat->name,12,'...') }}</button>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="d-none back-button-imaging">
                                    <i class="fa-solid fa-circle-arrow-left toggleSubCategory"></i>
                                    <h6 class="m-auto"></h6>
                                </div>
                                <div class="row gx-3 gy-2 d-none" id="load_imaging_product">
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
                    {{--<form id="add_dose_form" action="{{ url('/inclinic_add_dosage') }}" method="POST">--}}
                    <form id="add_dose_form" action="" method="POST">
                        @csrf
                        <input id="med_id" hidden="">
                        <input id="med_current_price" name="price" hidden="">
                        <input hidden="" id="session_id_modal" type="text" name="session_id" value="" />
                        <input type="hidden" id="pro_id_modal" name="pro_id" value="">
                        <input type="hidden" id="type_modal" name="type" value="">
                        <h5>Dosage</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="Expirationmonth">Choose Time For Dose</label>
                                <select id="dose" name="med_time" class="form-select" required>
                                    <option value="">Choose Time</option>
                                    <option value="4">4 Times</option>
                                    <option value="3">3 Times</option>
                                    <option value="2">2 Times</option>
                                    <option value="1">1 Times</option>
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
                                <input type="text" id="instructions" name="instructions" class="bg-light form-control"
                                    placeholder="Special Instructions" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">DONE</button>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
</div>
<!-- ------------------Dosage-Modal-end------------------ -->
