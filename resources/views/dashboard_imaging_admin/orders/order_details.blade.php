@extends('layouts.dashboard_imaging_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>Order Details</title>
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

          <div class="col-md-6">
            <div class="card" style="width: 100%">
              <ul class="list-group list-group-flush">
                @if ($tblOrders->order_status == 'pending')
                    <li class="list-group-item d-flex justify-content-between">Payment  : <label class="order-progress">{{ $tblOrders->order_status }}</label></li>
                @else
                    <li class="list-group-item d-flex justify-content-between">Payment  : <label class="order-paid">{{ $tblOrders->order_status }}</label></li>
                @endif
                @if ($tblOrders->status == 'pending')
                    <li class="list-group-item d-flex justify-content-between">Status  : <label class="order-progress">{{ $tblOrders->status }}</label></li>
                @else
                    <li class="list-group-item d-flex justify-content-between">Status  : <label class="order-paid">{{ $tblOrders->status }}</label></li>
                @endif
                {{-- <li class="list-group-item d-flex justify-content-between"> Status :  <label class="order-">In progress</label></li> --}}
              </ul>
            </div>
          </div>

        </div>
        <div class="row m-auto">
            <div class="col-12">
                <div class="card mt-3">
                  <h5 class="card-header">Imaging Order</h5>
                  <div class="card-body">
                    <div class="row">
                    <div class="col-md-12">
                        <div class="card" style="width: 100%">
                            <ul class="list-group list-group-flush">
                              <li class="list-group-item"><b>Tracking ID : </b> {{ $tblOrders->order_id }} </li>
                              <li class="list-group-item"><b>Service Name :</b> {{ $tblOrders->name }} </li>
                              <li class="list-group-item"><b>Name :</b> {{ $tblOrders->fname." ".$tblOrders->lname }}</li>
                              <li class="list-group-item"><b>Product Price :</b> Rs. {{ $tblOrders->price }}</li>
                              {{-- <li class="list-group-item"><b>Username :</b> {{ $tblOrders->name }}</li> --}}
                              <li class="list-group-item"><b>Order State	 :</b> {{ $tblOrders->order_state }}</li>
                              <li class="list-group-item"><b>Payment Title	 :</b> {{ $tblOrders->payment_title }}</li>
                              <li class="list-group-item"><b>Payment Method	 :</b> {{ $tblOrders->payment_method }}</li>
                              <li class="list-group-item"><b>Currency :</b> {{ $tblOrders->currency }}</li>
                            </ul>
                          </div>
                    </div>

                </div>

                    <div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
      </div>
</div>
@endsection
