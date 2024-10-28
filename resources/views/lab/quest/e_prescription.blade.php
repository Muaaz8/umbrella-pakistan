@extends('layouts.admin')
@section('content')
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            <h2>E-Prescription</h2>
        </div>
        <div class="card">
            <div class="body">
                <div style="overflow-x:hidden">
                    <table class="table" id="table">

                        <thead>
                            <!-- <th scope="col">S.No</th> -->
                            <th scope="col">Order ID</th>
                            <th scope="col">Date</th>
                            <th scope="col">Action</th>
                        </thead>
                        <tbody>
                            @php
                            $counter = 1;
                            @endphp
                            @forelse ($med as $medicine)
                            <tr>
                                <!-- <td data-label="S.No" scope="row">{{ $counter }}</td> -->
                                <td data-label="Order ID">{{ $medicine->order_main_id }}</td>
                                <td data-label="Date">{{ $medicine->created_at }}</td>

                                <td data-label="Action"><a target="_blank"
                                        href="{{\App\Helper::get_files_url($medicine->filename) }}">
                                        <button class="orders-view-btn">E-Prescription</button>
                                    </a>
                                </td>
                            </tr>
                            @php
                            $counter++;
                            @endphp
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6>No Prescription To Show</h6>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('script')
<script src="{{ asset('asset_admin/js/pages/index.js') }}"></script>
<script src="{{ asset('asset_admin/js/pages/charts/sparkline.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('asset_admin/js/datatables/datatables.min.css') }}" />

<script type="text/javascript" src="{{ asset('asset_admin/js/datatables/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('asset_admin/js/datatables/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ asset('asset_admin/js/datatables/datatables.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.tblData').DataTable();
    });
</script>
@endsection
