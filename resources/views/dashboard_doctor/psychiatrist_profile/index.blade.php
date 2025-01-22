@extends('layouts.new_web_layout')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Event Details</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
<script>
    $(document).ready(function(){

     $(document).on('click', '.pagination a', function(event){
      event.preventDefault();
      var page = $(this).attr('href').split('page=')[1];
      fetch_data(page);
     });

     function fetch_data(page)
     {
    //   alert(page);
      $.ajax({
       url:"/pagination/fetch_data?page="+page,
       success:function(data)
       {
        $('#table_data').html(data);
       }
      });
     }

    });
</script>
@endsection

@section('content')
<section class="container pt-2 pb-4">
    <div class="row">
        <div class="col-lg-9">
            <div>
                <h1 class="mt-4 mb-2" style="font-size: 35px;
                color: #08295a">Dr. {{$doc->name}} {{$doc->last_name}}</h1>
                <!-- <p>PH.D</p> -->
                <div style="line-height: 2;
                font-size: 20px">
                    <p><span class="fw-bold"><i style="color:red" class="fa-solid fa-calendar-days"></i>Event Date: </span><span style="font-weight: 800">{{$info->date->date}}</span> </p>
                    <p><span class="fw-bold"><i style="color:#08295a" class="fa-solid fa-clock"></i>Event Time: </span><span style="font-weight: 500">{{$info->date->est}} (EST), {{$info->date->cst}} (CST), {{$info->date->mst}} (MST), {{$info->date->pst}} (PST)</span></p>
                    <p><span class="fw-bold">Specialization:</span> Psychologist</p>
                    <p><span class="fw-bold">License State:</span>
                        @foreach ($doc->lic_state as $state)
                            {{ $state->name }},
                        @endforeach
                    </p>
                    {{-- <p><span class="fw-bold">Primary Credential:</span> Psychologist - PS019197 </p> --}}
                    <p><span class="fw-bold">Price:</span> $ 25.00</p>
                @if ($info->help != '')
                    <h5 class="mb-1" style="font-size: 22px;
                    color: #08295a;
                    font-weight: 800">My Approach to Helping</h5>
                    <p style="font-size: 17px;line-height: normal;text-align: justify;">{!! $info->help !!}</p>
                @endif

                </div>
            </div>
            <div>
                @if ($info->skills != '')
                <h5 class="mb-1" style="font-size: 22px;
                color: #08295a;
                font-weight: 800">SPECIFIC ISSUE(S) I'M SKILLED AT HELPING WITH</h5>
                    <p style="font-size: 17px;line-height: normal;text-align: justify;">{!! $info->skills !!}</p>
                @endif
            </div>

        </div>
        <div class="col-lg-3">
            <div>
                <div class="position-relative d-flex align-items-center justify-content-center">
                <img src="{{ asset('assets/images/brush_color2.png')}}" alt="" style="    height: 300px;
                width: 100%;
                ">
            <div class="doc__img position-absolute">
                <img src="{{$doc->user_image}}" alt="">

            </div>

                </div>

                <div class="text-center">
                    @if(!Auth()->user())
                    <a class="btn login_getroll_ " href="/login" role="button">Login/Register</a>
                    @elseif(Auth()->user()->user_type=='patient')
                        <a href='/therapy/event/payment/{{$info->date->session_id}}'
                            class="btn login_getroll_ ">Get Enrolled</a>
                    @endif
                </div>

            </div>
            <div class="mt-3">
                @if ($info->concerns != [])
                <h4 style="color: #08295a;">Services I Provide</h4>
                <ul>
                    @foreach ($info->concerns as $item)
                    <li style="list-style:inside">{{ $item }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            <div >
                @if ($info->services != [])
                <h4 style="color: #08295a" class="pt-3 pb-1">Client Concerns I Treat</h4>
                <ul>
                    @foreach ($info->services as $item)
                        <li style="list-style:inside">{{ $item }}</li>
                    @endforeach
                </ul>
                @endif
            </div>

        </div>
    </div>

    </section>
@endsection
