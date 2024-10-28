@php
use Carbon\Carbon;
@endphp
@php
$user = \Auth::user();
//echo $user;
$doctors = \App\Session::where('patient_id', $user->id)
->groupBy('doctor_id')
->get()
->count();
//echo $patients;
$totalPendingAppoint = \App\Appointment::where('patient_id', $user->id)
->get()
->count();
$totalsessions = \App\Session::where('status', 'ended')
->where('patient_id', $user->id)
->get()
->count();
@endphp
<div class="dashboard">
    <div class="dashboard-nav" id="style-10">
        <header>
            <a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a>
        </header>
        <div class="nav-info-div-wrapper">
            <div class="nav-info-img">
                <a href="{{ route('welcome_page') }}"><img src="{{ asset('assets/images/dashboards_logo.png') }}" alt="" ></a>
            </div>
            <h5 class="text-center pt-3">Patient Dashboard</h5>
            <!-- <div class="d-flex justify-content-center">
                <div class="nav-reports-card">
                    <h6>{{ $doctors }}</h6>
                    <p>Doctors</p>
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
            <a href="{{ route('New_Patient_Dashboard') }}" class="dashboard-nav-item"><i class="fa-solid fa-house"></i>
                Dashboard </a>
            <a href="{{ route('symptom_checker') }}" class="dashboard-nav-item"><i class="fa-solid fa-virus"></i> Symptom Checker</a>
            <a href="{{ route('patient_evisit_specialization') }}" class="dashboard-nav-item"><i
                class="fa-solid fa-stethoscope"></i> E-Visit</a>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-calendar-check"></i> Appointment</a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="/specializations" class="dashboard-nav-dropdown-item">Book Appointments</a>
                    <a href="/patient/appointments" class="dashboard-nav-dropdown-item">My Appointments</a>
                </div>
            </div>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-users"></i>Group Therapy Session</a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="/therapy/events" class="dashboard-nav-dropdown-item">Upcoming Events</a>
                    <!-- <a href="/patient/appointments" class="dashboard-nav-dropdown-item">My Appointments</a> -->
                </div>
            </div>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-capsules"></i> Pharmacy</a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('pharmacy') }}" class="dashboard-nav-dropdown-item">View Medicines</a>
                    <a href="{{ route('current_medication') }}" class="dashboard-nav-dropdown-item">Current
                        Medications</a>
                </div>
            </div>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-vial"></i> Labs</a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('labs') }}" class="dashboard-nav-dropdown-item">Order Labs</a>
                    <a href="{{ route('patient_Lab_requisition') }}" class="dashboard-nav-dropdown-item">Lab
                        Requisitions</a>
                    <a href="{{ route('Lab_pending_requisition') }}" class="dashboard-nav-dropdown-item">Pending
                        Requisitions</a>
                    <a href="{{ route('patient_Lab_result') }}" class="dashboard-nav-dropdown-item">Reports</a>
                </div>
            </div>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-x-ray"></i>Imaging</a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('imaging') }}" class="dashboard-nav-dropdown-item">View Services</a>
                    <a href="{{ route('patient_imaging_orders') }}" class="dashboard-nav-dropdown-item">Reports</a>
                    <a href="{{ route('patient_imaging_file') }}" class="dashboard-nav-dropdown-item">Imaging File</a>
                </div>
            </div>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-user"></i> Profile</a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('user_profile',['username'=>Auth::user()->username]) }}"
                        class="dashboard-nav-dropdown-item">My Profile</a>
                    <a href="{{ route('patient_medical_profile') }}" class="dashboard-nav-dropdown-item">Medical
                        Profile</a>
                </div>
            </div>

            <a href="{{ route('my_doctors') }}" class="dashboard-nav-item"><i class="fa fa-user-md"></i> My Doctors</a>
            <a href="{{ route('patients_all_sessions') }}" class="dashboard-nav-item"><i
                    class="fa-solid fa-hand-holding-medical"></i> Sessions Record</a>
            <a href="{{ route('patient_all_order') }}" class="dashboard-nav-item"><i
                    class="fa-solid fa-calendar-check"></i> Orders</a>
            <a href="{{ route('patient_acc_settings') }}" class="dashboard-nav-item"><i class="fa-solid fa-gear"></i>
                Account Settings</a>
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
                <!-- <div class="position-relative dash-cart-num">
            <span>5</span> -->
                <div class="head-cart-num" title="Cart">
                    <span>{{ app('item_count_cart') }}</span>
                    <i onclick="window.location.href='/my/cart'" class="fa-solid fa-cart-shopping m-auto"></i>
                </div>
                <!-- </div> -->
                <span class="label-count cart_counter cart_num"> </span>
                <div class="notification">
                    <div class="notBtn">
                        <div class="number " id="countNote">{{ app('notificationsCount') }}</div>
                        <i title="Notificatoin" class="fas fa-bell"></i>
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
                                        <a href="/ReadNotification/{{ $note->id }}">
                                            <div class="profCont">
                                                <img class="profile" src="{{asset('assets/images/logo.png')}}">
                                            </div>
                                            <div class="txt">{{ $note->text }}</div>
                                            <div class="txt sub">{{ Carbon::parse($note->created_at)->diffForHumans();
                                                }}</div>
                                        </a>
                                    </div>
                                    @else
                                    <div class="sec">
                                        <a href="/ReadNotification/{{ $note->id }}">
                                            <div class="profCont">
                                                <img class="profile" src="{{asset('assets/images/logo.png')}}">
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
                        <!-- <div class="profile_online"></div> -->
                        <img class="dropbtn" src="{{ $image }}">
                        <div class="dropdown-content">
                            <ul>
                                <!-- <li class="d-flex justify-content-between border-bottom">
                        <h6>Current Status:</h6>
                            <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                            </div>
                        </li> -->
                                <!-- <li class="d-flex justify-content-between">
                            <span>{{$user->name.' '.$user->last_name}}</span></li> -->
                                <!-- <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                            </div> -->
                                <li onclick="window.location.href='/profile/{{$username}}'"><i
                                        class="fa-regular fa-user"></i><span>{{$user->name.' '.$user->last_name}}</span>
                                </li>
                                <li onclick="window.location.href='/patient/account'"><i
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
