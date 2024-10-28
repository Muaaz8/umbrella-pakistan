@extends('layouts.frontend')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
<style>
    .top-cart {
         margin-bottom: 100px;
    }
</style>
@endsection
@section('content')
<div id="breadcrumb" class="division">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class=" breadcrumb-holder">

                    <!-- Breadcrumb Nav -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item active">FAQs</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">FAQs</h4>

                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END BREADCRUMB -->
<section id="info-4" class="wide-100 info-section division pt-4">
    <div class="container">


        <!-- TOP ROW -->
        <div class="top-row mb-5">
            <div class="row d-flex align-items-center">
            <div class="accordion" id="accordionExample">
                @foreach($faqs as $key=>$faq)
                <input id="content_text{{$faq->id}}" value="{{$faq->answer}}" hidden>

                <!-- <div class="sbox-7 icon-xs wow ml-5 col-8" style="padding:10px 2px;margin:5px 10px" data-wow-delay="0.4s">
                    <a href="{{route('faq_one', [$faq->id])}}">
                        <div class="sbox-7-txt col-12">
                            <h5 class="h5-sm steelblue-color" style="display:block"><i class="fas fa-caret-right"></i>  {{$faq->question}}</h5>
                        </div>
                    </a>
                </div> -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        @if($key==0)
                            <button class="accordion-button" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#collapse{{$faq->id}}" 
                                aria-expanded="true" aria-controls="collapse{{$faq->id}}">
                        @else
                            <button class="accordion-button collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#collapse{{$faq->id}}" 
                                aria-expanded="false" aria-controls="collapse{{$faq->id}}">
                        @endif
                            {{$faq->question}}
                            </button>
                        </h2>
                        @if($key==0)
                        <div id="collapse{{$faq->id}}" class="accordion-collapse collapse show"
                            aria-labelledby="collapse{{$faq->id}}" data-bs-parent="#accordionExample">
                        @else
                        <div id="collapse{{$faq->id}}" class="accordion-collapse collapse"
                            aria-labelledby="collapse{{$faq->id}}" data-bs-parent="#accordionExample">
                        @endif
                            <div class="accordion-body" id="answer{{$faq->id}}">
                            </div>
                        </div>
                    </div> 
                @endforeach
                </div> 

            </div> <!-- End row -->
        </div> <!-- END TOP ROW -->
    </div>
</section>


@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

<script>

$(document).ready(function(){
    <?php foreach($faqs as $faq){ ?>
    $('#answer<?php echo $faq->id; ?>').append($('#content_text<?php echo $faq->id ?>').val());
    <?php } ?>
});
</script>

@endsection