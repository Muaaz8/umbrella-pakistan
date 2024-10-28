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
								    	<li class="breadcrumb-item"><a href="index.html">Home</a></li>
								    	<li class="breadcrumb-item"><a href="tests.html">Tests</a></li>
								  	</ol>
								</nav>

								<!-- Title -->
								<h4 class="h4-sm steelblue-color">Test Description</h4>

							</div>
						</div>
					</div>  <!-- End row -->	
				</div>	<!-- End container -->		
			</div>	<!-- END BREADCRUMB -->	




			<!-- SERVICE DETAILS
			============================================= -->
			<div id="service-page" class="wide-60 service-page-section division">
				<div class="container">


					<!-- TEXT -->
					<div class="row">
				 		<div class="col-xl-10 offset-xl-1">
				 			<div class="s1-page content-block text-center mb-40">

				 				<!-- Title -->
								<h3 class="h3-xl blue-color">Annual Checkup Panel</h3>

				 			</div>
				 		</div>
				 	</div>  <!-- End row -->	


				 	


					<!-- TEXT -->
					<div class="row">
				 		<div class="col-xl-10 offset-xl-1">
				 			<div class="s1-page content-block text-center mb-40">

				 				<!-- Text -->
								<h4 class="">Test Description</h4>
								<p class="p-lg">The Drug Screening Panel will determine the presence or absence of 5 types of drugs or their metabolites in your urine.
								</p>


								<!-- Button -->
								<a href="{{ route('lab_appointment_form')}}" class="btn btn-md btn-blue blue-hover">Book an Appointment</a>

								<!-- Text -->
								<h4 class="h4-lg"> <span class="blue-color">$145.00</span></h4>

				 			</div>
				 		</div>

				 	</div>  <!-- End row -->	
					
			<section id="pricing-2" class="pb-60 pricing-section division">
				<div class="container">	
					<div class="row pricing-row">


						<!-- PRICING TABLE #1 -->
						<div class="col-lg-6">

							<!-- Plan Title  -->
							<h5 class="h5-md steelblue-color">Test Detail</h5>

							<div class="pricing-table mb-40">								
								<p>This Instant Urine Drug Test 5‐Panel checks for the presence of 5 drug metabolites including:<br>
								1.Amphetamine/Methamphetamines<br>
								2.Marijuana<br>
								3.Cocaine<br>
								4.Opiates
								</p>
							</div>
						</div>	<!-- END PRICING TABLE #1 -->


						<!-- PRICING TABLE #2 -->
						<div class="col-lg-6">

							<!-- Plan Title  -->
							<h5 class="h5-md steelblue-color">FAQs</h5>

							<div class="pricing-table mb-40">	
								<p>
								<b>IS A DOCTOR'S ORDER REQUIRED?</b><br>

									No. You do not need to provide a doctor's order to get lab testing done.<br>

								<b>WHEN WILL I GET MY TEST RESULTS?</b><br>

									The test results from the instant drug screen are available within minutes after the specimen collection.<br>
								</p>
							</div>
						</div>	<!-- END PRICING TABLE #2 -->

	
					</div>	<!-- End row -->


					


				</div>    <!-- End container -->		
			</section>


				</div>	<!-- End container -->	
			</div>	<!-- END SERVICE DETAILS -->


@endsection