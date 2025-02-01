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
<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
          <div class="col-md-12">
            <div class="row m-auto">
              <div class="d-flex justify-content-between flex-wrap align-items-baseline p-0">
                <h3>Fee Approval Requests</h3>
                <div class="col-md-4 p-0">
                  <div class="input-group">
                    <form action="#" method="POST" style="width: 100%;">
                        @csrf
                        <input
                        type="text"
                        id="search"
                        name="name"
                        class="form-control"
                        placeholder="Search"
                        aria-describedby="basic-addon1"/>
                    </form>
                  </div>
                </div>
              </div>
              <div class="wallet-table">
                <table class="table">
                  <thead>
                    <tr>
                      {{-- <th scope="col">S.No</th> --}}
                      <th scope="col">Doctor Name</th>
                      <th scope="col">Consultation Fee</th>
                      <th scope="col">Follow-up Fee</th>
                      <th scope="col">Requested At</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($approvals as $approval)
                      <tr>
                        <td data-label="Order ID">{{$approval->name ." ". $approval->last_name}}</td>
                        <td data-label="Order State">{{$approval->consultation_fee}}</td>
                        <td data-label="Order Status">{{$approval->followup_fee}}</td>
                        <td data-label="Date">{{$approval->created_at}}</td>
                        <td data-label="Action">
                        <div class="dropdown">
                            <button
                            class="btn dropdown-toggle orders-view-btn"
                            type="button"
                            id="dropdownMenuButton1"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            >
                            Accept / Decline
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li>
                                <form action="{{ route('decline_approval', ['id' => $approval->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="approval_id" value="{{$approval->id}}">
                                    <input type="hidden" name="doctor_id" value="{{$approval->doctor_id}}">
                                    <input type="hidden" name="username" value="{{$approval->username}}">
                                    <button class="dropdown-item" type="submit">Decline</button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route("confirm_approval") }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="approval_id" value="{{$approval->id}}">
                                    <input type="hidden" name="doctor_id" value="{{$approval->doctor_id}}">
                                    <input type="hidden" name="username" value="{{$approval->username}}">
                                    <input type="hidden" name="consultation_fee" value="{{$approval->consultation_fee}}">
                                    <input type="hidden" name="followup_fee" value="{{$approval->followup_fee}}">
                                    <button class="dropdown-item" type="submit">Approve</button>
                                </form>
                            </li>
                            </ul>
                        </div>
                        </td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="5">
                            <div class="m-auto text-center for-empty-div">
                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                <h6>Nothing for approval</h6>
                            </div>
                        </td>
                    </tr>
                      @endforelse
                  </tbody>
                </table>
                <div class="row d-flex justify-content-center">
                    <div class="paginateCounter">
                        {{ $approvals->links('pagination::bootstrap-4') }}
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>
@endsection
