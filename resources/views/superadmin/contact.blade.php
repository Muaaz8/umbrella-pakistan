@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css')}}">
<link rel="stylesheet" href="{{ asset('asset_admin/css/table-responsiveness.css')}}">

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted">Welcome to Umbrellamd</small>
            </div>
            <div class="col-md-12" style="overflow-x:hidden">
                <table class="table table-hover table-responsive tblData Contact" id="">
                    <thead >
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subject</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $tblcnt)
                            <tr>
                                <td>{{ $tblcnt->name }}</td>
                                <td>{{ $tblcnt->email }}</td>
                                <td>{{ $tblcnt->phone }}</td>
                                <td>{{ $tblcnt->subject}}</td>
                                <td> <a href="/admin_contact/{{ $tblcnt->id }}" class=''><i class="fa fa-eye"></i></a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="paginateCounter link-paginate">
                {{ $data->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>
@endsection
