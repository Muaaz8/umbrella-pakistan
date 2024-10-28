@extends('layouts.admin')
@section('css')
<style>
.heading p {
    /* margin: 5px 0px; */
    font-weight: bold;
}

.heading_row {
    padding: 10px;
    border-bottom: 1px solid #eee;
}
.btn-default {
    padding:0px !important;
    margin:0px !important;
    margin-left:15px !important;
}
</style>
@endsection
@section('content')

<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Dashboard</h2>
            <small class="text-muted">Welcome to Umbrellamd</small>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h4>Payment: <strong>Paid</strong> </h4>
                <h4>Status: <strong>Pending</strong> </h4>
                <div class="card">
                    <div class="row" style="padding-left: 20px">
                        <div class="row col-md-12">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header" style=" font-size: 1.5rem; margin-top: 30px; ">
                                        <div class="row">
                                            @if(auth()->user()->user_type == 'editor_imaging')
                                            <div class="col-md-10">
                                            Imaging Order
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" onclick="window.location.href='/profile/{{$img_order->username}}'" class="btn btn-raised btn-primary text-right p-2 waves-effect btn-raised waves-float m-0">view patient</button>
                                            </div>
                                            @elseif(auth()->user()->user_type == 'editor_pharmacy')
                                            <div class="col-md-10">
                                            pharmacy Order
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row heading_row">
                                            <table class="col-md-12 table table-bordered table-responsive table-hover">
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Tracking ID</th>
                                                    <td width="200">{{$img_order->order_id}}</td>
                                                </tr>
                                                @if(auth()->user()->user_type == 'editor_pharmacy')
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Medicine Name</th>
                                                    <td width="200">{{$img_order->name}}</td>
                                                </tr>
                                                @else
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Imaging Service</th>
                                                    <td width="200">{{$img_order->name}}</td>
                                                </tr>
                                                @endif
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Name</th>
                                                    <td width="1122">
                                                        {{$img_order->first_name.' '.$img_order->last_name}}
                                                    </td>
                                                </tr>
                                                @if(auth()->user()->user_type == 'editor_imaging')
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Selected Location</th>
                                                    <td width="1122">
                                                        {{$img_order->location}}
                                                    </td>
                                                </tr>
                                                @endif
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Product Price</th>
                                                    <td width="1122">
                                                        {{$img_order->price}}
                                                    </td>
                                                </tr>
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Username</th>
                                                    <td width="1122">{{$img_order->username}}</td>
                                                </tr>
                                                <!-- <tr class="p-2 mb-1">
                                                    <th width="150">Booking Date</th>
                                                    <td width="1122">{{$img_order->date}}</td>
                                                </tr>
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Booking Time</th>
                                                    <td width="1122">{{$img_order->time}}</td>
                                                </tr> -->
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Order State</th>
                                                    <td width="1122">{{$img_order->order_state}}</td>
                                                </tr>
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Payment Title</th>
                                                    <td width="1122">{{$img_order->payment_title}}</td>
                                                </tr>
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Payment Method</th>
                                                    <td width="1122">{{$img_order->payment_method}}</td>
                                                </tr>
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Currency</th>
                                                    <td width="1122">{{$img_order->currency}}</td>
                                                </tr>
                                                @if(auth()->user()->user_type == 'editor_imaging')
                                                <tr class="p-2 mb-1">
                                                    <th width="150">Report</th>
                                                    <td width="1122">
                                                        @if($img_order->order_status!='reported')
                                                        <form method="post"
                                                            action="{{route('upload_imaging_report',$img_order->id)}}"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="col-md-12 row">
                                                                <div class="custom-file col-md-10 p-0">
                                                                    <input type="file" hidden
                                                                        class="custom-file-input customFile"
                                                                        name="img_report" id="customFile">
                                                                    <label class="custom-file-label col-12 mb-0"
                                                                        for="customFile" style="padding:2px;
                                                                    border-radius:12px; border:grey 1px solid;top:9px">
                                                                        Select File</label>
                                                                </div>
                                                                <div class="col-md-2 p-0">
                                                                    <button type="button" id="cancel_file"
                                                                        class="cancel btn btn-raised btn-default btn-circle waves-effect waves-circle waves-float m-0"
                                                                        >
                                                                        <i style="margin-top: 5px;margin-right: -2px;"
                                                                            class="fa fa-times"></i>
                                                                    </button>
                                                                    <button type="submit" 
                                                                        class="btn btn-raised btn-primary p-2 waves-effect btn-raised waves-float m-0">Upload</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        @else
                                                        <a href="{{\App\Helper::get_files_url($img_order->report)}}"
                                                            target="_blank"><i class="fa fa-eye"></i> View</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
$('.cancel').click(function() {
    console.log('ghjkl');
    $('.customFile').val('');
    $('.customFile').siblings(".custom-file-label").removeClass("selected").html(
        'No File Added');
})
</script>
@endsection