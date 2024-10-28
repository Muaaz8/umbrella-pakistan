<!DOCTYPE html>
<html @lang('en')>
    <!-- video page -->
<?php  
// $user = Auth::user()->user_type;
?>
<head>
  <meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<title>Umbrella Health Care Systems</title>
<!-- <link rel="icon" href="{{ asset('asset_frontend/images/favicon.ico')}}" type="image/x-icon"> 
<link href="{{ asset('asset_admin/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src='https://kit.fontawesome.com/a076d05399.js'></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
 

  <head>
  @include('./frontend/_head')
  @include('./frontend/_css')
  @yield('css')
</head>

<body>
<div id="page" class="page">

    @yield('content')

</div>
    @include('./frontend/_script2')

@yield('script')
  </body>
</html>