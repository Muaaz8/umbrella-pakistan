@extends('layouts.admin')
@section('content')
<section class="content">
    <div class="container-fluid">
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
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Location Form<small>Fill the form below</small> </h2>
                    </div>
                    <div class="body">
                        <form method="POST" action="{{route('update_pharmacy_location')}}">
                            @csrf
                            @method('PUT')
                            @foreach($data as $app)
                            <h5><b>Add Pharmacy Location</b></h5>
                            <hr style="background-color: #ccc;">
                            <div class="row clearfix">
                                <input type="hidden" name="id" value="{{$app->id}}">
                                <div class="col-sm-6 col-xs-12 ">
                                    <div class="form-group">
                                        <div class="form-line">                                         
                                            <input type="text" name="l_name" value="{{$app->l_name}}" class="form-control" placeholder="Location Name" required>
                                        </div>
                                    </div>
                                </div>  
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" name="l_zipcode" value="{{$app->l_zipcode}}" class="form-control" placeholder="Location Zipcode">
                                        </div>
                                    </div>
                                </div>                               
                                
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="l_lat" value="{{$app->l_lat}}" class="form-control" placeholder="Location Latitude">
                                        </div>
                                    </div>
                                </div>                                
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="l_long" value="{{$app->l_long}}" class="form-control" placeholder="Location Longitude">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="l_address" value="{{$app->l_address}}" class="form-control" placeholder="Location Complete Address">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-raised g-bg-cyan">Submit</button>
                            </div>
                        </div>
                        </div>
                        @endforeach
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="asset_admin/plugins/autosize/autosize.js"></script> <!-- Autosize Plugin Js --> 
<script src="asset_admin/js/pages/forms/basic-form-elements.js"></script>

<script src="asset_admin/plugins/momentjs/moment.js"></script> <!-- Moment Plugin Js --> 
<script src="asset_admin/plugins/dropzone/dropzone.js"></script> <!-- Dropzone Plugin Js -->

<!-- Bootstrap Material Datetime Picker Plugin Js --> 
<script src="asset_admin/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script> 


@endsection
