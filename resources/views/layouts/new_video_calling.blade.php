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
        @include('./new_video_calling/top_script')
        @yield('top_import_file')
    </head>
    <body>
        @yield('content')
        @include('./new_video_calling/bottom_script')
        @yield('bottom_import_file')
    </body>
</html>
