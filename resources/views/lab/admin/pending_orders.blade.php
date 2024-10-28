@extends('layouts.admin')
@section('content')
<style>
    th {
        width: 25%
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
                            <th style="width:10%">Order State</th>
                            <th style="width:10%">Order Date</th>
                            <th style="width:30%">Lab Products</th>
                            <th style="width:10%">Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending_requisitions as $order)
                        @php $index = 1; @endphp
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->user_state }}</td>
                            <td>{{ $order->date }}</td>
                            <td class="text-start ps-3">
                                @foreach($pending_requisitions_test_name as $data)
                                @if($data->order_id==$order->order_id)
                                <strong>{{$index}}) </strong>{{ $data->TEST_NAME }}<br>
                                @php $index++; @endphp
                                @endif
                                @endforeach
                            </td>
                            <td>{{ $order->decline_status }}</td>



                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">No Orders To Show</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{$pending_requisitions->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
</section>
@endsection
