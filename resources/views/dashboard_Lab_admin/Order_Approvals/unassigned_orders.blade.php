@extends('layouts.dashboard_Lab_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title')
    <title>Unassigned Orders</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script>
    @php header("Access-Control-Allow-Origin: *"); @endphp
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function assignLabToDoctor(order_id) {
        var doctor_id = $('#doctor_id_'+order_id).val();
        if (doctor_id == '' || doctor_id == null) {
            $('#message_error_' + order_id).text('Please Select Doctor');
        }
        else {
            $.ajax({
                type: "POST",
                url: "{{URL('/assignLabForApprovalToDoctor')}}",
                data: {
                    doctor_id: doctor_id,
                    order_id: order_id
                },
                beforeSend: function () {
                    $('#assignbutn_'+order_id).html('');
                    $('#assignbutn_'+order_id).attr('disabled',true);
                    $('#assignbutn_'+order_id).html('<i class="fa fa-spinner fa-spin"></i>Wait...')
                },
                success: function (res) {
                    if (res == 'ok') {
                        location.href = '/unassigned/lab/orders';
                    }
                }
            });
        }
    }
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
        window.location.href='/unassigned/lab/orders';
      }
      else
      {
        $('#bodies').empty();
        $.each (array, function (key, arr) {
          var i = 0;
          if((arr.order_id != null && arr.order_id.toString().match(val)) || (arr.user_state != null && arr.user_state.toString().match(val))
            || (arr.decline_status != null && arr.decline_status.toString().match(val)) || (arr.date != null && arr.date.toString().match(val)))
          {
            $('#bodies').append('<tr id="body_'+arr.order_id+'"></tr>');
            $('#body_'+arr.order_id).append('<td data-label="Order ID">'+arr.order_id+'</td>'
            +'<td data-label="Order State">'+arr.user_state+'</td>'
            +'<td data-label="Date">'+arr.date+'</td>'
            +'<td data-label="Lab Products" id="names_'+arr.order_id+'"></td>'
            +'<td data-label="Status">'+arr.decline_status+'</td>'
            +'<td data-label="State Doctors">'
            +'<select class="form-select w-75 m-auto" id="doctor_id_'+arr.order_id+'" '
            +'aria-label="Default select example" required><option value="" selected>Choose</option></td>'
            +'<small class="text-danger" id="message_error_'+arr.order_id+'"></small></select>'
            +'<td data-label="">'
            +'<button class="btn orders-view-btn" id="assignbutn_'+arr.order_id+'" '
            +'onclick="assignLabToDoctor('+arr.order_id+')">Done</button></td>'
            );
            $.each (JSON.parse(arr.test_name), function (key, ar) {
              i+=1;
              $('#names_'+arr.order_id).append('<strong>'+i+') </strong>'+ar.TEST_NAME+'<br>');
            });
            $.each (JSON.parse(arr.doctors), function (key, ar) {
              $('#doctor_id_'+arr.order_id).append('<option value="'+ar.user_id+'">'
              +'Dr.'+ar.name+' '+ar.last_name+'</option>');
            });
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
                        <h3>Online Unassigned Orders</h3>
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
                            <button type="button" id="search_btn" onclick="search({{$data}})" class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                      <div class="wallet-table table-responsive">
                        <table class="table">
                            <thead>
                                <th scope="col">Order ID</th>
                                <th scope="col">Order State</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">Lab Products</th>
                                <th scope="col">Status</th>
                                <th scope="col">Select State Doctors</th>
                                <th scope="col">Action</th>
                            </thead>
                            <tbody id="bodies">
                            @forelse($pending_requisitions as $order)
                            @php $index = 1; @endphp
                              <tr>
                                  <td data-label="Order ID">{{ $order->order_id }}</td>
                                  <td data-label="Order State">{{ $order->user_state }}</td>
                                  <td data-label="Order Date">{{ $order->date }}</td>
                                  <td class="d-block" data-label="Lab Products">
                                    @foreach($order->test_name as $data)
                                    @if($data->order_id==$order->order_id)
                                    <strong>{{$index}}) </strong> {{ $data->TEST_NAME }}<br>
                                    @php $index++; @endphp
                                    @endif
                                    @endforeach
                                  </td>
                                  <td data-label="Status">{{ $order->decline_status }}</td>

                                  <td data-label="State Doctors">
                                    <select class="form-select w-75 m-auto" id="doctor_id_{{ $order->order_id }}" aria-label="Default select example" required>
                                      <option value="" selected>Choose</option>
                                      @foreach($order->doctors as $doctor)
                                      <option value="{{ $doctor->user_id }}">Dr.{{ucwords($doctor->name.'
                                        '.$doctor->last_name)}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger" id="message_error_{{ $order->order_id }}"></small>
                                </td>
                                  <td data-label="Action">
                                    <button class="btn orders-view-btn" id='assignbutn_{{$order->order_id}}' onclick="assignLabToDoctor({{ $order->order_id }})">Done</button>
                                  </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan='7'>
                                    <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6> No Unassigned Orders</h6>
                                    </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                          </table>
                          <div class="row d-flex justify-content-center">
                                <div class="paginateCounter">
                                    {{ $pending_requisitions->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
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
@endsection
