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
<title>Labtest</title>
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
        var mode='lab-test';

        $('.searchPharmacyProduct').click(function() {
            var text = $('#pharmacySearchText').val();
            var cat_id = $('#pharmacy_cat_name').val();
            var vendor_id = "{{ $vendor->id }}";
            $.ajax({
                    type: "POST",
                    url: "/search_lab_item_by_category/"+vendor_id,
                    data: {
                        text: text,
                        cat_id: "all"
                    },
                    success: function(res) {
                        $('.pagination').hide();
                        $('.prescription-req-view-btn').hide();
                        $('#loadSearchPharmacyItemByCategory').html('');
                        console.log("res.products",res);
                        if (res == "" || res == null) {
                            $('#loadSearchPharmacyItemByCategory').append(
                                '<div class="no-product-text d-flex justify-content-center align-items-center flex-column w-100 py-4">' +
                                '<img src="/assets/images/exclamation.png" alt="">' +
                                '<h1>NO ITEM Found</h1>' +
                                '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>' +
                                '</div>'
                            );

                        } else {
                            if(res.user_id==''){
                                $.each(res.products.data, function(key, value) {
                                    $('#loadSearchPharmacyItemByCategory').append(
                                    `<div class="col-md-4">
                                        <div class="card rounded-4 border-0 bg-light-sky-blue">
                                            <div class="card-body ps-4 pe-3">
                                                <div class="pe-0 h-100 d-flex flex-column justify-content-between">
                                                    <h5 class="card-title text-blue fw-semibold align-self-end">
                                                        Rs. ${value.SALE_PRICE}
                                                    </h5>
                                                    <div>
                                                        <h5 class="card-title fs-6 fw-semibold mb-2" title="${value.TEST_NAME}">
                                                            ${value.TEST_NAME}
                                                        </h5>
                                                        <p class="card-text fs-13 text-overflow">
                                                            ${value.DETAILS}
                                                        </p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <button onclick="window.location.href='/labtest/${value.SLUG}/${value.vendor_id}'"
                                                            class="read-btn-new mt-2 btn bg-white fw-medium fs-12 py-1">Read More</button>
                                                        <button data-bs-toggle="modal" data-bs-target="#loginModal"
                                                            class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                                            <span class="fs-14">Add to Cart</span>
                                                            <span
                                                                class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                                                    class="fs-14 fa-solid fa-arrow-right"
                                                                    style="transform: rotate(-45deg)"></i></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`
                                    );
                                });
                            }else{
                                $.each(res.products.data, function(key, value) {
                                    $('#loadSearchPharmacyItemByCategory').append(
                                    `<div class="col-md-4">
                                        <div class="card rounded-4 border-0 bg-light-sky-blue">
                                            <div class="card-body ps-4 pe-3">
                                                <div class="pe-0 h-100 d-flex flex-column justify-content-between">
                                                    <h5 class="card-title text-blue fw-semibold align-self-end">
                                                        Rs. ${value.SALE_PRICE}
                                                    </h5>
                                                    <div>
                                                        <h5 class="card-title fs-6 fw-semibold mb-2" title="${value.TEST_NAME}">
                                                            ${value.TEST_NAME}
                                                        </h5>
                                                        <p class="card-text fs-13 text-overflow">
                                                            ${value.DETAILS}
                                                        </p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <button onclick="window.location.href='/labtest/${value.SLUG}/${value.vendor_id}'"
                                                            class="read-btn-new mt-2 btn bg-white fw-medium fs-12 py-1">Read More</button>
                                                        <button data-bs-toggle="modal" data-bs-target="#loginModal"
                                                            class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                                            <span class="fs-14">Add to Cart</span>
                                                            <span
                                                                class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                                                    class="fs-14 fa-solid fa-arrow-right"
                                                                    style="transform: rotate(-45deg)"></i></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`
                                    );
                                });
                            }
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
                var text = $('#pharmacySearchText').val();
                var vendor_id = "{{ $vendor->id }}";
                $.ajax({
                    type: "POST",
                    url: "/search_lab_item_by_category/"+vendor_id,
                    data: {
                        text: text,
                        cat_id: "all"
                    },
                    success: function(res) {
                        $('.pagination').hide();
                        $('.prescription-req-view-btn').hide();
                        $('#loadSearchPharmacyItemByCategory').html('');
                        if (res == "" || res == null) {
                            $('#loadSearchPharmacyItemByCategory').append(
                                '<div class="no-product-text d-flex justify-content-center align-items-center flex-column w-100 py-4">' +
                                '<img src="/assets/images/exclamation.png" alt="">' +
                                '<h1>NO ITEM Found</h1>' +
                                '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>' +
                                '</div>'
                            );

                        } else {
                            if(res.user_id==''){
                                $.each(res.products.data, function(key, value) {
                                    $('#loadSearchPharmacyItemByCategory').append(
                                    `<div class="col-md-4">
                                        <div class="card rounded-4 border-0 bg-light-sky-blue">
                                            <div class="card-body ps-4 pe-3">
                                                <div class="pe-0 h-100 d-flex flex-column justify-content-between">
                                                    <h5 class="card-title text-blue fw-semibold align-self-end">
                                                        Rs. ${value.SALE_PRICE}
                                                    </h5>
                                                    <div>
                                                        <h5 class="card-title fs-6 fw-semibold mb-2" title="${value.TEST_NAME}">
                                                            ${value.TEST_NAME}
                                                        </h5>
                                                        <p class="card-text fs-13 text-overflow">
                                                            ${value.DETAILS}
                                                        </p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <button onclick="window.location.href='/labtest/${value.SLUG}/${value.vendor_id}'"
                                                            class="read-btn-new mt-2 btn bg-white fw-medium fs-12 py-1">Read More</button>
                                                        <button data-bs-toggle="modal" data-bs-target="#loginModal"
                                                            class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                                            <span class="fs-14">Add to Cart</span>
                                                            <span
                                                                class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                                                    class="fs-14 fa-solid fa-arrow-right"
                                                                    style="transform: rotate(-45deg)"></i></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`
                                    );
                                });
                            }else{
                                $.each(res.products.data, function(key, value) {
                                    $('#loadSearchPharmacyItemByCategory').append(
                                    `<div class="col-md-4">
                                        <div class="card rounded-4 border-0 bg-light-sky-blue">
                                            <div class="card-body ps-4 pe-3">
                                                <div class="pe-0 h-100 d-flex flex-column justify-content-between">
                                                    <h5 class="card-title text-blue fw-semibold align-self-end">
                                                        Rs. ${value.SALE_PRICE}
                                                    </h5>
                                                    <div>
                                                        <h5 class="card-title fs-6 fw-semibold mb-2" title="${value.TEST_NAME}">
                                                            ${value.TEST_NAME}
                                                        </h5>
                                                        <p class="card-text fs-13 text-overflow">
                                                            ${value.DETAILS}
                                                        </p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <button onclick="window.location.href='/labtest/${value.SLUG}/${value.vendor_id}'"
                                                            class="read-btn-new mt-2 btn bg-white fw-medium fs-12 py-1">Read More</button>
                                                        <button data-bs-toggle="modal" data-bs-target="#loginModal"
                                                            class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                                            <span class="fs-14">Add to Cart</span>
                                                            <span
                                                                class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                                                    class="fs-14 fa-solid fa-arrow-right"
                                                                    style="transform: rotate(-45deg)"></i></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`
                                    );
                                });
                            }
                        }
                        if(text == ""){
                            $('.pagination').show();
                        }
                    }
                });
            }
        });

        function addedItem(a) {
            var all_classes = $(a).attr('class');
            var class_split = all_classes.split(' ');
            console.log("class_split",class_split);

            var pro_id = class_split[1];
            var pro_mode = class_split[2];
            var pro_qty = 1;
            console.log(class_split, pro_id, pro_mode, pro_qty);
            $.ajax({
                type: "POST",
                url: "/add_to_cart",
                data: {
                    pro_id: pro_id,
                    pro_mode: pro_mode,
                    pro_qty: pro_qty,
                },
                success: function(data) {
                    if (data.check == '1') {
                        $('#alreadyadded').modal('show');
                    } else {
                        console.log('item added into cart');
                    }
                },
            });
        }
</script>
@endsection

@section('content')
<main>
    <section class="new-header w-85 mx-auto rounded-3">
        <div class="new-header-inner p-5">
            <h1 class="fs-40 fw-semibold">{{ $vendor->name }}</h1>
            <div>
                <a class="fs-12" href="{{ url('/') }}">Home</a>
                <span class="mx-1 align-middle">></span>
                <a class="fs-12" href="{{ route('labs_products', ['id' =>
                                            $vendor->id]) }}">Labs</a>
            </div>
        </div>
    </section>
    <section class="page-para my-5 px-5 w-85 mx-auto">
        <h2 class="fs-30 fw-semibold text-center mb-4">
            Community Healthcare Clinics - Labtests
        </h2>
        <p class="fs-14 text-center px-2">
            Community Healthcare Clinics offers a wide range of lab tests and
            diagnostic services.
        </p>
    </section>
    <section class="lab-card-section">
        <div class="container-fluid px-0">
            <div class="row gx-4 gy-3 mx-auto w-85">
                <div class="col-12 bg-white d-flex justify-content-between mb-3 align-items-center">
                    <div class="col-md-5">
                        <div
                            class="search-container d-flex align-items-center justify-content-center rounded-3 position-relative">
                            <input class="search-bar px-3 py-2" type="search" name="search"
                                placeholder="Search for labs" id="pharmacySearchText" />
                            <button class="px-3 py-2 search-icon searchPharmacyProduct"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gx-4 gy-3 mx-auto w-85" id="loadSearchPharmacyItemByCategory">
                @foreach ($data['products'] as $item)
                <div class="col-md-4">
                    <div class="card rounded-4 border-0 bg-light-sky-blue">
                        <div class="card-body ps-4 pe-3">
                            <div class="pe-0 h-100 d-flex flex-column justify-content-between">
                                <h5 class="card-title text-blue fw-semibold align-self-end">
                                    Rs. {{ number_format($item->sale_price,2) }}
                                </h5>
                                <div>
                                    <h5 class="card-title fs-6 fw-semibold mb-2">
                                        {{$item->name}}
                                    </h5>
                                    <p class="card-text fs-13 text-overflow">
                                        {!! strip_tags($item->short_description) !!}
                                    </p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <button
                                        onclick="window.location.href='{{ route('single_product_view_labtest', ['slug' => $item->slug, 'vendor_id' => $item->vendor_id]) }}'"
                                        class="read-btn-new mt-2 btn bg-white fw-medium fs-12 py-1">Read More</button>
                                    @if (Auth::check())
                                    <button
                                        class="fw-semibold {{ $item->vendor_product_id }} d-flex align-items-center gap-1 add-to-cart-btn-new"
                                        onclick="addedItem(this)">
                                        <span class="fs-14">Add to Cart</span>
                                        <span
                                            class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                                class="fs-14 fa-solid fa-arrow-right"
                                                style="transform: rotate(-45deg)"></i></span>
                                    </button>
                                    @else
                                    <button data-bs-toggle="modal" data-bs-target="#loginModal"
                                        class="fw-semibold d-flex align-items-center gap-1 add-to-cart-btn-new">
                                        <span class="fs-14">Add to Cart</span>
                                        <span
                                            class="d-flex align-items-center justify-content-center text-center new-arrow-icon-2 bg-blue rounded-circle text-white border-white border-2"><i
                                                class="fs-14 fa-solid fa-arrow-right"
                                                style="transform: rotate(-45deg)"></i></span>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="pagination mt-3 d-flex align-items-center justify-content-center">{{
                    $data['products']->links('pagination::bootstrap-4') }}</div>
            </div>
        </div>
    </section>

    {{--<div class="container-fluid px-5 mt-3 pharmacy-page-container">
        <div class="p-4 background-secondary d-flex align-items-center justify-content-between flex-column rounded-4">
            <div class="d-flex align-items-center justify-content-between custom-search-container">
                <div class="category-dropdown">
                </div>
                <div class="searchbar d-flex">
                    <input type="text" class="form-control custom-input" placeholder="Search for products"
                        id="pharmacySearchText">
                    <button class="btn custom-btn searchPharmacyProduct"><i class="fa-solid fa-search"></i></button>
                </div>
            </div>

            <div class="tests-container w-100" id="loadSearchPharmacyItemByCategory">
                @foreach ($data['products'] as $item)
                <div class="tests-card">
                    <div class="test-card-content">
                        <div class="add_to_cart_container">
                            <button class="add_to_cart_btn"
                                onclick="window.location.href='{{ route('single_product_view_labtest', ['slug' => $item->slug, 'vendor_id' => $item->vendor_id]) }}'">
                                Learn More
                            </button>
                        </div>
                        @if($item->discount_percentage != null && $item->discount_percentage != 0)
                        <span class="discount-no">{{ $item->discount_percentage }}% Off</span>
                        @endif
                        <h4 class="truncate">{{ $item->name }}</h4>
                        <p class="truncate-overflow">{!! strip_tags($item->short_description) !!}</p>
                        <div class="test-card-price d-flex flex-column gap-2 align-items-center">
                            <span class="discounted-price">Rs. {{ number_format($item->sale_price,2) }}</span>
                            @if($item->discount_percentage != null && $item->discount_percentage != 0)
                            <span class="actual-price">Rs. {{ number_format($item->actual_price,2) }}</span>
                            @endif
                        </div>
                        @if (Auth::check())
                        <button class="learn_btn {{ $item->vendor_product_id }} lab-test" onclick="addedItem(this)">Add
                            To Cart
                            <i class="fa-solid fa-cart-shopping mx-2"></i>
                        </button>
                        @else
                        <button class="learn_btn" data-bs-toggle="modal" data-bs-target="#loginModal">Add To Cart
                            <i class="fa-solid fa-cart-shopping mx-2"></i>
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="pagination">{{ $data['products']->links('pagination::bootstrap-4') }}</div>
        </div>

    </div>--}}
