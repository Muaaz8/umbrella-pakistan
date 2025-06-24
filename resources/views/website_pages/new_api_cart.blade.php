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
            color: #fff !important;
            border: none;
        }

        .process-pay:hover {
            background-image: linear-gradient(#568fe6, #051b3b);
            color: #fff;
        }

        .buttonload {
            padding: 12px 16px;
        }


        .dot-pulse {
            position: relative;
            left: -9999px;
            color: #08295a;
            box-shadow: 9999px 0 0 -5px #08295a;
            animation: 1.5s linear 0.25s infinite dotPulse;
        }

        .dot-pulse::after,
        .dot-pulse::before {
            content: "";
            display: inline-block;
            position: absolute;
            top: 0;
            color: #08295a;
        }

        .dot-pulse::before {
            box-shadow: 9984px 0 0 -5px #08295a;
            animation: 1.5s linear infinite dotPulseBefore;
        }

        .dot-pulse::after {
            box-shadow: 10014px 0 0 -5px #08295a;
            animation: 1.5s linear 0.5s infinite dotPulseAfter;
        }

        .payment-form-wrap .card,
        .payment-order-summary-wrap .card,
        .payment-your-order-wrap .accordion-body,
        .shopping-cart-all-wrap .card {
            box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .payment-form-wrap .card {
            height: 100%;
        }

        .stage {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        @keyframes dotPulseBefore {

            0%,
            100%,
            60% {
                box-shadow: 9984px 0 0 -5px #08295a;
            }

            30% {
                box-shadow: 9984px 0 0 2px #08295a;
            }
        }

        @keyframes dotPulse {

            0%,
            100%,
            60% {
                box-shadow: 9999px 0 0 -5px #08295a;
            }

            30% {
                box-shadow: 9999px 0 0 2px #08295a;
            }
        }

        @keyframes dotPulseAfter {

            0%,
            100%,
            60% {
                box-shadow: 10014px 0 0 -5px #08295a;
            }

            30% {
                box-shadow: 10014px 0 0 2px #08295a;
            }
        }

        .go-back {
            position: fixed;
            top: 3.5em;
            left: 15px;
            color: #08295a;
            font-size: 12px;
            z-index: 889;
            display: none;
            cursor: pointer;
        }

        .med-price,
        .payment-order-summary-wrap .list-group-flush>.list-group-item,
        .shopping-cart-all-wrap .title {
            display: flex;
            justify-content: space-between;
        }

        .shopping-cart-all-wrap .title {
            margin-bottom: 5vh;
            align-items: baseline;
        }

        .shopping-cart-all-wrap .card {
            margin: auto;
            width: 100%;
            border: transparent;
            padding: 20px;
        }

        .shopping-cart-all-wrap .summary {
            background-color: #ddd;
            border-top-right-radius: 1rem;
            border-bottom-right-radius: 1rem;
            padding: 4vh;
            color: #414141;
        }

        .text-prescribed {
            font-size: 11px;
            color: red;
        }

        .qty-num {
            padding: 4px 10px;
        }

        .item-tag-name {
            text-align: center;
            transform: rotate(-90deg);
            position: absolute;
            left: -11px;
            top: 30%;
            font-size: 11px;
            color: #fff;
            padding: 2px 5px;
            font-weight: 500;
        }

        .tag-name-imaging {
            background-color: #ff8400;
        }

        .tag-name-lab {
            background-color: purple;
        }

        .tag-name-pharmacy {
            background: red;
        }

        .cart-img-div {
            position: relative;
            text-align: center;
        }

        @media (max-width: 767px) {
            .shopping-cart-all-wrap .card {
                margin: 3vh auto;
            }

            .shopping-cart-all-wrap .summary {
                border-top-right-radius: unset;
                border-bottom-left-radius: 1rem;
            }
        }

        .shopping-cart-all-wrap #myList,
        .shopping-cart-all-wrap .summary .col-10,
        .shopping-cart-all-wrap .summary .col-2 {
            padding: 0;
        }

        .shopping-cart-all-wrap .title b {
            font-size: 1.5rem;
        }

        .shopping-cart-all-wrap .main {
            margin: 0;
            padding: 1vh 0;
            width: 100%;
        }

        .shopping-cart-all-wrap .close {
            margin-left: auto;
            float: right;
            color: red;
        }

        .shopping-cart-all-wrap .close input {
            position: absolute;
            top: 0;
            right: 0;
            transform: scale(2);
            width: auto !important;
        }

        .shopping-cart-all-wrap img {
            width: 3.5rem;
        }

        hr {
            margin-top: 1.25rem;
        }

        .shopping-cart-all-wrap form {
            padding: 2vh 0;
        }

        .shopping-cart-all-wrap select {
            border: 1px solid rgba(0, 0, 0, 0.137);
            padding: 1.5vh 1vh;
            margin-bottom: 4vh;
            outline: 0;
            width: 100%;
            background-color: #f7f7f7;
        }

        .tag-prescribed {
            display: inline-block;
            width: auto;
            height: 20px;
            background-color: red;
            -webkit-border-radius: 3px 4px 4px 3px;
            -moz-border-radius: 3px 4px 4px 3px;
            border-radius: 3px 4px 4px 3px;
            border-left: 1px solid #979797;
            margin-left: 19px;
            position: relative;
            color: #fff;
            font-weight: 300;
            font-family: "Source Sans Pro", sans-serif;
            font-size: 12px;
            line-height: 23px;
            padding: 0 10px;
        }

        .tag-prescribed:before {
            content: "";
            position: absolute;
            display: block;
            left: -13px;
            width: 0;
            height: 0;
            border-top: 12px solid transparent;
            border-bottom: 7px solid transparent;
            border-right: 12px solid red;
        }

        .tag-prescribed:after {
            content: "";
            background-color: #fff;
            border-radius: 50%;
            width: 4px;
            height: 4px;
            display: block;
            position: absolute;
            left: -9px;
            top: 9px;
        }

        .detail-cart-btn h4 {
            font-size: 20px;
            float: right;
            font-weight: 800;
            color: #fff;
            margin-right: 20px;
        }

        .add-to-cart-detail {
            box-shadow: rgba(149, 157, 165, 0.2) 0 8px 24px;
            margin: 0;
            background-color: #08295a;
            color: #fff;
            text-align: center;
            padding: 10px 30px;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: end;
        }

        .detail-pharmcy-wrapper {
            box-shadow: rgb(14 30 37 / 12%) 0 2px 4px 0, rgb(14 30 37 / 32%) 0 2px 16px 0;
            margin: 20px 0;
            border-radius: 20px;
        }

        .detail-pharmcy-content {
            padding: 30px;
            margin: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .add-to-cart-detail h3 {
            font-size: 20px;
        }

        .selected-option:first-child {
            margin-left: 10px;
        }

        .selected-option {
            border: none;
            padding: 2px 10px;
            font-size: 12px;
            border-radius: 22px;
            margin-bottom: 5px;
            background-color: #08295a;
            color: #fff;
            box-shadow: rgba(149, 157, 165, 0.2) 0 8px 24px;
        }

        .selected-option i {
            margin: 0;
            cursor: pointer;
        }

        .payment-form-wrap .card {
            margin: auto;
            padding: 3rem 2rem;
        }

        .mt-50 {
            margin-top: 50px;
        }

        .mb-50 {
            margin-bottom: 50px;
        }

        .payment-form-wrap .card-title {
            font-weight: 700;
            font-size: 2.5em;
        }

        .payment-form-wrap .form-label {
            margin: 0;
            font-weight: 600;
            text-align: left;
            width: 100%;
        }

        .payment-form-wrap .form-control {
            background-color: #c0d1dc38;
            line-height: 2;
        }

        .payment-form-wrap button {
            border: none;
            background-color: #08295a;
            font-size: 20px;
            font-weight: 800;
            border-radius: 10px;
            padding: 15px 0;
            width: 100%;
        }

        .payment-order-summary-wrap .card {
            padding: 0;
        }

        .payment-your-order-wrap .accordion-button {
            box-shadow: rgba(0, 0, 0, 0.16) 0 3px 6px, rgba(0, 0, 0, 0.23) 0 3px 6px;
            font-weight: 800;
        }

        .payment-order-summary-wrap .card-header,
        .payment-order-summary-wrap .list-group-flush>.list-group-item span {
            font-weight: 800;
        }

        .payment-your-order-wrap .card {
            border: 1px solid #00000038;
            height: 95px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            font-size: 12px;
            padding: 0;
        }

        .payment-your-order-wrap .card .card-body {
            padding: 0;
            text-align: left;
        }

        .payment-your-order-wrap .image-wrap-inner {
            display: flex;
            align-items: center;
            width: 20%;
            height: 100%;
        }

        .payment-your-order-wrap .image-wrap-inner img {
            width: 60px;
            height: 60px;
        }

        .checkout-steps-wrap #checkoutform .action-button {
            width: 100%;
            background: #08295a;
            font-weight: 700;
            color: #fff;
            border: 0;
            border-radius: 1px;
            cursor: pointer;
            padding: 10px 5px;
            margin: 10px 5px;
        }

        .checkout-steps-wrap #checkoutform .action-button:focus,
        .checkout-steps-wrap #checkoutform .action-button:hover {
            box-shadow: 0 0 0 2px #fff, 0 0 0 3px #08295a;
        }

        .checkout-steps-wrap #progressbar {
            margin: 20px 0;
            overflow: hidden;
            counter-reset: step;
            text-align: center;
            display: flex;
            justify-content: center;
        }

        .checkout-steps-wrap #progressbar li {
            list-style-type: none;
            color: #333;
            text-transform: uppercase;
            font-size: 12px;
            width: 33.33%;
            float: left;
            position: relative;
        }

        .checkout-steps-wrap #progressbar li:before {
            content: counter(step);
            counter-increment: step;
            width: 40px !important;
            height: 40px !important;
            line-height: 40px;
            display: block;
            font-size: 18px;
            color: #333;
            background: #ccc;
            border-radius: 3px;
            margin: 0 auto 5px;
        }

        .checkout-steps-wrap #progressbar li:after {
            content: "";
            width: 100%;
            height: 3px;
            background: #ccc;
            position: absolute;
            left: -50%;
            top: 24px;
            z-index: -1;
        }

        .checkout-steps-wrap #progressbar li:first-child:after {
            content: none;
        }

        .affiliate-selected,
        .checkout-steps-wrap #progressbar li.active:after,
        .checkout-steps-wrap #progressbar li.active:before {
            background: #08295a;
            color: #fff;
        }

        .proceed-checkou-btn button {
            width: 100%;
            background-color: #08295a;
            color: #fff;
            border: none;
            padding: 8px 0;
        }

        .affiliate-grid input[type="checkbox"] {
            position: absolute;
            top: 10px;
            right: 10px;
            display: none;
        }

        .affiliate-grid-details>span {
            display: block;
            padding-left: 10px;
            color: #7f7e7e;
            font-family: helvetica;
            font-size: 14px;
            text-align: left;
            line-height: 1.5;
            width: 200px;
            -ms-text-overflow: ellipsis;
        }

        .affiliate-exported {
            position: absolute;
            top: 40%;
            display: block;
            width: 100%;
            height: 40px;
            background: rgba(#666, 0.95);
        }

        .affiliate-exported:after {
            text-align: center;
            content: "Exported";
            display: inline-block;
            font-family: helvetica;
            padding-top: 10px;
            font-size: 14px;
            color: #ddd;
        }

        .shopping-cart-all-wrap #myList>li {
            border: 1px solid #ccc;
            list-style: none;
            padding: 5px;
            margin-bottom: 2px;
            color: #333;
            cursor: pointer;
        }

        .shopping-cart-all-wrap #myList>li.selected {
            background-color: #08295a;
            color: #fff;
            font-weight: 500;
        }

        .payment-back {
            font-size: 35px;
            margin-bottom: 10px;
            cursor: pointer;
            color: #08295a;
        }

        .payment_toggole_form .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .payment_toggole_form .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .payment_toggole_form .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: 0.4s;
            transition: 0.4s;
        }

        .payment_toggole_form .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: red;
            -webkit-transition: 0.4s;
            transition: 0.4s;
        }

        .payment_toggole_form input:checked+.slider::before {
            background-color: #26e826;
        }

        .payment_toggole_form input:focus+.slider {
            box-shadow: 0 0 1px #2196f3;
        }

        .payment_toggole_form input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        .payment_toggole_form .slider.round {
            border-radius: 34px;
            background-color: #08295a;
        }

        .payment_toggole_form .slider.round:before {
            border-radius: 50%;
        }

        .payment_toggole_form {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .health_cards:hover {
            box-shadow: rgba(0, 0, 0, 0.24) 0 3px 8px;
        }

        .health_cards {
            border: 1px solid #00000030;
        }

        .thankyoucontent .wrapper-1 {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .thankyoucontent .wrapper-2 {
            padding: 20px;
            text-align: center;
        }

        .thankyoucontent h1 {
            font-family: Raleway, Arial Black, Sans-Serif;
            font-size: 3em;
            font-weight: 900;
            letter-spacing: 3px;
            color: #08295a;
            margin: 20px 0;
        }

        .thankyoucontent .wrapper-2 p {
            margin: 0;
            font-size: 1.3em;
            color: #08295a;
            font-family: Raleway, sans-serif;
            letter-spacing: 1px;
            line-height: 1.5;
        }

        .thankyoucontent .go-home {
            background: #08295a;
            color: #fff;
            border: none;
            padding: 12px 30px;
            margin: 30px 0;
            border-radius: 5px;
            cursor: pointer;
        }

        .thankyoucontent .go-home:hover {
            opacity: 0.9;
        }

        .thankyoucontent .go-home a {
            font-family: Raleway, Arial Black;
            font-size: 1rem;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #fff !important;
        }

        .thankyoucontent img {
            width: 15%;
            filter: drop-shadow(2px 4px 6px black);
        }

        .Email-confirm-wrap {
            box-shadow: rgb(0 0 0 / 15%) 0 5px 15px 0;
            padding: 0 40px 40px;
            margin: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 400px;
            justify-content: space-around;
            background-color: #fff;
            border-radius: 20px;
        }

        .Email-confirm-wrap button {
            background: linear-gradient(to top, #08295a, #165dc8);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 40px;
        }

        .psychiatrist-div ul {
            margin: auto;
            padding: revert;
        }

        .psychiatrist-div ul li {
            list-style: disc;
        }

        .api_saved_card {
            box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
            border-radius: 10px;
            padding: 0 20px 0 10px;
        }

        .api_saved_card img {
            width: 50px;
        }

        .list-group {
            width: 100%;
        }

        /* add bootstrap default card class css  */
        .card {
            border: 1px solid #dee2e6 !important;
            border-radius: 0.25rem !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
            margin-bottom: 1.5rem;
            background-color: #fff !important;
            padding: 1.25rem !important;
            position: relative !important;
            overflow: hidden !important;
            transition: box-shadow 0.15s ease-in-out !important;
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            min-width: 100% !important;
            flex: 1 1 auto !important;
        }

        #myList {
            width: 100%;
        }
    </style>
@endsection

@section('top_import_file')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
        integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection


@section('bottom_import_file')
    <script src="https://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>
    <script>
        function toggleDrawer() {
            const drawer = document.getElementById("drawer");
            const blurOverlay = document.getElementById("blurOverlay");
            const hamburger = document.querySelector(".hamburger");

            drawer.classList.toggle("active");
            blurOverlay.classList.toggle("active");
            hamburger.classList.toggle("active");
        }

        $(document).ready(function() {

            $(".search-btn-mob").on("click", function() {
                $(".header-search-container").css("display", "block");
            });

            $(document).on("click", function(event) {
                if (!$(event.target).closest(".header-search-container") && !$(event.target).closest(
                        ".search-btn-mob")) {
                    $(".header-search-container").css("display", "none");
                }
            });

            $('#new-search2').on('input', function() {
                const searchTerm = $(this).val().trim().toLowerCase();

                if (searchTerm.length === 0) {
                    $('.header-search-result').empty().hide();
                    return;
                }

                $.ajax({
                    url: `/search_items/${searchTerm}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        const {
                            products,
                            test_codes
                        } = response;

                        $('.header-search-result').empty();

                        if (products.length > 0 || test_codes.length > 0) {
                            products.forEach(product => {
                                $('.header-search-result').append(`
                      <li>
                          <a href="/medicines/${product.slug}" class="d-flex flex-column justify-content-between align-items-start w-100">
                              <span class="product-name">${product.name}</span>
                              <span class="category-name">Pharmacy</span>
                          </a>
                      </li>
                  `);
                            });

                            test_codes.forEach(test => {
                                $('.header-search-result').append(`
                      <li>
                          <a href="/labtest/${test.SLUG}" class="d-flex flex-column justify-content-between align-items-start w-100">
                              <span class="product-name">${test.TEST_NAME}</span>
                                <span class="category-name">Lab Test</span>
                          </a>
                      </li>
                  `);
                            });

                            $('.header-search-result').show();
                        } else {
                            $('.header-search-result').hide();
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching search results:', error);
                    }
                });
            });
            $('#new-search').on('input', function() {
                const searchTerm = $(this).val().trim().toLowerCase();

                if (searchTerm.length === 0) {
                    $('.header-search-result').empty().hide();
                    return;
                }

                $.ajax({
                    url: `/search_items/${searchTerm}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        const {
                            products,
                            test_codes
                        } = response;

                        $('.header-search-result').empty();

                        if (products.length > 0 || test_codes.length > 0) {
                            products.forEach(product => {
                                $('.header-search-result').append(`
                      <li>
                          <a href="/medicines/${product.slug}" class="d-flex flex-column justify-content-between align-items-start w-100">
                              <span class="product-name">${product.name}</span>
                              <span class="category-name">Pharmacy</span>
                          </a>
                      </li>
                  `);
                            });

                            test_codes.forEach(test => {
                                $('.header-search-result').append(`
                      <li>
                          <a href="/labtest/${test.SLUG}" class="d-flex flex-column justify-content-between align-items-start w-100">
                              <span class="product-name">${test.TEST_NAME}</span>
                                <span class="category-name">Lab Test</span>
                          </a>
                      </li>
                  `);
                            });

                            $('.header-search-result').show();
                        } else {
                            $('.header-search-result').hide();
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching search results:', error);
                    }
                });
            });



            $(document).on('click', function(event) {
                if (!$(event.target).closest('.header-search-container')) {
                    $('.header-search-result').hide();
                }
            });

            $('#new-search').on('focus', function() {
                if ($('.header-search-result').children().length > 0) {
                    $('.header-search-result').show();
                }
            });

            $('#new-search').on('blur', function() {
                if ($(this).val() === "") {
                    $('.header-search-result').hide();
                }
            });

            $('#new-search2').on('focus', function() {
                if ($('.header-search-result').children().length > 0) {
                    $('.header-search-result').show();
                }
            });

            $('#new-search2').on('blur', function() {
                if ($(this).val() === "") {
                    $('.header-search-result').hide();
                }
            });
        });
        @php header("Access-Control-Allow-Origin: *"); @endphp
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches
        var check; //for enabling and disabling checkout button
        function addHyphen(element) {
            let ele = document.getElementById(element.id);
            ele = ele.value.split('-').join(''); // Remove dash (-) if mistakenly entered.
            if (ele.length <= 16) {
                let finalVal = ele.match(/.{1,4}/g).join('-');
                document.getElementById(element.id).value = finalVal;
            }
        }
        $(document).ready(function() {
            //get the divs to show/hide
            // alert('ok');
            check = $('input:checked', this).length;
            if (check == 0) {
                $('.next').attr('disabled', true);
            } else {
                $('.next').attr('disabled', false);
            }
        });

        $(document).ready(function() {
            var dd = "{{ count($cards) }}";
            if (dd == 0) {
                $("#div1").show();
                $('#div2').hide();
            }
            $("#card_num").keyup(function() {
                // $("#card_num").css("background-color", "green");
                var e = $("#card_num").val();
                if (e.substring(0, 1) == 3) {
                    $(".card-pic").html('');
                    $(".card-pic").html(
                        "<img src='{{ asset('assets/images/american-express.png') }}'' class='pay-image w-100' alt=''>"
                    );
                } else if (e.substring(0, 1) == 4) {
                    $(".card-pic").html('');
                    $(".card-pic").html(
                        "<img src='{{ asset('assets/images/visa.png') }}'' class='pay-image w-100' alt=''>"
                    );
                } else if (e.substring(0, 1) == 5) {
                    $(".card-pic").html('');
                    $(".card-pic").html(
                        "<img src='{{ asset('assets/images/master.png') }}'' class='pay-image w-100' alt=''>"
                    );
                } else if (e.substring(0, 1) == 6) {
                    $(".card-pic").html('');
                    $(".card-pic").html(
                        "<img src='{{ asset('assets/images/discover.png') }}'' class='pay-image w-100' alt=''>"
                    );
                } else {
                    $(".card-pic1").html('');
                }
            });
        });

        $(".next").click(function() {
            $('#loadItemChecoutFinal').text('');
            $.ajax({
                type: 'POST',
                url: "{{ route('show_product_on_final_checkout') }}",
                success: function(res) {
                    $('#totalCastFinal').text('Rs.' + res.itemSum);
                    $('#totalPaidFinal').text('Rs.' + res.totalPrice);
                    //if (res.providerFee > 0) {
                    //    $('#final_provider_fee').html('$6.00');
                    //} else {
                    //$('#final_provider_fee').html('$0.00');
                    //}
                    var showShipping = 0;
                    $.each(res.allProducts, function(key, product) {
                        if (product.product_mode == "medicine") {
                            showShipping += 1;
                        }
                        if (product.item_type == 'prescribed') {
                            $('#loadItemChecoutFinal').append('<div class="card mb-1">' +
                                '<div class="row g-0">' +
                                '<div class="d-flex">' +
                                '<div class="image-wrap-inner">' +
                                '<img src="' + product.product_image +
                                '" class="img-fluid rounded-start" alt="' + product.name +
                                '"/>' +
                                '</div>' +
                                '<div class="card-body">' +
                                '<h5 class="card-title fs-5">' + product.name + '</h5>' +
                                '<h5 class="checkoutItem_prod" id="' + product.product_id +
                                '" hidden></h5>' +
                                '<h5 class="checkoutItem_cart" id="' + product.id +
                                '" hidden>' +
                                product.id + '</h5>' +
                                '<p class="card-text">Qty:1 <span class="float-end pe-3"><b> Price: Rs.' +
                                product.update_price + '</b></span></p>' +
                                '<p class="card-text">Prescribed by ' + product.prescribed +
                                '</p>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>');
                        } else {
                            $('#loadItemChecoutFinal').append('<div class="card mb-1">' +
                                '<div class="row g-0">' +
                                '<div class="d-flex">' +
                                '<div class="image-wrap-inner">' +
                                '<img src="' + product.product_image +
                                '" class="img-fluid rounded-start fs-5" alt="' + product.name +
                                '"/>' +
                                '</div>' +
                                '<div class="card-body">' +
                                '<h5 class="card-title">' + product.name + '</h5>' +
                                '<h5 class="checkoutItem_prod" id="' + product.product_id +
                                '" hidden>' + product.product_id + '</h5>' +
                                '<h5 class="checkoutItem_cart" id="' + product.id +
                                '" hidden>' +
                                product.id + '</h5>' +
                                '<p class="card-text">Qty:1 <span class="float-end pe-3"><b> Price: Rs.' +
                                product.update_price + '</b></span></p>' +
                                '<p class="card-text">Counter Purchased</p>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>');
                        }

                    });
                    if (showShipping > 0) {
                        $('.payment_toggole_form').show();
                    } else {
                        $('.payment_toggole_form').hide();
                    }
                }
            });

            $.ajax({
                type: "get",
                url: "/admin/coupon/check",
                success: function(response) {
                    if (response == true) {
                        $('#promo_added').show();
                        $('#coupon_code').prop("disabled", true);
                        $('.coupon_apply').prop("disabled", true);
                    } else {
                        $('#promo_added').hide();
                        $('#coupon_code').prop("disabled", false);
                        $('.coupon_apply').prop("disabled", false);
                    }

                }
            });

            if (animating) return false;
            animating = true;
            current_fs = $(this).parent().parent().parent().parent().parent().parent().parent();
            next_fs = $(this).parent().parent().parent().parent().parent().parent().parent().next();
            //activate next step on progressbar using the index of next_fs
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = now * 50 + "%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        transform: "scale(" + scale + ")"
                    });
                    next_fs.css({
                        left: left,
                        opacity: opacity
                    });
                },
                duration: 800,
                complete: function() {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: "easeInOutBack",
            });
        });



        $(".previous").click(function() {
            if (animating) return false;
            animating = true;

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //de-activate current step on progressbar
            $("#progressbar li")
                .eq($("fieldset").index(current_fs))
                .removeClass("active");

            //show the previous fieldset
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = (1 - now) * 50 + "%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        left: left
                    });
                    previous_fs.css({
                        transform: "scale(" + scale + ")",
                        opacity: opacity,
                    });
                },
                duration: 800,
                complete: function() {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: "easeInOutBack",
            });
        });


        function checkboxFunction(a) {

            var classes = $(a).parent().parent().parent().parent().parent().attr('class');
            var counter = classes.split('_');
            if ($('#' + classes).is(':checked')) {
                check++;
                $('.next').attr('disabled', false);
                $('.heading_' + counter[1]).css('color', 'white');
                $('.price_' + counter[1]).css('color', 'white');
                $('#' + classes).prop("checked", true);
                $('.' + classes).css('background-color', '#08295a');
                var cartitemid = $('#cartitemid_' + counter[1]).val();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('show_product_on_checkout') }}",
                    data: {
                        item_id: cartitemid,
                    },
                    success: function(res) {
                        var countItem = res.countItem;
                        var itemSum = res.itemSum;
                        var totalPrice = res.totalPrice;
                        $('#totalItem').text(countItem);
                        $('#totalCast').text('Rs. ' + itemSum);
                        $('#totalPaid').text('Rs. ' + totalPrice);
                        $('.payAble').val(totalPrice);

                        // if (typeof res.providerFee == 'undefined') {
                        //     $('#provider_fee').html('Rs. 0.00');

                        // } else {
                        //     $('#provider_fee').html('Rs. ' + res.providerFee);
                        // }
                    }
                });
            } else {
                $('.heading_' + counter[1]).css('color', '#333');
                $('.price_' + counter[1]).css('color', '#333');
                $('#' + classes).prop("checked", false);
                $('.' + classes).css('background-color', 'white');
                var cartitemid = $('#cartitemid_' + counter[1]).val();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('remove_product_on_checkout') }}",
                    data: {
                        item_id: cartitemid,
                    },
                    success: function(res) {
                        check--;
                        if (check == 0) {
                            $('.next').attr('disabled', true)
                        }
                        var countItem = res.countItem;
                        var itemSum = res.itemSum;
                        var totalPrice = res.totalPrice;
                        $('#totalItem').text(countItem);
                        $('#totalCast').text('Rs. ' + itemSum);
                        $('#totalPaid').text('Rs. ' + totalPrice);
                        $('.payAble').val(totalPrice);
                        // if (typeof res.providerFee == 'undefined') {
                        //     $('#provider_fee').html('Rs. 0.00');

                        // } else {
                        //     $('#provider_fee').html('Rs. ' + res.providerFee);
                        // }

                    }
                });
            }
        }


        function create_custom_dropdowns() {}

        function divClick(e) {
            var value = $(e).data('card');
            var radioBtn = $(e).find('input[type="radio"]');
            radioBtn.prop('checked', true);
            if (value == 0) {
                $('#div2').hide();
                $("#div1").show()
            } else {
                $('#div1').hide();
                $('#div2').show();
                $("#card_no").attr("value", value);
                $.ajax({
                    type: 'POST',
                    url: "{{ URL('/get_card_details') }}",
                    data: {
                        id: value
                    },
                    success: function(response) {
                        $("#billing_name").val(response.billing.name);
                        $("#billing_last_name").val(response.billing.last_name);
                        $("#billing_email").val(response.billing.email);
                        $("#billing_card_number").val(response.billing.number);
                        $("#billing_month").val(response.billing.expiration_month);
                        $("#billing_year").val(response.billing.expiration_year);
                        $("#billing_csc").val(response.billing.csc);
                        // $("#billing_name").val(response.billing.name);
                        $("#billing_address").val(response.billing.street_address);
                        $("#billing_city").val(response.billing.city);
                        $("#billing_state").val(response.billing.state);
                        $("#billing_zip").val(response.billing.zip);
                        $("#billing_phone").val(response.billing.phoneNumber);
                    }
                });
                // $('.payment_toggole_form').show();
            }
        }

        function radioClick(e) {
            // var e = document.getElementById("cardNo");
            var value = $(e).attr('class');
            // alert(value);
            if (value == 0) {
                $('#div2').hide();
                $("#div1").show()
            } else {
                $('#div1').hide();
                $('#div2').show();
                $("#card_no").attr("value", value);
                $.ajax({
                    type: 'POST',
                    url: "{{ URL('/get_card_details') }}",
                    data: {
                        id: value
                    },
                    success: function(response) {
                        $("#billing_name").val(response.billing.name);
                        $("#billing_last_name").val(response.billing.last_name);
                        $("#billing_email").val(response.billing.email);
                        $("#billing_card_number").val(response.billing.number);
                        $("#billing_month").val(response.billing.expiration_month);
                        $("#billing_year").val(response.billing.expiration_year);
                        $("#billing_csc").val(response.billing.csc);
                        // $("#billing_name").val(response.billing.name);
                        $("#billing_address").val(response.billing.street_address);
                        $("#billing_city").val(response.billing.city);
                        $("#billing_state").val(response.billing.state);
                        $("#billing_zip").val(response.billing.zip);
                        $("#billing_phone").val(response.billing.phoneNumber);
                    }
                });
                // $('.payment_toggole_form').show();
            }
        }

        function addCard() {
            var e = document.getElementById("cardNo");
            $("input[name='card']").each(function() {
                if ($(this).val() !== "1") {
                    $(this).prop("checked", false);
                }
            });
            e.value = 0;
            // alert(e.value);
            $('#div2').hide();
            $("#div1").show();
        }
        $('#formWithCard').submit(function() {
            $('#final-pay-button').attr('disabled', true);
            $('button').attr('disabled', true);
            $('#slider_round').attr('disabled', true);
            var element = $(".pay");
            element.addClass("buttonload");
            element.html('<i class="fa fa-spinner fa-spin"></i>Processing...');
        });
        $('#formWithCard1').submit(function() {
            $('#final-pay-button1').attr('disabled', true);
            $('button').attr('disabled', true);
            $('#slider_round').attr('disabled', true);
            var element = $(".pay");
            element.addClass("buttonload");
            element.html('<i class="fa fa-spinner fa-spin"></i>Processing...');
        });

        $("#zipcode").keyup(function() {
            var zip = $("#zipcode").val();
            var length = $("#zipcode").val().length;
            if (length >= 5) {
                $('#state_code').val('');
                $('#city').val('');
                $.ajax({
                    type: "POST",
                    url: "/get_states_cities",
                    data: {
                        zip: zip,
                    },
                    success: function(data) {
                        if (data == "") {
                            return false;
                        } else {
                            $('#state_code').val(data.abbreviation);
                            $('#city').val(data.city);
                            $('#state_code').attr('readonly', true);
                            $('#city').attr('readonly', true);
                        }
                    },
                });
            }
        });

        $("#zip_code").keyup(function() {
            var zip = $("#zip_code").val();
            var length = $("#zip_code").val().length;
            if (length >= 5) {
                $('#ship_state_code').val('');
                $('#ship_city').val('');
                $.ajax({
                    type: "POST",
                    url: "/get_states_cities",
                    data: {
                        zip: zip,
                    },
                    success: function(data) {
                        if (data == "") {
                            return false;
                        } else {
                            $('#ship_state_code').val(data.abbreviation);
                            $('#ship_city').val(data.city);
                        }
                    },
                });
            }
        });

        $("#shipping_customer_zip").keyup(function() {
            var zip = $("#shipping_customer_zip").val();
            var length = $("#shipping_customer_zip").val().length;
            if (length >= 5) {
                $('#shipping_customer_state').val('');
                $('#shipping_customer_city').val('');
                $.ajax({
                    type: "POST",
                    url: "/get_states_cities",
                    data: {
                        zip: zip,
                    },
                    success: function(data) {
                        if (data == "") {
                            return false;
                        } else {
                            $('#shipping_customer_state').val(data.abbreviation);
                            $('#shipping_customer_city').val(data.city);
                        }
                    },
                });
            }
        });

        $('.coupon_apply').click(function() {
            var code = $('#coupon_code').val();
            var htmlProd = document.getElementsByClassName("checkoutItem_prod");
            var htmlCart = document.getElementsByClassName("checkoutItem_cart");
            var html = Array.from(htmlProd);
            var prod_id = '';
            html.forEach(element => {
                prod_id += element.id + ',';
            });
            html = Array.from(htmlCart);
            var cart_id = '';
            html.forEach(element => {
                cart_id += element.id + ',';
            });
            $.ajax({
                type: "post",
                url: "/coupon/apply/discount",
                data: {
                    code: code,
                    prod_id: prod_id,
                    cart_id: cart_id,
                },
                success: function(response) {
                    console.log(response);
                    if (response == 'true') {
                        $('#coupon_code').attr('disabled', true);
                        $('#promo_success').show();
                    } else if (response == 'false') {
                        $('#promo_already_used').show();
                    }
                    if (response == 'Date Expired!!') {
                        $('#promo_danger').show();
                    } else {
                        $('#loadItemChecoutFinal').text('');
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('show_product_on_final_checkout') }}",
                            success: function(res) {
                                $('#totalCastFinal').text('Rs.' + res.itemSum);
                                $('#totalPaidFinal').text('Rs.' + res.totalPrice);
                                // if (res.providerFee > 0) {
                                //     $('#final_provider_fee').html('$6.00');
                                // } else {

                                //     $('#final_provider_fee').html('$0.00');
                                // }
                                var showShipping = 0;
                                $.each(res.allProducts, function(key, product) {
                                    if (product.product_mode == "medicine") {
                                        showShipping += 1;
                                    }
                                    if (product.item_type == 'prescribed') {
                                        $('#loadItemChecoutFinal').append(
                                            '<div class="card mb-1">' +
                                            '<div class="row g-0">' +
                                            '<div class="d-flex">' +
                                            '<div class="image-wrap-inner">' +
                                            '<img src="' + product
                                            .product_image +
                                            '" class="img-fluid rounded-start fs-5" alt="' +
                                            product.name + '"/>' +
                                            '</div>' +
                                            '<div class="card-body">' +
                                            '<h5 class="card-title">' + product
                                            .name + '</h5>' +
                                            '<h5 class="checkoutItem" id="' +
                                            product.product_id +
                                            '" hidden></h5>' +
                                            '<h5 class="checkoutItem" id="' +
                                            product.id + '" hidden>' + product
                                            .id +
                                            '</h5>' +
                                            '<p class="card-text">Qty:1 <span class="float-end pe-3"><b> Price: Rs.' +
                                            product.update_price +
                                            '</b></span></p>' +
                                            '<p class="card-text">Prescribed by ' +
                                            product.prescribed + '</p>' +
                                            '</div>' +
                                            '</div>' +
                                            '</div>' +
                                            '</div>');
                                    } else {
                                        $('#loadItemChecoutFinal').append(
                                            '<div class="card mb-1">' +
                                            '<div class="row g-0">' +
                                            '<div class="d-flex">' +
                                            '<div class="image-wrap-inner">' +
                                            '<img src="' + product
                                            .product_image +
                                            '" class="img-fluid rounded-start fs-5" alt="' +
                                            product.name + '"/>' +
                                            '</div>' +
                                            '<div class="card-body">' +
                                            '<h5 class="card-title">' + product
                                            .name + '</h5>' +
                                            '<h5 class="checkoutItem" id="' +
                                            product.product_id + '" hidden>' +
                                            product.product_id + '</h5>' +
                                            '<h5 class="checkoutItem" id="' +
                                            product.id + '" hidden>' + product
                                            .id +
                                            '</h5>' +
                                            '<p class="card-text">Qty:1 <span class="float-end pe-3"><b> Price: Rs.' +
                                            product.update_price +
                                            '</b></span></p>' +
                                            '<p class="card-text">Counter Purchased</p>' +
                                            '</div>' +
                                            '</div>' +
                                            '</div>' +
                                            '</div>');
                                    }

                                });
                                if (showShipping > 0) {
                                    $('.payment_toggole_form').show();
                                } else {
                                    $('.payment_toggole_form').hide();
                                }
                                $(".payAble").val(res.totalPrice);
                            }
                        });
                    }
                }
            });
        });

        $(document).ready(function() {
            function disableBack() {
                window.history.forward()
            }
            window.onload = disableBack();
            window.onpageshow = function(e) {
                if (e.persisted)
                    disableBack();
            }
        });


        $(document).ready(function() {
            $(".payment-method").click(function() {
                $(".payment-method").removeClass("active");
                $(this).addClass("active");

                let selectedMethod = $(this).data("method");
                console.log(selectedMethod);

                $("#final-pay-button").attr("disabled", false);
                $("#payment_method").val(selectedMethod);
            });

        });
    </script>
@endsection

@section('content')

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
            <div class="checkout-steps-wrap">
                <div id="checkoutform">
                    <ul id="progressbar">
                        <li class="active">Checkout</li>
                        <li>Payment Details</li>
                    </ul>

                    <!-- ******* CHECKOUT-SEC STATRS ******** -->
                    <fieldset class="mb-3">
                        <section>
                            <div class="container-fluid px-3">
                                <div class="row w-100" style="--bs-gutter-x: 0.5rem;">
                                    <div class="col-md-8 shopping-cart-all-wrap">
                                        <div class="cart">
                                            <div class="card">
                                                <div class="row w-100">
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
                                                                            Rs.{{ number_format($item->update_price, 2) }}
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

                                                        {{-- <li class="list-group-item">Provider Fee<span id="provider_fee">

                                                                Rs.{{ number_format($providerFee, 2) ?? '' }}

                                                            </span></li> --}}

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
                                    <div class="col-md-8 mb-3 px-md-2 p-0 ">
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
                                    <div class="col-md-4">
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
