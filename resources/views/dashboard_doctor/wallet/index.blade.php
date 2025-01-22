@extends('layouts.dashboard_doctor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Doctor Wallet</title>
@endsection

@section('top_import_file')
<link
          href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/datedropper.css"
          rel="stylesheet"
        />
        <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/datedropper.js"></script>

@endsection


@section('bottom_import_file')
<script>
    function set_attr(msg)
    {
        if(msg == 'from')
        {
            $('#to_date').attr('min',$('#from_date').val());
        }
        else if(msg == 'to')
        {
            $('#from_date').attr('max',$('#to_date').val());
        }
    }
</script>
@endsection

@section('content')
{{-- {{ dd($doctorHistory) }} --}}
        <div class="dashboard-content">
          <div class="container-fluid">
            <div class="row m-auto">
              <div class="col-md-12">
                  <div class="row mb-3">

                      <div class="col-md-4 mb-2">
                          <div class="dashboard-small-card-wrap">
                              <div class="d-flex dashboard-small-card-inner">
                                <i class="fa-solid fa-money-check-dollar"></i>
                                  <div>
                                      <h6>Current Balance</h6>
                                      <p>$ @convert($totalEarning+$lab_approval_earning)</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-4 mb-2">
                          <div class="dashboard-small-card-wrap">
                              <div class="d-flex dashboard-small-card-inner">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                                  <div>
                                      <h6>This Month Earning</h6>
                                      <p>$ @convert($totalEarningCurrentMonth)</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-4 mb-2">
                          <div class="dashboard-small-card-wrap">
                              <div class="d-flex dashboard-small-card-inner">
                                <i class="fa-solid fa-sack-dollar"></i>
                                  <div>
                                      <h6>Today Earning</h6>
                                      <p>$ @convert($totalEarningCurrentDay)</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-4 mb-2">
                        <div class="dashboard-small-card-wrap">
                            <div class="d-flex dashboard-small-card-inner">
                              <i class="fa-solid fa-sack-dollar"></i>
                                <div>
                                    <h6>Earning By Lab Approval</h6>
                                    <p>$ @convert($lab_approval_earning)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
            <form method="POST" action="{{route('doctor_wallet')}}">
                @csrf
                <div class="wallet-date-picker-wrap mt-100">
                    <div class="card">
                        <div class="row">
                            <div class="col-md-5 mb-2"> <label>From</label> <input type="date" id="from_date" name="from_date" max="<?= date('Y-m-d'); ?>" onchange="set_attr('from')" class="form-control rounded-pill"> </div>
                            <div class="col-md-5 mb-2"> <label>To</label> <input type="date" id="to_date" name="to_date" max="<?= date('Y-m-d'); ?>" onchange="set_attr('to')" class="form-control rounded-pill"> </div>
                            <div class="col-md-2 mb-2 mt-auto"><button type="submit" class="btn btn-primary pro-button w-100">Search<i class="fa-solid fa-magnifying-glass ms-1"></i></button> </div>
                        </div>
                    </div>
                </div>
            </form>



<div class="row m-auto">
  <div class="wallet-table table-responsive">
    <h5 class="py-2 px-4 text">Earning By Sessions</h5>

    <table id="example" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Session Id</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Patient Name</th>
            <th>Earning</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $counter=1;
        foreach($doctorHistory as $d)
        {

        ?>
        <tr>
        <td data-label="Session Id">UEV-{{$d->session_id}}</td>
        <td data-label="Date">{{$d->date}}</td>
        <td data-label="Start Time">{{$d->start_time}}</td>
        <td data-label="End Time">{{$d->end_time}}</td>
        <td data-label="Patient Name">{{$d->name.' '.$d->last_name}}</td>
        <td data-label="Earning">$ @convert($d->price)</td>
        </tr>
        <?php
        $counter+=1;
        }
        ?>
    </tbody>
</table>

</div>
</div>

<div class="row d-flex justify-content-center">
    <div class="paginateCounter">
        {{ $doctorHistory->links('pagination::bootstrap-4') }}
    </div>
</div>


              </div>
          </div>
          </div>
        </div>
      </div>
    </div>
@endsection
