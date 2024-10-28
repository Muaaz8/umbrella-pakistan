@extends('layouts.dashboard_imaging_editor')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
    
@section('page_title')
    <title>Imaging Categories</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script type="text/javascript">
    @php header("Access-Control-Allow-Origin: *"); @endphp
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function edit_img_cat(id)
    {
        var na = $('#na_'+id).val();
        $('#e_na').val(na);
        $('#e_id').val(id);
        $('#edit_main_category').modal('show');

    }

    function del_img_cat(id)
    {
        $('#d_id').val(id);
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
        window.location.href='/imaging/lab/categories';
      }
      else
      {
        $('#bodies').empty();
        $('#pag').html('');
        $.each (array, function (key, arr) {
          if((arr.id != null && arr.id.toString().match(val)) || (arr.name != null && arr.name.match(val)))
          {
            $('#bodies').append('<tr id="body_'+arr.id+'"></tr>');
            $('#body_'+arr.id).append('<td data-label="Test Code">'+arr.id+'</td>'
            +'<td data-label="Service Name">'+arr.name+'</td>'
            +'<input type="hidden" id="na_'+arr.id+'" value="'+arr.name+'">'
            +'<td data-label="Action"><div class="dropdown">'
            +'<button class="btn option-view-btn dropdown-toggle" type="button"'
            +' id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">OPTIONS</button>'
            +'<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">'
            +'<li><a class="dropdown-item" href="#" onclick="edit_img_cat('+arr.id+')">Edit</a></li>'
            +'<li><a class="dropdown-item" href="#" onclick="del_img_cat('+arr.id+')">Delete</a></li></ul></div></td>'
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
                              <h3>Main Categories</h3>
                            </div>
     
                          </div>
                      <div class="d-flex align-items-baseline flex-wrap justify-content-between p-0">
                      <div class="d-flex w-25">
                        <input type="text" id="search" autocomplete="off" class="form-control mb-1" placeholder="Search Category">
                        <button type="button" id="search_btn" onclick="search({{$data}})" class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                      </div>
                        <div>
                            <button type="button" class="btn process-pay" data-bs-toggle="modal" data-bs-target="#add_main_category">Add New</button>
                        </div>
                        </div>
    
                      <div class="wallet-table">
                        <table class="table" id="table">
                          <thead>
                              <th scope="col">ID</th>
                              <th scope="col">Name</th>
                              <th scope="col">Actions</th>
                          </thead>
                          <tbody id="bodies">
                            @forelse($productCategories as $pc)
                            <tr>
                                <td data-label="ID">{{$pc->id}}</td>
                                <td data-label="Name">{{$pc->name}}</td>
                                <input type="hidden" id="na_{{$pc->id}}" value="{{$pc->name}}">
                                <td data-label="Actions">
                                    <div class="dropdown">
                                    <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                      OPTIONS
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                      
                                      <li><a class="dropdown-item" href="#" onclick="edit_img_cat({{$pc->id}})">Edit</a></li>
                                      <li><a class="dropdown-item" href="#" onclick="del_img_cat({{$pc->id}})">Delete</a></li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              @empty
                              <tr>
                                <td colspan='3'>
                                    <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6> No Imaging Category</h6>
                                    </div>
                                    </td>
                                </tr>
                              @endforelse
                          </tbody>
                        </table>
                        <div id="pag">
                        {{ $productCategories->links('pagination::bootstrap-4') }}
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

  
          <!-- ------------------Add-Main-Categories-Modal-start------------------ -->
  
            <!-- Modal -->
            <div class="modal fade" id="add_main_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/create/imaging/category" method="POST">
                        @csrf
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Main Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Category Type</label>
                                  <select class="form-select" aria-label="Default select example">
                                    <option selected>imaging</option>
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
  
  
    <!-- ------------------Add-Main-Categories-Modal-end------------------ -->

      <!-- ------------------Edit-Main-Categories-Modal-start------------------ -->
  
            <!-- Modal -->
            <div class="modal fade" id="edit_main_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/edit/imaging/category" method="POST">
                        @csrf
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Main Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" name="name" id="e_na" class="form-control" placeholder="MRI">
                                    <input type="hidden" name="id" id="e_id" class="form-control" placeholder="MRI">
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Category Type</label>
                                  <select class="form-select" aria-label="Default select example">
                                    <option selected>imaging</option>
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
  
  
    <!-- ------------------Edit-Main-Categories-Modal-end------------------ -->

    <!-- ------------------Delete-Main-Category-Modal-start------------------ -->
  
            <!-- Modal -->
            <div class="modal fade" id="delete_main_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                <form action="/del/imaging/category" method="POST">
                    @csrf
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="delete-modal-body">
                      Are you sure you want to delete this category?
                      <input type="hidden" name="id" id="d_id">
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
