@extends('layouts.frontend')

@section('content')
<section id="video-1" class="wide-10 video-section division ">
	<div class="container">
		<div class="row d-flex ">
			<div class="col-lg-6">
				<div class="video-preview mb-0 text-center wow fadeInUp" data-wow-delay="0.6s">	
					<span><img id="mimg" style="width:50%; margin-top:0px" src="asset_frontend/images/pera.jpg"></span>
				</div>
			</div>
			<div class="col-lg-6 mt-2">
				<div class="video-preview mb-0 text-center wow fadeInUp clearfix" data-wow-delay="0.6s" >
				<form name="myform" id="myform" method="post"  action="{{route('checkout')}}">
					<div class="row" >
						<h3 id="mname" class="h2-sm blue-color" style="font-size: 44px"><span id="mname">Paracetamol</span></h3>			 					    
					</div>
					<div class="row">
						<h3  class="h2-sm steelblue-color" style="padding-top:10px; "><span class="blue-color">Price:</span><span id="mprice"> $7.29</span></h3>
					</div>
					<!-- Text -->
					<div class="row m-1" >
						<h6 style="color: green">Status: Available</h6>
					</div>
						<!--       <hr class="col-md-6 ">
							<div class="row " style="padding: 0">
							<div class="col-md-4" style="font-size: 20px; ">Available Forms:</div>
							<div class="col-md-3">
								<input required="true" type="radio" class="m-1"  name="type_radio" value="Bottle"><span><img src="asset_frontend/images/bottle.png" height="30"></span>
								<input required="true" type="radio" class="m-1"  name="type_radio" value="Tablet"><span><i class="fa fa-tablets"></i></span>
								</div>
						</div> -->
					<hr class="col-md-6 ">
					<div class="row" >
						<div class="col-md-2 m-1 p-2" style="font-size: 20px; ">Quantity:</div>

							<div  class="col-md-4 input-doctor" >
						
									<select name="quantity" id="quantity" class="custom-select" required style="width:70px;height:50px">
										
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
									</select>
						</div>
					</div>
							<hr class="col-md-6 ">

						<div class="row">
						<div class="col-md-9" style="padding-top:10px;">
							<a class="btn btn-sm col-md-12 btn-blue blue-hover p-3  " id="cart" href="{{route('checkout')}}"  title="">Add To Cart</a>
									
									
						</div>
							</div>
						</form>
</div>
						</div>
						</div>
					</div>
						
						
						
						

					</div>
						</div>
					</div>	<!-- END VIDEO LINK -->
				</div>	    <!-- End row -->
			</div>	    <!-- End container -->
		</section>
		<hr style="border-top: 1px solid gray;margin-left:190px;margin-right:190px">
		
		
	
		<section id="services-3" class="wide-15 services-section division" style="padding-top:20px">
			<div class="container">	
			<h3 class="h5-lg blue-color">You May Also Like</h3>	
	<div class="row">
					<div class="col-md-12"  style="padding-top:10px">					
						<div class="owl-carousel owl-theme services-holder" >
					<div class="sbox-3 icon-sm" style="padding:20px">
					
							<!-- Icon -->
							<center><span><img style="width:50%; height:90px;" src="asset_frontend/images/rigix.jpg"></span></center>

							<!-- Title -->
							<h6 class="h5-lg blue-color" style="height:60px">Rigix</h6>	
	
							<!-- Plan Price  -->
							<div class="pricing-plan">
							<h6 class="h5-lg blue-color">$8.69</h6>
								
							</div>
																						
							<!-- Pricing Plan Features  -->
							

							<!-- Pricing Table Button  -->
							<div class="col-md-12">
							<div class="row">
							
									
									<div class="col-md-12">
								<a href=""   name="" class="btn btn-blue" type="Submit" >Order Now</a>
									</div>
									</div>
									</div>
					
					</div>
					<div class="sbox-3 icon-sm" style="padding:20px">
					
							<!-- Icon -->
							<center><span><img style="width:50%; height:90px;" src="asset_frontend/images/disprin.jpg"></span></center>

							<!-- Title -->
							<h6 class="h5-lg blue-color" style="height:60px">Diprin</h6>	
	
							<!-- Plan Price  -->
							<div class="pricing-plan">
							<h6 class="h5-lg blue-color">$8.69</h6>
								
							</div>
																						
							<!-- Pricing Plan Features  -->
							

							<!-- Pricing Table Button  -->
							<div class="col-md-12">
							<div class="row">
							
									
									<div class="col-md-12">
								<a href=""   name="" class="btn btn-blue" type="Submit" >Order Now</a>
									</div>
									</div>
									</div>
					
					</div>
					<div class="sbox-3 icon-sm" style="padding:20px">
					
							<!-- Icon -->
							<center><span><img style="width:50%; height:90px;" src="asset_frontend/images/polyfex.jpg"></span></center>

							<!-- Title -->
							<h6 class="h5-lg blue-color" style="height:60px">Polyfax</h6>	
	
							<!-- Plan Price  -->
							<div class="pricing-plan">
							<h6 class="h5-lg blue-color">$8.69</h6>
								
							</div>
																						
							<!-- Pricing Plan Features  -->
							

							<!-- Pricing Table Button  -->
							<div class="col-md-12">
							<div class="row">
							
									
									<div class="col-md-12">
								<a href=""   name="" class="btn btn-blue" type="Submit" >Order Now</a>
									</div>
									</div>
									</div>
					
					</div>
					<div class="sbox-3 icon-sm" style="padding:20px">
					
							<!-- Icon -->
							<center><span><img style="width:50%; height:90px;" src="asset_frontend/images/velosef.jpg"></span></center>

							<!-- Title -->
							<h6 class="h5-lg blue-color" style="height:60px">Velosef</h6>	
	
							<!-- Plan Price  -->
							<div class="pricing-plan">
							<h6 class="h5-lg blue-color">$8.69</h6>
								
							</div>
																						
							<!-- Pricing Plan Features  -->
							

							<!-- Pricing Table Button  -->
							<div class="col-md-12">
							<div class="row">
							
									
									<div class="col-md-12">
								<a href=""   name="" class="btn btn-blue" type="Submit" >Order Now</a>
									</div>
									</div>
									</div>
					
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
 <section class="col-md-12">
 	<div class="container">
 	<h3 class="text-center m-2 steelblue-color">Paracetamol</h3>
