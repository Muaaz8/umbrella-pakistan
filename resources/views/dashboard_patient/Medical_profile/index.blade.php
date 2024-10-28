@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Medical Profile</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')

{{-- {{ dd($profile,$update,$diseases) }} --}}
<div class="dashboard-content">
    <div class="container-fluid">
      <div class="row m-auto">
        <div class="col-md-12">
          <div class="row m-auto">
            <div class="d-flex align-items-end p-0 justify-content-between flex-wrap">
              <div class="">
                <h3>Medical History</h3>
                <p>All your medical details are here</p>
              </div>
              <div class="p-0 mt-2">
                <div class="">
                  <a href="{{ route('patient_update_medical_profile') }}"><button class="btn process-pay">Edit Medical History</button></a>
                </div>
                <small >Last Updated On: {{ date('m-d-Y h:i A', strtotime($profile->updated_at)) }}</small>
              </div>
            </div>
            <div class="medical-main p-0">
              <h5 class="mb-1">All Types of Allergies</h5>
              <div class="allergies-sec mb-3 mt-2 p-2">
                  <div class="col-lg-12">{{$profile->allergies ?: 'No Allergies'}}</div>
              </div>
              <h5 class="mb-1">Any Types of Surgeries</h5>
              <div class="allergies-sec mb-3 mt-2 p-2">
                  <div class="col-lg-12">{{$profile->surgeries ?: 'No Surgeries'}}</div>
              </div>
              <div class="row mt-4">
                  <div class="col-lg-5">
                  <h5>Medical Conditions</h5>
                  {{-- @php
                    $diseases=->hypertension'=>'Hypertension','diabetes'=>'Diabetes',
                    'cancer'=>'Cancer','heart'=>'Heart Disease',
                    'chest'=>'Chest Pain/chest tightness',
                    'shortness'=>'Shortness of breath',
                    'swollen'=>'Swollen Ankles',
                    'palpitation'=>'Palpitation/Irregular Heartbeat',
                    'stroke'=>'Stroke;
                  @endphp --}}
                  <table class="table table-box-style">
                      <thead>
                        <tr>
                          <th scope="col">Disease</th>
                          <!-- <th scope="col">Involved</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($profile->previous_symp as $disease)
                        <tr>
                            <td data-label="Disease">{{$disease}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <div class="col-lg-7">
                      <h5>Immunization History</h5>
                      <table class="table table-box-style">
                          <thead>
                            <tr>
                              <th scope="col">Vaccination</th>
                              <th scope="col">Yes/No</th>
                              <th scope="col">When</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($profile->immunization_history != "" || $profile->immunization_history != null || $profile->immunization_history != [])

                            @foreach($profile->immunization_history as $history)
                            {{-- {{ dd($history) }} --}}
                            <tr>
                                <td data-label="Vaccination"> {{ucwords($history->name)}} </td>
                                <td data-label="Yes/No"> {{ucwords($history->flag)}} </td>
                                <td data-label="When">
                                    {{($history->when!= "") ? ucwords($history->when) : "-" }}
                                </td>
                            </tr>
                            @endforeach

                            @else
                            <tr>
                              <td colspan="3">
                                      <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6>No Immunization History</h6>
                                      </div>
                                    </td>
                            </tr>
                            @endif
                          </tbody>
                        </table>
                      </div>
              </div>
              <div class="row mt-2">
                  <div class="col-lg-12">
                      <h5>Family Details</h5>
                      <p>Has any of your family member (including parents, grandparents, and siblings) ever had the following</p>
                      <table class="table table-box-style mt-2">
                          <thead>
                            <tr>
                              <th scope="col">Disease</th>
                              <th scope="col">Family Member</th>
                              <th scope="col">Aprox. Age</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(isset($profile->family_history) && $profile->family_history!=null )
                                @forelse($profile->family_history as $fam)
                                <tr>
                                    <td data-label="Disease">
                                        @if($fam->disease=='heart')
                                        Heart Disease
                                        @elseif($fam->disease=='mental')
                                        Mental Disease
                                        @elseif($fam->disease=='drugs')
                                        Drugs/Alcohol Addiction
                                        @elseif($fam->disease=='bleeding')
                                        Bleeding Disease
                                        @else
                                        {{ucwords($fam->disease)}}
                                        @endif
                                    </td>
                                    <td data-label="Family Member">@if($fam->family=='grand')
                                        Grandparent
                                        @else
                                        {{ucwords($fam->family)}}
                                        @endif
                                    </td>
                                    <td data-label="Aprox. Age">
                                        {{ucwords($fam->age)}}

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                  <td colspan="3">
                                          <div class="m-auto text-center for-empty-div">
                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                            <h6>No Family History</h6>
                                          </div>
                                        </td>
                                </tr>
                                @endforelse
                            @else
                            <tr>
                              <td colspan="3">
                                      <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6>No Family History</h6>
                                      </div>
                                    </td>
                            </tr>
                            @endif
                          </tbody>
                        </table>
                      </div>


                  <div class="col-lg-12">
                      <h5>Medication History</h5>
                      {{-- <p>Has any of your family member (including parents, grandparents, and siblings) ever had the following</p> --}}
                      <table class="table table-box-style mt-2">
                          <thead>
                            <tr>
                              <th scope="col">Medication</th>
                              <th scope="col">Dosage</th>
                              {{-- <th scope="col">Aprox. Age</th> --}}
                            </tr>
                          </thead>
                          <tbody>
                            @if(isset($profile->medication) && $profile->medication!=null )
                                @forelse($profile->medication as $fam)
                                <tr>
                                    <td data-label="Disease">
                                       {{ $fam->med_name }}
                                    </td>
                                    <td data-label="Family Member">
                                        {{ $fam->med_dosage }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                  <td colspan="2">
                                          <div class="m-auto text-center for-empty-div">
                                            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                            <h6>No Medication History</h6>
                                          </div>
                                        </td>
                                </tr>
                                @endforelse
                            @else
                            <tr>
                              <td colspan="2">
                                      <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6>No Medication History</h6>
                                      </div>
                                    </td>
                            </tr>
                            @endif
                          </tbody>
                        </table>
                      </div>

              </div>
              <div class="col-lg-12 p-2 allergies-sec">
                @php
                $count = 1;
                @endphp
                <h5 class="fw-bold">Medical Record</h5>
                @forelse($med_files as $file)
                  <a href="{{url(\App\Helper::get_files_url($file->record_file))}}" target="_blank">View_file_{{$count}}</a><br>
                @php
                $count = $count+1;
                @endphp
                @empty
                <p>No Medical Records</p>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

@endsection
