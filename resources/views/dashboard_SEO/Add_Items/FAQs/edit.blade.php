@extends('layouts.dashboard_SEO')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - SEO Dashboard</title>
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
                          <h4>CREATE FAQ
                            <br>
                            <p class="fs-6 fw-normal">Fill The Form Below</p></h4>
                        </div>
                        </div>
                    <div class="home ">
                        <form action="{{route('update_faqs',$tblFaq->id)}}" method="POST">
                            @csrf
                            @method('POST')
                            <label > Questions </label>
                            <input type="text" name="questions" placeholder="Title" name="title" class="w-100" value="{{$tblFaq->question}}">
                            <label > Answer </label>
                          <textarea name="answers" id="editor" value="{{$tblFaq->answer}}">{{$tblFaq->answer}}</textarea>
                          <div class="d-flex justify-content-between mt-3">
                            <button class="btn process-pay" type="submit">Save</button>
                            <input class="btn btn-secondary" type="button" value="cancel" onClick="document.location.href='{{ route('FAQs') }}';" />
                            {{--  <button>Cancel</button>  --}}
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
@endsection
