@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Imaging Orders</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
crossorigin="anonymous"
></script>
<script src="assets/js/custom.js"></script>
<script>
  $("#search").keyup(function() {
    var rex = new RegExp($(this).val(), 'i');
    $('#table tr').hide();
    //Recusively filter the jquery object to get results.
    $('#table tr ').filter(function(i, v) {
        //Get the 3rd column object here which is userNamecolumn
        var $t = $(this).children(":eq(" + "0" + ")");
        return rex.test($t.text());
    }).show();
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
              <h3>Imaging Orders</h3>
              <div class="p-0">
                <div class="input-group">
                  <input
                    type="text"
                    id="search"
                    class="form-control"
                    placeholder="Search"
                    aria-label="Username"
                    aria-describedby="basic-addon1"
                  />
                </div>
              </div>
            </div>
            <div class="wallet-table table-responsive">
              <table class="table" id="table">
                <thead>
                    <th scope="col">Service Name</th>
                    <th scope="col">Doctor Name</th>
                    <th scope="col">Order Status</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Action</th>
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
                        <td data-label="Created At">{{ $order->date." ".$order->time }}</td>
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
                  <td colspan="6">
                          <div class="m-auto text-center for-empty-div">
                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                            <h6>No Reports</h6>
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
@endsection
