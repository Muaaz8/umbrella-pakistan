

@extends('layouts.new_web_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    <title>UHCS - Reset Password</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')


<!-- forgot - Password - Modal -->

  <div class="container">
    <div class="forgot-pass-modal">
     <div class="row align-items-center my-4 my-sm-0">
    		<div class="col-12 col-md-6 text-center">
    		    <img src="{{ asset('assets/images/umbrella-forgot-password.jpg')}}" class="img-fluid" alt="">
    		</div>
    		<div class="col-12 col-md-6">
    		    <h4 class="font-weight-light">Forgot your password?</h4>
    		   <p> Enter new password here </p>
               @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
           <form method="POST" action="{{ route('password.update') }}">
            @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                        <input
                          type="hidden"
                          name="email"
                          placeholder="Email Address"
                          class="form-control"
                          value="{{ htmlspecialchars($_GET["email"]) }}"
                          required
                        />
    		        {{-- <input class="form-control form-control-lg" type="email" placeholder="E-Mail Address" name="email" required> --}}
                    <div class="mb-3 login-inp">
                        <label for="password"
                          >Password</label
                        >
                        <small>Error Message</small>
                        <input
                          type="password"
                          name="password"
                          placeholder="New Password"
                          class="form-control"
                          id="password"
                          required
                        />
                        <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                      </div>
    		        {{-- <input class="form-control form-control-lg" type="password" name="password" placeholder="New Password" required> --}}
                    <div class="mb-3 login-inp abc">
                        <label for="password_confirmation"
                          >Confirm Password</label
                        >
                        <small>Error Message</small>
                        <input
                          type="password"
                          name="password_confirmation"
                          placeholder="Confirm Password"
                          class="form-control"
                          id="password_confirmation"
                          required
                        />
                        <span toggle="#password_confirmation" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                      </div>
    		        {{-- <input class="form-control form-control-lg" type="password" name="password_confirmation" placeholder="Confirm Password" required> --}}
                    <div class="text-right my-3">
    		            <button type="submit" id="btn_reset_password" class="btn btn-lg btn-success">Reset Password</button>
    		        </div>
    		    </form>
    		</div>
    	</div>
      </div>
    </div>
  </div>

<!-- forgot - Password - Modal -end -->




@endsection
