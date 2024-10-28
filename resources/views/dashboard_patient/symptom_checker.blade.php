@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Symptoms Checker</title>
@endsection

@section('top_import_file')
<style>
    .dropdown-select {
      display: none;
    }

    .address_phone_card {
      text-align: left;
      font-size: 13px;
      box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
      /* padding: 8px; */
      border-radius: 10px;
      transition: all 300ms linear;
      cursor: pointer;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .address_phone_card:hover {
      transform: scale(1.07);

    }

    .find_location_btn {
      cursor: pointer;
    }

    .find_location_btn:hover {
      color: rgb(255, 53, 53);
    }

    .main_cards_scroll {
      overflow-y: auto;
      max-height: 366px;
    }

    .buttons_Main_div {
      display: flex;
      gap: 0px;
    }

    .buttons_Main_div button {
      background-color: #08295a;
      width: 100%;
      border: 1px solid #fff;
      color: #fff;
      padding: 7px;
      font-size: 15px;
      border-radius: 0px 0px 0px 10px;

    }

    .buttons_Main_div .second_btn {
      border-radius: 0px 0px 10px 0px;

    }

    .services_ul li {
      list-style: disc;
      margin-left: 30px;
    }

    .heading_underL {
      color: #08295a;
      text-decoration: 2px underline #08295a;
    }

    .left_servi_main {
      box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
      padding: 15px 8px;
      border-radius: 5px;
    }

    /* -----symptoms-Checker-Css-- */
    #heading {
      text-transform: uppercase;
      color: #673AB7;
      font-weight: normal
    }

    #msform {
      text-align: center;
      position: relative;
      margin-top: 20px
    }

    #msform fieldset {
      background: white;
      border: 0 none;
      border-radius: 0.5rem;
      box-sizing: border-box;
      width: 100%;
      margin: 0;
      padding-bottom: 20px;
      position: relative
    }

    .form-card {
      text-align: left
    }

    #msform fieldset:not(:first-of-type) {
      display: none
    }

    #msform .custom_input {
      padding: 8px 15px 8px 15px;
      border: 1px solid #ccc;
      border-radius: 0px;
      margin-bottom: 14px;
      margin-top: 2px;
      width: 100%;
      box-sizing: border-box;
      font-family: montserrat;
      color: #2C3E50;
      background-color: #ECEFF1;
      font-size: 16px;
      letter-spacing: 1px
    }

    #msform .custom_input:focus {
      -moz-box-shadow: none !important;
      -webkit-box-shadow: none !important;
      box-shadow: none !important;
      border: 1px solid #673AB7;
      outline-width: 0
    }

    #msform .action-button {
      width: 100px;
      background: #673AB7;
      font-weight: bold;
      color: white;
      border: 0 none;
      border-radius: 0px;
      cursor: pointer;
      padding: 10px 5px;
      margin: 10px 0px 10px 5px;
      float: right
    }

    #msform .action-button:hover,
    #msform .action-button:focus {
      background-color: #311B92
    }

    #msform .action-button-previous {
      width: 100px;
      background: #616161;
      font-weight: bold;
      color: white;
      border: 0 none;
      border-radius: 0px;
      cursor: pointer;
      padding: 10px 5px;
      margin: 10px 5px 10px 0px;
      float: right
    }

    #msform .action-button-previous:hover,
    #msform .action-button-previous:focus {
      background-color: #000000
    }

    .card {
      z-index: 0;
      border: none;
      position: relative
    }

    .fs-title {
      font-size: 25px;
      color: #673AB7;
      margin-bottom: 15px;
      font-weight: normal;
      text-align: left
    }

    .purple-text {
      color: #673AB7;
      font-weight: normal
    }

    .steps {
      font-size: 25px;
      color: gray;
      margin-bottom: 10px;
      font-weight: normal;
      text-align: right
    }

    .fieldlabels {
      color: gray;
      text-align: left
    }

    #progressbar {
      margin-bottom: 30px;
      overflow: hidden;
      color: lightgrey
    }

    #progressbar .active {
      color: #673AB7
    }

    #progressbar li {
      list-style-type: none;
      font-size: 15px;
      width: 25%;
      float: left;
      position: relative;
      font-weight: 400
    }

    #progressbar #account:before {
      font-family: FontAwesome;
      content: "\f13e"
    }

    #progressbar #personal:before {
      font-family: FontAwesome;
      content: "\f007"
    }

    #progressbar #payment:before {
      font-family: FontAwesome;
      content: "\f030"
    }

    #progressbar #confirm:before {
      font-family: FontAwesome;
      content: "\f00c"
    }

    #progressbar li:before {
      width: 50px;
      height: 50px;
      line-height: 45px;
      display: block;
      font-size: 20px;
      color: #ffffff;
      background: lightgray;
      border-radius: 50%;
      margin: 0 auto 10px auto;
      padding: 2px
    }

    #progressbar li:after {
      content: '';
      width: 100%;
      height: 2px;
      background: lightgray;
      position: absolute;
      left: 0;
      top: 25px;
      z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
      background: #673AB7
    }

    .progress {
      height: 20px
    }

    .progress-bar {
      background-color: #673AB7
    }

    .fit-image {
      width: 100%;
      object-fit: cover
    }

    .right__user {
      display: flex;
      justify-content: end;
      gap: 14px;
      align-items: center;
    }

    .right__user_img {
      border-radius: 15px;
      width: 30px;
      height: 30px;
    }

    .chat__main__ {
      max-height: 180px;
      overflow-y: auto;
    }

    .message__div {
      display: flex;
      align-items: center;
      gap: 7px;
      margin-top: 10px;
    }

    .send_icon:hover {
      transform: scale(1.3);
      transition: 150ms ease-in;
      color: #08295a;
      font-weight: 600;
      cursor: pointer;
    }

    .left_p {
      background-color: #cecece;
      padding: 10px 19px;
      border-radius: 10px 10px 10px 0px;
      color: #000;
      text-align: left;
      max-width: 300px;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .right_p {
      background-color: #08295a;
      padding: 10px 19px;
      border-radius: 10px 10px 0px 10px;
      color: #fff;
      max-width: 300px;
    }
    .btn_finish{
      float: right;
      margin-top: 10px;
      padding: 10px;
      background: #08295a;
      border: 0px;
      border-radius: 5px;
      color: #ffff;
      width: 100px;
    }
    .model_body{
        padding: 25px;
    }
  </style>
@endsection


@section('bottom_import_file')
<script src="{{asset('assets\js\searching.js')}}"></script>
<script>
    var token = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function () {
        $('.view_btn').click(function (e) {
            e.preventDefault();
            var id = $(this).data('id');
                $.ajax({
                type: "post",
                url: "get_symptom",
                data:{
                    _token:token,
                    id:id
                },
                success: function (response) {
                    if(response != null){
                        html= '<div class="text-start conclusions">'+
                                '<i class="conclusion_loader fa fa-spinner fa-spin d-none d-flex justify-content-center"></i>'+
                                '<h3 class="CEva_heading">Clinical Evaluation</h3>'+
                                '<p class="CEva" style="text-align: justify;">'+response.clinical_evaluation+'</p>'+
                                '<h3 class="HRep_heading">Hypothesis Report</h3>'+
                                '<p class="HRep" style="text-align: justify;">'+response.hypothesis_report+'</p>'+
                                '<h3 class="INote_heading">Intake Notes</h3>'+
                                '<p class="INote" style="text-align: justify;">'+response.intake_notes+'</p>'+
                                '<h3 class="RAT_heading">Referrals And Tests</h3>'+
                                '<p class="RAT" style="text-align: justify;">'+response.referrals_and_tests+'</p>'+
                            '</div>';
                        $('.model_body').html(html);
                        $('#registar_open').modal('show');
                    }
                }
            });
        });
        $('.btn-close').click(function (e) {
            e.preventDefault();
            $('#registar_open').modal('hide');
        });
    });
