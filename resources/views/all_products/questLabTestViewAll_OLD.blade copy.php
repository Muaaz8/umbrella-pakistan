@extends('layouts.admin')
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link type="text/css" rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />


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
                            <h3 class="text-center">Quest Diagnostics Lab Tests</h3>
                        </div>
                        <div class="body">
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
        var db = {
            loadData: function(filter) {
                return $.grep(this.clients, function(client) {
                    return (!filter.id || client.id.indexOf(filter.id) > -1) &&
                        (!filter.TEST_CD || client.TEST_CD.indexOf(filter.TEST_CD) > -1) &&
                        (!filter.DESCRIPTION || client.DESCRIPTION.indexOf(filter.DESCRIPTION) > -1) &&
                        (!filter.PRICE || client.PRICE.indexOf(filter.PRICE) > -1)
                });
            },
            updateItem: function(item) {
                return $.ajax({
                    type: "PUT",
                    url: "/editQuestLabTestPrice",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: item
                });
            },
        };

        db.clients = <?= $dataTable ?>;

        $("#jsGrid").jsGrid({
            width: "100%",
            height: "auto",
            filtering: true,
            inserting: false,
            editing: true,
            sorting: true,
            paging: true,
            autoload: true,
            deleteConfirm: "Do you really want to delete?",
            controller: db,
            fields: [{
                    name: "TEST_CD",
                    type: "readonly",
                    validate: "required",
                    title: "TEST CODE",
                    width: "50"
                },
                {
                    name: "DESCRIPTION",
                    type: "text",
                    validate: "required",
                    title: "DESCRIPTION",
                    width: "150"
                },
                {
                    name: "TEST_NAME",
                    type: "text",
                    validate: "required",
                    title: "TEST_NAME",
                    width: "150"
                },
                {
                    name: "PRICE",
                    type: "text",
                    title: "PRICE",
                    width: "50"
                },
                {
                    name: "SALE_PRICE",
                    type: "text",
                    title: "SALE_PRICE",
                    width: "50"
                },
                {
                    name: "main_category_name",
                    title: "Category",
                    type: "select",
                    items: [{
                            Name: "",
                            Id: ''
                        },
                        {
                            Name: "Others",
                            Id: 'Others'
                        },
                        {
                            Name: "General Health",
                            Id: 'General Health'
                        }
                    ],
                    valueField: "Id",
                    textField: "Name"
                },
                {
                    type: "control",
                    deleteButton: false,
                    width: "100"
                }
            ]
        });
    </script>
@endsection
