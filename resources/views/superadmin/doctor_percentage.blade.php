@extends('layouts.admin')

@section('content')
    <section class="content">

        <div class="container-fluid">
            <div class="block-header">
                <h2>Add Doctor</h2>
                <small class="text-muted">Welcome to Swift application</small>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="body">
                            <form action="{{ route('add.doctor.percentage',['id'=>$doc_id]) }}" method="POST">
                                @csrf
                                @if(Session::has('message'))
                                    <div class="row clearfix">
                                        <div class="col-sm-3">
                                            <div class="alert alert-success">
                                                <ul>
                                                    <li>{{ Session::get('message')}}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($errors->any())
                                <div class="row clearfix">
                                    <div class="col-sm-3">
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="row clearfix">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" placeholder="Add Persentage" name="doc_percentage">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn callbtn">Submit</button>
                                        <a href="{{ route('admin_doctors') }}"><button type="button" class="btn btn-raised">Back</button></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
