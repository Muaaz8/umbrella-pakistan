@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')


  <script src="//cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
  <script src="{{ url('assets/ckeditor') }}/ckeditor.js"></script>
  <script>
  CKEDITOR.replace( 'content', {
    enterMode: CKEDITOR.ENTER_BR,
    on: {'instanceReady': function (evt) { evt.editor.execCommand('');}},
    });
</script>
@endsection

@section('content')


        <div class="dashboard-content">
            <div class="container-fluid">
                <div class="row m-auto">
                    <div class="d-flex justify-content-between align-items-center border-bottom">
                        <div>
                          <h4>Mental Conditions
                            <br>
                            <p class="fs-6 fw-normal">Fill The Form Below</p></h4>
                        </div>
                        </div>
                    <div class="home ">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <form action="{{route('insert_condition')}}" method="POST">
                            @csrf
                            @method('POST')

                            <label > Name </label>
                            <input type="text" name="name" placeholder="Title" name="title" class="w-100">
                            <label >Content</label>
                          <textarea name="content" id="content"></textarea>
                          <div class="d-flex justify-content-between mt-3">
                            <button class="btn process-pay" type="submit">Save</button>
                            <input class="btn btn-secondary" type="button" value="cancel" onClick="document.location.href='{{ route('mental_condition') }}';" />
                            {{--  <button>Cancel</button>  --}}
                        </div>
                        </form>
                      </div>
                </div>
            </div>

        </div>
      </div>
    </div>
@endsection