</script>
@endsection

@section('content')
<div class="dashboard-content">
    <div class="container-fluid">
      <div class="row m-auto">
        <div class="col-md-12">
          <div class="row m-auto">
            <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
              <h3>Symptoms Checker History</h3>
              <div class="p-0">
                <div class="input-group">
                  <input
                    type="text"
                    id="search"
                    class="form-control"
                    placeholder="Search"
                    aria-label="Username"
                    aria-describedby="basic-addon1"
                  />
                </div>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                @include('flash::message')
            </div>
            <div class="wallet-table table-responsive">
              <table class="table" id="table">
                <thead>
                    <!-- <th scope="col">S.No</th> -->
                    <th scope="col">S.no</th>
                    <th scope="col">Clinical evaluation</th>
                    <th scope="col">Hypothesis report</th>
                    <th scope="col">Intake notes</th>
                    <th scope="col">Referrals and tests</th>
                    <th scope="col">Checked at</th>
                    <th scope="col">Action</th>
                </thead>
                <tbody>
                @php
                    $counter = 1;
                @endphp
                @forelse ($symptomsChecker as $symptoms)
                <tr>
                     <td data-label="S.No" scope="row">{{ $counter }}</td>
                    <td data-label="Order ID" type="hidden">{{ \Str::limit($symptoms->clinical_evaluation,50,'...') }}</td>
                    <td data-label="Order State">{{ \Str::limit($symptoms->clinical_evaluation,50,'...') }}</td>
                    <td data-label="Date">{{ \Str::limit($symptoms->intake_notes,50,'...') }}</td>
                    <td data-label="Time">{{ \Str::limit($symptoms->referrals_and_tests,50,'...') }}</td>
                    <td data-label="Time">{{ $symptoms->created_at->format('m-d-Y') }}</td>
                    <td data-label="Action">
                      <button type="button" data-id="{{ $symptoms->id }}" class="view_btn btn btn-raised process-pay btn-sm waves-effect">View</button>
                    </td>
                </tr>
                @php
                    $counter++;
                @endphp
                @empty
                <tr>
                    <td colspan='6'>
                    <div class="m-auto text-center for-empty-div">
                        <h6> No Symptoms added yet!</h6>
                    </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
              </table>

            </div>
            {{ $symptomsChecker->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="registar_open" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Automated Symptoms Checker</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>
          <div class="">
            <div class="row justify-content-center p-0 m-0">
              <div class=" text-center p-0">
                <div class="card px-0 ">
                    <div class="model_body">

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
@endsection
