@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Admin Dashboard</title>
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
                        <div class="d-flex flex-wrap justify-content-between align-items-baseline p-0">
                            <h3>All In Clinics Patients</h3>
                            <div class="col-md-4 col-sm-6 col-12 p-0">
                                <div class="input-group">
                                    <a href="{{ route('in_clinics_create') }}" class="btn process-pay">Add new</a>
                                </div>
                            </div>
                        </div>
                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Patient Name</th>
                                        <th scope="col">Patient Number</th>
                                        <th scope="col">Patient Email</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{$item->id}}</td>
                                            <td>{{$item->user->name}}</td>
                                            <td>{{$item->user->phone_number}}</td>
                                            <td>{{$item->user->email}}</td>
                                            <td>{{$item->created_at}}</td>
                                            <td data-label="Action">
                                                <a>
                                                    <form action="#" method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                        <input class="btn btn-danger" type="submit" value="Delete" />
                                                    </form>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="m-auto text-center for-empty-div">
                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                    <h6>No Related Products To Show</h6>
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
