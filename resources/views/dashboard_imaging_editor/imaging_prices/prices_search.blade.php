@extends('layouts.dashboard_imaging_editor')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title')
    <title>Imaging Prices</title>
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

    function edit_img_pr(id)
    {
        var pid = $('#pid_'+id).val();
        var pna = $('#pna_'+id).val();
        var lid = $('#lid_'+id).val();
        var lna = $('#lna_'+id).val();
        var pr = $('#pr_'+id).val();
        var ap = $('#ap_'+id).val();

        $('#e_pro').val(pid);
        $('#e_pro').text(pna);
        $('#e_loc').val(lid);
        $('#e_loc').text(lna);
        $('#e_ap').val(ap);
        $('#e_pr').val(pr);
        $('#e_id').val(id);

        $('#edit_imaging_price').modal('show');

    }

    function del_img_pr(id)
    {
        $('#d_id').val(id);
        $('#delete_imaging_price').modal('show');
    }

$(document).on('click', '.pagination a', function(event){
  event.preventDefault();
  var page = $(this).attr('href').split('page=')[1];
  fetch_data(page);

  function fetch_data(page)
  {
      var search = '{{$search ?? ''}}'
      $.ajax({
      url:"/search/imaging/lab/prices?page="+page,
      data: {
          search: search,
      },
      method: 'post',
      success:function(data)
      {
        console.log(data);
        $('#bodies').html('');
        $.each (data[0].data, function (key, val) {
          $('#bodies').append('<tr><td data-label="Parent Category">'+val.id+'</td>'
          +'<td data-label="Parent Category">'+val.cat_name+'</td>'
          +'<td data-label="Name">'+val.pro_name+'</td>'
          +'<td data-label="CPT Code">'+val.pro_cpt+'</td>'
          +'<td data-label="Actual Price">'+val.actual_price+'</td>'
          +'<td data-label="Price">'+val.price+'</td>'
          +'<td data-label="Address">'+val.loc_add+'</td>'
          +'<td data-label="Location">'+val.loc_st+'</td>'
          +'<td data-label="Zip Code">'+val.loc_zip+'</td>'
          +'<input type="hidden" id="pid_'+val.id+'" value="'+val.pro_id+'">'
          +'<input type="hidden" id="pna_'+val.id+'" value="'+val.pro_name+'">'
          +'<input type="hidden" id="lid_'+val.id+'" value="'+val.loc_id+'">'
          +'<input type="hidden" id="lna_'+val.id+'" value="'+val.loc_st+'">'
          +'<input type="hidden" id="pr_'+val.id+'" value="'+val.price+'">'
          +'<input type="hidden" id="ap_'+val.id+'" value="'+val.actual_price+'">'
          +'<td data-label="Actions">'
          +'<a class="btn btn-primary mb-1" href="#" onclick="edit_img_pr('+val.id+')">Edit</a>'
          +'<a class="btn btn-danger mb-1" href="#" onclick="del_img_pr('+val.id+')">Delete</a>'
          +'</td></tr>');
        });
      }
    });
  }
});
  $('.page-item').click(function(){
    $(".page-item").removeClass('active');
    $(this).addClass('active');
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
                              <h3>Imaging Price</h3>
                            </div>

                          </div>
                         <div class="d-flex align-items-baseline flex-wrap justify-content-between p-0">
                         <div class="d-flex w-25">
                         <form action="{{route('search_imaging_prices')}}" method="post">
                          @csrf
                            <input type="text" id="search" name="search" autocomplete="off" class="form-control mb-1" placeholder="Search Category">
                         </form>
                        </div>
                        <div>
                            <button type="button" class="btn process-pay" data-bs-toggle="modal" data-bs-target="#add_imaging_price">Add New</button>
                        </div>
                        </div>

                      <div class="wallet-table table-responsive" id="table">
                        <table class="table">
                          <thead>
                              <th scope="col">Id</th>
                              <th scope="col">Parent Category</th>
                              <th scope="col">Name</th>
                              <th scope="col">CPT Code</th>
                              <th scope="col">Actual Price</th>
                              <th scope="col">Price</th>
                              <th scope="col">Address</th>
                              <th scope="col">Location</th>
                              <th scope="col">Zip Code</th>
                              <th scope="col">Actions</th>
                          </thead>
                          <tbody id="bodies">
                            @forelse($prices as $price)
                              <tr>
                                <td data-label="Parent Category">{{$price->id}}</td>
                                <td data-label="Parent Category">{{$price->cat_name}}</td>
                                <td data-label="Name">{{$price->pro_name}}</td>
                                <td data-label="CPT Code">{{$price->pro_cpt}}</td>
                                <td data-label="Actual Price">{{$price->actual_price}}</td>
                                <td data-label="Price">{{$price->price}}</td>
                                <td data-label="Address">{{$price->loc_add}}</td>
                                <td data-label="Location">{{$price->loc_st}}</td>
                                <td data-label="Zip Code">{{$price->loc_zip}}</td>
                                <input type="hidden" id="pid_{{$price->id}}" value="{{$price->pro_id}}">
                                <input type="hidden" id="pna_{{$price->id}}" value="{{$price->pro_name}}">
                                <input type="hidden" id="lid_{{$price->id}}" value="{{$price->loc_id}}">
                                <input type="hidden" id="lna_{{$price->id}}" value="{{$price->loc_st}}">
                                <input type="hidden" id="pr_{{$price->id}}" value="{{$price->price}}">
                                <input type="hidden" id="ap_{{$price->id}}" value="{{$price->actual_price}}">
                                <td data-label="Actions">
                                      <a class="btn btn-primary mb-1" href="#" onclick="edit_img_pr({{$price->id}})">Edit</a>
                                      <a class="btn btn-danger mb-1" href="#" onclick="del_img_pr({{$price->id}})">Delete</a>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              @empty
                              <tr>
                            <td colspan='10'>
                                <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No Imaging Prices</h6>
                                </div>
                                </td>
                            </tr>
                              @endforelse
                          </tbody>
                        </table>
                        <div id="pag">
                        {{ $prices->links('pagination::bootstrap-4') }}
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


      <!-- ------------------Add-Imaging-Price-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="add_imaging_price" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/add/imaging/prices" method="POST">
                        @csrf
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Imaging Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Service Name</label>
                                    <select class="form-select" name="pro_id" aria-label="Default select example" required>
                                        <option value="" selected>Select Services</option>
                                        @foreach($product as $pro)
                                        <option value="{{$pro->id}}">{{$pro->name}}</option>
                                        @endforeach
                                      </select>
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Location</label>
                                  <select class="form-select" name="location_id" aria-label="Default select example" required>
                                    <option value="" selected>Select Location</option>
                                    @foreach($locations as $loc)
                                    <option value="{{$loc->id}}">{{$loc->city}}</option>
                                    @endforeach
                                  </select>
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="specialInstructions">price</label>
                                    <input type="text" name="actual_price" class="form-control" placeholder="Enter Actual Price" required>
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


    <!-- ------------------Add-Imaging-Price-Modal-end------------------ -->

      <!-- ------------------Edit-Imaging-Price-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="edit_imaging_price" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/edit/imaging/prices" method="POST">
                        @csrf
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Imaging Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Service Name</label>
                                    <select class="form-select" name="pro_id" aria-label="Default select example">
                                        <option id="e_pro" value="" selected>Select Services</option>
                                        @foreach($product as $pro)
                                        <option value="{{$pro->id}}">{{$pro->name}}</option>
                                        @endforeach
                                      </select>
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Location</label>
                                  <select class="form-select" name="location_id" aria-label="Default select example">
                                    <option id="e_loc" value="" selected>Select Location</option>
                                    @foreach($locations as $loc)
                                    <option value="{{$loc->id}}">{{$loc->city}}</option>
                                    @endforeach
                                  </select>
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Actual Price</label>
                                    <input type="text" name="actual_price" id="e_ap" class="form-control" placeholder="625">
                                    <input type="hidden" name="id" id="e_id" class="form-control" placeholder="625">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Sale Price</label>
                                    <input type="text" name="price" id="e_pr" class="form-control" placeholder="625">
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


    <!-- ------------------Edit-Imaging-Price-Modal-end------------------ -->

    <!-- ------------------Delete-imaging-price-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="delete_imaging_price" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                <form action="/del/imaging/prices" method="POST">
                    @csrf
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Price</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="delete-modal-body">
                      Are you sure you want to delete this price?
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

  <!-- ------------------Delete-imaging-price-Modal-end------------------ -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
