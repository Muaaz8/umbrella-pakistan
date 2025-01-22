@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Admin Dashboard</title>
@endsection

@section('top_import_file')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <div>
                                <h3>Edit Physical Location</h3>
                            </div>
                        </div>
                        <div class="wallet-table " style="border-radius: 18px;">
                            <form action="{{ url('admin/update/physical/location/'.$pl->id) }}" method="POST">
                                @csrf
                                <div class="p-3">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Address:</label>
                                            <input type="text" class="form-control" name="name" placeholder="Location Name" value="{{ $pl->name }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Phone Number:</label>
                                            <input type="text" class="form-control" name="phone_number" placeholder="Phone Number" value="{{ $pl->phone_number }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Zip Code:</label>
                                            <input type="text" class="form-control zip_code" name="zipcode" placeholder="Zip Code"  value="{{ $pl->zipcode }}"
                                            onkeydown='javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))'
                                            >
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">State:</label>
                                            <select id="state_id" class="form-control state" name="state_id" required>
                                                <option value="">Select State</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">City:</label>
                                            <select id="city_id" class="form-control city" name="city_id" required>
                                                <option value="">Select City</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Latitude: </label>
                                            <input type="text" class="form-control" name="latitude" placeholder="Latitude"  value="{{ $pl->latitude }}">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6 sub_cat">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Longitude: </label>
                                            <input type="text" class="form-control" name="longitude" placeholder="Longitude" value="{{ $pl->longitude }}">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="selectmedicine">Services:</label>
                                            <select id="services" class="form-control js-select2" name="services[]" multiple>
                                                <option value=""></option>
                                                @if (json_decode($pl->services) != null)
                                                    <option value="labs"{{ is_array(in_array('labs',json_decode($pl->services)))?'selected':'' }}>Labs</option>
                                                    <option value="imaging" {{ in_array('imaging',json_decode($pl->services))?'selected':'' }}>Imaging</option>
                                                    <option value="pharmacy" {{ in_array('pharmacy',json_decode($pl->services))?'selected':'' }}>Pharmacy</option>
                                                    @php
                                                        $array = ['labs','imaging','pharmacy'];
                                                        $remainder = array_diff(json_decode($pl->services),$array);
                                                    @endphp
                                                    @foreach ($remainder as $item)
                                                        <option value="{{ $item }}" selected>{{ $item }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="labs">Labs</option>
                                                    <option value="imaging">Imaging</option>
                                                    <option value="pharmacy">Pharmacy</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2"  for="time_from">From:</label>
                                            <input type="time" name="time_from" class="form-control" id="time_from" value="{{ date('H:i', strtotime($pl->time_from)) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bolder mb-2" for="time_to">To:</label>
                                            <input type="time" id="time_to" name="time_to" class="form-control" value="{{ date('H:i', strtotime($pl->time_to)) }}">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="text-end">
                                            <button type="submit" class="btn process-pay">Submit</button>
                                        </div>
                                    </div>
                                </div>
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
    $(".js-select2").select2({
        closeOnSelect: false,
        placeholder: "Click to View Options",
        allowHtml: true,
        allowClear: true,
        tags: true,
    });
</script>
<script>
    $(document).ready(function () {
        var zip = $(".zip_code").val();
        var length = $(".zip_code").val().length;
        if(length >= 5) {
            $('.state').html('<option value="">Select State</option>');
            $('.city').html('<option value="">Select City</option>');
            $.ajax({
                type: "POST",
                url: "/get_states_cities",
                data: {
                    zip: zip,
                },
                success: function(data) {
                    if (data == "") {
                        $('.zipcode_error').text('Please enter a valid zipcode');
                        $('.zip_code').addClass('border-danger');
                        return false;
                    } else {
                        $('.zipcode_error').text('');
                        $('.zip_code').removeClass('border-danger');
                        $('.state').html('<option value="'+data.state_id+'" selected>'+data.state+'</option>');
                        $('.city').html('<option value="'+data.city_id+'" selected>'+data.city+'</option>');
                    }
                },
            });
        }
    });
$(".zip_code").keyup(function(){
    var zip = $(".zip_code").val();
    var length = $(".zip_code").val().length;
    if(length >= 5) {
        $('.state').html('<option value="">Select State</option>');
        $('.city').html('<option value="">Select City</option>');
        $.ajax({
            type: "POST",
            url: "/get_states_cities",
            data: {
                zip: zip,
            },
            success: function(data) {
                if (data == "") {
                    $('.zipcode_error').text('Please enter a valid zipcode');
                    $('.zip_code').addClass('border-danger');
                    return false;
                } else {
                    $('.zipcode_error').text('');
                    $('.zip_code').removeClass('border-danger');
                    $('.state').html('<option value="'+data.state_id+'" selected>'+data.state+'</option>');
                    $('.city').html('<option value="'+data.city_id+'" selected>'+data.city+'</option>');
                }
            },
        });
    }
});
</script>
@endsection
