@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Admin Dashboard</title>
@endsection

@section('top_import_file')

@endsection


@section('bottom_import_file')
<script>
    $('.delete').click(function(){
        var id= $(this).attr('id');
        $("#delete_id").val(id);
        $('#delete_terms').modal('show');
        // $('#delete_term_form').attr('action','/admin/document/delete/'+breakClasses[2];
    });

</script>
@endsection

@section('content')




        <div class="dashboard-content">
            <div class="container-fluid">
                <div class="row m-auto">
                  <div class="col-md-12">
                    <div class="row m-auto">
                      <div class="d-flex align-items-baseline p-0">
                        <h3>View Terms</h3>
                        <div class="col-md-4 ms-auto p-0">


                        </div>
                      </div>

                      <div class="wallet-table">
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">ID</th>
                              <th  scope="col">Terms Of Use</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (is_array($data) || is_object($data))
                            @foreach($data as $dt)
                            <tr>
                                <td data-label="ID">{{ $dt->id }}</td>
                                <td class="d-flex flex-wrap text-start" data-label="Terms Of Use">
                                    {!! strip_tags($dt->content) !!}
                                </td>
                                <td data-label="Action">
                                    <div class="dropdown">
                                        <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                          OPTIONS
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                            <li><a class="dropdown-item" href="{{ route('edit_term',['id'=>$dt->id]) }}">Edit</a></li>
                                            <li><a class="dropdown-item delete" id="{{$dt->id}}">Delete</a></li>
                                        </ul>
                                      </div>
                                </td>
                              </tr>
                              @endforeach
                              @endif
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
      </div>


    </div>

            <!-- ------------------Delete-Terms-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="delete_terms" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Term</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form  method="post" action="{{ route('delete_docs') }}">
                    @csrf
                  <div class="modal-body">

                      <div class="delete-modal-body">
                      Are you sure you want to Delete this Term?
                    </div>
                    <input type="hidden" id="delete_id" name="id">
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-danger">Delete</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  </div>
                </form>
              </div>
              </div>
          </div>


  <!-- ------------------Delete-Terms-Modal-end------------------ -->



  @endsection
