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
                <a href="{{ route('welcome_page') }}"><img src="{{ asset('assets/images/dashboards_logo.png') }}" alt=""  ></a>
            </div>
            <h5 class="text-center py-2"> Lab Admin Dashboard</h5>

            <div class="d-flex">

            </div>
        </div>
        <nav class="dashboard-nav-list">
          <a href="/lab/admin/dash" class="dashboard-nav-item"><i class="fa-solid fa-house"></i> Dashboard </a>
          <div class="dashboard-nav-dropdown">
            <p
              class="dashboard-nav-item dashboard-nav-dropdown-toggle" style="cursor:pointer;"
              ><i class="fa-solid fa-calendar-check"></i> Lab Tests
            </p>
            <div class="dashboard-nav-dropdown-menu">
              <a href="/quest/lab/tests" class="dashboard-nav-dropdown-item">Labtest</a>
              <a href="/online/lab/tests" class="dashboard-nav-dropdown-item">Online Labtest</a>
              <a href="/lab/test/categories" class="dashboard-nav-dropdown-item">Labtest Categories</a>
              <a href="/lab/reports" class="dashboard-nav-dropdown-item">Patient Lab Reports</a>

            </div>
          </div>
          <div class="dashboard-nav-dropdown">


          </div>

          <a href="/lab/orders" class="dashboard-nav-item"><i class="fa-solid fa-gear"></i>Orders </a>
          {{-- <a href="/quest/orders" class="dashboard-nav-item"><i class="fa-solid fa-gear"></i>Quest Orders </a> --}}
          {{-- <a href="/quest/failed/requests" class="dashboard-nav-item"><i class="fa-solid fa-gear"></i>Quest Failed Requests</a> --}}
          <div class="dashboard-nav-dropdown">
            <p
              class="dashboard-nav-item dashboard-nav-dropdown-toggle" style="cursor:pointer;"
              ><i class="fa-solid fa-calendar-check"></i> Order Approvals
            </p>
            <div class="dashboard-nav-dropdown-menu">
              <a href="/unassigned/lab/orders" class="dashboard-nav-dropdown-item">Unassigned Orders</a>
              <a href="/pending/lab/orders" class="dashboard-nav-dropdown-item">Pending Orders</a>
              <a href="/pending/lab/refunds" class="dashboard-nav-dropdown-item">Pending Refunds</a>

            </div>
          </div>



          <a href="/lab/admin/account/setting" class="dashboard-nav-item"><i class="fa-solid fa-gear"></i>Account Setting </a>

        </nav>
    </div>

    <div class="dashboard-app">
        <header class="dashboard-toolbar">
            <div class="d-flex align-items-baseline">
                <a href="javascript:void(0)" class="menu-toggle"><i class="fas fa-bars"></i></a>
            </div>
            <div class="d-flex shop-bell-icon">
            <div class="notification">
                    <div class="notBtn">
                        <div class="number" id="countNote">{{ app('notificationsCount') }}</div>
                        <i title="Notification" class="fas fa-bell"></i>
                        <div class="box noti-box" id="myNotificationDiv">
                            <div class="notifications-btn p-2">
                                <button onclick="window.location.href='{{ route('notifications') }}'"
                                    class="active">See
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
                        <img class="dropbtn" src="{{ asset('assets/images/user.png') }}">
                        <div class="dropdown-content">
                            <ul>
                                <li class="d-flex justify-content-between">
                                    <span>{{auth()->user()->username}}</span>
                                    <div class="form-check form-switch">
                                    </div>
                                </li>
                                <li onclick="window.location.href='/imaging/admin/account/setting'"><i class="fa-solid fa-gear"></i><span>Settings</span></li>
                                {{-- <li><i class="fa-regular fa-user"></i><span>Profiles</span></li> --}}
                                <li href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

        </header>
