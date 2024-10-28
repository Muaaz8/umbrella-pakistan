@extends('layouts.dashboard_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>UHCS - Psychiatrist Services</title>
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
                        <h3>View All Psychiatrist Services</h3>
                      </div>
                      <div class="wallet-table">
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">ID</th>
                              <th scope="col">Psychiatrist Services</th>
                              <th scope="col" colspan="5">Description</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($data as $dt)
                              <tr>
                                <td data-label="ID">{{ $dt->id }}</td>
                                <td data-label="Psychiatrist Services">{{ $dt->title }}</td>
                                <td data-label="Description" colspan="5">{{ $dt->description }}</td>
                              </tr>
                              @empty
                              <tr>
                                <td colspan="7">
                                    <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6>No Prescription To Show</h6>
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
