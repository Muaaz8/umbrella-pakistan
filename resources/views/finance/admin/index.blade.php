@extends('layouts.admin')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Add Schedule</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">

                    <div class="body">

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NAME</th>
                                        <th>STATUS</th>
                                        <th>DATE</th>
                                        <th>PRICE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td class="txt-oflo">Ipsum is simply</td>
                                        <td><span class="label label-success">SALE</span> </td>
                                        <td class="txt-oflo">Feb 11, 2017</td>
                                        <td><span class="text-success">$25</span></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td class="txt-oflo">Lorem Ipsum is</td>
                                        <td><span class="label label-info">EXTENDED</span></td>
                                        <td class="txt-oflo">March 29, 2017</td>
                                        <td><span class="text-info">$1234</span></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td class="txt-oflo">Lorem Ipsum is simply</td>
                                        <td><span class="label label-danger">TAX</span></td>
                                        <td class="txt-oflo">April 21, 2017</td>
                                        <td><span class="text-danger">-$204</span></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td class="txt-oflo">Hosting press html</td>
                                        <td><span class="label label-warning">SALE</span></td>
                                        <td class="txt-oflo">Jun 22, 2017</td>
                                        <td><span class="text-success">$24</span></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td class="txt-oflo">Lorem is simply</td>
                                        <td><span class="label label-success">member</span></td>
                                        <td class="txt-oflo">July 20, 2017</td>
                                        <td><span class="text-success">$21</span></td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td class="txt-oflo">Lorem Ipsum simply</td>
                                        <td><span class="label label-success">SALE</span> </td>
                                        <td class="txt-oflo">July 21, 2017</td>
                                        <td><span class="text-danger">-$12</span></td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td class="txt-oflo">Lorem Ipsum is simply</td>
                                        <td><span class="label label-warning">member</span></td>
                                        <td class="txt-oflo">July 21, 2017</td>
                                        <td><span class="text-success">$54</span></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</section>
@endsection
