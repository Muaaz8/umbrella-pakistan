@extends('layouts.dashboard_imaging_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
    
@section('page_title')
    <title>Imaging Services</title>
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

    function edit_img_ser(id)
    {
      var na = $('#na_'+id).val();
      var pc = $('#pc_'+id).val();
      var pct = $('#pct_'+id).val();
      var cp = $('#cp_'+id).val();
      var sd = $('#sd_'+id).val();
      var des = $('#des_'+id).val();
      $('#e_id').val(id);
      $('#e_na').val(na);
      $('#cp_c').val(cp);
      $('#s_d').val(sd);
      $('#des').val(des);
      $('#s_cat').val(pct);
      $('#s_cat').text(pc);
      $('#edit_service').modal('show');
        
    }

    function del_img_ser(id)
    {
      $('#id').val(id);
      $('#delete_service').modal('show');
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
        window.location.href='/imaging/lab/services';
      }
      else
      {
        $('#bodies').empty();
        $('#pag').html('');
        $.each (array, function (key, arr) {
          if((arr.product_name != null && arr.product_name.match(val)) || (arr.product_category != null && arr.product_category.match(val))
            || (arr.cpt_code != null && arr.cpt_code.toString().match(val)))
          {
            $('#bodies').append('<tr id="body_'+arr.id+'"></tr>');
            $('#body_'+arr.id).append('<td data-label="Test Code">'+arr.product_category+'</td>'
            +'<td data-label="Service Name">'+arr.product_name+'</td>'
            +'<td data-label="Sale Price">'+arr.cpt_code+'</td>'
            +'<input type="hidden" id="na_'+arr.id+'" value="'+arr.product_name+'">'
            +'<input type="hidden" id="pc_'+arr.id+'" value="'+arr.product_category+'">'
            +'<input type="hidden" id="pct_'+arr.id+'" value="'+arr.product_category_id+'">'
            +'<input type="hidden" id="cp_'+arr.id+'" value="'+arr.cpt_code+'">'
            +'<input type="hidden" id="sd_'+arr.id+'" value="'+arr.short_description+'">'
            +'<input type="hidden" id="des_'+arr.id+'" value="'+arr.description+'">'
            +'<td data-label="Action"><div class="dropdown">'
            +'<button class="btn option-view-btn dropdown-toggle" type="button"'
            +' id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">OPTIONS</button>'
            +'<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">'
            +'<li><a class="dropdown-item" href="#" onclick="edit_img_ser('+arr.id+')">Edit</a></li>'
            +'<li><a class="dropdown-item" href="#" onclick="del_img_ser('+arr.id+')">Delete</a></li></ul></div></td>'
            );
          }
        });
      }
    }
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector("#editor"));

document.querySelector("form").addEventListener("submit", (e) => {
  e.preventDefault();
  console.log(document.getElementById("editor").value);
});
  </script>
  <script>
    ClassicEditor.create(document.querySelector("#editors"));

