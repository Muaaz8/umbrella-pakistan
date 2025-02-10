@extends('layouts.dashboard_Lab_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
@endsection

@section('page_title')
    <title>Orders</title>
@endsection

@section('top_import_file')
<script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>
<script src="./assets/js/custom.js"></script>
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
        window.location.href='/lab/orders';
      }
      else
      {
        $('#bodies').empty();
        $('#pag').html('');
        $.each (array, function (key, arr) {
          if((arr.order_id != null && arr.order_id.toString().match(val)) || (arr.order_state != null && arr.order_state.toString().match(val))
            || (arr.order_status != null && arr.order_status.toString().match(val)) || (arr.created_at.date != null && arr.created_at.date.toString().match(val)))
          {
            $('#bodies').append('<tr id="body_'+arr.order_id+'"></tr>');
            $('#body_'+arr.order_id).append('<td data-label="Order ID">'+arr.order_id+'</td>'
            +'<td data-label="Order Status">'+arr.order_status+'</td>'
            +'<td data-label="Date">'+arr.created_at.date+'</td>'
            +'<td data-label="Time">'+arr.created_at.time+'</td>'
            +'<td data-label="Action">'
            +'<a href="{{ url("/lab/order/'+arr.id+'") }}">'
            +'<button class="orders-view-btn">View</button></a></td>'
            );
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
                        <h3>Orders</h3>
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
                      <div class="wallet-table">
                        <table class="table">
                          <thead>
                            <th scope="col">Order ID</th>
                            {{-- <th scope="col">Order State</th> --}}
                            <th scope="col">Order Status</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Action</th>
                          </thead>
                          <tbody id="bodies">
                            @forelse($tblOrders as $order)
                            <tr>
                                <td data-label="Order ID">{{$order->order_id}}</td>
                                {{-- <td data-label="Order State">{{$order->order_state}}</td> --}}
                                <td data-label="Order Status">{{$order->order_status}}</td>
                                <td data-label="Date">{{$order->created_at['date']}}</td>
                                <td data-label="Time">{{$order->created_at['time']}}</td>
                                <td data-label="Action">
                                    <a href="{{ route('dash_lab_order', ['id'=>$order->id]) }}">
                                        <button class="orders-view-btn">View</button>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan='6'>
                                <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No Orders</h6>
                                </div>
                                </td>
                            </tr>
                            @endforelse
                          </tbody>
                        </table>
                        <div id="pag">
                        {{ $tblOrders->links('pagination::bootstrap-4') }}
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



    <!-- Option 1: Bootstrap Bundle with Popper -->

