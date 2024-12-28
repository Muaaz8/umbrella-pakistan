{{-- Hi <strong>{{ $customer_name }}</strong>,

<p>Your Invoice #<strong>{{ $invoice_no }}</strong></p>

<p><strong>{{ $body }}</strong></p> --}}



<html>

<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
    <thead>
      <tr>
        <th style="text-align:left;"><img style="max-width: 120px;" src="https://www.umbrellamd.com/asset_frontend/images/logo_haris.jpeg" alt="Umbreallamd"></th>
        <th style="text-align:right;font-weight:400;">{{ $order_date }}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
        <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Order ID</span> {{ $order_id }}</p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Order status</span><b style="color:red;font-weight:normal;margin:0">In Process</b></p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">PayPal Transaction ID</span> {{ $payment['transaction_id']}}</p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Payment status</span><b style="color:green;font-weight:normal;margin:0">Paid</b></p>
          <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Order amount</span> <?= '$'.number_format($order_total,2);?></p>
        </td>
      </tr>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td style="width:50%;padding:20px;vertical-align:top">
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px">Full Name</span> {{ $billing['full_name']}}</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Phone</span> {{ $billing['phone_number']}}</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">City</span> {{ $billing['city']}}</p>
        </td>
        <td style="width:50%;padding:20px;vertical-align:top">
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Email</span> {{ $billing['email_address']}}</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Address</span> {{ $billing['address']}}2</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">State</span> {{ $billing['state']}}</p>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="font-size:20px;padding:30px 15px 0 15px;">Order Items</td>
      </tr>
      <tr>
        <td colspan="2" style="padding:15px;">
            <table style="width: 100%; border-collapse: collapse; border-style: solid; margin-left: auto; margin-right: auto;" border="1" cellspacing="10" cellpadding="5">
            <tbody>
            <tr>
            <th style="width: 100%;">Product</th>
            <th style="width: 100%;">Price</th>
            <th style="width: 100%;">Quantity</th>
            <th style="width: 100%;">Total</th>
            </tr>
            @foreach($order_items as $item)
            <tr>
            <td style="width: 100%; text-align: center;">{{ $item['name'] }}</td>
            <td style="width: 100%; text-align: center;">Rs. {{ number_format($item['price'], 2) }}</td>
            <td style="width: 100%; text-align: center;">{{ $item['quantity'] }} </td>
            <td style="width: 100%; text-align: center;">Rs. {{ number_format($item['update_price'], 2) }}</td>
            </tr>
            @endforeach
            </tbody>
            </table>
        </td>
      </tr>
    </tbody>
    <tfooter>
      <tr>
        <td colspan="2" style="font-size:14px;padding:50px 15px 0 15px;">
          <strong style="display:block;margin:0 0 10px 0;">Regards</strong> Umbrellamd<br> 625 School House Road #2, Lakeland, FL 33813<br><br>
          <b>Phone:</b> +1 (407) 693-8484<br>
          <b>Email:</b> sales@umbrellamd.com
        </td>
      </tr>
    </tfooter>
  </table>
</body>

</html>
