@extends('layouts.dashboard_Pharm_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>Order Details</title>
@endsection

@section('top_import_file')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="./assets/js/custom.js"></script>
@endsection

@section('bottom_import_file')
@endsection
@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-6">
                    <div class="card" style="width: 100%">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">Name
                                :<label>{{ $img_order->user->name . ' ' . $img_order->user->last_name }}</label></li>
                            <li class="list-group-item d-flex justify-content-between">Phone Number
                                :<label>{{ $img_order->user->phone_number }}</label></li>
                            <li class="list-group-item d-flex justify-content-between">Reason
                                :<label>{{ $img_order->reason }}</label></li>
                            <li class="list-group-item d-flex justify-content-between">Payment :<label>{{ \Str::ucfirst($img_order->prescriptions[0]->title) }}</label></li>
                        </ul>
                    </div>
                </div>
                @if ($img_order->prescriptions[0]->title == "pending")
                    <div class="col-md-6">
                        <div class="card" style="width: 100%">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">Payment:</li>
                                <form action="{{ route('inclinic_pharmacy_payment') }}" method="POST">
                                @csrf
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-around">
                                        <input type="hidden" name="session_id" value="{{ $img_order->id }}">
                                        <div>
                                            <input type="radio" name="payment" id="card" value="card" disabled>
                                            <label for="card">Card</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="payment" id="easypaisa" value="easypaisa">
                                            <label for="easypaisa">Easy Paisa</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="payment" id="cash" value="cash">
                                            <label for="cash">Cash</label>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-end">
                                        <button class="btn btn-primary">Submit</button>
                                </li>
                                </form>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row m-auto">
                <div class="col-12">
                    <div class="card mt-3">
                        <h5 class="card-header d-flex justify-content-md-between">
                            <span>Order</span>
                            <span>Total Amount: {{$img_order->medicine_sum+$img_order->lab_sum+$img_order->imaging_sum}}</span>
                        </h5>
                        <div class="card-body">
                            @foreach ($img_order->prescriptions as $pres)
                                @if ($pres->type == "medicine")
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="card" style="width: 100%">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><b>Product: </b>{{ $pres->med_details->name }}
                                                    </li>
                                                    <li class="list-group-item"><b>Product Price: </b> Rs.{{ $pres->price }}</li>
                                                    <li class="list-group-item"><b>Dosage: </b> {{ $pres->usage }}</li>
                                                    {{-- <li class="list-group-item"><b>E-Prescription :</b><a class="btn process-pay m-3" href="{{ $file->filename }}" target="_blank"> View </a></li> --}}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($pres->type == "lab-test")
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="card" style="width: 100%">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><b>Product: </b>{{ $pres->lab_details->TEST_NAME }}
                                                    </li>
                                                    <li class="list-group-item"><b>Product Price: </b> Rs.{{ $pres->lab_details->SALE_PRICE }}</li>
                                                    {{-- <li class="list-group-item"><b>E-Prescription :</b><a class="btn process-pay m-3" href="{{ $file->filename }}" target="_blank"> View </a></li> --}}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($pres->type == "imaging")
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="card" style="width: 100%">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><b>Product: </b>{{ $pres->imaging_details->TEST_NAME }}
                                                    </li>
                                                    <li class="list-group-item"><b>Product Price: </b> Rs.{{ $pres->imaging_details->SALE_PRICE }}</li>
                                                    {{-- <li class="list-group-item"><b>E-Prescription :</b><a class="btn process-pay m-3" href="{{ $file->filename }}" target="_blank"> View </a></li> --}}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        <div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
