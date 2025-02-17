@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Admin Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script type="text/javascript">
  <?php header("Access-Control-Allow-Origin: *"); ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#spec').on('change',function(){
        $.ajax({
            type: 'POST',
            url: "/admin/all/sessions/record/spec",
            data: {
                spec:$('#spec').val(),
            },
            success: function(status)
            {
                $('#accordionFlushExample').html(status);
            }
        });
    });
</script>
@endsection

@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <h4 class="col-md-4  mb-3 px-3">All Session</h4>
            <div class="row m-auto all-session-wrap">
                <div class="col-md-4  mb-3">
                    <select class="form-select me-2" aria-label="Default select example" id="spec">
                        <option Value="0" selected>All</option>
                        @foreach($specializations as $spec)
                            <option value="{{$spec->id}}">{{$spec->name}}</option>
                        @endforeach
                    </select>
                </div>
                <h4 class="col-md-4  mb-3"></h4>
                <div class="col-md-4 mb-3 p-0">
                    <div class="input-group ">
                      <form action="{{ url('/admin/all/sessions/record') }}" method="POST" style="width: 100%;">
                          @csrf
                          <input
                          type="text"
                          id="search"
                          name="name"
                          class="form-control"
                          placeholder="Search By Session Id"
                          aria-label="Username"
                          aria-describedby="basic-addon1"/>
                      </form>
                    </div>
                  </div>

                <div class="accordion accordion-flush" id="accordionFlushExample">
                    @foreach ($sortedData as $session)
                        @if($session->type == "session")
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="flush-{{ $session->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapse{{ $session->id }}" aria-expanded="false"
                                        aria-controls="flush-collapse{{ $session->id }}">
                                        <div style="
                                            background: #db1c13;
                                            color: white;
                                            border: black 1px solid;
                                            position: absolute;
                                            z-index: 3;
                                            font-size: 11px;
                                            writing-mode: vertical-lr;
                                            text-orientation: sideways;
                                            padding: 8px;
                                            left: -13px;
                                        ">{{ ucfirst($session->type)}}</div>
                                        <div class="d-flex flex-wrap justify-content-between w-100">
                                            <div>
                                                Dr.
                                                {{ ucwords($session->doc_name) . ' with ' . ucwords($session->pat_name) }}
                                            </div>
                                            <div>
                                                <span class="float-right">{{ $session->date }}</span>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="flush-collapse{{ $session->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="flush-heading{{ $session->id }}" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body p-0">
                                        <div class="wallet-table table-responsive">
                                            <div class="d-flex border p-1 px-3">
                                                <h6>Diagnosis :</h6>
                                                <p class="ms-3">{{ ucfirst($session->diagnosis) }}</p>
                                            </div>
                                            <div class="d-flex border p-1 px-3">
                                                <h6>Provider Note :</h6>
                                                <p class="ms-3">{{ $session->provider_notes }}</p>
                                            </div>
                                            <div class="d-flex border p-1 px-3">
                                                <h6>Patient Feedback on Session :</h6>
                                            <p class="ms-3">{{ $session->patient_suggestions }}</p>
                                            </div>
                                            <div class="d-flex border p-1 px-3">
                                                @if ($user_type == 'doctor')
                                                    <h6>Notes :</h6>
                                                @endif
                                                @if ($user_type == 'doctor')
                                                    <input id="notes_text" hidden=""
                                                        value="{{ ucfirst($session->provider_notes) }}">
                                                    <td id="notes"></td>
                                                @endif
                                                {{--  <p class="ms-3">Lorem ipsum dolor voluptatibus optio? Et, quis. Ab earum, tempore enim veniam,</p>  --}}
                                            </div>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Session ID</th>
                                                        @if ($user_type == 'doctor')
                                                            <th scope="col">Earning</th>
                                                        @endif
                                                        @if ($session->refered != null)
                                                            <th scope="col">Referred Doctor</th>
                                                        @endif
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Start Time</th>
                                                        <th scope="col">End Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td data-label="Session ID">UEV-{{ $session->session_id }}</td>
                                                        @if ($user_type == 'doctor')
                                                        <td data-label="Referred Doctor">Rs. {{ number_format($session->price, 2) }}</td>
                                                        @endif
                                                        @if ($session->refered != null)
                                                            {{-- <th scope="col">Referred Doctor</th> --}}
                                                            <td data-label="Referred Doctor">{{ $session->refered }}</td>
                                                        @endif
                                                        <td data-label="Date">{{ $session->date }}</td>
                                                        <td data-label="Start Time">{{ $session->start_time }}</td>
                                                        <td data-label="End Time">{{ $session->end_time }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="wallet-table">
                                            <table class="table">
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

                                                    @forelse($session->pres as $pres)
                                                        @if ($pres->prod_detail != null)
                                                            @if ($pres->prod_detail['mode'] == 'medicine')
                                                                <tr>
                                                                @elseif($pres->prod_detail['mode'] == 'lab-test')
                                                                <tr>
                                                                @elseif($pres->prod_detail['mode'] == 'imaging')
                                                                <tr>
                                                            @endif
                                                            @if (isset($pres->prod_detail['name']))
                                                                <td>{{ ucfirst($pres->prod_detail['name']) }}</td>
                                                            @else
                                                                <td>{{ ucfirst($pres->prod_detail['TEST_NAME']) }}</td>
                                                            @endif
                                                            <!-- <td>{{ ucfirst($pres->usage) }}</td> -->
                                                            @php
                                                                $t = "<div class='text-center'>N/A</div>";
                                                            @endphp
                                                            <td>{!! $pres->usage != '' ? $pres->usage : $t !!}</td>
                                                            @php
                                                                $t = "<div class='text-center'>N/A</div>";
                                                            @endphp
                                                            <td>{!! $pres->comment != '' ? $pres->comment : $t !!}</td>

                                                            <!-- <td>{{ ucfirst($pres->comment) }}</td> -->
                                                            <td>{{ ucfirst($pres->type) }}</td>
                                                            <!-- Status from Cart table -->

                                                            <td><label
                                                                    class="order-paid">{{ ucfirst($pres->cart_status) }}</label>
                                                            </td>


                                                            </tr>
                                                        @endif
                                                    @empty
                                                        <tr>
                                                            <td colspan="5">
                                                                <div class="m-auto text-center for-empty-div">
                                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                                    <h6>No Prescriptions To Show</h6>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif ($session->type == "inclinic")
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item mb-2">
                                    <h2 class="accordion-header" id="flush-heading{{ $session->id }}">
                                        @if ($session->doctor != null)
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapse{{ $session->id }}" aria-expanded="false"
                                                aria-controls="flush-collapse{{ $session->id }}">
                                                <div style="
                                                    background: #113ee0;
                                                    color: white;
                                                    border: black 1px solid;
                                                    position: absolute;
                                                    z-index: 3;
                                                    font-size: 11px;
                                                    writing-mode: vertical-lr;
                                                    text-orientation: sideways;
                                                    padding: 8px;
                                                    left: -13px;
                                                ">{{ ucfirst($session->type)}}</div>
                                                <div class="d-flex flex-wrap justify-content-between w-100">
                                                    <div>
                                                        Dr.
                                                        {{ ucwords($session->doctor->name) . ' with ' . ucwords($session->user->name)." ".ucwords($session->user->last_name) }}
                                                    </div>
                                                    <div>
                                                        <span class="float-right">{{ $session->date }}</span>
                                                    </div>
                                                </div>
                                            </button>
                                        @else
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapse{{ $session->id }}" aria-expanded="false"
                                                aria-controls="flush-collapse{{ $session->id }}">
                                                <div style="
                                                        background: #113ee0;
                                                        color: white;
                                                        border: black 1px solid;
                                                        position: absolute;
                                                        z-index: 3;
                                                        font-size: 11px;
                                                        writing-mode: vertical-lr;
                                                        text-orientation: sideways;
                                                        padding: 8px;
                                                        left: -13px;
                                                    ">{{ ucfirst($session->type)}}</div>
                                                <div class="d-flex flex-wrap justify-content-between w-100">
                                                    <div>
                                                        {{ ucwords($session->user->name)." ".ucwords($session->user->last_name) }}
                                                    </div>
                                                    <div>
                                                        <span class="float-right">{{ $session->date }}</span>
                                                    </div>
                                                </div>
                                            </button>

                                        @endif
                                    </h2>
                                    <div id="flush-collapse{{ $session->id }}" class="accordion-collapse collapse"
                                        aria-labelledby="flush-heading{{ $session->id }}"
                                        data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body p-0">
                                            <div class="row m-auto">
                                                <div class="d-flex border p-1 px-3">
                                                    <h6>Session ID :</h6>
                                                    <p class="ms-3">UEV-{{ $session->id }}</p>
                                                </div>
                                                <div class="d-flex border p-1 px-3">
                                                    <h6>Time :</h6>
                                                    <p class="ms-3">{{ $session->updated_at }}</p>
                                                </div>
                                                <div class="d-flex border p-1 px-3">
                                                    <h6>Doctor note :</h6>
                                                    <p class="ms-3">{{ $session->doctor_note }}</p>
                                                </div>
                                                <div class="wallet-table table-responsive">
                                                    @if(($session->prescriptions) != null)
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
                                                                    @foreach ($session->prescriptions as $med)
                                                                            <tr>
                                                                                @if($med->type=='lab-test')
                                                                                    <td data-label="Recommendation">{{ \App\QuestDataTestCode::where('TEST_CD',$med->test_id)->first()->TEST_NAME }}</td>
                                                                                @elseif($med->type=='imaging')
                                                                                    <td data-label="Recommendation">{{ \App\QuestDataTestCode::where('TEST_CD',$med->imaging_id)->first()->TEST_NAME }}</td>
                                                                                @else
                                                                                    <td data-label="Recommendation">{{ \App\Models\AllProducts::find($med->medicine_id)->name }}</td>
                                                                                @endif
                                                                                <td data-label="Dosage">{{ $med->usage }}</td>
                                                                                <td data-label="Comment">{{ $med->comment }}</td>
                                                                                <td data-label="Type">{{ $med->type }}</td>
                                                                                <td data-label="Status">{{ $med->title }}</td>
                                                                            </tr>
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
                        @endif
                    @endforeach
                    {{-- $sortedData->links('pagination::bootstrap-4') --}}
                </div>
            </div>
        </div>
    </div>
    </div>


    </div>
@endsection
