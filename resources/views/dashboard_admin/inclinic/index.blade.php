@extends('layouts.dashboard_admin')

@section('meta_tags')
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
<style>
    .searchable-list {
        max-height: 200px;
        overflow-y: auto;
    }

    .table-height {
        max-height: 300px;
        overflow-y: auto;
    }

    .table-height::-webkit-scrollbar {
        width: 5px;
    }

    .table-height::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 5px;
    }

    .table-height::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .table-height::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

</style>
@endsection

@section('page_title')
<title>UHCS - Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
            <div class="col-md-12">
                <div class="row m-auto">
                    <div class="d-flex flex-wrap justify-content-between align-items-baseline p-0">
                        <h3>All In Clinics Patients</h3>
                        <div class="col-md-4 col-sm-6 col-12 p-0">
                            <div class="input-group">
                                <a href="{{ route('in_clinics_create') }}" class="btn process-pay">Add new</a>
                                <div class="btn process-pay mx-2" data-bs-toggle="modal"
                                    data-bs-target="#find-user-in-table">Existing Patients</div>
                            </div>
                        </div>
                    </div>
                    <div class="wallet-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Patient Name</th>
                                    <th scope="col">Patient Number</th>
                                    <th scope="col">Patient Email</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->user->name}}</td>
                                    <td>{{$item->user->phone_number}}</td>
                                    <td>{{$item->user->email}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td data-label="Action">
                                        <a>
                                            <form action="#" method="post">
                                                @method('DELETE')
                                                @csrf
                                                <input class="btn btn-danger" type="submit" value="Delete" />
                                            </form>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="m-auto text-center for-empty-div">
                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                            <h6>No Related Products To Show</h6>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $data->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="find-user-in-table" tabindex="-1" aria-labelledby="find-user-in-tableLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Patients List</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="search-view">
                    <div class="container mt-2">
                        <form id="search-form" action="#">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" id="search-input" class="form-control" placeholder="Search User" oninput="searchUsers()">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="container mt-2 table-height">
                        <table class="table table-hover mb-2" id="user-table">
                            <thead>
                                <tr scope="row">
                                    <th class="p-2">Id</th>
                                    <th class="p-2">Patient Name</th>
                                    <th class="p-2">Patient Number</th>
                                    <th class="p-2">Patient Email</th>
                                    <th class="p-2">Action</th>
                                </tr>
                            </thead>
                            <tbody id="user-table-body">
                                @foreach ($patients as $patient)
                                <tr>
                                    <td class="p-2">{{$patient->id}}</td>
                                    <td class="p-2">{{$patient->name}}</td>
                                    <td class="p-2">{{$patient->phone_number}}</td>
                                    <td class="p-2">{{$patient->email}}</td>
                                    <td class="p-2">
                                        <button class="btn process-pay" onclick="showUserForm('{{ $patient->id }}', '{{ $patient->name }}', '{{ $patient->phone_number }}', '{{ $patient->email }}')">Select</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="user-detail-view" style="display: none;">
                    <button class="btn btn-secondary mx-3 my-2" onclick="showSearchView()"><i class="fas fa-arrow-left"></i> Back</button>
                    <form id="user-detail-form" action="{{ route('in_clinics_store') }}" method="post">
                        @csrf
                        <div class="row container mb-3">
                            <input type="hidden" name="user_id" id="user-id" value="">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="user-name" class="form-label">Name</label>
                                    <input type="text" id="user-name" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="user-email" class="form-label">Email</label>
                                    <input type="email" id="user-email" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="user-phone" class="form-label">Phone</label>
                                    <input type="text" id="user-phone" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="reason" class="form-label">Reason</label>
                                    <textarea id="reason" name="reason" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="payment" class="form-label">Payment</label>
                                    <div class="d-flex justify-content-between border border-1">
                                        <div>
                                            <input type="radio" name="payment" id="card" value="card" disabled>
                                            <label for="card">Card</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="payment" id="easypaisa" value="easypaisa">
                                            <label for="easypaisa">Easy Paisa</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="payment" id="cash" value="cash">
                                            <label for="cash">Cash</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn process-pay w-100">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function searchUsers() {
        const searchValue = document.getElementById('search-input').value.toLowerCase();
        const rows = document.querySelectorAll('#user-table tbody tr');
        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            const name = cells[1].textContent.toLowerCase();
            const email = cells[3].textContent.toLowerCase();
            row.style.display = (name.includes(searchValue) || email.includes(searchValue)) ? '' : 'none';
        });
    }

    function showUserForm(id, name, phone, email) {
        document.getElementById('search-view').style.display = 'none';
        document.getElementById('user-detail-view').style.display = 'block';

        document.getElementById('user-id').value = id;
        document.getElementById('user-name').value = name;
        document.getElementById('user-email').value = email;
        document.getElementById('user-phone').value = phone;
    }

    function showSearchView() {
        document.getElementById('user-detail-view').style.display = 'none';
        document.getElementById('search-view').style.display = 'block';
    }
</script>


@endsection
