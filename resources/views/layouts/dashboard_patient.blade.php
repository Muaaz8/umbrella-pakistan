<!DOCTYPE html>
<html @lang('en')>

    <?php
    use Illuminate\Support\Facades\DB;
    if (Auth::check()) {
        $user = Auth::user()->user_type;
    }
    ?>
    <head>
        @yield('meta_tags')
        @yield('page_title')
        @include('./new_backend_patient/top_script')
        @yield('top_import_file')
    </head>
    <body>
        @include('./new_backend_patient/header')
        @yield('content')
        @include('./new_backend_patient/bottom_script')
        @yield('bottom_import_file')
    </body>
</html>