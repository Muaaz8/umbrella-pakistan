@extends('layouts.admin')

@section('content')

<section class="content home">
   <div class="container-fluid ">
      <div class="block-header">
         <h2>Dashboard</h2>
         <small class="text-muted">Welcome to Umbrellamd</small>
      </div>
      <div class="row clearfix">
         <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
               <div class="header">
                  <h2 class="" >Add Map Markers <small>All Locations</small> </h2>
                  <h1 class="pull-right" tyle="margin:0px;">
                     {{-- <a class="btn btn-primary pull-right" style="margin-bottom: 25px;color: white;margin-top: -20px;" href="create?form_type=pharmacy">Add New</a> --}}
                  </h1>
               </div>
               <div class="conte2nt">
                  <div class="clearfix"></div>
                  @include('flash::message')
                  <div class="clearfix"></div>
                  <div class="box box-primary">
                     <div class="box-body">
                        @include('map_markers.table')
                     </div>
                  </div>
                  <div class="text-center">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
  
@endsection

@section('script')
<script src="asset_admin/js/pages/index.js"></script>
<script src="asset_admin/js/pages/charts/sparkline.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/datatables.min.js"></script>
<script>
    $(document).ready( function () {
    $('.tblData').DataTable();
});
</script>
@endsection


