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
                        <p>Our pharmacy store offers to buy cheap prescription drugs online at discounts and
                            low-priced generic and branded prescription drugs and non-prescription medicines.</p>
                    </div>
                </div>
            </div>


            @include('layouts.search_locator')

            <section id="doctors-3" class="wide-60 doctors-section division">
                <div class="container">
                    <div class="row">
                        @if (count($data['products']) == 0)
                            <div class="alert alert-info" role="alert">
                                Sorry !! Products Not Found.
                            </div>
                        @else
                            @foreach ($data['products'] as $item)
                                <div class="col-md-3 col-sm-6 product-box">
                                    <div class="ui cards">
                                        <div class="card">
                                            <div class="content">
                                                <div class="header">
                                                    @if ($item->medicine_type != 'counter')
                                                        <div class="reduce-price" style="background:#e52121 !important">
                                                            <span>Prescription <br> Required</span>
                                                        </div>
                                                    @endif

                                                    <div class="product-img">
                                                        <img src="{{ url('/uploads/' . $item->featured_image) }}"
                                                            alt="test" class="lazz" width="162px">
                                                    </div>
                                                    <h4 class="textOneLine" style="margin: 15px 0px 0px 0px;">
                                                        {{ $item->name }}</h4>
                                                    <small><a class="grey-color"
                                                            href="/pharmacy/{{ $item->sub_category_slug }}">{{ $item->sub_category_name }}</a></small>
                                                </div>
                                                <div class="description">
                                                    @if ($item->medicine_type == 'prescribed')
                                                        <h4 class="textOneLine">
                                                            {{ strip_tags($item->short_description) }}</h4>
                                                    @else
                                                        <h4>${{ number_format($item->sale_price, 2) }}
                                                            {{-- <span>${{ number_format($item->regular_price,2) }}</span> --}}
                                                        </h4>
                                                    @endif
                                                </div>

                                                <div class="row" style="padding: 5px;">
                                                    <div class="col-{{ $item->medicine_type != 'prescribed' ? '6' : '12' }}"
                                                        style="padding: 0px 5px 0px 0px;">
                                                        <a href="/product/pharmacy/{{ $item->slug }}"
                                                            class="ui bottom attached button btn-cart view-test-main moreViewBtn">View
                                                            More</a>
                                                    </div>
                                                    @if ($item->medicine_type != 'prescribed')
                                                        <div class="col-6" style="padding: 0px;">
                                                            @if (Auth::check())
                                                                @if ($item->quantity > 0)
                                                                    <div class="addToCartBtn ui bottom attached button btn-cart view2-cart-btn{{ $item->id }}"
                                                                        onclick="add_to_cart(event, {{ $item->id }}, 'medicine')"
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
                                                    @endif

                                                </div>
                                                {{-- @else
                                                        <div class="addToCartBtn ui bottom attached button btn-cart"
                                                            style="background:#08295a !important;color:white">
                                                            Go to E-Visit
                                                        </div>
                                                    @endif --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="row d-flex justify-content-center">
                    {{-- <div class="paginateCounter1"> --}}
                    {{ $data['products']->links('pagination::bootstrap-4') }}
                    {{-- </div> --}}
                </div>
            </section>

        </div>
    </div>
@endsection
