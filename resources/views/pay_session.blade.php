@extends('layouts.admin')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Credit Card Payment</h2>

        </div>
        <div class="row clearfix">
            <div class="col-md-8 offset-md-2 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header mx-0">
                        <h2>
                            <center>Payment Details</center>
                        </h2>
                        <div class="col-md-12 clearfix mt-2 p-0">
                            <div class="border border-dark  col-lg-8 offset-lg-2">
                                <img style="height:100px; width:100%;"
                                    src="{{asset('images/payment_card.png')}}">
                            </div>


                        </div>
                    </div>
                    @if(session()->get('message'))
                    <div class="alert alert-danger col-12 col-md-6 offset-md-3">
                        @php
                            $es=session()->get('message');
                        @endphp

                        <span class="invalid-feedback" role="alert"> <strong>{{ $es }}</strong></span>


                    </div>
                @endif
                    @if(session()->get('error_message'))
                        <div class="alert alert-danger col-12 col-md-6 offset-md-3">
                            @php
                                $es=session()->get('error_message');
                            @endphp
                            @foreach ($es as $e)
                            <span class="invalid-feedback" role="alert"> <strong>{{ $e }}</strong></span>

                            @endforeach
                        </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger col-12 col-md-6 offset-md-3">
                        @foreach ($errors->all() as $error)
                        <span class="invalid-feedback" role="alert"> <strong>{{ $error }}</strong></span>
                        @endforeach
                    </div>
                @endif
                    <div class="card-block">
                        <form action="{{route('session_payment')}}" method="post" id="payment-form">
                            @csrf

                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>Card Holder Name</label>
                                </div>
                                <div class="col-md-9">
                                    <input id="name" type="text" name="card_holder_name" required class="form-control m-0"
                                        placeholder="Card Holder Name" maxlength="25" value="{{ old('card_holder_name') ?? 'Baqir' }}" required/>


                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>Middle Name(Optional)</label>
                                </div>
                                <div class="col-md-9">
                                    <input id="card" type="text"  value="{{ old('card_holder_name_middle') ?? 'Raza' }}" name="card_holder_name_middle" class="form-control m-0" placeholder="Middle Name(Optional)" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>Card Number</label>
                                </div>
                                <div class="col-md-9">
                                    <input id="card" type="tel" name="card_num" class="form-control m-0"
                                        placeholder="Valid Card Number" maxlength="16" required  value="{{ old('card_num') ?? '5499740000000057' }}" required />
                                    <div class="form-group row error col-md-12 text-align-center mb-2 font-weight-bold">
                                        <span id="card_err" role="alert" class="invalid-feedback err_msg">
                                            Please Enter valid Card Number
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>Expiration Month</label>
                                </div>
                                <div class="col-md-9">
                                    <select id="date" name="month" class="form-control m-0"  required  value="{{ old('exp_date') ?? '12' }}" required >
                                        <option vlaue="01">01</option>
                                        <option vlaue="02">02</option>
                                        <option vlaue="03">03</option>
                                        <option vlaue="04">04</option>
                                        <option vlaue="05">05</option>
                                        <option vlaue="06">06</option>
                                        <option vlaue="07">07</option>
                                        <option vlaue="08">08</option>
                                        <option vlaue="09">09</option>
                                        <option vlaue="10">10</option>
                                        <option vlaue="11">11</option>
                                        <option vlaue="12">12</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>Expiration Year</label>
                                </div>
                                <div class="col-md-9">
                                <select id="date" name="year" class="form-control m-0" placeholder="Expiration Date (MM)" required  value="{{ old('exp_date') ?? '12' }}" required >
                                    @foreach ($years as $year)
                                        <option vlaue="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>


                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>CVC</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="tel" id="cvc" name="cvc" class="form-control m-0" placeholder="Enter CVC" maxlength="4" required value="{{ old('cvc') ?? '998' }}" required/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>Zipcode</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="tel" id="zipcode" name="zipcode" oninput="myFunction()" class="form-control m-0" maxlength="6"
                                        placeholder="Add Zipcode" required value="{{ old('zipcode') ?? '746000' }}" required/>


                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>State</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="state" id="state" class="form-control" value="" autocomplete="state" required>
                                        <option value="" selected disabled>Select State</option>
                                        @foreach($states as $state)
                                        <option value="{{$state->id}}"
                                            {{ old('state') == $state->id ? 'selected' : '' }}>
                                            {{$state->name}}</option>
                                        @endforeach
                                    </select>


                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>City</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="city" id="city"
                                        class="form-control"
                                        value="{{ old('City') ?? 'Spokane' }}" autocomplete="city" required>
                                        <option value="" selected >Select City</option>
                                    </select>


                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>Billing Address</label>
                                </div>
                                <div class="col-md-9">
                                    <input  type="text" id="address" name="address" class="form-control m-0"
                                        placeholder="Billing Address" required value="{{ old('address') ?? 'Morbi faucibus nulla id cursus tempor. Integer iaculis, odio a fermentum porttitor, lacus risus rhoncus metus, in pharetra sem eros a velit.' }}" required/>


                                </div>
                            </div>
                            <div class="form-group row pt-2">
                                <div class="col-md-3">
                                    <label><b>Total Amount</b></label>
                                </div>
                                <div class="col-md-9">
                                    <b>Rs. {{ $price }}</b>
                                </div>
                            </div>
                            <!-- <center> -->
                            {{-- production change apply here change amount charge--}}
                            <input type="hidden" name="session_id" value="{{ old('session_id') ?? $session_id }}">
                            <input type="hidden" name="amount_charge" value="{{ old('amount_charge') ?? $price }}">
                            <input type="hidden" name="subject" value="{{ old('subject') ?? 'Session' }}">

                            <!-- </center> -->

                            <button type="submit" id="pay_submit" class="btn btn-warning btn-raised col-md-12 ">Process Payment</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('script')
<script>
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

<?php header("Access-Control-Allow-Origin: *"); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

document.getElementById("pay_submit").onclick = function() {
    this.disabled = true;
}
</script>
<script src="{{ asset('asset_frontend/js/register.js')}}"></script>
<script src= "{{ asset('asset_admin/js/payment.js')}}"></script>
@endsection
