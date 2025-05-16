@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <style>
        .location-details {
            box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .location-header {
            padding-bottom: 15px;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .location-label {
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .location-value {
            margin-bottom: 20px;
        }
        
        .nearby-locations {
            list-style-type: none;
            padding-left: 0;
        }
        
        .nearby-locations li {
            padding: 5px 0;
            border-bottom: 1px solid #f3f3f3;
        }
        
        .nearby-locations li:last-child {
            border-bottom: none;
        }
        
        .action-buttons {
            margin-top: 20px;
        }
        
        .btn-edit {
            background-color: #2196F3;
            color: white;
            border: none;
            margin-right: 10px;
        }
        
        .btn-back {
            background-color: #757575;
            color: white;
            border: none;
        }
    </style>
@endsection

@section('page_title')
    <title>CHCC - Location Details</title>
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
                                <h3>Location Details</h3>
                            </div>
                        </div>
                        
                        <div class="location-details mt-3" style="border-radius: 18px;">
                            <div class="location-header">
                                <h4>{{ $location->name }}</h4>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="location-label">Location ID</div>
                                    <div class="location-value">{{ $location->id }}</div>
                                    
                                    <div class="location-label">Name</div>
                                    <div class="location-value">{{ $location->name }}</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="location-label">Created At</div>
                                    <div class="location-value">{{ $location->created_at->format('F d, Y h:i A') }}</div>
                                    
                                    <div class="location-label">Last Updated</div>
                                    <div class="location-value">{{ $location->updated_at->format('F d, Y h:i A') }}</div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="location-label">Nearby Locations</div>
                                    @if($location->nearby)
                                        <ul class="nearby-locations">
                                            @foreach(explode(',', $location->nearby) as $nearby)
                                                <li>{{ trim($nearby) }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="location-value">No nearby locations specified</div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="action-buttons">
                                <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-edit">Edit</a>
                                <a href="{{ route('locations.index') }}" class="btn btn-back">Back to List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection