<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('admin._head')

    <style>
    .page {
        background-color: white;
        border: 3px solid #a4a0a0;
        min-height: 700px;
    }

    .client-info {
        margin: 0px 2%;
        font-size: 12px;
    }

    .grey-font {
        color: #d1c8c8;
    }

    .grey-bg {
        background-color: #eee;
    }

    .black-bg {
        background-color: #000;
        color: #fff;
        font-weight: bold;
        text-align: center;
        font-size: 20px;
        padding: 8px;
    }

    .lab-use {
        padding: 3rem 8rem !important;
    }

    .big-font {
        font-size: 135px;
    }
    .medium-font{
        font-size: 95px;

    }
    .sub {
        font-size: 27px;
    }

    .border {
        border: 3px solid #000;
    }

    p {
        margin-bottom: 0px;
    }

    .small-font {
        font-size: 12px;
    }
    </style>
</head>

<body>
    <div class="page">
        <div class="row">
            <img class="col-1 offset-1" height=100" src="{{ asset('/images/email_logo.ico')}}">
            <img class="my-1 col-4 offset-2"
                src="data:image/png;base64,{{DNS1D::getBarcodePNG($order->barcode, 'C39+',1,100,array(0,0,0), true)}}"
                alt="barcode" />
            <div class="row col-4 m-0 p-0">
                <h3 class="font-weight-bold">Quest Diagnostics Incorporated</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-5">
                <p class="client-info grey-font">Client #: 97502840</p>
                <p class="client-info font-weight-bold">TEST CLIENT (HQ)</p>
                <p class="client-info">ATTN: FIRSTNAME LASTNAME</p>
                <p class="client-info">0765 BROOK AVE</p>
                <p class="client-info">CITY, CA 91304</p>
                <p class="client-info">610-555-0144</p>
            </div>
            <div class="col-3 grey-bg lab-use">
                For Lab Use
            </div>
            <div class="col-4">
                <div class="row">
                    @if($order->psc_hold)
                    <p class="medium-font">PSC Hold<span class="sub">WS</span></p>
                    @else
                    <p class="big-font">EREQ<span class="sub">WS</span></p>
                    @endif
                </div>
                <div class="row">
                    <p class="border col-11">Patient Information</p>
                    <div class="border col-11">
                        <p class="grey-font">{{$order->last_name.", ".$order->name}}</p>
                        <p>{{$order->office_address}}</p>
                        <p>{{$order->city.", ".$order->state.' '.$order->zip_code}}</p>
                        <p>{{$order->phone_number}}</p>
                    </div>
                </div>
            </div>
            <div class="row col-12 m-0 p-0">
                <div class="border col-6 row p-0 m-0">
                    <p class="col-6">Collection Date: {{$order->collect_date}}</p>
                    <p class="col-6">Time: {{$order->collect_time}}</p>
                    <p class="col-4">Urine Volume:</p>
                    <p class="col-4">Hours:</p>
                    <p class="col-4">{{$order->comment}}</p>
                    <p class="col-12">Lab Reference ID: {{$order->lab_reference_num}}</p>
                </div>
                <div class="border p-0 row m-0" style="width:719px">
                    <p class="col-6">Pat ID #: {{$order->umd_patient_id}}</p>
                    <p class="col-6">SSN: {{$order->ssn}}</p>
                    <p class="col-3">DOB: {{$order->date_of_birth}}</p>
                    <p class="col-3">Sex: @if($order->gender=='female')F @elseif($order->gender=='male')M @else O @endif
                    </p>
                    <p class="col-6">Room/Loc: {{$order->room}}</p>
                    <p class="col-12">Result Notification: {{$order->result_notification}}</p>
                </div>
            </div>
            <div class="row col-12 m-0 p-0">
                <div class="border col-6 row p-0 m-0">
                    <p class="col-12 mt-3">UPIN: {{$order->upin.' '.$order->ref_physician_id}}</p>
                    <p class="col-12">Refered Physician ID: {{$order->ref_physician_id}}</p>
                    <p class="col-12 mt-4">TMP - {{$order->temp}}</p>
                </div>
                <div class="border row p-0 m-0" style="width:719px">
                    <p class="col-6">Responsible Party: </p>
                    <p class="col-6">Bill Type: {{$order->billing_type}}</p>
                    <p class="col-6 grey-font">{{$order->name.' '.$order->last_name}}</p>
                    <p class="col-6">Phone: {{$order->phone_number}}</p>
                    <p class="col-12">{{$order->office_address}}</p>
                    <p class="col-12">Primary Carrier: None</p>
                    <p class="col-6">Insurance #: {{$order->insurance_num}}</p>
                    <p class="col-6">SSN: {{$order->ssn}}</p>
                    <p class="col-6">Group #: {{$order->group_num}}</p>
                    <p class="col-6">Relationship: {{$order->relation}}</p>
                    <p class="col-6">DOB: {{$order->date_of_birth}}</p>
                    <p class="col-6">Sex: @if($order->gender=='female')F @elseif($order->gender=='male')M @else O @endif
                    </p>

                </div>
            </div>
            <div class="row col-12">
                <div class="border col-12 m-0 pr-0" style="max-width:1481px​ !important;">
                    <p class="col-6">ICD Diagnosis Code(s): {{$order->icd_diagnosis_code}}</p>
                </div>
            </div>
            <div class="col-12 mt-3">
                <p class="black-bg col-12">Profiles/Tests</p>
                @php $arr=json_decode($order->names); @endphp
                @foreach($arr as $name)
                <p class="px-4">
                    {{$name}}
                </p><br>
                @endforeach
            </div>
            <p class="col-12" style="text-align:center">End of Requisition: 4025250</p>

        </div>
        @include('admin._script')
    </div>
</body>

</html>
