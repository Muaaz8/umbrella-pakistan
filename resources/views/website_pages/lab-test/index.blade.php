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
<meta name="viewport" content="width=device-width, initial-scale=1" />
@foreach($tags as $tag)
<meta name="{{$tag->name}}" content="{{$tag->content}}">
@endforeach
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
@if($title != null)
    <title>{{$title->content}} | Umbrella Health Care Systems</title>
@else
    <title>Lab Tests | Umbrella Health Care Systems</title>
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
    $(document).ready(function(){
      var array = $('#array').val();
      $('#banner1').html('');
      $.each (JSON.parse(array), function (key, arr) {
        if(arr.img == '' || arr.img == null)
        {
            $('#banner1').html(arr.html);
        }
        else
        {
            $('#banner1').html('<img style="border-radius: 35px; filter: drop-shadow(2px 4px 5px black);" class="ad_img" '
            +'src="'+arr.img+'" width="100%" height="100%" alt="Adds" loading="lazy">');
        }
      });
    });

</script>
<script src="{{ asset('assets/js/lab_test.js') }}"></script>
@endsection

@section('content')
<!-- ******* LABTEST STATRS ******** -->
<input id="array" type="hidden" value="{{$banners}}">
<section class="about-bg labtest-bg">
  <div class="container">
    <div class="row">
    <div class="back-arrow-about">
        {{--   <i class="fa-solid fa-circle-arrow-left go-back" onclick="window.location=document.referrer;"></i> --}}
        <h1 id="demo">Lab Tests {{$slug_name}}</h1>
        <nav aria-label="breadcrumb">
          <i class="fa-solid fa-arrow-left"></i>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Lab Tests</a></li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</section>
{{--
<section>
      <div class="container my-4" id="banner1">
        </div>
      </section>  --}}

<section>
  <div class="container">
    <div class="row my-2">
      <div class="py-3 scroll-head">
        @php
            $page = DB::table('pages')->where('url','/labtests')->first();
            $section = DB::table('section')->where('page_id',$page->id)->where('section_name','top-section')->where('sequence_no','1')->first();
            $top_content = DB::table('content')->where('section_id',$section->id)->first();
        @endphp
        @if ($top_content)
            {!! $top_content->content !!}
        @else
        <h2><strong>Umbrella Health Care System - Labtests</strong></h2><p>Umbrella Health Care Systems medical labs are state of the art lab services , we use several reference labs to bring you best price and precise lab work, you can feel free to order any Labtest you wish without any physician’s referral, all results are highly confidential and also no doctor visits required for any labtest.</p>
        @endif
      </div>
      @foreach ($data['sidebar'] as $key => $item)
      @if($item->product_parent_category == $slug_name)
      <div class="col-md-4 col-lg-3 col-6 mb-2 labsbutton lab-img-icon">
        <button class="grow_ellipse active" onclick="location.href='{{ route('slug.labtest',['slug'=>$item->slug]) }}'">
          <img src="{{ asset('assets/images/'.$item->thumbnail) }}" alt=""/>
          {{ $item->product_parent_category }}
        </button>
      </div>
      @else
      <div class="col-md-4 col-lg-3 col-6 mb-2 labsbutton lab-img-icon">
        <button class="grow_ellipse" onclick="location.href='{{ route('slug.labtest',['slug'=>$item->slug]) }}'">
          <img src="{{ asset('assets/images/'.$item->thumbnail) }}" alt=""/>
          {{ $item->product_parent_category }}
        </button>
      </div>
      @endif
      @endforeach



      <div class="col-md-12 col-12 col-lg-12 m-auto catalog-search-wrapper">
                <div class="row">
                      <div class="col-md-3 mb-2">
                      <div class="main">
                        <select id="lab_cat_id">
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
                            id="search_lab_text"
                            type="text"
                            class="form-control"
                            placeholder="Search what you are looking for"
                            aria-label="Recipient's username"
                            aria-describedby="basic-addon2"
                        />
                        <span class="input-group-text search_lab_btn" id="basic-addon2"
                          ><i class="fa-solid fa-magnifying-glass"></i
                        ></span>
                      </div>
                    </div>

                  </div>
                </div>
                <!-- <div class="row align-items-baseline m-auto"> -->
      <div class="py-3">
        <div class="text-hover text-center hover-text-new ">
        {{--All lab tests include $6 Physician's fee.
        <i class="fa-solid fa-circle-info"></i>--}}
        <div class="tooltip">$6 fee is collected on behalf of affiliated physicians oversight for lab testing, lab results may require physicians follow-up services, UmbrellaMD will collect this fee for each order and it&sbquo;s non-refundable.</div>
       </div>

      </div>
    <!-- </div> -->
    </div>
    <div class="row " id="load_lab_test_item">
        @foreach ($data['products'] as $key => $item)
            <div class="col-lg-3 col-md-6 col-sm-6 mt-lg-0 my-5 ">
                <div class="add-to-cart-card">
                <div class="card d-flex align-items-center justify-content-center">
                    <div class="ribon"> <span class="fa-solid fa-flask"></span> </div>
                    <div class="add-to-cart-card-head"><h4 class="h-1 pt-5" title="{{ $item->name }}">{{ $item->name }}</h4></div>
                    <span class="price"> <sup class="sup">$</sup> <span class="number">{{ number_format($item->sale_price, 2) }}</span> </span>
                    <p>{!! strip_tags($item->short_description) !!}</p>
                    <div class="add-cart-btn-div">
                        <a href="{{ route('single_product_view_labtest',['slug'=>$item->slug]) }}"><button class="btn btn-primary view-detail"> View Details  </button></a>
                        @if(Auth::check())
                            <button class="btn btn-primary" type="button" onclick="addToCart({{ $item->id }},'lab-test',1)">Add To Cart</button>
                        @else
                            <button class="btn btn-primary" type="button" onclick="openBeforeModal(this)">Add To Cart</button>
                        @endif
                    </div>
                </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center mt-5 mb-3 prescription-req-view-btn">
        {!! $data['products']->links() !!}
    </div>
  </div>
</section>



<!-- Modal -->
<div class="modal fade cart-modal" id="afterLogin" tabindex="-1" aria-labelledby="afterLoginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="afterLoginLabel">Item Added</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="custom-modal">
                    <div class="succes succes-animation icon-top"><i class="fa fa-check"></i></div>
                      <div class="content">
                          <p class="type py-2">Item Added</p>
                          <div class="modal-login-reg-btn"><a href="" data-bs-dismiss="modal" aria-label="Close"> Continue Shopping </a>
                          <a href="{{ url('/my/cart') }}"> Go to checkout </a>

                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade cart-modal" id="alreadyadded" tabindex="-1" aria-labelledby="alreadyaddedLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alreadyaddedLabel">Item Not Added</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="custom-modal">
                    <div class="succes succes-animation icon-top"><i class="fa fa-times"></i></div>
                    <div class="content">
                        <p class="type py-2">Item Is Already in Cart</p>
                        <div class="modal-login-reg-btn"><a href="" data-bs-dismiss="modal" aria-label="Close"> Continue Shopping </a>
                        <a href="{{ url('/my/cart') }}"> Go to checkout </a>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade cart-modal" id="beforeLogin" tabindex="-1" aria-labelledby="beforeLoginLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="beforeLoginLabel">Not Logged In</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="custom-modal">
            <div class="icon-top"><i class="fa fa-times"></i></div>
            <div class="content">
                <p class="type py-2">Please login to add into cart</p>
                <div class="modal-login-reg-btn">
                    <a href="{{ route('login') }}"> Login </a>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- ******* LABTEST ENDS ******** -->

@endsection
