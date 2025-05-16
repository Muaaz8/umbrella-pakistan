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
            <h5 class="text-center py-2">Admin Dashboard</h5>

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
            <a href="{{ url('admin/dash') }}" class="dashboard-nav-item"><i class="fa-solid fa-house"></i> Dashboard
            </a>
            <a href="{{ route('lab_for_patient') }}" class="dashboard-nav-item"><i class="fa-solid fa-flask"></i>Lab For Patient</a>
            <a href="{{ route('inclinic_patient') }}" class="dashboard-nav-item"><i class="fa-solid fa-house-chimney-medical"></i>In Clinic</a>
            <a href="{{ route('inclinic_pharmacy_editor_orders') }}" class="dashboard-nav-item"><i
                class="fa-solid fa-calendar-check"></i>Inclinic Orders</a>
            {{-- <a href="{{ route('admin_wallet_pay') }}" class="dashboard-nav-item"><i
                    class="fa fa-dollar-sign"></i>Finance</a> --}}
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fas fa-file-contract"></i> Add Items </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('mental_condition') }}" class="dashboard-nav-dropdown-item">Conditions</a>
                    <a href="{{ route('FAQs') }}" class="dashboard-nav-dropdown-item"> FAQs</a>
                </div>
            </div>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa fa-user-plus"></i> Vendors </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('add_vender_page') }}" class="dashboard-nav-dropdown-item"> Add Vendor</a>
                    <a href="{{ route('all_vendors_page') }}" class="dashboard-nav-dropdown-item">All Vendors</a>
                </div>
            </div>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa fa-user-plus"></i> Locations </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('locations.index') }}" class="dashboard-nav-dropdown-item"> All Location</a>
                    <a href="{{ route('locations.create') }}" class="dashboard-nav-dropdown-item">Add Location</a>
                </div>
            </div>
            {{-- <a href="{{ route('admin_error_log_view') }}" class="dashboard-nav-item"><i
                    class="fa-solid fa-circle-exclamation"></i> Error Log </a>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-vial"></i> Edit Permissions </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('admin_patient_records') }}" class="dashboard-nav-dropdown-item"> Patient Records
                    </a>
                    <a href="{{ route('admin_doctor_profile_update') }}" class="dashboard-nav-dropdown-item"> Doctor
                        Records </a>
                </div>
            </div>--}}
            <a href="{{ route('fee_approval') }}" class="dashboard-nav-item"><i
                class="fa-solid fa-dollar-sign"></i>Fee Approvals</a>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fas fa-user-md"></i> Doctors </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('all_doctors') }}" class="dashboard-nav-dropdown-item">All Doctors </a>
                    <a href="{{ route('online_docs') }}" class="dashboard-nav-dropdown-item">Online Doctors </a>
                    <a href="{{ route('all_doctor_schedule') }}" class="dashboard-nav-dropdown-item"> Doctor
                        Schedule</a>
                    <a href="{{ route('pending_doctor_requests') }}" class="dashboard-nav-dropdown-item"> Pending
                        Doctor
                        Request</a>
                    <a href="{{ route('blocked_doctor') }}" class="dashboard-nav-dropdown-item">
                        Blocked Doctors
                    </a>
                    {{-- <a href="{{ route('lab_approval_doctor') }}" class="dashboard-nav-dropdown-item">
                        Lab Approval Doctors
                    </a> --}}
                    <a href="{{ route('pending_contract') }}" class="dashboard-nav-dropdown-item">
                        Contract Pending Doctors
                    </a>
                    <a href="{{ route('admin_doctor_profile_management') }}" class="dashboard-nav-dropdown-item">
                        Doctor Profile Management
                    </a>


                </div>
            </div>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid  fa-medkit"></i> Specializations </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('specializations') }}" class="dashboard-nav-dropdown-item">View All
                        Specialization</a>
                    {{-- <a href="{{ route('all_doctor_schedule') }}" class="dashboard-nav-dropdown-item"> Add Specialization</a> --}}
                    {{-- <a href="{{ route('price_specializations') }}" class="dashboard-nav-dropdown-item"> Add
                        Specialization Price</a> --}}

                </div>
            </div>
            {{-- <a href="{{ route('admin_lab_reports') }}" class="dashboard-nav-item"><i class="fa-solid fa-file"></i>Reports</a> --}}
            {{-- <a href="{{ route('admin_all_state') }}" class="dashboard-nav-item"><i class="fa-sharp fa-solid fa-location-dot"></i>All
                States</a> --}}
            <a href="{{ route('all_sessions_record') }}" class="dashboard-nav-item"><i
                    class="fa-solid fa-hand-holding-medical"></i>All Sessions</a>
            <a href="{{ route('admin_inclinic_sessions') }}" class="dashboard-nav-item"><i
                    class="fa-solid fa-calendar-check"></i>Inclinic Sessions</a>
            <a href="{{ route('admin_all_appointments') }}" class="dashboard-nav-item"><i
                    class="fa-solid fa-calendar-check"></i>All Appointments</a>
            <a href="{{ route('manage_all_users', ['id', 'all']) }}" class="dashboard-nav-item"><i
                    class="fas fa-file-upload"></i>Manage Users</a>
            <a href="{{ route('admin_all_patients') }}" class="dashboard-nav-item"><i
                    class="fas fa-users"></i>All Patients</a>
            <a href="{{ route('all_orders_admin') }}" class="dashboard-nav-item"><i
                    class="fa-solid fa-calendar-check"></i> Orders </a>
            {{-- <a href="{{ route('admin_quest_orders') }}" class="dashboard-nav-item"><i
                    class="fa-solid fa-calendar-check"></i> Quest Orders </a> --}}
            {{-- <a href="{{ route('admin_e_prescription') }}" class="dashboard-nav-item"><i
                    class="fa-regular fa-calendar-days"></i> E-Prescriptions </a> --}}
            {{-- <a href="{{ route('imaging_file') }}" class="dashboard-nav-item"><i
                    class="fa-regular fa-calendar-days"></i> Imaging Orders </a> --}}
            {{-- <a href="{{ route('admin_quest_failed_requests') }}" class="dashboard-nav-item"><i
                    class="fa-regular fa-calendar-days"></i> Quest Failed Requests </a> --}}
            <a href="{{ route('admin_contact_us') }}" class="dashboard-nav-item"><i class="fa-regular fa-calendar-days"></i>
                Contact us </a>
            <a href="{{ route('tbl_transaction') }}" class="dashboard-nav-item"><i class="fa-regular fa-calendar-days"></i>
                Transactions</a>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-vial"></i> Coupon </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('ViewCoupons') }}" class="dashboard-nav-dropdown-item">View All
                        Coupons</a>
                    <a href="{{ route('CouponPage') }}" class="dashboard-nav-dropdown-item">Coupons</a>

                </div>
            </div>
            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-vial"></i> Documents </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('docs') }}" class="dashboard-nav-dropdown-item">View Terms Of Use</a>
                    <a href="{{ route('add_docs') }}" class="dashboard-nav-dropdown-item"> Add Terms Of Use</a>
                    {{-- <a href="{{ route('price_specializations') }}" class="dashboard-nav-dropdown-item"> Add Specialization Price</a> --}}

                </div>
            </div>
            {{-- <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-vial"></i> Psychiatry </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('admin_view_psychiatrist_services') }}"
                        class="dashboard-nav-dropdown-item">View Psychiatry Services</a>
                    <a href="{{ route('admin_addPsycService') }}" class="dashboard-nav-dropdown-item"> Add Psychiatry
                        Services</a>
                    <a href="{{ route('price_specializations') }}" class="dashboard-nav-dropdown-item"> Add Specialization Price</a>

                </div>
            </div> --}}
            {{-- <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-vial"></i> Therapy </a>
                <div class="dashboard-nav-dropdown-menu">
                    <a href="{{ route('admin_view_therapy_issues') }}"
                        class="dashboard-nav-dropdown-item">View Therapy Issues</a>
                    <a href="{{ route('admin_add_therapy_issues') }}" class="dashboard-nav-dropdown-item"> Add Therapy Issues</a>
                    <a href="{{ route('price_specializations') }}" class="dashboard-nav-dropdown-item"> Add Specialization Price</a>

                </div>
            </div> --}}
            {{-- <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-image"></i> Banners </a>
                <div class="dashboard-nav-dropdown-menu">
                <a href="{{ route('view_banners') }}" class="dashboard-nav-dropdown-item"> View Banners </a>
                <a href="{{ route('upload_banner') }}" class="dashboard-nav-dropdown-item"> Upload Banners </a>
                </div>
            </div> --}}

            <div class="dashboard-nav-dropdown">
                <a href="javascript:void(0)" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                        class="fa-solid fa-image"></i> Related Products </a>
                <div class="dashboard-nav-dropdown-menu">
                <a href="{{ route('related_products.index') }}" class="dashboard-nav-dropdown-item"> View Related Products </a>
                <a href="{{ route('related_products.create') }}" class="dashboard-nav-dropdown-item"> Add Related Products </a>
                </div>
            </div>

            {{-- <a href="{{ route('admin_physical_location') }}" class="dashboard-nav-item"><i
                 class="fa fa-map-marker" aria-hidden="true"></i> Physical Locations </a>
            <a href="{{ route('medicine_purchase') }}" class="dashboard-nav-item"><i class="fa-solid fa-capsules"></i> Medicine Purchase </a> --}}

            <a href="{{ route('admin_acc_settings') }}" class="dashboard-nav-item"><i
                    class="fa-regular fas fa-gear"></i> Account Settings </a>

        </nav>
    </div>

    <div class="dashboard-app">
        <header class="dashboard-toolbar">
            <div class="d-flex align-items-baseline">
                <a href="javascript:void(0)" class="menu-toggle"><i class="fas fa-bars"></i></a>
                {{-- <form class="d-flex header-search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                </form> --}}

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
                                <li onclick="window.location.href='/admin/acc/settings'"><i
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
