@extends('layouts.admin')
@section('css')
<style>
    #bg{
        padding: 30px 0px 0px 0px;
    }
</style>
<link href="asset_admin/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">

            <h2>Locations</h2>
            <small class="text-muted">All the Location Address to you are listed here</small>
            <div class="block-header">         

            @if($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    <strong>Danger!</strong>  {{ $error }}
                </div>
           
            @endforeach
            @endif
            @if (\Session::has('message'))
                <div class="alert alert-success">
                    <ul>
                        <li>{!! \Session::get('message') !!}</li>
                    </ul>
                </div>
            @endif

        </div>
        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>All Pharmacy Locations </h2>
					</div>
					<div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable js-sweetalert">
                            
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th >Address</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Zipcode</th>
                                    <th>Action</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $app)
                                 <tr>
                                    <td>{{$app->id}}</td>
                                    <td>{{$app->l_name}}</td>
                                    <td>{{$app->l_address}}</td>
                                    <td>{{$app->l_lat}}</td>
                                    <td>{{$app->l_long}}</td>
                                    <td>{{$app->l_zipcode}}</td>
                                    <td>
                                        <a href="delete_pharmacy_location/{{ $app->id }}" role="button" class="btn btn-secondary" style="background-color:#FF5966; color:white;" >Delete</a>
                                        <a href="edit_pharmacy_location/{{ $app->id }}" role="button" class="btn" style="background-color:#70cebe; color:white;" >Update</a>
                                        
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
<style>
table{
            border:5px ridge #606263 !important;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px !important;
            width:100% !important;
        }
        thead{
            background-color: #4676be !important;
            color:#fff !important;
            padding-left:30px !important;

        }

</style>
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



