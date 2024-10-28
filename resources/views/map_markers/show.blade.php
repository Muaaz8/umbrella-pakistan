@extends('layouts.admin')

@section('content')
    <section class="content home">
        <div class="container-fluid">

            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted">Mig Media</small>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2>Map Markers<small>Marker Details</small> </h2>
                          
                        </div>
                        <div class="row" style="padding-left: 20px">
                            @include('map_markers.show_fields')
                        </div>
    
                        <div class="row clearfix" style="padding-left: 20px">
                            <div class="col-md-3">
                                <a href="{{ route('mapMarkers.index') }}" class="btn btn-default">Back</a>
                            </div>
                         </div>
    
                        </div>
                    </div>
                </div>
    
            </div>


        </div>
       

      
    </section>
    {{-- <style>
    .form-group{
    margin-top:32px !important;
    text-align:center !important;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px; 
     border-radius: 10px !important;
     margin-left:32px;
     padding:35px !important;
  
}
    </style> --}}
@endsection
