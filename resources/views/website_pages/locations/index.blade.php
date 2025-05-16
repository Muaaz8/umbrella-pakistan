@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <style>
        .location-table {
            box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .action-buttons .btn {
            margin-right: 5px;
        }
        
        .btn-add-location {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
        }
        
        .btn-edit {
            background-color: #2196F3;
            color: white;
            border: none;
        }
        
    </style>
@endsection

@section('page_title')
    <title>CHCC - Locations</title>
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
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <div>
                                <h3>Manage Locations</h3>
                            </div>
                            <div>
                                <a href="{{ route('locations.create') }}" class="btn process-pay">Add New Location</a>
                            </div>
                        </div>
                        
                        @if (session('success'))
                            <div class="alert alert-success mt-3" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <div class="mt-3" style="border-radius: 18px;">
                            <div class="wallet-table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>S.no#</th>
                                            <th>Name</th>
                                            <th>Nearby Locations</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($locations as $key => $location)
                                            <tr>
                                                <td>{{ $locations->firstItem() + $key }}</td>
                                                <td>{{ $location->name }}</td>
                                                <td>{{ $location->nearby }}</td>
                                                <td class="action-buttons">
                                                    <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-sm btn-edit">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('locations.destroy', $location->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this location?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No locations found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $locations->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection