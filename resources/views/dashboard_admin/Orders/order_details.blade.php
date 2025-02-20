@extends('layouts.dashboard_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection
@section('top_import_file')
@endsection
@section('page_title')
    <title>Order Detail</title>
@endsection
@section('content')
    <div class="dashboard-content">
        <div class="container">
            <div class="row m-auto">
                <div class="col-md-6 mb-2 mb-md-0">
                    <div class="card" style="width: 100%">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Order ID </b> : {{ $data['order_data']->order_id }}</li>
                            <li class="list-group-item"><b> Date</b> :
                                {{ explode(' ', $data['order_data']->created_at)[0] }}</li>
                            <li class="list-group-item"> <b> Time</b> :
                                {{ explode(' ', $data['order_data']->created_at)[1]." ".explode(' ', $data['order_data']->created_at)[2] }}</li>
                            {{-- <li class="list-group-item"></li> --}}
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card" style="width: 100%">
                        <ul class="list-group list-group-flush">
                            @if ($data['order_data']->order_status == 'paid')
                                <li class="list-group-item d-flex justify-content-between"><b>Payment Status :</b> <label
                                        class="order-paid">{{ $data['order_data']->order_status }}</label></li>
                                {{-- <li class="list-group-item d-flex justify-content-between"><b>Order Status :</b> <label
                                        class="order-paid">{{ $data['order_data']->status }}</label></li> --}}
                            @else
                                <li class="list-group-item d-flex justify-content-between"><b>Payment Status :</b> <label
                                        class="order-progress">{{ $data['order_data']->order_status }}</label></li>
                                {{-- <li class="list-group-item d-flex justify-content-between"><b>Order Status :</b> <label
                                        class="order-progress">{{ $data['order_data']->status }}</label></li> --}}

                            @endif
                            <li class="list-group-item d-flex justify-content-between"><b>Payment Type :</b>
                                {{ $data['payment_method'] }}</li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="row m-auto mt-3">
                <div class="col-md-8 mb-3">
                    <div class="wallet-table m-0">
                        <table class="table table-bordered checkout-table-das">
                            <thead>
                                <tr>
                                    <!-- <th scope="col"></th> -->
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $priceTotal = 0;
                                    $providerFee = 0;
                                    $itemCount = 0;
                                @endphp
                                @foreach ($orderMeds as $med)
                                    <tr>
                                        <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                                        <td data-label="Product Name">{{ $med->name }}</td>
                                        <td data-label="Quantity">1</td>
                                        <td data-label="Price">Rs. {{ $med->update_price }}</td>
                                        <td data-label="Status">{{ $med->status }}</td>
                                        @php
                                            $priceTotal = $priceTotal + $med->update_price;
                                            $itemCount += 1;
                                            $med = '1';
                                        @endphp
                                        {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a></td> --}}
                                    </tr>
                                @endforeach
                                @foreach ($ordercntLabs as $labs)
                                    <tr>
                                        <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                                        <td data-label="Product Name">{{ $labs->TEST_NAME }}</td>
                                        <td data-label="Quantity">1</td>
                                        <td data-label="Price">Rs. {{ $labs->SALE_PRICE }}</td>
                                        <td data-label="Status">{{ $labs->status }}</td>
                                        @php
                                            $priceTotal = $priceTotal + $labs->SALE_PRICE;
                                            $providerFee = 0;
                                            $itemCount += 1;
                                        @endphp
                                        {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a></td> --}}
                                    </tr>
                                @endforeach
                                @foreach ($orderLabs as $labs)
                                    <tr>
                                        <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                                        <td data-label="Product Name">{{ $labs->TEST_NAME }}</td>
                                        <td data-label="Quantity">1</td>
                                        <td data-label="Price">Rs. {{ $labs->SALE_PRICE }}</td>
                                        <td data-label="Status">{{ $labs->status }}</td>
                                        @php
                                            $priceTotal = $priceTotal + $labs->SALE_PRICE;
                                            $providerFee = 0;
                                            $itemCount += 1;
                                        @endphp
                                        {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a></td> --}}
                                    </tr>
                                @endforeach
                                @foreach ($orderImagings as $image)
                                    <tr>
                                        <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                                        <td data-label="Product Name">{{ $image->name }}</td>
                                        <td data-label="Quantity">1</td>
                                        <td data-label="Price">Rs. {{ $image->price }}</td>
                                        <td data-label="Status">{{ $image->status }}</td>
                                        @php
                                            $priceTotal = $priceTotal + $image->price;
                                            $itemCount += 1;
                                        @endphp
                                        {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a></td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="row">
                        <div class="payment-order-summary-wrap">
                            <div class="card">
                                <div class="card-header">Order Summary</div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        Total Item <span>{{ $itemCount }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        Total Cost <span>Rs. {{ $priceTotal }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        Tax <span>Rs. {{ $data['order_data']->total_tax }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        To be Paid
                                        <span>Rs. {{ (double) $priceTotal + (double) $data['order_data']->total_tax + (double) $providerFee }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-auto">
                <div class="col-12">
                    <div class="card mt-3">
                        <h5 class="card-header">Billing Details</h5>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="card" style="width: 100%">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><b>Name : </b>
                                                {{  $data['billing'][1] }}</li>
                                            <li class="list-group-item"><b>Phone :</b>
                                                {{  $data['billing'][2] }}</li>
                                            </li>
                                            <li class="list-group-item"><b>Email :</b>
                                                {{ $data['billing'][3] }}</li>
                                            <li class="list-group-item"><b>Address :</b>
                                                {{ $data['billing'][4] }}</li>
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
            {{-- <div class="row m-auto">
                <div class="col-12">
                    <div class="card mt-3">
                      <h5 class="card-header">Shipping Details</h5>
                      <div class="card-body">
                        <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="card" style="width: 100%">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><b>Name : </b> {{  explode('|', $data['shipping'][0])[1] }}</li>
                                    <li class="list-group-item"><b>Phone :</b> {{  explode('|', $data['shipping'][2])[1] }}</li>
                                    <li class="list-group-item"><b>Email :</b> {{  explode('|', $data['shipping'][1])[1] }}</li>
                                    <li class="list-group-item"><b>Address :</b> {{ explode('|', $data['shipping'][3])[1] }}</li>
                                  </ul>
                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card" style="width: 100%">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><b>Zip : </b> {{ explode('|', $data['shipping'][6])[1] }}</li>
                                    <li class="list-group-item"><b>State : </b> {{ explode('|', $data['shipping'][5])[1] }}</li>
                                    <li class="list-group-item"><b>City : </b> {{ explode('|', $data['shipping'][4])[1] }}</li>
                                    <li class="list-group-item"><b>Notes : </b> Nothing</li>
                                  </ul>
                              </div>
                        </div>
                    </div>

                        <div>
                        </div>
                      </div>
                    </div>
                  </div>
            </div> --}}
        </div>




    </div>
    </div>
    </div>
@endsection
@section('script')
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="assets/js/custom.js"></script>
@endsection
