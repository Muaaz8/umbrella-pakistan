<!DOCTYPE html>
<html @lang('en')>

    <?php
    if (Auth::check()) {
        $user = Auth::user()->user_type;
    }

    ?>
    <head>
        @yield('meta_tags')
        @yield('page_title')
        @include('./new_frontend/top_script')
        @yield('top_import_file')
    </head>
    <body>
        @include('./new_frontend/header')
        @yield('content')
        @include('./new_frontend/footer')
        @yield('bottom_import_file')
    </body>
</html>
