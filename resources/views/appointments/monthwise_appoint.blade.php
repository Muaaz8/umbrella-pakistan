@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>All Appointments</h2>
            <small class="text-muted">All the appointments schedule to you are listed here</small>
        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>All Appointments(Monthwise)<small>All the appointments schedule to you are listed here</small> </h2>
                        
					</div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                                <div class="panel-group" id="accordion_10" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-col-blue-grey">
                                        <div class="panel-heading" role="tab" id="headingOne_10">
                                            <h4 class="panel-title"> <a role="button" data-toggle="collapse" data-parent="#accordion_10" href="#collapseOne_10" aria-expanded="true" aria-controls="collapseOne_10"> August 2020</a> </h4>
                                        </div>
                                        <div id="collapseOne_10" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_10">
                                            <div class="panel-body"> 
<div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>Session No.</th>
                                    <th>Patient Name</th>
                                    <th>Earning</th>
                                    <th>Patient Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <tr>
                                    <td>S12</td>
                                    <td>Sara Khan</td>
                                    <td>$35.4</td>
                                    <td>Feeling better already.</td>

                                </tr>
                                <tr>
                                    <td>S32</td>
                                    <td>Tiger Nixon</td>
                                    <td>$40.5</td>
                                    <td>Detailed checkup for my sore throat</td>

                                </tr>
                                <tr>
                                    <td>S34</td>
                                    <td>Garrett Winters</td>
                                    <td>$40.5</td>
                                    <td>Covered maximum stuff in precise session</td>

                                </tr>
                                <tr>
                                    <td>S45</td>
                                    <td>Ashton Cox</td>
                                    <td>$50.5</td>
                                    <td>Feeling better already.</td>

                                </tr>
                                <tr>
                                    <td>S32</td>
                                    <td>Jennifer Acosta</td>
                                    <td>$25.5</td>
                                    <td>Feeling better already.</td>

                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel ">
                                        <div class="panel-heading" role="tab" id="headingTwo_10">
                                            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_10" href="#collapseTwo_10" aria-expanded="false"
                                                       aria-controls="collapseTwo_10"> July 2020 </a> </h4>
                                        </div>
                                        <div id="collapseTwo_10" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_10">
                                            <div class="panel-body"> 
<div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>Session No</th>
                                    <th>Patient Name</th>
                                    <th>Earning</th>
                                    <th>Patient Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <tr>
                                    <td>S12</td>
                                    <td>Sara Khan</td>
                                    <td>$35.4</td>
                                    <td>Feeling better already.</td>

                                </tr>
                                <tr>
                                    <td>S32</td>
                                    <td>Tiger Nixon</td>
                                    <td>$40.5</td>
                                    <td>Detailed checkup for my sore throat</td>

                                </tr>
                                <tr>
                                    <td>S34</td>
                                    <td>Garrett Winters</td>
                                    <td>$40.5</td>
                                    <td>Covered maximum stuff in precise session</td>

                                </tr>
                                <tr>
                                    <td>S45</td>
                                    <td>Ashton Cox</td>
                                    <td>$50.5</td>
                                    <td>Feeling better already.</td>

                                </tr>
                                <tr>
                                    <td>S08</td>
                                    <td>Jennifer Acosta</td>
                                    <td>$25.5</td>
                                    <td>Feeling better already.</td>

                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-col-blue-grey">
                                        <div class="panel-heading" role="tab" id="headingThree_10">
                                            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_10" href="#collapseThree_10" aria-expanded="false"
                                                       aria-controls="collapseThree_10"> June 2020 </a> </h4>
                                        </div>
                                        <div id="collapseThree_10" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_10">
                                            <div class="panel-body"> 

                                            <div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>Session No</th>
                                    <th>Patient Name</th>
                                    <th>Earning</th>
                                    <th>Patient Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <tr>
                                    <td>S12</td>
                                    <td>Sara Khan</td>
                                    <td>$35.4</td>
                                    <td>Feeling better already.</td>

                                </tr>
                                <tr>
                                    <td>S08</td>
                                    <td>Tiger Nixon</td>
                                    <td>$40.5</td>
                                    <td>Detailed checkup for my sore throat</td>

                                </tr>
                                <tr>
                                    <td>S34</td>
                                    <td>Garrett Winters</td>
                                    <td>$40.5</td>
                                    <td>Covered maximum stuff in precise session</td>

                                </tr>
                                <tr>
                                    <td>S45</td>
                                    <td>Ashton Cox</td>
                                    <td>$50.5</td>
                                    <td>Feeling better already.</td>

                                </tr>
                                <tr>
                                    <td>S08</td>
                                    <td>Jennifer Acosta</td>
                                    <td>$25.5</td>
                                    <td>Feeling better already.</td>

                                </tr>
                                
                            </tbody>
                        </table>
                    </div>

                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Examples With Material Design Colors --> 

					
    </div>
</section>
@endsection
@section('script')
<script src="assets/js/pages/tables/jquery-datatable.js"></script>
@endsection



