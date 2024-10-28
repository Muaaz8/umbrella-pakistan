@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css') }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

@section('content')
<section class="content ovrflw">
    <div class="container-fluid">
        <div class="block-header">
            <h2>All States</h2>
            <ul class="breadcrumb mb-0 pb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0);">States</a></li>
            </ul>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table
                    class="table table-bordered table-responsive table-striped table-hover dataTable">
                    <thead>
                        <th>Name</th>
                        <th>State Code</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($states as $state)
                        <tr>
                            <td>{{ $state->name  }}</td>
                            <td>{{ $state->state_code  }}</td>
                            <td>{{ ($state->active==1)?'Active':'Not active' }}</td>

                            <td>
                                <div class="d-flex">
                                    @if($state->active==0)
                                    <a href="{{ route('activate_state', $state->id) }}" title="Activate">
                                        <button class="btn btn-raised btn-success px-2 py-2">Activate</button>
                                    </a>
                                    @else
                                    <a href="{{ route('deactivate_state', $state->id) }}" title="Deactivate">
                                        <button class="btn btn-raised btn-danger px-2 py-2">Deactivate</button>
                                    </a>
                                    @endif
                                </div>

                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row d-flex justify-content-center">
                    <div class="paginateCounter">
                        {{ $states->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
