@extends('layouts.dashboard_Lab_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
    
@section('page_title')
    <title>Quest Lab Tests</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script>
    @php header("Access-Control-Allow-Origin: *"); @endphp
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function edit_lab_category(id)
    {
        var na = $('#na_'+id).val();
        var sl = $('#ty_'+id).val();

        $('#e_name').val(na);
        $('#e_slug').val(sl);
        $('#e_id').val(id);

        $('#edit_main_category').modal('show');
    }

    function del_lab_cat(id)
    {
        $('#id').val(id);
        $('#delete_main_category').modal('show');
    }
    var input = document.getElementById("search");
  input.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
      document.getElementById("search_btn").click();
    }
  });
    function search(array)
    {
      var val = $('#search').val();
      if(val=='')
      {
        window.location.href='/lab/test/categories';
      }
      else
      {
        $('#bodies').empty();
        $('#pag').html('');
        $.each (array, function (key, arr) {
          if((arr.id != null && arr.id.toString().match(val)) || (arr.name != null && arr.name.match(val)))
          {
            $('#bodies').append('<tr id="body_'+arr.id+'"></tr>');
            $('#body_'+arr.id).append('<td data-label="ID">'+arr.id+'</td>'
            +'<td data-label="Name">'+arr.name+'</td>'
            +'<input type="hidden" id="na_'+arr.id+'" value="'+arr.name+'">'
            +'<input type="hidden" id="ty_'+arr.id+'" value="'+arr.slug+'">'
            +'<td data-label="Action"><div class="dropdown">'
            +'<button class="btn option-view-btn dropdown-toggle" type="button"'
            +' id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">OPTIONS</button>'
            +'<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">'
            +'<li><a class="dropdown-item" href="#" onclick="edit_lab_category('+arr.id+')">Edit</a></li>'
            +'<li><a class="dropdown-item" href="#" onclick="del_lab_cat('+arr.id+')">Delete</a></li>'
            );
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
                          <h3>Lab Test Categories</h3>
                        </div>
 
                      </div>
                      <div class="d-flex align align-items-baseline flex-wrap justify-content-between p-0">
                        <div class="d-flex align-items-baseline col-12 col-md-4 col-sm-6">
                            <input type="text" id="search" class="form-control mb-1" placeholder="Search Category">
                            <button type="button" id="search_btn" onclick="search({{$data}})" class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                        </div>
                    <div>
                        <button type="button" class="btn process-pay" data-bs-toggle="modal" data-bs-target="#add_main_category">Add New</button>
                    </div>
                    </div>

                  <div class="wallet-table">
                    <table class="table">
                      <thead>
                          <th scope="col">ID</th>
                          <th scope="col">Name</th>
                          <th scope="col">Options</th>
                      </thead>
                      <tbody id="bodies">
                      @forelse($productCategories  as $productCategory)
                        <tr>
                            <td data-label="ID">{{$productCategory->id}}</td>
                            <input type="hidden" id="na_{{$productCategory->id}}" value="{{$productCategory->name}}">
                            <td data-label="Name">{{$productCategory->name}}</td>
                            <input type="hidden" id="ty_{{$productCategory->id}}" value="{{$productCategory->slug}}">
                            <td data-label="Options">
                                <div class="dropdown">
                                <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  OPTIONS
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                  
                                  <li><a class="dropdown-item" href="#" onclick="edit_lab_category({{$productCategory->id}})" >Edit</a></li>
                                  <li><a class="dropdown-item" href="#" onclick="del_lab_cat({{$productCategory->id}})" >Delete</a></li>
                                </ul>
                              </div>
                            </td>
                          </tr>
                          @empty
                          <tr>
                                <td colspan='3'>
                                <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No products category</h6>
                                </div>
                                </td>
                            </tr>
                          @endforelse
                      </tbody>
                    </table>
                    <div class="row d-flex justify-content-center">
                        <div id="pag" class="paginateCounter">
                            {{ $productCategories->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                    <!-- <nav aria-label="..." class="float-end pe-3">
                      <ul class="pagination">
                        <li class="page-item disabled">
                          <span class="page-link">Previous</span>
                        </li>
                        <li class="page-item">
                          <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item active" aria-current="page">
                          <span class="page-link">2</span>
                        </li>
                        <li class="page-item">
                          <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                          <a class="page-link" href="#">Next</a>
                        </li>
                      </ul>
                    </nav> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>


          <!-- ------------------Add-LabTest-Categories-Modal-start------------------ -->
  
            <!-- Modal -->
            <div class="modal fade" id="add_main_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/create/lab/cat" method="POST">
                        @csrf
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Labtest Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="">
                                    <input type="hidden" name="slug" value="" class="form-control" placeholder="">
                                    <input type="hidden" name="created_by" value="{{auth()->user()->id}}" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Category Type</label>
                                  <select class="form-select" name="category_type" aria-label="Default select example">
                                    <option value="lab-test" selected>Lab Test</option>
                                  </select>
                              </div>
                            </div>
    
                          </div>
                        
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn process-pay">Add</button>
                    </div>
                </form>
                </div>
                </div>
            </div>
  
  
    <!-- ------------------Add-LabTest-Categories-Modal-end------------------ -->

      <!-- ------------------Edit-labtest-Categories-Modal-start------------------ -->
  
            <!-- Modal -->
            <div class="modal fade" id="edit_main_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                <form action="/update/lab/cat" method="POST">
                    @csrf
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Labtest Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" id="e_name" name="name" class="form-control" placeholder="General Health">
                                    <input type="hidden" id="e_slug" name="slug" class="form-control" placeholder="General Health">
                                    <input type="hidden" name="created_by" value="{{auth()->user()->id}}" class="form-control" placeholder="General Health">
                                    <input type="hidden" id="e_id" name="id" class="form-control" placeholder="General Health">
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Category Type</label>
                                  <select class="form-select" name="category_type" aria-label="Default select example">
                                    <option value="lab-test" selected>Lab Test</option>
                                  </select>
                              </div>
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
  
  
    <!-- ------------------Edit-labtest-Categories-Modal-end------------------ -->

    <!-- ------------------Delete-Main-Category-Modal-start------------------ -->
  
            <!-- Modal -->
            <div class="modal fade" id="delete_main_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                <form action="/del/lab/cat" method="POST">
                    @csrf
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="delete-modal-body">
                      Are you sure you want to delete this category?
                      <input type="hidden" id="id" name="id">
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
@endsection

  <!-- ------------------Delete-Main-Category-Modal-end------------------ -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
