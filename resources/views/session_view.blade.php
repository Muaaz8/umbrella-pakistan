@extends('layouts.admin')
@section('css')
<link href="asset_admin/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Presription</h2>
            <small class="text-muted">Recommended treatment is listed here</small>
        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>Final Prescription </h2>
					</div>
                    <div class="col-md-12">
                        <span class="col-md-3 pl-0"><b>Doctor Name: </b></span>
                        <span class="col-md-9">{{ucwords($session->doc_name)}}</span>
                    </div>
                    <div class="col-md-12">
                        <span class="col-md-3 pl-0"><b>Diagnosis: </b></span>
                        <span class="col-md-9">{{ucfirst($session->diagnosis)}}</span>
                    </div>
                    <div class="col-md-12">
                        <span class="col-md-3 pl-0"><b>Date: </b></span>
                        <span class="col-md-9">{{$session->date}}</span>
                    </div>
                    <div class=" col-md-12 row clearfix">
                        <div class="col-md-6">
                            <span class="col-md-3 pl-0"><b>Start Time: </b></span>
                            <span class="col-md-9">{{$session->start_time}}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="col-md-3 pl-0"><b>End Time: </b></span>
                            <span class="col-md-9">{{$session->end_time}}</span>
                        </div>
                    </div>
					<div class="body table-responsive">
                        <table id="table" class="table table-bordered table-striped table-hover ">
                            <thead>
                                <tr>
                                    <th>Recommendation</th>
                                    <th>Dosage</th>
                                    <th>Comment</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($session->prescription as $pres)
                                 <tr>
                                 <td>{{ucwords($pres->prod_detail->name)}}</td>
                                 <td>{{$pres->usage}}</td>
                                 <td>{{ucwords($pres->comment)}}</td>
                                <td>{{ucwords($pres->type)}}</td>
                                <td><center>                                        
                                    <a href="#"><button class="btn btn-raised g-bg-cyan">Order</button></a>
                                </center> </td>
                                </tr>
                                @empty
                                <tr><td colspan="5"><center>No products added</center></td></tr>
                            @endforelse
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
<script src="{{asset('asset_admin/plugins/jquery-datatable/jquery.dataTables.min.js')}}"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('#table').DataTable();
});
</script>
@endsection



