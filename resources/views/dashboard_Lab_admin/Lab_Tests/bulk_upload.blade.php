@extends('layouts.dashboard_Lab_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title')
    <title>Quest Lab Tests</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
    <script>
        @php header("Access-Control-Allow-Origin: *"); @endphp
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function edit_lab_category(id) {
            var na = $('#na_' + id).val();
            var sl = $('#ty_' + id).val();

            $('#e_name').val(na);
            $('#e_slug').val(sl);
            $('#e_id').val(id);

            $('#edit_main_category').modal('show');
        }

        function del_lab_cat(id) {
            $('#id').val(id);
            $('#delete_main_category').modal('show');
        }
        var input = document.getElementById("search");
        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                document.getElementById("search_btn").click();
            }
        });

        function search(array) {
            var val = $('#search').val();
            if (val == '') {
                window.location.href = '/lab/test/categories';
            } else {
                $('#bodies').empty();
                $('#pag').html('');
                $.each(array, function(key, arr) {
                    if ((arr.id != null && arr.id.toString().match(val)) || (arr.name != null && arr.name.match(
                            val))) {
                        $('#bodies').append('<tr id="body_' + arr.id + '"></tr>');
                        $('#body_' + arr.id).append('<td data-label="ID">' + arr.id + '</td>' +
                            '<td data-label="Name">' + arr.name + '</td>' +
                            '<input type="hidden" id="na_' + arr.id + '" value="' + arr.name + '">' +
                            '<input type="hidden" id="ty_' + arr.id + '" value="' + arr.slug + '">' +
                            '<td data-label="Action"><div class="dropdown">' +
                            '<button class="btn option-view-btn dropdown-toggle" type="button"' +
                            ' id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">OPTIONS</button>' +
                            '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">' +
                            '<li><a class="dropdown-item" href="#" onclick="edit_lab_category(' + arr.id +
                            ')">Edit</a></li>' +
                            '<li><a class="dropdown-item" href="#" onclick="del_lab_cat(' + arr.id +
                            ')">Delete</a></li>'
                        );
                    }
                });
            }
        }
    </script>
@endsection
@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">

                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <div class="col-md-3">
                                <h3>Upload Lab Tests</h3>
                            </div>
                            <div class="col-md-3">
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-5 p-0">
                                </div>
                                <div class="col-md-4 p-0">
                                    <div class="input-group">
                                        <a href="{{ asset('csv/sample_lab_testss_upload.csv') }}" download>
                                            <button class="btn process-pay">Download Sample</button>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-3 p-0">
                                    <div class="input-group">
                                        <button type="button" id="csv" onclick="gen_csv()"
                                            class="btn process-pay">Generate CSV</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            @include('flash::message')
                        </div>
                        <div class="wallet-table " style="border-radius: 18px;">
                            <form action="{{ route('dash_uploadLabCSV') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="medicine_description p-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Upload CSV File Here</label>
                                            <input type="file"name="file" accept=".csv" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="text-end">
                                            <button type="submit" class="btn process-pay">Upload</button>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    </div>
@endsection
