@extends('layouts.admin')

@section('content')
<section class="content patients">
    <div class="container-fluid">
        <div class="block-header">
            <h2>{{ucwords($doc_name)}}</h2>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="body">
                        <div class="row col-md">
                            <div class="col-md ">
                                <h5 class="mt-2">Payment Details</h5>
                                <hr>
                                    <table class="table table-bordered">
                                        <thead>
                                            <th width="20%">Type</th>
                                            <th width="20%">Date</th>
                                            <!-- <th width="20%">Amount Paid</th> -->
                                            <!-- <th width="20%">Other deductions</th> -->
                                            <th width="20%">Earning</th>
                                        </thead>
                                        <tbody>
                                        @if($doc->id=='6')
                                            <!-- <tr style="background-color:#d0fffe">
                                                <td>Pharmacy</td>
                                                <td>Nov,13 2020</td>
                                                <td>$260</td>
                                                <td>$170</td>
                                                <td>$90</td>
                                            </tr>
                                            <tr style="background-color:#d0fffe">
                                                <td>Pharmacy</td>
                                                <td>Nov,13 2020</td>
                                                <td>$100</td>
                                                <td>$60</td>
                                                <td>$40</td>
                                            </tr>
                                            <tr style="background-color:#fffddb">
                                                <td>Lab Test</td>
                                                <td>Nov,13 2020</td>
                                                <td>$200</td>
                                                <td>$150</td>
                                                <td>$50</td>
                                            </tr>
                                            <tr style="background-color:#e4ffde">
                                                <td>Imaging</td>
                                                <td>Nov,13 2020</td>
                                                <td>$260</td>
                                                <td>$170</td>
                                                <td>$90</td>
                                            </tr> -->
                                            <tr class="bg-teal">
                                                <td>Sessions</td>
                                                <td>Nov,10 2020</td>
                                                <!-- <td>$30</td> -->
                                                <!-- <td>$24</td> -->
                                                <td class="font-weight-bold">$24</td>
                                            </tr>
                                            <tr class="bg-teal">
                                                <td>Sessions</td>
                                                <td>Nov,10 2020</td>
                                                <!-- <td>$30</td>
                                                <td>$24</td> -->
                                                <td class="font-weight-bold">$24</td>
                                            </tr>
                                            <tr class="bg-teal">
                                                <td>Sessions</td>
                                                <td>Nov,10 2020</td>
                                                <!-- <td>$30</td>
                                                <td>$24</td> -->
                                                <td class="font-weight-bold">$24</td>
                                            </tr>
                                            <tr class="bg-teal">
                                                <td>Sessions</td>
                                                <td>Nov,10 2020</td>
                                                <!-- <td>$30</td>
                                                <td>$24</td> -->
                                                <td class="font-weight-bold">$24</td>
                                            </tr>
                                            <tr class="bg-teal">
                                                <td>Sessions</td>
                                                <td>Nov,10 2020</td>
                                                <!-- <td>$30</td>
                                                <td>$24</td> -->
                                                <td class="font-weight-bold">$24</td>
                                            </tr>
                                            <tr class="bg-teal">
                                                <td>Sessions</td>
                                                <td>Nov,10 2020</td>
                                                <!-- <td>$30</td>
                                                <td>$24</td> -->
                                                <td class="font-weight-bold">$24</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Total</td>
                                                <td class="font-weight-bold">$306</td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td colspan="5" class="text-center bg-grey">No Payment History</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection