@extends('layouts.dashboard_finance_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('page_title')
    <title>Doctor Reports</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script type="text/javascript">
<?php header("Access-Control-Allow-Origin: *");?>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

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
        window.location.href='/doctors/finance/reports';
      }
      else
      {
        $('#bodies').empty();
        $.each (array, function (key, arr) {
          var name = arr.name+' '+arr.last_name;
          if((arr.nip_number != null && arr.nip_number.toString().match(val)) || (arr.name != null && arr.name.match(val))
            || (arr.last_name != null && arr.last_name.toString().match(val)) || (arr.payable != null && arr.payable.toString().match(val))
            || (name != null && name.toString().match(val)))
          {
            $('#bodies').append('<tr id="body_'+arr.id+'"></tr>');
            $('#body_'+arr.id).append('<td data-label="NPI">'+arr.nip_number+'</td>'
            +'<td data-label="Name">'+arr.name+' '+arr.last_name+'</td>'
            +'<td data-label="Payable">$'+arr.payable+'</td>'
            +'<td data-label="Action"><div class="dropdown">'
            +'<button class="btn option-view-btn dropdown-toggle" type="button"'
            +' id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">OPTIONS</button>'
            +'<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">'
            +'<li><a class="dropdown-item" href="/doctors/online/lab/'+arr.id+'">Online Labs Approved</a></li>'
            +'<li><a class="dropdown-item" href="/doctors/evisit/'+arr.id+'">E-visit</a></li>'
            +'<li><a class="dropdown-item" href="/doctors/payable/'+arr.id+'">Payable Amounts</a></li>'
            +'<li><a class="dropdown-item" href="/doctors/paid/'+arr.id+'">Paid Amounts</a></li></ul></div></td>'
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
                        <h3>Doctor Reports</h3>
                        <div class="col-md-4 p-0">
                          <div class="input-group">
                            <input
                              type="text"
                              id="search"
                              class="form-control"
                              id="search"
                              placeholder="Search"
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
                              <th scope="col">NPI</th>
                              <th scope="col">Name</th>
                              <th scope="col">Payable</th>
                              <th scope="col">Actions</th>
                          </thead>
                          <tbody id="bodies">
                            @forelse($doctors as $doc)
                            <tr>
                                <td data-label="NPI">{{$doc->nip_number}}</td>
                                <td data-label="Name">{{$doc->name}} {{$doc->last_name}}</td>
                                <td data-label="Payable">${{$doc->payable}}</td>
                                <td data-label="Actions">
                                    <div class="dropdown">
                                    <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                      OPTIONS
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                      
                                      <li><a class="dropdown-item" href="/doctors/online/lab/{{$doc->id}}">Online Labs Approved</a></li>
                                      <li><a class="dropdown-item" href="/doctors/evisit/{{$doc->id}}">E-visit</a></li>
                                      <li><a class="dropdown-item" href="/doctors/payable/{{$doc->id}}">Payable Amounts</a></li>
                                      <li><a class="dropdown-item" href="/doctors/paid/{{$doc->id}}">Paid Amounts</a></li>

                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              @empty
                              <tr>
                                <td colspan='4'>
                                <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No Doctors Available</h6>
                                </div>
                                </td>
                            </tr>
                              @endforelse
                          </tbody>
                        </table>
                        {{ $doctors->links('pagination::bootstrap-4') }}
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


      <!-- ------------------Edit-Quest-labtest-Categories-Modal-start------------------ -->
  
            <!-- Modal -->
            <div class="modal fade" id="edit_quest_labtests" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Quest Labtest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                          <form action="">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Code</label>
                                    <input type="text" class="form-control" placeholder="223">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Service Name</label>
                                    <input type="text" class="form-control" placeholder="ALBUMIN">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Name</label>
                                    <input type="text" class="form-control" placeholder="ALBUMIN">
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Category</label>
                                  <select class="form-select" aria-label="Default select example">
                                    <option selected>Digestive Health</option>
                                    <option value="1">DNA</option>
                                    <option value="2">Drugs and Alcohol</option>
                                  </select>
                              </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Price</label>
                                    <input type="text" class="form-control" placeholder="2.5">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Sale Price</label>
                                    <input type="text" class="form-control" placeholder="15">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Description</label>
                                    <textarea class="form-control" rows="5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente ullam ducimus reiciendis quia eos veniam animi porro placeat corrupti dolore dignissimos excepturi numquam illum quas sunt recusandae dolorem quisquam odit explicabo, nulla saepe corporis exercitationem? Hic accusamus eius non incidunt aperiam esse, cupiditate fugit voluptas officia beatae molestiae atque velit?</textarea>
                                </div>
                            </div>
    
                          </div>
                          </form>
                        
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn process-pay">Save</button>
                    </div>
                </div>
                </div>
            </div>
@endsection