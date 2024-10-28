<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<style>
    h4,
    p {
        padding: 0;
        margin: 0;
    }

    main {
        border: 1px solid #000;
    }

    .red_large_line {
        width: 40%;
        border: 2px solid red;
    }

    .red_small_line {
        width: 30%;
        border: 2px solid #a30000;
        margin-top: 4px;
        margin-bottom: 8px;
    }

    .head__line {
        width: 330px;
        margin: auto;
        border: 1px solid #a30000;
    }
    .head__sec{
        padding: 16px;
    }

    .head__sec h4 {
        color: #c00000;
        margin-top: 7px;
    }
    .head__sec .umb{
        color: #c00000;
    margin-top: 7px;
    font-size: 23px;
    font-weight: 600;
    }

    .head__sec .imging {
        color: #1f497d;
        font-size: 23px;
        font-weight: 500;
        margin-bottom: 7px;

    }

    .patient_inf_main {
        border: 1px solid #000;

    }

    .border_bot {
        border-bottom: 1px solid #000;
    }

    .border_r {
        border-right: 1px solid #000;
    }

    .two_division {
        display: flex;
    }

    .width__50 {
        width: 50%;
    }

    .spa__bold {
        font-weight: 600;
    }

    .sig_b {
        border-bottom: 2px solid #000;
    }

    .right__pad {
        padding: 20px;
    }

    .sig_img {
        margin-top: 20px;
        margin-left: 10px;
    }

    .pad_lef {
        padding-left: 4px;
    }

    main {
        position: relative;
    }

    .logo_div {
        display: flex;
        justify-content: center;
    }

    .logo__ {
        position: absolute;
        height: 500px;
        width: 500px;
        filter: opacity(0.1);
        transform: translate(25%, 45%);;
    }

    .tab__bor{
        border: 1px solid #000;
    }
    .bor_left{
        border-left: 1px solid #000;
    }
</style>

