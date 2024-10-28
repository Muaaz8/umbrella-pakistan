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
            <h5 class="text-center py-2"> Chat Support</h5>

            <div class="d-flex">

            </div>
        </div>
        <nav class="dashboard-nav-list">
          <a href="/chat/support" class="dashboard-nav-item"><i class="fa-solid fa-house"></i> Dashboard </a>
          <!-- <a href="#" class="dashboard-nav-item"><i class="fa-solid fa-calendar-check"></i>Wallet</a> -->
          <!-- <a href="/doctors/finance/reports" class="dashboard-nav-item"><i class="fa-solid fa-calendar-check"></i>Doctor Reports</a>
          <a href="/vendors" class="dashboard-nav-item"><i class="fa-solid fa-calendar-check"></i>Vendors</a> -->
          <a href="/chatbot/questions" class="dashboard-nav-item"><i class="fa-solid fa-calendar-check"></i>Questions</a>
          <a href="/chat/support/account/setting" class="dashboard-nav-item"><i class="fa-solid fa-gear"></i>Account Setting </a>

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

            <!-- <div class="d-flex">

                <i class="fa-solid fa-cart-shopping m-auto fs-3"></i>

            </div> -->



                <div class="dropdown">
                    <div class="profile">
                        <div class=""></div>
                        <img class="dropbtn" src="{{ asset('assets/images/user.png') }}">
                        <div class="dropdown-content">
                            <ul>
                                <li class="d-flex justify-content-between">
                                    <span>{{auth()->user()->username}}</span>
                                    <div class="form-check form-switch">
                                    </div>
                                </li>
                                <li onclick="window.location.href='/finance/admin/account/setting'"><i class="fa-solid fa-gear"></i><span>Settings</span></li>
                                <!-- <li><i class="fa-regular fa-user"></i><span>Profiles</span></li> -->
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
