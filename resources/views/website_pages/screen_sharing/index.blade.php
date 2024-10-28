@extends('layouts.new_web_login_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    <title>Meeting | Umbrella Health Care Systems</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>
<script>
    <?php header("Access-Control-Allow-Origin: *"); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
  $('#timezone').val(moment.tz.guess())
});

$('#btn_reset_password').click(function(){
    var rest_email=$('#reset_email_input').val();
    var _token=$('#_token').val();
    $.ajax({
        type:"POST",
        url:"{{ route('password.email') }}",
        data: {
            email: rest_email,
            token:_token
        },
        success: function(message) {
            $.each(message.errors, function (key, value) {
                alert(value);
            });

        }
    });
});


</script>
@endsection

@section('content')

<!-- ******* LOGIN STARTS ******** -->
<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 login-page-wrapper">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="col-md-10 col-lg-8 m-auto login-form-wrapper">
              <button class="login-back" onclick="history.back()">
                <i class="fa-solid fa-arrow-left"></i>
              </button>
              <!-- <a href="{{ url('/') }}"> -->
              <!-- <button class="login-back" onclick="history.back()" style="float:right">
              <i class="fa-solid fa-house"></i>
              </button> -->
            <!-- </a> -->
              <div class="text-center">
                <img src="{{ asset('assets/images/logo.png') }}" alt="" width="30%" />
              </div>
              <h1 class="text-center py-3">Create meeting and set password</h1>

              <form action="/create/screen_sharing" method="POST">
                @csrf
                <div class="mb-3 login-inp">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                  <input type="hidden" id="timezone" name="timezone" value=""/>
                  <label for="email">Channel</label>
                  <input
                    type="text"
                    placeholder="Enter Channel"
                    id="channel"
                    class="form-control"
                    name="channel"
                    value=""
                    placeholder="Channel"
                    class="form-control"
                    id="exampleInputEmail1"
                    aria-describedby="emailHelp"
                    required
                  />
                </div>



                <div class="mb-3 login-inp abc">
                  <label for="password"
                    >Password</label
                  >
                  <small>Error Message</small>
                  <input
                    type="text"
                    name="password"
                    placeholder="Password"
                    class="form-control"
                    id="password"
                  />
                  <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                </div>

                <button class="btn btn-primary login-btn">
                  Create Meeting
                </button>
                <div class="mb-3 form-check login-remb-div">
                </div>
              </form>
              <div class="login-or-sec">
              </div>
            </div>
          </div>
          <!-- <div class="col-md-6 p-0">
            <div class="login-image">
              <img
                src="{{ asset('assets/images/Home-Screen-image.jpg')}}"
                alt=""
              />
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ******* LOGIN ENDS ******** -->


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="loginModalLabel">Select Registration Type</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
     <div class="modal-login-reg-btn my-3">
       <a href="{{ route('pat_register') }}"> REGISTER AS A PATIENT</a>
       <a href="{{ route('doc_register') }}">REGISTER AS A DOCTOR </a>
      </div>
      <div class="login-or-sec">
        <hr />
        OR
        <hr />
      </div>
      <div>
        <p>Already have account?</p>
        <a href="{{ route('login') }}">Login</a>
      </div>
    </div>
  </div>
</div>
</div>
<!-- Modal - end -->



@endsection
