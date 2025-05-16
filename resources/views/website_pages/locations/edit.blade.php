@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <style>
        .required-field::after {
            content: " *";
            color: red;
        }

        .form-section {
            box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .form-section-title {
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .nearby-input {
            margin-bottom: 10px;
        }

        .btn-add-nearby {
            margin-top: 10px;
        }

        .nearby-list {
            margin-top: 15px;
        }

        .nearby-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .remove-nearby {
            margin-left: 10px;
            color: red;
            cursor: pointer;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
        }
    </style>
@endsection

@section('page_title')
    <title>CHCC - Edit Location</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
    <script>
        // Initialize nearby locations from existing data
        let nearbyLocations = [];

        @if($location->nearby)
            nearbyLocations = [{{ Illuminate\Support\Js::from(array_map('trim', explode(',', $location->nearby))) }}][0];
            updateNearbyLocationsList();
        @endif

            function addNearbyLocation() {
                const input = document.getElementById('nearby_input');
                const location = input.value.trim();

                if (location) {
                    nearbyLocations.push(location);
                    updateNearbyLocationsInput();
                    updateNearbyLocationsList();
                    input.value = '';
                }
            }

        function removeNearbyLocation(index) {
            nearbyLocations.splice(index, 1);
            updateNearbyLocationsInput();
            updateNearbyLocationsList();
        }

        function updateNearbyLocationsInput() {
            document.getElementById('nearby').value = nearbyLocations.join(', ');
        }

        function updateNearbyLocationsList() {
            const list = document.getElementById('nearby_list');
            list.innerHTML = '';

            nearbyLocations.forEach((location, index) => {
                const item = document.createElement('div');
                item.className = 'nearby-item';
                item.innerHTML = `
                        <span>${location}</span>
                        <span class="remove-nearby" onclick="removeNearbyLocation(${index})">
                            <i class="fa fa-times"></i> Remove
                        </span>
                    `;
                list.appendChild(item);
            });
        }
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <div>
                                <h3>Edit Location</h3>
                            </div>
                        </div>
                        <div class="wallet-table" style="border-radius: 18px;">
                            <form action="{{ route('locations.update', $location->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="p-3">
                                    <!-- Location Information Section -->
                                    <div class="form-section">
                                        <h5 class="form-section-title">Location Information</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2 required-field" for="name">Location
                                                    Name</label>
                                                <input id="name" type="text"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    value="{{ old('name', $location->name) }}" required
                                                    placeholder="Enter Location Name">
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bolder mb-2" for="nearby">Nearby Locations</label>
                                                <input id="nearby" type="hidden"
                                                    class="form-control @error('nearby') is-invalid @enderror" name="nearby"
                                                    value="{{ old('nearby', $location->nearby) }}">

                                                <div class="nearby-input-wrapper">
                                                    <div class="input-group">
                                                        <input id="nearby_input" type="text" class="form-control"
                                                            placeholder="Enter nearby location name">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn process-pay"
                                                                onclick="addNearbyLocation()">
                                                                Add
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        Add multiple nearby locations one by one
                                                    </small>
                                                </div>

                                                <div id="nearby_list" class="nearby-list"></div>

                                                @error('nearby')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    @if (session('success'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="row mt-3">
                                        <div class="text-end">
                                            <button type="submit" class="btn process-pay">Update</button>
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