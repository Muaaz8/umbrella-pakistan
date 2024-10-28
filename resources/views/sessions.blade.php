@extends('layouts.admin')
@section('css')
<link href="asset_admin/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>All Sessions</h2>
            <small class="text-muted"></small>
        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>All Sessions </h2>
					</div>
					<div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-sweetalert">
                            <thead>
                                <tr>
                                    <th>Doctor Name</th>
                                    <th>Doctor Specialization</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $session)
                                 <tr>
                                    <td>Dr. Imran Ali</td>
                                    <td>None</td>
                                    <td>Medications Recommended</td>
                                    <td>{{$session->date}}</td>
                                    <td><center>                                        
                                <a href="{{ route('session.show',$session->id)}}">
                                            <button class="btn btn-raised g-bg-cyan">Details</button></a>
                                        </center></td>

                                </tr>
                                @endforeach
                               
                            </tbody>
                            
                        </table>
                    </div>


				</div>
			</div>
		</div>
    </div>
</section>
@endsection
@section('script')
<script src="asset_admin/js/pages/tables/jquery-datatable.js"></script>
@endsection



