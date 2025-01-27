@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - My Profile</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
    $(document).ready(function(){

     $(document).on('click', '.pagination a', function(event){
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      fetch_data(page);
     });

     function fetch_data(page)
     {
    //   alert(page);
      $.ajax({
       url:"/pagination/fetch_data?page="+page,
       success:function(data)
       {
        $('#table_data').html(data);
       }
      });
     }

    });
</script>
@endsection

@section('content')
    <style>
        div.ex1 {
            background-color: lightblue;
            height: 40px;
            width: 200px;
            overflow-y: scroll;
        }
    </style>

    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row profile-row-wrapper m-auto">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-12  mb-3">
                            <div class="profile-box bg-white">
                                <a href="{{ route('editDoctorDetail') }}">
                                    <i class="fa-solid fa-pen-to-square float-end fs-3"></i>
                                </a>
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ $doctor->user_image }}" class="img-fluid rounded profile_picture"
                                        alt="">
                                    <p class="fw-bold h4 mt-3">Dr. {{ $doctor->name." ".$doctor->last_name }}</p>
                                </div>

                                <div class="">
                                    <div class=" px-3 py-2">
                                        <div class="d-flex align-items-center justify-content-between border-bottom">

                                            <p class="py-2">Specialization</p>

                                            <p class="py-2 text-muted"> {{ $doctor->spec->name }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Email</p>
                                            <p class="py-2 text-muted">{{ $doctor->email }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Phone</p>
                                            <p class="py-2 text-muted">{{ $doctor->phone_number }}</p>
                                        </div>
                                        @if (isset($doctor->upin))
                                            <div class="d-flex align-items-center justify-content-between border-bottom">
                                                <p class="py-2">Upin</p>
                                                <p class="py-2 text-muted">{{ $doctor->upin }}</p>
                                            </div>
                                        @endif
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">PMDC Number</p>
                                            <p class="py-2 text-muted">{{ $doctor->nip_number }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Gender</p>
                                            <p class="py-2 text-muted">{{ Str::ucfirst($doctor->gender) }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between border-bottom">
                                            <p class="py-2">Date of Birth</p>
                                            @php
                                            $date = str_replace('-', '/', $doctor->date_of_birth);
                                            $newd_o_b = date('m/d/Y', strtotime($date));
                                            //    dd($newd_o_b);
                                            @endphp
                                            <p class="py-2 text-muted">{{ $newd_o_b }}</p>
                                        </div>

                                        <div class="d-flex justify-content-between border-bottom">
                                            <p class="py-2">Address</p>
                                            <p class="py-2 text-muted text-break text-end"> {{ $doctor->office_address }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom">
                                            <p class="py-2">Timezone</p>
                                            <p class="py-2 text-muted text-break text-end"> {{ $doctor->timeZone }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 ">
                    <div class="row">
                        <div class="col-12 mb-3 bg-white profile-box">
                            <div class="py-3">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="col-12 col-sm-4 nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                            aria-selected="true">Bio</button>
                                    </li>
                                    <li class="col-12 col-sm-4 nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                                            aria-selected="false">Certificates</button>
                                    </li>
                                    <li class="col-12 col-sm-4 nav-item" role="presentation">
                                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#contact" type="button" role="tab" aria-controls="contact"
                                            aria-selected="false">Activities</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                        aria-labelledby="home-tab">
                                        <div class="page-content page-container bio-content">
                                            <div class="padding">
                                                <div class="row">
                                                    <h1>{{ $doctor->bio }}</h1>
                                                    <div class="seven">
                                                        <h1>My Patient</h1>

                                                    </div>
                                                    @forelse($doctor->all_patients as $patient)
                                                        <div class="col-sm-6 col-lg-4 d-flex mb-3">
                                                            <div class="card flex">
                                                                <div class="card-body m-auto">
                                                                    <div
                                                                        class="d-flex align-items-center text-hover-success">
                                                                        {{-- <div class="">
                                                                            <a
                                                                                href="{{ url('/patient/detail/' . $patient->id) }}">
                                                                                <img style="height: 140px; width: 140px;"
                                                                                    src="{{ $patient->user_image }}"
                                                                                    alt="user"
                                                                                    class="img-thumbnail img-fluid">
                                                                                <h4>{{ ucwords($patient->name . ' ' . $patient->last_name) }}
                                                                                </h4>
                                                                            </a>
                                                                        </div> --}}
                                                                        <div class="text-center">
                                                                            <a
                                                                                href="{{ url('/patient/detail/' . $patient->id) }}" class="p-profile-pix">
                                                                                <img style="height: 80px; width: 80px;" src="{{ $patient->user_image }}" alt="user" class="img-thumbnail img-fluid">
                                                                                <h6>{{ ucwords($patient->name . ' ' . $patient->last_name) }} </h6>
                                                                            </a>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                    <div class="text-center for-empty-div">
                                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                        <h6> No Patients</h6>
                                                    </div>
                                                    @endforelse
                                                    {{-- <div class="col-md-4 d-flex">
                                <div class="card flex">
                                   <div class="card-body">
                                      <div class="d-flex align-items-center text-hover-success">
                                         <div class="avatar w-56 no-shadow gd-success">
<img src="{{ asset('https://www.iconpacks.net/icons/2/free-user-icon-3296-thumb.png') }}" alt="">
                                         </div>
                                         <div class="px-1 flex">
                                            <div>{{$doctor->name }}</div>
                                         </div>
                                         <a href="#" class="text-muted" data-abc="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                               <line x1="5" y1="12" x2="19" y2="12"></line>
                                               <polyline points="12 5 19 12 12 19"></polyline>
                                            </svg>
                                         </a>
                                      </div>
                                   </div>
                                </div>
                             </div> --}}
                                                    {{-- <div class="col-md-4 d-flex">
                                <div class="card flex">
                                   <div class="card-body">
                                      <div class="d-flex align-items-center text-hover-success">
                                         <div class="avatar w-56 no-shadow gd-success">
<img src="{{ asset('https://www.iconpacks.net/icons/2/free-user-icon-3296-thumb.png') }}" alt="">
                                         </div>
                                         <div class="px-1 flex">
                                            <div>{{$doctor->name }}</div>
                                         </div>
                                         <a href="#" class="text-muted" data-abc="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                               <line x1="5" y1="12" x2="19" y2="12"></line>
                                               <polyline points="12 5 19 12 12 19"></polyline>
                                            </svg>
                                         </a>
                                      </div>
                                   </div>
                                </div>
                             </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade  " id="profile" role="tabpanel"
                                        aria-labelledby="profile-tab">
                                        <div>
                                            <div class="row mt-4 ">

                                                @forelse($doctor->certificates as $cert)
                                                    <div class="col-md-4 col-6 mb-3">

                                                        <div class="file-view-div">
                                                            <img src="{{ asset('assets/images/umbrella-file-view-icon.png') }}"
                                                                alt="View icon" height=50 width=50>
                                                            <a href="{{ $cert->certificate_file }}" target="blank"
                                                                class="ml-3">View
                                                                File</a>
                                                            <!-- <div class="row">
                                            <div class="col-md-12">
                                                <img src="{{ $cert->certificate_file }}" alt="View Document" height=100 width=100>
                                            </div>
                                        </div> -->
                                                            {{-- <img src="{{ asset('assets/images/umbrella-file-view-icon.png') }}" alt="">
                                <h6>View Item</h6> --}}
                                                        </div>

                                                    </div>
                                                @empty
                                                <div class="text-center for-empty-div">
                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                    <h6> No Certifications</h6>
                                                </div>
                                                @endforelse


                                                {{-- <div class="col-md-4 col-sm-6 mb-3">
                                  <div class="file-view-div">
                                    <img src="{{ asset('assets/images/umbrella-file-view-icon.png') }}" alt="">
                                    <h6>View Item</h6>
                                  </div>
                                </div> --}}

                                                {{-- <div class="col-md-4 col-sm-6 mb-3">
                                  <div class="file-view-div">
                                    <img src="{{ asset('assets/images/umbrella-file-view-icon.png') }}" alt="">
                                    <h6>View Item</h6>
                                  </div>
                                </div> --}}

                                                {{-- <div class="col-md-4 col-sm-6 mb-3">
                                  <div class="file-view-div">
                                    <img src="{{ asset('assets/images/umbrella-file-view-icon.png') }}" alt="">
                                    <h6>View Item</h6>
                                  </div>
                                </div> --}}

                                                {{-- <div class="col-md-4 col-sm-6 mb-3">
                                  <div class="file-view-div">
                                    <img src="{{ asset('assets/images/umbrella-file-view-icon.png') }}" alt="">
                                    <h6>View Item</h6>
                                  </div>
                                </div> --}}

                                                {{-- <div class="col-md-4 col-sm-6 mb-3">
                                  <div class="file-view-div">
                                    <img src="{{ asset('assets/images/umbrella-file-view-icon.png') }}" alt="">
                                    <h6>View Item</h6>
                                  </div>
                                </div> --}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="contact" role="tabpanel"
                                        aria-labelledby="contact-tab">
                                        {{-- <div class="widget">
                                            <div class="widget-content">
                                                <div class="column-wrap">
                                                    <div class="coloumn">
                                                        <div class="activity">

                                                            <h3>LAST ACTIVITIES</h3>

                                                            <div class="activity-items">


                                                                <div class="activity-item-wrap activity-date">

                                                                    <h4 class="activity-date"></h4>

                                                                </div>

                                                                <!-- start activity -->
                                                                @forelse ($doctor->activities as $item)
                                                                    <div class="activity-item-wrap activity-call">
                                                                        <div class="activity-item">
                                                                            <div class="activity-item-meta">

                                                                                <div class="activity-user"><i
                                                                                        class="aroicon-entity-contacts"></i>
                                                                                </div>

                                                                                <p class="activity-summary">
                                                                                    < {{ $item->activity }} @ <a
                                                                                        href="javascript:void(0);"
                                                                                        data-abc="true">
                                                                                        {{ $item->created_at }}
                                                                                </p>
                                                                                <p class="activity-timestamp">
                                                                                    {{ $doctor->last_activity }}</p>
                                                                            </div>

                                                                            <div class="activity-item-details">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- end activity -->


                                                                    <div class="activity-item-wrap activity-date">
                                                                        {{-- <h4 class="activity-date">{{ $item->heading }}
                                                                        </h4> --}}
                                                                    {{-- </div>


                                                                @endforeach --}}
                                                                {{-- <!-- start activity -->

                                        <div class="activity-item-wrap activity-call">
                                          <div class="activity-item-badge"><i class="aroicon-entity-listings"></i></div>
                                          <div class="activity-item">
                                            <div class="activity-item-meta"> --}}
                                              {{-- <div class="activity-user"><i class="aroicon-entity-contacts"></i></div>
                                              <p class="activity-summary" >
                                                <strong>SESSION RECOMMENDATION STATUS</strong> Create session recommendations for rizwan ali  </p>
                                            <p class="activity-timestamp">{{ $doctor->last_activity }}</p>
                                            </div>

                                      </div>
                                        </div> --}}

                                        <!-- end activity -->


                                                                {{-- <div class="activity-item-wrap activity-date">
                                          <p><a href="">Load more activity...</a></p>
                                        </div> --}}

                                                            {{-- </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="container">
                                                <h4 class="py-2">Activities</h4>
                                              <div id="table_data">
                                                  @include('dashboard_patient.Profile.pagination_data')
                                              </div>
                                          </div>
                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
