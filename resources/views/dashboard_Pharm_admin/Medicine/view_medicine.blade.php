@extends('layouts.dashboard_Pharm_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('page_title')
    <title>UHCS - Pharmacy Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function create_custom_dropdowns() {
        $('.med-pro-select select').each(function (i, select) {
            if (!$(this).next().hasClass('dropdown-select')) {
                $(this).after('<div class="dropdown-select wide ' + ($(this).attr('class') || '') + '" tabindex="0"><span class="current"></span><div class="list"><ul></ul></div></div>');
                var dropdown = $(this).next();
                var options = $(select).find('option');
                var selected = $(this).find('option:selected');
                dropdown.find('.current').html(selected.data('display-text') || selected.text());
                options.each(function (j, o) {
                    var display = $(o).data('display-text') || '';
                    dropdown.find('ul').append('<li class="option ' + ($(o).is(':selected') ? 'selected' : '') + '" data-value="' + $(o).val() + '" data-display-text="' + display + '">' + $(o).text() + '</li>');
                });
            }
        });

        $('.dropdown-select ul').before('<div class="dd-search"><input id="txtSearchValue" autocomplete="off" onkeyup="filter()" class="dd-searchbox" type="text"></div>');
    }

    // Close when clicking outside
    $(document).on('click', function (event) {
        if ($(event.target).closest('.med-pro-select .dropdown-select').length === 0) {
            $('.dropdown-select').removeClass('open');
            $('.dropdown-select .option').removeAttr('tabindex');
        }
        event.stopPropagation();
    });

    function filter(){
        var valThis = $('#txtSearchValue').val();
        $('.dropdown-select ul > li').each(function(){
        var text = $(this).text();
            (text.toLowerCase().indexOf(valThis.toLowerCase()) > -1) ? $(this).show() : $(this).hide();
    });
    };
    //Search

    //Option click
    $(document).on('click', '.med-pro-select .dropdown-select .option', function (event) {
        $(this).closest('.list').find('.selected').removeClass('selected');
        $(this).addClass('selected');
        var text = $(this).data('display-text') || $(this).text();
        $(this).closest('.dropdown-select').find('.current').text(text);
        $(this).closest('.dropdown-select').prev('select').val($(this).data('value')).trigger('change');
    });

    //Keyboard events
    $(document).on('keydown', '.med-pro-select .dropdown-select', function (event) {
        var focused_option = $($(this).find('.list .option:focus')[0] || $(this).find('.list .option.selected')[0]);
        // Space or Enter
        //if (event.keyCode == 32 || event.keyCode == 13) {
        if (event.keyCode == 13) {
            if ($(this).hasClass('open')) {
                focused_option.trigger('click');
            } else {
                $(this).trigger('click');
            }
            return false;
            // Down
        } else if (event.keyCode == 40) {
            if (!$(this).hasClass('open')) {
                $(this).trigger('click');
            } else {
                focused_option.next().focus();
            }
            return false;
            // Up
        } else if (event.keyCode == 38) {
            if (!$(this).hasClass('open')) {
                $(this).trigger('click');
            } else {
                var focused_option = $($(this).find('.list .option:focus')[0] || $(this).find('.list .option.selected')[0]);
                focused_option.prev().focus();
            }
            return false;
            // Esc
        } else if (event.keyCode == 27) {
            if ($(this).hasClass('open')) {
                $(this).trigger('click');
            }
            return false;
        }
    });

    // $(document).ready(function () {
    //     create_custom_dropdowns();
    // });
</script>
{{-- <script src={{ asset('assets/js/custom.js') }}></script> --}}


<script>
    $(document).on("click", '.edit', function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var unit = $("#un_"+id).text();
        var med_name = $("#medname_"+id).text();
        var days = $("#dy_"+id).text();
        var price = $("#p_"+id).text();
        var percentage = $("#per_"+id).text();
        var sub_c = $("#sc_"+id).text();
        var prod_id = $("#prod_id_"+id).val();

        $("#edit_id").val(id);
        $('#unit').append('<option selected value="'+unit+'">'+unit+'</option>');
        $('#days').append('<option selected value="'+days+'">'+days+'</option>');
        $("#edit_price").val(price);
        $("#edit_med_name").val(med_name);
        $("#pro_id").val(prod_id);
        $("#edit_sub_category").append('<option selected value="'+sub_c+'">'+sub_c+'</option>');
        $('#edit_medicine_variations').modal('show')
    });


    $(document).on("click", '.delete', function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        $("#del_id").val(id);
        $('#delete_medicine').modal('show')
    });

    $('.addMed').click(function(){
        $('#add_medicine_variations').modal('show')
    })
</script>

