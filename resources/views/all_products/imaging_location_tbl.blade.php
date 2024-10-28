@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css') }}">
@section('content')
    <section class="content home">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted">Welcome to Umbrellamd</small>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    @include('flash::message')
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2>Imaging Location </h2>


                            {{-- <h1 class="pull-right" style=" margin-top: -40px; ">
                            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('allProducts.create') }}">Add New</a>
                        </h1> --}}
                        </div>
                        <div class="body">
                            <table class="table table-hover tblData" id="allProducts-table">
                                <thead>
                                    <tr>
                                        <th>State</th>
                                        <th>City</th>
                                        <th>Zip Code</th>
                                        <th>Latitue</th>
                                        <th>Longitude</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($services as $service)
                                        <tr>
                                            <td>{{ $service->clinic_name }}</td>
                                            <td>{{ $service->city }}</td>
                                            <td>{{ $service->zip_code }}</td>
                                            <td>{{ $service->lat }}</td>
                                            <td>{{ $service->long }}</td>
                                            <td>{{ $service->address }}</td>
                                            <td>
                                                <div class="actions">
                                                    <?php $edit_url = 'allProducts/' . $service->id . '/edit?form_type=imaging_location'; ?>
                                                    <p class="font-18">
                                                        <a style=" margin-right: 10px; " href="{{ $edit_url }}"><i
                                                                class="fa fa-edit"></i></a>
                                                        <a href="/imaging_locations_delete/{{ $service->id }}"><i
                                                                class="fa fa-trash"></i></a>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <style>
                            .btns-group a {
                                margin-right: 10px;
                                color: black;
                                background: #eee;
                                padding: 5px 10px;
                                border-radius: 5px;
                            }

                            .btns-group button {
                                background: #eee;
                                border: none;
                                border-radius: 5px;
                                padding: 5px 10px;
                            }
                        </style>

                    </div>
                </div>
            </div>
        </div>

        </div>
    </section>
@endsection

@section('script')
    <script src="asset_admin/js/pages/index.js"></script>
    <script src="asset_admin/js/pages/charts/sparkline.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/datatables.min.css" />

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/datatables.min.js">
    </script>
    <script>
        $(document).ready(function() {
            $('.tblData').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
            });
        });
    </script>
@endsection
