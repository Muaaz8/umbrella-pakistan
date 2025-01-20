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
    $(".act").click(function () {
        var id= $(this).attr('id');
        $('#_id').val(id);
        // alert(id);
        $('#activate_doctor').modal('show');
    });
</script>
@endsection

@section('content')

        <div class="dashboard-content">
            <div class="container-fluid">
                <div class="row m-auto">
                  <div class="col-md-12">
                    <div class="row m-auto">
                      <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                        <h3>Blocked Doctor List</h3>
                        <div class="col-md-4 p-0">
                          <div class="input-group">
                            <form action="{{ url('/doctors/blocked/doctors') }}" method="POST" style="width: 100%;">
                                @csrf
                                <input
                                type="text"
                                id="search"
                                name="name"
                                class="form-control"
                                placeholder="Search By name"
                                aria-label="Username"
                                aria-describedby="basic-addon1"/>
                            </form>
                          </div>
                        </div>
                      </div>
                      <div class="wallet-table">
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">Name</th>
                              <th scope="col">State</th>
                              <th scope="col">Registered On</th>
                              <th scope="col">UPIN</th>
                              <th scope="col">PMDC</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($doctors as $doc)
                            <tr>
                                <td data-label="Name">{{ "Dr. ".ucfirst($doc->name)." ".ucfirst($doc->last_name) }}</td>
                                <td data-label="State">{{$doc->state_name}}</td>
                                <td data-label="Registered On">{{$doc->created_at}}</td>
                                <td data-label="UPIN">{{$doc->upin}}</td>
                                <td data-label="PMDC">{{$doc->nip_number}}</td>
                                <td data-label="Action">
                                    <center>
                                        <a href="#">
                                             <button class="btn btn-raised process-pay act" id="{{ $doc->id }}" >Activate</button>
                                        </a>
                                    </center>

                                </td>
                              </tr>
                              @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="m-auto text-center for-empty-div">
                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                            <h6>No Blocked Doctors To Show</h6>
                                        </div>
                                    </td>
                                </tr>
                              @endforelse
                          </tbody>
                        </table>
                        {{ $doctors->links('pagination::bootstrap-4') }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
      </div>


    </div>
     <!-- ------------------Block-Doctor-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="activate_doctor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Activate Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('Activate') }}"  method="POST" id="deactive_form">
                      @csrf
                    <div class="modal-body">
                        <div class="delete-modal-body">
                        Are you sure you want to Activate this Doctor?
                        </div>
                        <input type="hidden" name="id" id="_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-raised process-pay">Activate</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                  </form>
                </div>
                </div>
            </div>


    <!-- ------------------Block-Doctor-Modal-end------------------ -->

@endsection



