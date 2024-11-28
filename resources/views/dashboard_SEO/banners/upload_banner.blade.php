@extends('layouts.dashboard_SEO')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Upload Banner</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection


@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <div>
                                <h3>Upload Banner</h3>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            @include('flash::message')
                        </div>
                        <div class="wallet-table " style="border-radius: 18px;">
                            <form action="{{ route('upload_new_banner') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="medicine_description p-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Upload image</label>
                                            <input type="file" name="image" class="form-control">

                                            <label class="fw-bolder mb-2" for="selectmedicine">Sequence</label>
                                            <input type="number" name="sequence" class="form-control"
                                                placeholder="sequence" required>

                                            <label class="fw-bolder mb-2" for="selectmedicine">Status</label>
                                            <select name="status" class="form-control" aria-label="Default select example"
                                                required>
                                                <option value="1">Activated</option>
                                                <option value="0">Deactivated</option>
                                            </select>
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
@endsection
