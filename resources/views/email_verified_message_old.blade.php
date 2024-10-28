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
                            <li class="breadcrumb-item active" aria-current="page">email-verification</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">Email Verification</h4>

                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END BREADCRUMB -->




<!-- CONTACTS-2
			============================================= -->
<section id="contacts-2" class="wide-100 contacts-section division m-5 p-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h4 class="h4-md steelblue-color">Email Verification</h4>
                @if (Auth::user()->user_type == 'patient')
                    <p class="contact-notice" style="margin:0px;">Your email is verified successfully. Go to <a href="{{ route('New_Patient_Dashboard') }}">dashboard</a>. </p>
                @elseif (Auth::user()->user_type == 'doctor')
                    <p class="contact-notice" style="margin:0px;">Your email is verified successfully. Go to <a href="{{ route('doctor_dashboard') }}">dashboard</a>. </p>
                @endif
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</section> <!-- END CONTACTS-2 -->

@endsection
