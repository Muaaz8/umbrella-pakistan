@extends('layouts.new_pakistan_layout')

@section('meta_tags')
@foreach ($meta_tags as $tags)
<meta name="{{ $tags->name }}" content="{{ $tags->content }}">
@endforeach
<meta charset="utf-8" />
<meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="language" content="en-us">
<meta name="robots" content="index,follow" />
<meta name="copyright" content="© 2022 All Rights Reserved. Powered By Community Healthcare Clinics">
<meta name="url" content="https://www.communityhealthcareclinics.com">
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://www.umbrellamd.com" />
<meta property="og:site_name" content="Community Healthcare Clinics | Umbrellamd.com" />
<meta name="twitter:site" content="@umbrellamd	">
<meta name="twitter:card" content="summary_large_image" />
<meta name="author" content="Umbrellamd">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
@if ($title != null)
<title>{{ $title->content }}</title>
@else
<title>Pharmacy</title>
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

    $('.searchPharmacyProduct').click(function(){
        var text=$('#pharmacySearchText').val();
        var cat_id=$('#pharmacy_cat_name').val();
        var vendor_id = "{{ $vendor->id }}";

        console.log('vendor_id', vendor_id)

        $.ajax({
            type: "POST",
            url: "/search_pharmacy_item_by_category/"+vendor_id,
            data: {
                text:text,
                cat_id:"all"
            },
            success: function(res)
            {
                AOS.refresh();
                $('.pagination').hide();
                $('.prescription-req-view-btn').hide();
                $('#loadSearchPharmacyItemByCategory').html('');
                if(res=="" || res==null)
                {
                    $('#loadSearchPharmacyItemByCategory').append(
                        '<div class="no-product-text d-flex justify-content-center align-items-center flex-column w-100">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );

                }
                else
                {
                    $.each(res, function(key, value) {
                        console.log(value);
                        $('#loadSearchPharmacyItemByCategory').append(
                            `<div class="col-md-4" data-aos="zoom-in" data-aos-delay="${key * 100}">
                                <div class="card rounded-4 border-0 bg-light-sky-blue">
                                    <div class="row overflow-hidden card-body px-4">
                                        <div
                                            class="col-5 px-0 h-100 bg-white rounded-4 d-flex align-items-center justify-content-center position-relative z-0">
                                            <img class="object-fit-contain w-100 h-100"
                                                src="${value.featured_image?value.featured_image:'assets/new_frontend/panadol2.png'}"
                                                alt="" />
                                            <a href="/medicines/${value.slug}/${value.vendor_id}"
                                                class="position-absolute read-btn-new btn bg-white fw-medium fs-12 py-1">Read
                                                More</a>
                                        </div>
                                        <div class="col-7 pe-0 d-flex flex-column justify-content-between">
                                            <h5 class="card-title text-blue fw-semibold align-self-end">
                                                Rs: ${value.sale_prices}
                                            </h5>
                                            <div>
                                                <h5 class="card-title fw-medium text-truncate" title="${value.name}">${value.name}</h5>
                                                <h6 class="card-subtitle fs-12 fw-medium">15ml</h6>
                                                <h5 class="card-title mt-2 fw-medium">${value.sub_category_name}</h5>
                                            </div>
                                            <a href="/medicines/${value.slug}/${value.vendor_id}"
                                                class="align-self-end fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                                <span class="fs-14">Add to Cart</span>
                                                <span
                                                    class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                                        class="fs-14 fa-solid fa-arrow-right"
                                                        style="transform: rotate(-45deg)"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                        );
                    });
                }
                if(text == ""){
                    $('.pagination').show();
                }
            }
        });
    });


    var input = document.getElementById("pharmacySearchText");
    input.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        var text=$('#pharmacySearchText').val();
        var vendor_id = "{{ $vendor->id }}";
        $.ajax({
            type: "POST",
            url: "/search_pharmacy_item_by_category/"+vendor_id,
            data: {
                text:text,
                cat_id:"all"
            },
            success: function(res)
            {
                $('.prescription-req-view-btn').hide();
                $('.pagination').hide();
                $('#loadSearchPharmacyItemByCategory').html('');
                if(res=="" || res==null)
                {
                    $('#loadSearchPharmacyItemByCategory').append(
                        '<div class="no-product-text d-flex justify-content-center align-items-center flex-column w-100 py-4">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );

                }
                else
                {
                    $.each(res, function(key, value) {
                        console.log(value);
                        $('#loadSearchPharmacyItemByCategory').append(
                            `<div class="col-md-4" data-aos="zoom-in" data-aos-delay="${key * 100}">
                                <div class="card rounded-4 border-0 bg-light-sky-blue">
                                    <div class="row overflow-hidden card-body px-4">
                                        <div
                                            class="col-5 px-0 h-100 bg-white rounded-4 d-flex align-items-center justify-content-center position-relative z-0">
                                            <img class="object-fit-contain w-100 h-100"
                                                src="${value.featured_image?value.featured_image:'assets/new_frontend/panadol2.png'}"
                                                alt="" />
                                            <a href="/medicines/${value.slug}/${value.vendor_id}"
                                                class="position-absolute read-btn-new btn bg-white fw-medium fs-12 py-1">Read
                                                More</a>
                                        </div>
                                        <div class="col-7 pe-0 d-flex flex-column justify-content-between">
                                            <h5 class="card-title text-blue fw-semibold align-self-end">
                                                Rs: ${value.sale_prices}
                                            </h5>
                                            <div>
                                                <h5 class="card-title fw-medium text-truncate" title="${value.name}">${value.name}</h5>
                                                <h6 class="card-subtitle fs-12 fw-medium">15ml</h6>
                                                <h5 class="card-title mt-2 fw-medium">${value.sub_category_name}</h5>
                                            </div>
                                            <a href="/medicines/${value.slug}/${value.vendor_id}"
                                                class="align-self-end fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                                <span class="fs-14">Add to Cart</span>
                                                <span
                                                    class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                                        class="fs-14 fa-solid fa-arrow-right"
                                                        style="transform: rotate(-45deg)"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                        );
                    });
                }
                if(text == ""){
                    $('.pagination').show();
                }
            }
        });

    }
    });
    function changed(){
        var svalue = $(".custom-select").val()
        var vendor_id = "{{ $vendor->id }}";

        const url = new URL(window.location);
        url.searchParams.set('sub_id', svalue); // set or update sub_id param
        window.history.pushState({}, '', url);

        $.ajax({
            type: "POST",
            url: "/search_pharmacy_item_by_sub_id/"+vendor_id,
            data: {
                sub_id:svalue,
            },
            beforeSend: function() {
                $('#loadSearchPharmacyItemByCategory').html(
                    '<div class="d-flex justify-content-center align-items-center w-100 h-100"><i class="fa fa-spinner fa-spin fa-4x" /></div>'
                );
            },
            success: function(res)
            {
                $('.prescription-req-view-btn').hide();
                $('#loadSearchPharmacyItemByCategory').html('');
                if(res.data=="" || res.data==null){
                    $('#loadSearchPharmacyItemByCategory').append(
                        '<div class="no-product-text d-flex justify-content-center align-items-center flex-column w-100 py-4">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
                else{
                    $.each(res.data, function(key, value) {
                        $('#loadSearchPharmacyItemByCategory').append(
                            `<div class="col-md-4" data-aos="zoom-in" data-aos-delay="${key * 100}">
                                <div class="card rounded-4 border-0 bg-light-sky-blue">
                                    <div class="row overflow-hidden card-body px-4">
                                        <div
                                            class="col-5 px-0 h-100 bg-white rounded-4 d-flex align-items-center justify-content-center position-relative z-0">
                                            <img class="object-fit-contain w-100 h-100"
                                                src="${value.featured_image?value.featured_image:'assets/new_frontend/panadol2.png'}"
                                                alt="" />
                                            <a href="/medicines/${value.slug}/${value.vendor_id}"
                                                class="position-absolute read-btn-new btn bg-white fw-medium fs-12 py-1">Read
                                                More</a>
                                        </div>
                                        <div class="col-7 pe-0 d-flex flex-column justify-content-between">
                                            <h5 class="card-title text-blue fw-semibold align-self-end">
                                                Rs: ${value.sale_prices}
                                            </h5>
                                            <div>
                                                <h5 class="card-title fw-medium text-truncate" title="${value.name}">${value.name}</h5>
                                                <h6 class="card-subtitle fs-12 fw-medium">15ml</h6>
                                                <h5 class="card-title mt-2 fw-medium">${value.sub_category_name}</h5>
                                            </div>
                                            <a href="/medicines/${value.slug}/${value.vendor_id}"
                                                class="align-self-end fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                                <span class="fs-14">Add to Cart</span>
                                                <span
                                                    class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                                        class="fs-14 fa-solid fa-arrow-right"
                                                        style="transform: rotate(-45deg)"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                        );
                    });
                }
                if(res.data.length > 0){
                    updatePagination(res);
                } else {
                    $('.pagination').hide();
                }
            }
        });
    }

    function updatePagination(res){
        var svalue = $(".custom-select").val()
        let paginationHTML = '';
        console.log(paginationHTML);
        if (res.links && res.links.length > 0) {
            paginationHTML += '<ul class="pagination justify-content-center">';

            $.each(res.links, function (index, link) {
                let url = link.url;
                if (url) {
                    const urlObj = new URL(url, window.location.origin);
                    urlObj.searchParams.set('sub_id', svalue);
                    url = urlObj.toString();
                }
                let label = link.label.replace('&laquo;', '«').replace('&raquo;', '»');

                paginationHTML += `<li class="page-item ${link.active ? 'active' : ''} ${link.url === null ? 'disabled' : ''}">
                    <a class="page-link" href="${url || '#'}">${label}</a>
                </li>`;
            });
            paginationHTML += '</ul>';
            $('.pagination').html(paginationHTML);
        }
    }

    // $(document).ready(function () {
    //     var sub_id = "{{ request()->get('sub_id') }}";
    //     var page = "{{ request()->get('page', 1) }}";
    //     if (sub_id && page == 1) {
    //         changed();
    //     }
    // });
</script>
@endsection

@section('content')
<main class="shops-page">
    <section class="new-header w-85 mx-auto rounded-3" data-aos="fade-down" data-aos-delay="100">
        <div class="new-header-inner py-4 px-4">
            <h1 class="fs-30 mb-0 fw-semibold">{{ $slug!=""?$slug:"Pharmacy" }}</h1>
            <div>
                <a class="fs-12" href="{{ url('/') }}">Home</a>
                <span class="mx-1 align-middle">></span>
                <a class="fs-12"
                    href="{{ route('pharmacy_products',
                    ['id' => $vendor->id,'sub_id'=>request()->query('sub_id', null)]) }}">{{$vendor->name}}</a>
            </div>
        </div>
    </section>
    <section class="page-para my-3 px-3 px-sm-5 w-85 mx-auto">
        <h2 class="fs-30 fw-semibold text-center mb-2" data-aos="fade-up" data-aos-delay="300">
            Community Healthcare Clinics - Medicines
        </h2>
        <p class="fs-14 text-center px-sm-2" data-aos="fade-up" data-aos-delay="500">
            Our pharmacy offers prescription drugs at discounted prices.
        </p>
    </section>
    <section class="medicine-card-section">
        <div class="container-fluid px-0">
            <div class="row gx-4 gy-2 mx-auto w-85 justify-content-between mb-3 align-items-center">
                <div class="col-md-5">
                    <div
                        class="search-container d-flex align-items-center justify-content-center rounded-3 position-relative" data-aos="fade-right" data-aos-delay="700">
                        <input class="search-bar px-3 py-2" type="search" name="search"
                            placeholder="Search for medicines" id="pharmacySearchText" />
                        <button class="px-3 py-2 search-icon searchPharmacyProduct"><i
                                class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>
                <div class="col-md-5">
                    @php
                    $sub_id = request()->get('sub_id');
                    @endphp
                    <select class="text-secondary form-select border-blue-2 py-2 rounded-3" id="category" data-aos="slide-left"
                        onchange="changed()" name="category">
                        <option value="all">All</option>
                        @foreach ($data['sidebar'] as $val)
                        <option value="{{ $val->id }}" @if ($sub_id==$val->id) selected @endif>{{ $val->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mx-auto px-0 w-85 gx-4 gy-3" data-aos="fade-up" data-aos-delay="200" id="loadSearchPharmacyItemByCategory">
                @foreach ($data['products'] as $item)
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="card rounded-4 border-0 bg-light-sky-blue">
                        <div class="row overflow-hidden card-body px-4">
                            <div
                                class="col-5 px-0 h-100 bg-white rounded-4 d-flex align-items-center justify-content-center position-relative z-0">
                                <img class="object-fit-contain w-100 h-100"
                                    src="{{ $item->featured_image?$item->featured_image:asset('assets/new_frontend/panadol2.png') }}"
                                    alt="" />
                                <a href="{{ route('single_product_view_medicines', ['slug' => $item->slug , 'vendor_id' => $item->vendor_id]) }}"
                                    class="position-absolute read-btn-new btn bg-white fw-medium fs-12 py-1">Read
                                    More</a>
                            </div>
                            <div class="col-7 pe-0 d-flex flex-column justify-content-between">
                                <h5 class="card-title fs-6 text-blue fw-semibold align-self-end">
                                    Rs: {{ $item->sale_prices - (($item->sale_prices*$item->discount)/100)}}
                                </h5>
                                <div>
                                    <h5 class="card-title fs-6 fw-medium text-overflow-2" title="{{ $item->name }}">{{
                                        $item->name }}</h5>
                                    {{--<h6 class="card-subtitle fs-12 fw-medium">15ml</h6>--}}
                                    <h6 class="card-subtitle fs-14 mt-2 fw-medium">{{ $item->sub_category_name }}</h6>
                                </div>
                                <a href="{{ route('single_product_view_medicines', ['slug' => $item->slug , 'vendor_id' => $item->vendor_id]) }}"
                                    class="align-self-end fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                    <span class="fs-14">Add to Cart</span>
                                    <span
                                        class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                            class="fs-14 fa-solid fa-arrow-right"
                                            style="transform: rotate(-45deg)"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pagination d-flex justify-content-center mt-3">{{
                $data['products']->links('pagination::bootstrap-4') }}</div>
        </div>
    </section>

    {{--<div class="container-fluid px-5 mt-3 pharmacy-page-container">
        @php
        $sub_id = request()->get('sub_id');
        @endphp
        <div class="p-4 background-secondary d-flex align-items-center justify-content-between flex-column rounded-4">
            <div class="d-flex align-items-center justify-content-between custom-search-container">
                <div class="category-dropdown">
                    <select class="form-select custom-select" name="category" id="category" onchange="changed()">
                        <option value="all">All</option>
                        @foreach ($data['sidebar'] as $val)
                        <option value="{{ $val->id }}" @if ($sub_id==$val->id) selected @endif>{{ $val->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="searchbar d-flex">
                    <input type="text" class="form-control custom-input" placeholder="Search for products"
                        id="pharmacySearchText">
                    <button class="btn custom-btn searchPharmacyProduct"><i class="fa-solid fa-search"></i></button>
                </div>
            </div>

            <div class="medicines-container w-100" id="loadSearchPharmacyItemByCategory">
                @foreach ($data['products'] as $item)
                <div class="card">
                    <div class="prescription">
                        <p style="background: {{ $item->is_otc==1?'green':'red'}}">{{$item->is_otc==1?'over the
                            counter':'prescription required'}}</p>
                    </div>
                    <div class="price">
                        <p>Rs: {{ $item->sale_prices - (($item->sale_prices*$item->discount)/100)}}</p>
                    </div>
                    <div class="med-img"><img
                            src="{{ $item->featured_image?$item->featured_image:asset('assets/new_frontend/panadol2.png') }}"
                            alt="img"></div>
                    <h4 title="{{ $item->name }}" class="truncate m-0 p-0">{{ $item->name }}</h4>
                    <h6 class="truncate m-0 p-0">{{ $item->sub_category_name }}</h6>
                    <div class="pharmacy_btn">
                        <a class="read-more btn btn-outline-danger"
                            href="{{ route('single_product_view_medicines', ['slug' => $item->slug , 'vendor_id' => $item->vendor_id]) }}">Read
                            More <i class="fa-solid fa-sheet-plastic mx-2 ms-xxl-2 me-xxl-0"></i></a>
                        <a class="add-to-cart"
                            href="{{ route('single_product_view_medicines', ['slug' => $item->slug , 'vendor_id' => $item->vendor_id]) }}">Add
                            to Cart <i class="fa-solid fa-cart-shopping mx-2 ms-xxl-2 me-xxl-0"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="pagination">{{ $data['products']->links('pagination::bootstrap-4') }}</div>
        </div>

    </div>--}}

</main>
@endsection
