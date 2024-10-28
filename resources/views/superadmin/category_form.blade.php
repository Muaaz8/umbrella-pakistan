@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css')}}">
<link rel="stylesheet" href="{{ asset('asset_admin/css/table-responsiveness.css')}}">

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Add Product Category</h2>
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
                    <form action="{{ route('add_product_category') }}" method="POST">
                    @csrf
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="category" placeholder="Enter New Category Name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn callbtn">Submit</button>
                                    <button onclick="{{ url('/productCategory') }}" class="btn btn-raised">Cancel</button>
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
