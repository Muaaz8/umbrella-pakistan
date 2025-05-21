@php
    use Carbon\Carbon;
@endphp

@extends('layouts.dashboard_vendor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Vendor Products</title>
@endsection

@section('top_import_file')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@endsection


@section('bottom_import_file')
    <script>
        function toggleProductStatus(id) {
            $.ajax({
                url: "{{ route('toggle_product_status') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: id
                },
                success: function (response) {
                    if (response) {
                        location.reload()
                    } else {
                        console.log("fail");
                    }
                },
                error: function () {
                    console.log("Error occurred while toggling product status.");
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
                    url: "{{ route('vendor_products') }}",
                    method: "GET",
                    data: {
                        search: searchValue,
                        page: 1
                    },
                    beforeSend: function () {
                        $('#bodies').html('<tr><td colspan="8" class="text-center">Loading...</td></tr>');
                    },
                    success: function (response) {
                        $('#bodies').html($(response).find('#bodies').html());
                        $('#pag').html($(response).find('#pag').html());
                    },
                    error: function (xhr) {
                        console.error("Error occurred during search:", xhr.responseText);
                        $('#bodies').html('<tr><td colspan="8" class="text-center">Error occurred during search</td></tr>');
                    }
                });
            }

            $(document).on('click', '#pag .pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let searchValue = $('#search').val().trim();

                $.ajax({
                    url: "{{ route('vendor_products') }}",
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
                            <h3>All Products</h3>
                            <div class="col-md-4 p-0">
                                <div class="input-group">
                                    <div class="d-flex">
                                        <input type="text" class="form-control mb-1" id="search"
                                            placeholder="Search Product">
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
                                        <th scope="col">ID</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Status</th>
                                        @if ($vendor_type == 'pharmacy')
                                            <th scope="col">Available Stock</th>
                                        @endif
                                        <th scope="col">SKU</th>
                                        <th scope="col">Selling Price</th>
                                        <th scope="col">Discount</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="bodies">
                                    @forelse ($products as $key => $product)
                                        <tr>
                                            <th scope="row">{{ $product->id }}</th>
                                            <td data-label="Name">{{ $product->name }}</td>
                                            <td data-label="Type">{{ $product->is_active == 0 ? 'false' : 'true' }}</td>
                                            @if ($vendor_type == 'pharmacy')
                                                <td data-label="Stock">{{ $product->available_stock }}</td>
                                            @endif
                                            <td data-label="Actual Price">{{ $product->SKU }}</td>
                                            <td data-label="Selling Price">{{ $product->selling_price }}</td>
                                            <td data-label="Discount">{{ $product->discount }}%</td>
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
                                                                href="{{ route('edit_product', $product->id) }}"
                                                                >Edit
                                                                Product</a>
                                                        </li>
                                                        <li>
                                                            @if($product->is_active == 1 || $product->is_active == "1")
                                                                <button class="dropdown-item text-danger"
                                                                    onclick="toggleProductStatus({{ $product->id }})">
                                                                    Deactivate Product
                                                                </button>
                                                            @else
                                                                <button class="dropdown-item text-success"
                                                                    onclick="toggleProductStatus({{ $product->id }})">
                                                                    Activate Product
                                                                </button>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                <div class="m-auto text-center for-empty-div">
                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                    <h6>No Products To Show</h6>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div id="pag">
                                {{ $products->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection