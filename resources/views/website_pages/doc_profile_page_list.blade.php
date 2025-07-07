@extends('layouts.new_pakistan_layout')

@section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
<title>Our Doctors</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
    function generateStarRatings(rating) {
    let fullStars = Math.floor(rating / 20); // Number of full stars
    let halfStar = rating % 20 >= 10 ? 1 : 0; // Check if a half-star is needed
    let emptyStars = 5 - (fullStars + halfStar); // Remaining stars will be empty

    let starHtml = '';

   if (fullStars == 0) {
    for (let i = 0; i < 5; i++) {
        starHtml += '<span class="fs-18 custom-star"><i class="fa-solid fa-star"></i></span>';
    }
   }else{
    // Append full stars
    for (let i = 0; i < fullStars; i++) {
        starHtml += '<span class="fs-18 custom-star"><i class="fa-solid fa-star"></i></span>';
    }

    // Append half star if needed
    if (halfStar) {
        starHtml += '<span class="fs-18 custom-star"><i class="fa-solid fa-star-half-alt"></i></span>';
    }

    // Append empty stars
    for (let i = 0; i < emptyStars; i++) {
        starHtml += '<span class="fs-18 custom-star"><i class="fa-regular fa-star"></i></span>';
    }
   }
    return starHtml;
}

    function safeSubstring(text, length) {
        if (text.length <= length) return text;

        // Trim the text to the desired length
        let trimmedText = text.substring(0, length);

        // Check if the next character after trimmed length is part of a word
        if (text.charAt(length) !== ' ') {
            // Find the last space within the trimmed text
            const lastSpace = trimmedText.lastIndexOf(' ');
            if (lastSpace !== -1) {
                trimmedText = trimmedText.substring(0, lastSpace);
            }
        }

        return trimmedText + ' ...';
    }

    <?php
        header('Access-Control-Allow-Origin: *');
        ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        ///////////////////////////

        function select_doc(value){
            $.ajax({
                    type: "get",
                    url: "/our-doctors/" + value,
                    beforeSend: function() {
                        $(".doctor-cont2").removeClass("d-none");
                        $(".new-doc-card-cont").addClass("d-none");
                        $(".doctor-cont2").html('<div class="d-flex flex-col align-items-center justify-content-center h-100"><i class="fa fa-spin fa-spinner fs-1"></i> </div>');
                    },
                    success: function(response) {
                        $(".doctor-cont2").html("");
                        $.each(JSON.parse(response), function (indexInArray, element) {
                            console.log(element);
                            if (element.details) {
                                $(".doctor-cont2").append(
                                    `<div class="col-md-6">
                    <div class="card border-0 rounded-4 bg-light-sky-blue">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex gap-3">
                                <div>
                                    <div class="position-relative doc-new-profile rounded-4">
                                        <img class="object-fit-cover rounded-4 w-100 h-100"
                                            src="${element.user_image}" alt="" />
                                        <span
                                            class="new-indicator ${element.status == 'online' ? 'bg-green' : 'bg-new-red' }"></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-3">
                                        <span
                                            class="${element.status == 'offline' ? 'text-new-red fw-medium' : 'text-secondary' } fw-medium fs-14">Offline</span>
                                        <span class="vertical-stick"></span>
                                        <span
                                            class="${element.status == 'online' ? 'text-green fw-medium' : 'text-secondary' } fs-14">Online</span>
                                    </div>
                                </div>
                                <div class="w-100">
                                    <div class="card-header bg-transparent border-0 w-100">
                                        <div class="w-100 d-flex align-items-center justify-content-between gap-2">
                                            <h4 class="mb-0 card-title fs-20 fw-semibold">
                                                ${element.specialization == 32? element.gender=="male"?"Mr.":"Ms.":"Dr."} ${element.name} ${element.last_name}
                                            </h4>
                                            <div
                                                class="client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                                                ${generateStarRatings(element.rating)}
                                            </div>
                                        </div>
                                        <h6 class="card-subtitle fs-14 fw-normal mt-2">
                                            ${element.specializations.name}
                                        </h6>
                                        <h6 class="fs-14 text-new-red fw-normal mt-2">
                                            ${element.zip_code == null?element.specialization!=32?"PMDC Verified":"":""}
                                        </h6>
                                        ${element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Consultation Fee:
                                            <span class="text-greenfw-semibold">Rs. ${element.consultation_fee}</span>
                                        </h6> ${element.followup_fee && element.followup_fee != element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Follow-Up Fee:
                                            <span class="text-blue fw-semibold">Rs. ${element.followup_fee}</span>
                                        </h6>` : '' : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="bg-blue text-white experience-badge">
                                    ${element.details.experience && `<p class="px-4 fw-semibold py-2">${element.details.experience} Years Experience
                                    </p>`}
                                </div>
                                <button
                                    onclick="window.location.href='/doctor-profile/${element.name}-${element.last_name}'"
                                    class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                    <span class="fs-14">View Profile</span>
                                    <span
                                        class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                            class="fs-14 fa-solid fa-arrow-right"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`
                                );
                            } else {
                                $(".doctor-cont2").append(
                                    `<div class="col-md-6">
                    <div class="card border-0 rounded-4 bg-light-sky-blue">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex gap-3">
                                <div>
                                    <div class="position-relative doc-new-profile rounded-4">
                                        <img class="object-fit-cover rounded-4 w-100 h-100"
                                            src="${element.user_image}" alt="" />
                                        <span
                                            class="new-indicator ${element.status == 'online' ? 'bg-green' : 'bg-new-red' }"></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-3">
                                        <span
                                            class="${element.status == 'offline' ? 'text-new-red fw-medium' : 'text-secondary' } fw-medium fs-14">Offline</span>
                                        <span class="vertical-stick"></span>
                                        <span
                                            class="${element.status == 'online' ? 'text-green fw-medium' : 'text-secondary' } fs-14">Online</span>
                                    </div>
                                </div>
                                <div class="w-100">
                                    <div class="card-header bg-transparent border-0 w-100">
                                        <div class="w-100 d-flex align-items-center justify-content-between gap-2">
                                            <h4 class="mb-0 card-title fs-20 fw-semibold">
                                                ${element.specialization == 32? element.gender=="male"?"Mr.":"Ms.":"Dr."} ${element.name} ${element.last_name}
                                            </h4>
                                            <div
                                                class="client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                                                ${generateStarRatings(element.rating)}
                                            </div>
                                        </div>
                                        <h6 class="card-subtitle fs-14 fw-normal mt-2">
                                            ${element.specializations.name}
                                        </h6>
                                        <h6 class="fs-14 text-new-red fw-normal mt-2">
                                            ${element.zip_code == null?element.specialization!=32?"PMDC Verified":"":""}
                                        </h6>
                                        ${element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Consultation Fee:
                                            <span class="text-greenfw-semibold">Rs. ${element.consultation_fee}</span>
                                        </h6> ${element.followup_fee && element.followup_fee != element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Follow-Up Fee:
                                            <span class="text-blue fw-semibold">Rs. ${element.followup_fee}</span>
                                        </h6>` : '' : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="bg-blue text-white experience-badge">
                                    ${element.details.experience && `<p class="px-4 fw-semibold py-2">${element.details.experience} Years Experience
                                    </p>`}
                                </div>
                                <button
                                    onclick="window.location.href='/doctor-profile/${element.name}-${element.last_name}'"
                                    class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                    <span class="fs-14">View Profile</span>
                                    <span
                                        class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                            class="fs-14 fa-solid fa-arrow-right"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`
                                );
                            }
                        });
                    }
                });

        }

        ///////////////////////////

        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.href.includes('online=1')) {
                select_doc(3);
                $('#cb-50').prop('checked', true);
            }
        });

        $("#search").keyup(function(e) {
            var name = e.target.value;
            $('#cb-47').prop('checked', true);
            if (name.length == 0) {
                $(".new-doc-card-cont").removeClass("d-none");
                $(".doctor-cont2").addClass("d-none");
            }
            else if (name.length > 2) {
                $.ajax({
                    type: "get",
                    url: "/our-doctors/" + name,
                    success: function(response) {
                        $(".doctor-cont2").removeClass("d-none");
                        $(".new-doc-card-cont").addClass("d-none");
                        $(".doctor-cont2").html("");
                        $("#select_doc").val(2);
                        $.each(JSON.parse(response), function (indexInArray, element) {
                            console.log(element)
                            if (element.details) {
                                $(".doctor-cont2").append(
                                    `<div class="col-md-6">
                    <div class="card border-0 rounded-4 bg-light-sky-blue">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex gap-3">
                                <div>
                                    <div class="position-relative doc-new-profile rounded-4">
                                        <img class="object-fit-cover rounded-4 w-100 h-100"
                                            src="${element.user_image}" alt="" />
                                        <span
                                            class="new-indicator ${element.status == 'online' ? 'bg-green' : 'bg-new-red' }"></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-3">
                                        <span
                                            class="${element.status == 'offline' ? 'text-new-red fw-medium' : 'text-secondary' } fw-medium fs-14">Offline</span>
                                        <span class="vertical-stick"></span>
                                        <span
                                            class="${element.status == 'online' ? 'text-green fw-medium' : 'text-secondary' } fs-14">Online</span>
                                    </div>
                                </div>
                                <div class="w-100">
                                    <div class="card-header bg-transparent border-0 w-100">
                                        <div class="w-100 d-flex align-items-center justify-content-between gap-2">
                                            <h4 class="mb-0 card-title fs-20 fw-semibold">
                                                ${element.specialization == 32? element.gender=="male"?"Mr.":"Ms.":"Dr."} ${element.name} ${element.last_name}
                                            </h4>
                                            <div
                                                class="client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                                                ${generateStarRatings(element.rating)}
                                            </div>
                                        </div>
                                        <h6 class="card-subtitle fs-14 fw-normal mt-2">
                                            ${element.specializations.name}
                                        </h6>
                                        <h6 class="fs-14 text-new-red fw-normal mt-2">
                                            ${element.zip_code == null?element.specialization!=32?"PMDC Verified":"":""}
                                        </h6>
                                        ${element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Consultation Fee:
                                            <span class="text-greenfw-semibold">Rs. ${element.consultation_fee}</span>
                                        </h6> ${element.followup_fee && element.followup_fee != element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Follow-Up Fee:
                                            <span class="text-blue fw-semibold">Rs. ${element.followup_fee}</span>
                                        </h6>` : '' : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="bg-blue text-white experience-badge">
                                    ${element.details.experience && `<p class="px-4 fw-semibold py-2">${element.details.experience} Years Experience
                                    </p>`}
                                </div>
                                <button
                                    onclick="window.location.href='/doctor-profile/${element.name}-${element.last_name}'"
                                    class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                    <span class="fs-14">View Profile</span>
                                    <span
                                        class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                            class="fs-14 fa-solid fa-arrow-right"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`
                                );
                            } else {
                                $(".doctor-cont2").append(
                                    `<div class="col-md-6">
                    <div class="card border-0 rounded-4 bg-light-sky-blue">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex gap-3">
                                <div>
                                    <div class="position-relative doc-new-profile rounded-4">
                                        <img class="object-fit-cover rounded-4 w-100 h-100"
                                            src="${element.user_image}" alt="" />
                                        <span
                                            class="new-indicator ${element.status == 'online' ? 'bg-green' : 'bg-new-red' }"></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-3">
                                        <span
                                            class="${element.status == 'offline' ? 'text-new-red fw-medium' : 'text-secondary' } fw-medium fs-14">Offline</span>
                                        <span class="vertical-stick"></span>
                                        <span
                                            class="${element.status == 'online' ? 'text-green fw-medium' : 'text-secondary' } fs-14">Online</span>
                                    </div>
                                </div>
                                <div class="w-100">
                                    <div class="card-header bg-transparent border-0 w-100">
                                        <div class="w-100 d-flex align-items-center justify-content-between gap-2">
                                            <h4 class="mb-0 card-title fs-20 fw-semibold">
                                                ${element.specialization == 32? element.gender=="male"?"Mr.":"Ms.":"Dr."} ${element.name} ${element.last_name}
                                            </h4>
                                            <div
                                                class="client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                                                ${generateStarRatings(element.rating)}
                                            </div>
                                        </div>
                                        <h6 class="card-subtitle fs-14 fw-normal mt-2">
                                            ${element.specializations.name}
                                        </h6>
                                        <h6 class="fs-14 text-new-red fw-normal mt-2">
                                            ${element.zip_code == null?element.specialization!=32?"PMDC Verified":"":""}
                                        </h6>
                                        ${element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Consultation Fee:
                                            <span class="text-greenfw-semibold">Rs. ${element.consultation_fee}</span>
                                        </h6> ${element.followup_fee && element.followup_fee != element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Follow-Up Fee:
                                            <span class="text-blue fw-semibold">Rs. ${element.followup_fee}</span>
                                        </h6>` : '' : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="bg-blue text-white experience-badge">
                                    ${element.details.experience && `<p class="px-4 fw-semibold py-2">${element.details.experience} Years Experience
                                    </p>`}
                                </div>
                                <button
                                    onclick="window.location.href='/doctor-profile/${element.name}-${element.last_name}'"
                                    class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                    <span class="fs-14">View Profile</span>
                                    <span
                                        class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                            class="fs-14 fa-solid fa-arrow-right"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`
                                );
                            }
                        });
                    }
                });
            }
        });

        $("#specialization").change(function(e){
            var id = e.target.value;
            $('#cb-47').prop('checked', true);
                if (id != null) {
                $.ajax({
                    type: "get",
                    url: "/filter/doctors/" + id,
                    success: function(response) {
                        $(".doctor-cont2").removeClass("d-none");
                        $(".new-doc-card-cont").addClass("d-none");
                        $(".doctor-cont2").html("");
                        $("#select_doc").val(2);
                        if (JSON.parse(response).length === 0) {
                            $(".doctor-cont2").html(`
                                <p class="text-center text-danger fw-bold">No doctors available in this specialization.</p>
                            `);
                            return;
                        }
                        $.each(JSON.parse(response), function (indexInArray, element) {
                            if (element.details) {
                                $(".doctor-cont2").append(
                                    `<div class="col-md-6">
                    <div class="card border-0 rounded-4 bg-light-sky-blue">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex gap-3">
                                <div>
                                    <div class="position-relative doc-new-profile rounded-4">
                                        <img class="object-fit-cover rounded-4 w-100 h-100"
                                            src="${element.user_image}" alt="" />
                                        <span
                                            class="new-indicator ${element.status == 'online' ? 'bg-green' : 'bg-new-red' }"></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-3">
                                        <span
                                            class="${element.status == 'offline' ? 'text-new-red fw-medium' : 'text-secondary' } fw-medium fs-14">Offline</span>
                                        <span class="vertical-stick"></span>
                                        <span
                                            class="${element.status == 'online' ? 'text-green fw-medium' : 'text-secondary' } fs-14">Online</span>
                                    </div>
                                </div>
                                <div class="w-100">
                                    <div class="card-header bg-transparent border-0 w-100">
                                        <div class="w-100 d-flex align-items-center justify-content-between gap-2">
                                            <h4 class="mb-0 card-title fs-20 fw-semibold">
                                                ${element.specialization == 32? element.gender=="male"?"Mr.":"Ms.":"Dr."} ${element.name} ${element.last_name}
                                            </h4>
                                            <div
                                                class="client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                                                ${generateStarRatings(element.rating)}
                                            </div>
                                        </div>
                                        <h6 class="card-subtitle fs-14 fw-normal mt-2">
                                            ${element.specializations.name}
                                        </h6>
                                        <h6 class="fs-14 text-new-red fw-normal mt-2">
                                            ${element.zip_code == null?element.specialization!=32?"PMDC Verified":"":""}
                                        </h6>
                                        ${element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Consultation Fee:
                                            <span class="text-greenfw-semibold">Rs. ${element.consultation_fee}</span>
                                        </h6> ${element.followup_fee && element.followup_fee != element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Follow-Up Fee:
                                            <span class="text-blue fw-semibold">Rs. ${element.followup_fee}</span>
                                        </h6>` : '' : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="bg-blue text-white experience-badge">
                                    ${element.details.experience && `<p class="px-4 fw-semibold py-2">${element.details.experience} Years Experience
                                    </p>`}
                                </div>
                                <button
                                    onclick="window.location.href='/doctor-profile/${element.name}-${element.last_name}'"
                                    class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                    <span class="fs-14">View Profile</span>
                                    <span
                                        class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                            class="fs-14 fa-solid fa-arrow-right"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`
                                );
                            } else {
                                $(".doctor-cont2").append(
                                    `<div class="col-md-6">
                    <div class="card border-0 rounded-4 bg-light-sky-blue">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex gap-3">
                                <div>
                                    <div class="position-relative doc-new-profile rounded-4">
                                        <img class="object-fit-cover rounded-4 w-100 h-100"
                                            src="${element.user_image}" alt="" />
                                        <span
                                            class="new-indicator ${element.status == 'online' ? 'bg-green' : 'bg-new-red' }"></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-3">
                                        <span
                                            class="${element.status == 'offline' ? 'text-new-red fw-medium' : 'text-secondary' } fw-medium fs-14">Offline</span>
                                        <span class="vertical-stick"></span>
                                        <span
                                            class="${element.status == 'online' ? 'text-green fw-medium' : 'text-secondary' } fs-14">Online</span>
                                    </div>
                                </div>
                                <div class="w-100">
                                    <div class="card-header bg-transparent border-0 w-100">
                                        <div class="w-100 d-flex align-items-center justify-content-between gap-2">
                                            <h4 class="mb-0 card-title fs-20 fw-semibold">
                                                ${element.specialization == 32? element.gender=="male"?"Mr.":"Ms.":"Dr."} ${element.name} ${element.last_name}
                                            </h4>
                                            <div
                                                class="client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                                                ${generateStarRatings(element.rating)}
                                            </div>
                                        </div>
                                        <h6 class="card-subtitle fs-14 fw-normal mt-2">
                                            ${element.specializations.name}
                                        </h6>
                                        <h6 class="fs-14 text-new-red fw-normal mt-2">
                                            ${element.zip_code == null?element.specialization!=32?"PMDC Verified":"":""}
                                        </h6>
                                        ${element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Consultation Fee:
                                            <span class="text-greenfw-semibold">Rs. ${element.consultation_fee}</span>
                                        </h6> ${element.followup_fee && element.followup_fee != element.consultation_fee ? `<h6 class="fs-14 fw-normal mt-2">
                                            Follow-Up Fee:
                                            <span class="text-blue fw-semibold">Rs. ${element.followup_fee}</span>
                                        </h6>` : '' : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="bg-blue text-white experience-badge">
                                    ${element.details.experience && `<p class="px-4 fw-semibold py-2">${element.details.experience} Years Experience
                                    </p>`}
                                </div>
                                <button
                                    onclick="window.location.href='/doctor-profile/${element.name}-${element.last_name}'"
                                    class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                    <span class="fs-14">View Profile</span>
                                    <span
                                        class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                            class="fs-14 fa-solid fa-arrow-right"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`
                                );
                            }
                        });
                    }
                });
            }
        })
</script>
@endsection

@section('content')
<main class="our-doctors-page shops-page">
    <section class="new-header w-85 mx-auto rounded-3">
        <div class="new-header-inner p-5">
            <h1 class="fs-40 fw-semibold">Our Doctors</h1>
            <div>
                <a class="fs-12" href="{{ url('/') }}">Home</a>
                <span class="mx-1 align-middle">></span>
                <a class="fs-12" href="{{ route('doc_profile_page_list') }}">Our Doctors</a>
            </div>
        </div>
    </section>
    <section class="page-para my-5 px-5 w-85 mx-auto text-center">
        <h2 class="fs-30 fw-semibold text-center mb-2">
            Our Doctors
        </h2>
        @php
        $page = DB::table('pages')->where('url', '/our-doctor')->first();
        $section = DB::table('section')
        ->where('page_id', $page->id)
        ->where('section_name', 'upper-text')
        ->where('sequence_no', '1')
        ->first();
        $top_content = DB::table('content')
        ->where('section_id', $section->id)
        ->first();
        @endphp
        @if ($top_content)
        {!! $top_content->content !!}
        @else
        <p class="fs-14 text-center px-2">
            Best Pakistani doctors available online, with American doctors
            accessible on demand.
        </p>
        @endif
    </section>
    <section class="medicine-card-section">
        <div class="container-fluid px-0">
            <div class="row gx-4 mx-auto w-85">
                <div class="col-12 bg-white d-flex justify-content-between mb-3 align-items-center">
                    <div class="col-md-8 d-flex align-items-center gap-2">
                        <div onclick="select_doc(2)" class="checkbox-wrapper-new">
                            <input checked type="radio" name="cb" id="cb-47" value="2" />
                            <label for="cb-47">All Doctors</label>
                        </div>
                        <div onclick="select_doc(0)" class="checkbox-wrapper-new">
                            <input type="radio" name="cb" id="cb-48" value="0" />
                            <label for="cb-48">Pakistani Doctors</label>
                        </div>
                        <div onclick="select_doc(1)" class="checkbox-wrapper-new">
                            <input type="radio" name="cb" id="cb-49" value="1" />
                            <label for="cb-49">American Doctors</label>
                        </div>
                        <div onclick="select_doc(3)" class="checkbox-wrapper-new">
                            <input type="radio" name="cb" id="cb-50" value="3" />
                            <label for="cb-50">Online Doctors</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div
                            class="search-container d-flex align-items-center justify-content-center rounded-3 position-relative">
                            <input class="search-bar px-3 py-2" type="search" name="search"
                                placeholder="Search for Doctor" id="search" />
                            <button type="button" class="px-3 py-2 search-icon"><i
                                    class="fa-solid fa-magnifying-glass"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="col-md-4 ms-auto mt-0">
                        <select class="text-secondary form-select border-blue-2 py-2 rounded-3" id="specialization"
                            name="specialization">
                            <option value="0">All Specializations</option>
                            @foreach ($specializations as $specialization)
                            <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mt-2 g-3 mx-auto new-doc-card-cont w-85 g-3">
                @foreach ($doctors as $doctor)
                <div class="col-md-6">
                    <div class="card border-0 rounded-4 bg-light-sky-blue">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex gap-3">
                                <div>
                                    <div class="position-relative doc-new-profile rounded-4">
                                        <img class="object-fit-cover rounded-4 w-100 h-100"
                                            src="{{ $doctor->user_image }}" alt="" />
                                        <span
                                            class="new-indicator {{ $doctor->status == 'online' ? 'bg-green' : 'bg-new-red' }}"></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-3">
                                        <span
                                            class="{{ $doctor->status == 'offline' ? 'text-new-red fw-medium' : 'text-secondary' }} fw-medium fs-14">Offline</span>
                                        <span class="vertical-stick"></span>
                                        <span
                                            class="{{ $doctor->status == 'online' ? 'text-green fw-medium' : 'text-secondary' }} fs-14">Online</span>
                                    </div>
                                </div>
                                <div class="w-100">
                                    <div class="card-header bg-transparent border-0 w-100">
                                        <div class="w-100 d-flex align-items-center justify-content-between gap-2">
                                            @if ($doctor->specialization != '32' && $doctor->zip_code == null)
                                            <h4 class="mb-0 card-title fs-20 fw-semibold">
                                                Dr. {{ \Str::ucfirst($doctor->name) . ' ' .
                                                \Str::ucfirst($doctor->last_name) }}
                                            </h4>
                                            @else
                                            <h4 class="mb-0 card-title fs-20 fw-semibold">
                                                {{$doctor->gender == "male"? "Mr.":"Ms."}}
                                                {{ \Str::ucfirst($doctor->name) . ' ' .
                                                \Str::ucfirst($doctor->last_name) }}
                                            </h4>
                                            @endif
                                            <div
                                                class="client-rating gap-small text-gold fs-6 mt-0 d-flex align-items-center">
                                                @if ($doctor->rating != null)
                                                @php
                                                $fullStars = floor($doctor->rating / 20);
                                                $halfStar = $doctor->rating % 20 >= 10 ? 1 : 0;
                                                $emptyStars = 5 - ($fullStars + $halfStar);
                                                @endphp
                                                @for ($i = 0; $i < $fullStars; $i++) <span class="fs-18 custom-star"><i
                                                        class="fa-solid fa-star"></i></span>
                                                    @endfor
                                                    @if ($halfStar)
                                                    <span class="fs-18 custom-star"><i
                                                            class="fa-solid fa-star-half-alt"></i></span>
                                                    @endif
                                                    @for ($i = 0; $i < $emptyStars; $i++) <span
                                                        class="fs-18 custom-star"><i
                                                            class="fa-regular fa-star"></i></span>
                                                    @endfor
                                                    @else
                                                        <span class="fs-18 custom-star"><i
                                                                class="fa-solid fa-star"></i></span>
                                                        <span class="fs-18 custom-star"><i
                                                                class="fa-solid fa-star"></i></span>
                                                        <span class="fs-18 custom-star"><i
                                                                class="fa-solid fa-star"></i></span>
                                                        <span class="fs-18 custom-star"><i
                                                                class="fa-solid fa-star"></i></span>
                                                        <span class="fs-18 custom-star"><i
                                                                class="fa-solid fa-star"></i></span>
                                                        <span class="fs-14 text-black ms-2">(17)</span>
                                                    @endif
                                            </div>
                                        </div>
                                        <h6 class="card-subtitle fs-14 fw-normal mt-2">
                                            {{ $doctor->specializations->name }}
                                        </h6>
                                        @if ($doctor->zip_code == null)
                                        <h6 class="fs-14 text-new-red fw-normal mt-2">
                                            PMDC Verified
                                        </h6>
                                        @endif
                                        @if(isset($doctor->consultation_fee))
                                        <h6 class="fs-14 fw-normal mt-2">
                                            Consultation Fee:
                                            <span class="text-greenfw-semibold">Rs. {{$doctor->consultation_fee}}</span>
                                        </h6>
                                        @if (isset($doctor->followup_fee) && $doctor->consultation_fee !==
                                        $doctor->followup_fee)
                                        <h6 class="fs-14 fw-normal mt-2">
                                            Follow-Up Fee:
                                            <span class="text-blue fw-semibold">Rs. {{$doctor->followup_fee}}</span>
                                        </h6>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="bg-blue text-white experience-badge">
                                    @if ($doctor->details)
                                    <p class="px-4 fw-semibold py-2">{{ $doctor->details->experience }} Years Experience
                                    </p>
                                    @endif
                                </div>
                                <button
                                    onclick="window.location.href='/doctor-profile/{{ $doctor->name }}-{{ $doctor->last_name }}'"
                                    class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                    <span class="fs-14">View Profile</span>
                                    <span
                                        class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                            class="fs-14 fa-solid fa-arrow-right"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row mt-2 g-3 mx-auto doctor-cont2 w-85 g-3">
            </div>
            <div class="d-flex align-items-center mt-3 justify-content-center">
                {{ $doctors->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>
</main>
@endsection