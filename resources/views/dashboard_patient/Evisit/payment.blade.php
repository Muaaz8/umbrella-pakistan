@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Session Payment</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script>
        function addHyphen(element) {
            let ele = document.getElementById(element.id);
            ele = ele.value.split('-').join(''); // Remove dash (-) if mistakenly entered.
            if (ele.length <= 16) {
                let finalVal = ele.match(/.{1,4}/g).join('-');
                document.getElementById(element.id).value = finalVal;
            }
        }
        $(document).ready(function() {
            var dd = "{{ count($cards) }}";
            if (dd == 0) {
                $(".div1").show();
                $('.div2').hide();
            }
            $("#card_num").keyup(function() {
                // $("#card_num").css("background-color", "green");
                var e = $("#card_num").val();
                if (e.substring(0, 1) == 3) {
                    $(".card-pic1").html('');
                    $(".card-pic1").html(
                        "<img src='{{ asset('assets/images/american-express.png') }}'' class='pay-image' alt=''>"
                    );
                } else if (e.substring(0, 1) == 4) {
                    $(".card-pic1").html('');
                    $(".card-pic1").html(
                        "<img src='{{ asset('assets/images/visa.png') }}'' class='pay-image' alt=''>");
                } else if (e.substring(0, 1) == 5) {
                    $(".card-pic1").html('');
                    $(".card-pic1").html(
                        "<img src='{{ asset('assets/images/master.png') }}'' class='pay-image' alt=''>");
                } else if (e.substring(0, 1) == 6) {
                    $(".card-pic1").html('');
                    $(".card-pic1").html(
                        "<img src='{{ asset('assets/images/discover.png') }}'' class='pay-image' alt=''>"
                    );
                } else {
                    $(".card-pic1").html('');
                    $(".card-pic1").html(
                        "<img src='{{ asset('assets/images/master.png') }}'' class='pay-image' alt=''>" +
                        "<img src='{{ asset('assets/images/visa.png') }}'' class='pay-image' alt=''>" +
                        "<img src='{{ asset('assets/images/discover.png') }}'' class='pay-image' alt=''>" +
                        "<img src='{{ asset('assets/images/american-express.png') }}'' class='pay-image' alt=''>"
                    );
                }
            });
        });

        function divClick(e) {
            $("#errorDiv1").hide();
            $("#errorDiv2").hide();
            var value = $(e).data('card');
            var radioBtn = $(e).find('input[type="radio"]');
            radioBtn.prop('checked', true);
            var cfirst = e.id;
            if (value == 0) {
                $('.div2').hide();
                $("#addButton").show()
            } else {
                $(".div1").hide();
                $('.div2').show();
                $("#card_no").attr("value", value);
                if (cfirst.substring(0, 1) == '3') {
                    $(".card-pic").html(
                        "<img src='{{ asset('assets/images/american-express.png') }}'' class='pay-image' alt=''>");
                } else if (cfirst.substring(0, 1) == '4') {
                    $(".card-pic").html("<img src='{{ asset('assets/images/visa.png') }}'' class='pay-image' alt=''>");
                } else if (cfirst.substring(0, 1) == '5') {
                    $(".card-pic").html("<img src='{{ asset('assets/images/master.png') }}'' class='pay-image' alt=''>");
                } else if (cfirst.substring(0, 1) == '6') {
                    $(".card-pic").html("<img src='{{ asset('assets/images/discover.png') }}'' class='pay-image' alt=''>");
                } else {
                    $(".card-pic").html("<img src='{{ asset('assets/images/master.png') }}'' class='pay-image' alt=''>" +
                        "<img src='{{ asset('assets/images/visa.png') }}'' class='pay-image' alt=''>" +
                        "<img src='{{ asset('assets/images/discover.png') }}'' class='pay-image' alt=''>" +
                        "<img src='{{ asset('assets/images/american-express.png') }}'' class='pay-image' alt=''>");
                }
            }
        }
        function radioClick(e) {
            $("#errorDiv1").hide();
            $("#errorDiv2").hide();
            var value = $(e).attr('class');
            var cfirst = e.id;
            if (value == 0) {
                $('.div2').hide();
                $("#addButton").show()
            } else {
                $(".div1").hide();
                $('.div2').show();
                $("#card_no").attr("value", value);
                if (cfirst.substring(0, 1) == '3') {
                    $(".card-pic").html(
                        "<img src='{{ asset('assets/images/american-express.png') }}'' class='pay-image' alt=''>");
                } else if (cfirst.substring(0, 1) == '4') {
                    $(".card-pic").html("<img src='{{ asset('assets/images/visa.png') }}'' class='pay-image' alt=''>");
                } else if (cfirst.substring(0, 1) == '5') {
                    $(".card-pic").html("<img src='{{ asset('assets/images/master.png') }}'' class='pay-image' alt=''>");
                } else if (cfirst.substring(0, 1) == '6') {
                    $(".card-pic").html("<img src='{{ asset('assets/images/discover.png') }}'' class='pay-image' alt=''>");
                } else {
                    $(".card-pic").html("<img src='{{ asset('assets/images/master.png') }}'' class='pay-image' alt=''>" +
                        "<img src='{{ asset('assets/images/visa.png') }}'' class='pay-image' alt=''>" +
                        "<img src='{{ asset('assets/images/discover.png') }}'' class='pay-image' alt=''>" +
                        "<img src='{{ asset('assets/images/american-express.png') }}'' class='pay-image' alt=''>");
                }
            }
        }

        function addCard() {
            var e = document.getElementById("cardNo");
            $("input[name='card']").each(function() {
                if ($(this).val() !== "1") {
                    $(this).prop("checked", false);
                }
            });
            $('.div2').hide();
            $(".div1").show();
            $("#errorDiv1").hide();
            $("#errorDiv2").hide();
        }

        function myFunction() {
            var zipcode = $('#zipcode').val();
            if (zipcode.length >= 5) {
                $.ajax({
                    type: "POST",
                    url: "/getCityStateByZipCode",
                    data: {
                        zip: zipcode,
                    },
                    success: function(data) {
                        $(".checkout_loader").hide();
                        if (data.country_id == "") {
                            $(".zipCodeErrCheckout").show();
                            $(".states_select_for_billing")
                                .empty()
                                .trigger("change");
                            $(".city_select_for_billing").empty().trigger("change");
                        } else {
                            $(".zipCodeErrCheckout").hide();
                            // console.log(data);
                            $.ajax({
                                type: "POST",
                                url: "/get_states_cities",
                                data: {
                                    id: data.state_id,
                                    city_id: data.city_id,
                                },
                                success: function(resp) {
                                    $(".checkout_loader").hide();
                                    if (resp.count > 0) {
                                        $("#state").val(resp.single.id);
                                        $("#city option:selected").text(resp.city.city_name);
                                        $("#city option:selected").val(resp.city.city_id);
                                    } else {
                                        $(".zipCodeErrCheckout").show();
                                    }
                                },
                            });
                        }
                        // console.log(data.country_id+"="+data.state_id+"="+data.city_id);
                    },
                });
            }
        }

        $("#zipcode").keyup(function() {
            var zip = $("#zipcode").val();
            var length = $("#zipcode").val().length;
            if (length >= 5) {
                $('#state').html('<option value="">Select State</option>');
                $('#city').html('<option value="">Select City</option>');
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
                            $('#state').html('<option value="' + data.state_id + '">' + data.state +
                                '</option>');
                            $('#city').html('<option value="' + data.city_id + '">' + data.city +
                                '</option>');
                        }
                        // console.log(data.country_id+"="+data.state_id+"="+data.city_id);
                    },
                });
            }
        });

        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // document.getElementById("pay_submit").onclick = function() {
        //     $(this).prop('disabled', true);
        //     $('button').prop('disabled', true);
        //     $(this).html('<i class="fa fa-spinner fa-spin"></i>Processing...');

        // }
        $('#payment-form').submit(function(event) {
            event.preventDefault(); // prevent default form submission
            var submitBtn = $('#pay_submit');
            submitBtn.prop('disabled', true);
            $('button').prop('disabled', true);
            submitBtn.html('<i class="fa fa-spinner fa-spin"></i>Processing...');
            if (validateForm()) {
                this.submit(); // submit the form
            } else {
                submitBtn.prop('disabled', false);
                $('button').prop('disabled', false);
                submitBtn.html('Submit');
            }
        });

        function validateForm() {
            var valid = true;
            $('#payment-form :input[required]').each(function() {
                if ($.trim($(this).val()) === '') {
                    valid = false;
                    return false; // exit the each loop
                }
            });
            return valid;
        }


        document.getElementById("pay_submit1").onclick = function() {
            $('#pay_submit1').attr('disabled', true);
            $('button').attr('disabled', true);
            $('#pay_submit1').html('<i class="fa fa-spinner fa-spin"></i>Processing...');
            $('#payment-form-old').submit();
        }
    </script>
    <script src="{{ asset('asset_frontend/js/register.js') }}"></script>
    <script src="{{ asset('asset_admin/js/payment.js') }}"></script>
