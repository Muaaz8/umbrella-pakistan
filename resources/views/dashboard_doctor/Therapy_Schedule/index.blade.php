@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Doctor Schedule</title>
@endsection

@section('top_import_file')
<script src="//cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.16/clipboard.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
@endsection


@section('bottom_import_file')
<script>

<?php header("Access-Control-Allow-Origin: *"); ?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// var myModalEl = document.getElementById('editscheduleModal');
// myModalEl.addEventListener('hidden.bs.modal', function(event) {
//   $('#edit_error').html('');
// });
CKEDITOR.replace( 'helping', {
  enterMode: CKEDITOR.ENTER_BR,
  on: {'instanceReady': function (evt) { evt.editor.execCommand('');}},
  });
CKEDITOR.replace( 'skilled', {
  enterMode: CKEDITOR.ENTER_BR,
  on: {'instanceReady': function (evt) { evt.editor.execCommand('');}},
  });
$(".js-select2").select2({
    closeOnSelect: false,
    scrollAfterSelect: false,
    placeholder: "Select Option",
    allowHtml: false,
    allowClear: true,
    tags: true,
    dropdownParent: $('#therapy'),
    });

function copy_link(link)
{
  link = "{{env('APP_URL')}}/patient/therapy/"+link;
  $('#link_address').val(link);
  $('#copylink').modal('show');
}

var clipboard = new Clipboard('#copy_btn');
clipboard.on('success', function(e) {
    e.clearSelection();
    alert('Copy to Clipboard Successfully');
});
clipboard.on('error', function(e) {
    alert('Something is wrong!');
});

function available_therapy_time()
{
    $.ajax({
        type: 'POST',
        url: "/get_therapy_slots",
        data: {
            date: $('#therapydate').val(),
            id:0,
        },
        async: false,
        success: function(data)
        {
          $('#therapy_start_slots').html('');
          $.each (data, function (key, start) {
              $('#therapy_start_slots').append('<option value="'+start.time+'">'+start.time+'</option>');
          });
        }
    });
}

function edit_therapy(event,count)
{
  if(count>0)
  {
    alert('You can not edit this Group Therapy because you have Enrolled Patients')
  }
  else
  {
    // var e_date = moment(event.date, 'mm-DD-YYYY').format('YYYY-mm-DD');
    // $('#edit_therapydate_div').html('<input id="edit_therapydate" name="date" type="text" '
    // +'class="form-control" value="'+e_date+'" readonly/>'
    // +'<input type="hidden" id="event_id" name="id"  value="'+event.id+'">');
    // $.ajax({
    //     type: 'POST',
    //     url: "/get_therapy_slots",
    //     data: {
    //         date: moment(event.date, 'mm-DD-YYYY').format('YYYY-mm-DD'),
    //         id:event.id,
    //     },
    //     async: false,
    //     success: function(data)
    //     {
    //         $('#edit_therapy_start_slots').html('');
    //         $.each (data, function (key, start) {
    //             $('#edit_therapy_start_slots').append('<option value="'+start.time+'">'+start.time+'</option>');
    //         });
    //     }
    // });
    // $('#therapy_edit').modal('show');
    window.location.href = '/edit/psychiatrist/info/form/'+event.id;

  }
}

function show_patients(params){
    console.log(params);
    $('#load_appointments').html('');
    // alert(params[0].date);
    if(params!=null || params!=''){
        var sno=1;
        $.each (JSON.parse(params), function (key, appoint) {
            $('#load_appointments').append('<tr>'+
                  '<th scope="row">'+sno+'</th>'+
                  '<td>'+appoint.name+'</td>'+
                '</tr>'
                );
            sno++;
        });
        $('#viewappointmentModal').modal('show');
    }
}
function delete_therapy(id,appoint){
    $('#del_schedule').html('');
    if(appoint!=0){
        alert('You can not delete this Group Therapy because you have Enrolled Patients');
        return false;
    }
    else if(id!=null){
        $('#del_schedule').append('<div class="p-5 text-center">'+
              '<a href="/delete/doctor_schedule/'+id+'"><button type="button" class="btn btn-danger delete-m-btn me-2">Delete</button></a>'+
              '<button type="button" class="btn btn-primary delete-m-btn" data-bs-dismiss="modal">No</button>'
            );
        $('#deleteeModal').modal('show');
    }

  }
</script>
@endsection