<script>
    var input = document.getElementById("search");
    input.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            document.getElementById("search_btn").click();
        }
    });

    function search(array) {
        var val = $('#search').val();
        console.log(val,array);
        if (val == '') {
            window.location.href = '/pharmacy/medicine/view';
        } else {
            $('#bodies').empty();
            $('#pag').html('');
            $.each(array, function(key, arr) {
                if ((arr.id != null && arr.id.toString().match(val)) || (arr.name != null &&
                        arr.name.toString().match(val)) ||
                        (arr.category_type != null && arr.category_type.toString().match(val))) {

                        $('#bodies').append('<tr id="body_'+arr.id+'"></tr>');
                        $('#body_'+arr.id).append(
                            +'<input type="hidden" name="prod_id" id="prod_id_'+arr.id+'" value="'+arr.product_id+'">'
                            +'<td data-label="Sr ">'+ ++key +'</td>'
                            +'<td data-label="Medicine Name" id="medname_'+arr.id+'">'+arr.name+'</td>'
                            +'<td data-label="Unit" id="un_'+arr.id+'">'+arr.unit+'</td>'
                            +'<td data-label="Price" id="p_'+arr.id+'">'+arr.price+'</td>'
                            +'<td data-label="Sale Price">'+arr.sale_price+'</td>'
                            +'<td data-label="Sub Category" id="sc_'+arr.id+'">'+arr.sub_category+'</td>'
                            +'<td data-label="Updated At" >'+arr.updated_at+'</td>'
                        );
                        if(arr.statusProduct == 'Active'){
                            $('#body_'+arr.id).append('<td data-label="Status"><select class="form-select w-100 m-sm-0 ad_act_dact m-md-auto"'
                            +'onchange="window.location.href=\'view?product_id='+arr.id+'&status=deactive\'"'
                            +'aria-label="Default select example"><option selected>Active</option>'
                            +'<option value="1">Deactivate</option></select>'
                            );
                        }
                        else {
                            $('#body_'+arr.id).append('<td data-label="Status"><select class="form-select w-100 m-sm-0 ad_act_dact m-md-auto"'
                            +'onchange="window.location.href=\'view?product_id='+arr.id+'&status=deactive\'"'
                            +'aria-label="Default select example"><option selected>Deactive</option>'
                            +'<option value="1">Activate</option></select>');
                        }

                        $('#body_'+arr.id).append('</td><td data-label="Actions">'
                            +'<div class="dropdown"><button class="btn option-view-btn dropdown-toggle" type="button"'
                            +'id="dropdownMenuButton1" data-bs-toggle="dropdown"aria-expanded="false">'
                            +'OPTIONS</button><ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">'
                            +'<li><a class="dropdown-item edit"id="'+arr.id+'">Edit</a></li><li><a class="dropdown-item delete"'
                            +'id="'+arr.product_id+'">Delete</a></li></ul></div></td>');
                }
            });
        }
    }
</script>
@endsection


