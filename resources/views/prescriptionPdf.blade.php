<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email</title>

    <style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.contact-section-small {
    display: none;
}

.email-container {
    background-color: white;
}

.email-container main {
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

.prescription {
    width: 100%;
    border: 2px solid black;
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
    padding-bottom: 0.2rem;
}

.footer-underline {
    width: 75%;
    background-color: #c80919;
    height: 2px;
    border-radius: 20px;
}

.email-container>header {
    text-align: center;
}

.contact-div:first-child {
    margin-top: 0;
}

.contact-div {
    margin-top: 0.75rem;
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

.email-logo>img {
    max-width: 400px;
    width: 70%;
    margin: auto;
}

.email-container main .email-image-section {
    margin: 1rem auto;
    text-align: center;
}

.email-container main .email-image-section>img {
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
    border-radius: 0.25rem;
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
    /* background: linear-gradient(to bottom, #2964bc), #082755; */
    color: white;
    text-align: center;
    border-bottom-left-radius: 1rem;
    border-bottom-right-radius: 1rem;
}

.email-footer>p {
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
    text-align: left;
    margin-top: 1rem;
}

.footer-highlight {
    width: max-content;
    background-color: #c80919;
    padding: 10px 15px;
    border-radius: 20px;
}

.contact-div a {
    color: blue;
}

.footer-highlight > a {
    color: white;
    text-decoration: none;
}

.footer-table .footer-head {
    margin-bottom: 0.25rem;
}

.footer-table tbody td {
    width: 50%;
}

.footer-table th, .footer-table td {
    padding: 0.1rem;
}

.top {
    vertical-align: top;
}

.prescription-details .border-red{
    margin-bottom: 1rem;
}

th {
    text-transform: uppercase;
}

.border-top-bottom {
    border-top: 2px solid #c80919;
    border-bottom: 2px solid #c80919;
    border-radius: 0.15rem;
    vertical-align: top;
}

.qualification {
    /* color: gray; */
    font-weight: normal;
}

@media screen and (max-width: 665px) {
    .contact-section-big {
        display: none;
    }

    .contact-section-small {
        display: block;
    }

    .footer-highlight {
        margin: 0.5rem 0;
    }

    .contact-div a, .contact-div p, .footer-highlight:not(a) {
        margin: 0.25rem 0;
    }

    .contact-section-small hr {
        margin-bottom: 1rem;
    }
}

@media screen and (max-width: 550px) {

    .patient thead,
    .prescription thead {
        font-size: 0.8rem;
    }

    .patient tbody,
    .prescription tbody {
        font-size: 0.8rem;
    }

    .patient-heading {
        font-size: 1.2rem;
    }

    .footer-contact-head {
        font-size: 1.2rem;
    }

    .footer-highlight {
        font-size: 0.8rem;
    }

    .contact-div a, .contact-div p, .footer-highlight:not(a) {
       font-size: 0.8rem;
    }

    .email-copyright {
        font-size: 0.8rem;
    }
}

@media screen and (max-width: 455px) {
    .patient th,
    .prescription th,
    .patient td,
    .prescription td {
        padding: 0.25rem;
        font-size: 0.7rem;
    }

    .patient,
    .prescription {
        border: 1px solid black;
    }

    .patient th,
    .patient td, .prescription th, .prescription td {
        border: 1px solid black;
    }

    .email-container main {
        padding: 0.5rem;
    }

    .email-footer {
        padding: 0.5rem 0.5rem 0 0.5rem;
    }

    .patient-heading {
        font-size: 1rem;
    }

    .footer-contact-head {
        font-size: 1rem;
    }
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
                <td>{{ $inclinic_data->user->date_of_birth }}</td>
                <td>{{ $inclinic_data->user->email }}</td>
                <td>{{ $inclinic_data->user->phone_number }}</td>
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
            };
        @endphp

        <section class="prescription-details patient-details">
          <h1 class="patient-heading">Prescription Details</h1>
          <!-- <hr class="border-red" /> -->

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
                    <td>{{ $labtest->lab_details->TEST_NAME }}</td>
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
        <section class="contact-section-big patient-details">
          <table class="footer-table">
            <thead>
              <tr>
                <th>

                </th>
                <th>

                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><div class="footer-head">
                  <h3 class="footer-contact-head">E-MAIL</h3>
                  <div class="footer-underline"></div>
                </div></td>
                <td><div class="footer-head">
                  <h3 class="footer-contact-head">ADDRESS</h3>
                  <div class="footer-underline"></div>
                </div></td>
              </tr>
              <tr>
                <td>support@communityhealthcareclinics.com</td>
                <td style="padding-bottom: 0.5rem;">
                  Progressive Center, 4th Floor Suite # 410, Main
                  Shahrah-e-Faisal, Karachi
                </td>
              </tr>
              <tr>
                <td class="top">contact@communityhealthcareclinics.com</td>
                <td>
                  <div class="footer-head">
                    <h3 class="footer-contact-head">WEBSITE</h3>
                    <div class="footer-underline"></div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="footer-head">
                    <h3 class="footer-contact-head">PHONE</h3>
                    <div class="footer-underline"></div>
                  </div>
                </td>
                <td class="top">
                  <div class="contact-div">
                    <a
                      href="https://www.communityhealthcareclinics.com"
                      target="_blank"
                      >www.communityhealthcareclinics.com</a
                    >
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <span class="footer-highlight">
                    <a href="tel:+14076938484">+1 (407) 693-8484</a>
                  </span>
                  <span class="footer-highlight" style="margin-left: 0.5rem;">
                    <a href="https://wa.me/923372350684" target="_blank"
                      >0337-2350684</a
                    >
                  </span>
                </td>
                <td class="border-top-bottom">
                  <h3 class="doctor-name">Dr. Rama Siddiqui</h3>
                  <h5 class="qualification">M.B.B.S, B.D.S</h5>
                </td>
              </tr>
            </tbody>
          </table>
        </section>

        <section class="contact-section-small">
          <h1 class="patient-heading">CONTACT US</h1>
          <hr class="border-red" />
          <div class="email-footer-details">
            <div class="contact-div">
              <div class="footer-head">
                <h3 class="footer-contact-head">E-MAIL</h3>
                <div class="footer-underline"></div>
              </div>
              <div>
                <p>contact@communityhealthcareclinics.com</p>
                <p>support@communityhealthcareclinics.com</p>
              </div>
            </div>
            <div class="contact-div">
              <div class="footer-head">
                <h3 class="footer-contact-head">ADDRESS</h3>
                <div class="footer-underline"></div>
              </div>
              <div>
                <p>
                  Progressive Center, 4th Floor Suite # 410, Main
                  Shahrah-e-Faisal, Karachi
                </p>
              </div>
            </div>
            <div class="contact-div">
              <div class="footer-head">
                <h3 class="footer-contact-head">WEBSITE</h3>
                <div class="footer-underline"></div>
              </div>
              <a
                href="https://www.communityhealthcareclinics.com"
                target="_blank"
                >www.communityhealthcareclinics.com</a
              >
            </div>
            <div class="contact-div">
              <div class="footer-head">
                <h3 class="footer-contact-head">PHONE</h3>
                <div class="footer-underline"></div>
              </div>
              <div class="footer-highlight">
                <a href="tel:+14076938484">+1 (407) 693-8484</a>
              </div>
              <div class="footer-highlight">
                <a href="https://wa.me/923372350684" target="_blank"
                  >0337-2350684</a
                >
              </div>
            </div>
            <div class="contact-div border-top-bottom">
              <h3 class="doctor-name">Dr. Rama Siddiqui</h3>
              <h5 class="qualification">M.B.B.S, B.D.S</h5>
            </div>
          </div>
        </section>
      </main>
      <footer class="email-footer">
        <p class="email-copyright">
          Copyright &copy; 2025. Community Health Care Clinics. All Rights
          Reserved
        </p>
      </footer>
    </div>
  </body>
</html>
