@extends('layouts.frontend')

@section('content')
<div id="breadcrumb" class="division">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class=" breadcrumb-holder">
<style>
p{
    font-size:18px;
}
</style>
                    <!-- Breadcrumb Nav -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <!-- <li class="breadcrumb-item">Substance Abuse</li> -->
                            <li class="breadcrumb-item "><a href="{{url('/mental_conditions')}}">Heath Issues</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$mentalCondition->name}}</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h4 class="h4-sm steelblue-color">{{$mentalCondition->name}}</h4>

                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End container -->
</div> <!-- END BREADCRUMB -->
<section id="info-4" class="wide-100 info-section division pt-4">
    <div class="container">
        <h1>{{$mentalCondition->name}}</h1>
        <input id="content_text" value="{{$mentalCondition->content}}" hidden>
        <p id="content" class="pt-2"></p>
    </div>
</section>


@endsection
@section('script')
<script>
$(document).ready(function(){
    $('#content').append($('#content_text').val());
})
</script>
@endsection