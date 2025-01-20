@extends('layouts.dashboard_patient')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">

@endsection
@section('top_import_file')
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.16/clipboard.min.js"></script>
@endsection
@section('page_title')
    <title>CHCC - Upcoming Therapy Events</title>
@endsection
@section('bottom_import_file')
<script src="{{asset('assets\js\searching.js')}}"></script>
<script>
function copy_link(id)
{
  id = "{{env('APP_URL')}}"+'/patient/therapy/'+id;
  $('#link_address').val(id);
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
</script>
@endsection
@section('content')
        <div class="dashboard-content">
          <div class="container-fluid">
          @if (session()->get('message'))
            <div id="errorDiv1" class="alert alert-success col-12 col-md-6 offset-md-3">
                @php
                    $es = session()->get('message');
                @endphp
                <span role="alert"> <strong>{{ $es }}</strong></span>

            </div>
            @endif
          @if (session()->get('error'))
            <div id="errorDiv1" class="alert alert-danger col-12 col-md-6 offset-md-3">
                @php
                    $es = session()->get('error');
                @endphp
                <span role="alert"> <strong>{{ $es }}</strong></span>

            </div>
            @endif
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
                  <div class="row m-auto p-0">
                    <div class="col-md-8">
                      <h3>Upcoming Therapy Events</h3>
                    </div>
                    <div class="col-md-4 mt-md-auto mt-2 p-0">
                      <div class="input-group">
                        <input
                          type="text"
                          class="form-control"
                          id="search"
                          placeholder="Search any doctor name"
                          aria-label="Username"
                          aria-describedby="basic-addon1"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="wallet-table table-responsive">
                    <table class="table" id="table">
                      <thead>
                          <th scope="col">Doctor Name</th>
                          <th scope="col">Specialization</th>
                          <th scope="col">Date</th>
                          <th scope="col">Time</th>
                          <th scope="col">Status</th>
                          <th scope="col">Action</th>
                      </thead>
                      <tbody>
                        @forelse ($events as $ev)
                            <tr>
                            <td data-label="Doctor Name">{{$ev->doc_name}}</td>
                            <td data-label="Symptoms">Psychiatrist</td>
                            <td data-label="Date">{{$ev->start_time['date']}}</td>
                            <td data-label="Time">{{$ev->start_time['time']}}</td>
                            <td data-label="Status"><label class="badge bg-danger text-wrap">{{$ev->status}}</label></td>
                            <td data-label="Action">
                                @if($ev->enroll==1)
                                <i class="fa-solid fa-link"
                                 onclick="copy_link({{$ev->id}})"></i>
                                <a href="/patient/therapy/{{$ev->id}}">
                                    <button class="btn btn-raised btn-success btn-sm waves-effect mb-1">Join</button>
                                </a>
                                @else
                                <a href="/therapy/event/payment/{{$ev->id}}">
                                    <button class="btn btn-raised btn-info btn-sm waves-effect mb-1">Get Enrolled</button>
                                </a>
                                @endif
                            </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan='6'>
                                <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No Upcoming Therapy Events</h6>
                                </div>
                                </td>
                            </tr>
                            @endforelse
                      </tbody>
                    </table>
                    <div class="row d-flex justify-content-center">
                        <div class="paginateCounter">
                            {{ $events->links('pagination::bootstrap-4') }}
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
@endsection
