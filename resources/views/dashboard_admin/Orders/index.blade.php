@extends('layouts.dashboard_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>Orders</title>
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
                        <h3>Orders</h3>
                        <div class="col-md-4 p-0">
                          <div class="input-group">
                            <form action="{{ url('/admin/all/orders') }}" method="POST" style="width: 100%;">
                                @csrf
                                <input
                                type="text"
                                id="search"
                                name="name"
                                class="form-control"
                                placeholder="Search By Order Id"
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
                              {{-- <th scope="col">S.No</th> --}}
                              <th scope="col">Order ID</th>
                              <th scope="col">Order Status</th>
                              <th scope="col">Date</th>
                              <th scope="col">Time</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($tblOrders as $orders)
                              <tr>
                                {{-- <td data-label="S.No">{{$orders->id}}</td> --}}
                                <td data-label="Order ID">{{$orders->order_id}}</td>
                                <td data-label="Order Status">{{$orders->order_status}}</td>
                                <td data-label="Date">{{$orders->created_at['date']}}</td>
                                <td data-label="Time">{{$orders->created_at['time']}}</td>
                                <td data-label="Action"><button onclick="window.location.href='{{ route('admin_order_details',['id'=> $orders->id]) }}'" class="orders-view-btn">View</button></td>
                              </tr>
                              @empty
                              <tr>
                                <td colspan="6">
                                    <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6>No Prescription To Show</h6>
                                    </div>
                                </td>
                            </tr>
                              @endforelse
                          </tbody>
                        </table>
                        <div class="row d-flex justify-content-center">
                            <div class="paginateCounter">
                                {{ $tblOrders->links('pagination::bootstrap-4') }}
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
