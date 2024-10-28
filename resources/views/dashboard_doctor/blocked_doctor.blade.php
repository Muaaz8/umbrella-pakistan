@extends('layouts.new_web_layout')

@section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
<title>UHCS - Blocked Account</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
<section class="Email-confirm-sec">
    <div class="container">
        <div class="row my-5">
          <div class="col-md-6 col-11 Email-confirm-wrap">
            <img src="assets/images/admin_block.png" alt="" style="width: 90px;">
          <h1>Account Blocked</h1>
          <p>Admin blocked your account.</p>
         <div>
          <!-- <p>If you did not get it.</p> -->
            <!-- <button>(112) Resend Email</button> -->
          </div>
          </div>
          </div>
    </div>
</section>
@endsection
