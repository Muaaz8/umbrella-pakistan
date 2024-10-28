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
        font-size: 15px;
        font-weight: 500;
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
    </style>

    {{-- Product Page --}}
    <div class="row mt-5">
        @if(count($product) == 0)
        <div class="alert alert-info" role="alert">
            Sorry ! Products not found.
        </div>
        @else
        {{-- ek price ha to regular hay   = dono hay to regular sale dono hay --}}
        <div class="col-md-4">
            <div class="content-area">
                <h1 style="margin-bottom:0px;margin-top:0px;font-size: 22px;color:#08295a;">{{ $item->name}}</h1>
                {{-- 
                        <p>CPT CODE:<span> {{ }</span></p>
                        --}}
                @if (!empty($item->cpt_code))
                <p>CPT Code:<span> {{$item->cpt_code }}</span></p>
                @else
                <p>Type:<span>Panel Tes ( Including Multiple Tests) </span></p>
                @endif
                {{-- <div class="stock-availability">
                            Availability: &nbsp; <span>
                            <a class="ui green label">In Stock</a>                </span>
                        </div> --}}
                @if (!empty($item->including_test))
                <div class="including_test">
                    <h4 class="mb-4 mt-4">Test Included:</h4>
                    <?php $i = 0; ?>
                    @foreach (explode(",",$item->including_test) as $test_name)
                    <div id="accordion" class="TestAccording">
                        <div class="card">
                            <div class="card-header" id="heading{{ $i }}">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{ $i }}"
                                        aria-expanded="true" aria-controls="collapseOne">
                                        {{ $test_name }}
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse{{ $i }}" class="collapse" aria-labelledby="heading{{ $i }}"
                                data-parent="#accordion">
                                <div class="card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                    richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor
                                    brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt
                                    aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.
                                    Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente
                                    ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them
                                    accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $i++; ?>
                    @endforeach
                </div>
                @endif

                <div class="ui card full-view-mobile">
                    <div class="content">
                        <!--Product PRICE-->
                        <div class="price-box">
                            <div class="cut-price"></div>
                            <div class="price-reg" id="price_product" style=" font-size: 16px; font-weight: bold; ">
                                <b>${{ number_format($item->sale_price, 2)}}</b>
                            </div>
                        </div>
                        <button class="positive ui blue button proceedBtn nextBtn" id="proceed_to_address"
                            style="display: none"></button>
                        <button type="button" onclick="add_to_cart(event, {{ $item->id }}, 'lab-test')" id="buy_now"
                            class="ui basic button AddtoCart chs-btn"><i class="shopping cart icon"></i> Add to
                            Cart</button>
                        {{-- <button class="ui basic button AddtoCart chs-btn" id="buy_now">
                                <i class="shopping cart icon"></i>
                                Add to cart
                                </button> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="labtest-bg"></div>
            <div class="respective-test-features">
                {{-- {!! $item->test_details !!} --}}
                <p><strong>PRINCIPAL OF TEST:</strong> Real-time PCR</p>
                <p><strong>SAMPLE REQUIRED: . </strong>Nasal Swab&nbsp;</p>
                <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    &nbsp; &nbsp; &nbsp;&nbsp;<strong>&nbsp; .</strong> Throat Swab</p>
                <p>&nbsp;</p>
                <p><strong>Reporting Time :</strong> 48 Hours</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="chs-lab-tests mandatory">
                <div class="subsitute-drug">
                    <h3 class="subsitute-drug-modal-heading">Most Popular Test</h3>
                    <div class="box-drug">
                        <h4><a href="##############hvs-for-bacterial-cs-aerobic-e-wet-smear-8209.html">HVS for BACTERIAL
                                C/S (Aerobic) e Wet Smear Lab Test</a> </h4>
                        <h6>$1950.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############anti-thrombin-8252.html">Anti Thrombin Lab Test</a> </h4>
                        <h6>$4850.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############joint-fluid-for-bacterial-cs-aerobic-e-gram-stain-8366.html">Joint
                                Fluid for BACTERIAL C/S (Aerobic) e Gram Stain Lab Test</a> </h4>
                        <h6>$1950.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############anion-gap-serum-8685.html">Anion Gap (Serum) Lab Test</a> </h4>
                        <h6>$1100.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############sputum-for-cytology-malignant-cells-gram-stain-zn-stain-8194.html">Sputum
                                For Cytology (Malignant Cells, Gram Stain &amp; ZN Stain) Lab Test</a> </h4>
                        <h6>$1600.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############rbc-morphology-8167.html">RBC Morphology Lab Test</a> </h4>
                        <h6>$550.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############bone-for-bacterial-cs-aerobic-e-gram-stain-8789.html">Bone for
                                BACTERIAL C/S (Aerobic) e Gram Stain Lab Test</a> </h4>
                        <h6>$1950.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############cmv-cytomegalovirus-dna-by-pcr-viral-load-quantitation-8346.html">CMV
                                (Cytomegalovirus) DNA BY PCR (Viral Load / Quantitation) Lab Test</a> </h4>
                        <h6>$11550.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############calprotectin-stool-8946.html">Calprotectin (Stool) Lab Test</a> </h4>
                        <h6>$4200.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############ett-tip-for-bacterial-cs-aerobic-e-gram-stain-8782.html">ETT Tip for
                                BACTERIAL C/S (Aerobic) e Gram Stain Lab Test</a> </h4>
                        <h6>$1500.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############mll-gene-arrangment-bone-marrow-from-nibd-8428.html">MLL Gene
                                Arrangment (Bone Marrow) (From NIBD) Lab Test</a> </h4>
                        <h6>$8550.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############dihydrotestosterone-dht-8465.html">Dihydrotestosterone (DHT) Lab
                                Test</a> </h4>
                        <h6>$3300.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############afb-cs-first-report-after-three-weeks-30-9115.html">AFB C/S - First
                                Report after three weeks Lab Test</a> </h4>
                        <h6>$3300.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############3gallergy-specific-ige-universal-egg-white-8743.html">3gAllergy
                                Specific IgE Universal Egg White Lab Test</a> </h4>
                        <h6>$1050.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############opiates-urine-8176.html">Opiates (Urine) Lab Test</a> </h4>
                        <h6>$1600.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############dhea-so4-dehydro-epiandrosterone-sulfate-8459.html">DHEA SO4
                                (Dehydro- epiandrosterone Sulfate) Lab Test</a> </h4>
                        <h6>$2150.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############c-2-monitoring-8533.html">C 2 Monitoring Lab Test</a> </h4>
                        <h6>$6050.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############ultra-sound-usg-pelvis-9011.html">Ultra Sound (USG): Pelvis Lab
                                Test</a> </h4>
                        <h6>$2000.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############cvp-tip-for-afb-smearzn-stain-8822.html">CVP Tip For AFB Smear/ZN
                                Stain Lab Test</a> </h4>
                        <h6>$450.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############x-ray-skull-ap-9051.html">X-RAY: Skull AP Lab Test</a> </h4>
                        <h6>$1200.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############methamphetamine-urine-8653.html">Methamphetamine (Urine) Lab Test</a>
                        </h4>
                        <h6>$1600.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############free-t4-8446.html">Free T4 Lab Test</a> </h4>
                        <h6>$2000.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############hemosiderin-urine-8180.html">Hemosiderin (Urine) Lab Test</a> </h4>
                        <h6>$1800.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############dialysis-profile-9167.html">Dialysis Profile Lab Test</a> </h4>
                        <h6>$7050.00/Test</h6>
                        <br clear="all">
                    </div>
                    <div class="box-drug">
                        <h4><a href="##############protein-electrophoresis-serum-8572.html">Protein Electrophoresis
                                (Serum) Lab Test</a> </h4>
                        <h6>$1450.00/Test</h6>
                        <br clear="all">
                    </div>
                    {{-- <button class="ui teal basic button SimilarProductMed desktop hidden">Show more </button> --}}
                </div>
            </div>
        </div>
    </div>





    <div class="row">
        <div class="col-md-12">
            <div class="product-description">
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist" style="margin-right:0px;width:100%">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-1" role="tab"
                            aria-controls="nav-home" aria-selected="true">Description</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-2" role="tab"
                            aria-controls="nav-profile" aria-selected="false">How does it work?</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-5" role="tab"
                            aria-controls="nav-profile" aria-selected="false">FAQ's</a>
                        {{-- @if (!empty($item->medicine_ingredients) || !empty($item->medicine_directions) || !empty($item->medicine_warnings))
                                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-4" role="tab" aria-controls="nav-profile" aria-selected="false">Test Results</a>    
                                        @endif --}}
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-6" role="tab"
                            aria-controls="nav-profile" aria-selected="false">Test Results</a>

                    </div>
                </nav>
                <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-1" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="ui bottom attached tab segment active" data-tab="product-first">
                            <p>&nbsp;</p>

                            <p><strong>&nbsp;</strong></p>

                            <p><strong><strong><u>&nbsp;</u></strong></strong></p>

                            <p><strong><strong><u>&nbsp;</u></strong></strong></p>

                            <p><strong><strong><u>Why is Cholesterol – Total done?</u></strong></strong></p>

                            <p><strong>Cholesterol is tested at more frequent intervals (often several times per year)
                                    when a &nbsp; </strong></p>

                            <p><strong>&nbsp;</strong></p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-2" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="ui bottom attached tab segment active" data-tab="product-twelve">
                            <p><strong><u>How is this test performed?</u></strong></p>
                            <p>This test is performed on a blood sample. The blood is obtained from the vein and this is
                                performed by a healthcare provider in the following way.&nbsp;</p>
                            <ul>
                                <li>clean the skin</li>
                                <li>put an elastic band above the area</li>
                                <li>insert a needle into a vein (usually in the arm inside of the elbow or on the back
                                    of the hand)</li>
                                <li>pull the blood sample into a vial or syringe</li>
                                <li>take off the elastic band and remove the needle from the vein</li>
                            </ul>
                            <p>&nbsp;</p>
                            <p><strong><u>What are the risks associated with this test?</u></strong></p>
                            <p>There is no significant risk associated with this test. A small bruise or mild soreness
                                around the blood test site is common and can last for a few days. Get medical care if
                                the discomfort gets worse or lasts longer.</p>
                            <p>&nbsp;</p>
                            <p><strong><u>Any special preparations for the test?</u></strong></p>
                            <p>8-12 hours of fasting is important. Do not eat or drink anything other than water.</p>
                            <p>&nbsp;</p>
                            <p><strong><u>Can this test be performed during pregnancy?</u></strong></p>
                            <p>Cholesterol is typically high during pregnancy. Women should wait at least six weeks
                                after having a baby to have cholesterol measured.</p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-5" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="ui bottom attached tab segment active" data-tab="product-fourth">
                            <p><strong><u>What does the test measure?</u></strong></p>
                            <p><strong><u>&nbsp;</u></strong></p>
                            <p>In general, healthy lipid levels help to maintain a healthy heart and lower the risk of
                                heart attack or stroke. A general healthcare practitioner&nbsp; will take into
                                consideration total cholesterol results and the other components of a lipid profile as
                                well as other risk factors to help determine a person's overall risk of heart disease,
                                whether treatment is necessary and, if so, which treatment will best help to lower the
                                person's risk.</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-6" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="ui bottom attached tab segment active" data-tab="product-thirteen">
                            <p><strong><u>INTERPRETATION OF THE TEST RESULTS:</u></strong></p>
                            <p>&nbsp;</p>
                            <ul>
                                <li>Cholesterol level below 200mg/dl is considered desirable and reflects low risk of
                                    heart diseases.</li>
                                <li>Level between 200-239mg/dl is borderline high and usually signifies moderate risk of
                                    heart disease</li>
                                <li>Level greater than 240mg/dl is called high and indicates high risk of heart disease
                                </li>
                            </ul>
                            <p><strong><u>&nbsp;</u></strong></p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-3" role="tabpanel" aria-labelledby="nav-profile-tab">
                        @if (!empty($item->medicine_ingredients))
                        <h3> Warnings:</h3>
                        <p>{{  $item->medicine_ingredients }}</p>
                        @endif

                        @if (!empty($item->medicine_directions))
                        <h3>Directions:</h3>
                        <p>{{  $item->medicine_directions }}</p>
                        @endif

                        @if (!empty($item->medicine_warnings))
                        <h3>Directions:</h3>
                        <p>{{  $item->medicine_warnings }}</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <header class="page-header">
            <h1 align="left" class="page-title">Related Lab Test</h1>
        </header>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="owl-carousel owl-theme related_products services-holder">
                <?php $aa = [0,0,1,1,1,1] ?>

                @foreach ($aa as $item)

                <div class="product-box">
                    <div class="ui cards">
                        <div class="card">
                            <div class="content">
                                <div class="header">
                                    <div class="reduce-price">
                                        <span>10% <br>
                                            <strong>Off</strong></span>
                                    </div>
                                    <div class="product-img">
                                        <img src="https://demo1.leotheme.com/bos_medicor_demo/24-large_default/hummingbird-printed-t-shirt.jpg"
                                            alt="test" class="lazz" width="162px">
                                    </div>
                                    <h3>ParacetamolGoody's Extra Strength Headache Pow</h3>
                                </div>
                                <div class="description">
                                    <h4>$203 <span>$225</span></h4>
                                </div>
                                <a href="#" class="ui bottom attached button btn-cart view-test-main">Learn More</a>
                                <div class="ui bottom attached button btn-cart">
                                    Add to cart
                                </div>
                            </div>
                            <a href="#" class="Clickable-Card-Anchor"></a>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>
        </div>
    </div>
    {{-- Product Page --}}
    @endif
</div>