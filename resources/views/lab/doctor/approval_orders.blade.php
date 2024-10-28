@extends('layouts.admin')
@section('content')
<style>
th {
    width: 25%
}

.text_white {
    color: white !important;
}

.border {
    border: 2px solid #03a9f4 !important;
}
</style>
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Online Unassigned Orders</h2>
        </div>
        @include('flash::message')
        <div class="card">
            <div class="body">
                <table class="table-responsive table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="width:10%">Order ID</th>
                            <th style="width:10%">Order Date</th>
                            <!-- <th style="width:10%">Test Code</th> -->
                            <th style="width:35%">Lab Product</th>
                            <th style="width:45%">
                                <center>Action</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <form method="post" action="{{url('submit_pending_approvals')}}">
                                @csrf
                                <input name="id" type="hidden" value="{{$order->id}}">
                                <input name="tbl_order_id" type="hidden" value="{{$order->tbl_order_id}}">
                                <input name="order_id" type="hidden" value="{{$order->order_id}}">
                                <td>{{$order->order_id}}</td>
                                <td>{{$order->created_at}}</td>
                                <!-- <td>{{--$order->test_cd--}}</td> -->
                                <td>{{$order->name}}</td>
                                <input type="hidden" name="status" id="status" value="accept">
                                <td>
                                    <div id="options_div_{{$order->id}}" class="col-12 row">
                                        <center>
                                            <a href="{{url('patient_record/'.$order->user_id)}}"
                                                class="btn btn-info text_white py-2 px-3">View Patient</a>
                                            <button type="submit" name="action" value="Decline"
                                            class="cancel btn btn-danger py-2 px-3 btn-raised text_white">Decline</i></button>
                                            <button type="submit" name="action" value="Approve"
                                                class="sumbit btn btn-success py-2 px-3 btn-raised text_white">Accept</button>

                                        </center>
                                    </div>

                                </td>
                            </form>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="width:100%"><center>No Orders To Show</center></td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
                {{--$unassignedOrders->links('pagination::bootstrap-4')--}}
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
    $('.decline_btn').on('click',function(){
        id=$(this).attr('id');
        id_sp=id.split('_');
        order_id=id_sp[2];
        console.log();
        $('#options_div_'+order_id).hide();
        $('#decline_div_'+order_id).show();
    });
    $(document).ready(function(){
        $('.decline_div').hide();
    });
    $('.cancel').on('click',function(){
        id=$(this).attr('id');
        id_sp=id.split('_');
        order_id=id_sp[1];
        console.log();
        $('#decline_input_'+order_id).val('');
        $('#options_div_'+order_id).show();
        $('#decline_div_'+order_id).hide();
    });
    $('.submit').on('click',function(){
        $('#status').val('declined');
    });
</script>
@endsection
