@extends('layouts.dashboard_imaging_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
    
@section('page_title')
    <title>Imaging Locations</title>
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

    function edit_img_loc(id)
    {
        var st = $('#st_'+id).val();
        var zip = $('#zip_'+id).val();
        var ci = $('#ci_'+id).val();

        $('#state').val(st);
        $('#zip').val(zip);
        $('#city').val(ci);
        $('#id').val(id);

        $('#edit_location').modal('show');

    }

    function del_img_loc(id)
    {
        $('#d_id').val(id);
        $('#delete_location').modal('show');
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
        window.location.href='/imaging/lab/locations';
      }
      else
      {
        $('#bodies').empty();
        $('#pag').html('');
        $.each (array, function (key, arr) {
          if((arr.clinic_name != null && arr.clinic_name.match(val)) || (arr.city != null && arr.city.match(val))
            || (arr.zip_code != null && arr.zip_code.toString().match(val)) || (arr.address != null && arr.address.toString().match(val)))
          {
            $('#bodies').append('<tr id="body_'+arr.id+'"></tr>');
            $('#body_'+arr.id).append('<td data-label="Test Code">'+arr.clinic_name+'</td>'
            +'<td data-label="Service Name">'+arr.city+'</td>'
            +'<td data-label="Sale Price">'+arr.zip_code+'</td>'
            +'<td data-label="Sale Price">'+arr.lat+'</td>'
            +'<td data-label="Sale Price">'+arr.long+'</td>'
            +'<td data-label="Sale Price">'+arr.address+'</td>'
            +'<input type="hidden" id="st_'+arr.id+'" value="'+arr.clinic_name+'">'
            +'<input type="hidden" id="zip_'+arr.id+'" value="'+arr.zip_code+'">'
            +'<input type="hidden" id="ci_'+arr.id+'" value="'+arr.city+'">'
            +'<td data-label="Action"><div class="dropdown">'
            +'<button class="btn option-view-btn dropdown-toggle" type="button"'
            +' id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">OPTIONS</button>'
            +'<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">'
            +'<li><a class="dropdown-item" href="#" onclick="edit_img_loc('+arr.id+')">Edit</a></li>'
            +'<li><a class="dropdown-item" href="#" onclick="del_img_loc('+arr.id+')">Delete</a></li></ul></div></td>'
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
                          <h3>Imaging Locations</h3>
                        </div>
 
                      </div>
                    <div class="d-flex align-items-baseline flex-wrap justify-content-between p-0">
                    <div class="d-flex w-25">
                        <input type="text" id="search" autocomplete="off" class="form-control mb-1" placeholder="Search Category">
                        <button type="button" id="search_btn" onclick="search({{$data}})" class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                    </div>
                    <div>
                        <button type="button" class="btn process-pay" data-bs-toggle="modal" data-bs-target="#add_location">Add New</button>
                    </div>
                    </div>

                  <div class="wallet-table table-responsive">
                    <table class="table" id="table">
                      <thead>
                          <th scope="col">State</th>
                          <th scope="col">City</th>
                          <th scope="col">Zip Code</th>
                          <th scope="col">Latitue</th>
                          <th scope="col">Longitude</th>
                          <th scope="col">Address</th>
                          <th scope="col">Actions</th>
                      </thead>
                      <tbody id="bodies">
                        @forelse($locations as $loc)
                        <tr>
                            <td data-label="State">{{$loc->clinic_name}}</td>
                            <td data-label="City">{{$loc->city}}</td>
                            <td data-label="Zip Code">{{$loc->zip_code}}</td>
                            <td data-label="Latitue">{{$loc->lat}}</td>
                            <td data-label="Longitude">{{$loc->long}}</td>
                            <td data-label="Address">{{$loc->address}}</td>
                            <input type="hidden" id="st_{{$loc->id}}" value="{{$loc->clinic_name}}">
                            <input type="hidden" id="zip_{{$loc->id}}" value="{{$loc->zip_code}}">
                            <input type="hidden" id="ci_{{$loc->id}}" value="{{$loc->city}}">
                            <td data-label="Actions">
                                <div class="dropdown">
                                <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  OPTIONS
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                  
                                  <li><a class="dropdown-item" href="#" onclick="edit_img_loc({{$loc->id}})">Edit</a></li>
                                  <li><a class="dropdown-item" href="#" onclick="del_img_loc({{$loc->id}})">Delete</a></li>
                                </ul>
                              </div>
                            </td>
                          </tr>
                          @empty
                          <tr>
                            <td colspan='7'>
                            <div class="m-auto text-center for-empty-div">
                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                <h6> No Imaging Locations</h6>
                            </div>
                            </td>
                        </tr>
                          @endforelse
                      </tbody>
                    </table>
                    <div id="pag">
                    {{ $locations->links('pagination::bootstrap-4') }}
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


      <!-- ------------------Add-imaging-location-Modal-start------------------ -->
  
            <!-- Modal -->
            <div class="modal fade" id="add_location" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/add/imaging/location" method="POST">
                        @csrf
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Imaging Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="specialInstructions">State:</label>
                                    <input type="text" name="clinic_name" class="form-control" placeholder="">
                                </div>
 

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">City:</label>
                                    <input type="text" name="city" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">ZipCode:</label>
                                    <input type="text" name="zip" class="form-control" placeholder="">
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
  
  
    <!-- ------------------Add-imaging-location-Modal-end------------------ -->

      <!-- ------------------Edit-imaging-location-Modal-start------------------ -->
  
            <!-- Modal -->
            <div class="modal fade" id="edit_location" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/edit/imaging/location" method="POST">
                        @csrf
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Imaging Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="specialInstructions">State:</label>
                                    <input type="text" name="clinic_name" id="state" class="form-control" placeholder="Alabama">
                                    <input type="hidden" name="id" id="id" class="form-control" placeholder="Alabama">
                                </div>
 

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">City:</label>
                                    <input type="text" name="city" id="city" class="form-control" placeholder="Alabaster">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">ZipCode:</label>
                                    <input type="text" name="zip" id="zip" class="form-control" placeholder="35007">
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
  
  
    <!-- ------------------Edit-imaging-location-Modal-end------------------ -->

    <!-- ------------------Delete-Service-Modal-start------------------ -->
  
            <!-- Modal -->
            <div class="modal fade" id="delete_location" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                <form action="/del/imaging/location" method="POST">
                    @csrf
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Location</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="delete-modal-body">
                      Are you sure you want to delete this location?
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