@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Patient Details</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')

    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div>
                    <h4>Patient Details</h4>
                    <hr>
                </div>
                <div>
                <div class="name-sec">
                    <div class="d-flex justify-content-between name-sec-inner">
                    <h5 class="border rounded-3">Name: <span> {{ $pat_name }} </span></h5>
                    <h5 class="border rounded-3">Date Of Birth : <span>{{ date('m-d-Y',strtotime($pat_info->date_of_birth)) }}</span></h5>
                    </div>
                    {{-- <div class="d-flex justify-content-between name-sec-inner">
                    <h5 class="border rounded-3">State: <span> {{ $pat_info->state }} </span></h5>
                    <h5 class="border rounded-3">City : <span>{{ $pat_info->city }}</span></h5>
                    </div> --}}
                    <div class="d-flex justify-content-between name-sec-inner">
                    <h5 class="border rounded-3">User Type : <span>{{ $pat_info->user_type }}</span></h5>
                    <h5 class="border rounded-3">Gender: <span> {{ $pat_info->gender }} </span></h5>
                    </div>
                    <div class="d-flex justify-content-between name-sec-inner">
                    {{-- <h5 class="border rounded-3">Zipcode: <span> {{ $pat_info->zip_code }} </span></h5> --}}
                    <h5 class="border rounded-3">Timezone : <span>{{ $pat_info->timeZone }}</span></h5>
                    <h5 class="border rounded-3">Phone No: <span> {{ $pat_info->phone_number }} </span></h5>
                    </div>
                    <div class="d-flex justify-content-between name-sec-inner">
                    <h5 class="border rounded-3">Email : <span>{{ $pat_info->email }}</span></h5>
                    <h5 class="border rounded-3">Address: <span> {{ $pat_info->office_address }} </span></h5>
                    </div>
                    <div class="d-flex justify-content-between name-sec-inner">
                    <h5 class="border rounded-3">Register Date : <span>{{ date('m-d-Y',strtotime($pat_info->created_at)) }}</span></h5>
                    </div>
                    <!-- <h5 class="name-sec-bio">
                        Bio :
                        <span style="word-break: break-word;">{{ $pat_info->bio }}</span>
                    </h5> -->
                </div>
                </div>
            </div>
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row my-4">
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-small-card-wrap">
                                <div class="d-flex dashboard-small-card-inner">
                                    {{-- <i class="fa-solid fa-user-doctor"></i> --}}
                                    <div>
                                        <h6>Total E-Visits</h6>
                                        <p>{{ $box['sessions'] }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-small-card-wrap">
                                <div class="d-flex dashboard-small-card-inner">
                                    {{-- <i class="fa-solid fa-cart-shopping"></i> --}}
                                    <div>
                                        <h6>Total Pharmacy</h6>
                                        <p>Rs. {{ $box['med_price'] }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-small-card-wrap">
                                <div class="d-flex dashboard-small-card-inner">
                                    {{-- <i class="fa-solid fa-vials"></i> --}}
                                    <div>
                                        <h6>Total Lab Test</h6>
                                        <p>Rs. {{ $box['lab_price'] }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="dashboard-small-card-wrap">
                                <div class="d-flex dashboard-small-card-inner">
                                    {{-- <i class="fa-solid fa-prescription-bottle-medical"></i> --}}
                                    <div>
                                        <h6>Total Imaging</h6>
                                        <p>Rs. {{ $box['imaging_price'] }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row m-auto patient-detail-tabs">
                <div class="col-md-3">
                    <!-- Tabs nav -->
                    <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <a class="nav-link mb-3 py-3 px-2 shadow-new-btn active" id="v-pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                            aria-selected="true">
                            <i class="fa-solid fa-timeline me-2"></i>
                            <span class="font-weight-bold small text-uppercase">Session History</span></a>

                        <a class="nav-link mb-3 py-3 px-2 shadow-new-btn" id="v-pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile"
                            aria-selected="false">
                            <i class="fa-solid fa-book-medical me-2"></i>
                            <span class="font-weight-bold small text-uppercase">Medical Profile</span></a>

                        <!-- <a class="nav-link mb-3 py-3 px-2 shadow-new-btn" id="v-pills-messages-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-messages" type="button" role="tab"
                            aria-controls="v-pills-messages" aria-selected="false">
                            <i class="fa-solid fa-hospital-user me-2"></i>
                            <span class="font-weight-bold small text-uppercase">Patient Info</span></a> -->

                        <a class="nav-link mb-3 py-3 px-2 shadow-new-btn" id="v-pills-settings-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-settings" type="button" role="tab"
                            aria-controls="v-pills-settings" aria-selected="false">
                            <i class="fa-solid fa-notes-medical me-2"></i>
                            <span class="font-weight-bold small text-uppercase">Medication History</span></a>

                        <a class="nav-link mb-3 py-3 px-2 shadow-new-btn" id="v-pills-lab-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-lab" type="button" role="tab" aria-controls="v-pills-lab"
                            aria-selected="false">
                            <i class="fa-solid fa-flask me-2"></i>
                            <span class="font-weight-bold small text-uppercase">Lab History</span></a>

                        <a class="nav-link mb-3 py-3 px-2 shadow-new-btn" id="v-pills-imaging-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-imaging" type="button" role="tab" aria-controls="v-pills-imaging"
                            aria-selected="false">
                            <i class="fa-solid fa-x-ray"></i>
                            <span class="font-weight-bold small text-uppercase">Imaging History</span></a>
                    </div>
                </div>

                <div class="col-md-9">
                    <!-- Tabs content -->
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade shadow-new rounded bg-white show active p-4" id="v-pills-home" role="tabpanel"
                            aria-labelledby="v-pills-home-tab">
                            <h4 class="font-italic mb-4">Session History</h4>
                            @foreach ($sessionss as $ses)
                                <div class="accordion accordion-flush session-history" id="accordionFlushExample">
                                    <div class="accordion-item mb-2">
                                        <h2 class="accordion-header" id="flush-heading{{ $ses->id }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapse{{ $ses->id }}" aria-expanded="false"
                                                aria-controls="flush-collapse{{ $ses->id }}">
                                                {{ $pat_name }} session with Dr. {{ $ses->doc_name }}
                                                {{ $ses->date }}
                                            </button>
                                        </h2>
                                        <div id="flush-collapse{{ $ses->id }}" class="accordion-collapse collapse"
                                            aria-labelledby="flush-heading{{ $ses->id }}"
                                            data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body p-0">
                                                <div class="row m-auto">
                                                    <div class="d-flex border p-1 px-3">
                                                        <h6>Session ID :</h6>
                                                        <p class="ms-3">UEV-{{ $ses->session_id }}</p>
                                                    </div>
                                                    <div class="d-flex border p-1 px-3">
                                                        <h6>Time :</h6>
                                                        <p class="ms-3">{{ $ses->start_time }}</p>
                                                    </div>
                                                    <div class="wallet-table table-responsive">
                                                        @if(($ses->pres) != null)
                                                        <table class="table">
                                                            <thead>
                                                              <tr>
                                                                <th scope="col">Recommendation</th>
                                                                <th scope="col">Dosage</th>
                                                                <th scope="col">Comment</th>
                                                                <th scope="col">Type</th>
                                                                <th scope="col">Status</th>
                                                              </tr>
                                                            </thead>
                                                            <tbody>
                                                                    @foreach ($ses->pres as $med)
                                                                    @if($med->prod_detail!=null)
                                                                    <tr>
                                                                        @if($med->type=='lab-test')
                                                                        <td data-label="Recommendation">{{ucfirst($med->prod_detail->DESCRIPTION)}}</td>
                                                                        @else
                                                                        <td data-label="Recommendation">{{ucfirst($med->prod_detail->name)}}</td>
                                                                        @endif
                                                                        <td data-label="Dosage">{{ $med->usage }}</td>
                                                                        <td data-label="Comment">{{ $med->comment }}</td>
                                                                        <td data-label="Type">{{ $med->prod_detail->mode }}</td>
                                                                        <td data-label="Status">{{ $med->status }}</td>
                                                                    </tr>
                                                                    @endif
                                                                    @endforeach
                                                            </tbody>
                                                        </table>
                                                        @else
                                                        <div class="text-center for-empty-div">
                                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                            <h6>No Medications Recommended during the Session</h6>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            {{ $sessionss->links('pagination::bootstrap-4') }}
                        </div>

                        <div class="tab-pane fade shadow-new rounded bg-white p-4" id="v-pills-profile" role="tabpanel"
                            aria-labelledby="v-pills-profile-tab">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <h4 class="font-italic mb-4">Medical profile</h4>
                                        <h6>Last updated: {{ $last_updated }}</h6>

                                        {{-- {{ dd(($medical_profile->previous_symp)) }} --}}
                                        @if ($medical_profile != null && $medical_profile->immunization_history !='[]')
                                            @php
                                                $immunization = json_decode($medical_profile->immunization_history)
                                            @endphp
                                            <form>
                                            @foreach ($immunization as $immu)
                                                @if ($immu->flag == "yes")
                                                    <input type="checkbox" id="weekday-1" name="weekday-1" value="Friday" checked disabled>
                                                    <label for="weekday-1">{{ $immu->name }}</label>
                                                @else
                                                    <input type="checkbox" id="weekday-1" name="weekday-1" value="Friday" disabled>
                                                    <label for="weekday-1">{{ $immu->name }}</label>
                                                @endif
                                            @endforeach
                                            </form>
                                        @else
                                            <div class="text-center for-empty-div">
                                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                <h6>No Immunization History</h6>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-7">
                                        <h4 class="font-italic mb-4">Family History</h4>
                                        <div class="wallet-table">
                                            @if ($medical_profile != null && $medical_profile->family_history !='[]')
                                            <table class="table">
                                              <thead>
                                                <tr>
                                                  <th scope="col">Disease</th>
                                                  <th scope="col">Family Member</th>
                                                  <th scope="col">Aprox. Age</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                  @foreach (json_decode($medical_profile->family_history) as $med_profile)
                                                    <tr>
                                                      <td data-label="Disease" scope="row">{{ $med_profile->disease }}</td>
                                                      <td data-label="Family Member"> {{ $med_profile->family }}</td>
                                                      <td data-label="Aprox. Age">{{ $med_profile->age }}</td>
                                                    </tr>
                                                  @endforeach
                                              </tbody>
                                            </table>
                                            @else
                                            <!-- <p class="text-center">No Family History</p> -->
                                            <div class="text-center for-empty-div">
                                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                <h6>No Family History</h6>
                                            </div>
                                            @endif

                                            {{-- <table class="rwd-table">
                                                <tbody>
                                                    <tr>
                                                        <th>Disease</th>
                                                        <th>Family Member</th>
                                                        <th>Aprox. Age</th>
                                                    </tr>
                                                    @foreach (json_decode($medical_profile->family_history) as $med_profile)
                                                        <tr>
                                                            <td data-label="Disease" data-th="Supplier Code">
                                                                {{ $med_profile->disease }}
                                                            </td>
                                                            <td data-label="Family Member" data-th="Supplier Name">
                                                                {{ $med_profile->family }}
                                                            </td>
                                                            <td data-label="Aprox. Age" data-th="Invoice Number">
                                                                {{ $med_profile->age }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="tab-pane fade shadow rounded bg-white p-4" id="v-pills-messages" role="tabpanel"
                            aria-labelledby="v-pills-messages-tab">
                            <h4 class="font-italic mb-4">Patient Information</h4>
                            <div class="name-sec">
                                <h5>Name: <span> {{ $pat_name }} </span></h5>
                                <h5>
                                    Bio :
                                    <span style="word-break: break-word;">{{ $pat_info->bio }}</span>
                                </h5>
                            </div>
                        </div> -->

                        <div class="tab-pane fade shadow-new rounded bg-white p-4" id="v-pills-settings" role="tabpanel"
                            aria-labelledby="v-pills-settings-tab">
                            <h4 class="font-italic mb-4">Medication History</h4>
                            <div class="">
                                @if ($history['patient_meds'] != null)
                                <table class="table">
                                    <thead>
                                      <tr>
                                        <th scope="col">MEDICINE NAME</th>
                                        <th scope="col">USAGE</th>
                                        <th scope="col">SESSION DATE</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($history['patient_meds'] as $meds)
                                      <tr>
                                        <td data-label="MEDICINE NAME" scope="row">{{ $meds->prod->name }}</td>
                                        <td data-label="USAGE">{{ $meds->usage }}</td>
                                        <td data-label="SESSION DATE">{{ $meds->ndate }}</td>
                                      </tr>
                                    @endforeach
                                    </tbody>
                                  </table>
                                @else
                                <div class="text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No Medication History</h6>
                               </div>
                                @endif
                                {{-- <table class="rwd-table">
                                    <tbody>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th>USAGE</th>
                                            <th>SESSION DATE</th>
                                        </tr>
                                        @foreach ($history['patient_meds'] as $meds)
                                            <tr>
                                                <td data-label="Medicine Name" data-th="Supplier Code">
                                                    {{ $meds->prod->name }}
                                                </td>
                                                <td data-label="USAGE" data-th="Supplier Name">
                                                    {{ $meds->usage }}
                                                </td>
                                                <td data-label="SESSION DATE" data-th="Invoice Number">{{ $meds->updated_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table> --}}
                            </div>
                        </div>

                        <div class="tab-pane fade shadow-new rounded bg-white p-4" id="v-pills-lab" role="tabpanel"
                            aria-labelledby="v-pills-lab-tab">
                            <h4 class="font-italic mb-4">Lab History</h4>

                            <div class="">
                                {{-- {{ dd(count($history['patient_labs'])) }} --}}
                                @if (count($history['patient_labs']) != 0)
                                <table class="table">
                                    <thead>
                                      <tr>
                                        <th scope="col">Test Name</th>
                                        <th scope="col">Result Date</th>
                                        <th scope="col">Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($history['patient_labs'] as $lab)
                                        {{-- {{ dd($lab) }}
                                        <tr>
                                            <td data-label="Test Name" data-th="Supplier Code">
                                                MELATONIN TABLET (OTC)
                                            </td>
                                            <td data-label="Result Date" data-th="Supplier Name">
                                                DOSAGE: EVERY 12HRS FOR 120 DAYS
                                            </td>
                                            <td data-label="Action" data-th="Invoice Number">AUG, 06TH 2022</td>
                                        </tr> --}}
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <div class="text-center for-empty-div">
                                     <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                     <h6> No Lab History</h6>
                                </div>
                                <!-- <p class="text-center"> No Lab History</p> -->
                                @endif
                            </div>
                        </div>

                        <div class="tab-pane fade shadow-new rounded bg-white p-4" id="v-pills-imaging" role="tabpanel"
                            aria-labelledby="v-pills-imaging-tab">
                            <h4 class="font-italic mb-4">Imaging History</h4>

                            <div class="">
                                @if (count($history['patient_imaging']) != 0)
                                <table class="table">
                                    <thead>
                                      <tr>
                                        <th scope="col">Service Name</th>
                                        <th scope="col">Result Date</th>
                                        <th scope="col">Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($history['patient_imaging'] as $imaging)
                                        <tr>
                                          <td data-label="Service Name" scope="row">MELATONIN TABLET (OTC)</td>
                                          <td data-label="Result Date">DOSAGE: EVERY 12HRS FOR 120 DAYS</td>
                                          <td data-label="Action">AUG, 06TH 2022</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @else
                                <div class="text-center for-empty-div">
                                     <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                     <h6> No Imaging History</h6>
                                </div>
                                <!-- <p class="text-center"> No Imaging History</p> -->
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#example").DataTable();
        });
    </script>
@endsection
