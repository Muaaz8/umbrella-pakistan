<div class="tab-pane fade show" id="tab-1" role="tabpanel" aria-labelledby="tab1-list">
    <div class="container section-bg">
        <div class="row">
            <div class="col-md-12 pt-5">
                <h2 class="text-center mb-0">Umbrella Health Care Systems - Pharmacy</h2>
                {{-- <p class="text-center lead"><strong>Shop by Category</strong> --}}
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-5 mb-5">
                <div class="categoryBar text-center">
                    <div class="category_item">
                        <a href="/pharmacy/">
                            <button class="labsbutton">
                                <i class="fa fa-capsules" style="color:#08295a;font-size:3.4rem"></i>
                            </button>
                        </a>
                        <p class="text-center">
                            <a href="#" class="labs-service">All </a>
                        </p>
                    </div>
                    @foreach ($data['prescribed_medicines_category'] as $key => $item)
                        <div class="category_item">
                            <a href="/pharmacy/{{ $item->slug }}" class="labs-service">
                                <button class="labsbutton">
                                    <i class="fa fa-capsules" style="color:#08295a;font-size:3.4rem"></i>
                                </button>
                            </a>
                            <p class="text-center">
                                <a href="/pharmacy/{{ $item->slug }}" class="labs-service">{{ $item->title }}
                                </a>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <hr>

        <div class="row pt-5 pb-4">
            <div class="col-md-12 text-center">
                <h2 class="text-center">Our pharmacy offers prescription drugs at discounted
                    prices</h2>
            </div>
            <div class="col-md-8 mx-auto pt-5">
                @include('layouts.search_locator')
            </div>
        </div>

        <div class="row">
            @foreach ($data['medicines_products'] as $item)
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
                                            <img src="{{ url('/uploads/' . $item->featured_image) }}" alt="test"
                                                class="lazz" width="160px">
                                        </div>
                                        <h4 class="textOneLine" style="margin: 15px 0px 0px 0px; font-size: 1.3rem;">
                                            {{ $item->name }}</h4>
                                        <small><a class="grey-color"
                                                href="/pharmacy/{{ $item->sub_category_slug }}">{{ $item->sub_category_name }}</a></small>
                                    </div>
                                    <div class="description">
                                        <h4>Rs. {{ number_format($item->sale_price, 2) }}
                                            {{-- <span>Rs. {{ number_format($item->regular_price,2) }}</span> --}}
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
        </div>

        <div class="row pt-5 pb-5">
            <div class="col-md-12 text-center">
                <a href="/pharmacy" class="ui theme-color button py-3">View More</a>
            </div>
        </div>

    </div>
</div>
