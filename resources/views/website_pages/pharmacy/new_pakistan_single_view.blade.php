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
        <title>{{ $products[0]->name }} | Pharmacy </title>
    @endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script>
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function addedItem() {
            var pro_id = $('#product_id').val();
            var quantity = $('#quantity').val();
            var pro_mode = "medicine";

            $.ajax({
                type: "POST",
                url: "/add_to_cart",
                data: {
                    pro_id: pro_id,
                    pro_mode: pro_mode,
                    quantity: quantity,
                },
                success: function(data) {
                    console.log(data);
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
    <main class="shops-page">
        <section class="new-header w-85 mx-auto rounded-3">
            <div class="new-header-inner p-4">
                <h1 class="fs-30 fw-semibold mb-0">{{ $products[0]->name }}</h1>
                <div>
                    <a class="fs-12" href="{{ url('/') }}">Home</a>
                    <span class="mx-1 align-middle">></span>
                    <a class="fs-12"
                        href="{{ route('pharmacy_products', ['id' => $products[0]->vendor_id, 'sub_id' => request()->query('sub_id', null)]) }}"
                    >{{$products[0]->vendor_name}}</a>
                    <span class="mx-1 align-middle">></span>
                    <a class="fs-12"
                        href="{{ route('single_product_view_medicines', ['slug' => $products[0]->slug, 'vendor_id' => $products[0]->vendor_id]) }}">{{ $products[0]->slug }}</a>
                </div>
            </div>
        </section>
        <div class="container w-85 mt-3 mb-1 z-3 pharmacy-page-container border border-1 rounded-3">

            @if ($products[0]->is_otc == 1)
                <div class="container border border-1 rounded-2 p-3 mb-1 mt-3 medicine_detail_container">
                    <div class="row align-items-center">

                        <div class="col-md-3 d-flex align-items-center justify-content-start flex-wrap">
                            <img src="{{ $products[0]->featured_image }}" width="230" height="150" alt="Medicine"
                                class="img-fluid rounded">
                        </div>
                        <div class="col-md-5 d-flex align-items-start justify-content-start flex-wrap flex-column">
                            {{-- <div class="w-100">
                        <h3 title="{{ $products[0]->name }}" class="fw-bold text-truncate w-100 w-lg-75">{{
                            $products[0]->name }}</h3>
                        <p class="text-muted w-100 w-lg-75">{{ $products[0]->generic }}</p>
                        <hr class="mt-2 m-0">
                    </div> --}}
                            <div>
                                @if ($products[0]->is_single)
                                    <div class="my-2">
                                        <label class="form-label fw-bold"><u>Options</u></label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="options" id="option1"
                                                    value="5">
                                                <label class="form-check-label" for="option1">5 Pieces</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="options" id="option2"
                                                    value="10">
                                                <label class="form-check-label" for="option2">10 Pieces</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="options" id="option3"
                                                    value="12">
                                                <label class="form-check-label" for="option3">12 Pieces</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <form action="#" method="post" onsubmit="return false;">
                                @csrf
                                <input type="hidden" name="pro_id" id="product_id"
                                    value="{{ $products[0]->vendor_product_id }}">
                                <input type="hidden" id="base_price"
                                    value="{{ $products[0]->sale_prices - ($products[0]->sale_prices * $products[0]->discount) / 100 }}">
                                <div class="d-flex justify-content-end ">
                                    <label for="Price" class="form-label medicine-total fw-bold" id="price">Rs.
                                        {{ $products[0]->sale_prices - ($products[0]->sale_prices * $products[0]->discount) / 100 }}</label>
                                </div>
                                <div class="my-2">
                                    <label for="quantity" class="form-label fw-bold"><u>Quantity</u></label>
                                    <div class="input-group">
                                        <button class="btn btn-outline-primary w-25 fs-4  counter_btn" id="decrement"
                                            type="button">-</button>
                                        <input type="number" class="form-control text-center" id="quantity"
                                            value="1" min="1" name="quantity" readonly>
                                        <button class="btn btn-outline-primary w-25 fs-4 counter_btn" id="increment"
                                            type="button">+</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        @if (Auth::check())
                                            <button class="medicine_btn w-100 fs-6 fw-bold" onclick="addedItem()">Add to
                                                Cart</button>
                                        @else
                                            <button class="medicine_btn w-100 fs-6 fw-bold" data-bs-toggle="modal"
                                                data-bs-target="#loginModal">Add to Cart</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif


            <h3 class="text-center p-2"><u>Detail Description</u></h3>
            <div class="med-description pt-0 p-2">
                @if ($products[0]->description != null)
                    {!! $products[0]->description !!}
                @else
                    <p class="text-center">No description available for this product.</p>
                @endif
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

    <script>
        const quantityInput = document.getElementById('quantity');
        const incrementButton = document.getElementById('increment');
        const decrementButton = document.getElementById('decrement');
        const radioButtons = document.querySelectorAll('input[name="options"]');
        const priceElement = document.getElementById('price');
        const basePrice = parseFloat(document.getElementById('base_price').value);

        function updatePrice() {
            const quantity = parseInt(quantityInput.value, 10);
            const totalPrice = basePrice * quantity;
            priceElement.textContent = `Rs. ${totalPrice.toFixed(2)}`;
        }

        radioButtons.forEach(radio => {
            radio.addEventListener('change', (event) => {
                quantityInput.value = event.target.value;
                updatePrice();
            });
        });

        const deselectRadioButtons = () => {
            radioButtons.forEach(radio => {
                radio.checked = false;
            });
        };

        incrementButton.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value, 10);
            quantityInput.value = currentValue + 1;
            deselectRadioButtons();
            updatePrice();
        });

        decrementButton.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value, 10);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                deselectRadioButtons();
                updatePrice();
            }
        });

        updatePrice();
    </script>
@endsection
