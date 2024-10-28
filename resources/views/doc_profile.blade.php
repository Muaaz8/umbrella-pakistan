@extends('layouts.admin')

@section('content')
<section class="content profile-page">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-12 p-l-0 p-r-0">
                <section class="boxs-simple">
                    <div class="profile-header">
                        <div class="profile_info">
                            <div class="profile-image"> <img src="asset_admin/images/random-avatar7.jpg" alt=""> </div>
                            <h4 class="mb-0"><strong>Dr. John</strong> Smith</h4>
                            <span class="text-muted col-white">Dentist</span>
                            <div class="mt-10">
                                <button class="col-2 btn btn-raised btn-default bg-blush btn-sm">Book Appointment</button>
                                <button class="col-2 btn btn-raised btn-default bg-green btn-sm">Message (3 free)</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2>About</h2>
                    </div>
                    <div class="body">
                        <p class="text-default">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                        <blockquote>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <small>Designer <cite title="Source Title">Source Title</cite></small> </blockquote>
                    </div>
                </div>
                <div class="card">
                    <div class="header">
                        <h2>Education</h2>
                    </div>
                    <div class="body">
                        <ul class="dis">
                            <li>FCPS from Ski University</li>
                            <li>MBBS from Stanford</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="body"> 
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tab-nav-right" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#mypat">My Patients</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#timeline">Activities</a></li>
                        </ul>
                        
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane in active" id="mypat">
                                <div class="wrap-reset">
                                    <div class="mypost-list">
<div class="row clearfix">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card all-patients">
                    <div class="body">
                        <div class="row">
                                <a href="#" class="p-profile-pix"><img src="asset_admin/images/patients/random-avatar3.jpg" alt="user" class="img-thumbnail img-fluid"></a>
                            </div>
                            <div class="row">
                                <h5 class="m-b-0">Johnathan Doe </h5>
                                <address class="m-b-0">
                                    123 Folsom Ave, Suite 100 New York, CADGE 56824<br>
                                    <abbr title="Phone">P:</abbr> (123) 456-7890
                                </address>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card all-patients">
                    <div class="body">
                        <div class="row">
                                <a href="#" class="p-profile-pix"><img src="asset_admin/images/patients/random-avatar4.jpg" alt="user" class="img-thumbnail img-fluid"></a>
                            </div>
                            <div class="row">
                                <h5 class="m-b-0">Johnathan Doe </h5>
                                <address class="m-b-0">
                                    123 Folsom Ave, Suite 100 New York, CADGE 56824<br>
                                    <abbr title="Phone">P:</abbr> (123) 456-7890
                                </address>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card all-patients">
                    <div class="body">
                        <div class="row">
                                <a href="#" class="p-profile-pix"><img src="asset_admin/images/patients/random-avatar5.jpg" alt="user" class="img-thumbnail img-fluid"></a>
                            </div>
                            <div class="row">
                                <h5 class="m-b-0">Johnathan Doe </h5>                            
                                    <address class="m-b-0">
                                    123 Folsom Ave, Suite 100 New York, CADGE 56824<br>
                                    <abbr title="Phone">P:</abbr> (123) 456-7890
                                </address>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card all-patients">
                    <div class="body">
                        <div class="row">
                                <a href="#" class="p-profile-pix"><img src="asset_admin/images/patients/random-avatar6.jpg" alt="user" class="img-thumbnail img-fluid"></a>
                            </div>
                            <div class="row">
                                <h5 class="m-b-0">Johnathan Doe </h5>
                                <address class="m-b-0">
                                    123 Folsom Ave, Suite 100 New York, CADGE 56824<br>
                                    <abbr title="Phone">P:</abbr> (123) 456-7890
                                </address>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card all-patients">
                    <div class="body">
                        <div class="row">
                                <a href="#" class="p-profile-pix"><img src="asset_admin/images/patients/random-avatar1.jpg" alt="user" class="img-thumbnail img-fluid"></a>
                            </div>
                            <div class="row">
                                <h5 class="m-b-0">Johnathan Doe </h5>
                                <address class="m-b-0">
                                    123 Folsom Ave, Suite 100 New York, CADGE 56824<br>
                                    <abbr title="Phone">P:</abbr> (123) 456-7890
                                </address>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card all-patients">
                    <div class="body">
                        <div class="row">
                                <a href="#" class="p-profile-pix"><img src="asset_admin/images/patients/random-avatar1.jpg" alt="user" class="img-thumbnail img-fluid"></a>
                            </div>
                            <div class="row">
                                <h5 class="m-b-0">Johnathan Doe </h5>
                                <address class="m-b-0">
                                    123 Folsom Ave, Suite 100 New York, CADGE 56824<br>
                                    <abbr title="Phone">P:</abbr> (123) 456-7890
                                </address>
                            </div>
                    </div>
                </div>
            </div>
        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="timeline">
                                <div class="timeline-body">
                                    <div class="timeline m-border">
                                        <div class="timeline-item">
                                            <div class="item-content">
                                                <div class="text-small">Just now</div>
                                                <p>Finished task #features 4.</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item border-info">
                                            <div class="item-content">
                                                <div class="text-small">11:30</div>
                                                <p>@Jessi retwit your post</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item border-warning border-l">
                                            <div class="item-content">
                                                <div class="text-small">10:30</div>
                                                <p>Call to customer #Jacob and discuss the detail.</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item border-warning">
                                            <div class="item-content">
                                                <div class="text-small">3 days ago</div>
                                                <p>Jessi commented your post.</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item border-danger">
                                            <div class="item-content">
                                                <div class="text--muted">Thu, 10 Mar</div>
                                                <p>Trip to the moon</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item border-info">
                                            <div class="item-content">
                                                <div class="text-small">Sat, 5 Mar</div>
                                                <p>Prepare for presentation</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item border-danger">
                                            <div class="item-content">
                                                <div class="text-small">Sun, 11 Feb</div>
                                                <p>Jessi assign you a task #Mockup Design.</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item border-info">
                                            <div class="item-content">
                                                <div class="text-small">Thu, 17 Jan</div>
                                                <p>Follow up to close deal</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="item-content">
                                                <div class="text-small">Just now</div>
                                                <p>Finished task #features 4.</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item border-info">
                                            <div class="item-content">
                                                <div class="text-small">11:30</div>
                                                <p>@Jessi retwit your post</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item border-warning border-l">
                                            <div class="item-content">
                                                <div class="text-small">10:30</div>
                                                <p>Call to customer #Jacob and discuss the detail.</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item border-warning">
                                            <div class="item-content">
                                                <div class="text-small">3 days ago</div>
                                                <p>Jessi commented your post.</p>
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
</section>
@endsection