<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../">
    <meta charset="utf-8" />
    <title>Provider Contract</title>
    <meta name="description" content="Provider Contract" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="asset_contract/plugins/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="asset_contract/plugins/prismjs.bundle.css" rel="stylesheet" type="text/css" />
    <link href="asset_contract/plugins/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="asset_contract/plugins/base_light.css" rel="stylesheet" type="text/css" />
    <link href="asset_contract/plugins/menu_light.css" rel="stylesheet" type="text/css" />
    <link href="asset_contract/plugins/brand_dark.css" rel="stylesheet" type="text/css" />
    <link href="asset_contract/plugins/aside_dark.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('asset_contract/css/provider_contract.css')}}">

    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="asset_contract/media/logos/favicon.ico" />
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body"
    class="print-content-only header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">

    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <div class="card card-custom contractOverflow">
                <div class="card-body p-0">
                    <!-- begin: Invoice-->
                    <!-- begin: Invoice header-->
                    <div class="row justify-content-center bgi-size-cover bgi-no-repeat py-8 px-8 py-md-27 px-md-0" style="background-color: #08295a; height:150px">
                        <div class="col-md-9">
                            <div class="d-flex justify-content-between pb-10 pb-md-20 flex-column flex-md-row">
                                <h1 class="display-4 text-white font-weight-boldest mb-10">Provider's Contract</h1>
                                <div class="d-flex flex-column align-items-md-end px-0">
                                    <!--begin::Logo-->
                                    <!-- <a href="#" class="mb-5">
                                        <img src="asset_contract/media/logos/logo-light.png" alt="" />
                                    </a> -->
                                    <!--end::Logo-->
                                    <span class="text-white d-flex flex-column align-items-md-end">
                                        <span>625 School House Road #2,</span>
                                        <span>Lakeland, FL 33813</span>
                                    </span>
                                </div>
                            </div>
                            <div class="border-bottom w-100 opacity-20"></div>
                            <!-- <div class="d-flex justify-content-between text-white pt-6">
                                <div class="d-flex flex-column flex-root">
                                    <span class="font-weight-bolde mb-2r">DATA</span>
                                    <span class="opacity-70">Dec 12, 2017</span>
                                </div>
                                <div class="d-flex flex-column flex-root">
                                    <span class="font-weight-bolder mb-2">INVOICE NO.</span>
                                    <span class="opacity-70">GS 000014</span>
                                </div>
                                <div class="d-flex flex-column flex-root">
                                    <span class="font-weight-bolder mb-2">INVOICE TO.</span>
                                    <span class="opacity-70">Iris Watson, P.O. Box 283 8562 Fusce RD.
                                        <br />Fredrick Nebraska 20620</span>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <!-- end: Invoice header-->
                    <!-- begin: Invoice body-->
                    <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0 contractWindow">
                        <div class="col-md-9">
                            <p>PARTICIPATING PROVIDER AGREEMENT</p>
                            <p>This Participating Provider Agreement (the “Agreement”) is made as of <b><u>
                                        <?php
                                    $date = str_replace('-', '/', $contract->date);
                                    $newd_o_b = date('m/d/Y', strtotime($date));
                                    ?>
                                        {{$newd_o_b}}
                                    </u>
                                </b> (the &ldquo;<u>Effective Date</u>&rdquo;), by and between Community Healthcare Clinics, LLC, a Missouri professional limited
liability company (“Community”), and <strong>{{$contract->provider_name}}</strong> (“Provider”), and if Provider is a legal entity
comprised of physicians and/or other qualified practitioners (each, an “Eligible     Professional” and
collectively, “Eligible     Professionals”), by and on behalf of each individual Eligible Professional of
Provider.  Community and Provider may be referred to herein individually as a “Party” or collectively, as the
“Parties.”  Any reference to Provider shall be read to include reference to its Eligible Professionals, as
applicable, whether specifically stated to include Eligible Professionals or not.  All capitalized terms used,
but not otherwise defined in the Agreement (including Addendum A) shall have the meaning assigned to
them in the Participating Provider Manual.
                            </p>
                            <p>RECITALS</p>
                            <p><strong>Whereas</strong>, Community is establishing a network of contracted Participating Providers, whose
primary purpose is arranging the provision of telehealth medical care to Members by entering into
relationships with such Members to arrange for Contracted Services through its Participating Providers;
and </p>
                            <p><strong>Whereas</strong>, the Parties desire to enter into this Agreement, whereby Community will arrange for
Provider to render Contracted Services to its Members.&nbsp;
                            </p>
                            <p><strong>In Witness Whereof</strong>, the Parties hereby execute this Agreement as of the Effective Date
with the intent to be bound by the terms stated below.</p>
                            <table>
                                <tbody>
                                    <tr>
                                        <td width="301">
                                            <p><strong>Community</strong>:</p>
                                            <p>Community Healthcare Clinics, LLC </p>
                                            <img src="{{$contract->admin_sign}}" style="height: 105px;">

                                            <p>By:<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </u></p>
                                            <p>Name:Mubeen Mahmood</p>
                                            <p>Title:President</p>
                                        </td>
                                        <td width="42">
                                            <p><strong>&nbsp;</strong></p>
                                        </td>
                                        <td width="295">
                                            <p><strong>PROVIDER</strong>:</p>
                                            <img src="{{$contract->signature}}" style="height: 90px; width:230px; object-fit:contain;">
                                            <!-- <p>&nbsp;</p> -->
                                            <p><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </u></p>
                                            <p>{{$contract->provider_name}}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p>&nbsp;</p>
                            <p><strong>&nbsp;</strong></p>
                            <p>AGREEMENT</p>
                            <p><strong>Now, Therefore, </strong>in consideration of the foregoing Recitals and the covenants contained
herein, the Parties hereby agree as follows: </p>
                            <ol>
                                <li><u>Community OBLIGATIONS</u>
                                    <ul>
                                        <li><strong>Platform</strong>. Community may arrange access to, and use of, a Platform to support the
arranging of Contracted Services, subject to any applicable terms of use and the Community Provider
Manual. </li>
                                        <li><strong>Participating Provider Qualification Standards</strong>. Community shall establish and maintain
Qualification Standards that adhere to current applicable requirements regarding the credentialing and re-
credentialing of Participating Providers.  The Qualification Standards may be modified from time to time,
in the sole discretion of Community. Provider agrees that Provider’s credentialing records may be reviewed
by authorized representatives.</li>
                                    </ul>
                                </li>
                                <li><u>Provider Obligations</u>
                                    <ul>
                                        <li><strong>Compliance with Community      Provider Manual</strong>. Provider shall comply with the
Community Provider Manual, and Requirements, any or all of which may be amended from time to time by
Community in its sole discretion.  The terms and conditions set forth in the Participating Provider Manual
are hereby incorporated into this Agreement.  If there is a conflict between this Agreement and the
Community Provider Manual, the Community Provider Manual shall control. </li>
                                        <li><strong><u>Platform</u></strong>. Provider agrees to utilize the Platform in accordance with the Terms of Use as
set forth in Addendum B.  </li>
                                        <li><strong>Provision of Services</strong>. Provider agrees to render Contracted Services within the scope of
the licensing, training, experience, and qualification of Provider consistent with accepted standards of
medical and other healthcare practice, and in accordance with the Community Provider Manual. </li>


                                        <li><strong>Non‑Discrimination</strong>. Provider shall not discriminate against any Member whether on the
basis of the Member’s age, sex, gender identity, race, color, religious beliefs, ancestry, national origin,
disability, health status, source of payment, utilization of health services or supplies, or any other
unlawful basis. </li>
                                        <li><strong>Insurance</strong>. Provider agrees to maintain during the term of this Agreement, at Provider’s
