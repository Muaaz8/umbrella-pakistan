@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>{{ucwords($doctor->name." ".$doctor->last_name)}}</h2>
        </div>
        <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>Activity Log</h2>
					</div>
					<div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <th>Activity</th>
                                <th>Date</th>
                                <th>Time</th>
                            </thead>
                            <tbody>
                            @forelse($activities as $activity)
                            <tr>
                                <td>{{ucfirst($activity->activity)}}</td>
                                <td>{{$activity->date}}</td>
                                <td>{{$activity->time}}</td>
                            </tr>
                            @empty
                            <tr>
                            <td colspan="3"><center>No Activity Yet</center></td>
                            </tr>
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