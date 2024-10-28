@extends('layouts.frontend')

@section('content')
<div class="row" id="content" style="min-height:600px;">
  <!-- Sidebar -->
  @include('layouts.lab_tests_sidebar')
  <!-- Page Content -->
  <style>
    .lab-test-box p {
      text-align: justify;
      padding: 0px 15px;
      margin: 10px 0px 15px 0px;
      overflow: hidden;
      width: 100%;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      height: 80px;
      font-size: 14px;
    }

    .mostPopularTestRow {
      text-align: center;
    }

    .mostPopularTestRow p {
      color: #000;
      font-weight: 500;
      /* margin-top: 20px; */
    }

    .mostPopularTestRow img {
      display: block;
      margin-left: auto;
      margin-right: auto;
      margin: 0 auto;
      height: 80px;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .featured_test .header {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      width: 100%;
    }

    .featured_test .header a {
      font-size: 14px !important;
      color: #08295a !important;
    }

    .featured_test .ui.items {
      background: #fff;
      box-sizing: border-box;
      padding: 15px;
    }

    .featured_test .description {
      font-size: 15px !important;
      font-weight: 500;
    }
  </style>

  <section id="services-7" class="bg-lightgrey wide-70 servicess-section division col-md-9" style="padding-top: 2%">
    <div class="container">

      <div class="text-center mb-5">
        <h4 class="h4-sm" style="color:#08295a !important;background: white;padding: 10px 0px 15px 0px;">
          Featured Lab Tests</h4>
      </div>

      <div class="row featured_test mb-5">
        @forelse ($data['featured_test'] as $item)
        <div class="col-md-3 mb-4">
          <div class="ui items">
            <div class="item">
              <div class="ui tiny image">
                <img src="{{ url('/uploads/' . $item->featured_image) }}">
              </div>
              <div class="middle aligned content">
                <div class="header">
                  <a href="{{ url('product/lab-test/' . $item->slug) }}"> {{ $item->name }}</a>
                </div>
                <div class="description">
                  ${{ number_format($item->sale_price, 2) }}
                </div>
                <div class="extra" align="right">
                  <div style=" background: #08295a; " onclick="add_to_cart(event, {{ $item->id }}, 'lab-test')" class="ui right mini floated primary button btn-carts">
                    ADD TO CART
                    {{-- <i class="right cart icon"></i> --}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @empty
        <h2>Not Found</h2>
        @endforelse
      </div>

      {{-- <div class="text-center">
                    <h4 class="h4-sm " style="color:#08295a !important;background: white;padding: 10px 0px 15px 0px;">Most Popular Panels </h4>
                </div>

                <div class="row mt-5 mb-5 mostPopularTestRow">
                    <div class="col">
                        <a href="/labs/annual-check-up-panel">
                            <img src="http://softvrbox.com/ezpay/images/homepanel1.png"/>
                            <p>Annual Check-Up Panel</p>
                        </a>
                    </div>
                    <div class="col">
                        <a href="/labs/comprehensive-panel">
                            <img src="http://softvrbox.com/ezpay/images/homepanel2.png"/>
                            <p>Comprehensive Panel</p>
                        </a>
                    </div>
                    <div class="col">
                        <a href="/labs/paternity-informational-non-legal">
                            <img src="http://softvrbox.com/ezpay/images/homepanel3.png"/>
                            <p>Paternity Informational (Non-legal)</p>
                        </a>
                    </div>
                    <div class="col">
                        <a href="/labs/cholestrol-lipid-panel">
                            <img src="http://softvrbox.com/ezpay/images/homepanel4.png"/>
                            <p>Cholestrol (Lipid) Panel</p>
                        </a>
                    </div>
                    <div class="col">
                        <a href="/labs/drug-screening-panel">
                            <img src="http://softvrbox.com/ezpay/images/homepanel5.png"/>
                        <p>Drug Screening Panel</p>
                        </a>
                    </div>
                    <div class="col">
                        <a href="/labs/food-allergy-panel">
                            <img src="http://softvrbox.com/ezpay/images/homepanel6.png"/>
                        <p>Food Allergy Panel</p>
                        </a>
                    </div>
                    <div class="col">
                        <a href="/labs/measles-mumps-rubella">
                            <img src="http://softvrbox.com/ezpay/images/homepanel7.png"/>
                        <p>Measles, Mumps, Rubella</p>
                        </a>
                    </div>
                    <div class="col">
                       <a href="/labs/men-s-health-panel">
                        <img src="http://softvrbox.com/ezpay/images/homepanel8.png"/>
                        <p>Men's Health Panel</p>
                       </a>
                    </div>
                    <div class="col">
                        <a href="/labs/women-s-health-panel">
                            <img src="http://softvrbox.com/ezpay/images/homepanel9.png"/>
                        <p>Women's Health Panel</p>
                        </a>
                    </div>
                    <div class="col">
                        <a href="/labs/hepatitis-b">
                            <img src="http://softvrbox.com/ezpay/images/homepanel10.png"/>
                            <p>Hepatitis B</p>
                        </a>
                    </div>
                </div> --}}

      <div class="text-center  mb-5">
        <h4 class="h4-sm " style="color:#08295a !important;background: white;padding: 10px 0px 15px 0px;">Most
          Popular Lab Tests</h4>
      </div>

      <div class="row mb-5">
        <div class="col-md-12">
          <div id="alpha-panel-toggle">
            <div class="search-alpha">
              <?php $alphabet = range('A', 'Z'); ?>
              <ul id="divLabTestAlphaPaging">
                <ul>
                  <li>
                    <a href="javaScript:void(0);" class="getLabTestAlphabetPaging" data-value="0">All</a>
                  </li>
                  @foreach ($alphabet as $item)
                  <li class="">
                    <a href="javaScript:void(0);" class="getLabTestAlphabetPaging" data-value="{{ $item }}">{{ $item }}</a>
                  </li>
                  @endforeach
                </ul>
              </ul>
            </div>
          </div>
        </div>
      </div>




      <?php $lab_listing = $data['labs-test']; ?>

      @if (count($lab_listing) === 0)
      <div class="row">
        <div style="display:block;" class="col-md-12 noTestFound">
          <h2 class="text-center">Sorry No Lab Test Found.</h2>
        </div>
      </div>
      <div class="row mt-30 myLabTestContainer"></div>
      @else

      <div class="row">
        <div style="display:none;" class="col-md-12 labPageLoder">
          <div class="ui active centered inline large loader"></div>
        </div>
      </div>
      <div class="row">
        <div style="display:none;" class="col-md-12 noTestFound">
          <h2 class="text-center">Sorry No Lab Test Found.</h2>
        </div>
      </div>

      <div class="row mt-30 myLabTestContainer">
        @foreach ($lab_listing as $key => $item)
        <div class="col-12 col-lg-4 col-md-6 col-xl-3 animated fadeInRight">
          <div class="team-item thumbnail team-item-Custom">
            <a class="thumb-info team">
              <span class="thumb-info-title">
                <i class="fa fa-flask font-color-theme-red doctorprofile-icon" style="color:#08295a !important"></i>
                <span class="thumb-info-inner font-color-theme-red labtest-title-customsize" style="color:#08295a !important;font-size: 16px;position: relative;top: 10px;">{{ $item->name }}</span>
              </span>
              <span class="thumb-info-action" style="display: none;"><span class="thumb-info-action-left"><em>View</em>
                </span>
                <span class="thumb-info-action-right">
                  <em>Details
                  </em>
                </span>
              </span>
            </a>
            <span class="thumb-info-caption">

              <div class="lab-test-box">
                {{ $item->short_description }}
              </div>
              <div class="product-price">
                <h4 style="color:#08295a !important">
                  ${{ number_format($item->sale_price, 2) }}
                </h4>
              </div>
              <span class="thumb-info-social-icons text-center ">
                <a href="{{ url('product/lab-test/' . $item->slug) }}" class="ui bottom attached button btn-cart view-test-main">Learn More</a>
                <div class="ui bottom attached button btn-cart" onclick="add_to_cart(event, {{ $item->id }}, 'lab-test')" style="background-color:#08295a !important;color:#fff !important">
                  Add to cart
                </div>
              </span>
            </span>
          </div>
        </div>
        @endforeach
      </div>
      @endif

  </section>

</div>
@endsection