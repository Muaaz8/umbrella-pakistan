<meta name="csrf-token" content="{{ csrf_token() }}" />
<section>

    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <div class="umb-logo-border">
        <div class="umb-log">
            <div class="navbar-header">
                <!-- <a href="javascript:void(0);" class="bars"></a> -->
                <a class="" href="/">
                    <img src="{{ asset('asset_admin/images/logo.png')}}" class="img-center">
                </a>
            </div>
        </div>
    </div>
        <!-- User Info -->
        <div class="user-info">
            <div class="quick-stats">
                @hasanyrole('admin')
                <h5> Admin Dashboard</h5>
                <ul>
                    <li><span>16<i>Doctors</i></span></li>
                    <li><span>20<i>Patients</i></span></li>
                    <!-- <li><span>04<i>Visit</i></span></li> -->
                </ul>
                @endhasanyrole
                @hasanyrole('doctor')
                <h5> Doctor Dashboard</h5>
                <ul>
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
                    <li><span>{{ $patients }}<i>Patient</i></span></li>
                    <li><span>{{ $totalPendingAppoint }}<i>Appointments</i></span></li>
                    <li><span>{{ $totalsessions }}<i>Sessions</i></span></li>
                </ul>
                @endhasanyrole

                @hasanyrole('patient')
                <h5>Patient Dashboard</h5>

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
                <ul>
                    <li><span>{{ $doctors }}<i>Doctor</i></span></li>
                    <li><span>{{ $totalPendingAppoint }}<i>Appointments</i></span></li>
                    <li><span>{{ $totalsessions }}<i>Sessions</i></span></li>
                </ul>
                @endhasanyrole
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list" style="height: calc(95vh - 184px) !important;">
                <li class="header">MAIN NAVIGATION</li>

                @hasanyrole('admin_pharmacy')
                <li><a href="/editors"><i class="fa fa-users"></i><span>Manage Editors</span> </a></li>
                <li><a href="javascript:void(0);" class="menu-toggle"><i
                            class="zmdi zmdi-calendar-check"></i><span>Pharmacy Products</span> </a>
                    <ul class="ml-menu">
                        <li><a href="/allProducts/create?form_type=pharmacy">Add New Medicine</a></li>
                        <li><a href="/allProducts">View Medicine</a></li>
                        <li><a href="/productCategories">Main Categories</a></li>
                        {{-- <li><a href="/productsSubCategories">Sub Categories</a></li> --}}
                    </ul>
                </li>
                <li><a href="/orders"><i class="zmdi zmdi-calendar-check"></i><span>Orders</span></a></li>
                <li class="mb-5"><a href="#"><i class="fa fa-cog"></i><span>Account Settings</span></a>
                </li>
                @endhasanyrole
                @hasanyrole('admin_lab')
                <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-calendar-check"></i><span>Add
                            Laboratory
                        </span> </a>
                    <ul class="ml-menu">
                        <li><a href="/mapMarkers">View Labs</a></li>
                        <li><a href="/mapMarkers/create?form_type=lab">Add New Labs</a></li>
                    </ul>
                </li>
                <li><a href="/editors"><i class="fa fa-users"></i><span>Manage Editors</span> </a>

                <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-calendar-check"></i><span>Lab
                            Tests</span> </a>
                    <ul class="ml-menu">
                        <li><a href="/allProducts">View Test</a></li>
                        <li><a href="/viewAllQuestLabTest">Quest Lab Tests</a></li>
                        <li><a href="/allProducts/create?form_type=lab-test">New Lab Test</a></li>
                        <li><a href="/allProducts/create?form_type=panel-test">New Test Panel</a></li>
                        <li><a href="/productCategories">Test Categories</a></li>
                    </ul>
                </li>
                <li><a href="/orders"><i class="zmdi zmdi-calendar-check"></i><span>Orders</span></a></li>
                <li><a href="{{ route('quest_orders') }}"><i class="zmdi zmdi-calendar-check"></i><span>Quest
                            Orders</span></a></li>
                <li><a href="{{ route('quest_failed_requests') }}"><i class="zmdi zmdi-plus"></i><span>Quest Failed
                            Requests</span></a></li>
                <li><a href="javascript:void(0);" class="menu-toggle"><i
                            class="zmdi zmdi-calendar-check"></i><span>Order Approvals</span> </a>
                    <ul class="ml-menu">
                        <li><a href="/unassignedLabOrders">Unassigned Orders</a></li>
                        <li><a href="/pendingLabOrders">Pending Orders</a></li>
                        <li><a href="/pendingRefunds">Pending Refunds</a></li>
                    </ul>
                </li>
                <li class="mb-5"><a href="#"><i class="fa fa-cog"></i><span>Account Settings</span></a>
                </li>
                @endhasanyrole
                @hasanyrole('editor_pharmacy')
                {{-- <li><a href="javascript:void(0);" class="menu-toggle"><i
                                class="zmdi zmdi-calendar-check"></i><span>Add
                                Pharmacy
                                Tests</span> </a>
                        <ul class="ml-menu">
                            <li><a href="/mapMarkers">View Pharmacy</a></li>
                            <li><a href="/mapMarkers/create?form_type=pharmacy">Add New Pharmacy</a></li>
                        </ul>
                    </li> --}}
                <li><a href="javascript:void(0);" class="menu-toggle"><i
                            class="zmdi zmdi-calendar-check"></i><span>Pharmacy Products</span> </a>
                    <ul class="ml-menu">
                        <li><a href="/viewMedicines">View Medicines</a></li>
                        {{-- <li><a href="/allProducts/create?form_type=pharmacy">Add New Medicine</a></li> --}}
                        <li><a href="/uploadMedicineByCSV">Upload RxOutreach Medication</a></li>
                        <li><a href="/medicineUOM">Medicines UOM</a></li>
                        <li><a href="/productCategories">Main Categories</a></li>
                        <li><a href="/productsSubCategories">Sub Categories</a></li>
                        <li><a href="/medicine_description">Medicine Description</a></li>

                    </ul>

                </li>
                {{-- <li><a href="javascript:void(0);" class="menu-toggle"><i
                                class="zmdi zmdi-calendar-check"></i><span>Pharmacy</span> </a>
                        <ul class="ml-menu">
                            <li><a href="{{ route('add_pharmacy_location') }}">Add New </a></li>
                <li><a href="{{ route('view_pharmacy_location') }}">List All</a></li>
            </ul>
            </li> --}}
            <li><a href="/orders"><i class="zmdi zmdi-calendar-check"></i><span>Orders</span></a></li>
            <li class="mb-5"><a href="#"><i class="fa fa-cog"></i><span>Account Settings</span></a>
            </li>
            @endhasanyrole

            @hasanyrole('finance_admin')
            <li><a href="javascript:void(0);" class="menu-toggle"><i
                        class="zmdi zmdi-calendar-check"></i><span>Transactions</span> </a>
                <ul class="ml-menu">
                    <li><a href="{{ route('finance.create') }}">Add Transaction</a></li>
                    <li><a href="{{ route('finance.index') }}">View Transaction</a></li>
                </ul>
            </li>
            @endhasanyrole

            @hasanyrole('editor_lab')
            <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-calendar-check"></i><span>Lab
                        Tests</span> </a>
                <ul class="ml-menu">
                    <li><a href="/viewAllQuestLabTest">Quest Labtest</a></li>
                    <li><a href="/allProducts">Online Labtest</a></li>
                    <li><a href="/allProducts/create?form_type=lab-test">Add Online Labtest</a></li>
                    {{-- <li><a href="/allProducts/create?form_type=panel-test">New Test Panel</a></li> --}}
                    <li><a href="/productCategories">Labtest Categories</a></li>
                    <!-- <li><a href="/productsSubCategories">Sub Categories</a></li> -->
                </ul>
            </li>
            {{-- <li><a href="javascript:void(0);" class="menu-toggle"><i
                                class="zmdi zmdi-calendar-check"></i><span>Add
                                Laboratory
                            </span> </a>
                        <ul class="ml-menu">
                            <li><a href="/mapMarkers">View Labs</a></li>
                            <li><a href="/mapMarkers/create?form_type=lab">Add New Labs</a></li>
                        </ul>
                    </li> --}}

            {{-- <li><a href="javascript:void(0);" class="menu-toggle"><i
                                class="zmdi zmdi-calendar-check"></i><span>Labtest FAQ's</span> </a>
                        <ul class="ml-menu">
                            <li><a href="/faq">View FAQ's</a></li>
                            <li><a href="/faq/{id}'">Add FAQ's</a></li>
                        </ul>
                    </li> --}}

            <li><a href="/orders"><i class="zmdi zmdi-calendar-check"></i><span>Orders</span></a></li>
            <li><a href="{{ route('quest_orders') }}"><i class="zmdi zmdi-calendar-check"></i><span>Quest
                        Orders</span></a></li>
            <li><a href="{{ route('quest_failed_requests') }}"><i class="zmdi zmdi-plus"></i><span>Quest Failed
                        Requests</span></a></li>
            <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-calendar-check"></i><span>Order
                        Approvals</span> </a>
                <ul class="ml-menu">
                    <li><a href="/unassignedLabOrders">Unassigned Orders</a></li>
                    <li><a href="/pendingLabOrders">Pending Orders</a></li>
                </ul>
            </li>
            <li class="mb-5"><a href="{{ route('acc_settings') }}"><i class="fa fa-cog"></i><span>Account Settings</span></a>
            </li>
            @endhasanyrole
            @hasanyrole('editor_imaging')
            <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-calendar-check"></i><span>Imaging
                        Services</span> </a>
                <ul class="ml-menu">
                    <li><a href="/imaging_services_all">Imaging All Records</a></li>
                    <li><a href="/imaging_services">View Services</a></li>
                    <li><a href="/allProducts/create?form_type=imaging_services">Add Services</a></li>
                    <li><a href="/allProducts/create?form_type=imaging_location">Add Location</a></li>
                    <li><a href="/imaging_locations">View Locations</a></li>
                    <li><a href="/bulkUploadImagingServices">Bulk Upload Services</a></li>
                    <li><a href="/bulkUploadImagingPrices">Bulk Upload Prices</a></li>
                    <li><a href="/allProducts/create?form_type=imaging">Add Prices</a></li>
                    <li><a href="/imaging_prices">View Prices</a></li>
                    <li><a href="/productCategories/create">Add Category</a></li>
                    <li><a href="/productCategories">View Category</a></li>
                </ul>
            </li>
            <li><a href="/orders"><i class="zmdi zmdi-calendar-check"></i><span>Orders</span></a></li>
            <li class="mb-5"><a href="#"><i class="fa fa-cog"></i><span>Account Settings</span></a>
            </li>
            @endhasanyrole
            <!-- Admin -->
            @hasanyrole('admin')
            <li @if (\Request::route()->getName() == 'home') class="active open" @endif>
                <a href="{{ route('home') }}"><i class="zmdi zmdi-home">
                    </i><span>Dashboard</span>
                </a>
            </li>

            <li @if (\Request::route()->getName() == 'wallet') class="active open" @endif><a
                    href="{{ route('admin_wallet') }}"><i class="fa fa-dollar-sign"></i><span>Wallet</span></a>
            </li>
            <li><a href="{{ route('new_item') }}"><i class="fa fa-plus-square"></i><span>Add Item</span></a>
            </li>
            <li><a href="{{ route('error_log_view') }}"><i class="fa fa-plus-square"></i><span>View Errors</span></a>
            </li>



            <li
                ><a href="javascript:void(0);" class="menu-toggle"><i class="fa fa-user"></i><span>Edit Permission</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('patient_records') }}">Patient Records</a></li>
                    <li><a href="{{ route('Doctor_profile_update') }}">Doctors Records</a></li>

                </ul>
            </li>




            <li @if (\Request::route()->getName() == 'admin_doctor_calendar'||\Request::route()->getName() ==
                'admin_doctors'
                ||\Request::route()->getName() == 'pending_doctors') class="active open" @endif
                ><a href="javascript:void(0);" class="menu-toggle"><i class="fa fa-user"></i><span>Doctors</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin_doctors') }}">All Doctors</a></li>
                    <li><a href="{{ route('admin_doctor_calendar') }}">Doctors Schedule</a></li>
                    <li><a href="{{ route('pending_doctors') }}">Pending Doctor Requests</a></li>
                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'addSpec'||\Request::route()->getName() == 'addSpecPrice') class="active open" @endif
                ><a href="javascript:void(0);" class="menu-toggle"><i class="fa fa-user"></i><span>Specialization</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('viewSpec') }}">View all Specialization</a></li>
                    <li><a href="{{ route('addSpec') }}">Add Specialization</a></li>
                    <li><a href="{{ route('addSpecPrice') }}">Add Specialization Price</a></li>
                </ul>
            </li>

            <li><a href="{{ route('all-states') }}"><i class="fa fa-plus-square"></i><span>All
                        States</span></a>
            </li>
            <li><a href="{{ route('sessions.record') }}"><i class="fa fa-plus-square"></i><span>All
                        Sessions</span></a>
            </li>

            <li><a href="{{ route('appointment.index') }}"><i class="zmdi zmdi-calendar-check"></i><span>All
                        Appointments</span></a></li>
            <li><a href="{{ route('manage_users') }}"><i class="fa fa-users"></i><span>Manage Users</span></a>
            </li>
            <li><a href="javascript:void(0);" class="menu-toggle"><i class="fa fa-user"></i><span>Delete
                        Requests</span> </a>
                <ul class="ml-menu">
                    <li><a href="{{ route('all_prod_del_req') }}">Products</a></li>
                    <li><a href="{{ route('doctor_calendar') }}">Calendar Schedule</a></li>
                </ul>
            </li>
            <li><a href="{{ route('admin_patients') }}"><i class="fa fa-user"></i><span>All
                        Patients</span></a>
            </li>
            <li><a href="/orders"><i class="zmdi zmdi-calendar-check"></i><span>Orders</span></a></li>
            <li><a href="{{ route('quest_orders') }}"><i class="zmdi zmdi-calendar-check"></i><span>Quest
                        Orders</span></a></li>
            <li><a href="{{ route('e_prescription') }}"><i class="zmdi zmdi-calendar-check"></i><span>E-Prescriptions</span></a></li>

            <li><a href="{{ route('quest_failed_requests') }}"><i class="zmdi zmdi-plus"></i><span>Quest Failed
                        Requests</span></a></li>
            <li><a href="{{ route('admin_contact') }}"><i class="fas fa-user-friends"></i><span>Contact
                        Us</span></a></li>
            <li><a href="javascript:void(0);" class="menu-toggle"><i class="fa fa-file" aria-hidden="true"></i>
                    <span>Documents
                    </span> </a>
                <ul class="ml-menu">
                    <li><a href="/add_terms_of_use">Add Terms of Use</a></li>
                    <li><a href="/view_terms_of_use">View Terms</a></li>
                    <li><a href="/add_privacy_policy">Add Privacy Policy</a></li>
                    <li><a href="/view_privacy_policy">View Privacy Policy</a></li>

                </ul>
            </li>

            <li><a href="javascript:void(0);" class="menu-toggle"><i class="fa fa-file" aria-hidden="true"></i>
                <span>Psychiatrist
                </span> </a>
            <ul class="ml-menu">
                <li><a href="/add/PsychiatryService">Add Psychiatry Service</a></li>
                <li><a href="/view_psychiatrist_services">View Psychiatry services</a></li>

            </ul>
        </li>

            <!-- <li><a href="{{ route('inbox') }}"><i class="fa fa-envelope"></i><span>Inbox</span></a></li> -->

            <li class=" @if (\Request::route()->getName() == 'acc_settings') active open @endif mb-5">
                <a href="{{ route('acc_settings') }}"><i class="fa fa-cog"></i><span>Account
                        Settings</span></a>
            </li>
            @endhasanyrole
            @hasanyrole('doctor')
            <li @if (\Request::route()->getName() == 'home') class="active open" @endif>
                <a href="{{ route('home') }}"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a>
            </li>
            <li @if (\Request::route()->getName() == 'user_profile') class="active open" @endif>
                <a href="{{ url('/user/profile/'.auth()->user()->username) }}">
                    <i class="zmdi zmdi-account-o"></i><span>Profile</span>
                </a>
            </li>
            <li @if (\Request::route()->getName() == 'doctor_calendar') class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle"><i
                        class="fas fa-calendar-alt"></i><span>Schedule</span> </a>
                <ul class="ml-menu">
                    <!-- <li><a href="{{ route('doctor_schedule') }}">My Schedule</a></li> -->
                    <li @if (\Request::route()->getName() == 'doctor_calendar') class="active" @endif>
                        <a href="{{ route('doctor_calendar') }}">Calendar Schedule</a>
                    </li>
                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'doc_waiting_room') class="active open" @endif>
                <a href="{{ route('doc_waiting_room') }}">
                    <i class="far fa-plus-square"></i><span>Waiting
                        Room</span></a>
            </li>
            <li @if (\Request::route()->getName() == 'patients') class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle"><i class="fas fa-procedures"></i><span>Patients</span>
                </a>
                <ul class="ml-menu">
                    <li @if (\Request::route()->getName() == 'patients') class="active" @endif>
                        <a href="{{ route('patients') }}">All Patients</a>
                    </li>
                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'appointment.index') class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle"><i
                        class="zmdi zmdi-calendar-check"></i><span>Appointments</span> </a>
                <ul class="ml-menu">
                    <li @if (\Request::route()->getName() == 'appointment.index') class="active" @endif>
                        <a href="{{ route('appointment.index') }}">Appointments</a>
                    </li>
                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'sessions.record') class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle"><i
                        class="fas fa-hand-holding-medical"></i><span>Sessions</span> </a>
                <ul class="ml-menu">
                    <li @if (\Request::route()->getName() == 'sessions.record') class="active" @endif>
                        <a href="{{ route('sessions.record') }}">Sessions</a>
                    </li>
                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'get_refill_requests') class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle"><i class="fas fa-capsules"></i><span>Pharmacy</span>
                </a>
                <ul class="ml-menu">
                    <li><a href="/pharmacy">Order Medicines</a></li>
                    <!-- <li><a href="#">Recommended Medications</a></li> -->
                    <li @if (\Request::route()->getName() == 'get_refill_requests') class="active" @endif>
                        <a href="{{ route('get_refill_requests') }}">Refill Requests</a>
                    </li>
                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'lab_orders' || \Request::route()->getName() ==
                'doctor_lab_approvals') class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle"><i class="fas fa-vial"></i><span>Lab</span>
                </a>
                <ul class="ml-menu">
                    <li><a href="/labtests">Order Labs</a></li>
                    <li @if (\Request::route()->getName() == 'lab_orders') class="active" @endif>
                        <a href="{{ route('lab_orders') }}">Patient Reports</a>
                    </li>
                    <li @if (\Request::route()->getName() == 'doctor_lab_approvals') class="active" @endif>
                        <a href="{{ route('doctor_lab_approvals') }}">Online Lab Approvals</a>
                    </li>
                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'doctors.all') class="active open" @endif>
                <a href="{{ route('doctors.all') }}"><i class="fas fa-user-md">
                    </i><span>Doctors</span>
                </a>
            </li>
            <li @if (\Request::route()->getName() == 'orders.index') class="active open" @endif>
                <a href="/orders"><i class="zmdi zmdi-calendar-check">
                    </i><span>Orders</span>
                </a>
            </li>
            <!-- <li><a href="#">Tests</a></li>Order test -->
            <li @if (\Request::route()->getName() == 'wallet_page') class="active open" @endif>
                <a href="/wallet_page"><i class="far fa-handshake"></i><span>Wallet</span></a>
            </li>

            <!-- <li><a href="#"><i class="fas fa-comment-dots"></i><span>Inbox</span></a></li> -->
            <li class=" @if (\Request::route()->getName() == 'acc_settings') active open @endif mb-5">
                <a href="{{ route('acc_settings') }}"><i class="fas fa-cog"></i><span>Account
                        Settings</span></a>
            </li>
            @endhasanyrole
            <!-- Patient -->
            @hasanyrole('patient')
            <li @if (\Request::route()->getName() == 'home') class="active open" @endif>
                <a href="{{ route('home') }}">
                    <i class="zmdi zmdi-home"></i><span>Dashboard</span></a>
            </li>
            <li @if (\Request::route()->getName() == 'user_profile' || \Request::route()->getName() == 'medical_profile') class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="zmdi zmdi-account-o"></i><span>Profile</span> </a>
                <ul class="ml-menu">
                    <li @if (\Request::route()->getName() == 'user_profile') class="active" @endif>
                        <a href="{{ url('/user/profile/'.auth()->user()->username) }}">My Profile</a>
                    </li>
                    <li @if (\Request::route()->getName() == 'medical_profile') class="active" @endif>
                        <a href="{{ route('medical_profile') }}">Medical Profile</a>
                    </li>


                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'evisit.specialization') class="active" @endif>
                <a href="{{ route('evisit.specialization') }}">
                    <i class="fa fa-stethoscope"></i>
                    <span>E-Visit</a></span>
            </li>

            <li @if (\Request::route()->getName() == 'select.specialization' || \Request::route()->getName() ==
                'appointment.index') class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle"><i
                        class="zmdi zmdi-calendar-check"></i><span>Appointments</span> </a>
                <ul class="ml-menu">
                    <li @if (\Request::route()->getName() == 'select.specialization') class="active" @endif>
                        <a href="{{ route('select.specialization') }}">Book Appointment</a>
                    </li>
                    <li @if (\Request::route()->getName() == 'appointment.index') class="active" @endif>
                        <a href="{{ route('appointment.index') }}">My Appointments</a>
                    </li>
                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'sessions.record' || \Request::route()->getName() == 'doctors.all')
                class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle"><i
                        class="fas fa-hand-holding-medical"></i><span>Sessions
                        <!-- <span
                                                class="badge bg-orange ml-2">1</span> -->
                    </span> </a>
                <ul class="ml-menu">
                    <!-- <li><a href="{{ route('sessions.all') }}">My Sessions <span class="badge bg-orange ml-2">1
                                                    New</span></a></li> -->
                    <li @if (\Request::route()->getName() == 'sessions.record') class="active" @endif>
                        <a href="{{ route('sessions.record') }}">Session Record</a>
                    </li>
                    <li @if (\Request::route()->getName() == 'doctors.all') class="active" @endif>
                        <a href="{{ route('doctors.all') }}">My Doctors</a>
                    </li>

                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'current_meds') class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle"><i class="fas fa-capsules"></i><span>Pharmacy</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="/pharmacy">Order Medicines</a>
                    </li>
                    <li @if (\Request::route()->getName() == 'current_meds') class="active" @endif>
                        <a href="{{ route('current_meds') }}">Current Medications</a>
                    </li>
                    <!-- <li><a href="#">Request Refill</a></li> -->
                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'lab_orders' || \Request::route()->getName() == 'lab_requisitions')
                class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle"><i class="fas fa-vial"></i><span>Lab</span> </a>
                <ul class="ml-menu">
                    <li>
                        <a href="/labtests">Order Labs</a>
                    </li>
                    <li @if (\Request::route()->getName() == 'lab_requisitions') class="active" @endif>
                        <a href="{{ route('lab_requisitions') }}">Lab Requisitions</a>
                    </li>
                    <li @if (\Request::route()->getName() == 'patient_lab_reports') class="active" @endif>
                        <a href="{{ route('patient_lab_reports') }}">Reports</a>
                    </li>
                </ul>
            </li>
            <li @if (\Request::route()->getName() == 'imaging_orders') class="active open" @endif><a
                    href="javascript:void(0);" class="menu-toggle"><i class="fas fa-x-ray"></i><span>Imaging</span>
                </a>
                <ul class="ml-menu">
                    <li><a href="{{ route('imaging') }}">View Services</a></li>
                    <li @if (\Request::route()->getName() == 'imaging_orders') class="active" @endif>
                        <a href="{{ route('imaging_orders') }}">Reports</a>
                    </li>
                </ul>
            </li>
            <li @if (\Request::url()=='/orders' ) class="active open" @endif>
                <a href="/orders"><i class="zmdi zmdi-calendar-check"></i><span>Orders</span></a>
            </li>
            <!-- <li><a href="/wallet_page"><i class="far fa-handshake"></i><span>Wallet</span></a></li> -->
            <!-- <li><a href="#"><i class="fas fa-comment-dots"></i><span>Inbox</span></a></li> -->
            <li class="@if (\Request::route()->getName() == 'acc_settings') active open @endif mb-5">
                <a href="{{ route('acc_settings') }}"><i class="fas fa-cog"></i><span>Account
                        Settings</span></a>
            </li>
            @endhasanyrole
            <!-- Temporary User(Social Media Login) -->
            @hasanyrole('temp_patient')
            <li @if (\Request::route()->getName() == 'user_profile' || \Request::route()->getName() ==
                'medical_profile') class="active open" @endif>
                <a href="javascript:void(0);" class="menu-toggle"><i
                        class="zmdi zmdi-account-o"></i><span>Profile</span> </a>
                <ul class="ml-menu">
                    <li @if (\Request::route()->getName() == 'user_profile') class="active" @endif>
                        <a href="{{ url('/user/profile/'.auth()->user()->username) }}">My Profile</a>
                    </li>
                    <li @if (\Request::route()->getName() == 'medical_profile') class="active" @endif>
                        <a href="{{ route('medical_profile') }}">Medical Profile</a>
                    </li>


                </ul>
            </li>
            @endhasanyrole
        </div>
        <!-- #Menu -->
    </aside>
    <!-- #END# Left Sidebar -->
    <!-- Right Sidebar -->
    <aside id="rightsidebar" class="right-sidebar">
        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#chat">Chat</a></li>
        </ul>
        <div role="tabpanel" class="tab-pane" id="chat">
            <div class="demo-settings">
                <div class="search">
                    <div class="input-group">
                        <div class="form-line">
                            <input type="text" class="form-control" placeholder="Search..." required autofocus>
                        </div>
                    </div>
                </div>
                <h6>Recent</h6>
                <ul>
                    <li class="online">
                        <div class="media">
                            <a role="button" tabindex="0"> <img class="media-object "
                                    src="{{ asset('asset_admin/images/xs/avatar1.jpg') }}" alt=""> </a>
                            <div class="media-body">
                                <span class="name">Claire Sassu</span> <span class="message">Can you
                                    share
                                    the...</span>
                                <span class="badge badge-outline status"></span>
                            </div>
                        </div>
                    </li>
                    <li class="online">
                        <div class="media"> <a role="button" tabindex="0"> <img class="media-object "
                                    src="{{ asset('asset_admin/images/xs/avatar2.jpg') }}" alt=""> </a>
                            <div class="media-body">
                                <span class="name">Maggie jackson</span> <span class="message">Can
                                    you share
                                    the...</span> <span class="badge badge-outline status"></span>
                            </div>
                        </div>
                    </li>
                    <li class="online">
                        <div class="media"> <a role="button" tabindex="0"> <img class="media-object "
                                    src="{{ asset('asset_admin/images/xs/avatar3.jpg') }}" alt=""> </a>
                            <div class="media-body">
                                <span class="name">Joel King</span> <span class="message">Ready for
                                    the meeti...</span>
                                <span class="badge badge-outline status"></span>
                            </div>
                        </div>
                    </li>
                </ul>
                <h6>Contacts</h6>
                <ul>
                    <li class="offline">
                        <div class="media"> <a role="button" tabindex="0"> <img class="media-object"
                                    src="{{ asset('asset_admin/images/xs/avatar4.jpg') }}" alt=""> </a>
                            <div class="media-body">
                                <span class="name">Joel King</span> <span class="badge badge-outline status"></span>
                            </div>
                        </div>
                    </li>
                    <li class="online">
                        <div class="media"> <a role="button" tabindex="0"> <img class="media-object "
                                    src="{{ asset('asset_admin/images/xs/avatar1.jpg') }}" alt=""> </a>
                            <div class="media-body">
                                <span class="name">Joel King</span> <span class="badge badge-outline status"></span>
                            </div>
                        </div>
                    </li>
                    <li class="offline">
                        <div class="media"> <a class="pull-left " role="button" tabindex="0"> <img class="media-object "
                                    src="{{ asset('asset_admin/images/xs/avatar2.jpg') }}" alt=""> </a>
                            <div class="media-body">
                                <span class="name">Joel King</span> <span class="badge badge-outline status"></span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
    <!-- #END# Right Sidebar -->
</section>
