<html>

<head>
    <style>
    :root {
        --litepicker-container-months-color-bg: #fff;
        --litepicker-container-months-box-shadow-color: #ddd;
        --litepicker-footer-color-bg: #fafafa;
        --litepicker-footer-box-shadow-color: #ddd;
        --litepicker-tooltip-color-bg: #fff;
        --litepicker-month-header-color: #333;
        --litepicker-button-prev-month-color: #9e9e9e;
        --litepicker-button-next-month-color: #9e9e9e;
        --litepicker-button-prev-month-color-hover: #2196f3;
        --litepicker-button-next-month-color-hover: #2196f3;
        --litepicker-month-width: calc(var(--litepicker-day-width) * 7);
        --litepicker-month-weekday-color: #9e9e9e;
        --litepicker-month-week-number-color: #9e9e9e;
        --litepicker-day-width: 38px;
        --litepicker-day-color: #333;
        --litepicker-day-color-hover: #2196f3;
        --litepicker-is-today-color: #f44336;
        --litepicker-is-in-range-color: #bbdefb;
        --litepicker-is-locked-color: #9e9e9e;
        --litepicker-is-start-color: #fff;
        --litepicker-is-start-color-bg: #2196f3;
        --litepicker-is-end-color: #fff;
        --litepicker-is-end-color-bg: #2196f3;
        --litepicker-button-cancel-color: #fff;
        --litepicker-button-cancel-color-bg: #9e9e9e;
        --litepicker-button-apply-color: #fff;
        --litepicker-button-apply-color-bg: #2196f3;
        --litepicker-button-reset-color: #909090;
        --litepicker-button-reset-color-hover: #2196f3;
        --litepicker-highlighted-day-color: #333;
        --litepicker-highlighted-day-color-bg: #ffeb3b
    }

    .show-week-numbers {
        --litepicker-month-width: calc(var(--litepicker-day-width) * 8)
    }

    .litepicker {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        font-size: 0.8em;
        display: none
    }

    .litepicker button {
        border: none;
        background: none
    }

    .litepicker .container__main {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex
    }

    .litepicker .container__months {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        background-color: var(--litepicker-container-months-color-bg);
        border-radius: 5px;
        -webkit-box-shadow: 0 0 5px var(--litepicker-container-months-box-shadow-color);
        box-shadow: 0 0 5px var(--litepicker-container-months-box-shadow-color);
        width: calc(var(--litepicker-month-width) + 10px);
        -webkit-box-sizing: content-box;
        box-sizing: content-box
    }

    .litepicker .container__months.columns-2 {
        width: calc((var(--litepicker-month-width) * 2) + 20px)
    }

    .litepicker .container__months.columns-3 {
        width: calc((var(--litepicker-month-width) * 3) + 30px)
    }

    .litepicker .container__months.columns-4 {
        width: calc((var(--litepicker-month-width) * 4) + 40px)
    }

    .litepicker .container__months.split-view .month-item-header .button-previous-month,
    .litepicker .container__months.split-view .month-item-header .button-next-month {
        visibility: visible
    }

    .litepicker .container__months .month-item {
        padding: 5px;
        width: var(--litepicker-month-width);
        -webkit-box-sizing: content-box;
        box-sizing: content-box
    }

    .litepicker .container__months .month-item-header {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        font-weight: 500;
        padding: 10px 5px;
        text-align: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        color: var(--litepicker-month-header-color)
    }

    .litepicker .container__months .month-item-header div {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1
    }

    .litepicker .container__months .month-item-header div>.month-item-name {
        margin-right: 5px
    }

    .litepicker .container__months .month-item-header div>.month-item-year {
        padding: 0
    }

    .litepicker .container__months .month-item-header .reset-button {
        color: var(--litepicker-button-reset-color)
    }

    .litepicker .container__months .month-item-header .reset-button>svg {
        fill: var(--litepicker-button-reset-color)
    }

    .litepicker .container__months .month-item-header .reset-button * {
        pointer-events: none
    }

    .litepicker .container__months .month-item-header .reset-button:hover {
        color: var(--litepicker-button-reset-color-hover)
    }

    .litepicker .container__months .month-item-header .reset-button:hover>svg {
        fill: var(--litepicker-button-reset-color-hover)
    }

    .litepicker .container__months .month-item-header .button-previous-month,
    .litepicker .container__months .month-item-header .button-next-month {
        visibility: hidden;
        text-decoration: none;
        padding: 3px 5px;
        border-radius: 3px;
        -webkit-transition: color 0.3s, border 0.3s;
        transition: color 0.3s, border 0.3s;
        cursor: default
    }

    .litepicker .container__months .month-item-header .button-previous-month *,
    .litepicker .container__months .month-item-header .button-next-month * {
        pointer-events: none
    }

    .litepicker .container__months .month-item-header .button-previous-month {
        color: var(--litepicker-button-prev-month-color)
    }

    .litepicker .container__months .month-item-header .button-previous-month>svg,
    .litepicker .container__months .month-item-header .button-previous-month>img {
        fill: var(--litepicker-button-prev-month-color)
    }

    .litepicker .container__months .month-item-header .button-previous-month:hover {
        color: var(--litepicker-button-prev-month-color-hover)
    }

    .litepicker .container__months .month-item-header .button-previous-month:hover>svg {
        fill: var(--litepicker-button-prev-month-color-hover)
    }

    .litepicker .container__months .month-item-header .button-next-month {
        color: var(--litepicker-button-next-month-color)
    }

    .litepicker .container__months .month-item-header .button-next-month>svg,
    .litepicker .container__months .month-item-header .button-next-month>img {
        fill: var(--litepicker-button-next-month-color)
    }

    .litepicker .container__months .month-item-header .button-next-month:hover {
        color: var(--litepicker-button-next-month-color-hover)
    }

    .litepicker .container__months .month-item-header .button-next-month:hover>svg {
        fill: var(--litepicker-button-next-month-color-hover)
    }

    .litepicker .container__months .month-item-weekdays-row {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        justify-self: center;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        color: var(--litepicker-month-weekday-color)
    }

    .litepicker .container__months .month-item-weekdays-row>div {
        padding: 5px 0;
        font-size: 85%;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        width: var(--litepicker-day-width);
        text-align: center
    }

    .litepicker .container__months .month-item:first-child .button-previous-month {
        visibility: visible
    }

    .litepicker .container__months .month-item:last-child .button-next-month {
        visibility: visible
    }

    .litepicker .container__months .month-item.no-previous-month .button-previous-month {
        visibility: hidden
    }

    .litepicker .container__months .month-item.no-next-month .button-next-month {
        visibility: hidden
    }

    .litepicker .container__days {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        justify-self: center;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        text-align: center;
        -webkit-box-sizing: content-box;
        box-sizing: content-box
    }

    .litepicker .container__days>div,
    .litepicker .container__days>a {
        padding: 5px 0;
        width: var(--litepicker-day-width)
    }

    .litepicker .container__days .day-item {
        color: var(--litepicker-day-color);
        text-align: center;
        text-decoration: none;
        border-radius: 3px;
        -webkit-transition: color 0.3s, border 0.3s;
        transition: color 0.3s, border 0.3s;
        cursor: default
    }

    .litepicker .container__days .day-item:hover {
        color: var(--litepicker-day-color-hover);
        -webkit-box-shadow: inset 0 0 0 1px var(--litepicker-day-color-hover);
        box-shadow: inset 0 0 0 1px var(--litepicker-day-color-hover)
    }

    .litepicker .container__days .day-item.is-today {
        color: var(--litepicker-is-today-color)
    }

    .litepicker .container__days .day-item.is-locked {
        color: var(--litepicker-is-locked-color)
    }

    .litepicker .container__days .day-item.is-locked:hover {
        color: var(--litepicker-is-locked-color);
        -webkit-box-shadow: none;
        box-shadow: none;
        cursor: default
    }

    .litepicker .container__days .day-item.is-in-range {
        background-color: var(--litepicker-is-in-range-color);
        border-radius: 0
    }

    .litepicker .container__days .day-item.is-start-date {
        color: var(--litepicker-is-start-color);
        background-color: var(--litepicker-is-start-color-bg);
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0
    }

    .litepicker .container__days .day-item.is-start-date.is-flipped {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px
    }

    .litepicker .container__days .day-item.is-end-date {
        color: var(--litepicker-is-end-color);
        background-color: var(--litepicker-is-end-color-bg);
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px
    }

    .litepicker .container__days .day-item.is-end-date.is-flipped {
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0
    }

    .litepicker .container__days .day-item.is-start-date.is-end-date {
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px
    }

    .litepicker .container__days .day-item.is-highlighted {
        color: var(--litepicker-highlighted-day-color);
        background-color: var(--litepicker-highlighted-day-color-bg)
    }

    .litepicker .container__days .week-number {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        color: var(--litepicker-month-week-number-color);
        font-size: 85%
    }

    .litepicker .container__footer {
        text-align: right;
        padding: 10px 5px;
        margin: 0 5px;
        background-color: var(--litepicker-footer-color-bg);
        -webkit-box-shadow: inset 0px 3px 3px 0px var(--litepicker-footer-box-shadow-color);
        box-shadow: inset 0px 3px 3px 0px var(--litepicker-footer-box-shadow-color);
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px
    }

    .litepicker .container__footer .preview-date-range {
        margin-right: 10px;
        font-size: 90%
    }

    .litepicker .container__footer .button-cancel {
        background-color: var(--litepicker-button-cancel-color-bg);
        color: var(--litepicker-button-cancel-color);
        border: 0;
        padding: 3px 7px 4px;
        border-radius: 3px
    }

    .litepicker .container__footer .button-cancel * {
        pointer-events: none
    }

    .litepicker .container__footer .button-apply {
        background-color: var(--litepicker-button-apply-color-bg);
        color: var(--litepicker-button-apply-color);
        border: 0;
        padding: 3px 7px 4px;
        border-radius: 3px;
        margin-left: 10px;
        margin-right: 10px
    }

    .litepicker .container__footer .button-apply:disabled {
        opacity: 0.7
    }

    .litepicker .container__footer .button-apply * {
        pointer-events: none
    }

    .litepicker .container__tooltip {
        position: absolute;
        margin-top: -4px;
        padding: 4px 8px;
        border-radius: 4px;
        background-color: var(--litepicker-tooltip-color-bg);
        -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
        white-space: nowrap;
        font-size: 11px;
        pointer-events: none;
        visibility: hidden
    }

    .litepicker .container__tooltip:before {
        position: absolute;
        bottom: -5px;
        left: calc(50% - 5px);
        border-top: 5px solid rgba(0, 0, 0, 0.12);
        border-right: 5px solid transparent;
        border-left: 5px solid transparent;
        content: ""
    }

    .litepicker .container__tooltip:after {
        position: absolute;
        bottom: -4px;
        left: calc(50% - 4px);
        border-top: 4px solid var(--litepicker-tooltip-color-bg);
        border-right: 4px solid transparent;
        border-left: 4px solid transparent;
        content: ""
    }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
    <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
    <meta name="format-detection" content="date=no"> <!-- disable auto date linking in iOS -->
    <meta name="format-detection" content="address=no"> <!-- disable auto address linking in iOS -->
    <meta name="format-detection" content="email=no"> <!-- disable auto email linking in iOS -->
    <title>Account Active</title>
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,300i,400,400i,600,600i,700,700i,800,800i"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lora:300,300i,400,400i,600,600i,700,700i,800,800i"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script:300,300i,400,400i,600,600i,700,700i,800,800i"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,600,600i,700,700i,800,800i"
        rel="stylesheet">
    <style type="text/css">
    /* basics */
    body {
        margin: 0px !important;
        padding: 0px !important;
        display: block !important;
        min-width: 100% !important;
        width: 100% !important;
        -webkit-text-size-adjust: none;
    }

    table {
        border-spacing: 0;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
    }

    table td {
        border-collapse: collapse;
    }

    strong {
        font-weight: bold !important;
    }

    td img {
        -ms-interpolation-mode: bicubic;
        display: block;
        width: auto;
        max-width: auto;
        height: auto;
        margin: auto;
        display: block !important;
        border: 0px !important;
    }

    td p {
        margin: 0 !important;
        padding: 0 !important;
        display: inline-block !important;
        font-family: inherit !important;
    }

    td a {
        text-decoration: none !important;
    }

    /* outlook */
    .ExternalClass {
        width: 100%;
    }

    .ExternalClass,
    .ExternalClass p,
    .ExternalClass span,
    .ExternalClass font,
    .ExternalClass td,
    .ExternalClass div {
        line-height: inherit;
    }

    .ReadMsgBody {
        width: 100%;
        background-color: #ffffff;
    }

    /* iOS blue links */
    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }

    /* gmail blue links */
    u+#body a {
        color: inherit;
        text-decoration: none;
        font-size: inherit;
        font-family: inherit;
        font-weight: inherit;
        line-height: inherit;
    }

    /* buttons fix */
    .undoreset a,
    .undoreset a:hover {
        text-decoration: none !important;
    }

    .yshortcuts a {
        border-bottom: none !important;
    }

    .ios-footer a {
        color: #aaaaaa !important;
        text-decoration: none;
    }

    /* responsive */
    @media screen and (max-width: 640px) {

        td.img-responsive img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            margin: auto;
        }

        table.row {
            width: 100% !important;
            max-width: 100% !important;
        }

        table.center-float,
        td.center-float {
            float: none !important;
        }

        /* stops floating modules next to each other */
        td.center-text {
            text-align: center !important;
        }

        td.container-padding {
            width: 100% !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        table.hide-mobile,
        tr.hide-mobile,
        td.hide-mobile,
        br.hide-mobile {
            display: none !important;
        }

        td.menu-container {
            text-align: center !important;
        }

        td.autoheight {
            height: auto !important;
        }

        table.mobile-padding {
            margin: 15px 0 !important;
        }

        table.br-mobile-true td br {
            display: initial !important;
        }
    }
    </style>
