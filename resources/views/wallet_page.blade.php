@extends('layouts.admin')
@section('content')
<style>
.info-box-4 .icon {
    right: 10px !important;
}

.btn-default {
    background: #1f91f3 !important;
    border-radius: 0px !important;
    padding: 4px 8px !important;
    box-shadow: none !important;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Wallet</h2>
        </div>
        <div class="row clearfix">
            <div class="col-md-12 col-sm-12 col-xs-12">



                <?php
                    if($user_type=='doctor')
                    {
                    ?>
                <div class="card">
                    <div class="header">

                    </div>
                    <div class="card-body row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">Current Balance</div>
                                    <div class="number"> $ @convert($totalEarning)</div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-2 col-md-2 col-sm-6">
                                <div class="info-box-4 hover-zoom-effect">
                                    <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                    <div class="content">
                                        <div class="text">Clear</div>
                                        <div class="number"> $ @convert(00)</div>
                                    </div>
                                </div>
                            </div> --}}
                        {{-- <div class="col-lg-2 col-md-2 col-sm-6">
                                <div class="info-box-4 hover-zoom-effect">
                                    <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                    <div class="content">
                                        <div class="text">Total Earning</div>
                                        <div class="number">$ @convert($totalEarning)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-6">
                                <div class="info-box-4 hover-zoom-effect">
                                    <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                    <div class="content">
                                        <div class="text">This Year Earning</div>
                                        <div class="number"> $ @convert($totalEarningCurrentYear)</div>
                                    </div>
                                </div>
                            </div> --}}
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">This Month Earning</div>
                                    <div class="number"> $ @convert($totalEarningCurrentMonth)</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">Today Earning</div>
                                    <div class="number"> $ @convert($totalEarningCurrentDay)</div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row clearfix">
                        <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                            <div class="panel-group" id="accordion_10" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-col-blue-grey">
                                    <div class="col-lg-12 col-md-12 col-sm-12 row ml-1 pt-3">
                                        <b class="mt-3 mr-2">Pick A Date Range To Check History</b>
                                        <form method="POST" action="{{route('wallet_page')}}">
                                            @csrf
                                            <input type="text" name="daterange"
                                                style="border-radius:10px ; border:1px solid rgb(82, 81, 81); padding:1rem;" />
                                            <input type="submit" value="Check" class="p-3"
                                                style="background:linear-gradient(45deg, #5e94e4 , #08295a) !important; color:white; cursor:pointer; border-radius:10px;">
                                        </form>
                                    </div>
                                    <div class="panel-heading mt-3" role="tab" id="headingOne_10">

                                        <h4 class="panel-title"
                                            style="background:linear-gradient(45deg, #5e94e4 , #08295a) !important;"> <a
                                                role="button" data-toggle="collapse" data-parent="#accordion_10"
                                                href="#collapseOne_10" aria-expanded="true"
                                                aria-controls="collapseOne_10"> History</a> </h4>

                                    </div>


                                    <div id="collapseOne_10" class="panel-collapse  i" role="tabpanel"
                                        aria-labelledby="headingOne_10">
                                        <div class="panel-body">
                                            <div class="body table-responsive p-0">
                                                <table
                                                    class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                    <thead>
                                                        <tr>
                                                            <!-- <th>Session No.</th> -->
                                                            <th>S.no</th>
                                                            <th>Date</th>
                                                            <th>Start Time</th>
                                                            <th>End Time</th>
                                                            <th>Patient Name</th>
                                                            <th>Earning</th>
                                                            <!-- <th>Patient Remarks</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php
                                                            $counter=1;
                                                            foreach($doctorHistory as $d)
                                                            {

                                                                $date = date("m-d-Y", strtotime($d->date));
                                                                $timefrom = date("g:i a", strtotime($d->start_time));
                                                                $timeto = date("g:i a", strtotime($d->end_time));
                                                                $money=$d->price;

                                                            ?>
                                                        <tr>
                                                            <td>{{$counter}}</td>
                                                            <td>{{$date}}</td>
                                                            <td>{{$timefrom}}</td>
                                                            <td>{{$timeto}}</td>
                                                            <td>{{$d->name.' '.$d->last_name}}</td>
                                                            <td>$ @convert($money)</td>
                                                        </tr>
                                                        <?php
                                                            $counter+=1;
                                                            }
                                                            ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php
            }
            ?>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
$(function() {
    $('input[name="daterange"]').daterangepicker({
        opens: 'right'
    }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('MM-DD-YYYY') + ' to ' + end
            .format('MM-DD-YYYY'));
    });
});
</script>
@endsection
