@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>All Doctors</h2>
        </div>
        <div class="row clearfix">
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">                            
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar3.jpg" class="img-thumbnail rounded-circle" alt="profile-image">                               
                            </div>

                            <div class="">
                                <h4 class="m-b-5 m-t-20">Dr. John</h4>
                                <p class="text-muted">Dentist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>                           
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card">                            
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar4.jpg" class="img-thumbnail rounded-circle" alt="profile-image">                               
                            </div>

                            <div class="">
                                <h4 class="m-b-5 m-t-20">Kendra V. Alfaro</h4>
                                <p class="text-muted">ENT Specialist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">                           
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar6.jpg" class="img-thumbnail rounded-circle" alt="profile-image">
                               
                            </div>

                            <div class="">
                                <h4 class="m-b-5 m-t-20">Kendra V. Alfaro</h4>
                                <p class="text-muted">Neurologist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">                           
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar6.jpg" class="img-thumbnail rounded-circle" alt="profile-image">
                               
                            </div>

                            <div class="">
                                <h4 class="m-b-5 m-t-20">Kendra V. Alfaro</h4>
                                <p class="text-muted">Neurologist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar3.jpg" class="img-thumbnail rounded-circle" alt="profile-image">
                               
                            </div>

                            <div class="">
                                <h4 class="m-b-5 m-t-20">Kendra V. Alfaro</h4>
                                <p class="text-muted">Dentist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card">
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar4.jpg" class="img-thumbnail rounded-circle" alt="profile-image">
                            </div>
                            <div class="">
                                <h4 class="m-b-5 m-t-20">Kendra V. Alfaro</h4>
                                <p class="text-muted">ENT Specialist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar6.jpg" class="img-thumbnail rounded-circle" alt="profile-image">
                               
                            </div>

                            <div class="">
                                <h4 class="m-b-5 m-t-20">Kendra V. Alfaro</h4>
                                <p class="text-muted">Neurologist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar6.jpg" class="img-thumbnail rounded-circle" alt="profile-image">
                               
                            </div>

                            <div class="">
                                <h4 class="m-b-5 m-t-20">Kendra V. Alfaro</h4>
                                <p class="text-muted">Neurologist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar6.jpg" class="img-thumbnail rounded-circle" alt="profile-image">
                               
                            </div>

                            <div class="">
                                <h4 class="m-b-5 m-t-20">Kendra V. Alfaro</h4>
                                <p class="text-muted">Neurologist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar6.jpg" class="img-thumbnail rounded-circle" alt="profile-image">
                               
                            </div>

                            <div class="">
                                <h4 class="m-b-5 m-t-20">Kendra V. Alfaro</h4>
                                <p class="text-muted">Neurologist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card verified">
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar3.jpg" class="img-thumbnail rounded-circle" alt="profile-image">
                               
                            </div>

                            <div class="">
                                <h4 class="m-b-5 m-t-20">Kendra V. Alfaro</h4>
                                <p class="text-muted">Dentist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="member-card">
                            <div class="thumb-xl member-thumb">
                                <img src="asset_admin/images/random-avatar4.jpg" class="img-thumbnail rounded-circle" alt="profile-image">
                                
                            </div>

                            <div class="">
                                <h4 class="m-b-5 m-t-20">Kendra V. Alfaro</h4>
                                <p class="text-muted">ENT Specialist<span> <a href="#" class="text-pink">websitename.com</a> </span></p>
                            </div>

                            <p class="text-muted">795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                            <a href="{{route('doc_logs')}}"  class="btn btn-raised btn-sm">View Logs</a>
                            <ul class="social-links list-inline m-t-10">
                                <li><a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a title="twitter" href="#" ><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a title="instagram" href="3" ><i class="zmdi zmdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-sm-12 text-center">
                <a href="#" class="btn btn-raised g-bg-cyan">Add Doctor</a>
            </div>
        </div>
    </div>
</section>
@endsection