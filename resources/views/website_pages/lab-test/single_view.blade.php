@extends('layouts.new_web_layout')

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
    <meta property="og:site_name" content="Umbrella Health Care Systems | Umbrellamd.com" />
    <meta name="twitter:site" content="@umbrellamd	">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="author" content="Umbrellamd">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <style>
        .detail-pharmcy-content li {
            list-style: disc;
        }
    </style>
@endsection


@section('page_title')
    @if ($title != null)
        <title>{{ $title->content }}</title>
    @else
        <title>{{ $products[0]->name }} | Lab Tests | Umbrella Health Care Systems</title>
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
    <script src="{{ asset('assets/js/single_lab_test.js') }}"></script>
    <script>
        // Get the height of the content div
        var contentHeight = document.getElementById("contentDiv").clientHeight;

        // Set the height of the main div to the content height
        document.getElementById("mainDiv").style.height = contentHeight + "px";
    </script>
@endsection

@section('content')
    <!-- ******* DETAIIl-PHARMACY STATRS ******** -->
    <section class="detail-pharmacy-bg detail-labtest-bg">
        <div class="container">
            <div class="row">
                <div class="back-arrow-detail-pharmacy">
                    <?php $item = $products[0]; ?>
                    @if (!empty($item->name))
                        <h1 class="Lab_head_MainB">{!! $item->name !!}</h1>
                    @endif

                    {{-- <i class="fa-solid fa-circle-arrow-left go-back" onclick="window.location=document.referrer;"></i> --}}
                    <nav aria-label="breadcrumb">
                        <i class="fa-solid fa-arrow-left"></i>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('welcome_page') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('labs') }}">Lab Tests</a></li>
                            <li class="breadcrumb-item active custom_last_li" aria-current="page">{{ $item->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <div class="d-flex justify-content-between align-items-baseline py-3 lab-test-info-div">
        <div class="container">
            <!-- <h4>Lab Test Information</h4> -->
            <div class="d-flex justify-content-md-between justify-content-center align-items-center flex-wrap">
                <h5 class="text-danger">No Doctor visit is required for this Labtest</h5>
                <!-- <button class="btn detailed_talk_doc">Talk To Doctors</button> -->
                @if (!Auth::check())
                    <button class="btn detailed_talk_doc" data-bs-toggle="modal" data-bs-target="#loginModal">Talk To
                        Doctor</button>
                @elseif(Auth::user()->user_type == 'patient')
                    <button class="btn detailed_talk_doc"
                        onclick="window.location.href='/patient/evisit/specialization'">Talk To Doctor</button>
                @elseif(Auth::user()->user_type == 'doctor')
                    <button class="btn detailed_talk_doc" onclick="window.location.href='/doctor/patient/queue'">Go To
                        Waiting Room</button>
                @endif
            </div>
        </div>
    </div>


    <section class="my-4">
        <div class="container">
            <div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="add-to-cart-detail">
                            <div>
                                @if (!empty($item->name))
                                    <h3>{!! $item->name !!}</h3>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-end detail-cart-btn">
                                <h4>$ {{ number_format($item->sale_price, 2) }}</h4>
                                @if (Auth::check())
                                    <button class="btn btn-primary" type="button"
                                        onclick="addToCart({{ $item->id }},'lab-test',1)"><i
                                            class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                                @else
                                    <button class="btn btn-primary" type="button" onclick="openBeforeModal(this)"><i
                                            class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                                @endif
                            </div>

                            
                        </div>
                    </div>
                    <div class="col-md-3 p-md-0 d-none d-md-block ">
                        <div class="related_items_heading">
                            <h4 class="">Related Labtests</h4>
                        </div>
                    </div>
                </div>


                <div class="row" id="mainDiv">
                    <div class="col-md-9" id="contentDiv">
                        <div class="main_labtest__ConTent mb-2">
                            <div class="detail-pharmcy-content">
                                <div>
                                    <h4 class="pb-3">Detail Description</h4>
                                </div>

                                @if (!empty($item->description))
                                    {!! $item->description !!}
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 main_labtest__ConTent mb-1 d-none d-md-block " id="secondaryDiv">
                        <div class="mt-5">
                            @forelse ($item->related_products as $prod)
                                <div class=" mb-5 mb-2">
                                    <div class="add-to-cart-card lab_sub_page_card">
                                        <div class="card d-flex align-items-center justify-content-center">
                                            <div class="ribon"> <span class="fa-solid fa-flask"></span> </div>
                                            <div class="add-to-cart-card-head">
                                                <h4 class="h-1 pt-5"">{{ $prod->TEST_NAME }}</h4>
                                            </div>
                                            <span class=" price"> <sup class="sup">$</sup> <span
                                                    class="number">{{ number_format($prod->SALE_PRICE, 2) }}</span>
                                            </span>
                                            @if (!empty($prod->DETAILS))
                                                <p> {{ strip_tags(str_replace(["\r", "\n", '&quot;'], '', $prod->DETAILS)) }}
                                                </p>
                                            @endif
                                            <div class="add-cart-btn-div">
                                                <a href="{{ route('single_product_view_labtest',['slug'=>$prod->SLUG]) }}"><button class="btn btn-primary view-detail"> View Details
                                                    </button></a>
                                                    @if(Auth::check())
                                                        <button class="btn btn-primary" type="button" onclick="addToCart({{ $item->id }},'lab-test',1)">Add To Cart</button>
                                                    @else
                                                        <button class="btn btn-primary" type="button" onclick="openBeforeModal(this)">Add To Cart</button>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    {{-- <img src="{{ asset('assets/images/for-empty.png') }}" alt=""> --}}
                                    <h5 class="text-center">No Related Labtests</h5>
                                </div>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ******* DETAIIl-PHARMACY ENDS ******** -->




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
                        <div class="content">
                            <p class="type">Item Added</p>
                            <div class="modal-login-reg-btn"><a href="" data-bs-dismiss="modal"
                                    aria-label="Close"> Continue Shopping </a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- Modal -->
    <div class="modal fade cart-modal" id="beforeLogin" tabindex="-1" aria-labelledby="beforeLoginLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="beforeLoginLabel">Not Logged In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="custom-modal">
                        <div class="icon-top"><i class="fa fa-times"></i></div>
                        <div class="content">
                            <p class="type">Please login to add into cart</p>
                            <div class="modal-login-reg-btn">
                                <a href="{{ route('login') }}"> Login </a>
                            </div>
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
                    <div class="custom-modal">
                        <div class="succes succes-animation icon-top"><i class="fa fa-times"></i></div>
                        <div class="content">
                            <p class="type">Item Is Already in Cart</p>
                            <div class="modal-login-reg-btn"><a href="" data-bs-dismiss="modal"
                                    aria-label="Close"> Continue Shopping </a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ******* LABTEST ENDS ******** -->
    <!-- ******* LOGIN-REGISTER-MODAL STARTS ******** -->
    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Select Registration Type</h5>
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
@endsection
