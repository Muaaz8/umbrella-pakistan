@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Doctor Requests</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Pending Doctor Requests<small>All the requests for doctor accounts are here</small> </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <th>Name</th>
                                <th>State</th>
                                <th>Registered On</th>
                                <th>UPIN</th>
                                <th>NPI</th>
                                <th>
                                    <center>Action</center>
                                </th>
                            </thead>
                            <tbody>
                                @forelse($doctors as $doc)
                                @php
                                    // dd($doc);
                                @endphp
                                <tr>
                                    {{-- <td>{{\App\User::getName($doc->id)}} <span class="label-info label">new</span></td> --}}
                                    <td>{{ "Dr. ".ucfirst($doc->name)." ".ucfirst($doc->last_name) }}</td>
                                    <td>{{$doc->state_name}}</td>
                                    <td>{{$doc->created_at}}</td>
                                    <td>{{$doc->upin}}</td>
                                    <td>{{$doc->nip_number}}</td>
                                    <td>
                                        <center>
                                            <a href="{{ route('pending_doctor_detail',$doc->id)}}">
                                                <button class="btn btn-raised g-bg-cyan">Details</button></a>
                                        </center>

                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <center>No Pending Requests</center>
                                    </td>
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
@section('script')
<script src="assets/js/pages/tables/jquery-datatable.js"></script>
@endsection
