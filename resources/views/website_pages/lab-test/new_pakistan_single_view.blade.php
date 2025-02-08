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
    <main>
        <div class="contact-section">
            <div class="contact-content">
                <h1>{{ $products[0]->name }}</h1>
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

        <div class="row container-fluid px-5 mt-2">
            <div class="col-md-8 h-100">
                <div class="position-relative  w-100 h-100 p-4 my-3 bg-white pharmacy-page-container border border-1 rounded-3">
                    <div class="d-flex align-items-center justify-content-between pt-4">
                        <h3 class="pt-2 px-2">Detail Description</h3>
                        <div class="price-tag">
                            <span class="badge bg-danger px-3 py-2">Price: Rs. {{ $products[0]->sale_price}}.00</span>
                            @if ($products[0]->actual_price != null)
                            <span class="actual-price">Rs. {{ $products[0]->actual_price }}</span>
                            @endif
                            </div>
                        @if(Auth::check())
                            <button class="btn-outline-primary {{ $products[0]->id }} lab-test btn" onclick="addedItem(this)">
                                Add to Cart <i class="fa-solid fa-shopping-cart mx-2"></i>
                            </button>
                        @else
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                                Add to Cart <i class="fa-solid fa-shopping-cart mx-2"></i>
                            </button>
                        @endif
                    </div>
                    <hr class="hr">
                    {!! $products[0]->description !!}

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
@endsection
