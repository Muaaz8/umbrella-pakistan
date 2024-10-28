@extends('layouts.admin')

@section('script')
<script>
    @php header("Access-Control-Allow-Origin: *"); @endphp
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function assignLabToDoctor(order_id) {
        var doctor_id = $('#doctor_id_' + order_id).val();
        if (doctor_id == '' || doctor_id == null) {
            $('#message_error_' + order_id).text('Please Select Doctor');
        }
        else {
            $('#assignbutn').html('');
            $('#assignbutn').attr('disabled',true);
            $('#assignbutn').html('<i class="fa fa-spinner fa-spin"></i>Wait...')
            $.ajax({
                type: "POST",
                url: "{{URL('/assignLabForApprovalToDoctor')}}",
                data: {
                    doctor_id: doctor_id,
                    order_id: order_id
                },
                success: function (res) {
                    if (res == 'ok') {
                        location.href = '/unassignedLabOrders';
                    }
                }
            });
        }
    }
</script>
@endsection
@section('content')
<style>
    th {
        width: 25%
    }
</style>
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Online Unassigned Orders</h2>
        </div>
        <div class="card">
            <div class="body">
                @if (\Session::has('message'))
                <div class="alert alert-success">
                    <ul>
                        <li>{!! \Session::get('message') !!}</li>
                    </ul>
                </div>
                @endif
                <table class="table-responsive table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="width:10%">Order ID</th>
                            <th style="width:10%">Order State</th>
                            <th style="width:10%">Order Date</th>
                            <th style="width:30%">Lab Products</th>
                            <th style="width:10%">Status</th>
                            <th style="width:30%">State Doctors</th>
                            <th style="width:10%">Action</th>
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
                            <td>

                                <select class="form-control" id="doctor_id_{{ $order->order_id }}">
                                    <option value="">Select Doctor</option>
                                    @foreach($order->doctors as $doctor)
                                    <option value="{{ $doctor->user_id }}">Dr.{{ucwords($doctor->name.'
                                        '.$doctor->last_name)}}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <center>
                                    <button type="button" id='assignbutn' onclick="assignLabToDoctor({{ $order->order_id }})"
                                        class="btn btn-primary">Done</button>

                                </center>
                                <span class="text-danger" id="message_error_{{ $order->order_id }}"></span>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">No Orders To Show</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
                {{-- $pending_requisitions->links('pagination::bootstrap-4') --}}
            </div>
        </div>
    </div>
</section>
@endsection
