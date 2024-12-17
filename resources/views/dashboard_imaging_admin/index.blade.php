<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="style.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
      integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <title>Imaging Locations</title>
  </head>
  <body>
    <div class="dashboard">
        <div class="dashboard-nav" id="style-10">
            <!-- <header>
              <a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a
              >
              <a href="#" class="brand-logo">
                <img src="assets/images/logo.png" alt="" />
              </a>
            </header> -->
            <div class="nav-info-div-wrapper">
                <div class="nav-info-img">
                    {{-- <img src="assets/images/logo.png" alt=""> --}}
                </div>
                <h5 class="text-center py-2">Editors Dashboard</h5>
                <div class="d-flex">
                    <div class="nav-reports-card">
                        <h6>0</h6>
                        <p>Patient</p>
                    </div>
                    <div class="nav-reports-card">
                        <h6>0</h6>
                        <p>Patient</p>
                    </div>
                    <div class="nav-reports-card">
                        <h6>0</h6>
                        <p>Sessions</p>
                    </div>

                </div>
            </div>
            <nav class="dashboard-nav-list">
              <a href="#" class="dashboard-nav-item active"><i class="fa-solid fa-house"></i> Dashboard </a>
              <div class="dashboard-nav-dropdown">
                <a
                  href="#!"
                  class="dashboard-nav-item dashboard-nav-dropdown-toggle"
                  ><i class="fa-solid fa-calendar-check"></i> Lab Tests
                </a>
                <div class="dashboard-nav-dropdown-menu">
                  <a href="#" class="dashboard-nav-dropdown-item">Quest Labtest</a>
                  <a href="#" class="dashboard-nav-dropdown-item">Online Labtest</a>
                  <a href="#" class="dashboard-nav-dropdown-item">Labtest Categories</a>

                </div>
              </div>
              <div class="dashboard-nav-dropdown">


              </div>

              <a href="accountSetting.html" class="dashboard-nav-item"><i class="fa-solid fa-gear"></i>Orders </a>
              <a href="accountSetting.html" class="dashboard-nav-item"><i class="fa-solid fa-gear"></i>Quest Orders </a>
              <a href="accountSetting.html" class="dashboard-nav-item"><i class="fa-solid fa-gear"></i>Quest Failed Requests</a>
              <a href="accountSetting.html" class="dashboard-nav-item"><i class="fa-solid fa-calendar-check"></i>Order Approvals</a>



              <a href="accountSetting.html" class="dashboard-nav-item"><i class="fa-solid fa-gear"></i>Account Setting </a>

            </nav>
          </div>
      <div class="dashboard-app">
        <header class="dashboard-toolbar">
          <div class="d-flex align-items-baseline">
            <a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a>
            <form class="d-flex header-search">
              <input
                class="form-control me-2"
                type="search"
                placeholder="Search"
                aria-label="Search"
              />
            </form>
            <i class="fa-solid fa-bell"></i>
          </div>
          <div>
            <i class="fa-solid fa-bell"></i>
            <i class="fa-solid fa-user"></i><span>Login</span>
          </div>
        </header>
        <div class="dashboard-content">
          <div class="container">
            <div class="row m-auto">
              <div class="col-md-12">
                <div class="row m-auto">
                    <div class="d-flex align-items-end p-0">
                        <div>
                          <h3>Imaging Locations</h3>
                        </div>

                      </div>
                      <div class="d-flex justify-content-between p-0">
                        <div class="d-flex w-25">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                    <div>
                        <button type="button" class="btn process-pay" data-bs-toggle="modal" data-bs-target="#add_location">Add New</button>
                    </div>
                    </div>

                  <div class="wallet-table">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">State</th>
                          <th scope="col">City</th>
                          <th scope="col">Zip Code</th>
                          <th scope="col">Latitue</th>
                          <th scope="col">Longitude</th>
                          <th scope="col">Address</th>
                          <th scope="col">Options</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                            <th scope="row">Alabama</th>
                            <td>Alabaster</td>
                            <td>35007</td>
                            <td>33.2288218</td>
                            <td>-86.7919009</td>
                            <td>Fulton Lake Rd, Alabaster, AL 35007, USA</td>

                            <td>
                                <div class="dropdown">
                                <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  OPTIONS
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_location">Edit</a></li>
                                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_location">Delete</a></li>
                                </ul>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">Alabama</th>
                            <td>Alabaster</td>
                            <td>35007</td>
                            <td>33.2288218</td>
                            <td>-86.7919009</td>
                            <td>Fulton Lake Rd, Alabaster, AL 35007, USA</td>

                            <td>
                                <div class="dropdown">
                                <button class="btn option-view-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  OPTIONS
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_location">Edit</a></li>
                                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_location">Delete</a></li>
                                </ul>
                              </div>
                            </td>
                          </tr>
                      </tbody>
                    </table>
                    <nav aria-label="..." class="float-end pe-3">
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
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>


      <!-- ------------------Add-imaging-location-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="add_location" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Imaging Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                          <form action="">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="specialInstructions">State:</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>


                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">City:</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">ZipCode:</label>
                                    <input type="text" class="form-control" placeholder="">
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


    <!-- ------------------Add-imaging-location-Modal-end------------------ -->

      <!-- ------------------Edit-imaging-location-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="edit_location" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Imaging Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                          <form action="">
                            <div class="p-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="specialInstructions">State:</label>
                                    <input type="text" class="form-control" placeholder="Alabama">
                                </div>


                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">City:</label>
                                    <input type="text" class="form-control" placeholder="Alabaster">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">ZipCode:</label>
                                    <input type="text" class="form-control" placeholder="35007">
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


    <!-- ------------------Edit-imaging-location-Modal-end------------------ -->

    <!-- ------------------Delete-Service-Modal-start------------------ -->

            <!-- Modal -->
            <div class="modal fade" id="delete_location" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Location</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="delete-modal-body">
                      Are you sure you want to delete this location?
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger">Delete</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  </div>
              </div>
              </div>
          </div>


  <!-- ------------------Delete-Service-Modal-end------------------ -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>
<script src="./assets/js/custom.js"></script>
  </body>
</html>
