@extends('layouts.frontend')


@section('content')

    <style>
        .search_breadcumb {
            background: #08295a12;
            box-sizing: border-box;
            padding: 10px 15px;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .search_breadcumb a {
            display: inline-block;
            vertical-align: middle;
            color: #08295a;
            padding: 0 4px;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 500;
        }

        .search_breadcumb li {
            display: inline-block;
            vertical-align: middle;
            color: #000 !important;
            padding: 0 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search_result .btn-carts {
            background: #08295a !important;
            font-size: 12px;
        }

        .search_result .description p span,
        .search_result .description p {
            font-size: 14px !important;
            background-color: transparent !important;
            text-align: left;
            line-height: 25px;
        }

        .search_result .meta span {
            margin: 12px 0px 12px 0px;
            display: block;
            color: red;
            line-height: 20px;
            font-size: 18px;
            font-weight: 500;
        }

        .search_result .description {
            padding-right: 20%;
        }

        .searchHeader {
            background: #f7f7f7;
            padding: 10px;
        }

        .searchHeader h3 {
            color: #666;
        }

        .search_result .item:hover {
            box-shadow: 0 1px 6px rgb(0 0 0 / 30%) !important;
            -webkit-box-shadow: 0 1px 6px rgb(0 0 0 / 30%) !important;
        }

        .search_result .image img {
            width: 75% !important;
            margin: 0 auto;
        }

        .search_result .item {
            position: relative;
            padding: 1em 1em !important;
        }

        .search_result .description .tag {
            position: absolute;
            top: 8%;
            right: 2%;
            font-size: 10px;
        }

        .sidebarContainer .widget-1,
        .sidebarContainer .widget-2 {
            border: 1px solid #eaeaea;
            box-shadow: none !important;
        }

        .widget-1 a {
            color: #08295a;
            font-weight: 500;
            font-size: 14px;
        }

        .top_rated_products .header {
            font-size: 13px !important;
            line-height: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            overflow: hidden;
            width: 90%;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: bottom;
        }

        .top_rated_products .description p {
            color: red;
            font-weight: bold;
        }

        .top_rated_products .item {
            margin: 0px !important;
            border-bottom: 1px solid #f7f7f7 !important;
            box-sizing: border-box;
            padding: 10px !important;
        }

        .top_rated_products .image img {
            width: 80% !important;
            margin: 0 auto;
        }

    </style>

    <?php
    // print_r($data);
    ?>
    <div class="container mb-5" id="SearchContent">

        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb" class="search_breadcumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Search Result</li>
                    </ol>
                </nav>

            </div>
        </div>

        <div class="row">
            <div class="col-md-3 sidebarContainer">

                <div class="card widget-1 mb-4">
                    <article class="card-group-item">
                        <header class="card-header">
                            <h6 class="title">Sort By Type</h6>
                        </header>
                        <div class="filter-content">
                            <div class="list-group list-group-flush">
                                <a href="/search?mode=medicine&q={{ $data['keyword'] }}"
                                    class="list-group-item">Pharmacy</a>
                                <a href="/search?mode=lab-test&q={{ $data['keyword'] }}" class="list-group-item">Lab
                                    Tests</a>
                                <a href="/search?mode=imaging&q={{ $data['keyword'] }}" class="list-group-item">Imaging </a>
                                <a href="/search?q={{ $data['keyword'] }}" class="list-group-item">View All </a>
                            </div> <!-- list-group .// -->
                        </div>
                    </article> <!-- card-group-item.// -->
                </div> <!-- card.// -->

                <div class="card widget-2 mb-4">
                    <article class="card-group-item">
                        <header class="card-header">
                            <h6 class="title">Top Rated Medicines</h6>
                        </header>
                        <div class="filter-content top_rated_products">
                            <div class="ui items">
                                @foreach ($data['top_rated_pharmacy'] as $item)
                                    <div class="item">
                                        <a class="ui tiny image" href="/products/pharmacy/{{ $item->slug }}">
                                            <img src="{{url('/uploads/'.$item->featured_image)}}">
                                        </a>
                                        <div class="middle aligned content">
                                            <a class="header" href="/products/pharmacy/{{ $item->slug }}">{{ $item->name }}</a>
                                            <div class="description">
                                                <p>${{ number_format($item->sale_price, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </article> <!-- card-group-item.// -->
                </div> <!-- card.// -->

                <div class="card widget-2">
                    <article class="card-group-item">
                        <header class="card-header">
                            <h6 class="title">Top Rated Lab Tests</h6>
                        </header>
                        <div class="filter-content top_rated_products">
                            <div class="ui items">
                                @foreach ($data['top_rated_labs'] as $item)
                                    <div class="item">
                                        <a class="ui tiny image" href="/products/lab-test/{{ $item->slug }}">
                                            <img src="{{url('/uploads/'.$item->featured_image)}}">
                                        </a>
                                        <div class="middle aligned content">
                                            <a class="header" href="/products/lab-test/{{ $item->slug }}">{{ $item->name }}</a>
                                            <div class="description">
                                                <p>${{ number_format($item->sale_price, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </article> <!-- card-group-item.// -->
                </div> <!-- card.// -->


            </div>


            <div class="col-md-9">

                <div class="ui divided items search_result">

                    @if (empty($data['data']))
                        <h2>Sorry. No Result Found.</h2>
                    @else

                        <div class="searchHeader row">
                            <div class="col-6">
                                <h3 align="left">{{ count($data['data']) }} items found for "{{ $data['keyword'] }}"</h3>
                            </div>
                            <div class="col-6">
                                <h3 align="right">Category
                                    "{{ $data['category'] === 'medicine' ? 'Pharmacy' : ($data['category'] === 'lab-test' ? 'Lab Tests' : ($data['category'] === 'imaging' ? 'Imaging' : 'All')) }}"
                                </h3>
                            </div>
                        </div>
                        @foreach ($data['data'] as $item)

                            @php
                            $url = "";
                            $tag = "";
                            if($item->mode === 'medicine'){
                            $url = '/product/pharmacy/'.$item->slug;
                            $tag = '<a class="ui small red tag label">Pharmacy</a>';
                            }else{
                            $url = '/product/lab-test/'.$item->slug;
                            $tag = '<a class="ui small purple tag label">Lab Test</a>';
                            }
                            @endphp

                            <div class="item">
                                <div class="image">
                                    <img src="{{url('/uploads/'.$item->featured_image)}}">
                                </div>
                                <div class="content middle aligned">
                                    <a href="{{ $url }}" class="header">{{ $item->name }}</a>
                                    <div class="meta">
                                        <span class="cinema">${{ number_format($item->sale_price, 2) }}</span>
                                    </div>
                                    <div class="description">

                                        {!! $tag !!}
                                        {!! $item->short_description !!}
                                    </div>
                                    <div class="extra">
                                        <div onclick="add_to_cart(event, {{ $item->id }}, '{{ $item->tbl_name }}')"
                                            class="ui right floated primary button btn-carts">
                                            ADD TO CART
                                            <i class="right cart icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    @endif


                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-sm-12">
            <div class="paginateCounter link-paginate">
                {{ $item->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div> --}}
    </div>
@endsection
