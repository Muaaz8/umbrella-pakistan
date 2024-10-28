<div class="container mb-4 single-pharmacy-product-page">
    <div class="row">
        <header class="page-header position-relative">
            <h1 align="center" class="page-title">{{ $item->name }}</h1>
            <div class="bredcumbs">
                <nav aria-label="breadcrumb d-flex justify-content-between">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('imaging') }}">Imaging Services</a></li>
                        {{-- <li class="breadcrumb-item"><a
                                href="/labs/{{ $item->product_sub_category_slug }}">{{ $item->product_sub_category }}</a>
                        </li> --}}
                        <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="goBackDesktop">
                <a href="{{ URL::previous() }}" class="text-white">Go Back</a>
            </div>
        </header>
    </div>

    <div class="container mb-4 single-test-page">

        <style>
            .price-box {
                margin-bottom: 0px;
                display: inline-block;
            }

            #buy_now {
                background: #08295a !important;
                color: #fff !important;
                display: inline-block;
                float: right;
                font-size: 1.2rem;
            }

            .labtest-bg {
                background: url('https://dawaai.pk/assets/img/blood-test-bg.png');
                background-size: contain;
                background-repeat: no-repeat;
                width: 100%;
                height: 200px;
                opacity: 0.1;
                z-index: 0;
            }

            .respective-test-features {
                margin-top: -200px;
            }

            .subsitute-drug {
                margin: 0px 0 20px 0 !important;
                height: auto;
                max-height: 320px;
            }

            .subsitute-drug {
                width: 100%;
                border-radius: 6px;
                background: #fff;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(63, 63, 68, 0.1);
                margin: 33px 0 0 0;
                padding: 20px;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                position: relative;
                overflow-x: hidden;
                /* overflow-y: hidden; */
            }

            .subsitute-drug-modal-heading {
                font-size: 18px;
            }

            h1:first-child,
            h2:first-child,
            h3:first-child,
            h4:first-child,
            h5:first-child {
                margin-top: 0;
            }

            .box-drug {
                width: 100%;
                margin: 0 0;
                border-bottom: 1px solid #3c3c3c;
                padding: 4px 0;
            }

            .box-drug h4 {
                color: #1DC7EA;
                font-size: 13px;
                display: inline-block;
                width: 50%;
            }

            .box-drug h6 {
                color: #000;
                font-size: 13px;
                text-align: right;
                display: inline-block;
                margin: 0px;
                float: right;
            }

            .TestAccording .card-header button {
                color: black;
                padding: 0
            }

            div#nav-tab .nav-item {
                padding: 1rem !important;
            }

            .labtest-bg {
                background: url('https://dawaai.pk/assets/img/blood-test-bg.png');
                background-size: contain;
                background-repeat: no-repeat;
                width: 100%;
                height: 200px;
                opacity: 0.1;
                z-index: 0;
            }

            .respective-test-features {
                margin-top: -200px;
            }

            .subsitute-drug {
                margin: 0px 0 20px 0 !important;
                height: auto;
                max-height: 320px;
            }

            .subsitute-drug {
                width: 100%;
                border-radius: 6px;
                background: #fff;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(63, 63, 68, 0.1);
                margin: 33px 0 0 0;
                padding: 20px;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                position: relative;
                overflow-x: hidden;
                /* overflow-y: hidden; */
            }

            .subsitute-drug-modal-heading {
                font-size: 18px;
            }

            h1:first-child,
            h2:first-child,
            h3:first-child,
            h4:first-child,
            h5:first-child {
                margin-top: 0;
                color: #08295a;
            }

            .box-drug {
                width: 100%;
                margin: 0 0;
                border-bottom: 1px solid #3c3c3c;
                padding: 4px 0;
            }

            .box-drug h4 {
                color: #1DC7EA;
                font-size: 13px;
                display: inline-block;
                width: 50%;
            }

            .box-drug h6 {
                color: #08295a;
                font-size: 16px;
                text-align: right;
                display: inline-block;
                margin: 0px;
                font-weight: bold;
                float: right;
            }

            .TestAccording .card-header button {
                color: black;
                padding: 0
            }

            .single-test-page .content-area p {
                font-size: 14px;
                font-weight: 500;
            }

            .box-drug a {
                color: #08295a;
                font-size: 15px;
            }

            .respective-test-features p {
                font-size: 16px;
                font-weight: 500;
            }

            .single-test-page .nav-item {
                font-size: 16px !important;
                font-weight: 400;
                color: #08295a;
            }

            .single-test-page .nav-tabs .nav-item.show .nav-link,
            .single-test-page .nav-tabs .nav-link.active {
                color: #08295a;
                font-weight: 700;
            }

            .single-test-page p {
                font-size: 16px;
                font-weight: 500;
                line-height: 30px;
                text-align: justify;
            }

            .including_test .card h5 button {
                font-size: 15px;
                font-weight: bold;
                color: #fff;
            }

            .including_test .card {
                border: 1px solid #fff;
                background: #08295a;
            }


            .faqContainer .FaqBtn {
                width: 100%;
                text-align: left;
                border-left-color: transparent !important;
                border-bottom: none;
                border-radius: 0 !important;
                background: #f5fafb;
                padding: 15px;
                border-top: 2px solid #65b0bd;
                font-weight: 400;
                font-size: 1.18rem;
                color: #000 !important;
            }

            .faqContainer .card {
                box-shadow: none !important;
            }

            .faqContainer p {
                color: #08295a;
            }

            .single-test-page .table {
                font-size: 16px;
                font-weight: 500;
            }
        </style>

        <div class="row">
            <header style=" margin: 20px 0px 0px 0px; " class="page-header">
                <h1 align="left" class="page-title" style=" font-size: 1.5rem; ">Service Description</h1>
            </header>
        </div>

        <div class="row">
            <header class="page-header">

                {{-- @if (!empty($item->short_description))
                    <div class="col-12">
                        <h3 class="mt-5 mb-4"><b>Description</b></h3>
                        {!! $item->short_description !!}
                    </div>
                @endif --}}


                @if (!empty($item->description))
                    <div class="col-12">
                        <h3 class="mt-5 mb-4"><b>Description</b></h3>
                        {!! $item->description !!}
                    </div>
                @endif

            </header>
        </div>
    </div>
