@php
    use Carbon\Carbon;
@endphp
<div class="dashboard">
    <div class="dashboard-nav" id="style-10">
        <!-- <header>
          <a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a
          >
          <a href="#" class="brand-logo">
            <img src="assets/images/logo.png" alt="" />
          </a>
        </header> -->
        <div class="nav-info-div-wrapper">
            <div class="nav-info-img">
                <a href="{{ route('welcome_page') }}"><img src="{{ asset('assets/images/dashboards_logo.png') }}" alt="" ></a>
            </div>
            <h5 class="text-center py-2">Pharmacy Admin Dashboard</h5>

            {{-- <div class="d-flex">
                <div class="nav-reports-card">
                    <h6>0</h6>
                    <p>Patient</p>
                </div>
                <div class="nav-reports-card">
                    <h6>0</h6>
                    <p>Patient</p>
                </div>
                <div class="nav-reports-card">
                    <h6>0</h6>
                    <p>Sessions</p>
                </div>
            </div> --}}
        </div>
        <nav class="dashboard-nav-list">
            <a href="{{ url('pharmacy/admin/dash') }}" class="dashboard-nav-item"><i class="fa-solid fa-house"></i> Dashboard
            </a>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-vial"></i> Pharmacy Products </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('pe_view_Medicine') }}" class="dashboard-nav-dropdown-item">View Medicines</a>
                    <a href="{{ route('upload_Medicine') }}" class="dashboard-nav-dropdown-item">Upload Medication</a>
                    <a href="{{ route('dash_medicine_UOM_show') }}" class="dashboard-nav-dropdown-item">Medicine UOM</a>
                    <a href="{{ route('dash_medicine_description') }}" class="dashboard-nav-dropdown-item">Medicine Description</a>
                    <a href="{{ route('pharmacy_editor_prod_cat') }}" class="dashboard-nav-dropdown-item">Main Categories</a>
                    <a href="{{ route('pharmacy_editor_sub_cat') }}" class="dashboard-nav-dropdown-item">Sub Categories</a>
                </div>
            </div>
            <a href="{{ route('pharmacy_editor_orders') }}" class="dashboard-nav-item"><i
                class="fa-solid fa-calendar-check"></i>Orders</a>
            <a href="{{ route('pharmacy_admin_manage_editors') }}" class="dashboard-nav-item"><i
                class="fa-solid fa-calendar-check"></i>Manage Editors</a>
            <a href="{{ route('pharmacy_editor_setting') }}" class="dashboard-nav-item"><i
                    class="fa-solid fa-gear"></i> Account Settings </a>
        </nav>
    </div>

    <div class="dashboard-app">
        <header class="dashboard-toolbar">
            <div class="d-flex align-items-baseline">
                <a href="javascript:void(0)" class="menu-toggle"><i class="fas fa-bars"></i></a>
            </div>
            <div class="d-flex shop-bell-icon">
                {{-- <div class="head-cart-num">
                    <span>{{ app('item_count_cart') }}</span>
                    <i onclick="window.location.href='/my/cart'" title="Cart"
                        class="fa-solid fa-cart-shopping m-auto"></i>
                </div>
                <span class="label-count cart_counter cart_num"> </span> --}}
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
                                        @if ($note->status == 'new')
                                            <div class="sec new">
                                                <a href="/ReadNotification/{{ $note->id }}">
                                                    <div class="profCont">
                                                        <img class="profile"
                                                            src="{{ asset('assets/images/notifyuser.png') }}">
                                                    </div>
                                                    <div class="txt">{{ $note->text }}</div>
                                                    <div class="txt sub">
                                                        {{ Carbon::parse($note->created_at)->diffForHumans() }}</div>
                                                </a>
                                            </div>
                                        @else
                                            <div class="sec">
                                                <a href="/ReadNotification/{{ $note->id }}">
                                                    <div class="profCont">
                                                        <img class="profile"
                                                            src="{{ asset('assets/images/notifyuser.png') }}">
                                                    </div>
                                                    <div class="txt">{{ $note->text }}</div>
                                                    <div class="txt sub">
                                                        {{ Carbon::parse($note->created_at)->diffForHumans() }}</div>
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
                            $user = Auth()->user();
                            $username = $user->username;
                            $image = \App\Helper::check_bucket_files_url(Auth::user()->user_image);
                        @endphp
                        <img class="dropbtn" src="{{ $image }}">
                        <div class="dropdown-content">
                            <ul>
                                <li class="d-flex justify-content-between">
                                    <span>{{ $user->name . ' ' . $user->last_name }}</span>
                                </li>
                                <li onclick="window.location.href='/admin/acc/settings'"><i
                                        class="fa-solid fa-gear"></i><span>Settings</span></li>
                                <li href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </header>
