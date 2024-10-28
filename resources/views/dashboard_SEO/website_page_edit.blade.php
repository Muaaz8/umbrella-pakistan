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
            <form action="/update/pages/{{ $pages->id }}" method="POST">
                @csrf
                <h4 class="pb-4 border-bottom">Pages</h4>
                <div class="py-2">
                    <div class="row py-2">
                        <div class="col-md-6">
                            <label for="page_name">Page Name</label>
                            <input type="text" name="page_name" class="form-control" placeholder="" value="{{ $pages->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="url">Url</label>
                            <input type="text" name="url" class="form-control" placeholder="" value="{{ $pages->url }}" required>
                        </div>
                    </div>
                    <div class="py-3 pb-4">
                        <button type="submit" class="btn btn-primary mr-3">Update Page</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
