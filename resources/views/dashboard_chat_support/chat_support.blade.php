@extends('layouts.dashboard_chat_support')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('page_title')
    <title>Chat Admin</title>
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
        window.location.href='/chat/support';
      }
      else
      {
        $('#bodies').empty();
        $('#pag').html('');
        $.each (array, function (key, arr) {
          if((arr.username != null && arr.username.toString().match(val)) || (arr.token != null && arr.token.match(val))
          || (arr.token_status != null && arr.token_status.toString().match(val)))
          {
            $('#bodies').append('<tr id="body_'+arr.id+'"></tr>');
            $('#body_'+arr.id).append('<td data-label="ID">'+arr.username+'</td>'
            +'<td data-label="Name">'+arr.token+'</td>'
            +'<td data-label="Name">'+arr.token_status+'</td>'
            +'<td data-label="Action"><button class="btn option-view-btn" type="button">view</button></td>'
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
                        <div class="d-flex align-items-end p-0">
                            <div>
                              <h3>Solved Problems</h3>
                            </div>
     
                          </div>
                          <div class="d-flex justify-content-between p-0">
                          <div class="d-flex w-25">
                            <input type="text" id="search" class="form-control mb-1" placeholder="Search">
                            <button type="button" id="search_btn" onclick="search({{$data}})" class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                        </div>
                        <div>
                            <!-- <button type="button" class="btn process-pay" data-bs-toggle="modal" data-bs-target="#add_online_test">Add Online Labtest</button> -->
                        </div>
                        </div>
    
                      <div class="wallet-table">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Token No</th>
                                <th scope="col">Token Status</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody id="bodies">
                              @forelse($solved as $sol)
                              <tr>
                                <td data-label="Username">{{$sol->username}}</td>
                                <td data-label="Token No">{{$sol->token}}</td>
                                <td data-label="Token Status">{{$sol->token_status}}</td>
                                <td data-label="Action">
                                    <!-- <div class="dropdown"> -->
                                    <button class="btn option-view-btn" onclick="window.location.href='/view/chat/{{$sol->id}}'" type="button">
                                      View
                                    </button>
                                    <!-- <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                      <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#view_online_labtest">View</a></li>
                                      <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_online_test">Edit</a></li>
                                      <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_online_labtest">Delete</a></li>
                                    </ul> -->
                                  <!-- </div> -->
                                </td>
                              </tr>
                              @empty
                              <tr>
                                  <td colspan='4'>
                                  <div class="m-auto text-center for-empty-div">
                                      <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                      <h6> No Solved Problem</h6>
                                  </div>
                                  </td>
                              </tr>
                              @endforelse
                          </table>
                          <div id="pag" class="row d-flex justify-content-center">
                              <div class="paginateCounter">
                                  {{ $solved->links('pagination::bootstrap-4') }}
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
@endsection