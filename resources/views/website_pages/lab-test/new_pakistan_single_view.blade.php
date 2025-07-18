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
<style>
    .price-tag::before {
        bottom: -28% !important;
    }

    .discount-no {
        top: 3% !important;
        right: -2% !important;
    }
</style>
@endsection


@section('page_title')
@if ($title != null)
<title>{{ $title->content }}</title>
@else
<title>{{ $products[0]->name }} | Labtest</title>
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
</script>
@endsection

@section('content')
<main class="shops-page">
    <section class="new-header w-85 mx-auto rounded-3">
        <div class="new-header-inner p-4">
            <h1 class="fs-30 mb-0 fw-semibold">{{ $products[0]->name }}</h1>
            <div>
                <a class="fs-12" href="{{ url('/') }}">Home</a>
                <span class="mx-1 align-middle">></span>
                <a class="fs-12" href="{{ route('labs_products', ['id' => $products[0]->vendor_id]) }}">{{$products[0]->vendor_name}}</a>
                <span class="mx-1 align-middle">></span>
                <a class="fs-12" href="{{ route('labs_products', ['id' =>
                $products[0]->vendor_id]) }}">{{$products[0]->slug}}</a>
            </div>
        </div>
    </section>
    <div class="row container-fluid w-85 mt-1 mx-auto">
        <div class="col-md-8 h-100">
            <div
                class="position-relative  w-100 h-100 p-4 my-3 bg-white pharmacy-page-container border border-1 rounded-3">
                <div class="d-flex align-items-center justify-content-between pt-5 pt-sm-4">
                    <h3 class="pt-2 pe-2">Detail Description</h3>
                    <div class="price-tag">
                        <span class="badge bg-danger px-3 py-2">Rs. {{
                            number_format($products[0]->sale_price,2)}}</span>
                        @if ($products[0]->discount_percentage != null && $products[0]->discount_percentage > 0)
                        <span class="actual-price">Rs. {{ number_format($products[0]->actual_price,2) }}</span>
                        @endif
                    </div>
                    @if ($products[0]->discount_percentage != null && $products[0]->discount_percentage > 0)
                    <span class="discount-no">{{ $products[0]->discount_percentage}}% Off</span>
                    @endif
                    @if(Auth::check())
                    <button class="btn-outline-primary {{ $products[0]->vendor_product_id }} lab-test btn"
                        onclick="addedItem(this)">
                        Add to Cart <i class="fa-solid fa-shopping-cart mx-2"></i>
                    </button>
                    @else
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                        Add to Cart <i class="fa-solid fa-shopping-cart mx-2"></i>
                    </button>
                    @endif
                </div>
                <hr class="hr my-2">
                @if ($products[0]->description != null)
                    {!! $products[0]->description !!}
                @else
                    <p class="text-center">No description available for this product.</p>
                @endif

            </div>
        </div>
        <div class="col-md-4 h-100 ">
            <div class="p-4 my-3 z-3 pharmacy-page-container border border-1 rounded-3 background-secondary">
                <h5 class="p-2"><u>Related Labtests</u></h5>

                <div class="d-flex align-items-center justify-content-between p-2 flex-column">
                    @forelse ($products[0]->related_products as $item)
                    <div class="tests-card mb-2">
                        <div class="test-card-content">
                            <h4>{{ $item->TEST_NAME }}</h4>
                            <p class="truncate-overflow">{!! $item->DESCRIPTION !!}</p>
                            <button class="learn_btn">Learn More</button>
                        </div>
                    </div>
                    @empty
                    No Related Product Added
                    @endforelse
                    {{-- <div class="tests-card mb-2">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>
                            </div>
                            <h4>Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <button class="learn_btn">Learn More</button>
                        </div>
                    </div>

                    <div class="tests-card mb-2">
                        <div class="test-card-content">
                            <div class="add_to_cart_container">
                                <button class="add_to_cart_btn">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>
                            </div>
                            <h4>Complete Blood Count</h4>
                            <p class="truncate-overflow">
                                Complete Blood Count (CBC) is a blood test used to
                                evaluate your overall health and detect a wide range of
                                disorders, including anemia, infection and leukemia.
                            </p>
                            <button class="learn_btn">Learn More</button>
                        </div>
                    </div> --}}
                </div>
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
@endsection
