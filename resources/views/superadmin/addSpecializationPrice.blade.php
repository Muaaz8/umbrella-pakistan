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
                    <form action="{{ route('storeSpecPrice') }}" method="POST">
                    @csrf
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group drop-custum">
                                        <select class="form-control show-tick" name="specialization_name" style="margin:0px !important;">

                                            @if($edit_data)
                                                <option value="{{ $edit_data->spec_id }}">{{ $edit_data->spec_name }}</option>
                                                @foreach ($spec as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="">-- Select Specialization --</option>
                                                @foreach ($spec as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group drop-custum">
                                        <select class="form-control show-tick" name="state_name" style="margin:0px !important;">

                                            @if($edit_data)
                                                <option value="{{ $edit_data->state_id }}">{{ $edit_data->state_name }}</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="">-- Select State --</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            @if($edit_data)
                                                <input type="hidden" name="specialization_price_id" value="{{ $edit_data->id }}">
                                                <input type="text" class="form-control" name="specialization_initial_price" value="{{ $edit_data->initial_price }}" placeholder="Initial Price" required>
                                            @else
                                                <input type="text" class="form-control" name="specialization_initial_price"  placeholder="Initial Price" required>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            @if($edit_data)
                                            <input type="text" class="form-control" name="specialization_follow_up_price" value="{{ $edit_data->follow_up_price }}" placeholder="Follow Up Price">
                                            @else
                                            <input type="text" class="form-control" name="specialization_follow_up_price" placeholder="Follow Up Price">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn callbtn">Submit</button>
                                    <button onclick="{{ route('addSpecPrice') }}" class="btn btn-raised">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
				</div>
			</div>
		</div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <th>Specialization</th>
                        <th>State Name</th>
                        <th>Initial Price</th>
                        <th>Follow Up Price</th>
                        <th>
                            <center>Action</center>
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($data as $spec)

                        <tr>
                            <td>{{ $spec->spec_name }}</td>
                            <td>{{ $spec->state_name }}</td>
                            <td>{{ $spec->initial_price }}</td>
                            <td>{{ $spec->follow_up_price }}</td>
                           <td style="padding:0px">
                                <center>
                                    <a href="{{ route('editPriceSpec',['id'=>$spec->id]) }}">
                                    <button class="btn p-2 btn-default btn-raised btn-circle waves-effect waves-circle waves-float"><i class="fa fa-edit"></i></button>
                                    </a>
                                    <a href="{{ route('delete.specialization',['id'=>$spec->id]) }}">
                                    <button class="btn p-2 btn-default btn-danger btn-raised btn-circle waves-effect waves-circle waves-float"><i class="fa fa-trash"></i></button>
                                    </a>
                                </center>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
@endsection