@endsection

@section('content')
    {{-- {{ dd($session_id,$states,$years,$price) }} --}}
    <div class="dashboard-content">
        <div class="col-11 m-auto">

            @if (session()->get('error_message'))
                <div id="errorDiv1" class="alert alert-danger col-12 col-md-6 offset-md-3">
                    @php
                        $es = session()->get('error_message');
                    @endphp
                    <span role="alert"> <strong>{{ $es }}</strong></span>

                </div>
            @endif
            @if ($errors->any())
                <div id="errorDiv2" class="alert alert-danger col-12 col-md-6 offset-md-3">
                    @foreach ($errors->all() as $error)
                        <span role="alert"> <strong>{{ $error }}</strong></span>
                    @endforeach
                </div>
            @endif
            <div class="row">
                <div class="col-md-4">
                    <div class="row my-2">
                        @if (count($cards) != 0)
                            <h6>Please select from one of your previous card for payment:</h6>
                            <h6>Your saved cards:</h6>
                            <div class="col-md-12 mb-3 px-md-2 p-0 ">
                                <div class="col-md-12 mb-2">
                                    @foreach ($cards as $card)
                                        <div class="d-flex align-items-center mb-2 justify-content-between api_saved_card" onclick="divClick(this)" data-card="{{ $card->id }}">
                                            <div class="d-flex align-items-center">
                                                @if ($card->card_type == '5')
                                                    <img src="{{ asset('assets/images/master.png') }}" alt="">
                                                @elseif ($card->card_type == '4')
                                                    <img src="{{ asset('assets/images/visa.png') }}" alt="">
                                                @elseif ($card->card_type == '3')
                                                    <img src="{{ asset('assets/images/american-express.png') }}"
                                                        alt="">
                                                @elseif ($card->card_type == '6')
                                                    <img src="{{ asset('assets/images/discover.png') }}" alt="">
                                                @endif
                                                <p class="ps-3"><b>**** **** ****
                                                        {{ $card->card_number }}</b></p>
                                            </div>
                                            <div class="checkb-round">
                                                <input type="radio" onclick="radioClick(this)"
                                                    class="{{ $card->id }}" name="card"
                                                    id="{{ $card->card_type }}" />
                                                {{-- <label for="checkbox"></label> --}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12 mb-3 px-md-2 p-0 ">
                            @if (count($cards) != 0)
                                <div class="col-md-8 mb-3">
                                    <button id="addButton" onclick="addCard()" class="btn process-pay"
                                        style="width:100%">Add New
                                        Card</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="div2" style="display: none;">
                        <div class="account-setting-wrapper pay-image bg-white">
                            <h4 class="pb-4 border-bottom">Payment Details</h4>
                            <div class="d-flex align-items-center py-3 border-bottom justify-content-between flex-wrap">
                                <div class="d-flex align-items-center card-pic">
                                    {{-- <imgid="AE"src="asset('assets /images/american-express.png') }}" class="pay-image" alt="">
                            <img id="V" src="{{ asset('assets/images/visa.png') }}" class="pay-image" alt="">
                            <img id="M" src="{{ asset('assets/images/master.png') }}" class="pay-image" alt="">
                            <img id="D" src="{{ asset('assets/images/discover.png') }}" class="pay-image" alt=""> --}}
                                </div>
                                <div class="fs-5">
                                    <p class="fw-bold fs-4 text-end">Rs. {{ $price }}</p>
                                    <p class="fw-bold text-nowrap">Total Amount</p>
                                </div>
                            </div>
                            <form action="{{ route('payment_session1') }}" method="post" id="payment-form-old">
                                @csrf
                                <input type="hidden" name="old_card" value="yes">
                                <input type="hidden" name="session_id" value="{{ \Crypt::encrypt($session_id) }}">
                                <input type="hidden" name="amount_charge" value="{{ old('amount_charge') ?? $price }}">
                                <input type="hidden" name="card_no" id="card_no" value="">
                                <div class="py-3 pb-4" id="buttondiv">
                                    <button type="submit" id="pay_submit1" class="btn process-pay mr-3">PROCESS
                                        PAYMENT</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="div1" style="display: none;">
                        <div class="account-setting-wrapper pay-image bg-white" style="padding: 15px 25px;">
                            <h4 class="">Payment Details</h4>
                            <div class="d-flex align-items-center py-3 border-bottom justify-content-between flex-wrap">
                                <div class="d-flex align-items-center card-pic1">
                                    <img src="{{ asset('assets/images/master.png') }}" class="pay-image" alt="">
                                    <img src="{{ asset('assets/images/visa.png') }}" class="pay-image" alt="">
                                    <img src="{{ asset('assets/images/discover.png') }}" class="pay-image" alt="">
                                    <img src="{{ asset('assets/images/american-express.png') }}" class="pay-image"
                                        alt="">
                                </div>
                                <div style="font-size: 17px;">
                                    <p class="fw-bold text-end">Rs. {{ $price }}</p>
                                    <p class="fw-bold text-nowrap">Total Amount</p>
                                </div>
                            </div>
                            <form action="{{ route('payment_session1') }}" method="post" id="payment-form">
                                @csrf
                                <div class="py-2">
                                    <div class="row py-2">
                                        <div class="col-md-6 mb-1">
                                            <label for="cardholdername">Card Holder Name</label>
                                            <input type="text" name="card_holder_name" id="card_holder_name"
                                                class="bg-light form-control" maxlength="30" value=""
                                                placeholder="Enter Card Holder Name" required>
                                        </div>
                                        <div class="col-md-6 pt-md-0 pt-3 mb-1">
                                            <label for="Middlename">Card Holder Last Name</label>
                                            <input type="text" name="card_holder_last_name" id="card_holder_last_name"
                                                class="bg-light form-control" value="" maxlength="30"
                                                placeholder="Enter Card Holder Last Name" required>
                                        </div>
                                        <div class="col-md-4 mb-1">
                                            <label for="email">Email</label>
                                            <input type="text" name="email" id="email"
                                                class="bg-light form-control" value=""
                                                placeholder="Enter Your Email Address" required>
                                        </div>
                                        <div class="col-md-4 mb-1">
                                            <label for="phone">Phone Number</label>
                                            <input type="text" name="phoneNumber" id="phoneNumber" maxlength="10"
                                                class="bg-light form-control" value=""
                                                placeholder="Enter Your Phone" required>
                                        </div>
                                        <div class="col-md-4 pt-md-0 pt-3 mb-1">
                                            <label for="CardNumber">Card Number</label>
                                            <input type="text" name="card_num" id="card_num"
                                                class="bg-light form-control" value=""
                                                placeholder="1234-2345-3445-2324" onkeyup="addHyphen(this)"
                                                maxlength="19" required>
                                        </div>
                                    </div>
                                    <div class="row py-2">
                                        <div class="col-md-4">
                                            <label for="Expirationmonth">Expiration Month</label>
                                            <select class="form-select" name="month" id="month" value=""
                                                required>
                                                <option value="01" selected>Jan</option>
                                                <option value="02">Feb</option>
                                                <option value="03">Mar</option>
                                                <option value="04">Apr</option>
                                                <option value="05">May</option>
                                                <option value="06">June</option>
                                                <option value="07">July</option>
                                                <option value="08">Aug</option>
                                                <option value="09">Sep</option>
                                                <option value="10">Oct</option>
                                                <option value="11">Nov</option>
                                                <option value="12">Dec</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 pt-md-0 pt-3">
                                            <label for="Expirationyear">Expiration Year</label>
                                            <select class="form-select" name="year" id="year" value="2022">
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-md-4 pt-md-0 pt-3">
                                            <label for="cvc">CVV/CVC</label>
                                            <input type="text" name="cvc" id="cvc"
                                                class="bg-light form-control" maxlength="4" value=""
                                                placeholder="Enter CVV/CVC" required>
                                        </div>

                                    </div>
                                    {{--<div class="row py-2">
                                        <div class="col-md-4 pt-md-0 pt-3">
                                            <label for="zipcode">Zipcode</label>
                                            <input type="tel" class="bg-light form-control" id="zipcode"
                                                name="zipcode" oninput="myFunction()" class="form-control m-0"
                                                maxlength="6" placeholder="Add Zipcode" required value=""
                                                required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="state">State</label>
                                            <select name="state" id="state" class="form-control" value=""
                                                autocomplete="state" required>
                                                <option value="" selected disabled>Select State</option>
                                                @foreach ($states as $state)
                                                    <option value="">
                                                        {{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 pt-md-0 pt-3">
                                            <label for="city">City</label>
                                            <select name="city" id="city" class="form-control" value=""
                                                autocomplete="city" required>
                                                <option value="" selected>Select City</option>
                                            </select>
                                        </div>
                                    </div>--}}
                                    <div class="row py-2">
                                        <div class="col-md-12">
                                            <label for="billingaddress">Billing Address</label>
                                            <input type="text" id="address" name="address"
                                                class="bg-light form-control" maxlength="60" value=""
                                                placeholder="Enter Your Address" required>

                                        </div>

                                    </div>

                                    <input type="hidden" name="session_id" value="{{ \Crypt::encrypt($session_id) }}">
                                    <input type="hidden" name="amount_charge"
                                        value="{{ old('amount_charge') ?? $price }}">
                                    <input type="hidden" name="subject" value="{{ old('subject') ?? 'Session' }}">


                                    <div class="py-3 pb-4">
                                        <button type="submit" id="pay_submit" class="btn process-pay mr-3">PROCESS
                                            PAYMENT</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>






        </div>
    </div>

@endsection
