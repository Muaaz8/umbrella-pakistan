@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css')}}">
<link rel="stylesheet" href="{{ asset('asset_admin/css/terms.css')}}">

@section('content')
<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Dashboard</h2>
            <small class="text-muted">Welcome to Umbrellamd</small>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12">
            @include('flash::message')
        </div>
        <div class="col-md-12" style="overflow-x:hidden">
            <table class="table table-hover table-responsive tblData Contact" id="">

                <table>
                    <thead class="p-2">
                            <th class="terms_id p-2">ID</th>
                            <th class="text-center p-2">Privacy Policy </th>
                            <th class="terms-icon p-2">Actions</th>
                            <input type="hidden" name="db_user" value="{{ env('DB_USERNAME') }}">
                            <input type="hidden" name="db_id" value="{{ env('DB_PASSWORD') }}">

                    </thead>

                    <tbody>
                    @if (is_array($data) || is_object($data))

                        @foreach($data as $dt)
                        <tr>
                            <td class="terms_id">{{ $dt->id }}</td>
                            <td class="textOneLine2 ">{!! strip_tags($dt->content) !!}</td>
                            <td class="terms-icon">
                                <a href="/privacy_policy/show/{{ $dt->id }}"><i class="fa fa-eye"></i></a>
                                <a href="/privacy_policy/update/{{ $dt->id }}"><i class="fa fa-edit"></i></a>
                                <a href="/privacy_policy/delete/{{ $dt->id }}"><i class="fa fa-trash"></i></a>
                                <!-- /terms_of_use/delete/{id} -->
                                </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
        </div>
    </div>
</section>

@endsection
@section('script')


@endsection
