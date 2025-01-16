@php
use Carbon\Carbon;
@endphp
<div class="dashboard">
    <div class="dashboard-nav" id="style-10">
        <header>
            <a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a>
        </header>
        <div class="nav-info-div-wrapper">
            <div class="nav-info-img">
                <a href="{{ route('welcome_page') }}"><img src="{{ asset('assets/images/dashboards_logo.png') }}" alt=""  ></a>
            </div>
            <h5 class="text-center pt-3">Doctor Dashboard</h5>
            @php
            $user = \Auth::user();
            //echo $user;
            $patients = \App\Session::where('doctor_id', $user->id)
            ->groupBy('patient_id')
            ->get()
            ->count();
            //echo $patients;
            $totalPendingAppoint = \App\Appointment::where('doctor_id', $user->id)
            ->get()
            ->count();
            $totalsessions = \App\Session::where('status', 'ended')
            ->where('doctor_id', $user->id)
            ->get()
            ->count();
            @endphp
            <!-- <div class="d-flex justify-content-center">
                <div class="nav-reports-card">
                    <h6>{{ $patients }}</h6>
                    <p>Patients</p>
                </div>
                <div class="nav-reports-card">
                    <h6>{{ $totalPendingAppoint }}</h6>
                    <p>Appointments</p>
                </div>
                <div class="nav-reports-card">
                    <h6>{{ $totalsessions }}</h6>
                    <p>Sessions</p>
                </div>

            </div> -->
        </div>
        <nav class="dashboard-nav-list">
            <a href="{{route('doctor_dashboard')}}" class="dashboard-nav-item"><i class="fa-solid fa-house"></i>
                Dashboard </a>
            <a href="{{ route('doctor_queue') }}" class="dashboard-nav-item"><i
                class="fa-regular fa-square-plus"></i>Online Waiting Room</a>
            @if (Auth::user()->email == 'rama.siddiqui@gmail.com' || Auth::user()->email == 'Dr.RabiaAjaz@gmail.com' || Auth::user()->email == 'zaidtahir@yopmail.com')
                <a href="{{ route('doctor_in_clinic') }}" class="dashboard-nav-item"><i
                    class="fa-regular fa-square-plus"></i>In-Clinic Waiting Room</a>
            @endif
            <a href="{{ route('doc_appointments') }}" class="dashboard-nav-item"><i
                class="fa-regular fa-calendar-check"></i>Appointments</a>
            <a href="{{ route('add_doctor_schedule') }}" class="dashboard-nav-item"><i
                class="fa-regular fa-calendar-days"></i>Schedule </a>
            <a href="{{ route('doctor_profile_management') }}" class="dashboard-nav-item"><i
                class="fa-solid fa-user-doctor"></i>Profile Management</a>
            @if(auth()->user()->specialization == '21')
                <div class="dashboard-nav-dropdown">
                    <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                            class="fa-regular fa-calendar-days"></i> Group Therapy Session </a>
                    <div class="dashboard-nav-dropdown-menu">
                        <a href="{{ route('add_therapy_schedule') }}" class="dashboard-nav-dropdown-item">View Therapy Schedule</a>
                        <a href="{{ route('psychiatrist_form') }}" class="dashboard-nav-dropdown-item">Add Therapy Schedule</a>
                    </div>
                </div>
            @endif
            {{-- <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-vial"></i> Labs </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('labs') }}" class="dashboard-nav-dropdown-item">Order Labs </a>
                    <a href="{{ route('all_lab_reports') }}" class="dashboard-nav-dropdown-item">Patient Reports</a>
                    <a href="{{ route('lab_approve') }}" class="dashboard-nav-dropdown-item">Pending Online Labs</a>
                    <a href="{{ route('approved_labs') }}" class="dashboard-nav-dropdown-item">Approved Labs</a>
                    <a href="{{ route('Doctor_lab_pending_requisition') }}" class="dashboard-nav-dropdown-item">My Pending Requisitions</a>
                    <a href="{{ route('doctor_Lab_requisition') }}" class="dashboard-nav-dropdown-item">My Requisitions</a>
                </div>
            </div> --}}
            {{-- <a href="{{ route('patient_refill_requests') }}" class="dashboard-nav-item"><i
                    class="fa-solid fa-user-doctor"></i> Refill Requests </a> --}}

            <a href="{{ route('user_profile',['username'=>Auth::user()->username]) }}" class="dashboard-nav-item"><i
                class="fa-regular fa-user"></i>My Profile</a>
            <a href="{{ route('patient_all') }}" class="dashboard-nav-item"><i class="fa-solid fa-hospital-user"></i>
                All Patients</a>
            <a href="{{ route('sessions_all') }}" class="dashboard-nav-item"><i
                    class="fas fa-file-upload"></i>Sessions</a>


            <!-- <a href="{{ route('doctors_all') }}" class="dashboard-nav-item"><i class="fa-solid fa-user-doctor"></i> Doctors </a> -->
            <a href="{{ route('doctors_orders') }}" class="dashboard-nav-item"><i
                    class="fa-solid fa-calendar-check"></i> Orders</a>
            {{-- <a href="/doctor/wallet" class="dashboard-nav-item"><i class="fa-solid fa-wallet"></i> Wallet</a> --}}
            <a href="/doctor/account_settings" class="dashboard-nav-item"><i class="fa-solid fa-gear"></i> Account
                Settings</a>
        </nav>
    </div>

    <div class="dashboard-app">
        <header class="dashboard-toolbar">
            <div class="d-flex align-items-baseline">
                <a href="javascript:void(0)" class="menu-toggle"><i class="fas fa-bars"></i></a>
                <!-- <form class="d-flex header-search">
            <input
                class="form-control me-2"
                type="search"
                placeholder="Search"
                aria-label="Search"
            />
            </form> -->

            </div>

            <div class="d-flex shop-bell-icon">
                <div class="head-cart-num">
                    <span>{{ app('item_count_cart') }}</span>
                    <i onclick="window.location.href='/my/cart'" title="Cart" class="fa-solid fa-cart-shopping m-auto"></i>
                </div>
                <span class="label-count cart_counter cart_num"> </span>
                <div class="notification">
                    <div class="notBtn">
                        <div class="number" id="countNote">{{ app('notificationsCount') }}</div>
                        <i title="Notification" class="fas fa-bell"></i>
                        <div class="box noti-box" id="myNotificationDiv">
                            <div class="notifications-btn p-2">
                                <button onclick="window.location.href='{{ route('notifications') }}'" class="active">See
                                    all</button>
                                <button onclick="myFunction()">Mark all read</button>
                                <button id="unreadmsgs">Unmarked</button>
                            </div>
                            <div class="display">
                                <div class="nothing">
                                    <i class="fas fa-child stick"></i>
                                    <div class="cent">Looks Like your all caught up!</div>
                                </div>
                                <div class="cont" id="notif">
                                    @foreach (app('getNote') as $note)
                                    @if ($note->status=='new')
                                    <div class="sec new">
                                        <a href="/ReadNotification/{{$note->id}}">
                                            <div class="profCont">
                                                <img class="profile" src="{{asset('assets/images/notifyuser.png')}}">
                                            </div>
                                            <div class="txt">{{ $note->text }}</div>
                                            <div class="txt sub">{{ Carbon::parse($note->created_at)->diffForHumans();
                                                }}</div>
                                        </a>
                                    </div>
                                    @else
                                    <div class="sec">
                                        <a href="/ReadNotification/{{$note->id}}">
                                            <div class="profCont">
                                                <img class="profile" src="{{asset('assets/images/notifyuser.png')}}">
                                            </div>
                                            <div class="txt">{{ $note->text }}</div>
                                            <div class="txt sub">{{ Carbon::parse($note->created_at)->diffForHumans();
                                                }}</div>
                                        </a>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="dropdown">
                    <div class="profile">
                        @php
                        $user=Auth()->user();
                        $username = $user->username;
                        $image = \App\Helper::check_bucket_files_url(Auth::user()->user_image);
                        @endphp
                        <div id="status_color" class="profile_offline"></div>
                        <img class="dropbtn" src="{{ $image }}">
                        <div class="dropdown-content">
                            <ul>
                                <li class="d-flex justify-content-between border-bottom">
                                    <h6>Current Status:</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                                    </div>
                                </li>
                                <!-- <li class="d-flex justify-content-between">
                            <span>Dr. {{$user->name.' '.$user->last_name}}</span></li> -->
                                <!-- <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                            </div> -->

                                <li onclick="window.location.href='/profile/{{$username}}'"><i
                                        class="fa-regular fa-user"></i><span>Dr. {{$user->name.'
                                        '.$user->last_name}}</span></li>
                                <li onclick="window.location.href='/doctor/account_settings'"><i
                                        class="fa-solid fa-gear"></i><span>Settings</span></li>
                                <li href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                        class="fa-solid fa-right-from-bracket"></i><span>Logout</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </header>