</head>

<body>
<!--module-->

  <!--module-->




<!--module-->
<table style="width:100%;max-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tbody>
		<tr>
         <td bgcolor="#F4F4F4" align="center">
				<table class="row" style="width:600px;max-width:600px;" width="600" cellspacing="0" cellpadding="0" border="0" align="center">
               <tbody>
						<tr>
							<td bgcolor="#f6f6f4" align="center">
								<table class="row" style="width:540px;max-width:540px;" width="540" cellspacing="0" cellpadding="0" border="0" align="center">
									<tbody>
										<tr>
											<td class="container-padding" align="center">
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" class="row" style="width:540px;max-width:540px;">
													<tbody>
														<tr>
															<td align="center">
																<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="width:100%; max-width:100%;">
																	<tbody>
																		<tr>
																			<td height="40">&nbsp;</td>
																		</tr>
																		<tr>
																			<td align="center">
									<img style="height:100px; width:100px;" src="https://communityhealthcareclinics.com/assets/new_frontend/logo.png" alt="img" />
																			</td>
																		</tr>
																		<tr>
																			<td height="20">&nbsp;</td>
																		</tr>
																		<tr>
																			<td align="center" style="font-family:'Josefin Sans', Arial, Helvetica, sans-serif;font-size: 30px;color: #282828;">Account Activated</td>
																		</tr>
																		<tr>
																			<td height="18">&nbsp;</td>
																		</tr>
																		<tr>
																			<td align="center" style="font-family:'Josefin Sans', Arial, Helvetica, sans-serif;font-size: 24px;color: #282828;">Hi Dr.{{ $doctorName }}, your  account has been activated, now you can login into your account at<br>Community Healthcare Clinics, Web-portal.</td>
																		</tr>
																		<tr>
																			<td height="30">&nbsp;</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<table height="30" border="0" bgcolor="#9c8563" cellpadding="0" cellspacing="0">
																					<tbody>
																						<tr>
																							<td align="center" height="40" width="170" style="background-color:#364d81; font-family:'Open Sans', Arial, Helvetica, sans-serif;font-size: 13px;color: #ffffff;font-weight: 600;letter-spacing: 0.5px;">
																								<a href="https://www.umbrellamd.com" target="_blank" style="color: #ffffff">Login Now</a>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																		</tr>
																		<tr>
																			<td height="18">&nbsp;</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
                     </td>
						</tr>
					</tbody>
			   </table>
      	</td>
      </tr>
	</tbody>
