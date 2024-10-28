@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Add New Item</h2>
        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body">
                        <!-- <button type="button" class="btn  btn-raised col-md-4 bg-red btn-block btn-lg waves-effect">Pharmacy</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-purple btn-block btn-lg waves-effect">Lab</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-cyan btn-block btn-lg waves-effect">Dialysis</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-orange btn-block btn-lg waves-effect">Physical Therapy</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-deep-purple btn-block btn-lg waves-effect">Home Health Care</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-blue btn-block btn-lg waves-effect">Imaging Item</button> -->
                        <a href="{{url('/mentalConditions')}}">
                            <button type="button" class="btn  btn-raised col-md-3 bg-green btn-block btn-lg waves-effect">Conditions</button>
                        </a>
                        <!-- <button type="button" class="btn  btn-raised col-md-4 bg-indigo btn-block btn-lg waves-effect">Dental Item</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-lime btn-block btn-lg waves-effect">Heart Item</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-light-green btn-block btn-lg waves-effect">Eye-Care Item</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-brown btn-block btn-lg waves-effect">Kidney Item</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-deep-orange btn-block btn-lg waves-effect">Symptom</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-grey btn-block btn-lg waves-effect">Pharmacy Store</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-amber btn-block btn-lg waves-effect">Patient</button>
                        <button type="button" class="btn  btn-raised col-md-4 bg-black btn-block btn-lg waves-effect">Doctor</button> -->
                        <a href="{{url('/faqs')}}">
                            <button type="button" class="btn  btn-raised col-md-3 bg-blue-grey btn-block btn-lg waves-effect">FAQs</button>
                        </a>
                        <a href="{{url('/productCategory')}}">
                            <button type="button" class="btn  btn-raised col-md-3 bg-red btn-block btn-lg waves-effect">Add Product Category</button>
                        </a>



                    </div>
				</div>
			</div>
		</div>
    </div>
</section>
@endsection

@section('script')
<script src="asset_admin/js/pages/forms/basic-form-elements.js"></script>
<script src="asset_admin/plugins/momentjs/moment.js"></script> <!-- Moment Plugin Js -->

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="asset_admin/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

<script src="asset_admin/plugins/autosize/autosize.js"></script> <!-- Autosize Plugin Js -->
<script type="text/javascript">
    $('#input_starttime').pickatime({});
</script>

@endsection
