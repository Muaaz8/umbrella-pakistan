@extends('layouts.dashboard_Lab_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>CHCC - Quest Orders</title>
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
              <div class="d-flex justify-content-between flex-wrap align-items-baseline p-0">
                <h3>Quest Failed Requests</h3>
                <div class="col-md-4 p-0">
                  <div class="input-group">
                    <!-- <input
                      type="text"
                      class="form-control"
                      placeholder="Search what you are looking for"
                      aria-label="Username"
                      aria-describedby="basic-addon1"
                    /> -->
                  </div>
                </div>
              </div>
              <div class="wallet-table table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Placer Order ID</th>
                      <th scope="col">Filler Order ID</th>
                      <th scope="col">Quest Request ID</th>
                      <th scope="col">Control ID</th>
                      <th scope="col">Recieved At</th>
                      <th scope="col">Error Message</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($results as $res)
                    <tr>
                        <td data-label="Placer Order ID">{{$res->placer_order_num}}</td>
                        <td data-label="Filler Order ID">{{$res->filler_order_num}}</td>
                        <td data-label="Quest Request ID">{{$res->get_quest_request_id}}</td>
                        <td data-label="Control ID">{{$res->control_id}}</td>
                        <td data-label="Recieved At">{{date('Y-m-d',strtotime($res->created_at))}}</td>
                        <td data-label="Error Message">{{ucfirst($res->status)}}</td>
                      </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="m-auto text-center for-empty-div">
                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                <h6>No Failed Quest Lab To Show</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
                <div class="row d-flex justify-content-center">
                    <div class="paginateCounter">
                        {{ $results->links('pagination::bootstrap-4') }}
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>
@endsection
