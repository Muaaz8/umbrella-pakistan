<style>
    .heading p {
        /* margin: 5px 0px; */
        font-weight: bold;
    }

    .heading_row {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

</style>
<div class="row col-md-12">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header" style=" font-size: 1.5rem; margin-top: 30px; ">Billing Details</div>
            <div class="card-body">
                @foreach ($data['billing'] as $item)
                    @php
                        $vals = explode('|', $item);
                    @endphp
                    <div class="row heading_row">
                        <div class="col heading">
                            <p>{{ ucwords(str_replace('_', ' ', str_replace("'", '', $vals[0]))) }}</p>
                        </div>
                        <div class="col answer">
                            <p>{{ ucwords($vals[1]) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header" style=" font-size: 1.5rem; margin-top: 30px; ">Order Tracking </div>
            <div class="card-body">
                <div class="row heading_row">
                    <div class="col heading">
                        <p>Order State</p>
                    </div>
                    <div class="col answer">
                        <p>{{ ucwords($data['order_data']->order_state) }}</p>
                    </div>
                </div>
                <div class="row heading_row">
                    <div class="col heading">
                        <p>Order ID</p>
                    </div>
                    <div class="col answer">
                        <p>{{ ucwords($data['order_data']->order_id) }}</p>
                    </div>
                </div>

                <!-- @foreach ($data['order_sub_id'] as $item)
                    @php
                        $vals = explode('|', $item);
                    @endphp
                    <div class="row heading_row">
                        <div class="col heading">
                            <p><?php
                            // $pharName = ucwords(str_replace('_', ' ', str_replace("'", '', $vals[0])));

                            // if ($pharName === 'PHAR') {
                            //     echo 'Pharmacy';
                            // } elseif ($pharName === 'LBT') {
                            //     echo 'Lab Test';
                            // } elseif ($pharName === 'IMG') {
                            //     echo 'Imaging';
                            // } elseif ($pharName === 'PPHAR') {
                            //     echo 'Prescribed Pharmacy';
                            // } elseif ($pharName === 'PLBT') {
                            //     echo 'Prescribed Lab Test';
                            // } elseif ($pharName === 'PIMG') {
                            //     echo 'Prescribed Imaging';
                            // } elseif ($pharName === 'UMB') {
                            //     echo 'Umbrella Master ID';
                            // }
                            ?></p>
                        </div>
                    </div>
                @endforeach -->
            </div>
        </div>
    </div>
</div>
<div class="row col-md-12">
    <div class="card">
        <div class="card-header" style=" font-size: 1.5rem; margin-top: 30px; ">Products</div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Dosage and Imaging Location</th>
                        <th>Price</th>
                        <th>Cost</th>
                        {{-- <th>View Report</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @php
                        $priceTotal = 0;
                        $providerFee = 0;
                        $itemCount = 0
                    @endphp
                    @foreach ($orderMeds as $med)
                    <tr>
                    <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                    <td data-label="Product Name">{{ $med->name }}</td>
                    <td data-label="Quantity">{{ $med->usage }}</td>
                    <td data-label="Price">${{ $med->update_price }}</td>
                    @php
                        $priceTotal = $priceTotal + $med->update_price;
                        $itemCount += 1;
                    @endphp
                    {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a></td> --}}
                    </tr>
                    @endforeach
                    @foreach ($orderLabs as $labs)
                    <tr>
                    <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                    <td data-label="Product Name">{{ $labs->DESCRIPTION }}</td>
                    <td data-label="Quantity"></td>
                    <td data-label="Price">${{ $labs->SALE_PRICE }}</td>
                    @php
                        $priceTotal = $priceTotal + $labs->SALE_PRICE;
                        $itemCount += 1;
                    @endphp
                    {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a></td> --}}
                    </tr>
                    @endforeach
                    @foreach ($ordercntLabs as $labs)
                        <tr>
                        <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                        <td data-label="Product Name">{{ $labs->DESCRIPTION }}</td>
                        <td data-label="Quantity"></td>
                        <td data-label="Price">${{ $labs->SALE_PRICE }}</td>
                        @php
                            $priceTotal = $priceTotal + $labs->SALE_PRICE;
                            $itemCount += 1;
                            $providerFee = 6;
                        @endphp
                        {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a></td> --}}
                        </tr>
                    @endforeach
                    @foreach ($orderImagings as $image)
                    <tr>
                    <!-- <th><i class="fa-solid fa fa-flask fs-4"></i></th> -->
                    <td data-label="Product Name">{{ $image->name }}</td>
                    <td data-label="Quantity">{{ $image->location }}</td>
                    <td data-label="Price">${{ $image->price }}</td>
                    @php
                        $priceTotal = $priceTotal + $image->price;
                        $itemCount += 1;
                    @endphp
                    {{-- <td data-label=""><a href="{{ url('/viewQuestTestReport/1') }}">View Report</a></td> --}}
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style=" font-weight: bold; ">
                        <td colspan="2"></td>
                        <td colspan="1">SUBTOTAL</td>
                        <td>${{ $priceTotal }}</td>
                    </tr>
                    <tr style=" font-weight: bold; ">
                        <td colspan="2"></td>
                        <td colspan="1">TAX 25%</td>
                        <td>$0.00</td>
                    </tr>
                    <tr style=" font-weight: bold; ">
                        <td colspan="2"></td>
                        <td colspan="1">GRAND TOTAL</td>
                        <td>${{ (int)$priceTotal+(int)$providerFee }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="row col-md-12">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header" style=" font-size: 1.5rem; margin-top: 30px; ">Shipping Details</div>
            <div class="card-body">
                @foreach ($data['shipping'] as $item)
                    @php
                        $vals = explode('|', $item);
                    @endphp
                    <div class="row heading_row">
                        <div class="col heading">
                            <p>{{ ucwords(str_replace('_', ' ', str_replace("'", '', $vals[0]))) }}</p>
                        </div>
                        <div class="col answer">
                            <p>{{ ucwords($vals[1]) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header" style=" font-size: 1.5rem; margin-top: 30px; ">Payment Method</div>
            <div class="card-body">
                    <div class="row heading_row">
                        <div class="col heading">
                            <p>Payment Method</p>
                        </div>
                        <div class="col answer">
                            <p> {{ $data['payment_method'] }} </p>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
