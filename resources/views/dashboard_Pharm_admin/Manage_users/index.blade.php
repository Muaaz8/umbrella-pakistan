@extends('layouts.dashboard_Pharm_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Pharmacy Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script>
        function email_modal_function(a) {
            var email = $(a).attr('class');
            var breakClasses = email.split(' ');
            $('#email').val(breakClasses[1]);
            $('#send_email_modal').modal('show');
        }
        var input = document.getElementById("search");
        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                document.getElementById("search_btn").click();
            }
        });

        function search(array) {
            var val = $('#search').val();
            if (val == '') {
                window.location.href = '/pharmacy/admin/manage/users';
            } else {
                $('#editors').empty();
                console.log(array);
                $.each(array, function(key, arr) {
                    if (arr.name.match(val) || arr.email.match(val) || arr.status.match(val)) {
                        $('#editors').append('<tr id="editor_' + arr.id + '"></tr>');
                        $('#editor_' + arr.id).append('<td data-label="Name">' + arr.name + ' ' + arr.last_name +
                            '</td>' +
                            '<td data-label="Email">' + arr.email + '</td>'
                        );
                        if (arr.active == '1') {
                            $('#editor_' + arr.id).append(
                                '<td data-label="Status"><select onchange="status_change(' + arr.id +
                                ')" class="form-select w-75 m-sm-0 ad_act_dact m-md-auto" aria-label="Default select example">' +
                                '<option selected>Active</option><option >Deactivate</option></select></td>'
                            );
                        } else {
                            $('#editor_' + arr.id).append(
                                '<td data-label="Status"><select onchange="status_change(' + arr.id +
                                ')" class="form-select w-75 m-sm-0 ad_act_dact m-md-auto" aria-label="Default select example">' +
                                '<option>Active</option><option selected>Deactivate</option></select></td>'
                            );
                        }
                        $('#editor_' + arr.id).append('<td data-label="Action"><input type="hidden" id="' + arr.id +
                            '" value="' + arr.email + '">' +
                            '<div class="dropdown"><button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">OPTIONS</button>' +
                            '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">' +
                            '<li><a class="dropdown-item" href="/admin/editor/details/' + arr.id +
                            '">Details & Activities</a></li>' +
                            '<li><a class="dropdown-item'+ arr.email+'" id="send_email_btn"'+
                            'href="#" data-bs-toggle="modal"onclick="email_modal_function(this)"'+
                            'data-bs-target="#send_email">Send Emails</a></li>' +
                            '</ul></div></td>'
                        );
                    }
                });
            }
        }
    </script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-end p-0">
                            <div>
                                <h3>Manage Lab Editors</h3>
                            </div>

                        </div>
                        <div class="d-flex align-items-baseline flex-wrap justify-content-between p-0">
                            <div class="d-flex">
                                <input type="text" class="form-control mb-1" id="search" placeholder="Search editor">
                                <button type="button" id="search_btn" onclick="search({{ json_encode($users) }})"
                                    class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                            </div>
                            <div>
                                <button type="button" class="btn process-pay" data-bs-toggle="modal"
                                    data-bs-target="#add_new_editor">Add New Editor</button>
                            </div>
                        </div>

                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="editors">
                                    @forelse ($users as $user)
                                        <tr>
                                            <td data-label="Name" scope="row">{{ $user->name . ' ' . $user->last_name }}
                                            </td>
                                            <td data-label="Email">{{ $user->email }}</td>
                                            <td data-label="Status">
                                                @if ($user->active == '1')
                                                    <select class="form-select w-75 m-sm-0 ad_act_dact m-md-auto"
                                                        onchange="window.location.href='/pharmacy/admin/manage/editor/status/{{ $user->id }}'"
                                                        aria-label="Default select example">
                                                        <option selected>Active</option>
                                                        <option>Deactivate</option>
                                                    </select>
                                                @else
                                                    <select class="form-select w-75 m-sm-0 ad_act_dact m-md-auto"
                                                        onchange="window.location.href='/pharmacy/admin/manage/editor/status/{{ $user->id }}'"
                                                        aria-label="Default select example">
                                                        <option selected>Deactive</option>
                                                        <option>Activate</option>
                                                    </select>
                                                @endif
                                            </td>
                                            <td data-label="Action">
                                                <div class="dropdown">
                                                    <button class="btn option-view-btn dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        OPTIONS
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('dash_editor_details', ['id' => $user->id]) }}">Details
                                                                & Activities</a>
                                                        </li>
                                                        <li><a class="dropdown-item {{ $user->email }}" id="send_email_btn"
                                                                href="#" data-bs-toggle="modal"
                                                                onclick="email_modal_function(this)"
                                                                data-bs-target="#send_email">Send Emails</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">
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
        </div>
    </div>

    <!-- ------------------Send-Email-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="send_email_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="send_email" id="send_email" action="/doctors/send_mail" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">To</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        placeholder="xyx@gmail.com">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Subject</label>
                                    <input type="text" class="form-control" name="subject" placeholder="Enter Subject">
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="email_body">Email Body</label>
                                    <textarea class="form-control" id="email_body" name="ebody" rows="3" placeholder="Type your email message"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn process-pay">Send</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <!-- ------------------Send-Email-Modal-end------------------ -->
    <!-- ------------------Add-New-Editor-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="add_new_editor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Editor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('add_editor') }}" method="POST">
                        @csrf
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter New Editor's Name">
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" class="form-control" name="role" value="pharmacy">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="email_body">Email Address</label>
                                    <input type="text" class="form-control" name="email"
                                        placeholder="xyz@gmail.com">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn process-pay">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Add-New-Editor-Modal-end------------------ -->
@endsection
