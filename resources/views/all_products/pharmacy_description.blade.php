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
                    <div class="card">

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            @include('flash::message')
                        </div>
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div id="messageDiv">
                                    <div class="alert alert-danger">
                                        <strong>Danger!</strong> {{ $error }}
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <form method="POST" action="{{ url('/medicine_desc') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group p-3">
                                <label>Select Medicine</label>
                                <select class="form-control medicineID" name="medicine">
                                    @foreach ($data as $dt)
                                        <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex">
                                <div class="form-group col-sm-6">
                                    <label><strong>Short Description :</strong></label>
                                    <textarea class="short_description form-control" name="short_description"></textarea>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label><strong>Description :</strong></label>
                                    <textarea class="description form-control" name="description"></textarea>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@section('script')
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $('.medicineID').on('change', function(e) {
            var optionSelected = $("option:selected", this);
            var valueSelected = this.value;
            var URL = "/medicine_description?id=" + this.value;
            $.ajax({
                type: "GET",
                url: URL,
                success: function(response) {
                    var result = JSON.parse(response);
                    // console.log(result);
                    CKEDITOR.replace('description');
                    CKEDITOR.add
                    CKEDITOR.instances['description'].setData(result.description);
                    CKEDITOR.replace('short_description');
                    CKEDITOR.add
                    CKEDITOR.instances['short_description'].setData(result.short_description);
                },
            });
        });
    </script>
@endsection
