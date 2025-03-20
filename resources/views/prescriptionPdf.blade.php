<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Email</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        @page {
            size: A4;
        }

        body {
            margin: 110px 24px 200px 24px;
        }

        header {
            position: fixed;
            top: 10px;
            left: 0;
            right: 0;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0;
            right: 0;
        }

        table{
            font-size: 14px;
        }

        h1{
            font-size: 18px;
        }

        h2{
            font-size: 16px;
        }

        .contact-section-small {
            display: none;
        }

        main {
            background-color: white;
            padding: 0.5rem 1rem;
        }

        .patient {
            width: 100%;
            margin: 1rem 0;
            text-align: left;
        }

        .patient th {
            padding: 0.5rem;
        }

        .patient th,
        .patient td {
            border: 1px solid black;
        }

        .patient td,
        .prescription td {
            padding: 0.25rem 0.5rem;
        }

        .patient thead,
        .prescription thead {
            background-color: #f5f5f5;
        }

        .patient tbody,
        .prescription tbody {
            /* font-weight: 500; */
            font-style: italic;
        }

        .prescription-container {
            /* page-break-inside: avoid; */
        }

        .prescription {
            width: 100%;
            /* border: 2px solid black; */
            margin: 1rem 0;
            text-align: center;
        }

        .prescription th {
            padding: 0.5rem;
        }

        .prescription th,
        .prescription td {
            border: 1px solid black;
        }

        .border-red {
            border-color: #c80919;
        }

        .footer-head {
            width: max-content;
        }

        .footer-contact-head {
            padding-bottom: 0.1rem;
        }

        .footer-underline {
            width: 10%;
            background-color: #c80919;
            height: 2px;
        }

        .footer-contact-head {
            margin-top: 0.1rem;
        }

        .contact-div:first-child {
            margin-top: 0;
        }

        .contact-div a {
            color: white;
        }

        .email-copyright {
            padding: 1rem 0;
        }

        .email-logo {
            margin: 0 auto;
        }

        .email-logo img {
            width: 300px;
            margin: auto;
        }

        main .email-image-section {
            margin: 1rem auto;
            text-align: center;
        }

        main .email-image-section>img {
            width: 40%;
        }

        .email-body {
            padding: 1rem 0;
            margin: 0 2rem;
        }

        .prescription-details .patient-heading {
            text-align: center;
        }

        .patient-heading {
            text-transform: uppercase;
        }

        .email-body-info {
            background-color: #f5f5f5;
            padding: 0.25rem 0.5rem;
            font-weight: 500;
            margin-top: 0.1rem;
            font-style: italic;
        }

        .email-details {
            margin: 1rem 0;
            text-align: center;
        }

        .dosage {
            color: #c80919;
        }

        .unit {
            color: #2964bc;
        }

        .number-of-days {
            color: #35b518;
        }

        .dosage,
        .unit,
        .number-of-days {
            margin-top: 1rem;
        }

        .spec-instructions {
            margin: 1rem;
            color: #c80919;
        }

        .patient-detail {
            margin: 1rem 0;
        }

        .patient-detail td {
            padding: 0.5rem 0.5rem 0.5rem 0;
        }

        .prescription-detail {
            padding: 1rem 0;
        }

        .italic {
            font-style: italic;
            font-weight: 500;
        }

        .patient-prescription td {
            padding-right: 0.25rem;
        }

        .patient-prescription {
            padding: 0.5rem 0;
        }

        .patient-prescription li {
            margin: 0 1rem;
        }

        .email-footer {
            background-color: #082755;
            color: white;
            text-align: center;
        }

        .email-footer p {
            margin: 0 1rem;
        }

        .label {
            color: #2964bc;
            font-weight: normal;
        }

        .underline {
            border-bottom: 1px solid #c80919;
            padding-left: 0;
            font-style: italic;
            font-weight: 500;
        }

        .footer-table {
            width: 100%;
            padding: 0 37px;
            text-align: left;
            margin-top: 1rem;
        }

        .contact-div a {
            color: blue;
        }

        .footer-table tbody td {
            width: 50%;
        }

        .footer-table th,
        .footer-table td {
            padding: 0.1rem;
        }

        .top {
            vertical-align: top;
        }

        .prescription-details .border-red {
            margin-bottom: 1rem;
        }

        th {
            text-transform: uppercase;
        }

        .border-top-bottom {
            border-top: 2px solid #c80919;
            border-bottom: 2px solid #c80919;
        }

        .qualification {
            font-weight: normal;
        }

        .text-left{
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <header>
            <div class="email-logo">
                <img src="{{ public_path('assets/new_frontend/logo.png') }}" alt="logo" />
            </div>
        </header>
        <footer>
            <table class="footer-table">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="footer-head">
                                <h3 class="footer-contact-head">E-MAIL</h3>
                                <div class="footer-underline"></div>
                            </div>
                        </td>
                        <td>
                            <div class="footer-head">
                                <h3 class="footer-contact-head">ADDRESS</h3>
                                <div class="footer-underline"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="mailto:support@communityhealthcareclinics.com" target="_blank">support@communityhealthcareclinics.com</a>
                            <a href="mailto:contact@communityhealthcareclinics.com" target="_blank">contact@communityhealthcareclinics.com</a>
                        </td>
                        <td style="padding-bottom: 0.5rem;">
                            Progressive Center, 4th Floor Suite # 410, Main Shahrah-e-Faisal, Karachi
                        </td>
                    </tr>
                    <tr>
                        <!-- <td class="top">contact@communityhealthcareclinics.com</td> -->
                        <td>
                            <div class="footer-head">
                                <h3 class="footer-contact-head">PHONE</h3>
                                <div class="footer-underline"></div>
                            </div>
                        </td>
                        <td>
                            <div class="footer-head">
                                <h3 class="footer-contact-head">WEBSITE</h3>
                                <div class="footer-underline"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                    <td>
                            <div class="footer-highlight">
                                <a href="tel:+14076938484">+1 (407) 693-8484</a>
                            </div>
                            <div class="footer-highlight" style="margin-top: 4px">
                                <a href="https://wa.me/923372350684">0337-2350684</a>
                            </div>
                        </td>
                        <td class="top">
                            <div class="contact-div">
                                <a href="https://www.communityhealthcareclinics.com" target="_blank">www.communityhealthcareclinics.com</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="border-top-bottom" style="margin-top: -20px; padding: 5px 0;">
                                <h3 class="doctor-name">Dr.
                                    {{ $inclinic_data->doctor->name }} {{ $inclinic_data->doctor->last_name }}
                                </h3>
                            </div>
                            {{-- <h5 class="qualification">M.B.B.S, B.D.S</h5> --}}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="email-footer">
                <p class="email-copyright">
                    Copyright &copy; {{date('Y')}}. Community Healthcare Clinics. All Rights
                    Reserved
                </p>
            </div>
        </footer>
        <main>
            <section class="patient-details">
                <h1 class="patient-heading">Patient Details</h1>
                <hr class="border-red" />
                <table class="patient">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $inclinic_data->user->name }}</td>
                            <td>{{ $inclinic_data->user->get_age($inclinic_data->user->id) }}</td>
                            <td>{{ $inclinic_data->user->email }}</td>
                            <td>{{ $inclinic_data->user->phone_number }}</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="patient-details">
                <hr class="border-red" />
                <table class="patient">
                    <thead>
                        <tr>
                            <th>Doctor Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $inclinic_data->doctor_note }}</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            @php
                $medicines = [];
                $labtests = [];
                $imaging = [];

                foreach ($inclinic_data->prescriptions as $items) {
                    if ($items->type == 'medicine') {
                        array_push($medicines, $items);
                    } elseif ($items->type == 'lab-test') {
                        array_push($labtests, $items);
                    } elseif ($items->type == 'imaging') {
                        array_push($imaging, $items);
                    }
                }
            @endphp

            <section class="prescription-details patient-details">
                <h1 class="patient-heading">Prescription Details</h1>
                @if (count($medicines) > 0)
                    <div>
                        <div class="footer-head">
                            <h2 class="footer-contact-head">PHARMACY</h2>
                            <div class="footer-underline"></div>
                        </div>
                        <table class="prescription">
                            <thead>
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Dosage</th>
                                    <th>Unit</th>
                                    <th>No. of Times/Day</th>
                                    <th>Special Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medicines as $medicine)
                                    <tr>
                                        <td>{{ $medicine->med_details->name }}</td>
                                        <td>{{ $medicine->usage }}</td>
                                        <td>{{ $medicine->med_unit }}</td>
                                        <td>{{ $medicine->med_time }}</td>
                                        <td>
                                            @if ($medicine->comment)
                                                {{ $medicine->comment }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if (count($labtests) > 0)
                    <div>
                        <div class="footer-head">
                            <h2 class="footer-contact-head">LABTESTS</h2>
                            <div class="footer-underline"></div>
                        </div>
                        <table class="prescription">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($labtests as $labtest)
                                    <tr>
                                        <td style="text-align: left;">{{ $labtest->lab_details->TEST_NAME }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if (count($imaging) > 0)
                    <div>
                        <div class="footer-head">
                            <h2 class="footer-contact-head">IMAGING</h2>
                            <div class="footer-underline"></div>
                        </div>
                        <table class="prescription">
                            <thead>
                                <tr>
                                    <th>Imaging Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($imaging as $image)
                                    <tr>
                                        <td>{{ $image->imaging_details->TEST_NAME }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </section>
        </main>
    </div>
</body>

</html>
