@extends('layouts.frontend')

@section('content')
<!-- BREADCRUMB
			============================================= -->
<div id="breadcrumb" class="division">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class=" breadcrumb-holder">

                    <!-- Breadcrumb Nav -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">Contact Us</h4>

                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END BREADCRUMB -->




<!-- CONTACTS-2
			============================================= -->
<section id="contacts-2" class="wide-60 contacts-section division">
    <div class="container">
        <div class="row my-2">


            <!-- CONTACTS INFO -->
            <div class="col-lg-7">

                <!-- Title-->
                <h4 class="h4-md steelblue-color">Have a Question? Contact Us.
                </h4>

                <!-- Text-->
                <p class="contact-notice" style="margin:0px;">Give us a call or send an email. Our team is always ready
                    to provide customer
                    care help. For more information, visit us.
                </p>

                <div class="row">


                    <!-- CITY CONTACT DATA -->
                    <div class="col-md-6">
                        <div class="contact-box mb-40">
                            <!-- City -->
                            <p>625 School House</p>
                            <p>Road #2, </p>
                            <p>Lakeland, FL 33813</p>
                            <p>Phone: +1 (407) 693-8484</p>
                            <p>Email: <a href="mailto:contact@umbrellamd.com"
                                    class="blue-color">contact@umbrellamd.com</a></p>
                        </div>
                    </div>


                    <!-- CITY CONTACT DATA -->
                    <!-- <div class="col-md-6">
				 					<div class="contact-box mb-40">
				 						
										<h5 class="h5-sm steelblue-color">Melbourne</h5>
										<p>121 King Street, Melbourne,</p> 
										<p>Victoria 3000 Australia</p>
										<p>Phone: 1-(800)-569-7890</p>
										<p>Email: <a href="mailto:yourdomain@mail.com" class="blue-color">hello@yourdomain.com</a></p>
				 					</div>
				 				</div> -->


                    <!-- CITY CONTACT DATA -->
                    <!-- <div class="col-md-6">
				 					<div class="contact-box mb-40">
				 						
										<h5 class="h5-sm steelblue-color">Brisbane</h5>
										<p>121 King Street, Melbourne,</p> 
										<p>Victoria 3000 Australia</p>
										<p>Phone: 1-(800)-569-7890</p>
										<p>Email: <a href="mailto:yourdomain@mail.com" class="blue-color">hello@yourdomain.com</a></p>		
				 					</div>
				 				</div> -->


                    <!-- CITY CONTACT DATA -->
                    <!-- <div class="col-md-6">
				 					<div class="contact-box mb-40">
				 						
										<h5 class="h5-sm steelblue-color">Adelaide</h5>
										<p>121 King Street, Melbourne,</p> 
										<p>Victoria 3000 Australia</p>
										<p>Phone: 1-(800)-569-7890</p>
										<p>Email: <a href="mailto:yourdomain@mail.com" class="blue-color">hello@yourdomain.com</a></p>						
				 					</div>
				 				</div> -->


                </div>
            </div> <!-- END CONTACTS INFO -->


            <!-- CONTACT FORM -->
            <div class="col-lg-5 ">
                <div> 
                    <form id="frm" class="row contact-form" method="POST" action="{{route('contact_submit')}}">
                        @csrf
                        <!-- Contact Form Input -->
                        <div id="input-name" class="col-md-12">
                            <input type="text" name="name" id="name" maxlength="25" class="form-control"
                                placeholder="Enter Your Name*" autocomplete="off">
                            <!-- onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" > -->
                            <span id="name_err" role="alert" class="invalid-feedback err_msg">
                                <!-- <span> -->
                                Please Enter Name
                            </span>

                        </div>

                        <div id="input-email" class="col-md-12">
                            <input type="text" name="email" id="email" maxlength="30" class="form-control"
                                placeholder="Enter Your Email*" autocomplete="off">
                            <span id="email_err" role="alert" class="invalid-feedback err_msg">
                                Please Enter Valid Email
                            </span>

                        </div>

                        <div id="input-phone" class="col-md-12">
                            <input type="number" name="phone" id="phone" class="form-control" maxlength="15"
                                autocomplete="off" placeholder="Enter Your Phone Number*" 
                                title='Phone Number (Format: +99(99)9999-9999)'>
                            <span id="phone_err" role="alert" class="invalid-feedback err_msg">
                                Please Enter valid Phone Number
                            </span>

                        </div>

                        <!-- Form Select -->
                        <div id="input-subject" class="col-md-12">
                            <input type="text" name="subject" maxlength="70" id="subject" class="form-control"
                                placeholder="Subject*" autocomplete="off">
                            <span id="sub_err" role="alert" class="invalid-feedback err_msg">
                                Please Enter Subject
                            </span>

                        </div>

                        <div id="input-message" class="col-md-12 input-message">
                            <textarea class="form-control" rows="6" maxlength="500" id="message" name="message"
                                autocomplete="off" placeholder="Your Message ..."></textarea>
                            <span id="msg_err" role="alert" class="invalid-feedback err_msg">
                                Please Enter Message
                            </span>

                        </div>

                        <span id="msg" role="alert" class="invalid-feedback err_msg">
                            Incomplete Information
                        </span>
                        <!-- Contact Form Button -->
                        <div class="col-lg-12 mt-15 form-group">
                            <button type="button" class="btn btn-blue blue-hover" id="send_msg">Send Your
                                Message</button>
                                <!-- <input type="submit" value="Send your message"  id="send_msg"> -->
                            <span class="loading"></span>
                            <!-- <input type="submit" name="subject" value="Send your Message" class="btn btn-blue blue-hover"   required> -->

                        </div>


                    </form>
                </div>
            </div> <!-- END CONTACT FORM -->


        </div> <!-- End row -->
    </div> <!-- End container -->
</section> <!-- END CONTACTS-2 -->




<!-- BANNER-8
			============================================= -->
<section id="banner-8" class="bg-fixed banner-section division">
    <div class="container white-color">
        <div class="row d-flex align-items-center">


            <!-- BANNER TEXT -->
            <div class="col-lg-8 offset-lg-2">
                <div class="banner-txt icon-lg text-center">

                    <!-- Icon  -->
                    <span class="flaticon-072-hospital-5"></span>

                    <!-- Title  -->
                    <h3 class="h3-sm">Take the First Step to Help</h3>

                    <h4 class="h4-lg">Call Umbrella Health Care Services Now</h4>
                    <h2 class="h2-lg">+1 (407) 693-8484</h2>

                </div>
            </div>


        </div> <!-- End row -->
    </div> <!-- End container -->
</section> <!-- END BANNER-8 -->
<script>


</script>

@endsection