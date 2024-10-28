@extends('video.layout')
@section('css')
<style>
.checked {
    color: orange;
}
</style>
@endsection
@section('content')
<nav class="navbar navbar-expand-sm justify-content-between"
    style=" background:linear-gradient(45deg, #08295a , #5e94e4);height:55px">
    <!-- <a class="navbar-brand " href="#">Video Session
  </a> -->
    <ul class="nav navbar-nav">
        <li>
            <h2 style="color:#fff;font-size:24px;font-weight:bold">Session with Dr. {{$session->doctor_name}}</h2>
        </li>
    </ul>
</nav>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">

        </div>
        <div class="row clearfix">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="card" style="height:550px">
                    <div class="header">
                        <input value="{{$session->id}}" id="session_id" hidden>
                    </div>
                    <div class="card-body">

                        <div class="col-md-12 " style="margin-top:5%">
                            <div class=" text-center align-self-center">
                                <img src="{{ asset('asset_frontend/images/logo.png') }}" width="100" height="110"
                                    alt="header-logo" class="mt-2">
                                <p class="text-center" style="font-size:26px;margin-top:4%">Please Wait! Doctor is
                                    adding products and
                                    services to your cart.</p>
                                    <img src="{{ asset('asset_frontend/images/loader.gif') }}" width="500" height="50"
                                    alt="header-logo" class="mt-2">
                            </div>

                            <div class="offset-4 ">
                                <div class="offset-2 preloader pl-size-xl">
                                    <div class="spinner-layer pl-orange">
                                        <div class="circle-clipper left">
                                            <div class="circle"></div>
                                        </div>
                                        <div class="circle-clipper right">
                                            <div class="circle"></div>
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
@endsection
@section('script')
<script>
<?php header("Access-Control-Allow-Origin: *"); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<script>
$(document).ready(function() {
    session_id = $('#session_id').val();
    check_cart_status(session_id);
})

function check_cart_status(session_id) {
    timer = setInterval(function() {
        $.ajax({
            type: "POST",
            url: "{{URL('/check_cart_status')}}",
            data: {
                session_id: session_id,
            },
            success: function(message) {
                console.log(message);
                if (message == 'added') {
                    window.location = "{{ url('/my/cart')}}";

                }
            }
        });
    }, 3000);
}
</script>
@endsection
