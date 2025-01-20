@extends('layouts.dashboard_Pharm_editor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Pharmacy Editor Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script type="text/javascript">
$(document).on("click", '.view', function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    var name = $('#name'+id).text();
    var pid = $('#parent'+id).text();
    var pname = $('#parent_name'+id).text();
    var description = $('#description'+id).text();
    var created = $('#created'+id).text();
    var updated = $('#updated'+id).text();

    $('#title').text(name);
    // $('#title').text(pid);
    $('#pname').text(pname);
    $('#description').text(description);
    $('#created_at').text(created);
    $('#updated_at').text(updated);


    $('#view_subcategory').modal('show');
});

$(document).on("click", '.edit', function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    var name = $('#name'+id).text();
    var pname = $('#parent_name'+id).text();
    var description = $('#description'+id).text();
    // alert(pname);
    $('#edit_id').val(id);
    $('#edit_title').val(name);
    $('#edit_sub_cat').html("");
    $('#edit_sub_cat').append('<option value="'+pname+'">'+pname+'</option>');
    $('#edit_description').val(description);
    $('#edit_subcategory').modal('show');
});

$(document).on("click", '.delete', function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    $('#delete_id').val(id);
    $('#delete_sub_category').modal('show');
});

$(".add").click(function () {
    $.ajax({
        type: 'GET',
        url: "{{ URL('/getMainCategories') }}",
        success: function(res)
        {
            $('#sub_cat').html("");
            $.each(res, function (I, V) {
                 $('#sub_cat').append('<option value="'+V.id+'">'+V.name+'</option>');
            });
        }
    });
    $('#add_subcategory').modal('show');
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
            window.location.href = '/pharmacy/product/sub/categories';
        } else {
            $('#bodies').empty();
            $('#pag').html('');
            $.each(array, function(key, arr) {
                if ((arr.id != null && arr.id.toString().match(val)) || (arr.title != null &&
                        arr.title.toString().match(val)) ||
                    (arr.parent_id != null && arr.parent_id.toString().match(val)) || (arr.parent_name != null &&
                        arr.parent_name.toString().match(val))) {

                        $('#bodies').append('<tr>'
                            +'<td data-label="Sub ID" scope="row">'+arr.id+'</td>'
                            +'<td data-label="Sub Title" id="name'+arr.id+'">'+arr.title+'</td>'
                            +'<td data-label="Main ID" id="parent'+arr.id+'">'+arr.parent_id+'</td>'
                            +'<td data-label="Main Title" id="parent_name'+arr.id+'">'+arr.parent_name+'</td>'
                            +'<td data-label="Actions"><div class="dropdown">'
                            +'<button class="btn option-view-btn dropdown-toggle" type="button"'
                            +'id="dropdownMenuButton1" data-bs-toggle="dropdown"'
                            +'aria-expanded="false">OPTIONS</button>'
                            +'<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">'
                            +'<li><a class="dropdown-item view" id="'+arr.id+'" href="#" >View</a></li>'
                            +'<li><a class="dropdown-item edit" id="'+arr.id+'" href="#">Edit</a></li>'
                            +'<li><a class="dropdown-item delete" id="'+arr.id+'" href="#" >Delete</a></li>'
                            +'</ul></div></td></tr>'
                            +'<p id="description'+arr.id+'" hidden>'+arr.description+'</p>'
                            +'<p id="created'+arr.id+'" hidden >'+arr.created_at+'</p>'
                            +'<p id="updated'+arr.id+'" hidden >'+arr.updated_at+'</p>'
                            );
                    // $('#bodies').append('<tr id="body_' + arr.order_id + '">'+
                    //     '<td data-label="Order ID">' + arr.order_id + '</td>' +
                    //     '<td data-label="Order State">' + arr.order_state + '</td>' +
                    //     '<td data-label="Order Status">' + arr.order_status + '</td>' +
                    //     '<td data-label="Date">' + arr.created_at.split(' ')[0] + '</td>' +
                    //     '<td data-label="Time">' + arr.created_at.split(' ')[1] + '</td>' +
                    //     '<td data-label="Action">' +
                    //     '<a href="/pharmacy/order/'+arr.id+'">' +
                    //     '<button class="orders-view-btn">View</button></a></td></tr>'
                    // );
                }
            });
        }
    }
</script>
@endsection

