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
        @foreach($prescriptions as $pres)
        <div class="main-div" style="    border: 1px solid #d5d5d585;
      
        width: 100%;
        height: 115px;
white-space: nowrap;
        padding: 20px;
        margin-bottom: 10px;">
            <div style="width: 50%;  float: left;">
            <div><strong>Product Name:</strong> &nbsp;<span>{{$pres->name}}</span></div>
            <div><strong>Session ID:</strong> &nbsp;<span>UEV-{{$pres->ses_id}}</span></div>
            <div><strong>Order ID:</strong>&nbsp;{{$pres->order_id}}</div>
            <div><strong>Product ID:</strong>&nbsp;{{$pres->pro_id}}</div>
            <div><strong>Date:</strong> {{$pres->datetime['time']}},{{$pres->datetime['date']}} </div>

            </div>
            <div class="payment-div" style=" margin-top:30px   padding: 10px;
            border: 1px solid #d5d5d585; width: 50%; float: right;">
                <div><span>Dosage Days:</span>&nbsp; &nbsp;<span class=" fw-bold float-end">{{$pres->usage}}</span></div>
                <div><span>Selling Price:</span>&nbsp; &nbsp; <span class=" fw-bold float-end">${{$pres->sale_price}}</span></div>
                <div><span>Price:</span> &nbsp; &nbsp;<span class=" fw-bold float-end">${{$pres->price}}</span></div>
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
