<style>
    .single-pharmacy-product-page .product-price h4 {
        color: #08295a;
    }

    .single-pharmacy-product-page p,
    .single-pharmacy-product-page a,
    .single-pharmacy-product-page li {
        font-weight: 500;
    }

    .single-pharmacy-product-page .cartSingleProduct button {
        background: #08295a;
        border: 1px solid;
        font-size: 15px;
    }

    .single-pharmacy-product-page .nav-item {
        font-size: 16px;
        color: #08295a;
        font-weight: 500;
    }

    .single-pharmacy-product-page .nav-tabs .nav-item.show .nav-link,
    .single-pharmacy-product-page .nav-tabs .nav-link.active {
        color: #08295a;
        font-weight: 700;
        font-size: 16px;
    }

    .single-pharmacy-product-page .ui.green {
        background: #017d22;

    }

    .single-pharmacy-product-page .ui.green,
    .single-pharmacy-product-page .ui.red {
        font-size: 0.9rem;
    }


    .single-pharmacy-product-page .btn-cart,
    .single-pharmacy-product-page .reduce-price {
        background: #08295a !important;
        color: #fff;
    }

    div#nav-tab .nav-item {
        padding: 1rem !important;
    }

    .single-pharmacy-product-page .view-test-main {
        background: #e0e1e2 !important;
        color: rgba(0, 0, 0, .6);
    }
</style>

<div class="container mb-4 single-pharmacy-product-page">
    <div class="row">
        <header class="page-header position-relative">
            <h1 align="center" class="page-title">{{ $item->name }}</h1>
            <div class="bredcumbs">
                <nav aria-label="breadcrumb d-flex justify-content-between">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/pharmacy">Pharmacy</a></li>
                        <li class="breadcrumb-item"><a
                                href="/pharmacy/{{ $item->sub_category_slug }}">{{ $item->sub_category_name }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="goBackDesktop">
                <a href="{{ URL::previous() }}" class="text-white">Go Back</a>
            </div>
        </header>
    </div>

    {{-- Product Page --}}
    <div class="row">
        @if (count($product) == 0)
            <div class="alert alert-info" role="alert">
                Sorry ! Products not found.
            </div>
        @else
            @if ($item->medicine_type != 'prescribed')
                <div class="col-md-6 product-left">
                    <div class="single-product-image">
                        <img src="{{ url('/uploads/' . $item->featured_image) }}" class="product-img img-fluid"
                            loading="lazy" alt='{{ $item->name }}' />
                    </div>
                </div>

                <div class="col-md-6 product-right">
                    <div class="product-name" style=" margin-bottom: 20px; ">
                        @if ($item->quantity <= 0)
                            <button class="mini ui red button">
                                Out Of Stock
                            </button>
                        @endif
                    </div>
                    <div class="product-price">
                        <h4>Rs. {{ number_format($item->sale_price, 2) }}
                            {{-- <span>Rs. {{ $item->regular_price }}</span> --}}
                        </h4>
                    </div>
                    <div class="product-short-desc">
                        <p>{!! $item->short_description !!}</p>
                    </div>
                    <div class="product-add-to-cart-box">
                        @if (Auth::check())
                            <form class="cartSingleProduct" method="get" enctype="multipart/form-data">
                                {{-- @csrf --}}
                                <div class="quantity">
                                    <input type="number" id="quantity" name="quantity" class="input-text qty text"
                                        step="1" min="1" max="{{ $item->quantity }}" value="1"
                                        title="Qty" size="4" placeholder="" inputmode="numeric">
                                    <input type="hidden" id="product_id" name="product_id" value="{{ $item->id }}">
                                    {{-- <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}"> --}}
                                </div>

                                @if ($item->quantity > 0)
                                    <button type="button"
                                        onclick="add_to_cart(event, {{ $item->id }}, '{{ $item->tbl_name }}')"
                                        class="btn btn-blue blue-hover submit">Add To Cart</button>
                                @else
                                    <button type="button" class="btn btn-blue blue-hover submit disabled">Out of
                                        Stock</button>
                                @endif
                            </form>
                        @else
                            <button type="button" class="btn btn-blue btnDialogueLogin blue-hover submit">Add To
                                Cart</button>
                        @endif
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <h3 class="text-center text-danger">Prescription Required for this Medicine</h3>
                </div>
            @endif
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="ui top attached tabular menu">
                <a class="active item" data-tab="first">Detail Description</a>
                {{-- <a class="item" data-tab="second">More Information</a> --}}
            </div>
            <div class="ui bottom attached active tab segment" data-tab="first">
                {!! $item->description !!}
            </div>
            <div class="ui bottom attached tab segment" data-tab="second">
                {!! $item->short_description !!}
            </div>
        </div>
    </div>

    <div class="row d-none">
        <header class="page-header">
            <h1 align="left" class="page-title">Related Products</h1>
        </header>
    </div>
    <div class="row d-none">
        <div class="col-md-12">
            <div class="owl-carousel owl-theme related_products services-holder">
                <?php $aa = [0, 0, 1, 1, 1, 1]; ?>
                @foreach ($aa as $item)
                    <div class="product-box">
                        <div class="ui cards">
                            <div class="card">
                                <div class="content">
                                    <div class="header">
                                        <div class="reduce-price">
                                            <span>10% <br>
                                                <strong>Off</strong></span>
                                        </div>
                                        <div class="product-img">
                                            <img src="https://demo1.leotheme.com/bos_medicor_demo/24-large_default/hummingbird-printed-t-shirt.jpg"
                                                alt="test" class="lazz" width="162px">
                                        </div>
                                        <h3>ParacetamolGoody's Extra Strength Headache Pow</h3>
                                    </div>
                                    <div class="description">
                                        <h4>$203 <span>$225</span></h4>
                                    </div>
                                    <a href="#" class="ui bottom attached button btn-cart view-test-main">Learn
                                        More</a>
                                    <div class="ui bottom attached button btn-cart">
                                        Add to cart
                                    </div>
                                </div>
                                <a href="#" class="Clickable-Card-Anchor"></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- Product Page --}}
    @endif

</div>
