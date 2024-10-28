<!DOCTYPE html>
<html @lang('en')>
<!-- Admin Page -->
<?php  
// dd(Auth::user()->user_type);
$user = Auth::user()->user_type;
?>
<head>
  @include('./admin/_head')
  @include('./admin/_css')
  @yield('css')
</head>
@if($user=='admin')
<body class="theme-green" >
@elseif($user=='doctor')
<body class="theme-blue" >
@elseif($user=='patient')
<body class="theme-yellow" >
@else
@endif
     @include('./admin/_pageloader')
     @include('./admin/_search')
     @include('./admin/_nav')
     @include('./admin/_sidebar')


    @yield('content')

<div class="color-bg"></div>
    @include('./admin/_script2')

@yield('script')
  </body>
</html>
