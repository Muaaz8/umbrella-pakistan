@extends('layouts.admin')
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
<link rel="stylesheet" href="{{ asset('asset_admin/css/view_medicine.css') }}">
@section('content')
{{-- {{ dd($allProducts) }} --}}
    <section class="content home">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted">Welcome to Umbrellamd</small>
            </div>
            <div class="row clearfix">
                <div class="button-card pr-4 d-flex justify-content-end">
                    <button class="btn med-variation">
                        <a href="/viewMedicines?form_type=medicine_variation" class="text-white">Add Medicine Variations</a>
                    </button>
                </div>
                @if ($form_type == 'medicine_variation')
                    <div class="card mb-5 mt-3">
                        <div class="header">
                            <h4 class="text-center">Add Medicine Variations</h4>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 pt-3">
                            @include('flash::message')
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <form action="{{ url('medicinePricingVariation') }}" method="POST">
                                @csrf
                                <div class="row medicine-select">
                                    <div class="col-md-4">
                                        <label for="" class="prod-label">Medicine Products</label>
                                        <select name="product_id" id="" class="form-control medicine_product "
                                            required>
                                            <option value="" selected> Select option</option>
                                            @forelse ($allProducts as $prod)
                                                <option value="{{ $prod['id'] }}">{{ $prod['name'] }}
                                                </option>
                                            @empty
                                                <option value=""> No option</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="prod-label">Medicine Units</label>
                                        <select name="unit_id" id="" class="form-control medicine_unit" required>
                                            <option value="" selected> Select option</option>
                                            @forelse ($medicineUOM as $med)
                                                <option value="{{ $med['id'] }}">{{ $med['unit_name'] }}
                                                </option>
                                            @empty
                                                <option value=""> No option</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="prod-label">Medicine Days</label>
                                        <select name="days_id" id="" class="form-control medicine_days" required>
                                            <option value="" selected> Select option</option>
                                            @forelse ($medicineDays as $day)
                                                <option value="{{ $day['id'] }}">{{ $day['days'] }}
                                                </option>
                                            @empty
                                                <option value=""> No option</option>
                                            @endforelse
                                        </select>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="" class="prod-label">Price</label>
                                        <input type="text" maxlength="5"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                            name="price" id="" class="form-control prod-input" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="prod-label">Percentage%</label>
                                        <input type="text" maxlength="5"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                            name="percentage" id="" class="form-control prod-input" required>
                                    </div>
                                </div>

                                <div class="form-group text-center  mt-3">
                                    <button type="submit" class="btn callbtn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="header">
                        <h4 class="text-center">RxOutreach Medicine Catalogue</h4>
                    </div>
                    <div class="body">
                        <div id="jsGrid"></div>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.medicine_unit').select2({
                placeholder: 'Select Unit',
            });
            $('.medicine_days').select2({
                placeholder: 'Select Days',
            });
            $('.medicine_product').select2({
                placeholder: 'Select Product',
            });
        });
    </script>
    </script>
    <script>
        var sub_categoryG = '{{ $sub_category }}';
        var sub_category = sub_categoryG.replace(/(&quot\;)/g, "\"");
        var sub_category_items = JSON.parse(sub_category);

        var medicineUOM = '<?php echo json_encode($medicineUOM); ?>';
        var medicineUOMParse = JSON.parse(medicineUOM);

        var medicineDays = '<?php echo json_encode($medicineDays); ?>';
        var medicineDaysParse = JSON.parse(medicineDays);

        $("#jsGrid").jsGrid({
            width: "100%",
            height: "auto",
            filtering: true,
            inserting: false,
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
                        url: "/getRxMedicine",
                        data: filter,
                        dataType: "json"
                    });
                },
                updateItem: function(item) {
                    return $.ajax({
                        type: "PUT",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: "/editRxMedicine/1",
                        data: item

                    });
                },
                deleteItem: function(item) {
                    return $.ajax({
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: "/deleteRxMedicine/1",
                        data: item,
                        success: function(response) {
                            $("#jsGrid").jsGrid();
                        },
                    });
                },
            },
            fields: [{
                    name: "name",
                    type: "text",
                    title: "Medicine Name",
                    editing: false,
                },
                {
                    name: "unit",
                    type: "select",
                    items: medicineUOMParse,
                    valueField: "unit_id",
                    textField: "unit_name",
                    title: "Unit",
                    validate: "required",
                    filtering: false,
                },
                {
                    name: "days",
                    type: "select",
                    items: medicineDaysParse,
                    valueField: "days_id",
                    textField: "days",
                    title: "Days",
                    validate: "required",
                    filtering: false,
                },
                {
                    name: "price",
                    type: "text",
                    title: "Price"
                },
                {
                    name: "sale_price",
                    type: "text",
                    title: "Sale Price",
                    editing: false,

                },
                {
                    name: "percentage",
                    type: "text",
                    validate: "required",
                    title: "Percentage"

                },
                {
                    name: "sub_category",
                    type: "select",
                    items: sub_category_items,
                    valueField: "sub_category",
                    textField: "sub_category",
                    title: "Sub Category",
                    validate: "required"
                },
                {
                    name: "statusProduct",
                    title: "Status",
                    editing: false,
                    itemTemplate: function(value, item) {
                        var URL = "";
                        var btnClass = "";
                        var iconName = "";
                        if (value == 'Active') {
                            URL = "viewMedicines?product_id=" + item.product_id + "&status=deactive";
                            btnClass = "success";
                            iconName = "fa fa-check";
                        } else {
                            URL = "viewMedicines?product_id=" + item.product_id + "&status=active";
                            btnClass = "danger";
                            iconName = "fa fa-ban";
                        }
                        return $("<a class='btn btn-" + btnClass + " btn-xs mx-auto'>").attr("href", URL)
                            .html(
                                '<i style=" color: white; top: 0px; font-size: 17px;" class="' + iconName +
                                '" aria-hidden="true"></i>'
                            );
                    },
                    width: 50,
                    align: "center"
                },
                {
                    type: "control"
                }
            ]
        });
    </script>
@endsection
