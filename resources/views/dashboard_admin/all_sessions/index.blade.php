@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Admin Dashboard</title>
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
                    @foreach ($sessions as $session)
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="flush-{{ $session->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapse{{ $session->id }}" aria-expanded="false"
                                aria-controls="flush-collapse{{ $session->id }}">
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
                                                <th scope="col">Recording</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-label="Session ID">UEV-{{ $session->session_id }}</td>
                                                @if ($user_type == 'doctor')
                                                   <td data-label="Referred Doctor">${{ number_format($session->price, 2) }}</td>
                                                @endif
                                                @if ($session->refered != null)
                                                    {{-- <th scope="col">Referred Doctor</th> --}}
                                                    <td data-label="Referred Doctor">{{ $session->refered }}</td>
                                                @endif
                                                <td data-label="Date">{{ $session->date }}</td>
                                                <td data-label="Start Time">{{ $session->start_time }}</td>
                                                <td data-label="End Time">{{ $session->end_time }}</td>
                                                <td data-label="Recording"><a class="btn btn-secondary video-btn"
                                                        onclick="window.open('{{ route('VideoRecordSession', ['id' => $session->id]) }}','popup','width=600,height=500'); return false;"
                                                        target="popup" rola="button"><i class="fa-solid fa-video fs-5"
                                                            title="click to watch call recording"
                                                            style="cursor:pointer; font-size:35px;"></td>
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
                                                        <td>{{ ucfirst($pres->prod_detail['DESCRIPTION']) }}</td>
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
                    @endforeach
                    {{ $sessions->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    </div>


    </div>
@endsection