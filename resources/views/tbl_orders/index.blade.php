@extends('layouts.admin')

@section('content')
    <section class="content home">
        <div class="container-fluid ">
            <div class="block-header">
                <h2>Umbrella Health Care Systems</h2>
                <small class="text-muted">Welcome to Umbrella Health Care Systems</small>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2 class="">Orders
                                <!-- <small>All Locations</small>  -->
                            </h2>

                        </div>
                        <div class="conte2nt">
                            <div class="clearfix"></div>

                            @include('flash::message')

                            <div class="clearfix"></div>
                            <div class="box box-primary">
                                <div class="box-body">
                                    @include('tbl_orders.table')
                                </div>
                            </div>
                            <div class="text-center">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
<style>
    .home {
        overflow-x: hidden !important;
    }

</style>
@section('script')
    <script src="{{ asset('asset_admin/js/pages/index.js') }}"></script>
    <script src="{{ asset('asset_admin/js/pages/charts/sparkline.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('asset_admin/js/datatables/datatables.min.css') }}" />

    <script type="text/javascript" src="{{ asset('asset_admin/js/datatables/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset_admin/js/datatables/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset_admin/js/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#tblOrders-table').DataTable();
        });
        // $('#filters').on('change', function() {
        //     console.log();
        //     filter = $(this).val();
        //     $.get('/lab_orders_filter?filter=' + filter, function(data) {
        //         var table = $('#tblOrders-table').DataTable();
        //         table.clear();
        //         table.destroy();
        //         console.log(data);
        //         appendData(data);
        //         //   $('#loading').hide();
        //     })
        // })
        $('#filters').on('change', function() {
            console.log();
            filter = $(this).val();
            $.get('/imaging_orders_filter?filter=' + filter, function(data) {
                var table = $('#tblOrders-table').DataTable();
                table.clear();
                table.destroy();
                console.log(data);
                appendData(data);
                //   $('#loading').hide();
            })
        })

        function appendData(data) {
            $(() => {
                $("#tblOrders-table").DataTable({
                    data: data,
                    columns: [{
                            data: "order_state"
                        },
                        {
                            data: "order_city"
                        },
                        {
                            data: "order_id"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.fname + ' ' + row.lname;
                            }
                        },
                        {
                            data: "address"
                        },
                        {
                            data: "name"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.lab_name + ', ' + row.lab_address;
                            }
                        },
                        {
                            data: "created_at['date']"
                        },
                        {
                            data: "created_at['time']"
                        },
                        {
                            data: "total"
                        },
                        {
                            data: "order_status",
                            render: function(data, type, row) {
                                return row.order_status.charAt(0).toUpperCase() + row.order_status
                                    .slice(1);
                            }
                        },
                        {
                            data: "id",
                            render: function(data, type, row) {
                                return '<div class="btns-group"><a href="/lab_order/' + row.id +
                                    '" class="action-btn"><i class="fa fa-eye"></i></a></div>'

                            }
                        }


                    ]
                });
            });


        }
    </script>
@endsection
