@extends('layouts.dashboard_Pharm_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>Pharmacy Admin Dashboard</title>
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

    function gen_csv()
    {
        $('#csv_form').submit();
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
                                <h3>Upload Medicines</h3>
                            </div>
                            <div class="col-md-3">
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-5 p-0">
                                </div>
                                <div class="col-md-4 p-0">
                                    <div class="input-group">
                                        <a href="{{ asset('csv/sample_medicine_upload.csv') }}" download>
                                            <button class="btn process-pay">Download Sample</button>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-3 p-0">
                                    <div class="input-group">
                                        <button type="button" id="csv" onclick="gen_csv()" class="btn process-pay">Generate CSV</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            @include('flash::message')
                        </div>
                        <div class="wallet-table " style="border-radius: 18px;">
                            <form action="{{ route('dash_uploadCSV') }}" method="POST" enctype="multipart/form-data">
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
    <form type="hidden" id="csv_form" action="/generate_all_medicine_record_csv" method="POST">
        @csrf
        <input type="hidden" id="csv_date" name="csv_date" value="" />
    </form>
@endsection
