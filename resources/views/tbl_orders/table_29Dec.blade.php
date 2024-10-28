<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css') }}">
<div class="col-md-12" style="overflow-x:hidden">
    <table class="table table-hover tblData" id="tblOrders-table">
        <thead>
            <tr>
                <th>Order State</th>
                <!-- <th>Order City</th> -->
                <th>Order Id</th>
                {{-- @if ($user->user_type == 'admin_lab') --}}
                {{-- <th>Patient Name</th> --}}
                {{-- <th>Patient Address</th> --}}
                <!-- <th>Order Sub Id</th> -->
                <!-- <th>Customer Id</th> -->
                {{-- <th>Lab Test</th> --}}
                {{-- <th>Lab Location</th> --}}
                {{-- <th>Appointment Date/Time</th> --}}
                <th>Payment</th>
                {{-- @endif --}}
                {{-- @if ($user->user_type == 'admin_imaging' || $user->user_type == 'editor_imaging')
                <th>Patient Name</th>
                <th>Patient Address</th>
                <!-- <th>Order Sub Id</th> -->
                <!-- <th>Customer Id</th> -->
                <th>Service Name</th>
                <th>Created At</th>
                @endif --}}

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
                <th>Order Status</th>
                <th>Action</th>


            </tr>
        </thead>
        <tbody>
            @foreach ($tblOrders as $tblOrders)
                <tr>
                    <td>{{ $tblOrders->order_state }}</td>
                    {{-- <!-- <td>{{ $tblOrders->order_city }}</td> --> --}}
                    <td>{{ $tblOrders->order_id }}</td>
                    @if ($user->user_type == 'admin_lab' || $user->user_type == 'editor_lab')
                        <td>{{ $tblOrders->fname . ' ' . $tblOrders->lname }}</td>
                        <td>{{ $tblOrders->address }}</td>
                        <!-- <td>{{-- $tblOrders->order_sub_id --}}</td> -->
                        <!-- <td>{{-- $tblOrders->customer_id --}}</td> -->
                        <td>{{ $tblOrders->name }}</td>
                        <td>{{ $tblOrders->lab_name . ', ' . $tblOrders->lab_address }}</td>
                        <td>{{ $tblOrders->date }}</td>
                        <td>{{ $tblOrders->time }}</td>
                    @endif
                    @if ($user->user_type == 'admin_imaging' || $user->user_type == 'editor_imaging')
                        <td>{{ $tblOrders->fname . ' ' . $tblOrders->lname }}</td>
                        <td>{{ $tblOrders->address }}</td>
                        <td>{{ $tblOrders->name }}</td>
                        <td>{{ $tblOrders->created_at }}</td>
                        <td>${{ $tblOrders->price }}</td>
                    @else
                        <td>${{ $tblOrders->total }}</td>
                    @endif
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
                    <td>{{ ucfirst($tblOrders->order_status) }}</td>
                    <td width="10%">
                        {!! Form::open(['route' => ['orders.destroy', $tblOrders->id], 'method' => 'delete']) !!}
                        <div class='btns-group'>
                            @if ($user->user_type == 'admin_lab' || $user->user_type == 'editor_lab')
                                <a href="{{ route('lab_order', [$tblOrders->id]) }}" class='action-btn'>
                                    <i class="fa fa-eye"></i></a>
                            @elseif($user->user_type=='admin_imaging'||$user->user_type=='editor_imaging')
                                <a href="{{ route('imaging_order', [$tblOrders->id]) }}" class='action-btn'>
                                    <i class="fa fa-eye"></i></a>
                            @else
                                <a href="{{ route('orders.show', [$tblOrders->id]) }}" class='action-btn'>
                                    <i class="fa fa-eye"></i></a>

                            @endif

                            <!-- <a href="{{ route('orders.edit', [$tblOrders->id]) }}" class='action-btn'
                                    ><i class="fa fa-pencil-square-o"></i></a>
                                {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'action-btn', 'onclick' => "return confirm('Are you sure?')"]) !!} -->
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
    .btns-group a {
        margin-right: 10px;
        color: black;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .btns-group button {
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
    }

</style>
