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

        $(document).ready(function () {
            var mode = $('#page').val();
            var section_id = "{{ $content->section_id }}"
            if(mode){
                $.ajax({
                    type: "get",
                    url: '/get/sections/by/page/'+mode,
                    success: function (response) {
                        $('#section_selection').html("");
                        $.each(response, function(key, val) {
                            if(val.id == section_id){
                                $('#section_selection').append('<option value="' + val.id + '" selected>' + val.section_name + '</option>');
                            }else{
                                $('#section_selection').append('<option value="' + val.id + '">' + val.section_name + '</option>');
                            }
                        });
                    }
                });
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
                }
            });
        });
    </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>
    <script>

    </script>
    <script>
        ClassicEditor.create(document.querySelector("#editors"));
        document.querySelector("form").addEventListener("submit", (e) => {
            e.preventDefault();
        });
    </script>
@endsection
@section('content')
    <div class="col-11 m-auto">
        <div class="account-setting-wrapper bg-white">
            <form action="/update/content/{{ $content->id }}" method="POST">
                @csrf
                <h4 class="pb-4 border-bottom">Seo Form</h4>
                <div class="py-2">
                    <div class="row py-2">
                        <div class="col-md-6">
                            <label for="firstname">Page</label>
                            <select class="form-select" name="page_id" required aria-label="Default select example" id="page"
                                required>
                                <option selected>Select Page</option>
                                @foreach ($pages as $page)
                                    <option value="{{ $page->id }}" {{ $page->id==$content->page_id?"selected":"" }}>{{ $page->name }}</option>
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
                        <div class="col-md-6">
                            <label for="sequence_no">Sequence No</label>
                            <input type="text" name="sequence_no" class="form-control" placeholder="" value="{{ $content->sequence_no }}" required>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-md-12">
                            <label for="editors">Content</label>
                            <textarea type="text" name="content" id="editors" class="form-control" rows="4" cols="50" >{{ $content->content }}</textarea>
                        </div>
                    </div>

                    <div class="py-3 pb-4">
                        <button type="submit" class="btn btn-primary mr-3">Save Content</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
