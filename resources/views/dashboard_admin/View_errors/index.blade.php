@extends('layouts.dashboard_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>CHCC - Error Logs</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
@endsection
@section('content')
        <div class="dashboard-content">
            <div class="container-fluid">
                <div class="row m-auto">
                  <div class="col-md-12">
                    <div class="row m-auto">
                      <div class="d-flex align-items-baseline p-0">
                        <h3>Error Logs</h3>
                        {{-- <div class="col-md-4 ms-auto p-0">
                          <div class="input-group">
                            <input
                              type="text"
                              class="form-control"
                              placeholder="Search what you are looking for"
                              aria-label="Username"
                              aria-describedby="basic-addon1"
                            />
                          </div>
                        </div> --}}
                      </div>
                      <div class="wallet-table">
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">User Id</th>
                              <th scope="col">User Name</th>
                              <th scope="col">Error Code</th>
                              <th scope="col">Error Text</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($data as $dt)
                              <tr>
                                <td data-label="User Id">{{ $dt->id }}</td>
                                <td data-label="User Name">{{$dt->name." ".$dt->last_name}}</td>
                                <td data-label="Error Code">{{$dt->Error_code}}</td>
                                <td data-label="Error Text">{{$dt->Error_text}}</td>
                              </tr>
                              @empty
                              <tr>
                                <td colspan="4">
                                    <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6>No Errors To Show</h6>
                                    </div>
                                </td>
                            </tr>
                              @endforelse
                          </tbody>
                        </table>
                        <div class="row d-flex justify-content-center">
                            <div class="paginateCounter">
                                {{ $data->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
      </div>
    </div>
@endsection
