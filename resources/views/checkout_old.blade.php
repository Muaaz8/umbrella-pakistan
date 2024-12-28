{{-- {{ dd($data['checkoutItems']) }} --}}
@extends('layouts.frontend')
@section('content')
    @php
    $showPharmacy = '';
    $user = Auth::user();
    $checkType = [];
    foreach ($data['checkoutItems'] as $val) {
        if ($val['product_mode'] == 'medicine') {
            array_push($checkType, 1);
        }
    }
    if (in_array(1, $checkType)) {
        $showPharmacy = 1;
    } else {
        $showPharmacy = 0;
    }
    @endphp

    <div class="full-page base-color">

        <div class="container">
            <div class="row mt-5">
                <div class="col-md-8 checkoutErroBox">
                </div>
            </div>
            <form method="post" action="{{ url('/create_order') }}" enctype="multipart/form-data" id="checkoutForm">
                {{ csrf_field() }}
                <div class="row mb-5">
                    <div class="col-md-8">
                        <div class="card p-0 card-border">
                            <div class="row justify-content-center mrow partner-row">
                                <div class="col-12">
                                    <img src="{{ asset('asset_admin/images/visa.png') }}" class="partners-logo" />
                                    <img src="{{ asset('asset_admin/images/paypal.png') }}" class="partners-logo" />
                                    <img src="{{ asset('asset_admin/images/mastercard-logo.png') }}"
                                        class="partners-logo" />
                                    {{-- <img src="{{ asset('asset_admin/images/stripe.png') }}" class="logo-stripe"/> --}}

                                </div>
                            </div>
                            <div class=" mb-4">
                                <h6 class="text-capitalize letter-spacing bg-heading"
                                    style="background-color:#08295a;color:white;font-size:16px; text-transform:uppercase !important">
                                    Contact Information
                                </h6>
                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <div class="mt-3 mr-2"> <input type="text" name="billing[first_name]" required
                                                class="form-control" placeholder="Full Name *"
                                                value="{{ isset($user) ? $user->name : '' }}"> </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <div class="mt-3 mr-2"> <input type="text" name="billing[middle_name]"
                                                class="form-control" placeholder="Middle Name"> </div>
                                    </div> --}}
                                    <div class="col-md-3">
                                        <div class="mt-3 mr-2"> <input type="text" name="billing[last_name]" required
                                                class="form-control" placeholder="Last Name *"
                                                value="{{ isset($user) ? $user->last_name : '' }}"> </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mt-3 mr-2"> <input type="text" name="billing[phone_number]"
                                                required class="form-control" placeholder="Phone"
                                                value="{{ isset($user) ? $user->phone_number : '' }}"> </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mt-3 mr-2"> <input type="email" name="billing[email_address]"
                                                required class="form-control" placeholder="Email *"
                                                value="{{ isset($user) ? $user->email : '' }}"> </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-3 mr-2 mb-1"> <input type="number" name="payment[card_number]"
                                                required class="form-control" placeholder="Card Number"
                                                onkeyup="if(this.value<0){this.value= this.value * -1}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-row">

                                            <div class="mt-3 mr-2">
                                                <input type="month" name="payment[card_expiry]"
                                                    onkeyup="if(this.value<0){this.value= this.value * -1}"
                                                    class="form-control" placeholder="Expiry Date" required>
                                            </div>
                                            <div class="mt-3 mr-2 mb-1">
                                                <input onkeyup="if(this.value<0){this.value= this.value * -1}" type="number"
                                                    name="payment[cvc]" class="form-control" placeholder="CSC" required>

                                                <input type="hidden" name="payment[payment_method]"
                                                    value="online_bank_transfer" class="form-control">
                                                <input type="hidden" name="payment[payment_method_title]"
                                                    value="Direct Bank Transfer / Online Payment" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">

                                    <div class="col-md-3">
                                        <div class="mt-3 mr-2" style=" padding-left: 6px; ">
                                            <input type="hidden" name="billing[state]" required
                                                class="form-control set_state_name_for_billing" value="">
                                            <select class="form-control states_select_for_billing"
                                                name="billing[state_code]" required
                                                onChange="getCitiesByStateForBilling(this.value);">
                                                <option value="" default>Select State</option>
                                                @forelse ($data['countries'] as $state)
                                                    <option value="{{ $state->abbreviation }}">{{ $state->state }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mt-3 mr-2">
                                            <select class="form-control city_select_for_billing" name="billing[city]"
                                                required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mt-3 mr-2">
                                            <input type="text" name="billing[address]" required class="form-control"
                                                placeholder="Address"
                                                value="{{ isset($user) ? $user->office_address : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mt-3 mr-2">
                                            <input type="number" name="billing[zip_code]" required class="form-control"
                                                placeholder="Zip Code" value="{{ isset($user) ? $user->zip_code : '' }}"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                @if ($data['AOEsCount'] > 0)
                                    <div class="row mt-4">
                                        <div class="col-md-12 mb-3">
                                            <h3
                                                class="text-capitalize theme-color bg-heading letter-spacing checkout-pharmacy">
                                                AOES required for Labtest</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            {{-- {{ dd($data['AOEs']['AOECollection']) }} --}}
                                            @foreach ($data['AOEs']['AOECollection'] as $key => $collections)
                                                <div class="col-md-12 pb-4">
                                                    <h4 class="shipping-add" style=" padding: 5px; ">
                                                        * {{ $data['AOEs']['TestsName'][$key] }} </h4>
                                                </div>
                                                @foreach ($collections as $collection)
                                                    <div class="form-group col-md-4 pb-4">
                                                        <label for="">
                                                            <strong>*
                                                                {{ $collection['QuestionLong'] }}</strong></label>
                                                        <input type="text" class="form-control" required
                                                            name="AOEs[{{ $collection['TestCode'] }}][{{ $collection['QuestionLong'] }}|{{ $collection['TestCode'] }}]">
                                                        {{-- name="AOEs[{{ $collection['TestCode'] }}][{{ strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $collection['QuestionLong']), '_'))| $collection['TestCode'] }}]"> --}}
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                @endif


                                @if ($showPharmacy == 1)
                                    {{-- For Pharmacy Location & Shipping --}}
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h3
                                                class="text-capitalize theme-color bg-heading letter-spacing checkout-pharmacy">
                                                Select nearby Pharmacy for Medicines</h3>
                                            <div class="ui toggle pharmacy_toggle checkbox rdio-place">
                                                <input type="checkbox" name="billing[medicines_delivery]">
                                                <label class="med-del">Medicines
                                                    Delivery</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row shipping_bar display">
                                        <div class="col-md-12">
                                            <div class="mt-2 p-0">
                                                <div class=" mb-4" style="position: relative">
                                                    <h3
                                                        class="text-capitalize theme-color bg-heading letter-spacing shipping-add">
                                                        Shipping Address for medicines
                                                    </h3>
                                                    <div class="ui toggle checkbox shipping_box_toggle rdio-place">
                                                        <input type="checkbox" checked name="public">
                                                        <label class="billing-add">Same
                                                            as Billing Address</label>
                                                    </div>
                                                    <div class="ship_hide display">
                                                        <div class="row mt-2">
                                                            <div class="col-md-4">
                                                                <div class="mt-3 mr-2"> <input type="text"
                                                                        name="shipping[full_name]" class="form-control"
                                                                        placeholder="Full Name">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="mt-3 mr-2"> <input type="email"
                                                                        name="shipping[email_address]"
                                                                        class="form-control" placeholder="Email">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="mt-3 mr-2"> <input type="number"
                                                                        name="shipping[phone_number]" class="form-control"
                                                                        placeholder="Phone">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-3">
                                                                <div class="mt-3 mr-2"> <input type="text"
                                                                        name="shipping[address]" class="form-control"
                                                                        placeholder="Address"> </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mt-3 mr-2 pl-2">
                                                                    <input type="hidden" name="shipping[state]"
                                                                        class="form-control set_state_name_for_shipping"
                                                                        value="">
                                                                    <select class="form-control states_select_for_shipping"
                                                                        name="shipping[state_code]"
                                                                        onChange="getCitiesByStateForShipping(this.value);">
                                                                        <option value="" default>Select State</option>
                                                                        @forelse ($data['countries'] as $state)
                                                                            <option value="{{ $state->abbreviation }}">
                                                                                {{ $state->state }}</option>
                                                                        @empty
                                                                        @endforelse
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mt-3 mr-2">
                                                                    <select class="form-control city_select_for_shipping"
                                                                        name="shipping[city]">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mt-3 mr-2"> <input type="number"
                                                                        name="shipping[zip_code]" class="form-control"
                                                                        placeholder="Zip Code"> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row mt-0 mb-4 d-none">
                                        <div class="col-md-6">
                                            <div class="mt-3 mr-2 col-12 p-0 row ml-1">
                                                <input onkeyup="if(this.value<0){this.value= this.value * -1}" type="text"
                                                    name="billing[pharmacy_zipcode]"
                                                    class="form-control col-10 billingPharmacyZipCodeInput Zipcodepharmacy"
                                                    placeholder="Zip Code of Pharmacy">
                                                <button
                                                    class="btn theme-color px-2 col-2 mt-0 mb-0 billingPharmacyZipCodeBtn zipcodebtn"
                                                    style="">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mt-3 mr-2 col-12 p-0 row ml-1">
                                                <div class="form-group pharmacy-location">
                                                    <select class="form-control selectPharmacyNearbyLocation"
                                                        name="billing[pharmacy_nearby_location]">
                                                        <option value="">Select Your Neareast Pharmacy Location</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="map showPharmacyMap">
                                                <div class="pharmacymapbox" id="pharmacyMapBox"></div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- For Pharmacy Location & Shipping --}}
                                @endif

                            </div>
                        </div>

                        <div class="row mt-3 mb-3 disclaimer_row display">
                            <div class="col-md-12">
                                <div class="ui warning message">
                                    <i class="close icon"></i>
                                    <div class="header">
                                        Disclaimer
                                    </div>
                                    <ul class="list">
                                        <li>Delivery time depends on vary area to area.</li>
                                        <li>All orders are processed within 2-4 business days. Orders are not shipped or
                                            delivered on weekends or holidays.</li>
                                        <li>You will receive a Shipment Confirmation email once your order has shipped
                                            containing your tracking number(s). The tracking number will be active
                                            within 24
                                            hours.</li>
                                        <li>We currently do not ship outside the U.S.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 row pr-0 mr-0 checkout">
                            <button type="submit"
                                class="col-12 theme-color mt-3 p-3 white-color heading-text checkout-btn ">CONTINUE
                                TO CHECKOUT</button>

                            <div class="checkout_loader">Loading&#8230;</div>

                        </div>
            </form>
        </div>

        <div class="col-md-4 order-md-2 mb-4 review-order-box">
            <div class="card  p-0 card-border">
                <h6 class="bg-heading theme-color mb-0 justify-content-between checkfont">
                    <span class="check-order">Your Order</span>
                    <span class="badge badge-secondary badge-pill float-right theme-color-invert check-items">
                        {{ count($data['checkoutItems']) }} Items
                    </span>
                    @if ($data['prescribedCount'] > 0)
                        <span onclick="openCartDialogue()" data-toggle="modal" data-target="#dialogueBoxCart"
                            class="badge badge-secondary badge-pill float-right theme-color-invert prescribedcount">
                            <i class="edit icon"></i>
                        </span>
                    @endif
                </h6>
                <ul class="list-group">
                    @foreach ($data['checkoutItems'] as $cart_item)
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div class="item d-flex">
                                <img src="/uploads/{{ $cart_item['product_image'] }}" width="12%" />
                                <div class="ml-3">
                                    <h6 class="my-2 cart-name">{{ $cart_item['name'] }}</h6>
                                    <small class="text-muted">Price:
                                        Rs. {{ number_format($cart_item['price'], 2) }}</small> | <small
                                        class="text-muted">Qty: {{ $cart_item['quantity'] }}</small>
                                </div>
                            </div>
                            <span class="text-muted">Rs. {{ number_format($cart_item['update_price'], 2) }}</span>
                        </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between">
                        <span style="font-size:16px">Total Shipping Tax (USD)</span>
                        <strong class="font-weight-bold" style="font-size:18px">
                            Rs. {{ number_format(200, 2) }}
                        </strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span style="font-size:16px">Total (USD)</span>
                        <strong class="font-weight-bold" style="font-size:18px">
                            Rs. {{ number_format($data['itemTotal'] + 200, 2) }}
                        </strong>
                    </li>
                </ul>
            </div>
            {{-- <div class="ui info message">
                <div class="header">
                    <a target="_blank" href="https://developers.paytrace.com/support/home#14000041397">Testing Data
                        <small>(Click Here)</small></a>
                </div>
                <ul class="list">
                    <strong>
                        <li>Address: 8320 E. West St.</li>
                        <li>City: Spokane</li>
                        <li>State: WA</li>
                        <li>Zip: 85284</li>
                    </strong>
                </ul>
            </div> --}}
            <table class="ui celled table">
                <thead>
                    <tr>
                        <th>Card</th>
                        <th>Number</th>
                        <th>MM/YY</th>
                        <th>CSC</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Visa</td>
                        <td>4012000098765439</td>
                        <td>12/24</td>
                        <td>999</td>
                    </tr>
                    <tr>
                        <td>MasterCard</td>
                        <td>5499740000000057</td>
                        <td>12/24</td>
                        <td>998</td>
                    </tr>
                    <tr>
                        <td>Discover</td>
                        <td>6011000993026909</td>
                        <td>12/24</td>
                        <td>996</td>
                    </tr>
                    <tr>
                        <td>American Express</td>
                        <td>371449635392376</td>
                        <td>12/24</td>
                        <td>9997</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    </div>
