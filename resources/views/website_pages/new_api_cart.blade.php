@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="language" content="en-us">
    <meta name="robots" content="index,follow" />
    <meta name="copyright" content="© 2022 All Rights Reserved. Powered By Community Healthcare Clinics">
    <meta name="url" content="https://www.communityhealthcareclinics.com">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.umbrellamd.com" />
    <meta property="og:site_name" content="Community Healthcare Clinics | communityhealthcareclinics.com" />
    <meta name="twitter:site" content="@umbrellamd">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="author" content="Umbrellamd">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    <title>Cart | Community Healthcare Clinics</title>
    <style>
        .payment-method {
            cursor: pointer;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            transition: 0.3s;
        }

        .payment-method:hover,
        .payment-method.active {
            border-color: #007bff;
            background-color: #f8f9fa;
        }

        .payment-method h5 {
            font-size: 16px;
            font-weight: 700;
        }

        .icon {
            height: 35px;
            object-fit: cover;
        }

        .process-pay {
            background: linear-gradient(to top, #08295a, #165dc8);
            /* background-image: linear-gradient(#2c66bb, #08295a); */
            color: #fff !important;
            border: none;
        }

        .process-pay:hover {
            background-image: linear-gradient(#568fe6, #051b3b);
            color: #fff;
        }

        .buttonload {

            padding: 12px 16px;
            /* Some padding */

        }

        .w-100 {
            width: 100% !important;
            height: auto !important;
            object-fit: none !important;
        }

        .icon {
            width: auto !important;
            filter: none !important;
        }

        /* .left {
                box-shadow: none;
                padding: 20px;
                width: 100%;
                text-align: justify;
            } */
    </style>
@endsection

@section('top_import_file')
    <link rel="stylesheet" href="{{ asset('assets/css/minifyStyle.css?n=1') }}" />
@endsection


@section('bottom_import_file')
    <script src="https://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>
<script>
    // CSRF Token Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Global Variables
    let current_fs, next_fs, previous_fs;
    let left, opacity, scale;
    let animating = false;
    let check = 0;

    // Utility Functions
    function addHyphen(element) {
        let ele = document.getElementById(element.id);
        if (!ele) return;

        let value = ele.value.replace(/-/g, ''); // Remove existing hyphens
        if (value.length <= 16) {
            let finalVal = value.match(/.{1,4}/g)?.join('-') || value;
            ele.value = finalVal;
        }
    }

    function updateCheckoutTotals(res) {
        $('#totalItem').text(res.countItem || 0);
        $('#totalCast').text('Rs. ' + (res.itemSum || 0));
        $('#totalPaid').text('Rs. ' + (res.totalPrice || 0));
        $('.payAble').val(res.totalPrice || 0);
    }

    function loadFinalCheckoutItems() {
        $('#loadItemChecoutFinal').empty();

        $.ajax({
            type: 'POST',
            url: window.routes?.show_product_on_final_checkout || '/show-product-on-final-checkout',
            success: function(res) {
                $('#totalCastFinal').text('Rs.' + (res.itemSum || 0));
                $('#totalPaidFinal').text('Rs.' + (res.totalPrice || 0));

                let showShipping = 0;

                if (res.allProducts && Array.isArray(res.allProducts)) {
                    res.allProducts.forEach(function(product) {
                        if (product.product_mode === "medicine") {
                            showShipping++;
                        }

                        const itemHtml = createProductItemHtml(product);
                        $('#loadItemChecoutFinal').append(itemHtml);
                    });
                }

                if (showShipping > 0) {
                    $('.payment_toggole_form').show();
                } else {
                    $('.payment_toggole_form').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading checkout items:', error);
            }
        });
    }

    function createProductItemHtml(product) {
        const prescribedText = product.item_type === 'prescribed'
            ? `<p class="card-text">Prescribed by ${product.prescribed || 'N/A'}</p>`
            : '<p class="card-text">Counter Purchased</p>';

        return `
            <div class="card mb-1">
                <div class="row g-0">
                    <div class="d-flex">
                        <div class="image-wrap-inner">
                            <img src="${product.product_image || ''}"
                                 class="img-fluid rounded-start"
                                 alt="${product.name || ''}" />
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${product.name || 'Unknown Product'}</h5>
                            <h5 class="checkoutItem_prod" id="${product.product_id || ''}" hidden>${product.product_id || ''}</h5>
                            <h5 class="checkoutItem_cart" id="${product.id || ''}" hidden>${product.id || ''}</h5>
                            <p class="card-text">Qty:1 <span class="float-end pe-3"><b>Price: Rs.${product.update_price || 0}</b></span></p>
                            ${prescribedText}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function detectCardType(cardNumber) {
        const firstDigit = cardNumber.charAt(0);
        const cardImages = {
            '3': 'american-express.png',
            '4': 'visa.png',
            '5': 'master.png',
            '6': 'discover.png'
        };

        if (cardImages[firstDigit]) {
            const imagePath = window.assetPath
                ? `${window.assetPath}/assets/images/${cardImages[firstDigit]}`
                : `/assets/images/${cardImages[firstDigit]}`;

            $(".card-pic").html(`<img src="${imagePath}" class="pay-image w-100" alt="Card Type">`);
        } else {
            $(".card-pic").empty();
        }
    }

    function populateCardDetails(response) {
        if (!response.billing) return;

        const billing = response.billing;
        const fields = {
            'billing_name': billing.name,
            'billing_last_name': billing.last_name,
            'billing_email': billing.email,
            'billing_card_number': billing.number,
            'billing_month': billing.expiration_month,
            'billing_year': billing.expiration_year,
            'billing_csc': billing.csc,
            'billing_address': billing.street_address,
            'billing_city': billing.city,
            'billing_state': billing.state,
            'billing_zip': billing.zip,
            'billing_phone': billing.phoneNumber
        };

        Object.entries(fields).forEach(([fieldId, value]) => {
            const field = document.getElementById(fieldId);
            if (field && value !== undefined) {
                field.value = value;
            }
        });
    }

    function populateLocationFromZip(zipCode, stateField, cityField) {
        if (zipCode.length < 5) return;

        $(stateField).val('');
        $(cityField).val('');

        $.ajax({
            type: "POST",
            url: "/get_states_cities",
            data: { zip: zipCode },
            success: function(data) {
                if (data && data.abbreviation && data.city) {
                    $(stateField).val(data.abbreviation);
                    $(cityField).val(data.city);

                    if (stateField === '#state_code' && cityField === '#city') {
                        $(stateField).attr('readonly', true);
                        $(cityField).attr('readonly', true);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching location data:', error);
            }
        });
    }

    // Event Handlers
    $(document).ready(function() {
        // Initial setup
        const cardCount = window.cardCount || 0;

        if (cardCount === 0) {
            $("#div1").show();
            $('#div2').hide();
        }

        // Check initial checkbox states
        check = $('input:checked').length;
        $('.next').prop('disabled', check === 0);

        // Card number input handler
        $("#card_num").on('input', function() {
            detectCardType($(this).val());
        });

        // Zip code handlers
        $("#zipcode").on('input', function() {
            populateLocationFromZip($(this).val(), '#state_code', '#city');
        });

        $("#zip_code").on('input', function() {
            populateLocationFromZip($(this).val(), '#ship_state_code', '#ship_city');
        });

        $("#shipping_customer_zip").on('input', function() {
            populateLocationFromZip($(this).val(), '#shipping_customer_state', '#shipping_customer_city');
        });

        // Payment method selection
        $(".payment-method").on('click', function() {
            $(".payment-method").removeClass("active");
            $(this).addClass("active");

            const selectedMethod = $(this).data("method");
            $("#final-pay-button").prop("disabled", false);
            $("#payment_method").val(selectedMethod);
        });

        // Disable browser back button
        function disableBack() {
            window.history.forward();
        }
        window.onload = disableBack;
        window.onpageshow = function(e) {
            if (e.persisted) disableBack();
        };
    });

    // Navigation Functions
    $(".next").on('click', function() {
        if (animating) return false;

        loadFinalCheckoutItems();
        checkCouponStatus();

        animating = true;
        current_fs = $(this).closest('fieldset');
        next_fs = current_fs.next('fieldset');

        // Activate next step on progress bar
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

        // Animate transition
        next_fs.show();
        current_fs.animate({ opacity: 0 }, {
            step: function(now) {
                scale = 1 - (1 - now) * 0.2;
                left = now * 50 + "%";
                opacity = 1 - now;

                current_fs.css({ transform: "scale(" + scale + ")" });
                next_fs.css({ left: left, opacity: opacity });
            },
            duration: 800,
            complete: function() {
                current_fs.hide();
                animating = false;
            },
            easing: "easeInOutBack"
        });
    });

    $(".previous").on('click', function() {
        if (animating) return false;

        animating = true;
        current_fs = $(this).parent();
        previous_fs = current_fs.prev();

        $('.payment-order-summary-wrap').css({ display: 'block', opacity: '1' });
        $("#progressbar li").removeClass("active").first().addClass("active");

        previous_fs.show();
        current_fs.animate({ opacity: 0 }, {
            step: function(now) {
                scale = 0.8 + (1 - now) * 0.2;
                left = (1 - now) * 50 + "%";
                opacity = 1 - now;

                current_fs.css({ left: left });
                previous_fs.css({ transform: "scale(" + scale + ")", opacity: opacity });
            },
            duration: 800,
            complete: function() {
                current_fs.hide();
                animating = false;
            },
            easing: "easeInOutBack"
        });
    });

    // Checkbox Functions
    function checkboxFunction(element) {
        const classes = $(element).closest('.item-container').attr('class');
        if (!classes) return;

        const counter = classes.split('_')[1];
        const isChecked = $('#' + classes).is(':checked');
        const cartItemId = $('#cartitemid_' + counter).val();

        if (isChecked) {
            check++;
            $('.next').prop('disabled', false);
            $('.heading_' + counter).css('color', 'white');
            $('.price_' + counter).css('color', 'white');
            $('.' + classes).css('background-color', '#08295a');

            updateProductCheckout(cartItemId, true);
        } else {
            check--;
            if (check === 0) {
                $('.next').prop('disabled', true);
            }

            $('.heading_' + counter).css('color', '#333');
            $('.price_' + counter).css('color', '#333');
            $('.' + classes).css('background-color', 'white');

            updateProductCheckout(cartItemId, false);
        }
    }

    function updateProductCheckout(itemId, isAdding) {
        const url = isAdding
            ? window.routes?.show_product_on_checkout || '/show-product-on-checkout'
            : window.routes?.remove_product_on_checkout || '/remove-product-on-checkout';

        $.ajax({
            type: 'POST',
            url: url,
            data: { item_id: itemId },
            success: updateCheckoutTotals,
            error: function(xhr, status, error) {
                console.error('Error updating checkout:', error);
            }
        });
    }

    // Card Management Functions
    function divClick(element) {
        const value = $(element).data('card');
        const radioBtn = $(element).find('input[type="radio"]');
        radioBtn.prop('checked', true);

        handleCardSelection(value);
    }

    function radioClick(element) {
        const value = $(element).attr('class');
        handleCardSelection(value);
    }

    function handleCardSelection(value) {
        if (value == 0) {
            $('#div2').hide();
            $("#div1").show();
        } else {
            $('#div1').hide();
            $('#div2').show();
            $("#card_no").attr("value", value);

            $.ajax({
                type: 'POST',
                url: '/get_card_details',
                data: { id: value },
                success: populateCardDetails,
                error: function(xhr, status, error) {
                    console.error('Error fetching card details:', error);
                }
            });
        }
    }

    function addCard() {
        $("input[name='card']").each(function() {
            if ($(this).val() !== "1") {
                $(this).prop("checked", false);
            }
        });

        document.getElementById("cardNo").value = 0;
        $('#div2').hide();
        $("#div1").show();
    }

    // Form Submission Handlers
    $('#formWithCard, #formWithCard1').on('submit', function() {
        $('#final-pay-button, #final-pay-button1').prop('disabled', true);
        $('button').prop('disabled', true);
        $('#slider_round').prop('disabled', true);

        const element = $(".pay");
        element.addClass("buttonload");
        element.html('<i class="fa fa-spinner fa-spin"></i>Processing...');
    });

    // Coupon Functions
    function checkCouponStatus() {
        $.ajax({
            type: "GET",
            url: "/admin/coupon/check",
            success: function(response) {
                if (response === true) {
                    $('#promo_added').show();
                    $('#coupon_code').prop("disabled", true);
                    $('.coupon_apply').prop("disabled", true);
                } else {
                    $('#promo_added').hide();
                    $('#coupon_code').prop("disabled", false);
                    $('.coupon_apply').prop("disabled", false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking coupon status:', error);
            }
        });
    }

    $('.coupon_apply').on('click', function() {
        const code = $('#coupon_code').val();
        if (!code.trim()) return;

        const prodIds = Array.from(document.getElementsByClassName("checkoutItem_prod"))
            .map(el => el.id).join(',');
        const cartIds = Array.from(document.getElementsByClassName("checkoutItem_cart"))
            .map(el => el.id).join(',');

        $.ajax({
            type: "POST",
            url: "/coupon/apply/discount",
            data: {
                code: code,
                prod_id: prodIds,
                cart_id: cartIds
            },
            success: function(response) {
                // Hide all messages first
                $('#promo_success, #promo_already_used, #promo_danger').hide();

                if (response === 'true') {
                    $('#coupon_code').prop('disabled', true);
                    $('#promo_success').show();
                    loadFinalCheckoutItems(); // Refresh items with discount
                } else if (response === 'false') {
                    $('#promo_already_used').show();
                } else if (response === 'Date Expired!!') {
                    $('#promo_danger').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error applying coupon:', error);
            }
        });
    });

    // Make functions globally available
    window.checkboxFunction = checkboxFunction;
    window.divClick = divClick;
    window.radioClick = radioClick;
    window.addCard = addCard;
    window.addHyphen = addHyphen;
</script>
@endsection

@section('content')
    {{-- after registration and login modal --}}

    <div class="container-fluid pt-1">
        <div>
            @if (session()->get('msg'))
                <div id="errorDiv1" class="alert alert-danger col-12 col-md-6 offset-md-3 mt-2">
                    @php
                        $es = session()->get('msg');
                    @endphp
                    <span role="alert"> <strong>{{ $es }}</strong></span>

                </div>
            @endif
            @include('flash::message')
        </div>
        @if (count($user_cart_items) != 0)
            <div class="left checkout-steps-wrap">
                <div id="checkoutform">
                    <ul id="progressbar">
                        <li class="active">Checkout</li>
                        <li>Payment Details</li>
                    </ul>

                    <!-- ******* CHECKOUT-SEC STATRS ******** -->
                    <fieldset class="mb-3">
                        <section>
                            <div class="container-fluid px-3">
                                <div class="row" style="--bs-gutter-x: 0.5rem;">
                                    <div class="col-md-8 shopping-cart-all-wrap">
                                        <div class="cart">
                                            <div class="card">
                                                <div class="row">
                                                    <div class="title">
                                                        <div class="">
                                                            <h4><b>Shopping Cart</b></h4>
                                                        </div>
                                                    </div>
                                                    <div class="text-center py-2">
                                                        <h6>Please select items before checkout</h4>
                                                    </div>
                                                </div>
                                                <ul id="myList">
                                                    @php
                                                        $counter = 0;
                                                    @endphp
                                                    @forelse ($user_cart_items as $item)
                                                        @if ($item->show_product == 1)
                                                            <li class="item_{{ $counter }}"
                                                                style="background-color:#08295a;">
                                                                <input type="hidden" id="cartitemid_{{ $counter }}"
                                                                    value="{{ $item->id }}">
                                                                <div class="row">
                                                                    <div class="row main align-items-center"
                                                                        style="position: relative">
                                                                        <div class="col-2 cart-img-div">
                                                                            @if (
                                                                                $item->product_image != 'dummy_medicine.png' &&
                                                                                    $item->product_image != 'default-labtest.jpg' &&
                                                                                    $item->product_image != 'default-imaging.png')
                                                                                <img class="img-fluid"
                                                                                    alt="{{ $item->name }}"
                                                                                    src="{{ \App\Helper::check_bucket_files_url($item->product_image) }}" />
                                                                            @else
                                                                                <img class="img-fluid"
                                                                                    alt="{{ $item->name }}"
                                                                                    src="{{ asset('assets/images/' . $item->product_image) }}" />
                                                                            @endif
                                                                            @if ($item->product_mode == 'medicine')
                                                                                <h6 class="item-tag-name tag-name-pharmacy">
                                                                                    Pharmacy</h6>
                                                                            @elseif($item->product_mode == 'lab-test')
                                                                                <h6 class="item-tag-name tag-name-lab">Lab
                                                                                    Test</h6>
                                                                            @elseif($item->product_mode == 'imaging')
                                                                                <h6 class="item-tag-name tag-name-imaging">
                                                                                    Imaging</h6>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col">
                                                                            <div class="row med-name heading_{{ $counter }}"
                                                                                style="color:white;">{{ $item->name }}
                                                                            </div>
                                                                            @if ($item->item_type == 'prescribed')
                                                                                @if (!empty($item->medicine_usage) && $item->product_mode != 'lab-test')
                                                                                    <div class="row text-prescribed">
                                                                                        {{ $item->medicine_usage }}
                                                                                    </div>
                                                                                @endif
                                                                                @isset($item->prescribed_by)
                                                                                    <div class="row text-prescribed">
                                                                                        Prescribed:
                                                                                        {{ $item->prescribed_by }} -
                                                                                        {{ $item->prescription_date }}</div>
                                                                                @endisset
                                                                            @endif
                                                                            {{-- @if ($item->product_mode == 'lab-test' && $item->item_type == 'counter')
                                                                                <div class="row text-prescribed"> +$6.00
                                                                                    Provider's Fee
                                                                                </div>
                                                                            @endif --}}
                                                                        </div>
                                                                        <div class="col text-center">
                                                                            @if ($item->item_type == 'prescribed')
                                                                                <span
                                                                                    class="tag-prescribed">Prescribed</span>
                                                                            @else
                                                                                <div class="text-center">
                                                                                    <span
                                                                                        class="tag-prescribed">Online</span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col med-price price_{{ $counter }}"
                                                                            style="color: white;">
                                                                            Rs.{{ number_format($item->price, 2) }}
                                                                            <span class="close">
                                                                                <input type="checkbox" checked
                                                                                    id="item_{{ $counter }}"
                                                                                    value="0"
                                                                                    onclick="checkboxFunction(this)">
                                                                                @if ($item->item_type == 'counter')
                                                                                    <a
                                                                                        href="{{ route('remove_item_from_cart', ['id' => $item->id]) }}"><i
                                                                                            class="fa-solid fa-trash-can text-danger"></i></a>
                                                                                    <!-- <a href="#" class="cross quick-fix remove_cart_items" product_id={{ $item->product_id }} cart_row_id={{ $item->cart_row_id }} style="color:grey"><i class="fa fa-trash"></i> Remove</a> -->
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @else
                                                            <li class="item_{{ $counter }}">
                                                                <input type="hidden" id="cartitemid_{{ $counter }}"
                                                                    value="{{ $item->id }}">
                                                                <div class="row">
                                                                    <div class="row main align-items-center"
                                                                        style="position: relative">
                                                                        <div class="col-2 cart-img-div">
                                                                            @if (
                                                                                $item->product_image != 'dummy_medicine.png' &&
                                                                                    $item->product_image != 'default-labtest.jpg' &&
                                                                                    $item->product_image != 'default-imaging.png')
                                                                                <img class="img-fluid"
                                                                                    alt="{{ $item->name }}"
                                                                                    src="{{ \App\Helper::check_bucket_files_url($item->product_image) }}" />
                                                                            @else
                                                                                <img class="img-fluid"
                                                                                    alt="{{ $item->name }}"
                                                                                    src="{{ asset('assets/images/' . $item->product_image) }}" />
                                                                            @endif
                                                                            @if ($item->product_mode == 'medicine')
                                                                                <h6
                                                                                    class="item-tag-name tag-name-pharmacy">
                                                                                    Pharmacy</h6>
                                                                            @elseif($item->product_mode == 'lab-test')
                                                                                <h6 class="item-tag-name tag-name-lab">Lab
                                                                                    Test</h6>
                                                                            @elseif($item->product_mode == 'imaging')
                                                                                <h6 class="item-tag-name tag-name-imaging">
                                                                                    Imaging</h6>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col">
                                                                            <div class="row heading_{{ $counter }}">
                                                                                {{ $item->name }}
                                                                            </div>
                                                                            @if ($item->item_type == 'prescribed')
                                                                                @if (!empty($item->medicine_usage) && $item->product_mode != 'lab-test')
                                                                                    <div class="row text-prescribed">
                                                                                        {{ $item->medicine_usage }}</div>
                                                                                @endif
                                                                                @isset($item->prescribed_by)
                                                                                    <div class="row text-prescribed">
                                                                                        Prescribed:
                                                                                        {{ $item->prescribed_by }} -
                                                                                        {{ $item->prescription_date }}</div>
                                                                                @endisset
                                                                            @endif
                                                                            {{-- @if ($item->product_mode == 'lab-test' && $item->item_type == 'counter')
                                                                                <div class="row text-prescribed"> +$6.00
                                                                                    Provider's Fee
                                                                                </div>
                                                                            @endif --}}
                                                                        </div>
                                                                        <div class="col text-center">
                                                                            @if ($item->item_type == 'prescribed')
                                                                                <span
                                                                                    class="tag-prescribed">Prescribed</span>
                                                                            @else
                                                                                <div class="text-center">
                                                                                    <span
                                                                                        class="tag-prescribed">Online</span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col price_{{ $counter }}">
                                                                            Rs.{{ number_format($item->price, 2) }}
                                                                            <span class="close">
                                                                                <input type="checkbox"
                                                                                    id="item_{{ $counter }}"
                                                                                    value="0"
                                                                                    onclick="checkboxFunction(this)">
                                                                                @if ($item->item_type == 'counter')
                                                                                    <a
                                                                                        href="{{ route('remove_item_from_cart', ['id' => $item->id]) }}"><i
                                                                                            class="fa-solid fa-trash-can text-danger"></i></a>
                                                                                    <!-- <a href="#" class="cross quick-fix remove_cart_items" product_id={{ $item->product_id }} cart_row_id={{ $item->cart_row_id }} style="color:grey"><i class="fa fa-trash"></i> Remove</a> -->
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endif

                                                        @php
                                                            $counter++;
                                                        @endphp
                                                    @empty
                                                        <li class="font-weight-bold no_item"> No Items Added </li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="payment-order-summary-wrap">
                                                <div class="card">
                                                    <div class="card-header">Order Summary</div>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item">Total Item <span
                                                                id="totalItem">{{ $countItem }}</span></li>
                                                        <li class="list-group-item">Total Cost <span
                                                                id="totalCast">Rs.{{ number_format($itemSum, 2) }}</span>
                                                        </li>
                                                        <li class="list-group-item">To be Paid <span
                                                                id="totalPaid">Rs.{{ number_format($totalPrice, 2) }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <input type="button" name="next" class="next action-button m-0 mt-2"
                                                    value="PROCEED TO CHECKOUT" />
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </fieldset>
                    <!-- ******* CHECKOUT-SEC ENDS ******** -->

                    <!-- ******* PAYMENT-SEC STATRS ******** -->
                    <fieldset class="mb-3">
                        <i class="fa-solid fa-circle-left previous payment-back" name="previous"></i>
                        <section>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-7 mb-3 px-md-2 p-0 ">
                                        {{-- //////////////////////////////////// --}}
                                        <div class="payment-form-wrap" id="div1" style="display: none;">
                                            <div class="card">
                                                <div class="card-title mx-auto" style="margin-bottom: 0;">PAYMENT</div>
                                                <form method="post" id="formWithCard"
                                                    action="{{ route('order.payment') }}">
                                                    @csrf
                                                    <input type="hidden" class="payAble" id="payAble" name="payAble"
                                                        value="{{ $totalPrice }}">
                                                    <input type="hidden" name="payment_method" id="payment_method">
                                                    <div class="row gap-2 mb-2">
                                                        <div class="col-md-12">
                                                            <div class="payment-method p-3 d-flex align-items-center justify-content-between"
                                                                data-method="credit-card">
                                                                <h5>Pay with Credit Card</h5>
                                                                <img class="icon"
                                                                    src="{{ asset('assets/new_frontend/cards.png') }}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="payment-method p-3 d-flex align-items-center justify-content-between"
                                                                data-method="easy-paisa">
                                                                <h5>Pay with EasyPaisa</h5>
                                                                <img class="icon"
                                                                    src="{{ asset('assets/new_frontend/easypaisa-logo.png') }}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="payment-method p-3 d-flex align-items-center justify-content-between"
                                                                data-method="online-cash">
                                                                <h5>Pay with Online/Cash</h5>
                                                                <img class="icon"
                                                                    src="{{ asset('assets/new_frontend/online-money.png') }}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-center payment_toggole_form mb-3">
                                                        <h5>SHIPPING ADDRESS FOR MEDICINES</h5>
                                                    </div>
                                                    <div class="col-md-12 pt-3 phd border-top">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-1">
                                                                <label for="exampleInputEmail1" class="form-label">Full
                                                                    Name*</label>
                                                                <input required name="shipping_customer_name"
                                                                    type="text" class="form-control mt-1"
                                                                    placeholder="Full Name" />
                                                            </div>
                                                            <div class="col-md-6 mb-1">
                                                                <label for="exampleInputEmail1"
                                                                    class="form-label">Email</label>
                                                                <input name="shipping_customer_email" type="text"
                                                                    class="form-control mt-1" id="exampleInputEmail1"
                                                                    placeholder="Email" aria-describedby="emailHelp" />
                                                            </div>
                                                            <div class="col-md-6 mb-1">
                                                                <label for="exampleInputEmail1"
                                                                    class="form-label">Phone*</label>
                                                                <input required name="shipping_customer_phone"
                                                                    type="tel" class="form-control mt-1"
                                                                    placeholder="Phone" maxlength="11" />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="ship_city" class="form-label">City*</label>
                                                                <input required name="shipping_customer_city"
                                                                    type="text" id="ship_city"
                                                                    class="form-control mt-1" placeholder="City" />
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <label for="exampleInputEmail1"
                                                                    class="form-label">Address*</label>
                                                                <input required name="shipping_customer_address"
                                                                    type="text" class="form-control mt-1"
                                                                    placeholder="Address" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" id="final-pay-button"
                                                        class="btn btn-primary pay" disabled> Pay
                                                        Now</button>
                                                </form>
                                            </div>
                                        </div>
                                        {{-- //////////////////////////////////// --}}
                                    </div>
                                    <div class="col-md-5">
                                        <div>
                                            <div class="row payment-order-summary-wrap">
                                                <div class="mb-2 promo_maIN_div">
                                                    <label class="form-label">Enter Promo Code:</label>
                                                    <div class="d-flex justify-content-between">
                                                        <input type="text" class="form-control w-75 promo_input_F"
                                                            id="coupon_code">
                                                        <button class="btn promo__btn_apl coupon_apply">Apply</button>
                                                    </div>
                                                    <small id="promo_already_used" class="text-danger"
                                                        style="display: none"> Promo Code Already Used Once. </small>
                                                    <small id="promo_success" class="text-success" style="display: none">
                                                        Promo Code Added Successfully. </small>
                                                    <small id="promo_danger" class="text-danger" style="display: none">
                                                        Promo Code Expired. </small>
                                                    <small id="promo_added" class="text-success" style="display: none">
                                                        Promo Code Already Applied. </small>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header">Order Summary</div>
                                                    <ul class="list-group list-group-flush">
                                                        {{-- <li class="list-group-item">
                                                            Provider Fee <span id="final_provider_fee"></span>
                                                        </li> --}}
                                                        <li class="list-group-item">
                                                            Total Cost <span id="totalCastFinal"></span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            To be Paid <span id="totalPaidFinal"></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row mt-2 payment-your-order-wrap">
                                                <div class="accordion accordion-flush p-0" id="accordionFlushExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="flush-headingOne">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#flush-collapseOne" aria-expanded="false"
                                                                aria-controls="flush-collapseOne">
                                                                Your Items
                                                            </button>
                                                        </h2>
                                                        <div id="flush-collapseOne"
                                                            class="accordion-collapse collapse show"
                                                            aria-labelledby="flush-headingOne"
                                                            data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <div id="loadItemChecoutFinal">

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
                        </section>
                    </fieldset>
                    <!-- ******* PAYMENT-SEC ENDS ******** -->
                </div>
            </div>
        @else
            <div class="col my-5">
                <h2 class="text-center">Your Cart is empty</h2>
            </div>
        @endif
    </div>
    @php
        $page = DB::table('pages')->where('url', '/')->first();
    @endphp

@endsection
