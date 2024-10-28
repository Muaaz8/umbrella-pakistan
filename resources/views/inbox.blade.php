@extends('layouts.admin')

@section('content')
<!-- main content -->
<section class="content email-page">
    <div class="container-fluid">
        <div class="row">                 
            <div class="col-lg-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#primary">Inbox</a></li>                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active in" id="primary">
                        <section class="mail_listing body table-responsive">
                            <table class="table table-hover">                                
                                <tbody>
                                    <tr class="unread">
                                        <td>
                                            <div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_2" />
                                                <label for="basic_checkbox_2"></label>
                                            </div>
                                        </td>                                    
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star-outline"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar1.jpg" alt="profile img">Hritik Roshan</td>
                                        <td class="max-texts">
                                            <a href="#"><span class="label label-info">Work</span>Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a>
                                        </td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> 12:30 PM </td>
                                    </tr>
                                    <tr class="unread">
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_3" />
                                                <label for="basic_checkbox_3"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star text-warning"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar2.jpg" alt="profile img">Genelia Roshan</td>
                                        <td class="max-texts"><a href="#">Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 13 </td>
                                    </tr>
                                    <tr class="unread">
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_4" />
                                                <label for="basic_checkbox_4"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star-outline"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar3.jpg" alt="profile img">Ritesh Deshmukh</td>
                                        <td class="max-texts"><a href="#"><span class="label label-success">Themeforest</span> Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 12 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_5" />
                                                <label for="basic_checkbox_5"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star-outline"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar4.jpg" alt="profile img">Akshay Kumar</td>
                                        <td class="max-texts"><a href="#"><span class="label label-warning">Work</span> Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 12 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_55" />
                                                <label for="basic_checkbox_55"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star-outline"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar5.jpg" alt="profile img">Hritik Roshan</td>
                                        <td class="max-texts"><a href="#"><span class="label label-info">Work</span> Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 12 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_44" />
                                                <label for="basic_checkbox_44"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star text-warning"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar1.jpg" alt="profile img">Genelia Roshan</td>
                                        <td class="max-texts"><a href="#">Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 11 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_43" />
                                                <label for="basic_checkbox_43"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star-outline"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar2.jpg" alt="profile img">Ritesh Deshmukh</td>
                                        <td class="max-texts"><a href="#"><span class="label label-success">Themeforest</span> Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 11 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_23" />
                                                <label for="basic_checkbox_23"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star-outline"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar3.jpg" alt="profile img">Akshay Kumar</td>
                                        <td class="max-texts"><a href="#"><span class="label label-warning">Work</span> Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 11 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_13" />
                                                <label for="basic_checkbox_13"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star-outline"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar4.jpg" alt="profile img">Hritik Roshan</td>
                                        <td class="max-texts"><a href="#"><span class="label label-info">Work</span> Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 10 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_32" />
                                                <label for="basic_checkbox_32"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star text-warning"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar5.jpg" alt="profile img">Genelia Roshan</td>
                                        <td class="max-texts"><a href="#">Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 10 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_37" />
                                                <label for="basic_checkbox_37"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star-outline"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar1.jpg" alt="profile img">Ritesh Deshmukh</td>
                                        <td class="max-texts"><a href="#"><span class="label label-success">Themeforest</span> Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 10 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_38" />
                                                <label for="basic_checkbox_38"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star-outline"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar2.jpg" alt="profile img">Akshay Kumar</td>
                                        <td class="max-texts"><a href="#"><span class="label label-warning">Work</span> Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 09 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_39" />
                                                <label for="basic_checkbox_39"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star-outline"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar4.jpg" alt="profile img">Hritik Roshan</td>
                                        <td class="max-texts"><a href="#"><span class="label label-info">Work</span> Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 09 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_8" />
                                                <label for="basic_checkbox_8"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star text-warning"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar3.jpg" alt="profile img">Genelia Roshan</td>
                                        <td class="max-texts"><a href="#">Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 09 </td>
                                    </tr>
                                    <tr>
                                        <td><div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_9" />
                                                <label for="basic_checkbox_9"></label>
                                            </div></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-star-outline"></i></td>
                                        <td class="hidden-sm-down hidden-md-down"><img src="asset_admin/images/xs/avatar5.jpg" alt="profile img">Ritesh Deshmukh</td>
                                        <td class="max-texts"><a href="#"><span class="label label-success">Themeforest</span> Lorem ipsum perspiciatis unde omnis iste natus error sit voluptatem</a></td>
                                        <td class="hidden-sm-down"><i class="zmdi zmdi-attachment-alt"></i></td>
                                        <td class="text-right"> May 09 </td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                    </div>
                </div> 
            </div>       
        </div>
    </div>
</section>
@endsection