sole cost and expense, policies of general liability, professional liability, and other insurance as shall be
necessary to adequately insure Provider and its agents against any claim or claims for damages arising by
reason of property damage, personal injury, or death occasioned directly or indirectly in connection with
the performance of any procedure or service provided hereunder, the use of any property and facilities
provided by Provider, and activities performed by Provider and its agents in connection with this
Agreement.  Such policies shall provide coverage in the amounts acceptable to Community, but in no event
shall professional liability insurance be less than One Million Dollars ($1,000,000) per claim and Three
Million Dollars ($3,000,000) annually in the aggregate, or any such minimum coverage as may be
required by applicable State law.  Such policies may be increased or decreased by Community in its
reasonable judgment and consistent with industry standards.  Provider shall require that its professional liability insurance name Community as an additional insured on such policy and a party entitled to thirty
(30) days’ prior written notice of an intent to cancel or terminate such insurance.
                                        </li>
                                        <li><strong>Notice of Adverse Action</strong>. Provider shall notify Community in writing within fortyeight
(48) hours, or such lesser period of time as required by applicable State or federal law, of receiving any
written or oral notice of any adverse action (“Adverse    Action”) pertaining to Provider, including, without
limitation, those Adverse Actions set forth in the Community Provider Manual. </li>
                                        <li><strong>Provider Qualification</strong>. Provider shall submit to Community in a timely manner the
Participating Provider Application, as modified from time to time by Community, the current form of which
will be provided by Community upon request.  Provider shall be responsible for completing the Participating
Provider Application in its entirety, in accordance with the Requirements, and the State. Provider
authorizes Community to verify any and all credentialing information as well as licensure, accreditations,
services and programs offered, specialties, levels of malpractice insurance and experience.  Provider
agrees to cooperate with a periodic re-evaluation of its credentials conducted by Community. Provider
acknowledges and agrees that credentialing and appointment to Community, and reappointment to Community
upon recredentialing, is necessary for this Agreement to be effective. The submitted Participating
Provider Application shall be deemed a part of this Agreement.  In no event shall this Agreement become
effective, nor shall Provider begin performing his, her, or its obligations under this Agreement, until the
Participating Provider Application has been accepted and approved in writing by Community. </li>
<li><strong>Confidentiality.</strong> Provider acknowledges that Community, in connection with its business,
has developed and will develop certain designs, contracts, procedures, protocols, processes, records, and
files respecting any customer or services provided to any customer, provider lists, fee schedules,
compensation data, vendor price lists, outside provider information, provider contracting information,
documentation relating to the provision of services hereunder, and other copyrighted, patented,
trademarked, or other legally protectable information that is confidential and proprietary to Community. At
the outset and during the Term of this Agreement, Provider will gain access to the above, trade secrets, or
other confidential or privileged information (collectively “Confidential     Information”) regarding
Community’s customers or business activities.  Provider shall not to use any such Confidential Information
during the Term of this Agreement or thereafter except in furtherance of Provider’s obligations under this
Agreement as contemplated herein, without the prior written consent of Community. During the Term of
this Agreement and forever thereafter, Provider will not release, disclose, or disseminate any Confidential
Information of Community to any other person or entity except as medically necessary, upon the prior
written authorization of Community, or as otherwise required by federal or State law. Upon termination of
this Agreement, Provider will not use Community’s name, address, or telephone number for any
inappropriate purpose and will promptly return any Confidential Information in Provider’s possession or
control to Community.</li>
<li><strong>Non-solicitation.</strong> Provider agrees and covenants that during the Term of this Agreement
(including any extensions hereof) and for a period of eighteen (18) months after the effective date of
termination of this Agreement, Provider will not, either (i) directly as a partner, employer, agent,
independent contractor, or employee, or (ii) indirectly through a corporation, partnership, affiliate,
subsidiary, or otherwise, unless approved by Community:
<p>(i)&nbsp; &nbsp; &nbsp;solicit,&nbsp;induce,&nbsp;or&nbsp;attempt&nbsp;to&nbsp;induce,&nbsp;in&nbsp;connection&nbsp;with&nbsp;any&nbsp;business&nbsp;competitive&nbsp;with&nbsp;that&nbsp;of&nbsp;Community,&nbsp;patients&nbsp;of&nbsp;any&nbsp;physician&nbsp;associated&nbsp;with&nbsp;Community&nbsp;to&nbsp;leave&nbsp;the&nbsp;care&nbsp;of&nbsp;physicians&nbsp;associated&nbsp;with&nbsp;Community;&nbsp;or&nbsp;</p>

