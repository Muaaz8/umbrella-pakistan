@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Lab Results</title>
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
                        <div class="col-12 mb-3 bg-white profile-box">
                            <div class="py-3">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item col-6" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                            aria-selected="true">
                                            Lab
                                        </button>
                                    </li>
                                    <li class="nav-item col-6" role="presentation">
                                        <button class="nav-link " id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#contact" type="button" role="tab" aria-controls="contact"
                                            aria-selected="false">
                                            Imaging
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                        aria-labelledby="home-tab">
                                        <div class="row m-auto mt-3">
                                            <div class="d-flex align-items-end justify-content-between flex-wrap p-0">
                                                <h3 class="m-0">Lab Results</h3>
                                                <div class="p-0 col-12 col-sm-4">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                            placeholder="Search "
                                                            aria-label="Username" aria-describedby="basic-addon1" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wallet-table table-responsive">
                                                <table class="table">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Test Names</th>
                                                      @if(auth()->user()->user_type=='patient')
                                                          <th scope="col">Provider Name</th>
                                                      @else
                                                          <th scope="col">Patient Name</th>
                                                      @endif
                                                      <th scope="col">Result Status</th>
                                                      <th scope="col">Specimen Collection Date</th>
                                                      <th scope="col">Result Date</th>
                                                      <th scope="col">Action</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                  @forelse($reports as $report)
                                                    <tr>
                                                      <td data-label="Test Names" scope="row">{{$report->test_names}}</td>
                                                      @if(auth()->user()->user_type=='patient')
                                                          <td data-label="Provider Name">{{$report->doctor}}</td>
                                                      @else
                                                          <td data-label="Patient Name">{{$report->patient}}</td>
                                                      @endif
                                                      <td data-label="Result Status">{{$report->type}}</td>
                                                      <td data-label="Specimen Collection Date">{{$report->specimen_date}}</td>
                                                      <td data-label="Result Date">{{$report->result_date}}</td>
                                                      <td data-label="Action"><a href="{{url(\App\Helper::get_files_url($report->file))}}" target="_blank" class="btn btn-primary">
                                                          <button class="orders-view-btn">View</button>
                                                      </a></td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan='6'>
                                                        <div class="m-auto text-center for-empty-div">
                                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                            <h6>No Reports To Show</h6>
                                                        </div>
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                  </tbody>
                                                </table>
                                                {{$reports->links('pagination::bootstrap-4')}}
                                              </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                        <div class="col-md-12 mt-3">
                                            <div class="row m-auto">
                                                <div class="d-flex align-items-end justify-content-between flex-wrap p-0">
                                                    <h3 class="m-0">Imaging Orders</h3>
                                                    <div class="p-0 col-12 col-sm-4">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="Search "
                                                                aria-label="Username" aria-describedby="basic-addon1" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wallet-table table-responsive">
                                                    <table class="table">
                                                      <thead>
                                                        <tr>
                                                          <th scope="col">Service Name</th>
                                                          <th scope="col">Doctor Name</th>
                                                          <th scope="col">Order Status</th>
                                                          <th scope="col">Created At</th>
                                                          <th scope="col">Amount</th>
                                                          <th scope="col">Action</th>
                                                        </tr>
                                                      </thead>
                                                      <tbody>
                                                      @forelse ($tblOrders as $order)
                                                          <tr>
                                                              <td data-label="Service Name" scope="row">{{ $order->name }}</td>
                                                              <td data-label="Doctor Name">Dr. {{ $order->doc_fname.' '.$order->doc_lname }}</td>
                                                              @if ($order->order_status == 'paid' || $order->order_status == 'reported')
                                                                  <td data-label="Order Status"><label class="order-paid">{{ ucfirst($order->order_status) }}</label></td>
                                                              @elseif($order->order_status == 'Pending')
                                                                  <td data-label="Order Status"><label class="order-pending">{{ ucfirst($order->order_status) }}</label></td>
                                                              @else
                                                                  <td data-label="Order Status"><label class="order-progress">{{ ucfirst($order->order_status) }}</label></td>
                                                              @endif
                                                              <td data-label="Created At">{{ $order->created_at }}</td>
                                                              <td data-label="Amount">Rs. {{ $order->price }}</td>
                                                              @if($order->order_status=='reported')
                                                              <td data-label="Action">
                                                                  <a target="_blank"href="{{$order->report}}">
                                                                      <button class="orders-view-btn">View</button>
                                                                  </a>
                                                              </td>
                                                              @else
                                                              <td data-label="Action">Waiting..</td>
                                                              @endif
                                                          </tr>
                                                      @empty
                                                      <tr>
                                                        <td colspan='6'>
                                                        <div class="m-auto text-center for-empty-div">
                                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                            <h6>No Reports To Show</h6>
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
                </div>
            </div>
        </div>
    </div>
@endsection
