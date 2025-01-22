
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
    $('.deactive').click(function(){
        var id= $(this).attr('id');
        $('#deactive_form').attr('action','/doctors/deactivate/'+id);
    });

    $(document).ready(function(){
        $('.changePer').click(function(){
           var id= $(this).attr('id');
           $('#change_percentage_form').attr('action','/doctors/percentage/'+id);
        });
    });

    function email_modal_function(a){
        var email = $(a).attr('class');
        var breakClasses=email.split(' ');
        $('#email_send').val(breakClasses[1]);
        $('#send_email_modal').modal('show');
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
                            <div class="row">
                                <div>
                                    <h3 >All Doctors</h3>
                                </div>

                            </div>

                          </div>
                          <div class="d-flex justify-content-between flex-wrap p-0">
                            <div class="col-12 col-sm-6">
                              <form action="{{ url('/doctors/all/doctors') }}" method="POST">
                                  @csrf
                                  <input type="hidden" name="id" id="search_spec">
                                  <input
                                  type="text"
                                  id="search"
                                  name="name"
                                  class="form-control mb-1"
                                  placeholder="Search By Name, Email, PMDC number or State"
                                  aria-label="Username"
                                  aria-describedby="basic-addon1"/>
                              </form>
                          </div>
                          <div class="">
                            <div class="input-group justify-content-end">
                              <a href="{{route('pending_doctor_requests')}}" class="btn process-pay">PENDING REQUEST</a>
                            </div>
                          </div>

                          </div>
                      <div class="wallet-table table-responsive">
                        <table class="table dataTable">
                          <thead>
                            <tr style="font-size: 14px">
                              <th scope="col">First Name</th>
                              <th scope="col">Last Name</th>
                              <th scope="col">Email</th>
                              <th scope="col">PMDC</th>
                              <th scope="col">Percentage</th>
                              <th scope="col">Specialization</th>
                              <th scope="col">Registration Date</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($doctors as $doctor)
                            <tr >
                                <td data-label="First Name">{{ $doctor->name }} </td>
                                <td data-label="Last Name">{{ $doctor->last_name }} </td>
                                <td data-label="Email">{{ $doctor->email }}</td>
                                <td data-label="PMDC">{{ $doctor->nip_number }}</td>
                                <td data-label="Percentage">{{ $doctor->percentage_doctor  }}</td>
                                <td data-label="Specialization">{{ $doctor->spec_name }}</td>
                                <td data-label="Join Date">{{ date('m-d-Y',strtotime($doctor->created_at)) }}</td>

                                <td data-label="Action">
                                    <div class="dropdown">
                                    <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                      OPTIONS
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                      <li><a class="dropdown-item" href="{{ route('all_doctor_view',$doctor->id) }}">View Details</a></li>
                                      <li><a class="dropdown-item deactive" href="{{ route('deactivate', $doctor->id) }}" id="{{$doctor->id}}" data-bs-toggle="modal" data-bs-target="#deactivate_doctor">Deactivate</a></li>
                                      <li><a style="cursor:pointer;" class="dropdown-item {{ $doctor->email }}" id="send_email_btn" onclick="email_modal_function(this)">Send Emails</a></li>
                                      <li><a class="dropdown-item changePer" href="{{ route('change_percentage',$doctor->id) }}" data-bs-toggle="modal" id="{{$doctor->id}}" data-bs-target="#change_percentage">Change Percentage</a></li>
                                      <li><a class="dropdown-item" href="{{ $doctor->contract }}" target="_blank" >View Contract</a></li>
                                      @if (!isset($doctor->lab_status))
                                          <li><a class="dropdown-item" href="{{ route('assign_doctor_for_lab',$doctor->id) }}">Assign For Lab Approval</a></li>
                                      @endif
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="m-auto text-center for-empty-div">
                                        <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                        <h6>No Doctors To Show</h6>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                          </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="paginateCounter link-paginate">
                                    {{$doctors->links('pagination::bootstrap-4') }}
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


    </div>
    <!-- ------------------Block-Doctor-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="deactivate_doctor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Deactivate Doctor</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form  method="GET" id="deactive_form">
                    @csrf
                  <div class="modal-body">
                      <div class="delete-modal-body">
                      Are you sure you want to Deactivate this Doctor?
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-danger">Deactivate</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  </div>
                </form>
              </div>
              </div>
          </div>


  <!-- ------------------Block-Doctor-Modal-end------------------ -->
    <!-- ------------------Send-Email-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="send_email_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Send Email</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form name="send_email" id="send_email" action="/doctors/send_mail" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="email" value=''>
                    <div class="modal-body">

                            <div class="p-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="specialInstructions">To</label>
                                        <input type="text" class="form-control" id="email_send" name="email" placeholder="xyx@gmail.com" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="specialInstructions">Subject</label>
                                        <input type="text" class="form-control" name="subject" placeholder="Enter Subject" required>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label for="email_body">Email Body</label>
                                        <textarea class="form-control" id="email_body" name="ebody" rows="3" placeholder="Type your email message" required></textarea>
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

      <!-- ------------------Change-Percentage-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="change_percentage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Change Percentage</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form  method="POST" id="change_percentage_form">
                    @csrf
                  <div class="modal-body">


                          <div class="p-3">
                          <div class="row">
                            <div class="col-md-12">
                                <label for="add_percentage">Add Percentage</label>
                                <input type="text" class="form-control" name="doc_percentage" placeholder="10.00%">
                            </div>
                        </div>
                        </div>

                  </div>
                  <div class="modal-footer">
                    <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" data-id="" class="btn process-pay">Submit</button>
                  </div>
                </form>

              </div>
              </div>
          </div>


  <!-- ------------------Change-Percentage-Modal-end------------------ -->


@endsection
