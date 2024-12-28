@extends('layouts.dashboard_finance_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('page_title')
    <title>Evisit Earnings</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript">
<?php header("Access-Control-Allow-Origin: *");?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function() {
  $('input[name="ses_date"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear'
      }
  });
  $('input[name="ses_date"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
      var date = $(this).val();
      var id = $('#user_id').val();
      var ses_id = $('#ses_id').val();
      url = "/doctors/evisit";
      $('#fdate').val(date);
      $('#uid').val(id);
      $('#s_id').val('');
      $('#filter').attr('action',url);
      $('#filter').submit();
  });
  $('input[name="ses_date"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
    var id = $('#user_id').val();
    window.location.href="/doctors/evisit/"+id;
  });
});

function ses_search()
{
    var ses_id = $('#ses_id').val();
    var id = $('#user_id').val();
    if(ses_id != '')
    {
      if(ses_id[0]!= 'U')
      {
        ses_id = 'UEV-'+ses_id;
      }
      url = "/doctors/evisit";
      $('#fdate').val('');
      $('#uid').val(id);
      $('#s_id').val(ses_id);
      $('#filter').attr('action',url);
      $('#filter').submit();
    }
    else
    {
        window.location.href="/doctors/evisit/"+id;
    }
}

function createfile(type)
{
    if(type=='pdf')
    {
      $('#fdate').val('');
      $('#s_id').val('');
      var date = $('#ses_date').val();
      var ses_id = $('#ses_id').val();
      var id = $('#user_id').val();
      url = "/generate-doc_evisitpdf";
      $('#fdate').val(date);
      $('#s_id').val(ses_id);
      $('#uid').val(id);
      $('#filter').attr('action',url);
      $('#filter').submit();
    }
    else if(type=='csv')
    {
      $('#fdate').val('');
      $('#s_id').val('');
      var date = $('#ses_date').val();
      var ses_id = $('#ses_id').val();
      if(ses_id[0]!= 'U')
      {
        ses_id = 'UEV-'+ses_id;
      }
      var id = $('#user_id').val();
      url = "/generate-doc_evisitcsv";
      $('#fdate').val(date);
      $('#s_id').val(ses_id);
      $('#uid').val(id);
      $('#filter').attr('action',url);
      $('#filter').submit();
    }
}
</script>
@endsection
@section('content')
<div class="dashboard-content">
            <div class="container-fluid">
                <div class="row m-auto">
                  <div class="col-md-12">
                    <div class="row m-auto">
                      <div class="p-0">
                        <h3>Evisit</h3>
                        <div class="my-1" style="box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px; border-radius: 8px;">
                          <div style="font-size:17px ;" class="p-0 py-3 d-flex col-lg-5 col-md-8 flex-wrap justify-content-between">
                            <p><b>Doctor:</b> <span>{{$doctor->name}} {{$doctor->last_name}}</span></p>
                            <p><b> NPI:</b> <span>{{$doctor->nip_number}}</span></p>
                          </div>
                        </div>

                        <div class="d-flex align-items-baseline flex-wrap justify-content-between">
                            <div class="d-flex flex-wrap justify-content-between">
                            <div>
                                <div class="input-group ">
                                    <input
                                      type="text"
                                      name="ses_date"
                                      id="ses_date"
                                      value="{{$date}}"
                                      class="form-control mb-1 me-2"
                                      aria-label="Username"
                                      aria-describedby="basic-addon1"
                                      placeholder="filter by date-range"
                                    />
                                  </div>
                            </div>
                            <div>
                                <div class="input-group">
                                  <input
                                    type="text"
                                    name="ses_id"
                                    id="ses_id"
                                    value="{{$ses_id}}"
                                    onchange="ses_search()"
                                    class="form-control mb-1"
                                    placeholder="Search by Session-ID"
                                    aria-label="Username"
                                    aria-describedby="basic-addon1"
                                  />
                                </div>
                              </div>
                            </div>
                              <div class="dropdown">
                                <button class="btn process-pay dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  Download Data
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                  <li><a class="dropdown-item" href="#" onclick="createfile('csv')">Download As CSV</a></li>
                                  <li><a class="dropdown-item" href="#" onclick="createfile('pdf')">Download As PDF</a></li>

                                </ul>
                              </div>
                        </div>
                      </div>

                      <div class="wallet-table">
                        <table class="table">
                          <thead>
                              <th scope="col">Session ID</th>
                              <th scope="col">Date</th>
                              <th scope="col">Time</th>
                              <th scope="col">Earning</th>
                          </thead>
                          <tbody>
                            @forelse($sessions as $ses)
                            <tr>
                                <td data-label="Session ID">UEV-{{$ses->session_id}}</td>
                                <td data-label="Date">{{$ses->created_at['date']}}</td>
                                <td data-label="Time">{{$ses->created_at['time']}}</td>
                                <td data-label="Earning">Rs. {{$ses->doc_fee}}</td>

                              </tr>
                              @empty
                              <tr>
                                <td colspan='4'>
                                <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No E-visit Earnings</h6>
                                </div>
                                </td>
                                </tr>
                              @endforelse
                          </tbody>
                        </table>
                        {{ $sessions->links('pagination::bootstrap-4') }}
                        <!-- <nav aria-label="..." class="float-end pe-3">
                          <ul class="pagination">
                            <li class="page-item disabled">
                              <span class="page-link">Previous</span>
                            </li>
                            <li class="page-item">
                              <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item active" aria-current="page">
                              <span class="page-link">2</span>
                            </li>
                            <li class="page-item">
                              <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                              <a class="page-link" href="#">Next</a>
                            </li>
                          </ul>
                        </nav> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
      </div>


    </div>
<input type="hidden" id="user_id" value="{{$doctor->id}}">
<form type="hidden" id="filter" action="" method="POST">
  @csrf
  <input type="hidden" id="fdate" name="date" value="">
  <input type="hidden" id="uid" name="id" value="">
  <input type="hidden" id="s_id" name="s_id" value="">
</form>
@endsection
