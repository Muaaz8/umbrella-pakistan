@extends('layouts.new_web_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="language" content="en-us">
    <meta name="robots" content="index,follow" />
    <meta name="copyright" content="© 2022 All Rights Reserved. Powered By UmbrellaMd">
    <meta name="url" content="https://www.umbrellamd.com">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.umbrellamd.com" />
    <meta property="og:site_name" content="Umbrella Health Care Systems | Umbrellamd.com" />
    <meta name="twitter:site" content="@umbrellamd	">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="author" content="Umbrellamd">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <style>
        :root {

            --red: #c80919;

            --blue: #2964bc;

            --maroon: #c80919;

            --navy-blue: #082755;

            --green: #35b518;

            --lh: 1.4rem;

        }



        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;

            transition: all 0.3s ease-out;

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

            height: 85px;

            background-color: white;

            position: sticky;

            top: 0;

            z-index: 10;

        }

        .primary-bg {
            background: #0048b1;
        }


        #contact-bar {

            height: 50px;

            background-color: var(--red);

            color: white;

            font-size: small;

        }



        #navbar {

            position: sticky;

            top: 0;

            width: 100%;

            background-color: white;

        }



        #left-side>div>div,

        #right-side>div,

        #social-icons>div {

            display: flex;

            align-items: center;

            justify-content: center;

            border: 2px solid white;

            border-radius: 30px;

            padding: 6px;

        }



        #contact-bar img {

            width: 15px;

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

            justify-content: center;

        }



        .logo>img {

            width: 250px;

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

            padding: 5px;

            font-weight: 600;

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



        .background-secondary {

            background: #f5f5f5;

            height: 100%;

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

            padding: 5px 15px;

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




        footer {
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

        .footer {
            display: flex;
            flex-direction: column;
            gap: 25px;
            align-items: start;
        }

        #footer-1 {
            display: flex;
            justify-content: space-between;
        }

        .footer-content {
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

        .footer-content>h4 {
            font-weight: 400;
            color: gainsboro;
        }

        .footer-content>div>a {
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

        #footer-logo {
            display: block;
            background-color: white;
            border-radius: 5px;
        }

        #footer-logo>img {
            width: 230px;
        }

        #footer-2>.footer-content>h4 {
            text-decoration: 1px solid underline;
        }

        #social-icons {
            display: flex;
            justify-content: space-between;
        }

        #social-icons>div {
            background-color: var(--red);
            border: none;
            padding: 8px;
        }

        #social-icons>div>i {
            font-size: 17px;
        }

        #footer-4>div>h4 {
            font-weight: 500;
            color: white;
            font-size: 18px;
        }

        #copyright>p {
            color: gainsboro;
        }

        #copyright>span {
            font-weight: bold;
        }

        .seperation {
            width: 100%;
            height: 1px;
            background-color: gainsboro;
        }

        #disclaimer {
            background-position: cover center;
            background-repeat: no-repeat;
            background-size: 100vw;
            position: relative;
            min-height: 410px;
            padding-bottom: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .disclaimer-box {
            width: 100%;
            height: 400px;
            position: absolute;
            background-color: white;
            opacity: 0.82;
        }

        #disclaimer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            position: relative;
            z-index: 2;
        }

        #disclaimer-content>div:first-child>.underline {
            background-color: var(--navy-blue);
        }

        #disclaimer-content>div:first-child {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
            font-size: 40px;
            color: var(--red);
        }

        #disclaimer-content>div:first-child>h2 {
            color: var(--red);
            font-size: 2.5rem;
        }

        #disclaimer-content>div:last-child {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        #disclaimer-content>div>p {
            font-size: 16px;
            padding: 0 50px;
            color: var(--navy-blue);
            text-align: justify;
            text-align-last: center;
            font-weight: 400;
        }

        .disclaimer-blob {
            position: absolute;
            left: -300px;
            top: -100px;
            opacity: 0.2;
            width: 700px;
            z-index: -1;
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
            padding-top: 60px;
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
        }

        .drawer hr {
            width: 90%;
            margin: 0 auto;
            border: 1px solid #ff5757;
        }

        .drawer a:hover {
            background-color: #575757;
            color: #ffffff;
        }

        .drawer.active {
            transform: translateX(0);
        }

        .close-btn {
            position: absolute;
            top: 20px;
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

        /* utility.css  */

        .text-center {
            text-align: center;
        }
    </style>
@endsection


@section('page_title')
    <title>Cart | Umbrella Health Care Systems</title>
    <style>
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
                    $('#totalCastFinal').text('$' + res.itemSum);
                    $('#totalPaidFinal').text('$' + res.totalPrice);
                    if (res.providerFee > 0) {
                        $('#final_provider_fee').html('$6.00');
                    } else {

                        $('#final_provider_fee').html('$0.00');
                    }
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
                                '<img src="/uploads/' + product.product_image +
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
                                '<p class="card-text">Qty:1 <span class="float-end pe-3"><b> Price: $' +
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
                                '<img src="/uploads/' + product.product_image +
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
                                '<p class="card-text">Qty:1 <span class="float-end pe-3"><b> Price: $' +
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
                        $('#totalCast').text('$' + itemSum);
                        $('#totalPaid').text('$' + totalPrice);
                        $('.payAble').val(totalPrice);

                        if (typeof res.providerFee == 'undefined') {
                            $('#provider_fee').html('$0.00');

                        } else {
                            $('#provider_fee').html('$' + res.providerFee);
                        }
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
                        $('#totalCast').text('$' + itemSum);
                        $('#totalPaid').text('$' + totalPrice);
                        $('.payAble').val(totalPrice);
                        if (typeof res.providerFee == 'undefined') {
                            $('#provider_fee').html('$0.00');

                        } else {
                            $('#provider_fee').html('$' + res.providerFee);
                        }

                    }
                });
            }
        }

        $(document).ready(function() {
            $('[name="sameBilling"]').change(function() {
                var e = document.getElementById("cardNo");
                var value = e.className;
                // var value = $(e).attr('class');
                if ($('[name="sameBilling"]:checked').is(":checked")) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ URL('/get_card_details') }}",
                        data: {
                            id: value
                        },
                        success: function(response) {
                            if (response.shipping) {
                                $("#shipping_customer_name").val(response.shipping.name);
                                $("#shipping_customer_email").val(response.shipping.email);
                                $("#shipping_customer_phone").val(response.shipping.phone);
                                $("#shipping_customer_zip").val(response.shipping.zip);
                                $("#shipping_customer_state").val(response.shipping.state);
                                $("#shipping_customer_city").val(response.shipping.city);
                                $("#shipping_customer_address").val(response.shipping
                                    .street_address);
                            } else {
                                $("#shipping_customer_name").val(response.billing.name);
                                $("#shipping_customer_email").val(response.billing.email);
                                $("#shipping_customer_phone").val(response.billing.phoneNumber);
                                $("#shipping_customer_zip").val(response.billing.zip);
                                $("#shipping_customer_state").val(response.billing.state);
                                $("#shipping_customer_city").val(response.billing.city);
                                $("#shipping_customer_address").val(response.billing
                                    .street_address);
                            }
                        }
                    });
                    $('.phd').show();
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "{{ URL('/get_card_details') }}",
                        data: {
                            id: value
                        },
                        success: function(response) {
                            $("#shipping_customer_name").val(response.billing.name);
                            $("#shipping_customer_email").val(response.billing.email);
                            $("#shipping_customer_phone").val(response.billing.number);
                            $("#shipping_customer_zip").val(response.billing.zip);
                            $("#shipping_customer_state").val(response.billing.state);
                            $("#shipping_customer_city").val(response.billing.city);
                            $("#shipping_customer_address").val(response.billing
                                .street_address);
                        }
                    });
                    $('.phd').hide();
                }
            });
        });

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
                                $('#totalCastFinal').text('$' + res.itemSum);
                                $('#totalPaidFinal').text('$' + res.totalPrice);
                                if (res.providerFee > 0) {
                                    $('#final_provider_fee').html('$6.00');
                                } else {

                                    $('#final_provider_fee').html('$0.00');
                                }
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
                                            '<img src="/uploads/' + product
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
                                            '<p class="card-text">Qty:1 <span class="float-end pe-3"><b> Price: $' +
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
                                            '<img src="/uploads/' + product
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
                                            '<p class="card-text">Qty:1 <span class="float-end pe-3"><b> Price: $' +
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
    </script>
