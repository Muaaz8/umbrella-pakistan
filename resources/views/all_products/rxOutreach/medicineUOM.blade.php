@extends('layouts.admin')
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link type="text/css" rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />


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
                            <h4 class="text-center">Medicines UOMs (unit of measurements)</h4>
                        </div>
                        <div class="body">
                            <div class="alert alert-danger showErr" style="display: none;">
                                <strong>ERROR!!! </strong> This unit already exist.
                            </div>
                            <div id="jsGrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .jsgrid-table th {
            color: black !important;
        }

    </style>
@endsection

@section('script')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
    <script>
        $("#jsGrid").jsGrid({
            width: "100%",
            height: "auto",
            filtering: true,
            inserting: true,
            deletion: true,
            editing: true,
            sorting: true,
            paging: true,
            autoload: true,
            deleteConfirm: "Do you really want to delete?",
            controller: {
                loadData: function(filter) {
                    return $.ajax({
                        type: "GET",
                        url: "/medicineUOM/1",
                        data: filter,
                        dataType: "json"
                    });
                },
                insertItem: function(item) {
                    return $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: "/medicineUOM",
                        data: item,
                        dataType: "json",
                        success: function(response) {
                            if (response == 0) {
                                $(".showErr").show().delay(3000).fadeOut();
                                $("#jsGrid").jsGrid();
                            }
                        },
                    });
                },
                updateItem: function(item) {
                    return $.ajax({
                        type: "PUT",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: "/medicineUOM/1",
                        data: item
                    });
                },
                deleteItem: function(item) {
                    return $.ajax({
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: "/medicineUOM/1",
                        data: item
                    });
                },
            },
            fields: [{
                name: "unit",
                type: "text",
                title: "Unit Of Measurement (UOM)",
                validate: "required"
            }, {
                type: "control"
            }]
        });
    </script>
@endsection
