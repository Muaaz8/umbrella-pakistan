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
        <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
        @include('./new_frontend/top_script')
        @yield('top_import_file')
    </head>
    <body>
        @yield('content')
        @include('./new_frontend/bottom_script')
        @yield('bottom_import_file')
    </body>
</html>
