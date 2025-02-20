<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="style.css" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Order Placed Email</title>
</head>

<body>
    <!-- ******* Email STATRS ******** -->
    <table class="container container_full" width="100%" style="border-collapse: collapse; min-width: 100%">
        <tbody style="background-color: #e4e4e4;">
            <tr>
                <th>
                    <!-- <center style="width: 100%"> -->
                    <table width="600" style="margin: auto;background-color: #ffff;">
                        <tbody>
                            <tr>
                                <th>
                                    <!-- BEGIN : SECTION : HEADER -->
                                    <table data-id="header" id="section-header" width="100%" cellpadding="0"
                                        cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td style=" background-color: #ffff">
                                                    <table width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <th class="column_logo" style="
                                      padding-top: 13px;
                                      padding-bottom: 13px;
                                    ">
                                                                    <!-- Logo : BEGIN -->
                                                                    <a href="https://www.communityhealthcareclinics.com/" target="_blank">
                                                                        <img src="https://www.communityhealthcareclinics.com/assets/new_frontend/logo.png"
                                                                            style="
                                          width: 200px;
                                          display: block;
                                          margin: auto;
                                        " />
                                                                    </a>
                                                                    <!-- Logo : END -->
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- END : SECTION : HEADER -->
                                    <!-- BEGIN : SECTION : MAIN -->
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="bg-light">
                                                    <table>
                                                        <!-- BEGIN SECTION: Heading -->
                                                        <tbody>
                                                            <tr>
                                                                <th style="
                                      color: #4b4b4b;
                                      padding: 26px 52px 13px;
                                      background-color: #ffff;
                                    ">
                                                                    <table width="100%" style="color: #4b4b4b">
                                                                        <tbody>
                                                                            <tr style="color: #4b4b4b">
                                                                                <th>
                                                                                    <h1 style="
                                                font-size: 28px;
                                                font-weight: 700;
                                                color: #4b4b4b;
                                                text-transform: none;
                                                text-align: center;
                                              ">
                                                                                        Order Confirmation
                                                                                    </h1>
                                                                                </th>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </th>
                                                            </tr>
                                                            <!-- END SECTION: Heading -->
                                                            <!-- BEGIN SECTION: Introduction -->
                                                            <tr>
                                                                <th style="padding: 0px 45px;background: #fff;">
                                                                    <p style="
                                        font-size: 16px;
                                        font-weight: 400;
                                        color: #666363;
                                        margin: 0;
                                        background: #f9f9f9;
                                        padding: 9px 20px;
                                      ">
                                                                        <span style="
                                          font-size: 16px;

                                          font-weight: 400;
                                          color: #666363;
                                        ">
                                                                            Hey,
                                                                        </span>
                                                                    </p>

                                                                    <p style="
                                         font-size: 16px;
                                        font-weight: 400;
                                        color: #666363;
                                        margin: 0;
                                        background: #f9f9f9;
                                        padding: 9px 20px;
                                      ">
                                                                        We've got your order!
                                                                    </p>
                                                                    <p style="
                                         font-size: 16px;
                                        font-weight: 400;
                                        color: #666363;
                                        margin: 0;
                                        background: #f9f9f9;
                                        padding: 9px 20px;
                                      ">

                                                                    </p>
                                                                </th>
                                                            </tr>
                                                            <!-- END SECTION: Introduction -->
                                                            <!-- BEGIN SECTION: Order Number And Date -->
                                                            <tr>
                                                                <th class="bg-white">
                                                                    <h2 style="
                                        color: #4b4b4b;
                                        font-size: 20px;
                                        line-height: 26px;
                                        font-weight: 700;
                                        text-transform: uppercase;
                                        letter-spacing: 1px;
                                        margin: 0;
                                        text-align: center;
                                      ">
                                                                        <span>Order No.</span>
                                                                        {{ $userDetails['order_id'] }}
                                                                    </h2>
                                                                </th>
                                                            </tr>
                                                            <!-- END SECTION: Order Number And Date -->
                                                            <!-- BEGIN SECTION: Products With Pricing -->
                                                            <tr>
                                                                <!-- Bold 1 -->

                                                                <!-- end Bold 1 -->
                                                                <th style="
                                      background-color: #fff;
                                      padding: 13px 52px;
                                    ">
                                                                    <table width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th class="bg-white">
                                                                                    <table width="100%">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <th colspan="2">
                                                                                                    <h3 style="
                                                        color: #bdbdbd;
                                                        font-size: 16px;
                                                        line-height: 52px;
                                                        font-weight: 700;
                                                        text-transform: uppercase;
                                                        border-bottom-width: 2px;
                                                        border-bottom-color: #dadada;
                                                        border-bottom-style: solid;
                                                        letter-spacing: 1px;
                                                        margin: 0;
                                                        text-align: left;
                                                      ">
                                                                                                        Items ordered
                                                                                                    </h3>
                                                                                                </th>
                                                                                            </tr>
                                                                                            @foreach ($data as $test)

                                                                                            <!-- Bold 2 -->

                                                                                            <!-- end Bold 2 -->
                                                                                            <tr
                                                                                                class="row-border-bottom">
                                                                                                <th style="
                                                      border-bottom-width: 2px;
                                                      border-bottom-color: #dadada;
                                                      border-bottom-style: solid;
                                                      padding: 13px 13px 13px 0;
                                                    ">
                                                                                                    <i class="fa-solid fa-capsules"
                                                                                                        style="font-size: 30px"></i>
                                                                                                </th>
                                                                                                <th style="
                                                      padding-top: 13px;
                                                      padding-bottom: 13px;
                                                      border-bottom-width: 2px;
                                                      border-bottom-color: #dadada;
                                                      border-bottom-style: solid;
                                                    ">
                                                                                                    <table width="100%">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <th style="
                                                              font-size: 16px;
                                                              line-height: 26px;
                                                              font-weight: 400;
                                                              color: #666363;
                                                              padding: 13px 6px
                                                                13px 0;
                                                            ">
                                                                                                                    <p style="
                                                                font-size: 16px;
                                                                line-height: 26px;
                                                                font-weight: 400;
                                                                color: #666363;
                                                                margin: 0;
                                                              ">
                                                                                                                        <a href="#"
                                                                                                                            target="_blank"
                                                                                                                            style="
                                                                  color: #666363;
                                                                  text-decoration: none !important;
                                                                  text-underline: none;
                                                                  word-wrap: break-word;
                                                                  text-align: left !important;
                                                                  font-weight: bold;
                                                                ">
                                                                                                                            {{
                                                                                                                            $test->name
                                                                                                                            }}
                                                                                                                        </a>
                                                                                                                        <br />
                                                                                                                        <span
                                                                                                                            class="muted"
                                                                                                                            style="
                                                                  text-align: center;
                                                                  font-size: 14px;
                                                                  line-height: 26px;
                                                                  font-weight: normal;
                                                                  color: #bdbdbd;
                                                                  word-break: break-all;
                                                                ">

                                                                                                                        </span>
                                                                                                                    </p>
                                                                                                                </th>

                                                                                                                <th style="
                                                              padding: 13px 0
                                                                13px 13px;
                                                            " align="right" bgcolor="#ffffff" valign="top">
                                                                                                                    <p style="
                                                                font-size: 16px;
                                                                line-height: 26px;
                                                                font-weight: 400;
                                                                color: #666363;
                                                                margin: 0;
                                                                text-align: right;
                                                              ">
                                                                                                                        ×&nbsp;{{
                                                                                                                        $test->quantity
                                                                                                                        }}
                                                                                                                    </p>
                                                                                                                </th>
                                                                                                                <th width="1"
                                                                                                                    style="
                                                              white-space: nowrap;
                                                              padding: 13px 0
                                                                13px 26px;
                                                            " align="right" bgcolor="#ffffff" valign="top">
                                                                                                                    <p style="
                                                                font-size: 16px;
                                                                line-height: 26px;
                                                                font-weight: 400;
                                                                color: #666363;
                                                                margin: 0;
                                                              " align="right">
                                                                                                                        Rs. {{
                                                                                                                        $test->update_price
                                                                                                                        }}
                                                                                                                    </p>
                                                                                                                </th>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </th>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <th colspan="2"
                                                                                                    bgcolor="#ffffff"
                                                                                                    valign="top"></th>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                        </tbody>
                                                                                    </table>
                                                                                </th>
                                                                            </tr>
                                                                            <tr>
                                                                                <th class="pricing-table" style="
                                              padding: 13px 0;
                                            " valign="top">
                                                                                    <table cellspacing="0"
                                                                                        cellpadding="0" border="0"
                                                                                        width="100%"
                                                                                        style="min-width: 100%"
                                                                                        role="presentation">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <th class="table-title"
                                                                                                    style="
                                                      font-size: 16px;
                                                      font-weight: bold;
                                                      color: #666363;
                                                      width: 65%;
                                                      padding: 6px 0;
                                                      text-align: left;
                                                    ">
                                                                                                    <span
                                                                                                        data-key="1468271_discount"
                                                                                                        style="font-weight: bold">Discount</span>
                                                                                                </th>
                                                                                            </tr>

                                                                                            <tr>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <th style="
                                                      font-size: 16px;
                                                      font-weight: bold;
                                                      color: #666363;
                                                      width: 65%;
                                                      padding: 6px 0;
                                                      text-align: left;
                                                    ">
                                                                                                    Subtotal
                                                                                                </th>
                                                                                                <th style="
                                                      font-size: 16px;
                                                      font-weight: 400;
                                                      color: #666363;
                                                      width: 35%;
                                                      padding: 6px 0;
                                                      text-align: right;
                                                    ">
                                                                                                    Rs. {{
                                                                                                    $userDetails['order_total']
                                                                                                    }}
                                                                                                </th>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <th style="
                                                      font-size: 16px;
                                                      line-height: 26px;
                                                      font-weight: bold;
                                                      color: #666363;
                                                      width: 65%;
                                                      padding: 6px 0;
                                                    " align="left" bgcolor="#ffffff" valign="top">
                                                                                                    Total
                                                                                                </th>
                                                                                                <th style="
                                                      font-size: 16px;
                                                      font-weight: 400;
                                                      color: #666363;
                                                      width: 35%;
                                                      padding: 6px 0;
                                                      text-align: right;
                                                    ">
                                                                                                    Rs. {{
                                                                                                   $userDetails['order_total']
                                                                                                    }}
                                                                                                </th>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </th>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </th>
                                                            </tr>
                                                            <!-- END SECTION: Products With Pricing -->
                                                            <!-- BEGIN SECTION: Payment Info -->


                                                            <tr id="section-1468275" class="section divider">
                                                                <th style="
                                      mso-line-height-rule: exactly;
                                      padding: 26px 52px;
                                    " bgcolor="#ffffff">
                                                                    <table cellspacing="0" cellpadding="0" border="0"
                                                                        width="100%" role="presentation">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th style="
                                              mso-line-height-rule: exactly;
                                              border-top-width: 2px;
                                              border-top-color: #dadada;
                                              border-top-style: solid;
                                            " bgcolor="#ffffff" valign="top"></th>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </th>
                                                            </tr>
                                                            <!-- END SECTION: Divider -->
                                                            <!-- BEGIN SECTION: Closing Text -->
                                                            <tr id="section-1468276" class="section closing_text">
                                                                <th data-key="1468276_closing_text" class="text" style="
                                      mso-line-height-rule: exactly;
                                      font-family: -apple-system,
                                        BlinkMacSystemFont, 'Segoe UI', Arial,
                                        'Karla';
                                      font-size: 16px;
                                      line-height: 26px;
                                      font-weight: 400;
                                      color: #666363;
                                      padding: 13px 52px 52px;
                                    " align="center" bgcolor="#ffffff">
                                                                    <p style="
                                        mso-line-height-rule: exactly;
                                        font-family: -apple-system,
                                          BlinkMacSystemFont, 'Segoe UI', Arial,
                                          'Karla';
                                        font-size: 16px;
                                        line-height: 26px;
                                        font-weight: 400;
                                        color: #666363;
                                        margin: 0;
                                      " align="center">
                                                                        If you need help with anything please
                                                                        don't hesitate to drop us an email:
                                                                        <a href=""> support@communityhealthcareclinics.com</a>
                                                                    </p>
                                                                </th>
                                                            </tr>
                                                            <!-- END SECTION: Closing Text -->
                                                            <tr data-id="link-list">

                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- END : SECTION : MAIN -->
                                    <!-- BEGIN : SECTION : FOOTER -->
                                    <table class="section_wrapper footer" data-id="footer" id="section-footer"
                                        border="0" width="100%" cellpadding="0" cellspacing="0" align="center"
                                        style="min-width: 100%" role="presentation" bgcolor="#ffffff">
                                        <tbody>
                                            <tr>
                                                <td class="section_wrapper_th" style="
                              mso-line-height-rule: exactly;
                              padding: 0 52px;
                            " bgcolor="#ffffff">
                                                    <table border="0" width="100%" cellpadding="0" cellspacing="0"
                                                        align="center" style="min-width: 100%" role="presentation">
                                                        <!-- BEGIN : 2 COLUMNS : SOCIAL_BLOCK -->
                                                        <tbody>
                                                            <tr>
                                                                <th style="mso-line-height-rule: exactly"
                                                                    bgcolor="#ffffff">
                                                                    <table border="0" width="100%" cellpadding="0"
                                                                        cellspacing="0" align="center"
                                                                        style="min-width: 100%" role="presentation">
                                                                        <tbody>
                                                                            <tr>
                                                                                <!-- BEGIN : Column 1 of 2 : SOCIAL_BLOCK -->
                                                                                <th width="50%"
                                                                                    class="column_1_of_2 column_social_block"
                                                                                    style="
                                              mso-line-height-rule: exactly;
                                              padding-top: 26px;
                                              padding-bottom: 26px;
                                              border-top-width: 2px;
                                              border-top-color: #dadada;
                                              border-top-style: solid;
                                              border-bottom-width: 2px;
                                              border-bottom-color: #dadada;
                                              border-bottom-style: solid;
                                              border-right-width: 2px;
                                              border-right-color: #dadada;
                                              border-right-style: solid;
                                            " align="center" bgcolor="#ffffff" valign="top">
                                                                                    <table align="center" border="0"
                                                                                        width="100%" cellpadding="0"
                                                                                        cellspacing="0" style="
                                                min-width: 100%;
                                                text-align: center;
                                              " role="presentation">
                                                                                        <!-- Social heading : BEGIN -->
                                                                                        <tbody>
                                                                                            <tr style="" align="center">
                                                                                                <th class="column_footer_title"
                                                                                                    width="100%" style="
                                                      mso-line-height-rule: exactly;
                                                      padding-right: 5%;
                                                      font-family: -apple-system,
                                                        BlinkMacSystemFont,
                                                        'Segoe UI', Arial,
                                                        'Karla';
                                                      font-size: 14px;
                                                      line-height: 24px;
                                                      font-weight: 400;
                                                      color: #a3a1a1;
                                                      text-transform: none;
                                                    " align="center" bgcolor="#ffffff" valign="top">
                                                                                                </th>
                                                                                            </tr>
                                                                                            <!-- Social heading : END -->
                                                                                            <!-- Store Address : BEGIN -->
                                                                                            <tr
                                                                                                style="text-align: center;">
                                                                                                <th class="d-flex justify-content-evenly"
                                                                                                    width="100%"
                                                                                                    style="padding-right: 5%;">
                                                                                                    <a class="social-link"
                                                                                                        href="https://www.facebook.com/CommunityHealthcareClinics"
                                                                                                        target="_blank"
                                                                                                        title="Facebook"
                                                                                                        style="
                                                        color: grey;
                                                        text-decoration: none !important;
                                                        text-decoration: none;
                                                        font-size: 14px;
                                                        text-align: center;
                                                        cursor: pointer;
                                                      ">
                                                                                                        <img style="height:30px !important;" src="https://www.communityhealthcareclinics.com/assets/images/facebook.png"></img>
                                                                                                    </a>

                                                                                                    <a class="social-link"
                                                                                                        href="https://www.instagram.com/community_healthcare_clinics"
                                                                                                        target="_blank"
                                                                                                        title="instagram"
                                                                                                        style="
                                                        color: grey;
                                                        text-decoration: none !important;
                                                        text-decoration: none;
                                                        font-size: 14px;
                                                        text-align: center;
                                                        cursor: pointer;
                                                      ">
                                                      <img src="https://www.communityhealthcareclinics.com/assets/images/instagram.png" style="height:30px !important;"></img>
                                                                                                    </a>
                                                                                                    <a class="social-link"
                                                                                                        href="https://www.linkedin.com/company/community-health-care-clinics/"
                                                                                                        target="_blank"
                                                                                                        title="linkedin"
                                                                                                        style="
                                                        color: grey;
                                                        text-decoration: none !important;
                                                        text-decoration: none;
                                                        font-size: 14px;
                                                        text-align: center;
                                                        cursor: pointer;
                                                      ">
                                                      <img src="https://www.communityhealthcareclinics.com/assets/images/linkedin.png" style="height:30px !important;"></img>
                                                                                                    </a>
                                                                                                </th>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </th>
                                                                                <!-- END : Column 1 of 2 : SOCIAL_BLOCK -->
                                                                                <!-- BEGIN : Column 2 of 2 : SHOP_BLOCK -->
                                                                                <th width="50%"
                                                                                    class="column_2_of_2 column_shop_block"
                                                                                    style="
                                              mso-line-height-rule: exactly;
                                              padding-top: 26px;
                                              padding-bottom: 26px;
                                              border-top-width: 2px;
                                              border-top-color: #dadada;
                                              border-top-style: solid;
                                              border-bottom-width: 2px;
                                              border-bottom-color: #dadada;
                                              border-bottom-style: solid;
                                            " align="center" bgcolor="#ffffff" valign="top">
                                                                                    <table align="center" border="0"
                                                                                        width="100%" cellpadding="0"
                                                                                        cellspacing="0" style="
                                                min-width: 100%;
                                                text-align: center;
                                              " role="presentation">
                                                                                        <!-- Store Address : BEGIN -->
                                                                                        <tbody>
                                                                                            <tr style="" align="center">
                                                                                                <th class="column_shop_block2"
                                                                                                    data-key="section_shop_block2"
                                                                                                    width="100%" style="
                                                      mso-line-height-rule: exactly;
                                                      padding-left: 5%;
                                                      font-family: -apple-system,
                                                        BlinkMacSystemFont,
                                                        'Segoe UI', Arial,
                                                        'Karla';
                                                      font-size: 14px;
                                                      line-height: 24px;
                                                      font-weight: 400;
                                                      color: #a3a1a1;
                                                      text-transform: none;
                                                    " align="center" bgcolor="#ffffff" valign="top">
                                                                Progressive Center, 4th Floor Suite#410, Main Shahrah Faisal, Karachi
                                                                                                    <br
                                                                                                        style="text-align: center" />
                                                                                                    Copyright © 2025
                                                                                                </th>
                                                                                            </tr>
                                                                                            <!-- Store Address : END -->
                                                                                        </tbody>
                                                                                    </table>
                                                                                </th>
                                                                                <!-- END : Column 2 of 2 : SHOP_BLOCK -->
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </th>
                                                                <!-- END : 2 COLUMNS : SHOP_BLOCK -->
                                                            </tr>
                                                            <tr>
                                                                <th data-id="store-info"
                                                                    style="mso-line-height-rule: exactly"
                                                                    bgcolor="#ffffff">
                                                                    <table border="0" width="100%" cellpadding="0"
                                                                        cellspacing="0" role="presentation">
                                                                        <!-- <tbody>
                                        <tr>
                                          <th
                                            class="column_shop_block1"
                                            width="100%"
                                            style="
                                              mso-line-height-rule: exactly;
                                              font-family: -apple-system,
                                                BlinkMacSystemFont, 'Segoe UI',
                                                Arial, 'Karla';
                                              font-size: 14px;
                                              line-height: 24px;
                                              font-weight: 400;
                                              color: #a3a1a1;
                                              text-transform: none;
                                              padding-bottom: 13px;
                                              padding-top: 26px;
                                            "
                                            align="center"
                                            bgcolor="#ffffff"
                                          >
                                            <a
                                              href="https://us.tens.co/tools/emails/click/order-confirmation/1/footer-website-link/link?url=https%3A%2F%2Fus.tens.co"
                                              target="_blank"
                                              data-key="section_shop_block1"
                                              style="
                                                color: #ecba78;
                                                text-decoration: none !important;
                                                text-underline: none;
                                                font-size: 14px;
                                                font-weight: 400;
                                                text-transform: none;
                                              "
                                              >us.tens.co</a
                                            >
                                          </th>
                                        </tr>
                                      </tbody> -->
                                                                    </table>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th height="1" border="0" style="
                                      height: 1px;
                                      line-height: 1px;
                                      font-size: 1px;
                                      mso-line-height-rule: exactly;
                                      padding: 0;
                                    " bgcolor="#ffffff">
                                                                    <img id="open-image"
                                                                        src="https://us.tens.co/tools/emails/open/order-confirmation/1"
                                                                        alt="" width="1" height="1" border="0" />
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- END : SECTION : FOOTER -->
                                </th>
                            </tr>
                        </tbody>
                    </table>
                    <!-- </center> -->
                </th>
            </tr>
        </tbody>
    </table>

    <!-- ******* Email ENDS ******** -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>