<div class="m-2 p-3" >

<h5 style="font-size:25px">DETAILS</h5>
<p style="font-size: 20px">

This medicine contains paracetamol. It belongs to a group of medicines called analgesics (painkillers) and is used to treat pain (including headache, toothache, back and period pain) and cold or flu symptoms.
</p>

<h5 style="font-size:25px">WARNINGS</h5>
<p style="font-size: 20px">

Do not take paracetamol and tell your doctor if:
</p>
<p style="font-size: 20px">
You are allergic to the active substance or any of the other ingredients in your medicine (listed in Section 6).
Signs of an allergic reaction include a rash and breathing problems. There can also be swelling of the legs, arms, face, throat or tongue
Children
</p>
<p style="font-size: 20px">
Paracetamol 500mg Tablets must not be given to children under 10 years of age.
</p>
<p style="font-size: 20px">
Do not take paracetamol if any of the above apply to you. If you are not sure, talk to your doctor or pharmacist before taking paracetamol.
</p>
<p style="font-size: 20px">
Take special care and check with your doctor before taking paracetamol if:
</p>
<p style="font-size: 20px">
You have severe kidney or liver problems
You have a liver problem caused by alcohol
If you are not sure if any of the above apply to you, talk to your doctor or pharmacist before taking this medicine.
</p>
<p style="font-size: 20px">
Other medicines and paracetamol
</p>
<p style="font-size: 20px">
Please tell your doctor or pharmacist if you are taking or have recently taken any other medicines. This includes medicines obtained without a prescription, including herbal medicines. This is because paracetamol can affect the way some other medicines work. Also, some other medicines can affect the way paracetamol works.
</p>
<p style="font-size: 20px">
While taking paracetamol you should not take any other medicines which contain paracetamol.
</p>
<p style="font-size: 20px">
This includes some painkillers, cough and cold remedies. It also includes a wide range of other medicines available from your doctor and more widely in shops.
</p>
<p style="font-size: 20px">
Tell your doctor if you are taking any of the following medicines:
</p>
<p style="font-size: 20px">
Medicines used to thin the blood such as warfarin
Metoclopramide or domperidone - used to stop you feeling sick (nausea) or being sick (vomiting)
Colestyramine - for lowering blood cholesterol levels
If you are not sure if any of the above apply to you, talk to your doctor or pharmacist before taking paracetamol.
</p>
<p style="font-size: 20px">
Taking paracetamol with alcohol
</p>
<p style="font-size: 20px">
You should not drink whilst taking these tablets. Taking alcohol with paracetamol can increase your chances of getting side effects.
</p>
<p style="font-size: 20px">
Pregnancy and breast-feeding
</p>
<p style="font-size: 20px">
Talk to your doctor before taking these tablets if:
</p>
<p style="font-size: 20px">
You are pregnant, think you may be pregnant or plan to get pregnant.
You are breast-feeding or planning to breast-feed
Paracetamol can be used during pregnancy. You should use the lowest possible dose that reduces your pain and/or your fever and use it for the shortest time possible. Contact your doctor or midwife if the pain and/or fever are not reduced or if you need to take the medicine more often.
</p>
<h5 style="font-size:25px">DIRECTIONS</h5>
<p style="font-size: 20px">