@section('content')
{{-- {{ dd($units) }} --}}
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-end p-0">
                            <div>
                                <h3>Medicine Catalogue</h3>
                            </div>

                        </div>
                        <div class="d-flex flex-wrap align-items-baseline justify-content-between p-0">
                            <div class="d-flex">
                                <input type="text" class="form-control mb-1" id="search"
                                    placeholder="Search">
                                <button type="button" id="search_btn"
                                    onclick="search({{ json_encode($data1) }})" class="btn process-pay"><i
                                        class="fa-solid fa-search"></i></button>
                            </div>
                            <div>
                                <button type="button" class="btn process-pay addMed" >Add Medicine Variant</button>
                            </div>
                        </div>

                        <div class="wallet-table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr. </th>
                                        <th scope="col">Medicine Name</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Sale Price</th>
                                        <th scope="col">Sub Category</th>
                                        <th scope="col">Updated At</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>


                                    </tr>
                                </thead>
                                <tbody id="bodies">
                                    @forelse ($data as $key => $item)
                                        <tr>
                                            <input type="hidden" name="prod_id" id="prod_id_{{ $item->id }}" value="{{ $item->product_id }}">
                                            <td data-label="Medicine Name">{{ $key + 1 + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                            <td data-label="Medicine Name" id="medname_{{$item->id}}">{{ $item->name }}</td>
                                            <td data-label="Unit" id="un_{{ $item->id }}">{{ $item->unit }}</td>
                                            <td data-label="Price" id="p_{{ $item->id }}">{{ $item->price }}</td>
                                            <td data-label="Sale Price">{{ $item->sale_price }}</td>
                                            <td data-label="Sub Category" id="sc_{{ $item->id }}">{{ $item->sub_category }}</td>
                                            <td data-label="Updated At" >{{ $item->updated_at }}</td>


                                            <td data-label="Status">
                                                @if ($item->statusProduct == 'Active')
                                                    <select class="form-select w-100 m-sm-0 ad_act_dact m-md-auto"
                                                        onchange="window.location.href='view?product_id={{ $item->id }}&status=deactive'"
                                                        aria-label="Default select example">
                                                        <option selected>Active</option>
                                                        <option value="1">Deactivate</option>
                                                    </select>
                                                @else
                                                    <select class="form-select w-100 m-sm-0 ad_act_dact m-md-auto"
                                                        onchange="window.location.href='view?product_id={{ $item->id }}&status=active'"
                                                        aria-label="Default select example">
                                                        <option selected>Deactive</option>
                                                        <option value="1">Activate</option>
                                                    </select>
                                                @endif
                                            </td>
                                            <td data-label="Actions">
                                                <div class="dropdown">
                                                    <button class="btn option-view-btn dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        OPTIONS
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                                        <li><a class="dropdown-item edit"
                                                                id="{{ $item->id }}">Edit</a></li>
                                                        <li><a class="dropdown-item delete"
                                                                id="{{ $item->id }}">Delete All Pricing</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>
                                                No Medicines to Show
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div id="pag">
                            {{ $data->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ------------------Add-Medicine-Variant-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="add_medicine_variations" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Medicine Variant</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="dosage-body">
                        <form action="{{ route('dash_store_Medicine_Variation') }}" method="POST">
                            @csrf
                            {{-- <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Medicine Products</label>
                                    <div class="med-pro-select">
                                        <div class="main">
                                            <select name="product_id" class="modal_prod" >
                                                @foreach ($allProducts as $prod)
                                                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialization_status">Medicine Units</label>
                                    <div class="med-pro-select">
                                        <div class="main">
                                            <select name="unit_id" class="modal_units">
                                                @foreach ($units as $u)
                                                    <option value="{{ $u->id }}">{{ $u->unit }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Medicine Days</label>
                                    <div class="med-pro-select">
                                        <div class="main">
                                            <select name="days_id" class="modal_day">
                                                @foreach ($days as $d)
                                                    <option value="{{ $d->id }}">{{ $d->days }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <label for="specialInstructions">Price</label>
                                    <input type="text" class="form-control" name="price" placeholder="Price">
                                </div>

                            </div>

                            <div class="row mt-3 mb-3">
                                <div class="col-md-6">
                                    <label for="specialization_status">Percentage</label>
                                    <input type="text" class="form-control" name="percentage" placeholder="Percentage">
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Medicine Products</label>
                                    <div class="med-pro-select">
                                        <div class="main">
                                            <select name="product_id" class="modal_prod" >
                                                @foreach ($allProducts as $prod)
                                                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialization_status">Medicine Units</label>
                                    <div class="med-pro-select">
                                        <div class="main">
                                            <select name="unit_id" class="modal_units">
                                                @foreach ($units as $u)
                                                    <option value="{{ $u->id }}">{{ $u->unit }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Sub Category</label>
                                    <select class="form-select" id="sub_category" name="sub_category" readonly>
                                        @foreach ($subs as $item)
                                            <option value="{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialization_status">Price</label>
                                    <input type="text" id="price" name="price" class="form-control" placeholder="Price">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn con-recomm-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ------------------Add-Medicine-Variant-Modal-end------------------ -->


    <!-- ------------------Edit-Medicine-Variant-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="edit_medicine_variations" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Medicine Variant</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dash_editRxMedicine') }}" method="POST">
                    @csrf
                <div class="modal-body">
                    <div class="dosage-body">
                        <form action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Medicine Name</label>
                                    <input class="form-control" id="edit_med_name" name="med_name" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="hidden" name="id" id="edit_id">
                                    <input type="hidden" name="product_id" id="pro_id">
                                    <label for="specialInstructions">Unit</label>
                                    <select class="form-select" id="unit" name="unit" required readonly>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Sub Category</label>
                                    <select class="form-select" id="edit_sub_category" name="sub_category" readonly>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialization_status">Price</label>
                                    <input type="text" id="edit_price" name="price" class="form-control" placeholder="Price">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn con-recomm-btn">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ------------------Edit-Medicine-Variant-Modal-end------------------ -->

    <!-- ------------------Delete-Medicine-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="delete_medicine" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('dash_deleteRxMedicine') }}" method="POST">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Medicine</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="delete-modal-body">
                            Are you sure you want to Delete this Medicine?
                            <input type="hidden" name="del_id" id="del_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- ------------------Delete-Medicine-Modal-end------------------ -->
@endsection
