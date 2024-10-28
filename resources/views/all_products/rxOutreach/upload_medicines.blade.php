@extends('layouts.admin')

@section('content')
    <section class="content home">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Dashboard</h2>
                <small class="text-muted">Welcome to Umbrellamd</small>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    @include('flash::message')
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h4 class="text-center">Upload RxOutreach Medicine</h4>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::open(['url' => '/uploadMedicineByCSV', 'files' => true]) !!}
                                    <div class="form-group">
                                        <label for="file">Upload CSV File Here</label>
                                        <input type="file" class="form-control-file" id="file" name="file" accept=".csv"
                                            required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                    {!! Form::close() !!}
                                </div>
                                <div class="col-md-6 text-right">
                                    <p>
                                        <a href="{{ asset('csv/sample_medicine_upload.csv') }}" download
                                            class="btn btn-primary">Download Sample</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
