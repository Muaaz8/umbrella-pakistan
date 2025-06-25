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
                if (!$(event.target).closest(".header-search-container") && !$(event.target).closest(".search-btn-mob")) {
                    $(".header-search-container").css("display", "none");
                }
            });

            $('#new-search2').on('input', function () {
            const searchTerm = $(this).val().trim().toLowerCase();

            if (searchTerm.length === 0) {
                $('.header-search-result').empty().hide();
                return;
            }

  $.ajax({
      url: `/search_items/${searchTerm}`,
      type: 'GET',
      dataType: 'json',
      success: function (response) {
          const { products, test_codes } = response;

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
      error: function (error) {
          console.error('Error fetching search results:', error);
      }
  });
});
$('#new-search').on('input', function () {
const searchTerm = $(this).val().trim().toLowerCase();

  if (searchTerm.length === 0) {
      $('.header-search-result').empty().hide();
      return;
  }

  $.ajax({
      url: `/search_items/${searchTerm}`,
      type: 'GET',
      dataType: 'json',
      success: function (response) {
          const { products, test_codes } = response;

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
      error: function (error) {
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
                            $('#loadItemChecoutFinal').append('<div class="card mb-1">' +
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
            $('.payment-order-summary-wrap').css({ display: 'block', opacity: '1' });
            $("#progressbar li").removeClass("active").first().addClass("active");

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
                                            '<div class="card mb-1">' +
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
