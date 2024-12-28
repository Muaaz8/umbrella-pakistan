@extends('layouts.admin')

@section('content')
<script>
setTimeout(function() {
    $('.messageDiv').fadeOut('fast');
}, 5000);
</script>
<style>
    #findBtn{
            background:linear-gradient(45deg, #5e94e4 , #08295a) !important;
            color:white;
            cursor: pointer;
    }
    #findSearch{
            border: 1px solid gray;
            padding: 5px;
    }
</style>
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">

        @isset($message)


            <div class="messageDiv">
                <div class="alert alert-danger">
                    {{$message}}
                </div>
            </div>

            @endisset
        </div>
        <div class="card">
            <div class="body">
                <h4>Find Paitent Record By Order Tracking ID</h4>


                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{route('find_patient_record')}}" method="post">
                              @csrf
                              <div class="row">
                                   <div class="col-sm-4">
                                        <input class="form-control" placeholder="Search..." id="findSearch" name="search" type="text">
                                   </div>
                                   <div class="col-sm-2 ">
                                        <input class="form-control" type="submit" id="findBtn" value="Find" >
                                   </div>
                              </div>
                        </form>
                    </div>
                </div>
                @isset($userID)
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12">

                            <div class="card">
                                <div class="row" style="padding-left: 20px">

                                    <style>
                                    .heading p {
                                        /* margin: 5px 0px; */
                                        font-weight: bold;
                                    }
                                    .heading_row{
                                    padding: 10px; border-bottom: 1px solid #eee;
                                    }
                                    </style>
                                    <div class="row col-md-12">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header" style=" font-size: 1.5rem; margin-top: 30px; ">Order Tracking </div>
                                            <div class="card-body">
                                            <div class="row heading_row">
                                                    <div class="col heading">
                                                        <p>Order State</p>
                                                    </div>

                                                    <div class="col answer">
                                                    <p>
                                                    <?php
                                                         if($orderState!=null)
                                                         {
                                                            echo ucwords($orderState);
                                                         }
                                                      ?>
                                                          </p>
                                                    </div>
                                                </div>


                                            @foreach($order_sub_id as $key => $item)

                                                <div class="row heading_row">
                                                    <div class="col heading">
                                                        <p>

                                                            <?php
                                                            if( $key === 'UMB' ){
                                                                echo 'Umbrella Master ID ';
                                                            }
                                                            else if( $key === 'PHAR'){
                                                                echo 'Pharmacy ';
                                                            }else if( $key === 'LBT'){
                                                                echo 'Lab Test';
                                                            }else if( $key === 'PPHAR' ){
                                                                echo 'Prescribed Pharmacy ';
                                                            }else if( $key === 'PLBT' ){
                                                                echo 'Prescribed Lab Test ';
                                                            }
                                                            ?>
                                                        </p>
                                                    </div>
                                                    <div class="col answer">
                                                      {{$item}}
                                                    </div>
                                                </div>
                                                @endforeach
                                                <div class="row heading_row">
                                                    <div class="col heading">
                                                        <p>Order Status</p>
                                                    </div>

                                                    <div class="col answer">
                                                    <p>
                                                    <?php
                                                         if($order_status!=null)
                                                         {
                                                            echo ucwords($order_status);
                                                         }
                                                      ?>
                                                          </p>
                                                    </div>
                                                </div>
                                                <div class="row heading_row">
                                                    <div class="col heading">

                                                    @if($userID=="GUEST")
                                                        <p>Order As</p>
                                                        @else
                                                        <p>User ID</p>
                                                   @endif
                                                    </div>

                                                    <div class="col answer">
                                                    <p>
                                                    <?php
                                                         if($userID!=null)
                                                         {
                                                            echo ucwords($userID);
                                                         }
                                                      ?>
                                                          </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header" style=" font-size: 1.5rem; margin-top: 30px; ">Payment Method</div>
                                            <div class="card-body">
                                            @foreach($payment as $item)
                                                @php
                                                $vals = explode("|",$item);
                                                @endphp
                                                <div class="row heading_row">
                                                    <div class="col heading">
                                                        <p>{{ ucwords(str_replace("_", " ", str_replace("'", '', $vals[0])))  }}</p>
                                                    </div>
                                                    <div class="col answer">

                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row col-md-12">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header" style=" font-size: 1.5rem; margin-top: 30px; ">Billing Details</div>
                                            <div class="card-body">
                                                @foreach($billing as $item)
                                                @php
                                                $vals = explode("|",$item);
                                                @endphp
                                                <div class="row heading_row">
                                                    <div class="col heading">
                                                        <p>{{ ucwords(str_replace("_", " ", str_replace("'", '', $vals[0])))  }}</p>
                                                    </div>
                                                    <div class="col answer">

                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header" style=" font-size: 1.5rem; margin-top: 30px; ">Shipping Details</div>
                                            <div class="card-body">
                                                @foreach($shipping as $item)
                                                @php
                                                $vals = explode("|",$item);
                                                @endphp
                                                <div class="row heading_row">
                                                    <div class="col heading">
                                                        <p>{{ ucwords(str_replace("_", " ", str_replace("'", '', $vals[0])))  }}</p>
                                                    </div>
                                                    <div class="col answer">

                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row col-md-12">
                                    <div class="card">
                                        <div class="card-header" style=" font-size: 1.5rem; margin-top: 30px; ">Products</div>
                                        <div class="card-body">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Cost</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($cart_data['products'] as $item)
                                                    <tr>
                                                        <td>{{ $item['product_name'] }}</td>
                                                        <td>{{ $item['quantity'] }}</td>
                                                        <td>Rs. {{ number_format($item['sale_price'], 2) }}</td>
                                                        <td>Rs. {{ number_format($item['cost'], 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                <tfoot>
                                                <tr style=" font-weight: bold; ">
                                                    <td colspan="2"></td>
                                                    <td colspan="1">SUBTOTAL</td>
                                                    <td>Rs. {{ number_format(array_sum($cart_data['total']), 2) }}</td>
                                                </tr>
                                                <tr style=" font-weight: bold; ">
                                                    <td colspan="2"></td>
                                                    <td colspan="1">TAX 25%</td>
                                                    <td>$0.00</td>
                                                </tr>
                                                <tr style=" font-weight: bold; ">
                                                    <td colspan="2"></td>
                                                    <td colspan="1">GRAND TOTAL</td>
                                                    <td>Rs. {{ number_format(array_sum($cart_data['total']), 2) }}</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                @endisset

            </div>
        </div>
    </div>
</section>
@endsection
@section('script')

@endsection
