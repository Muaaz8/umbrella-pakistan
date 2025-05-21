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

        function edit(id) {
            console.log(id);
            $.ajax({
                type: "GET",
                url: "/get/pages/meta/tag/" + id,
                success: function(response) {
                    $.each(response, function (indexInArray, valueOfElement) {
                        if(valueOfElement.name == "Title") {
                            document.getElementById("title").value = valueOfElement.content;
                        } else if(valueOfElement.name == "Description") {
                            document.getElementById("site_description").value = valueOfElement.content;
                        } else if(valueOfElement.name == "Keywords") {
                            document.getElementById("Site_Keywords").value = valueOfElement.content;
                        }
                    });
                    document.getElementById("meta_tag_form").setAttribute("action", "/update/pages/meta/tag/" + id);
                    document.getElementById("save_btn").innerHTML = "Update Meta Tags";
                    //Window scroll to top
                    window.scrollTo(0, 0);
                }
            });
        }
    </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create(document.querySelector("#editor"));

        document.querySelector("form").addEventListener("submit", (e) => {
            e.preventDefault();
            console.log(document.getElementById("editor").value);
        });
    </script>
    <script>
        ClassicEditor.create(document.querySelector("#editors"));

        document.querySelector("form").addEventListener("submit", (e) => {
            e.preventDefault();
            console.log(document.getElementById("editors").value);
        });
    </script>
@endsection
@section('content')
    <div class="col-11 m-auto">
        <div class="account-setting-wrapper bg-white">
            <form action="/insert/pages/meta/tag" id="meta_tag_form" method="POST">
                @csrf
                <h4 class="pb-4 border-bottom">Seo Form</h4>
                <div class="py-2">
                    <div class="row py-2">
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
                    </div>
                    <div class="row py-2">
                        <div class="col-md-12">
                            <label for="firstname">Set Title</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="" required>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-md-6">
                            <label for="site_description">Site Description</label>
                            <textarea class="form-control" name="description" id="site_description" rows="4" required></textarea>
                        </div>
                        <div class="col-md-6 pt-md-0 pt-3">
                            <label for="Site_Keywords">Site Keywords (Separate with commas)*</label>
                            <textarea class="form-control" name="keywords" id="Site_Keywords" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="py-3 pb-4">
                        <button type="submit" id="save_btn" class="btn btn-primary mr-3">Save Meta Tags</button>
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
                                            <th scope="col">Content</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tags as $tag)
                                            <tr>
                                                <th scope="row">{{ $tag->url }}</th>
                                                <td>{{ $tag->name }}</td>
                                                <td>{{ $tag->content }}</td>
                                                <td>
                                                    <button class="btn option-view-btn" type="button"
                                                        onclick="window.location.href='/del/pages/meta/tag/{{ $tag->id }}'">
                                                        Delete
                                                    </button>

                                                    <button class="btn option-view-btn" type="button"
                                                        onclick="edit({{ $tag->id }})">
                                                        Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                </table>
                                <div class="row d-flex justify-content-center">
                                    <div id="pag" class="paginateCounter">
                                        {{ $tags->links('pagination::bootstrap-4') }}
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
