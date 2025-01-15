<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Receipt</title>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: Arial, sans-serif;
        width: 100%;
      }

      .receipt {
        width: 80mm;
        height: 270mm;
      }

      .center {
        text-align: center;
      }

      .dotted-line {
        border-bottom: 2px dashed #000;
        margin: 5px 0;
      }

      .header img {
        width: 90%;
      }

      .price {
        font-size: 11px;
      }

      /* .header h1 {
        font-size: 16px;
        margin: 5px 0;
      }

      .header p {
        margin: 0;
        font-size: 12px;
      } */

      .receipt-title {
        font-size: 24px;
        font-weight: bold;
      }

      .details {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
      }

      .details div {
        font-size: 14px;
        margin-top: 20px;
      }

      .items {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 13px;
      }

      .items th,
      .items td {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
      }

      .items th {
        text-align: center;
        font-weight: bold;
      }

      .items .item {
        text-align: left;
        min-height: 10%;
      }

      .totals {
        margin-top: 10px;
        font-size: 16px;
      }

      .totals .row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
      }

      .totals .row div {
        width: 48%;
      }

      .thank-you {
        margin-top: 20px;
        text-align: center;
        font-weight: bold;
        font-size: 20px;
      }

      .barcode {
        text-align: center;
        margin-top: 10px;
      }

      .barcode img {
        width: 80%;
      }

      .right {
        text-align: right;
        font-size: 15px;
      }
      .to-lower {
        text-transform: lowercase;
      }
    </style>
  </head>
  <body>
    <div class="receipt">
      <div class="dotted-line"></div>
      <div class="header center">
        <img src="https://communityhealthcareclinics.com/assets/new_frontend/logo.png" alt="Community Healthcare Clinics">
      </div>

      <div class="dotted-line"></div>

      <div class="center receipt-title">*** RECEIPT ***</div>

      <div class="details">
        <div>Cashier #1</div>

        <div class="right">{{now()->format('d-m-Y') }} &nbsp; {{ now()->format('h:i A') }}</div>
      </div>

      <div class="dotted-line"></div>

      <table class="items">
        <thead>
          <tr>
            <th>Medicine Name</th>
            <th>Qty</th>
            <th>Price (Rs.)</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($prescription as $item)
                @if ($item->type == 'medicine')
                    <tr>
                        <td class="item to-lower">{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="price">{{ $item->price }}</td>
                    </tr>
                @elseif ($item->type == 'lab-test')
                    <tr>
                        <td class="item to-lower">{{ $item->product->TEST_NAME }}</td>
                        <td>-</td>
                        <td class="price">{{ $item->price }}</td>
                    </tr>
                @elseif ($item->type == 'imaging')
                    <tr>
                        <td class="item to-lower">{{ $item->product->TEST_NAME }}</td>
                        <td>-</td>
                        <td class="price">{{ $item->price }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
      </table>

      <div class="totals">
        <div class="dotted-line"></div>
        <div class="row">
          <div>Total Amount</div>
          <div class="right">
            Rs. {{ $prescription->sum('price') }}
          </div>
        </div>
        <div class="dotted-line"></div>
        {{-- <div class="row">
          <div>Total Amount</div>
          <div class="right">Rs. 155.00</div>
        </div>
        <div class="row">
          <div>Cash</div>
          <div class="right">Rs. 160.00</div>
        </div>
        <div class="row">
          <div>Change</div>
          <div class="right">Rs. 5.00</div>
        </div>
        <div class="dotted-line"></div> --}}
      </div>

      <div class="thank-you">THANK YOU!</div>
    </div>
  </body>
</html>
