@extends('layouts.new_web_layout')

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
    </style>
    <style>
        :root {
            --red: #c80919;
            --blue: #2964bc;
            --maroon: #c80919;
            --navy-blue: #082755;
            --green: #35b518;
            --lh: 1.4rem;
            --lightgray: #f5f5f5;
            --lightblue: #2964BCA3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s ease-out;
        }

        .offer-banner>span {
            font-size: 16px;
        }

        i {
            margin-right: auto !important;
        }

        p {
            margin-bottom: 0;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #c7c7c7;
        }

        ::-webkit-scrollbar-thumb {
            background: #3b35ac;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #082755;
        }

        html,
        body {
            width: 100%;
            overflow-x: hidden;
        }

        header {
            background-color: white;
        }

        #contact-bar {
            height: 50px;
            background-color: var(--red);
            color: white;
            font-size: small;
        }

        #navbar {
            background-color: white;
        }

        #left-side>div>div,
        #right-side>div,
        #social-icons>a {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            border-radius: 30px;
            padding: 6px;
        }

        .checkout-steps-wrap #checkoutform .action-button:disabled {
            background: #334a6d;
            color: #ffff;
        }

        #contact-bar img {
            width: 15px;
        }

        .dropdown-item.active,
        .dropdown-item:active {
            color: white !important;
            text-decoration: none;
            background-color: var(--navy-blue);
        }

        .wrap {
            height: 100%;
            width: 90%;
            margin: 0 auto;
            border-bottom: 1px solid #e0e0e0;
        }

        .logo {
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: start;
        }

        .logo>img {
            width: 250px;
        }

        #nav-right-side {
            justify-content: flex-end;
        }

        #nav-left-side,
        #nav-right-side {
            gap: 20px;
        }

        #nav-left-side {
            justify-content: center;
        }

        #navbar a {
            text-decoration: none;
            color: black;
            border-bottom: 2px solid transparent;
            font-size: 15px;
            padding: 3px 0;
            font-weight: 600;
            width: max-content;
        }

        #drawer a {
            margin-left: 1rem;
        }

        #nav-left-side>a:hover {
            border-bottom: 2px solid var(--red);
        }

        #checker,
        #joinDropdown {
            border: 2px solid var(--green);
            padding: 5px 15px;
            border-radius: 30px;
            background: #ffff;
            font-weight: 600;
        }

        #checker>img,
        #joinDropdown>img {
            width: 25px;
        }

        #checker:hover,
        #joinDropdown:hover {
            border: 2px solid var(--red);
        }

        .nav_btns {
            display: flex;
            align-items: center;
            justify-content: between;
            border: 2px solid var(--green);
            border-radius: 30px;
            background: #ffff;
            padding: 3px 15px;
        }

        .nav_btns>a {
            font-size: 16px !important;
            text-decoration: none;
            color: black;
            padding: 0px !important;
            margin-bottom: 0 !important;
            margin-left: 5px;
            font-weight: 500;
        }

        .nav_btns:hover {
            border: 2px solid var(--red);
        }


        #nav-right-side>div:last-child>img {
            width: 30px;
        }

        #checker,
        #joinDropdown {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .primary-bg {
            background: #0048b1;
        }

        .cart-count {
            position: absolute;
            top: -45%;
            right: -50%;
            background: var(--red);
            color: white;
            height: 25px;
            width: 25px;
            border-radius: 50%;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .drawer img[alt="shop-icon"] {
            width: 25px;
            margin-left: 1rem;
        }

        .drawer div {
            margin-top: 0.35rem;
        }

        .drawer .cart-count {
            top: -8px;
            left: 32px;
            color: white;
            height: 15px;
            width: 15px;
            font-size: 8px;
        }

        .new-footer-cont {
            max-width: 100vw;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: var(--navy-blue);
            padding: 40px 0 20px 0;
            gap: 20px;
        }

        #footer-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            align-items: flex-start;
            width: 90%;
            gap: 20px;
            color: white;
        }

        .footer-new {
            display: flex;
            flex-direction: column;
            gap: 25px;
            align-items: start;
        }

        #footer-1 {
            display: flex;
            justify-content: space-between;
        }

        .footer-content-new {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: start;
        }

        .footer-heading {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-heading>h3 {
            font-size: 24px;
        }

        .footer-content-new>h4 {
            font-weight: 400;
            color: gainsboro;
        }

        .footer-content-new>div>a {
            text-decoration: none;
            color: gainsboro;
        }

        .footer-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-info>img {
            width: 10px;
        }

        .footer-info>a {
            border-bottom: 1px solid transparent;
        }

        .footer-info>a:hover {
            border-bottom: 1px solid var(--red);
            color: white;
        }

        .footer-highlight {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: var(--red);
            padding: 10px 15px;
            border-radius: 20px;
        }

        .footer-highlight>img {
            width: 20px;
        }

        .footer-highlight>a:hover {
            color: gainsboro;
        }

        #footer-logo-new {
            display: block;
        }

        #footer-logo-new>img {
            width: 80%;
            padding: 1rem;
            background-color: white;
            border-radius: 0.5rem;
            min-width: 220px;
        }

        #footer-2>.footer-content-new>h4 {
            text-decoration: 1px solid underline;
        }

        #social-icons {
            display: flex;
            justify-content: space-between;
        }

        #social-icons>a {
            background-color: var(--red);
            border: none;
            padding: 8px;
            color: white;
            text-decoration: none;
            min-width: 33px;
            min-height: 33px;
        }

        #social-icons>a>i {
            font-size: 17px;
        }

        #footer-4>div>h4 {
            font-weight: 500;
            color: white;
            font-size: 18px;
        }

        #copyright>p {
            color: gainsboro;
            text-align: center;
            padding: 0 0.5rem;
        }

        #copyright>span {
            font-weight: bold;
        }

        .seperation {
            width: 100%;
            height: 1px;
            background-color: gainsboro;
        }

        .hamburger_container {
            cursor: pointer;
            display: none;
            z-index: 1000;
        }

        .hamburger div {
            width: 30px;
            height: 3px;
            background-color: #333;
            margin: 5px 0;
            transition: 0.4s;
        }

        .drawer-item {
            color: var(--blue);
        }

        .drawer {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #ffffff;
            color: #202020;
            transform: translateX(-100%);
            transition: transform 0.4s ease;
            padding-top: 40px;
            z-index: 999;
            border-right: 1px solid #ff5757;
        }

        .drawer a {
            padding: 15px 30px;
            text-decoration: none;
            font-size: 16px;
            color: #fff;
            display: block;
            transition: 0.3s;
            border-bottom: 1px solid transparent;
        }

        .drawer hr {
            width: 90%;
            margin: 0 auto;
            border: 1px solid #ff5757;
        }

        .drawer a:hover {
            border-bottom: 1px solid var(--red) !important;
        }

        .drawer.active {
            transform: translateX(0);
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #202020;
            transition: 0.3s;
        }

        .close-btn:hover {
            color: #ff5757;
        }

        .blur-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
            display: none;
        }

        .blur-overlay.active {
            display: block;
        }

        .hamburger.active div:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active div:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active div:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        .dropdown-menu li a {
            color: #000;
            font-size: 14px !important;
            padding: 10px !important;
            display: block;
            font-weight: 500 !important;
            text-decoration: none !important;
            color: #000 !important;
            font-size: 14px !important;
            padding: 8px !important;
            text-decoration: none;
            width: 100% !important;
        }

        .footer-content-new>p {
            font-size: 0.9rem;
        }

        .footer-content-new>div>a {
            font-size: 0.8rem;
        }

        /* utility.css  */

        .text-center {
            text-align: center;
        }

        .flex {
            display: flex;
            align-items: center;
        }

        .gap-5 {
            gap: 5px;
        }

        .gap-10 {
            gap: 10px;
        }

        .gap-15 {
            gap: 10px;
        }

        .between {
            justify-content: space-between;
        }

        @media screen and (min-width: 1400px) {
            .offer-banner>span {
                font-size: 1.1rem;
            }

            .logo>img {
                width: 40%;
            }

            .dropdown-menu li a {
                font-size: 1.1rem !important;
            }

            #joinDropdown,
            #navbar a,
            .nav_btns>i {
                font-size: 1.1rem !important;
            }

            #nav-right-side>div:last-child>img {
                width: 35px;
            }

            #nav-right-side>div:last-child {
                width: 6%;
            }

            .footer-heading>h3 {
                font-size: 1.75rem;
            }

            .footer-content-new>p {
                font-size: 1.1rem;
            }

            .footer-content-new>div>a {
                font-size: 1.1rem;
            }

            #social-icons>a>i {
                font-size: 1.3rem;
            }

            #social-icons {
                gap: 1.5rem;
            }

            #copyright>p {
                font-size: 1.15rem;
            }

            .footer-content-new>iframe {
                width: 400px !important;
                height: 200px;
            }

            #footer-logo-new>img {
                width: 100%;
                min-width: 225px;
            }

        }

        @media screen and (min-width: 2000px) {
            .offer-banner>span {
                font-size: 1.2rem;
            }

            .logo>img {
                width: 40%;
            }

            .dropdown-menu li a {
                font-size: 1.2rem !important;
            }

            #joinDropdown,
            #navbar a,
            .nav_btns>i {
                font-size: 1.2rem !important;
            }

            #nav-right-side>div:last-child {
                width: 6%;
            }

            #nav-right-side>div:last-child>img {
                width: 65%;
            }

            .footer-heading>h3 {
                font-size: 2rem;
            }

            .footer-content-new>p {
                font-size: 1.3rem;
            }

            .footer-content-new>div>a {
                font-size: 1.3rem;
            }

            #social-icons>a>i {
                font-size: 1.5rem;
            }

            #social-icons {
                gap: 1.3rem;
            }

            #copyright>p {
                font-size: 1.3rem;
            }

            #footer-logo-new>img {
                width: 50%;
            }
        }

        @media screen and (max-width: 1200px) {
            .w-100 {
                height: auto !important;
            }
        }

        @media screen and (max-width: 1024px) {

            #footer-section {
                grid-template-columns: repeat(2, 1fr);
            }

            #navbar a {
                font-size: 12px;
            }

        }

        @media screen and (max-width: 980px) {

            #nav-left-side,
            #nav-right-side {
                display: none;
            }

            .hamburger_container {
                display: block;
            }

        }

        @media screen and (max-width: 768px) {
            #footer-logo-new>img {
                max-width: 400px;
            }

            #footer-section {
                grid-template-columns: 1fr;
            }
        }

        @media screen and (max-width: 480px) {
            .footer-content-new i {
                margin-left: 0 !important;
            }

            .footer-content-new iframe {
                width: 280px !important;
            }
        }

        @media only screen and (max-width: 373px) {
            .logo>img {
                width: 225px;
            }
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
                            $('#loadItemChecoutFinal').append('<div class="card mb-3">' +
                                '<div class="row g-0">' +
                                '<div class="d-flex">' +
                                '<div class="image-wrap-inner">' +
                                '<img src="' + product.product_image +
                                '" class="img-fluid rounded-start" alt="' + product.name +
                                '"/>' +
                                '</div>' +
                                '<div class="card-body">' +
                                '<h5 class="card-title">' + product.name + '</h5>' +
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
                            $('#loadItemChecoutFinal').append('<div class="card mb-3">' +
                                '<div class="row g-0">' +
                                '<div class="d-flex">' +
                                '<div class="image-wrap-inner">' +
                                '<img src="' + product.product_image +
                                '" class="img-fluid rounded-start" alt="' + product.name +
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
                                            '<div class="card mb-3">' +
                                            '<div class="row g-0">' +
                                            '<div class="d-flex">' +
                                            '<div class="image-wrap-inner">' +
                                            '<img src="' + product
                                            .product_image +
                                            '" class="img-fluid rounded-start" alt="' +
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
                                            '<div class="card mb-3">' +
                                            '<div class="row g-0">' +
                                            '<div class="d-flex">' +
                                            '<div class="image-wrap-inner">' +
                                            '<img src="' + product
                                            .product_image +
                                            '" class="img-fluid rounded-start" alt="' +
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

    <header class="py-2">
        <nav>
            <section id="navbar">
                <div class="wrap flex gap-15 between">
                    <div id="nav-logo" class="logo" onclick="window.location.href='{{ url('/') }}'"
                        style="cursor: pointer;">
                        <img src="{{ asset('assets/new_frontend/logo.png') }}" alt="umbrella-logo" />
                    </div>
                    <div class="flex gap-15" id="nav-right-side">
                        <div id="checker" class="d-none">
                            <i class="fa-regular fa-user"></i>
                            <a href="#" class="pe-none">Symptoms Checker</a>
                        </div>

                        @if (Auth::check())
                            <div class="dropdown">
                                <button class="dropdown-toggle w-100" type="button" id="joinDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user-group"></i> Hi {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="joinDropdown">
                                    <li><a class="dropdown-item" href="{{ route('home') }}">Go to Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </ul>
                            </div>
                        @else
                            <div class="dropdown">
                                <button class="dropdown-toggle w-100" type="button" id="joinDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user-group"></i> Join Us
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="joinDropdown">
                                    <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                                    <li><a class="dropdown-item" href="{{ route('doc_register') }}">Register as Doctor</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('pat_register') }}">Register as Patient</a>
                                    </li>
                                </ul>
                            </div>
                        @endif
                        <button class="nav_btns">
                            <i class="fa-brands fa-whatsapp"></i>
                            <a href="https://wa.me/923372350684" target="_blank">0337-2350684</a>
                        </button>
                        <div class="position-relative" onclick="window.location.href='{{ url('/my/cart') }}'">
                            <img src="{{ asset('assets/new_frontend/purchase-icon.svg') }}" alt="shop-icon" />
                            @if (Auth::check())
                                <div class="cart-count">
                                    <span>{{ app('item_count_cart_responsive') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="hamburger_container" onclick="toggleDrawer()">
                        <div class="hamburger">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>

                    <div class="drawer" id="drawer">
                        <span class="close-btn" onclick="toggleDrawer()">×</span>
                        <img width="220px" src="{{ asset('assets/new_frontend/logo.png') }}" alt="" />
                        <hr />
                        <a href="{{ url('/') }}">Home</a>
                        <a href="{{ route('pharmacy') }}">Pharmacy</a>
                        <a href="{{ route('labs') }}">Lab Tests</a>
                        <a href="{{ route('imaging') }}">Imaging</a>
                        <a href="{{ route('e-visit') }}">E-Visit</a>
                        <a href="{{ route('doc_profile_page_list') }}">Our Doctors</a>
                        <a href="{{ route('about_us') }}">About Us</a>
                        <a href="{{ route('contact_us') }}">Contact Us</a>
                        <hr />
                        @if (Auth::check())
                            <a href="{{ route('home') }}">Go to Dashboard</a></li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        @else
                            <a class="drawer-item" href="{{ route('login') }}">Login</a>
                            <a class="drawer-item" href="{{ route('doc_register') }}">Register as Doctor</a>
                            <a class="drawer-item" href="{{ route('pat_register') }}">Register as Patient</a>
                        @endif
                        <a href="https://wa.me/923372350684" target="_blank">0337-2350684</a>
                        <div class="position-relative" onclick="window.location.href='{{ url('/my/cart') }}'">
                            <img src="{{ asset('assets/new_frontend/purchase-icon.svg') }}" alt="shop-icon" />
                            @if (Auth::check())
                                <div class="cart-count">
                                    <span>{{ app('item_count_cart_responsive') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="blur-overlay" id="blurOverlay" onclick="toggleDrawer()"></div>
                </div>
                <div class="flex gap-15" id="nav-left-side">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="{{ route('pharmacy') }}">Pharmacy</a>
                    <a href="{{ route('labs') }}">Lab Tests</a>
                    <a href="{{ route('imaging') }}">Imaging</a>
                    <a href="{{ route('e-visit') }}">E-Visit</a>
                    <a href="{{ route('doc_profile_page_list') }}">Our Doctors</a>
                    <a href="{{ route('about_us') }}">About Us</a>
                    <a href="{{ route('contact_us') }}">Contact Us</a>



                    {{--            <div class="dropdown">
                <a
                  class="dropdown-toggle"
                  href="#"
                  role="button"
                  id="servicesDropdown"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  Services
                </a>
                <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                  <li><a class="dropdown-item" href="{{ route('pharmacy') }}">Pharmacy</a></li>
                  <li><a class="dropdown-item" href="{{ route('labs') }}">Lab Tests</a></li>
                  <li><a class="dropdown-item" href="{{ route('imaging') }}">Imaging</a></li>
                  <li><a class="dropdown-item" href="{{ route('psychiatry',['slug'=>'anxiety']) }}">Psychiatry</a></li>
                  <li>
                    <a class="dropdown-item" href="{{ route('pain.management') }}">Pain Management</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('substance',['slug'=>'first-visit']) }}">Substance Abuse</a>
                  </li>
                </ul>
              </div> --}}
                </div>
            </section>
        </nav>
    </header>

    {{-- after registration and login modal --}}

    <div class="container pt-4">
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
                            <div class="container">
                                <div class="row">
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
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-7 mb-3 px-md-2 p-0 ">
                                        {{-- //////////////////////////////////// --}}
                                        <div class="payment-form-wrap" id="div1" style="display: none;">
                                            <div class="card">
                                                <div class="card-title mx-auto">PAYMENT</div>
                                                <form method="post" id="formWithCard"
                                                    action="{{ route('order.payment') }}">
                                                    @csrf
                                                    <input type="hidden" class="payAble" id="payAble"
                                                        name="payAble" value="{{ $totalPrice }}">
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
                                                                <label for="exampleInputEmail1"
                                                                    class="form-label">Full
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
                                                                    placeholder="Email"
                                                                    aria-describedby="emailHelp" />
                                                            </div>
                                                            <div class="col-md-6 mb-1">
                                                                <label for="exampleInputEmail1"
                                                                    class="form-label">Phone*</label>
                                                                <input required name="shipping_customer_phone"
                                                                    type="text" class="form-control mt-1"
                                                                    placeholder="Phone" />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="ship_city"
                                                                    class="form-label">City*</label>
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
                                                <div class="mb-3 promo_maIN_div">
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
                                            <div class="row mt-4 payment-your-order-wrap">
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
    <footer class="new-footer-cont">
        <section id="footer-section">
            <div id="footer-1" class="footer-new">
                <div class="logo" id="footer-logo-new">
                    <img src="{{ asset('assets/new_frontend/logo.png') }}" alt="umbrella-logo" />
                </div>
                <div class="flex gap-15" id="social-icons">
                    <a href="https://www.facebook.com/share/15m4ofYggZ/" target="_blank"><i
                            class="fa-brands fa-facebook"></i></a>
                    <a href="https://www.linkedin.com/company/community-health-care-clinics/" target="_blank"><i
                            class="fa-brands fa-linkedin"></i></a>
                    <a href="https://www.instagram.com/community_healthcare_clinics?igsh=MXh6aHRzM2NrNThlMw=="
                        target="_blank"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <div id="footer-2" class="footer-new">
                <div class="footer-heading">
                    <h3>Contact Us</h3>
                    <div class="underline"></div>
                </div>
                <div class="footer-content-new">
                    <p class="d-flex align-items-center"><i class="fa-solid mx-2 fa-envelope"></i>
                        <span>contact@communityhealthcareclinics.com</span></p>
                    <p class="d-flex align-items-center"><i class="fa-solid mx-2 fa-envelope"></i>
                        <span>support@communityhealthcareclinics.com</span></p>
                    <p class="d-flex align-items-center"><i class="fa-solid mx-2 fa-location-dot"></i> <span>Progressive
                            Center, 4th Floor Suite#410, Main Shahrah Faisal, Karachi</span></p>

                    <div class="footer-highlight">
                        <i class="fa-solid fa-phone"></i>
                        <a href="tel:+14076938484">+1 (407) 693-8484</a>
                    </div>
                    <div class="footer-highlight">
                        <i class="fa-brands fa-whatsapp"></i>
                        <a href="https://wa.me/923372350684" target="_blank">0337-2350684</a>
                    </div>
                </div>
            </div>
            <div id="footer-3" class="footer-new">
                <div class="footer-heading">
                    <h3>Working Hours</h3>
                    <div class="underline"></div>
                </div>
                <div class="footer-content-new">
                    <p><b>Inclinic:</b> 9am - 9pm</p>
                    <p><b>Online:</b> 24 hours</p>
                    <p>Community Healthcare Clinics</p>
                    <div class="footer-info">
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="{{ route('about_us') }}">About Us</a>
                    </div>
                    <div class="footer-info">
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="{{ route('contact_us') }}">Contact Us</a>
                    </div>
                    <div class="footer-info">
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="{{ route('faq') }}">FAQs</a>
                    </div>
                    {{-- <div class="footer-info">
                    <i class="fa-solid fa-chevron-right"></i>
                    <a href="{{ route('privacy_policy') }}">Privacy Policy</a>
                </div> --}}
                </div>
            </div>
            <div id="footer-4" class="footer-new">
                <div class="footer-heading">
                    <h3>Find Us</h3>
                    <div class="underline"></div>
                </div>

                <div class="footer-content-new">

                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3620.017148282561!2d67.0743981!3d24.8632639!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33f3f9ba7181d%3A0x99571ff4d3fb7e52!2sCommunity%20Health%20Care%20Clinics!5e0!3m2!1sen!2s!4v1734451314564!5m2!1sen!2s"
                        width="300" height="150" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>

                </div>

        </section>

        <div class="seperation"></div>
        <section id="copyright">
            <p>
                Copyright &copy; {{ date('Y') }}.
                <span>Community Healthcare Clinics. All Rights Reserved</span>
            </p>
        </section>
    </footer>

@endsection