@endsection

@section('script')
    <script defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRPRccs93XYIWyD-1I5ygtkzQ_ROCFafU&callback=initMap">
    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('.states_select_for_billing').select2({
                placeholder: 'Select State',
            });
            $('.city_select_for_billing').select2({
                placeholder: 'Select City',
            });
            $('.states_select_for_shipping').select2({
                placeholder: 'Select State',
            });
            $('.city_select_for_shipping').select2({
                placeholder: 'Select City',
            });
        });
    </script>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

    <script type="text/javascript">
        $("#DOB").datetimepicker({
            format: 'd/m/Y',
            timepicker: false,
            maxDate: new Date,
        });

        $('#datetimepicker').datetimepicker({
            timepicker: false,
            format: 'd/m/Y',
            minDate: '+0'
        });

        $('#datetimepicker_2').datetimepicker({
            timepicker: false,
            format: 'd/m/Y',
            minDate: '+0'
        });

        $('.timepicker_2').timepicker({
            timeFormat: 'h:mm p',
            interval: 60,
            minTime: '9:00am',
            maxTime: '9:00pm',
            defaultTime: '11',
            startTime: '09:00',
            dynamic: true,
            dropdown: true,
            scrollbar: false
        });

        $('.timepicker').timepicker({
            timeFormat: 'h:mm p',
            interval: 60,
            minTime: '9:00am',
            maxTime: '9:00pm',
            defaultTime: '11',
            startTime: '09:00',
            dynamic: true,
            dropdown: true,
            scrollbar: false
        });
    </script>
@endsection
