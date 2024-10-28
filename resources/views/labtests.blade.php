@extends('layouts.frontend')
@section('css')
<style>
.lab-test-box h4 {
    text-align: justify;
    padding: 0px 15px;
    margin: 10px 0px 15px 0px;
    overflow: hidden;
    width: 100%;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    height: 94px;
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

.team-item-Custom .corner.label {
    right: 16px;
    border-color: #ecdf41;
    color: #f8f9fb;
}

.paginateCounter .page-item.active .page-link,
.paginateCounter .page-item .page-link:hover {
    background: #092a5b !important;
}

.features-details {
    padding: 0px 15px;
    margin: 10px 0px 15px 0px;
}

.swal-button {
    background-color: #092a5b;
}

.second-desc {
    color: red;
    font-weight: bold;
    font-style: italic;
}

.desc {
    font-size: 18px;
}

.lab-text {
    text-align: center;
}

.info-icon {
    border: solid black 1.5px;
    border-radius: 45px;
    padding: 3px;
    box-sizing: border-box;
    width: 23px;
    text-align: center;
}

.tooltip1 {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
    bottom: 39px;
    left: 811px;
    opacity: 1 !important;
}

.tooltiptext {
    opacity: 0;
}

.tooltip1 .tooltiptext {
    /* visibility: hidden; */
    width: 250px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;

    /* Position the tooltip */
    position: absolute;
    z-index: 1;
}

.tooltip1:hover .tooltiptext {
    visibility: visible;
    opacity: 1 !important;
}
</style>
@endsection
@section('content')
<section id="services-7" class="bg-lightgrey wide-70 servicess-section division col-md-12" style="padding-top: 2%">
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4 mb-3">
                <h2 class="text-center">Umbrella Health Care System - Labtests</h2>
                <p class="text-center desc">Umbrella Health Care Systems medical labs are state of the art lab services
                    , we use several reference labs to bring you best price and precise lab work, you can feel free to
                    order any Labtest you wish without any physician’s referral, all results are highly confidential and also no doctor visits required for any labtest.
                </p>
            </div>

            <div class="col-md-12">
                <div id="alpha-panel-toggle">
                    <div class="search-alpha">
                        <ul id="divLabTestAlphaPaging">
                            <div class="container">
                                <div class="row">
                                    {{-- <div>
                                        <a href="/labtests">
                                            <button class="labsbutton">
                                                <img src="/uploads/601a49b9caa65.png" />
                                            </button>
                                        </a>
                                        <p class="text-center">
                                            <a href="/labtests" class="labs-service">All </a>
                                        </p>
                                    </div> --}}
                                    @foreach ($data['sidebar'] as $key => $item)
                                    <div>
                                        <a href="/labtests/{{ $item->slug }}" class="labs-service">
                                            <button class="labsbutton">
                                                <img src="/uploads/{{ $item->thumbnail }}" />
                                            </button>
                                        </a>
                                        <p class="text-center">
                                            <a href="/labtests/{{ $item->slug }}"
                                                class="labs-service">{{ $item->product_parent_category }}
                                            </a>
                                        </p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="searchBoxPageMiddle w-50 mx-auto pt-5">
            @include('layouts.search_locator')
        </div>
        <div class="row d-flex justify-content-around cursor">
                <p class="text-center text-danger font-weight-bold pe-auto mb-5"
                title="$6 fee is collected on behalf of affiliated physicians oversight for lab testing, lab results may require physicians follow-up services, UmbrellaMD will collect this fee for each order and it's non-refundable."
                >All lab tests include $6 physician's fee
                <i class=" fa fa-info info-icon"
                title="$6 fee is collected on behalf of affiliated physicians oversight for lab testing, lab results may require physicians follow-up services, UmbrellaMD will collect this fee for each order and it's non-refundable."
                ></i></p>
        </div>
        <!-- <div class="col-md-12">
            <p class="lab-text"><span class="second-desc">All lab tests include $6 physician's fee
                </span>
            <div class="tooltip1"><i class=" fa fa-info info-icon"></i><span class="tooltiptext">$6 fee is
                    collected on behalf of affiliated physicians oversight for lab testing, lab results may require
                    physicians
                    follow-up services, UmbrellaMD will collect this fee for each order and it's
                    non-refundable.</span></div>
            </p>
        </div> -->

        <?php $lab_listing = $data['products']; ?>

        @if (count($lab_listing) === 0)
        <div class="row">
            <div style="display:block;" class="col-md-12 noTestFound">
                <h2 class="text-center">Sorry No Lab Test Found.</h2>
            </div>
        </div>
        @else
        <div class="row myLabTestContainer">
            @foreach ($lab_listing as $key => $item)
            <div class="col-12 col-lg-4 col-md-6 col-xl-3 animated fadeInRight">
                <div class="team-item thumbnail team-item-Custom">
                    <?php //echo $item->is_featured == 1 ? '<a class="ui right corner label"><i class="star icon"></i></a>' : '';
                                ?>
                    <a class="thumb-info team">
                        <span class="thumb-info-title">

                            <i class="fa fa-flask font-color-theme-red doctorprofile-icon"
                                style="color:#08295a !important"></i>
                            <span class="thumb-info-inner font-color-theme-red labtest-title-customsize"
                                style="color:#08295a !important;font-size: 16px;position: relative;top: 10px;">{{ $item->name }}</span>
                        </span>
                        <span class="thumb-info-action" style="display: none;"><span
                                class="thumb-info-action-left"><em>View</em>
                            </span>
                            <span class="thumb-info-action-right">
                                <em>Details
                                </em>
                            </span>
                        </span>
                    </a>
                    <span class="thumb-info-caption">

                        <div class="lab-test-box">
                            <h4 class="textOneLine2">
                                {!! strip_tags($item->short_description) !!}
                            </h4>
                            <!-- {!! $item->short_description !!} -->
                        </div>
                        <div class="product-price">
                            <h4 style="color:#08295a !important">
                                ${{ number_format($item->sale_price, 2) }}
                            </h4>
                        </div>
                        <span class="thumb-info-social-icons text-center ">
                            <a href="{{ url('product/labtests/' . $item->slug) }}"
                                class="ui bottom attached button btn-cart view-test-main">View
                                Details</a>
                            @if (Auth::check())
                            <!-- Add to Cart Code -->
                            <div class="ui bottom attached button btn-cart"
                                onclick="add_to_cart(event, {{ $item->id }}, '{{ $item->tbl_name }}')"
                                style="background-color:#08295a !important;color:#fff !important">
                                Add to cart
                            </div>
                            @else
                            <div class="btnDialogueLogin ui bottom attached button btn-cart"
                                style="background-color:#08295a !important;color:#fff !important">
                                Add To Cart
                            </div>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="paginateCounter link-paginate">
                    {{ $lab_listing->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
