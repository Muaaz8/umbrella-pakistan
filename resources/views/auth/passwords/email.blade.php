@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    <title>CHCC - Forgot Password</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')


<!-- forgot - Password - Modal -->

  <div class="container">
    <div class="forgot-pass-modal my-4">
     <div class="row align-items-center my-4 my-sm-0">
    		<div class="col-12 col-md-6 text-center">
    		    <img src="{{ asset('assets/images/umbrella-forgot-password.jpg')}}" class="img-fluid" alt="">
    		</div>
    		<div class="col-12 col-md-6">
    		    <h4 class="font-weight-light">Forgot your password?</h4>
    		   <p> Not to Worry. Just enter your email address below and we'll send you an instruction email for recovery</p>
            @if ($errors->any())
               <div class="alert alert-danger">
                   <ul>
                       @foreach ($errors->all() as $error)
                           <li>{{ $error }}</li>
                       @endforeach
                   </ul>
               </div>
            @endif
            @if(session()->has('status'))
                <div class="alert alert-success">
                    <ul><li><p>
                        Sent!!
                    </p></li></ul>
                </div>
            @endif

    		    <form class="mt-3" method="POST" action="{{ route('password.email') }}">
                    @csrf
    		        <input class="form-control form-control-lg" type="email" placeholder="Your email address" name="email" required>
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
{{-- <script>
$("#btn_reset_password").click(function(){
    $('#success').html('<li>Link Sent to Email</li>');
    $('#success').addClass('alert alert-success');
})
</script> --}}



@endsection
