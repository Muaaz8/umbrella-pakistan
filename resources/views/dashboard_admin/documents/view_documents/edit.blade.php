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
  CKEDITOR.replace( 'editor', {
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
                          <h4>Terms Of Use</h4>
                        </div>
                        </div>
                    <div class="home faqs_text_editor p-2">
                        <form action="{{ route('update_docs') }}" method="POST">
                            @csrf
                            <label > Edit Terms Of Use: </label>
                            <input type="hidden" name="id" value="{{ $data->id }}">
                          <textarea name="content" id="editor" value='{{ $data->content  }}'>{{ $data->content }}</textarea>
                          <div class="d-flex justify-content-between mt-3">
                            <button type="submit">Update</button>
                            <button type="button" onclick="window.location.href='/admin/documents/view'">Cancel</button>
                        </div>
                        </form>
                      </div>
                </div>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
      @if (Session::has('success'))
            <div class="alert alert-success">
                <ul>
                    <li>{!! \Session::get('success') !!}</li>
                </ul>
            </div>
        @endif
        </div>
      </div>
    </div>




</html>
@endsection