</main>
<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Registration Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-login-reg-btn my-3">
                    <a href="{{ route('pat_register') }}"> REGISTER AS A PATIENT</a>
                    <a href="{{ route('doc_register') }}">REGISTER AS A DOCTOR </a>
                </div>
                <div class="login-or-sec">
                    <hr />
                    OR
                    <hr />
                </div>
                <div>
                    <p>Already have account?</p>
                    <a href="{{ route('login') }}">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ******* LOGIN-REGISTER-MODAL ENDS ******** -->
<!-- ******* LOGIN-REGISTER-MODAL ENDS ******** -->
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
                    <div class="content flex-column align-items-center justify-content-center w-100 gap-1">
                        <p class="type">Item Added</p>
                        <div class="modal-login-reg-btn"><button data-bs-dismiss="modal" aria-label="Close">
                                Continue Shopping
                            </button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade cart-modal" id="alreadyadded" tabindex="-1" aria-labelledby="alreadyaddedLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alreadyaddedLabel">Item Not Added</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="custom-modal1">
                    <div class="succes succes-animation icon-top"><i class="fa fa-check"></i></div>
                    <div class="content flex-column align-items-center justify-content-center w-100 gap-1">
                        <p class="type">Item Is Already in Cart</p>
                        <div class="modal-login-reg-btn"><button data-bs-dismiss="modal" aria-label="Close">
                                Continue Shopping
                            </button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection