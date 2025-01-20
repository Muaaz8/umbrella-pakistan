@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Admin Dashboard</title>
@endsection

@section('top_import_file')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#user').on("change", function(e) {
                var type = $('#user').val();
                window.location.href = "/admin/manage/all/users/" + type;
            });
        });

        function email_modal_function(a) {
            var email = $(a).attr('class');
            var breakClasses = email.split(' ');
            $('#email').val(breakClasses[1]);
            $('#send_email_modal').modal('show');
        }
    </script>
@endsection


@section('bottom_import_file')
    <script>
        $('.acctive').click(function() {
            var id = $(this).attr('id');
            $("#activate_user_id").val(id);
            $('#activate_user').modal('show');
        });
        $('.deactive').click(function() {
            var id = $(this).attr('id');
            $("#deactivate_user_id").val(id);
            $('#deactivate_user').modal('show');
        });
    </script>
@endsection

@section('content')
    {{-- {{ dd($roles,$userType) }} --}}
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-end p-0">
                            <div>
                                <h3>All Users</h3>
                            </div>
                        </div>
                        @if (session()->get('success'))
                        <div id="errorDiv" class="alert alert-success col-12 col-md-6 offset-md-3">
                            @php
                            $es = session()->get('success');
                            @endphp
                            <span role="alert"> <strong>{{ $es }}</strong></span>
                        </div>
                        @endif
                        @if (session()->get('error'))
                        <div id="errorDiv" class="alert alert-danger col-12 col-md-6 offset-md-3">
                            @php
                            $es = session()->get('error');
                            @endphp
                            <span role="alert"> <strong>{{ $es }}</strong></span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between p-0">
                            <div class="d-flex w-50">

                                <select class="form-select me-2" aria-label="Default select example" id="user">
                                    <option selected>Select User</option>
                                    @foreach ($roles as $role)
                                        @if ($role->name != 'admin')
                                            @if ($userType == 'all')
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @else
                                                @if ($userType == $role->name)
                                                    <option selected value="{{ $role->name }}">{{ $role->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                                {{-- <select class="form-select" aria-label="Default select example">
                        <option selected>Select Department</option>
                        <option value="1">Pharmacy</option>
                        <option value="2">Lab</option>
                        <option value="3">Imaging</option>
                      </select> --}}
                            </div>
                            <div>
                                <div class="dropdown">
                                    <button class="btn process-pay dropdown-toggle" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Add New
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#add_new_admin">Add New Admin</a></li>
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#add_new_editor">Add New Editor</a></li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 d-flex p-0">

                            </div>
                            <div class="col-md-6 text-end">

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
                                <tbody>

                                    @forelse ($users as $user)
                                        <tr>
                                            <td data-label="Name">{{ $user->name . ' ' . $user->last_name }}</td>
                                            <td data-label="Email">{{ $user->email }}</td>
                                            @if ($user->active == 1)
                                                <td data-label="Status">Active</td>
                                            @else
                                                <td data-label="Status">Deactive</td>
                                            @endif
                                            <td data-label="Action">
                                                <div class="dropdown">
                                                    <button class="btn option-view-btn dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        OPTIONS
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                                        <li><a class="dropdown-item"
                                                            href="{{ route('dash_view_user',['type'=>$user->user_type ,'id'=>$user->id ]) }}"
                                                            >Details And Activities</a></li>
                                                        @if ($user->active == 1)
                                                            <li><a class="dropdown-item deactive"
                                                                    id="{{ $user->id }}">Deactivate</a></li>
                                                        @else
                                                            <li><a class="dropdown-item acctive"
                                                                    id="{{ $user->id }}">Activate</a></li>
                                                        @endif
                                                        <li><a class="dropdown-item {{ $user->email }}"
                                                                id="send_email_btn" href="#" data-bs-toggle="modal"
                                                                onclick="email_modal_function(this)"
                                                                data-bs-target="#send_email">Send Emails</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="m-auto text-center for-empty-div">
                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                    <h6>Select User Type To See</h6>
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
    </div>


    </div>
    <!-- ------------------Block-Doctor-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="deactivate_doctor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deactivate Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-modal-body">
                        Are you sure you want to Deactivate this Doctor?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger">Deactivate</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Block-Doctor-Modal-end------------------ -->
    <!-- ------------------Block-User-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="deactivate_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deactivate User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-modal-body">
                        Are you sure you want to Deactivate this User?
                    </div>
                </div>
                <form action="{{ route('deactivate_user') }}" method="post">
                    @csrf
                    <div class="modal-footer">
                        <input type="hidden" name="user_id" id="deactivate_user_id">
                        <button type="submit" class="btn btn-danger">Deactivate</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- ------------------Block-User-Modal-end------------------ -->
    <!-- ------------------Block-User-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="activate_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Activate User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-modal-body">
                        Are you sure you want to Activate this User?
                    </div>
                </div>
                <form action="{{ route('activate_user') }}" method="post">
                    @csrf
                    <div class="modal-footer">
                        <input type="hidden" name="user_id" id="activate_user_id">
                        <button type="submit" class="btn btn-primary">Activate</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- ------------------Block-User-Modal-end------------------ -->
    <!-- ------------------Add-New-Admin-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="add_new_admin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dash_store_admin') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Title</label>
                                    <input type="text" class="form-control" name="dtitle" placeholder="">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Department</label>
                                    <select class="form-select" name="role" aria-label="Default select example">
                                        <option selected value="pharmacy">Pharmacy</option>
                                        <option value="lab">Lab</option>
                                        <option value="imaging">Imaging</option>
                                        <option value="finance">Finance</option>
                                        <option value="chat_support">Chat</option>
                                        <option value="seo">SEO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="email_body">Email Address</label>
                                    <input type="text" name="email" class="form-control"
                                        placeholder="xyz@gmail.com">
                                </div>
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


    <!-- ------------------Add-New-Admin-Modal-end------------------ -->

    <!-- ------------------Add-New-Editor-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="add_new_editor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Editor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dash_store_editor') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Title</label>
                                    <input type="text" name="dtitle" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Department</label>
                                    <select class="form-select" name="role"  aria-label="Default select example">
                                        <option selected value="pharmacy" >Pharmacy</option>
                                        <option value="lab">Lab</option>
                                        <option value="imaging">Imaging</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="email_body">Email Address</label>
                                    <input type="text" name="email" class="form-control" placeholder="xyz@gmail.com">
                                </div>
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



    <!-- ------------------Send-Email-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="send_email_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                                    <input type="text" class="form-control" name="subject"
                                        placeholder="Enter Subject">
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


@endsection
