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
    <meta name="copyright" content="© 2022 All Rights Reserved. Powered By UmbrellaMd">
    <meta name="url" content="https://www.umbrellamd.com">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.umbrellamd.com" />
    <meta property="og:site_name" content="Community Health Care Clinics | Umbrellamd.com" />
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
            $.ajax({
                    type: "POST",
                    url: "/search_lab_item_by_category",
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
                                '<div class="no-product-text py-4">' +
                                '<img src="/assets/images/exclamation.png" alt="">' +
                                '<h1>NO ITEM Found</h1>' +
                                '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>' +
                                '</div>'
                            );

                        } else {
                            if(res.user_id==''){
                                $.each(res.products, function(key, value) {
                                    $('#loadSearchPharmacyItemByCategory').append(
                                    `<div class="tests-card">
                                            <div class="test-card-content">
                                                <div class="add_to_cart_container">
                                                    <button class="add_to_cart_btn" onclick="window.location.href='/labtest/${value.SLUG}'">
                                                        Learn More
                                                    </button>
                                                </div>
                                                <h4 class="truncate" title="${value.TEST_NAME}">${value.TEST_NAME}</h4>
                                                <p class="truncate-overflow">${value.DETAILS}</p>
                                                <div class="test-card-price">Rs. ${value.SALE_PRICE}</div>
                                                <button class="learn_btn" data-bs-toggle="modal" data-bs-target="#loginModal" type="button">Add To Cart <i class="fa-solid fa-cart-shopping mx-2"></i></button>
                                            </div>
                                        </div>`
                                    );
                                });
                            }else{
                                $.each(res.products, function(key, value) {
                                    $('#loadSearchPharmacyItemByCategory').append(
                                        `<div class="tests-card">
                                            <div class="test-card-content">
                                                <div class="add_to_cart_container">
                                                    <button class="add_to_cart_btn" onclick="window.location.href='/labtest/${value.SLUG}'">
                                                        Learn More
                                                    </button>
                                                </div>
                                                <h4 class="truncate" title="${value.TEST_NAME}">${value.TEST_NAME}</h4>
                                                <p class="truncate-overflow">${value.DETAILS}</p>
                                                <div class="test-card-price">Rs. ${value.SALE_PRICE}</div>
                                                <button class="learn_btn ${value.TEST_CD} ${mode}" onclick="addedItem(this)" type="button">Add To Cart <i class="fa-solid fa-cart-shopping mx-2"></i></button>
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
                $.ajax({
                    type: "POST",
                    url: "/search_lab_item_by_category",
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
                                '<div class="no-product-text py-4">' +
                                '<img src="/assets/images/exclamation.png" alt="">' +
                                '<h1>NO ITEM Found</h1>' +
                                '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>' +
                                '</div>'
                            );

                        } else {
                            if(res.user_id==''){
                                $.each(res.products, function(key, value) {
                                    $('#loadSearchPharmacyItemByCategory').append(
                                    `<div class="tests-card">
                                            <div class="test-card-content">
                                                <div class="add_to_cart_container">
                                                    <button class="add_to_cart_btn" onclick="window.location.href='/labtest/${value.SLUG}'">
                                                        Learn More
                                                    </button>
                                                </div>
                                                <h4 class="truncate" title="${value.TEST_NAME}">${value.TEST_NAME}</h4>
                                                <p class="truncate-overflow">${value.DETAILS}</p>
                                                <div class="test-card-price">Rs. ${value.SALE_PRICE}</div>
                                                <button class="learn_btn" data-bs-toggle="modal" data-bs-target="#loginModal" type="button">Add To Cart <i class="fa-solid fa-cart-shopping mx-2"></i></button>
                                            </div>
                                        </div>`
                                    );
                                });
                            }else{
                                $.each(res.products, function(key, value) {
                                    $('#loadSearchPharmacyItemByCategory').append(
                                        `<div class="tests-card">
                                            <div class="test-card-content">
                                                <div class="add_to_cart_container">
                                                    <button class="add_to_cart_btn" onclick="window.location.href='/labtest/${value.SLUG}'">
                                                        Learn More
                                                    </button>
                                                </div>
                                                <h4 class="truncate" title="${value.TEST_NAME}">${value.TEST_NAME}</h4>
                                                <p class="truncate-overflow">${value.DETAILS}</p>
                                                <div class="test-card-price">Rs. ${value.SALE_PRICE}</div>
                                                <button class="learn_btn ${value.TEST_CD} ${mode}" onclick="addedItem(this)" type="button">Add To Cart <i class="fa-solid fa-cart-shopping mx-2"></i></button>
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
        <div class="contact-section">
            <div class="contact-content">
                <h1>Labtests</h1>
                <div class="underline3"></div>
            </div>
            <div class="custom-shape-divider-bottom-17311915372">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                    preserveAspectRatio="none">
                    <path
                        d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                        opacity=".25" class="shape-fill"></path>
                    <path
                        d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                        opacity=".5" class="shape-fill"></path>
                    <path
                        d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                        class="shape-fill"></path>
                </svg>
            </div>
        </div>
        {{--@php
            $alpha = [
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'O',
                'P',
                'Q',
                'R',
                'S',
                'T',
                'U',
                'V',
                'X',
                'Y',
                'Z',
            ];
            $len = count($alpha);
        @endphp
        <div class="container">
            <h3>Categories By Alphabets</h3>
            <div class="container-fluid">
                <div class="alphabetical-categories">
                    <div class="alphabets">
                        @for ($i = 0; $i < $len; $i++)
                            @php
                                $alphabit = $alpha[$i];
                            @endphp
                            <div class="alphabet-group">
                                <span class="alphabet">{{ $alphabit }}</span>
                                <ul class="categories-list">
                                    @foreach ($data['sidebar'] as $val)
                                        @php
                                            $first_char = substr($val->product_parent_category, 0, 1);
                                        @endphp
                                        @if ($first_char == $alphabit)
                                            <li
                                                onclick="window.location.href='{{ route('slug.labtest', ['slug' => $val->slug]) }}'">
                                                {{ $val->product_parent_category }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endfor
                    </div>
                    <hr>
                </div>
            </div>
        </div>--}}

        <div class="container-fluid px-5">
            <h3>Community Health Care Clinics - Labtests</h3>
            <p>
                Community Health Care Clinics offers a wide range of lab tests and diagnostic services.
            </p>
        </div>

        <div class="container-fluid px-5 mt-3 pharmacy-page-container">
            <div
                class="p-4 background-secondary d-flex align-items-center justify-content-between flex-column rounded-4">
                <div class="d-flex align-items-center justify-content-between custom-search-container">
                    <div class="category-dropdown">
                        {{--<select class="form-select custom-select" name="category" id="category">
                            <option value="all">All</option>
                            @foreach ($data['sidebar'] as $key => $item)
                                <option value="{{ $item->id }}">{{ $item->product_parent_category }}</option>
                            @endforeach
                        </select>--}}
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
                                    <button class="add_to_cart_btn" onclick="window.location.href='{{ route('single_product_view_labtest', ['slug' => $item->slug]) }}'">
                                        Learn More
                                    </button>
                                </div>
                                <h4 class="truncate">{{ $item->name }}</h4>
                                <p class="truncate-overflow">{!! strip_tags($item->short_description) !!}</p>
                                <div class="test-card-price">Rs. {{ $item->sale_price }}</div>
                                @if (Auth::check())
                                    <button class="learn_btn {{ $item->id }} lab-test" onclick="addedItem(this)">Add To Cart
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

        </div>
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
    <div class="modal fade cart-modal" id="afterLogin" tabindex="-1" aria-labelledby="afterLoginLabel"
        aria-hidden="true">
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
