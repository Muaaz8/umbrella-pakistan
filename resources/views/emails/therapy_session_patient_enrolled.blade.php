<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <!-- <link rel="stylesheet" href="style.css" /> -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
      integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
      integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>

    <title>Patient Enrolled</title>
  </head>
  <body>
    <!-- ******* Email STATRS ******** -->
    <table
      class="container container_full"
      width="100%"
      style="border-collapse: collapse; min-width: 100%; background-color: #e4e4e4;"

    >
      <tbody>
        <tr>
          <th>
            <!-- <center style="width: 100%"> -->
            <table
              width="600"
              style="margin: auto"
              class="email-container"

            >
              <tbody>
                <tr>
                  <th>
                    <!-- BEGIN : SECTION : HEADER -->
                    <table
                      width="100%"
                    >
                      <tbody>
                        <tr>
                          <td style=" background-color: #ffff">
                            <table width="100%">
                              <tbody>
                                <tr>
                                  <th

                                    style="
                                      padding-top: 13px;
                                      padding-bottom: 13px;
                                    "
                                  >
                                    <!-- Logo : BEGIN -->
                                    <a href="https://www.communityhealthcareclinics.com/" target="_blank">
                                      <img
                                        src="https://www.communityhealthcareclinics.com/assets/new_frontend/logo.png"
                                        style="
                                          width: 200px;
                                          display: block;
                                          margin: auto;
                                        "
                                      />
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
                                  <th
                                    style="
                                      color: #4b4b4b;
                                      padding: 26px 52px 13px;
                                      background-color: #ffff;
                                    "
                                  >
                                    <table width="100%" style="color: #4b4b4b">
                                      <tbody>
                                        <tr style="color: #4b4b4b">
                                          <th>
                                            <h1
                                              style="
                                                font-size: 28px;
                                                font-weight: 700;
                                                color: #4b4b4b;
                                                text-transform: none;
                                                text-align: center;
                                              "
                                            >
                                            Patient Enrolled
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
                                  <th style="padding: 0px 45px;">
                                    <p
                                    style="
                                      font-size: 16px;

                                      font-weight: 400;
                                      color: #666363;
                                      margin: 13px 0;

                                      padding: 0px 20px;
                                    "
                                  >
                                  Dear Dr.{{ ucwords($data['doc_name']) }}
                                  </p>

                                    <p
                                      style="
                                        font-size: 16px;

                                        font-weight: 400;
                                        color: #666363;
                                        margin: 13px 0;

                                        padding: 0px 20px;
                                      "
                                    >
                                    A new patient "{{ ucwords($data['pat_name']) }}" have enrolled in your therapy session.
                                    </p>


                                  </th>
                                </tr>
                                <!-- END SECTION: Introduction -->
                                <!-- BEGIN SECTION: Order Number And Date -->
                                <tr class="bg-white">
                                    <th style="padding: 0px 45px;">


                                      <p
                                        style="
                                          font-size: 16px;

                                          font-weight: 400;
                                          color: #666363;
                                          margin: 13px 0;

                                          padding: 0px 20px;
                                        "
                                      >
                                      <span style="
                                      font-size: 20px;
                                        font-weight: 600;">
                                        Session Concerns</span>

                                        <ul style="margin-left: 18px;">
                                        @foreach($data['concerns'] as $concern)
                                            <li style="color: #666363;">{{$concern}}</li>
                                        @endforeach
                                        </ul>

                                      </p>


                                    </th>
                                  </tr>

                                  <tr

                                  >
                                    <th
                                      style="
                                        background-color: #fff;
                                        padding: 0px 52px;
                                      "
                                    >
                                      <table

                                        width="100%"

                                      >
                                        <tbody>
                                          <tr>
                                            <th
                                              style="
                                                border-top-width: 2px;
                                                border-top-color: #dadada;
                                                border-top-style: solid;
                                                background-color: #ffffff;
                                              "
                                              valign="top"
                                            ></th>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </th>
                                  </tr>



                                  <tr class="bg-white">
                                    <th style="padding: 0px 45px;">


                                      <p
                                        style="
                                          font-size: 16px;

                                          font-weight: 400;
                                          color: #666363;
                                          margin: 13px 0;

                                          padding: 0px 20px;
                                        "
                                      >

                                      <span><span style="font-weight:600;">Session Date:</span> {{$data['date']}}</span>
                                      <br>
                                      <span><span style="font-weight:600;">Session Time:</span> {{date('h:i A',strtotime($data['start_time']))}}</span>
                                      <br>
                                      <span><span style="font-weight:600;">Patient State:</span> {{$data['pat_state']}}</span>
                                      <br>
                                      <span><span style="font-weight:600;">Total Patients:</span> {{$data['total']}}</span>
                                      </p>


                                    </th>
                                  </tr>

                                  <tr

                                  >
                                    <th
                                      style="
                                        background-color: #fff;
                                        padding: 0px 52px;
                                      "
                                    >
                                      <table

                                        width="100%"

                                      >
                                        <tbody>
                                          <tr>
                                            <th
                                              style="
                                                border-top-width: 2px;
                                                border-top-color: #dadada;
                                                border-top-style: solid;
                                                background-color: #ffffff;
                                              "
                                              valign="top"
                                            ></th>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </th>
                                  </tr>







                                <!-- END SECTION: Order Number And Date -->
                                <!-- BEGIN SECTION: Products With Pricing -->



                                <!-- BEGIN SECTION: Divider -->

                                <!-- END SECTION: Divider -->
                                <!-- BEGIN SECTION: Closing Text -->
                                <tr
                                  id="section-1468276"
                                  class="section closing_text"
                                >
                                  <th
                                    data-key="1468276_closing_text"
                                    class="text"
                                    style="

                                      font-size: 16px;
                                      line-height: 26px;
                                      font-weight: 400;
                                      color: #666363;
                                      padding: 16px 52px;
                                      text-align: center;
                                      background-color: #fff;
                                    "
                                  >
                                    <p
                                      style="

                                        font-family: -apple-system,
                                          BlinkMacSystemFont, 'Segoe UI', Arial,
                                          'Karla';
                                        font-size: 16px;
                                        line-height: 26px;
                                        font-weight: 400;
                                        color: #666363;
                                        margin: 0;
                                        text-align: center;
                                      "
                                    >
                                      If you need help with anything please
                                      don't hesitate to drop us an email:
                                      <a href=""> support@communityhealthcareclinics.com</a>
                                    </p>
                                  </th>
                                </tr>
                                <!-- END SECTION: Closing Text -->

                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <!-- END : SECTION : MAIN -->
                    <!-- BEGIN : SECTION : FOOTER -->
                    <table
                      style="min-width: 100%">
                      <tbody>
                        <tr>
                          <td
                            class="section_wrapper_th"
                            style="
                              background-color: #fff;
                              padding: 0 52px;
                            "

                          >
                            <table
                              width="100%"

                              style="min-width: 100%"

                            >
                              <!-- BEGIN : 2 COLUMNS : SOCIAL_BLOCK -->
                              <tbody>
                                <tr>
                                  <th
                                  >
                                    <table
                                      width="100%"
                                      style="min-width: 100%">
                                      <tbody>
                                        <tr>
                                          <!-- BEGIN : Column 1 of 2 : SOCIAL_BLOCK -->
                                          <th
                                            width="50%"
                                            style="
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
                                            "
                                          >
                                            <table

                                              width="100%"

                                              style="
                                                min-width: 100%;
                                                text-align: center;
                                              "
                                            >
                                              <!-- Social heading : BEGIN -->
                                              <tbody>
                                                <tr style="text-align: center;" >
                                                  <th
                                                    class="column_footer_title"
                                                    width="100%"
                                                    style="
                                                      padding-right: 5%;
                                                      font-size: 14px;
                                                      line-height: 24px;
                                                      font-weight: 400;
                                                      color: #a3a1a1;
                                                      text-transform: none;
                                                    "

                                                  >
                                                    <p
                                                      style="
                                                        font-size: 14px;
                                                        line-height: 24px;
                                                        font-weight: 400;
                                                        color: #a3a1a1;
                                                        text-transform: none;
                                                        margin: 0 0 13px;
                                                      "

                                                    >
                                                      Let's hang out?
                                                    </p>
                                                  </th>
                                                </tr>
                                                <!-- Social heading : END -->
                                                <!-- Store Address : BEGIN -->
                                                <tr style="text-align: center;">
                                                  <th
                                                    class="d-flex justify-content-evenly"
                                                    width="100%"
                                                    style="padding-right: 5%;">
                                                    <a
                                                      class="social-link"
                                                      href="https://www.facebook.com/CommunityHealthcareClinics"
                                                      target="_blank"
                                                      title="Facebook"
                                                      style="
                                                        color: grey;
                                                        text-decoration: none !important;
                                                        text-decoration: none;
                                                        font-size: 14px;
                                                        text-align: center;
                                                      "
                                                    >
                                                    <i class="fa-brands fa-facebook"></i>
                                                    </a>
                                                    <a
                                                      class="social-link"
                                                      href="https://www.instagram.com/community_healthcare_clinics"
                                                      target="_blank"
                                                      title="Instagram"
                                                      style="
                                                        color: grey;
                                                        text-decoration: none !important;
                                                        text-decoration: none;
                                                        font-size: 14px;
                                                        text-align: center;
                                                      "
                                                    >
                                                    <i class="fa-brands fa-instagram"></i>
                                                    </a>
                                                    <a
                                                      class="social-link"
                                                      href="https://www.linkedin.com/company/community-health-care-clinics/"
                                                      target="_blank"
                                                      title="Linkedin"
                                                      style="
                                                        color: grey;
                                                        text-decoration: none !important;
                                                        text-decoration: none;
                                                        font-size: 14px;
                                                        text-align: center;
                                                      "
                                                    >
                                                    <i class="fa-brands fa-linkedin"></i>
                                                    </a>
                                                  </th>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </th>
                                          <!-- END : Column 1 of 2 : SOCIAL_BLOCK -->
                                          <!-- BEGIN : Column 2 of 2 : SHOP_BLOCK -->
                                          <th
                                            width="50%"

                                            style="

                                              padding-top: 26px;
                                              padding-bottom: 26px;
                                              border-top-width: 2px;
                                              border-top-color: #dadada;
                                              border-top-style: solid;
                                              border-bottom-width: 2px;
                                              border-bottom-color: #dadada;
                                              border-bottom-style: solid;
                                            "

                                          >
                                            <table
                                              width="100%"

                                              style="
                                                min-width: 100%;
                                                text-align: center;
                                              "

                                            >
                                              <!-- Store Address : BEGIN -->
                                              <tbody>
                                                <tr style="text-align: center;">
                                                  <th

                                                    width="100%"
                                                    style="
                                                      padding-left: 5%;
                                                      font-size: 14px;
                                                      line-height: 24px;
                                                      font-weight: 400;
                                                      color: #a3a1a1;
                                                      text-transform: none;
                                                    "
                                                  >
                                                  Progressive Center, 4th Floor Suite#410, Main Shahrah Faisal, Karachi
                                                    <br
                                                      style="text-align: center"
                                                    />
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

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
