@extends('layouts.admin')
@section('css')
<link href="asset_admin/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Products Requests</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>All Products New Added Request </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-sweetalert">
                            <thead>
                                <th>Product Name</th>
                                <th>Product Mode</th>
                                <th>
                                    <center>Action</center>
                                </th>
                            </thead>
                            <tbody>
                                @forelse($new_added_products as $prod)
                                <tr>
                                    <td>{{ucfirst($prod->name)}}</td>
                                    <td>{{ucfirst($prod->mode)}}</td>
                                    <td>
                                        <center>
                                            <a href="{{route('add_approve_prod',$prod->id)}}" class="btn p-2 btn-primary my-0 btn-raised btn-circle waves-effect waves-circle waves-float"><i class="fa fa-check"></i></a>
                                            <a href="{{route('final_del_prod',$prod->id)}}" class="btn p-2 btn-danger my-0 btn-raised btn-circle waves-effect waves-circle waves-float"><i class="fa fa-trash"></i></a>
                                        </center>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <center>No Delete Requests</center>
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>All Products Delete Request </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-sweetalert">
                            <thead>
                                <th>Product Name</th>
                                <th>Product Mode</th>
                                <th>
                                    <center>Action</center>
                                </th>
                            </thead>
                            <tbody>
                                @forelse($prods as $prod)
                                <tr>
                                    <td>{{ucfirst($prod->name)}}</td>
                                    <td>{{ucfirst($prod->mode)}}</td>
                                    <td>
                                        <center>
                                            <a href="{{route('reset_del_prod',$prod->id)}}" class="btn p-2 btn-primary my-0 btn-raised btn-circle waves-effect waves-circle waves-float"><i class="fa fa-undo"></i></a>
                                            <a href="{{route('final_del_prod',$prod->id)}}" class="btn p-2 btn-danger my-0 btn-raised btn-circle waves-effect waves-circle waves-float"><i class="fa fa-trash"></i></a>

                                        </center>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <center>No Delete Requests</center>
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
</section>
@endsection