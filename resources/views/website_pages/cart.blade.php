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
@endsection


@section('page_title')
<title>Cart | Umbrella Health Care Systems</title>
@endsection

@section('top_import_file')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
    integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection


@section('bottom_import_file')
<script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>
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

    $(".next").click(function () {
        $('#loadItemChecoutFinal').text('');
        $.ajax({
            type: 'POST',
            url: "{{route('show_product_on_final_checkout')}}",
            success: function (res) {
                $('#totalCastFinal').text('$' + res.itemSum);
                $('#totalPaidFinal').text('$' + res.totalPrice);

                if (res.providerFee > 0) {
                    $('#final_provider_fee').html('$6.00');
                }
                else {

                    $('#final_provider_fee').html('$0.00');
                }
                var showShipping = 0;
                $.each(res.allProducts, function (key, product) {
                    if (product.product_mode == "medicine") {
                        showShipping += 1;
                    }
                    if (product.item_type == 'prescribed') {
                        $('#loadItemChecoutFinal').append('<div class="card mb-3">' +
                            '<div class="row g-0">' +
                            '<div class="d-flex">' +
                            '<div class="image-wrap-inner">' +
                            '<img src="/uploads/' + product.product_image + '" class="img-fluid rounded-start" alt="' + product.name + '"/>' +
                            '</div>' +
                            '<div class="card-body">' +
                            '<h5 class="card-title">' + product.name + '</h5>' +
                            '<p class="card-text">Qty:1 <span class="float-end pe-3"><b> Price: $' + product.update_price + '</b></span></p>' +
                            '<p class="card-text">Priscribed by Dr.' + product.prescribed + '</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    } else {
                        $('#loadItemChecoutFinal').append('<div class="card mb-3">' +
                            '<div class="row g-0">' +
                            '<div class="d-flex">' +
                            '<div class="image-wrap-inner">' +
                            '<img src="/uploads/' + product.product_image + '" class="img-fluid rounded-start" alt="' + product.name + '"/>' +
                            '</div>' +
                            '<div class="card-body">' +
                            '<h5 class="card-title">' + product.name + '</h5>' +
                            '<p class="card-text">Qty:1 <span class="float-end pe-3"><b> Price: $' + product.update_price + '</b></span></p>' +
                            '<p class="card-text">Counter Purchased</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    }

                });
                if (showShipping >= 0) {
                    $('.payment_toggole_form').show();
                }
                else {
                    $('.payment_toggole_form').hide();
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
        current_fs.animate(
            { opacity: 0 },
            {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = now * 50 + "%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({ transform: "scale(" + scale + ")" });
                    next_fs.css({ left: left, opacity: opacity });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: "easeInOutBack",
            }
        );
    });


    $(".previous").click(function () {
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
        current_fs.animate(
            { opacity: 0 },
            {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = (1 - now) * 50 + "%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({ left: left });
                    previous_fs.css({
                        transform: "scale(" + scale + ")",
                        opacity: opacity,
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: "easeInOutBack",
            }
        );
    });


    function checkboxFunction(a) {

        var classes = $(a).parent().parent().parent().parent().parent().attr('class');
        var counter = classes.split('_');
        if ($('#' + classes).is(':checked')) {
            $('.heading_' + counter[1]).css('color', 'white');
            $('.price_' + counter[1]).css('color', 'white');
            $('#' + classes).prop("checked", true);
            $('.' + classes).css('background-color', '#08295a');
            var cartitemid = $('#cartitemid_' + counter[1]).val();

            $.ajax({
                type: 'POST',
                url: "{{route('show_product_on_checkout')}}",
                data: {
                    item_id: cartitemid,
                },
                success: function (res) {
                    var countItem = res.countItem;
                    var itemSum = res.itemSum;
                    var totalPrice = res.totalPrice;
                    $('#totalItem').text(countItem);
                    $('#totalCast').text('$' + itemSum);
                    $('#totalPaid').text('$' + totalPrice);
                    $('#payAble').val(totalPrice);

                    if (typeof res.providerFee == 'undefined') {
                        $('#provider_fee').html('$0.00');

                    } else {
                        $('#provider_fee').html('$' + res.providerFee);
                    }
                }
            });
        }
        else {
            $('.heading_' + counter[1]).css('color', '#333');
            $('.price_' + counter[1]).css('color', '#333');
            $('#' + classes).prop("checked", false);
            $('.' + classes).css('background-color', 'white');
            var cartitemid = $('#cartitemid_' + counter[1]).val();
            $.ajax({
                type: 'POST',
                url: "{{route('remove_product_on_checkout')}}",
                data: {
                    item_id: cartitemid,
                },
                success: function (res) {
                    var countItem = res.countItem;
                    var itemSum = res.itemSum;
                    var totalPrice = res.totalPrice;
                    $('#totalItem').text(countItem);
                    $('#totalCast').text('$' + itemSum);
                    $('#totalPaid').text('$' + totalPrice);
                    $('#payAble').val(totalPrice);
                    if (typeof res.providerFee == 'undefined') {
                        $('#provider_fee').html('$0.00');

                    } else {
                        $('#provider_fee').html('$' + res.providerFee);
                    }

                }
            });
        }
    }

    $(document).ready(function () {
        $('[name="sameBilling"]').change(function () {
            if ($('[name="sameBilling"]:checked').is(":checked")) {
                $('.phd').show();
            }
            else {
                $('.phd').hide();
            }
        });
    });

</script>
@endsection

@section('content')
<div class="container">
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
                                            $counter=0;
                                            @endphp
                                            @forelse ($user_cart_items as $item)
                                            @if($item->show_product==1)
                                            <li class="item_{{ $counter }}" style="background-color:#08295a;">
                                                <input type="hidden" id="cartitemid_{{ $counter }}"
                                                    value="{{ $item->id }}">
                                                <div class="row">
                                                    <div class="row main align-items-center" style="position: relative">
                                                        <div class="col-2 cart-img-div">
                                                            <img class="img-fluid" alt="{{ $item->name }}"
                                                                src="{{ url('/uploads/' . $item->product_image) }}" />
                                                            @if($item->product_mode == 'medicine')
                                                            <h6 class="item-tag-name tag-name-pharmacy">Pharmacy</h6>
                                                            @elseif($item->product_mode == 'lab-test')
                                                            <h6 class="item-tag-name tag-name-lab">Lab Test</h6>
                                                            @elseif($item->product_mode == 'imaging')
                                                            <h6 class="item-tag-name tag-name-imaging">Imaging</h6>
                                                            @endif
                                                        </div>
                                                        <div class="col">
                                                            <div class="row med-name heading_{{ $counter }}"
                                                                style="color:white;">{{ $item->name }}</div>
                                                            @if ($item->item_type == 'prescribed')
                                                            @if (!empty($item->medicine_usage) && $item->product_mode !=
                                                            'lab-test')
                                                            <div class="row text-prescribed">{{ $item->medicine_usage }}
                                                            </div>
                                                            @endif
                                                            @isset($item->prescribed_by)
                                                            <div class="row text-prescribed">Prescribed: {{
                                                                $item->prescribed_by }} - {{
                                                                Carbon\Carbon::parse($item->prescription_date)->format('m/d/Y
                                                                h:i A'); }}</div>
                                                            @endisset
                                                            @endif
                                                            @if ($item->product_mode == 'lab-test' &&
                                                            $item->item_type == 'counter')
                                                            <div class="row text-prescribed"> +$6.00 Provider's Fee
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="col text-center">
                                                            @if ($item->item_type == 'prescribed')
                                                            <span class="tag-prescribed">Prescribed</span>
                                                            @else
                                                            <div class="text-center">
                                                                <span class="tag-prescribed">Online</span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="col med-price price_{{ $counter }}"
                                                            style="color: white;">${{ number_format($item->price, 2)
                                                            }}
                                                            <span class="close">
                                                                <input type="checkbox" checked id="item_{{ $counter }}"
                                                                    value="0" onclick="checkboxFunction(this)">
                                                                @if ($item->item_type == 'counter')

                                                                <a
                                                                    href="{{ route('remove_item_from_cart',['id'=>$item->id]) }}"><i
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
                                                    <div class="row main align-items-center" style="position: relative">
                                                        <div class="col-2 cart-img-div">
                                                            <img class="img-fluid" alt="{{ $item->name }}"
                                                                src="{{ url('/uploads/' . $item->product_image) }}" />
                                                            @if ($item->product_mode == 'medicine')
                                                            <h6 class="item-tag-name tag-name-pharmacy">Pharmacy</h6>
                                                            @elseif($item->product_mode == 'lab-test')
                                                            <h6 class="item-tag-name tag-name-lab">Lab Test</h6>
                                                            @elseif($item->product_mode == 'imaging')
                                                            <h6 class="item-tag-name tag-name-imaging">Imaging</h6>
                                                            @endif
                                                        </div>
                                                        <div class="col">
                                                            <div class="row heading_{{ $counter }}">{{ $item->name }}
                                                            </div>
                                                            @if ($item->item_type == 'prescribed')
                                                            @if (!empty($item->medicine_usage) &&
                                                            $item->product_mode != 'lab-test')
                                                            <div class="row text-prescribed">{{ $item->medicine_usage
                                                                }}</div>
                                                            @endif
                                                            @isset($item->prescribed_by)
                                                            <div class="row text-prescribed">Prescribed: {{
                                                                $item->prescribed_by }} - {{
                                                                Carbon\Carbon::parse($item->prescription_date)->format('m/d/Y
                                                                h:i A'); }}</div>
                                                            @endisset
                                                            @endif
                                                            @if ($item->product_mode == 'lab-test' &&
                                                            $item->item_type == 'counter')
                                                            <div class="row text-prescribed"> +$6.00 Provider's Fee
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="col text-center">
                                                            @if ($item->item_type == 'prescribed')
                                                            <span class="tag-prescribed">Prescribed</span>
                                                            @else
                                                            <div class="text-center">
                                                                <span class="tag-prescribed">Online</span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="col price_{{ $counter }}">${{
                                                            number_format($item->price, 2) }}
                                                            <span class="close">
                                                                <input type="checkbox" id="item_{{ $counter }}"
                                                                    value="0" onclick="checkboxFunction(this)">
                                                                @if ($item->item_type == 'counter')
                                                                <a
                                                                    href="{{ route('remove_item_from_cart',['id'=>$item->id]) }}"><i
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
                                            $counter++
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
                                                <li class="list-group-item">Total Item <span id="totalItem">{{
                                                        $countItem }}</span></li>

                                                <li class="list-group-item">Provider Fee<span id="provider_fee">

                                                        ${{ number_format($providerFee,2) ?? '' }}

                                                    </span></li>

                                                <li class="list-group-item">Total Cost <span id="totalCast">${{
                                                        number_format($itemSum,2) }}</span></li>
                                                <li class="list-group-item">To be Paid <span id="totalPaid">${{
                                                        number_format($totalPrice,2) }}</span></li>
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
                                <div class="payment-form-wrap">
                                    <div class="card">
                                        <div class="card-title mx-auto">PAYMENT</div>
                                        <form method="post" action="{{ route('order.done') }}">
                                            @csrf
                                            <input type="hidden" id="payAble" name="payAble" value="{{ $totalPrice }}">
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Card Holder
                                                    Name</label>
                                                <input type="text" value="baqir raza" name="card_holder_name"
                                                    class="form-control mt-1" id="exampleInputEmail1"
                                                    placeholder="Haris Unar" aria-describedby="emailHelp" />
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputPassword1" class="form-label">Card
                                                    Number</label>
                                                <div id="emailHelp" class="form-text">Enter the 16 digit card number on
                                                    the card</div>
                                                <div class="input-group">
                                                    <span class="input-group-text p-0"
                                                        style="width: 10%; background-color: #c0d1dc38">
                                                        <img src="https://download.logo.wine/logo/Mastercard/Mastercard-Logo.wine.png"
                                                            alt="" width="100%" />
                                                    </span>
                                                    <input type="text" name="card_number" class="form-control"
                                                        value="5499740000000057"
                                                        placeholder="1234 - 2345 - 3445 - 2324" />
                                                </div>
                                            </div>
                                            <div class="row mb-3 align-items-center">
                                                <div class="col-md-6">
                                                    <label for="inputPassword6" class="form-label">CVV Number</label>
                                                    <p id="passwordHelpInline" class="form-text">Enter the 4 or 5 digit
                                                        numbers on card</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" name="cvc" value="998" id="inputPassword6"
                                                        class="form-control" aria-describedby="passwordHelpInline" />
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-3">
                                                <div class="col-md-6">
                                                    <label for="inputPassword6" class="form-label">Expiry Date</label>
                                                    <p id="passwordHelpInline" class="form-text">Enter the expiration
                                                        date of the card</p>
                                                </div>
                                                <div class="row col-md-6">
                                                    <div class="col-5">
                                                        <input type="number" value="12" name="exp_month"
                                                            id="inputPassword6" class="form-control"
                                                            aria-describedby="passwordHelpInline" />
                                                    </div>
                                                    <div class="col-2 text-center m-auto" style="font-size: 2rem">/
                                                    </div>
                                                    <div class="col-5">
                                                        <input type="number" value="2022" name="exp_year"
                                                            id="inputPassword6" class="form-control"
                                                            aria-describedby="passwordHelpInline" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Zip</label>
                                                    <input type="number" name="zipcode" value="35242"
                                                        class="form-control mt-3" id="exampleInputEmail1"
                                                        placeholder="Zip Code" aria-describedby="emailHelp" />
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">State</label>
                                                    <input type="text" value="AL" name="state_code"
                                                        class="form-control mt-3" id="exampleInputEmail1"
                                                        placeholder="State" aria-describedby="emailHelp" />
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">City</label>
                                                    <input type="text" value="Huntsville" name="city"
                                                        class="form-control mt-3" id="exampleInputEmail1"
                                                        placeholder="City" aria-describedby="emailHelp" />
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Address</label>
                                                <input type="text" value="abc flat 3 road none" name="address"
                                                    class="form-control" id="exampleInputEmail1" placeholder="Address"
                                                    aria-describedby="emailHelp" />
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
                                                        <label for="exampleInputEmail1" class="form-label">Full
                                                            Name</label>
                                                        <input name="shipping_customer_name" type="text"
                                                            class="form-control mt-3" placeholder="Full Name" />
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">Email</label>
                                                        <input name="shipping_customer_email" type="text"
                                                            class="form-control mt-3" id="exampleInputEmail1"
                                                            placeholder="Email" aria-describedby="emailHelp" />
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">Phone</label>
                                                        <input name="shipping_customer_phone" type="text"
                                                            class="form-control mt-3" placeholder="Phone" />
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">City</label>
                                                        <input name="shipping_customer_city" type="text"
                                                            class="form-control mt-3" placeholder="City" />
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">State</label>
                                                        <input name="shipping_customer_state" type="text"
                                                            class="form-control mt-3" placeholder="State" />
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="exampleInputEmail1" class="form-label">Zip</label>
                                                        <input name="shipping_customer_zip" type="text"
                                                            class="form-control mt-3" placeholder="Zip Code" />
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label for="exampleInputEmail1"
                                                            class="form-label">Address</label>
                                                        <input name="shipping_customer_address" type="text"
                                                            class="form-control mt-3" placeholder="Address" />
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary"> Pay Now</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div>
                                    <div class="row payment-order-summary-wrap">
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
                                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                        aria-expanded="false" aria-controls="flush-collapseOne">
                                                        Your Items
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseOne" class="accordion-collapse collapse show"
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
@endsection
