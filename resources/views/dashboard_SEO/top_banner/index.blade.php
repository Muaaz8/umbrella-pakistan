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
            <form action="/insert/top_banner" method="POST">
                @csrf
                <h4 class="pb-4 border-bottom">Top Banner</h4>
                <div class="py-2">
                    <div class="row py-2">
                        <div class="col-md-12">
                            <label for="page_name">Text</label>
                            <input type="text" name="value" class="form-control" placeholder="Get 20% off on your first purchase! Use code: FIRST20" required>
                        </div>
                    </div>
                    <div class="py-3 pb-4">
                        <button type="submit" class="btn btn-primary mr-3">Save</button>
                        <button class="btn border button">Cancel</button>
                    </div>
                </div>
            </form>
            <div class="container">
                <div class="row m-auto">
                    <div class="col-md-12">
                        <div class="row m-auto">
                            <div class="wallet-table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Text</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($top_banner as $banner)
                                            <tr>
                                                <th scope="row">{{ $banner->status }}</th>
                                                <td>
                                                    <button class="btn option-view-btn" type="button"
                                                        onclick="window.location.href='/edit/top_banner/{{ $banner->id }}'">
                                                        Edit
                                                    </button>
                                                    <button class="btn option-view-btn" type="button"
                                                        onclick="window.location.href='/del/top_banner/{{ $banner->id }}'">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                </table>
                                <div class="row d-flex justify-content-center">
                                    <div id="pag" class="paginateCounter">
                                        {{ $top_banner->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
