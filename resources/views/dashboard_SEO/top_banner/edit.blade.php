@extends('layouts.dashboard_SEO')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title')
    <title>Dashboard SEO</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
    <script type="text/javascript">
        @php header("Access-Control-Allow-Origin: *"); @endphp
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endsection
@section('content')
    <div class="col-11 m-auto">
        <div class="account-setting-wrapper bg-white">
            <form action="/update/top_banner/{{ $top_banner->id }}" method="POST">
                @csrf
                <h4 class="pb-4 border-bottom">Top Banner</h4>
                <div class="py-2">
                    <div class="row py-2">
                        <div class="col-md-12">
                            <label for="page_name">Text</label>
                            <input type="text" name="value" value="{{ $top_banner->status }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="py-3 pb-4">
                        <button type="submit" class="btn btn-primary mr-3">Save</button>
                        <button class="btn border button">Cancel</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
