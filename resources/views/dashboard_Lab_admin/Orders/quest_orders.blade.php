@extends('layouts.dashboard_Lab_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>CHCC - Quest Orders</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script>
  var input = document.getElementById("search");
  input.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
      document.getElementById("search_btn").click();
    }
  });
  function search(array)
    {
      var val = $('#search').val();
      if(val=='')
      {
        window.location.href='/quest/orders';
      }
      else
      {
        $('#bodies').empty();
        $.each (array, function (key, arr) {
          if((arr.order_id != null && arr.order_id.toString().match(val)) || (arr.quest_patient_id != null && arr.quest_patient_id.toString().match(val))
            || (arr.ref_physician_id != null && arr.ref_physician_id.toString().match(val)) || (arr.upin != null && arr.upin.toString().match(val))
            || (arr.npi != null && arr.npi.toString().match(val)) || (arr.created_at.date != null && arr.created_at.date.toString().match(val)))
          {
            var names = '';
            $.each(JSON.parse(arr.names), function (keys, namess) {
              names += namess.testName;
            });
            $('#bodies').append('<tr id="body_'+arr.order_id+'"></tr>');
            $('#body_'+arr.order_id).append('<td data-label="Order ID">'+arr.order_id+'</td>'
            +'<td data-label="Quest Patient ID">'+arr.quest_patient_id+'</td>'
            +'<td data-label="Referred Physician ID">'+arr.ref_physician_id+'</td>'
            +'<td data-label="UPIN">'+arr.upin+'</td>'
            +'<td data-label="NPI">'+arr.npi+'</td>'
            +'<td data-label="Test Names">'+names+'</td>'
            +'<td data-label="Date">'+arr.created_at.date+'</td>'
            +'<td data-label="Time">'+arr.created_at.time+'</td>'
            );
            if(arr.requisition_file != null || arr.requisition_file != '')
            {
              $('#body_'+arr.order_id).append('<td id="file" data-label="Action"><a target="_blank"'
              +'href="{{\App\Helper::get_files_url('+arr.requisition_file+') }}">'
              +'<button class="orders-view-btn">Requisition</button></a></td>');
            }
            else
            {
              $('#body_'+arr.order_id).append('<td id="file" data-label="Action">Requisition Not Generated</td>');
            }
          }
        });
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
              <div class="d-flex justify-content-between flex-wrap align-items-baseline p-0">
                <h3>Quest Orders</h3>
                <div class="col-md-4 p-0">
                  <div class="input-group">
                    <input
                      type="text"
                      id="search"
                      class="form-control"
                      placeholder="Search what you are looking for"
                      aria-label="Username"
                      aria-describedby="basic-addon1"
                    />
                    <button type="button" id="search_btn" onclick="search({{$req}})" class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                  </div>
                </div>
              </div>
              <div class="wallet-table table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Order ID</th>
                      <th scope="col">Quest Patient ID</th>
                      <th scope="col">Referred Physician ID</th>
                      <th scope="col">UPIN</th>
                      <th scope="col">NPI</th>
                      <th scope="col">Test Names</th>
                      <th scope="col">Date</th>
                      <th scope="col">Time</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody id="bodies">
                    @forelse ($requisitions as $requisition)
                    <tr>
                        <td data-label="Order ID">{{ $requisition->order_id }}</td>
                        <td data-label="Quest Patient ID">{{ $requisition->quest_patient_id }}</td>
                        <td data-label="Referred Physician ID">{{ $requisition->ref_physician_id }}</td>
                        <td data-label="UPIN">{{ $requisition->upin }}</td>
                        <td data-label="NPI">{{ $requisition->npi }}</td>
                        <td data-label="Test Names">@foreach($requisition->names as $names)
                            {{$names->testName }}</br>
                            @endforeach</td>
                        <td data-label="Date">{{ $requisition->created_at['date'] }}</td>
                        <td data-label="Time">{{ $requisition->created_at['time'] }}</td>
                        {{-- <td data-label="Time">{{ $requisition->created_at }}</td> --}}
                        @if($requisition->requisition_file != null)
                        <td data-label="Action"><a target="_blank"
                            href="{{\App\Helper::get_files_url($requisition->requisition_file) }}">
                            <button class="orders-view-btn">Requisition</button></a>
                        </td>
                        @else
                        <td>Requisition Not Generated</td>
                        @endif
                      </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="m-auto text-center for-empty-div">
                                <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                <h6>No Failed Quest Lab To Show</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
                <div class="row d-flex justify-content-center">
                    <div class="paginateCounter">
                        {{ $requisitions->links('pagination::bootstrap-4') }}
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>

@endsection
