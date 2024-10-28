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

        $('#page').change(function() {
            var mode = $('#page').val();
            $.ajax({
                type: "get",
                url: '/get/sections/by/page/'+mode,
                success: function (response) {
                    $('#section_selection').html("");
                    $.each(response, function(key, val) {
                        $('#section_selection').append('<option value="' + val.id + '">' + val.section_name + '</option>');
                    });
                    $('#section_selection').change();
                }
            });
        });

        $('#section_selection').change(function() {
            var section_id = $('#section_selection').val();
            $.ajax({
                type: "get",
                url: '/get/image/content/by/section/'+section_id,
                success: function (response) {
                    if (!$.isEmptyObject(response)) {
                        $('#alt_text').val(response.alt);
                    }else{
                        $('#alt_text').val("");
                    }
                }
            });
        });
    </script>
@endsection
@section('content')
    <div class="col-11 m-auto">
        <div class="account-setting-wrapper bg-white">
            <form action="/update/image/content" method="POST">
                @csrf
                <h4 class="pb-4 border-bottom">Image Alt Form</h4>
                <div class="py-2">
                    <div class="row py-2">
                        <div class="col-md-6">
                            <label for="firstname">Page</label>
                            <select class="form-select" name="page_id" required aria-label="Default select example" id="page"
                                required>
                                <option selected>Select Page</option>
                                @foreach ($pages as $page)
                                    <option value="{{ $page->id }}">{{ $page->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="firstname">Section</label>
                            <select class="form-select" name="section_id" required aria-label="Default select example" id="section_selection"
                                required>
                                <option selected>Select Section</option>
                            </select>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-md-12">
                            <label for="editors">Alt Text</label>
                            <input type="text" name="alt" id="alt_text" class="form-control" ></input>
                        </div>
                    </div>

                    <div class="py-3 pb-4">
                        <button type="submit" class="btn btn-primary mr-3">Save Section</button>
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
                                            <th scope="col">Page</th>
                                            <th scope="col">Section</th>
                                            {{-- <th scope="col">Sequence No</th> --}}
                                            <th scope="col">Alt Text</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contents as $content)
                                            <tr>
                                                <th scope="row">{{ $content->page_name }}</th>
                                                <th >{{ $content->section_name }}</th>
                                                {{-- <td>{{ $content->sequence_no }}</td> --}}
                                                <td>{{ $content->alt }}</td>
                                                <td>
                                                    {{-- <button class="btn option-view-btn" type="button"
                                                        onclick="window.location.href='/edit/content/{{ $content->id }}'">
                                                        Edit
                                                    </button> --}}
                                                    {{-- <button class="btn option-view-btn" type="button"
                                                        onclick="window.location.href='/del/pages/section/content/{{ $content->id }}'">
                                                        Delete
                                                    </button> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                </table>
                                <div class="row d-flex justify-content-center">
                                    <div id="pag" class="paginateCounter">
                                        {{ $contents->links('pagination::bootstrap-4') }}
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
