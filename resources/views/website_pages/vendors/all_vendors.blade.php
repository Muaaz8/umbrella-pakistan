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
@endsection


@section('bottom_import_file')
    <script>
        function toggleVendorStatus(id) {
            $.ajax({
                url: "{{ route('toggle_status') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    vendor_id: id
                },
                success: function (response) {
                    if (response) {
                        location.reload()
                    } else {
                        console.log("fail");
                    }
                },
                error: function () {
                    console.log("Error occurred while toggling vendor status.");
                }
            });

        }

        $(document).ready(function () {
            // Handle search button click
            $('#search_btn').on('click', function () {
                performSearch();
            });

            // Handle enter key press in search input
            $('#search').on('keyup', function (e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            function performSearch() {
                let searchValue = $('#search').val().trim();

                $.ajax({
                    url: "{{ route('all_vendors_page') }}",
                    method: "GET",
                    data: {
                        search: searchValue,
                        page: 1 
                    },
                    beforeSend: function () {
                        $('#bodies').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
                    },
                    success: function (response) {
                        $('#bodies').html($(response).find('#bodies').html());
                        $('#pag').html($(response).find('#pag').html());
                    },
                    error: function (xhr) {
                        console.error("Error occurred during search:", xhr.responseText);
                        $('#bodies').html('<tr><td colspan="7" class="text-center">Error occurred during search</td></tr>');
                    }
                });
            }

            $(document).on('click', '#pag .pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let searchValue = $('#search').val().trim();

                $.ajax({
                    url: "{{ route('all_vendors_page') }}",
                    method: "GET",
                    data: {
                        search: searchValue,
                        page: page
                    },
                    success: function (response) {
                        $('#bodies').html($(response).find('#bodies').html());
                        $('#pag').html($(response).find('#pag').html());
                    },
                    error: function (xhr) {
                        console.error("Error occurred during pagination:", xhr.responseText);
                    }
                });
            });
        }); </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex justify-content-between flex-wrap align-items-baseline p-0">
                            <h3>All Vendors</h3>
                            <div class="col-md-4 p-0">
                                <div class="input-group">
                                    <div class="d-flex">
                                        <input type="text" class="form-control mb-1" id="search"
                                            placeholder="Search Vendor">
                                        <button type="button" id="search_btn" class="btn process-pay"><i
                                                class="fa-solid fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Phone Number</th>
                                        <th scope="col">Vendor</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Vendor Type</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="bodies">
                                    @forelse ($vendors as $key => $vendor)
                                        <tr>
                                            <th scope="S.No">{{ $vendor->id }}</th>
                                            <td data-label="User Name">{{ $vendor->user_name . " " . $vendor->user_last_name}}
                                            </td>
                                            <td data-label="User Number">{{ $vendor->vendor_number }}</td>
                                            <td data-label="Vendor">{{ $vendor->name }}</td>
                                            <td data-label="is_active">{{ $vendor->is_active == 0 ? "in active" : "active" }}
                                            </td>
                                            <td data-label="Vendor_type">{{ $vendor->vendor}}</td>
                                            <td class="d-flex align-items-center justify-content-center" data-label="Action">
                                                <div class="dropdown">
                                                    <button class="orders-view-btn dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Actions
                                                    </button>

                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('pharmacy_products', ['id' => $vendor->id]) }}">View
                                                                products</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('edit_vendor', $vendor->id) }}">Edit Vendor</a>
                                                        </li>
                                                        <li>
                                                            @if($vendor->is_active == 1 || $vendor->is_active == "1")
                                                                <button class="dropdown-item text-danger"
                                                                    onclick="toggleVendorStatus({{ $vendor->id }})">
                                                                    Deactivate Vendor
                                                                </button>
                                                            @else
                                                                <button class="dropdown-item text-success"
                                                                    onclick="toggleVendorStatus({{ $vendor->id }})">
                                                                    Activate Vendor
                                                                </button>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="m-auto text-center for-empty-div">
                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                    <h6>No Vndors To Show</h6>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                            <div id="pag">
                                {{ $vendors->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection