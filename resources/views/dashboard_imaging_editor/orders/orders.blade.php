@extends('layouts.dashboard_imaging_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection
    
@section('page_title')
    <title>Orders</title>
@endsection

@section('top_import_file')
<script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>
<script src="./assets/js/custom.js"></script>
@endsection

@section('bottom_import_file')
@endsection
@section('content')
  <div class="dashboard-content">
            <div class="container">
                <div class="row m-auto">
                  <div class="col-md-12">
                    <div class="row m-auto">
                      <div class="d-flex align-items-baseline p-0">
                        <h3>Orders</h3>
                        <div class="col-md-4 ms-auto p-0">
                          <div class="input-group">
                            <input
                              type="text"
                              class="form-control"
                              placeholder="Search what you are looking for"
                              aria-label="Username"
                              aria-describedby="basic-addon1"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="wallet-table">
                        <table class="table">
                          <thead>
                            <th scope="col">Order ID</th>
                            <th scope="col">Order State</th>
                            <th scope="col">Order Status</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Action</th>
                          </thead>
                          <tbody>
                            @forelse($tblOrders as $order)
                            <tr>
                                <td>{{$order->order_id}}</td>
                                <td>{{$order->order_state}}</td>
                                <td>{{$order->order_status}}</td>
                                <td>{{$order->created_at['date']}}</td>
                                <td>{{$order->created_at['time']}}</td>
                                <td><button class="orders-view-btn">View</button></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan='6'>
                                <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No Orders</h6>
                                </div>
                                </td>
                            </tr>
                            @endforelse
                          </tbody>
                        </table>
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
@endsection



    <!-- Option 1: Bootstrap Bundle with Popper -->
    
