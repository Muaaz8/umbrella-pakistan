
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
<script>
document.addEventListener("wheel", function(event){
    if(document.activeElement.type === "number"){
        document.activeElement.blur();
    }
});
</script>


@endsection

@section('content')
<div class="dashboard-content">
    <div class="container-fluid">
      <div class="row m-auto">
        <div class="nav-borders">
          <h4>Doctor Profile</h4>
        </div>
      </div>
      <hr class="mt-0 mb-4" />
      <div class="row m-auto">
        <div class="col-md-12">
          <div class="card" style="width: 100%">
            <form name="send_contract" action="{{route('admin_send_contract',$doctor->id)}}" method="get">
                @csrf
                <input type="hidden" name="doctor_id" value="{{$doctor->id}}">
            <div class="card-header d-flex justify-content-between align-items-center">
              ABOUT DOCTOR
              @if ($doctor->id_card_front != "")
                @if ($doctor->id_card_back != "")
                    @if ($doctor->email_verify->status != 0)
                        <button type="submit" class="contract_btn">Send Contract</button>
                    @else
                      <p>Email Not Verified Yet</p>
                    @endif
                @else
                    <p>Driver License Not uploaded yet</p>
                @endif
              @else
                    <p>Driver License Not uploaded yet</p>
              @endif
            </div>
            <div class="row m-auto">
              <ul class="col-md-6 list-group list-group-flush">
                <li class="list-group-item">
                  <b> Name : </b> Dr. {{ ucfirst($doctor->name)." ".ucfirst($doctor->last_name) }}
                </li>
                <li class="list-group-item">
                  <b> Date Of Birth : </b> {{ $doctor->date_of_birth }}
                </li>
                <li class="list-group-item">
                  <b> Email : </b> {{ $doctor->email }}
                </li>
                <li class="list-group-item">
                  <b> Phone : </b> {{ $doctor->phone_number }}
                </li>
                <li class="list-group-item">
                  <b> Office Address : </b> {{ $doctor->office_address }}
                </li>
                <li class="list-group-item">
                  <b> Signature : </b>
                  <img
                    src="{{$doctor->signature}}"
                    alt=""
                    class="w-50"
                  />
                </li>
              </ul>
              <ul class="col-md-6 list-group list-group-flush">
                <li class="list-group-item"><b> PMDC : </b> {{ $doctor->nip_number }}</li>
                <li class="list-group-item">
                  <b> Specalization : </b> {{ucwords($doctor->specialization)}}
                </li>
                <li class="list-group-item">
                  <b> National ID Card : </b>
                   <span class="float-end">
                    {{-- {{ dd($doctor->id_card_front) }} --}}
                    @if ($doctor->id_card_front != "")
                        <a href="{{ \App\Helper::get_files_url($doctor->id_card_front) }}" target="_blank"><button type="button" class="contract_btn">Front</button></a>
                        <a href="{{ \App\Helper::get_files_url($doctor->id_card_back) }}" target="_blank"><button type="button" class="contract_btn">Back</button></a>
                    @else
                        <p>No Driving License Uploaded</p>
                    @endif
                    </span>
                </li>
                <li class="list-group-item">
                    <b> Contract Date : </b> <input type="date" name="date" min="<?= date('Y-m-d') ?>" class="float-end" required/>
                </li>
                <li class="list-group-item">
                    <b> Percentage per Session : </b>
                    <input type="number" name="session_percentage" max="99" min="1" placeholder="%" onKeyPress="if(this.value.length==2)return false;" class="float-end" required />
                </li>
              </ul>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
