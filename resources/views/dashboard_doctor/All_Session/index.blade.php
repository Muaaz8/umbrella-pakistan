@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - All Session</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript">
        $(function() {

            $('input[name="datefilter"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });
            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            });

        });
        </script>
@endsection

@section('content')
{{-- {{ dd($user_type,$sessions) }} --}}
<div class="dashboard-content">
    <div class="container-fluid">

      <div class="row m-auto all-session-wrap">
        <div>
        <h4>All Sessions</h4>
        <form action="{{ route('session_search') }}" method="post">
            @csrf
        <div class="row">

                <div class="col-md-5 col-sm-6 mb-2"><input type="search" name="session_id" id="search" class="bg-light form-control" placeholder="Search Here By Session ID(Only Numeric Values)" value=""></div>
                <div class="col-md-5 col-sm-6 mb-2"><input type="text" readonly name="datefilter" id="datePick" class="bg-light form-control" placeholder="Filter Session Record By Date-Range" value=""></div>
                <div class="col-md-2 col-sm-12 mb-2"><button type="submit" class="btn medi-search">Search</button></div>

        </div>
    </form>
        <hr>
        </div>
        @forelse ($sessions as $ses)
        <div class="accordion accordion-flush all-session-accord" id="accordionFlushExample">
          <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="flush-heading{{ $ses->id }}">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $ses->id }}" aria-expanded="false" aria-controls="flush-collapse{{ $ses->id }}">
            <div class="d-flex justify-content-between w-100">
                <div>
              {{ $ses->pat_name}}
             </div>
             <!-- <div class="arrow-container">
              <div class="arrow-down"></div>
          </div> -->
             <div>
                {{ $ses->date." ".$ses->start_time }}
             </div>
            </div>
              </button>
            </h2>
            <div id="flush-collapse{{ $ses->id }}" class="accordion-collapse collapse" aria-labelledby="flush-heading{{ $ses->id }}" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body p-0">
                <div class="wallet-table table-responsive">
                  <div class="d-flex border p-1 px-3">
                    <h6>Diagnosis :</h6>
                    <p class="ms-3">{{ $ses->diagnosis }}</p>
                  </div>
                  <div class="d-flex border p-1 px-3">
                    <h6>Provider Note :</h6>
                    <p class="ms-3">{{ $ses->provider_notes }}</p>
                  </div>
                  @if(isset($ses->sympptoms->symptoms_text))
                  <div class="d-flex border p-1 px-3">
                    <h6>Symptoms :</h6>
                    <p class="ms-3">{{ str_replace(',', ', ',$ses->sympptoms->symptoms_text) }}</p>
                  </div>
                  @endif
                  @if(isset($ses->sympptoms->description))
                  <div class="d-flex border p-1 px-3">
                    <h6>Patient Description :</h6>
                    <p class="ms-3">{{ str_replace(',', ', ',$ses->sympptoms->description) }}</p>
                  </div>
                  @endif
                  @if ($ses->refered != null)
                  <div class="d-flex border p-1 px-3">
                    <h6>Referred :</h6>
                    <p class="ms-3">{{ $ses->refered }}</p>
                  </div>
                  @endif
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">Session ID</th>
                        <th scope="col">Earning</th>
                        @if ($ses->refered != null)
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
                        <td  data-label="Session ID" scope="row">UEV-{{ $ses->session_id }}</td>
                        <td  data-label="Earning">Rs. {{ number_format($ses->price,2) }}</td>
                        @if ($ses->refered != null)
                        <td  data-label="Referred Doctor">{{ $ses->refered }}</td>
                        @endif
                        <td  data-label="Date">{{ $ses->date }}</td>
                        <td  data-label="Start Time">{{ $ses->start_time }}</td>
                        <td  data-label="End Time">{{ $ses->end_time }}</td>
                        {{-- <td  data-label="Recording"><a onclick="window.open('{{ route('sessions.record.view',['id'=>$ses->id]) }}','popup','width=600,height=500'); return false;"
                            target="popup" rola="button"><button type="button" class="btn btn-secondary video-btn"><i class="fa-solid fa-video"></i>&nbsp; View</button></a></td> --}}
                      </tr>
                    </tbody>
                  </table>
                </div>
                @if ($ses->pres != null)
                    <div class="wallet-table table-responsive">
                    <table class="table">
                        <thead>
                        <tr class="table-primary">
                            <th scope="col">Recommendation</th>
                            <th scope="col">Dosage and Imaging Location</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Type</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($ses->pres as $meds)
                            <tr>
                                @if ($meds->type == 'lab-test' || $meds->type == 'imaging')
                                    <td data-label="Recommendation" scope="row">{{ $meds->prod_detail['TEST_NAME'] }}</td>
                                @else
                                    <td data-label="Recommendation" scope="row">{{ $meds->prod_detail['name'] }}</td>
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
        @empty
        <div class="text-center for-empty-div">
            <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
            <h6> No Sessions Records</h6>
        </div>
        @endforelse
        {{ $sessions->links('pagination::bootstrap-4') }}
      </div>
    </div>
</div>
@endsection
