@extends('layouts.dashboard_patient')
@section('meta_tags')
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">

@endsection
@section('top_import_file')
@endsection
@section('page_title')
  <title>UHCS - Current Medication</title>
@endsection
@section('bottom_import_file')
    <script src="{{asset('assets\js\patient_dashboard_script\refill_request.js')}}"></script>
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
          </script>
@endsection
@section('content')
{{-- {{ dd($pres) }} --}}
<div class="dashboard-content">
        <div class="container-fluid">
          <div class="row m-auto">
            <div class="col-md-12">
              <div class="row m-auto">
                <div class="d-flex align-items-end p-0">
                  <div>
                    <h3>Current Medications</h3>
                    <p>All Prescribed Medications Are Listed Here</p>
                  </div>
                </div>
                <div class="p-0">
                  <div class="refill">
                    <form action="{{ route('current_medication') }}" method="post">
                        @csrf
                    <div class="row mb-4">

                            <div class="col-md-5 mb-2"><input type="search" name="session_id" id="search" class="bg-light form-control" placeholder="Search Here By Session ID" value=""></div>
                            <div class="col-md-5 mb-2"><input type="text" name="dates" id="datePick" class="bg-light form-control" placeholder="filter session record by date-range" value=""></div>
                            <div class="col-md-2 mb-2"><button type="submit" class="btn medi-search">Search</button></div>

                    </div>
                </form>
                    <div class="row  mt-4">
                    @if($errors->any())
                    <h3 style="color:red;">{{$errors->first()}}</h3>
                    <br><br>
                    @endif
                        @forelse($pres as $pr)
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="refill-first">
                          <div class="row m-auto">
                            <div class="col-md-8 mb-2">
                              <div class="refill-data">
                              <i class="fa-solid fa-capsules refil-card-icon"></i>
                                <h6 title="{{ $pr->prod_name }}" class="fw-bold">{{ $pr->prod_name }}</h6>
                                <p><span>Session ID:</span>&nbsp;<span>UEV-{{ $pr->ses_id }}</span></p>
                                @if (isset($pr->fname))
                                <p><span>Prescribed By:</span>&nbsp;<span>Dr {{ $pr->fname . ' ' . $pr->lname }}</span></p>
                                @else
                                <p><span>Prescribed By:</span>&nbsp;<span>Dr {{ $pr->doc }}</span></p>
                                @endif
                                @if (isset($pr->session_date))
                                <p><span>Session Date:</span>&nbsp;<span>{{ $pr->session_date }}</span></p>
                                @else
                                <p><span>Session Date:</span>&nbsp;<span>{{ $pr->date }}</span></p>
                                @endif
                                <p><span>Session Time:</span>&nbsp;<span>{{ $pr->start_time }} -
                                    {{ $pr->end_time }}</span></p>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="format-btn gap-2">
                                @if ($pr->granted == null && $pr->status == 'purchased')
                                    <button id="refill_{{ $pr->id }}"
                                        class="btn refill-btn btn-info btn-raised" type="button" onclick="myFunction({{ $pr->id }})">Request Refill
                                    </button>
                                @elseif($pr->session_req == '1')
                                    <a
                                    href="{{ url('/book/requested/appointment/'.$pr->session_id) }}">
                                    <button class="btn refill-btn btn-secondary btn-raised">Book An
                                        Appointment</button>
                                    </a>
                                @elseif($pr->granted == '0' && $pr->session_req == '0')
                                    <a>
                                        <button class="btn refill-btn btn-danger btn-raised" disabled>Refill Requested
                                        </button>
                                    </a>
                                @elseif($pr->granted == '1' || $pr->status == 'recommended')

                                    <a href="{{ route('user_cart') }}">
                                        <button class="btn refill-btn btn-success btn-raised">Go To Cart </button>
                                    </a>
                                @endif
                                <a href="{{ route('sessionDetail', $pr->session_id) }}">
                                <button class="btn details-btn btn-warning btn-raised" type="button">DETAILS</button>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      @empty
                        <p>No prescriptions available</p>
                        @endforelse
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="paginateCounter link-paginate">
                                {{ $pres->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                      {{-- <div class="col-lg-6">
                        <div class="refill-first">
                          <div class="row">
                            <div class="col-lg-8">
                              <div class="refill-data">
                                <h6 class="fw-bold">Melatonin tablet (OTC)</h6>
                                <p><span>Session ID:</span>&nbsp;<span>UEV-496</span></p>
                                <p><span>Prescribed By:</span>&nbsp;<span>Dr Umar Khan</span></p>
                                <p><span>Session Date:</span>&nbsp;<span>August,31,2022</span></p>
                                <p><span>Session Time:</span>&nbsp;<span>06:02 PM</span></p>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="format-btn gap-2">
                                <button class="btn refill-btn" type="button" data-bs-toggle="modal"
                                  data-bs-target="#refill-modal">REFILL</button>
                                <button class="btn details-btn" type="button">DETAILS</button>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-6">
                          <div class="refill-first">
                            <div class="row">
                              <div class="col-lg-8">
                                <div class="refill-data">
                                  <h6 class="fw-bold">Melatonin tablet (OTC)</h6>
                                  <p><span>Session ID:</span>&nbsp;<span>UEV-496</span></p>
                                  <p><span>Prescribed By:</span>&nbsp;<span>Dr Umar Khan</span></p>
                                  <p><span>Session Date:</span>&nbsp;<span>August,31,2022</span></p>
                                  <p><span>Session Time:</span>&nbsp;<span>06:02 PM</span></p>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="format-btn gap-2">
                                  <button class="btn refill-btn" type="button" data-bs-toggle="modal"
                                    data-bs-target="#refill-modal">REFILL</button>
                                  <button class="btn details-btn" type="button">DETAILS</button>
                                </div>
                              </div>

                            </div>
                          </div>




                        </div>
                        <div class="col-lg-6">
                          <div class="refill-first">
                            <div class="row">
                              <div class="col-lg-8">
                                <div class="refill-data">
                                  <h6 class="fw-bold">Melatonin tablet (OTC)</h6>
                                  <p><span>Session ID:</span>&nbsp;<span>UEV-496</span></p>
                                  <p><span>Prescribed By:</span>&nbsp;<span>Dr Umar Khan</span></p>
                                  <p><span>Session Date:</span>&nbsp;<span>August,31,2022</span></p>
                                  <p><span>Session Time:</span>&nbsp;<span>06:02 PM</span></p>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="format-btn gap-2">
                                  <button class="btn refill-btn" type="button" data-bs-toggle="modal"
                                    data-bs-target="#refill-modal">REFILL</button>
                                  <button class="btn details-btn" type="button">DETAILS</button>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div> --}}
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


                                  <!-- *********REFILL MODAL*********** -->



                                  <div class="modal fade" id="refill-modal" tabindex="-1"
                                  aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Request Refill</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                          aria-label="Close"></button>
                                      </div>
                                      <form action="{{ route('refill_request') }}" method="post">
                                        @csrf
                                        <div class="mod-body p-4">
                                        <div class="modal-body" id="load_refill">
                                            <input id="pr_id" name="id" value="" hidden="">
                                        </div>
                                        <div class="modal-body">
                                          <h6>Any Comment</h6>
                                            <textarea class="form-control" id="note-textarea" name="note" rows="4" placeholder="Create a new note by typing or using voice recording." required></textarea>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                      <center>
                                      <button id="start-record-btn" class="btn ui blue button" onclick="runSpeechRecognition()" type="button" title="Start Recording"><i class="fa fa-microphone"></i> Start Recording</button>
                                      <br>
                                      <br>
                                      <p id="recording-instructions">Press the <strong>Start Recording </strong> button and allow access.</p>
                                      </center>
                                        <button type="submit" class="btn-primary modal-btn">SUBMIT</button>
                                      </div>
                                    </form>
                                    </div>
                                  </div>
                                </div>
@endsection
@section('script')
  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
  <script src="assets/js/custom.js"></script>
@endsection
