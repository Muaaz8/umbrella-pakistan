@php
    use Carbon\Carbon;
@endphp
<div class="dashboard">
<div class="dashboard-nav" id="style-10">
      <div class="nav-info-div-wrapper">
        <div class="nav-info-img">
            <a href="{{ route('welcome_page') }}"><img src="{{ asset('assets/images/dashboards_logo.png') }}" alt="" ></a>
        </div>
        <h5 class="text-center py-2">Vendor Dashboard</h5>
        <div class="d-flex">
        </div>
      </div>
      <nav class="dashboard-nav-list">
        <a href="/vendor/dash" class="dashboard-nav-item"><i class="fa-solid fa-house"></i> Dashboard </a>
        <div class="dashboard-nav-dropdown">
            <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i class="fas fa-file-contract"></i> Products </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('add_product_page') }}" class="dashboard-nav-dropdown-item">Add Products</a>
                    <a href="{{ route('vendor_products') }}" class="dashboard-nav-dropdown-item">My Products</a>
                    <a href="{{ route('upload_page') }}" class="dashboard-nav-dropdown-item">Upload Products</a>
                </div>
        </div>
        <div class="dashboard-nav-dropdown">
            <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i class="fas fa-file-contract"></i>Request Product </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('request_page') }}" class="dashboard-nav-dropdown-item">Request New Product</a>
                    <a href="{{ route('pending_page') }}" class="dashboard-nav-dropdown-item">Requested Products</a>
                </div>
        </div>
        <a href="{{ route('vendor_all_order') }}" class="dashboard-nav-item"><i class="fa-solid fa-cart-shopping"></i> Orders </a>
      </nav>
    </div>

    <div class="dashboard-app">
        <header class="dashboard-toolbar">
            <div class="d-flex align-items-baseline">
                <a href="javascript:void(0)" class="menu-toggle"><i class="fas fa-bars"></i></a>
                <form class="d-flex header-search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                </form>

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
                        @php
                            $user = Auth()->user();
                            $username = $user->username;
                            $image = \App\Helper::check_bucket_files_url(Auth::user()->user_image);
                        @endphp
                        <img class="dropbtn" src="{{ $image }}">
                        <div class="dropdown-content">
                            <ul>
                                <li class="d-flex justify-content-between">
                                    <span>{{ $user->name }}</span>
                                </li>
                                <li onclick="window.location.href='/imaging/editor/account/setting'"><i
                                        class="fa-solid fa-gear"></i><span>Settings</span></li>
                                <li href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </header>

