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
                            <li class="breadcrumb-item active" aria-current="page">Careers</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">Careers</h4>

                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END BREADCRUMB -->


<div class="row" id="content" style="min-height:600px;">
    <!-- Sidebar -->
    
    <section id="services-7" class="bg-lightgrey wide-70 servicess-section division col-md-12" style="padding-top: 2%">
        <div class="container" style="height:200px;">
            <div class="text-center mt-5">
                <h1 class="h4-sm mt-5" style="color:#08295a !important;background: white;padding: 150px 0px 150px 0px; font-size:65px;">COMING SOON...</h1>
            </div>
        </div>
    </section>

</div>
@endsection
