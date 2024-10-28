<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
@php
    use Carbon\Carbon;
@endphp
<!--Top Bar -->
<div class="mynav">
    <nav class="navbar clearHeader" id="listed">
        <div class="col-lg-12 col-md-10 col-6">
            <div class="navbar-header"> <a href="javascript:void(0);" class="bars"
                    style=" width: 90px;position: absolute;top: 0;left: -100px;"></a>
                <!-- <a class="navbar-brand" href="/">
                    <img style="width: 90px;position: absolute;top: 0;left: -100px; "
                        src="{{ asset('asset_admin/images/logo.png') }}">
                </a> -->
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="not-icon">
                    <div class="icons_">
                        <div class="notification_" style="width: 50px;">
                            <a href="#">
                                <div class="notBtn_" href="#">
                                    <div class="number_" id="countNote">{{  app('notificationsCount')  }}</div>
                                    <i class="zmdi zmdi-notifications bell_note" id="click"></i>
                                    <div class="box_">
                                        <div id="ReadAllNoti" class="txt_ not_text float-left" onclick="myFunction()">Mark all as read</div>
                                                <br>
                                        <div class="display_">
                                            <div class="cont_" id="noteData">
                                                @foreach (app('getNote') as $note)
                                                    @if ($note->status=='new')
                                                        <a href="/ReadNotification/{{ $note->id }}" >
                                                            <div class="sec_ newNotifications" style="background-color: #fcfafa !important;">
                                                                <span class="nav_notification float-right">New</span>
                                                                <div class="txt_ not_text">{{ $note->text }}</div>
                                                                <div class="txt_ not_subtext"><i class="zmdi zmdi-time"></i> {{ Carbon::parse($note->created_at)->diffForHumans(); }}</div>
                                                            </div>
                                                        </a>
                                                    @else
                                                        <a href="/ReadNotification/{{ $note->id }}" >
                                                            <div class="sec_  oldNotifications" style=" background-color: #ffffff !important;">
                                                                <span class="nav_notification_seen float-right">Seen</span>
                                                                <div class="txt_ not_text">{{ $note->text }}</div>
                                                                <div class="txt_ not_subtext"><i class="zmdi zmdi-time"></i> {{ Carbon::parse($note->created_at)->diffForHumans(); }}</div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>
                <li style="width: 50px;">
                    <a href="/cart">
                        <i style="font-size:32px" class="zmdi zmdi-shopping-cart mt-1"></i>
                        <span class="label-count cart_counter cart_num"></span>
                    </a>
                </li>
                <div id="profile_div" class="admin-image">
                    @php
                        $user=Auth::user();
                        $username = $user->username;

                        $image = \App\Helper::check_bucket_files_url(Auth::user()->user_image);

                        if (Auth::user()->user_type == 'doctor')
                        {
                            $name = 'Dr. ' . Auth::user()->name;
                            $representative = Auth::user()->representative_name;
                        }
                        else
                        {
                            $name = Auth::user()->name;
                            $representative = Auth::user()->representative_name;
                        }
                    @endphp

                    <img src="{{ $image }}" class="testClass" alt="{{ $user->name }}" style="width:38px; height: 38px; border-radius:100%">

                    <div id="profile-info" class="profile-info p-2" style="display:none;">
                        <div class="admin-action-info" style=" font-size:18px">
                            <div class="username d-flex justify-content-around">
                                <div class="mb-2">
                                    <img src="{{$image}}" alt="{{$user->name}}"  class="inn-img">
                                </div>
                                <div class="name">
                                    <span class="mb-2">
                                        Welcome {{ $name }}
                                    </span>

                                    @if ($representative != '')
                                    <p style="font-size:12px;"> Representative:
                                        {{ $representative }} </p>
                                    @endif
                                </div>
                            </div>
                            <ul>
                        </div>
                        <div class="d-flex flex-column justify-content-center">

                            @hasanyrole('doctor|patient')
                            <li class=" mt-3 pro-li ">
                                <a data-placement="bottom" title="Go to Profile" href="{{ url('/user/profile/'.$username) }}">
                                    <i style="font-size:20px !important;" class="fas fa-user nav-icon mr-3"></i>
                                    My Profile
                                </a>
                            </li>
                            @endhasanyrole

                            <!-- @hasanyrole('admin')
                            <li class=" mt-3 pro-li">
                                <a data-placement="bottom" title="Go to Profile" href="/all_users">
                                    <i style="font-size:20px; !important" class="fas fa-user nav-icon mr-3"></i>
                                    User Profile
                                </a>
                            </li>
                            @endhasanyrole -->

                            <li class=" mt-3 pro-li">
                                <a href="{{ route('acc_settings') }}">
                                    <i style="font-size:20px;  !important" class="fas fa-cog nav-icon mr-3"></i>
                                    Account Settings
                                </a>
                            </li>
                            <li class=" mt-3 pro-li">
                                <a data-placement="bottom" title="Logout" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i style="font-size:20px;  !important"
                                        class="fas fa-sign-out-alt nav-icon mr-3"></i>
                                    Logout
                                </a>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
        </div>
        <!-- #END# Notifications -->
        <!-- Tasks -->
        <!-- <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-flag"></i><span class="label-count">9</span> </a>
                    <ul class="dropdown-menu">
                        <li class="header">TASKS</li>
                        <li class="body">
                            <ul class="menu tasks">
                                <li> <a href="javascript:void(0);">
                                    <h4> Task 1 <small>32%</small> </h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 32%"> </div>
                                    </div>
                                    </a> </li>
                                <li> <a href="javascript:void(0);">
                                    <h4>Task 2 <small>45%</small> </h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-cyan" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 45%"> </div>
                                    </div>
                                    </a> </li>
                                <li> <a href="javascript:void(0);">
                                    <h4>Task 3 <small>54%</small> </h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 54%"> </div>
                                    </div>
                                    </a> </li>
                                <li> <a href="javascript:void(0);">
                                    <h4> Task 4 <small>65%</small> </h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 65%"> </div>
                                    </div>
                                    </a> </li>
                            </ul>
                        </li>
                        <li class="footer"> <a href="javascript:void(0);">View All Tasks</a> </li>
                    </ul>
                </li>
                <li><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="zmdi zmdi-settings"></i></a></li>
            </ul> -->
        </ul>
</div>
</nav>

</div>
<!-- #Top Bar -->
<!-- <script src="{{ asset('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js')}}"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->

<!-- <script src="{{ asset('asset_admin/plugins/jquery/jquery-v3.2.1.min.js')}}"></script> -->

<!-- <script>

$(document).ready(function() {
    $('#profile-info').css('display', 'none');
});
$('#profile_div').click(function() {
    alert(123123123);
    if ($('#profile-info').css('display') == 'none')
        $('#profile-info').css('display', 'block');
    else
        $('#profile-info').css('display', 'none');

})
</script> -->
@section('script')
<script>

</script>
@endsection
