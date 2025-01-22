@extends('layouts.new_web_register_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    <title>CHCC - Add Sign</title>
@endsection

@section('top_import_file')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<style>
    {{--  .form-control {
        border-radius: 0;
        box-shadow: none;
        border-color: #d2d6de
    }  --}}

    .select2-hidden-accessible {
        border: 0 !important;
        clip: rect(0 0 0 0) !important;
        height: 1px !important;
        margin: -1px !important;
        overflow: hidden !important;
        padding: 0 !important;
        position: absolute !important;
        width: 1px !important
    }

    {{--  .form-control {
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s
    }  --}}

    .select2-container--default .select2-selection--single,
    .select2-selection .select2-selection--single {
        border: 1px solid #d2d6de;
        border-radius: 0;
        padding: 6px 12px;
        height: 34px
    }

    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #aaa;
        border-radius: 4px
    }

    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 28px;
        user-select: none;
        -webkit-user-select: none
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-right: 10px
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 0;
        padding-right: 0;
        height: auto;
        margin-top: -3px
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 28px
    }

    .select2-container--default .select2-selection--single,
    .select2-selection .select2-selection--single {
        border: 1px solid #d2d6de;
        border-radius: 0 !important;
        padding: 6px 12px;
        height: 40px !important
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 26px;
        position: absolute;
        top: 6px !important;
        right: 1px;
        width: 20px
    }
</style>

@endsection


@section('bottom_import_file')
<script type="text/javascript">
    <?php header("Access-Control-Allow-Origin: *"); ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script>
    function isCanvasEmpty(cnv) {
        const blank = document.createElement('canvas');

        blank.width = cnv.width;
        blank.height = cnv.height;

        return cnv.toDataURL() === blank.toDataURL();
    }
    $(function () {
        $('#sig-canvas').sketch();
        $("#btnSave").bind("click", function () {
            var cnv = document.getElementById('sig-canvas');
            var base64 = $('#sig-canvas')[0].toDataURL();
            $('#reload').html(
                '<img alt="" src="'+base64+'" style = "border:1px solid #ccc" />'
            );
            $("#signature").val(base64);
            if(!isCanvasEmpty(cnv))
            {
                $('#term').val('1');
                $('.forthNext').prop("disabled", false);
                $('.forthNext').css("background", '#08295a');
                $('#error').html('');
            }
            else{
                alert('add sign');
            }
        });
        $('#clearBtn').click(function(){
            $('#error').html('');
            $("#signature").val('');
            $('#reload').html(
                '<canvas id="sig-canvas" width="620" height="160"></canvas>'
            );
            $('#sig-canvas').sketch();
            $('.forthNext').prop("disabled", true);
            $('.forthNext').css("background", '#a9b9d0');
        });
    });
</script>
<!-- Option 1: Bootstrap Bundle with Popper -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
  crossorigin="anonymous"
></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="{{ asset('assets/js/chatbot.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdn.rawgit.com/mobomo/sketch.js/master/lib/sketch.min.js" type="text/javascript"></script>
@endsection

@section('content')


<!-- ******* DOCTOR-FORM STATRS ******** -->
<section class="login-patient-sec">
  <div class="container-fluid my-5">
      <div class="row justify-content-center">
          <div class="col-11 col-sm-9 col-md-6 col-lg-6 col-xl-5 text-center p-0 mt-3 mb-2 patient-form-wrapper">
              <div class="card mt-3 mb-3">
                  <form id="msform" method="POST" enctype="multipart/form-data" action="/add_sign">
                    @csrf
                      <!-- fieldsets -->
                    <fieldset>
                        <div class="form-card">
                            <div class="row mt-4">
                              <div class="col-md-12">
                                <h6>Add E-Signature</h6>
                                <input type="hidden" name="signature" id="signature">
                                    <div id="reload">
                                        <canvas id="sig-canvas" width="620" height="160"></canvas>
                                    </div>
                               </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="error">
                                </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <button class="btn btn-default" type="button" onclick="javascript:void(0)" id="clearBtn">Retake Signature</button>
                                <button class="btn btn-default" type="button" onclick="javascript:void(0)" id="btnSave">Save Signature</button>
                                <button type="submit" class="next action-button btn btn-info" style="background:blue;">submit</button>
                              </div>
                            </div>
                        </div>
                    </fieldset>
                  </form>
              </div>
          </div>
          <!-- <div class="col-md-6">
            <div>
              <img src="{{ asset('assets/images/logo.png') }}" alt="" width="100%">
            </div>
          </div> -->
      </div>
  </div>
</section>
<!-- ******* DOCTOR-FORM ENDS ******** -->
<!-- ============= Term Policy Modal starts ==================== -->
<!-- ============= Term Policy Modal Ends ==================== -->

<script>
	function addHyphen (element) {
    	let ele = document.getElementById(element.id);
        ele = ele.value.split('-').join('');    // Remove dash (-) if mistakenly entered.
        if(ele.length <= 5)
        {
            let finalVal = ele.match(/.{1,2}/g).join('-');
            document.getElementById(element.id).value = finalVal;
        }
    }
</script>

@endsection
