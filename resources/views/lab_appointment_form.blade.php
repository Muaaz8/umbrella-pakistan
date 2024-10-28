@extends('layouts.frontend')

@section('content')
	<!-- CONTACTS-1 -->
			<section id="contacts-1" class="wide-60 contacts-section division">				
				<div class="container">
				    <div class="row">
				    
<div class="col-lg-2">
    </div>
					<div class="col-lg-8">
				 			<div class="txt-block pr-30">

				 				<!-- Title -->
								<center><h3 class="h3-md steelblue-color">Book an Appointment</h3></center>

								<!-- Text -->
								
								
									
				 			<div class="form-holder mb-40" align="center">
				 				<form method="POST">
				                     <div class="row">                       
					                <!-- Contact Form Input -->
					               
					                <div  class="col-lg-12 mb-20">
					                <input id="" name="type" disabled class="form-control department" value="Lab Test">
						                       
					                </div>
					                <div  class="col-lg-12 mb-20">
					                <input name="test" disabled class="form-control department" value="Annual Checkup Panel">
						
					                </div> 
					                <div  class="col-lg-6 mb-20">
					                	 <select id="inlineFormCustomSelect1" name="department" class="custom-select department" required>
						                        <option value="none">Patient Type</option>  
						                      	<option value="Walk In">Walk In</option>
						                      	<option value="Mobile Phlebotomy">Mobile Phlebotomy</option>
						                      	
						                    </select>
					                </div> 
					                <div  class="col-lg-6 mb-20">
					                	<input id="datetimepicker" type="text" name="date" class="form-control date" placeholder="Appointment Date" required> 
					                </div>
					                 
					                <!-- Contact Form Button -->
					                <div class="col-lg-12 mt-15 form-btn">  
					                <button type="submit" name="btnrequest" class="btn btn-blue blue-hover" >Request an Appointment</button>
					                </div>
					                                                              
					                <!-- Contact Form Message -->
					                 
				                          </div>                    
				                </form> 

				 			</div>	
								

				 				
				 			</div>
				 		</div>	<!-- End row -->
				 		<div class="col-lg-2"></div>
				 		</div>
 

				</div>	   <!-- End container -->		
			</section>	<!-- END CONTACTS-1 -->
@endsection