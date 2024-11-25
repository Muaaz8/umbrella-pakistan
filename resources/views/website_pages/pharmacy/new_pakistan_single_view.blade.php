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
    <meta property="og:site_name" content="Umbrella Health Care Systems | Umbrellamd.com" />
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
        <title>{{ $products[0]->name }} | Pharmacy | Umbrella Health Care Systems</title>
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
    function addedItem()
    {
        var pro_id = $('#product_id').val();
        var quantity = $('#quantity').val();
        var unit = $('#strength').val();
        var pro_mode = "medicine";

        $.ajax({
            type: "POST",
            url: "/add_to_cart",
            data: {
                pro_id: pro_id,
                pro_mode: pro_mode,
                quantity: quantity,
                unit:unit
            },
            success: function(data) {
                console.log(data);
                if(data.check=='1'){
                    $('#alreadyadded').modal('show');
                }
                else{
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

        <div class="container my-3 z-3 pharmacy-page-container border border-1 rounded-3">

            <div class="container border border-1 rounded-2 p-3 my-3">
                <div class="row align-items-center">

                    <div class="col-md-8 d-flex align-items-center justify-content-start">
                        <img src="{{ $products[0]->featured_image }}" width="150" height="150" alt="Medicine"
                            class="img-fluid rounded">
                        <div class="p-3">
                            <h5 class="fw-bold">{{ $products[0]->name }}</h5>
                            <p class="text-muted w-75">{{ $products[0]->generic }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                    <form action="#" method="post" onsubmit="return false;">
                        @csrf
                        <input type="hidden" name="pro_id" id="product_id" value="{{$products[0]->id}}">
                            <div>
                                <label for="quantity" class="form-label fw-bold"><u>Quantity</u></label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary w-25" id="decrement" type="button">-</button>
                                    <input type="number" class="form-control text-center" id="quantity" value="1"
                                        min="1" name="quantity" readonly>
                                    <button class="btn btn-outline-secondary w-25" id="increment" type="button">+</button>
                                </div>
                            </div>
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

                            <hr class="py-1 m-0">
                            <div class="row">
                                <label for="strength" class="form-label fw-bold"><u>Strength</u></label>
                                <div class="col-lg-6 col-md-12">
                                    <select id="strength" class="form-select w-100 h-100" name="unit">
                                        @foreach ($products[0]->units as $item)
                                            <option value="{{ $item->id }}">{{ $item->unit }} (Rs.
                                                {{ $item->sale_price }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    @if(Auth::check())
                                        <button class="medicine_btn w-100 fs-6 fw-bold" onclick="addedItem()">Add to Cart</button>
                                    @else
                                        <button class="medicine_btn w-100 fs-6 fw-bold" data-bs-toggle="modal" data-bs-target="#loginModal">Add to Cart</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <h3 class="text-center p-2"><u>Detail Description</u></h3>
            <div class="med-description p-4">
                {!! $products[0]->description !!}
            </div>
        </div>

        <section id="disclaimer">
            <div class="disclaimer-box"></div>
            <div id="disclaimer-content">
                <div>
                    <h2>DISCLAIMER</h2>
                    <div class="underline"></div>
                </div>
                <div>
                    <p>
                        The information on this site is not intended or implied to be a
                        substitute for professional medical advice, diagnosis or
                        treatment. All content, including text, graphics, images and
                        Information, contained on or available through this web site is
                        for general information purposes only. Umbrellamd.com makes no
                        representation and assumes no responsibility for the accuracy of
                        information contained on or available through this web site, and
                        such information is subject to change without notice. You are
                        encouraged to confirm any information obtained from or through
                        this web site with other sources, and review all information
                        regarding any medical condition or treatment with your
                        physician.
                    </p>
                    <p>
                        Never disregard professional medical advice or delay seeking
                        medical treatment because of something you have read on or
                        accessed through this web site. umbrella health care systems not
                        responsible nor liable for any advice, course of treatment,
                        diagnosis or any other information, services or products that
                        you obtain through this website.
                    </p>
                </div>
            </div>
            <div class="custom-shape-divider-bottom-1731257443">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                    preserveAspectRatio="none">
                    <path
                        d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"
                        class="shape-fill"></path>
                </svg>
            </div>
            <div class="disclaimer-blob">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="gray"
                        d="M46,-39.1C56.3,-35.6,59.3,-17.8,54.9,-4.4C50.5,9.1,38.9,18.1,28.5,30.5C18.1,42.9,9.1,58.5,-2.6,61.1C-14.2,63.7,-28.4,53.2,-43.7,40.8C-59.1,28.4,-75.5,14.2,-75.6,-0.1C-75.6,-14.3,-59.3,-28.6,-44,-32.2C-28.6,-35.7,-14.3,-28.4,1.7,-30.2C17.8,-31.9,35.6,-42.7,46,-39.1Z"
                        transform="translate(100 100)" />
                </svg>
            </div>
        </section>
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

        radioButtons.forEach(radio => {
            radio.addEventListener('change', (event) => {
                quantityInput.value = event.target.value;
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
        });

        decrementButton.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value, 10);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                deselectRadioButtons();
            }
        });
    </script>
@endsection
