<!DOCTYPE html>
<html @lang('en')>
<!-- Frontend Page -->

<head>
  @include('./frontend/_head')
  @include('./frontend/_css')
  @yield('css')
</head>

<body>
    @include('./frontend/_pageloader')
<div id="page" class="page">
    @include('./frontend/_nav')

    @yield('content')


    @include('./frontend/_footer')
</div>
    @include('./frontend/_script')

@yield('script')
  </body>
</html>
