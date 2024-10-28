@extends('layouts.dashboard_finance_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('page_title')
    <title>Vendors</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
    <script type="text/javascript">
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var text = $(this).attr('href').split('?');
            var mode = text[1].split('=');
            var page = mode[1];

            mode = mode[0];
            alert(mode);
            if (mode == 'pres') {
                fetch_pres_data(page);
            } else if (mode == 'online') {
                fetch_online_data(page);
            }
        });

        function fetch_pres_data(page) {
            $.ajax({
                url: "/pagination/fetch_vendor_pres_data?pres=" + page,
                success: function(data) {
                    $('#precriptionItem').html(data);
                }
            });
        }

        function fetch_online_data(page) {
            $.ajax({
                url: "/pagination/fetch_vendor_online_data?online=" + page,
                success: function(data) {
                    console.log(data);
                    $('#onlineItem').html(data);
                }
            });
        }
    </script>
    <script src="{{ asset('assets\js\searching.js') }}"></script>
@endsection
@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="col-md-5">
                            <div class="vendor_Info text-center">
                                <img src="{{ asset($vendor->image) }}" alt="">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class=" py-3">
                                <h1>{{ $vendor->name }}</h1>
                                <h4>{{ $vendor->category }} Vendor</h4>
                            </div>
                        </div>

                    </div>

                    <div class="row finance-screen-wrapper mt-2">
                        <div class="col-lg-12 col-sm-12">
                            <div class="first-box">
                                <i class="fa-solid fa-dollar-sign"></i><span class="tot-font">&nbsp; Payments</span>
                                <div class="second-box mt-2">
                                    <div class="total tot-font d-flex justify-content-between px-2">
                                        <span> Total Earning</span> <span>${{ $Total_earning }}</span>
                                    </div>
                                    <div class="total tot-font d-flex justify-content-between px-2">
                                        <span> Total Prescription Earning</span> <span>${{ $pres_earning }}</span>
                                    </div>
                                    <div class="total tot-font d-flex justify-content-between px-2">
                                        <span> Total Online Earning</span> <span>${{ $on_earning }}</span>
                                    </div>
                                    <div class="total tot-font d-flex justify-content-between px-2">
                                        <span> Total Amount Payable</span> <span>${{ $Amount_payable }}</span>
                                    </div>
                                    <div class="total tot-font d-flex justify-content-between px-2">
                                        <span> Total Amount Paid</span> <span>${{ $Amount_paid }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-lg-8 col-sm-12">
                                      <div class="Graph-sec">
                                        <div class="graph-details">
                                          <h6>
                                            <span class="text-primary details-bold"> E-Visit Earning</span>
                                            <br />
                                            <span>$ &nbsp;849.00</span>
                                          </h6>
                                          <hr>
                            
                                          <h6>
                                            <span class="text-danger details-bold">
                                              Precription Earning</span
                                            >
                                            <br />
                                            <span>$ &nbsp;849.00</span>
                                          </h6>
                                          <hr>
                            
                                          <h6>
                                            <span class="text-success details-bold"> Online Earning</span>
                                            <br />
                                            <span>$ &nbsp;849.00</span>
                                          </h6>
                                        </div>
                                        <div class="graph w-100">
                                            <canvas id="myChart" style="width:100%;max-width:600px ;  height: 211px;"></canvas>
                                        </div>
                                      </div>
                                    </div> -->
                    </div>



                    <section class="sec-2 mt-3">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item col-md-3 col-12">
                                <a class="nav-link active" data-bs-toggle="tab" href="#onlineEarn">Online Earning</a>
                            </li>
                            <li class="nav-item col-md-3 col-12">
                                <a class="nav-link" data-bs-toggle="tab" href="#eVisit">Prescriptions Earning</a>
                            </li>
                            <li class="nav-item col-md-3 col-12">
                                <a class="nav-link" data-bs-toggle="tab" href="#amountPayable">Amount Payable</a>
                            </li>
                            <li class="nav-item col-md-3 col-12">
                                <a class="nav-link" data-bs-toggle="tab" href="#paid">Paid</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane p-0 container active tab-pad" id="onlineEarn">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Earning</th>
                                        </tr>
                                    </thead>
                                    <tbody id="onlineItem">
                                        @forelse($OnlineItems as $ot)
                                            <tr>
                                                <td data-label="Type of Earning">{{ $ot->name }}</td>
                                                <td data-label="Date">{{ $ot->datetime['date'] }}</td>
                                                <td data-label="Time">{{ $ot->datetime['time'] }}</td>
                                                <td data-label="Earning">${{ $ot->price }}</td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan='4'>
                                                    <div class="m-auto text-center for-empty-div">
                                                        <img src="{{ asset('assets/images/for-empty.png') }}"
                                                            alt="">
                                                        <h6> No Online Earnings</h6>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane p-0 container fade" id="eVisit">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Earning</th>
                                        </tr>
                                    </thead>
                                    <tbody id="precriptionItem">
                                        @forelse($prescriptions as $pres)
                                            <tr>
                                                <td data-label="Type of Earning">{{ $pres->name }}</td>
                                                <td data-label="Date">{{ $pres->datetime['date'] }}</td>
                                                <td data-label="Time">{{ $pres->datetime['time'] }}</td>
                                                <td data-label="Earning">${{ $pres->price }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan='4'>
                                                    <div class="m-auto text-center for-empty-div">
                                                        <img src="{{ asset('assets/images/for-empty.png') }}"
                                                            alt="">
                                                        <h6> No Prescription Earnings</h6>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                            <div class="tab-pane p-0 container fade" id="amountPayable">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Type of Earning</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Earning</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payable as $pay)
                                            <tr>
                                                <td data-label="Type of Earning">{{ $pay['Type'] }}</td>
                                                <td data-label="Date">{{ $pay['date'] }}</td>
                                                <td data-label="Time">{{ $pay['time'] }}</td>
                                                <td data-label="Earning">${{ $pay['Earning'] }}</td>
                                                <td data-label="Earning">
                                                    <a href="/pay/{{$vendor->category}}/{{$pay['id']}}"><button class="btn btn-success">Mark as Paid</button></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan='4'>
                                                    <div class="m-auto text-center for-empty-div">
                                                        <img src="{{ asset('assets/images/for-empty.png') }}"
                                                            alt="">
                                                        <h6> No Payable Amounts</h6>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane p-0 container fade" id="paid">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Type of Earning</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Earning</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($paid as $pa)
                                            <tr>
                                                <td data-label="Type of Earning">{{ $pa['Type'] }}</td>
                                                <td data-label="Date">{{ $pa['date'] }}</td>
                                                <td data-label="Time">{{ $pa['time'] }}</td>
                                                <td data-label="Earning">${{ $pa['Earning'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan='4'>
                                                    <div class="m-auto text-center for-empty-div">
                                                        <img src="{{ asset('assets/images/for-empty.png') }}"
                                                            alt="">
                                                        <h6> No Paid Amounts</h6>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>
    </div>


    </div>
@endsection
