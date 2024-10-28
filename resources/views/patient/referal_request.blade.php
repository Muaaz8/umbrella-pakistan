@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Umbrella Health Care Systems</h2>
            <!-- <small class="text-muted">All prescribed medications are listed here</small> -->
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Refered Doctors
                            <small>Accept request so that you can book appointment with them and they can see your
                                medical records</small>
                        </h2>
                    </div>
                    <div class="body">
                    <div class="col-12">
                        <table class="table">
                            <thead>
                                <th>Doctor</th>
                                <th>Speciality</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @forelse($referals as $referal)
                                <td>{{$referal->fname.' '.$referal->lname}}</td>
                                <td>{{$referal->name}}</td>
                                <td>
                                    <a href="{{route('accept_referal',$referal->ref_id)}}">
                                        <button class="btn btn-primary btn-raised">Accept</button>
                                    </a>
                                    <a href="{{route('decline_referal',$referal->ref_id)}}">
                                        <button class="btn btn-danger btn-raised">Decline</button>
                                    </a>
                                </td>
                                @empty
                                <tr><td colspan="3"><center>No Requests</center></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection