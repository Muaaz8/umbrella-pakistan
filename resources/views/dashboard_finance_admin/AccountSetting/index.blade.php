@extends('layouts.dashboard_finance_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Account Setting</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
    function myFunction(id,icon) {
        var x = document.getElementById(id);
        var y = document.getElementById(icon);
        if (x.type === "password") {
            x.type = "text";
            $(y).removeClass('fa-eye-slash');
            $(y).addClass('fa-eye');
        } else {
            x.type = "password";
            $(y).removeClass('fa-eye');
            $(y).addClass('fa-eye-slash');
        }
    }
</script>
@endsection

@section('content')
    {{-- {{ dd($user) }} --}}
    <div class="dashboard-content">
        <div class="col-11 m-auto">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="account-setting-wrapper bg-white">
                <h4 class="pb-4 border-bottom">Account Settings</h4>
                <form id="form_change_password" action="{{ route('change_password') }}" method="post">
                    @csrf
                    <div class="row py-2">
                        <div class="col-md-12">
                            <label for="cpass">Current Password</label>
                            <input type="password" id="old_pass" name="old_password" autocomplete="off" required
                                class="bg-light form-control" placeholder="Current Password">
                            <span id="old_icon" toggle="#old_pass" onclick="myFunction('old_pass', 'old_icon')" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>

                        </div>
                        <div class="col-md-12 pt-md-0 pt-3">
                            <label for="newpass">New Password</label>
                            <input type="password" id="new_pass" name="new_password" autocomplete="off" required
                                class="bg-light form-control" placeholder="New Password">
                            <span id="new_icon" toggle="#new_pass" onclick="myFunction('new_pass','new_icon')" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>

                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-md-12">
                            <label for="cpass">Confirm New Password</label>
                            <input type="password" id="re_new_pass" class="bg-light form-control"
                                placeholder="Confirm New Password" name="confirm_password" autocomplete="off" required>
                            <span id="re_new_icon" toggle="#new_pass" onclick="myFunction('re_new_pass','re_new_icon')" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>

                        </div>
                    </div>
                    <div class="py-3 pb-4">
                        <button class="btn btn-primary mr-3">Save Changes</button>
                    </div>
                </form>
                </div>
        </div>
    </div>
    </div>
@endsection