Always take paracetamol exactly as instructed on this leaflet. Check with your doctor or pharmacist if you are not sure.
</p>
<p style="font-size: 20px">
Do not take more than the recommended dose
If you need to use this medicine for more than three days at a time, see your doctor
</p>
<p style="font-size: 20px">
Adults and children over 16
</p>
<p style="font-size: 20px">
The usual dose of paracetamol is 2 tablets
Swallow the tablets whole with a drink of water
Wait at least 4 hours before taking another dose
Do not take more than 4 doses in any 24-hour period
Use in children aged 10 to 15
</p>
<p style="font-size: 20px">
Take one tablet every four to six hours when necessary to a maximum of four doses in 24 hours.
</p>
<p style="font-size: 20px">
Use in children under 10
</p>
<p style="font-size: 20px">
Paracetamol 500mg Tablets should not be given to children under 10 years of age.
</p>
<p style="font-size: 20px">
If you take more paracetamol than you should:
</p>
<p style="font-size: 20px">
Talk to a doctor at once if you take too much of this medicine even if you feel well. This is because too much paracetamol can cause delayed, serious liver damage.
</p>
<p style="font-size: 20px">
Remember to take any remaining tablets and the pack with you. This is so the doctor knows what you have taken.
If you forget to take paracetamol
</p>
<p style="font-size: 20px">
If you forget to take a dose at the right time, take it as soon as you remember. However, do not take a double dose to make up for a forgotten dose. Remember to leave at least 4 hours between doses.
</p>
<h5 style="font-size:25px">SIDE EFFECTS</h5>
<p style="font-size: 20px">

Like all medicines, this medicine can cause side effects, although not everybody gets them.
</p>
<p style="font-size: 20px">
The following side effects may happen with this medicine:
</p>
<p style="font-size: 20px">
Stop taking paracetamol and see a doctor or go to a hospital straight away if:
</p>
<p style="font-size: 20px">
You get swelling of the hands, feet, ankles, face, lips or throat which may cause difficulty in swallowing or breathing. You could also notice an itchy, lumpy rash (hives) or nettle rash (urticaria).
This may mean you are having an allergic reaction to paracetamol.
You get serious skin reactions. Very rare cases have been reported.
Tell your doctor or pharmacist if any of the following side effects gets serious or lasts longer than a few days:
</p>
<p style="font-size: 20px">
You get infections or bruise more easily than usual. This could be because of a blood problem (such as agranulocytosis, neutropenia or thrombocytopenia). This side effect has only happened in a few people taking paracetamol.
Reporting of side effects
</p>
<p style="font-size: 20px">
If you get any side effects, talk to your doctor or pharmacist. This includes any possible side effects not listed in this leaflet. You can also report side effects directly via the Yellow Card Scheme at: www.mhra.gov.uk/yellowcard or search for MHRA Yellow Card in the Google Play or Apple App Store.
</p>
<p style="font-size: 20px">
By reporting side effects, you can help provide more information on the safety of this medicine.
</p>
<h5 style="font-size:25px">PRECAUTIONS</h5>
<p style="font-size: 20px">

Keep this medicine out of the sight and reach of children.
Do not use this medicine after the expiry date shown on the pack. The expiry date refers to the last day of that month.
Store your medicine in the original packaging in order to protect from moisture.
Do not throw away any medicines via wastewater or household waste. Ask your pharmacist how to throw away medicines you no longer use. These measures will help protect the environment.
</p>
</div>
</div>
 </section>	
		
@endsection
<!-- <input type="text" name="span" id="span" class="form-control"> -->
@section('script')
<script type="text/javascript">
	$(document).ready(function(){
	 var nm = "medname=";
	 var pr = "medprice=";
	 var img = "medimg=";
  var decodedCookie = decodeURIComponent(document.cookie);
	//$('#span').val(decodedCookie);	

  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length-1; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
    	c = c.substring(1);

    }
     
    if (c.indexOf(nm) == 0) {

    $('#mname').text(c.substring(nm.length, c.length));

    }
    if (c.indexOf(pr) == 0) {
    $('#mprice').text(c.substring(pr.length, c.length));

    }if (c.indexOf(img) == 0) {
    $('#mimg').attr('src',c.substring(img.length, c.length));

     }
            }  

 
  $('#cart').click(function(){
			var name=$('#mname').text();
			var price=$('#mprice').text();
			var quantity = $("#quantity").val();

	 var m1 = "mname=";
	 var m2 = "mname1=";
	 var m3 = "mname2=";
	 var m1exist=false;
	 var m2exist=false;
	 // var m3exist=false;
  var decodedCookie = decodeURIComponent(document.cookie);

  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length-1; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
    	c = c.substring(1);

    }
     
    if (c.indexOf(m1) == 0) {
    	m1exist=true;
    }
    if(c.indexOf(m2) == 0) {
    	m2exist=true; 
    }
    
            }  
if(!m1exist){//0meds
    	document.cookie="mname="+name+";path=/";
		document.cookie="mprice="+price+";path=/";
		document.cookie="mqua="+quantity+";path=/";
		}
else if((m1exist)&&(!m2exist)){//1meds
		document.cookie="mname1="+name+";path=/";
		document.cookie="mprice1="+price+";path=/";
		document.cookie="mqua1="+quantity+";path=/";
}
else if(m2exist){//2meds
		document.cookie="mname2="+name+";path=/";
		document.cookie="mprice2="+price+";path=/";
		document.cookie="mqua2="+quantity+";path=/";
	}
		});
	});
	
</script>
@endsection