@section('content')
{{-- {{ dd($productsSubCategories) }} --}}
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-end p-0">
                            <div>
                                <h3>Sub Categories</h3>
                            </div>

                        </div>
                        <div class="d-flex align-items-baseline flex-wrap justify-content-between p-0">
                            <div class="d-flex">
                                <input type="text" class="form-control mb-1" id="search"
                                    placeholder="Search">
                                <button type="button" id="search_btn"
                                    onclick="search({{ json_encode($data) }})" class="btn process-pay"><i
                                        class="fa-solid fa-search"></i></button>
                            </div>
                            <div>
                                <button type="button" class="btn process-pay add">Add New</button>
                            </div>
                        </div>

                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Sub ID</th>
                                        <th scope="col">Sub Title</th>
                                        <th scope="col">Main ID</th>
                                        <th scope="col">Main Title</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="bodies">
                                    @forelse ($productsSubCategories as $item)
                                        <p id="description{{ $item->id }}" hidden>{{ $item->description }}</p>
                                        <p id="created{{ $item->id }}" hidden >{{ $item->created_at }}</p>
                                        <p id="updated{{ $item->id }}" hidden >{{ $item->updated_at }}</p>
                                        <tr>
                                            <td data-label="Sub ID">{{ $item->id }}</td>
                                            <td data-label="Sub Title" id="name{{ $item->id }}">{{ $item->title }}</td>
                                            <td data-label="Main ID" id="parent{{ $item->id }}">{{ $item->parent_id }}</td>
                                            <td data-label="Main Title" id="parent_name{{ $item->id }}">{{ $item->parent_name }}</td>
                                            <td data-label="Action">
                                                <div class="dropdown">
                                                    <button class="btn option-view-btn dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        OPTIONS
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li><a class="dropdown-item view" id="{{ $item->id }}" href="#" >View</a></li>
                                                        <li><a class="dropdown-item edit" id="{{ $item->id }}" href="#">Edit</a></li>
                                                        <li><a class="dropdown-item delete" id="{{ $item->id }}" href="#" >Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="m-auto text-center for-empty-div">
                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                    <h6>No Sub Categories To Show</h6>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                            </table>
                            <div id="pag">
                            {{ $productsSubCategories->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                <!-- ------------------View-SubCategory-Details-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="view_subcategory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Subcategory View Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                          <form action="">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                  <p><span class="fw-bold">Title:</span>  <span id="title"></span></p>
                                </div>
                                <div class="col-md-6">
                                  <p><span class="fw-bold">Parent Name:</span> <span id="pname"></span></p>
                              </div>
                            </div>
                            <div class="row mt-2">
                              <div class="col-md-6">
                                <p><span class="fw-bold">Created At:</span> <span id="created_at"></span></p>
                            </div>
                                <div class="col-md-6">
                                  <p><span class="fw-bold">Updated At:</span> <span id="updated_at"></span></p>
                              </div>
                            </div>
                            <div class="row mt-2">
                              <div class="col-md-12">
                                <p><span class="fw-bold">Description:</span> <span id="description"></span></p>
                            </div>
                            </div>

                          </div>
                          </form>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                    </div>
                </div>
                </div>
            </div>


    <!-- ------------------View-SubCategory-Details-Modal-end------------------ -->

        <!-- ------------------SubCategories-Add-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="add_subcategory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Sub Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                          <form action="{{ route('pharmacy_editor_sub_cat_store') }}" method="post">
                            @csrf
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Main Category</label>
                                  <select class="form-select" name="sub_cat" id="sub_cat" aria-label="Default select example">
                                    <option selected>Pain Management</option>
                                    <option value="1">CT Scan</option>
                                    <option value="2">MR</option>
                                  </select>
                              </div>
                              <div class="col-md-12">
                              <label for="specialInstructions">Description</label>
                              <input type="text" name="description" id="description" class="form-control" placeholder="">
                              </div>
                              {{-- <div class="col-md-12">
                                <label for="specialInstructions">Thumbnail</label>
                                <input type="file" class="form-control" placeholder="">
                                </div> --}}
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


    <!-- ------------------SubCategories-Add-Modal-end------------------ -->


        <!-- ------------------SubCategories-Edit-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="edit_subcategory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Sub Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                          <form action="{{ route('pharmacy_editor_sub_cat_update') }}" method="post">
                            @csrf
                            <div class="p-3">
                            <div class="row">
                                <input type="hidden" name="edit_id" id="edit_id">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" id="edit_title" name="title" class="form-control" value="">
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Sub Category</label>
                                  <select class="form-select" name="sub_cat" id="edit_sub_cat" aria-label="Default select example">
                                    <option selected>Pain Management</option>
                                    <option value="1">CT Scan</option>
                                    <option value="2">MR</option>
                                  </select>
                              </div>
                              <div class="col-md-12">
                              <label for="specialInstructions">Description</label>
                              <input type="text" name="description" id="edit_description" class="form-control" value="">
                              </div>
                              <div class="col-md-12">
                                <label for="specialInstructions">Thumbnail</label>
                                <input type="file" class="form-control" placeholder="">
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


    <!-- ------------------SubCategories-Edit-Modal-end------------------ -->

    <!-- ------------------Delete-Sub-Category-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="delete_sub_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Sub Category</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('pharmacy_editor_sub_cat_delete') }}" method="post">
                    @csrf
                      <div class="modal-body">
                          <div class="delete-modal-body">
                          Are you sure you want to delete this Subcategory?
                          <input type="hidden" name="delete_id" id="delete_id">
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


  <!-- ------------------Delete-Sub-Category-Modal-end------------------ -->
@endsection
