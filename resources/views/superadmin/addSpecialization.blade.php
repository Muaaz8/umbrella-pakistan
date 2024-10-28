@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css')}}">
<link rel="stylesheet" href="{{ asset('asset_admin/css/table-responsiveness.css')}}">

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Add Specialization Price</h2>
            <small class="text-muted">Welcome to Umbrelamd Health Care</small>
        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="header">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <p><strong>Opps Something went wrong</strong></p>
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif

					</div>
                    <form action="{{ route('storeSpec') }}" method="POST">
                    @csrf
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group ">
                                        @if($edit_data)
                                            <input type="hidden" value="{{ $edit_data->id }}" name="specialization_id">
                                            <input type="text" value="{{ $edit_data->name }}" class="form-control" name="specialization_name" placeholder="Add Specialization Name">
                                        @else
                                            <input type="text" value="{{ $edit_data->name ?? '' }}" class="form-control" name="specialization_name" placeholder="Add Specialization Name">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">

                                                <select class="form-control show-tick" name="specialization_status" style="margin:0px !important;">
                                                    @if($edit_data)

                                                            @if ($edit_data->status==0)
                                                            <option value="{{ $edit_data->status }}">Deactive</option>
                                                            <option value="1">Active</option>
                                                            @else
                                                            <option value="{{ $edit_data->status }}">Active</option>
                                                            <option value="0">Deactive</option>
                                                            @endif


                                                    @else
                                                        <option value="">Select Status</option>
                                                        <option value="1">Active</option>
                                                        <option value="0">Deactive</option>
                                                    @endif
                                                </select>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">


                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn callbtn">Submit</button>
                                    <button onclick="{{ route('addSpec') }}" class="btn btn-raised">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
				</div>
			</div>
		</div>

    </div>
</section>
@endsection
