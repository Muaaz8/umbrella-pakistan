@extends('layouts.dashboard_Lab_editor')
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
        var doctor_id = $('#doctor_id_' + order_id).val();
        if (doctor_id == '' || doctor_id == null) {
            $('#message_error_' + order_id).text('Please Select Doctor');
        }
        else {
            $('#assignbutn').html('');
            $('#assignbutn').attr('disabled',true);
            $('#assignbutn').html('<i class="fa fa-spinner fa-spin"></i>Wait...')
            $.ajax({
                type: "POST",
                url: "{{URL('/assignLabForApprovalToDoctor')}}",
                data: {
                    doctor_id: doctor_id,
                    order_id: order_id
                },
                success: function (res) {
                    if (res == 'ok') {
                        location.href = '/unassigned/lab/orders';
                    }
                }
            });
        }
    }
</script>
@endsection
@section('content')
    <div class="dashboard-content">
            <div class="container">
                <div class="row m-auto">
                  <div class="col-md-12">
                    <div class="row m-auto">
                      <div class="d-flex align-items-baseline p-0">
                        <h3>Online Unassigned Orders</h3>
                        <div class="col-md-4 ms-auto p-0">
                          <div class="input-group">
                            <input
                              type="text"
                              class="form-control"
                              placeholder="Search what you are looking for"
                              aria-label="Username"
                              aria-describedby="basic-addon1"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="wallet-table">
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
                            <tbody>
                            @forelse($pending_requisitions as $order)
                            @php $index = 1; @endphp
                              <tr>
                                  <th scope="row">{{ $order->order_id }}</th>
                                  <td>{{ $order->user_state }}</td>
                                  <td>{{ $order->date }}</td>
                                  <td>
                                    @foreach($order->test_name as $data)
                                    @if($data->order_id==$order->order_id)
                                    <strong>{{$index}}) </strong>{{ $data->TEST_NAME }}<br>
                                    @php $index++; @endphp
                                    @endif
                                    @endforeach 
                                  </td>
                                  <td>{{ $order->decline_status }}</td>

                                  <td>
                                    <select class="form-select w-75 m-auto" id="doctor_id_{{ $order->order_id }}" aria-label="Default select example">
                                      <option selected>Choose</option>
                                      @foreach($order->doctors as $doctor)
                                      <option value="{{ $doctor->user_id }}">Dr.{{ucwords($doctor->name.'
                                        '.$doctor->last_name)}}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                  <td>
                                    <button class="btn orders-view-btn" id='assignbutn' onclick="assignLabToDoctor({{ $order->order_id }})">Done</button>
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