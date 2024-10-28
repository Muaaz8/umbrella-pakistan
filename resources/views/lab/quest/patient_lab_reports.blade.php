@extends('layouts.admin')
@section('content')
{{-- {{ dd('ok') }} --}}
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Lab Results</h2>
        </div>
        <div class="card">
            <div class="body">

                <table class="table-responsive table table-hover table-bordered col-12">
                    <thead>
                        <tr>
                            <!-- <th width="50">Sr. #</th> -->
                            <th width="550" class="text-center">Test Name/(s)</th>
                            @if(auth()->user()->user_type=='patient')
                            <th width="550" class="text-center">Provider Name</th>
                            @else
                            <th width="550" class="text-center">Patient Name</th>
                            @endif
                            <th width="550" class="text-center">Result Status</th>
                            <th width="550" class="text-center">Specimen Collection Date</th>
                            <th width="550" class="text-center">Result Date</th>
                            <!-- <th width="550" class="text-center">Out of Range Values</th> -->
                            <th width="200" colspan="2" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @forelse($reports as $report)
                            <!-- <td width="20%">{{$report->id}}</td> -->
                            <td width="40%">{{$report->test_names}}</td>
                            @if(auth()->user()->user_type=='patient')
                            <td width="10%">{{$report->doctor}}</td>
                            @else
                            <td width="10%">{{$report->patient}}</td>
                            @endif
                            <td width="10%">{{$report->type}}</td>
                            <td width="10%">{{$report->specimen_date}}</td>
                            <td width="10%">{{$report->result_date}}</td>
                            {{--@if($report->out_of_range!='No out of range value')
                            <td width="20%" style="color:red;font-weight:bold">{{$report->out_of_range}}</td>
                            @else
                            <td width="20%">{{$report->out_of_range}}</td>
                            @endif
                            --}}<td width="10%">
                                <a href="{{url(\App\Helper::get_files_url($report->file))}}"  class="btn btn-primary">
                                    View Full Report
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6"><center>No Reports To Show</center></td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
                {{$reports->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
</section>
@endsection
