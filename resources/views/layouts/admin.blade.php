<!DOCTYPE html>
<html @lang('en')>
<?php
$user = Auth::user()->user_type;
?>

<head>
    @include('./admin/_head')
    @include('./admin/_css')
    @yield('css')
</head>

<body class="theme-dark-blue">
    @include('./admin/_pageloader')
    @include('./admin/_search')
    @include('./admin/_nav')
    @include('./admin/_sidebar')
    @yield('content')

    <div class="color-bg" style="z-index:-1;"></div>

    @include('./admin/_script')

    @yield('script')
</body>

</html>
