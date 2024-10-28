@extends('layouts.admin')
@section('content')
<style>
th{
    width:25%
}
</style>
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Pending Lab Orders</h2>
        </div>
        <div class="card">
            <div class="body">
                <table class="table-responsive table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="width:10%">Order ID</th>
                            <th style="width:10%">Order Date</th>
                            <th style="width:10%">Transaction ID</th>
                            <th style="width:10%">Price</th>
                            <th style="width:10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <form method="post" action="{{ url('refundLabOrder') }}">
                                @csrf
                                <input hidden name="lab_approval_order_id" value="{{$order->id}}">
                                <input hidden name="transaction_id" value="{{$order->transaction_id}}">
                                <input hidden name="price" value="{{$order->price}}">
                                <td><b>{{$order->order_id}}</b></td>
                                <td>{{$order->created_at}}</td>
                                <td>{{$order->transaction_id}}</td>
                                <td>{{$order->price}}</td>
                                <td><center>
                                    <button type="submit" class="btn btn-primary">Done</button>
                                </center></td>
                            </form>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">No Orders To Show</td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
                {{$orders->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
</section>
@endsection