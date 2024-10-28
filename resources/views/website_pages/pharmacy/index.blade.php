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
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@foreach($tags as $tag)
    <meta name="{{$tag->name}}" content="{{$tag->content}}">
    @endforeach
@endsection

@section('page_title')
@if($title != null)
    <title>{{$title->content}} | Umbrella Health Care Systems</title>
@else
    <title>Pharmacy | Umbrella Health Care Systems</title>
@endif
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
    <script type="text/javascript">
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let paramString = location.pathname.split('/')[2];
        if (!paramString) {
            document.getElementById("demo").innerHTML = "Pharmacy";
        } else if (paramString) {
            document.getElementById("demo").innerHTML = "Pharmacy - " + paramString;
        }
        $(document).ready(function() {
            var array = $('#array').val();
            $('#banner1').html('');
            $.each(JSON.parse(array), function(key, arr) {
                if (arr.img == '' || arr.img == null) {
                    $('#banner1').html(arr.html);
                } else {
                    $('#banner1').html(
                        '<img style="border-radius: 35px; filter: drop-shadow(2px 4px 5px black);" class="ad_img" ' +
                        'src="' + arr.img + '" width="100%" height="100%" alt="Adds" loading="lazy">');
                }
            });
        });
    </script>
    <script src="{{ asset('assets/js/pharmacy.js') }}"></script>
@endsection

@section('content')

    <!-- ******* PHARMACY STATRS ******** -->
    <input id="array" type="hidden" value="{{ $banners }}">
    <section class="about-bg pharmacy-bg">
        <div class="container">
            <div class="row">
                <!-- <a href="#" class="go-top">Go Top</a> -->
                <div class="back-arrow-about">
                    <h1 id="demo">Pharmacy</h1>
                    <nav aria-label="breadcrumb">
                        <i class="fa-solid fa-arrow-left"></i>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Pharmacy</a></li>

                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>


    {{--  <section>
      <div class="container my-4">
        <img style="border-radius: 35px; filter: drop-shadow(2px 4px 5px black);" class="ad_img" src="{{ asset('assets/images/ad_banner.jpg') }}" width="100%" height="100%" alt="adds" loading="lazy">
        </div>
      </section>  --}}

    <div class="container">
        <div class=" mt-2">
            <div class="catalog-heading">
                <i class="fa-solid fa-capsules"></i>
                <h3>Categories By Alphabets</h3>
            </div>
        </div>
        <div class="catalog-left py-2">
            <div class="dropdown1">
                <button class="all-btn-pharmacy">All</button>
                <div class="dropdown1-content">
                    <div class="header">
                        <h2>ALL</h2>
                    </div>
                    <div class="col-md-12 ">
                        <div class="row m-auto">
                            @foreach ($data['sidebar'] as $val)
                                <div class="col-md-4">
                                    <a href="{{ route('pharmacy.category', ['slug' => $val->slug]) }}">
                                        <i class="fa-solid fa-capsules"></i>{{ $val->title }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @php
                $alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z'];
                $len = count($alpha);
            @endphp
            @for ($i = 0; $i < $len; $i++)
                @php
                    $alphabit = $alpha[$i];
                @endphp
                <div class="dropdown1">
                    <button class="dropbtn">{{ $alphabit }}</button>
                    <div class="dropdown1-content">
                        <div class="header">
                            <h2>{{ $alphabit }}</h2>
                        </div>
                        <div class="col-md-12 ">
                            <div class="row m-auto">
                                @foreach ($data['sidebar'] as $val)
                                    @php
                                        $first_char = substr($val->title, 0, 1);
                                    @endphp
                                    @if ($first_char == $alphabit)
                                        <div class="col-md-4">
                                            <a href="{{ route('pharmacy.category', ['slug' => $val->slug]) }}">
                                                <i class="fa-solid fa-capsules"></i>{{ $val->title }}
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>


        <section>
            <div class="container">
                <div class="row my-2">
                    <div class=" align-items-start p-0">

                        <div>
                            <div class="col-12 m-auto py-3 text-center">
                                @php
                                    $page = DB::table('pages')->where('url','/pharmacy')->first();
                                    $section = DB::table('section')->where('page_id',$page->id)->where('section_name','top-section')->where('sequence_no','1')->first();
                                    $top_content = DB::table('content')->where('section_id',$section->id)->first();
                                @endphp
                                @if ($top_content)
                                    {!! $top_content->content !!}
                                @else
                                <h2><strong>Umbrella Health Care Systems - Medicines</strong></h2><p>Our pharmacy offers prescription drugs at discounted prices.</p>
                                @endif
                            </div>

                            <div class="col-md-12 col-12 col-lg-12 m-auto catalog-search-wrapper">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <div class="main">
                                            <select id="pharmacy_cat_name">
                                                <option value="all">All Categories</option>
                                                @foreach ($data['sidebar'] as $val)
                                                    <option value="{{ $val->id }}">{{ $val->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-9 mb-2">
                                        <div class="input-group pharmacy-search">
                                            <input id="pharmacySearchText" type="text" class="form-control"
                                                placeholder="Search what you are looking for"
                                                aria-label="Recipient's username" aria-describedby="basic-addon2" />
                                            <span class="input-group-text searchPharmacyProduct" id="basic-addon2"><i
                                                    class="fa-solid fa-magnifying-glass"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 m-auto">
                                <div class="row" id="loadSearchPharmacyItemByCategory">
                                    @foreach ($data['products'] as $item)
                                        <div class="col-md-4 col-lg-3 col-sm-6 col-11 resp-phar-col">
                                            <div class="required-cards">
                                                <div class="card_container prescription-req-div">
                                                    <div class="card" data-label="Prescription Required">
                                                        <div class="card-container prescription-req-content">
                                                            <div class="d-flex pt-3">
                                                                <i class="fa-solid fa-capsules"></i>
                                                                <div class="prescription-req-heading ">

                                                                    <h3 title="{{ $item->name }}">{{ $item->name }}
                                                                    </h3>
                                                                    <h6 title="{{ $item->sub_category_name }}">
                                                                        {{ $item->sub_category_name }}</h6>
                                                                </div>
                                                            </div>
                                                            <p>{!! strip_tags($item->description) !!}</p>
                                                        </div>
                                                        <div class="prescription-req-btn">
                                                            <a
                                                                href="{{ route('single_product_view_medicines', ['slug' => $item->slug]) }}"><button>Learn
                                                                    More</button></a>
                                                        </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
        <!-- ******* PHARMACY ENDS ******** -->

    @endsection
