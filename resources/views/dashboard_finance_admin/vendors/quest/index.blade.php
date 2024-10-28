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
                                <div class="col-md-2">
                                    <div class="vendor_Info text-center">
                                        <img src="{{ asset($vendor->image) }}" alt="">
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class=" py-3">
                                    <h2><span></span> {{$vendor->name}}</h2>
                                    <h4>Lab Tests Vendor</h4>
                                    </div>
                                </div>

                            </div>

                            <section class="total__main">
                                <div class="row">
                                    <div class="col-md-4 mb-3 m-auto">
                                        <div class="fi__main__card_t">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2"><i class="fa-solid fa-cart-shopping fi_cart_Iconss"></i></div>
                                                <div>
                                                    <p class="tt_sale">TOTAL Earning</p>
                                                    <p class="card_fi_price">$&nbsp{{ $vendor->total }}</p>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="fi__main__card">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2"><i class="fa-solid fa-hand-holding-hand fi_cart_Icons"></i></div>
                                                <div>
                                                    <p class="t_sale">Total Amount Payable</p>
                                                    <p class="card_fi_price">$&nbsp{{ $vendor->pay }}</p>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="fi__main__card">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2"><i class="fa-solid fa-handshake fi_cart_Icons"></i></div>
                                                <div>
                                                    <p class="t_sale">Total Amount Paid</p>
                                                    <p class="card_fi_price">$&nbsp{{ $vendor->paid }}</p>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="fi__main__card">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2"><i class="fa-solid fa-handshake fi_cart_Icons"></i></div>
                                                <div>
                                                    <p class="t_sale">Total Profit</p>
                                                    <p class="card_fi_price">$&nbsp{{ $vendor->profit }}</p>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </section>

                            <section class="shortcuts__main">
                                <div class="row">
                                    <div class="col-md-4">
                                        <button class="finance__shortcut_btn" onclick="window.location.href='/quest/amount/Invoices'">Invoices</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="finance__shortcut_btn" onclick="window.location.href='/quest/amount/Payable'">Payable</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="finance__shortcut_btn" onclick="window.location.href='/quest/amount/Paid'">Paid</button>
                                    </div>
                                </div>

                            </section>
                            
                        </div>
                    </div>
                </div>
            </div>
@endsection
