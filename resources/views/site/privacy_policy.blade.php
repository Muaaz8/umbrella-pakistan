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
                            <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">Privacy Policy</h4>

                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END BREADCRUMB -->

<style>
    .main_listing{
        font-size: 14px;
        margin-left: -2%;
    }
    .main_list_items{
        font-size: 22px;
        font-family:"Times New Roman&quot;,&quot;serif";
    }
    .list_item_content_heading_li{
        margin-left: -2%;
        font-size: 13px;
        font-weight: 700;
    }
    .list_item_content_heading{
        font-size: 22px;
        font-weight: 700;
    }
</style>


<!-- INFO-4
			============================================= -->
<section id="info-4" class="wide-100 info-section division">
    <div class="container">

        <p style="text-align:center"><span style="font-size:11pt"><span
                    style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span
                                style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">PRIVACY
                                POLICY</span></span></strong></span></span></p>

        <p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><em><span
                            style="font-size:12.0pt"><span
                                style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Effective as of [<span
                                    style="background-color:yellow">________</span>],
                                2022</span></span></em></span></span></p>

        {!! $pp !!}

        <hr />
        <!-- <p><span style="font-size:10pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span
                        style="font-size:8.0pt">&nbsp;<a class="msocomoff" href="#_msoanchor_1"
                            style="color:#0563c1; text-decoration:underline">[DS1]</a></span>Note to Umbrella: Please
                    provide all of the information that a Physician will provide to register on the website.
                </span></span></p>

        <p><span style="font-size:10pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span
                        style="font-size:8.0pt">&nbsp;<a class="msocomoff" href="#_msoanchor_2"
                            style="color:#0563c1; text-decoration:underline">[DS2]</a></span>Note to Umbrella: Please
                    provide all of the information that a Patient will provide to register on the website.
                </span></span></p>

        <p><span style="font-size:10pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span
                        style="font-size:8.0pt">&nbsp;<a class="msocomoff" href="#_msoanchor_3"
                            style="color:#0563c1; text-decoration:underline">[DS3]</a></span>Note to Umbrella: Please
                    confirm that you collect all of this information through the website. We can delete any category
                    that is inapplicable. </span></span></p>

        <p><span style="font-size:10pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span
                        style="font-size:8.0pt">&nbsp;<a class="msocomoff" href="#_msoanchor_4"
                            style="color:#0563c1; text-decoration:underline">[DS4]</a></span>Note to Umbrella: If you
                    use service providers, will you require such service providers to be under an obligation to maintain
                    confidentiality (i.e. contractually)? If no, we can delete this statement. </span></span></p>

        <p><span style="font-size:10pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span
                        style="font-size:8.0pt">&nbsp;<a class="msocomoff" href="#_msoanchor_5"
                            style="color:#0563c1; text-decoration:underline">[DS5]</a></span>Note to Umbrella: We can
                    delete any category here that is inapplicable. </span></span></p>

        <p><span style="font-size:10pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span
                        style="font-size:8.0pt">&nbsp;<a class="msocomoff" href="#_msoanchor_6"
                            style="color:#0563c1; text-decoration:underline">[DS6]</a></span>Note to Umbrella: What type
                    of service providers do you use? We provide examples in brackets here. </span></span></p>

        <p><span style="font-size:10pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span
                        style="font-size:8.0pt">&nbsp;<a class="msocomoff" href="#_msoanchor_7"
                            style="color:#0563c1; text-decoration:underline">[DS7]</a></span>Note to Umbrella: Please
                    confirm this statement is accurate. </span></span></p> -->



    </div> <!-- End container -->
</section> <!-- END INFO-4 -->




@endsection
