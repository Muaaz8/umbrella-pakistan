@php
    use Carbon\Carbon;
@endphp

@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Admin Dashboard</title>
@endsection

@section('top_import_file')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#user').on("change", function (e) {
                var type = $('#user').val();
                window.location.href = "/admin/manage/all/users/" + type;
            });
        });

        function email_modal_function(a) {
            var email = $(a).attr('class');
            var breakClasses = email.split(' ');
            $('#email').val(breakClasses[1]);
            $('#send_email_modal').modal('show');
        }
    </script>
@endsection


@section('bottom_import_file')
    <script>
        $('.acctive').click(function () {
            var id = $(this).attr('id');
            $("#activate_user_id").val(id);
            $('#activate_user').modal('show');
        });
        $('.deactive').click(function () {
            var id = $(this).attr('id');
            $("#deactivate_user_id").val(id);
            $('#deactivate_user').modal('show');
        });
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex justify-content-between flex-wrap align-items-baseline p-0">
                            <h3>Requested Product</h3>
                            {{-- <div class="col-md-4 p-0">
                                <div class="input-group">
                                    <div class="d-flex">
                                        <input type="text" class="form-control mb-1" id="search"
                                            placeholder="Search Product">
                                        <button type="button" id="search_btn" class="btn process-pay"><i
                                                class="fa-solid fa-search"></i></button>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Sno.</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Vendor</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Requested at</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="bodies">
                                    @if ($pendingRequests->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center">No pending requests found.</td>
                                        </tr>
                                    @else
                                        @foreach ($pendingRequests as $index => $request)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $request->product }}</td>
                                                <td>{{ $request->vendor_account_name }}</td>
                                                <td>{{ $request->status }}</td>
                                                <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d M, Y h:i A') }}</td>

                                                <td class="text-center" data-label="Action">
                                                    @if ($request->status === 'pending')
                                                        <div class="dropdown">
                                                            <button class="orders-view-btn dropdown-toggle" type="button"
                                                                id="dropdownMenuButton{{ $request->id }}" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                Actions
                                                            </button>

                                                            <ul class="dropdown-menu"
                                                                aria-labelledby="dropdownMenuButton{{ $request->id }}">
                                                                <li>
                                                                    <form action="{{ route('updateStatus', $request->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="status" value="approved">
                                                                        <button type="submit"
                                                                            class="dropdown-item text-success">Approve</button>
                                                                    </form>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('updateStatus', $request->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="status" value="rejected">
                                                                        <button type="submit"
                                                                            class="dropdown-item text-danger">Reject</button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @else
                                                        <span class="badge bg-secondary text-capitalize">{{ $request->status }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div id="pag">
                                {{ $pendingRequests->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection