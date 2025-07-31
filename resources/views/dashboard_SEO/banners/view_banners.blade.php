@extends('layouts.dashboard_SEO')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - View Banners</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
    $(document).ready(function(){
        var array = $('#array').val();
        $.each (JSON.parse(array), function (key, arr) {
            if(arr.img==null)
            {
                $('#iframe_'+arr.id).html(arr.html);
            }
        });
    });
    function update(id)
    {
        window.location.href="/change/banner/status/"+id;
    }
</script>
@endsection


@section('content')
<input type="hidden" id="array" value="{{json_encode($banners)}}">
<div class="dashboard-content">
            <div class="col-11 m-auto">
            <div class="account-setting-wrapper bg-white">
                <h4 class="pb-4 border-bottom">Ads Status</h4>
                @php $i = 0; @endphp
                @foreach($banners as $banner)
                @php $i += 1; @endphp
                <h5>Banner No # {{$i}}</h5>
                <div class="py-2">
                    <div class="row py-2">
                        <div class="col-md-4 pt-md-0 pt-3">
                            <label for="lastname">Status</label>
                            <select class="form-select" onchange="update({{$banner->id}})" aria-label="Default select example">
                                <option @if($banner->status == "1") selected @endif value="1">Active</option>
                                <option @if($banner->status == "0") selected @endif value="0">Deactivate</option>
                              </select>
                        </div>
                        <div class="col-md-4">
                            <label for="email">Sequence</label>
                            <input type="text" class="form-control" value="{{$banner->sequence}}" placeholder="">
                        </div>
                        <div class="col-md-4">
                            <label for="email">Platform</label>
                            <select class="form-select" aria-label="Default select example">
                                <option @if($banner->platform == "web") selected @endif value="web">Web</option>
                                <option @if($banner->platform == "mobile") selected @endif value="mobile">Mobile</option>
                              </select>
                        </div>
                    </div>
                    @if($banner->img!=null)
                    <label for="email">Banner Image</label>
                    <div class="py-2">
                        <img class="w-100" src="{{$banner->img}}" alt="">
                    </div>
                    @else
                    <div class="py-3 pb-4">
                    <label for="email">iframe html code</label>
                    <textarea class="form-control">{{$banner->html}}</textarea>
                    </div>
                    <div class="py-3 pb-4">
                    <label for="email">iframe looks</label>
                    <div class="i___frame" id="iframe_{{$banner->id}}">
                    </div>
                    </div>
                    @endif
                    <div class="py-3 pb-4 text-center">
                        <a href="/delete/banner/{{$banner->id}}"><button class="btn btn-danger ">Delete</button></a>

                    </div>
                </div>
                <h4 class="pb-4 border-bottom"></h4>
                @endforeach
            </div>
        </div>
        </div>
      </div>
    </div>

@endsection
