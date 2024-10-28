@extends('layouts.admin')
@section('css')
<style>
.checked {
  color: orange;
}
</style>
@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Feedback</h2>
        </div>
        <div class="row clearfix">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
                        <!-- <h2>Feedback form
                        </h2>  -->
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <p class="text-center" style="font-size:26px">We would like your feedback to improve our quality of service</p>
                            <div>
                            <form method="post" action="{{route('feedback_submit')}}">
                            @csrf
                            <center>
                                <p class="offset-3"><b>How satisfied are you with the quality of service provided</b></p>
                                <div class="col-md-12">
                                    <i style="font-size:45px" id="fb_1" class="col-md-1 smiley far fa-angry" ></i>
                                    <i style="font-size:45px" id="fb_2" class="col-md-1 smiley far fa-frown"></i>
                                    <i style="font-size:45px" id="fb_3" class="col-md-1 smiley far fa-meh"></i>
                                    <i style="font-size:45px" id="fb_4" class="col-md-1 smiley far fa-smile"></i>
                                    <i style="font-size:45px" id="fb_5" class="col-md-1 smiley far fa-laugh-beam"></i>
                                </div>
                                <input id="feedback" hidden="" name="feedback">
                                <input id="session_id" hidden="" value="{{$session}}" name="session_id">
                                <div class="form-group col-md-6">
                                    <div class="form-line">
                                        <input class="form-control"type="text" name="suggestions" required placeholder="Write any suggestions or feedback">
                                    </div>
                                </div>
                                <!-- doctor rating -->
                                <p class="offset-3"><b>Rate your Doctor</b></p>
                                <div class="col-md-12 mb-2">
                                    <i style="font-size:40px" id="rt_1" class="far fa-star"></i>
                                    <i style="font-size:40px" id="rt_2" class="far fa-star"></i>
                                    <i style="font-size:40px" id="rt_3" class="far fa-star"></i>
                                    <i style="font-size:40px" id="rt_4" class="far fa-star"></i>
                                    <i style="font-size:40px" id="rt_5" class="far fa-star"></i>
                                </div>
                                <input id="rating" hidden="" name="rating">
                            </center>

                                <div class="col-md-6 offset-3">
                                <input id="check_1" type="checkbox" class="m-1 form-check-input" checked=""><label  class="form-check-label" for="check_1">Doctor listened to my issue</label>
                                </div>
                                <div class="col-md-6 offset-3">
                                <input id="check_2" type="checkbox" class="m-1 form-check-input" checked=""><label  class="form-check-label" for="check_2">Doctor guided me about treatment and alternatives</label>
                                </div>
                                <div class="col-md-6 offset-3">
                                <input id="check_3" type="checkbox" class="m-1 form-check-input" checked=""><label  class="form-check-label" for="check_3">Quality of call was good</label>
                                </div>
                                <center><button class="btn btn-primary offset-3 btn-raised col-md-6">Submit</button></cdnter>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
$('.smiley').hover(function(){
    classes=$(this).attr('class');
    sp_class=classes.split(' ');
    $(this).attr('class',sp_class[0]+' '+sp_class[1]+' fas '+sp_class[3]);
});
$('.smiley').mouseout(function(){
    classes=$(this).attr('class');
    sp_class=classes.split(' ');
    $(this).attr('class',sp_class[0]+' '+sp_class[1]+' far '+sp_class[3]);
});
$('.smiley').click(function(){
    // classes=$(this).attr('class');
    // sp_class=classes.split(' ');
    // // foreach($('.smiley').attr('class') as aclass){
    // //     sp_aclass=aclass.split(' ');
    // //     $(this).attr('class',sp_aclass[0]+' '+sp_aclass[1]+' far '+sp_aclass[3])

    // // }
    // // $('.smiley').attr('class',sp_class[0]+' '+sp_class[1]+' far ')
    $(this).prevUntil().css('color','black');
    id=$(this).attr('id');
    id_sp=id.split('_');
    $('#feedback').val(id_sp[1]);
    // $(this).attr('class',sp_class[0]+' '+sp_class[1]+' fas '+sp_class[3]);
    $(this).css('color','orange');
    $(this).nextAll().css('color','black');

});
$('.fa-star').click(function(){
    $(this).prevUntil().css('color','orange');
    $(this).prevUntil().removeClass('far');
    $(this).prevUntil().addClass('fa');
    $(this).css('color','orange');
    $(this).removeClass('far');
    $(this).addClass('fa');
    $(this).nextAll().css('color','black');
    $(this).nextAll().removeClass('fa');
    $(this).nextAll().addClass('far');
    id=$(this).attr('id');
    id_sp=id.split('_');
    $('#rating').val(id_sp[1]);

});
</script>
@endsection