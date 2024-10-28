<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style>
body {
    padding: 0px;
    margin: 0px;
    width: 700px;
    height: 175px;
    border: 2px solid black;
}

html {
    -webkit-print-color-adjust: exact;
}

.flexrow {
    clear: both;
}

table {
    /* border: 1px solidblack; */
    border-collapse: collapse;
    display: flex;
    justify-content: center;
    align-items: center;
}

th {
    border: 4px solid rgb(33, 32, 32);
    border-collapse: collapse;

}

td {
    border: 1px solidblack;
    padding: 8px;
    border-collapse: collapse;

}

h1 {
    font-size: 30px;
}

.main-head {
    font-weight: 700;
    display: flex;
    justify-content: flex-start;
}

.align {
    width: 250px;
}

.text-border {
    border-bottom: 2px solid black;
}

.text-border1 {
    border-bottom: 2px solid black;
    width: 280px;
}

.text-border2 {
    border-bottom: 2px solid black;
    width: 90px;
}

.text-border4 {
    border-bottom: 2px solid black;
    width: 200px;
}

.text-border3 {
    border-bottom: 2px solid black;
    width: 130px;
}

.square {
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 3px;
    width: 20px;
    height: 20px;
    border-radius: 100%;
    transform: translateX(-13px);
}

.width {
    width: 50%;
}

.width-40 {
    width: 40%;
}

.width-10 {
    width: 10%;
}

.width-90 {
    width: 90%;
}

.width-80 {
    width: 80%;
}

.width-20 {
    width: 20%;
}

.width-30 {
    width: 30%;
}

.width-60 {
    width: 60%;
}

.height {
    height: 100vh;
}

.height-13 {
    height: 13vh;

}

.para {
    font-size: 15px;
    text-align: justify;
}

label {
    font-size: 13px;
    padding-left: 8px;
}

.head {
    font-weight: 700;
    font-size: 14px;
}

.style-right {
    height: 50px;
    width: 50px;
    border-bottom: 5px solid black;
    border-left: 5px solid black;
}

.style-left {
    height: 50px;
    width: 50px;
    border-top: 5px solid black;
    border-right: 5px solid black;
    display: flex;
    justify-content: flex-end;

}

.forms-heading {
    margin: 0px;
    font-weight: 400;
    border-top: 2px solid rgb(33, 32, 32);
    border-bottom: 1px solid rgb(33, 32, 32);
    padding: 4px;
}

.div-border {
    border-right: 1px solid black;
    border-top: 1px solid black;
    padding-top: 5px;
    padding-bottom: 5px;
}

.div-border1 {
    border-left: 1px solid black;
    border-right: 1px solid black;
}

input {
    border: none;
    font-size: 13px;

}

input:focus {
    outline: none !important;
}

.main-cont {

    width: 100%;
    height: 100%;
    display: flex;
}

.left-cont,
.right-cont {
    width: 49%;
    height: 100%;
    padding: 1px 4px;

}

/* .left-cont {
    border-left: 1px solid black;
    display: flex;
    flex-direction: column;

    }

    .left-cont div {
        border-bottom: 1px solid black;

    } */

.left-last-cont {
    /* height: 40% !important; */
    border-bottom: none !important;
    text-align: justify !important;
    font-size: 11px;
    border-right: 1px solid black;
    border-top: 1px solid black;

    /* padding: 3px; */
}

.left-second-last {
    display: flex;
    clear: both;
    height: 30px;
}

.left-second-last div {
    height: 15px;
    width: 50%;
}

.left-second-last .one {
    border-right: 2px solid black;
}

.right-cont {
    border-left: 3px solid black;
    border-right: 1px solid black;
    border-top: 1px solid black;
    /* border-bottom: 2px solid black; */
    margin-top: 2px;
    margin-bottom: 0px;
    height: 297px;
}

.line {
    line-height: 0.5;
}

.sub-btn {
    background-color: #08295a;
    color: white;
    letter-spacing: 1px;
    text-transform: uppercase !important;
    font-size: 18px;
    font-weight: bold;

}

textarea {
    width: 98%;
    border-left: none;
    border-right: none;
    border-top: none;
    margin-bottom: 15px;
}

.notes {
    padding: 16px;
}

.refill {
    margin-left: 16px;
}

.float-left {
    float: left;
}

.float-right {
    float: right;
}

