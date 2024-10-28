@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css')}}">
<link rel="stylesheet" href="{{ asset('asset_admin/css/table-responsiveness.css')}}">

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Add Psychiatry Service</h2>
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
                    <form action="{{ route('storePsycService') }}" method="POST">
                    @csrf
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label><strong>Service :</strong></label>
                                            <input type="text" name="service_name" placeholder="Service Name">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label><strong>Description :</strong></label>

                                            <textarea class="form-control" id="terms_of_use" name="content" value="">

                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">


                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn callbtn">Submit</button>
                                    <button onclick="{{ route('view_psychiatrist_services') }}" class="btn btn-raised">Cancel</button>
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

@section('script')
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace( 'terms_of_use' );
</script>

@endsection
