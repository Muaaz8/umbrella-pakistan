@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css')}}">

@section('content')

    <section class="content home">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted ">Welcome to Umbrellamd</small>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    @include('flash::message')
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h3 class="text-center">Imaging All Records</h3>
                        </div>
                        @if (session()->get('msg'))
                            <div id="errorDiv1" class="alert alert-info col-12 col-md-6 offset-md-3">
                                @php
                                    $es = session()->get('msg');
                                @endphp
                                <span role="alert"> <strong>{{ $es }}</strong></span>

                            </div>
                        @endif
                        <div class="body">
                            <table class="table table-hover imagingAllServices" id="allProducts-table">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Parent Category</th>
                                        <th>Name</th>
                                        <th>CPT Code</th>
                                        <th>Price</th>
                                        <th>Address</th>
                                        <th>Location</th>
                                        <th>ZipCode</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
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
                                border: none;
                                border-radius: 5px;
                                padding: 5px 10px;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="deleteeModal" tabindex="-1" role="dialog" aria-labelledby="deleteeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteeModalLabel">You want to delete this pricing?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="del_price">
            </div>
            </div>
        </div>
        </div>

@endsection

@section('script')
    {{-- <script src="asset_admin/js/pages/index.js"></script>
    <script src="asset_admin/js/pages/charts/sparkline.min.js"></script> --}}
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/datatables.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/datatables.min.js">
    </script>
    <script>
        $(document).ready(function() {
            $('.imagingAllServices').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                'iDisplayLength': 10,
                "order": [
                    [0, "desc"]
                ],
                "processing": true,
                "ajax": "/get_imaging_services_all",
                "columns": [{
                        data: 'id'
                    },
                    {
                        data: 'product_category'
                    }, 
                    {
                        data: 'product_name'
                    },
                    {
                        data: 'cpt_code'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'location_name'
                    },
                    {
                        data: 'zip_code'
                    },
                    {
                        data: "id",
                        render: function(data, type, row) {
                            return `<button type="button" class="btn btn-primary" onclick="delete_price(${data})">Delete</button>`
                        }
                    }
                ]
            });
        });
        function delete_price(id)
        {
            $('#del_price').html('<div class="p-5 text-center">'+
                '<a href="/delete/price/'+id+'"><button type="button" class="btn btn-danger delete-m-btn me-2 text-white">Delete</button></a>'+
                '<button type="button" class="btn btn-primary delete-m-btn" data-dismiss="modal">No</button>'
            );
            $('#deleteeModal').modal('show');
        }
    </script>
@endsection