<body>
    <!-- ----line--design--- -->
    <div style="position: relative;">
        <div class="">
            {{-- <img class="logo__" src="{{ public_path('assets/images/logo.png') }}" style="opacity:0.1;" alt=""> --}}
        </div>
    <section class="mt-4">
        <div class="red_large_line"></div>
        <div class="red_small_line"></div>
    </section>
    <table class="tab__bor" style="width: 100%;">
        <tr class="border_bot">
            <td>
                <div>
                    <div class="text-center head__sec">
                        <div class="head__line"></div>
                        <p class="umb">Umbrella Health Care Systems</p>
                        <p class="imging">IMAGING ORDER FORM</p>
                        <div class="head__line"></div>

                    </div>
                </div>
            </td>
        </tr>
        <tr class="border_bot">
            <td>
                <div>
                    <p >Patient information — to be completed by member</p>
                </div>
            </td>
        </tr>
        <tr class="border_bot">
            <td class="p-0">
                <table style="width: 100%;">
                    <tr class=" ">
                        <td class="border_r">
                            <p class="">Name: <span>{{ $first_name }}</span></p>
                        </td>
                        <td>
                            <p class="">Order#: <span>{{ $order_main_id }}</span></p>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr class="border_bot">
            <td>
                <div>
                    <p >Address: {{ $address }}</p>
                </div>
            </td>
        </tr>
        <tr class="border_bot">
            <td class="p-0">
                <table style="width: 100%;">
                    <tr class=" ">
                        <td class="border_r">
                            <p>City: {{ $city }}</p>
                        </td>
                        <td class="border_r">
                            <p>State: {{ $state }}</p>
                        </td>
                        <td class="border_r">
                            <p>Zip: {{ $zip_code }}</p>
                        </td>
                        <td>
                            <p>Phone Number With Area Code: {{ $phone_number }}</p>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr class="border_bot">
            <td class="p-0">
                <table style="width: 100%;">
                    <tr class=" ">
                        <td class="border_r">
                            <p>DOB: {{$patient_dob}}</p>
                        </td>
                        <td class="border_r">
                            <p>Gender: <span>{{ Str::ucfirst($patient_gender) }}</span></p>
                        </td>
                        <td>
                            <p>Email: {{$email_address}}</p>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr class="border_bot">
            <td>
                <div>
                    <p>Imaging service that your doctor recommended</p>
                    @foreach($items as $item)
                        <p><span class="spa__bold">IMAGING PRODUCT:</span> {{$item['name']}}</p>
                        <p><span class="spa__bold">LOCATION:</span> {{$item['address']}}</p>
                        <p><span class="spa__bold">LOCATION ZIP:</span> {{$item['zip_code']}}</p>
                    @endforeach
                </div>
            </td>
        </tr>
        <tr class="border_bot">
            <td>
                <div>
                    <p>Physician and prescription information — physician to complete this section</p>
                </div>
            </td>
        </tr>
        <tr class="border_bot">
            <td class="p-0">
                <table style="width: 100%;">
                    <tr class=" ">
                        <td class="border_r">
                            <p>Prescribing Physician Name: {{'Dr. '.$phy_by}}</p>
                        </td>

                        <td class="border_r">
                            <p>Patient Name: <span>{{$first_name}}</span></p>
                        </td>
                        <td>
                            <p>DOB: {{$patient_dob}}</p>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr class="border_bot">
            <td class="p-0">
                <table style="width: 100%;">
                    <tr class=" ">
                        <td class="border_r p-0" style="width: 50%;">
                            <table style="width: 100%;">
                                <tr class="border_bot">
                                    <td>
                                        <p>Physician Phone Number with Area Code: </p>
                                        <p>{{$phy_phone_number}}</p>
                                    </td>
                                </tr>
                                <tr class="border_bot">
                                    <td>
                                        <p>Physician Fax Number with Area Code: </p>
                                        <p>---</p>
                                    </td>
                                </tr>
                                <tr class="border_bot">
                                    <td>
                                        <p>Physician Street Address: <span>{{$phy_address}}</span></p>
                                    </td>
                                    <tr class="border_bot">
                                        <td>
                                            <p>City, State, ZIP: <span>{{$phy_city.', '.$phy_state.', '.$phy_zip_code}}</span></p>
                                        </td>
                                    </tr>
                                    <tr class="border_bot">
                                        <td class="p-0">
                                            <table style="width: 100%;">
                                                <tr class=" ">
                                                    <td class="border_r">
                                                        <p >NPI: <span>{{$NPI}}</span></p>
                                                    </td>
                                                    <td>
                                                        <p >DEA: <span>------</span></p>

                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr >
                                        <td>
                                            <p style="font-size: 13px;">This document and others if attached contain
                                                information from UHCS that is
                                                privileged, confidential and/or may contain protected health information
                                                (PHI). We are required to safeguard PHI by applicable law. The information
                                                in this document is for the sole use of the person(s) or company named above.
                                                Proper consent to disclose PHI between these parties has been obtained. If
                                                you received this document by mistake, please know that sharing, copying,
                                                distributing or using information in this document is against the law. If you
                                                are not the intended recipient, please notify the sender immediately and
                                                returns the documents by mail to UHCS privacy office 17900 Von Karman, M/S
                                                CA016-0101, Irvine, CA,92614.</p>
                                        </td>
                                    </tr>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50%;">
                            <div>
                                <div style="text-align: center;">
                                    <p class="mb-2">Physician Recommended Product</p>
                                    @foreach($items as $item)
                                        <p>{{$item['name']}}</p>
                                    @endforeach
                                </div>
                                <div style="width:21%">
                                    <img style="height:100px;
                                    width: 150px;
                                    " class="sig_img" src="{{$signature}}" alt="">
                                    <!-- <span>Physician Signature</span> -->
                                </div>
                                <div style="width: 100%;">
                                    <div style="width: 50%;float: left;">
                                        <span class="sig_b">Physician Signature</span>
                                    </div>
                                    <div style="width: 50%;float: right;">
                                        <span class="sig_b">Date: <span>{{$date}}</span></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
        <!-- <tr>
            <td colspan="2">Larry the Bird</td>
            <td>@twitter</td>
        </tr> -->

    </table>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

</html>
