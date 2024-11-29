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
        var prod_id = $("#prod_id_"+id).val();
        var med_name = $("#medname_"+id).text();
        var sub_c = $("#subcat_"+id).text();
        var medgeneric = $("#medgeneric_"+id).text();
        var medclass = $("#medclass_"+id).text();
        var subcat = $("#sub_category_id_"+id).val();
        var is_single = $("#is_single_"+id).text();
        console.log(subcat)
        $("#edit_id").val(id);
        $("#edit_product_id").val(prod_id);
        $("#edit_med_name").val(med_name);
        $("#edit_sub_category").val(subcat);
        $("#edit_generic").val(medgeneric);
        $("#edit_class").val(medclass);
        if (is_single == 1){
            $('#edit_is_single').prop('checked',true);
        }else{
            $('#edit_is_single').prop('checked',false);
        }
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
        if (val == '') {
            window.location.href = '/pharmacy/admin/all/medicines';
        } else {
            $('#bodies').empty();
            $('#pag').html('');
            $.each(array, function(key, arr) {
                if ((arr.id != null && arr.id.toString().match(val)) || (arr.name != null &&
                        arr.name.toString().match(val))) {
                        $('#bodies').append('<tr id="body_'+arr.id+'"></tr>');
                        $('#body_'+arr.id).append(
                            `<td data-label="Sr_">${key}</td>
                            <input type="hidden" name="prod_id" id="prod_id_${arr.id}" value="${arr.id}">
                            <td data-label="Medicine Name" id="medname_${arr.id}">${arr.name}</td>
                            <td data-label="Generic" id="medname_${arr.id}">${arr.generic}</td>
                            <td data-label="Class" id="medname_${arr.id}">${arr.class}</td>
                            <td data-label="Product Sub Category" id="subcat_${arr.id}">${arr.title}</td>
                            <td data-label="Is Single" id="is_single_${arr.id}">${arr.is_single}</td>`
                        );


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
                                <button type="button" class="btn process-pay addMed" >Add Medicine Product</button>
                            </div>
                        </div>

                        <div class="wallet-table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr. </th>
                                        <th scope="col">Medicine Name</th>
                                        <th scope="col">Generic</th>
                                        <th scope="col">Class</th>
                                        <th scope="col">Product Sub Category</th>
                                        <th scope="col">Is Single</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="bodies">
                                    @forelse ($data as $key => $item)
                                        <tr>
                                            <input type="hidden" name="prod_id" id="prod_id_{{ $item->id }}" value="{{ $item->id }}">
                                            <input type="hidden" name="sub_category_id" id="sub_category_id_{{ $item->id }}" value="{{ $item->sub_category }}">
                                            <td data-label="Sr.">{{ $key + 1 + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                            <td data-label="Medicine Name" id="medname_{{$item->id}}">{{ $item->name }}</td>
                                            <td data-label="Generic" id="medgeneric_{{$item->id}}">{{ $item->generic }}</td>
                                            <td data-label="Class" id="medclass_{{$item->id}}">{{ $item->class }}</td>
                                            <td data-label="Product Sub Category" id="subcat_{{$item->id}}">{{ $item->title }}</td>
                                            <td data-label="Is Single" id="is_single_{{$item->id}}">{{ $item->is_single }}</td>
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
                                                                id="{{ $item->id }}">Delete</a></li>
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
                        <form action="{{ route('dash_store_medicine_product') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Medicine Name</label>
                                    <input class="form-control" id="med_name" name="med_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Sub Category</label>
                                    <select class="form-select" id="sub_category" name="sub_category">
                                        @foreach ($subs as $item)
                                            <option value="{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Generic</label>
                                    <input class="form-control" id="generic" name="generic" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Class</label>
                                    <input class="form-control" id="class" name="class" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="checkbox" id="is_single" name="is_single">
                                    <label for="is_single"> Is Single </label><br>
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
                <form action="{{ route('dash_edit_medicine_product') }}" method="POST">
                    @csrf
                <div class="modal-body">
                    <div class="dosage-body">
                        <form action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" id="edit_product_id" name="product_id" required>
                                    <label for="specialInstructions">Medicine Name</label>
                                    <input class="form-control" id="edit_med_name" name="med_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Sub Category</label>
                                    <select class="form-select" id="edit_sub_category" name="sub_category" readonly>
                                        @foreach ($subs as $item)
                                            <option value="{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Generic</label>
                                    <input class="form-control" id="edit_generic" name="generic" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Class</label>
                                    <input class="form-control" id="edit_class" name="class" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="checkbox" id="edit_is_single" name="is_single" value="1">
                                    <label for="edit_is_single"> Is Single </label><br>
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
        <form action="{{ route('dash_delete_medicine_product') }}" method="POST">
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
