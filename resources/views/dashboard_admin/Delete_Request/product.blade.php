@extends('layouts.dashboard_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>CHCC - Product Requests</title>
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
                        <div class="d-flex justify-content-between flex-wrap align-items-end p-0">
                            <div>
                                <h3>Product Requests</h3>
                                <p>All Products New Added Request</p>
                            </div>
                            {{-- <div class="col-md-4 p-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search what you are looking for"
                                        aria-label="Username" aria-describedby="basic-addon1" />
                                </div>
                            </div> --}}
                        </div>
                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Product Mode</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($new_added_products as $prod)
                                    <tr>
                                        <td data-label="Product Name">{{ucfirst($prod->name)}}</td>
                                        <td data-label="Product Mode">{{ucfirst($prod->mode)}}</td>
                                        <td data-label="Action">
                                            <div>
                                                <a href="{{route('add_approve_prod',$prod->id)}}" class="product_circle"><i
                                                        class="fa-solid fa-circle-check fs-3"></i></a>
                                                <a href="{{route('final_del_prod',$prod->id)}}" class="product_xmark"><i
                                                        class="fa-solid fa-circle-xmark fs-3"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="m-auto text-center for-empty-div">
                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                            <h6>No Product Requset To Show</h6>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
