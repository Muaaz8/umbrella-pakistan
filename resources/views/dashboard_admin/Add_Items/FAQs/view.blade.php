@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')

        <div class="dashboard-content">
            <div class="container-fluid">
                <div class="col-11 m-auto">
                    <div class="account-setting-wrapper edit_med_profile bg-white">
                    <div class="d-flex justify-content-between align-items-center border-bottom">
                        <div>
                          <h4>FAQ FOR TEST
                            <br>
                            <p class="fs-6 fw-normal">FAQ Details</p></h4>
                        </div>
                        </div>
                        <div>
                            @foreach ($tblFaq as $tblFaqs)
                            <div class="py-3">
                                <h6><b> Question:</b></h6>
                                {{ $tblFaqs->question  }}
                            </div>
                            <div>
                                <h6><b> Answer: </b></h6>
                              {{ $tblFaqs->answer }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
      </div>
    </div>


@endsection
