@extends('layouts.admin')

@section('content')
<section class="content home">
    <div class="container-fluid ">
        <div class="block-header">
            <h2>Umbrella Health Care Systems</h2>
            <small class="text-muted">Welcome to Umbrella Health Care Systems</small>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12" style="overflow-x:hidden">
                <div class="card">
                    <div class="header">
                        <h2 class="">Orders
                            <!-- <small>All Locations</small>  -->
                        </h2>

                    </div>
                    <div class="conte2nt">
                         <div class="clearfix"></div>

                        @include('flash::message')
                        <div class="row col-md-12 my-2 offset-md-8">
                            <!-- <label class="col-md-1" style="font-size:18px">Filters</label> -->
                            <!-- <select class="form-control col-md-2" style="border: grey 1px solid; border-radius: 5px;"
                                id="filters">
                                <option value="all">All</option>
                                <option value="past">Past</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="pending">Pending</option>
                                <option value="reported">Reported</option>
                            </select> -->
                        </div>
                        <div class="clearfix"></div>
                        <div class="box box-primary">
                            <div class="box-body">
                                @include('imaging.table')
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

@section('script')
<script src="{{asset('asset_admin/js/pages/index.js')}}"></script>
<script src="{{asset('asset_admin/js/pages/charts/sparkline.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('asset_admin/js/datatables/datatables.min.css')}}" />

<script type="text/javascript" src="{{asset('asset_admin/js/datatables/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('asset_admin/js/datatables/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{asset('asset_admin/js/datatables/datatables.min.js')}}"></script>
<script>
$(document).ready(function() {
    $('#tblOrders-table').DataTable();
});
$('#filters').on('change', function() {
    console.log();
    filter = $(this).val();
    $.get('/lab_orders_filter?filter=' + filter, function(data) {
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
                    data: "date"
                },
                {
                    data: "time"
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