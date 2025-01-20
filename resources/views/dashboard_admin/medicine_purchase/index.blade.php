@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Medicine Purchase</title>
@endsection

@section('top_import_file')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <style>
            .table> :not(caption)>*>*{
                padding: 10px !important;
            }
        </style>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <div>
                                <h3>Medicine Purchase</h3>
                            </div>
                        </div>
                        <div class="row">
                            @if(Session::has('success'))
                            <div class="alert alert-success flash-message" role="alert">{{ Session::get('success') }}</div>
                            @endif
                        </div>
                        <div class="wallet-table " style="border-radius: 18px;">
                            <form action="{{ route('medicine_purchase_store') }}" method="POST">
                                @csrf
                                <div class="p-3">
                                    <div class="row mb-3">
                                        <div class="col-md-1">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Address:</label>
                                        </div>
                                        <div class="col-md-11" >
                                            <select name="location" id="loction" class="location form-control @error('loction')  is-invalid state-invalid @enderror" required>
                                                <option disabled selected>Please select location</option>
                                                @foreach($locations as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('location'))
                                                <p class="text-danger" role="alert">{{ $errors->first('location') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row" style="background:#f8f8f8">
                                        <table class="table table-bordered table-responsive" id="OptionTable">
                                            <thead>
                                                <tr>
                                                    <th width="10%">S.no</th>
                                                    <th width="70%">Medicine Name</th>
                                                    <th width="20%">Quantity</th>
                                                    <th width="250px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr data-keyy="1">
                                                    <td><p class="sno" data-key="1">1</p></td>
                                                    <td>
                                                        <div class="form-group">
                                                            <select name="medicine[]" class="form-control medicine " id="medicine" required>
                                                                <option disabled selected>Select Medicine</option>
                                                                @foreach($medicines as $medicine)
                                                                    <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="number" id="medicine_quantity" name="medicine_quantity[]" autocomplete="off" class="form-control medicine_quantity"  placeholder="Enter Quantity" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="removeRow btn btn-danger">Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="col col-lg-10 col-md-10"></div>
                                        <div class="col col-lg-2 col-md-2 p-4">
                                            <button type="button" class="addRow btn btn-info">Add
                                                More</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="form-control btn btn-success">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom_import_file')
<script>
$(document).ready(function () {
    $('.location').select2({
        placeholder: "Click to View Options",
    });
    // $('#medicine').select2();
    // Add event listener for adding rows
    $('body').on('click', '.addRow', function (e) {
        var row = $('#OptionTable tbody tr').last().clone();
        row.find('.medicine_quantity').val('');
        row.find('.addRow').addClass('removeRow btn-danger');
        var rowCount = $('#OptionTable tbody tr').length + 1;
        row.find('.sno').text(rowCount);
        row.find('.sno').attr('data-key', rowCount);
        row.attr('data-keyy', rowCount);
        // row.find('#medicine').select2(); // Initialize Select2 on the cloned element
        $('#OptionTable tbody tr').last().after(row);
    });

    // Add event listener for removing rows
    $('body').on('click', '.removeRow', function () {
        var rowCount = $('#OptionTable tbody tr').length;
        if (rowCount > 1) {
            $(this).parents('tr').remove();
            var con = 1;
            $('.sno').each(function () {
                $(this).text(con);
                $(this).attr('data-key', con);
                con++;
            });
        } else {
            alert('You cannot remove the last row');
        }
    });
});


</script>
@endsection
