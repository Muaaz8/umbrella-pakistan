@extends('layouts.dashboard_Pharm_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Pharmacy Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script type="text/javascript">
$(document).on("click", '.edit', function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    var text = $('#name'+id).text();
    $('#edit_id').val(id);
    $('#cat_name_edit').val(text);
    $('#edit_main_category').modal('show');
});

$(document).on("click", '.delete', function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    // var text = $('#name'+id).text();
    $('#delete_id').val(id);
    // $('#cat_name').val(text);
    $('#delete_main_category').modal('show');
});

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
            window.location.href = '/pharmacy/product/categories';
        } else {
            $('#bodies').empty();
            $.each(array, function(key, arr) {
                if ((arr.id != null && arr.id.toString().match(val)) || (arr.name != null &&
                        arr.name.toString().match(val)) ||
                        (arr.category_type != null && arr.category_type.toString().match(val))) {

                        $('#bodies').append('<tr><td data-label="ID" scope="row">'+arr.id+'</td>'
                            +'<td data-label="Name" id="name'+arr.id+'">'+arr.name+'</td>'
                            +'<td data-label="Category Type">'+arr.category_type+'</td>'
                            +'<td data-label="Actions"><div class="dropdown">'
                            +'<button class="btn option-view-btn dropdown-toggle" type="button"'
                            +'id="dropdownMenuButton1" data-bs-toggle="dropdown"'
                            +'aria-expanded="false">OPTIONS</button>'
                            +'<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">'
                            +'<li><a class="dropdown-item edit" id="'+arr.id+'" href="#">Edit</a></li>'
                            +'<li><a class="dropdown-item delete" id="'+arr.id+'" >Delete</a></li>'
                            +'</ul></div></td></tr>')
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
                                <h3>Main Categories</h3>
                            </div>

                        </div>
                        <div class="d-flex align-items-baseline flex-wrap justify-content-between p-0">
                            <div class="d-flex">
                                <input type="text" class="form-control mb-1" id="search"
                                    placeholder="Search">
                                <button type="button" id="search_btn"
                                    onclick="search({{ json_encode($productCategories) }})" class="btn process-pay"><i
                                        class="fa-solid fa-search"></i></button>
                            </div>
                            <div>
                                <button type="button" class="btn process-pay" data-bs-toggle="modal"
                                    data-bs-target="#add_main_category">Add New</button>
                            </div>
                        </div>

                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Category Type</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="bodies">
                                    @forelse ($productCategories as $item)
                                    <tr>
                                        <td data-label="ID" scope="row">{{ $item->id }}</td>
                                        <td data-label="Name" id="name{{ $item->id }}">{{ $item->name }}</td>
                                        <td data-label="Category Type">{{ $item->category_type }}</td>
                                        <td data-label="Actions">
                                            <div class="dropdown">
                                                <button class="btn option-view-btn dropdown-toggle" type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    OPTIONS
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                                    <li><a class="dropdown-item edit" id="{{ $item->id }}" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item delete" id="{{ $item->id }}" >Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="m-auto text-center for-empty-div">
                                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                <h6>No Categories To Show</h6>
                                            </div>
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
    </div>

    <!-- ------------------Add-Main-Categories-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="add_main_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Main Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form action="{{ route('pharmacy_editor_prod_cat_store') }}" method="post">
                            @csrf
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" class="form-control" name="cat_name" id="cat_name" placeholder="Category Name">
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Category Type</label>
                                  <select class="form-select" name="cat_type" aria-label="Default select example">
                                    <option selected value="medicine">Medicine</option>
                                  </select>
                              </div>
                            </div>

                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn process-pay">Save</button>
                          </div>
                        </form>

                    </div>
                </div>
                </div>
            </div>


    <!-- ------------------Add-Main-Categories-Modal-end------------------ -->

      <!-- ------------------Edit-Main-Categories-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="edit_main_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Main Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                          <form action="{{ route('pharmacy_editor_prod_cat_update') }}" method="post">
                            @csrf
                            <div class="p-3">
                                <input type="hidden" class="form-control" name="edit_id" id="edit_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" class="form-control" name="cat_name" id="cat_name_edit" placeholder="Pain & Fever">
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Category Type</label>
                                  <select class="form-select" name="cat_type" aria-label="Default select example">
                                    <option selected value="medicine">Medicine</option>
                                  </select>
                              </div>
                            </div>

                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn process-pay">Save</button>
                          </div>
                          </form>

                    </div>
                </div>
                </div>
            </div>


    <!-- ------------------Edit-Main-Categories-Modal-end------------------ -->

    <!-- ------------------Delete-Main-Category-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="delete_main_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('pharmacy_editor_prod_cat_delete') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="delete-modal-body">
                        Are you sure you want to delete this category?
                        <input type="hidden" class="form-control" name="delete_id" id="delete_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
              </div>
              </div>
          </div>


  <!-- ------------------Delete-Main-Category-Modal-end------------------ -->
@endsection