<p>(ii)&nbsp; &nbsp; solicit,&nbsp;induce,&nbsp;or&nbsp;attempt&nbsp;to&nbsp;induce,&nbsp;any&nbsp;employee,&nbsp;consultant,&nbsp;or&nbsp;other&nbsp;persons&nbsp;associated&nbsp;with&nbsp;Community&nbsp;to&nbsp;leave&nbsp;the&nbsp;employment&nbsp;of,&nbsp;or&nbsp;to&nbsp;discontinue&nbsp;their&nbsp;engagement&nbsp;with&nbsp;Community&nbsp;or&nbsp;any&nbsp;affiliate&nbsp;thereof.&nbsp;</p>

<p>Nothing&nbsp;contained&nbsp;herein&nbsp;will&nbsp;prevent&nbsp;any&nbsp;customer&nbsp;from&nbsp;selecting&nbsp;Provider&nbsp;at&nbsp;the&nbsp;customer&rsquo;s&nbsp;unfettered&nbsp;and&nbsp;sole&nbsp;discretion&nbsp;to&nbsp;provide&nbsp;medical&nbsp;services&nbsp;to&nbsp;the&nbsp;patient.&nbsp;&nbsp;In&nbsp;addition,&nbsp;nothing&nbsp;set&nbsp;forth&nbsp;herein&nbsp;will&nbsp;be&nbsp;construed&nbsp;to&nbsp;prevent&nbsp;Provider&nbsp;from&nbsp;complying&nbsp;with&nbsp;the&nbsp;rules&nbsp;of&nbsp;the&nbsp;Medical&nbsp;Board(s)&nbsp;under&nbsp;which&nbsp;such&nbsp;Provider&nbsp;is&nbsp;licensed&nbsp;requiring&nbsp;Provider&nbsp;to&nbsp;provide&nbsp;notification&nbsp;of&nbsp;discontinuation&nbsp;of&nbsp;practice.&nbsp;</p>

</li>


<li><strong> Agreement    Not    To    Compete</strong>. In consideration of the mutual promises contained herein
including, but not limited to, access to Community’s Confidential Information, Provider agrees that, during
the Term of this Agreement, Provider will not, without the prior written approval of Community’s Board of
Managers, directly or indirectly own, or have any financial interest in, any other entity that provides
telehealth platform services to Community’s customers.  In its discretion, Community will have the right to
unilaterally waive or modify all or any portion of any promise, agreement, or covenant in this Section
2.10, provided that any such waiver or modification does not impose greater restrictions, obligations, or
duties on Provider including, but not limited to, reducing the time, geographical, and/or scope of activity
limitations in this Section    2.10.  Any such waiver or modification must be in writing and signed by
Community. </li>





                                    </ul>
                                </li>
                            </ol>
                            <ul>
                                <li><u>P</u><u>rovision of Excluded Services</u></li>
                            </ul>
                            <p>Community is not responsible for Provider’s provision of Excluded Services.  Prior to providing any
Excluded Service to a Member, Provider shall comply with the requirements of the Community Provider
Manual. </p>
                            <ol>
                                <li><u>Compensation and Billing Procedures</u>
                                    <ul>
                                        <li><strong>Compensation Rates</strong>.  Provider shall accept as compensation the amounts as set forth in
