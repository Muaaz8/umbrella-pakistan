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
<script type="text/javascript">
    @php header("Access-Control-Allow-Origin: *"); @endphp
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function edit_quest_lab(id)
    {
        var name = $('#nam_'+id).val();
        var des = $('#des_'+id).val();
        var pr = $('#pr_'+id).val();
        var sp = $('#sp_'+id).val();
        var cn = $('#cn_'+id).val();
        var det = $('#det_'+id).val();

        $('#test_cd').val(id);
        $('#sn').val(det);
        $('#tn').val(name);
        $('#pr').val(pr);
        $('#sp').val(sp);
        $('#des').text(des);
        $('#cat').html('');
        $('#cat').html('<option value="'+cn+'">'+cn+'</option>');
        $('#edit_quest_labtests').modal('show');

    }

    var input = document.getElementById("search");
  input.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
      document.getElementById("search_btn").click();
    }
  });

    function search(array)
    {
      var val = $('#search').val().toUpperCase();
      if(val=='')
      {
        window.location.href='/quest/lab/tests';
      }
      else
      {
        $('#bodies').empty();
        $('#pag').html('');
        $.each (array, function (key, arr) {
          if((arr.TEST_CD != null && arr.TEST_CD.match(val)) || (arr.DESCRIPTION != null && arr.DESCRIPTION.match(val))
            || (arr.TEST_NAME != null && arr.TEST_NAME.match(val)) || (arr.PRICE != null && arr.PRICE.toString().match(val))
            || (arr.SALE_PRICE != null && arr.SALE_PRICE.toString().match(val)))
          {
            $('#bodies').append('<tr id="body_'+arr.TEST_CD+'"></tr>');
            $('#body_'+arr.TEST_CD).append('<td data-label="Test Code">'+arr.TEST_CD+'</td>'
            +'<td data-label="Service Name">'+arr.DESCRIPTION+'</td>'
            +'<input type="hidden" id="des_'+arr.TEST_CD+'" value="'+arr.DESCRIPTION+'">'
            +'<td data-label="Full Name">'+arr.TEST_NAME+'</td>'
            +'<input type="hidden" id="nam_'+arr.TEST_CD+'" value="'+arr.TEST_NAME+'">'
            +'<td data-label="Price">'+arr.PRICE+'</td>'
            +'<input type="hidden" id="pr_'+arr.TEST_CD+'" value="'+arr.PRICE+'">'
            +'<td data-label="Sale Price">'+arr.SALE_PRICE+'</td>'
            +'<input type="hidden" id="sp_'+arr.TEST_CD+'" value="'+arr.SALE_PRICE+'">'
            +'<td data-label="Category">'+arr.main_category_name+'</td>'
            +'<input type="hidden" id="cn_'+arr.TEST_CD+'" value="'+arr.main_category_name+'">'
            +'<input type="hidden" id="det_'+arr.TEST_CD+'" value="'+arr.DETAILS+'">'
            +'<td data-label="Action"><button onclick="edit_quest_lab('+arr.TEST_CD+')" class="orders-view-btn" >Edit</button></td>'
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
                      <div class="d-flex justify-content-between flex-wrap align-items-baseline p-0">
                        <h3>Labtests</h3>
                        <div class="col-md-4 p-0">
                          <div class="input-group">
                            <input
                              type="text"
                              id="search"
                              class="form-control"
                              placeholder="Search"
                              aria-label="Username"
                              aria-describedby="basic-addon1"
                            />
                            <button type="button" id="search_btn" onclick="search({{json_encode($da)}})" class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                      <div class="wallet-table table-responsive">
                        <table class="table">
                          <thead>
                              <th scope="col">Test Code</th>
                              <th scope="col">Service Name</th>
                              <th scope="col">Full Name</th>
                              <th scope="col">Price</th>
                              <th scope="col">Sale Price</th>
                              <th scope="col">Category</th>
                              <th scope="col">Action</th>
                          </thead>
                          <tbody id="bodies">
                          @forelse($data as $item)
                            <tr>
                                <td data-label="Test Code">{{ $item->TEST_CD }}</td>
                                <td data-label="Service Name">{{ $item->DESCRIPTION }}</td>
                                <input type='hidden' id="des_{{ $item->TEST_CD }}" value="{{ $item->DESCRIPTION }}">
                                <td data-label="Full Name">{{ $item->TEST_NAME }}</td>
                                <input type='hidden' id="nam_{{ $item->TEST_CD }}" value="{{ $item->TEST_NAME }}">
                                <td data-label="Price">{{ $item->PRICE }}</td>
                                <input type='hidden' id="pr_{{ $item->TEST_CD }}" value="{{ $item->PRICE }}">
                                <td data-label="Sale Price">{{ $item->SALE_PRICE }}</td>
                                <input type='hidden' id="sp_{{ $item->TEST_CD }}" value="{{ $item->SALE_PRICE }}">
                                <td data-label="Category">{{ $item->main_category_name }}</td>
                                <input type='hidden' id="cn_{{ $item->TEST_CD }}" value="{{ $item->main_category_name }}">
                                <input type='hidden' id="det_{{ $item->TEST_CD }}" value="{{ $item->DETAILS }}">
                                <td data-label="Action"><button onclick="edit_quest_lab({{$item->TEST_CD}})" class="orders-view-btn" >Edit</button></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan='7'>
                                <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No Orders</h6>
                                </div>
                                </td>
                            </tr>
                            @endforelse
                          </tbody>
                        </table>
                        <div class="row d-flex justify-content-center">
                            <div id="pag" class="paginateCounter">
                                {{ $data->links('pagination::bootstrap-4') }}
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


      <!-- ------------------Edit-Quest-labtest-Categories-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="edit_quest_labtests" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                <form action="/edit/lab/test" method="POST">
                    @csrf
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Quest Labtest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                          <form action="">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Code</label>
                                    <input type="text" id="test_cd" name="test_cd" class="form-control" placeholder="223" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Service Name</label>
                                    <input type="text" id="sn" name="sn" class="form-control" placeholder="ALBUMIN">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Name</label>
                                    <input type="text" id="tn" name="tn" class="form-control" placeholder="ALBUMIN">
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Category</label>
                                  <select class="form-select" name="cat" aria-label="Default select example" disabled>
                                    <option id="cat" selected>Digestive Health</option>
                                    <option value="1">DNA</option>
                                    <option value="2">Drugs and Alcohol</option>
                                  </select>
                              </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Price</label>
                                    <input type="text" id="pr"  name="pr" class="form-control" placeholder="2.5">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Sale Price</label>
                                    <input type="text" id="sp" name="sp" class="form-control" placeholder="15">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Description</label>
                                    <textarea class="form-control" id="des" name="des" rows="5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente ullam ducimus reiciendis quia eos veniam animi porro placeat corrupti dolore dignissimos excepturi numquam illum quas sunt recusandae dolorem quisquam odit explicabo, nulla saepe corporis exercitationem? Hic accusamus eius non incidunt aperiam esse, cupiditate fugit voluptas officia beatae molestiae atque velit?</textarea>
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
@endsection

    <!-- ------------------Edit-Quest-labtest-Categories-Modal-end------------------ -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
