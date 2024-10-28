<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    @php
    header("Access-Control-Allow-Origin: *");
    @endphp
    <script>
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        Echo.channel('patient_redirect_to_cart')
            .listen('redirectToCart', (e) => {
                if (e.session_id == "{{ $session->id }}") {
                    window.location.href = '/my/cart';
                }
            });
    </script>


    <title>Waiting Patient</title>
</head>
<style>
    .sess-title {
        background: linear-gradient(to top, rgb(8, 41, 90), rgb(22, 93, 200));
        color: white;
        padding: 9px;
        border-radius: 20px;
        font-size: 16px;
        width: 60%;
        margin: 10px auto;
    }
</style>

<body>

    <section>
        <div class="container">
            <div class="row">
                <div class="text-center">
                    <h3 class="sess-title">Session with Dr.<b>{{ $session->doctor_name }} </b> Ended</h3>
                    <div>
                        <input type="hidden" id="session_id" value="{{ $session->id }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="" style="width:10% ; margin:20px 0;">
                    </div>
                    <div class="mt-2">
                        <h3>Please Wait!</h3>
                        <h4>Doctor is finalizing your prescription and adding services to your cart</h4>
                        <h6>( Estimated time <b>5 Minutes</b> )</h6>
                    </div>
                    <div>
                        <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_iXKIMU.json"
                            background="transparent" loop speed="1" style="width: 200px; height: 270px; margin: auto;"
                            autoplay></lottie-player>
                    </div>
                    <div>
                        <p><b>Attention : </b><span class="text-danger">Please don't refresh this page </span> </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
