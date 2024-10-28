@extends('layouts.admin')

@section('content')
<style>
.field-icon {
    float: right;
    margin-right: 25px;
    margin-top: -32px;
    position: relative;
    z-index: 2;
}
</style>
<section class="content profile-page">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Account Settings</h2>
        </div>
        <div class="card">
            <div class="body">
                <!-- <h4>Account Settings</h4> -->
                <div class="row clearfix col-md-10 col-sm-12">
                    <h5 class="">Change Password</h5>
                    <div class="col-sm-12">
                        <!-- <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" placeholder="Username">
                            </div>
                        </div> -->
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" id="popup" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                        @endif
                        @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" id="popup" data-dismiss="alert">×</button>
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


                        <form id="form_change_password" action="{{route('change_password')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <!-- <label>Current Password</label> -->

                                @hasanyrole('admin')
                                {{--  <input type="file" name="image" id="admin-img" class="form-control mb-3">  --}}
                                  @endhasanyrole

                                <div class="form-line">
                                    <input type="password" id="old_pass" class="form-control" placeholder="Current Password" name="old_password" autocomplete="off" required>
                                    <span toggle="#old_pass" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                </div>
                                <div class="mb-0 row col-md-12">
                                    <div id="error_div_1"
                                        class="form-group row error col-md-12 text-align-center">
                                        <span id="oldPassword" role="alert" class="d-none" style="color:red"><strong>Old Password Required</strong></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <!-- <label>New Password</label> -->
                                <div class="form-line">
                                    <input type="password" id="new_pass" class="form-control" placeholder="New Password" name="new_password" autocomplete="off" required>
                                    <span toggle="#new_pass" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                </div>
                                <div class="mb-0 row col-md-12">
                                    <div id="error_div_1"
                                        class="form-group row error col-md-12 text-align-center">
                                        <span id="mismatch" role="alert" class="d-none" style="color:red"><strong>Password Mismatch</strong></span>
                                    </div>
                                    <div id="error_div_2" class="form-group row error col-md-12 text-align-center">
                                        <span id="password_validate_err" role="alert" class="d-none"
                                            style="color:red"><strong>Password must contain minimum eight characters, at least one letter, one number and one special character</strong></span>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group mt-3">
                                <!-- <label>New Password</label> -->
                                <div class="form-line">
                                    <input type="password" id="re_new_pass" class="form-control"
                                        placeholder="Confirm New Password" name="confirm_password" autocomplete="off" required>
                                    <span toggle="#re_new_pass"
                                        class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                </div>
                            </div>
                            <button id="submit_change_password" class="btn callbtn my-3">Save Changes</button>
                        </form>
                    </div>
                </div>
                {{-- <h2 class="card-inside-title">Account Settings</h2>
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" placeholder="First Name">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" placeholder="Last Name">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" class="form-control no-resize" placeholder="Address Line 1"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" placeholder="City">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" placeholder="E-mail">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" placeholder="Country">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group checkbox">
                            <label>
                                <input name="optionsCheckboxes" type="checkbox">
                                <span class="checkbox-material"><span class="check"></span></span> Profile Visibility For Everyone </label>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button class="btn btn-raised btn-success">Save Changes</button>
                    </div>
                </div>--}}
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>



</script>
@endsection