document.querySelector("form").addEventListener("submit", (e) => {
  e.preventDefault();
  console.log(document.getElementById("editors").value);
});
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
                    <h3>Imaging Services</h3>
                  </div>

                </div>
                <div class="d-flex align-items-baseline flex-wrap justify-content-between p-0">
                    <div class="d-flex w-25">
                        <input type="text" id="search" autocomplete="off" class="form-control mb-1" placeholder="Search Category">
                        <button type="button" id="search_btn" onclick="search({{$data}})" class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                    </div>
                  <div>
                    <button type="button" class="btn process-pay" data-bs-toggle="modal"
                      data-bs-target="#add_service">Add New</button>
                  </div>
                </div>

                <div class="wallet-table">
                  <table class="table" id="table">
                    <thead>
                        <th scope="col">Category Name</th>
                        <th scope="col">Name</th>
                        <th scope="col">CPT Code</th>
                        <th scope="col">Actions</th>
                    </thead>
                    <tbody id="bodies">
                        @forelse($services as $ser)
                      <tr>
                        <td data-label="Category Name">{{$ser->product_category}}</td>
                        <td data-label="Name">{{$ser->product_name}}</td>
                        <td data-label="CPT Code">{{$ser->cpt_code}}</td>
                        <input type="hidden" id="na_{{$ser->id}}" value="{{$ser->product_name}}">
                        <input type="hidden" id="pc_{{$ser->id}}" value="{{$ser->product_category}}">
                        <input type="hidden" id="pct_{{$ser->id}}" value="{{$ser->product_category_id}}">
                        <input type="hidden" id="cp_{{$ser->id}}" value="{{$ser->cpt_code}}">
                        <input type="hidden" id="sd_{{$ser->id}}" value="{{$ser->short_description}}">
                        <input type="hidden" id="des_{{$ser->id}}" value="{{$ser->description}}">
                        <td data-label="Actions">
                          <div class="dropdown">
                            <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                              data-bs-toggle="dropdown" aria-expanded="false">
                              OPTIONS
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                              <li><a class="dropdown-item" href="#" onclick="edit_img_ser({{$ser->id}})">Edit</a></li>
                              <li><a class="dropdown-item" href="#" onclick="del_img_ser({{$ser->id}})" >Delete</a></li>
                            </ul>
                          </div>
                        </td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan='4'>
                        <div class="m-auto text-center for-empty-div">
                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                            <h6> No imaging service</h6>
                        </div>
                        </td>
                    </tr>
                      @endforelse
                    </tbody>
                  </table>
                  <div class="row d-flex justify-content-center">
                    <div id="pag" class="paginateCounter">
                        {{ $services->links('pagination::bootstrap-4') }}
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


  <!-- ------------------Add-imaging-service-Modal-start------------------ -->

  <!-- Modal -->
  <div class="modal fade" id="add_service" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="/add/imaging" method="POST">
            @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Imaging Service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="p-3">
              <div class="row">
                <div class="col-md-6">
                  <label for="specialInstructions">Service Name</label>
                  <input type="text" name="name" class="form-control" placeholder="" required>
                </div>
                <div class="col-md-6">
                  <label for="specialInstructions">CPT Code</label>
                  <input type="text" name="cpt_code" class="form-control" placeholder="" required>
                </div>

              </div>
              <div class="row mt-2">
                <div class="col-md-12">
                  <label for="specialInstructions">Imaging Category</label>
                  <select class="form-select" name="category" aria-label="Default select example" required>
                    <option value="" selected>Select Category</option>
                    @foreach($categories as $cat)
                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-md-12">
                  <label for="specialInstructions">Short Description:</label>
                  <!-- <textarea class="form-control" name="short_description" id="" rows="3" required></textarea> -->
                  <textarea name="short_description" id="editor"></textarea>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-md-12">
                  <label for="specialInstructions">Description:</label>
                  <textarea name="description" id="editors"></textarea>

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


  <!-- ------------------Add-imaging-service-Modal-end------------------ -->

  <!-- ------------------Edit-imaging-service-Modal-start------------------ -->

  <!-- Modal -->
  <div class="modal fade" id="edit_service" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
       <form action="/edit/imaging" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Imaging Service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="p-3">
              <div class="row">
                <div class="col-md-6">
                  <label for="specialInstructions">Service Name</label>
                  <input type="text" name="name" id="e_na" class="form-control" placeholder="CT ABDOMEN WO">
                  <input type="hidden" name="id" id="e_id" class="form-control" placeholder="CT ABDOMEN WO">
                </div>
                <div class="col-md-6">
                  <label for="specialInstructions">CPT Code</label>
                  <input type="text" name="cpt_code" id="cp_c" class="form-control" placeholder="74150">
                </div>

              </div>
              <div class="row mt-2">
                <div class="col-md-12">
                  <label for="specialInstructions">Imaging Category</label>
                  <select class="form-select" name="category" aria-label="Default select example">
                    <option id="s_cat" selected>Select Category</option>
                    @foreach($categories as $cat)
                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-md-12">
                  <label for="specialInstructions">Short Description:</label>
                  <textarea class="form-control" name="short_description" id="s_d" rows="3"></textarea>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-md-12">
                  <label for="specialInstructions">Description:</label>
                  <textarea class="form-control" name="description" id="des" rows="3"></textarea>
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


  <!-- ------------------Edit-imaging-service-Modal-end------------------ -->

  <!-- ------------------Delete-Service-Modal-start------------------ -->

  <!-- Modal -->
  <div class="modal fade" id="delete_service" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="/del/imaging" method="POST">
          @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="delete-modal-body">
            Are you sure you want to delete this service?
            <input type="hidden" name="id" id="id">
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