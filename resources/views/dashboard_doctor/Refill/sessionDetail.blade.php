@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Session Detail</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
{{-- {{ dd($session, $user_type) }} --}}
<div class="dashboard-content">
    <div class="container">
      <div class="row m-auto">
        <div class="col-md-12">
          <div class="row m-auto">
            <div class="d-flex align-items-end">
              <div>
                <h3>SESSION WITH {{ ucwords($session->doc_name) }}</h3>
                <p>All Related Details Are Listed Here</p>
              </div>
            </div>

              <div class="session-main">
                <div class="accordion-body p-0">
                    <div class="wallet-table table-responsive">
                      <div class="d-flex border p-1 px-3">
                        <h6>Diagnosis :</h6>
                        <p class="ms-3">{{ $session->diagnosis }}</p>
                      </div>
                      <div class="d-flex border p-1 px-3">
                        <h6>Provider Note :</h6>
                        <p class="ms-3">{{ $session->provider_notes }}</p>
                      </div>
                        @if ($session->refered != null)
                            <div class="d-flex border p-1 px-3">
                            <h6>Referred :</h6>
                            <p class="ms-3">{{ $session->refered }}</p>
                            </div>
                        @endif
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th scope="col">Session ID</th>
                            <th scope="col">Cost</th>
                            @if ($session->refered != null)
                                <th scope="col">Referred Doctor</th>
                            @endif
                            <th scope="col">Date</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                            {{-- <th scope="col">Recording</th> --}}
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td data-label="Session ID" scope="row">UEV-{{ $session->session_id }}</td>
                            <td data-label="Earning">Rs. {{ number_format($session->price,2) }}</td>
                            @if ($session->refered != null)
                                <td data-label="Referred Doctor">{{ $session->refered }}</td>
                            @endif
                            <td data-label="Date">{{ $session->date }}</td>
                            <td data-label="Start Time">{{ $session->start_time }}</td>
                            <td data-label="End Time">{{ $session->end_time }}</td>
                            {{-- <td data-label="Recording"><a onclick="window.open('{{ route('sessions.record.view',['id'=>$session->id]) }}','popup','width=600,height=500'); return false;"
                                target="popup" rola="button"><button type="button" class="btn btn-secondary video-btn"><i class="fa-solid fa-video"></i>&nbsp; View</button></a></td> --}}
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    @if ($session->pres != null)
                    <div class="wallet-table">
                    <table class="table table-resposnive">
                        <thead>
                        <tr class="table-primary">
                            <th scope="col">Recommendation</th>
                            <th scope="col">Dosage</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Type</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($session->pres as $meds)
                            <tr>
                                @if ($meds->type == 'lab-test')
                                    <td data-label="Recommendation" scope="row">{{ $meds->prod_detail->DESCRIPTION }}</td>
                                @else
                                    <td data-label="Recommendation" scope="row">{{ $meds->prod_detail->name }}</td>
                                @endif
                                <td data-label="Dosage">{{ $meds->usage }}</td>
                                <td data-label="Comment">{{ $meds->comment }}</td>
                                <td data-label="Type">{{ $meds->type }}</td>
                                <td data-label="Status">
                                @if ($meds->cart_status == 'purchased')
                                    <label class="order-paid">{{ $meds->cart_status }}</label>
                                @elseif($meds->cart_status == 'pending')
                                    <label class="order-pending">{{ $meds->cart_status }}</label>
                                @else
                                    <label class="order-progress">{{ $meds->cart_status }}</label>
                                @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    @else
                        <p class="text-center">No Recommendations</p>
                    @endif
                  </div>

              </div>
            </div>
        </div>
      </div>
    </div>
  </div>

@endsection