</table>
<!--module-->

<table style="width:100%;max-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tbody>
		<tr>
         <td bgcolor="#F4F4F4" align="center">
				<table class="row" style="width:600px;max-width:600px;" width="600" cellspacing="0" cellpadding="0" border="0" align="center">
               <tbody>
						<tr>
							<td bgcolor="#364d81" align="center">
                        <table class="row" style="width:540px;max-width:540px;" width="540" cellspacing="0" cellpadding="0" border="0" align="center">
                           <tbody>
										<tr>
											<td class="container-padding" align="center">
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" class="row" style="width:540px;max-width:540px;">
                                    	<tbody>
														<tr>
															<td align="center">
                                       			<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="width:100%; max-width:100%;">
                                          			<tbody>
																		<tr>
																			<td height="40">&nbsp;</td>
																		</tr>


                                        					<tr>
																			<td height="20">&nbsp;</td>
																		</tr>

                                        					<tr>
																			<td height="20">&nbsp;</td>
																		</tr>
                                        					<tr>
																			<td align="center" style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 19px">This email was sent to : Dr.{{ $doctorName }}<br>
                                            						For any questions please contact at <br>
																	Email: <p style="color:white;">support@communityhealthcareclinics.com</p><br>
																	Phone: +1(407)693-8484<br>
																	9914 Kennerly Rd, Saint Louis, MO, 63128, USA
																			</td>
																		</tr>
                                          				<tr>
																			<td>&nbsp;</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<table cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td align="center" style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 20px;text-decoration: underline"><a href="http://www.example.com" target="_blank" style="color: #dadada">Privacy Policy</a></td>

																						</tr>
																					</tbody>
																				</table>
																			</td>
																		</tr>
                                          				<tr>
																			<td height="40">&nbsp;</td>
																		</tr>
                                        				</tbody>
																</table>
                                        		</td>
														</tr>
                                			</tbody>
												</table>
                                	</td>
										</tr>
                           </tbody>
								</table>
                     </td>
						</tr>
               </tbody>
				</table>
         </td>
      </tr>
   </tbody>
