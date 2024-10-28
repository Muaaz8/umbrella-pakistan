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
            <form action="/update/section/{{ $section->id }}" method="POST">
                @csrf
                <h4 class="pb-4 border-bottom">Seo Form</h4>
                <div class="py-2">
                    <div class="row py-2">
                        <div class="col-md-6">
                            <label for="firstname">Page</label>
                            <select class="form-select" name="page_id" required aria-label="Default select example"
                                required>
                                <option selected>Select Page</option>
                                @foreach ($pages as $page)
                                    <option value="{{ $page->id }}" {{ $page->id==$section->page_id?"selected":"" }}>{{ $page->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-md-6">
                            <label for="section_name">Section Name</label>
                            <input type="text" name="section_name" class="form-control" placeholder="" value="{{ $section->section_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="sequence_no">Sequence No</label>
                            <input type="text" name="sequence_no" class="form-control" placeholder="" value="{{ $section->sequence_no }}" required>
                        </div>
                    </div>

                    <div class="py-3 pb-4">
                        <button type="submit" class="btn btn-primary mr-3">Update Section</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
