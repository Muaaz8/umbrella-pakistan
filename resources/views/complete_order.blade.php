@extends('layouts.frontend')

@section('content')
<div class="jumbotron text-center">
    <h1 class="display-3">Thank You!</h1>
    <p class="lead"><strong>We Recieved Your Request</strong> Your Order ID# <strong>{{ $order_id }}</strong>, please check your email for invoice.</p>
    <hr>
    <!-- <p>
      Having trouble? <a href="">Contact us</a>
    </p> -->
    <p class="lead">
      <a class="ui green button" style="color: white;background-color: #08295a;font-size: 18px;border-radius: 0px;text-transform: uppercase;" href="/" role="button">Continue to homepage</a>
    </p>
  </div>
@endsection