.data {
    font-size: 13px;
    padding-left: 8px;
}
</style>
<h3 style="text-align:center">Umbrella Health Care Systems</h3>
<span style="font-weight: 700;display: flex;padding-left:8px;">NEW PRESCRIPTION PHYSICIAN FAX ORDER
    FORM</span>
<p style=" font-size: 12px;text-align: justify;padding:8px;">
    Use this form to order a new mail service prescription by fax from
    the prescribing physician’s office. Member completes section 1,
    while the physician completes sections 2 and 3.
    <b>
        This fax is void unless received directly from physician’s office.
        To contact UHCS, physicians may call 1-800-791-7658.</b>
</p>
<!-- ****************************** First Section  ******************************** -->
<div class="forms-heading flexrow justify-content-justify">
    <span>Patient information — to be completed by member</span>
</div>
<form action="">
    <div style="display:flex">
        <div class="div-border width-40 float-left">
            <label for="">Name</label>
            <span class="data">{{$first_name}}</span>
        </div>

        <div class="div-border width-20 border-left float-left">
            <label for="">MI</label>
            <span class="data">-</span>
        </div>
        <div class="div-border width-20 border-left float-left">
            <label for="">Order # </label>
            <span class="data">{{$order_main_id}}</span>
        </div>
    </div>
    <br />
    <!-- 3rd flexrow -->
    <div style="display:flex;clear:both">
        <div class="div-border width-20 border-left float-left">
            <label for="">Apt. #</label>
            <span class="data">-</span>
        </div>
        <div class="div-border width-80 float-right">
            <label for="">Delivery Address</label>
            <span class="data">{{$address}}</span>
        </div>
    </div>
    <br />
    <!-- 4th flexrow -->
    <div style="display:flex;clear:both">
        <div class="div-border width-20 float-left">
            <label for="">City</label>
            <span class="data">{{$city}}</span>
        </div>
        <div class="div-border width-20 border-left  float-left">
            <label for="">State</label>
            <span class="data"> {{$state}}</span>
        </div>
        <div class="div-border width-20 border-left  float-left">
            <label for="">Zip</label>
            <span class="data">{{$zip_code}}</span>
        </div>
        <div class="div-border  border-left float-left" style="width:39.8%">
            <label for="">Phone Number With Area Code</label>
            <span class="data">{{$phone_number}}</span>
        </div>
    </div>
    <br />

    <!-- 5th flexrow -->
    <div style="display:flex;clear:both">
        <div class="div-border float-left" style="width:39.8%">
            <label for="">DOB </label>
            <span class="data">{{$patient_dob}}</span>
        </div>
        <div class="div-border width-20 border-left float-left">
            <label>Gender</label>
            <!-- @if($patient_gender=='male' || $patient_gender=='Male')
            <input type="radio" checked />
            @else
            <input type="radio" />
            @endif
            <label for="m">M</label>
            @if($patient_gender=='female'  || $patient_gender=='Female')
            <input type="radio" checked />
            @else
            <input type="radio" />
            @endif
            <label for="f">F</label> -->
            @if($patient_gender=='male' || $patient_gender=='Male')
            <label for="m">Male</label>
            @elseif($patient_gender=='female' || $patient_gender=='Female')
            <label for="f">Female</label>
            @else
            <label for="f"></label>
            @endif
        </div>
        <div class="div-border width-40 border-left float-left">
            <label for="">Email</label>
            <span class="data">{{$email_address}}</span>
        </div>
    </div>
    <br />
    <div class="flexrow">
        <div class="div-border" style="width:99%;padding-top:8px;padding-bottom:8px;padding-left:3px;">
            <div class="width-60 float-left">
                <span>
                    <b>Medications you want your doctor to send to Umbrella Health Care Systems. </b>
                    </apan>
            </div>

            <div class="width-20 float-left">
                <span>
                    <b class="">Strength:
                    </b>
                </span>
            </div>
            <div>
                <span class="width-20 float-left">
                    <b>Quantity desired:</b>
                </span>
            </div>
        </div>
    </div>
    <div style="height:260px">
        @foreach($items as $item)
        <br />
        <div class="div-border1 flexrow ">
            <div class="width-60  float-left" style="padding-top:2px;">
                <input type="text" value="{{$item->name}}" style="height:12px" class="text-border width-80">
            </div>
            <div class="width-20  float-left" style="padding-top:2px;">
                <input type="text" value="{{$item->med_unit}}" style="height:12px"
                    class="text-border  width-60">
            </div>
            <div class="width-20  float-right" style="padding-top:2px;">
                <input type="text" value="{{$item->med_days}}" style="height:12px" class="text-border width-60">
            </div>
        </div>
        @endforeach
    </div>

    <br />


    <!-- ****************************** Second Section  ******************************** -->
    <div class="forms-heading flexrow justify-content-justify">
        <!-- <div class="square">2</div> -->
        <span style=""> Physician and prescription information — physician to complete this section</span>
    </div>
    <!-- 1st flexrow  -->
    <div style="display:flex;clear:both;padding-bottom:12px;">
        <div class="div-border float-left" style="width:39.8%;">
            <label for="">
                Prescribing Physician Name
            </label>
            <span class="data">{{'Dr. '.$phy_by}}</span>
        </div>
        <div class="div-border width-30 float-left">
            <label for=""> Patient Name </label>
            <span class="data">{{$first_name}}</span>
        </div>
        <div class="div-border width-30 border-left float-left">
            <label for=""> DOB </label>
            <span class="data">{{$patient_dob}}</span>
        </div>
    </div>
    <br />
    <!-- 2nd flexrow -->
    <div class='main-cont flexrow'>
        <div class="left-cont float-left">
            <div>
                <div class="div-border">
                    <label for="" class="px-1">
                        Physician Phone Number with Area Code
                    </label>
                    <span class="data">{{$phy_phone_number}}</span>
                </div>
            </div>
            <div>
                <div class="div-border">
                    <label for="" class="px-1">
                        Physician Fax Number with Area Code
                    </label>
                    <span class="data">-</span>

                </div>
            </div>
            <div>
                <div style="display:flex" class="div-border">
                    <label for class="px-1">
                        Physician Street Address
                    </label>
                    <span style="font-size:8px" class="data">{{$phy_address}}</span>
                    <!-- <input type="text" name="" id="" class="width" /> -->
                </div>
            </div>
            <div>
                <div style="display:flex" class="div-border">
                    <label for="" class="px-1">
                        City, State, ZIP
                    </label>
                    <span class="data">{{$phy_city.', '.$phy_state.', '.$phy_zip_code}}</span>
                </div>

            </div>
            <div class="left-second-last div-border">
                <div class="one" style="height:30px;float:left">
                    <label for="" class="px-1 width">
                        NPI
                    </label>
                    <span class="data">{{$NPI}}</span>
                </div>
                <div class="" style="height:30px;float:right">
                    <label for=" " class="width" style="padding:4px">
                        DEA
                    </label>
                    <span class="data">-</span>
                </div>
            </div>
            <div class="left-last-cont" style="clear:both">
                <p class="py-1 px-2" style="padding-right:3px;">
                    This document and others if attached contain information from
                    UHCS that is privileged, confidential and/or may contain
                    protected health information (PHI). We are required to safeguard
                    PHI by applicable law. The information in this document is for
                    the sole use of the person(s) or company named above. Proper
                    consent to disclose PHI between these parties has been obtained.
                    If you received this document by mistake, please know that
                    sharing, copying, distributing or using information in this
                    document is against the law. If you are not the intended
                    recipient, please notify the sender immediately and returns the
                    documents by mail to UHCS privacy office 17900 Von Karman, M/S
                    CA016-0101, Irvine, CA,92614.
                </p>
            </div>
        </div>
        <div class='right-cont float-right'>
            <p class="mt-1">
                Enter prescription details here or attach your office
                prescription to the form.
            </p>
            <div class="float-left" style="margin-top:2px;">
                @foreach($items as $item)
                <span class="data">{{$item->name}}</span>
                <span class="data" style="margin-bottom:1px;">{{$item->med_unit}}</span>
                @endforeach
            </div>

            <div style="height:100px;margin-top:80px">
                <div class="float-left" style="width:50%">
                    <img style="height:75px; width: 150px;"  src="{{$signature}}" >
                    <span style="margin-left: 15px;">Physician Signature </span>
                    <div style="margin-left: 15px;" class="text-border3">
                        <span class="data"></span>
                    </div>
                </div>

                <div class="float-left" style="width:50%;margin-left:230px;margin-top:55px">
                    <span>Date</span>
                    <div class="text-border2">
                        <span class="data">{{$date}}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ****************************** Third Section  ******************************** -->
        <div class="forms-heading flexrow">
            <span>
                Physician to fax completed order form to UHCS at
                +1(407)693-8484.
            </span>
        </div>
</form>