@endsection

@section('content')

    <!-- header  -->
    <div id="offerBanner" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="offer-banner primary-bg text-white text-center py-2">
                    <span>Get 20% off on your first purchase! Use code: FIRST20</span>
                </div>
            </div>
            <div class="carousel-item">
                <div class="offer-banner primary-bg text-white text-center py-2">
                    <span>Free delivery on orders above $50!</span>
                </div>
            </div>
            <div class="carousel-item">
                <div class="offer-banner primary-bg text-white text-center py-2">
                    <span>Limited Time Offer: Buy 1 Get 1 Free!</span>
                </div>
            </div>
        </div>
    </div>

    <header>
        <nav>
            <section id="navbar">
                <div class="wrap flex gap-15 between">
                    <div id="nav-logo" class="logo">
                        <img src="{{ asset('assets/new_frontend/logo.png') }}" alt="umbrella-logo" />
                    </div>
                    <div class="flex gap-15" id="nav-right-side">
                        {{-- <div id="checker">
                            <i class="fa-regular fa-user"></i>
                            <a href="#" class="pe-none">Symptoms Checker</a>
                        </div> --}}

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
                        <div onclick="window.location.href='{{ url('/my/cart') }}'">
                            <img src="{{ asset('assets/new_frontend/purchase-icon.svg') }}" alt="shop-icon" />
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
                        <a href="{{ route('about_us') }}">About</a>
                        <a href="{{ route('contact_us') }}">Contact <i class="fa-solid fa-phone-flip"></i></a>
                        <hr />
                        <a href="#">Join Us</a>
                    </div>

                    <div class="blur-overlay" id="blurOverlay" onclick="toggleDrawer()"></div>
                </div>
                <div class="flex gap-15" id="nav-left-side">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="{{ route('pharmacy') }}">Pharmacy</a>
                    <a href="{{ route('labs') }}">Lab Tests</a>
                    <a href="{{ route('imaging') }}">Imaging</a>



                    {{--<div class="dropdown">
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
                    <a href="{{ route('e-visit') }}">E-Visit</a>
                    <a href="{{ route('about_us') }}">About</a>
                    <a href="{{ route('contact_us') }}">Contact <i class="fa-solid fa-phone-flip"></i></a>
                </div>
            </section>
        </nav>
    </header>

    <div class="container pt-4">
        <div>
            @if (session()->get('msg'))
                <div id="errorDiv1" class="alert alert-success col-12 col-md-6 offset-md-3 mt-2">
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
                <!-- multistep form -->
                <div id="checkoutform">
                    <!-- progressbar -->
                    <ul id="progressbar">
                        <li class="active">Checkout</li>
                        <li>Payment Details</li>
                    </ul>
                    <!-- fieldsets -->

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
                                                                            <img class="img-fluid"
                                                                                alt="{{ $item->name }}"
                                                                                src="{{ url('/uploads/' . $item->product_image) }}" />
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
                                                                            @if ($item->product_mode == 'lab-test' && $item->item_type == 'counter')
                                                                                <div class="row text-prescribed"> +$6.00
                                                                                    Provider's Fee
                                                                                </div>
                                                                            @endif
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
                                                                            <img class="img-fluid"
                                                                                alt="{{ $item->name }}"
                                                                                src="{{ url('/uploads/' . $item->product_image) }}" />
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
                                                                            @if ($item->product_mode == 'lab-test' && $item->item_type == 'counter')
                                                                                <div class="row text-prescribed"> +$6.00
                                                                                    Provider's Fee
                                                                                </div>
                                                                            @endif
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

                                                        <li class="list-group-item">Provider Fee<span id="provider_fee">

                                                                Rs.{{ number_format($providerFee, 2) ?? '' }}

                                                            </span></li>

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
                                    @if (count($cards) != 0)
                                        <h6>Your saved cards:</h6>
                                        <div class="col-md-7 mb-3 px-md-2 p-0 ">
                                            <div class="col-md-8 mb-2">
                                                @foreach ($cards as $card)
                                                    <div class="d-flex align-items-center mb-2 justify-content-between api_saved_card"
                                                        data-card="{{ $card->id }}" onclick="divClick(this)">
                                                        <div class="d-flex align-items-center">
                                                            @if ($card->card_type == '5')
                                                                <img src="{{ asset('assets/images/master.png') }}"
                                                                    alt="">
                                                            @elseif ($card->card_type == '4')
                                                                <img src="{{ asset('assets/images/visa.png') }}"
                                                                    alt="">
                                                            @elseif ($card->card_type == '3')
                                                                <img src="{{ asset('assets/images/american-express.png') }}"
                                                                    alt="">
                                                            @elseif ($card->card_type == '6')
                                                                <img src="{{ asset('assets/images/discover.png') }}"
                                                                    alt="">
                                                            @endif
                                                            <label for="cardNo" class="ps-3"><b>**** **** ****
                                                                    {{ $card->card_number }}</b></label>
                                                        </div>
                                                        <div class="checkb-round">
                                                            <input type="radio" onclick="radioClick(this)"
                                                                class="{{ $card->id }}" name="card"
                                                                id="cardNo" />
                                                            {{-- <label for="checkbox"></label> --}}
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                            <div class="col-md-12 mb-3 px-md-2 p-0 ">
                                                @if (count($cards) != 0)
                                                    <div class="col-md-4 mb-3">
                                                        <button id="addButton" onclick="addCard()"
                                                            class="btn process-pay" style="width:100%">Add New
                                                            Card</button>
                                                    </div>
                                                @endif
                                                <div class="payment-form-wrap" id="div1" style="display: none;">
                                                    <div class="card">
                                                        <div class="card-title mx-auto">PAYMENT</div>
                                                        <form method="post" id="formWithCard"
                                                            action="{{ route('order.payment') }}">
                                                            @csrf
                                                            <input type="hidden" class="payAble" id="payAble"
                                                                name="payAble" value="{{ $totalPrice }}">
                                                            <div class="row">
                                                                <div class="mb-3 col-md-6">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Card Holder
                                                                        First Name</label>
                                                                    <input type="text"
                                                                        value="{{ Auth::user()->name }}"
                                                                        name="card_holder_name" class="form-control mt-1"
                                                                        id="exampleInputEmail1" placeholder="First Name"
                                                                        aria-describedby="emailHelp" required
                                                                        maxlength="30" />
                                                                </div>
                                                                <div class="mb-3 col-md-6">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Card Holder
                                                                        Last Name</label>
                                                                    <input type="text"
                                                                        value="{{ Auth::user()->last_name }}"
                                                                        name="card_holder_last_name"
                                                                        class="form-control mt-1" id="exampleInputEmail1"
                                                                        placeholder="Last Name" maxlength="30"
                                                                        aria-describedby="emailHelp" required />
                                                                </div>
                                                                <div class="mb-3 col-md-6">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Email</label>
                                                                    <input type="email"
                                                                        value="{{ Auth::user()->email }}" name="email"
                                                                        class="form-control mt-1" id="email"
                                                                        placeholder="Email" aria-describedby="emailHelp"
                                                                        required />
                                                                </div>
                                                                <div class="mb-3 col-md-6">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Phone
                                                                        Number</label>
                                                                    <input type="text"
                                                                        value="{{ Auth::user()->phone_number }}"
                                                                        name="phoneNumber" class="form-control mt-1"
                                                                        id="exampleInputEmail1" placeholder="Phone Number"
                                                                        aria-describedby="emailHelp" maxlength="10"
                                                                        required />
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="exampleInputPassword1" class="form-label">Card
                                                                    Number</label>
                                                                <div id="emailHelp" class="form-text">Enter the 16 digit
                                                                    card
                                                                    number on
                                                                    the card</div>
                                                                <div class="input-group">
                                                                    <span class="input-group-text p-0 card-pic"
                                                                        style="width: 10%; background-color: #c0d1dc38">
                                                                        <img src="{{ asset('assets\images\discover.png') }}"
                                                                            alt="" width="90%" />
                                                                    </span>
                                                                    <input type="text" name="card_number"
                                                                        id="card_num" class="form-control"
                                                                        value="" placeholder="1234234534452324"
                                                                        required maxlength="19"
                                                                        onkeyup="addHyphen(this)" />
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3 align-items-center">
                                                                <div class="col-md-6">
                                                                    <label for="inputPassword6" class="form-label">CVV/CVC
                                                                        Number</label>
                                                                    <p id="passwordHelpInline" class="form-text">Enter the
                                                                        3 or 4
                                                                        digit
                                                                        numbers on card</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="number" name="cvc" value=""
                                                                        id="inputPassword6"
                                                                        onKeyPress="if(this.value.length==4) return false;"
                                                                        class="form-control" placeholder="Enter CVV/CVC"
                                                                        aria-describedby="passwordHelpInline" required />
                                                                </div>
                                                            </div>
                                                            <div class="row align-items-center mb-3">
                                                                <div class="col-md-6">
                                                                    <label for="inputPassword6" class="form-label">Expiry
                                                                        Date</label>
                                                                    <p id="passwordHelpInline" class="form-text">Enter the
                                                                        expiration
                                                                        date of the card</p>
                                                                </div>
                                                                <div class="row col-md-6">
                                                                    <div class="col-5">
                                                                        <input type="number" value="12"
                                                                            name="exp_month" id="inputPassword6"
                                                                            class="form-control"
                                                                            aria-describedby="passwordHelpInline"
                                                                            onKeyPress="if(this.value.length==2) return false;"
                                                                            required />
                                                                    </div>
                                                                    <div class="col-2 text-center m-auto"
                                                                        style="font-size: 2rem">
                                                                        /
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <input type="number" value="{{ date('Y') }}"
                                                                            name="exp_year"
                                                                            onKeyPress="if(this.value.length==4) return false;"
                                                                            id="inputPassword6" class="form-control"
                                                                            aria-describedby="passwordHelpInline"
                                                                            required />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="zipcode" class="form-label">Zip</label>
                                                                    <input type="text" name="zipcode" value=""
                                                                        class="form-control mt-3" id="zipcode"
                                                                        placeholder="Zip Code"
                                                                        aria-describedby="emailHelp" required />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="state_code"
                                                                        class="form-label">State</label>
                                                                    <input type="text" value=""
                                                                        name="state_code" class="form-control mt-3"
                                                                        id="state_code" placeholder="State"
                                                                        aria-describedby="emailHelp" required />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="city" class="form-label">City</label>
                                                                    <input type="text" value="" name="city"
                                                                        class="form-control mt-3" id="city"
                                                                        placeholder="City" aria-describedby="emailHelp"
                                                                        required />
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="exampleInputEmail1"
                                                                    class="form-label">Address</label>
                                                                <input type="text" value="" name="address"
                                                                    class="form-control" id="exampleInputEmail1"
                                                                    maxlength="60" placeholder="Address"
                                                                    aria-describedby="emailHelp" required />
                                                            </div>
                                                            <div class="text-center payment_toggole_form mb-3">
                                                                <h5>SHIPPING ADDRESS FOR MEDICINES</h5>
                                                                <label class="switch">
                                                                    <input type="checkbox" name="sameBilling">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-12 pt-3 phd border-top"
                                                                style="display:none">
                                                                <div class="row">
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">Full
                                                                            Name</label>
                                                                        <input name="shipping_customer_name"
                                                                            type="text" class="form-control mt-3"
                                                                            placeholder="Full Name" />
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">Email</label>
                                                                        <input name="shipping_customer_email"
                                                                            type="text" class="form-control mt-3"
                                                                            id="exampleInputEmail1" placeholder="Email"
                                                                            aria-describedby="emailHelp" />
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">Phone</label>
                                                                        <input name="shipping_customer_phone"
                                                                            type="text" class="form-control mt-3"
                                                                            placeholder="Phone" />
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="zip_code"
                                                                            class="form-label">Zip</label>
                                                                        <input name="shipping_customer_zip" type="text"
                                                                            id="zip_code" class="form-control mt-3"
                                                                            placeholder="Zip Code" />
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="ship_state_code"
                                                                            class="form-label">State</label>
                                                                        <input name="shipping_customer_state"
                                                                            type="text" id="ship_state_code"
                                                                            class="form-control mt-3"
                                                                            placeholder="State" />
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="ship_city"
                                                                            class="form-label">City</label>
                                                                        <input name="shipping_customer_city"
                                                                            type="text" id="ship_city"
                                                                            class="form-control mt-3"
                                                                            placeholder="City" />
                                                                    </div>
                                                                    <div class="col-md-12 mb-3">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">Address</label>
                                                                        <input name="shipping_customer_address"
                                                                            type="text" class="form-control mt-3"
                                                                            placeholder="Address" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="submit" id="final-pay-button"
                                                                class="btn btn-primary pay"> Pay
                                                                Now</button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="payment-form-wrap" id="div2" style="display: none;">
                                                    <div class="card">
                                                        <div class="card-title mx-auto">PAYMENT</div>

                                                        <form method="post" id="formWithCard1"
                                                            action="{{ route('order.payment') }}">
                                                            @csrf
                                                            {{-- <input type="hidden" id="payAble" name="payAble" value="{{ $totalPrice }}"> --}}
                                                            <input type="hidden" name="old_card" value="yes">
                                                            <input type="hidden" name="session_id" value="">
                                                            <input type="hidden" class="payAble" id="payAble"
                                                                name="payAble" value="{{ $totalPrice }}">
                                                            <input type="hidden" name="card_no" id="card_no"
                                                                value="">
                                                            <input type="hidden" id="billing_name"
                                                                name="card_holder_name">
                                                            <input type="hidden" id="billing_last_name"
                                                                name="card_holder_last_name">
                                                            <input type="hidden" id="billing_card_number"
                                                                name="card_number">
                                                            <input type="hidden" id="billing_email" name="email">
                                                            <input type="hidden" id="billing_month" name="exp_month">
                                                            <input type="hidden" id="billing_year" name="exp_year">
                                                            <input type="hidden" id="billing_csc" name="csc">
                                                            <input type="hidden" id="billing_name" name="name">
                                                            <input type="hidden" id="billing_address" name="address">
                                                            <input type="hidden" id="billing_city" name="city">
                                                            <input type="hidden" id="billing_state" name="state_code">
                                                            <input type="hidden" id="billing_zip" name="zipcode">
                                                            <input type="hidden" id="billing_phone" name="phoneNumber">

                                                            <div class="text-center payment_toggole_form mb-3">
                                                                <h5>SHIPPING ADDRESS FOR MEDICINES</h5>
                                                                <label class="switch">
                                                                    <input type="checkbox" id="slider_round"
                                                                        name="sameBilling">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-12 pt-3 phd border-top"
                                                                style="display:none">
                                                                <div class="row">
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">Full
                                                                            Name</label>
                                                                        <input name="shipping_customer_name"
                                                                            type="text" id="shipping_customer_name"
                                                                            class="form-control mt-3"
                                                                            placeholder="Full Name" value="" />
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">Email</label>
                                                                        <input name="shipping_customer_email"
                                                                            type="text" class="form-control mt-3"
                                                                            id="shipping_customer_email"
                                                                            placeholder="Email"
                                                                            aria-describedby="emailHelp" value="" />
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">Phone</label>
                                                                        <input name="shipping_customer_phone"
                                                                            type="text" id="shipping_customer_phone"
                                                                            class="form-control mt-3" placeholder="Phone"
                                                                            value="" />
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="Zip_Code"
                                                                            class="form-label">Zip</label>
                                                                        <input name="shipping_customer_zip" type="text"
                                                                            id="shipping_customer_zip"
                                                                            class="form-control mt-3"
                                                                            placeholder="Zip Code" value="" />
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="State_Code"
                                                                            class="form-label">State</label>
                                                                        <input name="shipping_customer_state"
                                                                            type="text" id="shipping_customer_state"
                                                                            class="form-control mt-3" placeholder="State"
                                                                            value="" />
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="City"
                                                                            class="form-label">City</label>
                                                                        <input name="shipping_customer_city"
                                                                            type="text" id="shipping_customer_city"
                                                                            class="form-control mt-3" placeholder="City"
                                                                            value="" />
                                                                    </div>
                                                                    <div class="col-md-12 mb-3">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">Address</label>
                                                                        <input name="shipping_customer_address"
                                                                            type="text" class="form-control mt-3"
                                                                            placeholder="Address" value=""
                                                                            id="shipping_customer_address" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="submit" id="final-pay-button1"
                                                                class="btn btn-primary pay">
                                                                Pay Now</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-7 mb-3 px-md-2 p-0 ">
                                            @if (count($cards) != 0)
                                                <div class="col-md-4 mb-3">
                                                    <button id="addButton" onclick="addCard()" class="btn process-pay"
                                                        style="width:100%">Add New
                                                        Card</button>
                                                </div>
                                            @endif
                                            <div class="payment-form-wrap" id="div1" style="display: none;">
                                                <div class="card">
                                                    <div class="card-title mx-auto">PAYMENT</div>
                                                    <form method="post" id="formWithCard"
                                                        action="{{ route('order.payment') }}">
                                                        @csrf
                                                        <input type="hidden" class="payAble" id="payAble"
                                                            name="payAble" value="{{ $totalPrice }}">
                                                        <div class="row">
                                                            <div class="mb-3 col-md-6">
                                                                <label for="exampleInputEmail1" class="form-label">Card
                                                                    Holder
                                                                    First Name</label>
                                                                <input type="text" value="{{ Auth::user()->name }}"
                                                                    name="card_holder_name" class="form-control mt-1"
                                                                    id="exampleInputEmail1" placeholder="First Name"
                                                                    aria-describedby="emailHelp" required
                                                                    maxlength="30" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label for="exampleInputEmail1" class="form-label">Card
                                                                    Holder
                                                                    Last Name</label>
                                                                <input type="text"
                                                                    value="{{ Auth::user()->last_name }}"
                                                                    name="card_holder_last_name" class="form-control mt-1"
                                                                    id="exampleInputEmail1" placeholder="Last Name"
                                                                    maxlength="30" aria-describedby="emailHelp"
                                                                    required />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label for="exampleInputEmail1"
                                                                    class="form-label">Email</label>
                                                                <input type="email" value="{{ Auth::user()->email }}"
                                                                    name="email" class="form-control mt-1"
                                                                    id="email" placeholder="Email"
                                                                    aria-describedby="emailHelp" required />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label for="exampleInputEmail1" class="form-label">Phone
                                                                    Number</label>
                                                                <input type="text"
                                                                    value="{{ Auth::user()->phone_number }}"
                                                                    name="phoneNumber" class="form-control mt-1"
                                                                    id="exampleInputEmail1" placeholder="Phone Number"
                                                                    aria-describedby="emailHelp" maxlength="10"
                                                                    required />
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputPassword1" class="form-label">Card
                                                                Number</label>
                                                            <div id="emailHelp" class="form-text">Enter the 16 digit card
                                                                number on
                                                                the card</div>
                                                            <div class="input-group">
                                                                <span class="input-group-text p-0 card-pic"
                                                                    style="width: 10%; background-color: #c0d1dc38">
                                                                    <img src="{{ asset('assets\images\discover.png') }}"
                                                                        alt="" width="90%" />
                                                                </span>
                                                                <input type="text" name="card_number" id="card_num"
                                                                    class="form-control" value=""
                                                                    placeholder="1234234534452324" required maxlength="19"
                                                                    onkeyup="addHyphen(this)" />
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3 align-items-center">
                                                            <div class="col-md-6">
                                                                <label for="inputPassword6" class="form-label">CVV/CVC
                                                                    Number</label>
                                                                <p id="passwordHelpInline" class="form-text">Enter the 3
                                                                    or 4
                                                                    digit
                                                                    numbers on card</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="number" name="cvc" value=""
                                                                    id="inputPassword6"
                                                                    onKeyPress="if(this.value.length==4) return false;"
                                                                    class="form-control" placeholder="Enter CVV/CVC"
                                                                    aria-describedby="passwordHelpInline" required />
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center mb-3">
                                                            <div class="col-md-6">
                                                                <label for="inputPassword6" class="form-label">Expiry
                                                                    Date</label>
                                                                <p id="passwordHelpInline" class="form-text">Enter the
                                                                    expiration
                                                                    date of the card</p>
                                                            </div>
                                                            <div class="row col-md-6">
                                                                <div class="col-5">
                                                                    <input type="number" value="12" name="exp_month"
                                                                        id="inputPassword6" class="form-control"
                                                                        aria-describedby="passwordHelpInline"
                                                                        onKeyPress="if(this.value.length==2) return false;"
                                                                        required />
                                                                </div>
                                                                <div class="col-2 text-center m-auto"
                                                                    style="font-size: 2rem">
                                                                    /
                                                                </div>
                                                                <div class="col-5">
                                                                    <input type="number" value="{{ date('Y') }}"
                                                                        name="exp_year"
                                                                        onKeyPress="if(this.value.length==4) return false;"
                                                                        id="inputPassword6" class="form-control"
                                                                        aria-describedby="passwordHelpInline" required />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="zipcode" class="form-label">Zip</label>
                                                                <input type="text" name="zipcode" value=""
                                                                    class="form-control mt-3" id="zipcode"
                                                                    placeholder="Zip Code" aria-describedby="emailHelp"
                                                                    required />
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="state_code" class="form-label">State</label>
                                                                <input type="text" value="" name="state_code"
                                                                    class="form-control mt-3" id="state_code"
                                                                    placeholder="State" aria-describedby="emailHelp"
                                                                    required />
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="city" class="form-label">City</label>
                                                                <input type="text" value="" name="city"
                                                                    class="form-control mt-3" id="city"
                                                                    placeholder="City" aria-describedby="emailHelp"
                                                                    required />
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="exampleInputEmail1"
                                                                class="form-label">Address</label>
                                                            <input type="text" value="" name="address"
                                                                class="form-control" id="exampleInputEmail1"
                                                                maxlength="60" placeholder="Address"
                                                                aria-describedby="emailHelp" required />
                                                        </div>
                                                        <div class="text-center payment_toggole_form mb-3">
                                                            <h5>SHIPPING ADDRESS FOR MEDICINES</h5>
                                                            <label class="switch">
                                                                <input type="checkbox" name="sameBilling">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                        <div class="col-md-12 pt-3 phd border-top" style="display:none">
                                                            <div class="row">
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Full
                                                                        Name</label>
                                                                    <input name="shipping_customer_name" type="text"
                                                                        class="form-control mt-3"
                                                                        placeholder="Full Name" />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Email</label>
                                                                    <input name="shipping_customer_email" type="text"
                                                                        class="form-control mt-3" id="exampleInputEmail1"
                                                                        placeholder="Email"
                                                                        aria-describedby="emailHelp" />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Phone</label>
                                                                    <input name="shipping_customer_phone" type="text"
                                                                        class="form-control mt-3" placeholder="Phone" />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="zip_code" class="form-label">Zip</label>
                                                                    <input name="shipping_customer_zip" type="text"
                                                                        id="zip_code" class="form-control mt-3"
                                                                        placeholder="Zip Code" />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="ship_state_code"
                                                                        class="form-label">State</label>
                                                                    <input name="shipping_customer_state" type="text"
                                                                        id="ship_state_code" class="form-control mt-3"
                                                                        placeholder="State" />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="ship_city" class="form-label">City</label>
                                                                    <input name="shipping_customer_city" type="text"
                                                                        id="ship_city" class="form-control mt-3"
                                                                        placeholder="City" />
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Address</label>
                                                                    <input name="shipping_customer_address" type="text"
                                                                        class="form-control mt-3" placeholder="Address" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" id="final-pay-button"
                                                            class="btn btn-primary pay"> Pay
                                                            Now</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="payment-form-wrap" id="div2" style="display: none;">
                                                <div class="card">
                                                    <div class="card-title mx-auto">PAYMENT</div>

                                                    <form method="post" id="formWithCard1"
                                                        action="{{ route('order.payment') }}">
                                                        @csrf
                                                        {{-- <input type="hidden" id="payAble" name="payAble" value="{{ $totalPrice }}"> --}}
                                                        <input type="hidden" name="old_card" value="yes">
                                                        <input type="hidden" name="session_id" value="">
                                                        <input type="hidden" class="payAble" id="payAble"
                                                            name="payAble" value="{{ $totalPrice }}">
                                                        <input type="hidden" name="card_no" id="card_no"
                                                            value="">
                                                        <input type="hidden" id="billing_name" name="card_holder_name">
                                                        <input type="hidden" id="billing_last_name"
                                                            name="card_holder_last_name">
                                                        <input type="hidden" id="billing_card_number"
                                                            name="card_number">
                                                        <input type="hidden" id="billing_email" name="email">
                                                        <input type="hidden" id="billing_month" name="exp_month">
                                                        <input type="hidden" id="billing_year" name="exp_year">
                                                        <input type="hidden" id="billing_csc" name="csc">
                                                        <input type="hidden" id="billing_name" name="name">
                                                        <input type="hidden" id="billing_address" name="address">
                                                        <input type="hidden" id="billing_city" name="city">
                                                        <input type="hidden" id="billing_state" name="state_code">
                                                        <input type="hidden" id="billing_zip" name="zipcode">
                                                        <input type="hidden" id="billing_phone" name="phoneNumber">

                                                        <div class="text-center payment_toggole_form mb-3">
                                                            <h5>SHIPPING ADDRESS FOR MEDICINES</h5>
                                                            <label class="switch">
                                                                <input type="checkbox" id="slider_round"
                                                                    name="sameBilling">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                        <div class="col-md-12 pt-3 phd border-top" style="display:none">
                                                            <div class="row">
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Full
                                                                        Name</label>
                                                                    <input name="shipping_customer_name" type="text"
                                                                        id="shipping_customer_name"
                                                                        class="form-control mt-3" placeholder="Full Name"
                                                                        value="" />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Email</label>
                                                                    <input name="shipping_customer_email" type="text"
                                                                        class="form-control mt-3"
                                                                        id="shipping_customer_email" placeholder="Email"
                                                                        aria-describedby="emailHelp" value="" />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Phone</label>
                                                                    <input name="shipping_customer_phone" type="text"
                                                                        id="shipping_customer_phone"
                                                                        class="form-control mt-3" placeholder="Phone"
                                                                        value="" />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="Zip_Code" class="form-label">Zip</label>
                                                                    <input name="shipping_customer_zip" type="text"
                                                                        id="shipping_customer_zip"
                                                                        class="form-control mt-3" placeholder="Zip Code"
                                                                        value="" />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="State_Code"
                                                                        class="form-label">State</label>
                                                                    <input name="shipping_customer_state" type="text"
                                                                        id="shipping_customer_state"
                                                                        class="form-control mt-3" placeholder="State"
                                                                        value="" />
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="City" class="form-label">City</label>
                                                                    <input name="shipping_customer_city" type="text"
                                                                        id="shipping_customer_city"
                                                                        class="form-control mt-3" placeholder="City"
                                                                        value="" />
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">Address</label>
                                                                    <input name="shipping_customer_address" type="text"
                                                                        class="form-control mt-3" placeholder="Address"
                                                                        value="" id="shipping_customer_address" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" id="final-pay-button1"
                                                            class="btn btn-primary pay">
                                                            Pay Now</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                                                    <small id="promo_success" class="text-success"
                                                        style="display: none">
                                                        Promo Code Added Successfully. </small>
                                                    <small id="promo_danger" class="text-danger"
                                                        style="display: none">
                                                        Promo Code Expired. </small>
                                                    <small id="promo_added" class="text-success"
                                                        style="display: none">
                                                        Promo Code Already Applied. </small>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header">Order Summary</div>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item">
                                                            Provider Fee <span id="final_provider_fee"></span>
                                                        </li>
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
                                                                data-bs-target="#flush-collapseOne"
                                                                aria-expanded="false" aria-controls="flush-collapseOne">
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
    <footer>
        <section id="footer-section">
            <div id="footer-1" class="footer">
                <div class="logo" id="footer-logo">
                    <img src="{{ asset('assets/new_frontend/logo.png') }}" alt="umbrella-logo" />
                </div>
                <div class="flex gap-15" id="social-icons">
                    <div><i class="fa-brands fa-facebook"></i></div>
                    <div><i class="fa-brands fa-linkedin"></i></div>
                    <div><i class="fa-brands fa-instagram"></i></div>
                    <div><i class="fa-brands fa-pinterest"></i></div>
                </div>
            </div>
            <div id="footer-2" class="footer">
                <div class="footer-heading">
                    <h3>Contact Us</h3>
                    <div class="underline"></div>
                </div>
                <div class="footer-content">
                    <div class="footer-highlight">
                        <i class="fa-solid fa-location-dot"></i>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#locationModal">Find Location</a>
                    </div>
                    <p>contact@communityhealthcareclinics.com</p>
                    <p>support@communityhealthcareclinics.com</p>
                    <p>Progressive Center, Main Shahrah Faisal, Karachi</p>
                </div>
            </div>
            <div id="footer-3" class="footer">
                <div class="footer-heading">
                    <h3>Working Hours</h3>
                    <div class="underline"></div>
                </div>
                <div class="footer-content">
                    <p>07:00 am - 08:00 pm</p>
                    <p>Community Health Care Clinics</p>
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
                    <div class="footer-info">
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="{{ route('privacy_policy') }}">Privacy Policy</a>
                    </div>
                </div>
            </div>
            <div id="footer-4" class="footer">
                <div class="footer-heading">
                    <h3>Emergency Contact</h3>
                    <div class="underline"></div>
                </div>
                <div class="footer-content">
                    <div class="footer-highlight">
                        <i class="fa-brands fa-whatsapp"></i>
                        <a href="">0337-2350684</a>
                    </div>
                </div>
            </div>
        </section>
        <div class="seperation"></div>
        <section id="copyright">
            <p>
                Copyright &copy; {{ date('Y') }}.
                <span>Community Health Care Clinics. All Rights Reserved</span>
            </p>
        </section>
    </footer>

@endsection
