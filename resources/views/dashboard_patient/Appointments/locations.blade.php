@extends('layouts.dashboard_patient')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Appointment</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
    function state_check(id)
    {
        var doc = $('#docs_'+id).val();
        if(doc==1){
            window.location.href="/specializations/"+id;
        } else {
            alert('Sorry...!!! Currently there is no doctor availabale in this state');
        }
    }
</script>
@endsection

@section('content')
<div class="dashboard-content" id="backto">
     <div class="container">
        <!-- <div class="row"> -->
            <div class="row my-2 m-auto">

              <div class="pb-2">
                <h3>Select State</h3>
                <h5 class="registered__state">Registered State: {{$Reg_state->name}}</h5>
                <!-- <p>Select </p> -->
              </div>
              <section class="mb-2">
                <div class="alphabets__main">
                    @foreach($locations as $key=>$loc)
                    <p ><a href="#{{$key}}"><span class="alphabet_Style">{{$key}}</span></a></p>
                    @endforeach
                    <!-- <p ><a href="#B"><span class="alphabet_Style">B</span></a></p>
                    <p ><a href="#C"><span class="alphabet_Style">C</span></a></p>
                    <p ><a href="#D"><span class="alphabet_Style">D</span></a></p>
                    <p ><a href="#E"><span class="alphabet_Style">E</span></a></p>
                    <p ><a href="#F"><span class="alphabet_Style">F</span></a></p>
                    <p ><a href="#G"><span class="alphabet_Style">G</span></a></p>
                    <p ><a href="#H"><span class="alphabet_Style">H</span></a></p>
                    <p ><a href="#I"><span class="alphabet_Style">I</span></a></p>
                    <p ><a href="#J"><span class="alphabet_Style">J</span></a></p>
                    <p ><a href="#K"><span class="alphabet_Style">K</span></a></p>
                    <p ><a href="#L"><span class="alphabet_Style">L</span></a></p>
                    <p ><a href="#M"><span class="alphabet_Style">M</span></a></p>
                    <p ><a href="#N"><span class="alphabet_Style">N</span></a></p>
                    <p ><a href="#O"><span class="alphabet_Style">O</span></a></p>
                    <p ><a href="#P"><span class="alphabet_Style">P</span></a></p>
                    <p ><a href="#Q"><span class="alphabet_Style">Q</span></a></p>
                    <p ><a href="#R"><span class="alphabet_Style">R</span></a></p>
                    <p ><a href="#S"><span class="alphabet_Style">S</span></a></p>
                    <p ><a href="#T"><span class="alphabet_Style">T</span></a></p>
                    <p ><a href="#U"><span class="alphabet_Style">U</span></a></p>
                    <p ><a href="#V"><span class="alphabet_Style">V</span></a></p>
                    <p ><a href="#W"><span class="alphabet_Style">W</span></a></p>
                    <p ><a href="#X"><span class="alphabet_Style">X</span></a></p>
                    <p ><a href="#Y"><span class="alphabet_Style">Y</span></a></p>
                    <p ><a href="#Z"><span class="alphabet_Style">Z</span></a></p> -->
                </div>

              </section>

                    <div>
                        @foreach($locations as $key=>$loc)
                        <div id="{{$key}}">
                            <div class="left_alphabets d-flex justify-content-between">
                                <div class="">{{$key}}</div>
                                <div><a class="bacKto">Back to top</a></div>
                            </div>
                            <div class="mt-2">
                                <div class="row">
                                    @foreach($loc as $lo)
                                    <div class="col-md-3 mb-2" onclick="state_check({{$lo->id}})">
                                        <p class="state_Des">{{$lo->name}}</p>
                                    </div>
                                    <input type="hidden" name="docs" id="docs_{{$lo->id}}" value="{{$lo->docs}}">
                                    @endforeach
                                    <!-- <div class="col-md-3 mb-2">
                                        <p class="state_Des">Alamaba</p>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <p class="state_Des">Alamaba</p>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <p class="state_Des">Alamaba</p>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <p class="state_Des">Alamaba</p>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <!-- <div id="B">
                          <div class="left_alphabets d-flex justify-content-between">
                            <div class="">B</div>
                            <div><a class="bacKto" href="#backto">Back to top</a></div>
                        </div>
                            <div class="mt-2">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <p class="state_Des">Blamaba</p>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <p class="state_Des">Blamaba</p>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <p class="state_Des">Blamaba</p>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <p class="state_Des">Blamaba</p>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <p class="state_Des">Blamaba</p>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>



                </div>

        <!-- </div> -->
        </div>
     </div>
@endsection
