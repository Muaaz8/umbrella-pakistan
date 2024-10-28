@extends('layouts.admin')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Wallet</h2>
        </div>
        <div class="row clearfix">
            <div class="col-md-12 col-sm-12 col-xs-12">



                <div class="card">
                    <div class="header">

                    </div>
                    <div class="card-body row">
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">Total Balance</div>
                                    <div class="number"> $ @convert($totalBalance)</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">Current Month Balance</div>
                                    <div class="number"> $ @convert($totalMonthBalance)</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">Today Balance</div>
                                    <div class="number"> $ @convert($totalTodayBalance)</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">E-visit Balance</div>
                                    <div class="number"> $ @convert($totalSessionPrice)</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">Pharmacy Balance</div>
                                    <div class="number">$ @convert($getOrderTotal)</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="info-box-4 hover-zoom-effect">
                                <div class="icon"> <i class="zmdi zmdi-account col-blue"></i> </div>
                                <div class="content">
                                    <div class="text">Labs Earning</div>
                                    <div class="number"> $ @convert($getLabOrderTotal)</div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row clearfix">
                        <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                            <div class="panel-group" id="accordion_10" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-col-blue-grey">
                                    <div class="col-lg-12 col-md-12 col-sm-12 row ml-1 pt-3">
                                        <b class="mt-3 mr-2">Choose Filters To Check History</b>
                                        <form method="POST" action="{{route('admin_wallet')}}">
                                            @csrf
                                            <input type="text" name="daterange"
                                                style="border-radius:10px ; border:1px solid rgb(82, 81, 81); padding:1rem;"
                                                class="" />
                                            <select name="filtertype"
                                                style="border-radius:10px ; border:1px solid rgb(82, 81, 81); padding:1rem;">
                                                <option value="evisit">E-visit</option>
                                                <option value="pharmacy">Pharmacy</option>
                                                <option value="labs">Labs</option>
                                                <option value="imaging">Imaging</option>
                                            </select>
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
                                                <?php
                                                $counter=1;
                                                if($filterType=="evisit")
                                                {
                                                  ?>
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
                                                              if($count>0)
                                                              {

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
                                                        }
                                                        else{
                                                            ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                            echo"Record Not Found";
                                                            ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        }
                                                            ?>

                                                    </tbody>
                                                </table>


                                                <?php
                                                }
                                                else if($filterType=="pharmacy")
                                                {
                                                  ?>
                                                <table
                                                    class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                    <thead>
                                                        <tr>
                                                            <!-- <th>Session No.</th> -->
                                                            <th>S.no</th>
                                                            <th>Date</th>
                                                            <th>Order Id</th>
                                                            <th>Customer Name</th>
                                                            <th>order Status</th>
                                                            <th>Order Amount</th>
                                                            <!-- <th>Patient Remarks</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php
                                                            if($count>0)
                                                            {


                                                            foreach($doctorHistory as $d)
                                                            {

                                                                $date = date("m-d-Y", strtotime($d->created_at));


                                                            ?>
                                                        <tr>

                                                            <td>{{$counter}}</td>
                                                            <td>{{$date}}</td>
                                                            <td>{{$d->order_id}}</td>
                                                            <td>{{$d->name.' '.$d->last_name}}</td>
                                                            <td>{{$d->order_status}}</td>
                                                            <td>$ @convert($d->total)</td>

                                                        </tr>
                                                        <?php
                                                            $counter+=1;
                                                            }
                                                        }
                                                        else{
                                                            ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                            echo"Record Not Found";
                                                            ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        }
                                                            ?>

                                                    </tbody>
                                                </table>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="paginateCounter link-paginate">
                                                            {{$doctorHistory->links('pagination::bootstrap-4') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                }
                                                else if($filterType=="labs")
                                                {
                                                  ?>
                                                <table
                                                    class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                    <thead>
                                                        <tr>
                                                            <!-- <th>Session No.</th> -->
                                                            <th>S.no</th>
                                                            <th>Date</th>
                                                            <th>Order Id</th>
                                                            <th>Customer Name</th>
                                                            <th>order Status</th>
                                                            <th>Order Amount</th>
                                                            <!-- <th>Patient Remarks</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php
                                                           if($count>0)
                                                           {
                                                            foreach($doctorHistory as $d)
                                                            {

                                                                $date = date("m-d-Y", strtotime($d->created_at));


                                                            ?>
                                                        <tr>

                                                            <td>{{$counter}}</td>
                                                            <td>{{$date}}</td>
                                                            <td>{{$d->order_id}}</td>
                                                            <td>{{$d->name.' '.$d->last_name}}</td>
                                                            <td>{{$d->product_name}}</td>
                                                            <td>$ @convert($d->sale_price)</td>

                                                        </tr>
                                                        <?php
                                                            $counter+=1;
                                                            }
                                                        }
                                                            else{
                                                                ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                echo"Record Not Found";
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                            }
                                                            ?>

                                                    </tbody>
                                                </table>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="paginateCounter link-paginate">
                                                            {{$doctorHistory->links('pagination::bootstrap-4') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                }
                                                else if($filterType=="imaging")
                                                {
                                                  ?>
                                                <table
                                                    class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                    <thead>
                                                        <tr>
                                                            <!-- <th>Session No.</th> -->
                                                            <th>S.no</th>
                                                            <th>Date</th>
                                                            <th>Order Id</th>
                                                            <th>Customer Name</th>
                                                            <th>order Status</th>
                                                            <th>Order Amount</th>
                                                            <!-- <th>Patient Remarks</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php
                                                           if($count>0)
                                                           {
                                                            foreach($doctorHistory as $d)
                                                            {

                                                                $date = date("m-d-Y", strtotime($d->created_at));


                                                            ?>
                                                        <tr>

                                                            <td>{{$counter}}</td>
                                                            <td>{{$date}}</td>
                                                            <td>{{$d->order_id}}</td>
                                                            <td>{{$d->name.' '.$d->last_name}}</td>
                                                            <td>{{$d->product_name}}</td>
                                                            <td>$ @convert($d->sale_price)</td>

                                                        </tr>
                                                        <?php
                                                            $counter+=1;
                                                            }
                                                        }
                                                            else{
                                                                ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                echo"Record Not Found";
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                            }
                                                            ?>

                                                    </tbody>
                                                </table>
                                                <?php
                                                }
                                                ?>

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
    </div>
</section>
<style>
.btn-default {
    background: #1f91f3 !important;
    margin-left: 8px !important;
    font-size: 12px !important;
    padding: 4px 8px !important;
    border-radius: 2px !important;
}
</style>

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
