@extends('layouts.admin')

@section('content')
@php 
$user_type=auth()->user()->user_type;
@endphp
<section class="content">
    <div class="container-fluid">
        <div class="block-header mb-0 pb-0">
            <h2>{{ucwords($user->name." ".$user->last_name)}}         
            </h2>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item "><a href="{{route('manage_users')}}">Users</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0);">{{ucwords($user->name." ".$user->last_name)}}</a></li>
            </ul>
        
        </div>
        <div class="row clearfix">
            <div class="col-md-12 clearfix row mb-0">
                    <a class="col-md-2 offset-8 revoke" id="{{$user->id}}" href="#">
                        <button class="btn btn-raised btn-danger col-md-12 px-2 py-2"><i class="fa fa-ban"></i>
                            Block</button>
                    </a>
                    <a class="col-md-2" href="#">
                        <button class="btn btn-raised btn-primary col-md-12 px-2 py-2"><i class="fa fa-envelope"></i>
                            Send Email</button>
                    </a>
                </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>User Details </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                            href="#personal">Personal Information </a></li>
                                    <!-- <li class="nav-item"><a class="nav-link " data-toggle="tab" href="#activity">Activity Log </a></li> -->
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                            href="#products">Products</a></li>
                                    @if($user->user_type=='admin_pharmacy'||$user->user_type=='admin_lab'||$user->user_type=='admin_imaging')
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                            href="#editors">Editors</a></li>
                                    @endif
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                            href="#activity">Activity Log</a></li>
                                    
                                </ul>

                                <!-- Tab panes -->
                                <div class="col-md-12">
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="personal">
                                            <!-- <b>Profile Content</b> -->
                                            <table class="table table-borderless">
                                                <thead>
                                                    <th width="20%"></th>
                                                    <th></th>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td>Name</td>
                                                        <td>{{ucwords($user->name." ".$user->last_name)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>State</td>
                                                        <td>{{ucwords($user->state)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone</td>
                                                        <td>{{ucwords($user->phone_number)}}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <div role="tabpanel" class="tab-pane in" id="products">
                                            <div class="row clearfix mt-2">
                                                <div class="col-sm-12 col-md-12 col-lg-12">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <li class="nav-item"><a class="nav-link active"
                                                                data-toggle="tab" href="#prod">Products </a></li>
                                                        <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                                                href="#prod_cat">Product Categories</a></li>
                                                        <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                                                href="#prod_sub_cat">Product Subcategories</a></li>
                                                    </ul>

                                                    <!-- Tab panes -->
                                                    <div class="col-md-12">
                                                        <div class="tab-content">
                                                            <div role="tabpanel" class="tab-pane active" id="prod">
                                                                @include('all_products.table')

                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="prod_cat">
                                                                @include('product_categories.table')

                                                            </div>
                                                            <div role="tabpanel" class="tab-pane" id="prod_sub_cat">
                                                                @include('products_sub_categories.table')
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($user->user_type=='admin_pharmacy'||$user->user_type=='admin_lab'||$user->user_type=='admin_imaging')
                                        <div role="tabpanel" class="tab-pane" id="editors">
                                            <!-- <b>Profile Content</b> -->
                                            <div class="body table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>
                                                            <center>Action</center>
                                                        </th>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($editors as $user)
                                                        <tr>
                                                            <td>{{$user->name." ".$user->last_name}}</td>
                                                            <td>{{$user->email}}</td>
                                                            <td style="padding:0px">
                                                                <center>
                                                                    <a href="{{route('user_details',$user->id)}}">
                                                                        <button
                                                                            class="btn p-2 btn-default btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                class="fa fa-eye"></i></button>
                                                                    </a>
                                                                    <a href="#">
                                                                        <button
                                                                            class="btn p-2 btn-primary btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                class="fa fa-envelope"></i> </button>
                                                                    </a>
                                                                    <a href="#">
                                                                        <button
                                                                            class="btn p-2 btn-danger btn-raised btn-circle waves-effect waves-circle waves-float"><i
                                                                                class="fa fa-ban"></i></button>
                                                                    </a>
                                                                </center>
                                                            </td>

                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="3">No Editor Added</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        @endif
                                        <div role="tabpanel" class="tab-pane in" id="activity">
                                            <div class="body table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <th>Activity</th>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($activities as $act)
                                                        <tr>
                                                            <td>{{ucwords($act->activity)}}</td>
                                                            <td>{{$act->date}}</td>
                                                            <td>{{$act->time}}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="3">No Avtivity Yet</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div class="d-flex justify-content-center">
                                                    {!! $activities->links('pagination::bootstrap-4') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script type="text/javascript">
    $('.revoke').click(function(){
        var id=$(this).attr('id');
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