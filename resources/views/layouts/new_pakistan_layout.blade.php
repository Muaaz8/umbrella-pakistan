<!DOCTYPE html>
<html lang="@lang('en')">

    <?php
    use Illuminate\Support\Facades\DB;
    if (Auth::check()) {
        $user = Auth::user()->user_type;
    }
    ?>
    <head>
        @yield('meta_tags')
        @yield('page_title')
        <link rel="icon" href="{{ asset('assets/new_frontend/fav_ico.png') }}" type="image/x-icon">
        @include('./new_pakistan_frontend/top_script')
        @yield('top_import_file')
    </head>
    <body>
        @include('./new_pakistan_frontend/header')
        @yield('content')
        @include('./new_pakistan_frontend/footer')
        @include('./new_pakistan_frontend/bottom_script')
        @yield('bottom_import_file')
    </body>
</html>
