<html lang="en">

<head>
    <title>Report</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{asset('fonts/courier_std_font/stylesheet.css')}}" />
    <link rel="shortcut icon" href="{{ asset('asset_frontend/images/logo.ico')}}" type="image/x-icon">

</head>

<body>
    <style>
       body,div, p{
            font-family: 'Courier Std'!important;
            text-transform: capitalize !important;
        }
    .receipt-content .logo a:hover {
        text-decoration: none;
        color: #7793c4;
    }

    .receipt-content .invoice-wrapper {
        background: #fff;
        border: 1px solid #cdd3e2;
        box-shadow: 0px 0px 1px #ccc;
        padding: 40px 40px 60px;
        margin-top: 40px;
        border-radius: 4px;
    }

    .receipt-content .invoice-wrapper .payment-details span {
        color: #a9b0bb;
        display: block;
    }

    .receipt-content .invoice-wrapper .payment-details a {
        display: inline-block;
        margin-top: 5px;
    }

    .receipt-content .invoice-wrapper .line-items .print a {
        display: inline-block;
        border: 1px solid #9cb5d6;
        padding: 13px 13px;
        border-radius: 5px;
        color: #708dc0;
        font-size: 13px;
        -webkit-transition: all 0.2s linear;
        -moz-transition: all 0.2s linear;
        -ms-transition: all 0.2s linear;
        -o-transition: all 0.2s linear;
        transition: all 0.2s linear;
    }

    .receipt-content .invoice-wrapper .line-items .print a:hover {
        text-decoration: none;
        border-color: #333;
        color: #333;
    }

    .receipt-content {
        background: #eceef4;
    }

    @media (min-width: 1200px) {
        .receipt-content .container {
            width: 1424px;
        }
    }

    .receipt-content .logo {
        text-align: center;
        margin-top: 50px;
    }

    .receipt-content .logo a {
        font-family: Myriad Pro, Lato, Helvetica Neue, Arial;
        font-size: 36px;
        letter-spacing: 0.1px;
        color: #555;
        font-weight: 300;
        -webkit-transition: all 0.2s linear;
        -moz-transition: all 0.2s linear;
        -ms-transition: all 0.2s linear;
        -o-transition: all 0.2s linear;
        transition: all 0.2s linear;
    }

    .receipt-content .invoice-wrapper .intro {
        line-height: 25px;
        color: #444;
    }

    .receipt-content .invoice-wrapper .payment-info {
        margin-top: 25px;
        padding-top: 15px;
    }

    .receipt-content .invoice-wrapper .payment-info span {
        color: #a9b0bb;
    }

    .receipt-content .invoice-wrapper .payment-info strong {
        display: block;
        color: #444;
        margin-top: 3px;
    }

    @media (max-width: 767px) {
        .receipt-content .invoice-wrapper .payment-info .text-right {
            text-align: left;
            margin-top: 20px;
        }
    }

    .receipt-content .invoice-wrapper .payment-details {
        border-top: 2px solid #ebecee;
        margin-top: 30px;
        padding-top: 20px;
        line-height: 22px;
    }

    @media (max-width: 767px) {
        .receipt-content .invoice-wrapper .payment-details .text-right {
            text-align: left;
            margin-top: 20px;
        }
    }

    .receipt-content .invoice-wrapper .line-items {
        margin-top: 40px;
    }

    .receipt-content .invoice-wrapper .line-items .headers {
        color: black;
        font-size: 18px;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #ebecee;
        padding-bottom: 4px;
    }

    .receipt-content .invoice-wrapper .line-items .items {
        margin-top: 8px;
        border-bottom: 2px solid #ebecee;
        padding-bottom: 8px;
    }

    .receipt-content .invoice-wrapper .line-items .items .item {
        padding: 10px 0;
        color: #696969;
        font-size: 15px;
    }

    @media (max-width: 767px) {
        .receipt-content .invoice-wrapper .line-items .items .item {
            font-size: 13px;
        }
    }

    .receipt-content .invoice-wrapper .line-items .items .item .amount {
        letter-spacing: 0.1px;
        color: #84868a;
        font-size: 16px;
    }

    @media (max-width: 767px) {
        .receipt-content .invoice-wrapper .line-items .items .item .amount {
            font-size: 13px;
        }
    }

    .receipt-content .invoice-wrapper .line-items .total {
        margin-top: 30px;
    }

    .receipt-content .invoice-wrapper .line-items .total .extra-notes {
        float: left;
        width: 40%;
        text-align: left;
        font-size: 13px;
        color: #7a7a7a;
        line-height: 20px;
    }

    @media (max-width: 767px) {
        .receipt-content .invoice-wrapper .line-items .total .extra-notes {
            width: 100%;
            margin-bottom: 30px;
            float: none;
        }
    }

    .receipt-content .invoice-wrapper .line-items .total .extra-notes strong {
        display: block;
        margin-bottom: 5px;
        color: #454545;
    }

    .receipt-content .invoice-wrapper .line-items .total .field {
        margin-bottom: 7px;
        font-size: 14px;
        color: #555;
    }

    .receipt-content .invoice-wrapper .line-items .total .field.grand-total {
        margin-top: 10px;
        font-size: 16px;
        font-weight: 500;
    }

    .receipt-content .invoice-wrapper .line-items .total .field.grand-total span {
        color: #20a720;
        font-size: 16px;
    }

    .receipt-content .invoice-wrapper .line-items .total .field span {
        display: inline-block;
        margin-left: 20px;
        min-width: 85px;
        color: #84868a;
        font-size: 15px;
    }

    .receipt-content .invoice-wrapper .line-items .print {
        margin-top: 50px;
        text-align: center;
    }

    .receipt-content .invoice-wrapper .line-items .print a i {
        margin-right: 3px;
        font-size: 14px;
    }

    .receipt-content .footer {
        margin-top: 40px;
        margin-bottom: 110px;
        text-align: center;
        font-size: 12px;
        color: #969cad;
    }
    .grey-bg{
        color:black !important;
        background-color:#acacac;
        padding:0px !important;
    }
    </style>

    <div class="receipt-content">
        <div class="container bootstrap snippets bootdey">
            <div class="row">
                <div class="col-md-12">
                    <div class="invoice-wrapper">
                        <div class="row">
                            <div class="intro col-md-4">
                                <p>
                                    QUEST DIAGNOSTICS INCORPORATED <br />
                                    CLIENT SERVICE 800.699.6605
                                </p>

                                <div class="specimen_box">
                                    <p>Specimen Information</p>
                                    @if(isset($report['patient_info']['specimen']))
                                    <p>SPECIMEN: {{$report['patient_info']['specimen']}}</p>
                                    @endif
                                    @if(isset($report['patient_info']['requisition']))
                                    <p>REQUISITION: {{$report['patient_info']['requisition']}}</p>
                                    @endif
                                    <p>LAB REF NO: </p>
                                </div>

                                <div class="dates">
                                    <p>COLLECTED: {{$report['arrOBR'][0]['specimen_collection_date']}}</p>
                                    <p>RECEIVED: {{$report['arrOBR'][0]['specimen_received_date']}}</p>
                                    <p>REPORTED: {{$report['arrOBR'][0]['result_date']}}</p>
                                </div>
                            </div>

                            <div class="intro col-md-4">
                                <p>
                                    PATIENT INFORMATION <br />
                                    <strong>{{$report['patient_info']['lname'].','.$report['patient_info']['fname']}}</strong>
                                </p>
                                <p>DOB: {{$report['patient_info']['dob']}} Age: 25</p>
                                <p>GENDER: {{$report['patient_info']['gender']}}</p>
                            </div>

                            <div class="intro col-md-4">
                                <p>REPORT STATUS : {{$report['result_type']}}</p>
                                <p>ORDERING PHYSICIAN</p>
                                <p>{{$report['provider_name']}}</p>
                                <p>CLIENT INFORMATION</p>
                                <p>{{$report['client_info']['ReceivingFacility']}}</p>
                                <p>UMBRELLA HEALTHCARE SYSTEMS
                                <p>625 School House</p>
                                <p>Road #2, Lakeland, FL 33813</p>
                            </div>
                        </div>

                        <div class="line-items">
                            <div class="headers clearfix">
                                <div class="row">
                                    <div class="col-xs-5">Test Name</div>
                                    <div class="col-xs-2">In Range</div>
                                    <div class="col-xs-2">Out of Range</div>
                                    <div class="col-xs-2">Reference Range</div>
                                    <div class="col-xs-1">Lab</div>
                                </div>
                            </div>
                            <div class="items">
                                @foreach($report['arrOBX'] as $key=>$obr)
                                    <div class="row item" style="color:black !important">
                                        <div class="px-0 col-xs-6">
                                        @if(substr($report['arrOBR'][$key]['name'], -1)!="=")
                                            {{$report['arrOBR'][$key]['name']}}
                                        @endif
                                        </div>
                                    </div>
                                    <div class="row item">  
                                        <div class="px-0 col-xs-5">
                                        </div>
                                        <div class="col-xs-2" style="padding:0px 3% !important">
                                        @if($report['arrOBR'][$key]['status']!="F")
                                        Pending
                                        @endif
                                        </div>
                                    </div>
                                    @if($report['arrOBR'][$key]['status']!="P")
                                    @foreach($obr['OBX'] as $index=>$obx)
                                    @if($obx['Status']!="DNR")
                                        @if($obx['Test Name']=="PROTHROMBIN TIME")
                                            @php $color_class='grey-bg'; @endphp 
                                        @else
                                            @php $color_class=""; @endphp 
                                        @endif
                                        <div class="row item <?php echo $color_class; ?>">
                                            <div class="ml-2 col-xs-5" style="padding-left:2.9rem !important">
                                                {{$obx['Test Name']}}
                                            </div>
                                            <div class="col-xs-2">
                                                @if(isset($obx['Results']))
                                                {{$obx['Results']}}
                                                @endif
                                                </div>
                                            <div class="col-xs-2"></div>
                                            @if(isset($obx['Reference Range'])&&isset($obx['Unit']))
                                            <div class="col-xs-2">{{$obx['Reference Range'].' '.$obx['Unit']}}</div>
                                            @elseif(isset($obx['Unit']))
                                            <div class="col-xs-2">{{$obx['Unit']}}</div>
                                            @elseif(isset($obx['Reference Range']))
                                            <div class="col-xs-2">{{$obx['Reference Range']}}</div>
                                            @endif
                                            @if(isset($obx['Lab']))
                                            <div class="col-xs-1">{{$obx['Lab']}}</div>
                                            @endif
                                        </div>
                                        @if(isset($report['arrNTE']['OBX'][$index]))
                                            @foreach($report['arrNTE']['OBX'][$index] as $nte)
                                            <div class="row item" style="padding:0px !important">
                                                <div class="ml-2 col-xs-5"></div>
                                                <div class="col-xs-7">{{$nte}}</div>
                                            </div>

                                            @endforeach
                                        @endif

                                    @else
                                    <div class="row item">
                                        <div class="ml-2 col-xs-5" style="padding-left:2.9rem !important">
                                            {{$obx['Test Name']}}

                                        </div>
                                        <div class="col-xs-2">Pending</div>
                                    </div>

                                    @endif
                                  @endforeach
                                  @endif
                                @endforeach
                            </div>
                            <div class="total text-right">
                                <p class="extra-notes" style="font-size:12px; width:100% !important">
                                    <strong>Performing Laboratory Information:</strong>
                                    HL  QUEST DIAGNOSTICS-HOUSTON 8933 INTERCHANGE DR HOUSTON TX  77054 Laboratory Director: FIRSTNAME LASTNAME, MD
                                </p>
                                <!-- <div class="field">Subtotal <span>$379.00</span></div>
                                <div class="field">Shipping <span>$0.00</span></div>
                                <div class="field">Discount <span>4.5%</span></div>
                                <div class="field grand-total">
                                    Total <span>$312.00</span>
                                </div> -->
                            </div>

                            <div class="print">
                                <!-- <a href="#">
                                    <i class="fa fa-print"></i>
                                    Print this receipt
                                </a> -->
                            </div>
                        </div>
                    </div>

                    <div class="footer">Copyright © 2021. UmbrellaMD</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>