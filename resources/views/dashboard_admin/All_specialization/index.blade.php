@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
$(document).on("click", ".edit", function (e) {

	e.preventDefault();
    var spec = $(this).attr('data-id');
    var spec_id = spec.split(",")[0];
    var spec_name = spec.split(",")[1];
    var spec_status = spec.split(",")[2];
    // console.log(spec_id,spec_name,spec_status);
	$("#spec_id").val(spec_id);
	$("#spec_name").val(spec_name);
	// $("#spec_status").val(spec_status);
    if (spec_status == 1){
        $("#actdec").html("");
        $("#actdec").append("<option selected>Active</option>");
        $("#actdec").append("<option>Deactivate</option>");
    }else{
        $("#actdec").html("");
        $("#actdec").append("<option selected>Deactive</option>");
        $("#actdec").append("<option>Activate</option>");
    }

});

$(document).on("click", ".delete", function (e) {
    e.preventDefault();
    var spec_id = $(this).attr('id');
    $("#delete_id").val(spec_id);
    $('#delete_specialization').modal('show');

});

$('#add_specailization_modal').click(function (e) {
        e.preventDefault();
        $('#add_specailization').modal('show');
        // $.ajax({
        //     type: "get",
        //     url: "/admin/all/statemodal",
        //     success: function (response) {
        //         $("#statesoption").html("")
        //         $("#specoption").html("")
        //         $.each(response[0], function(index, value) {
        //             $("#statesoption").append("<option value='"+value.id+"'>"+value.name+"</option>")
        //         });
        //         $.each(response[1], function(index, value) {
        //             $("#specoption").append("<option value='"+value.id+"'>"+value.name+"</option>")
        //         });
        //         id = $(this).attr('id');
        //     }
        // });
    });
</script>
@endsection

@section('content')
{{-- {{ dd($data) }} --}}
<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
          <div class="col-md-12">
            <div class="row m-auto">
              <div class="d-flex justify-content-between flex-wrap p-0">
                <h3>View All Specialization</h3>
              </div>
              <div class="align-items-baseline flex-wrap d-flex justify-content-between p-0">
                <div class="">
                  <form action="{{ url('/admin/all/specializations') }}" method="POST" style="width: 100%;">
                      @csrf
                      <input
                      type="text"
                      id="search"
                      name="name"
                      class="form-control mb-2"
                      placeholder="Search By name"
                      aria-label="Username"
                      aria-describedby="basic-addon1"/>
                  </form>

                </div>
                <button class="btn process-pay" id="add_specailization_modal">ADD SPECIALIZATION</button>

              </div>
              <div class="wallet-table">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Specialization</th>
                      <th scope="col">Specialization Status</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data as $spe)
                    <tr>
                        <td data-label="Specialization">{{ $spe->name }}</td>
                        @if ($spe->status == '1')
                            <td data-label="Specialization Status">Active</td>
                        @else
                            <td data-label="Specialization Status">Deactive</td>
                        @endif
                        <td data-label="Action">
                            <div class="dropdown">
                                <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  OPTIONS
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                  <li><a class="dropdown-item edit" data-id='{{ $spe->id.",".$spe->name.",".$spe->status }}' data-bs-toggle="modal" data-bs-target="#edit_specailization">Edit</a></li>
                                  <li><a class="dropdown-item delete" id='{{ $spe->id }}' >Delete</a></li>
                                </ul>
                              </div>
                        </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $data->links('pagination::bootstrap-4') }}
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
          <h6 class="modal-title" id="exampleModalLabel">Add Specialization</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin_store_spec') }}" method="post">
            <div class="modal-body">
              <div class="dosage-body">
                <form action="">
                    @csrf
                <div class="row mt-3 mb-3">
                    <div class="col-md-12">
                        <label for="specialInstructions">Specialization Name</label>
                        <input type="text" class="form-control" name="spec_name" placeholder="Specialization Name">
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

      <!-- Modal -->
      <div class="modal fade" id="edit_specailization" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title" id="exampleModalLabel">Edit Specialization</h6>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin_edit_spec') }}">
                @csrf
                <div class="modal-body">
                    <div class="dosage-body">
                        <div class="row">
                            <div>
                                <input type="hidden" name="id" id="spec_id">
                            </div>
                            <div class="col-md-6">
                                <label for="specialInstructions">Specialization</label>
                                <input type="text" name="specialization" id="spec_name" class="form-control" placeholder="Cardiologists">
                            </div>
                            <div class="col-md-6">
                            <label for="specialization_status">Specialization Status</label>
                            <select class="form-select" name="status" id="actdec">
                            </select>
                            </div>
                        </div>
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
    <!-- ------------------Edit-Specialization-Modal-end------------------ -->

        <!-- ------------------Delete-Button-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="delete_specialization" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Specialization</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin_del_spec') }}">
                        @csrf
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
