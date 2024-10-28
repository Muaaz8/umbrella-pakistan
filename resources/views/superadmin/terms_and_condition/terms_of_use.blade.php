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

                        <form method="POST" action="/submit_terms_of_use">
                         @csrf()
                        <!-- {!! Form::open(['route' => 'submit_terms_of_use']) !!} -->
                                <div class="form-group col-sm-12">
                                    <label><strong>Terms OF Use :</strong></label>
                                    <input hidden name="name" value="term of use">
                                    <textarea class="form-control" id="terms_of_use" name="content"></textarea> 
                                </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn callbtn">Save</button>
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

