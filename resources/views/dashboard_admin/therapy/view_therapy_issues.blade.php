@extends('layouts.dashboard_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>CHCC - Psychiatrist Services</title>
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
                        <h3>View All Therapy Issues</h3>
                      </div>
                      <div class="wallet-table">
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">ID</th>
                              <th scope="col">Therapy Issues</th>
                              <th scope="col">Description</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($data as $dt)
                              <tr>
                                <td data-label="ID">{{ $dt->id }}</td>
                                <td data-label="Therapy Issues">{{ $dt->title }}</td>
                                <td data-label="Description">{{ $dt->description }}</td>
                                <td data-label="Action">
                                    <div class="dropdown">
                                        <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                          OPTIONS
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                            <li><a class="dropdown-item" href="{{ route('admin_edit_therapy_issue',['id'=>$dt->id]) }}">Edit</a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin_delete_therapy_issue',['id'=>$dt->id]) }}">Delete</a></li>
                                        </ul>
                                      </div>
                                </td>
                              </tr>
                              @empty
                              <tr>
                                <td colspan="4">
                                    <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6>No Therapy To Show</h6>
                                    </div>
                                </td>
                            </tr>
                              @endforelse
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
@endsection
