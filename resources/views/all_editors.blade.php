@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>All Editors
                <small>
                    <button id="add_editor_btn" style="color:white"
                        class="btn btn-raised bg-deep-purple waves-effect"><i class="fa fa-plus" style="font-size:14px">
                            Add New Editor</i></button>
                </small>
            </h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <th>Name</th>
                        <th>Email</th>
                        {{--<th>Department</th>
                        <th>Role</th>
                        --}}<th>
                            <center>Action</center>
                        </th>
                    </thead>
                    <tbody>
                        @forelse($editors as $user)
                        <tr>
                            <td>{{$user->name." ".$user->last_name}}</td>
                            <td>{{$user->email}}</td>
                         {{--   @if($user->user_type=='editor_lab')
                            <td>Lab</td>
                            <td>Editor</td>
                            @elseif($user->user_type=='editor_pharmacy')
                            <td>Pharmacy</td>
                            <td>Editor</td>
                            @elseif($user->user_type=='editor_imaging')
                            <td>Imaging</td>
                            <td>Editor</td>
                            @elseif($user->user_type=='admin_lab')
                            <td>Lab</td>
                            <td>Admin</td>
                            @elseif($user->user_type=='admin_pharmacy')
                            <td>Pharmacy</td>
                            <td>Admin</td>
                            @elseif($user->user_type=='admin_imaging')
                            <td>Imaging</td>
                            <td>Admin</td>
                            @endif
                         --}}   <td style="padding:0px">
                                <center>
                                    <a href="{{route('user_details',$user->id)}}">
                                    <button class="btn p-2 btn-default btn-raised btn-circle waves-effect waves-circle waves-float"><i class="fa fa-eye"></i></button>
                                    </a>
                                    <a href="#">
                                        <button class="btn p-2 btn-primary btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                class="fa fa-envelope"></i> </button>
                                    </a>
                                    <a href="#">
                                    <button class="btn p-2 btn-danger btn-raised btn-circle waves-effect waves-circle waves-float"><i class="fa fa-ban"></i></button>
                                    </a> 
                                </center>
                            </td>

                        </tr>
                        @empty
                        <tr><td colspan="3">No Editor Added</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-sm-12 text-center">
                <!-- <a href="{{route('pending_doctors')}}" class="btn btn-raised g-bg-cyan">Add Doctors</a> -->
            </div>
        </div>
    </div>
</section>
<!-- Add New Admin Modal -->
<div class="modal fade" id="add_editor_modal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Editor
                </h4>
            </div>
            <div class="modal-body">
                <form name="add_new_editor" id="add_new_editor" action="{{route('store_editor')}}" method="post">
                    @csrf 
                    <div class="form-group my-1">
                        <label>Name</label>
                        <div class="form-line m-1 p-0">
                            <input required="" class="col-md-12 form-control p-0" name="dtitle">
                        </div>
                    </div>
                    @if($user_type=='admin_pharmacy')
                    <input hidden="" name="role" value="pharmacy">
                    @elseif($user_type=='admin_lab')
                    <input hidden="" name="role" value="lab">
                    @endif
                    <div class="form-group my-1">
                        <label>Email Address</label>
                        <div class="form-line m-1 p-0">
                            <input required="" type="email" class="col-md-12 form-control" name="email">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" style="color:white" class="btn btn-raised bg-deep-purple waves-effect col-md-12">Add</button>
                        <!-- <button type="submit" class="btn btn-raised">Cancel</button> -->
                    </div>
 
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
$('#add_admin_btn').click(function() {
    $('#add_admin_modal').modal('show');

});
$('#add_editor_btn').click(function() {
    $('#add_editor_modal').modal('show');

});
</script>
@endsection