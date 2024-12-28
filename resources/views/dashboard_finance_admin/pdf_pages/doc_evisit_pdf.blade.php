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
    <link rel="stylesheet" href="style.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <title>Finance</title>
<!-- <style>
.main-div{
    border: 1px solid #d5d5d585;
    display: flex;
    justify-content: space-between;
    padding: 20px;
    margin-bottom: 10px;
}
.payment-div{
    padding: 10px;
    border: 1px solid #d5d5d585;
}
.logo-style{
    margin-top: 10px;
    margin-bottom: 10px;
}

</style> -->
  </head>
  <body>
    <div class="water-marl-evisit">
    </div>


    <div class="container">
        <div class="logo-style" style="
        margin-bottom: 10px;
        text-align:center;">
            <img src="{{ public_path('asset_admin/images/logo.png') }}" alt="" style="width: 100px; height: 100px;">
        </div>
        @foreach($getSessionTotalSessions as $sessions)
        <div class="main-div" style="    border: 1px solid #d5d5d585;

        width: 100%;
        height: 140px;
white-space: nowrap;
        padding: 20px;
        margin-bottom: 10px;">
            <div style="margin-bottom: 10px; text-align:center; color:black;"><strong>Evisit Earning Details Of Dr.{{$sessions->doc_name}}</strong></div>
            <div style="width: 50%;  float: left;">
            <div><strong>Session ID:</strong> &nbsp;<span>UEV-{{$sessions->session_id}}</span></div>
            <div><strong>Earning:</strong>&nbsp;Rs. {{$sessions->Net_profit}}</div>
            <div><strong>Date:</strong> {{$sessions->datetime['time']}},{{$sessions->datetime['date']}} </div>
            <div><strong>Session with:</strong> &nbsp;<span>Dr.{{$sessions->doc_name}}</span></div>
            <div><strong>Patient:</strong>&nbsp;<span>{{$sessions->pat_name}}</span> </div>
            </div>
            <div class="payment-div" style="    padding: 10px;
            border: 1px solid #d5d5d585; width: 50%; float: right;">
                <div><span>Patient Paid:</span>&nbsp; &nbsp;<span class="text-success fw-bold float-end">+Rs. {{$sessions->price}}</span></div>
                <div><span>Doctor 55%:</span>&nbsp; &nbsp; <span class="text-danger fw-bold float-end">-Rs. {{$sessions->doc_price}}</span></div>
                <div><span>Credit card Fee:</span> &nbsp; &nbsp;<span class="text-danger fw-bold float-end">-Rs. {{$sessions->card_fee}}</span></div>
                <div><span>Net Profit:</span> &nbsp; &nbsp;<span class="fw-bold float-end">+Rs. {{$sessions->Net_profit}}</span></div>
            </div>
        </div>
@endforeach





    </div>






    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>
  </body>

</html>
