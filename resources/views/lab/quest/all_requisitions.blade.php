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
            <h2>Lab Requisitions</h2>
        </div>
        <div class="card">
            <div class="body">
                <table class="table-responsive table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="width:5%">Sr. #</th>
                            <!-- <th style="width:10%">Order ID</th> -->
                            <th style="width:10%">Session ID</th>
                            <th style="width:10%">Date</th>
                            <th style="width:10%">Refered Physician ID</th>
                            <!-- <th style="width:10%">UPIN</th> -->
                            <th style="width:10%">NPI</th>
                            <th style="width:35%">Test Name/s</th>
                            <th style="width:20%" colspan="2"><center>Action</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requisitions as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->session_id}}</td>
                            <!-- <td>{{$order->order_id}}</td> -->
                            <td>{{$order->created_at}}</td>
                            <td>{{$order->ref_physician_id}}</td>
                            <!-- <td>{{$order->upin}}</td> -->
                            <td>{{$order->npi}}</td>
                            <td>{{$order->names}}</td>
                            
                            <td><center>
                                <a class="btn btn-primary px-3 py-2" target="_blank" href="{{\App\Helper::get_files_url($order->requisition_file) }}">Requisition</a>
                                </center></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">No Orders To Show</td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
                {{$requisitions->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
</section>
@endsection