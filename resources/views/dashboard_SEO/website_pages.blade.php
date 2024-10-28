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
            <form action="/insert/pages" method="POST">
                @csrf
                <h4 class="pb-4 border-bottom">Pages</h4>
                <div class="py-2">
                    {{-- <div class="row py-2">
                        <div class="col-md-6">
                            <label for="firstname">Product Mode</label>
                            <select class="form-select" name="url" required aria-label="Default select example"
                                required>
                                <option selected>Select Page</option>
                                <option value="">Home</option>
                                <option value="/about-us">About</option>
                                <option value="/e-visit">Evisit</option>
                                <option value="/contact-us">Contact Us</option>
                                <option value="/primary-care">Primary Care</option>
                                <option value="/psychiatry/anxiety">Psychiatry</option>
                                <option value="/pain-management">Pain Management</option>
                                <option value="/substance-abuse/first-visit">Substance Abuse</option>
                                <option value="/pharmacy">Pharmacy</option>
                                <option value="/labtests">Lab Test</option>
                                <option value="/imaging">Imaging</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="row py-2">
                        <div class="col-md-6">
                            <label for="page_name">Page Name</label>
                            <input type="text" name="page_name" class="form-control" placeholder="" required>
                        </div>
                        <div class="col-md-6">
                            <label for="url">Url</label>
                            <input type="text" name="url" class="form-control" placeholder="" required>
                        </div>
                    </div>
                    <div class="py-3 pb-4">
                        <button type="submit" class="btn btn-primary mr-3">Save Page</button>
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
                                            <th scope="col">URL</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pages as $page)
                                            <tr>
                                                <th scope="row">{{ $page->url }}</th>
                                                <td>{{ $page->name }}</td>
                                                <td>
                                                    <button class="btn option-view-btn" type="button"
                                                        onclick="window.location.href='/edit/page/{{ $page->id }}'">
                                                        Edit
                                                    </button>
                                                    <button class="btn option-view-btn" type="button"
                                                        onclick="window.location.href='/del/pages/{{ $page->id }}'">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                </table>
                                <div class="row d-flex justify-content-center">
                                    <div id="pag" class="paginateCounter">
                                        {{ $pages->links('pagination::bootstrap-4') }}
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
