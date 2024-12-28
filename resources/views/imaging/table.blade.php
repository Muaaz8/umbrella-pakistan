<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css') }}">
<link rel="stylesheet" href="{{ asset('asset_admin/css/table-responsiveness.css') }}">
<style>
.table-responsive{
}

</style>
<div class=" col-md-12 ">
        <table class="table table-hover table-responsive tblData" id="tblOrders-table">

        <thead>
            <tr>
                <th>Service Name</th>
                <th>Doctor Name</th>
                <!-- <th>Order City</th> -->
                <th>Order Status</th>
                <th>Created At</th>
                <!-- <th>Order Sub Id</th> -->
                <!-- <th>Customer Id</th> -->
                <th>Amount</th>
                <th>Action</th>

                {{-- <th>Amount</th> --}}
                <!-- <th>Shipping Total</th>
                <th>Total Tax</th>
                <th>Billing</th>
                <th>Shipping</th>
                <th>Payment</th> -->
                <!-- <th>Payment Title</th>
                <th>Payment Method</th> -->
                <!-- <th>Cart Items</th>
                <th>Tax Lines</th>
                <th>Shipping Lines</th>
                <th>Coupon Lines</th> -->
                <!-- <th>Currency</th> -->
                {{-- <th>Order Status</th>
                <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                @foreach($tblOrders as $tblOrders)
                    <tr>
                        <td>{{ $tblOrders->name }}</td>
                        <td>Dr. {{ $tblOrders->doc_fname.' '.$tblOrders->doc_lname }}</td>
                        <td>{{ ucfirst($tblOrders->order_status) }}</td>
                        <td>{{ $tblOrders->created_at }}</td>
                        <td>Rs. {{ $tblOrders->price }}</td>
                        @if($tblOrders->order_status=='reported')
                        <td>
                            <a target="_blank"href="{{$tblOrders->report}}">
                                <button class="btn btn-primary">View</button>
                            </a>
                        </td>
                        @else
                        <td>Waiting..</td>
                        @endif
                        {{-- <td>{{ $tblOrders->name }}</td> --}}
                        {{-- <td>{{ $tblOrders->created_at }}</td> --}}
                        {{-- <td>Rs. {{ $tblOrders->price }}</td> --}}
                        <!-- <td>{{-- $tblOrders->shipping_total --}}</td>
                        <td>{{-- $tblOrders->total_tax --}}</td>
                        <td>{{-- $tblOrders->billing --}}</td>
                        <td>{{-- $tblOrders->shipping --}}</td>
                        <td>{{-- $tblOrders->payment --}}</td> -->
                        <!-- <td>{{-- $tblOrders->payment_title --}}</td>
                        <td>{{-- $tblOrders->payment_method --}}</td> -->
                        <!-- <td>{{-- $tblOrders->cart_items --}}</td>
                        <td>{{-- $tblOrders->tax_lines --}}</td>
                        <td>{{-- $tblOrders->shipping_lines --}}</td>
                        <td>{{-- $tblOrders->coupon_lines --}}</td> -->
                        <!-- <td>{{-- $tblOrders->currency --}}</td> -->
                        {{-- <td>{{ ucfirst($tblOrders->order_status) }}</td>
                        @if($tblOrders->order_status=='reported')
                        <td>
                            <a target="_blank"href="{{$tblOrders->report}}">
                                <button class="btn btn-primary">View</button>
                            </a>
                        </td>
                        @else
                        <td>Waiting..</td>
                        @endif --}}
                        </tr>
                @endforeach
        </tbody>
    </table>
</div>
