@extends('layouts.admin')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Quest Labtests</h2>
                <div class="dropdown float-right">
                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">
                    Select Type
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ url('/viewQuestLabTest') }}">All</a>
                    <a class="dropdown-item" href="{{ url('/viewAllQuestLabTest') }}">Priced</a>
                </div>
                </div>
            </div>

            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            @include('flash::message')
                        </div>
                    </div>
                    <table class="table-responsive table table-hover table-bordered tblData">
                        <thead>
                            <tr>
                                <th style="width:10%">Test Code</th>
                                <th style="width:12%">Service Name</th>
                                <th style="width:10%">Full Name</th>
                                <th style="width:8%">Price</th>
                                <th style="width:8%">Sale Price</th>
                                <th style="width:10%">Category</th>
                                <th style="width:8%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                                <tr>
                                    <td>{{ $item->TEST_CD }}</td>
                                    <td>{{ $item->DESCRIPTION }}</td>
                                    <td>{{ $item->TEST_NAME }}</td>
                                    <td>{{ $item->PRICE }}</td>
                                    <td>{{ $item->SALE_PRICE }}</td>
                                    <td>{{ $item->main_category_name }}</td>
                                    <td>
                                        <center> <a href="/editQuestLabTest/{{ $item->TEST_CD }}" class=''><i
                                                    class="fa fa-edit"></i></a></center>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">Recrods not found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/datatables.min.css" />

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/datatables.min.js">
    </script>
    <script>
        $(document).ready(function() {
            $('.tblData').DataTable();
        });
    </script>
@endsection
