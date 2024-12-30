@extends('layouts.new_web_layout')

@section('meta_tags')
<meta charset="utf-8" />
<meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="language" content="en-us">
<meta name="robots" content="index,follow" />
<meta name="copyright" content="© 2022 All Rights Reserved. Powered By UmbrellaMd">
<meta name="url" content="https://www.umbrellamd.com">
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://www.umbrellamd.com" />
<meta property="og:site_name" content="Umbrella Health Care Systems | Umbrellamd.com" />
<meta name="twitter:site" content="@umbrellamd	">
<meta name="twitter:card" content="summary_large_image" />
<meta name="author" content="Umbrellamd">
@foreach($tags as $tag)
<meta name="{{$tag->name}}" content="{{$tag->content}}">
@endforeach
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
@if($title != null)
    <title>{{$title->content}} | Umbrella Health Care Systems</title>
@else
    <title>Imaging | Umbrella Health Care Systems</title>
@endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script type="text/javascript">
    <?php header("Access-Control-Allow-Origin: *"); ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // let paramString = location.pathname.split('/')[2];
    //     if(!paramString){
    //     document.getElementById("demo").innerHTML = "MEDICAL IMAGING";
    //    } else if (paramString){
    //     document.getElementById("demo").innerHTML = "IMAGING - " + paramString;
    //   }
</script>
<script src="{{ asset('assets/js/imaging.js') }}"></script>
@endsection

@section('content')

<!-- ******* MEDICAL-IMAGING STATRS ******** -->

<section class="about-bg">
        <div class="container">
          <div class="row">
          <div class="back-arrow-about">
         {{--   <i class="fa-solid fa-circle-arrow-left go-back" onclick="window.location=document.referrer;"></i> --}}
              <h1 id="demo">MEDICAL IMAGING {{ $slug_name }}</h1>
              <nav aria-label="breadcrumb">
          <i class="fa-solid fa-arrow-left"></i>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="#">Medical Imaging</a></li>

                </ol>
              </nav>
            </div>
          </div>
        </div>
      </section>

<section>
  <div class="container">
    <div class="row mt-2">
      <div class="py-3 scroll-head">
        @php
            $page = DB::table('pages')->where('url','/imaging')->first();
            $section = DB::table('section')->where('page_id',$page->id)->where('section_name','top-section')->where('sequence_no','1')->first();
            $top_content = DB::table('content')->where('section_id',$section->id)->first();
        @endphp
        @if ($top_content)
            {!! $top_content->content !!}
        @else
        <h2><strong>Umbrella Health Care Systems - Medical Imaging Services</strong></h2><p>Umbrella Health Care Systems provides imaging services as well, you can find different MRI, CT scan, Ultrasound, and X-Ray services here.</p>
        @endif
      </div>
      @foreach ($data['sidebar'] as $key => $item)
      @if($item->product_parent_category == $slug_name)
      <div class="col-lg-2 col-sm-4 col-6 mb-3 labsbutton">
        <button id="sdsd" class="grow_ellipse active" onclick="location.href='{{ route('slug.imaging',['slug'=>$item->slug]) }}'">
          <img src="{{ asset('assets/images/'.$item->thumbnail) }}" alt="" /> {{ $item->product_parent_category }}
        </button>
      </div>
      @else
      <div class="col-lg-2 col-sm-4 col-6 mb-3 labsbutton">
        <button id="sdsd" class="grow_ellipse" onclick="location.href='{{ route('slug.imaging',['slug'=>$item->slug]) }}'">
          <img src="{{ asset('assets/images/'.$item->thumbnail) }}" alt="" /> {{ $item->product_parent_category }}
        </button>
      </div>
      @endif
      @endforeach

    <div class="col-md-12 col-12 col-lg-12 m-auto catalog-search-wrapper">
        <div class="row">
            <div class="col-md-3 mb-2">
                <div class="main">
                    <select id="imaging_cat_id">
                        <option value="all">All Categories</option>
                        @foreach ($data['sidebar'] as $key => $item)
                        <option value="{{ $item->id }}">{{ $item->product_parent_category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-9 mb-2">
                <div class="input-group pharmacy-search">
                    <input
                    id="imaging_search_text"
                    type="text"
                    class="form-control"
                    placeholder="Search what you are looking for"
                    aria-label="Recipient's username"
                    aria-describedby="basic-addon2"
                    />
                    <span class="input-group-text imagingSearchBtn" id="basic-addon2"
                    ><i class="fa-solid fa-magnifying-glass"></i
                    ></span>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="row my-2" id="load_imaging_product_search">
        @forelse ($data['products'] as $item)
            <div class="col-md-4 mb-3">
                <div class="medical-imaging-card">
                <div class="card">
                    <div class="content">
                    <div class="d-flex justify-content-between">
                        <h5 title="{{ $item->name }}">{{ $item->name }}</h5>
                        <i class="fa-solid fa-circle-radiation"></i>
                    </div>
                    <p>
                        {{ strip_tags($item->short_description) }}
                    </p>
                    <div>
                    <button onclick="location.href='{{ route('single_product_view_imagings',['slug'=>$item->slug]) }}'">Learn More</button>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        @empty
            <div class="no-product-text d-flex justify-content-center align-items-center flex-column w-100">
                <img src="{{ asset('assets/images/exclamation.png') }}" alt="">
                <h1>NO ITEM Found</h1>
                <p>There are no item that match your current filters. Try removing some of them to get better results.</p>
            </div>
        @endforelse
        <div class="text-center mt-5 mb-3 prescription-req-view-btn">
            {!! $data['products']->links() !!}
        </div>
    </div>
  </div>
</section>
<!-- ******* MEDICAL-IMAGING ENDS ******** -->


@endsection
