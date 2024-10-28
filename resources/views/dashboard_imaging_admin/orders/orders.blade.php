@extends('layouts.dashboard_imaging_admin')
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
        window.location.href='/imaging/lab/orders';
      }
      else
      {
        $('#bodies').empty();
        $('#pag').html('');
        $.each (array, function (key, arr) {
          if((arr.order_id != null && arr.order_id.match(val)) || (arr.order_state != null && arr.order_state.match(val))
            || (arr.status != null && arr.status.toString().match(val)) || (arr.created_at.date != null && arr.created_at.date.toString().match(val)))
          {
            $('#bodies').append('<tr id="body_'+arr.id+'"></tr>');
            $('#body_'+arr.id).append('<td>'+arr.order_id+'</td>'
            +'<td>'+arr.order_state+'</td>'
            +'<td>'+arr.status+'</td>'
            +'<td>'+arr.created_at.date+'</td>'
            +'<td>'+arr.created_at.time+'</td>'
            +'<td><a href="/imaging/lab/order/'+arr.id+'"><button class="orders-view-btn">View</button></a></td>'
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
                              placeholder="Search By Order # ID"
                              aria-label="Username"
                              aria-describedby="basic-addon1"
                            />
                            <button type="button" id="search_btn" onclick="search({{$data}})" class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                      <div class="wallet-table">
                        <table class="table" id="table">
                          <thead>
                            <th scope="col">Order ID</th>
                            <th scope="col">Order State</th>
                            <th scope="col">Order Status</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Action</th>
                          </thead>
                          <tbody id="bodies">
                            @forelse($tblOrders as $order)
                            <tr>
                                <td>{{$order->order_id}}</td>
                                <td>{{$order->order_state}}</td>
                                <td>{{$order->order_status}}</td>
                                <td>{{explode(" ",$order->created_at)[0]}}</td>
                                <td>{{explode(" ",$order->created_at)[1]." ".explode(" ",$order->created_at)[2]}}</td>
                                <td><button onclick="window.location.href='/imaging/lab/order/{{$order->id}}'" class="orders-view-btn">View</button></td>
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

