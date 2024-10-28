<!-- <link rel="stylesheet" href="{{ asset('asset_admin/css/table.css')}}"> -->

<div style="overflow-x:hidden">
<table class="table-responsive table table-hover table-bordered tblData">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Quest Patient ID</th>
            <th>Referred Physician ID</th>
            <th>UPIN</th>
            <th>NPI</th>
            <th>Test Name/s</th>
            <th>Date</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>{{$order->order_id}}</td>
            <td>{{$order->quest_patient_id}}</td>
            <td>{{$order->ref_physician_id}}</td>
            <td>{{$order->upin}}</td>
            <td>{{$order->npi}}</td>
            <td>{{$order->names}}</td>
            <td>{{$order->collect_date}}</td>
            <td>{{$order->collect_time}}</td>
            <td>
            <a href="{{route('quest.requisition',$order->id)}}">
                <button class="btn btn-primary p-1">Requisition</button>
            </a>
        </td>
       </tr>
       @empty
        <tr>
            <td colspan="8">No Orders To Show</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>