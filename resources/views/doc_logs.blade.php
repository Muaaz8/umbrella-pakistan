@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Doctor's Activity Log</h2>
        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>Dr. John's Log</h2>
					</div>
					<div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>Activity</th>
                                    <th>Type</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <tr class="bg-cyan">
                                    <td>Updated Profile Picture</td>
                                    <td>Profile</td>
                                    <td>04/July/2020 12:23</td>

                                </tr>
                                <tr class="bg-indigo">
                                    <td>Attended Session #SD21</td>
                                    <td>Session</td>
                                    <td>04/July/2020 12:23</td>

                                </tr>
                                <tr class="bg-grey">
                                    <td>Viewed file of patient #21</td>
                                    <td>Patient info</td>
                                    <td>04/July/2020 12:23</td>

                                </tr>
                                <tr class="bg-indigo">
                                    <td>Attended Session #SD51</td>
                                    <td>Session</td>
                                    <td>04/July/2020 12:23</td>


                                </tr>
                                <tr class="bg-grey">
                                    <td>Viewed file of patient #23</td>
                                    <td>Patient info</td>
                                    <td>04/July/2020 12:23</td>


                                </tr>
                                  <tr class="bg-brown">
                                    <td>Logged in</td>
                                    <td>Account activity</td>
                                    <td>04/July/2020 12:23</td>
                                    

                                </tr>
                                <tr class="bg-brown">
                                    <td>Logged out</td>
                                    <td>Account activity</td>
                                    <td>04/July/2020 12:23</td>


                                </tr>

                                <tr class="bg-indigo">
                                    <td>Attended Session #SD21</td>
                                    <td>Session</td>
                                    <td>04/July/2020 12:23</td>

                                </tr>
                                <tr class="bg-grey">
                                    <td>Viewed file of patient #21</td>
                                    <td>Patient info</td>
                                    <td>04/July/2020 12:23</td>

                                </tr>
                                <tr class="bg-indigo">
                                    <td>Attended Session #SD51</td>
                                    <td>Session</td>
                                    <td>04/July/2020 12:23</td>
                                </tr>
                                <tr class="bg-brown">
                                    <td>Logged in</td>
                                    <td>Account activity</td>
                                    <td>04/July/2020 12:23</td>


                                </tr>
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
<script src="assets/js/pages/tables/jquery-datatable.js"></script>
@endsection



