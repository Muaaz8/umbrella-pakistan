@extends('layouts.dashboard_finance_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('page_title')
    <title>Finance</title>
@endsection

@section('top_import_file')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
@endsection

@section('bottom_import_file')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript">
        <?php header('Access-Control-Allow-Origin: *'); ?>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: "{{ URL('/get_wallet_graph_values') }}",
                data: {},
                success: function(data) {
                    $('#evisit_ear').text('Rs. ' + data.evisit_ear);
                    $('#pres_ear').text('Rs. ' + data.pres_ear);
                    $('#online_ear').text('Rs. ' + data.online_ear);
                    new Chart("myChart", {
                        type: "line",
                        data: {
                            labels: data.MonthsForGraph,
                            datasets: [{
                                    fill: false,
                                    lineTension: 0,
                                    backgroundColor: "rgba(0,0,255,1.0)",
                                    borderColor: "rgba(0,0,255,0.1)",
                                    data: data.EvisitEarningsForGraf
                                },
                                {
                                    fill: false,
                                    lineTension: 0,
                                    backgroundColor: "rgba(0,255,0,1.0)",
                                    borderColor: "rgba(0,255,0,0.1)",
                                    data: data.OnlineEarningsForGraf
                                },
                                {
                                    fill: false,
                                    lineTension: 0,
                                    backgroundColor: "rgba(255,0,0,1.0)",
                                    borderColor: "rgba(255,0,0,0.1)",
                                    data: data.PrescriptionEarningsForGraf
                                }
                            ]
                        },
                        options: {
                            legend: {
                                display: false
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        min: 0,
                                        max: data.yaxis[3]
                                    }
                                }],
                            }
                        }
                    });
                }
            });

        });

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var text = $(this).attr('href').split('?');
            var mode = text[1].split('=');
            var page = mode[1];
            mode = mode[0];
            if (mode == 'session') {
                fetch_session_data(page);
            } else if (mode == 'pres') {
                fetch_pres_data(page);
            } else if (mode == 'online') {
                fetch_online_data(page);
            }
        });

        function fetch_session_data(page) {
            $.ajax({
                url: "/pagination/fetch_session_data?session=" + page,
                success: function(data) {
                    $('#eVisit').html(data);
                }
            });
        }

        function fetch_pres_data(page) {
            $.ajax({
                url: "/pagination/fetch_pres_data?pres=" + page,
                success: function(data) {
                    $('#precriptionItem').html(data);
                }
            });
        }

        function fetch_online_data(page) {
            $.ajax({
                url: "/pagination/fetch_online_data?online=" + page,
                success: function(data) {
                    $('#onlineItem').html(data);
                }
            });
        }

        function sessions() {
            var date = $('#session_date').val();
            var ses_id = $('#session_search_id').val();
            var msg = 'session';
            $.ajax({
                type: 'GET',
                url: "{{ URL('/get_filtered_values') }}",
                data: {
                    date: date,
                    id: ses_id,
                    msg: msg
                },
                success: function(data) {
                    $('#eVisit').html('');
                    var total = 0;
                    $.each(data, function(key, ses) {
                        total += parseFloat(ses.Net_profit);
                        $('#eVisit').append(
                            '<div class="accordion accordion-flush " id="accordionFlushExample_' +
                            ses.id + '">' +
                            '<div class="accordion-item mb-2">' +
                            '<h2 class="accordion-header" id="flush-heading_' + ses.id + '">' +
                            '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" ' +
                            'data-bs-target="#flush-collapse_' + ses.id +
                            '" aria-expanded="false" aria-controls="flush-collapse_' + ses.id +
                            '">' +
                            '<div class="accord-data"><div><i class="fa fa-users"></i>&nbsp; Session ID &nbsp;<span>UEV-' +
                            ses.session_id + '</span></div>' +
                            '<div>Earning <strong>Rs. ' + ses.Net_profit + '</strong></div>' +
                            '<div>' + ses.datetime['time'] + ',' + ses.datetime['date'] +
                            ' &nbsp;<a class="btn process-pay" href="#" ' +
                            'role="button">Details&nbsp;<i class="fa fa-arrow-down"></i></a></div>' +
                            '</div></button></h2><div id="flush-collapse_' + ses.id +
                            '" class="accordion-collapse collapse" aria-labelledby="flush-heading_' +
                            ses.id + '" data-bs-parent="#accordionFlushExample_' + ses.id + '">' +
                            '<div class="accordion-body"><div class="session-id-details"><div class="flexone"><p class="fw-bold">Dr.' +
                            ses.doc_name + '</p><p>Session with:</p><p>Patient: ' +
                            '<span class="fw-bold">' + ses.pat_name +
                            '</span></p></div><div class="flextwo">' +
                            '<div><span> Patient Paid</span> <span>+Rs. &nbsp;' + ses.price +
                            '</span></div>' +
                            '<div>Doctor ' + ses.doc_percent +
                            '%<span class="text-danger">-Rs. &nbsp;' + ses.doc_price +
                            '</span></div>' +
                            '<div>Credit card Fee <span class="text-danger">-Rs. &nbsp;' + parseFloat(
                                ses.card_fee) + '</span></div>' +
                            '<div>Net Profit <span>+Rs. &nbsp;' + ses.Net_profit + '</span></div>' +
                            '</div></div></div><div><div>'
                        );
                    });

                    $('#eVisit').append('<div class="accordion accordion-flush " id="accordionFlushExample">' +
                        '<div class="accordion-item mb-2">' +
                        '<h2 class="accordion-header" id="flush-heading">' +
                        '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" ' +
                        'data-bs-target="#flush-collapse" aria-expanded="false" aria-controls="flush-collapse">' +
                        '<div class="accord-data"><div><i class=""></i>&nbsp; Total Sessions: &nbsp;<span>' +
                        data.length + '</span></div>' +
                        '<div>Total Earning: <strong>Rs. ' + total +
                        '</strong></div>' +
                        '<div>' + date + ' &nbsp;</div>' +
                        '</div></button><div><div>'
                    );


                }
            });
        }

        function prescriptions() {
            var date = $('#pres_date').val();
            var ses_id = $('#pres_search_id').val();
            var msg = 'pres';
            $.ajax({
                type: 'GET',
                url: "{{ URL('/get_filtered_values') }}",
                data: {
                    date: date,
                    id: ses_id,
                    msg: msg
                },
                success: function(data) {
                    $('#precriptionItem').html('');
                    oid = 0;
                    var total = 0;
                    $.each(data, function(key, pres) {
                        total += parseFloat(pres.sale_price);
                        if (oid != pres.order_id) {
                            $('#precriptionItem').append(
                                '<div class="accordion accordion-flush " id="accordionFlushExample_' +
                                pres.order_id + '">' +
                                '<div class="accordion-item mb-2">' +
                                '<h2 class="accordion-header" id="flush-heading_' + pres.order_id +
                                '">' +
                                '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" ' +
                                'data-bs-target="#flush-collapse_' + pres.order_id +
                                '" aria-expanded="false" aria-controls="flush-collapse_' + pres
                                .order_id + '">' +
                                '<div class="accord-data"><div><i class="<i class="fa-solid fa-capsules"></i></i>&nbsp; Order ID: &nbsp;<span>' +
                                pres.order_id + '</span></div>' +
                                '<div><strong>Session ID : &nbsp;<span> UEV-' + pres.ses_id +
                                '</span></strong></div>' +
                                '<div>' + pres.datetime['time'] + ',' + pres.datetime['date'] +
                                ' &nbsp;<a class="btn process-pay" href="#" ' +
                                'role="button">Details&nbsp;<i class="fa fa-arrow-down"></i></a></div>' +
                                '</div></button></h2><div id="flush-collapse_' + pres.order_id +
                                '" class="accordion-collapse collapse" aria-labelledby="flush-heading_' +
                                pres.order_id + '" data-bs-parent="#accordionFlushExample_' + pres
                                .order_id + '">' +
                                '<div class="accordion-body" id="accorbody_' + pres.order_id +
                                '"><div class="p-3"><div class="row mb-1">' +
                                '<div class="col-md-4"><b>Product Name:</b> ' + pres.name +
                                '</div>' +
                                '<div class="col-md-4"><b>Product ID:</b> ' + pres.pro_id +
                                '</div>' +
                                '<div class="col-md-4"><b>Product type:</b> ' + pres.type +
                                '</div></div>' +
                                '<div class="row"><div class="col-md-4"><b>Dosage Days:</b> ' + pres
                                .usage + '</div>' +
                                '<div class="col-md-4"><b>Selling Price:</b> Rs. ' + pres.sale_price +
                                '</div>' +
                                '<div class="col-md-4"><b>Price:</b> Rs. ' + pres.price + '</div>' +
                                '</div></div></div></div></div></div>'
                            );
                        } else {
                            $('#accorbody_' + pres.order_id).append(
                                '<div class="p-3"><div class="row mb-1">' +
                                '<div class="col-md-4"><b>Product Name:</b> ' + pres.name +
                                '</div>' +
                                '<div class="col-md-4"><b>Product ID:</b> ' + pres.pro_id +
                                '</div>' +
                                '<div class="col-md-4"><b>Product type:</b> ' + pres.type +
                                '</div></div>' +
                                '<div class="row"><div class="col-md-4"><b>Dosage Days:</b> ' + pres
                                .usage + '</div>' +
                                '<div class="col-md-4"><b>Selling Price:</b> Rs. ' + pres.sale_price +
                                '</div>' +
                                '<div class="col-md-4"><b>Price:</b> Rs. ' + pres.price +
                                '</div></div></div>'
                            );
                        }
                        oid = pres.order_id;
                    });

                    $('#precriptionItem').append(
                        '<div class="accordion accordion-flush " id="accordionFlushExample">' +
                        '<div class="accordion-item mb-2">' +
                        '<h2 class="accordion-header" id="flush-heading">' +
                        '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" ' +
                        'data-bs-target="#flush-collapse" aria-expanded="false" aria-controls="flush-collapse">' +
                        '<div class="accord-data"><div><i class=""></i>&nbsp; Total Prescriptions: &nbsp;<span>' +
                        data.length + '</span></div>' +
                        '<div>Total Earning: <strong>Rs. ' + total +
                        '</strong></div>' +
                        '<div>' + date + ' &nbsp;</div>' +
                        '</div></button><div><div>'
                    );

                }
            });
        }

        function generate_pdf(mode) {
            if (mode == 'ses') {
                $('#fdate').val('');
                $('#fid').val('');
                var date = $('#session_date').val();
                var ses_id = $('#session_search_id').val();
                var url = '/generate-sessionspdf';
                $('#fdate').val(date);
                $('#fid').val(ses_id);
                $('#filter').attr('action', url);
                $('#filter').submit();
            } else if (mode == 'pres') {
                $('#fdate').val('');
                $('#fid').val('');
                var date = $('#pres_date').val();
                var ses_id = $('#pres_search_id').val();
                var url = '/generate-prescriptionspdf';
                $('#fdate').val(date);
                $('#fid').val(ses_id);
                $('#filter').attr('action', url);
                $('#filter').submit()
            } else if (mode == 'on') {
                $('#fdate').val('');
                $('#fid').val('');
                var date = $('#online_date').val();
                var url = '/generate-onlinepdf';
                $('#fdate').val(date);
                $('#filter').attr('action', url);
                $('#filter').submit()
            }
        }

        function generate_csv(mode) {
            if (mode == 'ses') {
                $('#fdate').val('');
                $('#fid').val('');
                var date = $('#session_date').val();
                var ses_id = $('#session_search_id').val();
                var url = '/generate-sessionscsv';
                $('#fdate').val(date);
                $('#fid').val(ses_id);
                $('#filter').attr('action', url);
                $('#filter').submit();
            } else if (mode == 'pres') {
                $('#fdate').val('');
                $('#fid').val('');
                var date = $('#pres_date').val();
                var ses_id = $('#pres_search_id').val();
                var url = '/generate-prescriptionscsv';
                $('#fdate').val(date);
                $('#fid').val(ses_id);
                $('#filter').attr('action', url);
                $('#filter').submit()
            } else if (mode == 'on') {
                $('#fdate').val('');
                $('#fid').val('');
                var date = $('#online_date').val();
                var url = '/generate-onlinecsv';
                $('#fdate').val(date);
                $('#filter').attr('action', url);
                $('#filter').submit()
            }
        }

        function online() {
            var date = $('#online_date').val();
            var msg = 'online';

            $.ajax({
                type: 'GET',
                url: "{{ URL('/get_filtered_values') }}",
                data: {
                    date: date,
                    msg: msg
                },
                success: function(data) {
                    $('#onlineItem').html('');
                    var total = 0;
                    $.each(data, function(key, on) {
                        total += parseFloat(on.sale_price);
                        $('#onlineItem').append(
                            '<div class="accordion accordion-flush " id="accordionFlushExample_' +
                            on.id + '">' +
                            '<div class="accordion-item mb-2">' +
                            '<h2 class="accordion-header" id="flush-heading_' + on.id + '">' +
                            '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" ' +
                            'data-bs-target="#flush-collapse_' + on.id +
                            '" aria-expanded="false" aria-controls="flush-collapse_' + on.id +
                            '">' +
                            '<div class="accord-data"><div><i class="<i class="fa-solid fa-capsules"></i></i>&nbsp; Product Name: &nbsp;<span>' +
                            on.name + '</span></div>' +
                            '<div><strong></strong></div>' +
                            '<div>' + on.datetime['time'] + ',' + on.datetime['date'] +
                            ' &nbsp;<a class="btn process-pay" href="#" ' +
                            'role="button">Details&nbsp;<i class="fa fa-arrow-down"></i></a></div>' +
                            '</div></button></h2><div id="flush-collapse_' + on.id +
                            '" class="accordion-collapse collapse" aria-labelledby="flush-heading_' +
                            on.id + '" data-bs-parent="#accordionFlushExample_' + on.id + '">' +
                            '<div class="accordion-body"><div class="p-3"><div class="row mb-1">' +
                            '<div class="col-md-6"><b>Order ID:</b> ' + on.order_id + '</div>' +
                            '<div class="col-md-6"><b>Product ID:</b> ' + on.product_id +
                            '</div></div>' +
                            '<div class="row"><div class="col-md-6"><b>Selling Price:</b> Rs.' + on
                            .sale_price + '</div>' +
                            '<div class="col-md-6"><b>Price:</b> Rs.' + on.price + '</div>' +
                            '</div></div></div></div></div></div>'

                        );
                    });
                    $('#onlineItem').append(
                        '<div class="accordion accordion-flush " id="accordionFlushExample">' +
                        '<div class="accordion-item mb-2">' +
                        '<h2 class="accordion-header" id="flush-heading">' +
                        '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" ' +
                        'data-bs-target="#flush-collapse" aria-expanded="false" aria-controls="flush-collapse">' +
                        '<div class="accord-data"><div><i class=""></i>&nbsp; Total Items: &nbsp;<span>' +
                        data.length + '</span></div>' +
                        '<div>Total Earning: <strong>Rs. ' + total +
                        '</strong></div>' +
                        '<div>' + date + ' &nbsp;</div>' +
                        '</div></button><div><div>'
                    );
                }
            });
        }

        $(function() {
            $('input[name="session_date_filter"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
            $('input[name="session_date_filter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
                sessions();
            });
            $('input[name="session_date_filter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });

        $(function() {
            $('input[name="session_date"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
            $('input[name="session_date"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
                sessions();
            });
            $('input[name="session_date"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                sessions();
            });
        });
        $(function() {
            $('input[name="pres_date"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
            $('input[name="pres_date"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
                prescriptions();
            });
            $('input[name="pres_date"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                prescriptions();
            });
        });
        $(function() {
            $('input[name="online_date"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
            $('input[name="online_date"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
                online();
            });
            $('input[name="online_date"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                online();
            });
        });
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid finance-screen-wrapper">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline p-0">
                            <h3>Finance</h3>
                            <div class="col-md-4 ms-auto p-0">

                            </div>
                        </div>
                        <div class="p-0">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12">
                                    <div class="first-box">
                                        <i class="fa-solid fa-dollar-sign"></i><span class="tot-font">&nbsp; Earnings</span>
                                        <div class="second-box mt-2">
                                            <div class="total tot-font d-flex justify-content-between px-2">
                                                <span> Total</span> <span>Rs. {{ $totalBalance }}</span>
                                            </div>
                                            <div class="total d-flex justify-content-between px-2 py-1">
                                                This Month <span class="">Rs. {{ $totalMonthBalance }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between px-2 py-1">
                                                Today<span class="">Rs. {{ $totalTodayBalance }} </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div class="Graph-sec">
                                        <div class="graph-details">
                                            <h6>
                                                <span class="text-primary details-bold"> E-Visit Earning</span>
                                                <br />
                                                <span id="evisit_ear"></span>
                                            </h6>
                                            <hr>
                                            <!-- <p>Rs. &nbsp;849.00</p> -->

                                            <h6>
                                                <span class="text-danger details-bold">
                                                    Prescription Earning</span>
                                                <br />
                                                <span id="pres_ear"></span>
                                            </h6>
                                            <hr>

                                            <h6>
                                                <span class="text-success details-bold"> Online Earning</span>
                                                <br />
                                                <span id="online_ear"></span>
                                            </h6>
                                        </div>
                                        <div class="graph w-100">
                                            <canvas id="myChart" style="width:100% !important;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <section class="sec-2">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="nav-item col-md-3 col-12">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#eVisits">E-Visit</a>
                                    </li>
                                    <li class="nav-item col-md-3 col-12">
                                        <a class="nav-link" data-bs-toggle="tab" href="#precriptionItems">Prescription
                                            Items</a>
                                    </li>
                                    <li class="nav-item col-md-3 col-12">
                                        <a class="nav-link" data-bs-toggle="tab" href="#onlineItems">Online Items</a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">


                                    <div class="tab-pane container active tab-pad" id="eVisits">
                                        <div class="row mb-2">
                                            <div class="col-md-6 d-flex">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <input type="text" class="form-control mb-2" name="session_date"
                                                            id="session_date" placeholder="filter record by date-range">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control mb-2"
                                                            name="session_search_id" id="session_search_id"
                                                            onchange="sessions()" placeholder="Search By SessionID">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6 text-end">
                                                <div class="dropdown">
                                                    <button class="btn process-pay dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Download Data
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="generate_csv('ses')">Download As CSV</a></li>
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="generate_pdf('ses')" id="ses">Download As
                                                                PDF</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- -------------Accordion--------------- -->
                                        <div id="eVisit">
                                            @foreach ($getSessionTotalSessions as $sessions)
                                                <div class="accordion accordion-flush "
                                                    id="accordionFlushExample_{{ $sessions->id }}">
                                                    <div class="accordion-item mb-2">
                                                        <h2 class="accordion-header" id="flush-heading_{{ $sessions->id }}">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#flush-collapse_{{ $sessions->id }}"
                                                                aria-expanded="false"
                                                                aria-controls="flush-collapse_{{ $sessions->id }}">
                                                                <div class="accord-data">
                                                                    <div><i class="fa fa-users"></i>&nbsp; Session ID
                                                                        &nbsp;<span>UEV-{{ $sessions->session_id }}</span>
                                                                    </div>
                                                                    <div>Earning
                                                                        <strong>Rs. {{ $sessions->Net_profit }}</strong></div>
                                                                    <div>
                                                                        {{ $sessions->datetime['time'] }},{{ $sessions->datetime['date'] }}
                                                                        &nbsp;</div>
                                                                    <a class="btn process-pay d-flex align-items-baseline"
                                                                        href="#" role="button">Details&nbsp;<i
                                                                            class="fa fa-arrow-down"></i></a>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <!-- <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                              <div class="accord-data">
                                                <div><i class="fa fa-users"></i>&nbsp; Session ID &nbsp;<span>UEV-326</span></div>
                                                <div>Earning <strong>Rs. 80.00</strong></div>
                                                <div>11am,Aug 19th 2022 &nbsp;<a class="btn process-pay" href="#" role="button">Details&nbsp;<i class="fa fa-arrow-down"></i></a></div>

                                              </div>
                                            </button>
                                          </h2> -->
                                                        <div id="flush-collapse_{{ $sessions->id }}"
                                                            class="accordion-collapse collapse"
                                                            aria-labelledby="flush-heading_{{ $sessions->id }}"
                                                            data-bs-parent="#accordionFlushExample_{{ $sessions->id }}">
                                                            <div class="accordion-body">
                                                                <div class="session-id-details">
                                                                    <div class="flexone">
                                                                        <p class="fw-bold">Dr.{{ $sessions->doc_name }}
                                                                        </p>
                                                                        <p>Session with</p>
                                                                        <p>Patient: <span
                                                                                class="fw-bold">{{ $sessions->pat_name }}</span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="flextwo">
                                                                        <div><span> Patient Paid</span> <span>+Rs.
                                                                                &nbsp;{{ $sessions->price }}</span></div>
                                                                        <div>Doctor {{ $sessions->doc_percent }}%<span
                                                                                class="text-danger">-Rs.
                                                                                &nbsp;{{ $sessions->doc_price }}</span>
                                                                        </div>
                                                                        <div>Net Profit <span>+Rs.
                                                                                &nbsp;{{ $sessions->Net_profit }}</span>
                                                                        </div>


                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach
                                            {{ $getSessionTotalSessions->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>

                                    <div class="tab-pane container fade" id="precriptionItems">
                                        <div class="row mb-2">
                                            <div class="col-md-6 d-flex">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <input type="text" class="form-control" name="pres_date"
                                                            id="pres_date" placeholder="filter record by date-range">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control mb-2"
                                                            name="pres_search_id" id="pres_search_id"
                                                            onchange="prescriptions()" placeholder="Search By SessionID">
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control me-1" name=""
                                                    id="" placeholder="Search" hidden="">


                                            </div>
                                            <div class="col-md-6 text-end">
                                                <div class="dropdown">
                                                    <button class="btn process-pay dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Download Data
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="generate_csv('pres')">Download As CSV</a></li>
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="generate_pdf('pres')">Download As PDF</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="tab-pane container fade" id="onlineItems">
                                        <div class="row mb-2">
                                            <div class="col-md-3 d-flex">
                                                <input type="text" class="form-control me-1" name=""
                                                    id="" placeholder="Search" hidden="">
                                                <input type="text" class="form-control" name="online_date"
                                                    id="online_date" placeholder="filter record by date-range">
                                            </div>
                                            <div class="col-md-9 text-end">
                                                <div class="dropdown">
                                                    <button class="btn process-pay dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Download Data
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="generate_csv('on')">Download As CSV</a></li>
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="generate_pdf('on')">Download As PDF</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- -------------Accordion--------------- -->
                                        <div id="onlineItem">
                                            @foreach ($OnlineItems as $ot)
                                                <div class="accordion accordion-flush "
                                                    id="accordionFlushExample_{{ $ot->id }}">
                                                    <div class="accordion-item mb-2">

                                                        <h2 class="accordion-header"
                                                            id="flush-heading_{{ $ot->id }}">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#flush-collapse_{{ $ot->id }}"
                                                                aria-expanded="false"
                                                                aria-controls="flush-collapse_{{ $ot->id }}">
                                                                <div class="accord-data">
                                                                    <div>Product Name:
                                                                        &nbsp;<span>{{ $ot->name }}</span></div>
                                                                    <!-- <div>Selling Price: <strong>Rs. 80.00</strong></div> -->
                                                                    <div>
                                                                        {{ $ot->datetime['time'] }},{{ $ot->datetime['date'] }}
                                                                        &nbsp;<a class="btn process-pay" href="#"
                                                                            role="button">Details&nbsp;<i
                                                                                class="fa fa-arrow-down"></i></a></div>

                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="flush-collapse_{{ $ot->id }}"
                                                            class="accordion-collapse collapse"
                                                            aria-labelledby="flush-heading_{{ $ot->id }}"
                                                            data-bs-parent="#accordionFlushExample_{{ $ot->id }}">
                                                            <div class="accordion-body">
                                                                <div class="p-3">
                                                                    <!-- <div class="flexone"><p class="fw-bold">Dr.Haris Rohail</p><p>Session with:</p><p>Patient: <span class="fw-bold">Abdul Musavir</span></p>
                                                </div> -->
                                                                    <div class="row mb-1">
                                                                        <div class="col-md-6"><b>Order ID:</b>
                                                                            {{ $ot->order_id }}</div>
                                                                        <div class="col-md-6"><b>Product ID:</b>
                                                                            {{ $ot->product_id }}</div>

                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6"><b>Selling Price:</b>
                                                                            Rs. {{ $ot->sale_price }}</div>
                                                                        <div class="col-md-6"><b>Price:</b>
                                                                            Rs. {{ $ot->price }}</div>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach
                                            {{ $OnlineItems->links('pagination::bootstrap-4') }}
                                        </div>
                                        <!-- -------------AccordionEND--------------- -->
                                    </div>
                                </div>
                            </section>
                            <!-- <nav aria-label="..." class="float-end pe-3">
                              <ul class="pagination">
                                <li class="page-item disabled">
                                  <span class="page-link">Previous</span>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item active" aria-current="page">
                                  <span class="page-link">2</span>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                  <a class="page-link" href="#">Next</a>
                                </li>
                              </ul>
                            </nav> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    </div>
    <form type="hidden" id="filter" action="" method="POST">
        @csrf
        <input type="hidden" id="fdate" name="date" value="">
        <input type="hidden" id="fid" name="id" value="">
    </form>
@endsection