Exhibit    A as payment in full for Contracted Services (including payment for any and all taxes) provided
through the Platform.
                                        </li>
                                        <li><strong>Billing and Collection</strong>. Provider shall comply with applicable Community Policies and
Requirements related to billing and collection.  Community shall have the right to offset against any sums to
be paid to Provider any amounts owed to Community (as applicable) by Provider, including, but not limited
to, overpayments or erroneous payments made to Provider. </li>
                                        <li><strong>Member Held Harmless</strong>. When required under Applicable Law, Provider agrees that in
no event shall Provider bill, charge, collect a deposit from, seek compensation, remuneration, or
reimbursement from, or have any recourse against Members or persons for Contracted Services provided
pursuant to this Agreement. </li>
                                    </ul>
                                </li>
                                <li><u>Relationship between Provider and Community</u></li>
                            </ol>
                            <p>Community and Provider are independent entities.  Nothing in this Agreement shall be construed or
be deemed to create a relationship of employer and employee, or principal and agent, or any relationship
other than that of independent entities contracting with each other solely for the purpose of carrying out
the terms and conditions of this Agreement.  Neither Party shall have any express or implied right or
authority to assume or create any obligation or responsibility on behalf of or in the name of the other
Party, except as expressly set forth herein.  </p>
                            <ol>
                                <li><u>Records, Audits, and Regulatory Requirements</u>
                                    <ul>
                                        <li><strong>Maintenance of Records</strong>.Provider shall maintain such financial, administrative, and
other records as may be necessary for compliance with Applicable Law by Community.  Provider agrees to
maintain the confidentiality of all information related to fees, charges, expenses, and utilization derived
from, through, or provided by Community.  Provider shall maintain appropriate health records for each
Member for which it provides Contracted Services to, including (without limitation) the recording of
Provider’s services and such other records as may be required by Applicable Law.  Such records shall be
maintained in accordance with the Requirements established by Community as well as Applicable Law.  All
such health records shall be treated as confidential, so as to comply with Applicable Law regarding the
confidentiality of patient records. </li>
                                        <li><strong>Access to Records</strong>. Subject to applicable State or federal confidentiality laws, Community
shall have the right to inspect, review, and make copies of records referenced in Section  6.1.  When
requested by Community or authorized representatives of local, State or federal regulatory agencies,
Provider shall provide copies of any such records in accordance with the Community Provider Manual. </li>
                                        <li><strong>Protected Health Information (&ldquo;PHI&rdquo;)</strong>. If either Party receives any PHI from the other
Party, or creates or receives any PHI in the course of its performance under this Agreement, such Party
shall maintain the security and confidentiality of such PHI in accordance with Applicable Law, including
the Health Insurance Portability and Accountability Act, the regulations promulgated thereunder, and
applicable State law. </li>
                                    </ul>
                                </li>
                            </ol>
                            <ul>
                                <li><u>Use of Name</u></li>
                            </ul>
                            <p>Neither Party shall use the other Party’s trademarks, name, or symbols without such other Party’s
express permission; provided, however, that Community may use the name(s), office address(es), telephone
number(s), specialty(ies), and a factual description of Provider’s practice(s) in directories and other
promotional materials. </p>
                            <ul>
                                <li><u>Dispute Settlement; Arbitration</u></li>
                            </ul>
                            <p>All disputes or controversies arising under or related to this Agreement between Community and
Provider shall be subject to binding confidential arbitration, and the dispute policy set forth in the
Community Provider Manual. </p>
                            <ol>
                                <li><u>Term and Termination</u>
                                    <ul>
                                        <li><strong>Term</strong>. The term of this Agreement shall commence on the Effective Date and shall
