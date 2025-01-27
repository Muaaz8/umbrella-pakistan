@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Doctor Refill Requests</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
    $(document).on("click", ".edit", function (e) {

        e.preventDefault();
        var spec = $(this).attr('data');
        var spec_id = spec.split(" ")[0];
        var spec_name = spec.split(" ")[1]+" "+spec.split(" ")[2];
        var spec_state = spec.split(" ")[3];
        var spec_initial = spec.split(" ")[4];
        var spec_final = spec.split(" ")[5];
        // console.log(spec_name,spec_state,spec_initial,spec_final);

        $('#id').val(spec_id);
        $('#name').val(spec_name);
        $('#state').val(spec_state);
        $('#initial').val(spec_initial);
        $('#final').val(spec_final);

    });

    $('.delete').click(function (e) {
        e.preventDefault();
        id = $(this).attr('id');
        $('#delete_id').val(id);
        $('#delete_specialization').modal('show');


    });

    $('#add_specailization_modal').click(function (e) {
        e.preventDefault();
        $('#add_specailization').modal('show');
        $.ajax({
            type: "get",
            url: "/admin/all/statemodal",
            success: function (response) {
                $("#statesoption").html("")
                $("#specoption").html("")
                $.each(response[0], function(index, value) {
                    $("#statesoption").append("<option value='"+value.id+"'>"+value.name+"</option>")
                });
                $.each(response[1], function(index, value) {
                    $("#specoption").append("<option value='"+value.id+"'>"+value.name+"</option>")
                });
                id = $(this).attr('id');
            }
        });
    });
</script>
@endsection

@section('content')
{{-- {{ dd($data, $edit_data, $spec, $states) }} --}}
<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
          <div class="col-md-12">
            <div class="row m-auto">
                <div class="d-flex justify-content-between flex-wrap p-0">

                      <h3>Add Specialization Price</h3>
                      <button class="btn process-pay" id="add_specailization_modal">ADD SPECIALIZATION PRICE</button>

                  </div>
              <div class="wallet-table">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Specialization</th>
                      <th scope="col">Initial Price</th>
                      <th scope="col">Follow Up Price</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data as $item)
                    <tr>
                      <td data-label="Specialization">{{ $item->spec_name }}</td>
                      <td data-label="Initial Price">{{ $item->initial_price }}</td>
                      <td data-label="Follow Up Price">{{ $item->follow_up_price }}</td>
                      @php
                          $data = $item->id." ".$item->spec_name." ".$item->initial_price." ".$item->follow_up_price;
                      @endphp
                      <td data-label="Action">
                          <div class="dropdown">
                          <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            OPTIONS
                          </button>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item edit" data="{{ $data }}" data-bs-toggle="modal" data-bs-target="#add_specailization_price">Edit</a></li>
                            <li><a class="dropdown-item delete" href="#" id="{{ $item->id }}">Delete</a></li>
                          </ul>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>

      <!-- Modal -->
      <div class="modal fade" id="add_specailization" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title" id="exampleModalLabel">Add Specialization Price</h6>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin_store_spec_price') }}" method="post">
                <div class="modal-body">
                  <div class="dosage-body">
                    <form action="">
                        @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="specialInstructions">Specialization</label>
                            <select class="form-select" name="spec" id="specoption" aria-label="Default select example">
                                <option selected>Select Specialization</option>
                              </select>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-md-6">
                            <label for="specialInstructions">Initial Price</label>
                            <input type="text" class="form-control" name="initial_price" placeholder="initial price">
                        </div>
                    <div class="col-md-6">
                      <label for="specialization_status">Follow Up Price</label>
                      <input type="text" class="form-control" name="follow_up_price" placeholder="follow up price">
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
    <!-- ------------------Add-Specialization-Modal-end------------------ -->


        <!-- ------------------Edit-Special-Modal-start------------------ -->

      <!-- Modal -->
      <div class="modal fade" id="add_specailization_price" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title" id="exampleModalLabel">Add Specialization Price</h6>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="dosage-body">
                <form action="{{ route('update_specialization_price') }}">
                    @csrf
                    <input id="id" name="id" type="hidden" class="form-control" readonly></input>
                <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                        <label>Specialization</label>
                        <input id="name" name="spec" type="text" class="form-control" readonly></input>
                    </div>
                    <div class="col-md-6">
                        <label>State</label>
                        <input id="state" name="state" type="text" class="form-control" readonly></input>
                    </div>
                </div>
                <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                        <label for="specialInstructions">Initial Price</label>
                        <input id="initial" name="initial" type="text" class="form-control" placeholder="initial price">
                    </div>
                    <div class="col-md-6">
                        <label for="specialization_status">Follow Up Price</label>
                        <input id="final" name="final" type="text" class="form-control" placeholder="follow up price">
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
    <!-- ------------------Edit-Special-Modal-end------------------ -->

        <!-- ------------------Delete-Button-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="delete_specialization" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Specialization</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('admin_del_spec_price') }}">
                    <div class="modal-body">
                        <div class="delete-modal-body">
                        Are you sure you want to delete your Specialization?
                        </div>
                        <input type="hidden" name="id" id="delete_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                  </form>
              </div>
              </div>
          </div>


  <!-- ------------------Delete-Button-Modal-start------------------ -->
@endsection
