@extends('layouts.admin')
@section('content')
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Patients</h2>
        </div>
        <div class="card">
            <div class="body">

                <table class="table-responsive table table-hover table-bordered col-12">
                    <thead>
                        <tr>
                            <th width="550">First Name</th>
                            <th width="550">Last Name</th>
                            <th width="200" colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @forelse($patients as $patient)

                            <td width="20%">{{$patient->pat_first_name}}</td>
                            <td width="20%">{{$patient->pat_last_name}}</td>
                            <td width="30%">
                                <a href="{{url('lab_reports/'.$patient->pat_id)}}">
                                    <button class="btn btn-primary">All reports</button>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">No Reports To Show</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
                {{$patients->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
</section>
@endsection