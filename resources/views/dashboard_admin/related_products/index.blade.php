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
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex flex-wrap justify-content-between align-items-baseline p-0">
                            <h3>All Related Products</h3>
                            <div class="col-md-4 col-sm-6 col-12 p-0">
                                <div class="input-group">
                                    <a href="{{ route('related_products.create') }}" class="btn process-pay">Add new</a>
                                </div>
                            </div>
                        </div>
                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Product Id</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Related Product Name</th>
                                        <th scope="col">Related Product Id</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $dt)
                                        <tr>
                                            <td data-label="Product">{{ $dt->product_id }}</td>
                                            <td data-label="Product Name">{{ $dt->TEST_NAME }}</td>
                                            <td data-label="Related Product Name">{{ $dt->related_test_name }}</td>
                                            <td data-label="Related Product">{{ $dt->related_product_ids }}</td>
                                            <td data-label="Action">
                                                <a>
                                                    <form action="{{route('related_products.destroy', ['related_product' => $dt->id])}}" method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                        <input class="btn btn-danger" type="submit" value="Delete" />
                                                    </form>
                                                </a>
                                            </td>

                                        </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="m-auto text-center for-empty-div">
                                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                <h6>No Related Products To Show</h6>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $data->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
