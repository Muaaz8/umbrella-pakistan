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

                        <form method="POST" action="{{ url('privacy_policy/update/'.$data->id) }}">
                         {{ csrf_field() }}
                         @method('PUT')

                                <div class="form-group col-sm-12">
                                    <label><strong>Privacy Policy :</strong></label>

                                    <textarea class="form-control" id="terms_of_use" name="content" value="">
                                    {!! ($data->content) !!}
                                    </textarea>
                                </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn callbtn">Update</button>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace( 'terms_of_use' );
</script>

@endsection

