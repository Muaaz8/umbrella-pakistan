<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_style.css') }}" />
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
      integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
      integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <title>Waiting Verified</title>
    <style>
      .resend-btn{
        background: linear-gradient(to top, #08295a, #165dc8);
        color: white;
        border: none;
        padding: 5px 9px;
        border-radius: 40px;
        font-size: 14px;
      }
    </style>
  </head>
  <body>
    <div class="verify-wrapper">
      <div class="container">
        <div class="row mt-1">
          <div class="d-flex justify-content-between align-items-center">
            <div class="reviewed-back-btn">
              <i onclick="window.location.href='/'" class="fa-solid fa-circle-left"></i>
            </div>
            <div class="dropdown">
              <a style="background-color: #08295a;color: #fff;" class="btn btn-secondary verify-profile-icon" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-circle-user"></i>
              </a>

              <ul style="padding: 8px 8px;background-color: #08295a;color: #fff;cursor:pointer;" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                {{-- <li><a class="dropdown-item" href="#">Logout</a></li> --}}
                <li href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></li>

              </ul>
            </div>
          </div>
        </div>
        <div class="row m-auto">

          <div class="text-center">
            <img src="{{ asset('assets/images/logo_all.png') }}" alt="" width="250" height="150" />
          </div>
          <div class="reviewed-account-txt">
            <h5>Dr. {{ $user->name }} {{ $user->last_name }} your account is being reviewed</h5>
          </div>
        </div>

        <div class="row mt-3">
          <div class="pb-3">
            <h5 class="m-0">Current status:</h5>
          </div>
          <div class="col-md-3 col-sm-6 mb-4">
            <h6 class="check-email-text"><br></h6>
            <div class="reviewed_card_area reviewed-done">
              <div class="single_reviewed_card">
                <div class="reviewed_card_content">
                  <div class="reviewed_card_icon">
                    <img src="{{ asset('assets/images/register.png') }}" alt="" />
                    <h6>Registration</h6>
                  </div>

                  <div class="reviewed_card_title reviewed-checked">

                      <i class="fa fa-check me-2" aria-hidden="true"></i>Step 1 - Complete

                  </div>
                </div>
              </div>
            </div>
          </div>
          @if($user->email_status==0)
          <div class="col-md-3 col-sm-6 mb-4">
            <div class="reviewed_card_area reviewed-in-progress">
              <h6 class="check-email-text">Please check email</h6>
              <div class="single_reviewed_card">
                <div class="reviewed_card_content">
                  <div class="reviewed_card_icon Email-confirm-wrap">
                    <img src="{{ asset('assets/images/verify-email.png') }}" alt="" />
                    <h6>Email Verification</h6>
                    <form action="{{ route('resend') }}" id="form_send" method="POST">
                      @csrf
                      <button type="submit" class="resend-btn" id="resendMail"><span id="emailCounter"></span> Resend Email</button>
                    </form>
                  </div>

                  <div class="reviewed_card_title reviewed-progress-checked">
                      <i class="fa-solid fa-spinner me-2"></i>Step 2 - In Progress
                    </div>
                  </div>
                </div>
                <p>Please verify your email address to accesss Umbrella Health Care System web portal.</p>
                <p>If you did not get it.</p>
            </div>
          </div>
          @elseif($user->email_status==1)
          <div class="col-md-3 col-sm-6 mb-4">
            <div class="reviewed_card_area reviewed-done">
              <h6 class="check-email-text"><br></h6>
              <div class="single_reviewed_card">
                <div class="reviewed_card_content">
                  <div class="reviewed_card_icon">
                    <img src="{{ asset('assets/images/verify-email.png') }}" alt="" />
                    <h6>Email Verification</h6>
                  </div>

                  <div class="reviewed_card_title reviewed-progress-checked">
                    <i class="fa fa-check me-2"></i>Step 2 - Complete
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif

          @if($user->email_status==1 && $user->card_status==0)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="reviewed_card_area reviewed-in-progress">
                <h6 class="check-email-text">Please Upload Driver License</h6>
                <div class="single_reviewed_card">
                    <div class="reviewed_card_content">
                    <div class="reviewed_card_icon Email-confirm-wrap">
                        <img src="{{ asset('assets/images/credit-card.png') }}" alt="" />
                        <h6>Driver License</h6>
                        <button type="button" class="resend-btn" data-bs-toggle="modal" data-bs-target="#upload">Upload</button>
                    </div>


                    <div class="reviewed_card_title reviewed-progress-checked">
                        <i class="fa-solid fa-spinner me-2"></i>Step 3 - In Progress
                        </div>
                    </div>
                    </div>
                </div>
            </div>
          @elseif($user->email_status==0 && $user->card_status==0)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="reviewed_card_area">
                <h6 class="check-email-text"><br></h6>
                <div class="single_reviewed_card">
                    <div class="reviewed_card_content">
                    <div class="reviewed_card_icon">
                        <img src="{{ asset('assets/images/credit-card.png') }}" alt="" />
                        <h6>Driver License</h6>
                    </div>

                    <div class="reviewed_card_title">
                        <i class="fa-solid fa-pause me-2"></i>Step 3 - Pending
                    </div>
                    </div>
                </div>
                </div>
            </div>
          @elseif($user->email_status==0 && $user->card_status==1)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="reviewed_card_area">
                <h6 class="check-email-text"><br></h6>
                <div class="single_reviewed_card">
                    <div class="reviewed_card_content">
                    <div class="reviewed_card_icon">
                        <img src="{{ asset('assets/images/credit-card.png') }}" alt="" />
                        <h6>Driver License</h6>
                    </div>

                    <div class="reviewed_card_title">
                        <i class="fa-solid fa-pause me-2"></i>Step 3 - Pending
                    </div>
                    </div>
                </div>
                </div>
            </div>
          @elseif($user->email_status==1 && $user->card_status==1)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="reviewed_card_area reviewed-done">
                <h6 class="check-email-text"><br></h6>
                <div class="single_reviewed_card ">
                    <div class="reviewed_card_content">
                    <div class="reviewed_card_icon ">
                        <img src="{{ asset('assets/images/credit-card.png') }}" alt="" />
                        <h6>Driver' License</h6>
                    </div>

                    <div class="reviewed_card_title">
                        <i class="fa-solid fa-check me-2"></i>Step 3 - Complete
                    </div>
                    </div>
                </div>
                </div>
            </div>
          @endif

          @if($user->active!=1 && $user->email_status==0 && $user->card_status==0)
          <div class="col-md-3 col-sm-6 mb-4">
            <div class="reviewed_card_area">
              <h6 class="check-email-text"><br></h6>
              <div class="single_reviewed_card">
                <div class="reviewed_card_content">
                  <div class="reviewed_card_icon">
                    <img src="{{ asset('assets/images/contract.png') }}" alt="" />
                    <h6>Contract</h6>
                  </div>

                  <div class="reviewed_card_title">
                    <i class="fa-solid fa-pause me-2"></i>Step 4 - Pending
                  </div>
                </div>
              </div>
            </div>
          </div>
          @elseif($user->active!=1 && $user->email_status==0 && $user->card_status==1)
          <div class="col-md-3 col-sm-6 mb-4">
            <div class="reviewed_card_area">
              <h6 class="check-email-text"><br></h6>
              <div class="single_reviewed_card reviewed-in-pending">
                <div class="reviewed_card_content">
                  <div class="reviewed_card_icon">
                    <img src="{{ asset('assets/images/contract.png') }}" alt="" />
                    <h6>Contract</h6>
                  </div>

                  <div class="reviewed_card_title">
                    <i class="fa-solid fa-pause me-2"></i>Step 4 - Pending
                  </div>
                </div>
              </div>
            </div>
          </div>
          @elseif($user->active!=1 && $user->email_status==1 && $user->card_status==0)
          <div class="col-md-3 col-sm-6 mb-4">
            <div class="reviewed_card_area">
              <h6 class="check-email-text"><br></h6>
              <div class="single_reviewed_card reviewed-in-pending">
                <div class="reviewed_card_content">
                  <div class="reviewed_card_icon">
                    <img src="{{ asset('assets/images/contract.png') }}" alt="" />
                    <h6>Contract</h6>
                  </div>

                  <div class="reviewed_card_title">
                    <i class="fa-solid fa-pause me-2"></i>Step 4 - Pending
                  </div>
                </div>
              </div>
            </div>
          </div>
          @elseif($user->active!=1 && $user->email_status==1 && $user->card_status==1)
          <div class="col-md-3 col-sm-6 mb-4">
            <div class="reviewed_card_area reviewed-in-progress">
              <h6 class="check-email-text"><br></h6>
              <div class="single_reviewed_card reviewed-in-progress">
                <div class="reviewed_card_content">
                  <div class="reviewed_card_icon">
                    <img src="{{ asset('assets/images/contract.png') }}" alt="" />
                    <h6>Contract</h6>
                  </div>

                  <div class="reviewed_card_title">
                    <i class="fa-solid fa-spinner me-2"></i>Step 4 - In Progress
                  </div>
                </div>
              </div>
            </div>
          </div>
          @elseif($user->active==1 && $user->card_status==1 && $user->email_status==1)
          <div class="col-md-3 col-sm-6 mb-4">
            <div class="reviewed_card_area reviewed-done">
              <h6 class="check-email-text"><br></h6>
              <div class="single_reviewed_card">
                <div class="reviewed_card_content">
                  <div class="reviewed_card_icon">
                    <img src="{{ asset('assets/images/contract.png') }}" alt="" />
                    <h6>Contract</h6>
                  </div>

                  <div class="reviewed_card_title">
                    <i class="fa-solid fa fa-check me-2"></i>Step 4 - Complete
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          @if(isset($user->contract_date->date))
          <div class="text-center mb-3"><p>Please wait till your contract date <b>{{$user->contract_date->date}}</b></p></div>
          @endif


          {{-- @if($user->active==1 && $user->email_status==1)
          <div class="col-md-3 col-sm-6 mb-4">
            <div class="reviewed_card_area reviewed-in-progress">
              <h6 class="check-email-text"><br></h6>
              <div class="single_reviewed_card">
                <div class="reviewed_card_content">
                  <div class="reviewed_card_icon">
                    <img src="{{ asset('assets/images/approved.png') }}" alt="" />
                    <h6>Please wait till your</h6>
                    <h6>contract date</h6>
                  </div>

                  <div class="reviewed_card_title">
                    <i class="fa-solid fa-spinner me-2"></i>Step 4 - In Progress
                  </div>
                </div>
              </div>
            </div>
          </div>
          @else
           <div class="col-md-3 col-sm-6 mb-4">
            <div class="reviewed_card_area ">
              <h6 class="check-email-text"><br></h6>
              <div class="single_reviewed_card">
                <div class="reviewed_card_content">
                  <div class="reviewed_card_icon">
                    <img src="{{ asset('assets/images/approved.png') }}" alt="" />
                    <h6>Approved</h6>
                  </div>

                  <div class="reviewed_card_title">
                    <i class="fa-solid fa-pause me-2"></i>Step 4 - Pending
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          --}}
        </div>
      </div>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>
    <script>

      document.getElementById("resendMail").onclick = function() {
          $('#resendMail').attr('disabled', true);
          $('#form_send').submit();
      }

      document.getElementById("resendMail").disabled = true;
      var emailCounter=120;
      var emailResendInterval=setInterval(() => {
      emailCounter--;

      document.getElementById("resendMail").disabled = true;
          if(emailCounter<1)
            {
              clearInterval(emailResendInterval);

              $("#emailCounter").hide();
              document.getElementById("resendMail").disabled = false;

            }else{
              $('#emailCounter').html('('+emailCounter+")");
            }
      }, 1000);

      </script>
    <script src="assets/js/custom.js"></script>
    <!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Launch demo modal
  </button> -->

  <!-- Modal -->
  <div class="modal fade" id="upload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Upload Drivering License</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('upload_license') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="p-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="formFile1" class="form-label">Driver' License Front</label>
                            <input class="form-control" name="front_license" type="file" id="formFile1" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="formFile2" class="form-label">Driver' License Back</label>
                            <input class="form-control" name="back_license" type="file" id="formFile2" required>
                        </div>
                    </div>

                </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn process-pay">Save changes</button>
        </div>
        </form>
      </div>
    </div>
  </div>
  </body>
</html>
