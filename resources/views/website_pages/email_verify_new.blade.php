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
<meta name="twitter:site" content="@umbrellamd	">
<meta name="twitter:card" content="summary_large_image" />
<meta name="author" content="Umbrellamd">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
<style>
    #resendMail {
        border: 2px solid var(--green);
        border-radius: 30px;
        background: #ffff;
        padding: 3px 15px;
    }

    #verifyOtp {
        border: 2px solid var(--navy-blue);
        border-radius: 30px;
        background: #ffff;
        padding: 3px 15px;
    }
    .otp-box {
        width: 3rem;
        height: 3rem;
        text-align: center;
        font-size: 1.5rem;
        border: 2px solid #ccc;
        border-radius: 0.5rem;
        outline: none;
        transition: border-color 0.3s;
    }

    .otp-box:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

</style>
@endsection


@section('page_title')
<title>Email Verfication</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<script>
    document.getElementById("resendMail").onclick = function() {
        $('#resendMail').attr('disabled', true);
        $('#form_send').submit();
    }

    document.getElementById("resendMail").disabled = true;
    var emailCounter=60;
    var emailResendInterval=setInterval(() => {
    emailCounter--;

    document.getElementById("resendMail").disabled = true;
        if(emailCounter<1)
          {
            clearInterval(emailResendInterval);

            $("#emailCounter").hide();
            document.getElementById("resendMail").disabled = false;

          }else{
            $('#emailCounter').html('('+emailCounter+")");
          }
    }, 1000);

    document.querySelectorAll('.otp-box').forEach((input, index, inputs) => {
    input.addEventListener('input', (e) => {
        if (e.target.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        } else if (e.target.value.length === 0 && index > 0) {
            inputs[index - 1].focus();
        }
        updateOtpValue(inputs);
    });

    input.addEventListener('paste', (e) => {
        e.preventDefault();
        const pastedData = e.clipboardData.getData('text').slice(0, inputs.length);
        pastedData.split('').forEach((char, i) => {
            if (inputs[i]) {
                inputs[i].value = char;
            }
        });
        inputs[Math.min(pastedData.length, inputs.length) - 1]?.focus();
        updateOtpValue(inputs);
    });
});

function updateOtpValue(inputs) {
    const otp = Array.from(inputs).map(input => input.value).join('');
    document.getElementById('otp').value = otp;
}



</script>
@endsection

@section('content')

<!-- ******* 404-ERROR STATRS ******** -->

<section class="Email-confirm-sec">
    <div class="container">
        {{-- <div class="row my-5">
            <div class="col-md-12 Email-confirm-wrap text-center">
                <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_1jbmcfmf.json"
                    background="transparent" speed="1"
                    style="width: auto; height: 200px;margin-bottom: -70px;    margin-top: -40px; " loop autoplay>
                </lottie-player>
                <h1>Email Verification</h1>
                @if(Session::has('message'))
                <div class="alert alert-success">
                    {{ Session::get('message')}}
                </div>
                @endif
                <p>Please verify your email address to accesss Community Healthcare Clinics web portal.</p>
                <div>
                    <p>If you did not get it.</p>
                    <form action="{{ route('resend') }}" id="form_send" method="POST">
                        @csrf
                        <button type="submit" id="resendMail"><span id="emailCounter"></span> Resend Email</button>
                    </form>
                </div>
            </div>
        </div> --}}

        <div class="row my-5">
            <div class="col-md-12 Email-confirm-wrap text-center">
                <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_1jbmcfmf.json"
                    background="transparent" speed="1"
                    style="width: auto; height: 200px;margin-bottom: -70px;    margin-top: -40px; " loop autoplay>
                </lottie-player>
                <h1>Email Verification</h1>
                @if(Session::has('message'))
                    <div class="alert alert-success">
                        {{ Session::get('message')}}
                    </div>
                @endif
                <p class="text-gray-700">Please verify your email address to access the Community Healthcare Clinics web portal.</p>
                @if(Session::has('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error')}}
                    </div>
                @endif

                <div class="d-flex justify-content-center align-items-center my-6 space-x-2 my-2">
                    <input name="num_1" type="text" maxlength="1" class="otp-box" />
                    <input name="num_2" type="text" maxlength="1" class="otp-box" />
                    <input name="num_3" type="text" maxlength="1" class="otp-box" />
                    <input name="num_4" type="text" maxlength="1" class="otp-box" />
                    <input name="num_5" type="text" maxlength="1" class="otp-box" />
                    <input name="num_6" type="text" maxlength="1" class="otp-box" />
                </div>
                <form action="{{ route('otp_verification') }}" method="POST">
                    @csrf
                    <input type="hidden" name="otp" id="otp">
                    <input type="hidden" name="user_type" value="{{ Auth::user()->user_type }}">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <button class="pointer w-50" id="verifyOtp">
                        Verify Email
                    </button>
                </form>

                <div class="mt-5">
                    <p>If you did not get it, click below to resend:</p>
                    <form action="{{ route('resend') }}" id="form_send" method="POST">
                        @csrf
                        <button type="submit" id="resendMail"><span id="emailCounter"></span> Resend Email</button>
                    </form>
                </div>
            </div>
        </div>


    </div>
</section>


<!-- ******* 404-ERROR ENDS ******** -->

@endsection