continue for a period of one (1) year.  This Agreement shall automatically renew for successive one (1) year periods on each anniversary of the Effective Date (each, a “Renewal    Date”), unless one Party notifies
the other in writing of its intent to terminate in accordance with this Agreement at least ninety (90) days
prior to any Renewal Date. </li>
                                        <li><strong>Termination Due to Material Breach</strong>. In the event that either Provider or Community
fails to cure a material breach of this Agreement (other than those listed in the Community Provider
Manual) within thirty (30) days of receipt of written notice of such breach from the nonbreaching Party,
the nonbreaching Party may terminate this Agreement, effective as of the expiration of said thirty (30)
day period.  If the breach is cured within such thirty (30) day period, or if the breach is one which cannot
reasonably be corrected within thirty (30) days, and the breaching Party makes substantial and diligent
progress toward correction during such thirty (30) day period, then this Agreement shall remain in full
force and effect; provided, however, that if such material breach is not cured within sixty (60) days of
receipt of written notice, this Agreement shall be automatically terminated.
                                        </li>
                                        <li><strong>Termination without Cause</strong>.
                                            <ul>
                                                <li><u>By Community</u>. Following twelve (12) months from the Effective Date, Provider
may terminate this Agreement without cause upon ninety (90) days’ prior written notice to
Community, unless another notice period is otherwise agreed to in writing by Community. </li>
                                                <li><u>By Network</u>. This Agreement may be terminated without cause by Community
upon ninety (90) days’ prior written notice to Provider, unless another notice period is otherwise
agreed to in writing by Provider. </li>
                                                <li><u>By Mutual Agreement</u><strong>.</strong>  The Parties may terminate this Agreement by the mutual
agreement, in writing, signed by the Parties, with such termination being effective on a mutually
agreed upon date stated in such writing. </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ol>
                            <ol>
                                <li><u>Miscellaneous</u>
                                    <ul>
                                        <li><strong>Entire Agreement</strong>. This Agreement constitutes the sole and only agreement of the
Parties with respect to the subject matter hereof and supersedes any and all prior agreements or
understandings between the Parties with respect to the subject matter hereof, and no other agreement,
statement, or promise relating to the subject matter of this Agreement that is not contained or referenced
herein shall be valid or binding.  The above notwithstanding, Community shall not be prohibited from
pursuing claims against Provider pursuant to a prior agreement between the Parties. </li>
                                        <li><strong>Amendments</strong>. Any amendment to this Agreement, including its Addenda and
Attachments, proposed by Community shall be effective thirty (30) days after Community has delivered
written notice to Provider of such amendment if Provider has failed within that time period to notify
Community in writing of Provider’s rejection of the amendment.  Amendments required because of
legislative, regulatory, or legal requirements do not require the consent of Provider or Community and will
be effective immediately on the effective date stated in such an amendment.  Further, Community may
amend the Community Provider Manual in any manner without notice to Provider unless Community
determines, in its sole discretion, that such amendment will materially affect Provider’s performance of its
duties under this Agreement. </li>
                                        <li><strong>Severability</strong>. If any one (1) or more of the provisions contained in this Agreement shall
for any reason be held to be invalid, illegal, or unenforceable in any respect, such invalidity, illegality, or
unenforceability shall not affect any other provision hereof, and this Agreement shall be construed as if such invalid, illegal, or unenforceable provision had never been contained herein. </li>
                                        <li><strong>Notices</strong>. All notices and other communications required or permitted under this
Agreement:  (i) must be in writing; (ii) shall be deemed to be duly given, (a) when delivered personally to
the recipient, (b) when transmitted by electronic mail to the recipient, immediately upon sending, or (c)
one (1) business day after being sent to the recipient by nationally recognized overnight private carrier
(charges prepaid); and (iii) shall be addressed as follows (as applicable): </li>
                                    </ul>
                                </li>
                            </ol>
                            <table width="598">
                                <tbody>
                                    <tr>
                                        <td width="299">
                                            <p><u>If to Community</u>:</p>
                                            <p>Community Healthcare Clinics, LLC</p>
                                            <p>Attention: +1 (407) 693-8484</p>
                                            <p>625 School House Road #2,</p>
                                            <p>Lakeland, FL 33813</p>
                                        </td>
                                        <td width="299">
                                            <p><u>If to Provider</u>:</p>
                                            <p><b>{{$contract->provider_name}}</b></p>
                                            <p><b>{{$contract->provider_address}}</b></p>
                                            <p>Email: <b>{{$contract->provider_email_address}}</b></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p>or to such other respective address as each Party may designate by notice given in accordance with this
Section  10.4. </p>
                            <ul>
                                <li><strong>No Third‑Party Beneficiary</strong>. Nothing in this Agreement is intended to, or shall be
deemed or construed to, create any rights or remedies in any third party. </li>
                                <li><strong>Headings</strong>. The headings contained in this Agreement are for the convenience of the
Parties only and shall not be deemed to affect the meaning of the provisions hereof.</li>
                                <li><strong>No Waiver of Rights</strong>. The failure of either Party to insist upon the strict observation or
performance of any provision of this Agreement or to exercise any right or remedy shall not impair or
waive any such right or remedy.  Every right and remedy given by this Agreement to the Parties may be
exercised from time to time and as often as appropriate. </li>
                                <li><strong>Electronic Signature</strong>. This Agreement may be executed in one or more counterparts.
All counterparts so executed shall be deemed an original and constitute one and the same agreement,
binding upon all Parties Documents scanned or otherwise transmitted electronically and electronic
signatures shall be deemed original signatures for purposes of this Agreement and all matters related
thereto, with such scanned and electronic signatures having the same legal effect as original signatures.</li>
                            </ul>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <p>Addendum&nbsp;A:&nbsp; Definitions</p>
                            <p>Addendum B: Platform Terms and Conditions</p>
                            <p>Addendum&nbsp;C:&nbsp; Contracted Services</p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <p>ADDENDUM&nbsp;A</p>
                            <p>Definitions</p>
                            <p>Unless otherwise defined within this Agreement, the following terms, and as appropriate, the
singular or plural of each, shall have the following meanings: </p>

                            <p><u>Applicable Law.</u>&nbsp; All local, State, and federal laws, rules, and regulations applicable to Provider
by federal and other governmental and regulatory bodies having jurisdiction over Provider. </p>
                            <p><u>Contracted Services.</u>&nbsp; The Contracted Services listed in Addendum D hereto to be rendered by
Provider to a Member in accordance with this Agreement, and as may be updated by Community from time
to time. </p>
                            <p><u>Excluded     Services.</u>&nbsp; Those health care services and supplies that are determined not to be
Contracted Services. </p>

                            <p><u>Member.</u>&nbsp;  A person who is eligible to receive Contracted Services, as may be amended from time
to time in accordance with this Agreement. </p>
                            <p><u>Participating    Provider.</u>&nbsp; A hospital, physician, physician organization, health care practitioner, or
other organization that has a direct or indirect contractual relationship with another participating provider
to provide health care services through the Platform. </p>

                            <p><u>Participating     Provider     Application.</u>&nbsp; The form used by Community for the qualification of
Participating Providers, which may be amended from time to time as Community deems appropriate. </p>
                            <p><u>Physician     Fee     Schedule.</u>&nbsp; The policies and procedures which describe the manner, terms, and
conditions under which Provider will be compensated by Community for Contracted Services provided to
Members. </p>
                            <p><u>Platform.</u>&nbsp; All technology services that may be created and/or offered by Community for Provider’s
use in the provision of Contracted Services. </p>
                            <p><u>Qualification    Standards.</u>&nbsp; The written standards and procedures adopted by Community for the
credentialing of Participating Providers.</p>
                            <p><u>Requirements.</u>&nbsp; The rules, procedures, policies, protocols, and other conditions to be followed by
Participating Providers with respect to providing Contracted Services.
                            </p>
                            <p><u>State.</u>&nbsp; The state in which the Member receiving the Contracted Services is physically located at
