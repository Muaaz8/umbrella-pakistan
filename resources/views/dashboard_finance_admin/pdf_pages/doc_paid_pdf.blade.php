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
        <div class="logo-style" style="    margin-top: 10px;
        margin-bottom: 10px;">
            <img src="{{ public_path('asset_admin/images/logo.png') }}" alt="" style="width: 100px; height: 100px;">
        </div>

        @foreach($payable as $ot)
        <div class="main-div" style="    border: 1px solid #d5d5d585;

        width: 100%;
        height: 120px;
white-space: nowrap;
        padding: 20px;
        margin-bottom: 10px;">
            <div style="margin-bottom: 10px; text-align:center; color:black;"><strong>Amounts Paid to Dr.{{$ot['doc_name']}}</strong></div>
            <div style="width: 50%;  float: left;">
            <div><strong>Type Of Earning:</strong> &nbsp;<span>{{$ot['type']}}</span></div>
            <div><strong>Date:</strong>&nbsp;{{$ot['date']}}</div>
            <div><strong>Time:</strong>&nbsp;{{$ot['time']}}</div>
            <div><strong>Amount:</strong>Rs. {{$ot['earning']}}</div>

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