</table>

    <!--module-->




    <!--module-->
    <table style="width:100%;max-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tbody>
            <tr>
                <td bgcolor="#F4F4F4" align="center">
                    <table class="row" style="width:600px;max-width:600px;" width="600" cellspacing="0" cellpadding="0"
                        border="0" align="center">
                        <tbody>
                            <tr>
                                <td bgcolor="#f6f6f4" align="center">
                                    <table class="row" style="width:540px;max-width:540px;" width="540" cellspacing="0"
                                        cellpadding="0" border="0" align="center">
                                        <tbody>
                                            <tr>
                                                <td class="container-padding" align="center">
                                                    <table width="540" border="0" cellpadding="0" cellspacing="0"
                                                        align="center" class="row" style="width:540px;max-width:540px;">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center">
                                                                    <table border="0" width="100%" cellpadding="0"
                                                                        cellspacing="0" align="center"
                                                                        style="width:100%; max-width:100%;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td height="40">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center">
                                                                                    <img width="200"
                                                                                        style="display:block;width:100%;max-width:200px;"
                                                                                        alt="img"
                                                                                        src="https://www.umbrellamd-video.com/asset_frontend/images/logo.png">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="20">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center"
                                                                                    style="font-family:'Josefin Sans', Arial, Helvetica, sans-serif;font-size: 30px;color: #282828;">
                                                                                    Account Activated</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="18">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center"
                                                                                    style="font-family:'Josefin Sans', Arial, Helvetica, sans-serif;font-size: 24px;color: #282828;">
                                                                                    Hi Dr.{{ $doctorName }}, your
                                                                                    account has been activated, now you
                                                                                    can login into your account
                                                                                    at<br>Community Healthcare Clinics,
                                                                                    Web-portal.</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="30">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center">
                                                                                    <table height="30" border="0"
                                                                                        bgcolor="#9c8563"
                                                                                        cellpadding="0" cellspacing="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td align="center"
                                                                                                    height="40"
                                                                                                    width="170"
                                                                                                    style="background-color:#364d81; font-family:'Open Sans', Arial, Helvetica, sans-serif;font-size: 13px;color: #ffffff;font-weight: 600;letter-spacing: 0.5px;">
                                                                                                    <a href="https://www.umbrellamd.com"
                                                                                                        target="_blank"
                                                                                                        style="color: #ffffff">Login
                                                                                                        Now</a>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="18">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!--module-->

    <table style="width:100%;max-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tbody>
            <tr>
                <td bgcolor="#F4F4F4" align="center">
                    <table class="row" style="width:600px;max-width:600px;" width="600" cellspacing="0" cellpadding="0"
                        border="0" align="center">
                        <tbody>
                            <tr>
                                <td bgcolor="#364d81" align="center">
                                    <table class="row" style="width:540px;max-width:540px;" width="540" cellspacing="0"
                                        cellpadding="0" border="0" align="center">
                                        <tbody>
                                            <tr>
                                                <td class="container-padding" align="center">
                                                    <table width="540" border="0" cellpadding="0" cellspacing="0"
                                                        align="center" class="row" style="width:540px;max-width:540px;">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center">
                                                                    <table border="0" width="100%" cellpadding="0"
                                                                        cellspacing="0" align="center"
                                                                        style="width:100%; max-width:100%;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td height="40">&nbsp;</td>
                                                                            </tr>


                                                                            <tr>
                                                                                <td height="20">&nbsp;</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td height="20">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center"
                                                                                    style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 19px">
                                                                                    This email was sent to :
                                                                                    Dr.{{ $doctorName }}<br>
                                                                                    For any questions please contact at
                                                                                    <br>
                                                                                    Email: <p style="color:white;">
                                                                                        support@communityhealthcareclinics.com</p><br>
                                                                                    Phone: +1(407)693-8484<br>
                                                                                    Progressive Center, 4th Floor Suite#410, Main Shahrah Faisal, Karachi
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center">
                                                                                    <table cellspacing="0"
                                                                                        cellpadding="0" border="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td align="center"
                                                                                                    style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 20px;text-decoration: underline">
                                                                                                    <a href="http://www.example.com"
                                                                                                        target="_blank"
                                                                                                        style="color: #dadada">Privacy
                                                                                                        Policy</a></td>

                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="40">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>


</body>

</html>