@section('content')
<div class="dashboard-content">
    <div class="container-fluid">
      @if (session()->get('msg'))
        <div id="errorDiv1" class="alert alert-primary col-12 col-md-6 offset-md-3">
            @php
                $es = session()->get('msg');
            @endphp
            <span role="alert"> <strong>{{ $es }}</strong></span>
        </div>
      @endif
      <div class="row m-auto">
        <div class="col-md-12">
          <div class="row m-auto">
            <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
              <h3>Therapy Schedule</h3>
              <div class="p-0">

                @if(auth()->user()->specialization == '21')
                <button
                  class="add-schedule" onclick="window.location.href='/view/psychiatrist/info/form'">
                  <i class="fa-solid fa-plus"></i> Add Therapy Event
                </button>
                @endif
              </div>
            </div>
            <div class="wallet-table table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">Time Zone</th>
                    <th scope="col">Enrolled Patients</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @forelse ($schedule as $sch)
                  <tr>
                    <td data-label="Date" scope="row">{{ $sch->date }}</td>
                    <td data-label="Start Time">{{ $sch->start }}</td>
                    <td data-label="End Time">{{$sch->time_zone}}</td>
                    <td data-label="Appointments">
                      <button
                        class="orders-view-btn"
                        style="position: relative"
                        onclick="show_patients({{ json_encode($sch->patients) }})"
                      >
                        <h6 class="app-num">{{ $sch->count }}</h6>
                        View Enrolled Patients
                      </button>
                    </td>
                    <td data-label="Action" class="lab-app-td-icon">
                    <i class="fa-solid fa-pen-to-square"
                         onclick="edit_therapy({{ $sch }},{{ $sch->count }})"></i>
                    <i class="fa-solid fa-link"
                      onclick="copy_link({{$sch->session_id}})"></i>
                      @if($sch->enroll=='1')
                      &nbsp;<a class="btn btn-raised btn-success" href="/doctor/therapy/{{$sch->session_id}}">Join</a>
                      @else
                      <i
                      class="fa-solid fa-circle-xmark"
                      onclick="delete_therapy({{ $sch->id }},{{ $sch->count }})"
                    ></i>
                    @endif
                    </td>
                  </tr>
                @empty
                <tr>
                  <td colspan="5">
                      <div class="m-auto text-center for-empty-div">
                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                        <h6> No Therapy Schedule added</h6>
                      </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
              </table>
              <div class="row d-flex justify-content-center">
                <div class="paginateCounter">
                    {{ $schedule->links('pagination::bootstrap-4') }}
                </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================= Add schedule Modal start ================ -->
    <!-- Button trigger modal -->

    <!-- Modal -->
    <!-- ================= Add schedule Modal Ends ================ -->


    <!-- ================= Edit schedule Modal Ends ================ -->

    <!-- ================= Delete Modal Starts ================ -->

    <div
      class="modal fade"
      id="deleteeModal"
      tabindex="-1"
      aria-labelledby="deleteeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteeModalLabel">
              You want delete this schedule?
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body" id="del_schedule">
          </div>
        </div>
      </div>
    </div>
    <!-- ================= Delete Modal Ends ================ -->

    <div
      class="modal fade"
      id="therapy"
      tabindex="-1"
      aria-labelledby="therapyModalLabel"
      aria-hidden="true"
    >
    <form method="POST" action="{{route('add_therapy_event')}}">
        @csrf
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="therapyModalLabel">
              Add Therapy Event
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="p-3">
              <div class="input-group mb-3">
                <input
                  id="therapydate"
                  name="date"
                  type="date"
                  class="form-control"
                  min="{{ $date }}"
                  max="<?= date('Y-m-d', strtotime('12/31')) ?>"
                  onchange="available_therapy_time()"
                  required
                  placeholder="Username"
                  aria-label="Username"
                  aria-describedby="basic-addon1"
                />
              </div>
              <div class="row align-items-center">
                <div class="col-md-6">
                  <div class="input-group mb-3">
                    <input type="hidden" name="AvailabilityTitle"  value="Therapy" id="title" placeholder="Title">
                          <input type="hidden" value="#008000" name="AvailabilityColor"  id="color" />
                          <input type='hidden' class="form-control" name="AvailabilityStart"  class="form-control" id="start"/>
                            {{-- <input type='text' class="form-control"  name="startTimePicker" id="timepicker1" value="12:00 PM" />  --}}
                          <select id="therapy_start_slots" onchange="remove_error()" class="form-control"  name="startTimePicker" required>
                            <option value="">Select Start Time</option>
                          </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group mb-3">
                      <select id="Time_Zone" onchange="remove_error()" class="form-control"  name="time_zone" required>
                        <option value="">Select Timezone</option>
                        <option value="CST">CST</option>
                        <option value="EST">EST</option>
                        <option value="PST">PST</option>
                        <option value="MST">MST</option>
                      </select>
                  </div>
                </div>
              </div>
              <div>
                <h3>Event Information</h3>
              </div>
              <div class="row">
                        <div class="col-md-12">
                            <label class="fw-bolder" for="selectmedicine">CLIENT CONCERNS I TREAT</label>
                            <select class="js-select2" name="concerns[]" multiple="multiple">
                                <option value="Worthlessness">Worthlessness</option>
                                <option value="Worry">Worry</option>
                                <option value="Women-Issues">Women's Issues</option>
                                <option value="Trust-Issues">Trust Issues</option>
                                <option value="Suicidal-Ideation-and-Behavior">Suicidal Ideation and Behavior</option>
                                <option value="Stress">Stress</option>
                                <option value="Spirituality">Spirituality</option>
                                <option value="Social-Anxiety-Phobia">Social Anxiety / Phobia</option>
                                <option value="Shame">Shame</option>
                                <option value="Self-Love">Self-Love</option>
                                <option value="Self-Esteem">Self-Esteem</option>
                                <option value="Self-Doubt">Self-Doubt</option>
                                <option value="Self-Confidence">Self-Confidence</option>
                                <option value="Self-Criticism">Self-Criticism</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bolder" for="selectmedicine">SERVICES I PROVIDE</label>
                            <select class="js-select2" name="services[]" multiple="multiple">
                                <option value="Consultation">Consultation</option>
                                <option value="Family">Family Therapy</option>
                                <option value="Individual">Individual Therapy & Counseling</option>
                                <option value="Marriage">Marriage, Couples, or Relationship Counseling</option>
                                <option value="Telehealth">Telehealth</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label class="fw-bolder" for="helping">My Approach to Helping:</label>
                            <textarea class="form-control" id="helping" name="helping" rows="4"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bolder" for="skilled">SPECIFIC ISSUE(S) I'M SKILLED AT HELPING WITH:</label>
                            <textarea class="form-control" id="skilled" name="skilled" rows="4"></textarea>
                        </div>
                    </div>
              <div class="text-center" id="error">
              </div>
              <div class="text-center">
                <button class="w-100 add-schedule">Add Event</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      </form>
    </div>

    <!-- ================= View Appointment Modal starts ================ -->

    <div
      class="modal fade"
      id="therapy_edit"
      tabindex="-1"
      aria-labelledby="therapyModalLabel"
      aria-hidden="true"
    >
    <form method="POST" action="{{route('edit_therapy_event')}}">
        @csrf
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="therapyModalLabel">
              Edit Therapy Event
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="p-3">
              <div class="input-group mb-3" id="edit_therapydate_div">
                <input
                  id="edit_therapydate"
                  name="date"
                  type="date"
                  class="form-control"
                  min="{{ $date }}"
                  max="<?= date('Y-m-d', strtotime('12/31')) ?>"
                  required
                  placeholder="Username"
                  aria-label="Username"
                  aria-describedby="basic-addon1"
                />
              </div>
              <div class="row align-items-center">
                <div class="col-md-6">
                  <div class="input-group mb-3">
                    <select id="edit_therapy_start_slots" onchange="remove_error()" class="form-control"  name="startTimePicker" required>
                      <option value="">Select Start Time</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group mb-3">
                      <select id="Time_Zone" onchange="remove_error()" class="form-control"  name="time_zone" required>
                        <option value="">Select Timezone</option>
                        <option value="CST">CST</option>
                        <option value="EST">EST</option>
                        <option value="PST">PST</option>
                        <option value="MST">MST</option>
                      </select>
                  </div>
                </div>
              </div>
              <div class="text-center" id="error">
              </div>
              <div class="text-center">
                <button class="w-100 add-schedule">Done</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      </form>
    </div>
    <!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="viewappointmentModal" tabindex="-1" aria-labelledby="viewappointmentModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title" id="viewappointmentModalLabel">Enrolled Patients</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
<div>
  <table class="table">
      <thead>
        <tr>
          <th scope="col">S.No</th>
          <th scope="col">Patient Name</th>
        </tr>
      </thead>
      <tbody id="load_appointments">



      </tbody>
    </table>
</div>
  </div>

</div>
</div>
</div>
    <!-- ================= View Appointment Modal Ends ================ -->
<!-- ================= Delete Modal Starts ================ -->

<div
      class="modal fade"
      id="copylink"
      tabindex="-1"
      aria-labelledby="copylinkModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="copylinkModalLabel">
              Copy Invitation Link
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body" id="del_schedule">
          <div class="p-5 text-center">
          <input
            id="link_address"
            class="form-control"
            readonly
          />
          <br>
          <button id="copy_btn" type="button" class="btn btn-danger delete-m-btn me-2" data-clipboard-action="copy" data-clipboard-target="#link_address">Copy Link</button>
          <!-- <button type="button" class="btn btn-primary delete-m-btn" data-bs-dismiss="modal">No</button> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection

