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
                <div class="col-11 m-auto">
                <div class="account-setting-wrapper bg-white">
                    <h4 class="pb-4 border-bottom">Add New Invoice</h4>
                    <form action="/add/quest/invoice/{{$invoice->id}}" method="POST">
                        @csrf
                    <div class="py-2">
                        <div class="row py-2">
                            <div class="col-md-6">
                                <label for="firstname">Order Number</label>
                                <input type="text" name="order_id" id="order_id" value="{{$invoice->Order_id}}" class="bg-light form-control" placeholder="" required readonly>
                            </div>
                            <div class="col-md-6 pt-md-0 pt-3">
                                <label for="lastname">Invoice No</label>
                                <input type="text" id="Invoice_number" name="Invoice_number" value="{{$invoice->Invoice_number}}" class="bg-light form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="row py-2">
                            <div class="col-md-6">
                                <label for="email">Services</label>
                                <input type="text" id="Services" name="Services" value="{{$invoice->Services}}" class="bg-light form-control" placeholder="" required>
                            </div>
                            <div class="col-md-6 pt-md-0 pt-3">
                                <label for="phone">CPT</label>
                                <input type="number" id="CPT" name="CPT" value="{{$invoice->CPT}}" class="bg-light form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="row py-2">
                            <div class="col-md-6">
                                <label for="cpass">Service Code</label>
                                <input type="text" id="Service_code" name="Service_code" value="{{$invoice->Service_code}}" class="bg-light form-control" placeholder="" required>
    
                            </div>
                            <div class="col-md-6 pt-md-0 pt-3">
                                <label for="newpass">Amount</label>
                                <input type="text" id="Amount" name="Amount" value="{{$invoice->Amount}}" class="bg-light form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="row py-2">
                            <div class="col-md-6">
                                <label for="cpass">Draw Fee</label>
                                <input type="text" id="Draw_fee" name="Draw_fee" value="{{$invoice->Draw_fee}}" class="bg-light form-control" placeholder="" required>
    
                            </div>
                            <div class="col-md-6 pt-md-0 pt-3">
                                <label for="newpass">Profit</label>
                                <input type="text" id="Profit" name="Profit" value="{{$invoice->Profit}}" class="bg-light form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="row py-2">
                            <div class="col-md-6">
                                <label for="cpass">Select Status</label>
                                <select name="Status" id="Status" class="bg-light form-control" required>
                                    <option value="Unpaid" selected>Unpaid</option>
                                    <option value="Paid">Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="py-3 pb-4">
                            <button type="submit" class="btn btn-primary mr-3">Add</button>
                            <button class="btn border button">Cancel</button>
                        </div>
    
                    </div>
                    </form>
                </div>
            </div>
            </div>
@endsection
