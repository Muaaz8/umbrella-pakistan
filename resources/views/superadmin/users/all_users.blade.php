@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header mb-0 pb-0">
            <h2>All Users
            </h2>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0);">Users</a></li>
            </ul>
        </div>
        <div class="body">
            <div class="col-md-12">
                <button id="add_admin_btn" style="color:white" class="btn btn-raised bg-green waves-effect"><i
                        class="fa fa-plus" style="font-size:14px"> Add New Admin</i></button>
                <button id="add_editor_btn" style="color:white" class="btn btn-raised bg-deep-purple waves-effect"><i
                        class="fa fa-plus" style="font-size:14px">
                        Add New Editor</i></button>
            </div>

            <div class="row clearfix">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#admins">Admins </a>
                        </li>
                        <li class="nav-item"><a class="nav-link " data-toggle="tab" href="#editors">Editors </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#hr">HR</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="col-md-12">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="admins">
                                <div class="row clearfix">
                                    <div class="mt-2 col-sm-12 col-md-12 col-lg-12">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                                    href="#admin_pharmacy">Pharmacy </a></li>
                                            <li class="nav-item"><a class="nav-link " data-toggle="tab"
                                                    href="#admin_lab">Lab </a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                                    href="#admin_imaging">Imaging</a></li>
                                        </ul>
                                        <div class="col-md-12">
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="admin_pharmacy">
                                                    <table
                                                        class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                        <thead>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Department</th>
                                                            <th>Role</th>
                                                            <th>
                                                                <center>Action</center>
                                                            </th>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($admin_phar as $user)
                                                            <tr>
                                                                <td>{{$user->name." ".$user->last_name}}</td>
                                                                <td>{{$user->email}}</td>
                                                                <td>Pharmacy</td>
                                                                <td>Admin</td>
                                                                <td style="padding:0px">
                                                                    <center>
                                                                        <a href="{{route('user_details',$user->id)}}">
                                                                            <button
                                                                                class="btn p-2 btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-eye"></i></button>
                                                                        </a>
                                                                        <a href="#" >
                                                                            <button
                                                                                class="btn p-2 btn-primary btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-envelope"></i>
                                                                            </button>
                                                                        </a>
                                                                        <a href="#" class="revoke" id="aphar_{{$user->id}}">
                                                                            <button
                                                                                class="btn p-2 btn-danger btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-ban"></i></button>
                                                                        </a>
                                                                    </center>
                                                                </td>

                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="5">
                                                                    <center>No Users</center>
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div role="tabpanel" class="tab-pane " id="admin_lab">
                                                    <table
                                                        class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                        <thead>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Department</th>
                                                            <th>Role</th>
                                                            <th>
                                                                <center>Action</center>
                                                            </th>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($admin_lab as $user)
                                                            <tr>
                                                                <td>{{$user->name." ".$user->last_name}}</td>
                                                                <td>{{$user->email}}</td>
                                                                <td>Lab</td>
                                                                <td>Admin</td>
                                                                <td style="padding:0px">
                                                                    <center>
                                                                        <a href="{{route('user_details',$user->id)}}">
                                                                            <button
                                                                                class="btn p-2 btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-eye"></i></button>
                                                                        </a>
                                                                        <a href="#">
                                                                            <button
                                                                                class="btn p-2 btn-primary btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-envelope"></i>
                                                                            </button>
                                                                        </a>
                                                                        <a href="#" class="revoke" id="alab_{{$user->id}}">
                                                                            <button
                                                                                class="btn p-2 btn-danger btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-ban"></i></button>
                                                                        </a>
                                                                    </center>
                                                                </td>

                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="5">
                                                                    <center>No Users</center>
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div role="tabpanel" class="tab-pane " id="admin_imaging">
                                                    <table
                                                        class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                        <thead>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Department</th>
                                                            <th>Role</th>
                                                            <th>
                                                                <center>Action</center>
                                                            </th>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($admin_imaging as $user)
                                                            <tr>
                                                                <td>{{$user->name." ".$user->last_name}}</td>
                                                                <td>{{$user->email}}</td>
                                                                <td>Imaging</td>
                                                                <td>Admin</td>
                                                                <td style="padding:0px">
                                                                    <center>
                                                                        <a href="{{route('user_details',$user->id)}}">
                                                                            <button
                                                                                class="btn p-2 btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-eye"></i></button>
                                                                        </a>
                                                                        <a href="#">
                                                                            <button
                                                                                class="btn p-2 btn-primary btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-envelope"></i>
                                                                            </button>
                                                                        </a>
                                                                        <a href="#" class="revoke" id="aimag_{{$user->id}}">
                                                                            <button
                                                                                class="btn p-2 btn-danger btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-ban"></i></button>
                                                                        </a>
                                                                    </center>
                                                                </td>

                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="5">
                                                                    <center>No Users</center>
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
                            <div role="tabpanel" class="tab-pane" id="editors">
                                <div class="row clearfix">
                                    <div class="mt-2 col-sm-12 col-md-12 col-lg-12">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                                    href="#editor_pharmacy">Pharmacy </a></li>
                                            <li class="nav-item"><a class="nav-link " data-toggle="tab"
                                                    href="#editor_lab">Lab </a></li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                                    href="#editor_imaging">Imaging</a></li>
                                        </ul>
                                        <div class="col-md-12">
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="editor_pharmacy">
                                                    <table
                                                        class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                        <thead>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Department</th>
                                                            <th>Role</th>
                                                            <th>
                                                                <center>Action</center>
                                                            </th>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($editor_phar as $user)
                                                            <tr>
                                                                <td>{{$user->name." ".$user->last_name}}</td>
                                                                <td>{{$user->email}}</td>
                                                                <td>Pharmacy</td>
                                                                <td>Editor</td>
                                                                <td style="padding:0px">
                                                                    <center>
                                                                        <a href="{{route('user_details',$user->id)}}">
                                                                            <button
                                                                                class="btn p-2 btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-eye"></i></button>
                                                                        </a>
                                                                        <a href="#">
                                                                            <button
                                                                                class="btn p-2 btn-primary btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-envelope"></i>
                                                                            </button>
                                                                        </a>
                                                                        <a href="#" class="revoke" id="ephar_{{$user->id}}">
                                                                            <button
                                                                                class="btn p-2 btn-danger btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-ban"></i></button>
                                                                        </a>
                                                                    </center>
                                                                </td>

                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="5">
                                                                    <center>No Users</center>
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div role="tabpanel" class="tab-pane " id="editor_lab">
                                                    <table
                                                        class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                        <thead>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Department</th>
                                                            <th>Role</th>
                                                            <th>
                                                                <center>Action</center>
                                                            </th>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($editor_lab as $user)
                                                            <tr>
                                                                <td>{{$user->name." ".$user->last_name}}</td>
                                                                <td>{{$user->email}}</td>
                                                                <td>Lab</td>
                                                                <td>Editor</td>
                                                                <td style="padding:0px">
                                                                    <center>
                                                                        <a href="{{route('user_details',$user->id)}}">
                                                                            <button
                                                                                class="btn p-2 btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-eye"></i></button>
                                                                        </a>
                                                                        <a href="#">
                                                                            <button
                                                                                class="btn p-2 btn-primary btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-envelope"></i>
                                                                            </button>
                                                                        </a>
                                                                        <a href="#" class="revoke" id="elab_{{$user->id}}">
                                                                            <button
                                                                                class="btn p-2 btn-danger btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-ban"></i></button>
                                                                        </a>
                                                                    </center>
                                                                </td>

                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="5">
                                                                    <center>No Users</center>
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div role="tabpanel" class="tab-pane " id="editor_imaging">
                                                    <table
                                                        class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                        <thead>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Department</th>
                                                            <th>Role</th>
                                                            <th>
                                                                <center>Action</center>
                                                            </th>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($editor_imaging as $user)
                                                            <tr>
                                                                <td>{{$user->name." ".$user->last_name}}</td>
                                                                <td>{{$user->email}}</td>
                                                                <td>Imaging</td>
                                                                <td>Editor</td>
                                                                <td style="padding:0px">
                                                                    <center>
                                                                        <a href="{{route('user_details',$user->id)}}">
                                                                            <button
                                                                                class="btn p-2 btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-eye"></i></button>
                                                                        </a>
                                                                        <a href="#">
                                                                            <button
                                                                                class="btn p-2 btn-primary btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-envelope"></i>
                                                                            </button>
                                                                        </a>
                                                                        <a href="#" class="revoke" id="eimag_{{$user->id}}">
                                                                            <button
                                                                                class="btn p-2 btn-danger btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                    class="fa fa-ban"></i></button>
                                                                        </a>
                                                                    </center>
                                                                </td>

                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="5">
                                                                    <center>No Users</center>
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
                            <div role="tabpanel" class="tab-pane" id="hr">
                                <!-- <b>Profile Content</b> -->
                                <table
                                    class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Role</th>
                                        <th>
                                            <center>Action</center>
                                        </th>
                                    </thead>
                                    <tbody>
                                        @forelse($hr as $user)
                                        <tr>
                                            <td>{{$user->name." ".$user->last_name}}</td>
                                            <td>{{$user->email}}</td>
                                            @if($user->user_type=='editor_lab')
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
                                            <td style="padding:0px">
                                                <center>
                                                    <a href="{{route('user_details',$user->id)}}">
                                                        <button
                                                            class="btn p-2 btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                class="fa fa-eye"></i></button>
                                                    </a>
                                                    <a href="#">
                                                        <button
                                                            class="btn p-2 btn-primary btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                class="fa fa-envelope"></i> </button>
                                                    </a>
                                                    <a href="#" class="revoke" id="hr_{{$user->id}}">
                                                        <button
                                                            class="btn p-2 btn-danger btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                class="fa fa-ban"></i></button>
                                                    </a>
                                                </center>
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5">
                                                <center>No Users</center>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-12 text-center">
                            <!-- <a href="{{route('pending_doctors')}}" class="btn btn-raised g-bg-cyan">Add Doctors</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Add New Admin Modal -->
