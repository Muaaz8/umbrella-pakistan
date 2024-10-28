@extends('layouts.dashboard_imaging_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title')
    <title>Manage imaging Editors</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script type="text/javascript">
    function update_email(id)
    {
        email = $('#'+id+'').val();
        $('#email').val(email);
        $('#send_email').modal('show');
    }
    function status_change(id)
    {
      window.location.href="/lab_editor/change_status/"+id;
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
        window.location.href='/imaging/admin/dash';
      }
      else
      {
        $('#editors').empty();
        console.log(array);
        $.each (array, function (key, arr) {
          if(arr.name.match(val) || arr.email.match(val) || arr.status.match(val))
          {
            $('#editors').append('<tr id="editor_'+arr.id+'"></tr>');
            $('#editor_'+arr.id).append('<td data-label="Name">'+arr.name+' '+arr.last_name+'</td>'
            +'<td data-label="Email">'+arr.email+'</td>'
            );
            if(arr.status=='active')
            {
              $('#editor_'+arr.id).append('<td data-label="Status"><select onchange="status_change('+arr.id+')" class="form-select ad_act_dact w-50 m-sm-0 m-md-auto" aria-label="Default select example">'
              +'<option selected>Active</option><option >Deactivate</option></select></td>'
              );
            }
            else
            {
              $('#editor_'+arr.id).append('<td data-label="Status"><select onchange="status_change('+arr.id+')" class="form-select ad_act_dact w-50 m-sm-0 m-md-auto" aria-label="Default select example">'
              +'<option>Active</option><option selected>Deactivate</option></select></td>'
              );
            }
            $('#editor_'+arr.id).append('<td data-label="Action"><input type="hidden" id="'+arr.id+'" value="'+arr.email+'">'
            +'<div class="dropdown"><button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">OPTIONS</button>'
            +'<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">'
            +'<li><a class="dropdown-item" href="/editor/details/'+arr.id+'">Details & Activities</a></li>'
            +'<li><a class="dropdown-item" onclick="update_email('+arr.id+')" href="#" id="u_email" >Send Email</a></li>'
            +'</ul></div></td>'
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
                              <h3>Manage imaging Editors</h3>
                            </div>

                          </div>
                          <div class="d-flex align-items-baseline flex-wrap justify-content-between p-0">
                          <div class="d-flex w-25">
                                <input id="search" type="text" class="form-control mb-1" placeholder="Search editor">
                                <button type="button" id="search_btn" onclick="search({{$edt}})" class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                            </div>
                        <div>
                            <button type="button" class="btn process-pay" data-bs-toggle="modal" data-bs-target="#add_new_editor">Add New Editor</button>
                        </div>
                        </div>

                      <div class="wallet-table">
                        <table class="table">
                            <thead>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </thead>
                            <tbody id="editors">
                            @forelse($lab_editors as $editor)
                              <tr>
                                <td data-label="Name">{{$editor->name}} {{$editor->last_name}}</td>
                                <td data-label="Email">{{$editor->email}}</td>
                                <td data-label="Status">
                                    @if($editor->status == 'active')
                                    <select onchange="window.location.href='/lab_editor/change_status/{{$editor->id}}'" class="form-select ad_act_dact w-50 m-sm-0 m-md-auto" aria-label="Default select example">
                                        <option selected>Active</option>
                                        <option >Deactivate</option>
                                    </select>
                                    @else
                                    <select onchange="window.location.href='/lab_editor/change_status/{{$editor->id}}'" class="form-select ad_act_dact w-50 m-sm-0 m-md-auto" aria-label="Default select example">
                                        <option >Active</option>
                                        <option selected>Deactivate</option>
                                    </select>
                                    @endif
                                </td>
                                <td data-label="Action">
                                    <input type="hidden" id="{{$editor->id}}" value="{{$editor->email}}">
                                    <div class="dropdown">
                                    <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                      OPTIONS
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                      <li><a class="dropdown-item" href="/editor/details/{{$editor->id}}">Details & Activities</a></li>
                                      <li><a class="dropdown-item" onclick="update_email({{ $editor->id }})" href="#" id="u_email" >Send Email</a></li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              @empty
                            <tr>
                                <td colspan='4'>
                                <div class="m-auto text-center for-empty-div">
                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                    <h6> No Orders</h6>
                                </div>
                                </td>
                            </tr>
                              @endforelse
                              </tbody>
                          </table>
                            <div class="row d-flex justify-content-center">
                                <div class="paginateCounter">
                                    {{ $lab_editors->links('pagination::bootstrap-4') }}
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


        <!-- ------------------Send-Email-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="send_email" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                <form action="/send_email" method="POST">
                    @csrf
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">To</label>
                                    <input type="text" id="email" name="email" class="form-control" readonly placeholder="xyx@gmail.com">
                                </div>
                                <div class="col-md-6">
                                  <label for="specialInstructions">Subject</label>
                                  <input type="text" name="subject" class="form-control" placeholder="Enter Subject" required>
                              </div>
                            </div>
                            <div class="row mt-1">
                              <div class="col-md-12">
                                  <label for="email_body">Email Body</label>
                                  <textarea class="form-control" name="ebody" id="email_body" rows="3" placeholder="Type your email message" required></textarea>
                              </div>
                          </div>
                          </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn process-pay">Send</button>
                    </div>
                </form>
                </div>
                </div>
            </div>


    <!-- ------------------Send-Email-Modal-end------------------ -->
    <!-- ------------------Add-New-Editor-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="add_new_editor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                <form action="/add/editor" method="POST">
                            @csrf
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Editor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="" required>
                                    <input type="hidden" name="role" value="imaging" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="row mt-2">
                              <div class="col-md-12">
                                  <label for="email_body">Email Address</label>
                                  <input type="text" name="email" class="form-control" placeholder="xyz@gmail.com" required>
                              </div>
                          </div>
                          </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn process-pay">Add</button>
                    </div>
                </div>
                </form>
                </div>
            </div>


    <!-- ------------------Add-New-Editor-Modal-end------------------ -->

                    <!-- ------------------View-Online-Labtest-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="view_online_labtest" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View Online Labtest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                          <form action="">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                  <p><span class="fw-bold">Test Name:</span>  <span>(ABO, Rh)</span></p>
                                </div>
                                <div class="col-md-6">
                                  <p><span class="fw-bold">Slug:</span> <span>blood-type-test-abo-rh</span></p>
                              </div>
                            </div>
                            <div class="row mt-2">
                              <div class="col-md-6">
                                <p><span class="fw-bold">Is Featured:</span> <span>Not Featured Test</span></p>
                            </div>
                                <div class="col-md-6">
                                  <p><span class="fw-bold">Test Category:</span> <span>21</span></p>
                              </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                  <p><span class="fw-bold">Price:</span> <span>$ 39.00</span></p>
                              </div>
                                  <div class="col-md-6">
                                    <p><span class="fw-bold">Keyword:</span> <span>blood-type</span></p>
                                </div>
                              </div>
                              <div class="row mt-2">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Is Approved from Admin:</span> <span>Approved</span></p>
                                </div>
                                <div class="col-md-6">
                                  <p><span class="fw-bold">Featured Image:</span> <span></span></p>
                                  <div>
                                  <img class="img-fluid" src="./assets/images/lab-test-featured-img.png" alt="" style="width: 122px; height: 105px;">
                                </div>
                              </div>

                              </div>
                            <div class="row mt-2">
                              <div class="col-md-12">
                                <p><span class="fw-bold">Test Details:</span> <span>This test determines your blood type. It identifies your blood group (A, B, AB, or O) and whether you are positive or negative for the Rh antigen, a protein that affects what type of blood you can receive or donate. For example, O negative is a universal donor, while AB positive is a universal recipient.</span></p>
                            </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                  <p><span class="fw-bold">Short Details:</span> <span>This test determines your blood type. It identifies your blood group (A, B, AB, or O) and whether you are positive or negative for the Rh antigen, a protein that affects what type of blood you can receive or donate. For example, O negative is a universal donor, while AB positive is a universal recipient.</span></p>
                              </div>
                              </div>
                              <div class="row mt-2">
                                <div class="col-md-12">
                                <p><span class="fw-bold">Description:</span> <span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio assumenda quibusdam, repudiandae eum adipisci nesciunt impedit et beatae a doloribus.</span></p>
                              </div>
                              </div>

                          </div>
                          </form>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                    </div>
                </div>
                </div>
            </div>


    <!-- ------------------View-Online-Labtest-Modal-end------------------ -->

      <!-- ------------------Add-Online-labtest-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="add_online_test" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Online Labtest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                          <form action="">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Name</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">CPT Code:</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Code:</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Price:</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Keyword:</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Featured</label>
                                    <select class="form-select" aria-label="Default select example">
                                      <option selected>True</option>
                                      <option value="1">False</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Choose Lab Test Category:</label>
                                    <select class="form-select" aria-label="Default select example">
                                      <option selected>General Health</option>
                                      <option value="1">Infectious Disease</option>
                                      <option value="2">Women's Health</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Test Details:</label>
                                    <textarea class="form-control" name="" id="" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Short Description:</label>
                                    <textarea class="form-control" name="" id="" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Description:</label>
                                    <textarea class="form-control" name="" id="" rows="4"></textarea>
                                </div>
                            </div>


                          </div>
                          </form>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn process-pay">Add</button>
                    </div>
                </div>
                </div>
            </div>

    <!-- ------------------Add-Online-labtest-Modal-end------------------ -->


      <!-- ------------------Edit-Online-labtest-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="edit_online_test" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Online Labtest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                          <form action="">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Name</label>
                                    <input type="text" class="form-control" placeholder="(ABO, Rh)">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">CPT Code:</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Code:</label>
                                    <input type="text" class="form-control" placeholder="21">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Price:</label>
                                    <input type="text" class="form-control" placeholder="39.00">
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Keyword:</label>
                                    <input type="text" class="form-control" placeholder="blood-type">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Featured</label>
                                    <select class="form-select" aria-label="Default select example">
                                      <option selected>True</option>
                                      <option value="1">False</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Choose Lab Test Category:</label>
                                    <select class="form-select" aria-label="Default select example">
                                      <option selected>General Health</option>
                                      <option value="1">Infectious Disease</option>
                                      <option value="2">Women's Health</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Test Details:</label>
                                    <textarea class="form-control" name="" id="" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Short Description:</label>
                                    <textarea class="form-control" name="" id="" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Description:</label>
                                    <textarea class="form-control" name="" id="" rows="4"></textarea>
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


    <!-- ------------------Edit-Online-labtest-Modal-end------------------ -->


    <!-- ------------------Delete-Online-labtest-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="delete_online_labtest" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Online Labtest</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="delete-modal-body">
                      Are you sure you want to delete this labtest?
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger">Delete</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  </div>
              </div>
              </div>
          </div>
@endsection
  <!-- ------------------Delete-Online-labtest-Modal-end------------------ -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
