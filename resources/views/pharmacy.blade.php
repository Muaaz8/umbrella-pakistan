@extends('layouts.frontend')

@section('content')
    <link rel="stylesheet" href="{{ asset('asset_admin/css/pharmacy-modal.css') }}">

    <div class="row" id="content" style="min-height:600px;">

        @include('layouts.category_sidebar')

        <!-- Page Content -->
        <div class="tab-content col-md-10 col-sm-12">

            <div class="container search-location">
                <div class="row">
                    <div class="col-12 mt-4 mb-3">
                        <h2>Umbrella Health Care Systems - Medicines</h2>
                        <p>Our pharmacy offers prescription drugs at discounted prices.</p>
                    </div>
                </div>
            </div>

            @include('layouts.search_locator')

            <section id="doctors-3" class="wide-60 doctors-section division">
                <div class="container">
                    <div class="row mb-3">
                        @if (count($data['products']) == 0)
                            <div class="alert alert-info" role="alert">
                                Sorry !! Products Not Found.
                            </div>
                        @else
                            @foreach ($data['products'] as $item)
                                @if ($item->medicine_type == 'prescribed')
                                    <div class="col-md-3 col-sm-6 product-box">
                                        <div class="ui cards">
                                            <div class="card card-hover-effect">
                                                <div class="content">
                                                    <div class="header">
                                                        <div class="reduce-price" style="background:#e52121 !important">
                                                            <span>Prescription <br> Required</span>
                                                        </div>
                                                        <h4 class="textOneLine m-0" style="width: 75%;font-size: 1.3rem;">
                                                            {{ $item->name }}</h4>
                                                        <small
                                                            style=" width: 82%; display: block;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><a
                                                                class="grey-color"
                                                                href="/pharmacy/{{ $item->sub_category_slug }}">{{ $item->sub_category_name }}</a></small>
                                                    </div>
                                                    <div class="description">
                                                        {{-- <h4 class="textOneLine">
                                                            {{ strip_tags($item->description) }}</h4> --}}
                                                        @if (strip_tags($item->description) != '-')
                                                            <h4 class="textOneLine2">{!! strip_tags($item->description) !!}</h4>
                                                        @else
                                                            <p class="textOneLine2"><i>Description not found</i></p>
                                                        @endif
                                                    </div>

                                                    <div class="row" style="padding: 5px;">
                                                        <div class="col-12" style="padding: 0px 5px 0px 0px;">
                                                            <a href="/product/pharmacy/{{ $item->slug }}"
                                                                class="ui bottom attached button btn-cart view-test-main moreViewBtn">Learn
                                                                More</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-3 col-sm-6 product-box">
                                        <div class="ui cards">
                                            <div class="card">
                                                <div class="content">
                                                    <div class="header">
                                                        <div class="product-img">
                                                            <img src="{{ url('/uploads/' . $item->featured_image) }}"
                                                                alt="test" class="lazz" width="160px">
                                                        </div>
                                                        <h4 class="textOneLine"
                                                            style="margin: 15px 0px 0px 0px; font-size: 1.3rem;">
                                                            {{ $item->name }}</h4>
                                                        <small><a class="grey-color"
                                                                href="/pharmacy/{{ $item->sub_category_slug }}">{{ $item->sub_category_name }}</a></small>
                                                    </div>
                                                    <div class="description">
                                                        <h4>${{ number_format($item->sale_price, 2) }}
                                                            {{-- <span>${{ number_format($item->regular_price,2) }}</span> --}}
                                                        </h4>
                                                    </div>

                                                    <div class="row" style="padding: 5px;">
                                                        <div class="col-6" style="padding: 0px 5px 0px 0px;">
                                                            <a href="/product/pharmacy/{{ $item->slug }}"
                                                                class="ui bottom attached button btn-cart view-test-main moreViewBtn">Learn
                                                                More</a>
                                                        </div>

                                                        <div class="col-6" style="padding: 0px;">
                                                            @if (Auth::check())
                                                                @if ($item->quantity > 0)
                                                                    <div class="addToCartBtn ui bottom attached button btn-cart view2-cart-btn{{ $item->id }}"
                                                                        onclick="add_to_cart(event, {{ $item->id }}, '{{ $item->tbl_name }}')"
                                                                        style="background:#08295a !important;color:white">
                                                                        Add To Cart
                                                                    </div>
                                                                @else
                                                                    <div class="addToCartBtn ui bottom attached button btn-cart"
                                                                        style="background:#08295a !important;color:white">
                                                                        Out of Stock
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <div class="addToCartBtn btnDialogueLogin ui bottom attached button btn-cart"
                                                                    style="background:#08295a !important;color:white">
                                                                    Add To Cart
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="row d-flex justify-content-center">
                    <div class="paginateCounter">
                        {{ $data['products']->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
