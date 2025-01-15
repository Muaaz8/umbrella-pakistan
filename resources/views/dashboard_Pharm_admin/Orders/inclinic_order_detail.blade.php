@extends('layouts.dashboard_admin')
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

<style>
    .pres_checkbox {
        position: absolute;
        top: 0;
        right: 0;
        margin: 10px;
        padding: 10px;
        z-index: 10;

    }

    .pointer {
        cursor: pointer;
    }

    .border-none {
        border: none;
    }

    .total_price {
        border: none;
        background-color: transparent;
        width: 50px;
    }
</style>
@section('content')
<div class="dashboard-content">
    <form class="container-fluid" action="{{ route('inclinic_pharmacy_payment') }}" method="POST">
        <div class="row m-auto">
            <div class="col-md-6">
                <div class="card" style="width: 100%">
                    <ul class="list-group list-group-flush border-none">
                        <li class="list-group-item d-flex justify-content-between">Name
                            :<label>{{ $img_order->user->name . ' ' . $img_order->user->last_name }}</label></li>
                        <li class="list-group-item d-flex justify-content-between">Phone Number
                            :<label>{{ $img_order->user->phone_number }}</label></li>
                        <li class="list-group-item d-flex justify-content-between">Reason
                            :<label>{{ $img_order->reason }}</label></li>
                        @if (count($img_order->prescriptions) > 0)
                        <li class="list-group-item d-flex justify-content-between">Payment :<label>{{
                                \Str::ucfirst($img_order->prescriptions[0]->title) }}</label></li>
                        @endif
                    </ul>
                </div>
            </div>
            @if (count($img_order->prescriptions) > 0)
            <div class="col-md-6">
                <div class="card" style="width: 100%">
                    <ul class="list-group list-group-flush border-none">
                        <li class="list-group-item d-flex justify-content-between">Payment:</li>
                        <div>
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
                        </div>
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
                        <span>Total Amount: <input class="total_price" name="total_price" value="0"
                                id="total_price"></input></span>
                    </h5>
                    <div class="card-body">
                        @foreach ($img_order->prescriptions as $pres)
                        @if ($pres->type == "medicine")
                        <div class="row mb-3 pointer">
                            <label for="{{ $pres->id }}" class="col-md-12 position-relative">
                                <div class="card" style="width: 100%; border-color: #082755 !important;">
                                    <input @if ($pres->title == "paid")
                                    disabled
                                    @endif
                                    type="checkbox" class="pres_checkbox" name="selected-med[]"
                                    value="{{ $pres->price }}" id="{{ $pres->id }}" class="form-check-input">
                                    <ul class="list-group list-group-flush border-none">
                                        <li class="list-group-item"><b>Product: </b>{{ $pres->med_details->name }}
                                        </li>
                                        <li class="list-group-item"><b>Product Price: </b> Rs.{{ $pres->price }}</li>
                                        <li class="list-group-item"><b>Dosage: </b> {{ $pres->usage }}</li>
                                        <li class="list-group-item"><b>Status: </b> {{ $pres->title }}</li>
                                        {{-- <li class="list-group-item"><b>E-Prescription :</b><a
                                                class="btn process-pay m-3" href="{{ $file->filename }}"
                                                target="_blank"> View </a></li> --}}
                                    </ul>
                                </div>
                            </label>
                        </div>
                        @elseif($pres->type == "lab-test")
                        <div class="row mb-3">
                            <label for="{{ $pres->id }}" class="col-md-12">
                                <div class="card" style="width: 100%; border-color: #35b518 !important;">
                                    <input @if ($pres->title == "paid")
                                    disabled
                                    @endif
                                    type="checkbox" class="pres_checkbox" name="selected-med[]"
                                    value="{{ $pres->price }}" id="{{ $pres->id }}" class="form-check-input">
                                    <ul class="list-group list-group-flush border-none">
                                        <li class="list-group-item"><b>Product: </b>{{ $pres->lab_details->TEST_NAME }}
                                        </li>
                                        <li class="list-group-item"><b>Product Price: </b> Rs.{{
                                            $pres->lab_details->SALE_PRICE }}</li>
                                        <li class="list-group-item"><b>Status: </b> {{ $pres->title }}</li>
                                        {{-- <li class="list-group-item"><b>E-Prescription :</b><a
                                                class="btn process-pay m-3" href="{{ $file->filename }}"
                                                target="_blank"> View </a></li> --}}
                                    </ul>
                                </div>
                            </label>
                        </div>
                        @elseif($pres->type == "imaging")
                        <div class="row mb-3">
                            <label for="{{ $pres->id }}" class="col-md-12">
                                <div class="card" style="width: 100%; border-color: #c80919 !important;">
                                    <input @if ($pres->title == "paid")
                                    disabled
                                    @endif
                                    type="checkbox" class="pres_checkbox" name="selected-med[]"
                                    value="{{ $pres->price }}" id="{{ $pres->id }}" class="form-check-input">
                                    <ul class="list-group list-group-flush border-none">
                                        <li class="list-group-item"><b>Product: </b>{{ $pres->imaging_details->TEST_NAME
                                            }}
                                        </li>
                                        <li class="list-group-item"><b>Product Price: </b> Rs.{{
                                            $pres->imaging_details->SALE_PRICE }}</li>
                                        <li class="list-group-item"><b>Status: </b> {{ $pres->title }}</li>
                                        {{-- <li class="list-group-item"><b>E-Prescription :</b><a
                                                class="btn process-pay m-3" href="{{ $file->filename }}"
                                                target="_blank"> View </a></li> --}}
                                    </ul>
                                </div>
                            </label>
                        </div>
                        @endif
                        @endforeach
                        <div>
                            <input type="checkbox" class="d-none" name="med-ids[]">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="selected_med" id="selected-med-ids">
    </form>
</div>

<script>
    $($('input[type="checkbox"]')).change(function() {
                var total = 0;
                var selectedIds = [];
                $('input[type="checkbox"]:checked').each(function() {
                    total += parseInt($(this).val());
                    selectedIds.push($(this).attr('id'));
                });
                $('#total_price').val(total);
                $('#selected-med-ids').val(selectedIds.join(','));
            });
</script>


@endsection
