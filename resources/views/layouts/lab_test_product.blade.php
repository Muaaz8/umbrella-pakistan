<div class="container mb-4 single-pharmacy-product-page">
    <div class="row">
        <header class="page-header position-relative">
            <h1 align="center" class="page-title">{{ $item->name }}</h1>
            <div class="bredcumbs">
                <nav aria-label="breadcrumb d-flex justify-content-between">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="/labtests">Labtest</a></li>
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

            .info-icon {
                border: solid white 1px;
                border-radius: 100%;
                padding: 3px;
                box-sizing: border-box;
                width: 18px;
                text-align: center;
                height: 18px;
                margin-left: 5px;
                background-color: #7a7474;
                color: #fff;
                font-size: 9px;
                position: relative;
                top: -3px;
            }

            .tooltip {
                position: relative;
                display: inline-block;
                border-bottom: 1px dotted black;
                opacity: 1 !important;
                width: 20px;
            }

            .tooltiptext {
                display: none;
            }

            .tooltip .tooltiptext {
                width: 250px;
                background-color: #484545;
                color: #fff;
                border-radius: 8px;
                text-align: justify;
                padding: 5px 5px;
                position: absolute;
                z-index: -1;
                right: -10px;
            }

            .doctorFee:hover .tooltiptext {
                display: block !important;
            }
        </style>

        <div class="row">
            <header style=" margin: 20px 0px 0px 0px; " class="page-header">
                <h1 align="left" class="page-title" style=" font-size: 1.5rem; ">Lab Test Information <small
                        class="text-info float-right">No
                        doctor visits required for any labtest</small></h1>
            </header>
        </div>

        <div class="row">
            <header class="page-header">


                @if (!empty($item->description))
                    <div class="col-12">
                        <h3 class="mt-5 mb-4"><b>Description</b></h3>
                        {!! $item->description !!}
                    </div>
                @endif

                @if (!empty($item->test_details))
                    <div class="col-12">
                        <h3 class="mt-5 mb-4"><b>More Test Details</b></h3>
                        {!! $item->test_details !!}
                    </div>
                @endif
            </header>
        </div>

        <div class="row page-header" style="height:110px !important">
            <div class="col-md-6">
                <h3 style="font-size: 2rem; "><b>Price:
                        ${{ number_format($item->sale_price, 2) }}</b></h3>
            </div>
            <div class="col-md-6">
                @if (Auth::check())
                    <div class="d-flex flex-column">
                        <div>
                            <button type="button"
                                onclick="add_to_cart(event, {{ $item->id }}, '{{ $item->tbl_name }}')"
                                id="buy_now" class="ui basic button AddtoCart chs-btn"><i
                                    class="shopping cart icon"></i> Add to
                                Cart</button>
                        </div>

                        <div class="customTooltip">
                            <span class="doctorFee"> +$6.00 Provider's Fee

                                <div class="tooltip"><i class="fa fa-info info-icon"></i>
                                    <span class="tooltiptext">$6 fee is collected
                                        on behalf of affiliated physicians oversight for lab testing, lab results may
                                        require physicians
                                        follow-up services, UmbrellaMD will collect this fee for each order and it's
                                        non-refundable.</span>
                                </div>
                            </span>
                        </div>
                    </div>
                @else
                    <div>
                        <button type="button" class="ui basic button chs-btn btnDialogueLogin" id="buy_now"><i
                                class="shopping cart icon"></i> Add to
                            Cart</button>
                    </div>
                @endif

            </div>

        </div>

        <div class="row" style=" margin-bottom: 5%; ">

            @if (isset($item->including_test) && !empty(json_decode($item->including_test)))

                <div class="col-6">

                    <header style="margin: 15px 0px 0px 0px; color: #08295a;" class="page-header">
                        Additional Information
                    </header>

                    <header style="margin: 15px 0px 0px 0px;" class="page-header">

                        @if (!empty($item->including_test))

                            <h3>These All Tests and Panels Are Include In This Panel. </h3>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Test</th>
                                                {{-- <th>Price</th> --}}
                                                <th>CPT Code</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (json_decode($item->including_test) as $panel_test)
                                                <tr>
                                                    <td><a style="color:#000;"
                                                            href="/product/lab-test/{{ $panel_test->slug }}">{{ $panel_test->test_name }}</a>
                                                    </td>
                                                    {{-- <td>${{ number_format($panel_test->price, 2) }}</td> --}}
                                                    <td>{{ empty($panel_test->cpt_code) ? '-' : $panel_test->cpt_code }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <h4>Not Found</h4>
                        @endif


                    </header>

                </div>

            @endif

            @if (isset($item->faq_for_test) && count(json_decode($item->faq_for_test)) < 0)
                <div class="col-6">
                    <header style="margin: 15px 0px 0px 0px; color: #08295a;" class="page-header">
                        FAQ Hello
                    </header>


                    <header style="margin: 15px 0px 0px 0px; " class="page-header">

                        <div class="faqContainer">
                            <div id="accordion">

                                @if (!empty($item->faq_for_test))

                                    @foreach (json_decode($item->faq_for_test) as $faq_item)
                                        <div class="card">

                                            <div class="card-header" id="heading{{ $faq_item->id }}">
                                                <h5 class="mb-0">

                                                    <button class="ui basic button AddtoCart chs-btn collapsed FaqBtn"
                                                        style="background: #08295a !important; color: #fff !important;"
                                                        data-toggle="collapse"
                                                        data-target="#collapse{{ $faq_item->id }}"
                                                        aria-expanded="true"
                                                        aria-controls="collapse{{ $faq_item->id }}">

                                                        {{ $faq_item->question }}

                                                    </button>

                                                </h5>
                                            </div>

                                            <div id="collapse{{ $faq_item->id }}" class="collapse"
                                                aria-labelledby="heading{{ $faq_item->id }}"
                                                data-parent="#accordion">
                                                <div class="card-body">
                                                    {!! $faq_item->answer !!}
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                @else
                                    <h2>No FAQ Found.</h2>
                                @endif

                            </div>
                        </div>

                        <!-- {!! $item->short_description !!} -->
                    </header>

                </div>
            @endif
        </div>
    </div>