<div class="modal fade" id="add_admin_modal" style="font-weight: normal; " tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Add New Admin
                </h4>
            </div>
            <div class="modal-body">
                <form name="add_new_admin" id="add_new_admin" action="{{route('store_admin')}}" method="post">
                    @csrf
                    <div class="form-group my-1">
                        <label>Title</label>
                        <div class="form-line m-1 p-0">
                            <input required="" type="text" class="col-md-12 form-control p-0" name="dtitle">
                        </div>
                    </div>
                    <div class="form-group my-1 drop-custom">
                        <label>Department</label>
                        <!-- <input name="role"> -->
                        <div class="form-control">
                            <select required="" class="col-md-12" name="role">
                                <option value="" hidden selected disabled>Choose Department</option>
                                <option value="pharmacy">Pharmacy</option>
                                <option value="lab">Lab</option>
                                <option value="imaging">Imaging</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group my-1">
                        <label>Email Address</label>
                        <div class="form-line m-1 p-0">
                            <input required="" type="email" class="col-md-12  form-control" name="email">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" style="color:white"
                            class="btn btn-raised bg-green waves-effect col-md-12">Add</button>
                        <!-- <button type="submit" class="btn btn-raised">Cancel</button> -->
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
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
                        <label>Title</label>
                        <div class="form-line m-1 p-0">
                            <input required="" class="col-md-12 form-control p-0" name="dtitle">
                        </div>
                    </div>
                    <div class="form-group my-1 drop-custom">
                        <label>Department</label>
                        <!-- <input name="role"> -->
                        <div class="form-control">
                            <select required="" class="col-md-12" name="role">
                                <option value="" hidden selected disabled>Choose Department</option>
                                <option value="pharmacy">Pharmacy</option>
                                <option value="lab">Lab</option>
                                <option value="imaging">Imaging</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group my-1">
                        <label>Email Address</label>
                        <div class="form-line m-1 p-0">
                            <input required="" type="email" class="col-md-12 form-control" name="email">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" style="color:white"
                            class="btn btn-raised bg-deep-purple waves-effect col-md-12">Add</button>
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
$('.revoke').click(function(){
    var id_all=$(this).attr('id');
    var id_sp=id_all.split('_');
    // console.log(id_sp[1]);
    var id=id_sp[1];
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to block this user from website",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText:'No',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.value) {
        window.location.href = "../revoke_role/"+id;
        }
    });
    
});
</script>
@endsection