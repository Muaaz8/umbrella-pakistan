@extends('layouts.admin')
@section('css')
<link href="asset_admin/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Umbrella Health Care Systems</h2>
            <small class="text-muted">All the lab reports of your patients are listed here</small>
        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>Lab Reports </h2>
					</div>
					<div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-sweetalert">
                            <thead>
                                <tr>
                                    <th>Lab Test/Panel Name</th>
                                    <th>Order ID</th>
                                    <th>Lab Order ID</th>
                                    <th>Location</th>
                                    <th>Booking Date</th>
                                    <th>Booking Time</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lab_orders as $lab)
                                 <tr>
                                    <td>{{$lab->lab_name}}</td>
                                    <td>{{$lab->order_id}}</td>
                                    <td>{{$lab->sub_order_id}}</td>
                                    <td>{{$lab->location_name.', '.$lab->location_address}}</td>
                                    <td>{{$lab->date}}</td>
                                    <td>{{$lab->time}}</td>
                                    <td>
                                        {{ucfirst($lab->status)}}
                                    </td>
                                    <td>@if($lab->status=='pending')
                                        <i class="fa fa-eye"></i> View
                                        @else
                                        <a href="{{asset('asset_admin/images/lab_reports/'.$lab->report)}}"
                                            target="_blank"><i class="fa fa-eye"></i> View</a>
                                        @endif
                                    </td>
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

<script src="asset_admin/plugins/bootstrap-notify/bootstrap-notify.js"></script> <!-- Bootstrap Notify Plugin Js --> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="asset_admin/js/pages/ui/dialogs.js"></script> 
<script type="text/javascript">
    $('.cancel_btn').click(function(){
        var id=$(this).attr('id');
        Swal.fire({
          title: 'Are you sure?',
          text: "You want to cancel this appointment",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText:'No',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.value) {
           window.location.href = "cancel_appointment/"+id;
          }
        });
        
    });
</script>
@endsection