the time such Contracted Services are received, unless Applicable Law in the state where the Member is
physically located at such time provides otherwise.
                            </p>
                            <p><u>Community     Policies.</u>&nbsp; All policies, procedures, standards, programs, criteria, and requirements
developed by Community, as modified from time to time at Community’s sole discretion, including but not
limited to those related to the provision of Contracted Services and use of the Platform.
                            </p>
                            <p><u>Community    Provider    Manual.</u>&nbsp; Those Community Policies, procedures, and guidelines (as may be
supplemented from time to time) in writing or available on the Community’s website.
                            </p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <p>ADDENDUM&nbsp;B</p>
                            <p>Platform Terms of Use</p>
                            <p><a href="{{url('terms_of_use')}}">Terms of use</a></p>
                            <p><strong><u>&nbsp;</u></strong></p>
                            <p>ADDENDUM C</p>
                            <p>Contracted Services</p>
                            <p>
                                Provider agrees to provide professional telehealth medical services to Members, specifically in
the specialty of <u><b>{{$contract->provider_speciality}}</b></u>, which shall be scheduled using the Community online platform.
</p>
                            <p>&nbsp;</p>
                            <p>ADDENDUM D</p>
                            <p>Compensation</p>
                            <p class="HBbj"><span style="font-size: 11.0pt;">Provider’s compensation shall be equal to <b>{{$contract->session_percentage}}%</b> of the fees collected for Contracted Services rendered by
Provider.</span></p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                        </div>
                    </div>
                    <!-- begin: Invoice action-->
                    <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
                        <div class="col-md-9">
                            <form action="{{route('sign_contract',$contract->slug)}}" method="post">
                                @csrf
                                <div class="sticky">
                                <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-light-primary font-weight-bold "
                                            onclick="window.print();">Print Contract</button>
                                        <button type="submit" class="btn btn-primary font-weight-bold">I Agree</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end: Invoice action-->

                </div>
            </div>
            <!-- end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->

    </div>
    <!--end::Demo Panel-->
    <script>
    // var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";
    </script>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
    var KTAppSettings = {
        "breakpoints": {
            "sm": 576,
            "md": 768,
            "lg": 992,
            "xl": 1200,
            "xxl": 1400
        },
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#3699FF",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#E4E6EF",
                    "dark": "#181C32"
                },
                "light": {
                    "white": "#ffffff",
                    "primary": "#E1F0FF",
                    "secondary": "#EBEDF3",
                    "success": "#C9F7F5",
                    "info": "#EEE5FF",
                    "warning": "#FFF4DE",
                    "danger": "#FFE2E5",
                    "light": "#F3F6F9",
                    "dark": "#D6D6E0"
                },
                "inverse": {
                    "white": "#ffffff",
                    "primary": "#ffffff",
                    "secondary": "#3F4254",
                    "success": "#ffffff",
                    "info": "#ffffff",
                    "warning": "#ffffff",
                    "danger": "#ffffff",
                    "light": "#464E5F",
                    "dark": "#ffffff"
                }
            },
            "gray": {
                "gray-100": "#F3F6F9",
                "gray-200": "#EBEDF3",
                "gray-300": "#E4E6EF",
                "gray-400": "#D1D3E0",
                "gray-500": "#B5B5C3",
                "gray-600": "#7E8299",
                "gray-700": "#5E6278",
                "gray-800": "#3F4254",
                "gray-900": "#181C32"
            }
        },
        "font-family": "Poppins"
    };
    </script>
    <!--end::Global Config-->
    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="asset_contract/plugins/plugins.bundle.js"></script>
    <script src="asset_contract/plugins/prismjs.bundle.js"></script>
    <script src="asset_contract/plugins/scripts.bundle.js"></script>
    <!--end::Global Theme Bundle-->
</body>
<!--end::Body-->

</html>
